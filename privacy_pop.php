<?php
include_once "path.php";

$nfor[title] = "개인정보취급방침";

$agreement = sql_fetch("select * from nfor_agreement where ag_code='private'");

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>