<?php
include_once "path.php";

$google_client = new Google_Client();
$google_client->setClientId($api['api_google_client_id']);
$google_client->setClientSecret($api['api_google_client_secret']);
$google_client->setRedirectUri($nfor['url'].'/google_callback.php');
$google_client->addScope('email');
$google_client->addScope('profile');

if(isset($_GET["code"])){
	$token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
	if(!isset($token['error'])){
		$google_client->setAccessToken($token['access_token']);
		$google_service = new Google_Service_Oauth2($google_client);
		$google = $google_service->userinfo->get();

		$mb = sql_fetch("select * from nfor_member where mb_google_id='{$google['id']}'");
		if($mb['mb_google_id']){
			$_SESSION['ss_mb_no'] = $mb['mb_no'];
			goto_url("index.php");
		}

		$_SESSION['sns_login'] = "google";
		$_SESSION[$_SESSION['sns_login']]['mb_google_id'] = $google['id'];
		$_SESSION[$_SESSION['sns_login']]['mb_name'] = $google['name'];
		$_SESSION[$_SESSION['sns_login']]['mb_email'] = $google['email'];
		$_SESSION[$_SESSION['sns_login']]['mb_sex'] = $google['gender'];
		$_SESSION[$_SESSION['sns_login']]['mb_nick'] = $google['name'];
		$_SESSION[$_SESSION['sns_login']]['mb_birth_1'] = "";
		$_SESSION[$_SESSION['sns_login']]['mb_birth_2'] = "";
		$_SESSION[$_SESSION['sns_login']]['mb_birth_3'] = "";  

		goto_url("member_join.php");
	}
}

?>