<?php
include_once $nfor[skin_path]."head.php";
?>
<!-- 홈 리뉴얼 테마 (목록 페이지 로드) 2026 -->
<link rel="stylesheet" type="text/css" href="<?=$nfor[path]?>/skin/demo/css/mc_home_theme.css?v=<?=@filemtime($nfor[path]."/skin/demo/css/mc_home_theme.css")?>" />
<script src="<?=$nfor[path]?>/js/mc_home.js?v=<?=@filemtime($nfor[path]."/js/mc_home.js")?>" defer></script>

<style>

.search_wrap{background-color:#ffffff; padding:0px;}
.search_wrap .title_ser{font-size:14px; padding:15px; background-color:#fafafa; border-bottom:solid 1px #efefef; border-top:solid 1px #efefef; font-weight:600;} 
.search_wrap .recent_keyword  li { position: relative;  }
.search_wrap .recent_keyword  li  a { position: relative;  border-bottom: 1px solid #e5e5e5; background-color:#FFF;font-size: 14px; display:block; height: 20px; padding: 14px 85px 14px 15px; line-height: 20px; color: #333;  overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;}
.search_wrap .recent_date {position: absolute; right: 30px; color: #9b9b9b; font-size: 11px; font-weight: normal;font-style: normal;}
.search_wrap .recent_history_no{padding: 0 15px; text-align: center; background: #fff url(<?=$nfor[skin_path]?>img/bg_no_result03.png) no-repeat 50% 40%;  background-size: 64px auto; font-size: 15px; color: #999;}
.search_wrap .recent_history_no p{min-height: 240px; padding: 250px 0px 0px;}

.recent_delete_ea_btn {display: block;width: 40px;height: 40px;position: absolute; right:0px; top:15px; margin-top: -10px;text-decoration: none; text-align: center;color: #a3a3a3; text-indent: -9999px; font-weight: bold; background-image: url('<?=$nfor[skin_path]?>img/btn-delete@2x.png');  background-size: 8px 8px; background-repeat: no-repeat;  background-position: 50%;}

.recent_menu {position: relative; display: block; overflow: hidden;  padding: 0px 11px;margin: 0;background-color: #f9f9fa; border-bottom: 1px solid #ccc; border-top: 1px solid #ccc;}
.recent_menu a{font-size: 13px;padding: 12px 5px;color: #777; text-decoration: none;}
.recent_menu .recent_save_onoff {font-size: 13px;padding: 12px 5px;color: #777; text-decoration: none;}
.recent_menu .bar{display: inline-block; padding: 12px 5px;color: #979797;font-size: 12px;}
.recent_menu .recent_close_btn{ position: absolute; right: 10px; font-size: 13px;padding: 12px 5px;color: #777; text-decoration: none;}

#keyword_keyup li{ padding:12px 0px; border-top:solid 1px #f4f4f4; background-color:#fff; }
#keyword_keyup a{ display:block; font-size:15px; background-color:#fff; margin:0px 20px; text-overflow:ellipsis; -webkit-text-overflow:ellipsis; white-space:nowrap; overflow:hidden; }

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
		<div class="title2">
			<span>입력하신 <b class="point-color1">"<?=htmlspecialchars($keyword)?>"</b>의 검색결과</span>
		</div>

		<div class="item_list_wrap">
			<div class="item_box_list nfor_campaign_list_wrap">
			<?php include_once $nfor[skin_path]."inc_index_list_item.php"; ?>
			</div>
		</div>
		<?php if(!$scroll_load){ ?><div class="page_center"><?=$pagelist?></div><?php } ?>
	</div>
</div>

<?php
include_once $nfor[skin_path]."inc_campaign_list.php";
include_once $nfor[skin_path]."tail.php";
?>