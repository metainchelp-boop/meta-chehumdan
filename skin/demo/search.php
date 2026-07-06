<?php
include_once $nfor[skin_path]."head.php";
?>
<!-- 홈 리뉴얼 테마 (목록 페이지 로드) 2026 -->
<link rel="stylesheet" type="text/css" href="<?=$nfor[path]?>/skin/demo/css/mc_home_theme.css?v=<?=@filemtime($nfor[path]."/skin/demo/css/mc_home_theme.css")?>" />
<script src="<?=$nfor[path]?>/js/mc_home.js?v=<?=@filemtime($nfor[path]."/js/mc_home.js")?>" defer></script>

<style>
.search_list .tit{padding-top: 61px;padding-bottom: 32px; border-bottom: 0; font-size: 34px; text-align:center; color:#000; font-weight:300;}
</style>

<div class="search_list">
	<div class="tit ">'<span class="point_color4"><?=htmlspecialchars($keyword)?></span>'에 대한 <span class="point_color4"><?=number_format($total_count)?></span>건의 검색결과 입니다.</div>
	<div class="item_list_wrap">
		<div class="item_box_list nfor_campaign_list_wrap">
			<?php include_once $nfor[skin_path]."inc_index_list_item.php"; ?>
		</div>
	</div>
	<?php if(!$scroll_load){ ?><div class="page_center"><?=$pagelist?></div><?php } ?>
</div>

<?php
include_once $nfor[skin_path]."inc_campaign_list.php";
include_once $nfor[skin_path]."tail.php";
?>