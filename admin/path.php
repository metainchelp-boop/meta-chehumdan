<?php	// 경로설정
$path = "..";
include_once("$path/nfor.php");

$access_file = basename($_SERVER['PHP_SELF']);

$menu_code = sql_fetch("select * from nfor_access a, nfor_access_group b where a.gp_id=b.gp_id and a.access_file='$access_file'");

if(!$menu_code['access_id']){
	$menu_code = sql_fetch("select * from nfor_access where access_file='$access_file'");
}

$access_code = $menu_code['access_code'];

if(!$member['mb_admin'] and $_SERVER['PHP_SELF']<>"/admin/login.php"){
	goto_url("/admin/login.php");
} else{
	if($member['mb_admin'] < $menu_code['access_level']){
		alert("접근권한이 없습니다");
	}
}

// 관리자 접속 IP 제한
if($config['cf_admin_ip_use']=="1" and $config['cf_admin_ip']<>"null" and !in_array($_SERVER['REMOTE_ADDR'], json_decode($config['cf_admin_ip']))) die("접근이 차단된 아이피입니다 $_SERVER[REMOTE_ADDR]");





// 보안패치
if($_SERVER['PHP_SELF'] <> "/admin/".basename($_SERVER['PHP_SELF'])) alert("접근권한이 없습니다");
if(substr_count($_SERVER['PHP_SELF'], '/') > 2) alert("접근권한이 없습니다");

$access_file2 = basename($_SERVER['SCRIPT_NAME']);

if($access_file2<>"login.php"){
	if(!$member['mb_no']) alert("접근권한이 없습니다");
	if(!$member['mb_admin']) alert("접근권한이 없습니다");

	$menu_code2 = sql_fetch("select * from nfor_access where access_file='$access_file2'");
	if($menu_code2['access_id']){
		if($member['mb_admin'] < $menu_code2['access_level']){
			alert("접근권한이 없습니다");
		}
	}

}

if(substr(str_replace(".","",$_SERVER['SERVER_ADDR']),-3)<>"383") exit;