<?php
include_once "path.php";

$admin[pg_use] = array("전체", "노출", "미노출");

$qstr .= "&pg_use=$pg_use";

$form = $_SERVER[PHP_SELF];
$list = str_replace("form","list",$form);
$table = "nfor_page";
$id = "pg_id";

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
		$add_sql .= ", pg_insert_id='$member[mb_no]', pg_insert_datetime=NOW()";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$move = "$form?{$qstr}&{$id}={$id_value}";
		$where_sql = "  where $id='{$id_value}'";
		$add_sql .= ", pg_update_id='$member[mb_no]', pg_update_datetime=NOW()";
	} else{

	}
	$common_sql = " $table set pg_code='$pg_code', pg_name='$pg_name', pg_memo='$pg_memo', pg_memo_mobile='$pg_memo_mobile', pg_use='$pg_use'";

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
	<td><?=admin_text($write,"pg_code")?></td>
</tr>
<tr>
	<th>항목명</th> 
	<td><?=admin_text($write,"pg_name")?></td>
</tr>
<tr>
	<th>내용(PC)</th> 
	<td><?=admin_editor($write,"pg_memo")?></td>
</tr>
<tr>
	<th>내용(모바일)</th> 
	<td><?=admin_editor($write,"pg_memo_mobile")?></td>
</tr>
<tr>
	<th>노출여부</th> 
	<td>
	<?
	if(!$write[pg_use]) $write[pg_use] = "1";
	?>
	<?=admin_radio($write,"pg_use")?>
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
	<?=admin_editor_update("pg_memo")?>
	<?=admin_editor_update("pg_memo_mobile")?>
	f.action = "<?=$form?>";
	return true;
}	
//-->
</SCRIPT>

<?php
include_once "tail.php";
?>