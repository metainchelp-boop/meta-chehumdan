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

if($nf_id) $write = sql_fetch("select * from nfor_bbs_{$tbl} where nf_id='$nf_id'");

if(($member[mb_no] and $write[nf_mb_no]==$member[mb_no]) or $is_admin){
	$write[nf_access] = "1";
} else{
	$write[nf_access] = "0";
}

for($i=0; $i<=10; $i++){
	if($write["nf_img".$i]){
		$write[nf_img_thumb][] = thumbnail("$nfor[path]/data/board/$tbl/".$write["nf_img".$i],140,140,0,1);
		$write[nf_img][] = $write["nf_img".$i];
	}
}

if($mode=="upload"){
	if($uploadfile = file_upload($nfor[path]."/data/board/$tbl/", $_FILES["uploadfile"])){
		$thumbnail = thumbnail("$nfor[path]/data/board/$tbl/".$uploadfile,80,80,0,1);
		echo json_encode(array('success' => true, 'filename'=>$uploadfile, 'thumbnail'=>$thumbnail));
	} else{
		echo json_encode(array('success' => false, 'msg' => 'error msg'));  
	}
	exit;
}

if($mode=="insert"){

	foreach($_POST as $key => $value){
		if(!is_array($_POST[$key])) $$key = htmlspecialchars(strip_tags($value));
	}

	for($i=0; $i<10; $i++){
		if($nf_img[$i]){
			$add_sql .= ", nf_img{$i}='{$nf_img[$i]}'";
		} else{
			$add_sql .= ", nf_img{$i}=''";
		}
	}

	if(!$member[mb_no]) json_return("로그인하셔야 이용가능합니다","login");
	//if(!$nf_category) json_return("분류를 선택해주세요","nf_category");
	if(!$nf_subject) json_return("제목을 입력해주세요","nf_subject");
	if(!$nf_memo) json_return("내용을 입력해주세요","nf_memo");

	$nf_mb_nick = $member[mb_nick]?$member[mb_nick]:$member[$member_config[cf_mb_id_type]];

	sql_query("insert nfor_bbs_{$tbl} set nf_mb_nick='$nf_mb_nick', nf_category='$nf_category', nf_subject='$nf_subject', nf_memo='$nf_memo', nf_mb_id='$member[mb_id]',  nf_datetime=NOW(), nf_hit='0', nf_like='0', nf_mb_no='$member[mb_no]', nf_ip='$REMOTE_ADDR' $add_sql");

	$return[url] = "board_list.php?tbl=".$tbl;
	json_return("정상적으로 등록되었습니다", "ok");
}

if($mode=="update"){
	
	foreach($_POST as $key => $value){
		if(!is_array($_POST[$key])) $$key = htmlspecialchars(strip_tags($value));
	}

	for($i=0; $i<10; $i++){
		if($nf_img[$i]){
			$add_sql .= ", nf_img{$i}='{$nf_img[$i]}'";
		} else{
			$add_sql .= ", nf_img{$i}=''";
		}
	}

	if(!$member[mb_no]) json_return("로그인하셔야 이용가능합니다","login");
	if(!$is_admin and $member[mb_no] <> $write[nf_mb_no]) json_return("로그인하셔야 이용가능합니다","mb_no");
	//if(!$nf_category) json_return("분류를 선택해주세요","nf_category");
	if(!$nf_subject) json_return("제목을 입력해주세요","nf_subject");
	if(!$nf_memo) json_return("내용을 입력해주세요","nf_memo");

	sql_query("update nfor_bbs_{$tbl} set nf_category='$nf_category', nf_subject='$nf_subject', nf_memo='$nf_memo' $add_sql where nf_id='$nf_id'");

	$return[url] = "board_view.php?tbl=".$tbl."&nf_id=".$nf_id;
	json_return("정상적으로 수정되었습니다", "ok");
}

$que = sql_query("select * from nfor_value where val_code='$tbl' order by val_rank asc");
if(sql_num_rows($que)){
	$admin[nf_category][""] = "선택";
	while($row = sql_fetch_array($que)){
		$admin[nf_category][$row[val_name]] = $row[val_name];
	}
}

if($json=="form"){
	$return["form"] = $admin;
	$return["value"] = $write;
	json_return($nfor[title], "ok");
}



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
if(file_exists($nfor[skin_path]."board_form.".$board[bo_skin].".php")){
	include_once $nfor[skin_path]."board_form.".$board[bo_skin].".php";
}
if(file_exists($nfor[skin_path].$board[bo_include_tail])){
	include_once $nfor[skin_path].$board[bo_include_tail];
}
?>