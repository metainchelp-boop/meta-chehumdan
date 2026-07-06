<?php
include_once $nfor[skin_path]."head.php";
?>
<!-- 홈 리뉴얼 테마(모바일, 캠페인 목록에도 로드) 2026 -->
<link rel="stylesheet" type="text/css" href="<?=$nfor[path]?>/skin/demo/css/mc_home_theme.css?v=<?=@filemtime($nfor[path]."/skin/demo/css/mc_home_theme.css")?>" />
<script src="<?=$nfor[path]?>/js/mc_home.js?v=<?=@filemtime($nfor[path]."/js/mc_home.js")?>" defer></script>



<?php
if($apply_goods){
	include_once $nfor[skin_path]."inc_campaign_list_banner.php";
}
?>


<div class="item_list_main_wrap">

	<?php if(count($admin[category_id_1])){ ?>
	<div class="sub_category_area  reco_area1">
		<ul>
			<li <? if(strlen($category_id)=="4"){ ?>class="on"<? } ?>><a href="<?=$PHP_SELF?>?category_id=<?=substr($category_id,0,4)?>">전체보기</a></li>
			<?php foreach($admin[category_id_1] as $key => $val){ ?>
			<li <? if($key==substr($category_id,0,8)){ ?>class="on"<? } ?>><a href="<?=$PHP_SELF?>?category_id=<?=$key?>"><?=$val?></a></li>
			<?php } ?>
		</ul>
	</div>
	<?php } ?>

	<?php if(count($admin[category_id_2])){ ?>
	<div class="item_list_zone">
		<div class="sub_menu_depth3 ">
			<ul>
				<?php foreach($admin[category_id_2] as $key => $val){ ?>
				<li><a href="<?=$PHP_SELF?>?category_id=<?=$key?>" <?php if($key==substr($category_id,0,12)){ ?>class="on"<?php } ?>><?=$val?> <span class="cnt txt_num point_color1"></span><b class="arrow_btn"></b></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<?php } ?>

	<div class="cate_wrap">

		<div class="left">
			<span class="title_sub"></span>
		</div>
		<div class="right" style="position:relative;">		
			<ul class="cate">
				<li>
					<a class="sort_items_sns last"><?=$nfor[cp_media][$cp_media]?$nfor[cp_media][$cp_media]:"전체"?><b></b></a>
					<div class="sort_div_sns">
						<?php foreach($nfor[cp_media] as $key => $val){ ?>
						<a href="campaign_list.php?category_id=<?=htmlspecialchars($category_id)?>&cp_media=<?=$key?>&orderby=<?=htmlspecialchars($orderby)?>" class="<?=$cp_media==$key?"on":""?>"><?=$val?></a>
						<?php } ?>
					</div>
				</li>
				<li>
					<a class="sort_items last"><?=$admin[orderby_text][$orderby]?><b></b></a>
					<div class="sort_div">
						<?php foreach($admin[orderby_text] as $key => $val){ ?>
						<a href="<?=$PHP_SELF?>?category_id=<?=htmlspecialchars($category_id)?>&cp_media=<?=$cp_media?>&orderby=<?=$key?>" class="<?=$orderby==$key?"on":""?>"><?=$val?></a>
						<?php } ?>
					</div>
				</li>
			</ul>
		</div>

	</div>

	<div style="clear:both;"></div>

	<div class="item_list_wrap" style="padding:15px;">
		<div class="item_box_list nfor_campaign_list_wrap">
		<?php include $nfor[skin_path]."inc_index_list_item.php"; ?>
		</div>
	</div>
	<?php if(!$scroll_load){ ?><div class="page_center"><?=$pagelist?></div><?php } ?>


</div>

<?php
include_once $nfor[skin_path]."inc_campaign_list.php";
include_once $nfor[skin_path]."tail.php";
?>