<?php
include_once "path.php";

$nfor[title] = "받은쪽지함";

if($mode=="list_delete"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		sql_query("update nfor_note set no_receive_del='1' where no_id='{$_POST['no_id'][$k]}'");
	}

	$mb_note = sql_fetch("select count(*) as cnt from nfor_note where no_receive_id='$member[mb_id]' and no_receive_datetime='0000-00-00 00:00:00' and no_receive_del='0'");
	sql_query("update nfor_member set mb_note='$mb_note[cnt]' where mb_id='$member[mb_id]'");
	
	$return[url] = "note_receive_list.php";
	
	json_return("정상적으로 삭제되었습니다","ok");
}

if(!$sort) $sort = "no_id desc";

$sql_common = " from nfor_note ";

$sql_search = " where no_receive_id='$member[mb_id]' and no_receive_del='0' ";

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
	$res = array();
	$res[no_num] = $total_count - ($page - 1) * $rows - $i;
	$res[no_id] = $row[no_id];
	$res[no_send_id] = $row[no_send_id];
	$res[no_memo] = cut_str(strip_tags($row[no_memo]),100);
	$res[no_send_datetime] = date("Y.m.d.",strtotime($row[no_send_datetime]));
	$res[no_receive_datetime] = substr($row[no_receive_datetime],0,10)=="0000-00-00"?"-":date("Y.m.d.",strtotime($row[no_receive_datetime]));
	$return["list"][] = $res;

	if($menu_config[menu_limit] and $menu_config[menu_limit] == $res[no_num]){
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

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>