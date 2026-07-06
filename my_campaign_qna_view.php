<?php
include "path.php";

$nfor[title] = "캠페인문의내역";

if(!$member[mb_no]) alert("로그인하셔야 이용가능합니다","login.php?url=$_SERVER[PHP_SELF]");

$qna = sql_fetch("select * from nfor_campaign_qna where qa_id='$qa_id'");

if(!$member[mb_no] or ($member[mb_no] and $qna[qa_insert_id] <> $member[mb_no])) alert("잘못된 접속입니다");

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>