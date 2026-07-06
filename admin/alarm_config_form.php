<?php
include_once "path.php";

$admin[sd_alarm_use] = array("전체","사용","미사용");
$admin["sd_alarm_use[]"] = $admin[sd_alarm_use];

if(!is_array($sd_alarm_use)){
	$qstr .= "&sd_alarm_use=$sd_alarm_use";
}

$qstr .= "&alarm_config_type=$alarm_config_type";

$form = $_SERVER[PHP_SELF];
$list = str_replace("form","list",$form);
$table = "nfor_send";
$id = "sd_id";

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
		$add_sql .= ", sd_insert_id='$member[mb_no]', sd_insert_datetime=NOW()";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$move = "$form?{$qstr}&{$id}={$id_value}";
		$where_sql = "  where $id='{$id_value}'";
		$add_sql .= ", sd_update_id='$member[mb_no]', sd_update_datetime=NOW()";
	} else{

	}
	$common_sql = " $table set sd_name='$sd_name', sd_code='$sd_code', sd_alarm_msg='$sd_alarm_msg', sd_alarm_use='$sd_alarm_use'";

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
	<td><?=admin_text($write,"sd_code","",$write[sd_id]?"readonly":"")?></td>
</tr>
<tr>
	<th>구분</th>
	<td><?=admin_text($write,"sd_name","",$write[sd_id]?"readonly":"")?></td>
</tr>
<tr>
	<th>알림메시지내용</th> 
	<td><?=admin_textarea($write,"sd_alarm_msg","","rows=\"5\"")?></td>
</tr>
<tr>
	<th>사용여부</th> 
	<td>
	<?
	if(!$write[sd_alarm_use]) $write[sd_alarm_use] = "1";
	?>
	<?=admin_radio($write,"sd_alarm_use")?>
	</td>
</tr>
</table>

<div class="bottom_btn">
	<div class="form-inline">
		<?=admin_submit("fsubmit", $write[$id]?"수정하기":"등록하기", "btn btn-lg btn-red")?>
		<?=admin_a("list", "목록보기", "btn btn-lg btn-black", "", $list."?".$qstr)?>
	</div>
</div>

</form>

<SCRIPT LANGUAGE="JavaScript">
<!--
function fsubmit(f){
	if(!$('#sd_alarm_msg').val()){
		alert("알림메시지 내용을 입력해주세요");
		$('#sd_alarm_msg').focus();
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