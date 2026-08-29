# 포인트 출금 지급정보 정정 DDL 실행 안내

운영 DB에서 아래 조회만 먼저 실행하고 결과를 보관한다. 비밀번호는 명령행이나 작업 로그에 남기지 않는다.

```sql
SELECT VERSION() AS mariadb_version;
SHOW TABLE STATUS WHERE Name IN ('nfor_point_bank', 'nfor_log');
SHOW CREATE TABLE nfor_point_bank;
SHOW CREATE TABLE nfor_log;
SELECT TABLE_ROWS, DATA_LENGTH, INDEX_LENGTH
FROM information_schema.TABLES
WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='nfor_point_bank';
SHOW FULL PROCESSLIST;
```

`nfor_point_bank`와 `nfor_log`가 모두 InnoDB인지, 현재 MariaDB가 `ADD COLUMN ... ALGORITHM=INPLACE, LOCK=NONE`을 지원하는지 확인한다. COPY 알고리즘이나 장시간 metadata lock 가능성이 있으면 실행하지 않는다. 엔진 변환은 이 작업에 포함하지 않는다.

DDL은 코드보다 먼저 실행한다. 세션 lock 대기를 짧게 제한하고 SQL 파일을 적용한다. `LOCK=NONE`을 지원하지 않으면 ALTER는 실패해야 하며 더 느슨한 옵션으로 재시도하지 않는다.

```sql
SET SESSION lock_wait_timeout=5;
SOURCE docs/sql/2026-08-29-point-bank-account-correction.sql;
```

적용 뒤 아래를 다시 확인한다.

```sql
SHOW COLUMNS FROM nfor_point_bank LIKE 'pb_row_revision';
SHOW TABLE STATUS WHERE Name IN (
  'nfor_point_bank',
  'nfor_log',
  'nfor_point_bank_account_audit',
  'nfor_point_bank_account_mode'
);
SHOW INDEX FROM nfor_point_bank_account_audit;
SELECT singleton_id, mode, state_version FROM nfor_point_bank_account_mode;
```

신규 두 테이블이 InnoDB이고 mode가 정확히 `OFF`일 때만 PHP 파일 배포로 진행한다. mode 전환은 `OFF -> ON -> DRAIN -> OFF`만 허용한다.

## 지급정보 writer 잠금·장애 원칙

동시 처리 교착을 피하기 위해 모든 지급정보 writer는 한 트랜잭션 안에서 `mode singleton -> audit -> nfor_point_bank` 순서만 사용한다. 여러 출금 건을 다루는 `setstep`은 ID를 숫자 오름차순으로 중복 제거한 뒤, 해당 unresolved audit 범위를 `FOR UPDATE`로 확인하고 지급 행도 같은 ID 순서로 잠근다. 하나라도 `PENDING` 또는 `FAILED_RETRYABLE`이면 단계와 revision을 한 건도 변경하지 않고 전체 rollback하며 HTTP 423을 반환한다. 따라서 RESERVE가 검사와 단계 UPDATE 사이에 끼어드는 기존 TOCTOU 구간이 없다.

관리자 계좌 수정은 mode 값이 `OFF`여도 기존 기능을 유지하되 mode 행을 공유 잠금 장벽으로만 사용한다. `mode -> 동일 pb_id unresolved audit 확인 -> ADMIN audit insert -> 지급 행 CAS update -> nfor_log -> audit APPLIED`를 단일 트랜잭션으로 실행한다. audit insert 직후 PHP 프로세스가 종료되어도 DB 연결 종료가 전체 transaction을 rollback하므로 lease 없는 `PENDING`이 영구 보존되지 않는다. 실패 경로에서도 미확정 ADMIN audit을 별도 commit하지 않는다.

S2S는 `mode -> operation audit -> 지급 행`, 관리자와 `setstep`은 `mode -> pb_id audit -> 지급 행` 순서다. 지급 행을 먼저 잡은 뒤 audit이나 mode로 역진입하는 코드를 추가하면 안 된다. PR의 MariaDB 실행 회귀는 ADMIN audit insert 직후 연결 종료 rollback과 미확정 RESERVE/setstep 경합을 실제 별도 연결로 확인한다.

코드 배포는 전용 Actions `포인트 출금 계좌 정정 검증·지정 업로드`만 사용한다. 먼저 미리보기 실행의 라이브 backup artifact와 diff를 확인한다. 아래 repository secret 이름만 등록하며 값은 저장소·workflow 입력·로그에 쓰지 않는다.

- `METACREW_POINT_BANK_TOKEN`
- `METACREW_POINT_BANK_HMAC_CURRENT_ID`
- `METACREW_POINT_BANK_HMAC_CURRENT_SECRET`
- `METACREW_POINT_BANK_HMAC_PREVIOUS_ID` (교체 중에만)
- `METACREW_POINT_BANK_HMAC_PREVIOUS_SECRET` (교체 중에만)

workflow는 이 값으로 추적 제외된 `data/private/mc_point_bank_account_secrets.php`를 실행 중에만 만들고 self-test 뒤 업로드한다. 직접 요청은 PHP guard와 `data/private/.htaccess`가 함께 차단한다. `baseline_confirm=VERIFIED`, `ddl_confirm=DDL_OFF_VERIFIED`, `confirm=UPLOAD` 세 조건이 모두 맞아야 지정 파일 업로드가 실행된다.

활성화와 중단은 반드시 아래 조건부 UPDATE를 사용하고 `ROW_COUNT()=1`을 확인한다. `ON -> OFF`, `DRAIN -> ON` 직접 전환 SQL은 사용하지 않는다.

```sql
-- 양쪽 preflight가 끝난 뒤 활성화
START TRANSACTION;
SELECT mode, state_version FROM nfor_point_bank_account_mode WHERE singleton_id=1 FOR UPDATE;
UPDATE nfor_point_bank_account_mode
SET mode='ON', state_version=state_version+1, updated_at=NOW()
WHERE singleton_id=1 AND mode='OFF'
  AND NOT EXISTS (
    SELECT 1 FROM nfor_point_bank_account_audit
    WHERE state IN ('PENDING','FAILED_RETRYABLE')
  );
SELECT ROW_COUNT() AS changed_rows;
COMMIT;

-- 신규 RESERVE를 막는 drain barrier
START TRANSACTION;
SELECT mode, state_version FROM nfor_point_bank_account_mode WHERE singleton_id=1 FOR UPDATE;
UPDATE nfor_point_bank_account_mode
SET mode='DRAIN', state_version=state_version+1, updated_at=NOW()
WHERE singleton_id=1 AND mode='ON';
SELECT ROW_COUNT() AS changed_rows;
COMMIT;

-- 기존 operation이 모두 terminal인 경우에만 OFF
START TRANSACTION;
SELECT mode, state_version FROM nfor_point_bank_account_mode WHERE singleton_id=1 FOR UPDATE;
UPDATE nfor_point_bank_account_mode
SET mode='OFF', state_version=state_version+1, updated_at=NOW()
WHERE singleton_id=1 AND mode='DRAIN'
  AND NOT EXISTS (
    SELECT 1 FROM nfor_point_bank_account_audit
    WHERE state IN ('PENDING','FAILED_RETRYABLE')
  );
SELECT ROW_COUNT() AS changed_rows;
COMMIT;
```
