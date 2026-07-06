<?php
include_once "path.php";

$fb = new Facebook\Facebook([
  'app_id' => $api[api_facebook_appid], // Replace {app-id} with your app id
  'app_secret' => $api[api_facebook_appsecret],
  'default_graph_version' => 'v2.6',
  ]);

$helper = $fb->getRedirectLoginHelper();

try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

if (! isset($accessToken)) {
  if ($helper->getError()) {
    header('HTTP/1.0 401 Unauthorized');
    echo "Error: " . $helper->getError() . "\n";
    echo "Error Code: " . $helper->getErrorCode() . "\n";
    echo "Error Reason: " . $helper->getErrorReason() . "\n";
    echo "Error Description: " . $helper->getErrorDescription() . "\n";
  } else {
    header('HTTP/1.0 400 Bad Request');
    echo 'Bad request';
  }
  exit;
}

// Logged in
/*
echo '<h3>Access Token</h3>';
var_dump($accessToken->getValue());
*/

// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);

/*
echo '<h3>Metadata</h3>';
var_dump($tokenMetadata);
*/

// Validation (these will throw FacebookSDKException's when they fail)
$tokenMetadata->validateAppId($api[api_facebook_appid]); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
$tokenMetadata->validateExpiration();

if (! $accessToken->isLongLived()) {
  // Exchanges a short-lived access token for a long-lived one
  try {
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
  } catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
    exit;
  }

  echo '<h3>Long-lived</h3>';
  var_dump($accessToken->getValue());
}


$_SESSION['fb_access_token'] = (string) $accessToken;

// User is logged in with a long-lived access token.
// You can redirect them to a members-only page.
//header('Location: https://example.com/members.php');

try {
  // Returns a `Facebook\FacebookResponse` object
  $response = $fb->get('/me?fields=id,name,email', $_SESSION['fb_access_token']);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

$facebook = $response->getGraphUser();


$mb = sql_fetch("select * from nfor_member where mb_facebook_id='$facebook[id]'");
if($mb[mb_facebook_id]){
	$_SESSION[ss_mb_no] = $mb[mb_no];
	goto_url("index.php");
}

$_SESSION[sns_login] = "facebook";
$_SESSION[$_SESSION[sns_login]][mb_facebook_id] = $facebook[id];
$_SESSION[$_SESSION[sns_login]][mb_name] = $facebook[name];
$_SESSION[$_SESSION[sns_login]][mb_email] = $facebook[email];
$_SESSION[$_SESSION[sns_login]][mb_sex] = "";
$_SESSION[$_SESSION[sns_login]][mb_nick] = "";
$_SESSION[$_SESSION[sns_login]][mb_birth_1] = "";
$_SESSION[$_SESSION[sns_login]][mb_birth_2] = "";
$_SESSION[$_SESSION[sns_login]][mb_birth_3] = "";  

goto_url("member_join.php");
?>