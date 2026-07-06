<style>
/*추천캠페인*/
.index_main1_con{background-color:#FFF; padding:30px 0px 30px; margin:0px auto;}
.index_main1_con .index_title{font-size:23px; color:#000; margin-bottom:10px;       font-family: 'notokr-medium'; }

</style>

<div class="index_main1_con"> 
	<div class="layout_inner">
		<div class="index_title">
			<b class="point_color4">메타 알고리즘 </b> 추천  캠페인
		</div>
		<div class="item_list_wrap">
			<div class="item_box_list">
				<?php
				$return["list"] = array();
				$return["list"] = $return["hit_campaign_list"];
				include $nfor[skin_path]."inc_index_list_item.php";
				?>
			</div>	
		</div>
	</div>
</div>



<script>
$(document).ready(function() {
	var swiperA = new Swiper('.swiper-container-campaign', {
		freeMode: true,
		spaceBetween: 10,
		slidesPerView: 4,
		loop: true,
		navigation: {
			nextEl: '.swiper-button-next-campaign',
			prevEl: '.swiper-button-prev-campaign',
		}
	});
});
</script>