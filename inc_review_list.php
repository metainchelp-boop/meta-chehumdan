<?php

if(!$sort) $sort = "a.rv_id desc";

$sql_common = " from nfor_review a left join nfor_campaign b on ( a.rv_cp_id = b.cp_id ) ";

$sql_search = " where a.rv_mb_no='$member[mb_no]' and a.rv_step='$rv_step'";

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

// 찜 N+1 제거: 회원 찜 캠페인 1쿼리 선로드
$my_zzim = array();
if($member[mb_no]){
	$__zq = sql_query("select zz_cp_id from nfor_campaign_zzim where zz_mb_no='$member[mb_no]'");
	while($__z = sql_fetch_array($__zq)) $my_zzim[$__z[zz_cp_id]] = 1;
}

for($i=0; $row=sql_fetch_array($result); $i++){
	$res = array();
	$res[rv_num] = $total_count - ($page - 1) * $rows - $i;
	$res[rv_id] = $row[rv_id];

	$res[cp_zzim_is] = isset($my_zzim[$row[rv_cp_id]]) ? 1 : "";

	$res[cp_id] = $row[rv_cp_id];
	$res[cp_img] = $row[cp_img]?thumbnail("$nfor[path]/data/campaign/$row[cp_img]",300,300,0,1):$nfor[skin_path]."img/no_img.png";

	$res[rv_img] = $row[rv_img]?thumbnail("$nfor[path]/data/review/$row[rv_img]",300,300,0,1):$res[cp_img];
	$res[rv_review] = $row[rv_review];
	$res[rv_url] = $row[rv_url];
	$res[rv_buy_chk] = $row[rv_buy_chk];   // 상품구매 신고/확인 상태(0/1/2) 2026-06-30
	$res[my_ext_edatetime] = ($row[rv_cp_contents_edatetime] && $row[rv_cp_contents_edatetime]!='0000-00-00 00:00:00' && strtotime($row[rv_cp_contents_edatetime]) > strtotime($row[cp_contents_edatetime])) ? $row[rv_cp_contents_edatetime] : '';   // 개인 리뷰마감 연장분 2026-06-30
	$res[rv_mb_nick] = $row[rv_mb_nick]?$row[rv_mb_nick]:$row[rv_mb_id];

	if($row[cp_id]){
		$res[cp_subject] = $row[cp_subject];
		$res[cp_description] = $row[cp_description];
	} else{
		$res[cp_subject] = "삭제된 캠페인입니다";
		$res[cp_description] = "삭제된 캠페인입니다";
	}
	$res[cp_type] = $nfor[cp_type][$row[cp_type]];
	$res[cp_point] = number_format($row[cp_point]);
	$res[cp_media] = $row[cp_media];
	$res[cp_order] = number_format($row[cp_order]);
	$res[cp_recruit] = number_format($row[cp_recruit]);
	$res[cp_review] = number_format($row[cp_review]);
	$res[cp_media_text] = $nfor[cp_media][$row[cp_media]];
	if(time() < strtotime($row[cp_edatetime])){
		$time = strtotime($row[cp_edatetime]) - time();
		$cp_day = "D-Day ".ceil($time/86400);
	} else{
		$cp_day = "모집마감";
	}
	$res[cp_day] = $cp_day;

	$return["list"][] = $res;

	if($menu_config[menu_limit] and $menu_config[menu_limit] == $res[rv_num]){
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