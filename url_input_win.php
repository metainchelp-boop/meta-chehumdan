<?php
include_once "path.php";

$write = sql_fetch("select * from nfor_review where rv_id='$rv_id'");
$campaign = sql_fetch("select * from nfor_campaign where cp_id='$write[rv_cp_id]'");

if($mode=="update"){

	if(!$member[mb_no]) json_return("로그인하셔야 이용가능합니다","mb_no");
	if($write[rv_mb_no] <> $member[mb_no]) json_return("잘못된 접근입니다","mb_no");

	if(!$rv_url) json_return("리뷰등록한 주소(URL)를 입력해주세요","rv_url");

	//if(!$rv_img) json_return("리뷰등록한 대표이미지를 입력해주세요","rv_img");

	// 페이백 캠페인(cp_check2): 구매자명 + 구매평 캡쳐 필수
	if($campaign[cp_check2]){
		if(!trim($rv_buyer_name)) json_return("구매자명(실제 주문하신 분 성함)을 입력해주세요","rv_buyer_name");
		if(!$rv_buy_img) json_return("구매평 캡쳐를 등록해주세요","rv_buy_img");
	}

	$rv_url = set_http($rv_url);
	sql_query("update nfor_review set rv_img='$rv_img', rv_buyer_name='$rv_buyer_name', rv_buy_img='$rv_buy_img', rv_step='3', rv_review='$rv_review', rv_url='$rv_url', rv_reg_datetime=NOW() where rv_id='$rv_id'");

	campaign_count($write[rv_cp_id]);
		
	if($write[rv_step]=="7"){
		$return[url] = "review_edit_list.php";
	} else{
		$return[url] = "review_post_list.php";
	}

	json_return($write[rv_step]=="2"?"리뷰등록이 완료되었습니다":"리뷰수정이 완료되었습니다","ok");
}

if($write[rv_step]=="2"){
	$nfor[title] = "리뷰등록";
} else{
	$nfor[title] = "리뷰수정";
}

$campaign_url = $nfor[url]."/campaign.php?cp_id=".$campaign[cp_id];
$traffic_url = $nfor[url]."/traffic.php?code=".nfor_encrypt($member[mb_no]."||".$campaign[cp_id]."||".date("Y_W",strtotime($campaign[cp_insert_datetime]))."||".$write[rv_id]);
$write[rv_traffic_code]	= "<a href=\"$campaign_url\" target=\"_blank\"><img src=\"$traffic_url\"></a>";

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>