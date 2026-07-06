<style type="text/css">
.mainviswrap{  position:relative;  width:100%; background-color:#ffffff; padding:0px 0px 0px 0px; }	

@media (max-width:1444px){
	.slider_center { width: 1100px; margin:0px auto;  position:relative;  }
.swiper-button-prev-banner{position:absolute; top:43%; left:10px; z-index:9999;}
.swiper-button-next-banner{position:absolute; top:43%; right:10px;  z-index:9999;}
}

@media (min-width:1444px){
	.slider_center { width: 1444px; margin:0px auto; position:relative;   }
.swiper-button-prev-banner{position:absolute; top:43%; left:-50px; z-index:9999;}
.swiper-button-next-banner{position:absolute; top:43%; right:-50px;  z-index:9999;}
}
.swiper-container-banner2 .swiper-slide img { width:100%; }

</style>

<div class="mainviswrap">

	<section class="slider_center">

		<a class="swiper-button-prev-banner"><img src="/skin/demo/img/prebtn.png"></a>
		<a class="swiper-button-next-banner"><img src="/skin/demo/img/nextbtn.png"></a>

		<div class="swiper-container swiper-container-banner2">
		<div class="swiper-wrapper">
		<?php
		for($i=0; $i<count($return[banner][index_slider2]["list"]); $i++){
			$banner = $return[banner][index_slider2]["list"][$i];
		?>
		<div class="thumb2 swiper-slide">
		  <a href="<?=$banner[bn_href]?>" target="<?=$banner[bn_target]?>"><img src="<?=$banner[bn_img]?>" alt="" style="border-radius:15px;"></a>
		</div>
		<?php }	?>
		</div>
		</div>
	</section>

</div>



<script>
$(document).ready(function() {
	var swiper2 = new Swiper('.swiper-container-banner2', {
		freeMode: true,
		spaceBetween: 10,
		slidesPerView: 3,
		loop: true,
		navigation: {
			nextEl: '.swiper-button-next-banner',
			prevEl: '.swiper-button-prev-banner',
		}
	});
});
</script>