<?php
include_once "path.php";

$nfor[title] = "쪽지보내기";

if($mode=="insert"){
	if(!$no_receive_id) alert("받는회원 아이디를 입력해주세요");
	if(!$no_memo) alert("내용을 입력해주세요");

	sql_query("insert nfor_note set no_send_id='$member[mb_id]', no_receive_id='$no_receive_id', no_memo='$no_memo', no_send_datetime=NOW()");

	$mb_note = sql_fetch("select count(*) as cnt from nfor_note where no_receive_id='$no_receive_id' and no_receive_datetime='0000-00-00 00:00:00' and no_receive_del='0'");
	sql_query("update nfor_member set mb_note='$mb_note[cnt]' where mb_id='$no_receive_id'");

	alert_close("쪽지를 발송하였습니다");
	exit;
}

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>