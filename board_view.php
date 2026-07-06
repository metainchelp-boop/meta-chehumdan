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

if(!$tbl) json_return("테이블 코드가 넘어오지 않았습니다", "tbl");

$write = sql_fetch("select * from nfor_bbs_{$tbl} where nf_id='$nf_id'");

if($member[mb_no]){
	$like = sql_fetch("select li_id from nfor_board_like where li_nf_id='$write[nf_id]' and li_mb_no='$member[mb_no]' and li_comment='0'");
	if($like[li_id]){
		$write[nf_like_is] = "on";
	} else{
		$write[nf_like_is] = "";
	}
} else{
	$write[nf_like_is] = "";
}

if(($member[mb_no] and $write[nf_mb_no]==$member[mb_no]) or $is_admin){
	$write[nf_access] = "1";
} else{
	$write[nf_access] = "0";
}

for($i=0; $i<=10; $i++){
	if($write["nf_img".$i]){
		$write[nf_img_thumb][] = thumbnail("$nfor[path]/data/board/$tbl/".$write["nf_img".$i],140,140,0,1);
		$write[nf_img][] = thumbnail("$nfor[path]/data/board/$tbl/".$write["nf_img".$i],600,"",0,1);//"$nfor[path]/data/board/$tbl/".$write["nf_img".$i];
	}
}

if($mode=="like"){
	if(!$write[nf_id]){
		json_return("존재하지 않는 글번호입니다");
	} elseif(!$member[mb_no]){
		json_return("로그인하셔야 이용가능합니다");
	} else{
		if($like[li_id]){
			sql_query("delete from nfor_board_like where li_id='$like[li_id]' and li_mb_no='$member[mb_no]'");
		} else{
			sql_query("insert nfor_board_like set li_tbl='$tbl', li_nf_id='$write[nf_id]', li_mb_no='$member[mb_no]', li_comment='0', li_insert_datetime=NOW()");
		}
		$nf_like = sql_fetch("select count(*) as cnt from nfor_board_like where li_tbl='$tbl' and li_nf_id='$write[nf_id]' and li_comment='0'");
		sql_query("update nfor_bbs_{$tbl} set nf_like='$nf_like[cnt]' where nf_id='$write[nf_id]'");
		$return[nf_like] = number_format($nf_like[cnt]);
		if($like[li_id]){
			json_return("이미 좋아요 등록하신 게시글입니다","delete");
		} else{
			json_return("좋아요 등록하였습니다","insert");
		}
	}
}

if($mode=="delete"){
	if(!$member[mb_no]) json_return("로그인하셔야 이용가능합니다", "login");
	if(!$is_admin and $write[nf_mb_no] <> $member[mb_no]) json_return("잘못된 접근입니다", "login");
	if(!$write[nf_id]) json_return("고유번호가 넘어오지 않았습니다","nf_id");
	sql_query("delete from nfor_bbs_{$tbl} where nf_id='$nf_id'");
	json_return("정상적으로 삭제되었습니다", "ok");
}

if(!$_SESSION["hit_{$tbl}_{$nf_id}"]){
	sql_query("update nfor_bbs_{$tbl} set nf_hit = nf_hit + 1 where nf_id = '$nf_id'");
	$_SESSION["hit_{$tbl}_{$nf_id}"] = TRUE;
}

if($json=="form"){
	$return = $write;
	json_return($nfor[title],"ok");
}

if(!$write[nf_id]) alert("고유번호가 넘어오지 않았습니다");



$write[nf_memo] = nl2br($write[nf_memo]);
$write[nf_datetime] = date("Y.m.d.",strtotime($write[nf_datetime]));

$mb = sql_fetch("select mb_photo from nfor_member where mb_no='$write[nf_mb_no]'");
$write[nf_mb_photo] = $mb[mb_photo]?thumbnail($nfor[path]."/data/member/mb/".$mb[mb_photo],60,60,0,1):"";

if($member[mb_no]){
	$write[mb_photo] = $member[mb_photo]?thumbnail($nfor[path]."/data/member/mb/".$member[mb_photo],60,60,0,1):"";
}

$write[nf_mb_nick] = $write[nf_mb_nick]?$write[nf_mb_nick]:$write[nf_mb_id];



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
if(file_exists($nfor[skin_path]."board_view.".$board[bo_skin].".php")){
	include_once $nfor[skin_path]."board_view.".$board[bo_skin].".php";
}
if(file_exists($nfor[skin_path].$board[bo_include_tail])){
	include_once $nfor[skin_path].$board[bo_include_tail];
}
?>