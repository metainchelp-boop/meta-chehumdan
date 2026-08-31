<?php
/*
 * 포인트 출금 건 지급정보 정정 공통 모듈 (PHP 7.0 호환)
 *
 * 공개 interface:
 * - mc_pb_handle_source_action($db, $action, $payload, $config)
 * - mc_pb_admin_change($db, $payload)
 * - mc_pb_revision_token / mc_pb_verify_revision
 *
 * 계좌번호 원문, 인증 헤더, 요청 전문을 로그나 감사 테이블에 저장하지 않는다.
 */

function mc_pb_encode($value){
    $value = (string)$value;
    return strlen($value).':'.$value;
}

function mc_pb_trim_unicode($value){
    $value = (string)$value;
    $trimmed = preg_replace('/^[\s\p{Z}]+|[\s\p{Z}]+$/u', '', $value);
    return is_string($trimmed) ? $trimmed : '';
}

function mc_pb_codepoint_length($value){
    $count = preg_match_all('/./us', (string)$value, $matches);
    return $count === false ? PHP_INT_MAX : $count;
}

function mc_pb_normalize_account($value){
    $value = preg_replace('/[-\s\p{Z}]+/u', '', (string)$value);
    if($value === '' || !preg_match('/^[0-9]+$/', $value)) return '';
    return $value;
}

function mc_pb_bank_options(){
    return array(
        array('code'=>'002', 'name'=>'산업은행'),
        array('code'=>'003', 'name'=>'기업은행'),
        array('code'=>'004', 'name'=>'국민은행'),
        array('code'=>'007', 'name'=>'수협은행'),
        array('code'=>'008', 'name'=>'수출입은행'),
        array('code'=>'011', 'name'=>'NH농협은행'),
        array('code'=>'012', 'name'=>'지역농축협'),
        array('code'=>'020', 'name'=>'우리은행'),
        array('code'=>'023', 'name'=>'SC제일은행'),
        array('code'=>'027', 'name'=>'한국씨티은행'),
        array('code'=>'031', 'name'=>'iM뱅크'),
        array('code'=>'032', 'name'=>'부산은행'),
        array('code'=>'034', 'name'=>'광주은행'),
        array('code'=>'035', 'name'=>'제주은행'),
        array('code'=>'037', 'name'=>'전북은행'),
        array('code'=>'039', 'name'=>'경남은행'),
        array('code'=>'045', 'name'=>'새마을금고'),
        array('code'=>'048', 'name'=>'신협'),
        array('code'=>'050', 'name'=>'저축은행'),
        array('code'=>'064', 'name'=>'산림조합'),
        array('code'=>'071', 'name'=>'우체국'),
        array('code'=>'081', 'name'=>'하나은행'),
        array('code'=>'088', 'name'=>'신한은행'),
        array('code'=>'089', 'name'=>'케이뱅크'),
        array('code'=>'090', 'name'=>'카카오뱅크'),
        array('code'=>'092', 'name'=>'토스뱅크')
    );
}

function mc_pb_bank_by_code($code){
    foreach(mc_pb_bank_options() as $bank){
        if($bank['code'] === (string)$code) return $bank;
    }
    return null;
}

function mc_pb_request_hmac($secret, $payload){
    $account = mc_pb_normalize_account(isset($payload['account']) ? $payload['account'] : '');
    $values = array(
        'REQUEST',
        isset($payload['hmacKeyId']) ? $payload['hmacKeyId'] : '',
        isset($payload['operationId']) ? strtolower($payload['operationId']) : '',
        isset($payload['pbId']) ? (string)$payload['pbId'] : '',
        isset($payload['expectedRevision']) ? $payload['expectedRevision'] : '',
        mc_pb_trim_unicode(isset($payload['name']) ? $payload['name'] : ''),
        isset($payload['bankCode']) ? $payload['bankCode'] : '',
        isset($payload['bankName']) ? $payload['bankName'] : '',
        $account,
        mc_pb_trim_unicode(isset($payload['reason']) ? $payload['reason'] : ''),
        isset($payload['actorEmployeeId']) ? (string)$payload['actorEmployeeId'] : '',
        mc_pb_trim_unicode(isset($payload['actorName']) ? $payload['actorName'] : '')
    );
    $encoded = '';
    foreach($values as $value) $encoded .= mc_pb_encode($value);
    return hash_hmac('sha256', $encoded, $secret);
}

function mc_pb_action_hmac($secret, $keyId, $action, $operationId, $requestHmac){
    $encoded = mc_pb_encode('ACTION')
        .mc_pb_encode($keyId)
        .mc_pb_encode($action)
        .mc_pb_encode(strtolower($operationId))
        .mc_pb_encode($requestHmac);
    return hash_hmac('sha256', $encoded, $secret);
}

function mc_pb_revision_token($secret, $keyId, $pbId, $rowRevision){
    $encoded = mc_pb_encode('REVISION')
        .mc_pb_encode($keyId)
        .mc_pb_encode((string)(int)$pbId)
        .mc_pb_encode((string)(int)$rowRevision);
    return 'v1.'.$keyId.'.'.hash_hmac('sha256', $encoded, $secret);
}

function mc_pb_mask_account($account){
    $account = mc_pb_normalize_account($account);
    $length = strlen($account);
    if($length === 0) return '';
    if($length <= 4) return str_repeat('*', $length);
    return substr($account, 0, 3).str_repeat('*', max(3, $length - 7)).substr($account, -4);
}

function mc_pb_redact_account_like_text($text){
    return preg_replace_callback('/[0-9][0-9\-\s\p{Z}]{4,}[0-9]/u', function($match){
        $digits = preg_replace('/[^0-9]/', '', $match[0]);
        return strlen($digits) >= 6 ? '[계좌번호 마스킹]' : $match[0];
    }, (string)$text);
}

function mc_pb_source_config(){
    $config = array(
        'token' => (string)getenv('METACREW_POINT_BANK_TOKEN'),
        'currentKeyId' => (string)getenv('METACREW_POINT_BANK_HMAC_CURRENT_ID'),
        'currentSecret' => (string)getenv('METACREW_POINT_BANK_HMAC_CURRENT_SECRET'),
        'previousKeyId' => (string)getenv('METACREW_POINT_BANK_HMAC_PREVIOUS_ID'),
        'previousSecret' => (string)getenv('METACREW_POINT_BANK_HMAC_PREVIOUS_SECRET')
    );
    if($config['token'] !== '' || $config['currentKeyId'] !== '' || $config['currentSecret'] !== '') return $config;
    $privatePath = dirname(__DIR__).'/data/private/mc_point_bank_account_secrets.php';
    if(!is_file($privatePath) || !is_readable($privatePath)) return $config;
    if(!defined('MC_POINT_BANK_PRIVATE_LOAD')) define('MC_POINT_BANK_PRIVATE_LOAD', true);
    $private = include $privatePath;
    if(!is_array($private)) return $config;
    foreach(array_keys($config) as $key){
        if(isset($private[$key]) && is_string($private[$key])) $config[$key] = $private[$key];
    }
    return $config;
}

