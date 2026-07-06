<?php
include_once "path.php";

$nfor[title] = "지역으로 찾기";


$que = sql_query("select * from nfor_sigudong where sd_si<>'' and sd_gu='' and sd_dong=''");
while($row = sql_fetch_array($que)){
	$admin[area_category][$row[sd_si]] = $row[sd_si];
}


include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>