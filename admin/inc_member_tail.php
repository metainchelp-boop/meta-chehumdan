<?php

$sql_common = " from $table ";

if(basename($PHP_SELF)=="member_list.php"){
	$sql_search .= " and mb_leave_datetime='' and mb_admin='0' ";
} elseif(basename($PHP_SELF)=="supply_list.php"){
	$sql_search .= " and mb_leave_datetime='' and mb_admin='1' and mb_id not like 'adv\\_%' ";   // 광고주(adv_)는 별도 광고주목록으로 분리 2026-06-17
} elseif(basename($PHP_SELF)=="advertiser_list.php"){
	$sql_search .= " and mb_leave_datetime='' and mb_admin='1' and mb_id like 'adv\\_%' ";
} elseif(basename($PHP_SELF)=="md_list.php"){
	$sql_search .= " and mb_leave_datetime='' and mb_admin='2' ";
} elseif(basename($PHP_SELF)=="leave_list.php"){
	$sql_search .= " and mb_out_datetime='' and mb_leave_datetime<>'' ";
} elseif(basename($PHP_SELF)=="admin_list.php"){
	$sql_search .= " and mb_leave_datetime='' and mb_admin='7' ";
} else{

}

$all_count_sql = "select count(*) as cnt $sql_common $sql_search";

if($mb_sns_type) $sql_search .= " and mb_{$mb_sns_type}_id <> ''";
if($mb_level) $sql_search .= " and mb_level = '$mb_level'";
if($mb_asign) $sql_search .= " and mb_asign = '$mb_asign'";
if($mb_mailling) $sql_search .= " and mb_mailling = '$mb_mailling'";
if($mb_sms) $sql_search .= " and mb_sms = '$mb_sms'";
if($mb_sex) $sql_search .= " and mb_sex = '$mb_sex'";
if($mb_wedding_status) $sql_search .= " and mb_wedding_status = '$mb_wedding_status'";
if($mb_join_channel) $sql_search .= " and mb_join_channel = '$mb_join_channel'";
if($mb_access) $sql_search .= " and mb_access = '$mb_access'";
if($mb_black) $sql_search .= " and mb_black = '$mb_black'";

if($mb_last_login_day){
	$mb_last_login = date("Y-m-d H:i:s",strtotime("-{$mb_last_login_day} day"));
	$sql_search .= " and date_format(mb_login_datetime,'%Y-%m-%d') < '$mb_last_login'";
}

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

$sql_search .= sql_range("mb_login_count", $mb_login_count_1,$mb_login_count_2);
$sql_search .= sql_range("date_format($period_type,'%Y-%m-%d')", $period_sdate,$period_edate);

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

			if($ex_field[$k]=="mb_level" or $ex_field[$k]=="mb_asign" or $ex_field[$k]=="mb_mailling" or $ex_field[$k]=="mb_sms"){
				$row[$ex_field[$k]] = admin_echo($row,$ex_field[$k]);
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