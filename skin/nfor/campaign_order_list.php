<style>
.cpa{width:100%; padding:0px;  -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box}
.cpa ul {width:100%; font-size:16px;}
.cpa ul li { border-bottom: 1px dashed #eee; padding: 20px 20px 20px 90px;position: relative;min-height: 50px;}
.cpa ul li .avatar { width: 60px; height: 60px; border-radius: 100rem; position: absolute; left: 15px; top: 15px;  background: url(/skin/demo/img/pro.png) no-repeat; }
.cpa ul li strong { font-size: 13px; color: #555;}
.cpa ul li p {margin: 5px 0 0 0; font-size: 13px; color: #777;}
.review_cancel_btn{position:absolute; right:15px; top:15px;border:solid 1px #ff0000; color:#ff0000; display:inline-block; font-size:10px; padding:3px 5px; letter-spacing:-1px;}
.campaign_order_list_more{display:block; border:solid 1px #dcdcdc;  padding:10px; text-align:center; margin:10px;}
</style>


<div class="cpa">
	<ul class="campaign_order_list">
		<?php
		for($i=0; $i<count($return["order_list"]); $i++){
			$order = $return["order_list"][$i];
		?>
		<li>
            <div class="avatar" <?php if($order[rv_mb_photo]){ ?>style="background:url(<?=$order[rv_mb_photo]?>)"<?php } ?>></div>
            <strong><?=$order[rv_mb_nick]?></strong>
			<?php if($order[rv_access]){ ?><a data-rv_id="<?=$order[rv_id]?>" class="review_cancel_btn">신청취소</a><?php } ?>
            <p><?=$order[rv_msg]?></p>
		</li>
		<?php } ?>
	</ul>
</div>


<a class="campaign_order_list_more">더보기</a>


<div class="loading_wait">
	<img src="<?=$nfor[skin_path]?>img/ajax-loader.gif">
	<span>목록을 불러오고 있습니다</span>
	<p>잠시만 기다려 주세요</p>
</div>


<script type="text/html" id="campaign_order_list_script">
<li>
	<div class="avatar" <% if(rv_mb_photo){ %>style="background:url(<%=rv_mb_photo%>)"<% } %>></div>
	<strong><%=rv_mb_nick%></strong>
	<% if(rv_access=="1"){ %><a data-rv_id="<%=rv_id%>" class="review_cancel_btn">신청취소</a><% } %>
	<p><%=rv_msg%></p>
</li>
</script>


<script>
var is_last_order = 0;
var page_order = 1;

$(document).on("click", ".campaign_order_list_more", function(){
	if(is_last_order==0){
		++page_order;
		campaign_order_list_load();
	}
});

function campaign_order_list_load(){
	$(".loading_wait").show();
	if(page_order==1){
		$(".campaign_order_list").html("");
	}
	$.ajax({
		type     : "get",
		url      : "campaign_order_list.php",
		data     : "json=list&page="+page_order+"&cp_id="+cp_id,
		dataType : "json",
		cache: false,
		success  : function(data) {
			if(data.count > 0){
				template = _.template($("#campaign_order_list_script").html());
				var template_html = "";
				for(var i=0; i<data.order_list.length; i++) {
					template_html +=  template({rv_id: data.order_list[i].rv_id
						, rv_mb_nick: data.order_list[i].rv_mb_nick
						, rv_mb_photo: data.order_list[i].rv_mb_photo					
						, rv_access: data.order_list[i].rv_access
						, rv_msg: data.order_list[i].rv_msg});
				}
				$(".campaign_order_list").append(template_html);
			}

			$(".loading_wait").hide();		
			
			if(data.last_page == 1){
				is_last_order++;
			}
			
		},
		error: function(e){
			console.log(e);
			console.log("Ajax failed");
		}
	});
}

$(document).on("click", ".review_cancel_btn", function(){
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