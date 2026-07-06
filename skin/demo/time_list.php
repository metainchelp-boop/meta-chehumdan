<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.time_bg{height:234px; background:url('<?=$nfor[skin_path]?>img/time_bg.png')center; margin-bottom:30px;}
.time_wrap{position: relative}
.time_wrap .time{overflow:hidden; text-align:center;  margin-bottom: 20px; height:234px;font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;}
.time_wrap .time .left{ font-size:28px; padding:55px 0px 20px; line-height: 31px; color:#FFF;} 
.time_wrap .time .left b{color:#ffcc00; font-weight:normal;}
.time_wrap .time .right{ font-size:55px; font-weight:bold; color:#FFF; position:absolute; top:123px; left:50%; margin-left:-150px;}
.time_wrap .time .right span{font-size:12px;}
#countdown b{display:inline-block; margin-right:21px; letter-spacing:6px;}
#countdown .color_y{color:#ffff00}
</style>

<div class="time_wrap">
	<div class="time_bg">
		<div class="time layout_inner">
			<div class="left fotm">
				평소보다 더큰할인 
				<b>매일 AM 12시 오픈</b>되는 <b>한정특가 아이템</b><br>
			</div>
			<div class="right">
				<div id="countdown"><b><?=sprintf("%02d",$h)?></b><b><?=sprintf("%02d",$i)?></b><b class="color_y"><?=sprintf("%02d",$s)?></b></div>
			</div>
		</div>
	</div>

	<div class="item_list_wrap layout_inner"><?php include_once $nfor[skin_path]."inc_item_list.php"; ?></div>

</div>

<script>
$(function (){
	var austDay = new Date(<?=strtotime($today)*1000?>);
	$('#countdown').countdown({until: austDay,  layout: " <b>{hnn}</b><b>{mnn}</b><b class=color_y>{snn}</b>" });
});
</script>

<?php
include_once $nfor[skin_path]."tail.php";
?>