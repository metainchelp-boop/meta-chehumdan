<?php
include_once "path.php";

if($mode=="next"){
	if(!$member[mb_no]) json_return("로그인하셔야 이용가능합니다","mb");
	sql_query("update nfor_member set mb_password_next_datetime=NOW() where mb_no='$member[mb_no]'");
	json_return("비밀번호를 다음번에 변경합니다","ok");
	exit;
}

if($mode=="change"){


	$password_now_check = json_decode(nfor_check("mb_password_now",$mb_password_now, $member[mb_no]),true);
	if($password_now_check[result] != "ok") json_return($password_now_check[msg],$password_now_check[result]);
	
	$password_check = json_decode(nfor_check("mb_password",$mb_password, $member[mb_no]),true);
	if($password_check[result] != "ok") json_return($password_check[msg],"mb_password");
	
	$password_confirm_check = json_decode(nfor_check("mb_password_confirm",$mb_password,$member[mb_no]),true);
	if($password_confirm_check[result] != "ok") json_return($password_confirm_check[msg],"mb_password_confirm");


	if(!$mb_password) json_return("새 비밀번호를 입력해주세요","mb_password");
	if(!$mb_password_confirm) json_return("새 비밀번호를 한번더 입력해주세요","mb_password_confirm");

 
 	if($mb_password_confirm <> $mb_password) json_return("입력하신 두 비밀번호가 일치하지 않습니다 다시 확인해주세요","mb_password_confirm");

	$mb_password = sql_password($member_config[cf_mb_id_type]=="mb_email"?$member[mb_email]:$member[mb_id], $mb_password);

	sql_query("update nfor_member set mb_password_change_datetime = NOW(), mb_password='$mb_password' where mb_no='$member[mb_no]'");

	json_return("비밀번호를 변경하였습니다","ok");
	exit;
}

$nfor[title] = "비밀번호 변경안내";

include_once $nfor[skin_path].basename($filename);
?>