<?php
include_once "path.php";

$qstr .= "&field_type=$field_type";

$form = $_SERVER[PHP_SELF];
$list = str_replace("form","list",$form);
$table = "nfor_field";
$id = "fd_id";

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
		$add_sql .= ", fd_insert_id='$member[mb_no]', fd_insert_datetime=NOW()";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$move = "$form?{$qstr}&{$id}={$id_value}";
		$where_sql = "  where $id='{$id_value}'";
		$add_sql .= ", fd_update_id='$member[mb_no]', fd_update_datetime=NOW()";
	} else{

	}
	$common_sql = " $table set fd_table='$fd_table', fd_name='$fd_name', fd_text='$fd_text', fd_memo='$fd_memo'";

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
	<th>테이블명</th> 
	<td><?=admin_text($write,"fd_table")?></td>
</tr>
<tr>
	<th>필드명</th> 
	<td><?=admin_text($write,"fd_name")?></td>
</tr>
<tr>
	<th>필드설명</th> 
	<td><?=admin_text($write,"fd_text")?></td>
</tr>
<tr>
	<th>메모</th> 
	<td><?=admin_textarea($write,"fd_memo")?></td>
</tr>
<? if($write[$id]){ ?>
<tr>
	<th>등록일시</th> 
	<td><?=$write[fd_insert_datetime]?></td>
</tr>
<? } ?>
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