function mc_pb_secret_for_key($config, $keyId){
    if($keyId !== '' && isset($config['currentKeyId']) && hash_equals((string)$config['currentKeyId'], (string)$keyId)){
        return (string)$config['currentSecret'];
    }
    if($keyId !== '' && isset($config['previousKeyId']) && $config['previousKeyId'] !== '' && hash_equals((string)$config['previousKeyId'], (string)$keyId)){
        return (string)$config['previousSecret'];
    }
    return '';
}

function mc_pb_verify_revision($config, $token, $pbId, $rowRevision){
    if(!preg_match('/^v1\.([A-Za-z0-9._-]{1,64})\.([0-9a-f]{64})$/', (string)$token, $match)) return false;
    $secret = mc_pb_secret_for_key($config, $match[1]);
    if($secret === '') return false;
    return hash_equals(mc_pb_revision_token($secret, $match[1], $pbId, $rowRevision), (string)$token);
}

function mc_pb_uuid(){
    $bytes = random_bytes(16);
    $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
    $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);
    $hex = bin2hex($bytes);
    return substr($hex,0,8).'-'.substr($hex,8,4).'-'.substr($hex,12,4).'-'.substr($hex,16,4).'-'.substr($hex,20);
}

function mc_pb_is_uuid($value){
    return preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', (string)$value) === 1;
}

function mc_pb_q($db, $value){
    return mysqli_real_escape_string($db, (string)$value);
}

function mc_pb_query($db, $sql){
    return @mysqli_query($db, $sql);
}

function mc_pb_one($db, $sql){
    $result = mc_pb_query($db, $sql);
    if(!$result) return null;
    $row = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $row ? $row : null;
}

function mc_pb_begin($db){ return mc_pb_query($db, 'START TRANSACTION'); }
function mc_pb_commit($db){ return mc_pb_query($db, 'COMMIT'); }
function mc_pb_rollback($db){ return mc_pb_query($db, 'ROLLBACK'); }

function mc_pb_error($code, $message, $field){
    $result = array('ok'=>false, 'code'=>$code, 'message'=>$message);
    if($field !== '') $result['field'] = $field;
    return $result;
}

function mc_pb_validate_full_payload($payload){
    $operationId = isset($payload['operationId']) ? (string)$payload['operationId'] : '';
    if(!mc_pb_is_uuid($operationId)) return mc_pb_error('INVALID_INPUT', '요청 식별자가 올바르지 않습니다.', '');
    $pbId = isset($payload['pbId']) ? (int)$payload['pbId'] : 0;
    if($pbId < 1) return mc_pb_error('INVALID_INPUT', '출금 건이 올바르지 않습니다.', '');
    $name = mc_pb_trim_unicode(isset($payload['name']) ? $payload['name'] : '');
    if($name === '' || mc_pb_codepoint_length($name) > 50) return mc_pb_error('INVALID_INPUT', '예금주를 확인해주세요.', 'name');
    $bank = mc_pb_bank_by_code(isset($payload['bankCode']) ? $payload['bankCode'] : '');
    if(!$bank || !isset($payload['bankName']) || $payload['bankName'] !== $bank['name']) return mc_pb_error('INVALID_INPUT', '은행을 다시 선택해주세요.', 'bankCode');
    $account = mc_pb_normalize_account(isset($payload['account']) ? $payload['account'] : '');
    if($account === '' || strlen($account) > 30) return mc_pb_error('INVALID_INPUT', '계좌번호는 숫자 30자리 이내로 입력해주세요.', 'account');
    $reason = mc_pb_trim_unicode(isset($payload['reason']) ? $payload['reason'] : '');
    if($reason === '' || mc_pb_codepoint_length($reason) > 500) return mc_pb_error('INVALID_INPUT', '변경 사유를 입력해주세요.', 'reason');
    if(!isset($payload['expectedRevision']) || !preg_match('/^v1\.[A-Za-z0-9._-]{1,64}\.[0-9a-f]{64}$/', (string)$payload['expectedRevision'])) return mc_pb_error('INVALID_INPUT', '목록을 새로고침해주세요.', '');
    $actorEmployeeId = isset($payload['actorEmployeeId']) ? (int)$payload['actorEmployeeId'] : 0;
    $actorName = mc_pb_trim_unicode(isset($payload['actorName']) ? $payload['actorName'] : '');
    if($actorEmployeeId < 1 || $actorName === '' || mc_pb_codepoint_length($actorName) > 100) return mc_pb_error('INVALID_INPUT', '처리자를 확인할 수 없습니다.', '');
    $payload['operationId'] = $operationId;
    $payload['pbId'] = $pbId;
    $payload['name'] = $name;
    $payload['bankCode'] = $bank['code'];
    $payload['bankName'] = $bank['name'];
    $payload['account'] = $account;
    $payload['reason'] = $reason;
    $payload['actorEmployeeId'] = $actorEmployeeId;
    $payload['actorName'] = $actorName;
    return array('ok'=>true, 'payload'=>$payload);
}

function mc_pb_validate_config($config){
    if(!isset($config['token']) || strlen($config['token']) < 32) return false;
    if(!isset($config['currentKeyId']) || !preg_match('/^[A-Za-z0-9._-]{1,64}$/', $config['currentKeyId'])) return false;
    if(!isset($config['currentSecret']) || strlen($config['currentSecret']) < 32) return false;
    if((isset($config['previousKeyId']) && $config['previousKeyId'] !== '') xor (isset($config['previousSecret']) && $config['previousSecret'] !== '')) return false;
    if(isset($config['previousKeyId']) && $config['previousKeyId'] !== '' &&
        (!preg_match('/^[A-Za-z0-9._-]{1,64}$/', $config['previousKeyId']) || strlen($config['previousSecret']) < 32 || $config['previousKeyId'] === $config['currentKeyId'])) return false;
    return true;
}

