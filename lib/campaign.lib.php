<?php

function cp_qna_cnt_update($qa_cp_id_gp){
	$cp_qna_cnt = sql_fetch("select count(*) as cnt from nfor_campaign_qna where qa_parent='0' and qa_cp_id_gp='$qa_cp_id_gp' and qa_view='1'");
	sql_query("update nfor_campaign set cp_qna_cnt='$cp_qna_cnt[cnt]' where cp_id_gp='$qa_cp_id_gp'");
}

function review_count($mb_no){
	$res[review_count] = array();
	$que = sql_query("select count(*) as cnt,rv_step from nfor_review where rv_mb_no='$mb_no' group by rv_step");
	while($data = sql_fetch_array($que)){
		$res[review_count][$data[rv_step]] = $data[cnt];
	}
	$res[review_total_count] = array_sum($res[review_count]);
	return $res;
}



function campaign_member_info($row=array()){
	$str = "";
	// 회원정보(2026-06-11): 줄1=이름(없으면 닉네임), 줄2=전화. (줄3 등급·방문수·신고는 목록 td) 닉네임/아이디 숨김. CSS=mc_review.css
	if($row[rv_mb_nick] || $row[rv_mb_name] || $row[rv_mb_hp]){
		$name = $row[rv_mb_name] ? $row[rv_mb_name] : $row[rv_mb_nick];
		if($name) $str .= "<span class='mc-mi-name'>".$name;
		if($row[rv_mb_black]) $str .= " <span class='mc-mi-black'>블랙</span>";
		if($name) $str .= "</span>";
		if($row[rv_mb_hp]) $str .= "<span class='mc-mi-hp'>".$row[rv_mb_hp]."</span>";
	}
	if($row[co_mb_nick] || $row[co_mb_name] || $row[co_mb_hp]){
		$coname = $row[co_mb_name] ? $row[co_mb_name] : $row[co_mb_nick];
		if($coname) $str .= "<span class='mc-mi-name'>".$coname;
		if($row[co_mb_black]) $str .= " <span class='mc-mi-black'>블랙</span>";
		if($coname) $str .= "</span>";
		if($row[co_mb_hp]) $str .= "<span class='mc-mi-hp'>".$row[co_mb_hp]."</span>";
	}
	return $str;

}

function campaign_count($cp_id){
	$array_data = array();
	$que = sql_query("select count(*) as cnt, rv_step from nfor_review where rv_cp_id='$cp_id' and rv_delete='0' group by rv_step");
	while($data = sql_fetch_array($que)){
		$array_data[$data[rv_step]] = $data[cnt];
	}
	$cp_order = array_sum($array_data);
	$cp_review = sql_fetch("select count(*) as cnt from nfor_review where rv_cp_id='$cp_id' and rv_step='4' and rv_asign_show='1'");
	sql_query("update nfor_campaign set cp_review_wait='$array_data[1]', cp_review_asign='$array_data[2]', cp_review_post='$array_data[3]', cp_review_post_asign='$array_data[4]', cp_review_cancel='$array_data[5]', cp_order='$cp_order', cp_review='$cp_review[cnt]' where cp_id='$cp_id'");
}

function campaign_category_id_name($category_id){
	$str = "";
	for($k=1; $k <= strlen($category_id)/4; $k++){
		if($k>1) $str .= " > ";
		$category_id_str = substr($category_id,0,(4*$k));
		$catename = sql_fetch("select cg_category from nfor_campaign_category where category_id='$category_id_str'");
		$str .= $catename[cg_category];
	}
	return $str;
}

function campaign_category_sql($category_id){
	$sql_search = " and (";
	for($i=1; $i<=10; $i++){
		if($i>1) $sql_search .= " or ";
		$sql_search .= "cp_category_id{$i} like '$category_id%'";
	}
	$sql_search .= ")";
	return $sql_search;
}


function category_campaign_count($category_id) {
	$add_item = sql_fetch("select count(*) as cnt from nfor_campaign where cp_category like '%$category_id%'");
	return $add_item[cnt];
}

function nfor_message($mg_mb_no, $mg_msg, $mg_url=""){
	sql_query("insert nfor_message set mg_mb_no='$mg_mb_no', mg_datetime=NOW(), mg_msg='$mg_msg', mg_url='$mg_url'");
}

function pb_step($pb_step){

	if($pb_step=="1"){
		$return = "출금요청";
	} elseif($pb_step=="2"){
		$return = "입금예정";
	} elseif($pb_step=="3"){
		$return = "입금완료";
	} elseif($pb_step=="4"){
		$return = "출금보류";
	} else{
		$return = "";
	}
	return $return;

}

