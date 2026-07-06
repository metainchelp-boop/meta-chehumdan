<?php
$jwt_check = "1";

include_once "path.php";

$write = sql_fetch("select * from nfor_member where mb_no='$member[mb_no]'");

/* 각페이지에서 개별지정 */
$mb_admin = $write[mb_admin]; // 회원형태(일반회원)
$mb_level = $write[mb_level]; // 회원레벨
$mb_asign = $write[mb_asign]; // 가입시승인상태
$mb_valid_date = $write[mb_valid_date]; // 가입시승인상태





if(!$member_config[mb_id_use]){
	$mb_id = $write[mb_id];
} else{
	$mb_id = $write[mb_id]; // readonly 정보는 수정못하게 설정
}
if(!$member_config[mb_name_use]){ 
	$mb_name = $write[mb_name]; 
} else{
	$mb_name = $write[mb_name]; // readonly 정보는 수정못하게 설정
}

if(!$member_config[mb_hp_use]) $mb_hp = $write[mb_hp];
if(!$member_config[mb_tel_use]) $mb_tel = $write[mb_tel];
if(!$member_config[mb_email_use]) $mb_email = $write[mb_email];

if(!$member_config[mb_birthday_type_use]) $mb_birthday_type = $write[mb_birthday_type];
if(!$member_config[mb_birthday_use]) $mb_birthday = $write[mb_birthday];


if(!$member_config[mb_sex_use]) $mb_sex = $write[mb_sex];
if(!$member_config[mb_nick_use]) $mb_nick = $write[mb_nick];
if(!$member_config[mb_address_use]){
	$mb_zipcode = $write[mb_zipcode];
	$mb_addr1 = $write[mb_addr1];
	$mb_addr2 = $write[mb_addr2];
}

if(!$member_config[mb_mailling_use]) $mb_mailling = $write[mb_mailling];
if(!$member_config[mb_sms_use]) $mb_sms = $write[mb_sms];

/* 환불계좌는 회원정보 수정시에 무조건 수정되므로 주석처리함
$mb_bank_name = $write[mb_bank_name];
$mb_bank_account = $write[mb_bank_account];
*/



if(!$member_config[mb_cp_name_use]) $mb_cp_name = $write[mb_cp_name];
if(!$member_config[mb_cp_ceo_use]) $mb_cp_ceo = $write[mb_cp_ceo];
if(!$member_config[mb_cp_number_use]) $mb_cp_number = $write[mb_cp_number];

if(!$member_config[mb_cp_address_use]){
	$mb_cp_zipcode = $write[mb_cp_zipcode];
	$mb_cp_addr1 = $write[mb_cp_addr1];
	$mb_cp_addr2 = $write[mb_cp_addr2];
}

if(!$member_config[mb_cp_type1_use]) $mb_cp_type1 = $write[mb_cp_type1];
if(!$member_config[mb_cp_type2_use]) $mb_cp_type2 = $write[mb_cp_type2];




$mb_black = $write[mb_black];
$mb_memo = $write[mb_memo];
$mb_friend = $write[mb_friend];

$mb_cp_bank_name = $write[mb_cp_bank_name]; 
$mb_cp_bank_account = $write[mb_cp_bank_account]; 
$mb_cp_bank_account_holder = $write[mb_cp_bank_account_holder]; 

$mb_access = $write[mb_access];


$mb_no = $member[mb_no];
/* 각페이지에서 개별지정 */




















include_once $nfor[path]."/inc_member_check.php";

if(!$member[mb_no]){
	goto_url("login.php?url=member_form.php");
	exit;
} 

if(!$_SESSION[password_expiry_date] or $_SESSION[password_expiry_date] < $nfor[ymdhis]){
	goto_url("member_confirm.php");
	exit;
}

$write = $member;

$write[mb_password] = "";

$nfor[title] = "회원정보수정";

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>