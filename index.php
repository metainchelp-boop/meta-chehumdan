<?php
include_once "path.php";

$nfor[title] = "홈";

$is_index = "1";

// 배너
$bn_code = "pc_main_demo2||index_slider1||index_slider2||index_slider3||index_slider4||index_slider5";
$bn_code_exp = explode("||",$bn_code);
for($e=0; $e<count($bn_code_exp); $e++){

	$result = sql_query("select * from nfor_banner where bn_use='1' and bn_code='{$bn_code_exp[$e]}' and ((bn_period_use='1') or (bn_period_use='2' and bn_sdatetime <='$nfor[ymdhis]' and bn_edatetime >='$nfor[ymdhis]')) order by bn_rank desc");
	for($i=0; $row=sql_fetch_array($result); $i++){
		$res = array();
		$res[bn_alt] = $row[bn_alt];
		$res[bn_img] = $nfor[url]."/data/banner/".$row[bn_img];	
		$res[bn_img_over] = $nfor[url]."/data/banner/".$row[bn_img_over];	

		


		$res[bn_href] = $row[bn_href];

		$res[bn_target] = $row[bn_target];

		
		$return[banner][$bn_code_exp[$e]]["list"][] = $res;
	}
	$return[banner][$bn_code_exp[$e]]["total_count"] = $i;

}

if($is_mobile){
	$limit = "6";
} else{
	$limit = "6";
}

// 찜 N+1 제거: 회원의 찜 캠페인을 1쿼리로 선로드 (각 목록 루프에서 행별 count 쿼리 대체)
$my_zzim = array();
if($member[mb_no]){
	$__zq = sql_query("select zz_cp_id from nfor_campaign_zzim where zz_mb_no='$member[mb_no]'");
	while($__z = sql_fetch_array($__zq)) $my_zzim[$__z[zz_cp_id]] = 1;
}

// 추천캠페인
$return["hit_campaign_list"] = array();
$result = sql_query("select * from nfor_campaign where cp_sdatetime <='$nfor[ymdhis]' and cp_edatetime >='$nfor[ymdhis]' and cp_use='1' and cp_asign='2' order by cp_rank1 desc limit 10");
for($i=0; $row=sql_fetch_array($result); $i++){
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

	$return["hit_campaign_list"][] = $res;
}



// 새로운캠페인
$return["new_campaign_list"] = array();
$result = sql_query("select * from nfor_campaign where cp_sdatetime <='$nfor[ymdhis]' and cp_edatetime >='$nfor[ymdhis]' and cp_use='1' and cp_asign='2' order by cp_id desc limit 24");
for($i=0; $row=sql_fetch_array($result); $i++){
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

	$return["new_campaign_list"][] = $res;
}



// 실시간 리뷰
$return["realtime_review_list"] = array();
$result = sql_query("select * from nfor_review a left join nfor_campaign b on ( a.rv_cp_id = b.cp_id ) where a.rv_step='4' and a.rv_asign_show='1' and b.cp_id<>'' order by a.rv_confirm_datetime desc limit 5");
for($i=0; $row=sql_fetch_array($result); $i++){
	$res[rv_id] = $row[rv_id];

	$res[rv_url] = $row[rv_url];
	$res[rv_mb_nick] = $row[rv_mb_nick]?$row[rv_mb_nick]:$row[rv_mb_id];

	$res[cp_img] = $row[cp_img]?thumbnail("$nfor[path]/data/campaign/$row[cp_img]",300,300,0,1):$nfor[skin_path]."img/no_img.png";
	$res[rv_img] = $row[rv_img]?thumbnail("$nfor[path]/data/review/$row[rv_img]",300,300,0,1):$res[cp_img];



	$res[rv_review] = $row[rv_review];

	$res[cp_id] = $row[cp_id];
	$res[cp_subject] = $row[cp_subject];
	$res[cp_description] = $row[cp_description];
	$res[cp_type] = $nfor[cp_type][$row[cp_type]];
	$res[cp_point] = number_format($row[cp_point]);
	$res[rv_media] = $row[rv_media];
	$res[rv_media_text] = $nfor[cp_media][$row[rv_media]];
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

	$return["realtime_review_list"][] = $res;
}


// 마감임박 캠페인
$return["end_campaign_list"] = array();
$result = sql_query("select * from nfor_campaign where cp_sdatetime <='$nfor[ymdhis]' and cp_edatetime >='$nfor[ymdhis]' and cp_use='1' and cp_asign='2' order by cp_edatetime asc limit 24");
for($i=0; $row=sql_fetch_array($result); $i++){
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

	$return["end_campaign_list"][] = $res;
}


if($json=="list"){	
	json_return($nfor[title],"ok");
}


include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>