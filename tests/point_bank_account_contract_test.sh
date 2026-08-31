#!/usr/bin/env bash
set -euo pipefail

root="$(cd "$(dirname "$0")/.." && pwd)"

fail() {
  echo "FAIL: $1" >&2
  exit 1
}

need_file() {
  test -f "$root/$1" || fail "$2"
}

need() {
  local file="$1"
  local pattern="$2"
  local message="$3"
  grep -Eq -- "$pattern" "$root/$file" || fail "$message"
}

need_file "lib/mc_point_bank_account.lib.php" "포인트 출금 계좌 정정 공통 모듈이 없습니다"
need_file "front/mc_point_bank_account_api.php" "서버 간 계좌 정정 API가 없습니다"
need_file "admin/inc_point_bank_account_edit.php" "기존 관리자 계좌 정정 공통 화면이 없습니다"
need_file "docs/sql/2026-08-29-point-bank-account-correction.sql" "운영자 선실행 DDL이 없습니다"
need_file "tests/point_bank_account_protocol_test.php" "프로토콜 골든 벡터 테스트가 없습니다"
need_file "scripts/build_point_bank_private_config.php" "런타임 private config 생성기가 없습니다"
need_file "data/private/.htaccess" "private config HTTP 접근 차단 파일이 없습니다"

need "front/mc_point_bank_account_api.php" 'REQUEST_METHOD.*POST' "S2S API가 POST만 허용하지 않습니다"
need "front/mc_point_bank_account_api.php" 'HTTP_X_METACREW_TOKEN' "S2S 인증이 헤더 토큰을 사용하지 않습니다"
need "front/mc_point_bank_account_api.php" 'dirname\(__DIR__\)' "front API가 상위 공통 bootstrap을 사용하지 않습니다"
if grep -Eq '\$_(GET|REQUEST).*token|post_log\(' "$root/front/mc_point_bank_account_api.php"; then
  fail "S2S API가 URL 토큰 또는 요청 전문 로깅을 사용합니다"
fi

for action in RESERVE APPLY STATUS CANCEL; do
  need "front/mc_point_bank_account_api.php" "'$action'" "S2S $action 동작이 없습니다"
done

need "docs/sql/2026-08-29-point-bank-account-correction.sql" 'pb_row_revision[[:space:]]+BIGINT[[:space:]]+UNSIGNED[[:space:]]+NOT[[:space:]]+NULL[[:space:]]+DEFAULT[[:space:]]+0' "행 revision DDL이 없습니다"
need "docs/sql/2026-08-29-point-bank-account-correction.sql" 'nfor_point_bank_account_audit' "원천 감사 테이블 DDL이 없습니다"
need "docs/sql/2026-08-29-point-bank-account-correction.sql" 'nfor_point_bank_account_mode' "원천 mode barrier DDL이 없습니다"
need "docs/sql/2026-08-29-point-bank-account-correction.sql" 'ENGINE=InnoDB' "신규 테이블이 InnoDB로 고정되지 않았습니다"
need "lib/mc_point_bank_account.lib.php" "mode\['mode'\][[:space:]]*!==[[:space:]]*'ON'" "RESERVE가 source ON mode를 요구하지 않습니다"
need "lib/mc_point_bank_account.lib.php" 'apply_attempt_count[[:space:]]*<[[:space:]]*4' "source APPLY 총 4회 상한이 없습니다"
need "lib/mc_point_bank_account.lib.php" "state in \('PENDING','FAILED_RETRYABLE'\)" "미확정 source 상태 집합이 일관되지 않습니다"
need "lib/mc_point_bank_account.lib.php" 'before_account_masked' "원천 감사에 변경 전 마스킹 계좌가 없습니다"
need "lib/mc_point_bank_account.lib.php" 'after_account_masked' "원천 감사에 변경 후 마스킹 계좌가 없습니다"
need "lib/mc_point_bank_account.lib.php" "preg_match_all\('/\./us'" "PHP7 code-point 길이 helper가 없습니다"
need "lib/mc_point_bank_account.lib.php" 'mc_pb_codepoint_length\(\$reason\)[[:space:]]*>[[:space:]]*500' "변경 사유 500 code-point 계약이 없습니다"
need "lib/mc_point_bank_account.lib.php" 'mc_pb_codepoint_length\(\$name\)[[:space:]]*>[[:space:]]*50' "예금주 50 code-point 계약이 없습니다"
need "lib/mc_point_bank_account.lib.php" 'mc_pb_codepoint_length\(\$actorName\)[[:space:]]*>[[:space:]]*100' "처리자명 100 code-point 계약이 없습니다"
if grep -Eq 'strlen\(\$(name|reason|actorName)\)' "$root/lib/mc_point_bank_account.lib.php"; then
  fail "승인 문자열 길이를 UTF-8 byte 수로 검사하는 경로가 남아 있습니다"
