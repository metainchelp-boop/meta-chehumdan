<style>
.mainwrap { width:100%; position:relative; }
#pc_plan_banner li a { display:block; width:100%; height:500px; }
</style>
<div class="mainwrap">

	<ul id="pc_plan_banner">

		<li style="background:url(<?=$nfor[skin_path]?>img/plan01.png); background-position:top center; height:500px; min-width:1020px;">
		<a href="#" target=""></a>
		</li>
	
	</ul>

	<span id="slider-prev" class="arrow prev" style="position:absolute; top:200px; right:0px;"></span>
	<span id="slider-next" class="arrow next" style="position:absolute; top:200px; left:0px;"></span>

</div>
<script type="text/javascript">
<!--
$(function(){
	var pc_wide_banner = $('#pc_wide_banner').bxSlider({
		nextSelector: '#slider-next',
		prevSelector: '#slider-prev',
		auto: true,
		pause:4000,
		nextText: "<img src='<?=$nfor[skin_path]?>img/nextbtn.png'>",
		prevText: "<img src='<?=$nfor[skin_path]?>img/prebtn.png'>"
	});
});
//-->
</script>