function mc_pb_protocol_self_test($config){
    if(!mc_pb_validate_config($config)) return false;
    $seenCodes = array();
    $seenNames = array();
    foreach(mc_pb_bank_options() as $bank){
        if(isset($seenCodes[$bank['code']]) || isset($seenNames[$bank['name']])) return false;
        $seenCodes[$bank['code']] = true;
        $seenNames[$bank['name']] = true;
        $roundTrip = mc_pb_bank_by_code($bank['code']);
        if(!$roundTrip || $roundTrip['name'] !== $bank['name']) return false;
    }
    if(mc_pb_bank_by_code('011')['name'] !== 'NH농협은행' || mc_pb_bank_by_code('012')['name'] !== '지역농축협') return false;
    $test = array(
        'hmacKeyId'=>'pointbank-test', 'operationId'=>'11111111-1111-4111-8111-111111111111',
        'pbId'=>123, 'expectedRevision'=>'v1.pointbank-test.0123456789abcdef', 'name'=>'김메타',
        'bankCode'=>'004', 'bankName'=>'국민은행', 'account'=>'001234567890',
        'reason'=>'통장 사본 확인: 오기입 정정', 'actorEmployeeId'=>1, 'actorName'=>'대표'
    );
    $request = mc_pb_request_hmac('pointbank-test-secret', $test);
    if($request !== '2d549c5b407a514b0d115f9da68dada504202ed45eaf2ede5a93ed6da74d4550') return false;
    $vectors = array(
        'RESERVE'=>'73ebe6310145890ae660a370835a629c529c7fb728a4d4dd86af02d600efd1ce',
        'APPLY'=>'3816dc1b98f6ca9eb07c1eccfa063b16e54c3254afe90b6a2319874fc171210f',
        'STATUS'=>'83ea511e1f7c0dd01b4c0e842c368a969b9eae4913478a80809a952a04ffd7c7',
        'CANCEL'=>'1659593e0bcf961bcc770bd0c85e23e86c8235f9cba30100e388896332a1f5b0'
    );
    foreach($vectors as $action=>$expected){
        if(mc_pb_action_hmac('pointbank-test-secret', 'pointbank-test', $action, $test['operationId'], $request) !== $expected) return false;
    }
    if(mc_pb_revision_token('pointbank-test-secret', 'pointbank-test', 123, 7) !== 'v1.pointbank-test.4521ef1952b9bcf416d5db1d9a03c5fb95e0c48c3d6df4e0346954af7420bca2') return false;
    $liveRevision = mc_pb_revision_token($config['currentSecret'], $config['currentKeyId'], 1, 0);
    return mc_pb_verify_revision($config, $liveRevision, 1, 0);
}

function mc_pb_schema_ready($db){
    $tables = array('nfor_point_bank', 'nfor_point_bank_account_audit', 'nfor_point_bank_account_mode', 'nfor_log');
    foreach($tables as $table){
        $row = mc_pb_one($db, "SHOW TABLE STATUS LIKE '".mc_pb_q($db, $table)."'");
        if(!$row || strcasecmp((string)$row['Engine'], 'InnoDB') !== 0) return false;
    }
    $column = mc_pb_one($db, "SHOW COLUMNS FROM nfor_point_bank LIKE 'pb_row_revision'");
    if(!$column || stripos((string)$column['Type'], 'bigint') === false || strtoupper((string)$column['Null']) !== 'NO' || (string)$column['Default'] !== '0') return false;
    $requiredAudit = array('audit_id','operation_id','pb_id','state','state_version','lease_owner','lease_expires_at','retry_after','apply_attempt_count','hmac_key_id','request_hmac','expected_revision','before_account_masked','after_account_masked');
    foreach($requiredAudit as $name){
        if(!mc_pb_one($db, "SHOW COLUMNS FROM nfor_point_bank_account_audit LIKE '".mc_pb_q($db, $name)."'")) return false;
    }
    $unique = mc_pb_one($db, "SHOW INDEX FROM nfor_point_bank_account_audit WHERE Key_name='uk_point_bank_account_operation' and Non_unique=0");
    if(!$unique) return false;
    $mode = mc_pb_one($db, "select singleton_id, mode, state_version from nfor_point_bank_account_mode where singleton_id=1");
    return $mode && in_array($mode['mode'], array('OFF','ON','DRAIN'));
}

function mc_pb_row_change_pending($db, $pbId){
    $row = mc_pb_one($db, "select audit_id from nfor_point_bank_account_audit where pb_id='".(int)$pbId."' and state in ('PENDING','FAILED_RETRYABLE') limit 1");
    return $row ? true : false;
}

function mc_pb_set_step_atomic($db, $ids, $step, $options=array(), $faultInjector=null){
    $normalized = array();
    foreach((array)$ids as $id){
        $id = (int)$id;
        if($id > 0) $normalized[$id] = $id;
    }
    $ids = array_values($normalized);
    sort($ids, SORT_NUMERIC);
    $step = (int)$step;
    if(!is_array($options)) $options = array();
    if(count($ids) === 0 || $step < 1 || $step > 4){
        return mc_pb_error('INVALID_INPUT', '변경할 출금 단계가 올바르지 않습니다.', '');
    }
    if(!mc_pb_begin($db)) return mc_pb_error('SAVE_FAILED', '단계 변경을 시작할 수 없습니다.', '');

    // 모든 지급정보 writer는 mode -> audit -> payout 순서로 잠근다.
    $mode = mc_pb_mode_lock($db, false);
    if(!$mode){
        mc_pb_rollback($db);
        return mc_pb_error('SAVE_FAILED', '계좌 정정 상태를 확인할 수 없습니다.', '');
    }
    if(is_callable($faultInjector)) call_user_func($faultInjector, 'after_mode_lock');

    $inClause = implode(',', $ids);
    $pending = mc_pb_one($db, "select audit_id, pb_id from nfor_point_bank_account_audit "
        ."where pb_id in (".$inClause.") and state in ('PENDING','FAILED_RETRYABLE') "
        ."order by pb_id, audit_id limit 1 FOR UPDATE");
    if($pending){
        mc_pb_rollback($db);
        return mc_pb_error('ACCOUNT_CHANGE_PENDING', '전산에서 계좌정보를 확인 중입니다. 잠시 후 다시 시도해주세요.', '');
    }
    if(is_callable($faultInjector)) call_user_func($faultInjector, 'after_audit_lock');

    $rows = mc_pb_query($db, "select pb_id from nfor_point_bank where pb_id in (".$inClause.") order by pb_id FOR UPDATE");
    if(!$rows){
        mc_pb_rollback($db);
        return mc_pb_error('SAVE_FAILED', '출금 건을 잠글 수 없습니다.', '');
    }
    while(mysqli_fetch_assoc($rows)){}
    mysqli_free_result($rows);
    if(is_callable($faultInjector)) call_user_func($faultInjector, 'after_payout_lock');

    $set = "pb_step='".$step."', pb_row_revision=pb_row_revision+1";
    if(!empty($options['changeDatetime'])) $set .= ", pb_chage_datetime=NOW()";
    if(isset($options['sendDate'])) $set .= ", pb_send_date='".mc_pb_q($db, $options['sendDate'])."'";
    $updated = "update nfor_point_bank set ".$set." where pb_id in (".$inClause.")";
    if(!mc_pb_query($db, $updated)){
        mc_pb_rollback($db);
        return mc_pb_error('SAVE_FAILED', '단계를 변경할 수 없습니다.', '');
    }
    $affected = mysqli_affected_rows($db);
    if(!mc_pb_commit($db)){
        mc_pb_rollback($db);
        return mc_pb_error('SAVE_FAILED', '단계 변경을 확정할 수 없습니다.', '');
    }
    return array('ok'=>true, 'updated'=>$affected, 'step'=>$step);
}