fi
if grep -Eq 'strtotime\(|[^A-Za-z_]time\(' "$root/lib/mc_point_bank_account.lib.php"; then
  fail "lease 또는 retry 판단이 DB 시각 대신 PHP 시각을 사용합니다"
fi
status_block=$(awk '/^function mc_pb_source_status\(/,/^function mc_pb_source_reserve\(/' "$root/lib/mc_point_bank_account.lib.php")
if printf '%s' "$status_block" | grep -Eq "mode.*(OFF|ON|DRAIN).*return"; then
  fail "STATUS가 mode OFF에서 원자 상태 조회를 막습니다"
fi
admin_block=$(awk '/^function mc_pb_admin_change\(/,0' "$root/lib/mc_point_bank_account.lib.php")
if printf '%s' "$admin_block" | grep -Eq 'mc_pb_source_config'; then
  fail "기존 관리자 수정이 S2S secret에 종속되었습니다"
fi
admin_begin_count=$(printf '%s' "$admin_block" | grep -Ec 'mc_pb_begin\(\$db\)')
test "$admin_begin_count" -eq 1 || fail "관리자 수정이 둘 이상의 트랜잭션으로 나뉘어 crash 시 PENDING을 남길 수 있습니다"
admin_mode_line=$(printf '%s' "$admin_block" | grep -n 'mc_pb_mode_lock' | head -1 | cut -d: -f1)
admin_audit_line=$(printf '%s' "$admin_block" | grep -n "state in ('PENDING','FAILED_RETRYABLE')" | head -1 | cut -d: -f1)
admin_insert_line=$(printf '%s' "$admin_block" | grep -n 'insert into nfor_point_bank_account_audit' | head -1 | cut -d: -f1)
admin_payout_line=$(printf '%s' "$admin_block" | grep -n 'select pb_id, pb_step.*FOR UPDATE' | head -1 | cut -d: -f1)
test -n "$admin_mode_line" && test -n "$admin_audit_line" && test -n "$admin_insert_line" && test -n "$admin_payout_line" || fail "관리자 lock order를 확인할 수 없습니다"
test "$admin_mode_line" -lt "$admin_audit_line" && test "$admin_audit_line" -lt "$admin_insert_line" && test "$admin_insert_line" -lt "$admin_payout_line" || fail "관리자 lock order가 mode→unresolved audit→자기 audit→payout이 아닙니다"
need_file "tests/point_bank_admin_atomicity_test.php" "관리자 crash rollback 실행 회귀가 없습니다"

setstep_block=$(awk '/^function mc_pb_set_step_atomic\(/,/^function mc_pb_mode_lock\(/' "$root/lib/mc_point_bank_account.lib.php")
setstep_mode_line=$(printf '%s' "$setstep_block" | grep -n 'mc_pb_mode_lock' | head -1 | cut -d: -f1)
setstep_audit_line=$(printf '%s' "$setstep_block" | grep -n "state in ('PENDING','FAILED_RETRYABLE')" | head -1 | cut -d: -f1)
setstep_payout_line=$(printf '%s' "$setstep_block" | grep -n 'select pb_id from nfor_point_bank.*FOR UPDATE' | head -1 | cut -d: -f1)
setstep_update_line=$(printf '%s' "$setstep_block" | grep -n 'update nfor_point_bank set' | head -1 | cut -d: -f1)
test -n "$setstep_mode_line" && test -n "$setstep_audit_line" && test -n "$setstep_payout_line" && test -n "$setstep_update_line" || fail "setstep 원자 잠금 순서를 확인할 수 없습니다"
test "$setstep_mode_line" -lt "$setstep_audit_line" && test "$setstep_audit_line" -lt "$setstep_payout_line" && test "$setstep_payout_line" -lt "$setstep_update_line" || fail "setstep lock order가 mode→audit→payout→update가 아닙니다"
need "lib/mc_point_bank_account.lib.php" 'sort\(\$ids, SORT_NUMERIC\)' "setstep ID가 교착 방지를 위해 정렬되지 않습니다"
need "mc_point_bank_api.php" 'mc_pb_set_step_atomic' "기존 setstep writer가 원자 kernel을 사용하지 않습니다"
if awk '/if\(\$action === '\''setstep'\''\)/,/^}/' "$root/mc_point_bank_api.php" | grep -Eq 'mc_pb_row_change_pending|update nfor_point_bank'; then
  fail "setstep에 검사-갱신 TOCTOU 경로가 남아 있습니다"
