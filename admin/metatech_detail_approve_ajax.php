<?
/* 메타테크 검수 승인/반려 (관리자) — 2026-06-16
   approve = 검수대기(status0) 참여건에 포인트 지급 + 승인(status1)
   reject  = 포인트 미지급, 반려(status2) */
include_once "path.php";
include_once $nfor[path]."/lib/campaign.lib.php";
include_once $nfor[path]."/lib/z_function.lib.php";

$uidx = (int)$_REQUEST['uidx'];
$act  = preg_replace('/[^a-z]/','',$_REQUEST['act']);

$ok = false;
if($uidx > 0){
	if($act == "approve")      $ok = metatech_approve_user($uidx);
	else if($act == "reject")  $ok = metatech_reject_user($uidx);
}

echo $ok ? "1" : "0";
?>
