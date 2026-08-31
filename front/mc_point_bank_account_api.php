<?php
/* 전산 BE 전용 포인트 출금 지급정보 정정 API. 브라우저 관리자 세션 API가 아니다. */
$path = dirname(__DIR__);
include_once $path.'/nfor.php';
require_once $nfor['path'].'/lib/mc_point_bank_account.lib.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function mc_pb_api_finish($status, $body){
    http_response_code($status);
    echo json_encode($body, JSON_UNESCAPED_UNICODE);
    exit;
}

if(!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'POST'){
    mc_pb_api_finish(405, array('ok'=>false, 'code'=>'METHOD_NOT_ALLOWED', 'message'=>'POST 요청만 허용됩니다.'));
}

$config = mc_pb_source_config();
$providedToken = isset($_SERVER['HTTP_X_METACREW_TOKEN']) ? (string)$_SERVER['HTTP_X_METACREW_TOKEN'] : '';
if(!isset($config['token']) || $config['token'] === '' || $providedToken === '' || !hash_equals($config['token'], $providedToken)){
    mc_pb_api_finish(403, array('ok'=>false, 'code'=>'FORBIDDEN', 'message'=>'인증할 수 없습니다.'));
}

$raw = file_get_contents('php://input');
$payload = json_decode($raw, true);
if(!is_array($payload) || json_last_error() !== JSON_ERROR_NONE){
    mc_pb_api_finish(400, array('ok'=>false, 'code'=>'INVALID_INPUT', 'message'=>'JSON 요청을 확인해주세요.'));
}

$action = isset($payload['action']) ? strtoupper((string)$payload['action']) : '';
if(!in_array($action, array('RESERVE', 'APPLY', 'STATUS', 'CANCEL'))){
    mc_pb_api_finish(400, array('ok'=>false, 'code'=>'INVALID_INPUT', 'message'=>'지원하지 않는 요청입니다.'));
}
if(!mc_pb_protocol_self_test($config) || !mc_pb_schema_ready($connect_db)){
    mc_pb_api_finish(503, array('ok'=>false, 'code'=>'DISABLED', 'message'=>'계좌 정정 기능이 준비되지 않았습니다.'));
}

$result = mc_pb_handle_source_action($connect_db, $action, $payload, $config);
if(!empty($result['ok'])) mc_pb_api_finish(200, $result);

$code = isset($result['code']) ? $result['code'] : 'SAVE_FAILED';
$statusMap = array(
    'INVALID_INPUT'=>400,
    'FORBIDDEN_STATE'=>409,
    'STALE_REVISION'=>409,
    'OPERATION_CONFLICT'=>409,
    'NOT_FOUND'=>404,
    'DISABLED'=>503,
    'SAVE_FAILED'=>500
);
mc_pb_api_finish(isset($statusMap[$code]) ? $statusMap[$code] : 500, $result);
