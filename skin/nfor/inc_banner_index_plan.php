<style>
.best_plan {overflow: hidden; margin-top: 10px; padding: 29px 0 15px;  border-bottom: 1px solid #dfe2e6; background: #fff;}
.best_plan h2{margin-bottom: 28px;font-size: 18px; color: #16181a; font-weight:normal; text-align: center;}
.best_plan .list{width:80%; overflow:hidden; margin:0px auto;}
.best_plan .list li{float:left;}
.best_plan .list img{width:100%;}

.best_plan .btn{padding:20px;}
.best_plan .btn .btn_ic_arr {display: inline-block;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;  box-sizing: border-box;  width: 100%;  height: 42px;  border: 1px solid rgba(22,24,26,.2); font-size: 13px; line-height: 42px; color: #6c7580;  text-align: center;}

.swiper-container-index-plan { width:100%; height:auto; position:relative; overflow:hidden; }
.swiper-container-index-plan .swiper-slide { width:80%; }
.swiper-container-index-plan .swiper-slide img { width:100%; }
</style>


<div class="best_plan">

	<h2>추천 기획전</h2>

	<div class="swiper-container-index-plan">
	<div class="swiper-wrapper">
	<?php
	$sql = "select *  from nfor_plan where pl_display='1' or (pl_display='2' and pl_sdate <='$nfor[ymdhis]' and pl_edate >='$nfor[ymdhis]') and pl_view_mobile='1' order by pl_rank desc limit 10";
	$que = sql_query($sql);
	while($plan=sql_fetch_array($que)){	
	?>
	  <div class="swiper-slide"><a href="plan.php?pl_id=<?=$plan[pl_id]?>"><img src="<?=thumbnail("$nfor[path]/data/plan/$plan[pl_img]",600,"",0,1)?>"></div>
	<? } ?>
	</div>
	</div>

	<div class="btn">
		<a href="plan_list.php" class="btn_ic_arr">기획전 더보기<i></i></a>
	</div>

</div>


<script>
var swiper = new Swiper('.swiper-container-index-plan', {
	slidesPerView: 'auto',
	loop: true,
	autoHeight: true,
	autoplay: {
		delay: 2500,
		disableOnInteraction: false,
	},
	grabCursor: true,
	spaceBetween: 10,
	centeredSlides: true,
});
</script>