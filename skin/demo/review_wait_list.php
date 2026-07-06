<?php	// 신청한 캠페인
include_once $nfor[skin_path]."mypage_head.php";
?>
<!-- 홈 리뉴얼 테마 (목록 페이지 로드) 2026 -->
<link rel="stylesheet" type="text/css" href="<?=$nfor[path]?>/skin/demo/css/mc_home_theme.css?v=<?=@filemtime($nfor[path]."/skin/demo/css/mc_home_theme.css")?>" />
<script src="<?=$nfor[path]?>/js/mc_home.js?v=<?=@filemtime($nfor[path]."/js/mc_home.js")?>" defer></script>

<?php
include_once $nfor[skin_path]."inc_cam.php";
?>

<div class="item_list_wrap">

	<div class="item_box_s nfor_campaign_list_wrap">
	<?php
	include $nfor[skin_path]."inc_index_list_item.php";
	?>
	</div>	

</div>

<?php if(!$scroll_load){ ?><div class="page_center"><?=$pagelist?></div><?php } ?>

<?php
include_once $nfor[skin_path]."inc_campaign_list.php";
include_once $nfor[skin_path]."mypage_tail.php";
?>