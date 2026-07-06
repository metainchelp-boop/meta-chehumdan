<?php
include_once "path.php";

$admin[keyword_type] = array(""=>"전체","bn_alt" => "배너이름", "bn_href"=>"배너링크");
$admin[bn_use] = array("전체","노출","미노출");
$admin[bn_target] = array(""=>"전체","_self" => "현제창", "_blank"=>"새창");
$admin[bn_period_use] = array("전체","상시노출","기간노출");
$admin[bn_code][""] = "전체";

$admin[period_type] = array("bn_insert_datetime" => "등록일자", "bn_update_datetime" => "수정일자");

$qstr .= "&bn_code=$bn_code&bn_use=$bn_use";

$list = $_SERVER[PHP_SELF];
$form = str_replace("list","form",$list);
$table = "nfor_random";
$id = "bn_id";

if($mode=="list_update"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		sql_query("update $table set bn_use='{$_POST['bn_use'][$k]}' where $id='{$_POST[$id][$k]}'");
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

if($bn_code) $sql_search .= " and bn_code = '$bn_code' ";

if($bn_use) $sql_search .= " and bn_use = '$bn_use' ";

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
				  $sql_order
				  limit $from_record, $page_row ";
$result = sql_query($sql);

include_once "head.php";
?>

<form name="fsearch" id="fsearch" method="get">
<table class="table cols_tbl">
<tr>
	<th>검색어</th>
	<td colspan="3">
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

<div class="table_btn"><div class="form-inline"><?=admin_submit("fsubmit", "검색하기", "btn-lg btn-black btn")?></div></div>

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
<tr>
	<th><?=admin_checkbox($row,"chkall")?></th>
	<th><div class="form-inline">배너이미지</div></th>
	<th>배너이름</th>
	<th>배너링크</th>
	<th>배너타겟</th>
	<th>노출여부</th>
	<th>노출기간</th>
	<th>등록일</th>
	<th>수정</th>
	<th>삭제</th>
</tr>
<?php
for($i=0; $row=sql_fetch_array($result); $i++){	
	$row["chk[]"] = $i;
	$row["{$id}[{$i}]"] = $row[$id];

	$row["bn_use[]"] = $row[bn_use];
	$admin["bn_use[]"] = $admin[bn_use];
?>
<tr>
	<td><?=admin_checkbox($row,"chk[]","chk")?><?=admin_hidden($row,"{$id}[{$i}]")?></td>
	<td><?=admin_img("banner",$row[bn_img],"40","40")?></td>
	<td><?=$row[bn_alt]?></td>
	<td><?=$row[bn_href]?></td>
	<td><?=$row[bn_target]?></td>
	<td><?=admin_select($row,"bn_use[]","width-80p")?></td>
	<td><?=admin_echo($row,"bn_period_use")?><? if($row[bn_period_use]=="2"){ ?><br>(<?=substr($row[bn_sdatetime],0,10)?>~<?=substr($row[bn_edatetime],0,10)?>)<? } ?></td>
	<td><?=substr($row[bn_insert_datetime],0,10)?></td>
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
	<?=admin_button("list_delete", "선택삭제", "btn btn-lg btn-red")?>
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

$(document).on("click", "#list_delete", function(){
	nfor_list_reload('삭제','list_delete');
});

$(document).on("click", "#list_update", function(){
	nfor_list_reload('수정','list_update');
});

$(document).on("click","#bn_group_btn",function(){ 
	nfor_layer('banner','','배너그룹관리');
});
//-->
</script>











<script>
$(document).on("click",".nfor_pagination a",function(){ 
	var page = $(this).data("page");
	$("#layer_banner #layer_page").val(page);
	layer_banner_search();
});

function layer_banner_search(){
	$("#layer_banner #layer_mode").val("");
	$.get("layer_banner.php", $("#layer_banner #fsearch").serialize(), function (data) {
		$("#layer_banner").html(data);
	});
}

$(document).on("click","#layer_banner #search_btn",function(){ 
	layer_banner_search();
});

$(document).on("keydown","#layer_banner #keyword",function(e){ 
	if(e.keyCode == 13){
		layer_banner_search();
		return false;
	}
});

$(document).on("click","#layer_banner #allchk",function(){ 
	nfor_chk_all(this, 'gp_chk');
});

function layer_banner_insert(){

	if(!$("#layer_banner #gp_code").val()){
		alert("그룹코드를 입력해주세요");
		$("#layer_banner #gp_code").focus();
		return;
	}

	if(!$("#layer_banner #gp_name").val()){
		alert("그룹이름을 입력해주세요");
		$("#layer_banner #gp_name").focus();
		return;
	}

	$.ajax({
		type: "post",
		url: "layer_banner.php",
		data: $("#layer_insert_form").serialize(),
		cache: false,
		async: false,
		success: function(response){
			var json = $.parseJSON(response);
			if(json["result"]=="ok"){
				layer_banner_search();
			} else{
				alert(json["msg"]);
			}
		}
	});
}

$(document).on("click","#layer_banner #submit_btn",function(){ 
	layer_banner_insert();
});

$(document).on("keydown","#layer_banner #gp_name, #layer_banner #gp_code",function(e){ 
	if(e.keyCode == 13){
		layer_banner_insert();
		return false;
	}
});

$(document).on("click","#layer_banner .delete",function(){ 

	var gp_id = $(this).data("gp_id");

	var gp_name = $(this).data("gp_name");

	if(confirm("\"" + gp_name + "\" 를 삭제하시겠습니까?")){

		$.ajax({
			type: "post",
			url: "layer_banner.php",
			data: {
				"mode":"delete",
				"gp_id":gp_id
			},
			cache: false,
			async: false,
			success: function(response){
				var json = $.parseJSON(response);
				if(json["result"]=="ok"){
					layer_banner_search();
				} else{
					alert(json["msg"]);
				}
			}
		});

	}
});

$(document).on("click","#layer_banner #select_delete_btn",function(){ 
	if($(".gp_chk:checked").length<1){
		alert("삭제할 데이터를 선택해주세요");
		return false;
	}

	if(confirm("선택하신 데이터를 삭제하시겠습니까?")){

		$("#layer_banner #layer_mode").val("list_delete");
		$.ajax({
			type: "post",
			url: "layer_banner.php",
			data: $("#layer_banner #fsearch").serialize(),
			cache: false,
			async: false,
			success: function(response){

				var json = $.parseJSON(response);
				if(json["result"]=="ok"){
					layer_banner_search();
				} else{
					alert(json["msg"]);
				}
			}
		});

	}
});


$(document).on("click","#layer_banner #select_update_btn",function(){ 
	if($(".gp_chk:checked").length<1){
		alert("수정할 데이터를 선택해주세요");
		return false;
	}

	if(confirm("선택하신 데이터를 수정하시겠습니까?")){

		$("#layer_banner #layer_mode").val("list_update");
		$.ajax({
			type: "post",
			url: "layer_banner.php",
			data: $("#layer_banner #fsearch").serialize(),
			cache: false,
			async: false,
			success: function(response){

				var json = $.parseJSON(response);
				if(json["result"]=="ok"){
					alert(json["msg"]);
					layer_banner_search();
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