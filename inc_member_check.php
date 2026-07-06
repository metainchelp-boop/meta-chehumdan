<?php
use \Firebase\JWT\JWT; // jwt토큰생성

if($mode=="insert" or $mode=="update"){
	// 보안패치
	foreach($_POST as $key => $value){
		if(!is_array($_POST[$key])) $$key = htmlspecialchars(strip_tags($value));
	}

	if(!$mb_mailling) $mb_mailling = "2";
	if(!$mb_sms) $mb_sms = "2";

	if($mode=="insert" and ($mb_naver_id or $mb_facebook_id or $mb_kakao_id or $mb_google_id or $mb_apple_id)){
		if($mb_naver_id) $mb_id = "naver_".time(); // $mb_naver_id."_".
		if($mb_facebook_id) $mb_id = "facebook_".time(); // $mb_facebook_id."_".
		if($mb_kakao_id) $mb_id = "kakao_".time(); // $mb_kakao_id."_".
		if($mb_google_id) $mb_id = "google_".time(); // $mb_google_id."_".
		if($mb_apple_id) $mb_id = "apple_".time(); // "apple_".$mb_apple_id."_".time();
		$mb_password = "nfor".rand("1111","9999");
		$mb_password_confirm = $mb_password;
		$_POST[mb_password] = $mb_password;
	}
	
	if($member_config[cf_mb_id_type]=="mb_id"){
		nfor_check_return("mb_id", $mb_id, $mb_no);
	}
	nfor_check_return("mb_nick", $mb_nick, $mb_no);
	nfor_check_return("mb_email", $mb_email, $mb_no);

	if($mode=="insert"){
		nfor_check_return("mb_password", $mb_password, $mb_no);	
		$mb_password = sql_password($member_config[cf_mb_id_type]=="mb_email"?$mb_email:$mb_id, $mb_password);
		$add_sql .= ", mb_password='$mb_password'";

		if(!$is_admin) nfor_check_return("mb_password_confirm", $mb_password_confirm, $mb_no);	
	}

	if($mode=="update" and ($mb_password_now or $mb_password or $mb_password_confirm)){

		if(!$is_admin) nfor_check_return("mb_password_now", $mb_password_now, $mb_no);

		nfor_check_return("mb_password", $mb_password, $mb_no);	
		$mb_password = sql_password($member_config[cf_mb_id_type]=="mb_email"?$mb_email:$mb_id, $mb_password);
		$add_sql .= ", mb_password='$mb_password', mb_password_change_datetime=NOW()";
	
		if(!$is_admin) nfor_check_return("mb_password_confirm", $mb_password_confirm, $mb_no);

	}
	
	nfor_check_return("mb_name", $mb_name, $mb_no);

	if($asign_number){
		$wr_hp = add_hyphen($mb_hp);
		$chk_asign_number = sql_fetch("select * from nfor_hp_asign where wr_hp='$wr_hp' order by wr_id desc limit 1");
		if($chk_asign_number[wr_asign] <> $asign_number) json_return("인증번호가 일치하지 않습니다3 $asign_number","different");

		// 기존 휴대폰회원 정보 모두 초기화
		sql_query("update nfor_member set mb_hp='' where mb_hp='$wr_hp'");
	} else{
		nfor_check_return("mb_hp", $mb_hp, $mb_no);
	}

	nfor_check_return("mb_tel", $mb_tel, $mb_no);
	nfor_check_return("mb_zipcode", $mb_zipcode, $mb_no);
	nfor_check_return("mb_addr1", $mb_addr1, $mb_no);
	nfor_check_return("mb_addr2", $mb_addr2, $mb_no);
	nfor_check_return("mb_mailling", $mb_mailling, $mb_no);
	nfor_check_return("mb_sms", $mb_sms, $mb_no);
	nfor_check_return("mb_sex", $mb_sex, $mb_no);
	nfor_check_return("mb_birthday_type", $mb_birthday_type, $mb_no);

	if($mb_birthday){
		nfor_check_return("mb_birthday", $mb_birthday, $mb_no);
	} else{
		nfor_check_return("mb_birthday_1", $mb_birthday_1, $mb_no);
		nfor_check_return("mb_birthday_2", $mb_birthday_2, $mb_no);
		nfor_check_return("mb_birthday_3", $mb_birthday_3, $mb_no);
		if($mb_birthday_1 and $mb_birthday_2 and $mb_birthday_3){
			$mb_birthday = $mb_birthday_1."-".sprintf("%02d",$mb_birthday_2)."-".sprintf("%02d",$mb_birthday_3);
		}
	}
	if($mode=="insert"){
		if($mb_birthday){
			$age = ceil((time()-strtotime($mb_birthday))/(60*60*24*365));
			if($member_config[cf_join_age]=="2" and $age <= $member_config[cf_join_teen]){ //  세 이하인 경우 운영자 승인후 가입
				$mb_asign = "2";
			} elseif($member_config[cf_join_age]=="3" and $age <= $member_config[cf_join_adult]){ // 세 이하인 경우 가입제한
				json_return("{$member_config[cf_join_adult]}세 이하인 경우 가입이 제한됩니다","age");
			} else{ // 가입연령 제한없음

			}
		}

		nfor_check_return("mb_friend", $mb_friend, $mb_no);

		if($is_mobile){
			$add_sql .= ", mb_join_channel='2'";
		} else{
			$add_sql .= ", mb_join_channel='1'";
		}

	}

	
	if($mb_admin=="1"){

		nfor_check_return("mb_cp_name", $mb_cp_name, $mb_no);
		nfor_check_return("mb_cp_ceo", $mb_cp_ceo, $mb_no);
		nfor_check_return("mb_cp_number", $mb_cp_number, $mb_no);
		nfor_check_return("mb_cp_type1", $mb_cp_type1, $mb_no);
		nfor_check_return("mb_cp_type2", $mb_cp_type2, $mb_no);
		nfor_check_return("mb_cp_zipcode", $mb_cp_zipcode, $mb_no);
		nfor_check_return("mb_cp_addr1", $mb_cp_addr1, $mb_no);
		nfor_check_return("mb_cp_addr2", $mb_cp_addr2, $mb_no);

	}


	/* 폼은 있지만 체크는 안함 */
	nfor_check_return("mb_valid_date", $mb_valid_date, $mb_no);
	nfor_check_return("mb_bank_name", $mb_bank_name, $mb_no);
	nfor_check_return("mb_bank_account", $mb_bank_account, $mb_no);

	if($mb_admin=="1"){
		nfor_check_return("mb_cp_bank_name", $mb_cp_bank_name, $mb_no);
		nfor_check_return("mb_cp_bank_account", $mb_cp_bank_account, $mb_no);
		nfor_check_return("mb_cp_bank_account_holder", $mb_cp_bank_account_holder, $mb_no);
	}
	
	nfor_check_return("mb_memo", $mb_memo, $mb_no);
	/* 폼은 있지만 체크는 안함 */


	$mb_hp = add_hyphen($mb_hp);
	$mb_tel = add_hyphen($mb_tel);

	if($mode=="insert"){
		$msg = "정상적으로 등록 되었습니다";
		$add_sql .= ", mb_login_ip='$_SERVER[REMOTE_ADDR]', mb_ip='$_SERVER[REMOTE_ADDR]', mb_datetime=NOW(), mb_login_datetime=NOW(), mb_password_change_datetime=NOW(), mb_timestamp='$mb_timestamp'";
		$add_sql .= ", mb_google_id='$mb_google_id', mb_kakao_id='$mb_kakao_id', mb_naver_id='$mb_naver_id', mb_facebook_id='$mb_facebook_id', mb_apple_id='$mb_apple_id'";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$where_sql = " where mb_no='$mb_no'";
		$add_sql .= ", mb_update_datetime=NOW()";
	} else{

	}

	$common_sql = " nfor_member set mb_id='$mb_id',
									mb_name='$mb_name',
									mb_hp='$mb_hp', 
									mb_email='$mb_email', 
									mb_birthday_type='$mb_birthday_type',
									mb_birthday='$mb_birthday',
									mb_sex='$mb_sex',
									mb_nick='$mb_nick',
									mb_zipcode='$mb_zipcode', 
									mb_addr1='$mb_addr1',
									mb_addr2='$mb_addr2',
									mb_mailling='$mb_mailling', 
									mb_sms='$mb_sms',									
									mb_level = '$mb_level',									
									mb_bank_name='$mb_bank_name',
									mb_bank_account='$mb_bank_account',									
									mb_cp_name='$mb_cp_name', 
									mb_tel='$mb_tel', 
									mb_black='$mb_black',
									mb_cp_ceo='$mb_cp_ceo',
									mb_cp_number='$mb_cp_number',
									mb_cp_zipcode='$mb_cp_zipcode',
									mb_cp_addr1='$mb_cp_addr1',
									mb_cp_addr2='$mb_cp_addr2',
									mb_cp_type1='$mb_cp_type1',
									mb_cp_type2='$mb_cp_type2',
									mb_asign='$mb_asign',
									mb_access='$mb_access',
									mb_memo='$mb_memo',
									mb_valid_date='$mb_valid_date', 
									mb_friend='$mb_friend', 									
									mb_cp_bank_name='$mb_cp_bank_name', 
									mb_cp_bank_account='$mb_cp_bank_account', 
									mb_cp_bank_account_holder='$mb_cp_bank_account_holder', 
									mb_admin='$mb_admin', 
									mb_blog='$mb_blog',
									mb_instagram='$mb_instagram',
									mb_youtube='$mb_youtube'";

	sql_query("$mode $common_sql $add_sql $where_sql");

	if($mode=="insert"){

		$mb_no = sql_insert_id();

		if($config[cf_join_money]) insert_money($mb_no,$config[cf_join_money],"회원가입","1");
		if($config[cf_join_point]) insert_point($mb_no,$config[cf_join_point],"회원가입","1",$mb_timestamp."||1");

		if($mb_friend){	
			$mb = sql_fetch("select * from nfor_member where {$member_config[cf_mb_id_type]}='$mb_friend'");	// 아이디일경우 mb_id, 이메일일경우 mb_email
			if($mb[mb_no]){
				$mb_friend_no = $mb[mb_no];
				if($config[cf_friend_money1]) insert_money($mb_no,$config[cf_friend_money1],"추천인입력","9");
				if($config[cf_friend_money2]) insert_money($mb[mb_no],$config[cf_friend_money2],"추천받음","10");
				sql_query("update nfor_member set mb_friend_no='$mb_friend_no' where mb_no='$mb_no'");

				$mb_friend_count = sql_fetch("select count(*) as cnt from nfor_member where mb_friend_no='$mb_friend_no'");
				sql_query("update nfor_member set mb_friend_count='$mb_friend_count[cnt]' where mb_no='$mb_friend_no'");
			}
		}

		nfor_send("member_join", $mb_email, $mb_hp, $mb_no);
		
		if(!$is_admin){
			$_SESSION[ss_mb_no] = $mb_no;
			$key = md5($mb_no.$mb_password);
			set_cookie('ck_mb_no', $mb_no, 86400 * 365);
			set_cookie('ck_auto', $key, 86400 * 365);

			$return[url] = "index.php";

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

			$exp_time = $time+$nfor[jwt_token_time];
			$token = array(
				"iss" => $nfor[url],
				"iat" => $time,
				"nbf" => $time,
				"exp" => $exp_time,
				"mb_no" => $mb_no
			);
			try{
				$jwt = JWT::encode($token, $nfor[jwt_key]);
				$return[jwt] = $jwt;
			} catch (Exception $e) {
				$return[jwt_error] = $e->getMessage();
			}

			$exp_time = $time+$nfor[jwt_refresh_token_time];
			$refresh_token = array(
				"iss" => $nfor[url],
				"iat" => $time,
				"nbf" => $time,
				"exp" => $exp_time,
				"mb_no" => $mb_no
			);
			try{
				$jwt = JWT::encode($refresh_token, $nfor[jwt_key]);
				$return[jwt_refresh] = $jwt;
			} catch (Exception $e) {
				$return[jwt_refresh_error] = $e->getMessage();
			}
			/* jwt토큰생성 */
		}

	}

	if($mode=="update"){
		if(!$is_admin){
			$return[url] = "member_form.php";
		}
	}

	json_return($msg,"ok");
}

