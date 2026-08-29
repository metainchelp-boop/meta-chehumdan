<?php
require_once dirname(__DIR__).'/lib/mc_point_bank_account.lib.php';

function fail_test($message){
    fwrite(STDERR, "FAIL: ".$message."\n");
    exit(1);
}

function db_connect(){
    $db = mysqli_connect(
        getenv('POINT_BANK_TEST_DB_HOST') ?: '127.0.0.1',
        getenv('POINT_BANK_TEST_DB_USER') ?: 'root',
        getenv('POINT_BANK_TEST_DB_PASSWORD') ?: '',
        '',
        (int)(getenv('POINT_BANK_TEST_DB_PORT') ?: 3306)
    );
    if(!$db) fail_test('MariaDB 연결 실패');
    mysqli_set_charset($db, 'utf8');
    return $db;
}

function query_ok($db, $sql){
    if(!mysqli_query($db, $sql)) fail_test('SQL 실패: '.mysqli_error($db));
}

if(getenv('POINT_BANK_TEST_WORKER') === 'setstep'){
    $workerDb = db_connect();
    query_ok($workerDb, 'USE pointbank_atomicity_test');
    $workerResult = mc_pb_set_step_atomic($workerDb, array(1), 2);
    echo json_encode($workerResult);
    mysqli_close($workerDb);
    exit(empty($workerResult['ok']) && isset($workerResult['code']) && $workerResult['code'] === 'ACCOUNT_CHANGE_PENDING' ? 0 : 2);
}

$db = db_connect();
query_ok($db, 'DROP DATABASE IF EXISTS pointbank_atomicity_test');
query_ok($db, 'CREATE DATABASE pointbank_atomicity_test CHARACTER SET utf8mb4');
query_ok($db, 'USE pointbank_atomicity_test');
query_ok($db, "CREATE TABLE nfor_point_bank (
    pb_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    pb_step TINYINT NOT NULL,
    pb_name VARCHAR(150) NOT NULL,
    pb_bank VARCHAR(90) NOT NULL,
    pb_bank_number VARCHAR(40) NOT NULL,
    PRIMARY KEY(pb_id)
) ENGINE=InnoDB");
query_ok($db, "CREATE TABLE nfor_log (
    log_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    log_file VARCHAR(100) NOT NULL,
    log_text TEXT NOT NULL,
    log_datetime DATETIME NOT NULL,
    PRIMARY KEY(log_id)
) ENGINE=InnoDB");

$ddl = file_get_contents(dirname(__DIR__).'/docs/sql/2026-08-29-point-bank-account-correction.sql');
if(!mysqli_multi_query($db, $ddl)) fail_test('DDL 실행 실패: '.mysqli_error($db));
do {
    if($result = mysqli_store_result($db)) mysqli_free_result($result);
} while(mysqli_more_results($db) && mysqli_next_result($db));
if(mysqli_errno($db)) fail_test('DDL 결과 처리 실패: '.mysqli_error($db));

query_ok($db, "INSERT INTO nfor_point_bank (pb_step,pb_name,pb_bank,pb_bank_number) VALUES (1,'기존예금주','기존은행','001-111-2222')");
$payload = array(
    'pbId'=>1,
    'expectedStep'=>1,
    'expectedRowRevision'=>0,
    'name'=>'새예금주',
    'bank'=>'국민은행',
    'account'=>'001-234-567890',
    'actorName'=>'관리자'
);

$crashed = false;
try {
    mc_pb_admin_change($db, $payload, function($stage){
        if($stage === 'after_audit_insert') throw new Exception('simulated process crash');
    });
} catch(Exception $expected){
    $crashed = true;
}
if(!$crashed) fail_test('audit insert 직후 crash가 주입되지 않았습니다');
mysqli_close($db);

$db = db_connect();
query_ok($db, 'USE pointbank_atomicity_test');
$row = mysqli_fetch_assoc(mysqli_query($db, 'SELECT pb_name,pb_bank,pb_bank_number,pb_row_revision FROM nfor_point_bank WHERE pb_id=1'));
if($row['pb_name'] !== '기존예금주' || (int)$row['pb_row_revision'] !== 0) fail_test('crash 뒤 payout row가 변경되었습니다');
$auditCount = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) cnt FROM nfor_point_bank_account_audit WHERE state IN ('PENDING','FAILED_RETRYABLE')"));
if((int)$auditCount['cnt'] !== 0) fail_test('crash 뒤 미확정 ADMIN audit가 남았습니다');
$logCount = mysqli_fetch_assoc(mysqli_query($db, 'SELECT COUNT(*) cnt FROM nfor_log'));
if((int)$logCount['cnt'] !== 0) fail_test('crash 뒤 로그만 남았습니다');

$result = mc_pb_admin_change($db, $payload);
if(empty($result['ok']) || $result['result'] !== 'UPDATED') fail_test('mode OFF에서 관리자 수정이 실패했습니다');
$row = mysqli_fetch_assoc(mysqli_query($db, 'SELECT pb_name,pb_bank,pb_bank_number,pb_row_revision FROM nfor_point_bank WHERE pb_id=1'));
if($row['pb_name'] !== '새예금주' || $row['pb_bank_number'] !== '001234567890' || (int)$row['pb_row_revision'] !== 1) fail_test('관리자 성공 결과가 원천 row에 반영되지 않았습니다');
$applied = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) cnt FROM nfor_point_bank_account_audit WHERE state='APPLIED'"));
if((int)$applied['cnt'] !== 1) fail_test('관리자 APPLIED 감사가 원자적으로 남지 않았습니다');

