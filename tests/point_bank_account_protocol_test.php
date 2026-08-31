<?php
require_once dirname(__DIR__).'/lib/mc_point_bank_account.lib.php';

function expect_same($expected, $actual, $message){
    if($expected !== $actual){
        fwrite(STDERR, "FAIL: ".$message."\nexpected=".$expected."\nactual=".$actual."\n");
        exit(1);
    }
}

$secret = 'pointbank-test-secret';
$request = array(
    'hmacKeyId' => 'pointbank-test',
    'operationId' => '11111111-1111-4111-8111-111111111111',
    'pbId' => 123,
    'expectedRevision' => 'v1.pointbank-test.0123456789abcdef',
    'name' => '김메타',
    'bankCode' => '004',
    'bankName' => '국민은행',
    'account' => '001-234 567890',
    'reason' => '통장 사본 확인: 오기입 정정',
    'actorEmployeeId' => 1,
    'actorName' => '대표'
);

expect_same('001234567890', mc_pb_normalize_account($request['account']), '계좌번호 구분선을 제거하고 선행 0을 보존해야 합니다');
expect_same('통장 [계좌번호 마스킹] 확인', mc_pb_redact_account_like_text('통장 001-234 567890 확인'), '변경 사유의 계좌번호 유사 문자열을 감사 전에 가려야 합니다');
expect_same(
    '2d549c5b407a514b0d115f9da68dada504202ed45eaf2ede5a93ed6da74d4550',
    mc_pb_request_hmac($secret, $request),
    'Java/PHP request HMAC 골든 벡터가 같아야 합니다'
);

$requestHmac = mc_pb_request_hmac($secret, $request);
$actions = array(
    'RESERVE' => '73ebe6310145890ae660a370835a629c529c7fb728a4d4dd86af02d600efd1ce',
    'APPLY' => '3816dc1b98f6ca9eb07c1eccfa063b16e54c3254afe90b6a2319874fc171210f',
    'STATUS' => '83ea511e1f7c0dd01b4c0e842c368a969b9eae4913478a80809a952a04ffd7c7',
    'CANCEL' => '1659593e0bcf961bcc770bd0c85e23e86c8235f9cba30100e388896332a1f5b0'
);
foreach($actions as $action => $expected){
    expect_same($expected, mc_pb_action_hmac($secret, $request['hmacKeyId'], $action, $request['operationId'], $requestHmac), $action.' HMAC 골든 벡터가 같아야 합니다');
}

expect_same(
    'v1.pointbank-test.4521ef1952b9bcf416d5db1d9a03c5fb95e0c48c3d6df4e0346954af7420bca2',
    mc_pb_revision_token($secret, 'pointbank-test', 123, 7),
    '불투명 revision 골든 벡터가 같아야 합니다'
);

expect_same('004', mc_pb_bank_by_code('004')['code'], '국민은행 코드가 왕복되어야 합니다');
expect_same('011', mc_pb_bank_by_code('011')['code'], 'NH농협은행은 011이어야 합니다');
expect_same('012', mc_pb_bank_by_code('012')['code'], '지역농축협은 012이어야 합니다');

$seen = array();
foreach(mc_pb_bank_options() as $bank){
    if(isset($seen[$bank['code']])){
        fwrite(STDERR, "FAIL: 은행코드가 중복되었습니다.\n");
        exit(1);
    }
    $seen[$bank['code']] = true;
    expect_same($bank['name'], mc_pb_bank_by_code($bank['code'])['name'], '모든 정식 은행 선택지가 왕복되어야 합니다');
}

$liveConfig = array(
    'token'=>str_repeat('t', 32),
    'currentKeyId'=>'current-test',
    'currentSecret'=>str_repeat('c', 32),
    'previousKeyId'=>'previous-test',
    'previousSecret'=>str_repeat('p', 32)
);
if(!mc_pb_protocol_self_test($liveConfig)){
    fwrite(STDERR, "FAIL: 서버 기동 프로토콜 self-test가 통과하지 않습니다.\n");
    exit(1);
}
$previousRevision = mc_pb_revision_token($liveConfig['previousSecret'], $liveConfig['previousKeyId'], 9, 3);
if(!mc_pb_verify_revision($liveConfig, $previousRevision, 9, 3)){
    fwrite(STDERR, "FAIL: previous key revision을 검증하지 못합니다.\n");
    exit(1);
}

$boundary = $request;
$boundary['expectedRevision'] = mc_pb_revision_token($secret, $request['hmacKeyId'], $request['pbId'], 0);
$boundary['reason'] = str_repeat('한', 500);
$validated = mc_pb_validate_full_payload($boundary);
if(empty($validated['ok'])){
    fwrite(STDERR, "FAIL: 한글 변경 사유 500 code point가 거절되었습니다.\n");
    exit(1);
}
$boundary['reason'] = str_repeat('한', 501);
$validated = mc_pb_validate_full_payload($boundary);
if(!empty($validated['ok']) || !isset($validated['field']) || $validated['field'] !== 'reason'){
    fwrite(STDERR, "FAIL: 한글 변경 사유 501 code point가 허용되었습니다.\n");
    exit(1);
}
$boundary = $request;
$boundary['expectedRevision'] = mc_pb_revision_token($secret, $request['hmacKeyId'], $request['pbId'], 0);
$boundary['name'] = str_repeat('김', 50);
$boundary['actorName'] = str_repeat('담', 100);
if(empty(mc_pb_validate_full_payload($boundary)['ok'])){
    fwrite(STDERR, "FAIL: 예금주 50자·처리자명 100자 경계가 거절되었습니다.\n");
    exit(1);
}
$boundary['name'] .= '김';
if(!empty(mc_pb_validate_full_payload($boundary)['ok'])){
    fwrite(STDERR, "FAIL: 예금주 51 code point가 허용되었습니다.\n");
    exit(1);
}
$boundary['name'] = str_repeat('김', 50);
$boundary['actorName'] .= '담';
if(!empty(mc_pb_validate_full_payload($boundary)['ok'])){
    fwrite(STDERR, "FAIL: 처리자명 101 code point가 허용되었습니다.\n");
    exit(1);
}

echo "PASS: 포인트 출금 계좌 정정 프로토콜\n";
