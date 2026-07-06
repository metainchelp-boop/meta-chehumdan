<?php
include_once "path.php";

/* 일단위 실행 */
$lg_exe = basename($PHP_SELF);
sql_query("insert nfor_crontab_log set lg_datetime=NOW(), lg_exe='$lg_exe'");


?>