<?php
include_once "path.php";

$nfor[title] = "이벤트";

$event = sql_fetch("select * from nfor_event where ev_id='$ev_id'");

if(strtotime($event[ev_edatetime]) < strtotime($nfor[ymdhis])){
	$event[ev_memo] = $event[ev_result];
}

if($event[ev_sdatetime] > $nfor[ymdhis]){
	$event[ev_state] = "com";
	$event[ev_state_text] = "진행예정";
} elseif($event[ev_sdatetime] <= $nfor[ymdhis] and $event[ev_edatetime] >= $nfor[ymdhis]){
	$event[ev_state] = "ing";
	$event[ev_state_text] = "진행중";
} else{
	$event[ev_state] = "end";
	$event[ev_state_text] = "진행완료";
}

$event[ev_sdatetime] = date("Y.m.d.",strtotime($event[ev_sdatetime]));
$event[ev_edatetime] = date("Y.m.d.",strtotime($event[ev_edatetime]));

if(!$_SESSION["ev_hit_event_{$ev_id}"]){
	sql_query("update nfor_event set ev_hit = ev_hit + 1 where ev_id = '$ev_id'");
	$_SESSION["ev_hit_event_{$ev_id}"] = TRUE;
}

if($json=="view"){
	$return = $event;
	json_return($nfor[title],"ok");
}

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>