<?php
include_once $nfor[skin_path]."head.php";
?>
<!-- 홈 리뉴얼 테마 (홈에서만 로드) 2026 -->
<link rel="stylesheet" type="text/css" href="<?=$nfor[path]?>/skin/demo/css/mc_home_theme.css?v=<?=@filemtime($nfor[path]."/skin/demo/css/mc_home_theme.css")?>" />
<script src="<?=$nfor[path]?>/js/mc_home.js?v=<?=@filemtime($nfor[path]."/js/mc_home.js")?>" defer></script>

	<?php
	if($apply_good){
		include_once $nfor[skin_path]."inc_banner1.php"; // 1단
	}
	?>
	<style>
	.icon_box { max-width:1444px;width:100%;height:auto;overflow:hidden;margin:0px auto;display:flex;padding:70px 0px 40px 0px; }
	.icon { width:11.11111111111111%;height:auto;overflow:hidden;cursor:pointer;text-align:center;font-size:18px;font-family: 'notokr-medium';font-weight:500;color:#333; }
	.icon:hover { color: #18a8f1; }
	.icon img { max-width:80px;margin-bottom:10px; }
	</style>
	<div class="layout_inner">
<div class="icon_box">
		<div class="icon" onclick="location.href='/banner_list.php';"><img src="/img/001.png"><br>기획전</div>
		<div class="icon" onclick="location.href='/attendance.php';"><img src="/img/010.png"><br>출석체크</div>
		<div class="icon" onclick="location.href='/campaign_list.php?category_id=001A';"><img src="/img/002.png"><br>방문</div>
		<div class="icon" onclick="location.href='/campaign_list.php?category_id=002A';"><img src="/img/011.png"><br>제품</div>
		<div class="icon" onclick="location.href='/campaign_list.php?category_id=002A034A';"><img src="/img/004.png"><br>뷰티</div>
		<div class="icon" onclick="location.href='/campaign_list.php?category_id=002A031A';"><img src="/img/006.png"><br>생활</div>
		<div class="icon" onclick="location.href='/campaign_list.php?category_id=002A035A';"><img src="/img/005.png"><br>식품</div>
		<div class="icon" onclick="location.href='/campaign_list.php?category_id=002A048A';"><img src="/img/007.png"><br>반려동물</div>
		<div class="icon" onclick="location.href='/campaign_list.php?category_id=004A';"><img src="/img/008.png"><br>기자단</div>
		<div class="icon"  onclick="window.open('http://pf.kakao.com/_xglPxob');"><img src="/img/009.png"><br>광고문의</div>

	</div>
</div>
	<?php // 메타픽캠페인(추천캠페인)
	include_once $nfor[skin_path]."inc_index_list1.php"; 
	?>

	<?php // 메인배너
	include_once $nfor[skin_path]."inc_banner2.php";
	?>

	<?php // 뉴메타캠페인(새로운캠페인)
	include_once $nfor[skin_path]."inc_index_list2.php"; 
	?>

	<?php
	if($apply_good){
		include_once $nfor[skin_path]."inc_banner3.php"; // 1단
	}
	?>


	<?php // 실시간리뷰 
	if($config[cf_realtime_review_use]=="1"){
		include_once $nfor[skin_path]."inc_index_reviewlist.php"; 
	}
	?>

	<?php
	if($apply_good){
		include_once $nfor[skin_path]."inc_banner4.php"; // 1단
	}
	?>

	<?php // 마감임박 캠페인
	include$nfor[skin_path]."inc_index_list3.php"; 
	?>

	<?php
	if($apply_good){
		include_once $nfor[skin_path]."inc_banner5.php"; // 2단
	}
	?>

<?php
include_once $nfor[skin_path]."tail.php";
?>