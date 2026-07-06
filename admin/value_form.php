<?php
include_once "path.php";

$admin[val_use] = array("전체","노출","미노출");

if(!is_array($val_use)){
	$qstr .= "&val_use=$val_use";
}

$qstr .= "&val_code=$val_code";

$form = $_SERVER[PHP_SELF];
$list = str_replace("form","list",$form);
$table = "nfor_value";
$id = "val_id";

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
		$add_sql .= ", val_insert_id='$member[mb_no]', val_insert_datetime=NOW()";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$move = "$form?{$qstr}&{$id}={$id_value}";
		$where_sql = "  where $id='{$id_value}'";
		$add_sql .= ", val_update_id='$member[mb_no]', val_update_datetime=NOW()";
	} else{

	}
	$common_sql = " $table set val_code='$val_code', val_name='$val_name', val_use='$val_use'";

	sql_query("$mode $common_sql $add_sql $where_sql");
	alert($msg,$move);
}

include_once "head.php";
$admin[val_code][""] = "전체";
$que = sql_query("select * from nfor_value_group where 1 order by gp_id desc");
while($row = sql_fetch_array($que)){
	$admin[val_code][$row[gp_code]] = $row[gp_name];
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
	<th>그룹</th> 
	<td><?=admin_select($write,"val_code","width-200p","","0")?></td>
</tr>
<tr>
	<th>항목값</th> 
	<td><?=admin_text($write,"val_name","width-300p")?></td>
</tr>
<tr>
	<th>노출여부</th> 
	<td>
	<?
	if(!$write[val_use]) $write[val_use] = "1";
	?>
	<?=admin_radio($write,"val_use")?>
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
function fsubmit(f){
	f.action = "<?=$form?>";
	return true;	    
}	
//-->
</SCRIPT>

<?php
include_once "tail.php";
?>