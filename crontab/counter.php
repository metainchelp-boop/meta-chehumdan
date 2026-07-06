<?php
include_once "path.php";



$lg_exe = basename($PHP_SELF);

sql_query("insert nfor_crontab_log set lg_datetime=NOW(), lg_exe='$lg_exe'");




$_GET['period_sdate'] = date("Y-m-d", strtotime("-7day"));
$_GET['period_edate'] = date("Y-m-d");


$dimensions = "date";

include "$nfor[path]/admin/inc_analytics.php";


// 오늘
$wr_today = $row2[0];

// 어제
$wr_yesterday = $row2[1];

// 굼주
$wr_week = $sum_count;

















$_GET['period_sdate'] = date("Y-m-d", strtotime("-10 year"));
$_GET['period_edate'] = date("Y-m-d");


$dimensions = "year";

include "$nfor[path]/admin/inc_analytics.php";


// 굼주
$wr_sum = $sum_count;















$cf_count = "오늘:$wr_today,어제:$wr_yesterday,금주:$wr_week,전체:$wr_sum";

sql_query("update nfor_config set cf_count = '$cf_count'");


?>