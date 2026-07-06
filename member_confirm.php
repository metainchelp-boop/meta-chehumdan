<?php
$jwt_check = "1";

include_once "path.php";

$nfor[title] = "회원정보확인";

if($mode=="insert" or $mode=="update"){
	if(!$member[mb_no]) json_return("로그인하셔야 이용가능합니다","login");
	if(!$mb_password) json_return("비밀번호를 입력해주세요","mb_password");
	if(sql_password($member[mb_id], $mb_password) != $member[mb_password]) json_return("로그인 패스워드와 입력하신 패스워드가 일치하지 않습니다","mb_password");
	$_SESSION[password_expiry_date] = date("Y-m-d H:i:s",strtotime("+1 hours"));
	$return[url] = "member_form.php";
	json_return("비밀번호가 일치합니다","ok");
}

if($nfor[test]){
	$write[mb_password] = $nfor[test_password];
} else{
	$write[mb_password] = "";
}

if($json=="form"){
	$return["value"] = $write;
	json_return($nfor[title], "ok");
}

if(!$member[mb_no]) goto_url("login.php?url=".basename($_SERVER[PHP_SELF]));

if(sns_id_check($member)){
	$_SESSION[password_expiry_date] = date("Y-m-d H:i:s",strtotime("+1 hours"));
	goto_url("member_form.php");
}

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>