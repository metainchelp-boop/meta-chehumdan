<?php
include_once "path.php";

$sql_common = " from nfor_review ";
$sql_search = " where rv_cp_id='$cp_id' ";
$sql_order = " order by rv_id desc ";

$row = sql_fetch("select count(*) as cnt $sql_common $sql_search");
$total_count = $row[cnt];

$rows = $menu_config[menu_rows];
$total_page  = ceil($total_count / $rows);
if(!$page) $page = 1;
$from_record = ($page - 1) * $rows;

$result = sql_query("select * $sql_common $sql_search $sql_order limit $from_record, $rows");
for($i=0; $row=sql_fetch_array($result); $i++){
	$res = array();
	$res[rv_num] = $total_count - ($page - 1) * $rows - $i;
	$res[rv_id] = $row[rv_id];
	$res[rv_mb_nick] = $row[rv_mb_nick]?$row[rv_mb_nick]:$row[rv_mb_id];
	$res[rv_msg] = $row[rv_msg];
	$mb = sql_fetch("select mb_photo from nfor_member where mb_no='$row[rv_mb_no]'");
	$res[rv_mb_photo] = $mb[mb_photo]?thumbnail($nfor[path]."/data/member/mb/".$mb[mb_photo],60,60,0,1):"";
	$res[rv_access] = (($member[mb_no] and $row[rv_mb_no]==$member[mb_no]) or $is_admin)?"1":"0";
	$res[rv_datetime] = date("Y.m.d.",strtotime($row[rv_datetime]));
	$return["order_list"][] = $res;
}

if($return["last_page"] or $rows<>$i){
	$return["last_page"] = 1; // 마지막페이지 체크
} else{
	$return["last_page"] = 0;
}

$return["rows"] = $rows; // 한페이지글수
$return["total_count"] = $total_count; // 전체글수
$return["count"] = $i; // 현제불러오는글수
$return["page"] = $page;

if($json=="list") json_return("신청자한마디","ok");

include_once $nfor[skin_path]."campaign_order_list.php";
?>