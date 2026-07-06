<?php
$admin[keyword_type] = array(""=>"전체", "rv_cp_id"=>"캠페인번호", "rv_id"=>"신청번호", "rv_dy_name" => "받는분 이름", "rv_dy_hp" => "받는분 전화번호", "rv_mb_name" => "회원 이름", "rv_mb_hp" => "회원 전화번호", "rv_mb_nick" => "회원 닉네임", "rv_mb_id" => "회원 아이디", "rv_mb_email" => "회원 이메일", "rv_msg" => "신청자 한마디", "rv_memo" => "신청필수 정보");

$admin[rv_supply_no][""] = "전체";
$que = sql_query("select mb_no, mb_name, mb_cp_name, mb_tel from nfor_member where mb_leave_datetime='' and mb_cp_name<>'' and mb_admin='1' order by ( case when mb_cp_name between '가' and  '金' then 1 when mb_cp_name between 'a' and 'z'  THEN 3 when mb_cp_name between 'A' and 'Z' then 4  when mb_cp_name between '0' and '9' then 5  when mb_cp_name between '!' and '.' then 6 else 0  end ), mb_cp_name asc");
while($row = sql_fetch_array($que)){
	$admin[rv_supply_no][$row[mb_no]] = $row[mb_cp_name];
}

$admin[rv_md_no][""] = "전체";
$que = sql_query("select mb_no, mb_name, mb_cp_name from nfor_member where mb_leave_datetime='' and mb_admin='2' order by ( case when mb_cp_name between '가' and  '金' then 1 when mb_cp_name between 'a' and 'z'  THEN 3 when mb_cp_name between 'A' and 'Z' then 4  when mb_cp_name between '0' and '9' then 5  when mb_cp_name between '!' and '.' then 6 else 0  end ), mb_cp_name asc");
while($row = sql_fetch_array($que)){
	$admin[rv_md_no][$row[mb_no]] = $row[mb_name];
}

$admin[rv_cp_id][""] = "전체";
if($rv_supply_no or $member[mb_admin]=="1"){
	if(!$rv_supply_no) $rv_supply_no = $member[mb_no];
	$que = sql_query("select * from nfor_campaign where cp_supply_no='$rv_supply_no' order by ( case when cp_subject between '가' and  '金' then 1 when cp_subject between 'a' and 'z'  THEN 3 when cp_subject between 'A' and 'Z' then 4  when cp_subject between '0' and '9' then 5  when cp_subject between '!' and '.' then 6 else 0  end ), cp_subject asc");
} elseif($rv_md_no or $member[mb_admin]=="2"){
	if(!$rv_md_no) $rv_md_no = $member[mb_no];
	$que = sql_query("select * from nfor_campaign where cp_md_no='$rv_md_no' order by ( case when cp_subject between '가' and  '金' then 1 when cp_subject between 'a' and 'z'  THEN 3 when cp_subject between 'A' and 'Z' then 4  when cp_subject between '0' and '9' then 5  when cp_subject between '!' and '.' then 6 else 0  end ), cp_subject asc");
} else{
	$que = sql_query("select * from nfor_campaign where (1) order by ( case when cp_subject between '가' and  '金' then 1 when cp_subject between 'a' and 'z'  THEN 3 when cp_subject between 'A' and 'Z' then 4  when cp_subject between '0' and '9' then 5  when cp_subject between '!' and '.' then 6 else 0  end ), cp_subject asc");
}
while($row = sql_fetch_array($que)){
	$admin[rv_cp_id][$row[cp_id]] = $row[cp_subject];
}


$admin[rv_cp_type] = array("전체","배송형","방문형");

$admin[rv_mb_sex] = array(""=>"전체", "M"=>"남자", "F"=>"여자");

$admin[rv_is_mobile] = array("전체","PC","모바일");

$admin[rv_asign_show] = array("전체","노출","미노출");

if(basename($PHP_SELF)=="review_wait_list.php"){
	$admin[period_type] = array("rv_datetime" => "신청일자");
}
if(basename($PHP_SELF)=="review_asign_list.php" or basename($PHP_SELF)=="review_asign_cancel_list.php"){
	$admin[period_type] = array("rv_asign_datetime" => "선정일자", "rv_datetime" => "신청일자");
}
if(basename($PHP_SELF)=="review_post_list.php"){
	$admin[period_type] = array("rv_reg_datetime" => "URL등록일자", "rv_asign_datetime" => "선정일자", "rv_datetime" => "신청일자");
}
if(basename($PHP_SELF)=="review_edit_list.php"){
	$admin[period_type] = array("rv_reg_datetime" => "URL등록일자", "rv_asign_datetime" => "선정일자", "rv_datetime" => "신청일자");
}
if(basename($PHP_SELF)=="review_post_asign_list.php"){
	$admin[period_type] = array("rv_confirm_datetime" => "리뷰등록확인일자", "rv_reg_datetime" => "URL등록일자", "rv_asign_datetime" => "선정일자", "rv_datetime" => "신청일자");
}
if(basename($PHP_SELF)=="review_cancel_list.php"){
	$admin[period_type] = array("rv_datetime" => "신청일자");
}

if(basename($PHP_SELF)=="review_hidden_list.php"){
	$admin[period_type] = array("rv_datetime" => "신청일자");
}

$admin[rv_media] = $nfor[cp_media];

$admin[rv_step] = array(""=>"전체", "1"=>"신청", "8"=>"1차후보", "2"=>"선정", "3" => "검수요청", "4" => "등록완료", "5" => "미선정", "6" => "선정후취소", "7" => "수정요청");

$qstr .= "&rv_is_mobile=$rv_is_mobile&rv_supply_no=$rv_supply_no&rv_md_no=$rv_md_no&rv_cp_id=$rv_cp_id";

$list = $_SERVER[PHP_SELF];
$form = str_replace("list","form",$list);
$table = "nfor_review";
$id = "rv_id";

$sql_common = " from $table ";
?>