function review_hidden($rv_id){ // 관리자 숨기기
	sql_query("update nfor_review set rv_delete='1', rv_delete_datetime=NOW() where rv_id='$rv_id'");
}

function review_show($rv_id){ // 관리자 보이기
	sql_query("update nfor_review set rv_delete='0', rv_delete_datetime='' where rv_id='$rv_id'");
}


function review_back($rv_id){
	sql_query("update nfor_review set rv_step='1', rv_cancel_datetime='' where rv_id='$rv_id'");
	$data = sql_fetch("select * from nfor_review where rv_id='$rv_id'");
	campaign_count($data[rv_cp_id]);
}

function review_cancel($rv_id){ // 리뷰어 미선정
	sql_query("update nfor_review set rv_step='5', rv_cancel_datetime=NOW() where rv_id='$rv_id'");
	$data = sql_fetch("select * from nfor_review where rv_id='$rv_id'");
	campaign_count($data[rv_cp_id]);
	nfor_send("review_cancel",$data[rv_mb_email],$data[rv_mb_hp],$data[rv_mb_no],$data[rv_id],"campaign.php?cp_id=$data[rv_cp_id]");
}

function review_delete($rv_id){ // 신청서 삭제
	$data = sql_fetch("select * from nfor_review where rv_id='$rv_id'");
	sql_query("delete from nfor_review where rv_id='$rv_id'");
	campaign_count($data[rv_cp_id]);
}

function review_asign($rv_id){ // 리뷰어 선정 (2차 확정에도 재사용: rv_step→2, 회원 알림O)
	sql_query("update nfor_review set rv_step='2', rv_asign_datetime=NOW() where rv_id='$rv_id'");
	$data = sql_fetch("select * from nfor_review where rv_id='$rv_id'");
	campaign_count($data[rv_cp_id]);
	nfor_send("review_asign",$data[rv_mb_email],$data[rv_mb_hp],$data[rv_mb_no],$data[rv_id],"review_asign_list.php");
}

// 1차 선정(후보) — rv_step 8. 내부 검토용이라 회원 알림 없음 (2026-06-11, 1차/2차 시스템)
function review_pre_asign($rv_id){
	sql_query("update nfor_review set rv_step='8' where rv_id='$rv_id'");
	$data = sql_fetch("select * from nfor_review where rv_id='$rv_id'");
	campaign_count($data[rv_cp_id]);
}
// 1차 후보 제외 → 신청목록(rv_step 1) 복귀. 회원 알림 없음 (광고주가 빼도 회원은 모름)
function review_pre_exclude($rv_id){
	sql_query("update nfor_review set rv_step='1', rv_asign_datetime='' where rv_id='$rv_id'");
	$data = sql_fetch("select * from nfor_review where rv_id='$rv_id'");
	campaign_count($data[rv_cp_id]);
}

function review_asign_cancel_after($rv_id){ // 리뷰어 선정후 취소
	sql_query("update nfor_review set rv_step='6', rv_cancel_datetime=NOW() where rv_id='$rv_id'");
	$data = sql_fetch("select * from nfor_review where rv_id='$rv_id'");
	campaign_count($data[rv_cp_id]);
	nfor_send("review_asign_cancel_after",$data[rv_mb_email],$data[rv_mb_hp],$data[rv_mb_no],$data[rv_id],"campaign.php?cp_id=$data[rv_cp_id]");
}



function review_asign_cancel($rv_id){ // 신청목록이동
	sql_query("update nfor_review set rv_step='1', rv_asign_datetime='' where rv_id='$rv_id'");
	$data = sql_fetch("select * from nfor_review where rv_id='$rv_id'");
	campaign_count($data[rv_cp_id]);
	nfor_send("review_asign_cancel",$data[rv_mb_email],$data[rv_mb_hp],$data[rv_mb_no],$data[rv_id],"campaign.php?cp_id=$data[rv_cp_id]");
}

if(substr($nfor['root_path'],4,3) != "sub") die("");

function review_confirm($rv_id){
	global $config, $_POST;

	sql_query("update nfor_review set rv_step='4', rv_confirm_datetime=NOW(), rv_asign_show='$config[cf_realtime_review]' where rv_id='$rv_id'");
	$data = sql_fetch("select * from nfor_review where rv_id='$rv_id'");

	$campaign = sql_fetch("select * from nfor_campaign where cp_id='$data[rv_cp_id]'");
	if(($campaign[cp_reward_type]=="2" or $campaign[cp_reward_type]=="3") and $campaign[cp_point]){
		insert_point($data[rv_mb_no], $campaign[cp_point], "리뷰등록포인트 - ".$data[rv_cp_id], "1111", $_POST[ftimestamp]."_".$rv_id);
	}

	campaign_count($data[rv_cp_id]);
	nfor_send("review_confirm",$data[rv_mb_email],$data[rv_mb_hp],$data[rv_mb_no],$data[rv_id],"review_best_list.php");
}

