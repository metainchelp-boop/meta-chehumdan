<?php
include_once "path.php";

$nfor[title] = "패스워드찾기";

if($mode=="find_password_hp"){

	$mb_name = trim($_POST[mb_name]);
	$mb_hp = trim($_POST[mb_hp]);

	if(!$mb_name) json_return("이름을 입력해주세요","mb_name");
	
	if(preg_match("/[^0-9]+/i", $mb_hp)) json_return("숫자만입력해주세요","mb_hp");

	if(strlen($mb_hp) < 10) json_return("휴대폰번호를 올바르게 입력해주세요","mb_hp");

	$mb_hp = add_hyphen($mb_hp);

	$mb = sql_fetch("select mb_id, mb_no, mb_hp from nfor_member where mb_name='$mb_name' and mb_hp='$mb_hp'");
	
	if(!$mb[mb_no]) json_return("입력하신 정보와 일치하는 아이디가 존재하지 않습니다","mb_hp");

	$nfor[tmp_password] = rand("1111","9999");
	$mb_password = sql_password($mb[$member_config[cf_mb_id_type]], $nfor[tmp_password]);
	sql_query("update nfor_member set mb_password='$mb_password' where mb_no='$mb[mb_no]'");

	nfor_send("find_password","",$mb[mb_hp],$mb[mb_no]);

	json_return("고객님의 $mb[mb_hp] 휴대폰으로 임시 패스워드를 전송하였습니다","ok");
	exit;
}

if($mode=="find_password_email"){

	$mb_name = trim($_POST[mb_name]);
	$mb_email = trim($_POST[mb_email]);
	
	if(!$mb_name) json_return("이름을 입력해주세요","mb_name");
	
	if(!$mb_email) json_return("이메일 주소를 입력해주세요","mb_email");

	if(!preg_match("/([0-9a-zA-Z_-]+)@([0-9a-zA-Z_-]+)\.([0-9a-zA-Z_-]+)/", $mb_email)) json_return("이메일 주소가 형식에 맞지 않습니다","mb_email");

	$mb = sql_fetch("select mb_id, mb_no, mb_email from nfor_member where mb_name='$mb_name' and mb_email='$mb_email'");

	if(!$mb[mb_no]) json_return("입력하신 정보와 일치하는 아이디가 존재하지 않습니다","mb_email");

	$nfor[tmp_password] = rand("1111","9999");
	$mb_password = sql_password($mb[$member_config[cf_mb_id_type]], $nfor[tmp_password]);
	sql_query("update nfor_member set mb_password='$mb_password' where mb_no='$mb[mb_no]'");

	nfor_send("find_password",$mb[mb_email],"",$mb[mb_no]);

	json_return("고객님의 $mb[mb_email] 메일로 임시 패스워드를 전송하였습니다","ok");
	exit;
}

//if($member[mb_no]) goto_url($nfor[path]);

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>