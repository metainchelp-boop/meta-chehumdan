<?php
include_once $nfor[skin_path]."head.php";
?>
<style>
.review_list_wrap{padding:15px;}
</style>
<div class="review_list_wrap">
	<div class="review_box_list nfor_campaign_list_wrap">
		<?php include $nfor[skin_path]."inc_review_list_item.php"; ?>
	</div>
</div>

<?php if(!$scroll_load){ ?><div class="page_center"><?=$pagelist?></div><?php } ?>

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
$(window).scroll(function(){
    var scrolltop = parseInt ( $(window).scrollTop() );
    if( scrolltop >= $(document).height() - $(window).height() - 500 ){
        if(is_last==0){
			++page;
			item_list_load();
		}
    }
});
<?php } ?>

function item_list_load(){
	$(".loading_wait").show();
	if(page==1){
		$(".nfor_campaign_list_wrap ul").html("");
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
					template_html +=  template({cp_id: data.list[i].cp_id
						, cp_img: data.list[i].cp_img
						, cp_zzim_is: data.list[i].cp_zzim_is
						, cp_media_blog: data.list[i].cp_media_blog
						, cp_media_instagram: data.list[i].cp_media_instagram
						, cp_media_youtube: data.list[i].cp_media_youtube
						, cp_media_shop: data.list[i].cp_media_shop
						, rv_id: data.list[i].rv_id
						, cp_subject: data.list[i].cp_subject
						, cp_description: data.list[i].cp_description
						, cp_order: data.list[i].cp_order
						, cp_recruit: data.list[i].cp_recruit
						, cp_review: data.list[i].cp_review
						, cp_point: data.list[i].cp_point
						, cp_day: data.list[i].cp_day
						, cp_type: data.list[i].cp_type
						, rv_url: data.list[i].rv_url
						, rv_img: data.list[i].rv_img
						, rv_review: data.list[i].rv_review
						, rv_media: data.list[i].rv_media
						, rv_media_text: data.list[i].rv_media_text
						, rv_mb_nick: data.list[i].rv_mb_nick});
				}
				$(".nfor_campaign_list_wrap ul").append(template_html);
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

<?php
include_once $nfor[skin_path]."tail.php";
?>