function mc_pb_mode_lock($db, $write){
    $suffix = $write ? ' FOR UPDATE' : ' LOCK IN SHARE MODE';
    return mc_pb_one($db, "select mode, state_version from nfor_point_bank_account_mode where singleton_id=1".$suffix);
}

function mc_pb_audit_lock($db, $operationId, $write){
    $suffix = $write ? ' FOR UPDATE' : ' LOCK IN SHARE MODE';
    return mc_pb_one($db, "select *, if(lease_owner is not null and lease_expires_at > NOW(), 1, 0) as lease_active, "
        ."if(retry_after is null or retry_after <= NOW(), 1, 0) as retry_due "
        ."from nfor_point_bank_account_audit where operation_id='".mc_pb_q($db, $operationId)."'".$suffix);
}

function mc_pb_status_body($mode, $audit){
    if(!$audit){
        return array(
            'ok'=>true,
            'result'=>'NOT_FOUND',
            'mode'=>$mode['mode'],
            'modeStateVersion'=>(int)$mode['state_version'],
            'evidenceId'=>'MODE:'.(int)$mode['state_version'].':NOT_FOUND'
        );
    }
    $body = array(
        'ok'=>true,
        'result'=>$audit['state'],
        'mode'=>$mode['mode'],
        'modeStateVersion'=>(int)$mode['state_version'],
        'operationId'=>$audit['operation_id'],
        'hmacKeyId'=>$audit['hmac_key_id'],
        'requestHmac'=>$audit['request_hmac'],
        'stateVersion'=>(int)$audit['state_version'],
        'applyAttemptCount'=>(int)$audit['apply_attempt_count'],
        'evidenceId'=>'AUDIT:'.(int)$audit['audit_id'].':'.(int)$audit['state_version']
    );
    if(!empty($audit['retry_after'])) $body['retryAfter'] = $audit['retry_after'];
    if(!empty($audit['result_revision'])) $body['revision'] = $audit['result_revision'];
    if(!empty($audit['failure_code'])) $body['code'] = $audit['failure_code'];
    return $body;
}

function mc_pb_source_status($db, $payload){
    $operationId = isset($payload['operationId']) ? (string)$payload['operationId'] : '';
    if(!mc_pb_is_uuid($operationId)) return mc_pb_error('INVALID_INPUT', '요청 식별자가 올바르지 않습니다.', '');
    if(!mc_pb_begin($db)) return mc_pb_error('SAVE_FAILED', '상태를 확인할 수 없습니다.', '');
    $mode = mc_pb_mode_lock($db, false);
    if(!$mode){ mc_pb_rollback($db); return mc_pb_error('DISABLED', '계좌 정정 기능을 확인할 수 없습니다.', ''); }
    $audit = mc_pb_audit_lock($db, $operationId, false);
    if($audit && (!isset($payload['hmacKeyId']) || !isset($payload['requestHmac']) ||
        !hash_equals((string)$audit['hmac_key_id'], (string)$payload['hmacKeyId']) ||
        !hash_equals((string)$audit['request_hmac'], (string)$payload['requestHmac']))){
        mc_pb_rollback($db);
        return mc_pb_error('OPERATION_CONFLICT', '같은 요청 식별자에 다른 내용이 등록되어 있습니다.', '');
    }
    $body = mc_pb_status_body($mode, $audit);
    mc_pb_commit($db);
    return $body;
}

function mc_pb_source_reserve($db, $payload){
    $validated = mc_pb_validate_full_payload($payload);
    if(!$validated['ok']) return $validated;
    $payload = $validated['payload'];
    $safeReason = mc_pb_redact_account_like_text($payload['reason']);
    if(!mc_pb_begin($db)) return mc_pb_error('SAVE_FAILED', '예약을 시작할 수 없습니다.', '');
    $mode = mc_pb_mode_lock($db, false);
    if(!$mode || $mode['mode'] !== 'ON'){
        mc_pb_rollback($db);
        return mc_pb_error('DISABLED', '계좌 정정 기능이 현재 비활성 상태입니다.', '');
    }
    $existing = mc_pb_audit_lock($db, $payload['operationId'], true);
    if($existing){
        if(!hash_equals((string)$existing['request_hmac'], (string)$payload['requestHmac'])){
            mc_pb_rollback($db);
            return mc_pb_error('OPERATION_CONFLICT', '같은 요청 식별자에 다른 내용이 등록되어 있습니다.', '');
        }
        $body = mc_pb_status_body($mode, $existing);
        if($existing['state'] === 'APPLIED') $body['result'] = 'ALREADY_APPLIED';
        elseif(in_array($existing['state'], array('PENDING','FAILED_RETRYABLE'))) $body['result'] = 'ALREADY_RESERVED';
        mc_pb_commit($db);
        return $body;
    }
    $sql = "insert into nfor_point_bank_account_audit set "
        ."operation_id='".mc_pb_q($db, $payload['operationId'])."', "
        ."pb_id='".(int)$payload['pbId']."', state='PENDING', state_version=0, apply_attempt_count=0, "
        ."hmac_key_id='".mc_pb_q($db, $payload['hmacKeyId'])."', request_hmac='".mc_pb_q($db, $payload['requestHmac'])."', "
        ."expected_revision='".mc_pb_q($db, $payload['expectedRevision'])."', "
        ."actor_employee_id='".(int)$payload['actorEmployeeId']."', actor_name='".mc_pb_q($db, $payload['actorName'])."', "
        ."reason='".mc_pb_q($db, $safeReason)."', "
        ."after_name='".mc_pb_q($db, $payload['name'])."', after_bank='".mc_pb_q($db, $payload['bankName'])."', "
        ."after_account_masked='".mc_pb_q($db, mc_pb_mask_account($payload['account']))."', created_at=NOW(), updated_at=NOW()";
    if(!mc_pb_query($db, $sql)){
        mc_pb_rollback($db);
        $status = mc_pb_source_status($db, $payload);
        if(!empty($status['ok']) && isset($status['requestHmac']) && hash_equals($payload['requestHmac'], $status['requestHmac'])){
            if($status['result'] === 'APPLIED') $status['result'] = 'ALREADY_APPLIED';
            elseif(in_array($status['result'], array('PENDING','FAILED_RETRYABLE'))) $status['result'] = 'ALREADY_RESERVED';
            return $status;
        }
        return mc_pb_error('SAVE_FAILED', '예약을 저장할 수 없습니다.', '');
    }
    $auditId = mysqli_insert_id($db);
    if(!mc_pb_commit($db)) return mc_pb_error('SAVE_FAILED', '예약을 확정할 수 없습니다.', '');
    return array(
        'ok'=>true,
        'result'=>'RESERVED',
        'operationId'=>$payload['operationId'],
        'hmacKeyId'=>$payload['hmacKeyId'],
        'requestHmac'=>$payload['requestHmac'],
        'stateVersion'=>0,
        'evidenceId'=>'AUDIT:'.$auditId.':0'
    );
}

