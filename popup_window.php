<?php
include_once "path.php";

$popup = sql_fetch("select * from nfor_popup where pop_id='$pop_id'");

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>