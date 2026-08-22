<?php
include_once "path.php";

$lg_exe = basename($PHP_SELF);

sql_query("insert nfor_crontab_log set lg_datetime=NOW(), lg_exe='$lg_exe'");

// 하루 1번 실행

$co_bank_expire = date("Ymd");
$que = sql_query("select * from nfor_campaign_order where co_pay_step='4' and date_format(co_bank_expire,'%Y%m%d') < '$co_bank_expire'");
while($data = sql_fetch_array($que)){
	sql_query("update nfor_campaign_order set co_pay_step='5', co_cancel_datetime=NOW() where co_id='$data[co_id]'");
	sql_query("update nfor_campaign set cp_pay_step='5' where cp_id='$data[co_cp_id]'");	
}


if($config[cf_contents_end_use]=="1" and $config[cf_contents_end_day] > 0){
	$rv_cp_contents_edatetime = date("Ymd",strtotime("+{$config[cf_contents_end_day]} day"));
	$que = sql_query("select * from nfor_review where rv_step='2' and rv_delete='0' and date_format(rv_cp_contents_edatetime,'%Y%m%d') = '$rv_cp_contents_edatetime'");
	while($data = sql_fetch_array($que)){
		nfor_send("review_end", $data[rv_mb_email], $data[rv_mb_hp], $data[rv_mb_no], $data[rv_id]);
	}
}


// 저장 기간이 지난 최근검색어 키워드 삭제
if($config[cf_recent_day] >= 1){
	$se_datetime = date("Y-m-d", strtotime("-{$config[cf_recent_day]} day"));
	sql_query("delete from nfor_search where date_format(se_datetime,'%Y-%m-%d') <= '$se_datetime'");
}

// 인기검색어 순위 업데이트
keyword_update();

// 선정 후 N영업일 미신고자 구매 독촉 알림톡 (요청:양근형 2026-06-30) — 템플릿코드 미설정 시 무발송(안전)
include_once "buy_remind.php";

// 결과일이 지난 회차의 광고주 공개 보고서를 자동 생성한다.
// 한 번에 20건만 처리해 기존 일일 크론 부하를 제한하고, 다음 실행에서 이어서 소급한다.
include_once $nfor[path]."/lib/mc_campaign_report.lib.php";
$mc_report_batch = mc_campaign_report_generate_missing(20);
if(!empty($mc_report_batch['failed'])){
	@error_log("[metacrew-report] batch failed=".(int)$mc_report_batch['failed']." ".json_encode($mc_report_batch['errors'], JSON_UNESCAPED_UNICODE));
}

exit;

// 휴면회원 자동삭제(개인정보유효기간 설정일까지만 보관) - 적용이후 복구불가
$ymd = date("Y-m-d",strtotime("-".$member_config[cf_mb_valid_date]));
echo ("update nfor_member set mb_leave_datetime=NOW(), mb_secession='휴면회원 삭제', mb_out_datetime=NOW(), mb_password='', mb_name='', mb_email='', mb_level='', mb_birthday_type='', mb_birthday='', mb_sex='', mb_hp='', mb_tel='', mb_zipcode='', mb_addr1='', mb_addr2='', mb_mailling='', mb_sms='', mb_cp_name='', mb_cp_photo='', mb_photo='', mb_ident='', mb_bank_name='', mb_bank_account='', mb_bank_agree='', mb_cp_bank_name='', mb_cp_bank_account='', mb_cp_bank_account_holder='', mb_cp_ceo='', mb_cp_number='', mb_cp_zipcode='', mb_cp_addr1='', mb_cp_addr2='', mb_nick='', mb_facebook_id='', mb_kakao_id='', mb_naver_id='', mb_cp_type1='', mb_cp_type2='', mb_valid_date='', mb_join_channel='', mb_admin='', mb_asign='' where DATE_FORMAT(mb_login_datetime,'%Y-%m-%d') < '$ymd' and mb_valid_date='".$member_config[cf_mb_valid_date]."'");


?>ok