function mc_pb_mark_retryable($db, $operationId, $leaseOwner, $stateVersion){
    $sql = "update nfor_point_bank_account_audit set state='FAILED_RETRYABLE', state_version=state_version+1, "
        ."lease_owner=NULL, lease_expires_at=NULL, retry_after=DATE_ADD(NOW(), INTERVAL "
        ."(case when apply_attempt_count <= 1 then 5 when apply_attempt_count = 2 then 30 else 120 end) SECOND), "
        ."failure_code='SAVE_FAILED', updated_at=NOW() where operation_id='".mc_pb_q($db, $operationId)."' "
        ."and state='PENDING' and lease_owner='".mc_pb_q($db, $leaseOwner)."' and state_version='".(int)$stateVersion."'";
    return mc_pb_query($db, $sql) && mysqli_affected_rows($db) === 1;
}

function mc_pb_terminal_source($db, $audit, $state, $code, $before){
    $sql = "update nfor_point_bank_account_audit set state='".mc_pb_q($db, $state)."', state_version=state_version+1, "
        ."lease_owner=NULL, lease_expires_at=NULL, failure_code='".mc_pb_q($db, $code)."', "
        ."before_name='".mc_pb_q($db, $before ? $before['pb_name'] : '')."', "
        ."before_bank='".mc_pb_q($db, $before ? $before['pb_bank'] : '')."', "
        ."before_account_masked='".mc_pb_q($db, $before ? mc_pb_mask_account($before['pb_bank_number']) : '')."', "
        ."updated_at=NOW(), completed_at=NOW() where audit_id='".(int)$audit['audit_id']."' "
        ."and state='PENDING' and lease_owner='".mc_pb_q($db, $audit['lease_owner'])."' and state_version='".(int)$audit['state_version']."'";
    return mc_pb_query($db, $sql) && mysqli_affected_rows($db) === 1;
}

function mc_pb_terminal_conflict($db, $audit, $code, $before){
    return mc_pb_terminal_source($db, $audit, 'FAILED_CONFLICT', $code, $before);
}

function mc_pb_claim_apply($db, $payload){
    if(!mc_pb_begin($db)) return mc_pb_error('SAVE_FAILED', '수정을 시작할 수 없습니다.', '');
    $mode = mc_pb_mode_lock($db, false);
    if(!$mode || !in_array($mode['mode'], array('ON','DRAIN'))){
        mc_pb_rollback($db);
        return mc_pb_error('DISABLED', '계좌 정정 기능이 현재 비활성 상태입니다.', '');
    }
    $audit = mc_pb_audit_lock($db, $payload['operationId'], true);
    if(!$audit){ mc_pb_rollback($db); return mc_pb_error('NOT_FOUND', '먼저 요청을 예약해주세요.', ''); }
    if(!hash_equals((string)$audit['request_hmac'], (string)$payload['requestHmac'])){
        mc_pb_rollback($db);
        return mc_pb_error('OPERATION_CONFLICT', '같은 요청 식별자에 다른 내용이 등록되어 있습니다.', '');
    }
    if($audit['state'] === 'APPLIED'){
        $body = mc_pb_status_body($mode, $audit);
        $body['result'] = 'ALREADY_APPLIED';
        mc_pb_commit($db);
        return $body;
    }
    if(in_array($audit['state'], array('FAILED_CONFLICT','FAILED','CANCELLED'))){
        mc_pb_rollback($db);
        return mc_pb_error(isset($audit['failure_code']) ? $audit['failure_code'] : 'FORBIDDEN_STATE', '종료된 요청은 다시 실행할 수 없습니다.', '');
    }
    if(!empty($audit['lease_active'])){
        mc_pb_commit($db);
        return array('ok'=>true, 'result'=>'IN_PROGRESS', 'retryAfter'=>$audit['lease_expires_at']);
    }
    if($audit['state'] === 'FAILED_RETRYABLE' && empty($audit['retry_due'])){
        mc_pb_commit($db);
        return array('ok'=>true, 'result'=>'IN_PROGRESS', 'retryAfter'=>$audit['retry_after']);
    }
    if((int)$audit['apply_attempt_count'] >= 4){
        mc_pb_rollback($db);
        return mc_pb_error('FORBIDDEN_STATE', '재시도 횟수를 모두 사용했습니다.', '');
    }
    $leaseOwner = mc_pb_uuid();
    $expectedState = $audit['state'];
    $expectedVersion = (int)$audit['state_version'];
    $sql = "update nfor_point_bank_account_audit set state='PENDING', state_version=state_version+1, "
        ."lease_owner='".mc_pb_q($db, $leaseOwner)."', lease_expires_at=DATE_ADD(NOW(), INTERVAL 90 SECOND), "
        ."retry_after=NULL, apply_attempt_count=apply_attempt_count+1, updated_at=NOW() "
        ."where audit_id='".(int)$audit['audit_id']."' and state='".mc_pb_q($db, $expectedState)."' "
        ."and state_version='".$expectedVersion."' and apply_attempt_count < 4 "
        ."and (state<>'FAILED_RETRYABLE' or retry_after is null or retry_after <= NOW()) "
        ."and (lease_owner is null or lease_expires_at <= NOW())";
    if(!mc_pb_query($db, $sql) || mysqli_affected_rows($db) !== 1){
        mc_pb_rollback($db);
        return array('ok'=>true, 'result'=>'IN_PROGRESS');
    }
    if(!mc_pb_commit($db)) return mc_pb_error('SAVE_FAILED', '수정 작업을 확정할 수 없습니다.', '');
    return array('ok'=>true, 'claimed'=>true, 'leaseOwner'=>$leaseOwner, 'stateVersion'=>$expectedVersion + 1);
}

