<?
include_once "path.php";


 // 세션 또는 별도의 저장 공간에 저장된 상태 토큰과 콜백으로 전달받은 state 파라미터의 값이 일치해야 함(CSRF 방지를 위한 상태 토큰 검증)
if($_GET[state] and $_GET[state] == $_SESSION[naver_state]) {

	$request = nfor_curl_request("https://nid.naver.com/oauth2.0/token?client_id=$api[api_naver_client_id]&client_secret=$api[api_naver_client_secret]&grant_type=authorization_code&state=$_GET[state]&code=$_GET[code]"); // access token을 가져옴
	unset($_SESSION[naver_state]);

	$_SESSION[naver_access_token] = json_decode($request,true);
	$_SESSION[naver_access_token][created] = time(); // 토큰생성시간저장


	$response = nfor_curl_request("https://openapi.naver.com/v1/nid/me", "get", "", array("Authorization: {$_SESSION[naver_access_token][token_type]} {$_SESSION[naver_access_token][access_token]}") );
	$response = json_decode($response,true);


	if($response[resultcode]=="00"){ // 사용자 정보를 가져왔다면

		$naver = $response[response];

		/*
		$naver[email]; nfor@nate.com
		$naver[nickname]; // 엔포
		$naver[enc_id]; // 030eac6c26e33920315f066006fc366adeb7
		$naver[profile_image]; https://phinf.pstatic.net/contactthumb/profile/blog/54/6/image.jpg?type=s80 사용자 프로필 사진 URL
		$naver[age]; // 30-39 사용자 연령대
		$naver[gender]; // M 남 F 여 U 확인불가
		$naver[id]; // 12345678 (네이버 아이디마다 고유하게 발급되는 값)
		$naver[name]; // 전지현
		$naver[birthday]; // 01-07  사용자 생일(MM-DD 형식)
		*/
		$age = explode("-",$naver[age]);
		$mb_birth_1 = date("Y",strtotime("-$age[0] year"));

		$birthday = explode("-",$naver[birthday]);

		$mb = sql_fetch("select * from nfor_member where mb_naver_id='$naver[id]'");
		if($mb[mb_naver_id]){
			$_SESSION[ss_mb_no] = $mb[mb_no];
			goto_url("index.php");
		}

		$_SESSION[sns_login] = "naver";
		$_SESSION[$_SESSION[sns_login]][mb_naver_id] = $naver[id];
		$_SESSION[$_SESSION[sns_login]][mb_name] = $naver[name];
		$_SESSION[$_SESSION[sns_login]][mb_email] = $naver[email];
		$_SESSION[$_SESSION[sns_login]][mb_sex] = $naver[gender];
		$_SESSION[$_SESSION[sns_login]][mb_nick] = $naver[nickname];
		$_SESSION[$_SESSION[sns_login]][mb_birth_1] = $mb_birth_1;
		$_SESSION[$_SESSION[sns_login]][mb_birth_2] = $birthday[0];
		$_SESSION[$_SESSION[sns_login]][mb_birth_3] = $birthday[1];  


		goto_url("member_join.php");

	} else{

		alert("네이버 로그인에 실패하였습니다");

	}


} else{
	alert("다시 시도해주세요","login.php");
}
?>