<?php
include_once "path.php";

$nfor[title] = "인플루언서";

$influencer = sql_fetch("select * from nfor_member where mb_no='$mb_no'");

if($json=="view"){
	$return = $influencer;
	json_return($nfor[title],"ok");
}

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>