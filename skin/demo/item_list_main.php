<?php
include_once $nfor[skin_path]."head.php";
?>
<!-- 홈 리뉴얼 테마 (목록 페이지 로드) 2026 -->
<link rel="stylesheet" type="text/css" href="<?=$nfor[path]?>/skin/demo/css/mc_home_theme.css?v=<?=@filemtime($nfor[path]."/skin/demo/css/mc_home_theme.css")?>" />
<script src="<?=$nfor[path]?>/js/mc_home.js?v=<?=@filemtime($nfor[path]."/js/mc_home.js")?>" defer></script>
<div class="item_list_main">
	<div class="item_main_banner">
		<div class="left">
			<span><?=$navi[category]?></span>
			<?php
			$category_id_sql = substr($category_id,0,3);
			$que = sql_query("select * from nfor_item_category where cg_use='1' and cg_depth='1' and category_id like '$category_id_sql%' order by cg_rank asc");
			if(sql_num_rows($que)){
			?>

			<ul class="sub_cte">
				<?php
				while($data = sql_fetch_array($que)){
				?>
				<li <? if($data[category_id]==$category_id){ ?>class="on"<? } ?>><a href="item_list.php?category_id=<?=$data[category_id]?>"><?=$data[cg_category]?></a></li>
				<? } ?>
			</ul>

			<? } ?>
		</div>
		<div class="right" >
				<ul class="bxslider">
				<?php
				$g = 0;
				$bn_code = "pc_subbanner_$category_id";
				$sql = "select * from nfor_banner where bn_use='1' and bn_code='$bn_code' and ((bn_period_use='1') or (bn_period_use='2' and bn_sdatetime <='$nfor[ymdhis]' and bn_edatetime >='$nfor[ymdhis]')) order by bn_rank desc";
				$que = sql_query($sql);
				while($banner=sql_fetch_array($que)){
				?>
				<li><a href="<?=$banner[bn_href]?>" target="<?=$banner[bn_target]?>"><img src="<?="$nfor[path]/data/banner/$banner[bn_img]"?>" alt=""></a></li>
				<?
					$g++;
				}
				?>
				</ul>

				<span id="slider-prev" class="arrow prev"  style="position: absolute; top:45%; left:-1px;"></span>
				<span id="slider-next" class="arrow next" style="position: absolute; top:45%; right:-1px;"></span>


				<script type="text/javascript">
				<!--
				$('.bxslider').bxSlider({
				  nextSelector: '#slider-next',
				  prevSelector: '#slider-prev',
					auto: true,
					pause:4000,
				  nextText: "<img src='<?=$nfor[skin_path]?>img/nextbtn2.png'>",
				  prevText: "<img src='<?=$nfor[skin_path]?>img/prebtn2.png'>"
				});
				//-->
				</script>
		</div>
	</div>
	<!--  -->
	<div class="item_main_banner2">
	<ul>
		<?php
		$bn_code = "pc_subbanner2_$category_id";
		$sql = "select * from nfor_banner where bn_use='1' and bn_code='$bn_code' and ((bn_period_use='1') or (bn_period_use='2' and bn_sdatetime <='$nfor[ymdhis]' and bn_edatetime >='$nfor[ymdhis]')) order by bn_rank desc";
		$que = sql_query($sql);
		while($banner=sql_fetch_array($que)){							
		?>
		<li><a href="<?=$banner[bn_href]?>" target="<?=$banner[bn_target]?>"><img src="<?="$nfor[path]/data/banner/$banner[bn_img]"?>" alt=""></a></li>
		<? } ?>
		</ul>
	</div>
	<!-- // -->
	<div style="position:relative; height:40px;">
		<div class="besttit"><img src="<?=$nfor[skin_path]?>img/cete_best_tit.png"></div>
		
		<ul class="subcete">
			<li <? if($sst=="it_rank"){ ?>class="on"<? } ?>><a href="<?=$PHP_SELF?>?category_id=<?=$category_id?>&sst=it_rank&sod=desc">인기순</a></li>
			<li <? if($sst=="it_price2" and $sod=="asc"){ ?>class="on"<? } ?>><a href="<?=$PHP_SELF?>?category_id=<?=$category_id?>&sst=it_price2&sod=asc">낮은가격순</a></li>
			<li <? if($sst=="it_price2" and $sod=="desc"){ ?>class="on"<? } ?>><a href="<?=$PHP_SELF?>?category_id=<?=$category_id?>&sst=it_price2&sod=desc">높은가격순</a></li>
			<li <? if($sst=="it_star"){ ?>class="on"<? } ?>><a href="<?=$PHP_SELF?>?category_id=<?=$category_id?>&sst=it_star&sod=desc">리뷰많은순</a></li>
		</ul>
		
	</div>

	<!-- item_wrap -->
	<div class="item_list_area">

		<div class="item_box03">		
			<?
			include $nfor[skin_path]."inc_index_list_item.php";
			?>
		</div>

	</div>
	<!-- //item_wrap -->




</div>
<?
include_once $nfor[skin_path]."tail.php";
?>