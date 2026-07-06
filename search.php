<?php
include_once "path.php";

$recent_save = recent_save();

if($mode=="recent_save_onoff"){
	if($member[mb_no]){	
		sql_query("update nfor_member set mb_recent_save='$recent_save' where mb_no='$member[mb_no]'");
	} else{
		$_SESSION[guest_recent_save] = $recent_save;
	}
	json_return("검색저장끄고켜기",$recent_save);
}

if($mode=="keyup"){
	$count = 0;
	if($config[cf_kd_keyword]){
		$que = sql_query("select kd_keyword from nfor_item_keyword where kd_use='1' and kd_keyword like '%$keyword%'");
		while($data = sql_fetch_array($que)){
			$return[keyword][] = $data[kd_keyword];
			$count++;
		}
	}
	if($config[cf_kd_it_name]){
		$sql = "select it_name from nfor_item where it_asign='2' and ((it_shopping='1') or (it_shopping='2' and it_paydate <='$nfor[ymdhis]' and it_payenddate >='$nfor[ymdhis]')) and it_name like '%$keyword%'";
		if($is_mobile){
			$sql .= " and it_view_mobile='1'";
		} else{
			$sql .= " and it_view_pc='1'";
		}
		$que = sql_query($sql);
		while($data = sql_fetch_array($que)){
			$return[keyword][] = $data[it_name];
			$count++;
		}
	}
	if($config[cf_kd_it_description]){
		$sql = "select it_description from nfor_item where it_asign='2' and ((it_shopping='1') or (it_shopping='2' and it_paydate <='$nfor[ymdhis]' and it_payenddate >='$nfor[ymdhis]')) and it_description like '%$keyword%'";
		if($is_mobile){
			$sql .= " and it_view_mobile='1'";
		} else{
			$sql .= " and it_view_pc='1'";
		}
		$que = sql_query($sql);
		while($data = sql_fetch_array($que)){
			$return[keyword][] = $data[it_description];
			$count++;
		}
	}	
	$return[count] = $count;
	json_return("검색결과","ok");
}

if($member[mb_no]){
	$view_sql = "se_mb_no='$member[mb_no]'";
} else{
	$se_guest_no = $_SESSION[guest_no];
	$view_sql = "se_guest_no='$se_guest_no'";
}

if($mode=="delete"){
	sql_query("delete from nfor_search where se_keyword='$keyword' and $view_sql");
	json_return("삭제","ok");
}

if($mode=="delete_all"){
	sql_query("delete from nfor_search where $view_sql");
	json_return("전체삭제","ok");
}

if($json=="recent_keyword"){
	$que = sql_query("select * from nfor_search where $view_sql order by se_id desc limit 10");
	while($data = sql_fetch_array($que)){
		$res = array();
		$res[se_id] = $data[se_id];
		$res[se_keyword] = $data[se_keyword];
		$res[se_datetime] = $data[se_datetime];
		$return["list"][] = $res;
	}
	json_return("최근검색","ok");
}

if($keyword){
	if(!$recent_save){
		$chk_data = sql_fetch("select * from nfor_search where se_keyword='$keyword' and $view_sql");
		if($chk_data[se_id]){
			sql_query("delete from nfor_search where se_id='$chk_data[se_id]'");
		}
		sql_query("insert nfor_search set se_keyword='$keyword', se_ip='$REMOTE_ADDR', se_datetime='$nfor[ymdhis]', $view_sql");

		$view_count = sql_fetch("select count(*) as cnt from nfor_search where $view_sql");
		if($view_count[cnt] >= $config[cf_recent_max]){
			$delete_count = $view_count[cnt] - $config[cf_recent_max];
			sql_query("delete from nfor_search where $view_sql order by se_datetime asc limit $delete_count");
		}
	}
}

// 최근검색어
$que = sql_query("select * from nfor_search where $view_sql order by se_id desc limit $config[cf_recent_max]");
while($row = sql_fetch_array($que)){
	$res[se_id] = $row[se_id];
	$res[se_keyword] = $row[se_keyword];
	$res[se_datetime] = date("y.m.d",strtotime($row[se_datetime]));
	$return["recent_list"][] = $res;
}
if(!$recent_save) $recent_cnt = count($return["recent_list"]);



// 인기검색어
$que = sql_query("select * from nfor_search_crontab order by sc_id asc limit 10");
while($row = sql_fetch_array($que)){
	$res[sc_id] = $row[sc_id];
	$res[sc_keyword] = $row[sc_keyword];
	$res[sc_rank] = $row[sc_rank];
	$res[sc_cnt] = $row[sc_cnt];
	$return["keyword_list"][] = $res;
}

$nfor[title] = "검색결과";

$rows = $menu_config[menu_rows];

$sort = "cp_id desc";

$scroll_load = $menu_config[menu_scroll];

include_once "campaign_list.php";
?>