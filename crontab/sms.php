<?php
include_once "path.php";

$limit = "500"; // 1회 최대발송수는 500을 넘어서는 안됨

$sl_datetime = date("Y-m-d H:i:s",strtotime("-24 hour")); // 발송요청후 24시간이내까지만 보내기를 허용함

$lg_exe = basename($PHP_SELF);
sql_query("insert nfor_crontab_log set lg_datetime=NOW(), lg_exe='$lg_exe'");

$sl_send = time();

// 알림톡
$que = sql_query("select * from nfor_sms_log where sl_send='0' and sl_templt_code<>'' and sl_datetime > '$sl_datetime' limit $limit");
while($data = sql_fetch_array($que)){
	$sl_result_code = kakao_alarm_send($data[sl_templt_code], $data[sl_send_hp], $data[sl_hp], $data[sl_subject], $data[sl_msg]);
	sql_query("update nfor_sms_log set sl_result_datetime=NOW(), sl_send='$sl_send', sl_result_code='$sl_result_code' where sl_id='$data[sl_id]'");
}

// SMS
$i = 1;
$que = sql_query("select * from nfor_sms_log where sl_send='0' and sl_templt_code='' and sl_datetime > '$sl_datetime' limit $limit");
while($data = sql_fetch_array($que)){
	$sms['rec_'.$i] = $data[sl_hp]; // 수신번호
	$sms['msg_'.$i] = $data[sl_msg]; // 수신내용
	$sms['sbj_'.$i] = $data[sl_subject]; // 수신제목
	sql_query("update nfor_sms_log set sl_send='$sl_send' where sl_id='$data[sl_id]'");
	$i++;
}
$sms['cnt'] = $i-1; // 전송건수

$sl_result_code = aligo_sms_send($sms);

sql_query("update nfor_sms_log set sl_result_datetime=NOW(), sl_result_code='$sl_result_code' where sl_send='$sl_send' and sl_templt_code=''");

?>