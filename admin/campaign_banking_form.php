<?php
include_once "path.php";

$admin[bnk_use] = array("전체", "노출", "미노출");

$qstr .= "&bnk_use=$bnk_use";

$form = $_SERVER[PHP_SELF];
$list = str_replace("form","list",$form);
$table = "nfor_campaign_banking";
$id = "bnk_id";

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
		$add_sql .= ", bnk_insert_id='$member[mb_no]', bnk_insert_datetime=NOW()";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$move = "$form?{$qstr}&{$id}={$id_value}";
		$where_sql = "  where $id='{$id_value}'";
		$add_sql .= ", bnk_update_id='$member[mb_no]', bnk_update_datetime=NOW()";
	} else{

	}
	$common_sql = " $table set bnk_bank='$bnk_bank', bnk_number='$bnk_number', bnk_name='$bnk_name', bnk_rank='$bnk_rank', bnk_use='$bnk_use'";

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
	<th>은행명</th> 
	<td><?=admin_text($write,"bnk_bank","width-150p")?></td>
</tr>
<tr>
	<th>계좌번호</th> 
	<td><?=admin_text($write,"bnk_number","width-150p")?></td>
</tr>
<tr>
	<th>예금주</th> 
	<td><?=admin_text($write,"bnk_name","width-80p")?></td>
</tr>
<tr>
	<th>노출순위</th> 
	<td><?=admin_text($write,"bnk_rank","width-50p")?></td>
</tr>
<tr>
	<th>노출여부</th> 
	<td>
	<?
	if(!$write[bnk_use]) $write[bnk_use] = "1";
	?>
	<?=admin_radio($write,"bnk_use")?>
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