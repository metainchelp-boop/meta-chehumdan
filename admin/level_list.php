<?php
include_once "path.php";

$admin[period_type] = array("lv_insert_datetime" => "등록일자", "lv_update_datetime" => "수정일자");	
$admin[sort_type] = array("lv_rank" => "레벨우선순위", "lv_insert_datetime" => "등록일자");	

$list = $_SERVER[PHP_SELF];
$form = str_replace("list","form",$list);
$table = "nfor_level";
$id = "lv_id";

if($mode=="list_update"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		sql_query("update $table set lv_rank='{$_POST['lv_rank'][$k]}' where lv_id='{$_POST['lv_id'][$k]}'");
	}

	json_return("정상적으로 수정되었습니다","ok");
}

if($mode=="list_delete"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		sql_query("delete from $table where $id='{$_POST[$id][$k]}'");
	}
	json_return("정상적으로 삭제되었습니다","ok");
}

if($mode=="delete"){
	demo_check_json();
	sql_query("delete from $table where $id='{$$id}'");
	json_return("정상적으로 삭제되었습니다","ok");
}

$sql_common = " from $table ";
$sql_search = " where (1) ";

$all_count_sql = "select count(*) as cnt $sql_common $sql_search";

if($keyword) $sql_search .= " and (lv_name like '%$keyword%') ";

if($period_sdate and $period_edate and $period_type){
	$sql_search .= " and date_format($period_type,'%Y-%m-%d')>='$period_sdate' and date_format($period_type,'%Y-%m-%d')<='$period_edate' ";
}

if(!$sort){
	$sort = "lv_rank asc";
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
				  $sql_order
				  limit $from_record, $page_row ";
$result = sql_query($sql);

include_once "head.php";
?>
<?=admin_help("회원의 레벨 및 명칭, 아이콘/이미지등을 설정합니다. 레벨우선순위값은 정수이며 높을수록 상위 레벨입니다.<br>레벨등록시 아이콘/이미지 지정이 가능하며 해당 기능은 코딩 및 디자인 정책에 따라 미사용될수 있습니다.<br>레벨 삭제시는 회원정보등에 설정된값까지 영향을 받게 되므로 신중히 진행해주세요","line50 notice_gray")?>

<form name="fsearch" id="fsearch" method="get">
<table class="table cols_tbl">
<tr>
	<th>레벨명</th>
	<td><?=admin_text($_GET,"keyword","","maxlength=\"30\"")?></td>
</tr>
<tr>
	<th>기간검색</th>
	<td>

	<div class="form-inline">
		<?=admin_select($_GET,"period_type","","","0")?>
		<?=admin_text($_GET,"period_sdate","width-100p datepicker-here","data-language=\"ko\"")?> ~ <?=admin_text($_GET,"period_edate","width-100p datepicker-here","data-language=\"ko\"")?>
		<?=period_day_echo($_GET[period_day])?>
	</div>

	</td>
</tr>
</table>
<div class="table_btn"><input type="submit" value="검색하기" class="btn btn-lg btn-black"></div>

<div class="ofw martop20">
	<div class="flol">
	전체 <span class="txt_red"><?=number_format($total_count)?></span>건 / 검색 <span class="txt_red"><?=number_format($search_count)?></span>건
	</div>
	<div class="flor">
	<?=admin_sort($admin[sort_type],$sort)?>
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
	<col >
	<col >
	<col >
	<col class="width-100p">
	<col class="width-100p">
	<col class="width-80p">
	<col class="width-80p">
</colgroup>
<tr>
	<th><?=admin_checkbox($row,"chkall")?></th>
	<th>레벨고유번호</th>
	<th>레벨명</th>	
	<th>레벨아이콘</th>
	<th>레벨이미지</th>
	<th>레벨우선순위</th>
	<th>등록일자</th>
	<th>수정</th>
	<th>삭제</th>
</tr>
<?php
for($i=0; $row=sql_fetch_array($result); $i++){
	$row["chk[]"] = $i;
	$row["{$id}[{$i}]"] = $row[$id];

	$row["lv_rank[$i]"] = $row[lv_rank];
?>
<tr>
	<td><?=admin_checkbox($row,"chk[]","chk")?><?=admin_hidden($row,"{$id}[{$i}]")?></td>
	<td><?=$row[lv_id]?></td>
	<td><?=$row[lv_name]?></td>
	<td>
		<?=admin_img("level/icon",$row[lv_icon_img],"","20")?>
	</td>
	<td>
		<?=admin_img("level/img",$row[lv_img],"","20")?>
	</td>
	<td>
		<div class="form-inline"><?=admin_text($row,"lv_rank[$i]","width-50p")?></div>
	</td>
	<td><?=substr($row[lv_insert_datetime],0,10)?></td>
	<td><?=admin_a("edit", "수정", "btn btn-white btn-sm", "", "{$form}?{$qstr}&{$id}={$row[$id]}")?></td>
	<td><?=admin_a("delete", "삭제", "btn btn-white btn-sm nfor_button", "data-confirm=\"삭제하시겠습니까?\" data-data=\"mode=delete&{$id}={$row[$id]}\"")?></td>
</tr>
<?php
}
$pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");
?>
</table>

<div class="bottom_btn">
	
	<div class="form-inline">
	<?=admin_button("list_update", "선택수정", "btn btn-lg btn-red")?>
	<?=admin_button("list_delete", "선택삭제", "btn-lg btn-red btn")?>
	<?=admin_a("form", "등록하기", "btn-lg btn-black btn", "", $form)?>
	</div>

</div>

<div class="table_btn"><?=$pagelist?></div>

</form>

<script type="text/javascript">
<!--
$(document).on("click", "#chkall", function(){
	nfor_chk_all(this, 'chk')
});

$(document).on("click", "#list_delete", function(){
	nfor_list_reload('삭제','list_delete');
});

$(document).on("click", "#list_update", function(){
	nfor_list_reload('수정','list_update');
});
//-->
</script>

<?php
include_once "tail.php";
?>