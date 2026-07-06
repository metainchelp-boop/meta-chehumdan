<?php
include_once "path.php";


if($cp_id) $cp_id = str_number($cp_id);


if($mode=="delete"){

	$rv_id = str_number($rv_id);

	if(!$member[mb_no]) json_return("로그인하셔야 이용가능합니다","login");

	$chk_review = sql_fetch("select * from nfor_review where rv_id='$rv_id'");
	if($chk_review[rv_step]<>"1") json_return("리뷰어 선정 이후에는 취소할수 없습니다","asign");
	if(!$rv_id or !$chk_review[rv_mb_no]) json_return("잘못된 접근입니다 잠시후 다시 이용해주세요","not");

	if($chk_review[rv_mb_no]==$member[mb_no] or $is_admin){
		review_delete($rv_id);
		json_return("신청이 취소되었습니다","ok");
	} else{
		json_return("잘못된 접근입니다 잠시후 다시 이용해주세요","not");
	}
}

if(!$campaign[cp_id]) $campaign = sql_fetch("select * from nfor_campaign where cp_id='$cp_id'");

if($mode=="insert"){
	// 보안패치
	foreach($_POST as $key => $value){
		if(!is_array($_POST[$key])) $$key = htmlspecialchars(strip_tags($value));
	}

	if(time() > strtotime($campaign[cp_edatetime])) json_return("모집 종료된 캠페인입니다");
	
	$rv_is_mobile = $is_mobile?"2":"1";

	if(!$member[mb_no]) json_return("로그인하셔야 이용가능합니다");
	if(!$campaign[cp_id]) json_return("캠페인이 존재하지 않습니다");
	if($member[mb_stop_date] and strtotime($member[mb_stop_date])>time()) json_return("이용정지 상태입니다 ".date("Y년 m월 d일",strtotime($member[mb_stop_date]))." 이후 부터 캠페인이 참여가 가능합니다");

	if($campaign[cp_buyer]=="2"){
		$cp_buyer_level = explode("||",$campaign[cp_buyer_level]);
		if(!in_array($member[mb_level], $cp_buyer_level)) {
			json_return("캠페인에 지정된 레벨 권한을 가진 사람만 참여 가능합니다");
		}
	}

	$chk_review = sql_fetch("select * from nfor_review where rv_mb_no='$member[mb_no]' and rv_cp_id='$campaign[cp_id]'");
	if($chk_review[rv_id]) json_return("이미 신청하신 캠페인입니다");

	if(!$rv_channel) json_return("리뷰에 사용할 채널을 선택해주세요","rv_channel");
	if(!$rv_msg) json_return("신청자 한마디를 입력해주세요","rv_msg");
	if(!$rv_memo) json_return("신청자 필수정보를 입력해주세요","rv_memo");

	if($campaign[cp_type]=="1"){
		if(!$rv_dy_zip) json_return("배송지 우편번호를 입력해주세요","rv_dy_zip");
		if(!$rv_dy_addr1) json_return("배송지 주소를 입력해주세요","rv_dy_addr1");
		if(!$rv_dy_addr2) json_return("배송지 상세주소를 입력해주세요","rv_dy_addr2");
		if(!$rv_dy_name) json_return("받는분 이름을 입력해주세요","rv_dy_name");
		if(!$rv_dy_hp) json_return("배송지 연락처를 입력해주세요","rv_dy_hp");
	}

	if(!$rv_agree1) json_return("이용약관에 동의하셔야 진행가능합니다","rv_agree1");
	if(!$rv_agree2) json_return("이용약관에 동의하셔야 진행가능합니다","rv_agree2");
	if(!$rv_agree3) json_return("이용약관에 동의하셔야 진행가능합니다","rv_agree3");
	if(!$rv_agree4) json_return("이용약관에 동의하셔야 진행가능합니다","rv_agree4");
	if(!$rv_agree5) json_return("이용약관에 동의하셔야 진행가능합니다","rv_agree5");
	if(!$rv_agree6) json_return("이용약관에 동의하셔야 진행가능합니다","rv_agree6");

	$rv_mb_age = substr(sprintf("%02d",date("Y") - substr($member[mb_birthday],0,4)),0,1)."0";

	$mb_addr1 = explode(" ",$member[mb_addr1]);
	$rv_mb_area = $mb_addr1[0];
	$rv_mb_area_detail = $mb_addr1[1];

	$rv_cp_subject = addslashes($campaign[cp_subject]);

	$rv_media = media_type($rv_channel);

	// 유입경로 기록 (세션 기반, 기존 로직 불변·추가만) 2026
	$rv_in_rv_id=''; $rv_in_mb_no=''; $rv_in_media=''; $rv_in_src='';
	$_inflow = preg_replace('/[^0-9]/','', $_SESSION["mc_inflow_".$campaign[cp_id]]);
	if($_inflow){
		$_inref = sql_fetch("select rv_mb_no, rv_media from nfor_review where rv_id='$_inflow'");
		if($_inref[rv_mb_no] && $_inref[rv_mb_no] != $member[mb_no]){
			$rv_in_rv_id = $_inflow;
			$rv_in_mb_no = $_inref[rv_mb_no];
			$rv_in_media = $_inref[rv_media];
			$rv_in_src   = $_SESSION["mc_insrc_".$campaign[cp_id]] ? $_SESSION["mc_insrc_".$campaign[cp_id]] : 'pixel';
		}
	}

	sql_query("insert nfor_review set rv_md_no='$campaign[cp_md_no]', rv_cp_contents_edatetime='$campaign[cp_contents_edatetime]', rv_media='$rv_media', rv_cp_subject='$rv_cp_subject', rv_mb_area='$rv_mb_area', rv_mb_area_detail='$rv_mb_area_detail', rv_mb_sex='$member[mb_sex]', rv_mb_age='$rv_mb_age', rv_mb_email='$member[mb_email]', rv_mb_black='$member[mb_black]', rv_mb_nick='$member[mb_nick]', rv_mb_id='$member[mb_id]', rv_mb_name='$member[mb_name]', rv_mb_hp='$member[mb_hp]', rv_is_mobile='$rv_is_mobile', rv_supply_no='$campaign[cp_supply_no]', rv_mb_no='$member[mb_no]', rv_cp_id='$campaign[cp_id]', rv_step='1', rv_msg='$rv_msg', rv_memo='$rv_memo', rv_channel='$rv_channel', rv_cp_type='$campaign[cp_type]', rv_dy_name='$rv_dy_name', rv_dy_zip='$rv_dy_zip', rv_dy_addr1='$rv_dy_addr1', rv_dy_addr2='$rv_dy_addr2', rv_dy_hp='$rv_dy_hp', rv_datetime=NOW(), rv_mb_level='$member[mb_level]', rv_in_rv_id='$rv_in_rv_id', rv_in_mb_no='$rv_in_mb_no', rv_in_media='$rv_in_media', rv_in_src='$rv_in_src'");

	campaign_count($campaign[cp_id]);
	
	//$return[back] = "1";

	$return[url] = "review_wait_list.php";

	json_return("리뷰신청이 완료되었습니다","ok");
}

