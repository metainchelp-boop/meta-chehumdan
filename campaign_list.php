<?php
include_once "path.php";

if($category_id){
	$category_id = str_eng_number($category_id);
	if(strlen($category_id) >= 20) $category_id = "";
}


// 배너
$bn_code = "campaign_list_".substr($category_id,0,4);
$bn_code_exp = explode("||",$bn_code);
for($e=0; $e<count($bn_code_exp); $e++){

	$result = sql_query("select * from nfor_banner where bn_use='1' and bn_code='{$bn_code_exp[$e]}' and ((bn_period_use='1') or (bn_period_use='2' and bn_sdatetime <='$nfor[ymdhis]' and bn_edatetime >='$nfor[ymdhis]')) order by bn_rank desc");
	for($i=0; $row=sql_fetch_array($result); $i++){
		$res = array();
		$res[bn_alt] = $row[bn_alt];
		$res[bn_img] = $nfor[url]."/data/banner/".$row[bn_img];	
		$res[bn_href] = $row[bn_href];
		$res[bn_img_over] = $nfor[url]."/data/banner/".$row[bn_img_over];	
		$res[bn_target] = $row[bn_target];

		
		$return[banner][$bn_code_exp[$e]]["list"][] = $res;
	}
	$return[banner][$bn_code_exp[$e]]["total_count"] = $i;

}



if($mode=="item_list_design"){
	$_SESSION[item_list_design] = $design;
	json_return("디자인변경","ok");
}


$scroll_load = $menu_config[menu_scroll];



$category_id_1 = substr($category_id,0,4);
$category_id_2 = substr($category_id,0,8);
$category_id_3 = substr($category_id,0,12);


if($category_id_1){
	$category = sql_fetch("select * from nfor_campaign_category where category_id='$category_id_1'");
	$cg_depth = strlen($category_id_1)/4;
	$que = sql_query("select * from nfor_campaign_category where cg_use='1' and category_id like '$category_id_1%' and cg_depth='$cg_depth' order by cg_rank asc");
	if(sql_num_rows($que)){
		while($row = sql_fetch_array($que)){
			$admin[category_id_1][$row[category_id]] = $row[cg_category];
		}
	}
}

if(strlen($category_id_2)=="8"){
	$category = sql_fetch("select * from nfor_campaign_category where category_id='$category_id_2'");
	$cg_depth = strlen($category_id_2)/4;
	$que = sql_query("select * from nfor_campaign_category where cg_use='1' and category_id like '$category_id_2%' and cg_depth='$cg_depth' order by cg_rank asc");
	if(sql_num_rows($que)){
		while($row = sql_fetch_array($que)){
			$admin[category_id_2][$row[category_id]] = $row[cg_category];
		}
	}
}


$admin[orderby_text] = array('cp_id'=>'신규오픈','cp_order'=>'인기순','cp_edatetime'=>'마감임박순');
$admin[orderby_sql] = array('cp_id'=>'cp_id desc','cp_order'=>'cp_order desc','cp_edatetime'=>'cp_edatetime asc');


// 네비게이션
for($i=1; $i <= (strlen($category_id)/4); $i++){
	$cate = sql_fetch("select category_id, cg_category from nfor_campaign_category where category_id='".substr($category_id,0,$i*4)."' ");
	$navi[category_id][] = $cate[category_id];
	$navi[cg_category][] = $cate[cg_category];
}

// 분류제목
$navi[category] = $navi[cg_category][(strlen($category_id)/4)-1];


if($orderby){
	if (!array_key_exists($orderby, $admin['orderby_sql'])) {
		$orderby = "";
	}
}

if($cp_media){
	if (!array_key_exists($cp_media, $nfor['cp_media'])) {
		$cp_media = "";
	}
}

if($cate[cg_category]) $nfor[title] = $cate[cg_category];
if(!$nfor[title]) $nfor[title] = "캠페인목록";


if($json=="form"){
	$return["form"] = $admin;
	json_return($nfor[title], "ok");
}

