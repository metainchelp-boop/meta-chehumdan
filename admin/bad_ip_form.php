<?php
include_once "path.php";

$form = $_SERVER[PHP_SELF];
$list = str_replace("form","list",$form);
$table = "nfor_bad_ip";
$id = "bd_id";

$id_value = $$id;

if($id_value){
	$write = sql_fetch("select * from $table where $id='{$id_value}'");
}

if($mode=="insert" or $mode=="update"){
	demo_check();

	if($REMOTE_ADDR==$bd_ip) alert("등록자 아이피와 동일한 아이피는 설정할수 없습니다");

	$add_sql = "";
	$where_sql = "";

	if($mode=="insert"){
		$msg = "정상적으로 등록 되었습니다";
		$move = "$list";
		$add_sql .= ", bd_insert_id='$member[mb_no]', bd_insert_datetime=NOW()";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$move = "$form?{$qstr}&{$id}={$id_value}";
		$where_sql = "  where $id='{$id_value}'";
		$add_sql .= ", bd_update_id='$member[mb_no]', bd_update_datetime=NOW()";
	} else{

	}
	$common_sql = " $table set bd_ip='$bd_ip', bd_memo='$bd_memo'";

	sql_query("$mode $common_sql $add_sql $where_sql");
	alert($msg,$move);
	exit;
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
	<th>아이피</th> 
	<td><?=admin_text($write,"bd_ip")?></td>
</tr>
<tr>
	<th>차단사유</th> 
	<td><?=admin_text($write,"bd_memo")?></td>
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
	if(!$('#bd_ip').val()){
		alert("아이피를 입력해주세요");
		$('#bd_ip').focus();
        return false;
	}
	if(!$('#bd_memo').val()){
		alert("차단사유를 입력해주세요");
		$('#bd_memo').focus();
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