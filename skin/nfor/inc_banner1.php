<div class="mHome-visual"  style>
	<div class="swiper-container swiper-container-a">
		<ul class="mSlider-home swiper-wrapper">
		<?php
		for($i=0; $i<count($return[banner][index_slider1]["list"]); $i++){
			$banner = $return[banner][index_slider1]["list"][$i];
		?>
			<li class="swiper-slide"><div ><a href="<?=$banner[bn_href]?>" target="<?=$banner[bn_target]?>"><img src="<?=$banner[bn_img_over]?>" alt="" style="border-radius:15px;"></a></div></li>
		<? } ?>
		</ul>
		<div class="swiper-pagination-a"></div>
	</div>
</div>

<style>
.mHome-visual {overflow:hidden;position:relative;width:100%; background-color:#fff;padding-bottom:15px;}
.mHome-visual .swiper-wrapper  {display:-webkit-box;display:-moz-box;display:-ms-flexbox;}
.mHome-visual ul li {overflow:hidden;position:relative;background:#fff;font-size:0;line-height:0;}
.mHome-visual ul li img:focus {border:2px solid #a5c7fe;}
.swiper-container-a { margin:1px 1px; }
.swiper-container-a .swiper-slide img { width:100%; }
.swiper-pagination-bullet-active { background:#4bc019; }
#mContents:before {
    display: none;
}
</style>


<SCRIPT LANGUAGE="JavaScript">
<!--
$(function(){

	var swiperA = new Swiper('.swiper-container-a', {
		loop: true,			
		autoplay:1000,
		paginationClickable: true,
		pagination: '.swiper-pagination-a'
	});

});
//-->
</SCRIPT>