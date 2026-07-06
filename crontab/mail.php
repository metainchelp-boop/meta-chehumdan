<?php
include_once "path.php";

/* 1분단위 실행 */
$lg_exe = basename($PHP_SELF);
sql_query("insert nfor_crontab_log set lg_datetime=NOW(), lg_exe='$lg_exe'");

$que = sql_query("select * from nfor_mail_log where ml_send='0'");
while($data = sql_fetch_array($que)){
	mailer($data[ml_send_name], $data[ml_send_email], $data[ml_email], $data[ml_subject], $data[ml_memo], 1);
	sql_query("update nfor_mail_log set ml_send='1' where ml_id='$data[ml_id]'");
}
?>