<script>
$(document).on("click",".sort_items",function(){
	if($(".sort_div").css("display") == "none"){		
		$(".sort_items").addClass("on");
		$(".sort_div").show();		
	} else{
		$(".sort_items").removeClass("on");
		$(".sort_div").hide();
	}
});

$(document).on("click", ".campain_cencle", function(){
	var rv_id = $(this).data("rv_id");
	if(confirm("신청을 취소하시겠습니까?")){
		$.ajax({
			type: "post",
			url: "review_order.php",
			data: {
				"mode":"delete",
				"rv_id":rv_id
			},
			cache: false,
			async: false,
			success: function(response){
				var json = $.parseJSON(response); 
				if(json["result"]=="ok"){				
					alert(json["msg"]);
					document.location.reload();
				} else{
					alert(json["msg"]);
				}
			}
		});
	}
});
</script>

<div class="loading_wait">
	<img src="<?=$nfor[skin_path]?>img/ajax-loader.gif">
	<span>상품목록을 불러오고 있습니다</span>
	<p>잠시만 기다려 주세요</p>
</div>

<script type="text/html" id="item_list_script">
<li>
	<div class="box">
			<div class="thumb">
					<a href="campaign.php?cp_id=<%=cp_id%>"><img src="<%=cp_img%>" class="it_img"><% if(cp_day=="모집마감"){ %><div class="soldout"><span class="tit ">모집마감</span></div><% } %></a>
				<a class="zzim campaign_zzim_btn <%=cp_zzim_is?"on":""%>" data-cp_id="<%=cp_id%>"></a>
			</div>
			<a href="campaign.php?cp_id=<%=cp_id%>">
			<div class="it_info">
				<div class="top_info">

					<% if(cp_media_blog=="1"){ %><span class="blog">네이버 블로그</span><% } %>
					<% if(cp_media_instagram=="1"){ %><span class="instagram">인스타그램</span><% } %>
					<% if(cp_media_youtube=="1"){ %><span class="youtube">유튜브</span><% } %>
					<% if(cp_media_shop=="1"){ %><span class="shop">네이버 쇼핑몰</span><% } %>
					<% if(cp_check1=="1"){ %><span class="gumepyung">구매평</span><% } %>
					<% if(cp_check2=="1"){ %><span class="payback">페이백</span><% } %>
					<% if($row[cp_ohouse]){ ?><span class="cp_ohouse">오늘의집<% } %>
					<% if($row[cp_coupang]){ ?><span class="cp_coupang">쿠팡</span><% } %>
					<% if($row[cp_media_receipt]){ ?><span class="cp_media_receipt">영수증</span><% } %>
					<?php if($row[cp_media_carrot]){ ?><span class="cp_media_carrot">당근</span><?php } ?>
				<div class="option2">
					<% if(cp_point != 0){ %><span class="txt_num point_color4">+ <%=cp_point%>P</span><% } %>
					<?php if($row[cp_type] == "배송형"){ ?>
						<span>배송형</span>
					<?php } else if($row[cp_type] == "방문형"){ ?>
						<span>방문형</span>
					<?php } else if($row[cp_type] == "배송형+리워드"){ ?>
						<span>배송형</span> <span>리워드</span>
					<?php } else if($row[cp_type] == "방문형+리워드"){ ?>
						<span>방문형</span> <span>리워드</span>
					<?php } ?>
					<?php if($row[cp_day] >= "1"){ ?>
						<span class="txt_num dday"><?=$row[cp_day]?>일 남음</span>
					<?php } else if($row[cp_day] == "0"){ ?>
						<span class="txt_num eday">오늘까지</span>
					<?php } ?>
				</div>


					<?php if(basename($PHP_SELF)=="review_asign_list.php" or basename($PHP_SELF)=="review_post_list.php" or basename($PHP_SELF)=="review_edit_list.php"){ ?>
					<a href="url_input_win.php?rv_id=<%=rv_id%>" class="url_input">리뷰<?=basename($PHP_SELF)=="review_asign_list.php"?"등록":"수정"?></a>
					<?php } ?>
					<?php if(basename($PHP_SELF)=="review_wait_list.php"){ ?>
					<a data-rv_id="<%=rv_id%>" class="campain_cencle">신청 취소</a>
					<?php } ?>
				</div>
				<span class="it_name"><%=cp_subject%></span>
				<span class="it_description"><%=cp_description%></span>
				<div class="option">
					<span><img src="<?=$nfor[skin_path]?>img/txt_ico.png"><b class="txt_num point_color4"><%=cp_order%></b>명 신청/<b class="txt_num point_color4"><%=cp_recruit%></b>명 모집중</span> 
					<span><img src="<?=$nfor[skin_path]?>img/pen_ico.png"><b class="txt_num point_color4"><%=cp_review%></b>개의 리뷰</span>		
				</div>
			</div>
		</a>
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
						, cp_check1: data.list[i].cp_check1
						, cp_check2: data.list[i].cp_check2
						, rv_id: data.list[i].rv_id
						, cp_subject: data.list[i].cp_subject
						, cp_description: data.list[i].cp_description
						, cp_order: data.list[i].cp_order
						, cp_recruit: data.list[i].cp_recruit
						, cp_review: data.list[i].cp_review
						, cp_point: data.list[i].cp_point
						, cp_day: data.list[i].cp_day
						, cp_type: data.list[i].cp_type});
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