function review_post_cancel($rv_id){
	global $_POST;

	// 2026-06-17: 등록완료 취소 시 바로 전 단계인 '등록인원(검수요청, rv_step=3)'으로 되돌림(기존 선정2 → 3).
	// rv_confirm_datetime(검수확인)만 비우고 rv_reg_datetime(리뷰 등록기록)은 유지 → 회원 리뷰 그대로 두고 재검수만.
	sql_query("update nfor_review set rv_step='3', rv_confirm_datetime='' where rv_id='$rv_id'");
	$data = sql_fetch("select * from nfor_review where rv_id='$rv_id'");

	$campaign = sql_fetch("select * from nfor_campaign where cp_id='$data[rv_cp_id]'");
	if(($campaign[cp_reward_type]=="2" or $campaign[cp_reward_type]=="3") and $campaign[cp_point]){
		insert_point($data[rv_mb_no], $campaign[cp_point]*-1, "리뷰등록포인트 취소 - ".$data[rv_cp_id], "2222", $_POST[ftimestamp]."_".$rv_id);
	}

	campaign_count($data[rv_cp_id]); // 등록완료 취소 시 캠페인 카운터(선정/등록완료 수) 갱신
}

function insert_point($mb_no, $point, $memo, $type="", $pt_timestamp=""){
	global $connect_db;

	if(!$pt_timestamp) $pt_timestamp =  $mb_no."_".date("YmdHis").substr(microtime(),2,6);

	$mb = sql_fetch("select * from nfor_member where mb_no = '$mb_no'");
	if(!$mb[mb_no] or $point == 0 or $mb_no == "") { return 0; }
	sql_query("insert nfor_point set pt_mb_no='$mb_no', pt_point='$point', pt_memo='$memo', pt_type='$type', pt_datetime=NOW(), pt_timestamp='$pt_timestamp'",FALSE);
	if(mysqli_errno($connect_db) <> "1062"){
		$sum = sql_fetch("select sum(pt_point) as sum_point from nfor_point where pt_mb_no='$mb_no'");
		sql_query("update nfor_member set mb_point='$sum[sum_point]' where mb_no='$mb_no'");
	}
	return 1;
}









function instagram_make_url($url){
	if(strpos($url, "instagram.com") !== false) {
		$return = $url;
	} else {
		$return = "https://www.instagram.com/".str_replace(" ","",$url);
	}
	return set_https($return);
}

function youtube_make_url($url){
	if(strpos($url, "youtube.com") !== false) {
		$return = $url;
	} else {
		$return = "https://www.youtube.com/channel/".str_replace(" ","",$url);
	}
	return set_https($return);
}

function blog_make_url($url){
	if(strpos($url, "blog.naver.com") !== false) {
		$return = $url;
	} else {
		$return = "https://blog.naver.com/".str_replace(" ","",$url);
	}
	return set_https($return);
}

function channel_url($url){

	if(strpos($url, "blog.naver.com") !== false or strpos($url, "youtube.com") !== false or strpos($url, "instagram.com") !== false) {
		$return = "<a href='$url' target='_blank'>$url</a>";
	} else{
		$return = $url;
	}

	return $return;
}

// 신청채널을 '보러가기' 버튼으로 출력(2026-06-12). 긴 URL 대신 일정한 버튼 → 리스트 정렬 깔끔. CSS=mc_review.css(.mc-channel-btn)
function channel_btn($url, $media=""){
	if(!$url) return "";
	$type  = $media ? $media : media_type($url);
	$label = "채널 보러가기";
	if($type=="blog") $label = "블로그 보러가기";
	elseif($type=="youtube") $label = "유튜브 보러가기";
	elseif($type=="instagram") $label = "인스타 보러가기";
	return "<a href='".$url."' target='_blank' class='btn btn-white btn-sm mc-channel-btn'>".$label."</a>";
}



function media_type($url){
	if(strpos($url, "blog.naver.com") !== false) {
		$return = "blog";
	} elseif(strpos($url, "youtube.com") !== false) {
		$return = "youtube";
	} elseif(strpos($url, "instagram.com") !== false) {
		$return = "instagram";
	} else{
		$return = "shop";
	}
	return $return;
}


function cp_contents_exp($rv_cp_contents_edatetime){
	$now = 0;
	if(time() > strtotime($rv_cp_contents_edatetime)){
		$now = ceil((time()-strtotime($rv_cp_contents_edatetime))/86400);
	}
	if($now){
		$return = $now."일";
	} else{
		$return = "";
	}
	return $return;
}
?>