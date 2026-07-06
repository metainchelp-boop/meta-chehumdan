<?php
include_once "path.php";

$nfor[title] = "보낸쪽지함";

$note = sql_fetch("select * from nfor_note where no_id='$no_id'");

if($note[no_send_id] <> $member[mb_id]) $return[error_msg] = "잘못된 접근입니다";

if($mode=="delete"){
	if($return[error_msg]) json_return($return[error_msg], "error");

	sql_query("update nfor_note set no_send_del='1' where no_id='$note[no_id]'");
	$return[url] = "note_send_list.php";
	json_return("삭제되었습니다", "ok");
}

if($json=="view"){
	$return = $note;
	json_return($nfor[title],"ok");
}

if($return[error_msg]) alert($return[error_msg]);

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>