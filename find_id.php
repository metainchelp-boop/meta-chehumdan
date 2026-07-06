<?php
include_once "path.php";

$nfor[title] = "아이디찾기";

if($mode=="find_id_hp"){

	$mb_name = trim($_POST[mb_name]);
	$mb_hp = trim($_POST[mb_hp]);

	if(!$mb_name) json_return("이름을 입력해주세요","mb_name");

	if(preg_match("/[^0-9]+/i", $mb_hp)) json_return("숫자만 입력해주세요","mb_hp");

	if(strlen($mb_hp) < 10) json_return("휴대폰번호를 올바르게 입력해주세요","mb_hp");
	
	$mb_hp = add_hyphen($mb_hp);

	$mb = sql_fetch("select mb_no, mb_id, mb_email, mb_name from nfor_member where mb_name='$mb_name' and mb_hp='$mb_hp'");

	if(!$mb[mb_no]) json_return("입력하신 정보와 일치하는 아이디가 존재하지 않습니다","mb_hp");

	$mb_id = $mb[$member_config[cf_mb_id_type]];
	json_return("가입하신 아이디는 $mb_id 입니다","ok");
	exit;
}

if($mode=="find_id_email"){

	$mb_name = trim($_POST[mb_name]);
	$mb_email = trim($_POST[mb_email]);

	if(!$mb_name) json_return("이름을 입력해주세요","mb_name");

	if(!$mb_email) json_return("이메일 주소를 입력해주세요","mb_email");
	
	if(!preg_match("/([0-9a-zA-Z_-]+)@([0-9a-zA-Z_-]+)\.([0-9a-zA-Z_-]+)/", $mb_email)) json_return("이메일 주소가 형식에 맞지 않습니다","mb_email");
	
	$mb = sql_fetch("select mb_no,mb_id,mb_email,mb_name from nfor_member where mb_name='$mb_name' and mb_email='$mb_email'");
	
	if(!$mb[mb_no]) json_return("입력하신 정보와 일치하는 아이디가 존재하지 않습니다","mb_email");


	$mb_id = $mb[$member_config[cf_mb_id_type]];
	json_return("가입하신 아이디는 $mb_id 입니다","ok");
	exit;
}

if($member[mb_no]) goto_url($nfor[path]);

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>