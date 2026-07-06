<?php
include_once "path.php";

use \Firebase\JWT\JWT; // jwt토큰생성

//print_r($_REQUEST);

$client_id = $api[api_apple_client_id]; // 'io.dangol.service';

$key = openssl_pkey_get_private("file://AuthKey_".$api[api_apple_key_id].".p8");

$redirect_uri = $nfor[url]."/apple_callback.php";

$time = time();

$exp_time = time() + 3600;

try{
	$client_secret = JWT::encode([
	"iss" => $api[api_apple_team_id], // "9JYV322XG7"
	"iat" => $time,
	"exp" => $exp_time,
	"aud" => "https://appleid.apple.com",
    "sub" => $client_id	
	], $key, 'ES256', $api[api_apple_key_id]); // "2P5SYVA548"

} catch (Exception $e) {
	$return[jwt_error] = $e->getMessage();

	echo $return[jwt_error];
}



if(isset($_REQUEST['code'])) {

	if($_SESSION['state'] != $_REQUEST['state']) {
		die('Authorization server returned an invalid state parameter');
	}

	// Token endpoint docs: 
	// https://developer.apple.com/documentation/signinwithapplerestapi/generate_and_validate_tokens

	$response = http('https://appleid.apple.com/auth/token', [
		'grant_type' => 'authorization_code',
		'code' => $_REQUEST['code'],
		'redirect_uri' => $redirect_uri,
		'client_id' => $client_id,
		'client_secret' => $client_secret,
	]);

	if(!isset($response->access_token)) {
		echo '<p>Error getting an access token:</p>';
		echo '<pre>'; print_r($response); echo '</pre>';
		echo '<p><a href="/">Start Over</a></p>';
		exit;
	}

	//echo '<h3>Access Token Response</h3>';
	//echo '<pre>'; print_r($response); echo '</pre>';

	$exp = explode('.', $response->id_token);
	for($i=0; $i<=count($exp); $i++){
		$aaa = json_decode(base64_decode($exp[$i]));
		//print_r($aaa);
	}

	$claims = explode('.', $response->id_token)[1];
	$claims = json_decode(base64_decode($claims));

	//echo '<h3>Parsed ID Token</h3>';
	//echo '<pre>';
	//print_r($claims);
	//echo '</pre>';

	$apple[id] = $claims->sub;
	$apple[email] = $claims->email;

	$mb = sql_fetch("select * from nfor_member where mb_apple_id='$apple[id]'");
	if($mb[mb_apple_id]){
		$_SESSION[ss_mb_no] = $mb[mb_no];
		goto_url("index.php");
	}

	$_SESSION[sns_login] = "apple";
	$_SESSION[$_SESSION[sns_login]][mb_apple_id] = $apple[id];
	$_SESSION[$_SESSION[sns_login]][mb_name] = "";
	$_SESSION[$_SESSION[sns_login]][mb_email] = $apple[email];
	$_SESSION[$_SESSION[sns_login]][mb_sex] = "";
	$_SESSION[$_SESSION[sns_login]][mb_nick] = "";
	$_SESSION[$_SESSION[sns_login]][mb_birth_1] = "";
	$_SESSION[$_SESSION[sns_login]][mb_birth_2] = "";
	$_SESSION[$_SESSION[sns_login]][mb_birth_3] = "";  
	
	goto_url("member_join.php");
}


function http($url, $params=false) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  if($params)
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'User-Agent: curl', # Apple requires a user agent header at the token endpoint
  ]);
  $response = curl_exec($ch);
  return json_decode($response);
}
?>