<?php
$path = "..";
include_once("$path/nfor.php");

if(substr(str_replace(".","",$_SERVER['SERVER_ADDR']),-3)<>"383") exit;
?>