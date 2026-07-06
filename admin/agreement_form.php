<?php
include_once "path.php";

$admin[ag_use] = array("전체","노출","미노출");
$admin[ag_group] = array("전체","개별","카피라이트");

if(!is_array($ag_use)){
	$qstr .= "&ag_use=$ag_use";
}

$qstr .= "&agreement_type=$agreement_type";

$form = $_SERVER[PHP_SELF];
$list = str_replace("form","list",$form);
$table = "nfor_agreement";
$id = "ag_id";

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
		$add_sql .= ", ag_insert_id='$member[mb_no]', ag_insert_datetime=NOW()";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$move = "$form?{$qstr}&{$id}={$id_value}";
		$where_sql = "  where $id='{$id_value}'";
		$add_sql .= ", ag_update_id='$member[mb_no]', ag_update_datetime=NOW()";
	} else{

	}
	$common_sql = " $table set ag_code='$ag_code', ag_name='$ag_name', ag_memo='$ag_memo', ag_group='$ag_group', ag_use='$ag_use'";

	sql_query("$mode $common_sql $add_sql $where_sql");
	alert($msg,$move);
}

include_once "head.php";
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
	<th>코드</th> 
	<td><?=admin_text($write,"ag_code")?></td>
</tr>
<tr>
	<th>항목명</th> 
	<td><?=admin_text($write,"ag_name")?></td>
</tr>
<tr>
	<th>내용</th> 
	<td><?=admin_editor($write,"ag_memo")?></td>
</tr>

<tr>
	<th>노출형태</th> 
	<td>
	<?php
	if(!$write[ag_group]) $write[ag_group] = "1";
	?>
	<?=admin_radio($write,"ag_group")?>
	</td>
</tr>


<tr>
	<th>노출여부</th> 
	<td>
	<?php
	if(!$write[ag_use]) $write[ag_use] = "1";
	?>
	<?=admin_radio($write,"ag_use")?>
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
	<?=admin_editor_update("ag_memo")?>

	f.action = "<?=$form?>";
	return true;	    
}	
//-->
</SCRIPT>

<?php
include_once "tail.php";
?>