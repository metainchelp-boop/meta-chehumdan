<?php
include_once "path.php";

$nfor[title] = "부동산소개";

$agent = sql_fetch("select * from nfor_member where mb_no='$mb_no'");

$agent[mb_zzim_is] = "zzim_off";
if($member[mb_no]){
	$zzim = sql_fetch("select zz_id from nfor_agent_zzim where zz_agent_no='$agent[mb_no]' and zz_mb_no='$member[mb_no]'");
	if($zzim[zz_id]){
		$agent[mb_zzim_is] = "zzim_on";
	}
}

if($mode=="zzim"){
	if(!$agent[mb_no]){
		json_return("잘못된 매물코드입니다","mb_no");
	} elseif(!$member[mb_no]){
		json_return("로그인하셔야 이용가능합니다","mb_no");
	} else{
		if($zzim[zz_id]){
			sql_query("delete from nfor_agent_zzim where zz_id='$zzim[zz_id]' and zz_mb_no='$member[mb_no]'");
		} else{
			sql_query("insert nfor_agent_zzim set zz_agent_no='$agent[mb_no]', zz_mb_no='$member[mb_no]', zz_insert_datetime=NOW()");
		}
		$mb_zzim = sql_fetch("select count(*) as cnt from nfor_agent_zzim where zz_agent_no='$agent[mb_no]'");
		sql_query("update nfor_member set mb_zzim='$mb_zzim[cnt]' where mb_no='$agent[mb_no]'");

		$return[zzim_cnt] = number_format($mb_zzim[cnt]);

		$return[mb_no] = $member[mb_no];

		if($zzim[zz_id]){
			json_return("찜하기 삭제하였습니다","delete");
		} else{
			json_return("찜하기 등록하였습니다","insert");
		}
	}
}





$ho_soldout = sql_fetch("select count(*) as cnt from nfor_house where ho_mb_no='$agent[mb_no]' and ho_soldout='1'");
$ho_sale = sql_fetch("select count(*) as cnt from nfor_house where ho_mb_no='$agent[mb_no]' and ho_sale='1'"); // 매매
$ho_yearly_rent = sql_fetch("select count(*) as cnt from nfor_house where ho_mb_no='$agent[mb_no]' and ho_yearly_rent='1'"); // 전세
$ho_monthly_rent = sql_fetch("select count(*) as cnt from nfor_house where ho_mb_no='$agent[mb_no]' and ho_monthly_rent='1'"); // 월세
$ho_short_rent = sql_fetch("select count(*) as cnt from nfor_house where ho_mb_no='$agent[mb_no]' and ho_short_rent='1'"); // 단기
$ho_total = sql_fetch("select count(*) as cnt from nfor_house where ho_mb_no='$agent[mb_no]' and ho_soldout='0' and ho_ing='0'"); // 진행중




$sms_href = "sms:$agent[mb_hp]";
if(preg_match('/iPhone/',$_SERVER['HTTP_USER_AGENT'])){
	$sms_href .= "&";
} else{
	$sms_href .= "?";
}
$sms_href .= "body=".rawurlencode("");

$agent[sms_href] = $sms_href;
$agent[tel_href] = "tel:".$agent[mb_tel];


$agent[map_href] = "https://map.kakao.com/link/search/".$agent[mb_cp_addr1];






if(!$sort) $sort = "ho_id desc";

$sql_common = " from nfor_house ";

$sql_search = " where ho_mb_no='$agent[mb_no]' ";

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
	$res[ho_num] = $total_count - ($page - 1) * $rows - $i;
	$res[ho_id] = $row[ho_id];

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
	$res[ho_exp_day] = house_state($row);

	$ho_monthly_rent_price_array = "";
	$exp1 = explode("||",$row[ho_monthly_rent_price1_array]);
	$exp2 = explode("||",$row[ho_monthly_rent_price2_array]);
	for($k=0; $k<count($exp1); $k++){
		$ho_monthly_rent_price_array .= " ".$exp1[$k]." /  ".$exp2[$k]."<br>";
	}
	$res[ho_monthly_rent_price_array] = $ho_monthly_rent_price_array;

	$res[ho_zzim_is] = "0";
	if($member[mb_no]){
		$zzim = sql_fetch("select zz_id from nfor_house_zzim where zz_ho_id='$row[ho_id]' and zz_mb_no='$member[mb_no]'");
		if($zzim[zz_id]){
			$res[ho_zzim_is] = "1";
		}
	}

	$return["list"][] = $res;

	if($menu_config[menu_limit] and $menu_config[menu_limit] == $res[ho_num]){
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

$pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>