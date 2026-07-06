<?php
include_once "path.php";

$nfor[title] = "공지사항";

$qstr = "no_category=$no_category";

$admin[no_category][""] = "선택";
$que = sql_query("select * from nfor_value where val_code='notice' order by val_rank asc");
while($row = sql_fetch_array($que)){
	$admin[no_category][$row[val_name]] = $row[val_name];
}

if($json=="form"){
	$return["form"] = $admin;
	$return["value"] = $write;
	json_return($nfor[title], "ok");
}



$sql_common = " from nfor_notice ";

$sql_search = " where no_use='1' ";



$sql_order = " order by no_id desc";

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
if(!$page) $page = 1;
$from_record = ($page - 1) * $rows;
$sql = " select *
				$sql_common
				$sql_search
				$sql_order
				limit $from_record, $rows ";
$result = sql_query($sql);

for($i=0; $row=sql_fetch_array($result); $i++){
	$res = array();
	$res[no_num] = $total_count - ($page - 1) * $rows - $i;
	$res[no_id] = $row[no_id];
	$res[no_category] = $row[no_category];
	$res[no_subject] = $row[no_subject];
	$res[no_hit] = $row[no_hit];	
	$res[no_insert_datetime] = date("Y.m.d.",strtotime($row[no_insert_datetime]));
	$return["list"][] = $res;

	if($menu_config[menu_limit] and $menu_config[menu_limit] == $res[no_num]){
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

$scroll_load = $menu_config[menu_scroll];

if(!$scroll_load) $pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>