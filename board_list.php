<?php
include_once "path.php";




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






// 배너
$bn_code = "board_list_".$tbl;
$bn_code_exp = explode("||",$bn_code);
for($e=0; $e<count($bn_code_exp); $e++){

	$result = sql_query("select * from nfor_banner where bn_use='1' and bn_code='{$bn_code_exp[$e]}' and ((bn_period_use='1') or (bn_period_use='2' and bn_sdatetime <='$nfor[ymdhis]' and bn_edatetime >='$nfor[ymdhis]')) order by bn_rank desc");
	for($i=0; $row=sql_fetch_array($result); $i++){
		$res = array();
		$res[bn_alt] = $row[bn_alt];
		$res[bn_img] = $nfor[url]."/data/banner/".$row[bn_img];	
		$res[bn_href] = $row[bn_href];
		$res[bn_img_over] = $nfor[url]."/data/banner/".$row[bn_img_over];	
		$res[bn_target] = $row[bn_target];

		
		$return[banner][$bn_code_exp[$e]]["list"][] = $res;
	}
	$return[banner][$bn_code_exp[$e]]["total_count"] = $i;

}






$board = sql_fetch("select * from nfor_board where bo_tbl='$tbl'");
if(!$board[bo_tbl]) json_return("잘못된 접근입니다", "tbl");

$nfor[title] = $board[bo_name];

$qstr = "tbl=$board[bo_tbl]&nf_category=$nf_category";

$admin[nf_category][""] = "선택";
$que = sql_query("select * from nfor_value where val_code='$tbl' order by val_rank asc");
while($row = sql_fetch_array($que)){
	$admin[nf_category][$row[val_name]] = $row[val_name];
}

$admin[orderby_value] = array('nf_datetime desc'=>'최신순','nf_hit desc'=>'조회수');

if($json=="form"){
	$return["form"] = $admin;
	$return["value"] = $write;
	json_return($nfor[title], "ok");
}

if(!$sort) $sort = "nf_id desc";

$sql_common = " from nfor_bbs_$tbl ";

$sql_search = " where nf_view=1 ";

if($type_str){
	$exp_type_str = explode("||",$type_str);
	$str_type = " and ( ";
	for($i=0; $i<count($exp_type_str); $i++){
		if($i>0) $str_type .= " or ";
		$str_type .= " nf_category like '%{$exp_type_str[$i]}%'";
	}
	$str_type .= " ) ";
	$sql_search .= $str_type;	
}

if($date_period){
	$sql_search .= " and nf_datetime > '".date("Y-m-d H:i:s",strtotime("- $date_period"))."' ";
}

if($keyword){
	$sql_search .= " and ".sql_keyword($keyword_type, $keyword);
}

if($nf_category) $sql_search .= " and nf_category='$nf_category' ";

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

$rows = $is_mobile?$board[bo_mobile_rows]:$board[bo_pc_rows];
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
	$res[nf_num] = $total_count - ($page - 1) * $rows - $i;
	$res[nf_id] = $row[nf_id];
	$res[nf_category] = $row[nf_category];
	$res[nf_like] = number_format($row[nf_like]);
	$res[nf_img] = thumbnail("$nfor[path]/data/board/$tbl/".$row["nf_img0"],140,140,0,1);
	$res[nf_image] = array();
	for($k=0; $k<=10; $k++){
		if($row["nf_img".$k]){
			$res[nf_image][] = thumbnail("$nfor[path]/data/board/$tbl/".$row["nf_img".$k],140,140,0,1);
		}
	}
	if($member[mb_no]){
		$like = sql_fetch("select li_id from nfor_board_like where li_nf_id='$row[nf_id]' and li_mb_no='$member[mb_no]' and li_comment='0'");
		if($like[li_id]){
			$res[nf_like_is] = "on";
		} else{
			$res[nf_like_is] = "";
		}
	} else{
		$res[nf_like_is] = "";
	}
	$res[nf_subject] = $row[nf_subject];
	$res[nf_mb_id] = $row[nf_mb_id];
    $res[nf_mb_nick] = $row[nf_mb_nick]?$row[nf_mb_nick]:$row[nf_mb_id];
	$res[nf_memo] = $row[nf_memo];
	$res[nf_hit] = number_format($row[nf_hit]);	
	$res[nf_comment] = number_format($row[nf_comment]);	
	$res[nf_datetime] = date("Y.m.d.",strtotime($row[nf_datetime]));


	$mb = sql_fetch("select mb_photo from nfor_member where mb_no='$row[nf_mb_no]'");
	$res[nf_mb_photo] = $mb[mb_photo]?thumbnail($nfor[path]."/data/member/mb/".$mb[mb_photo],60,60,0,1):"";
	



	$return["list"][] = $res;

	if($menu_config[menu_limit] and $menu_config[menu_limit] == $res[nf_num]){
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

$scroll_load = $is_mobile?$board[bo_mobile_scroll]:$board[bo_pc_scroll];
if(!$scroll_load) $pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");

if(!$board[bo_skin]) $board[bo_skin] = "basic";
if(!$board[bo_include_head]) $board[bo_include_head] = "head.php";
if(!$board[bo_include_tail]) $board[bo_include_tail] = "tail.php";
if(!$board[bo_include_mobile_head]) $board[bo_include_mobile_head] = "head.php";
if(!$board[bo_include_mobile_tail]) $board[bo_include_mobile_tail] = "tail.php";

if($is_mobile){
	$board[bo_include_head] = $board[bo_include_mobile_head];
	$board[bo_include_tail] = $board[bo_include_mobile_tail];
}

if(file_exists($nfor[skin_path].$board[bo_include_head])){
	include_once $nfor[skin_path].$board[bo_include_head];
}
if(file_exists($nfor[skin_path]."board_list.".$board[bo_skin].".php")){
	include_once $nfor[skin_path]."board_list.".$board[bo_skin].".php";
}
if(file_exists($nfor[skin_path].$board[bo_include_tail])){
	include_once $nfor[skin_path].$board[bo_include_tail];
}
?>