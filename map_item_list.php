<?php
include_once "path.php";

$nfor[title] = "지도보기";

$admin[location_select][""] = "지역검색";
$que = sql_query("select * from nfor_location where lo_use='1' order by lo_rank desc");
while($row = sql_fetch_array($que)){
	$admin[location_select]["$row[lo_lat],$row[lo_lng]"] = $row[lo_name];
}

$que = sql_query("select * from nfor_item_category where cg_use='1' and cg_map='1' order by cg_rank desc");
while($row = sql_fetch_array($que)){
	$admin[category_id][$row[category_id]] = $row[cg_category];
}

for($i=20; $i<=100; $i=$i+20){
	$admin[km][$i] = "주변 {$i}km 이내";
}

$admin["sort"] = array("3"=>"가까운순","1" => "인기순", "2"=>"최신순", "4"=>"평점순");

if($json=="form"){
	$return["form"] = $admin;
	json_return($nfor[title], "ok");
}

if(!$lat) $lat = "37.566535";
if(!$lng) $lng = "126.97796919999996";
if(!$sort) $sort = "3";
if(!$km) $km = "100";

if($category){
	$category_sql = " and (";
	$exp = explode(",",$category);
	for($i=0; $i<count($exp); $i++){			
		if($i>0){
			$category_sql .= " or ";
		}
		$category_sql .= " it_category like '%{$exp[$i]}%' ";
	}
	$category_sql .= ")";
}

if($sort=="1"){
	$sql_order = " order by it_rank desc";
} elseif($sort=="2"){
	$sql_order = " order by it_datetime desc";
} elseif($sort=="3"){
	$sql_order = " order by distance asc";
} elseif($sort=="4"){
	$sql_order = " order by it_star desc";
} else{
	$sql_order = " order by distance asc";
}

$sql_common = " from nfor_item_location a left join nfor_item b on ( a.lo_it_id = b.it_id ) ";

$sql_search = " where a.lo_use='1' and b.it_asign='2' and ((b.it_shopping='1') or (b.it_shopping='2' and b.it_paydate <='$nfor[ymdhis]' and b.it_payenddate >='$nfor[ymdhis]')) $category_sql HAVING distance <= $km ";

if($menu_config[menu_limit]){
	$total_count = $menu_config[menu_limit];
} else{
	$sql = " select count(*) as cnt, (6371*acos(cos(radians($lat))*cos(radians(lo_lat))*cos(radians(lo_lng) - radians($lng))+sin(radians($lat))*sin(radians(lo_lat)))) AS distance
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
$sql = " select *, (6371*acos(cos(radians($lat))*cos(radians(lo_lat))*cos(radians(lo_lng) - radians($lng))+sin(radians($lat))*sin(radians(lo_lat)))) AS distance
				$sql_common
				$sql_search
				$sql_order
				limit $from_record, $rows ";
$result = sql_query($sql);

for($i=0; $row=sql_fetch_array($result); $i++){
	$res[it_num] = ($page*$rows) - $rows + $i + 1;
	$res[it_id] = $row[it_id];
	$res[it_name] = $row[it_name];
	$res[it_img] = thumbnail("$nfor[path]/data/list/$row[it_img]",300,300,0,1);
	$res[it_price1] = number_format($row[it_price1]);
	$res[it_price2] = number_format($row[it_price2]);	
	$res[it_discount_rate] = $row[it_discount_rate];
	$res[it_description] = $row[it_description];
	$res[it_lat] = $row[lo_lat];
	$res[it_lng] = $row[lo_lng];
	$return["list"][] = $res;

	if($menu_config[menu_limit] and $menu_config[menu_limit] == $res[it_num]){
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

$return["count"] = $i;
$return["page"] = $page;

if($json=="list"){	
	json_return($nfor[title],"ok");
}

$scroll_load = $menu_config[menu_scroll];

if(!$scroll_load) $pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>