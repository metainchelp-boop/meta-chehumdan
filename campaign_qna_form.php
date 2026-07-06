<?php	// 캠페인문의
include_once "path.php";

if($qa_id) $qa_id = str_number($qa_id);
if($cp_id) $cp_id = str_number($cp_id);
if($qa_parent) $qa_parent = str_number($qa_parent);

if($qa_id) $write = sql_fetch("select * from nfor_campaign_qna where qa_id='$qa_id'");

if($write[qa_cp_id]){
	$qa_cp_id = $write[qa_cp_id];
}

if($qa_cp_id) $qa_cp_id = str_number($qa_cp_id);

if($qa_cp_id) $campaign = sql_fetch("select * from nfor_campaign where cp_id='$qa_cp_id'");

if($mode=="insert"){
	// 보안패치
	foreach($_POST as $key => $value){
		if(!is_array($_POST[$key])) $$key = htmlspecialchars(strip_tags($value));
	}

	if(!$member[mb_no]) json_return("로그인하셔야 이용가능합니다");

    // 가장 작은 번호를 얻어
    $row = sql_fetch("select min(qa_rank) as min_qa_rank from nfor_campaign_qna");
    // 가장 작은 번호에 1을 빼서 넘겨줌
    $qa_rank = $row[min_qa_rank] - 1;

	$qa_memo = strip_tags($qa_memo);

	$add_sql = ", qa_mb_id = '{$member[$member_config[cf_mb_id_type]]}', qa_mb_nick='$member[mb_nick]', qa_mb_name = '$member[mb_name]', qa_mb_hp = '$member[mb_hp]', qa_mb_email = '$member[mb_email]', qa_cp_subject = '$campaign[cp_subject]', qa_mb_black = '$member[mb_black]'";

	sql_query("insert nfor_campaign_qna set qa_rank='$qa_rank', qa_view='1', qa_cp_id_gp='$campaign[cp_id_gp]', qa_cp_id='$campaign[cp_id]', qa_md_no='$campaign[cp_md_no]', qa_supply_no='$campaign[cp_supply_no]', qa_parent='$qa_parent', qa_memo='$qa_memo', qa_insert_id='$member[mb_no]', qa_insert_datetime='$nfor[ymdhis]' $add_sql");
	
	cp_qna_cnt_update($campaign[cp_id_gp]);

	json_return("","ok");
}

if($mode=="delete"){
	if(!$member[mb_no]) json_return("로그인하셔야 이용가능합니다");
	if(($write[qa_insert_id] and $write[qa_insert_id]==$member[mb_no]) or $is_admin){
		sql_query("delete from nfor_campaign_qna where qa_id='$qa_id'");
	
		if($write[qa_parent]){ // 서브글
			$qa_reply = sql_fetch("select count(*) as cnt from nfor_campaign_qna where qa_parent='$write[qa_parent]'");

			if(!$qa_reply[cnt]){
				$add_sql = ", qa_reply_id='', qa_reply_datetime=''";
			}

			sql_query("update nfor_campaign_qna set qa_reply='$qa_reply[cnt]' $add_sql where qa_id='$write[qa_parent]'");
		} else{
			sql_query("delete from nfor_campaign_qna where qa_parent='$qa_id'");
			cp_qna_cnt_update($campaign[cp_id_gp]);
			
		}
	}
	json_return("","ok");
}

if($mode=="reply"){

	$write = sql_fetch("select * from nfor_campaign_qna where qa_id='$qa_parent'");

	if(!$member[mb_no]) json_return("로그인하셔야 이용가능합니다");


    $qa_rank = $write[qa_rank];


	$qa_memo = strip_tags($qa_memo);

	$add_sql = ", qa_mb_id = '{$member[$member_config[cf_mb_id_type]]}', qa_mb_nick='$member[mb_nick]', qa_mb_name = '$member[mb_name]', qa_mb_hp = '$member[mb_hp]', qa_mb_email = '$member[mb_email]', qa_cp_subject = '$write[qa_cp_subject]'";

	sql_query("insert nfor_campaign_qna set qa_rank='$qa_rank', qa_view='1', qa_cp_id_gp='$campaign[cp_id_gp]', qa_cp_id='$campaign[cp_id]', qa_supply_no='$campaign[cp_supply_no]', qa_parent='$qa_parent', qa_memo='$qa_memo', qa_insert_id='$member[mb_no]', qa_insert_datetime='$nfor[ymdhis]' $add_sql");
	
	$qa_reply = sql_fetch("select count(*) as cnt from nfor_campaign_qna where qa_parent='$qa_parent'");

	sql_query("update nfor_campaign_qna set qa_reply='$qa_reply[cnt]', qa_reply_id='$member[mb_no]', qa_reply_datetime='$nfor[ymdhis]' where qa_id='$qa_parent'");
	
	if($member[mb_admin]){
		$nfor[sd_memo] = $qa_memo;
		$mb = sql_fetch("select mb_no, mb_email, mb_hp from nfor_member where mb_no='$write[qa_insert_id]'");
		nfor_send("campaign_qna", $mb[mb_email], $mb[mb_hp], $mb[mb_no]);	
	}
	json_return("","ok");
}

if($mode=="update"){
	if(!$member[mb_no]) json_return("로그인하셔야 이용가능합니다");
	$qa_memo = strip_tags($qa_memo);
	if(($write[qa_insert_id] and $write[qa_insert_id]==$member[mb_no]) or $is_admin){
		sql_query("update nfor_campaign_qna set qa_memo='$qa_memo', qa_update_id='$member[mb_no]', qa_update_datetime='$nfor[ymdhis]' where qa_id='$qa_id'");
	}
	json_return("","ok");
}

if(!$member[mb_no]) alert("로그인하셔야 이용가능합니다","login.php?url=$PHP_SELF?qa_cp_id=$qa_cp_id");

if($write[qa_id] and !$write[qa_parent]){
	$nfor[title] = "캠페인문의수정";
	$nfor[btn_txt] = "수정하기";
} elseif(!$write[qa_id] and $qa_parent){
	$nfor[title] = "캠페인문의답변";
	$nfor[btn_txt] = "답변하기";
} elseif($write[qa_id] and $write[qa_parent]){
	$nfor[title] = "캠페인문의답변수정";
	$nfor[btn_txt] = "수정하기";
} else{
	$nfor[title] = "캠페인문의";
	$nfor[btn_txt] = "문의하기";
}


$campaign = sql_fetch("select * from nfor_campaign where cp_id='$cp_id'");

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>