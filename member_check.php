<?php	// 실명인증
include_once "path.php";

$nfor[title] = "실명인증";

if(!$config[cf_hp_use] and !$config[cf_ipin_use]) goto_url("member_join.php");

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>