<?php
include_once "path.php";

$admin[access_level] = array(""=>"전체","10" => "최고관리자", "7"=>"부관리자", "2"=>"캠페인관리자", "1"=>"입점업체");
$admin[access_tab] = $nfor[admin_tab];

$qstr .= "&access_tab=$access_tab&gp_id=$gp_id&access_code_select=$access_code_select";

$form = $_SERVER[PHP_SELF];
$list = str_replace("form","list",$form);
$table = "nfor_access";
$id = "access_id";

$id_value = $$id;

if($id_value){
	$write = sql_fetch("select * from $table where $id='{$id_value}'");
}

if($mode=="insert" or $mode=="update"){
	demo_check();

	$access_field = json_encode($_REQUEST[access_field_chk],JSON_UNESCAPED_UNICODE); // php5.3 이상부터 적용
	if($access_field=="null") $access_field="[]";
	
	$add_sql = "";
	$where_sql = "";

	if($mode=="insert"){
		$msg = "정상적으로 등록 되었습니다";
		$move = "$list";
		$add_sql .= ", access_insert_datetime=NOW()";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$move = "$form?{$qstr}&{$id}={$id_value}";
		$where_sql = "  where $id='{$id_value}'";
		$add_sql .= ", access_update_datetime=NOW()";
	} else{

	}
	$common_sql = " $table set access_field='$access_field', access_text='$access_text', access_file='$_POST[access_file]', access_rank='$access_rank', access_level='$access_level', access_table='$access_table', access_tab='$access_tab', gp_id='$gp_id'";

	sql_query("$mode $common_sql $add_sql $where_sql");

	if($mode=="insert"){
		$access_id = sql_insert_id();

		if($access_code_select){
			$data = sql_fetch("select * from nfor_access where access_id='$access_code_select'");
			sql_query("update nfor_access set access_path='1', access_tab='$data[access_tab]', gp_id='$data[gp_id]', access_code='$data[access_code]'  where access_id='$access_id'");
			$move .= "?access_code_select=$access_code_select";
		} else{
			sql_query("update nfor_access set access_path='0', access_code='$access_id' where access_id='$access_id'");
		}
	}

	alert($msg,$move);
}

include_once "head.php";
?>

<form name="fwrite" method="post" onsubmit="return fsubmit(this)">
<?=admin_hidden($write,"mode")?>
<?=admin_hidden($write,$id)?>

<?=admin_hidden($_GET,"access_code_select")?>




<table class="table cols_tbl margin0">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<?php
if(!$access_code_select){
?>
<tr>
	<th>메뉴구분</th>
	<td>
		<div class="form-inline">
		<?=admin_select($write,"access_tab","","","0")?>
		<?
		$admin[gp_id][""] = "선택";
		$que = sql_query("select * from nfor_access_group where gp_group='$write[access_tab]'");
		while($data = sql_fetch_array($que)){
			$admin[gp_id][$data[gp_id]] = $data[gp_text];
		}
		?>
		<?=admin_select($write,"gp_id","","","0")?>
		</div>
	</td>
</tr>
<? } ?>
<tr>
	<th>메뉴명</th> 
	<td><?=admin_text($write,"access_text","width-200p")?></td>
</tr>
<tr>
	<th>파일명</th> 
	<td><?=admin_text($write,"access_file","width-200p")?></td>
</tr>
<tr>
	<th>테이블</th> 
	<td><?=admin_text($write,"access_table","width-200p")?></td>
</tr>
<tr>
	<th>출력순위</th> 
	<td><?=admin_text($write,"access_rank","width-50p")?></td>
</tr>
<tr>
	<th>접근레벨</th> 
	<td><div class="form-inline"><?=admin_select($write,"access_level")?></div></td>
</tr>
</table>

<?php
if($write[access_table]){
?>
<br>
※ 미사용 기능(사용 편의상 제외되었습니다)
<table class="table cols_tbl margin0">
<colgroup>
	<col class="width-150p">
	<col >
	<col class="width-150p">
	<col >
</colgroup>
<tr>
<?php
$access_field_array = json_decode($write[access_field]);
$i = 0;
$que = sql_query("SELECT `COLUMN_NAME`,`COLUMN_COMMENT` FROM information_schema.COLUMNS WHERE `TABLE_NAME` = '$write[access_table]' and TABLE_SCHEMA='$nfor[sql_db]'");
while($data = sql_fetch_array($que)){
	if($i and $i%2==0) echo "</tr><tr>";
?>
	<th><?=$data[COLUMN_NAME]?></th>
	<td><?=$data[COLUMN_COMMENT]?> <input type="checkbox" name="access_field_chk[]" value="<?=$data[COLUMN_NAME]?>" <?=in_array($data[COLUMN_NAME],$access_field_array)?"checked":""?>></td>
<?
	$i++;
}
?>
</tr>
</table>
<? } ?>

<div class="bottom_btn">
	<div class="form-inline">
		<?=admin_submit("fsubmit_btn", $write[$id]?"수정하기":"등록하기", "btn btn-lg btn-red")?>
		<?=admin_a("list", "목록보기", "btn btn-lg btn-black", "", $list."?".$qstr)?>
	</div>
</div>

</form>

<SCRIPT LANGUAGE="JavaScript">
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

function fsubmit(f){

	f.action = "<?=$form?>";
	return true;	    
}	
//-->
</SCRIPT>

<?php
include_once "tail.php";
?>