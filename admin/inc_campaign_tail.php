<?php


$admin[campaign_category_1][""] = "==== 1차 분류 ====";
$que = sql_query("select * from nfor_campaign_category where cg_depth='0' order by cg_rank asc");
while($row = sql_fetch_array($que)){
	$admin[campaign_category_1][$row[category_id]] = $row[cg_category];
}

$admin[campaign_category_2][""] = "==== 2차 분류 ====";
if(strlen($insert_cate_id) >= 3){
	$que = sql_query("select * from nfor_campaign_category where cg_depth='1' and category_id like '".substr($insert_cate_id,0,3)."%' order by cg_rank asc");
	while($row = sql_fetch_array($que)){
		$admin[campaign_category_2][$row[category_id]] = $row[cg_category];
	}
}

$admin[campaign_category_3][""] = "==== 3차 분류 ====";
if(strlen($insert_cate_id) >= 6){
	$que = sql_query("select * from nfor_campaign_category where cg_depth='2' and category_id like '".substr($insert_cate_id,0,6)."%' order by cg_rank asc");
	while($row = sql_fetch_array($que)){
		$admin[campaign_category_3][$row[category_id]] = $row[cg_category];
	}
}

$admin[campaign_category_4][""] = "==== 4차 분류 ====";
if(strlen($insert_cate_id) >= 6){
	$que = sql_query("select * from nfor_campaign_category where cg_depth='3' and category_id like '".substr($insert_cate_id,0,9)."%' order by cg_rank asc");
	while($row = sql_fetch_array($que)){
		$admin[campaign_category_4][$row[category_id]] = $row[cg_category];
	}
}

$admin[cp_supply_no][""] = "전체";
$que = sql_query("select mb_no, mb_name, mb_cp_name from nfor_member where mb_leave_datetime='' and mb_admin='1' order by ( case when mb_cp_name between '가' and  '金' then 1 when mb_cp_name between 'a' and 'z'  THEN 3 when mb_cp_name between 'A' and 'Z' then 4  when mb_cp_name between '0' and '9' then 5  when mb_cp_name between '!' and '.' then 6 else 0  end ), mb_cp_name asc");
while($row = sql_fetch_array($que)){
	$admin[cp_supply_no][$row[mb_no]] = $row[mb_cp_name];
}

$admin[cp_md_no][""] = "전체";
$que = sql_query("select mb_no, mb_name, mb_cp_name from nfor_member where mb_leave_datetime='' and mb_admin='2' order by ( case when mb_cp_name between '가' and  '金' then 1 when mb_cp_name between 'a' and 'z'  THEN 3 when mb_cp_name between 'A' and 'Z' then 4  when mb_cp_name between '0' and '9' then 5  when mb_cp_name between '!' and '.' then 6 else 0  end ), mb_cp_name asc");
while($row = sql_fetch_array($que)){
	$admin[cp_md_no][$row[mb_no]] = $row[mb_name];
}

// 담당자(이름) 필터 — 실제 캠페인에 입력된 cp_md_name 목록(건수순) 2026-06-16
$admin[cp_md_name][""] = "전체 담당자";
$admin[cp_md_name]["__none__"] = "(담당자 미지정)";
$que = sql_query("select cp_md_name, count(*) as cnt from $table where cp_md_name <> '' group by cp_md_name order by cnt desc, cp_md_name asc");
while($row = sql_fetch_array($que)){
	$admin[cp_md_name][$row[cp_md_name]] = $row[cp_md_name]." (".number_format($row[cnt]).")";
}



$sql_common = " from $table ";
$sql_search = " where (1) ";

if($member[mb_admin]=="1"){
	$sql_search .= "and cp_supply_no='$member[mb_no]'";
} elseif($member[mb_admin]=="2"){
	$sql_search .= "and cp_md_no='$member[mb_no]'";
} else{

}

$all_count_sql = "select count(*) as cnt $sql_common $sql_search";


