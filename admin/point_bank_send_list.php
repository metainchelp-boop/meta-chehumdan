<?php
include_once "path.php";

$admin[keyword_type] = array(""=>"전체","pb_name" => "예금주", "pb_bank"=>"은행", "pb_bank_number"=>"계좌번호");
$admin[period_type] = array("pb_datetime" => "등록일자");

$list = $_SERVER[PHP_SELF];
$form = str_replace("list","form",$list);
$table = "nfor_point_bank";
$id = "pb_id";


$sql_common = " from $table ";
$sql_search = " where pb_step='3' ";

$all_count_sql = "select count(*) as cnt $sql_common $sql_search";

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
	<col >
</colgroup>
<tr>
	<th><?=admin_checkbox($row,"chkall")?></th>
	<th>아이디</th>
	<th>신청일자</th>
	<th>예금주</th>
	<th>은행</th>
	<th>계좌번호</th>
	<th>출금요청금액</th>
	<th>상태</th>
	<th>상태변경일</th>
	<th>입금예정일</th>
	<? if($config[cf_jumin]=="1"){ ?>
	<th>주민번호</th>
	<? } ?>
	<? if($config[cf_jumin_file]=="1" or $config[cf_bank_file]=="1"){ ?>
	<th>첨부파일</th>
	<? } ?>
</tr>
<?php
for($i=0; $row=sql_fetch_array($result); $i++){
	$row = nfor_tag_out($row);


	$row["chk[]"] = $i;
	$row["{$id}[{$i}]"] = $row[$id];
?>
<tr>
	<td><?=admin_checkbox($row,"chk[]","chk")?><?=admin_hidden($row,"{$id}[{$i}]")?></td>
	<td><?=$row[pb_mb_id]?></td>
	<td><?=substr($row[pb_datetime],0,10)?></td>
	<td><?=$row[pb_name]?></td>
	<td><?=$row[pb_bank]?></td>
	<td><?=$row[pb_bank_number]?></td>
	<td><?=number_format($row[pb_point])?>원</td>
	<td><?=pb_step($row[pb_step])?></td>
	<td><?=substr($row[pb_chage_datetime],0,10)?></td>
	<td><?=$row[pb_send_date]?></td>	
	<? if($config[cf_jumin]=="1"){ ?>
	<td><?=$row[pb_jumin]?></td>
	<? } ?>
	<? if($config[cf_jumin_file]=="1" or $config[cf_bank_file]=="1"){ ?>
	<td style="text-align:center;">
		<? if($row[pb_filename1]){ ?>
		<div style="display:inline-block;margin:3px;text-align:center;vertical-align:top;">
			<div style="font-size:11px;color:#888;margin-bottom:2px;">주민/신분증</div>
			<a href="<?=$nfor['path']?>/file_download.php?file_tbl=jumin&file_id=<?=$row['pb_id']?>&file_number=0&view=1" target="_blank">
				<img src="<?=$nfor['path']?>/file_download.php?file_tbl=jumin&file_id=<?=$row['pb_id']?>&file_number=0&view=1" alt="주민/신분증" style="width:84px;height:84px;object-fit:cover;border:1px solid #ddd;border-radius:6px;display:block;" onerror="this.onerror=null;this.style.objectFit='contain';this.style.padding='12px';this.style.background='#f8fafc';this.src=`data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='84' height='84' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='1.5'><path d='M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z'/><polyline points='14 2 14 8 20 8'/></svg>`">
			</a>
			<a href="<?=$nfor['path']?>/file_download.php?file_tbl=jumin&file_id=<?=$row['pb_id']?>&file_number=0" class="btn btn-white btn-sm" style="margin-top:3px;display:inline-block;">다운로드</a>
		</div>
		<? } ?>
		<? if($row[pb_filename2]){ ?>
		<div style="display:inline-block;margin:3px;text-align:center;vertical-align:top;">
			<div style="font-size:11px;color:#888;margin-bottom:2px;">통장사본</div>
			<a href="<?=$nfor['path']?>/file_download.php?file_tbl=bank&file_id=<?=$row['pb_id']?>&file_number=0&view=1" target="_blank">
				<img src="<?=$nfor['path']?>/file_download.php?file_tbl=bank&file_id=<?=$row['pb_id']?>&file_number=0&view=1" alt="통장사본" style="width:84px;height:84px;object-fit:cover;border:1px solid #ddd;border-radius:6px;display:block;" onerror="this.onerror=null;this.style.objectFit='contain';this.style.padding='12px';this.style.background='#f8fafc';this.src=`data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='84' height='84' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='1.5'><path d='M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z'/><polyline points='14 2 14 8 20 8'/></svg>`">
			</a>
			<a href="<?=$nfor['path']?>/file_download.php?file_tbl=bank&file_id=<?=$row['pb_id']?>&file_number=0" class="btn btn-white btn-sm" style="margin-top:3px;display:inline-block;">다운로드</a>
		</div>
		<? } ?>
	</td>
	<? } ?>
</tr>
<?php
}
$pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");
?>
</table>

<div class="table_btn"><?=$pagelist?></div>

</form>

<script type="text/javascript">
<!--
$(document).on("click", "#chkall", function(){
	nfor_chk_all(this, 'chk');
});
//-->
</script>

<?php
include_once "tail.php";
?>