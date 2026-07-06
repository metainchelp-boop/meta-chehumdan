<style>
.cpa{width:100%; padding:20px;  -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box}
.cpa ul {width:100%; font-size:16px;}
.cpa ul li { border-bottom: 1px dashed #eee; padding: 20px 20px 20px 90px;position: relative;min-height: 50px;}
.cpa ul li .avatar { width: 60px; height: 60px; border-radius: 100rem; position: absolute; left: 15px; top: 15px;  background: url(<?=$nfor[skin_path]?>img/pro.png) no-repeat; }
.cpa ul li strong { font-size: 16px; color: #555; font-weight:normal;}
.cpa ul li p {margin: 5px 0 0 0; font-size: 14px; color: #777;}

.campaign_review_list_more{display:block; border:solid 1px #dcdcdc;  padding:10px; text-align:center; margin:10px;}
</style>


<div class="cpa">
	<ul class="campaign_review_list">
		<?php
		for($i=0; $i<count($return["review_list"]); $i++){
			$review = $return["review_list"][$i];
		?>
		<li>
            <div class="avatar" <?php if($review[rv_mb_photo]){ ?>style="background:url(<?=$review[rv_mb_photo]?>)"<?php } ?>></div>
            <strong><?=$review[rv_mb_nick]?></strong>
            <p><a href="<?=$review[rv_url]?>" target="_blank"><?=$review[rv_review]?></a></p>
		</li>
		<?php } ?>
	</ul>
</div>


<a class="campaign_review_list_more"> + 더보기</a>


<div class="loading_wait">
	<img src="<?=$nfor[skin_path]?>img/ajax-loader.gif">
	<span>목록을 불러오고 있습니다</span>
	<p>잠시만 기다려 주세요</p>
</div>


<script type="text/html" id="campaign_review_list_script">
<li>
	<div class="avatar" <% if(rv_mb_photo){ %>style="background:url(<%=rv_mb_photo%>)"<% } %>></div>
	<strong><%=rv_mb_nick%></strong>
	<p><a href="<%=rv_url%>" target="_blank"><%=rv_review%></a></p>
</li>
</script>


<script>
var is_last_review = 0;
var page_review = 1;

$(document).on("click", ".campaign_review_list_more", function(){
	if(is_last_review==0){
		++page_review;
		campaign_review_list_load();
	}
});

function campaign_review_list_load(){
	$(".loading_wait").show();
	if(page_review==1){
		$(".campaign_review_list").html("");
	}
	$.ajax({
		type     : "get",
		url      : "campaign_review_list.php",
		data     : "json=list&page="+page_review+"&cp_id="+cp_id,
		dataType : "json",
		cache: false,
		success  : function(data) {
			if(data.count > 0){
				template = _.template($("#campaign_review_list_script").html());
				var template_html = "";
				for(var i=0; i<data.review_list.length; i++) {
					template_html +=  template({rv_id: data.review_list[i].rv_id
						, rv_mb_nick: data.review_list[i].rv_mb_nick
						, rv_mb_photo: data.review_list[i].rv_mb_photo					
						, rv_url: data.review_list[i].rv_url
						, rv_review: data.review_list[i].rv_review});
				}
				$(".campaign_review_list").append(template_html);
			}

			$(".loading_wait").hide();		
			
			if(data.last_page == 1){
				is_last_review++;
			}
			
		},
		error: function(e){
			console.log(e);
			console.log("Ajax failed");
		}
	});
}

</script>