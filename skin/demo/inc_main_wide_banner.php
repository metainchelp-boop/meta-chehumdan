<style>
.mainwrap { width:100%; height:464px; position:relative; margin:0 auto; }
.mainwrap #slider-prev { position:absolute; top:202px; right:10px; }
.mainwrap #slider-next { position:absolute; top:202px; left:10px; }
#pc_wide_banner { overflow:hidden; width:100%; height:464px; }
#pc_wide_banner li a { display:block; width:100%; height:464px; }
.mainwrap .bx-wrapper .bx-pager.bx-default-pager a:hover, .bx-wrapper .bx-pager.bx-default-pager a.active { background-color:#e83862; }
.move_href { display:block; width:100%; height:100%; }
</style>

<div class="mainwrap">

	<div id="pc_wide_banner">
		<?php
		$bn_code = "pc_wide_banner";
		$sql = "select * from nfor_banner where bn_use='1' and bn_code='$bn_code' and ((bn_period_use='1') or (bn_period_use='2' and bn_sdatetime <='$nfor[ymdhis]' and bn_edatetime >='$nfor[ymdhis]')) order by bn_rank desc";
		$que = sql_query($sql);
		while($banner=sql_fetch_array($que)){
		?>
		<div style="background:url(<?="$nfor[path]/data/banner/$banner[bn_img]"?>); background-position:top center; height:464px; mim-width:1020px;">
		<a href="<?=$banner[bn_href]?>" target="<?=$banner[bn_target]?>" class="move_href"></a>
		</div>
		<? } ?>
	</div>

	<span id="slider-prev" class="arrow prev"></span>
	<span id="slider-next" class="arrow next"></span>

</div>

<script>
$('#pc_wide_banner').bxSlider({
	nextSelector: '#slider-prev',
	prevSelector: '#slider-next',
	auto: true,
	pause:4000,
	nextText: "<img src='<?=$nfor[skin_path]?>img/prebtn.png'>",
	prevText: "<img src='<?=$nfor[skin_path]?>img/nextbtn.png'>"
});
</script>