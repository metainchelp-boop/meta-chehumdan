<?php

$admin[keyword_type] = array(""=>"전체","co_cp_id" => "캠페인코드", "co_cp_subject" => "캠페인명", "co_mb_id" => "아이디", "co_mb_name" => "이름", "co_mb_nick" => "닉네임", "co_mb_hp" => "휴대폰", "co_mb_email" => "이메일");



$admin[period_type] = array("co_datetime" => "주문일자", "co_pay_datetime" => "결제일자");

if(basename($PHP_SELF)=="campaign_order_wait_list.php"){
	$admin[period_type][co_bank_expire] = "입금기한";
}
if(basename($PHP_SELF)=="campaign_order_cancel_list.php" or basename($PHP_SELF)=="campaign_order_wait_cancel_list.php"){
	$admin[period_type][co_cancel_datetime] = "취소일자";
}




$admin[co_cp_type] = $nfor[cp_type];
$admin[co_payment_type] = array(""=>"전체", "banking"=>"무통장입금", "card" => "신용카드", "iche"=>"계좌이체", "vbanking"=>"가상계좌");  
$admin[co_cp_reward_type] = array("전체","제품/서비스","포인트","포인트 + 제품/서비스");
$admin[co_pay_step] = array(""=>"전체", "1"=>"결제완료", "3" => "결제취소", "4" => "입금대기","5" => "입금전취소", "7" => "부분취소");  


$list = $_SERVER[PHP_SELF];
$form = str_replace("list","form",$list);
$table = "nfor_campaign_order";
$id = "co_id";


$sql_common = " from $table ";

?>