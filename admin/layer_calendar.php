<?php
include_once "path.php";

if($mode=="insert"){
	sql_query("insert nfor_memo set me_memo='$me_memo', me_mb_no='$member[mb_no]', me_ymd='$me_ymd'");
	json_return("등록되었습니다","ok");
}

if($mode=="update"){
	sql_query("update nfor_memo set me_memo='$me_memo' where me_mb_no='$member[mb_no]' and me_ymd='$me_ymd'");
	json_return("수정되었습니다","ok");
}

if($mode=="delete"){
	sql_query("delete from nfor_memo where me_mb_no='$member[mb_no]' and me_ymd='$me_ymd'");
	json_return("삭제되었습니다","ok");
}

$write = sql_fetch("select * from nfor_memo where me_mb_no='$member[mb_no]' and me_ymd='$me_ymd'");
?>

<form id="layer_insert_form">
<input type="hidden" name="mode" value="<?=$write[me_id]?"update":"insert"?>">
<input type="hidden" name="me_ymd" id="me_ymd" value="<?=$write[me_ymd]?$write[me_ymd]:$me_ymd?>">
<?=admin_textarea($write,"me_memo")?>
<div class="bottom_btn">
	<?=admin_submit("memo_submit_btn", "메모저장", "btn btn-black")?>
	<? if($write[me_ymd]){ ?><?=admin_button("memo_delete_btn", "메모삭제", "btn btn-red")?><? } ?>
</div>
</form>