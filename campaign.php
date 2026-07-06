<?php	// 캠페인상세
include_once "path.php";

$cp_id = str_number($cp_id);

// 유입경로 기록용 — 추적링크 ?ref=<리뷰글id> 로 들어오면 세션에 저장 2026
if($_GET[ref]){ $_SESSION["mc_inflow_".$cp_id] = preg_replace('/[^0-9]/','',$_GET[ref]); $_SESSION["mc_insrc_".$cp_id] = "ref"; }

$campaign = sql_fetch("select * from nfor_campaign where cp_id='$cp_id'");

if(!$campaign[cp_id]) $return[item_error_msg] = "해당 캠페인이 존재하지 않습니다";

if(!$member[mb_admin]){
	if($campaign[cp_use]=="2") $return[item_error_msg] = "미노출 캠페인입니다";
	if($campaign[cp_asign]<>"2") $return[item_error_msg] = "승인된 캠페인만 접근 가능합니다";

	if($campaign[cp_buyer]=="2" and !$member[mb_no]) $return[item_error_msg] = "회원만 참여가능한 캠페인입니다";
	if($campaign[cp_buyer]=="2"){
		$cp_buyer_level = explode("||",$campaign[cp_buyer_level]);
		if(!in_array($member[mb_level], $cp_buyer_level)) {
			$return[item_error_msg] = "캠페인에 지정된 레벨 권한을 가진 사람만 참여 가능합니다";
		}
	}
}

if($member[mb_no]){
	$zzim = sql_fetch("select zz_id from nfor_campaign_zzim where zz_cp_id='$cp_id' and zz_mb_no='$member[mb_no]'");
	if($zzim[zz_id]){
		$campaign[cp_zzim_is] = "1";
	}
}

if($mode=="zzim"){
	if(!$campaign[cp_id] and !$campaign[cp_zzim_is]){
		json_return("삭제되거나 올바르지 않은 캠페인입니다","cp_id");
	} elseif(!$member[mb_no]){
		json_return("로그인하셔야 이용가능합니다","mb_no");
	} else{
		if($zzim[zz_id]){
			sql_query("delete from nfor_campaign_zzim where zz_id='$zzim[zz_id]' and zz_mb_no='$member[mb_no]'");
		} else{
			sql_query("insert nfor_campaign_zzim set zz_cp_id='$campaign[cp_id]', zz_mb_no='$member[mb_no]', zz_insert_datetime=NOW()");
		}
		$cp_zzim = sql_fetch("select count(*) as cnt from nfor_campaign_zzim where zz_cp_id='$campaign[cp_id]'");
		sql_query("update nfor_campaign set cp_zzim='$cp_zzim[cnt]' where cp_id='$campaign[cp_id]'");
		$return[zzim_cnt] = number_format($cp_zzim[cnt]);

		$return[mb_no] = $member[mb_no];

		if($zzim[zz_id]){
			json_return("이미 찜하기로 등록하신 캠페인입니다","delete");
		} else{
			json_return("찜하기로 등록하였습니다","insert");
		}
	}
}

if(!$_SESSION["click_item_{$campaign[cp_id]}"]){
	sql_query(" update nfor_campaign set cp_click = cp_click + 1 where cp_id = '$campaign[cp_id]' ");
	$_SESSION["click_item_{$campaign[cp_id]}"] = TRUE;
}

// 접근금지
if($return[item_error_msg]) alert($return[item_error_msg]);

