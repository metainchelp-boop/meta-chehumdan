<?
$layer_ids = "";
$que = sql_query("select * from nfor_popup where pop_device='1' and pop_sdatetime < '$nfor[ymdhis]' and pop_edatetime > '$nfor[ymdhis]' and pop_use='1' and (pop_type='2' or pop_type='3')");
while($popup=sql_fetch_array($que)){	
	if(!$_COOKIE["popup_{$popup[pop_id]}"]){
		if($popup[pop_type]=="3"){
			if($layer_ids) $layer_ids .= ",";
			$layer_ids .= "#popup_".$popup[pop_id];
		}
?>
	<div id="popup_<?=$popup[pop_id]?>" class="layer_popup" style="left:<?=$popup[pop_x]?>px; top:<?=$popup[pop_y]?>px; width:<?=$popup[pop_width]?>px; height:<?=$popup[pop_height]+30?>px;">
		<div style="width:<?=$popup[pop_width]?>px; height:<?=$popup[pop_height]?>px; overflow-x:hidden; overflow-y:auto;"><?=$popup[pop_memo]?></div>
		<div class="layer_popup_tail">
			<a class="popup_today_close" data-pop_id="<?=$popup[pop_id]?>">오늘 하루 팝업 보이지 않기</a>
			<a>|</a>
			<a class="popup_close" data-pop_id="<?=$popup[pop_id]?>">닫기</a>
		</div>
	</div>
<?
	}
}
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
$(document).ready(function(){
<?
	// 새창 팝업
	$que = sql_query("select * from `nfor_popup` where pop_device='1' and pop_sdatetime < '$nfor[ymdhis]' and pop_edatetime > '$nfor[ymdhis]' and pop_use='1' and pop_type='1'");
	while($popup=sql_fetch_array($que)){
		if(!$_COOKIE["popup_{$popup[pop_id]}"]){
			$popup[pop_height] = $popup[pop_height]+30;
			echo "window.open('popup_window.php?pop_id={$popup[pop_id]}','popup_{$popup[pop_id]}','toolbar=no, width={$popup[pop_width]}, height={$popup[pop_height]}, left={$popup[pop_x]}, top={$popup[pop_y]}');\n"; 
		}
	}
	
	// 레이어팝업
	if($layer_ids){ 
?>
	$("<?=$layer_ids?>").draggable().css("cursor","move").css("z-index","300");
<? } ?>

});	

$(document).on("click",".popup_close",function(){ 
	$('#popup_'+$(this).data("pop_id")).fadeOut();
});

$(document).on("click",".popup_today_close",function(){ 
	nfor_set_cookie('popup_'+$(this).data("pop_id"),'<?=date("Y-m-d")?>',1);
	$('#popup_'+$(this).data("pop_id")).fadeOut();
});
//-->
</SCRIPT>

<style>
.layer_popup { position:absolute; z-index:200; font-size:12px; }

.layer_popup_tail { height:30px; line-height:30px; background-color:#000; color:#fff; overflow:hidden; text-align:right; padding-right:7px; }
.layer_popup_tail a { color:#fff; text-decoration:none; cursor:pointer; }
</style>
<!-- 팝업 -->