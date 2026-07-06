<?php 
include_once "path.php";

// 토큰생성
$request = nfor_curl_request("https://kauth.kakao.com/oauth/token", "post", "grant_type=authorization_code&client_id={$api[api_kakao_rest]}&redirect_uri={$nfor[url]}/kakao_callback.php&code={$_GET[code]}");
$json = json_decode($request,true);

// 정보호출
$response = nfor_curl_request("https://kapi.kakao.com/v2/user/me", "get", "", array('Authorization: Bearer '.$json[access_token], 'Content-type: application/x-www-form-urlencoded;charset=utf-8'));
$kakao = json_decode($response,true);


// $kakao[properties][profile_image];
// $kakao[properties][thumbnail_image];

$mb = sql_fetch("select * from nfor_member where mb_kakao_id='$kakao[id]'");
if($mb[mb_kakao_id]){
	$_SESSION[ss_mb_no] = $mb[mb_no];
	goto_url("index.php");
}

$_SESSION[sns_login] = "kakao";
$_SESSION[$_SESSION[sns_login]][mb_kakao_id] = $kakao[id];
$_SESSION[$_SESSION[sns_login]][mb_name] = $kakao[properties][nickname];
$_SESSION[$_SESSION[sns_login]][mb_email] = "";
$_SESSION[$_SESSION[sns_login]][mb_sex] = "";
$_SESSION[$_SESSION[sns_login]][mb_nick] = $kakao[properties][nickname];
$_SESSION[$_SESSION[sns_login]][mb_birth_1] = "";
$_SESSION[$_SESSION[sns_login]][mb_birth_2] = "";
$_SESSION[$_SESSION[sns_login]][mb_birth_3] = "";  

goto_url("member_join.php");
?>