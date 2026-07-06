<?php
include_once "path.php";

$nfor[title] = "자주묻는질문";

$qstr = "fa_category=$fa_category&faq_keyword=$faq_keyword";

if($faq_keyword){
	$write[faq_keyword] = $faq_keyword;
}

$admin[fa_category][""] = "전체";
$que = sql_query("select * from nfor_value where val_code='faq' and val_use='1' order by val_rank asc");
while($row = sql_fetch_array($que)){
	$admin[fa_category][$row[val_name]] = $row[val_name];
}

$que = sql_query("select * from nfor_value where val_code='faq_keyword' and val_use='1' order by val_rank asc");
while($row = sql_fetch_array($que)){
	$admin[fa_keyword][$row[val_name]] = $row[val_name];
}

if($fa_category){
	if (!in_array($fa_category, $admin['fa_category'])) {
		$fa_category = "";
	}
}

if($json=="form"){
	$return["form"] = $admin;
	$return["value"] = $write;
	json_return($nfor[title], "ok");
}



$sql_common = " from nfor_faq ";

$sql_search = " where fa_use='1' ";

if($fa_category) $sql_search .= " and fa_category='$fa_category'";

if($faq_keyword) $sql_search .= " and (fa_subject like '%$faq_keyword%' or fa_memo like '%$faq_keyword%')";

$sql_order = " order by fa_rank desc ";

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
	$res[fa_num] = $total_count - ($page - 1) * $rows - $i;
	$res[fa_id] = $row[fa_id];
	$res[fa_category] = $row[fa_category];
	$res[fa_subject] = $row[fa_subject];
	$res[fa_memo] = nl2br($row[fa_memo]);	
	$return["list"][] = $res;

	if($menu_config[menu_limit] and $menu_config[menu_limit] == $res[fa_num]){
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