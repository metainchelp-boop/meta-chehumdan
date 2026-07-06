<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.realtime_review_wrap{margin-bottom:50px}
.realtime_review_wrap .best_wrap{position: relative;; background-color:#fef4f4; padding-bottom:50px;}
.realtime_review_wrap .best_wrap .bg_1{  position: absolute;  top: 0px; right: 0px;  width: 477px; height: 323px; background-image:url(/skin/demo/img/pr_top_bg3.png);   background-repeat: no-repeat;  background-position: 0;}
.realtime_review_wrap .best_wrap .best_title{padding:50px 0px; font-size:26px; color:#000; letter-spacing:-1px; }
.s_txt{font-size:16px; display:block; margin-top:10px;}
.review_list{padding:15px;}
</style>

<div class="realtime_review_wrap">
	<div class="best_wrap">
	<div class="bg_1"></div>
		<div class="layout_inner">
			<div class="best_title ">
				<span><b class="point_color4">베스트</b> 선정리뷰</span>
				<span class="s_txt">  깔끔한 사진과 함께 정성스럽게 작성된  베스트 리뷰입니다.
			</div>
		<div class="review_list_wrap">
			<div class="review_box_list">
				<?php
				$return["list_backup"] = $return["list"];
				$return["list"] = array();
				$return["list"] = $return["best_review_list"];
				include $nfor[skin_path]."inc_review_list_item.php";
				?>
			</div>
		</div>
		</div>
	</div>

<?php
if($apply_good){
	include_once $nfor[skin_path]."inc_real_banner.php";
}
?>

	<div class="review_list">
	<div class="layout_inner">
		<div class="cate_wrap">
			<div class="left">
				<span class="title_sub">선정된 리뷰</span>	
			</div>
		</div>
		<div style="clear:both; height:10px;"></div>
		<div class="review_list_wrap">
			<div class="review_box_list realtime_review_list">
				<?php
				$return["list"] = array();
				$return["list"] = $return["list_backup"];
				include $nfor[skin_path]."inc_review_list_item.php";
				?>
			</div>
			<?php if(!$scroll_load){ ?><div class="page_center"><?=$pagelist?></div><?php } ?>
		</div>
	</div>
	</div>
</div>





<?php if($scroll_load){ ?>
<div class="loading_wait">
	<img src="<?=$nfor[skin_path]?>img/ajax-loader.gif">
	<span>상품목록을 불러오고 있습니다</span>
	<p>잠시만 기다려 주세요</p>
</div>

<script type="text/html" id="item_list_script">
<li>
<div class="box">
	<a href="<%=rv_url%>" class="thum" target="_blank">
		<img src="<%=rv_img%>" class="it_img">
	</a>
	<div class="review_info">
		<a href="campaign.php?cp_id=<%=cp_id%>" class="top_info">
			<img src="<%=cp_img%>" class="review_img">
			<div class="review_des">
				<span class="review_cop"><%=cp_subject%></span>
				<span class="review_name"><%=cp_description%></span>
			</div>
		</a>
		<div class="review_description"><%=rv_review%></div>		
		<div class="review_bottom">
			<span class="sns <%=rv_media%>"><%=rv_media_text%></span>	
			<span class="id"><%=rv_mb_nick%></span>
		</div>
	</div>
</div>
</li>
</script>

<script>
var is_last = 0;
var page = 1;
var category_id = <?=json_encode((string)$category_id, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT)?>;
var keyword = <?=json_encode((string)$keyword, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT)?>;
var cp_media = "<?=$cp_media?>";
var orderby = <?=json_encode((string)$orderby, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT)?>;


<?php if($scroll_load){ ?>
$(window).scroll(function() {
	if ($(window).scrollTop() == $(document).height() - $(window).height()) {
		if(is_last==0){
			++page;
			realtime_review_list_load();
		}
	}
});
<?php } ?>

function realtime_review_list_load(){
	$(".loading_wait").show();
	if(page==1){
		$(".realtime_review_list ul").html("");
	}
	$.ajax({
		type     : "get",
		url      : "<?=basename($PHP_SELF)?>",
		data     : "json=list&category_id="+category_id+"&keyword="+keyword+"&cp_media="+cp_media+"&orderby="+orderby+"&page="+page,
		dataType : "json",
		cache: false,
		success  : function(data) {
			if(data.count > 0){
				template = _.template($("#item_list_script").html());
				var template_html = "";
				for(var i=0; i<data.list.length; i++) {
					template_html +=  template({rv_url: data.list[i].rv_url
						, rv_img: data.list[i].rv_img
						, cp_id: data.list[i].cp_id
						, cp_img: data.list[i].cp_img
						, cp_media_instagram: data.list[i].cp_media_instagram
						, cp_media_youtube: data.list[i].cp_media_youtube
						, cp_media_shop: data.list[i].cp_media_shop
						, rv_id: data.list[i].rv_id
						, cp_subject: data.list[i].cp_subject
						, cp_description: data.list[i].cp_description
						, rv_review: data.list[i].rv_review
						, rv_mb_nick: data.list[i].rv_mb_nick
						, rv_media: data.list[i].rv_media
						, rv_media_text: data.list[i].rv_media_text});
				}
				$(".realtime_review_list ul").append(template_html);
			}
			$(".loading_wait").hide();		
			if(data.last_page == 1){
				is_last++;
			}			
		},
		error: function(e){
			console.log(e);
			console.log("Ajax failed");
		}
	});
}
</script>
<?php } ?>

<?php
include_once $nfor[skin_path]."tail.php";
?>