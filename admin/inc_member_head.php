<?php
$admin['keyword_type'] = array(""=>"전체","mb_name" => "이름", "mb_nick"=>"닉네임", "mb_id"=>"아이디", "mb_email"=>"이메일", "mb_hp"=>"휴대폰번호", "mb_tel"=>"전화번호", "mb_friend"=>"추천인", "mb_cp_name"=>"상호", "mb_cp_number"=>"사업자등록번호", "mb_cp_ceo"=>"대표자명");
$admin['mb_asign'] = array("전체","승인","미승인");
$admin['mb_sns_type'] = array(""=>"전체","facebook" => "페이스북", "kakao"=>"카카오톡", "naver"=>"네이버", "google"=>"구글", "apple"=>"애플");
$admin['mb_join_channel'] = array("전체","PC","모바일");
$admin['mb_mailling'] = array("전체","동의","미동의");
$admin['mb_sms'] = array("전체","동의","미동의");
$admin['mb_sex'] = array(""=>"전체","M" => "남자", "F"=>"여자");
$admin['mb_admin'] = array(""=>"전체","0" => "일반회원", "1"=>"입점회원", "2"=>"MD", "7"=>"부관리자");
$admin['mb_valid_date'] = array(""=>"전체","1 year" => "1년", "3 year"=>"3년", "5 year"=>"5년", "leave"=>"탈퇴시");
$admin['mb_birthday_type'] = array("전체","양력","음력");
$admin['mb_level'][] = "선택";
$que = sql_query("select * from nfor_level where 1 order by lv_rank asc");
while($row = sql_fetch_array($que)){
	$admin['mb_level'][$row['lv_id']] = $row['lv_name'];
}
$admin['period_type'] = array("mb_datetime" => "회원가입일자", "mb_login_datetime" => "최근접속일자", "mb_birthday" => "생년월일", "mb_leave_datetime" => "탈퇴신청일자");	

$qstr .= "&mb_asign=$mb_asign&mb_level=$mb_level&period_day=$period_day&mb_login_count_1=$mb_login_count_1&mb_login_count_2=$mb_login_count_2&mb_order_count_1=$mb_order_count_1&mb_order_count_2=$mb_order_count_2&mb_order_price_1=$mb_order_price_1&mb_order_price_2=$mb_order_price_2&mb_join_channel=$mb_join_channel&mb_mailling=$mb_mailling&mb_sms=$mb_sms&mb_last_login_day=$mb_last_login_day&mb_sex=$mb_sex&mb_sns_type=$mb_sns_type&search_tr_detail=$search_tr_detail&mb_access=$mb_access&mb_black=$mb_black&it_asign=$it_asign";

$list = $_SERVER['PHP_SELF'];
$form = str_replace("list","form",$list);
$table = "nfor_member";
$id = "mb_no";

?>