function mc_pb_source_apply($db, $payload, $config){
    $validated = mc_pb_validate_full_payload($payload);
    if(!$validated['ok']) return $validated;
    $payload = $validated['payload'];
    $claim = mc_pb_claim_apply($db, $payload);
    if(empty($claim['claimed'])) return $claim;

    if(!mc_pb_begin($db)){
        mc_pb_mark_retryable($db, $payload['operationId'], $claim['leaseOwner'], $claim['stateVersion']);
        return mc_pb_error('SAVE_FAILED', '계좌정보 저장을 시작할 수 없습니다.', '');
    }
    $mode = mc_pb_mode_lock($db, false);
    $audit = mc_pb_audit_lock($db, $payload['operationId'], true);
    if(!$mode || !$audit || !in_array($mode['mode'], array('ON','DRAIN')) ||
        $audit['state'] !== 'PENDING' || $audit['lease_owner'] !== $claim['leaseOwner'] ||
        (int)$audit['state_version'] !== (int)$claim['stateVersion']){
        mc_pb_rollback($db);
        return array('ok'=>true, 'result'=>'IN_PROGRESS');
    }
    $row = mc_pb_one($db, "select pb_id, pb_step, pb_name, pb_bank, pb_bank_number, pb_row_revision from nfor_point_bank where pb_id='".(int)$payload['pbId']."' FOR UPDATE");
    if(!$row){
        if(!mc_pb_terminal_conflict($db, $audit, 'NOT_FOUND', null) || !mc_pb_commit($db)){
            mc_pb_rollback($db);
            return mc_pb_error('SAVE_FAILED', '실패 상태를 확정할 수 없습니다.', '');
        }
        return mc_pb_error('NOT_FOUND', '출금 건을 찾을 수 없습니다.', '');
    }
    if(!in_array((int)$row['pb_step'], array(1,2))){
        if(!mc_pb_terminal_conflict($db, $audit, 'FORBIDDEN_STATE', $row) || !mc_pb_commit($db)){
            mc_pb_rollback($db);
            return mc_pb_error('SAVE_FAILED', '실패 상태를 확정할 수 없습니다.', '');
        }
        return mc_pb_error('FORBIDDEN_STATE', '현재 단계에서는 계좌정보를 수정할 수 없습니다.', '');
    }
    if(!mc_pb_verify_revision($config, $payload['expectedRevision'], $payload['pbId'], $row['pb_row_revision'])){
        if(!mc_pb_terminal_conflict($db, $audit, 'STALE_REVISION', $row) || !mc_pb_commit($db)){
            mc_pb_rollback($db);
            return mc_pb_error('SAVE_FAILED', '실패 상태를 확정할 수 없습니다.', '');
        }
        return mc_pb_error('STALE_REVISION', '다른 변경이 반영되었습니다. 목록을 새로고침해주세요.', '');
    }
    if($row['pb_name'] === $payload['name'] && $row['pb_bank'] === $payload['bankName'] && mc_pb_normalize_account($row['pb_bank_number']) === $payload['account']){
        if(!mc_pb_terminal_source($db, $audit, 'FAILED', 'INVALID_INPUT', $row) || !mc_pb_commit($db)){
            mc_pb_rollback($db);
            return mc_pb_error('SAVE_FAILED', '실패 상태를 확정할 수 없습니다.', '');
        }
        return mc_pb_error('INVALID_INPUT', '변경된 내용이 없습니다.', '');
    }

    $update = "update nfor_point_bank set pb_name='".mc_pb_q($db, $payload['name'])."', "
        ."pb_bank='".mc_pb_q($db, $payload['bankName'])."', pb_bank_number='".mc_pb_q($db, $payload['account'])."', "
        ."pb_row_revision=pb_row_revision+1 where pb_id='".(int)$payload['pbId']."' and pb_row_revision='".(int)$row['pb_row_revision']."' and pb_step in ('1','2')";
    if(!mc_pb_query($db, $update) || mysqli_affected_rows($db) !== 1){
        mc_pb_rollback($db);
        mc_pb_mark_retryable($db, $payload['operationId'], $claim['leaseOwner'], $claim['stateVersion']);
        return mc_pb_error('SAVE_FAILED', '계좌정보 저장 중 오류가 발생했습니다.', '');
    }
    $after = mc_pb_one($db, "select pb_id, pb_step, pb_name, pb_bank, pb_bank_number, pb_row_revision from nfor_point_bank where pb_id='".(int)$payload['pbId']."'");
    $revision = mc_pb_revision_token($config['currentSecret'], $config['currentKeyId'], $payload['pbId'], $after['pb_row_revision']);
    $logText = "operationId=".$payload['operationId']
        ." actor=".$payload['actorEmployeeId']." reason=".mc_pb_redact_account_like_text($payload['reason'])
        ." before=".$row['pb_name']."/".$row['pb_bank']."/".mc_pb_mask_account($row['pb_bank_number'])
        ." after=".$after['pb_name']."/".$after['pb_bank']."/".mc_pb_mask_account($after['pb_bank_number']);
    if(!mc_pb_query($db, "insert nfor_log set log_file='point_bank_account_edit', log_text='".mc_pb_q($db, $logText)."', log_datetime=NOW()")){
        mc_pb_rollback($db);
        mc_pb_mark_retryable($db, $payload['operationId'], $claim['leaseOwner'], $claim['stateVersion']);
        return mc_pb_error('SAVE_FAILED', '변경 기록 저장 중 오류가 발생했습니다.', '');
    }
    $terminal = "update nfor_point_bank_account_audit set state='APPLIED', state_version=state_version+1, "
        ."lease_owner=NULL, lease_expires_at=NULL, retry_after=NULL, "
        ."before_name='".mc_pb_q($db, $row['pb_name'])."', before_bank='".mc_pb_q($db, $row['pb_bank'])."', "
        ."before_account_masked='".mc_pb_q($db, mc_pb_mask_account($row['pb_bank_number']))."', "
        ."after_name='".mc_pb_q($db, $after['pb_name'])."', after_bank='".mc_pb_q($db, $after['pb_bank'])."', "
        ."after_account_masked='".mc_pb_q($db, mc_pb_mask_account($after['pb_bank_number']))."', "
        ."result_revision='".mc_pb_q($db, $revision)."', failure_code=NULL, updated_at=NOW(), completed_at=NOW() "
        ."where audit_id='".(int)$audit['audit_id']."' and state='PENDING' and lease_owner='".mc_pb_q($db, $claim['leaseOwner'])."' and state_version='".(int)$claim['stateVersion']."'";
    if(!mc_pb_query($db, $terminal) || mysqli_affected_rows($db) !== 1 || !mc_pb_commit($db)){
        mc_pb_rollback($db);
        mc_pb_mark_retryable($db, $payload['operationId'], $claim['leaseOwner'], $claim['stateVersion']);
        return mc_pb_error('SAVE_FAILED', '계좌정보 확정 중 오류가 발생했습니다.', '');
    }
    return array(
        'ok'=>true,
        'result'=>'UPDATED',
        'operationId'=>$payload['operationId'],
        'hmacKeyId'=>$payload['hmacKeyId'],
        'requestHmac'=>$payload['requestHmac'],
        'id'=>(int)$after['pb_id'],
        'step'=>(int)$after['pb_step'],
        'name'=>$after['pb_name'],
        'bank'=>$after['pb_bank'],
        'account'=>$after['pb_bank_number'],
        'revision'=>$revision
    );
}

