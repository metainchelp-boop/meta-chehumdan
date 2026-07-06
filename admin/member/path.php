<?php
$path = "../..";
include_once("$path/nfor.php");

if(!$member['mb_no']) alert_close("잘못된 접근입니다");

if(!$member['mb_admin']) alert_close("잘못된 접근입니다");

if($member['mb_admin'] < 7) alert_close("최고관리자 또는 부관리자만 이용가능합니다");

if($_SERVER['PHP_SELF'] <> "/admin/member/".basename($_SERVER['PHP_SELF'])) alert_close("접근권한이 없습니다");
if(substr_count($_SERVER['PHP_SELF'], '/') > 3) alert_close("접근권한이 없습니다");

if($_SERVER['SCRIPT_NAME']<>$_SERVER['PHP_SELF']) alert_close("접근권한이 없습니다");