$config = array(
    'token'=>str_repeat('t',32),
    'currentKeyId'=>'pointbank-test',
    'currentSecret'=>'pointbank-test-secret-pointbank-test',
    'previousKeyId'=>'',
    'previousSecret'=>''
);
$operationId = '22222222-2222-4222-8222-222222222222';
$requestHmac = str_repeat('a',64);
$statusPayload = array(
    'action'=>'STATUS',
    'operationId'=>$operationId,
    'hmacKeyId'=>$config['currentKeyId'],
    'requestHmac'=>$requestHmac,
    'actionHmac'=>mc_pb_action_hmac($config['currentSecret'], $config['currentKeyId'], 'STATUS', $operationId, $requestHmac)
);
$status = mc_pb_handle_source_action($db, 'STATUS', $statusPayload, $config);
if(empty($status['ok']) || $status['result'] !== 'NOT_FOUND' || $status['mode'] !== 'OFF') fail_test('mode OFF에서 STATUS 원자 조회가 실패했습니다');

// RESERVE가 audit insert를 아직 commit하지 않은 사이 setstep이 들어오는 실제 경합을 재현한다.
// setstep의 audit FOR UPDATE는 commit을 기다린 뒤 pending을 관찰하고 전체 변경을 거부해야 한다.
if(!function_exists('proc_open')) fail_test('setstep 경합 회귀를 실행할 proc_open이 없습니다');
query_ok($db, 'START TRANSACTION');
$mode = mc_pb_mode_lock($db, false);
if(!$mode) fail_test('경합 테스트 mode 잠금 실패');
$raceOperation = '33333333-3333-4333-8333-333333333333';
query_ok($db, "INSERT INTO nfor_point_bank_account_audit SET operation_id='".$raceOperation."', pb_id=1, "
    ."state='PENDING', state_version=0, apply_attempt_count=0, hmac_key_id='pointbank-test', "
    ."request_hmac='".str_repeat('b',64)."', expected_revision='race', actor_employee_id=1, "
    ."actor_name='경합테스트', reason='경합테스트', created_at=NOW(), updated_at=NOW()");
$command = 'POINT_BANK_TEST_WORKER=setstep '.escapeshellarg(PHP_BINARY).' '.escapeshellarg(__FILE__);
$pipes = array();
$process = proc_open($command, array(1=>array('pipe','w'), 2=>array('pipe','w')), $pipes);
if(!is_resource($process)) fail_test('setstep 경합 worker 시작 실패');
usleep(300000);
query_ok($db, 'COMMIT');
$workerOutput = stream_get_contents($pipes[1]);
$workerError = stream_get_contents($pipes[2]);
fclose($pipes[1]);
fclose($pipes[2]);
$workerExit = proc_close($process);
$workerResult = json_decode($workerOutput, true);
if($workerExit !== 0 || !is_array($workerResult) || !empty($workerResult['ok']) || $workerResult['code'] !== 'ACCOUNT_CHANGE_PENDING'){
    fail_test('RESERVE/setstep 경합이 pending 전체 rollback으로 직렬화되지 않았습니다: '.$workerError);
}
$row = mysqli_fetch_assoc(mysqli_query($db, 'SELECT pb_step,pb_row_revision FROM nfor_point_bank WHERE pb_id=1'));
if((int)$row['pb_step'] !== 1 || (int)$row['pb_row_revision'] !== 1) fail_test('pending 경합에서 단계 또는 revision이 변경되었습니다');

query_ok($db, "UPDATE nfor_point_bank_account_audit SET state='CANCELLED', state_version=state_version+1, completed_at=NOW() WHERE operation_id='".$raceOperation."'");
query_ok($db, "INSERT INTO nfor_point_bank (pb_step,pb_name,pb_bank,pb_bank_number) VALUES (1,'두번째','국민은행','123456')");
$setstep = mc_pb_set_step_atomic($db, array(2,1,2), 2);
if(empty($setstep['ok']) || (int)$setstep['updated'] !== 2) fail_test('정렬·중복제거 setstep 성공 회귀가 실패했습니다');
$changed = mysqli_query($db, 'SELECT pb_id,pb_step,pb_row_revision FROM nfor_point_bank ORDER BY pb_id');
while($changedRow = mysqli_fetch_assoc($changed)){
    if((int)$changedRow['pb_step'] !== 2) fail_test('setstep이 선택 행 전체를 변경하지 않았습니다');
    $expectedRevision = (int)$changedRow['pb_id'] === 1 ? 2 : 1;
    if((int)$changedRow['pb_row_revision'] !== $expectedRevision) fail_test('setstep revision 증가가 정확히 한 번이 아닙니다');
}

mysqli_close($db);
echo "PASS: 관리자 원자성·mode OFF·RESERVE/setstep 경합 회귀\n";
