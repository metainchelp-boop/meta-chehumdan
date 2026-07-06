<?php
$jwt_check = "1";

include_once "path.php";

$nfor[title] = "마이페이지";

if($member[mb_no]){
	$return[mb_id] = $member[$member_config[cf_mb_id_type]];
	$return[mb_email] = $member[mb_email];
	$return[mb_login_datetime] = date("Y.m.d. H:i",strtotime($member[mb_login_datetime]));
}

if($json=="list"){	
	json_return($nfor[title],"ok");
}
if(!$member[mb_no]) goto_url("login.php?url=".basename($_SERVER[PHP_SELF]));

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>