<?php
include_once "path.php";

$nfor[title] = "받은쪽지함";

$note = sql_fetch("select * from nfor_note where no_id='$no_id'");

if($note[no_receive_id] <> $member[mb_id]) $return[error_msg] = "잘못된 접근입니다";

if($mode=="delete"){
	if($return[error_msg]) json_return($return[error_msg], "error");

	sql_query("update nfor_note set no_receive_del='1' where no_id='$note[no_id]'");
	$return[url] = "note_receive_list.php";
	json_return("삭제되었습니다", "ok");
}

if($note[no_receive_datetime]=="0000-00-00 00:00:00"){
	sql_query("update nfor_note set no_receive_datetime=NOW() where no_id='$note[no_id]'");

	$mb_note = sql_fetch("select count(*) as cnt from nfor_note where no_receive_id='$member[mb_id]' and no_receive_datetime='0000-00-00 00:00:00' and no_receive_del='0'");
	sql_query("update nfor_member set mb_note='$mb_note[cnt]' where mb_id='$member[mb_id]'");
}

if($json=="view"){
	$return = $note;
	json_return($nfor[title],"ok");
}

if($return[error_msg]) alert($return[error_msg]);

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>