$admin[mb_bank_name][""] = "선택";
$que = sql_query("select * from nfor_pg_code where pg_type='$nfor[pg_type]' and pg_payment_type='vbanking'");
while($row = sql_fetch_array($que)){
	$admin[mb_bank_name][$row[pg_code]] = $row[pg_name]."(".$row[pg_code].")";
}

$admin[mb_asign] = array("전체","승인","미승인");
$admin[mb_sns_type] = array(""=>"전체","facebook" => "페이스북", "kakao"=>"카카오톡", "naver"=>"네이버");
$admin[mb_join_channel] = array("전체","PC","모바일");
$admin[mb_mailling] = array("전체","동의","미동의");
$admin[mb_sms] = array("전체","동의","미동의");
$admin[mb_sex] = array(""=>"선택","M" => "남자", "F"=>"여자");
$admin[mb_admin] = array(""=>"전체","0" => "일반회원", "1"=>"입점회원", "2"=>"캠페인관리자", "7"=>"부관리자");
$admin[mb_valid_date] = array(""=>"전체","1 year" => "1년", "3 year"=>"3년", "5 year"=>"5년", "leave"=>"탈퇴시");
$admin[mb_birthday_type] = array("전체","양력","음력");

if($member_config[cf_join_age]=="3"){ // 가입불가
	$age_min = $member_config[cf_join_adult]; // 세 이하인 경우 가입제한
	$age_max = 100+$member_config[cf_join_adult];
} else{ // 1제한없음, 2운영자 승인후 가입
	$age_min = "1";
	$age_max = "101";
}

$admin[mb_birthday_1][""] = "생년";
for($i=date("Y")-$age_min; $i>=date("Y")-$age_max; $i--){
	$admin[mb_birthday_1][$i] = $i;
}

$admin[mb_birthday_2][""] = "월";
for($i=1; $i<=12; $i++){
	$admin[mb_birthday_2][$i] = $i;
}

$admin[mb_birthday_3][""] = "일";
for($i=1; $i<=31; $i++){
	$admin[mb_birthday_3][$i] = $i;
}

$admin[mb_level][""] = "선택";
$que = sql_query("select * from nfor_level where 1 order by lv_rank asc");
while($row = sql_fetch_array($que)){
	$admin[mb_level][$row[lv_id]] = $row[lv_name];
}	

if($json=="form"){
	$return["form"] = $admin;
	$return["value"] = $write;
	json_return($nfor[title], "ok");
}
?>