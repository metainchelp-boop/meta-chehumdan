<?php
include_once "path.php";

$nfor[title] = "포인트 출금내역";

if(!$sort) $sort = "pb_id desc";

$sql_common = " from nfor_point_bank ";

$sql_search = " where pb_mb_no='$member[mb_no]' ";

$sql_order = " order by ".safe_orderby($sort)." ";

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
	$res[pb_num] = $total_count - ($page - 1) * $rows - $i;
	$res[pb_id] = $row[pb_id];

	$res[pb_name] = $row[pb_name];
	$res[pb_bank] = $row[pb_bank];	
	$res[pb_bank_number] = $row[pb_bank_number];
	$res[pb_point] = number_format($row[pb_point]);	
	$res[pb_step] = pb_step($row[pb_step]);
	$res[pb_send_date] = $row[pb_send_date];
	$res[pb_chage_datetime] = substr($row[pb_chage_datetime],0,10);
	$res[pb_datetime] = substr($row[pb_datetime],0,10);

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