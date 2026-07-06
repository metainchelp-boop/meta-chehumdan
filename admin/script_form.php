<?php
include_once "path.php";

$admin[st_use] = array("전체","사용함","사용안함","테스트모드");

if(!is_array($st_use)){
	$qstr .= "&st_use=$st_use";
}

$form = $_SERVER[PHP_SELF];
$list = str_replace("form","list",$form);
$table = "nfor_script";
$id = "st_id";

$id_value = $$id;

if($id_value){
	$write = sql_fetch("select * from $table where $id='{$id_value}'");
}

if($mode=="insert" or $mode=="update"){
	demo_check();

	$add_sql = "";
	$where_sql = "";

	if($mode=="insert"){
		$msg = "정상적으로 등록 되었습니다";
		$move = "$list";
		$add_sql .= ", st_insert_id='$member[mb_no]', st_insert_datetime=NOW()";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$move = "$form?{$qstr}&{$id}={$id_value}";
		$where_sql = "  where $id='{$id_value}'";
		$add_sql .= ", st_update_id='$member[mb_no]', st_update_datetime=NOW()";
	} else{

	}

	$common_sql = " $table set st_name='$st_name', st_use='$st_use', st_top_use='$st_top_use', st_bottom_use='$st_bottom_use', st_page_use='$st_page_use', st_top='$st_top', st_bottom='$st_bottom'";
	sql_query("$mode $common_sql $add_sql $where_sql");

	if($write[$id]){
		$sp_parents = $write[$id];
	} else{
		$sp_parents = sql_insert_id();
	}

	sql_query("delete from nfor_script_page where sp_parents='$sp_parents'");
	for($i=0; $i<count($sp_file); $i++){
		sql_query("insert nfor_script_page set sp_file='{$sp_file[$i]}', sp_script='{$sp_script[$i]}', sp_parents='$sp_parents'");
	}

	alert($msg,$move);
}

include_once "head.php";

$admin["sp_file[]"][""] = "선택";
for($i=0; $i<count($nfor[script_name]); $i++){
	$admin["sp_file[]"][$nfor[script_file][$i]] = $nfor[script_name][$i]." : ".$nfor[script_file][$i];
}
?>

<form name="fwrite" method="post" onsubmit="return fsubmit(this)">
<?=admin_hidden($write,"mode")?>
<?=admin_hidden($write,$id)?>

<table class="table cols_tbl margin0">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>스크립트명</th>
	<td><?=admin_text($write,"st_name","","maxlength=\"15\"")?></td>
</tr>
<tr>
	<th>사용설정</th>
	<td><?=admin_radio($write,"st_use")?></td>
</tr>
<tr>
	<th>삽입위치</th>
	<td>
	<?
	if($write[st_top_use]){
		$checked[st_top_use] = "checked";
	}
	if($write[st_bottom_use]){
		$checked[st_bottom_use] = "checked";		
	}
	if($write[st_page_use]){
		$checked[st_page_use] = "checked";
	}
	$write[st_top_use] = "1";
	$write[st_bottom_use] = "1";
	$write[st_page_use] = "1";
	?>
	<?=admin_checkbox($write,"st_top_use","",$checked[st_top_use],"상단 공통영역(HEAD안쪽)")?>
	<?=admin_checkbox($write,"st_bottom_use","",$checked[st_bottom_use],"하단 공통영역")?>
	<?=admin_checkbox($write,"st_page_use","",$checked[st_page_use],"삽입페이지 선택")?>

	</td>
</tr>
<tr id="st_top_tr">
	<th>상단 공통영역(HEAD안쪽)</th>
	<td>
	<?=admin_textarea($write,"st_top","","rows=\"15\"")?>
	</td>
</tr>
<tr id="st_bottom_tr">
	<th>하단 공통영역</th>
	<td>
	<?=admin_textarea($write,"st_bottom","","rows=\"15\"")?>
	</td>
</tr>
<tr id="st_page_tr">
	<th>삽입페이지 선택</th>
	<td>

	<?=admin_button("add_st_page","삽입페이지 추가","btn-gray width-100p marbottom5")?>
	<?=admin_help("※ 스크립트를 추가할 페이지를 선택 후 스크립트를 입력하세요. (최대 12개 페이지 추가 가능)")?>

	<ul id="div_page_append">
	<?
	$k = 0;
	$que = sql_query("select * from nfor_script_page where sp_parents='{$write[$id]}' order by sp_id asc");
	while($data = sql_fetch_array($que)){
	?>
	<li id="script_page_<?=$k?>" data-li_id="<?=$k?>">

		<div class="form-inline">		
		<?
		$data["sp_file[]"] = $data[sp_file];
		?>
		<?=admin_select($data,"sp_file[]","","","0")?>
		<?=admin_button("delete", "삭제", "li_remove btn btn-white btn-sm")?>
		</div>
		<?
		$data["sp_script[]"] = $data[sp_script];
		?>
		<?=admin_textarea($data,"sp_script[]","","rows=\"15\"")?>

	</li>
	<?
		$k++;
	}
	?>
	</ul>

	</td>
</tr>
</table>

<div class="bottom_btn">
	<div class="form-inline">
		<?=admin_submit("fsubmit_btn", $write[$id]?"수정하기":"등록하기", "btn btn-lg btn-red")?>
		<?=admin_a("list", "목록보기", "btn btn-lg btn-black", "", $list."?".$qstr)?>
	</div>
</div>

</form>


<SCRIPT LANGUAGE="JavaScript">
<!--
function st_checkbox(name,bool){
	if(bool==true){
		$(name).show();
	} else{
		$(name).hide();
	}
}

$(document).ready(function () {

	st_checkbox("#st_top_tr",<?=$checked[st_top_use]?"true":"false"?>);
	st_checkbox("#st_bottom_tr",<?=$checked[st_bottom_use]?"true":"false"?>);
	st_checkbox("#st_page_tr",<?=$checked[st_page_use]?"true":"false"?>);

	$("#st_top_use").click(function () {		
		st_checkbox("#st_top_tr",this.checked);
	});
	$("#st_bottom_use").click(function () {	
		st_checkbox("#st_bottom_tr",this.checked);
	});
	$("#st_page_use").click(function () {		
		st_checkbox("#st_page_tr",this.checked);
	});

});

$(document).on("click", "#add_st_page", function(){

	script_id = $("#div_page_append li").last().data("li_id");
	script_id = script_id + 1;

	template = _.template($('#append_page').html());
	template_html = template({
		script_id: script_id,
	});

	$("#div_page_append").append(template_html);

});

function fsubmit(f){
	if($("#st_page_use:checked").val() && $("#div_page_append li").length<1){
		alert("삽입페이지를 추가하거나 삽입페이지 선택을 해제해주세요");
		return false;
	}
	f.action = "<?=$form?>";
	return true;	    
}	
//-->
</SCRIPT>
	
<script type="text/html" id="append_page">
<li id="script_page_<%=script_id%>" data-li_id="<%=script_id%>">
	<div class="form-inline">
	<?=admin_select($data,"sp_file[]","","","0")?>
	<?=admin_button("delete", "삭제", "li_remove btn btn-white btn-sm")?>
	</div>	
	<?=admin_textarea($data,"sp_script[]","","rows=\"15\"")?>
</li>
</script>

<?php
include_once "tail.php";
?>