<?php
include_once "path.php";

$nfor[title] = "고객센터";

$admin[fa_category][""] = "전체";
$que = sql_query("select * from nfor_value where val_code='faq' order by val_rank asc");
while($row = sql_fetch_array($que)){
	$admin[fa_category][$row[val_name]] = $row[val_name];
}

$result = sql_query("select * from nfor_value where val_code='faq_keyword' order by val_rank asc limit 5");
for($i=0; $row=sql_fetch_array($result); $i++){
	$res = array();
	$res[val_name] = $row[val_name];
	$return["faq_keyword_list"][] = $res;
}

$result = sql_query("select * from nfor_faq where fa_use='1' order by fa_rank desc limit 5");
for($i=0; $row=sql_fetch_array($result); $i++){
	$res = array();
	$res[fa_id] = $row[fa_id];
	$res[fa_best] = $row[fa_best];
	$res[fa_category] = $row[fa_category];
	$res[fa_subject] = $row[fa_subject];
	$res[fa_memo] = nl2br($row[fa_memo]);	
	$return["faq_list"][] = $res;
}

$result = sql_query("select * from nfor_notice where no_use='1' order by no_id desc limit 5");
for($i=0; $row=sql_fetch_array($result); $i++){
	$res = array();
	$res[no_id] = $row[no_id];
	$res[no_category] = $row[no_category];
	$res[no_subject] = $row[no_subject];
	$res[no_hit] = $row[no_hit];	
	$res[no_insert_datetime] = date("Y.m.d.",strtotime($row[no_insert_datetime]));
	$return["notice_list"][] = $res;
}

if($json=="list"){	
	json_return($nfor[title],"ok");
}

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>