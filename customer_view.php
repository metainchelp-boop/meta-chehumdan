<?php
$jwt_check = "1";

include_once "path.php";

/*뒤로가기 방지*/
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-reval!!idate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$nfor['title'] = "1:1문의";

$admin = array();
$admin['cs_reply_state'] = array("전체","답변대기","답변완료");

$customer = sql_fetch("select * from nfor_customer where cs_id='$cs_id' and cs_insert_id='{$member['mb_no']}'");

$customer['cs_insert_datetime'] = date("Y.m.d.",strtotime($customer['cs_insert_datetime']));
$customer['cs_reply_state_text'] = admin_echo($customer,"cs_reply_state");
$customer['cs_memo'] = nl2br($customer['cs_memo']);
$customer['cs_reply_memo'] = nl2br($customer['cs_reply_memo']);



if($customer['cs_file_array']){
	$customer['cs_file_href'] = array();
	$customer['cs_file_name'] = array();

	$cs_file_array = explode("||",$customer['cs_file_array']);
	$cs_filename_array = explode("||",$customer['cs_filename_array']);

	for($i=0; $i<count($cs_file_array); $i++){
		$customer_file_href[] = "{$nfor['path']}/file_download.php?file_tbl=customer&file_id={$customer['cs_id']}&file_number={$i}";
		$customer_file_name[] = $cs_filename_array[$i];
	}

	$customer['cs_file_href'] = $customer_file_href;
	$customer['cs_file_name'] = $customer_file_name;
}

if($json=="view"){
	if(!$member['mb_no']) json_return("로그인하셔야 이용가능합니다","mb_no");
	if(!$customer['cs_id']) json_return("존재하지 않는 글번호입니다","cs_id");

	$return = $customer;
	json_return($nfor['title'],"ok");
}

if(!$member['mb_no']) goto_url("login.php?url=customer_list.php");
if(!$customer['cs_id']) alert("존재하지 않는 글번호입니다");

include_once $nfor['skin_path'].basename($_SERVER['PHP_SELF']);
?>