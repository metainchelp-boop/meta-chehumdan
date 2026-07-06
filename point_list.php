<?php
$jwt_check = "1";

include_once "path.php";

/*뒤로가기 방지*/
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-reval!!idate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$nfor[title] = "나의 포인트";

$sql_common = " from nfor_point ";

$sql_search = " where pt_mb_no='$member[mb_no]' ";

if(!$sst) {
	$sst = "pt_id";
	$sod = "desc";
}

$sql_order = " order by $sst $sod ";

if($menu_config[menu_limit]){
	$total_count = $menu_config[menu_limit];
} else{
	$sql = " select count(*) as cnt
							$sql_common
							$sql_search
							$sql_order ";
	$row = sql_fetch($sql);
	$total_count = $row[cnt];
}

$rows = $menu_config[menu_rows];
$total_page  = ceil($total_count / $rows);
if (!$page) $page = 1; 
$from_record = ($page - 1) * $rows;
$sql = " select *
				$sql_common
				$sql_search
				$sql_order
				limit $from_record, $rows ";
$result = sql_query($sql);

for($i=0; $row=sql_fetch_array($result); $i++){
	$res[pt_num] = $total_count - ($page - 1) * $rows - $i;
	$res[pt_memo] = $row[pt_memo];
	if($row[pt_point]>0){
		$res[pt_point] = "+".number_format($row[pt_point])."P";
		$res[pt_plus_minus] = "plus";
	} else{
		$res[pt_point] = number_format($row[pt_point])."P";
		$res[pt_plus_minus] = "minus";
	}
	$res[pt_datetime] = date("Y.m.d.",strtotime($row[pt_datetime]));
	$return["list"][] = $res;

	if($menu_config[menu_limit] and $menu_config[menu_limit] == $res[pt_num]){
		$return["last_page"] = 1;
		$i++;
		break;
	}
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

if($json=="list"){
	json_return($nfor[title],"ok");
}

$write[mb_point] = number_format($member[mb_point]);

if($json=="form"){
	$return = array();
	$return["value"] = $write;
	json_return($nfor[title], "ok");
}

$scroll_load = $menu_config[menu_scroll];

if(!$scroll_load) $pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");

if(!$member[mb_no]) goto_url("login.php?url=".basename($_SERVER[PHP_SELF]));

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>