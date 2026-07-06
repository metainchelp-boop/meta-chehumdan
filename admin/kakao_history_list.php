<?php
include_once "path.php";



if(!$page) $page = "1";
if(!$limit) $limit = "20";

if(!$startdate) $startdate = date("Ymd");
if(!$enddate) $enddate = date("Ymd");


$history_list = kakao_alarm_history_list($page, $limit, $startdate, $enddate);

include_once "head.php";


print_r($history_list);



?>


<?php
include_once "tail.php";
?>