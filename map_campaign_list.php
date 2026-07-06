<?php
include_once "path.php";

$nfor[title] = "지도보기";

$admin[location_select][""] = "지역검색";
$que = sql_query("select * from nfor_location where lo_use='1' order by lo_rank desc");
while($row = sql_fetch_array($que)){
	$admin[location_select]["$row[lo_lat],$row[lo_lng]"] = $row[lo_name];
}

$que = sql_query("select * from nfor_campaign_category where cg_use='1' and cg_map='1' order by cg_rank desc"); 
while($row = sql_fetch_array($que)){
	$admin[category_id][$row[category_id]] = $row[cg_category];
}

for($i=20; $i<=100; $i=$i+20){
	$admin[km][$i] = "주변 {$i}km 이내";
}

$admin["sort"] = array("3"=>"가까운순","1" => "인기순", "2"=>"최신순", "4"=>"마감임박");

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
		$category_sql .= " cp_category like '%{$exp[$i]}%' ";
	}
	$category_sql .= ")";
}

if($sort=="1"){
	$sql_order = " order by cp_order desc";
} elseif($sort=="2"){
	$sql_order = " order by cp_insert_datetime desc";
} elseif($sort=="3"){
	$sql_order = " order by distance asc";
} elseif($sort=="4"){
	$sql_order = " order by cp_edatetime asc";
} else{
	$sql_order = " order by distance asc";
}

$sql_common = " from nfor_campaign ";

$sql_search = " where cp_asign='2' and cp_use='1' and cp_sdatetime <='$nfor[ymdhis]' and cp_edatetime >='$nfor[ymdhis]' $category_sql HAVING distance <= $km ";

if($menu_config[menu_limit]){
	$total_count = $menu_config[menu_limit];
} else{
	$sql = " select count(*) as cnt, (6371*acos(cos(radians($lat))*cos(radians(cp_lat))*cos(radians(cp_lng) - radians($lng))+sin(radians($lat))*sin(radians(cp_lat)))) AS distance
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
$sql = " select *, (6371*acos(cos(radians($lat))*cos(radians(cp_lat))*cos(radians(cp_lng) - radians($lng))+sin(radians($lat))*sin(radians(cp_lat)))) AS distance
				$sql_common
				$sql_search
				$sql_order
				limit $from_record, $rows ";
$result = sql_query($sql);

for($i=0; $row=sql_fetch_array($result); $i++){
	$res[cp_num] = ($page*$rows) - $rows + $i + 1;
	$res[cp_id] = $row[cp_id];
	$res[cp_subject] = $row[cp_subject];
	$res[cp_description] = $row[cp_description];

	$res[cp_img] = $row[cp_img]?thumbnail("$nfor[path]/data/campaign/$row[cp_img]",300,300,0,1):$nfor[skin_path]."img/no_img.png";


	$res[cp_category] = $admin[category_id][substr($row[cp_category_id],0,4)];


	$res[cp_addr1] = $row[cp_addr1];




	$res[cp_type] = $nfor[cp_type][$row[cp_type]];
	$res[cp_point] = number_format($row[cp_point]);
	$res[cp_media_blog] = $row[cp_media_blog];
	$res[cp_media_instagram] = $row[cp_media_instagram];
	$res[cp_media_youtube] = $row[cp_media_youtube];
	$res[cp_media_shop] = $row[cp_media_shop];
	$res[cp_order] = number_format($row[cp_order]);
	$res[cp_recruit] = number_format($row[cp_recruit]);
	$res[cp_review] = number_format($row[cp_review]);
	if(time() < strtotime($row[cp_edatetime])){
		$time = strtotime($row[cp_edatetime]) - time();
		$cp_day = "D-Day ".ceil($time/86400);
	} else{
		$cp_day = "모집마감";
	}
	$res[cp_day] = $cp_day;


	
	$res[cp_lat] = $row[cp_lat];
	$res[cp_lng] = $row[cp_lng];
	$return["list"][] = $res;

	if($menu_config[menu_limit] and $menu_config[menu_limit] == $res[cp_num]){
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