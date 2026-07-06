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

$admin['cs_reply_state'] = array("전체","답변대기","답변완료");

if($mode=="delete"){
	if(!$member['mb_no']) json_return("로그인하셔야 이용가능합니다","mb_no");
	if(!$cs_id) json_return("글번호가 입력되지 않았습니다","cs_id");
	sql_query("delete from nfor_customer where cs_id='$cs_id' and cs_insert_id = '{$member['mb_no']}'");
	json_return("삭제되었습니다","ok");
}

if(!$sort) $sort = "cs_id desc";

$sql_common = " from nfor_customer ";

$sql_search = " where cs_insert_id = '{$member['mb_no']}' ";

$sql_order = " order by ".safe_orderby($sort)." ";

if($menu_config['menu_limit']){
	$total_count = $menu_config['menu_limit'];
} else{
	$sql = " select count(*) as cnt
							$sql_common
							$sql_search
							$sql_order ";
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];
}

$rows = $menu_config['menu_rows'];
$total_page  = ceil($total_count / $rows);
if(!$page) $page = 1;
$from_record = ($page - 1) * $rows;
$sql = " select *
				$sql_common
				$sql_search
				$sql_order
				limit $from_record, $rows ";
$result = sql_query($sql);
$return["list"] = array();
for($i=0; $row=sql_fetch_array($result); $i++){
	$res['cs_num'] = $total_count - ($page - 1) * $rows - $i;
	$res['cs_id'] = $row['cs_id'];
	$res['cs_category'] = $row['cs_category'];
	$res['cs_insert_datetime'] = date("Y.m.d.",strtotime($row['cs_insert_datetime']));
	$res['cs_subject'] = $row['cs_subject'];
	$res['cs_reply_state'] = $row['cs_reply_state'];
	$res['cs_reply_state_text'] = admin_echo($row,"cs_reply_state");
	$return["list"][] = $res;

	if($menu_config['menu_limit'] and $menu_config['menu_limit'] == $res['cs_num']){
		$return["last_page"] = 1;
		$i++;
		break;
	}
}

if($return["last_page"] or $rows<>$i){
	$return["last_page"] = 1; // 마지막페이지 체크
} else{
	$return["last_page"] = 0;
}

$return["rows"] = $rows; // 한페이지글수
$return["total_count"] = $total_count; // 전체글수
$return["count"] = $i; // 현제불러오는글수
$return["page"] = $page;

if($json=="list"){
	json_return($nfor['title'],"ok");
}

$scroll_load = $menu_config['menu_scroll'];

if(!$scroll_load) $pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "?$qstr&page=");

if(!$member['mb_no']) goto_url("login.php?url=".basename($_SERVER['PHP_SELF']));

include_once $nfor['skin_path'].basename($_SERVER['PHP_SELF']);
?>