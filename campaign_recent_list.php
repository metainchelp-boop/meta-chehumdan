<?php
$jwt_check = "1";

include_once "path.php";
include_once "inc_house_value.php";

// 저장시간에 따른 최근본 상품 삭제
$cv_datetime = date("Y-m-d H:i:s",strtotime("-$config[cf_item_recent_hour] hour"));
if($member[mb_no]){
	sql_query("delete from nfor_campaign_view where cv_mb_no='$member[mb_no]' and cv_datetime < '$cv_datetime'");
} else{
	$cv_guest_no = $_SESSION[guest_no];
	sql_query("delete from nfor_campaign_view where cv_guest_no='$cv_guest_no' and cv_datetime < '$cv_datetime'");
}

$nfor[title] = "최근본매물";

if($member[mb_no]){
	$sql_search = " where a.cv_mb_no = '$member[mb_no]' ";
} else{
	$sql_search = " where a.cv_guest_no = '$cv_guest_no' ";
}

// 선택삭제
if($mode=="select_delete"){
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		sql_query("delete a from nfor_campaign_view a $sql_search and a.cv_cp_id='{$_POST['it_id'][$k]}'");
	}
	json_return("선택하신 최근본상품이 삭제되었습니다","ok");
}

if(!$sort) $sort = "cv_id desc";

$sql_common = " from nfor_campaign_view a left join nfor_campaign b on ( a.cv_cp_id = b.cp_id ) ";


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
	$res[cv_num] = ($page*$rows) - $rows + $i + 1;
	$res[cp_id] = $row[cp_id];

	if($row[ho_confirm_date]){
		$res[ho_confirm_date] = date("y.m.d",strtotime($row[ho_confirm_date]));
	} else{
		$res[ho_confirm_date] = "";
	}
	$res[ho_premium] = $row[ho_premium];

	// 월세
	if($row[ho_monthly_rent]){
		$ho_rent_type = "월세";
		$ho_monthly_rent_price1_array = explode("||",$row[ho_monthly_rent_price1_array]);
		$ho_monthly_rent_price2_array = explode("||",$row[ho_monthly_rent_price2_array]);
		$ho_rent_type_detail = price_type($ho_monthly_rent_price1_array[0])."/".price_type($ho_monthly_rent_price2_array[0]);
	} 

	// 전세
	if($row[ho_yearly_rent]){
		$ho_rent_type = "전세";
		$ho_rent_type_detail = price_type($row[ho_yearly_rent_price]);
	}

	// 매매
	if($row[ho_sale]){
		$ho_rent_type = "매매";
		$ho_rent_type_detail = price_type($row[ho_sale_price]);
	}

	$res[ho_rent_detail] = $ho_rent_type." ".$ho_rent_type_detail;
	$ho_detail = $row[ho_floor_now]."층, ".$row[ho_meter1]."㎡";
	if($row[ho_management_price]){
		$ho_detail .= ", ".$row[ho_management_price]."만원";
	}
	$res[ho_detail] = $ho_detail;

	$ho_image_array = explode("||",$row[ho_image_array]);
	$res[ho_image] = thumbnail("$nfor[path]/data/house/$ho_image_array[0]",292,186,0,1);
	$res[ho_type] = $admin[ho_type][$row[ho_type]];
	$res[ho_sale_price] = number_format($row[ho_sale_price])."<b class='sizedown'>만원</b>";
	$res[ho_yearly_rent_price] = number_format($row[ho_yearly_rent_price])."<b class='sizedown'>만원</b>";
	$res[ho_address1] = $row[ho_address1];
	$res[ho_hit] = $row[ho_hit];	
	$res[ho_zzim] = $row[ho_zzim];
	$res[ho_datetime] = date("Y.m.d.",strtotime($row[ho_datetime]));
	$res[ho_ing] = $row[ho_ing]; // 진행중
	$res[ho_soldout] = $row[ho_soldout]; // 거래완료

	if($row[ho_ing]){
		$res[ho_exp_day] = "광고종료";
	} elseif($row[ho_soldout]){
		$res[ho_exp_day] = "거래완료";
	} else{

		$time = strtotime($row[ho_exp_date])-time();
		if($time>0){
			$exp_day = ceil($time/86400);
			$res[ho_exp_day] = "광고중 D-".$exp_day;
		} else{
			$res[ho_exp_day] = "광고종료";
		}

	}

	$ho_monthly_rent_price_array = "";
	$exp1 = explode("||",$row[ho_monthly_rent_price1_array]);
	$exp2 = explode("||",$row[ho_monthly_rent_price2_array]);
	for($k=0; $k<count($exp1); $k++){
		$ho_monthly_rent_price_array .= " ".$exp1[$k]." /  ".$exp2[$k]."<br>";
	}
	$res[ho_monthly_rent_price_array] = $ho_monthly_rent_price_array;

	$res[cv_id] = $row[cv_id];
	$res[cv_datetime] = date("Y.m.d.",strtotime($row[cv_datetime]));

	$res[ho_zzim_is] = isset($my_zzim[$row[cp_id]]) ? "zzim_on" : "zzim_off";

	$return["list"][] = $res;

	if($menu_config[menu_limit] and $menu_config[menu_limit] == $res[cv_num]){
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