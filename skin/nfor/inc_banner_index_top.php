<style>
.swiper-container-index-top { width:100%; height:auto; position:relative; overflow:hidden; }
.swiper-container-index-top .swiper-slide img { width:100%; }
.swiper-container-index-top .swiper-pagination {  bottom:10px; left:auto; right:10px; background-color:rgba(0,0,0,.6); width:50px; font-size:12px; color:#fff; padding:5px 7px; border-radius:15px; } 
</style>


<div class="swiper-container-index-top">
    <div class="swiper-wrapper">
		<?php
		$bn_code = "index_top";
		$sql = "select * from nfor_banner where bn_use='1' and bn_code='$bn_code' and ((bn_period_use='1') or (bn_period_use='2' and bn_sdatetime <='$nfor[ymdhis]' and bn_edatetime >='$nfor[ymdhis]')) order by bn_rank desc";
		$que = sql_query($sql);
		while($banner=sql_fetch_array($que)){	
			$banners[] = $banner;
		?>
		<div class="swiper-slide"><a href="<?=$banner[bn_href]?>" target="<?=$banner[bn_target]?>"><img src="<?="$nfor[path]/data/banner/$banner[bn_img]"?>"></a></div>
		<? } ?>
    </div>
    <div class="swiper-pagination"></div>
</div>


<script>
$(document).ready(function () {

	var swiper = new Swiper('.swiper-container-index-top', {
		loop: true,
		autoHeight: true,
		autoplay: {
			delay: 2500,
			disableOnInteraction: false,
		},
		pagination: {
			el: '.swiper-pagination',
			type: 'fraction',
		},
		/*
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
		*/
	});

});

$(document).on("click", ".swiper-pagination-fraction", function(){ 
	$(".all_banner_popup").show().addClass("slideInUp");
});

$(document).on("click", ".all_banner_popup_close_btn", function(){ 
	$(".all_banner_popup").hide().removeClass("slideInUp");
});
</script>






<style>
.all_banner_popup { display:none; overflow:hidden; position:fixed; width:100%; height:100%; left:0px; top:0px; background-color:#fff; z-index:99998; }
.all_banner_popup li img { width:100%; border-bottom:1px solid #fff;  }
.all_banner_popup_list_wrap { overflow:auto; width:100%; height:100%; }
.all_banner_popup_close_btn { z-index:99999; position:absolute; top:10px; right:10px; background-color:rgba(0,0,0,.6); width:30px; font-size:12px; color:#fff; padding:5px 7px; border-radius:15px; text-align:center; } 

.slideInUp {
	-webkit-animation-name: slideInUp;
	animation-name: slideInUp;
	-webkit-animation-duration: 0.5s;
	animation-duration: 0.5s;
	-webkit-animation-fill-mode: both;
	animation-fill-mode: both;
}
@-webkit-keyframes slideInUp {
	0% {
		-webkit-transform: translateY(100%);
		transform: translateY(100%);
		visibility: visible;
	}
	100% {
		-webkit-transform: translateY(0);
		transform: translateY(0);
	}
}
@keyframes slideInUp {
	0% {
		-webkit-transform: translateY(100%);
		transform: translateY(100%);
		visibility: visible;
	}
	100% {
		-webkit-transform: translateY(0);
		transform: translateY(0);
	}
} 
</style>

<div class="all_banner_popup">

	<div class="all_banner_popup_close_btn">-</div>
	
	<div class="all_banner_popup_list_wrap">
		<ul>
			<?php
			for($i=0; $i<count($banners); $i++){	
				$banner = $banners[$i];
			?>
			<li><a href="<?=$banner[bn_href]?>" target="<?=$banner[bn_target]?>"><img src="<?="$nfor[path]/data/banner/$banner[bn_img]"?>"></a></li>
			<? } ?>
		</ul>
	</div>

</div>