$qstr .= "&category_id=$category_id&orderby=$orderby&cp_media=$cp_media";

$k = 0;
$orderby_text_javascript = "{";
foreach($admin[orderby_text] as $key => $value){
	if($k) $orderby_text_javascript .= ",";
	$orderby_text_javascript .= "\"$key\":\"$value\"";
	$k++;
}
$orderby_text_javascript .= "}";
if(!$orderby) $orderby = "cp_id";

if(!$sort) $sort = $admin[orderby_sql][$orderby];


$sql_common = " from nfor_campaign ";

$sql_search = " where cp_asign='2' and cp_use='1' and cp_sdatetime <='$nfor[ymdhis]' and cp_edatetime >='$nfor[ymdhis]'";

if($category_id){
	$sql_search .= " and cp_category like '%$category_id%' ";
}


if($cp_media){
	if($cp_media=="blog") $sql_search .= " and cp_media_blog = '1' ";
	if($cp_media=="instagram") $sql_search .= " and cp_media_instagram = '1' ";
	if($cp_media=="youtube") $sql_search .= " and cp_media_youtube = '1' ";
	if($cp_media=="shop") $sql_search .= " and cp_media_shop = '1' ";
}

if($keyword) $sql_search .= " and (cp_subject like '%$keyword%' or cp_description like '%$keyword%' or cp_keyword like '%$keyword%' or cp_id like '%$keyword%')";

if($nfor_sql){
	$sql_search .= $nfor_sql;
}

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

if(!$rows) $rows = $menu_config[menu_rows];
$total_page  = ceil($total_count / $rows);
if(!$page) $page = 1;
$from_record = ($page - 1) * $rows;
$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

// 찜 N+1 제거: 회원 찜 캠페인 1쿼리 선로드
$my_zzim = array();
if($member[mb_no]){
	$__zq = sql_query("select zz_cp_id from nfor_campaign_zzim where zz_mb_no='$member[mb_no]'");
	while($__z = sql_fetch_array($__zq)) $my_zzim[$__z[zz_cp_id]] = 1;
}

for($i=0; $row=sql_fetch_array($result); $i++){
	$res[cp_num] = ($page*$rows) - $rows + $i + 1;
	$res[cp_id] = $row[cp_id];

	$res[cp_zzim_is] = isset($my_zzim[$row[cp_id]]) ? 1 : "";

	$res[cp_img] = $row[cp_img]?thumbnail("$nfor[path]/data/campaign/$row[cp_img]",300,300,0,1):$nfor[skin_path]."img/no_img.png";
	$res[cp_subject] = $row[cp_subject];
	$res[cp_description] = $row[cp_description];
	$res[cp_type] = $nfor[cp_type][$row[cp_type]];
	$res[cp_point] = number_format($row[cp_point]);
	$res[cp_media_blog] = $row[cp_media_blog];
	$res[cp_media_instagram] = $row[cp_media_instagram];
	$res[cp_media_youtube] = $row[cp_media_youtube];
	$res[cp_media_shop] = $row[cp_media_shop];
	$res[cp_media_carrot] = $row[cp_media_carrot];



	$res[cp_check1] = $row[cp_check1];
	$res[cp_check2] = $row[cp_check2];
	$res[cp_ohouse] = $row[cp_ohouse];
	$res[cp_coupang] = $row[cp_coupang];



	$res[cp_order] = number_format($row[cp_order]);
	$res[cp_recruit] = number_format($row[cp_recruit]);
	$res[cp_review] = number_format($row[cp_review]);
	if(time() < strtotime($row[cp_edatetime])){
		$time = strtotime($row[cp_edatetime]) - time();
		$cp_day = "".floor($time/86400);
	} else{
		$cp_day = "모집마감";
	}
	$res[cp_day] = $cp_day;

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

$return["rows"] = $rows; // 한페이지글수
$return["total_count"] = $total_count; // 전체글수
$return["count"] = $i; // 현제불러오는글수
$return["page"] = $page;

if($json=="list"){	
	json_return($nfor[title],"ok");
}

if(!$scroll_load) $pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>