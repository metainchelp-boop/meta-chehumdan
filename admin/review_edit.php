<?php
include_once "path.php";


$table = "nfor_review";
$id = "rv_id";
$id_value = $$id;

$write = sql_fetch("select * from $table where $id='{$id_value}'");


if(!isset($write['rv_id'])) alert_close("잘못된 접근입니다");

if($write['rv_supply_no']<>$member['mb_no'] and $write['rv_md_no']<>$member['mb_no'] and $member['mb_admin'] < 7){
	alert_close("잘못된 접근입니다");
}



if($mode=="update"){
	sql_query("update $table set rv_edit='$rv_edit', rv_edit_datetime=NOW(), rv_step='7' where $id='{$id_value}'");
	$url = "url_input_win.php?rv_id=$write[rv_id]";
	$nfor[tmp_edit] = $rv_edit;
	nfor_send("review_edit_request",$write[rv_mb_email],$write[rv_mb_hp],$write[rv_mb_no],$write[rv_id],$url);


	alert_close_refresh("수정요청이 완료되었습니다");
}

include_once "html_head.php";
?>

<style>
#rv_edit { width:98%; height:300px; border:1px solid #d5d5d5; margin:5px 0px; }
</style>

<div class="table_wrap">

<form name="fwrite" method="post" onsubmit="return fsubmit(this)" enctype="multipart/form-data">
<?=admin_hidden($write,"mode")?>
<?=admin_hidden($write,$id)?>
<table class="table cols_tbl margin0">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>캠페인명</th>
	<td><?=$write[rv_cp_subject]?></td>
</tr>
<tr>
	<th>닉네임</th>
	<td><?=$write[rv_mb_nick]?$write[rv_mb_nick]:$write[rv_mb_id]?></td>
</tr>
<tr>
	<th>리뷰URL</th>
	<td><a href="<?=$write[rv_url]?>" target="_blank"><?=$write[rv_url]?></a></td>
</tr>
<tr>
	<th>수정요청사항</th>
	<td>
		<?=admin_textarea($write,"rv_edit")?>
	</td>
</tr>
</table>

<div class="bottom_btn"><?=admin_submit("fsubmit_btn", "수정요청하기", "btn btn-lg btn-red")?></div>

</div>

</form>

<SCRIPT LANGUAGE="JavaScript">
<!--
function fsubmit(f){

	if(!$("#rv_edit").val()){
		alert("수정요청사항을 입력해주세요");
		$("#rv_edit").focus();
		return false;
	}

	f.action = "<?=$PHP_SELF?>";
	return true;	    
}	
//-->
</SCRIPT>


<?php
include_once "html_tail.php";
?>