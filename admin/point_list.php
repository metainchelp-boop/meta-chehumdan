<?php
include_once "path.php";

$admin[keyword_type] = array(""=>"전체", "pt_memo" => "적립내용", $member_config[cf_mb_id_type]=>"아이디", "mb_nick" => "닉네임", "mb_name" => "이름");
$admin[period_type] = array("pt_datetime" => "등록일자");

$qstr .= "&pt_mb_no=$pt_mb_no";

$list = $_SERVER[PHP_SELF];
$form = str_replace("list","form",$list);
$table = "nfor_point";
$id = "pt_id";

if($mode=="list_delete"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];

		$data = sql_fetch("select * from $table where $id='{$_POST[$id][$k]}'");
		sql_query("delete from $table where $id='{$_POST[$id][$k]}'");
		$pt_point_sum = sql_fetch("select sum(pt_point) as pt_point_sum from $table where pt_mb_no='$data[pt_mb_no]'");
		sql_query("update nfor_member set mb_point='$pt_point_sum[pt_point_sum]' where mb_no='$data[pt_mb_no]'");

	}
	json_return("정상적으로 삭제되었습니다","ok");
}

if($mode=="delete"){
	demo_check_json();

	$data = sql_fetch("select * from $table where $id='{$$id}'");
	sql_query("delete from $table where $id='{$$id}'");
	$pt_point_sum = sql_fetch("select sum(pt_point) as pt_point_sum from $table where pt_mb_no='$data[pt_mb_no]'");
	sql_query("update nfor_member set mb_point='$pt_point_sum[pt_point_sum]' where mb_no='$data[pt_mb_no]'");

	json_return("정상적으로 삭제되었습니다","ok");
}

$sql_common = " from $table a, nfor_member b ";
$sql_search = " where a.pt_mb_no=b.mb_no ";

$all_count_sql = "select count(*) as cnt $sql_common $sql_search";

if($pt_mb_no) $sql_search .= " and pt_mb_no = '$pt_mb_no'";

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

include_once "head.php";

if($pt_mb_no){
	$mb = sql_fetch("select * from nfor_member where mb_no='$pt_mb_no'");
}
?>

<form name="fsearch" id="fsearch" method="get">

<table class="table cols_tbl">
<tr>
	<th>검색어</th>
	<td>
		<div class="form-inline">
		<?=admin_select($_GET,"keyword_type","","","0")?>
		<?=admin_text($_GET,"keyword","","maxlength=\"30\"")?>
		</div>
	</td>
</tr>
<tr>
	<th>기간검색</th>
	<td colspan="3">
	<div class="form-inline">
		<?=admin_select($_GET,"period_type","","","0")?>
		<?=admin_text($_GET,"period_sdate","width-100p datepicker-here","data-language=\"ko\"")?> ~ <?=admin_text($_GET,"period_edate","width-100p datepicker-here","data-language=\"ko\"")?>
		<?=period_day_echo($_GET[period_day])?>
	</div>
	</td>
</tr>
</table>

<div class="table_btn"><div class="form-inline"><?=admin_submit("fsubmit", "검색하기", "btn-lg btn-black btn")?> <?=admin_button("exceldown", "엑셀다운", "btn-lg btn exceldown")?></div></div>


<div class="ofw martop20">
	<div class="flol">
	전체 <span class="txt_red"> <?=number_format($total_count)?></span>건 / 검색 <span class="txt_red"><?=number_format($search_count)?></span>건

	<?php
	if($mb[mb_no]){ 
		echo " / ".$mb[$member_config[cf_mb_id_type]]." 님 포인트 합계 : ".number_format($mb[mb_point])."원";
	}
	?>
	</div>
	<div class="flor">
	<?=admin_sort($admin[period_type],$sort)?>
	<?=admin_page_row($page_row)?>
	</div>
</div>
</form>


<form name="flist" id="flist" method="post">
<?=admin_hidden($hidden,"ex_id")?>
<?=admin_get()?>
<table class="table row_tbl margin0">
<colgroup>
	<col class="width-80p">
	<col class="width-100p">
	<col class="width-100p">
	<col class="width-100p">
	<col >
	<col class="width-100p">
	<col class="width-100p">
	<col class="width-80p">
</colgroup>
<tr>
	<th><?=admin_checkbox($row,"chkall")?></th>
	<th>아이디</th>
	<th>닉네임</th>
	<th>이름</th>
	<th>적립내용</th>
	<th>포인트</th>
	<th>적립/사용일시</th>
	<th>삭제</th>
</tr>
<?php
for($i=0; $row=sql_fetch_array($result); $i++){
	$row["chk[]"] = $i;
	$row["{$id}[{$i}]"] = $row[$id];
?>
<tr>
	<td><?=admin_checkbox($row,"chk[]","chk")?><?=admin_hidden($row,"{$id}[{$i}]")?></td>
	<td><a href="<?=$PHP_SELF?>?pt_mb_no=<?=$row[mb_no]?>"><?=$row[$member_config[cf_mb_id_type]]?></a></td>
	<td><?=$row[mb_nick]?></td>
	<td><?=$row[mb_name]?></td>
	<td class="textleft"><?=$row[pt_memo]?></td>
	<td><?=number_format($row[pt_point])?>원</td>
	<td><?=$row[pt_datetime]?></td>
	<td><?=admin_a("delete", "삭제", "btn btn-white btn-sm nfor_button", "data-confirm=\"삭제하시겠습니까?\" data-data=\"mode=delete&{$id}={$row[$id]}\"")?></td>
</tr>
<?php
}
$pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");
?>
</table>

<div class="bottom_btn">
	
	<div class="form-inline">
	<?=admin_button("list_delete", "선택삭제", "btn btn-lg btn-red")?>
	<?=admin_a("form", "등록하기", "btn-lg btn-black btn", "", $form)?>
	</div>

</div>

<div class="table_btn"><?=$pagelist?></div>

</form>

<script type="text/javascript">
<!--
$(document).on("click", "#chkall", function(){
	nfor_chk_all(this, 'chk');
});

$(document).on("click", "#list_delete", function(){
	nfor_list_reload('삭제','list_delete');
});
//-->
</script>

<?php
include_once "tail.php";
?>