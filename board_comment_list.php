<?php
include_once "path.php";

if($nf_id) $nf_id = str_number($nf_id);

// 보안패치
$bo_tbl_array = array();
$que = sql_query("select * from nfor_board where 1");
while($row = sql_fetch_array($que)){
	$bo_tbl_array[$row['bo_tbl']] = $row['bo_tbl'];
}
if($tbl){
	if (!array_key_exists($tbl, $bo_tbl_array)) {
		$tbl = "";
	}
}
// 보안패치





if(!$write[nf_id]){
	$write = sql_fetch("select * from nfor_bbs_{$tbl} where nf_id='$nf_id'");
}

$nfor[title] = "코멘트";

$qstr = "nf_id=$nf_id";

$sort = "ct_id desc";

$sql_common = " from nfor_comment_{$tbl} ";

$sql_search = " where ct_parent='0' and ct_insert_datetime <= '$nfor[ymdhis]' and ct_nf_id='$nf_id' ";

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
	$res[ct_num] = $total_count - ($page - 1) * $rows - $i;
	
	$res[ct_id] = $row[ct_id];
	$res[ct_insert_datetime] = date("Y/m/d H:i:s",strtotime($row[ct_insert_datetime]));

	if($row[ct_secret]){
		if($is_admin or $write[nf_mb_no]==$member[mb_no] or $row[ct_mb_no]==$member[mb_no]){
			$res[ct_memo] = $row[ct_memo];
		} else{
			$res[ct_memo] = "비밀글 입니다";
		}
	} else{
		$res[ct_memo] = $row[ct_memo];
	}

    $res[ct_mb_nick] = $row[ct_mb_nick];


	$res[ct_mb_id] = $row[ct_mb_id];
		

    $res[ct_mb_no] = $row[ct_mb_no];

	if(($member[mb_no] and $row[ct_mb_no]==$member[mb_no]) or $is_admin){
		$res[ct_access] = "1";
	} else{
		$res[ct_access] = "0";
	}

	if($row[ct_delete]){
		$res[ct_access] = "0";
	}


	$mb = sql_fetch("select mb_photo from nfor_member where mb_no='$row[ct_mb_no]'");
	$res[ct_mb_photo] = $mb[mb_photo]?thumbnail($nfor[path]."/data/member/mb/".$mb[mb_photo],60,60,0,1):"";
	


	$reply_arr = array();
	$row_reply_que = sql_query("select * from nfor_comment_{$tbl} where ct_parent='$row[ct_id]'");
	while($row_reply = sql_fetch_array($row_reply_que)){
		$reply[ct_id] = $row_reply[ct_id];

        $reply[ct_mb_nick] = $row_reply[ct_mb_nick];

        $reply[ct_mb_id] = $row_reply[ct_mb_id];

		$mb = sql_fetch("select mb_photo from nfor_member where mb_no='$row_reply[ct_mb_no]'");
		$reply[ct_mb_photo] = $mb[mb_photo]?thumbnail($nfor[path]."/data/member/mb/".$mb[mb_photo],60,60,0,1):"";
		

		$reply[ct_parent] = $row_reply[ct_parent];
		$reply[ct_memo] = $row_reply[ct_memo];
		$reply[ct_insert_datetime] = date("Y.m.d.",strtotime($row_reply[ct_insert_datetime]));

		if(($member[mb_no] and $row_reply[ct_mb_no]==$member[mb_no]) or $is_admin){
			$reply[ct_access] = "1";
		} else{
			$reply[ct_access] = "0";
		}

		if($row_reply[ct_delete]){
			$reply[ct_access] = "0";
		}

		$reply_arr[] = $reply;
	}
	$res[reply] = $reply_arr;

	$return["comment_list"][] = $res;

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

if(!$board[bo_skin]) $board[bo_skin] = "basic";
if(file_exists($nfor[skin_path]."board_comment_list.".$board[bo_skin].".php")){
	include_once $nfor[skin_path]."board_comment_list.".$board[bo_skin].".php";
}

?>