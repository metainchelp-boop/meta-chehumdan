<?php
include_once "path.php";
$admin[menu_tab] = array(""=>"선택","대메뉴" => "대메뉴", "고객센터"=>"고객센터", "마이페이지"=>"마이페이지");

$admin[menu_pc_scroll] = array(""=>"선택", "1"=>"스크롤", "0" => "페이징");
$admin[menu_mobile_scroll] = array(""=>"선택", "1"=>"스크롤", "0" => "페이징");
$admin[period_type] = array("menu_insert_datetime" => "등록일자", "menu_update_datetime" => "수정일자");	

$qstr .= "&menu_tab=$menu_tab";

$list = $_SERVER[PHP_SELF];
$form = str_replace("list","form",$list);
$table = "nfor_menu";
$id = "menu_id";

if($mode=="list_update"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		sql_query("update $table set menu_text='{$_POST['menu_text'][$k]}', menu_file='{$_POST['menu_file'][$k]}', menu_rank='{$_POST['menu_rank'][$k]}', menu_pc_scroll='{$_POST['menu_pc_scroll'][$k]}', menu_mobile_scroll='{$_POST['menu_mobile_scroll'][$k]}', menu_pc_rows='{$_POST['menu_pc_rows'][$k]}', menu_mobile_rows='{$_POST['menu_mobile_rows'][$k]}' where $id='{$_POST[$id][$k]}'");
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
$sql_search = " where 1 ";

$all_count_sql = "select count(*) as cnt $sql_common $sql_search";

if($menu_tab) $sql_search .= " and menu_tab='$menu_tab' ";

$sql_search .= " and menu_path='0' ";

if($keyword) $sql_search .= " and (menu_text like '%$keyword%' or menu_file like '%$keyword%') ";

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

$config[cf_page_rows] = 100;
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
<form name="fsearch" id="fsearch" method="get">


<table class="table cols_tbl">
<tr>
	<th>메뉴</th>
	<td>
		<div class="form-inline">
		<?=admin_select($_GET,"menu_tab","","","0")?>
		</div>
	</td>
</tr>
<tr>
	<th>검색어</th>
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

<div class="table_btn"><div class="form-inline"><?=admin_submit("fsubmit", "검색하기", "btn-lg btn-black btn")?></div></div>

<div class="ofw martop20">
	<div class="flol">
	전체 <span class="txt_red"><?=number_format($total_count)?></span>건 / 검색 <span class="txt_red"><?=number_format($search_count)?></span>건
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
<tr>
	<th><?=admin_checkbox($row,"chkall")?></th>
	<th>메뉴구분</th>
	<th>메뉴명</th>
	<th>파일명</th>
	<th>출력순위</th>

	<th>PC 출력형태/페이지 출력</th>
	<th>모바일 출력형태/페이지 출력</th>



	<th>등록일자</th>

	<th>수정</th>
	<th>삭제</th>
</tr>
<?php
for($i=0; $row=sql_fetch_array($result); $i++){	
	$row["chk[]"] = $i;
	$row["{$id}[{$i}]"] = $row[$id];

	$row["menu_text[]"] = $row[menu_text];
	$row["menu_file[]"] = $row[menu_file];
	$row["menu_rank[]"] = $row[menu_rank];

	$row["menu_pc_scroll[]"] = $row[menu_pc_scroll];
	$admin["menu_pc_scroll[]"] = $admin[menu_pc_scroll];

	$row["menu_mobile_scroll[]"] = $row[menu_mobile_scroll];
	$admin["menu_mobile_scroll[]"] = $admin[menu_mobile_scroll];

	$row["menu_pc_rows[]"] = $row[menu_pc_rows];
	$row["menu_mobile_rows[]"] = $row[menu_mobile_rows];
?>
<tr>
	<td><?=admin_checkbox($row,"chk[]","chk")?><?=admin_hidden($row,"{$id}[{$i}]")?></td>
	<td><?=$row[menu_tab]?></td>

	<td><?=admin_text($row,"menu_text[]")?></td>
	<td><?=admin_text($row,"menu_file[]")?></td>
	<td><?=admin_text($row,"menu_rank[]","width-50p")?></td>

	<td><div class="form-inline"><?=admin_select($row,"menu_pc_scroll[]")?> <?=admin_text($row,"menu_pc_rows[]","width-50p")?></div></td>
	<td><div class="form-inline"><?=admin_select($row,"menu_mobile_scroll[]")?> <?=admin_text($row,"menu_mobile_rows[]","width-50p")?></div></td>

	<td><?=substr($row[menu_insert_datetime],0,10)?></td>



	<td><? if($row[menu_path]=="0"){ ?><?=admin_a("edit", "수정", "btn btn-white btn-sm", "", "{$form}?{$qstr}&{$id}={$row[$id]}")?><? } ?></td>
	<td><?=admin_a("delete", "삭제", "btn btn-white btn-sm nfor_button", "data-confirm=\"삭제하시겠습니까?\" data-data=\"mode=delete&{$id}={$row[$id]}\"")?></td>
</tr>
<?php
}
$pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");
?>
</table>

<div class="bottom_btn">
	
	<div class="form-inline">
	<!-- <?=admin_button("list_delete", "선택삭제", "btn btn-lg btn-red")?> -->
	<?=admin_button("list_update", "선택수정", "btn btn-lg btn-red")?>
	<?=admin_a("form", "등록하기", "btn-lg btn-black btn", "", $form."?$qstr")?>
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

$(document).on("click", "#list_update", function(){
	nfor_list_reload('수정','list_update');
});
//-->
</script>

<?php
include_once "tail.php";
?>