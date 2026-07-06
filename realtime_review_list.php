<?php
$jwt_check = "1";

include_once "path.php";


// 배너
$bn_code = "realtime_review_list";
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






$nfor[title] = "실시간 리뷰";

if(!$sort) $sort = "rv_confirm_datetime desc";

$sql_common = " from nfor_review a left join nfor_campaign b on ( a.rv_cp_id = b.cp_id ) ";

$sql_search = " where a.rv_step='4' and a.rv_asign_show='1' ";

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
	$res[rv_num] = ($page*$rows) - $rows + $i + 1;
	$res[rv_id] = $row[rv_id];

	$res[rv_url] = $row[rv_url];
	$res[rv_mb_nick] = $row[rv_mb_nick]?$row[rv_mb_nick]:$row[rv_mb_id];
	$res[cp_img] = $row[cp_img]?thumbnail("$nfor[path]/data/campaign/$row[cp_img]",300,300,0,1):$nfor[skin_path]."img/no_img.png";
	$res[rv_img] = $row[rv_img]?thumbnail("$nfor[path]/data/review/$row[rv_img]",300,300,0,1):$res[cp_img];
	if(!$res[rv_img]) $res[rv_img] = $nfor[skin_path]."img/no_img.png";
	$res[rv_review] = $row[rv_review];
	$res[cp_id] = $row[rv_cp_id];
	if($row[cp_id]){
		$res[cp_subject] = $row[cp_subject];
		$res[cp_description] = $row[cp_description];
	} else{
		$res[cp_subject] = "삭제된 캠페인입니다";
		$res[cp_description] = "삭제된 캠페인입니다";
	}
	$res[cp_type] = $nfor[cp_type][$row[cp_type]];
	$res[cp_point] = number_format($row[cp_point]);

	$res[rv_media] = $row[rv_media];
	$res[rv_media_text] = $nfor[cp_media][$row[rv_media]];

	$res[cp_order] = number_format($row[cp_order]);
	$res[cp_recruit] = number_format($row[cp_recruit]);
	$res[cp_review] = number_format($row[cp_review]);
	if(time() < strtotime($row[cp_edatetime])){
		$time = strtotime($row[cp_edatetime]) - time();
		$cp_day = "D-Day ".ceil($time/86400);
	} else{
		$cp_day = "";
	}
	$res[cp_day] = $cp_day;

	$return["list"][] = $res;

	if($menu_config[menu_limit] and $menu_config[menu_limit] == $res[rv_num]){
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




$return["best_review_list"] = array();

if($is_mobile){
	$result = sql_query("select * from nfor_review a left join nfor_campaign b on ( a.rv_cp_id = b.cp_id ) where a.rv_step='4' and a.rv_asign_show='1' order by a.rv_best desc limit 6");
} else{
	$result = sql_query("select * from nfor_review a left join nfor_campaign b on ( a.rv_cp_id = b.cp_id ) where a.rv_step='4' and a.rv_asign_show='1' order by a.rv_best desc limit 6");
}

for($i=0; $row=sql_fetch_array($result); $i++){
	$res[rv_id] = $row[rv_id];

	$res[rv_url] = $row[rv_url];
	$res[rv_mb_nick] = $row[rv_mb_nick]?$row[rv_mb_nick]:$row[rv_mb_id];
	$res[cp_img] = $row[cp_img]?thumbnail("$nfor[path]/data/campaign/$row[cp_img]",300,300,0,1):$nfor[skin_path]."img/no_img.png";
	$res[rv_img] = $row[rv_img]?thumbnail("$nfor[path]/data/review/$row[rv_img]",300,300,0,1):$res[cp_img];
	if(!$res[rv_img]) $res[rv_img] = $nfor[skin_path]."img/no_img.png";
	$res[rv_review] = $row[rv_review];
	$res[cp_id] = $row[rv_cp_id];
	if($row[cp_id]){
		$res[cp_subject] = $row[cp_subject];
		$res[cp_description] = $row[cp_description];
	} else{
		$res[cp_subject] = "삭제된 캠페인입니다";
		$res[cp_description] = "삭제된 캠페인입니다";
	}
	$res[cp_type] = $nfor[cp_type][$row[cp_type]];
	$res[cp_point] = number_format($row[cp_point]);

	$res[rv_media] = $row[rv_media];
	$res[rv_media_text] = $nfor[cp_media][$row[rv_media]];

	$res[cp_order] = number_format($row[cp_order]);
	$res[cp_recruit] = number_format($row[cp_recruit]);
	$res[cp_review] = number_format($row[cp_review]);
	if(time() < strtotime($row[cp_edatetime])){
		$time = strtotime($row[cp_edatetime]) - time();
		$cp_day = "D-Day ".ceil($time/86400);
	} else{
		$cp_day = "";
	}
	$res[cp_day] = $cp_day;

	$return["best_review_list"][] = $res;
}


if($json=="list"){	
	json_return($nfor[title],"ok");
}

$scroll_load = $menu_config[menu_scroll];

if(!$scroll_load) $pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");







include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>