function mc_pb_source_cancel($db, $payload){
    $operationId = isset($payload['operationId']) ? (string)$payload['operationId'] : '';
    if(!mc_pb_is_uuid($operationId)) return mc_pb_error('INVALID_INPUT', '요청 식별자가 올바르지 않습니다.', '');
    if(!mc_pb_begin($db)) return mc_pb_error('SAVE_FAILED', '취소를 시작할 수 없습니다.', '');
    $mode = mc_pb_mode_lock($db, false);
    if(!$mode || !in_array($mode['mode'], array('ON','DRAIN'))){ mc_pb_rollback($db); return mc_pb_error('DISABLED', '취소할 수 없는 상태입니다.', ''); }
    $audit = mc_pb_audit_lock($db, $operationId, true);
    if(!$audit){ mc_pb_rollback($db); return mc_pb_error('NOT_FOUND', '예약을 찾을 수 없습니다.', ''); }
    if(!isset($payload['requestHmac']) || !hash_equals((string)$audit['request_hmac'], (string)$payload['requestHmac'])){
        mc_pb_rollback($db);
        return mc_pb_error('OPERATION_CONFLICT', '같은 요청 식별자에 다른 내용이 등록되어 있습니다.', '');
    }
    if($audit['state'] === 'APPLIED'){
        $body = mc_pb_status_body($mode, $audit);
        mc_pb_commit($db);
        return $body;
    }
    if(!in_array($audit['state'], array('PENDING','FAILED_RETRYABLE'))){
        $body = mc_pb_status_body($mode, $audit);
        mc_pb_commit($db);
        return $body;
    }
    if(!empty($audit['lease_active'])){
        mc_pb_commit($db);
        return array('ok'=>true, 'result'=>'IN_PROGRESS', 'retryAfter'=>$audit['lease_expires_at']);
    }
    $sql = "update nfor_point_bank_account_audit set state='CANCELLED', state_version=state_version+1, "
        ."lease_owner=NULL, lease_expires_at=NULL, retry_after=NULL, updated_at=NOW(), completed_at=NOW() "
        ."where audit_id='".(int)$audit['audit_id']."' and state='".mc_pb_q($db, $audit['state'])."' and state_version='".(int)$audit['state_version']."' "
        ."and (lease_owner is null or lease_expires_at <= NOW())";
    if(!mc_pb_query($db, $sql) || mysqli_affected_rows($db) !== 1){ mc_pb_rollback($db); return array('ok'=>true, 'result'=>'IN_PROGRESS'); }
    if(!mc_pb_commit($db)){
        mc_pb_rollback($db);
        return mc_pb_error('SAVE_FAILED', '취소 상태를 확정할 수 없습니다.', '');
    }
    return array('ok'=>true, 'result'=>'CANCELLED', 'operationId'=>$operationId, 'hmacKeyId'=>$audit['hmac_key_id'], 'requestHmac'=>$audit['request_hmac']);
}

function mc_pb_handle_source_action($db, $action, $payload, $config){
    $action = strtoupper((string)$action);
    if(!in_array($action, array('RESERVE','APPLY','STATUS','CANCEL'))) return mc_pb_error('INVALID_INPUT', '지원하지 않는 요청입니다.', '');
    $operationId = isset($payload['operationId']) ? (string)$payload['operationId'] : '';
    if(!mc_pb_is_uuid($operationId)) return mc_pb_error('INVALID_INPUT', '요청 식별자가 올바르지 않습니다.', '');
    $keyId = isset($payload['hmacKeyId']) ? (string)$payload['hmacKeyId'] : '';
    $requestHmac = isset($payload['requestHmac']) ? (string)$payload['requestHmac'] : '';
    $actionHmac = isset($payload['actionHmac']) ? (string)$payload['actionHmac'] : '';
    $secret = mc_pb_secret_for_key($config, $keyId);
    if($secret === '' || !preg_match('/^[0-9a-f]{64}$/', $requestHmac) || !preg_match('/^[0-9a-f]{64}$/', $actionHmac)){
        return mc_pb_error('INVALID_INPUT', '요청 서명을 확인할 수 없습니다.', '');
    }
    $expectedAction = mc_pb_action_hmac($secret, $keyId, $action, $operationId, $requestHmac);
    if(!hash_equals($expectedAction, $actionHmac)) return mc_pb_error('INVALID_INPUT', '요청 서명이 올바르지 않습니다.', '');
    if(in_array($action, array('RESERVE','APPLY'))){
        $validated = mc_pb_validate_full_payload($payload);
        if(!$validated['ok']) return $validated;
        if(!hash_equals(mc_pb_request_hmac($secret, $validated['payload']), $requestHmac)) return mc_pb_error('INVALID_INPUT', '요청 내용이 서명과 다릅니다.', '');
        $payload = $validated['payload'];
    }
    if($action === 'RESERVE') return mc_pb_source_reserve($db, $payload);
    if($action === 'APPLY') return mc_pb_source_apply($db, $payload, $config);
    if($action === 'STATUS') return mc_pb_source_status($db, $payload);
    return mc_pb_source_cancel($db, $payload);
}

