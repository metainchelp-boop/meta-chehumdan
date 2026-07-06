<?php
include_once "path.php";







exit;


$que = sql_query("select * from info_happy_member");
while($data = sql_fetch_array($que)){


	$mb_birthday = $data[user_birth_year]."-".sprintf("%02d",$data[user_birth_month])."-".sprintf("%02d",$data[user_birth_day]);

	if($data[user_birth_type]=="solar"){
		$mb_birthday_type = "2";
	} else{
		$mb_birthday_type = "1";
	}
	
	
	if($data[user_prefix]=="man"){
		$mb_sex = "M";
	} else{
		$mb_sex = "F";
	}

	if($data[email_forwarding]=="n"){
		$mb_mailling = "2";
	} else{
		$mb_mailling = "1";
	}

	if($data[sms_forwarding]=="n"){
		$mb_sms = "2";
	} else{
		$mb_sms = "1";
	}
	
	


	$add_sql = ", mb_datetime='$data[reg_date]', mb_login_datetime='$data[login_date]', mb_password_change_datetime='$data[reg_date]', mb_timestamp='$data[number]', mb_join_channel='1'";

	if(substr($data[user_id],0,6)=="naver_"){
		$add_sql .= ", mb_naver_id='".substr($data[user_id],6)."'";
	} elseif(substr($data[user_id],0,6)=="kakao_"){
		$add_sql .= ", mb_kakao_id='".substr($data[user_id],6)."'";
	} else{

	}

	$mb_cp_number = $data[com_number1]."-".$data[com_number2]."-".$data[com_number3];

	$add_sql .= ", mb_password='$data[user_pass]'";

	
	$mb_addr1 = $data[user_addr1]." ".$data[user_addr2];


	$common_sql = " nfor_member set mb_id='$data[user_id]',
									mb_name='$data[user_name]',
									mb_hp='$data[user_hphone]', 
									mb_email='$data[user_email]', 
									mb_birthday_type='$mb_birthday_type',
									mb_birthday='$mb_birthday',
									mb_sex='$mb_sex',
									mb_nick='$data[user_nick]',
									mb_zipcode='$data[user_zip]', 
									mb_addr1='$mb_addr1',
									mb_addr2='$data[user_addr1_2]',
									mb_mailling='$mb_mailling', 
									mb_sms='$mb_sms',									
									mb_level = '1',									
									mb_bank_name='$data[extra8]',
									mb_bank_account='$data[extra9]',									
									mb_cp_name='$data[com_name]', 
									mb_tel='$data[com_tel]', 
									mb_black='0',
									mb_cp_ceo='',
									mb_cp_number='$mb_cp_number',
									mb_cp_zipcode='',
									mb_cp_addr1='',
									mb_cp_addr2='',
									mb_cp_type1='',
									mb_cp_type2='',
									mb_asign='1',
									mb_access='0',
									mb_memo='$data[admin_memo]',
									mb_valid_date='leave', 
									mb_friend='', 									
									mb_cp_bank_name='', 
									mb_login_count='$data[login_count]',
									mb_cp_bank_account='', 
									mb_cp_bank_account_holder='', 
									mb_admin='0', 
									mb_blog='$data[etc_blog1_url]',
									mb_instagram='$data[instagram_url]',
									mb_youtube='$data[youtube_url]', mb_chk_insert='1'";

	sql_query("insert $common_sql $add_sql");

}

?>ok