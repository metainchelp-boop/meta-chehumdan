<?php
include_once "path.php";

if($config[cf_intro_type]=="1"){ // 제한없음
	$filename = "intro_basic.php";
} elseif($config[cf_intro_type]=="2"){ // 접속불가
	$filename = "intro_basic.php";
} elseif($config[cf_intro_type]=="3"){ // 회원만가능
	$filename = "intro_member.php";
} elseif($config[cf_intro_type]=="4"){ // 성인만가능
	$filename = "intro_adult.php";
} else{

}

$page = sql_fetch("select * from nfor_page where pg_code='intro'");

$nfor[title] = "인트로";

include_once $nfor[skin_path].basename($filename);
?>