<?php
include_once "path.php";

use \Firebase\JWT\JWT; // jwt토큰생성

if($mode=="logout"){
	logout();

	if($url){
		$p = parse_url($url);
		if($p['scheme'] || $p['host']) json_return("도메인 지정이 되지 않았습니다");
		$link = $url;
	} else{
		$link = $nfor['path'];
	}
	$return['url'] = $link;

	json_return("로그아웃 되었습니다","ok");
}

if($mode=="login"){
	
	$mb_id = $_POST[$member_config['cf_mb_id_type']];
	$mb_password = $_POST['mb_password'];

	if (!trim($mb_id)) json_return("아이디를 입력해주세요","mb_id");
	if (!trim($mb_password)) json_return("패스워드를 입력해주세요","mb_password");

	$mb = member($member_config['cf_mb_id_type'], $mb_id);

	if(!$mb['mb_no']) json_return("가입된 아이디가 존재하지 않습니다","mb_id");

    if(sql_password($mb_id, $mb_password) != $mb['mb_password']) json_return("가입된 회원의 패스워드가 일치하지 않습니다","mb_password");

	if($mb['mb_access']) json_return("접근이 금지된 아이디입니다","mb_id");

	if($mb['mb_leavedatetime'] && $mb['mb_leavedatetime'] <= $nfor['ymdhis']) json_return("탈퇴한 아이디로 접근하실수 없습니다","mb_id");

	if($member_config['cf_mb_again_asign'] <> "1" and strtotime($mb['mb_login_datetime']) < strtotime("-1 year")){
		$return['url'] = "sleep.php?mb_no=".$mb['mb_no'];
		json_return("휴면 아이디는 인증후 이용이 가능합니다","sleep_member");
	}

	$_SESSION['ss_mb_no'] = $mb['mb_no'];

	if($auto_login){
		$key = md5($mb['mb_no'].$mb['mb_password']);
		set_cookie('ck_mb_no', $mb['mb_no'], 86400 * 365);
		set_cookie('ck_auto', $key, 86400 * 365);
	} else{
		set_cookie('ck_mb_no', '', 0);
		set_cookie('ck_auto', '', 0);
	}

	if($save_id) { 
		set_cookie('chk_save_id', $mb_id, time()+2592000); 
	} else{ 
		set_cookie('chk_save_id', '', 0); 
	} 

	if($url){
		$link = urldecode($url);

		if (preg_match("/\?/", $link)){
			$split= "&"; 
		} else{
			$split= "?"; 
		}

		foreach($_POST as $key=>$value){
			if($key != "mb_id" && $key != "mb_password" && $key != "x" && $key != "y" && $key != "url" && $key != "mode"){
				$link .= "$split$key=$value";
				$split = "&";
			}
		}
	} else{
		$link = $nfor['path'];
	}
	$return['url'] = $link;

	/* 최근본캠페인 저장 */
	$cv_guest_no = $_SESSION['guest_no'];
	$que = sql_query("select * from nfor_campaign_view where cv_guest_no='{$cv_guest_no}'");
	while($data = sql_fetch_array($que)){
		$chk_item_view = sql_fetch("select * from nfor_campaign_view where cv_mb_no='{$mb['mb_no']}' and cv_cp_id='{$data['cv_cp_id']}'");
		if($chk_item_view['cv_id']){
			sql_query("delete from nfor_campaign_view where cv_id='{$chk_item_view['cv_id']}'");
		}
		sql_query("update nfor_campaign_view set cv_mb_no='{$mb['mb_no']}', cv_guest_no='' where cv_id='{$data['cv_id']}'");
	}
	$view_count = sql_fetch("select count(*) as cnt from nfor_campaign_view where cv_mb_no='{$mb['mb_no']}'");
	if($view_count['cnt'] >= $config['cf_item_recent_max']){
		$delete_count = $view_count['cnt'] - $config['cf_item_recent_max'];
		sql_query("delete from nfor_campaign_view where cv_mb_no='{$mb['mb_no']}' order by cv_datetime asc limit $delete_count");
	}
	/* 최근본캠페인 저장 */

	if(substr($nfor['root_path'],4,3) != "sub") die("");
	/* 
	jwt토큰생성
	https://jwt.io
	iss: 토큰 발급자 (issuer)
	sub: 토큰 제목 (subject)
	aud: 토큰 대상자 (audience)
	exp: 토큰의 만료시간 (expiraton), 시간은 NumericDate 형식으로 되어있어야 하며 (예: 1480849147370) 언제나 현재 시간보다 이후로 설정되어있어야합니다.
	nbf: Not Before 를 의미하며, 토큰의 활성 날짜와 비슷한 개념입니다. 여기에도 NumericDate 형식으로 날짜를 지정하며, 이 날짜가 지나기 전까지는 토큰이 처리되지 않습니다.
	iat: 토큰이 발급된 시간 (issued at), 이 값을 사용하여 토큰의 age 가 얼마나 되었는지 판단 할 수 있습니다.
	jti: JWT의 고유 식별자로서, 주로 중복적인 처리를 방지하기 위하여 사용됩니다. 일회용 토큰에 사용하면 유용합니다.
	*/
	$time = time();

	$exp_time = $time+$nfor['jwt_token_time'];
	$token = array(
		"iss" => $nfor['url'],
		"iat" => $time,
		"nbf" => $time,
		"exp" => $exp_time,
		"mb_no" => $mb['mb_no']
	);
	try{
		$jwt = JWT::encode($token, $nfor['jwt_key']);
		$return['jwt'] = $jwt;
	} catch (Exception $e) {
		$return['jwt_error'] = $e->getMessage();
	}

	$exp_time = $time+$nfor['jwt_refresh_token_time'];
	$refresh_token = array(
		"iss" => $nfor['url'],
		"iat" => $time,
		"nbf" => $time,
		"exp" => $exp_time,
		"mb_no" => $mb['mb_no']
	);
	try{
		$jwt = JWT::encode($refresh_token, $nfor['jwt_key']);
		$return['jwt_refresh'] = $jwt;
	} catch (Exception $e) {
		$return['jwt_refresh_error'] = $e->getMessage();
	}
	/* jwt토큰생성 */

	json_return("로그인에 성공하였습니다","ok");
}