fi
if grep -Eq '(^|[[:space:],])((before|after)_)?account[[:space:]]+(VARCHAR|TEXT)' "$root/docs/sql/2026-08-29-point-bank-account-correction.sql"; then
  fail "원천 감사 DDL에 계좌번호 원문 컬럼이 있습니다"
fi

writers=(
  "admin/point_bank_list.php"
  "admin/point_bank_wait_list.php"
  "admin/point_bank_stop_list.php"
)
for file in "${writers[@]}"; do
  need "$file" 'mc_pb_set_step_atomic' "$file 단계 writer가 원자 kernel을 사용하지 않습니다"
  if grep -Eq 'mc_pb_row_change_pending|update nfor_point_bank set pb_step' "$root/$file"; then
    fail "$file 단계 writer에 검사-갱신 TOCTOU 경로가 남아 있습니다"
  fi
done
need "lib/mc_point_bank_account.lib.php" 'pb_step=.*pb_row_revision=pb_row_revision\+1' "setstep 원자 kernel이 revision을 증가시키지 않습니다"

need "mc_point_bank_api.php" 'pb_row_revision' "기존 보드 조회가 원천 revision을 읽지 않습니다"
need "mc_point_bank_api.php" '"revision"' "기존 보드 응답에 불투명 revision이 없습니다"
need "admin/point_bank_list.php" 'inc_point_bank_account_edit.php' "출금신청 관리자 화면이 공통 수정 kernel을 사용하지 않습니다"
need "admin/point_bank_wait_list.php" 'inc_point_bank_account_edit.php' "입금예정 관리자 화면이 공통 수정 kernel을 사용하지 않습니다"
need "admin/inc_point_bank_account_edit.php" 'pb_row_revision' "관리자 수정이 revision CAS를 사용하지 않습니다"

need ".github/workflows/point-bank-account-upload.yml" '--ftp-create-dirs' "FTP 배포가 신규 front 하위 디렉터리를 만들 수 없습니다"
need ".github/workflows/point-bank-account-upload.yml" 'php-version:[[:space:]]*'"'"'7\.0'"'"'' "배포 전 PHP 7.0 검증이 없습니다"
need ".github/workflows/point-bank-account-upload.yml" 'baseline_confirm' "라이브 기준선 확인 없이 업로드할 수 있습니다"
need ".github/workflows/point-bank-account-upload.yml" 'required_existing=' "필수 라이브 파일 기준선 검사가 없습니다"
need ".github/workflows/point-bank-account-upload.yml" 'missing_required.*-ne 0' "필수 라이브 파일 누락 시 업로드 차단이 없습니다"
need ".github/workflows/point-bank-account-upload.yml" 'if-no-files-found:[[:space:]]*error' "라이브 백업이 비어도 workflow가 성공할 수 있습니다"
need ".github/workflows/point-bank-account-upload.yml" 'remote_dir_probe' "FTP 루트를 쓰지 않고 진단할 읽기 전용 입력이 없습니다"
need ".github/workflows/point-bank-account-upload.yml" 'remote_dir_probe.*!=.*' "FTP 루트 probe 실행이 업로드를 막지 않습니다"
need ".github/workflows/point-bank-account-upload.yml" "@account-www.*home/hosting_users/.*USER.*/www" "Cafe24 계정 절대경로를 비밀 사용자명 노출 없이 진단할 수 없습니다"
need ".github/workflows/point-bank-account-upload.yml" 'DDL_OFF_VERIFIED' "DDL·InnoDB·mode OFF 확인 없이 업로드할 수 있습니다"
need ".github/workflows/point-bank-account-upload.yml" 'METACREW_POINT_BANK_HMAC_CURRENT_SECRET.*secrets\.' "HMAC secret이 Actions secret에서 주입되지 않습니다"
need ".gitignore" 'data/private/mc_point_bank_account_secrets.php' "런타임 private config가 git에서 제외되지 않았습니다"
need "lib/mc_point_bank_account.lib.php" 'data/private/mc_point_bank_account_secrets.php' "런타임이 private config를 읽지 않습니다"
need "scripts/build_point_bank_private_config.php" "defined\('MC_POINT_BANK_PRIVATE_LOAD'\)" "private config 직접 실행 차단 guard가 없습니다"
if git -C "$root" ls-files --error-unmatch data/private/mc_point_bank_account_secrets.php >/dev/null 2>&1; then
  fail "런타임 private config가 저장소에 추적되었습니다"
fi

if grep -Eq 'nfor_member[^;]*(mb_bank|bank_account)' "$root/front/mc_point_bank_account_api.php" "$root/lib/mc_point_bank_account.lib.php" "$root/admin/inc_point_bank_account_edit.php"; then
  fail "회원 기본 계좌를 변경하는 코드가 포함됐습니다"
fi

echo "PASS: 포인트 출금 계좌 정정 정적 계약"
