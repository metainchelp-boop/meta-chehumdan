<?php
include_once "path.php";

$admin[access_level] = array(""=>"전체","10" => "최고관리자", "7"=>"부관리자", "2"=>"캠페인관리자", "1"=>"입점업체");
$admin[access_tab] = $nfor[admin_tab];
$admin[period_type] = array("access_insert_datetime" => "등록일자", "access_update_datetime" => "수정일자");		
$admin[gp_id][""] = "선택";
$que = sql_query("select * from nfor_access_group where gp_group='$_GET[access_tab]'");
while($data = sql_fetch_array($que)){
	$admin[gp_id][$data[gp_id]] = $data[gp_text];
}
$admin[gp_id][""] = "선택";
$que = sql_query("select * from nfor_access_group where 1");
while($data = sql_fetch_array($que)){
	$admin[gp_id][$data[gp_id]] = $data[gp_text];
}

$qstr .= "&access_tab=$access_tab&gp_id=$gp_id&access_code_select=$access_code_select";

$list = $_SERVER[PHP_SELF];
$form = str_replace("list","form",$list);
$table = "nfor_access";
$id = "access_id";

if($mode=="list_update"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		sql_query("update $table set access_text='{$_POST['access_text'][$k]}', access_file='{$_POST['access_file'][$k]}', access_table='{$_POST['access_table'][$k]}', access_rank='{$_POST['access_rank'][$k]}', access_level='{$_POST['access_level'][$k]}' where $id='{$_POST[$id][$k]}'");
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
$sql_search = " where access_level < 11 ";

$all_count_sql = "select count(*) as cnt $sql_common $sql_search";

if($access_tab) $sql_search .= " and access_tab='$access_tab' ";

if($gp_id) $sql_search .= " and gp_id='$gp_id' ";

if($access_code_select){
	$sql_search .= " and access_code='$access_code_select' and access_path='1' ";
} else{
	$sql_search .= " and access_path='0' ";
}

if($keyword) $sql_search .= " and (access_text like '%$keyword%' or access_file like '%$keyword%') ";

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
				  $sql_order
				  limit $from_record, $page_row ";
$result = sql_query($sql);

include_once "head.php";
?>
<form name="fsearch" id="fsearch" method="get">

<? if(!$access_code_select){ ?>
<table class="table cols_tbl">
<tr>
	<th>메뉴</th>
	<td>
		<div class="form-inline">
		<?=admin_select($_GET,"access_tab","","","0")?>
		<?=admin_select($_GET,"gp_id","","","0")?>
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

<? } ?>

<?=admin_hidden($_GET,"access_code_select")?>


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
	<th>테이블명</th>
	<th>출력순위</th>
	<th>접근레벨</th>
	<th>등록일자</th>
	<th>소속메뉴</th>
	<th>수정</th>
	<th>삭제</th>
</tr>
<?php
for($i=0; $row=sql_fetch_array($result); $i++){	
	$row["chk[]"] = $i;
	$row["{$id}[{$i}]"] = $row[$id];
	$row["access_table[]"] = $row[access_table];
	$row["access_text[]"] = $row[access_text];
	$row["access_file[]"] = $row[access_file];
	$row["access_table[]"] = $row[access_table];
	$row["access_rank[]"] = $row[access_rank];
	$row["access_level[]"] = $row[access_level];
	$admin["access_level[]"] = $admin[access_level];
?>
<tr>
	<td><?=admin_checkbox($row,"chk[]","chk")?><?=admin_hidden($row,"{$id}[{$i}]")?></td>
	<td><?=$row[access_tab]?> > <?=admin_echo($row,"gp_id")?></td>
	<td><?=admin_text($row,"access_text[]")?></td>
	<td><?=admin_text($row,"access_file[]")?></td>
	<td><?=admin_text($row,"access_table[]")?></td>
	<td><?=admin_text($row,"access_rank[]","width-50p")?></td>
	<td><?=admin_select($row,"access_level[]")?></td>
	<td><?=substr($row[access_insert_datetime],0,10)?></td>
	<td><? if($row[access_path]=="0"){ ?><?=admin_a("submenu", "소속메뉴", "btn btn-white btn-sm", "", "{$list}?access_code_select={$row[access_code]}")?><? } ?></td>
	<td><? if($row[access_path]=="0"){ ?><?=admin_a("edit", "수정", "btn btn-white btn-sm", "", "{$form}?{$qstr}&{$id}={$row[$id]}")?><? } ?></td>
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
	<?=admin_button("list_update", "선택수정", "btn btn-lg btn-red")?>
	<?=admin_a("form", "등록하기", "btn-lg btn-black btn", "", $form."?$qstr")?>
	</div>

</div>

<div class="table_btn"><?=$pagelist?></div>

</form>

<script type="text/javascript">
<!--
$('#access_tab').change(access_tab_change);

function access_tab_change(){

	var gp_group = $(this).val();

	$.ajax({
		type     : "get",
		url      : nfor_path + "/json.php",
		data     : "mode=access_group&gp_group="+gp_group,
		dataType : 'json',
		cache: false,
		success  : function(data) {
			var output = '<option value="">선택</option>';
			for(var i=0; i<data.data.length; i++) {
				output += '<option value="' + data.data[i].gp_id + '">' + data.data[i].gp_text + '</option>';
			}
			$('#gp_id').empty().append(output);
		},
		error: function(){
			console.log("Ajax failed");
		}
	});
}

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