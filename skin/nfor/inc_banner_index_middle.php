<style>
.swiper-container-index-middle { width:100%; height:auto; position:relative; overflow:hidden; }
.swiper-container-index-middle .swiper-slide img { width:100%; }
</style>


<div class="swiper-container-index-middle">
    <div class="swiper-wrapper">
		<?php
		$i = 0;
		$bn_code = "index_middle";
		$sql = "select * from nfor_banner where bn_use='1' and bn_code='$bn_code' and ((bn_period_use='1') or (bn_period_use='2' and bn_sdatetime <='$nfor[ymdhis]' and bn_edatetime >='$nfor[ymdhis]')) order by bn_rank desc";
		$que = sql_query($sql);
		while($banner=sql_fetch_array($que)){	
		?>
		<div class="swiper-slide"><a href="<?=$banner[bn_href]?>" target="<?=$banner[bn_target]?>"><img src="<?="$nfor[path]/data/banner/$banner[bn_img]"?>"></a></div>
		<? 
			$i++;
		}
		?>
    </div>
</div>

<script>
$(document).ready(function () {

	var swiper = new Swiper('.swiper-container-index-middle', {
		loop: true,
		autoHeight: true,
	});

});
</script>