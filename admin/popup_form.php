<?php
include_once "path.php";

$admin[pop_width_type] = array(""=>"전체","px" => "px");
$admin[pop_height_type] = array(""=>"전체","px" => "px");
$admin[pop_use] = array("전체","노출","미노출");
$admin[pop_type] = array("전체","윈도우","고정레이어","이동레이어");
$admin[pop_device] = array("전체","PC","모바일");

if(!is_array($pop_type)){
	$qstr .= "&pop_type=$pop_type";
}
if(!is_array($pop_use)){
	$qstr .= "&pop_use=$pop_use";
}

$qstr .= "&popup_type=$popup_type";

$form = $_SERVER[PHP_SELF];
$list = str_replace("form","list",$form);
$table = "nfor_popup";
$id = "pop_id";

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
		$add_sql .= ", pop_insert_datetime=NOW(), pop_insert_id='$member[mb_no]'";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$move = "$form?{$qstr}&{$id}={$id_value}";
		$where_sql = "  where $id='{$id_value}'";
		$add_sql .= ", pop_update_datetime=NOW(), pop_update_id='$member[mb_no]'";
	} else{

	}
	$common_sql = " $table set pop_device='$pop_device', pop_subject='$pop_subject', pop_memo='$pop_memo', pop_sdatetime='$pop_sdatetime', pop_edatetime='$pop_edatetime', pop_x='$pop_x', pop_y='$pop_y', pop_width='$pop_width', pop_height='$pop_height', pop_width_type='$pop_width_type', pop_height_type='$pop_height_type', pop_use='$pop_use', pop_type='$pop_type'";

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
	<th>접근기기</th> 
	<td>
	<?
	if(!$write[pop_device]) $write[pop_device] = "1";
	?>
	<?=admin_radio($write,"pop_device")?>




<script type="text/javascript">
<!--
$(document).on("click","input[name=pop_device]",function(){
	if(this.value=="2"){
		$(".device_pc").addClass("hide");
	} else{
		$(".device_pc").removeClass("hide");
	}
});
//-->
</script>






	</td>
</tr>




<tr>
	<th>노출여부</th> 
	<td>
	<?
	if(!$write[pop_use]) $write[pop_use] = "1";
	?>
	<?=admin_radio($write,"pop_use")?>
	</td>
</tr>
<tr class="device_pc <?=$write[pop_device]=="2"?"hide":""?>">
	<th>형태</th> 
	<td>
	<?
	if(!$write[pop_type]) $write[pop_type] = "1";
	?>
	<?=admin_radio($write,"pop_type")?>
	</td>
</tr>
<tr class="device_pc <?=$write[pop_device]=="2"?"hide":""?>">
	<th>위치</th> 
	<td>
	<div class="form-inline">
	상단(x좌표) : <?=admin_text($write,"pop_x","width-80p")?>
	좌측(y좌표) : <?=admin_text($write,"pop_y","width-80p")?>
	</div>
	</td>
</tr>
<tr class="device_pc <?=$write[pop_device]=="2"?"hide":""?>">
	<th>사이즈</th> 
	<td>
	<div class="form-inline">
	가로(width) : <?=admin_text($write,"pop_width","width-80p")?> <?=admin_select($write,"pop_width_type","width-40p")?>
	세로(height) : <?=admin_text($write,"pop_height","width-80p")?> <?=admin_select($write,"pop_height_type","width-40p")?>
	</div>
	</td>
</tr>
<tr>
	<th>제목</th> 
	<td><?=admin_text($write,"pop_subject")?></td>
</tr>
<tr>
	<th>내용</th> 
	<td><?=admin_editor($write,"pop_memo")?></td>
</tr>
<tr>
	<th>노출기간</th>
	<td>
	<div class="form-inline">
	<?=admin_text($write,"pop_sdatetime","width-130p datepicker-here","data-timepicker=\"true\" data-language=\"ko\"")?>
	<?=admin_text($write,"pop_edatetime","width-130p datepicker-here","data-timepicker=\"true\" data-language=\"ko\"")?>
	</div>
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
	<?=admin_editor_update("pop_memo")?>

	f.action = "<?=$form?>";
	return true;	    
}	
//-->
</SCRIPT>

<?php
include_once "tail.php";
?>