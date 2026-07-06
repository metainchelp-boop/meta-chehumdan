<?php
include_once "path.php";

$admin[bn_use] = array("전체","노출","미노출");
$admin[bn_target] = array(""=>"전체","_self" => "현제창", "_blank"=>"새창");
$admin[bn_period_use] = array("전체","상시노출","기간노출");

if(!is_array($bn_use)){
//	$qstr .= "&bn_use=$bn_use";
}

$qstr .= "&banner_type=$banner_type&bn_code=$bn_code";

$form = $_SERVER[PHP_SELF];
$list = str_replace("form","list",$form);
$table = "nfor_random";
$id = "bn_id";

$id_value = $$id;

if($id_value){
	$write = sql_fetch("select * from $table where $id='{$id_value}'");
}

if($mode=="insert" or $mode=="update"){
	demo_check();

	$add_sql = "";
	$where_sql = "";

	$add_sql .= admin_upload($write,"bn_img","banner","$table"," where $id='{$id_value}'");
	$add_sql .= admin_upload($write,"bn_img_over","banner","$table"," where $id='{$id_value}'");
	$add_sql .= admin_upload($write,"bn_img_hover","banner","$table"," where $id='{$id_value}'");

	if($mode=="insert"){
		$msg = "정상적으로 등록 되었습니다";
		$move = "$list";
		$add_sql .= ", bn_insert_datetime=NOW(), bn_insert_id='$member[mb_no]'";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$move = "$form?{$id}={$id_value}";
		$where_sql = "  where $id='{$id_value}'";
		$add_sql .= ", bn_update_datetime=NOW(), bn_update_id='$member[mb_no]'";
	} else{

	}
	$common_sql = " $table set bn_code='$bn_code', bn_alt='$bn_alt', bn_href='$bn_href', bn_target='$bn_target', bn_rank='$bn_rank', bn_use='$bn_use', bn_period_use='$bn_period_use', bn_sdatetime='$bn_sdatetime', bn_edatetime='$bn_edatetime'";

	sql_query("$mode $common_sql $add_sql $where_sql");
	alert($msg,$move);
}

include_once "head.php";

$admin[bn_code][""] = "선택";

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
	<th>배너설명</th> 
	<td><div class="form-inline"><?=admin_text($write,"bn_alt","width-400p")?></div></td>
</tr>
<tr>
	<th>배너링크</th> 
	<td><?=admin_text($write,"bn_href")?></td>
</tr>
<tr>
	<th>배너타겟</th> 
	<td><div class="form-inline"><?=admin_select($write,"bn_target")?></div></td>
</tr>
<tr>
	<th>배너이미지 PC<br>넓이 480px</th>
	<td><?=admin_file($write,"bn_img","banner")?></td>
</tr>
<tr>
	<th>배너이미지 모바일<br>넓이 750px</th>
	<td><?=admin_file($write,"bn_img_over","banner")?></td>
</tr>
<!--tr>
	<th>배너이미지 여분2</th>
	<td><?=admin_file($write,"bn_img_hover","banner")?></td>
</tr-->
<tr>
	<th>노출여부</th> 
	<td>
	<?
	if(!$write[bn_use]) $write[bn_use] = "1";
	?>
	<?=admin_radio($write,"bn_use")?>
	</td>
</tr>
<tr>
	<th>노출기간</th> 
	<td>
	<?
	if(!$write[bn_period_use]) $write[bn_period_use] = "1";
	?>
	<?=admin_radio($write,"bn_period_use")?>

	<div id="bn_period_use_2">
	<div class="form-inline">
	<?=admin_text($write,"bn_sdatetime","width-130p datepicker-here","data-timepicker=\"true\" data-language=\"ko\"")?>
	<?=admin_text($write,"bn_edatetime","width-130p datepicker-here","data-timepicker=\"true\" data-language=\"ko\"")?>
	</div>
	</div>

	</td>
</tr>
<!--tr>
	<th>정렬</th> 
	<td><div class="form-inline"><?=admin_text($write,"bn_rank","width-80p")?></div></td>
</tr-->
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
$(document).on("click", "input[name=bn_period_use]", function(){
	nfor_radio_click("bn_period_use",this.value);
});
$(document).ready(function (){
	nfor_radio_click("bn_period_use","<?=$write[bn_period_use]?>");
});

function fsubmit(f){

	if(!$('#bn_alt').val()){
		alert("배너설명을 입력해주세요");
		$('#bn_alt').focus();
        return false;
	}
	if(!$('#bn_href').val()){
		alert("배너링크를 입력해주세요");
		$('#bn_href').focus();
        return false;
	}
	if($('[name=bn_period_use]:checked').val()=="2"){
		if(!$('#bn_sdatetime').val()){
			alert("시작일시를 입력해주세요");
			$('#bn_sdatetime').focus();
			return false;
		}
		if(!$('#bn_edatetime').val()){
			alert("종료일시를 입력해주세요");
			$('#bn_edatetime').focus();
			return false;
		}		
	}

	f.action = "<?=$form?>";
	return true;	    
}	
//-->
</SCRIPT>

<?php
include_once "tail.php";
?>