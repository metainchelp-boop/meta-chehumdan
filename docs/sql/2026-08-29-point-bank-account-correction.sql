-- 포인트 출금 건 지급정보 정정 원천 스키마
-- 운영 적용 전 반드시 README의 read-only preflight를 통과한다.
-- nfor_point_bank / nfor_log가 InnoDB가 아니면 실행하지 않는다.

ALTER TABLE nfor_point_bank
  ADD COLUMN IF NOT EXISTS pb_row_revision BIGINT UNSIGNED NOT NULL DEFAULT 0,
  ALGORITHM=INPLACE,
  LOCK=NONE;

CREATE TABLE IF NOT EXISTS nfor_point_bank_account_audit (
  audit_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  operation_id CHAR(36) NOT NULL,
  pb_id BIGINT UNSIGNED NOT NULL,
  state VARCHAR(32) NOT NULL,
  state_version BIGINT UNSIGNED NOT NULL DEFAULT 0,
  lease_owner CHAR(36) NULL,
  lease_expires_at DATETIME NULL,
  retry_after DATETIME NULL,
  apply_attempt_count TINYINT UNSIGNED NOT NULL DEFAULT 0,
  hmac_key_id VARCHAR(64) NOT NULL,
  request_hmac CHAR(64) NOT NULL,
  expected_revision VARCHAR(160) NOT NULL,
  actor_employee_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
  actor_name VARCHAR(150) NOT NULL,
  reason VARCHAR(600) NOT NULL,
  before_name VARCHAR(150) NULL,
  before_bank VARCHAR(90) NULL,
  before_account_masked VARCHAR(40) NULL,
  after_name VARCHAR(150) NULL,
  after_bank VARCHAR(90) NULL,
  after_account_masked VARCHAR(40) NULL,
  result_revision VARCHAR(160) NULL,
  failure_code VARCHAR(40) NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  completed_at DATETIME NULL,
  PRIMARY KEY (audit_id),
  UNIQUE KEY uk_point_bank_account_operation (operation_id),
  KEY ix_point_bank_account_pb_state (pb_id, state),
  KEY ix_point_bank_account_retry (state, retry_after, operation_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS nfor_point_bank_account_mode (
  singleton_id TINYINT UNSIGNED NOT NULL,
  mode VARCHAR(10) NOT NULL,
  state_version BIGINT UNSIGNED NOT NULL DEFAULT 0,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (singleton_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO nfor_point_bank_account_mode (singleton_id, mode, state_version, updated_at)
VALUES (1, 'OFF', 0, NOW())
ON DUPLICATE KEY UPDATE singleton_id=VALUES(singleton_id);
