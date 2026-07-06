<?php
$jwt_check = "1";

include_once "path.php";

/*뒤로가기 방지*/
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-reval!!idate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$nfor[title] = "캠페인문의/답변내역";

if(!$sort) $sort = "qa_id desc";

$sql_common = " from nfor_campaign_qna a left join nfor_campaign b on ( a.qa_cp_id = b.cp_id ) ";

$sql_search = " where a.qa_insert_datetime <= '$nfor[ymdhis]' and a.qa_insert_id='$member[mb_no]' and a.qa_parent='0' ";

$sql_order = " order by ".safe_orderby($sort)." ";

if($menu_config[menu_limit]){
	$total_count = $menu_config[menu_limit];
} else{
	$sql = " select count(*) as cnt
							$sql_common
							$sql_search
							$sql_order ";
	$row = sql_fetch($sql);
	$total_count = $row[cnt];
}

$rows = $menu_config[menu_rows];
$total_page  = ceil($total_count / $rows);
if(!$page) $page = 1;
$from_record = ($page - 1) * $rows;
$sql = " select *
				$sql_common
				$sql_search
				$sql_order
				limit $from_record, $rows ";
$result = sql_query($sql);

for($i=0; $row=sql_fetch_array($result); $i++){
	$res[qa_num] = $total_count - ($page - 1) * $rows - $i;
	$res[qa_id] = $row[qa_id];
	$res[qa_cp_id] = $row[qa_cp_id];
	$res[qa_cp_img] = $row[cp_img]?thumbnail("$nfor[path]/data/campaign/$row[cp_img]",300,300,0,1):$nfor[skin_path]."img/no_img.png";
	$res[qa_insert_datetime] = date("Y.m.d.",strtotime($row[qa_insert_datetime]));
	$res[qa_cp_subject] = $row[qa_cp_subject];
	$res[qa_memo] = nl2br($row[qa_memo]);
	if($row[qa_reply]){
		$res[qa_reply_state] = "답변완료";
	} else{
		$res[qa_reply_state] = "답변대기";
	}
	$reply_arr = "";
	$que2 = sql_query("select * from nfor_campaign_qna where qa_parent='$row[qa_id]'");
	while($data2 = sql_fetch_array($que2)){
		$reply[qa_mb_id] = $data2[qa_mb_id];
		$reply[qa_parent] = $data2[qa_parent];
		$reply[qa_memo] = nl2br($data2[qa_memo]);
		$reply[qa_insert_datetime] = date("Y.m.d. H:i",strtotime($data2[qa_insert_datetime]));
		$reply_arr[] = $reply;
	}
	$res[reply] = $reply_arr;
	$return["list"][] = $res;

	if($menu_config[menu_limit] and $menu_config[menu_limit] == $res[qa_num]){
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
	json_return($nfor[title],"ok");
}

$scroll_load = $menu_config[menu_scroll];

if(!$scroll_load) $pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");

if(!$member[mb_no]) goto_url("login.php?url=".basename($_SERVER[PHP_SELF]));

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>