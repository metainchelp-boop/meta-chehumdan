<?php
$jwt_check = "1";

include_once "path.php";

if($mode=="update"){

	if(!$member[mb_no]) json_return("회원만 접근가능합니다","login");

	if($member[mb_access]) json_return("접근이 금지된 아이디입니다","mb_access");
		
	if(!$mb_agree) json_return("약관에 동의해주세요","mb_agree");

	if(!sns_id_check($member)){
		if(!$mb_password) json_return("패스워드를 입력해주세요","mb_password");
		if(!($mb_password && $member[mb_password] == sql_password($member[$member_config[cf_mb_id_type]], $mb_password))) json_return("입력하신 패스워드가 일치하지 않습니다","mb_password");
	}
	
	if($is_admin) json_return("관리자는 이용할수 없습니다","is_admin");

	member_leave($member[mb_no], $mb_secession);

	logout();

	$return[url] = "index.php";

	json_return("정상적으로 탈퇴신청 하셨습니다", "ok");
}

$nfor[title] = "회원탈퇴";

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>