/* 최근본캠페인 저장 - 패치적용 미사용
if($member[mb_no]){
	$view_sql = "cv_mb_no='$member[mb_no]'";
} else{
	$cv_guest_no = $_SESSION[guest_no];
	$view_sql = "cv_guest_no='$cv_guest_no'";
}
$chk_campaign_view = sql_fetch("select * from nfor_campaign_view where $view_sql and cv_cp_id='$campaign[cp_id]'");
if($chk_campaign_view[cv_cp_id]){
	sql_query("update nfor_campaign_view set cv_datetime=NOW() where $view_sql and cv_cp_id='$campaign[cp_id]' ");
} else{
	sql_query("insert nfor_campaign_view set $view_sql, cv_cp_id='$campaign[cp_id]', cv_datetime=NOW()");
	$view_count = sql_fetch("select count(*) as cnt from nfor_campaign_view where $view_sql");
	if($view_count[cnt] >= $config[cf_campaign_recent_max]){
		$delete_count = $view_count[cnt] - $config[cf_campaign_recent_max];
		sql_query("delete from nfor_campaign_view where $view_sql order by cv_datetime asc limit $delete_count");
	}
}
 최근본캠페인 저장*/

$supply = sql_fetch("select * from nfor_member where mb_no='$campaign[supply_no]'");





$error = array();

if(!$member[mb_no]){
	$error[msg] = "로그인 후 이용해주세요";
	$error[url] = "login.php";
} else{
	$cp_media = 0;
	$cp_media_text = "";
	if($campaign[cp_media_blog]){
		if($member[mb_blog]) $cp_media++;
		$cp_media_text .= "블로그";
	}	
	if($campaign[cp_media_instagram]){
		if($cp_media_text) $cp_media_text .= "/";
		if($member[mb_instagram]) $cp_media++;
		$cp_media_text .= "인스타그램";
	}
	if($campaign[cp_media_youtube]){
		if($cp_media_text) $cp_media_text .= "/";
		if($member[mb_youtube]) $cp_media++;
		$cp_media_text .= "유튜브";
	}

	if(!$campaign[cp_media_shop] and !$cp_media){
		$error[msg] = "마이페이지 > 회원정보수정 메뉴에서 운영채널($cp_media_text)주소를 등록해주세요";
		$error[url] = "member_form.php";
	}

	$chk_review = sql_fetch("select * from nfor_review where rv_mb_no='$member[mb_no]' and rv_cp_id='$campaign[cp_id]'");
	if($chk_review[rv_id]){
		$error[msg] = "이미 신청하신 캠페인입니다";
	}

	if(time() > strtotime($campaign[cp_edatetime])){
		$error[msg] = "모집 종료된 캠페인입니다";
	}

}





$campaign[cp_img_url] = $nfor[url].substr(thumbnail("$nfor[path]/data/campaign/$campaign[cp_img]",300,300,0,1),1);
$campaign[cp_img] = thumbnail("$nfor[path]/data/campaign/$campaign[cp_img]",300,300,0,1);
$campaign[cp_type_text] = $nfor[cp_type][$campaign[cp_type]];
$campaign[cp_point] = number_format($campaign[cp_point]);
$campaign[cp_media] = $campaign[cp_media];
$campaign[cp_order] = number_format($campaign[cp_order]);
$campaign[cp_recruit] = number_format($campaign[cp_recruit]);
$campaign[cp_review] = number_format($campaign[cp_review]);
$campaign[cp_media_text] = $nfor[cp_media][$campaign[cp_media]];
if(time() < strtotime($campaign[cp_edatetime])){
	$time = strtotime($campaign[cp_edatetime]) - time();
	$cp_day = "D-Day ".ceil($time/86400);
} else{
	$cp_day = "";
}
$campaign[cp_day] = $cp_day;
$campaign[cp_reward] = ($campaign[cp_reward]);
$campaign[cp_keyword] = nl2br($campaign[cp_keyword]);

$campaign[cp_hashtag] = nl2br($campaign[cp_hashtag]);


$campaign[cp_guide] = ($campaign[cp_guide]);
$campaign[cp_notice] = ($campaign[cp_notice]);
$campaign[cp_work] = ($campaign[cp_work]);
$campaign[cp_order_text] = $campaign[cp_order]?"(".number_format($campaign[cp_order]).")":"";
$campaign[cp_review_text] = $campaign[cp_review]?"(".number_format($campaign[cp_review]).")":"";
$campaign[cp_url] = $nfor[url]."/campaign.php?cp_id=".$campaign[cp_id];

