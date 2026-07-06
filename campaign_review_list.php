<?php
include_once "path.php";

$sql_common = " from nfor_review ";
$sql_search = " where rv_cp_id='$cp_id' and rv_step='4' and rv_asign_show='1' ";
$sql_order = " order by rv_reg_datetime desc ";

$row = sql_fetch("select count(*) as cnt $sql_common $sql_search");
$total_count = $row[cnt];

$rows = $menu_config[menu_rows];
$total_page  = ceil($total_count / $rows);
if(!$page) $page = 1;
$from_record = ($page - 1) * $rows;

$result = sql_query("select * $sql_common $sql_search $sql_order limit $from_record, $rows");
// 회원사진 N+1 제거: 리뷰 행 버퍼링 후 회원사진 1쿼리(IN) 선로드
$__rows = array(); $__mbnos = array();
while($row = sql_fetch_array($result)){
	$__rows[] = $row;
	if($row[rv_mb_no]) $__mbnos[$row[rv_mb_no]] = 1;
}
$__photo = array();
if(count($__mbnos)){
	$__pq = sql_query("select mb_no, mb_photo from nfor_member where mb_no in ('".implode("','", array_keys($__mbnos))."')");
	while($__p = sql_fetch_array($__pq)) $__photo[$__p[mb_no]] = $__p[mb_photo];
}
for($i=0; $i<count($__rows); $i++){
	$row = $__rows[$i];
	$res = array();
	$res[rv_num] = $total_count - ($page - 1) * $rows - $i;
	$res[rv_id] = $row[rv_id];
	$res[rv_mb_nick] = $row[rv_mb_nick]?$row[rv_mb_nick]:$row[rv_mb_id];
	$res[rv_review] = $row[rv_review];
	$res[rv_url] = $row[rv_url];
	$__mbphoto = $__photo[$row[rv_mb_no]];
	$res[rv_mb_photo] = $__mbphoto?thumbnail($nfor[path]."/data/member/mb/".$__mbphoto,60,60,0,1):"";
	$res[rv_datetime] = date("Y.m.d.",strtotime($row[rv_datetime]));
	$return["review_list"][] = $res;
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

if($json=="list") json_return("리뷰","ok");

include_once $nfor[skin_path]."campaign_review_list.php";
?>