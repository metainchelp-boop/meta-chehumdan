<?php

function apple_login_btn(){ // 애플로그인 버튼링크생성
	global $api, $nfor, $_SESSION;

	$client_id = $api[api_apple_client_id];
	$redirect_uri = $nfor[url]."/apple_callback.php";
	$state = $_SESSION[state];

	$scope = "name%20email";

	$url = "https://appleid.apple.com/auth/authorize?response_type=code&response_mode=form_post&client_id={$client_id}&redirect_uri={$redirect_uri}&state={$state}&scope={$scope}";

	return $url;
	
}

function recaptcha_check(){
	global $api, $nfor, $is_guest, $_POST;
	if($api['api_google_recaptcha_use']=="1" and !$nfor['is_app'] and $is_guest){
		if(!$_POST['g-recaptcha-response']) json_return("보안문자를 선택해주세요","recaptcha");
		$request = nfor_curl_request("https://www.google.com/recaptcha/api/siteverify?secret=".urlencode($api['api_google_recaptcha_secretkey'])."&response=".urlencode($_POST['g-recaptcha-response']));
		$response = json_decode($request,true);
		if(!$response['success']) json_return("보안문자 입력이 올바르지 않습니다","recaptcha");
	}
}

function naverpay_script($where){ // 네이버페이
	global $config, $is_mobile, $HTTP_HOST;
	if($config[cf_naverpay_use]=="1"){
		
		if($config[cf_naverpay_test]=="1"){
			$test = "test-";
		}	
		if($is_mobile){
			$mobile = "mobile/";
		}

		if(substr($HTTP_HOST,0,4)=="www."){
			$host = substr($HTTP_HOST,4);
		} else{
			$host = $HTTP_HOST;
		}

		if($where=="head"){
			$return = "<script type=\"text/javascript\" src=\"//{$test}pay.naver.com/customer/js/{$mobile}naverPayButton.js\" charset=\"UTF8\"></script>\n";	
			$return .= "<script type=\"text/javascript\" src=\"//wcs.naver.net/wcslog.js\"></script>\n";
			$return .= "<script type=\"text/javascript\">\n";
			$return .= "if(!wcs_add) var wcs_add = {};\n";
			$return .= "wcs_add[\"wa\"] = \"{$config[cf_naverpay_wcs_add]}\";\n";
			$return .= "wcs.inflow(\"{$host}\");\n";
			$return .= "</script>\n";

		} elseif($where=="tail"){
			$return = "<script>\n";
			$return .= "wcs_do();\n";
			$return .= "</script>\n";
		} else{
			$return = "";
		}


	}
	return $return;
}


function cut_url($url){
	return naver_url($url);
}

function naver_url($url){ // 네이버 짧은주소(하루 25000번 제한)
	global $api;
	if($api[api_naver_client_id] and $api[api_naver_client_secret]){
		$return = "";
		$header = array("X-Naver-Client-Id: $api[api_naver_client_id]","X-Naver-Client-Secret: $api[api_naver_client_secret]");
		$request = nfor_curl_request("https://openapi.naver.com/v1/util/shorturl", "post", "url=$url", $header); // , $header="", $show_header=""
		$response = json_decode($request,true);
		if($response[message]=="ok"){
			$return = $response[result][url];
		} else{
			$return = $url;
		}
	} else{
		$return = $url;
	}
	return $return;
}


function kakao_login_btn(){ // 카카오로그인 버튼링크생성
	global $_SESSION, $nfor, $api;

	$redirect_uri  = $nfor[url]."/kakao_callback.php";
	$request = nfor_curl_request("https://kauth.kakao.com/oauth/authorize?client_id={$api[api_kakao_rest]}&redirect_uri={$redirect_uri}&response_type=code", "get", "", "", "1");
	$exp = explode("Location: ", trim($request));
	$move_url = explode("\n", trim($exp[1]));
	if(trim($move_url[0])){
		$kakao_url = trim($move_url[0]);
	} else{
		$kakao_url = "index.php";
	}

	return $kakao_url;
}

function google_login_btn(){ // 구글로그인 버튼링크생성
	global $nfor, $api;
	$google_client = new Google_Client();
	$google_client->setClientId($api['api_google_client_id']);
	$google_client->setClientSecret($api['api_google_client_secret']);
	$google_client->setRedirectUri($nfor['url'].'/google_callback.php');
	$google_client->addScope('email');
	$google_client->addScope('profile');
	
	$loginUrl = $google_client->createAuthUrl();
	return $loginUrl;
}

function facebook_login_btn(){ // 페이스북로그인 버튼링크생성
	global $nfor, $api;

	$fb = new Facebook\Facebook([
	  'app_id' => $api[api_facebook_appid], // Replace {app-id} with your app id
	  'app_secret' => $api[api_facebook_appsecret],
	  'default_graph_version' => 'v2.6',
	  ]);

	$helper = $fb->getRedirectLoginHelper();

	$permissions = ['email']; // Optional permissions
	$loginUrl = $helper->getLoginUrl($nfor[url]."/facebook_callback.php", $permissions);

	return $loginUrl;

}

function naver_login_btn(){ // 네이버로그인 버튼링크생성
	global $_SESSION, $nfor, $api;
	$_SESSION[naver_state] = md5(microtime() . mt_rand());
	$redirect_uri = $nfor[url]."/naver_callback.php";
	$naver_url = "https://nid.naver.com/oauth2.0/authorize?client_id=$api[api_naver_client_id]&response_type=code&redirect_uri=$redirect_uri&state=$_SESSION[naver_state]";
	return $naver_url;
}

