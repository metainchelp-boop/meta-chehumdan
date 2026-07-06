<?php
include_once $nfor[skin_path]."head.php";
?>
<!-- 홈 리뉴얼 테마(모바일, 홈에서만) 2026 -->
<link rel="stylesheet" type="text/css" href="<?=$nfor[path]?>/skin/demo/css/mc_home_theme.css?v=<?=@filemtime($nfor[path]."/skin/demo/css/mc_home_theme.css")?>" />
<script src="<?=$nfor[path]?>/js/mc_home.js?v=<?=@filemtime($nfor[path]."/js/mc_home.js")?>" defer></script>


<?php
if($apply_goods){
	include_once $nfor[skin_path]."inc_banner1.php"; // 1단
} else{
	include_once $nfor[skin_path]."inc_banner_temp.php"; // 1단
}
?>

<style>
.icon_box { width:100%;height:auto;overflow:hidden;margin:0px auto;display:flex;padding:5px 0px 0px 0px;flex-wrap: wrap;background:#fff;margin-bottom:3px; }
.icon { width:20%;height:auto;overflow:hidden;cursor:pointer;text-align:center;font-size:12px;font-family: 'notokr-medium';font-weight:500;color:#333;padding-bottom:15px; }
.icon:hover { color: #18a8f1; }
.icon img { max-width:50px;margin-bottom:5px; }
</style>
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
<style>
.index_wrap{ width:100%; background-color:#FFF; -moz-box-shadow: box-shadow: 0px 3px 20px  #888888; /* Firefox 3.6 and earlier */ box-shadow: 0px 3px 5px #dfdfdf; margin-bottom:20px; border-top:solid 1px #efefef;}
.index_wrap .reco_area1{padding:20px 15px 0px;}
.index_wrap .reco_area1 .title{display:block; letter-spacing:-1px;  }
.index_wrap .reco_area1 .title span + span{font-size:1.2em; font-weight:bold;}
.index_wrap .reco_area1 .title span{display:block; font-size:.9em;}
.index_wrap .reco_area1 .title2{margin-bottom:0px; letter-spacing:-1px;}
.index_wrap .reco_area1 .title2 span{font-size:1.2em; font-weight:bold;}
</style>
 <div class="index_wrap">
	<div class="reco_area1">
		<div class="title">
			<span><?=$member[mb_nick]?$member[mb_nick]:$member[$member_config[cf_mb_id_type]]?> </span> 
			<span><b class="point-color1">메타 알고리즘</b> 추천  캠페인</span>
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

<?php // 메인배너
if($apply_goods){
	include_once $nfor[skin_path]."inc_banner2.php";
}
?>


<div class="index_wrap">
	<div class="reco_area1">
		<div class="title2">
			<span><b class="point-color1">오늘의</b> 캠페인</span>
		</div>
		<div class="item_list_wrap">
			<div class="item_box_list">
					<?php
					$return["list"] = array();
					$return["list"] = $return["new_campaign_list"];
					include $nfor[skin_path]."inc_index_list_item.php";
					?>
			</div>	
		</div>
	</div>
</div>



	<?php
	if($apply_goods){
		include_once $nfor[skin_path]."inc_banner3.php"; // 1단
	}
	?>


<?php if($config[cf_realtime_review_use]=="1"){ ?>
<div class="index_wrap">
	<div class="reco_area1">
		<div class="title2">
			<span>체험단 생생<b class="point-color1">후기</b></span>
		</div>
		<div class="review_list_wrap">
			<div class="review_box_list">
				<?php
				$return["list"] = array();
				$return["list"] = $return["realtime_review_list"];
				include $nfor[skin_path]."inc_review_list_item.php";
				?>
			</div>
		</div>
	</div>
</div>
<?php } ?>


	<?php
	if($apply_goods){
		include_once $nfor[skin_path]."inc_banner4.php"; // 1단
	}
	?>



<div class="index_wrap">
	<div class="reco_area1">
		<div class="title2">
			<span>기회는 지금 뿐 <b class="point-color1">체험은 타이밍 !</b></span>
		</div>
		<div class="item_list_wrap">
			<div class="item_box_list">
					<?php
					$return["list"] = array();
					$return["list"] = $return["end_campaign_list"];
					include $nfor[skin_path]."inc_index_list_item.php";
					?>
			</div>	
		</div>
	</div>
</div>

	<?php
	if($apply_goods){
		include_once $nfor[skin_path]."inc_banner5.php"; // 2단
	}
	?>

<?php
include_once $nfor[skin_path]."tail.php";
?>