if(!$member[mb_no]) alert("로그인하셔야 이용가능합니다","login.php?url=review_order.php?cp_id=$cp_id");

if(time() > strtotime($campaign[cp_edatetime])) alert("모집 종료된 캠페인입니다");

$campaign[cp_img_url] = $nfor[url].substr(thumbnail("$nfor[path]/data/campaign/$campaign[cp_img]",300,300,0,1),1);
$campaign[cp_img] = thumbnail("$nfor[path]/data/campaign/$campaign[cp_img]",300,300,0,1);
$campaign[cp_type_text] = $nfor[cp_type][$campaign[cp_type]];
$campaign[cp_point] = number_format($campaign[cp_point]);
$campaign[cp_order] = number_format($campaign[cp_order]);
$campaign[cp_recruit] = number_format($campaign[cp_recruit]);
$campaign[cp_review] = number_format($campaign[cp_review]);
if(time() < strtotime($campaign[cp_edatetime])){
	$time = strtotime($campaign[cp_edatetime]) - time();
	$cp_day = "D-Day ".ceil($time/86400);
} else{
	$cp_day = "";
}
$campaign[cp_day] = $cp_day;
$campaign[cp_reward] = nl2br($campaign[cp_reward]);
$campaign[cp_keyword] = nl2br($campaign[cp_keyword]);
$campaign[cp_guide] = nl2br($campaign[cp_guide]);
$campaign[cp_notice] = nl2br($campaign[cp_notice]);
$campaign[cp_work] = nl2br($campaign[cp_work]);
$campaign[cp_order_text] = $campaign[cp_order]?"(".number_format($campaign[cp_order]).")":"";
$campaign[cp_review_text] = $campaign[cp_review]?"(".number_format($campaign[cp_review]).")":"";
$campaign[cp_url] = $nfor[url]."/campaign.php?cp_id=".$campaign[cp_id];


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
if(!$campaign[cp_media_shop] and !$cp_media) alert("마이페이지 > 회원정보수정 메뉴에서 운영채널($cp_media_text)주소를 등록해주세요","member_form.php");










$last_dy = sql_fetch("select * from nfor_review where rv_mb_no='$member[mb_no]' and rv_cp_type='1' order by rv_id desc");


$admin[dy_adress_type] = array("1"=>"새로운주소", "2"=>"최근발송주소", "3"=>"회원정보주소");

$admin[rv_channel][""] = "선택";
if($member[mb_blog] and $campaign[cp_media_blog]) $admin[rv_channel][blog_make_url($member[mb_blog])] = "블로그 - ".blog_make_url($member[mb_blog]);
if($member[mb_instagram] and $campaign[cp_media_instagram]) $admin[rv_channel][instagram_make_url($member[mb_instagram])] = "인스타그램 - ".instagram_make_url($member[mb_instagram]);
if($member[mb_youtube] and $campaign[cp_media_youtube]) $admin[rv_channel][youtube_make_url($member[mb_youtube])] = "유튜브 - ".youtube_make_url($member[mb_youtube]);

$write[rv_dy_hp] = str_number($write[rv_dy_hp]);
$last_dy[rv_dy_hp] = str_number($last_dy[rv_dy_hp]);


$nfor[title] = "리뷰어신청하기";

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>