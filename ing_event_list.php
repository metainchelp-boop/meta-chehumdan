<?php
include_once "path.php";

$nfor[title] = "진행중인이벤트";

if(!$sort) $sort = "ev_id desc";

$sql_common = " from nfor_event ";

$sql_search = " where ev_use='1' ";

if($period=="ing") $sql_search .= " and ev_edatetime >= '$nfor[ymdhis]' ";

if($period=="end") $sql_search .= " and ev_edatetime < '$nfor[ymdhis]' ";

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
	$res[ev_num] = ($page*$rows) - $rows + $i + 1;
	$res[ev_id] = $row[ev_id];	
	$res[ev_img] = $nfor[url].substr(thumbnail("$nfor[path]/data/event/$row[ev_img]",600,"",0,1),1);	
	if($row[ev_sdatetime] > $nfor[ymdhis]){
		$res[ev_state] = "com";
		$res[ev_state_text] = "진행예정";
	} elseif($row[ev_sdatetime] <= $nfor[ymdhis] and $row[ev_edatetime] >= $nfor[ymdhis]){
		$res[ev_state] = "ing";
		$res[ev_state_text] = "진행중";
	} else{
		$res[ev_state] = "end";
		$res[ev_state_text] = "진행완료";
	}
	$res[ev_subject] = $row[ev_subject];
	$res[ev_sdatetime] = date("Y.m.d.",strtotime($row[ev_sdatetime]));
	$res[ev_edatetime] = date("Y.m.d.",strtotime($row[ev_edatetime]));


	$res[ev_insert_datetime] = date("Y.m.d.",strtotime($row[ev_insert_datetime]));

	


	$res[ev_hit] = $row[ev_hit];
	$return["list"][] = $res;

	if($menu_config[menu_limit] and $menu_config[menu_limit] == $res[ev_num]){
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