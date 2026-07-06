<?php
include_once "path.php";

$nfor[title] = "쪽지보내기";

if($mode=="insert"){
	// 보안패치
	foreach($_POST as $key => $value){
		if(!is_array($_POST[$key])) $$key = htmlspecialchars(strip_tags($value));
	}

	if(!$no_receive_id) json_return("받는회원 아이디를 입력해주세요","no_receive_id");
	if(!$no_memo) json_return("내용을 입력해주세요","no_memo");

	sql_query("insert nfor_note set no_send_id='$member[mb_id]', no_receive_id='$no_receive_id', no_memo='$no_memo', no_send_datetime=NOW()");

	$mb_note = sql_fetch("select count(*) as cnt from nfor_note where no_receive_id='$no_receive_id' and no_receive_datetime='0000-00-00 00:00:00' and no_receive_del='0'");
	sql_query("update nfor_member set mb_note='$mb_note[cnt]' where mb_id='$no_receive_id'");

	$return[url] = "note_send_list.php";

	json_return("쪽지를 발송하였습니다", "ok");
}

if($no_receive_id){

	if(is_numeric($no_receive_id)){
		$mb = sql_fetch("select mb_id from nfor_member where mb_no='$no_receive_id'");
		$write[no_receive_id] = $mb[mb_id];
	} else{
		$write[no_receive_id] = $no_receive_id;
	}
}

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>