<?php
include_once "path.php";

$qstr .= "&keyword_type=$keyword_type";

$form = $_SERVER[PHP_SELF];
$list = str_replace("form","list",$form);
$table = "nfor_keyword";
$id = "ke_id";

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
		$add_sql .= ", ke_insert_id='$member[mb_no]', ke_insert_datetime=NOW()";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$move = "$form?{$qstr}&{$id}={$id_value}";
		$where_sql = "  where $id='{$id_value}'";
		$add_sql .= ", ke_update_id='$member[mb_no]', ke_update_datetime=NOW()";
	} else{

	}
	$common_sql = " $table set ke_keyword='$ke_keyword', ke_rank='$ke_rank', ke_current='$ke_current'";

	sql_query("$mode $common_sql $add_sql $where_sql");

	keyword_update();

	alert($msg,$move);
}

include_once "head.php";
?>

<form name="fwrite" method="post" onsubmit="return fsubmit(this)" enctype="multipart/form-data">
<?=admin_hidden($write,"mode")?>
<?=admin_hidden($write,$id)?>




<table class="table cols_tbl margin0">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>키워드</th> 
	<td><?=admin_text($write,"ke_keyword","width-150p")?></td>
</tr>
<tr>
	<th>랭킹</th> 
	<td>
	<div class="form-inline"><?=admin_text($write,"ke_rank","width-60p")?>위</div>
	</td>
</tr>
<tr>
	<th>변경순위</th> 
	<td>
	<div class="form-inline"><?=admin_text($write,"ke_current","width-60p")?></div>
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
	if(!$('#ke_keyword').val()){
		alert("키워드를 입력해주세요");
		$('#ke_keyword').focus();
        return false;
	}
	if(!$('#ke_rank').val()){
		alert("랭킹을 입력해주세요");
		$('#ke_rank').focus();
        return false;
	}
	if(!$('#ke_current').val()){
		alert("변경순위를 입력해주세요");
		$('#ke_current').focus();
        return false;
	}
	f.action = "<?=$form?>";
	return true;	    
}	
//-->
</SCRIPT>

<?php
include_once "tail.php";
?>