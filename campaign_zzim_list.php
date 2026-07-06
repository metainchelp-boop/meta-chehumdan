<?php
$jwt_check = "1";

include_once "path.php";

$nfor[title] = "관심 캠페인";

// 삭제
if($mode=="delete"){
	if(!$member[mb_no]) json_return("로그인하셔야 이용가능합니다","mb_no");
	if(!$zz_id) json_return("고유번호가 입력되지 않았습니다","zz_id");
	sql_query("delete from nfor_campaign_zzim where zz_id='$zz_id' and zz_mb_no='$member[mb_no]'");
	json_return("선택하신 찜하기가 삭제되었습니다","ok");
}

if(!$member[mb_no]) goto_url("login.php?url=".basename($_SERVER[PHP_SELF]));

if(!$sort) $sort = "a.zz_id desc";

$sql_common = " from nfor_campaign_zzim a left join nfor_campaign b on ( a.zz_cp_id = b.cp_id ) ";

$sql_search = " where a.zz_mb_no = '$member[mb_no]' ";

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
	$res[zz_num] = ($page*$rows) - $rows + $i + 1;
	$res[zz_id] = $row[zz_id];

	$res[cp_zzim_is] = "1";

	$res[cp_id] = $row[zz_cp_id];
	$res[cp_img] = $row[cp_img]?thumbnail("$nfor[path]/data/campaign/$row[cp_img]",300,300,0,1):$nfor[skin_path]."img/no_img.png";
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

	if($menu_config[menu_limit] and $menu_config[menu_limit] == $res[zz_num]){
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