function mc_pb_admin_change($db, $payload, $faultInjector=null){
    $pbId = isset($payload['pbId']) ? (int)$payload['pbId'] : 0;
    $expectedStep = isset($payload['expectedStep']) ? (int)$payload['expectedStep'] : 0;
    $expectedRowRevision = isset($payload['expectedRowRevision']) ? (int)$payload['expectedRowRevision'] : -1;
    $name = mc_pb_trim_unicode(isset($payload['name']) ? $payload['name'] : '');
    $bank = mc_pb_trim_unicode(isset($payload['bank']) ? $payload['bank'] : '');
    $account = mc_pb_normalize_account(isset($payload['account']) ? $payload['account'] : '');
    $actorName = mc_pb_trim_unicode(isset($payload['actorName']) ? $payload['actorName'] : '관리자');
    if($pbId < 1 || !in_array($expectedStep, array(1,2))) return mc_pb_error('INVALID_INPUT', '수정할 출금 건이 올바르지 않습니다.', '');
    if($name === '' || mc_pb_codepoint_length($name) > 50) return mc_pb_error('INVALID_INPUT', '예금주를 입력해주세요.', 'name');
    if($bank === '' || mc_pb_codepoint_length($bank) > 30) return mc_pb_error('INVALID_INPUT', '은행을 입력해주세요.', 'bank');
    if($account === '' || strlen($account) > 30) return mc_pb_error('INVALID_INPUT', '계좌번호는 숫자 30자리 이내로 입력해주세요.', 'account');
    if($actorName === '' || mc_pb_codepoint_length($actorName) > 100) return mc_pb_error('INVALID_INPUT', '처리자를 확인할 수 없습니다.', '');
    if($expectedRowRevision < 0) return mc_pb_error('STALE_REVISION', '목록을 새로고침해주세요.', '');

    $operationId = mc_pb_uuid();
    $fingerprint = hash('sha256', 'ADMIN'.mc_pb_encode($operationId).mc_pb_encode((string)$pbId));
    if(!mc_pb_begin($db)) return mc_pb_error('SAVE_FAILED', '수정을 시작할 수 없습니다.', '');

    // mode 값과 무관하게 잠금 장벽만 공유한다. 관리자 화면은 mode OFF에서도 기존대로 동작한다.
    $mode = mc_pb_mode_lock($db, false);
    if(!$mode){
        mc_pb_rollback($db);
        return mc_pb_error('SAVE_FAILED', '계좌 정정 상태를 확인할 수 없습니다.', '');
    }
    $other = mc_pb_one($db, "select audit_id from nfor_point_bank_account_audit where pb_id='".$pbId."' "
        ."and state in ('PENDING','FAILED_RETRYABLE') order by audit_id limit 1 FOR UPDATE");
    if($other){
        mc_pb_rollback($db);
        return mc_pb_error('FORBIDDEN_STATE', '전산에서 계좌정보를 확인 중입니다. 잠시 후 다시 시도해주세요.', '');
    }

    $insert = "insert into nfor_point_bank_account_audit set operation_id='".mc_pb_q($db, $operationId)."', "
        ."pb_id='".$pbId."', state='PENDING', state_version=0, apply_attempt_count=1, hmac_key_id='ADMIN', "
        ."request_hmac='".$fingerprint."', expected_revision='row:".$expectedRowRevision."', actor_employee_id=0, "
        ."actor_name='".mc_pb_q($db, $actorName)."', "
        ."reason='메타체험단 관리자 계좌정보 정정', after_name='".mc_pb_q($db, $name)."', "
        ."after_bank='".mc_pb_q($db, $bank)."', after_account_masked='".mc_pb_q($db, mc_pb_mask_account($account))."', "
        ."created_at=NOW(), updated_at=NOW()";
    if(!mc_pb_query($db, $insert)){
        mc_pb_rollback($db);
        return mc_pb_error('SAVE_FAILED', '변경 기록을 시작할 수 없습니다.', '');
    }
    $auditId = mysqli_insert_id($db);
    if(is_callable($faultInjector)) call_user_func($faultInjector, 'after_audit_insert');

    $row = mc_pb_one($db, "select pb_id, pb_step, pb_name, pb_bank, pb_bank_number, pb_row_revision from nfor_point_bank where pb_id='".$pbId."' FOR UPDATE");
    if(!$row || (int)$row['pb_step'] !== $expectedStep || (int)$row['pb_row_revision'] !== $expectedRowRevision){
        mc_pb_rollback($db);
        return mc_pb_error('STALE_REVISION', '처리 상태나 계좌정보가 변경되었습니다. 목록을 새로고침해주세요.', '');
    }
    if($row['pb_name'] === $name && $row['pb_bank'] === $bank && mc_pb_normalize_account($row['pb_bank_number']) === $account){
        mc_pb_rollback($db);
        return mc_pb_error('INVALID_INPUT', '변경된 내용이 없습니다.', '');
    }
    $sql = "update nfor_point_bank set pb_name='".mc_pb_q($db, $name)."', pb_bank='".mc_pb_q($db, $bank)."', "
        ."pb_bank_number='".mc_pb_q($db, $account)."', pb_row_revision=pb_row_revision+1 "
        ."where pb_id='".$pbId."' and pb_step='".$expectedStep."' and pb_row_revision='".$expectedRowRevision."'";
    if(!mc_pb_query($db, $sql) || mysqli_affected_rows($db) !== 1){
        mc_pb_rollback($db);
        return mc_pb_error('SAVE_FAILED', '계좌정보 저장 중 오류가 발생했습니다.', '');
    }
    $after = mc_pb_one($db, "select pb_name, pb_bank, pb_bank_number, pb_row_revision from nfor_point_bank where pb_id='".$pbId."'");
    $logText = "operationId=".$operationId
        ." actor=".$actorName
        ." reason=메타체험단 관리자 계좌정보 정정"
        ." before=".$row['pb_name']."/".$row['pb_bank']."/".mc_pb_mask_account($row['pb_bank_number'])
        ." after=".$after['pb_name']."/".$after['pb_bank']."/".mc_pb_mask_account($after['pb_bank_number']);
    $terminal = "update nfor_point_bank_account_audit set state='APPLIED', state_version=state_version+1, "
        ."before_name='".mc_pb_q($db, $row['pb_name'])."', before_bank='".mc_pb_q($db, $row['pb_bank'])."', "
        ."before_account_masked='".mc_pb_q($db, mc_pb_mask_account($row['pb_bank_number']))."', "
        ."after_name='".mc_pb_q($db, $after['pb_name'])."', after_bank='".mc_pb_q($db, $after['pb_bank'])."', "
        ."after_account_masked='".mc_pb_q($db, mc_pb_mask_account($after['pb_bank_number']))."', "
        ."result_revision='row:".(int)$after['pb_row_revision']."', updated_at=NOW(), completed_at=NOW() "
        ."where audit_id='".(int)$auditId."' and state='PENDING' and state_version=0 and lease_owner is null";
    if(!mc_pb_query($db, "insert nfor_log set log_file='point_bank_account_edit', log_text='".mc_pb_q($db, $logText)."', log_datetime=NOW()") ||
        !mc_pb_query($db, $terminal) || mysqli_affected_rows($db) !== 1 || !mc_pb_commit($db)){
        mc_pb_rollback($db);
        return mc_pb_error('SAVE_FAILED', '변경 기록 확정 중 오류가 발생했습니다.', '');
    }
    return array('ok'=>true, 'result'=>'UPDATED', 'rowRevision'=>(int)$after['pb_row_revision']);
}
