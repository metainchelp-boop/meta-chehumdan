<?php
include_once "path.php";

$nfor[title] = "휴면회원 해제";

if($mode=="info"){
	
	if(!$member_config[cf_asign_mb_hp] and !$member_config[cf_asign_mb_email]) json_return("휴면회원 설정이 올바르지 않습니다\n관리자에게 문의해주세요","config");

	$mb = sql_fetch("select * from nfor_member where mb_no='$mb_no'");

	$add_sql = "";
	if($member_config[cf_asign_mb_hp]){
		if(!$mb_hp) json_return("휴대폰번호를 입력해주세요","mb_hp");
		$mb_hp = add_hyphen($mb_hp);
		if($mb[mb_hp]<>$mb_hp) json_return("휴대폰번호가 일치하지 않습니다","mb_hp");
	}
	if($member_config[cf_asign_mb_email]){
		if(!$mb_email) json_return("이메일을 입력해주세요","mb_email");
		if($mb[mb_email]<>$mb_email) json_return("이메일정보가 일치하지 않습니다","mb_email");
	}

	sql_query("update nfor_member set mb_login_datetime=NOW() where mb_no='$mb[mb_no]'");
	
	$return[url] = "login.php";
	json_return("휴면회원이 해지되었습니다\n다시 로그인해주세요","ok");
}



if($mode=="asign"){
	
	$mb = sql_fetch("select * from nfor_member where mb_no='$mb_no'");
	$nfor[asign_number] = rand("11111","99999");
	
	if($asign_type=="sms"){ 
		sql_query("insert nfor_hp_asign set wr_hp='$mb[mb_hp]', wr_asign='$nfor[asign_number]', wr_datetime=NOW()");
		nfor_send("hp_asign", "", $mb[mb_hp]);
		json_return("인증번호를 휴대폰으로 전송하였습니다","ok");	
	}
	
	if($asign_type=="email"){
		sql_query("insert nfor_hp_asign set wr_email='$mb[mb_email]', wr_asign='$nfor[asign_number]', wr_datetime=NOW()");
		nfor_send("hp_asign", $mb[mb_email], "");
		json_return("인증번호를 이메일로 전송하였습니다","ok");	
	}

}


if($mode=="asign_confirm"){
	if(!$asign_number) json_return("인증 번호를 입력해주세요","asign_number");
	
	$mb = sql_fetch("select * from nfor_member where mb_no='$mb_no'");

	if($asign_type=="sms"){ 
		$chk = sql_fetch("select wr_asign from nfor_hp_asign where wr_hp='$mb[mb_hp]' and wr_asign='$asign_number' order by wr_id desc limit 1");
	}

	if($asign_type=="email"){
		$chk = sql_fetch("select wr_asign from nfor_hp_asign where wr_email='$mb[mb_email]' and wr_asign='$asign_number' order by wr_id desc limit 1");
	}

	if($chk[wr_asign]){
		sql_query("update nfor_member set mb_login_datetime=NOW() where mb_no='$mb[mb_no]'");
		$return[url] = "login.php";
		json_return("인증이 확인되어 휴면회원이 해지되었습니다\n다시 로그인해주세요","ok");	
	} else{
		json_return("인증번호가 일치하지 않습니다","different");
	}
}



$admin[asign_type] = array(""=>"전체","sms" => "휴대폰번호로 인증번호 수신", "email"=>"이메일로 인증번호 수신", "ipin"=>"아이핀 본인인증", "hp"=>"휴대폰 본인인증");


include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>