if($insert_cate_id) $sql_search .= " and cp_category like '%$insert_cate_id%'";
if($cp_supply_no) $sql_search .= " and cp_supply_no = '$cp_supply_no'";
if($cp_md_no) $sql_search .= " and cp_md_no = '$cp_md_no'";
if($cp_md_name=='__none__'){ $sql_search .= " and (cp_md_name='' or cp_md_name is null) "; }   // 담당자 미지정만
elseif($cp_md_name) $sql_search .= " and cp_md_name = '".addslashes($cp_md_name)."'";
// 진행 중 캠페인(대시보드 담당자 카드와 동일 정의: 노출 + 결과발표 전/미설정) 2026-06-17
if($mc_ing=='1') $sql_search .= " and cp_use='1' and (cp_result_datetime is null or cp_result_datetime='' or cp_result_datetime='0000-00-00 00:00:00' or cp_result_datetime >= NOW()) ";
// 진행 단계 세분(2026-06-18): recruit=모집중(신청기간 내), ongoing=모집마감 후 선정·리뷰중
$_notfin = "(cp_result_datetime is null or cp_result_datetime='' or cp_result_datetime='0000-00-00 00:00:00' or cp_result_datetime >= NOW())";
if($mc_phase=='recruit') $sql_search .= " and cp_use='1' and cp_sdatetime<=NOW() and cp_edatetime>=NOW() ";
elseif($mc_phase=='ongoing') $sql_search .= " and cp_use='1' and cp_edatetime<NOW() and $_notfin ";
if($cp_use and !is_array($cp_use)) $sql_search .= " and cp_use = '$cp_use'";
if($cp_asign and !is_array($cp_asign)) $sql_search .= " and cp_asign = '$cp_asign'";


if($cp_media_blog) $sql_search .= " and cp_media_blog = '$cp_media_blog'";
if($cp_media_instagram) $sql_search .= " and cp_media_instagram = '$cp_media_instagram'";
if($cp_media_youtube) $sql_search .= " and cp_media_youtube = '$cp_media_youtube'";

if($cp_type) $sql_search .= " and cp_type = '$cp_type'";

if($cp_pay_step) $sql_search .= " and cp_pay_step = '$cp_pay_step'";



if($keyword){
	if($keyword_type){
		$sql_search .= " and $keyword_type like '%$keyword%' ";
	} else{
		$sql_search .= " and (";
		$j = 0;
		foreach ($admin[keyword_type] as $key => $value){
			if($j>1) $sql_search .= " or ";
			if($key){
				$sql_search .= " ($key like '%$keyword%') ";
			}
			$j++;
		}
		$sql_search .= ") ";
	}
}

if($period_sdate and $period_edate and $period_type){
	$sql_search .= " and date_format($period_type,'%Y-%m-%d')>='$period_sdate' and date_format($period_type,'%Y-%m-%d')<='$period_edate' ";
}

if(!$sort){
	$sort = "$id desc";
}

$sql_order = " order by ".safe_orderby($sort)." ";
$search_count_sql = "select count(*) as cnt $sql_common $sql_search";

$row = sql_fetch($all_count_sql);
$total_count = $row[cnt];

if($all_count_sql==$search_count_sql){
	$search_count = $total_count;
} else{
	$row = sql_fetch($search_count_sql);
	$search_count = $row[cnt];
}

$page_row = sql_page_row($page_row);

$total_page  = ceil($search_count / $page_row);
if(!$page) $page = 1;
$from_record = ($page - 1) * $page_row;
$sql = " select *
				  $sql_common
				  $sql_search
				  $sql_order";

if($mode=="excel"){

	require_once "$nfor[path]/PHPExcel.php";
	$objPHPExcel = new PHPExcel();

	$result = sql_query($sql);
	$cnt = @sql_num_rows($result);
	if(!$cnt) alert("출력할 내역이 없습니다.");


	$data = sql_fetch("select * from nfor_excel where ex_id='$ex_id'");
	$ex_field = explode("^^^^^^^^^",$data[ex_field]);
	$ex_comment = explode("^^^^^^^^^",$data[ex_comment]);

	for($k=0; $k<count($ex_field); $k++){
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($nfor[excel][$k]."1", $ex_comment[$k]);
	}

	for($i=2; $row=sql_fetch_array($result); $i++){    
		for($k=0; $k<count($ex_field); $k++){


			if($ex_field[$k]=="cp_supply_no" or $ex_field[$k]=="cp_asign" or $ex_field[$k]=="cp_use" or $ex_field[$k]=="cp_pay_step"){
				$row[$ex_field[$k]] = admin_echo($row,$ex_field[$k],"1");
			}


			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($nfor[excel][$k].$i, $row[$ex_field[$k]]);
		}
	}


	// 시트이름
	$objPHPExcel->getActiveSheet()->setTitle($menu_code[access_text]);

	$objPHPExcel->setActiveSheetIndex(0);

	// 파일명
	$filename = urlencode($menu_code[access_text]);

	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');

	exit;
}


$sql .= " limit $from_record, $page_row ";
$result = sql_query($sql);

?>