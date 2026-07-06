<?php
include_once "path.php";

$nfor[title] = "출금신청";

if($mode=="insert"){
	// 보안패치
	foreach($_POST as $key => $value){
		if(!is_array($_POST[$key])) $$key = htmlspecialchars(strip_tags($value));
	}


	$pb_point = str_number($pb_point);
	if($member[mb_point] < $config[cf_get_money]) json_return("출금신청은 포인트가 ".number_format($config[cf_get_money])."원 이상 모여야  가능합니다","pb_point");
	if($pb_point<0) json_return("음수를 입력할수 없습니다","pb_point");
	if(!$member[mb_no]) json_return("로그인하셔야 이용가능합니다","mb_no");
	if(!$pb_name) json_return("예금주를 입력해주세요","pb_name");
	if(!$pb_bank) json_return("입금은행을 선택해주세요","pb_bank");
	if(!$pb_bank_number) json_return("계좌번호를 입력해주세요","pb_bank_number");
	if(!$pb_point) json_return("인출하신 포인트를 입력해주세요","pb_point");
	if($member[mb_point] < $pb_point) json_return("출금신청 요청하신 포인트가 보유하신 포인트를 초과했습니다.\n다시 확인해 주세요.","pb_point");
	if($pb_point < $config[cf_get_money]) json_return("출금금액은 최소 ".number_format($config[cf_get_money])."원 이상 신청해주세요","pb_point");
	if(substr($pb_point,-1) <> "0") json_return("10원단위로만 출금가능합니다","pb_point");

	if($config[cf_jumin]=="1"){
		if(!$pb_jumin1) json_return("주민번호를 입력해주세요","pb_jumin1");
		if(!$pb_jumin2) json_return("주민번호를 입력해주세요","pb_jumin2");
		if(strlen($pb_jumin1)<6) json_return("주민번호를 정확히 입력해주세요","pb_jumin1");
		if(strlen($pb_jumin2)<7) json_return("주민번호를 정확히 입력해주세요","pb_jumin2");
		$pb_jumin = $pb_jumin1."-".$pb_jumin2;
	}

	if(!$pb_file1 and $config[cf_jumin_file]=="1") json_return("주민등록 사본을 첨부해주세요","pb_file1");
	if(!$pb_file2 and $config[cf_bank_file]=="1") json_return("통장 사본을 첨부해주세요","pb_file2");

	insert_point($member[mb_no], $pb_point*-1, "출금신청", "700",$member[mb_no]."_".$ftimestamp);

	sql_query("insert nfor_point_bank set pb_file1='$pb_file1', pb_filename1='$pb_filename1', pb_file2='$pb_file2', pb_filename2='$pb_filename2', pb_jumin='$pb_jumin', pb_name='$pb_name', pb_bank='$pb_bank', pb_bank_number='$pb_bank_number', pb_point='$pb_point', pb_datetime=NOW(), pb_mb_no='$member[mb_no]', pb_mb_name='$member[mb_name]', pb_mb_id='$member[mb_id]', pb_step='1'");
	
	json_return("출금신청이 완료되었습니다","ok");
}

$pt_point_sum = sql_fetch("select sum(pt_point) as pt_point_sum from nfor_point where pt_mb_no='$member[mb_no]' and pt_type<>'700'");

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>