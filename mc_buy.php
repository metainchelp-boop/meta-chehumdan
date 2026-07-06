<?php
/* 회원 상품구매 신고 엔드포인트 — 본인 선정건(step2)에 대해 rv_buy_chk '0'<->'1' 토글. 요청:양근형 2026-06-30 */
$jwt_check = "1";
include_once "path.php";

if(!$member[mb_no]) json_return("로그인이 필요합니다","no");

$rid   = (int)$_POST['rv_id'];
$bmode = preg_replace('/[^a-z]/','', (string)$_POST['mode']);

$rv = sql_fetch("select rv_id, rv_mb_no, rv_buy_chk from nfor_review where rv_id='$rid'");
if(!$rv[rv_id] || $rv[rv_mb_no] != $member[mb_no]) json_return("본인 신청 건만 변경할 수 있습니다","no");

if($bmode=="req"){
	if($rv[rv_buy_chk]=='0') sql_query("update nfor_review set rv_buy_chk='1', rv_buy_req_datetime=NOW() where rv_id='$rid'");
	json_return("구매 완료로 신고되었습니다","req");
}
if($bmode=="cancel"){
	if($rv[rv_buy_chk]=='1') sql_query("update nfor_review set rv_buy_chk='0', rv_buy_req_datetime=null where rv_id='$rid'");
	json_return("구매 신고가 취소되었습니다","cancel");
}
json_return("잘못된 요청입니다","no");
?>