// 진행자 개인 리뷰마감 연장 표시 (요청:양근형 2026-06-30) — 선정된 본인이고 개인마감이 공통마감보다 뒤면 연장으로 표시
$campaign[my_ext_edatetime] = '';
if($member[mb_no]){
	$myrv = sql_fetch("select rv_cp_contents_edatetime from nfor_review where rv_mb_no='".addslashes($member[mb_no])."' and rv_cp_id='".addslashes($campaign[cp_id])."' and rv_step in ('2','3') and rv_delete='0' order by rv_id desc limit 1");
	if(!empty($myrv[rv_cp_contents_edatetime]) && $myrv[rv_cp_contents_edatetime]!='0000-00-00 00:00:00' && strtotime($myrv[rv_cp_contents_edatetime]) > strtotime($campaign[cp_contents_edatetime])){
		$campaign[my_ext_edatetime] = $myrv[rv_cp_contents_edatetime];
	}
}






$admin[rv_channel][""] = "선택";
if($member[mb_blog] and $campaign[cp_media_blog]) $admin[rv_channel][blog_make_url($member[mb_blog])] = "블로그 - ".blog_make_url($member[mb_blog]);
if($member[mb_instagram] and $campaign[cp_media_instagram]) $admin[rv_channel][instagram_make_url($member[mb_instagram])] = "인스타그램 - ".instagram_make_url($member[mb_instagram]);
if($member[mb_youtube] and $campaign[cp_media_youtube]) $admin[rv_channel][youtube_make_url($member[mb_youtube])] = "유튜브 - ".youtube_make_url($member[mb_youtube]);

$admin[dy_adress_type] = array("1"=>"새로운주소", "2"=>"최근발송주소", "3"=>"회원정보주소");



if($is_mobile){
	$hide = "1";
	//$campaign[cp_memo]=preg_replace("/ zzstyle=([^\"\']+) /"," ",$campaign[cp_memo]);
	//$campaign[cp_memo]=preg_replace("/ style=(\"|\')?([^\"\']+)(\"|\')?/","",$campaign[cp_memo]);
	$campaign[cp_memo] = str_replace('style="width:', 'alt="width:', $campaign[cp_memo]);
}


$result = sql_query("select * from nfor_campaign where cp_sdatetime <='$nfor[ymdhis]' and cp_edatetime >='$nfor[ymdhis]' and cp_use='1' and cp_asign='2' and cp_id<>'$campaign[cp_id]' and cp_category like '%".substr($campaign[cp_category],0,4)."%' order by cp_id asc limit 10");
for($i=0; $row=sql_fetch_array($result); $i++){
	$res[cp_id] = $row[cp_id];
	$res[cp_img] = $row[cp_img]?thumbnail("$nfor[path]/data/campaign/$row[cp_img]",300,300,0,1):$nfor[skin_path]."img/no_img.png";
	$res[cp_subject] = $row[cp_subject];
	$res[cp_description] = $row[cp_description];
	$res[cp_type] = $nfor[cp_type][$row[cp_type]];
	$res[cp_point] = number_format($row[cp_point]);
	$res[cp_media_blog] = $row[cp_media_blog];
	$res[cp_media_instagram] = $row[cp_media_instagram];
	$res[cp_media_youtube] = $row[cp_media_youtube];
	$res[cp_media_shop] = $row[cp_media_shop];
	$res[cp_order] = number_format($row[cp_order]);
	$res[cp_recruit] = number_format($row[cp_recruit]);
	$res[cp_review] = number_format($row[cp_review]);
	$res[cp_media_carrot] = $row[cp_media_carrot];

	if(time() < strtotime($row[cp_edatetime])){
		$time = strtotime($row[cp_edatetime]) - time();
		$cp_day = "D-Day ".ceil($time/86400);
	} else{
		$cp_day = "모집마감";
	}
	$res[cp_day] = $cp_day;

	$return["etc_list"][] = $res;
}





$nfor[title] = "캠페인상세";

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>