function naver_login_logout(){ // 네이버로그인 로그아웃처리
	global $_SESSION, $nfor, $api;
	$response = nfor_curl_request("https://nid.naver.com/oauth2.0/token?grant_type=delete&client_id=$api[api_naver_client_id]&client_secret=$api[api_naver_client_secret]&access_token=$_SESSION[naver_access_token][access_token]&service_provider=NAVER");
	$response = json_decode($response,true);
	if($response[result]=="success"){
		unset($_SESSION[naver_access_token]);
	}

}

function naver_login_refresh_token(){ // 네이버로그인 토큰갱신
	global $_SESSION, $nfor, $api;

	if(isset($_SESSION[naver_access_token])){

		// 토큰 만료 30초전 새로운 토큰으로 갱신
		if($_SESSION[naver_access_token][created] + $_SESSION[naver_access_token][expires_in] - 30 < time()){

			$response = nfor_curl_request("https://nid.naver.com/oauth2.0/token?grant_type=refresh_token&client_id=$api[api_naver_client_id]&client_secret=$api[api_naver_client_secret]&refresh_token=$_SESSION[naver_access_token][refresh_token]");
			$response = json_decode($response,true);

			// 새로 받은 access token 으로 갱신해줍니다.
			$_SESSION[naver_access_token] = array_merge($_SESSION[naver_access_token], $response);

		}
	}

}


function naver_blog_write($title, $contents){ // 네이버 블로그 글쓰기 (하루 10000번 제한)
	global $api;
	// 참고문서 https://developers.naver.com/docs/blog/post

	$response = nfor_curl_request("https://openapi.naver.com/blog/writePost.json", "post", "title=$title&contents=$contents", array("Authorization: {$_SESSION[naver_access_token][token_type]} {$_SESSION[naver_access_token][access_token]}","X-Naver-Client-Id: $api[api_naver_client_id]","X-Naver-Client-Secret: $api[api_naver_client_secret]") );
	$response = json_decode($response,true);

	$return = $response[message][result][postUrl];
}

function naver_common_login_btn(){ // 네이버 공통 로그인 버튼링크생성
	global $_SESSION, $nfor, $api;
	$_SESSION[naver_state] = md5(microtime() . mt_rand());
	$redirect_uri = $nfor[url]."/naver_blog_callback.php";
	$naver_url = "https://nid.naver.com/oauth2.0/authorize?client_id=$api[api_naver_client_id]&response_type=code&redirect_uri=$redirect_uri&state=$_SESSION[naver_state]";
	return $naver_url;
}

function naver_web_search($query, $display="10", $start="1"){ // 웹검색 (하루 25000번 제한)
	global $api;
	/*
	$query = urlencode($query); // 검색어
	$display = "10"; // 출력건수 10~100(MAX)
	$start = "1"; // 검색 시작 위치로 최대 1000까지 가능
	*/

	$header = array("X-Naver-Client-Id: $api[api_naver_client_id]","X-Naver-Client-Secret: $api[api_naver_client_secret]");
	$request = nfor_curl_request("https://openapi.naver.com/v1/search/webkr.xml?query=$query&display=$display&start=$start", "get","", $header);

	$request = xml2array($request);
	return $request;
}

function naver_cafe_search($query, $display="10", $start="1", $sort="date"){ // 카페글검색 (하루 25000번 제한)
	global $api;
	/*
	$query = urlencode($query); // 검색어
	$display = "10"; // 출력건수 10~100(MAX)
	$start = "1"; // 검색 시작 위치로 최대 1000까지 가능
	$sort = "date"; // 정렬 옵션: sim (유사도순), date (날짜순)
	*/

	$header = array("X-Naver-Client-Id: $api[api_naver_client_id]","X-Naver-Client-Secret: $api[api_naver_client_secret]");
	$request = nfor_curl_request("https://openapi.naver.com/v1/search/cafearticle.xml?query=$query&display=$display&start=$start&sort=$sort", "get","", $header);

	$request = xml2array($request);
	return $request;
}

function naver_news_search($query, $display="10", $start="1", $sort="date"){ // 뉴스검색 (하루 25000번 제한)
	global $api;
	/*
	$query = urlencode($query); // 검색어
	$display = "10"; // 출력건수 10~100(MAX)
	$start = "1"; // 검색 시작 위치로 최대 1000까지 가능
	$sort = "date"; // 정렬 옵션: sim (유사도순), date (날짜순)
	*/

	$header = array("X-Naver-Client-Id: $api[api_naver_client_id]","X-Naver-Client-Secret: $api[api_naver_client_secret]");
	$request = nfor_curl_request("https://openapi.naver.com/v1/search/news.xml?query=$query&display=$display&start=$start&sort=$sort", "get","", $header);

	$request = xml2array($request);
	return $request;
}

function naver_blog_search($query, $display="10", $start="1", $sort="date"){ // 블로그검색 (하루 25000번 제한)
	global $api;
	/*
	$query = urlencode($query); // 검색어
	$display = "10"; // 출력건수 10~100(MAX)
	$start = "1"; // 검색 시작 위치로 최대 1000까지 가능
	$sort = "date"; // 정렬 옵션: sim (유사도순), date (날짜순)
	*/

	$header = array("X-Naver-Client-Id: $api[api_naver_client_id]","X-Naver-Client-Secret: $api[api_naver_client_secret]");
	$request = nfor_curl_request("https://openapi.naver.com/v1/search/blog.xml?query=$query&display=$display&start=$start&sort=$sort", "get","", $header);

	$request = xml2array($request);
	return $request;
}


?>