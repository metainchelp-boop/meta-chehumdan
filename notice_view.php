<?php
include_once "path.php";

$nfor[title] = "공지사항";

$no_id = str_number($no_id);

$notice = sql_fetch("select * from nfor_notice where no_id='$no_id'");

if(!$_SESSION["no_hit_notice_{$no_id}"]){
	sql_query("update nfor_notice set no_hit = no_hit + 1 where no_id = '$no_id'");
	$_SESSION["no_hit_notice_{$no_id}"] = TRUE;
}


if($is_mobile){
	$notice[no_memo] = str_replace('style="width:', 'alt="width:', $notice[no_memo]);
}


$notice[no_insert_datetime] = date("Y.m.d.",strtotime($notice[no_insert_datetime]));

if($json=="view"){
	$return = $notice;
	json_return($nfor[title],"ok");
}

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>