if($mode=="refresh"){

	if(!$refresh_token) json_return("refresh_token 값이 넘어오지 않았습니다","refresh_token");

	try {
		$decoded = JWT::decode($refresh_token, $nfor['jwt_key'], array('HS256'));
		$mb_no = $decoded->mb_no;

		$time = time();
		$exp_time = $time+$nfor['jwt_token_time'];
		$token = array(
			"iss" => $nfor['url'],
			"iat" => $time,
			"nbf" => $time,
			"exp" => $exp_time,
			"mb_no" => $mb_no
		);
		try{
			$jwt = JWT::encode($token, $nfor['jwt_key']);
			$return['jwt'] = $jwt;
		} catch (Exception $e) {
			$return['jwt_error'] = $e->getMessage();
		}
		json_return("토큰이 갱신되었습니다","ok");

	} catch (Exception $e) {
		$return['jwt_error'] = $e->getMessage();
		json_return("인증에 실패하였습니다","jwt_refresh_error");
	}
	

}

if($mode=="token_update"){
	if(!$mb_id) json_return("아이디가 넘어오지 않았습니다","mb_id");
	if(!$mb_push_token) json_return("토큰 넘어오지 않았습니다","mb_push_token");
	sql_query("update nfor_member set mb_push_token='{$mb_push_token}' where mb_id='{$mb_id}'");
	json_return("토큰이 업데이트되었습니다","ok");
}


if($member['mb_no']){
	if($url){
		goto_url($url);
	} else{
        goto_url($nfor['path']."/");
	}
}

if($url){
    $write['url'] = urlencode($url);
} else{
    $write['url'] = urlencode($_SERVER['REQUEST_URI']);
}

$write['mb_id'] = get_cookie("chk_save_id");
if(!$write['mb_id']) $write['mb_id'] = "";

if($nfor['test']){
	$mb = sql_fetch("select mb_id, mb_email from nfor_member where mb_no='1'");
	$write[$member_config['cf_mb_id_type']] = $mb[$member_config['cf_mb_id_type']];
	$write['mb_password'] = $nfor['test_password'];
} else{
	$write['mb_password'] = "";
}

$nfor['title'] = "로그인";

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>