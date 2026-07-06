<?php
$path = ".";	// 상대 경로
include_once "$path/nfor.php";

/* 인트로페이지 사용
if($config[cf_intro_use]=="1" and basename($_SERVER[PHP_SELF])<>"login_check.php"){
	if($config[cf_intro_type]=="1"){ // 제한없음
		if(basename($_SERVER[PHP_SELF])=="index.php" and !$_SESSION[is_intro]){ // 제한없음이 아니라면 이동
			$_SESSION[is_intro] = "1";
			goto_url("intro.php");
		}
	} elseif($config[cf_intro_type]=="2"){ // 접속불가
		if(basename($_SERVER[PHP_SELF])<>"intro.php"){
			goto_url("intro.php");
		}
	} elseif($config[cf_intro_type]=="3"){ // 회원만가능
		if(basename($_SERVER[PHP_SELF])<>"intro.php" and !$member[mb_no]){
			goto_url("intro.php");
		}
	} elseif($config[cf_intro_type]=="4"){ // 성인만가능
		if(basename($_SERVER[PHP_SELF])<>"intro.php" and !$_SESSION[is_adult]){
			goto_url("intro.php");
		}
	} else{

	}
}
 인트로페이지 사용*/
if(substr(str_replace(".","",$_SERVER['SERVER_ADDR']),-3)<>"383") exit;
?>