<?php
include_once $nfor[skin_path]."head.php";
?>
<?php
$bn_code = "banner_list";
$bn_code_exp = explode("||",$bn_code);
$bcount = count($bn_code_exp);
//echo $bcount;
for($e=0; $e<count($bn_code_exp); $e++){

	$result = sql_query("select * from nfor_banner where bn_use='1' and bn_code='{$bn_code_exp[$e]}' and ((bn_period_use='1') or (bn_period_use='2' and bn_sdatetime <='$nfor[ymdhis]' and bn_edatetime >='$nfor[ymdhis]')) order by bn_rank desc");
	$btotal = "0";
	for($i=0; $row=sql_fetch_array($result); $i++){
		$res = array();
		$res[bn_alt] = $row[bn_alt];
		$res[bn_img] = $nfor[url]."/data/banner/".$row[bn_img];	
		$res[bn_href] = $row[bn_href];
		$res[bn_img_over] = $nfor[url]."/data/banner/".$row[bn_img_over];	
		$res[bn_target] = $row[bn_target];

		$btotal = $btotal + 1;
		$return[banner][$bn_code_exp[$e]]["list"][] = $res;
	}
	$return[banner][$bn_code_exp[$e]]["total_count"] = $i;

}
//echo $btotal;
?>
<?php if($btotal > "0") {?>
<style type="text/css">
.mainviswrap{  position:relative;  width:100%; background-color:#ffffff; padding:30px 0px 30px 0px; }	

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
.swiper-container-banner4 .swiper-slide img { width:100%; }

</style>

<div class="mainviswrap">

	<section class="slider_center">

		<a class="swiper-button-prev-banner"><img src="/skin/demo/img/prebtn.png"></a>
		<a class="swiper-button-next-banner"><img src="/skin/demo/img/nextbtn.png"></a>

		<div class="swiper-container swiper-container-banner4">
		<div class="swiper-wrapper">
		<?php
		for($i=0; $i<count($return[banner]["banner_list"]["list"]); $i++){
			$banner = $return[banner]["banner_list"]["list"][$i];
		?>
		<div class="thumb2 swiper-slide">
		  <a href="<?=$banner[bn_href]?>" target="<?=$banner[bn_target]?>"><img src="<?=$banner[bn_img]?>" alt=""></a>
		</div>
		<?php }	?>
		</div>
		</div>
	</section>

</div>



<script>
$(document).ready(function() {
	var swiper4 = new Swiper('.swiper-container-banner4', {
		freeMode: true,
		spaceBetween: 10,
		slidesPerView: 1,
		loop: true,
		navigation: {
			nextEl: '.swiper-button-next-banner',
			prevEl: '.swiper-button-prev-banner',
		}
	});
});
</script>
<?php }	?>
<style>
.bstyle1_lst { display:flex;flex-wrap: wrap; }
.bstyle1_lst li { width:33.33333%;padding:5px;box-sizing: border-box; }
.bstyle1_lst li img { width:480px; }
</style>

<div class="layout_inner">
	<div class="notice_list_wrap" style="width:100%;height:auto;overflow:hidden;min-height:800px;">

		<ul class="bstyle1_lst">
			<?php
			$to_day = date("Y-m-d H:i:s");
			$sql = " select * from nfor_random where (1) order by RAND() ";
			$result = sql_query($sql);

			for($i=0; $row=sql_fetch_array($result); $i++){
			?>
				<?php if($row[bn_use] == "1" && $row[bn_period_use] == "1" || ($row[bn_period_use] == "2" && $to_day >= $row[bn_sdatetime] && $row[bn_edatetime] >= $to_day)) { ?>

					<li>
						<a href="<?=$row[bn_href]?>" <?php if($row[bn_target] == "_blank") {?>target="_blank"<?php }?>>
							<img src="/data/banner/<?=$row[bn_img]?>" alt="" style="max-width:100%;">
							<b class="arrow"></b>
						</a>
					</li>
				<?php } ?>
			<?php } ?>
		</ul>
	</div>
</div>
<? if(!$scroll_load){ ?><div class="page_center"><?=$pagelist?></div><? } ?>


<?php
include_once $nfor[skin_path]."cus_tail.php";
?>