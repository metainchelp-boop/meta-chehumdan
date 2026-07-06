<?php
include_once $nfor[skin_path]."head.php";
?>
<!-- 홈 리뉴얼 테마 (캠페인 목록에도 로드) 2026 -->
<link rel="stylesheet" type="text/css" href="<?=$nfor[path]?>/skin/demo/css/mc_home_theme.css?v=<?=@filemtime($nfor[path]."/skin/demo/css/mc_home_theme.css")?>" />
<script src="<?=$nfor[path]?>/js/mc_home.js?v=<?=@filemtime($nfor[path]."/js/mc_home.js")?>" defer></script>


<?php
if($apply_good){
	include_once $nfor[skin_path]."inc_campaign_list_banner.php";
}
?>




<div style="clear:both;"></div>

<?php if(count($admin[category_id_1])){ ?>
<div class="sub_category_area">
	<ul>
		<li <?php if(strlen($category_id)=="4"){ ?>class="on"<?php } ?>><a href="<?=$PHP_SELF?>?category_id=<?=$category_id_1?>">전체보기</a></li>
		<?php foreach($admin[category_id_1] as $key => $val){ ?>
		<li <?php if($key==substr($category_id,0,8)){ ?>class="on"<?php } ?>><a href="<?=$PHP_SELF?>?category_id=<?=$key?>"><?=$val?><span class="txt_num"></span></a></li>
		<?php } ?>
	</ul>
</div>
<?php } ?>

<?php if(count($admin[category_id_2])){ ?>
<div class="sub_category_box">
	<ul>
		<?php foreach($admin[category_id_2] as $key => $val){ ?>
		<li><a href="<?=$PHP_SELF?>?category_id=<?=$key?>" <?php if($key==$category_id){ ?>class="on"<? } ?>><?=$val?><span class="num"></span></a></li>
		<?php } ?>
	</ul>
</div>
<?php } ?>


<div class="cate_wrap">

	<div class="left">

		<span class="title_sub"><?=$nfor[title]?></span>

		<span class="sub">
		<?php if(substr($category_id,0,3)=="001"){ ?><img src="<?=$nfor[skin_path]?>img/dot.png"><?php } ?>
		<?php if(strlen($category_id) > 4){ ?>
		<?php for($i=0; $i<count($navi[category_id]); $i++){ ?>
		<?php if($i){ ?><img src="<?=$nfor[skin_path]?>img/arrow.png"><?php } ?>
		<a href="<?=$PHP_SELF?>?category_id=<?=$navi[category_id][$i]?>"><?=$navi[cg_category][$i]?></a> 
		<?php } ?>	
		<?php } ?>		
		</span>

	</div>

	<div class="right" style="position:relative;">

		<ul class="cate">
			<?php foreach($nfor[cp_media] as $key => $val){ ?>
			<li><a href="campaign_list.php?category_id=<?=htmlspecialchars($category_id)?>&cp_media=<?=$key?>&orderby=<?=htmlspecialchars($orderby)?>" class="<?=$cp_media==$key?"on":""?>"><?=$val?></a></li>
			<?php } ?>
			<li><a class="sort_items last"><?=$admin[orderby_text][$orderby]?><b></b></a></li>
		</ul>
	
		<div class="sort_div">
			<?php foreach($admin[orderby_text] as $key => $val){ ?>
			<a href="<?=$PHP_SELF?>?category_id=<?=htmlspecialchars($category_id)?>&cp_media=<?=$cp_media?>&orderby=<?=$key?>" class="<?=$orderby==$key?"on":""?>"><?=$val?></a>
			<?php } ?>
		</div>

	</div>

</div>

<div style="clear:both; height:10px;"></div>

<div class="container">

	<div class="item_list_wrap">
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