<?php
include_once "path.php";


/* 각페이지에서 개별지정 */
$mb_admin = $mb_type=="company"?"1":"0"; // 회원형태(일반회원)
$mb_level = $member_config['cf_mb_level']; // 회원레벨
$mb_asign = $member_config['cf_join_asign']; // 가입시승인상태
$mb_valid_date = $member_config['cf_mb_valid_date']; // 가입시승인상태
/* 각페이지에서 개별지정 */

include_once $nfor['path']."/inc_member_check.php"; // JWT 문제시 인크루트 소스 직접 삽입


if(($config['cf_ipin_use']=="1" or $config['cf_hp_use']=="1") and $config['cf_check_code'] and !$_SESSION['okname_asign']) goto_url("member_check.php");

if($_SESSION['okname_asign']){ 
	$write['mb_name'] = $_SESSION['okname_name'];
	$write['mb_sex'] = $_SESSION['okname_sex'];
	$write['mb_birthday'] = $_SESSION['okname_birth'];
	$write['mb_birthday_1'] = substr($write['mb_birthday'],0,4);
	$write['mb_birthday_2'] = substr($write['mb_birthday'],4,2);
	$write['mb_birthday_3'] = substr($write['mb_birthday'],6,2);
	$write['mb_hp'] = $_SESSION['okname_hp'];
} else{		

	if($_SESSION['sns_login']){

		//$write['mb_name'] = $_SESSION[$_SESSION['sns_login']]['mb_name'];
		$write['mb_email'] = $_SESSION[$_SESSION['sns_login']]['mb_email'];
		$write['mb_sex'] = $_SESSION[$_SESSION['sns_login']]['mb_sex'];
		$write['mb_nick'] = $_SESSION[$_SESSION['sns_login']]['mb_nick']; // .rand("111111","999999")
		$write['mb_birthday_1'] = $_SESSION[$_SESSION['sns_login']]['mb_birth_1'];
		$write['mb_birthday_2'] = $_SESSION[$_SESSION['sns_login']]['mb_birth_2'];
		$write['mb_birthday_3'] = $_SESSION[$_SESSION['sns_login']]['mb_birth_3'];

		if($_SESSION['sns_login']=="naver"){
			if($mb_email){
				$exp = explode("@",$mb_email);
				$mb_id = $exp[0]; // .rand("111111","999999")
			} else{
				$mb_id = time();
			}
		}

	}

}

if($_SESSION['sns_login']){
	$write['mb_naver_id'] = $_SESSION[$_SESSION['sns_login']]['mb_naver_id'];
	$write['mb_facebook_id'] = $_SESSION[$_SESSION['sns_login']]['mb_facebook_id'];
	$write['mb_kakao_id'] = $_SESSION[$_SESSION['sns_login']]['mb_kakao_id'];
	$write['mb_google_id'] = $_SESSION[$_SESSION['sns_login']]['mb_google_id'];
	$write['mb_apple_id'] = $_SESSION[$_SESSION['sns_login']]['mb_apple_id'];
}

$write['mb_timestamp'] = make_timestamp();

$nfor['title'] = "회원가입";

include_once $nfor['skin_path'].basename($_SERVER['PHP_SELF']);
?>