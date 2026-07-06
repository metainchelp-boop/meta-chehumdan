<?php
include_once "path.php";

$admin[val_use] = array("전체","노출","미노출");
$admin[period_type] = array("val_insert_datetime" => "등록일자", "val_update_datetime" => "수정일자");	
$admin[val_code][""] = "전체";
$que = sql_query("select * from nfor_value_group where 1 order by gp_id desc");
while($row = sql_fetch_array($que)){
	$admin[val_code][$row[gp_code]] = $row[gp_name];
}

$qstr .= "&val_code=$val_code&val_use=$val_use";

$list = $_SERVER[PHP_SELF];
$form = str_replace("list","form",$list);
$table = "nfor_value";
$id = "val_id";

if($mode=="list_update"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		sql_query("update $table set val_use='{$_POST['val_use'][$k]}' where $id='{$_POST[$id][$k]}'");
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

if($val_code) $sql_search .= " and val_code = '$val_code' ";

if($val_use) $sql_search .= " and val_use = '$val_use' ";

if($keyword) $sql_search .= " and val_name like '%$keyword%' ";

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
<table class="table cols_tbl">
<tr>
	<th>그룹</th> 
	<td colspan="3"><?=admin_select($_GET,"val_code","width-200p","","0")?></td>
</tr>
<tr>
	<th>항목값</th>
	<td><?=admin_text($_GET,"keyword","","maxlength=\"30\"")?></td>
	<th>노출여부</th>
	<td><?=admin_radio($_GET,"val_use","","","0")?></td>
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
<colgroup>
	<col class="width-50p">
	<col>
	<col>
	<col class="width-100p">
	<col class="width-100p">
	<col class="width-80p">
	<col class="width-80p">
</colgroup>
<tr>
	<th><?=admin_checkbox($row,"chkall")?></th>
	<th><div class="form-inline">그룹 <?=admin_button("val_code_btn", "추가","btn btn-white btn-sm btn-th")?></div></th>
	<th>항목값</th>
	<th>노출여부</th>
	<th>등록일자</th>
	<th>수정</th>
	<th>삭제</th>
</tr>
<?php
for($i=0; $row=sql_fetch_array($result); $i++){
	$row["chk[]"] = $i;
	$row["{$id}[{$i}]"] = $row[$id];
	$row["val_use[]"] = $row[val_use];
	$admin["val_use[]"] = $admin[val_use];
?>
<tr>
	<td><?=admin_checkbox($row,"chk[]","chk")?><?=admin_hidden($row,"{$id}[{$i}]")?></td>
	<td><?=admin_echo($row,"val_code")?></td>
	<td><?=$row[val_name]?></td>
	<td><?=admin_select($row,"val_use[]","width-80p")?></td>
	<td><?=substr($row[val_insert_datetime],0,10)?></td>
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
	<?=admin_button("list_delete", "선택삭제", "btn-lg btn-red btn")?>
	<?=admin_button("list_update", "선택수정", "btn btn-lg btn-red")?>
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

$(document).on("click","#val_code_btn",function(){ 
	nfor_layer('value','','사용자정의 데이터 그룹관리');
});
$(document).on("click", "#list_delete", function(){
	nfor_list_reload('삭제','list_delete');
});
$(document).on("click", "#list_update", function(){
	nfor_list_reload('수정','list_update');
});
//-->
</script>







<script>
$(document).on("click",".nfor_pagination a",function(){ 
	var page = $(this).data("page");
	$("#layer_value #layer_page").val(page);
	layer_value_search();
});

function layer_value_search(){
	$("#layer_value #layer_mode").val("");
	$.get("layer_value.php", $("#layer_value #fsearch").serialize(), function (data) {
		$("#layer_value").html(data);
	});
}

$(document).on("click","#layer_value #search_btn",function(){ 
	layer_value_search();
});

$(document).on("keydown","#layer_value #keyword",function(e){ 
	if(e.keyCode == 13){
		layer_value_search();
		return false;
	}
});

$(document).on("click","#layer_value #allchk",function(){ 
	nfor_chk_all(this, 'gp_chk');
});

function layer_value_insert(){

	if(!$("#layer_value #gp_code").val()){
		alert("그룹코드를 입력해주세요");
		$("#layer_value #gp_code").focus();
		return;
	}

	if(!$("#layer_value #gp_name").val()){
		alert("그룹이름을 입력해주세요");
		$("#layer_value #gp_name").focus();
		return;
	}

	$.ajax({
		type: "post",
		url: "layer_value.php",
		data: $("#layer_insert_form").serialize(),
		cache: false,
		async: false,
		success: function(response){
			var json = $.parseJSON(response);
			if(json["result"]=="ok"){
				layer_value_search();
			} else{
				alert(json["msg"]);
			}
		}
	});
}

$(document).on("click","#layer_value #submit_btn",function(){ 
	layer_value_insert();
});

$(document).on("keydown","#layer_value #gp_name, #layer_value #gp_code",function(e){ 
	if(e.keyCode == 13){
		layer_value_insert();
		return false;
	}
});

$(document).on("click","#layer_value .delete",function(){ 

	var gp_id = $(this).data("gp_id");

	var gp_name = $(this).data("gp_name");

	if(confirm("\"" + gp_name + "\" 를 삭제하시겠습니까?")){

		$.ajax({
			type: "post",
			url: "layer_value.php",
			data: {
				"mode":"delete",
				"gp_id":gp_id
			},
			cache: false,
			async: false,
			success: function(response){
				var json = $.parseJSON(response);
				if(json["result"]=="ok"){
					layer_value_search();
				} else{
					alert(json["msg"]);
				}
			}
		});

	}
});

$(document).on("click","#layer_value #select_delete_btn",function(){ 
	if($(".gp_chk:checked").length<1){
		alert("삭제할 데이터를 선택해주세요");
		return false;
	}

	if(confirm("선택하신 데이터를 삭제하시겠습니까?")){

		$("#layer_value #layer_mode").val("list_delete");
		$.ajax({
			type: "post",
			url: "layer_value.php",
			data: $("#layer_value #fsearch").serialize(),
			cache: false,
			async: false,
			success: function(response){

				var json = $.parseJSON(response);
				if(json["result"]=="ok"){
					layer_value_search();
				} else{
					alert(json["msg"]);
				}
			}
		});

	}
});


$(document).on("click","#layer_value #select_update_btn",function(){ 
	if($(".gp_chk:checked").length<1){
		alert("수정할 데이터를 선택해주세요");
		return false;
	}

	if(confirm("선택하신 데이터를 수정하시겠습니까?")){

		$("#layer_value #layer_mode").val("list_update");
		$.ajax({
			type: "post",
			url: "layer_value.php",
			data: $("#layer_value #fsearch").serialize(),
			cache: false,
			async: false,
			success: function(response){

				var json = $.parseJSON(response);
				if(json["result"]=="ok"){
					alert(json["msg"]);
					layer_value_search();
				} else{
					alert(json["msg"]);
				}
			}
		});

	}
});
</script>


<?php
include_once "tail.php";
?>