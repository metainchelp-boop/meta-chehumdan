<?php
include_once "html_head.php";
?>
<style>
body { margin:0px; font-size:12px; }
#popup_body { width:<?=$popup[pop_width]?>px; height:<?=$popup[pop_height]?>px; overflow-x:hidden; overflow-y:auto; }
#popup_tail { height:30px; line-height:30px; background-color:#000; color:#fff; overflow:hidden; text-align:right; padding-right:7px; }
#popup_tail a { color:#fff; text-decoration:none; cursor:pointer; }
</style>

<div id="popup_body"><?=nl2br($popup[pop_memo])?></div>

<div id="popup_tail">
	<a class="popup_today_close" data-pop_id="<?=$popup[pop_id]?>">오늘 하루 팝업 보이지 않기</a>
	<a>|</a>
	<a class="popup_close" data-pop_id="<?=$popup[pop_id]?>">닫기</a>
</div>

<script type="text/javascript">
<!--
$(document).on("click",".popup_close",function(){ 
	self.close();
});

$(document).on("click",".popup_today_close",function(){ 
	nfor_set_cookie('popup_'+$(this).data("pop_id"),'<?=date("Y-m-d")?>',1);
	self.close();
});
//-->
</script>
<?
include_once "html_tail.php";
?>