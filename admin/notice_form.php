<?php
include_once "path.php";

$admin[no_use] = array("전체","노출","미노출");

if(!is_array($no_use)){
	$qstr .= "&no_use=$no_use";
}

$qstr .= "&notice_type=$notice_type&no_category=$no_category";

$form = $_SERVER[PHP_SELF];
$list = str_replace("form","list",$form);
$table = "nfor_notice";
$id = "no_id";

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
		$add_sql .= ", no_insert_id='$member[mb_no]', no_insert_datetime=NOW()";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$move = "$form?{$qstr}&{$id}={$id_value}";
		$where_sql = "  where $id='{$id_value}'";
		$add_sql .= ", no_update_id='$member[mb_no]', no_update_datetime=NOW()";
	} else{

	}
	$common_sql = " $table set no_subject='$no_subject', no_memo='$no_memo', no_use='$no_use', no_category='$no_category'";

	sql_query("$mode $common_sql $add_sql $where_sql");
	alert($msg,$move);
}

include_once "head.php";

$admin[no_category][""] = "선택";
$que = sql_query("select * from nfor_value where val_code='notice' order by val_rank asc");
while($row = sql_fetch_array($que)){
	$admin[no_category][$row[val_name]] = $row[val_name];
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
	<th>분류</th> 
	<td><?=admin_select($write,"no_category","width-150p","","0")?></td>
</tr>
<tr>
	<th>제목</th> 
	<td><?=admin_text($write,"no_subject")?></td>
</tr>
<tr>
	<th>내용</th> 
	<td><?=admin_editor($write,"no_memo")?></td>
</tr>
<tr>
	<th>노출여부</th> 
	<td>
	<?php
	if(!$write[no_use]) $write[no_use] = "1";
	?>
	<?=admin_radio($write,"no_use")?>
	</td>
</tr>
<? if($write[$id]){ ?>
<tr>
	<th>등록일시</th> 
	<td><?=$write[no_insert_datetime]?></td>
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
	if(!$('#no_category').val()){
		alert("분류를 선택해주세요");
		$('#no_category').focus();
        return false;
	}
	if(!$('#no_subject').val()){
		alert("제목을 입력해주세요");
		$('#no_subject').focus();
        return false;
	}
	<?=admin_editor_update("no_memo")?>
	f.action = "<?=$form?>";
	return true;	    
}	
//-->
</SCRIPT>

<?php
include_once "tail.php";
?>