<?php
if($member[mb_admin]=="1"){
	$sql_search .= " and rv_supply_no='$member[mb_no]'";	
} else if($member[mb_admin]=="2"){
	$sql_search .= " and rv_md_no='$member[mb_no]'";	
} else{

}

$all_count_sql = "select count(*) as cnt $sql_common $sql_search";

if($rv_supply_no) $sql_search .= " and rv_supply_no = '$rv_supply_no'";
if($rv_md_no) $sql_search .= " and rv_md_no='$rv_md_no'"; 
if($rv_cp_id) $sql_search .= " and rv_cp_id='$rv_cp_id'";
if($rv_is_mobile) $sql_search .= " and rv_is_mobile = '$rv_is_mobile'";

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

			if($ex_field[$k]=="rv_media" or $ex_field[$k]=="rv_cp_type" or $ex_field[$k]=="rv_is_mobile" or $ex_field[$k]=="rv_asign_show" or $ex_field[$k]=="rv_mb_sex"){
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