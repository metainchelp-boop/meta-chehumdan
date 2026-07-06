<style>
/* 더보기버튼 */
.star_list_more { display:block; letter-spacing:-1px; color:#ff284b; height:40px; line-height:40px; font-size:14px; text-align:center; border:solid 1px #ff284b; margin:16px;  }
.star_list_more b { display:inline-block; width:11px; height:7px; background:url('<?=$nfor[skin_path]?>img/layout.png') no-repeat ;  background-position:-200px -250px; background-size:320px auto; } 

/* 상품평리스트 */
.star_list .st_li { padding:15px 15px; border-top:solid 1px #e5e5e5; }

.star_list .star_memo { color:#444; font-size:13px; letter-spacing:-1px; overflow:hidden; text-overflow:ellipsis; -webkit-line-clamp:4; display:-webkit-box; -webkit-box-orient:vertical;  }
.star_list .star_memo.on { color:#444; font-size:13px; letter-spacing:-1px; overflow:hidden; text-overflow:ellipsis; -webkit-line-clamp:100; display:-webkit-box; -webkit-box-orient:vertical;  }


.star_list .st_li .star_list_name { position:relative; float:left; font-size:13px; color:#999; letter-spacing:-1px; }

.staricon { display:inline-block; position:relative; width:80px; height:15px; background:url('<?=$nfor[skin_path]?>img/star.png')no-repeat 0 -15px; background-size:87px auto; vertical-align:middle; }
.staricon em{ position:absolute; left:0px; top:0px; height:15px; background:url('<?=$nfor[skin_path]?>img/star.png')no-repeat 0 0; background-size:87px auto; }

.star_list .star_list_date { float:right; font-size:11px; color:#999; letter-spacing:-1px; }  
.star_list .star_list_btn { width:100%; text-align:right; margin-top:10px; }
.star_list .star_list_btn button{padding: 0px;margin: 0px; width: 40px; height: 25px;line-height: 25px;  text-align: center; font-size: 12px; text-decoration: none;}
.star_list .star_list_btn .update{border: 1px solid #9ea5ae;background: #fff;color: #9ea5ae;}
.star_list .star_list_btn .delete{border: solid 1px #e83862; background: #fff; color: #e83862;}


.staricon{ display:inline-block; position:relative; width:100px; height:18px; background:url('<?=$nfor[skin_path]?>img/star.png')no-repeat 0 -17px; background-size:87px auto; vertical-align:middle; }
.staricon em{ position:absolute; left:0px; top:0px; height:18px; background:url('<?=$nfor[skin_path]?>img/star.png')no-repeat 0 0; background-size:87px auto; }

.wrap_star_info { width:100%; padding:20px 0px;  overflow:hidden;  }
.wrap_star_info .txt{font-size:0.95em; text-align:center; margin-bottom:10px; letter-spacing: -1px;}
.wrap_star_info .txt b{font-size:0.7em; color: #959da6;}
.wrap_star_info .sub_txt{ font-size: .7rem; text-align:center; margin-bottom:20px;color: #6c7580; letter-spacing: -1px;}

.wrap_bigpoint { width:100%; text-align:center; margin-bottom:10px;}
.bigstaricon{ display:inline-block; position:relative; width:134px; height:24px; background:url('<?=$nfor[skin_path]?>img/star.png')no-repeat 0 -23px; background-size:134px auto; vertical-align:middle; font-weight:normal; }
.bigstaricon em{ position:absolute; left:0px; top:3px; height:24px; background:url('<?=$nfor[skin_path]?>img/star.png')no-repeat 0 0; background-size:134px auto; }
.bigpoint { display:inline-block; font-size:32px; letter-spacing:-1px; color:#222; vertical-align:middle; }
.bigpointmax { display:inline-block; font-size:22px; color:#999; letter-spacing:-0.5px; vertical-align:middle; }

.name_date_wrap { margin-bottom:10px; }
.name_date_wrap:after { clear:both; display:block; content:''; }

.wrap_star_info .graph_zone{width:90%; margin:0 auto; padding-bottom:3px;}
.wrap_star_info .graph_zone .g_txt{font-size:0.6em; color:#4a4a4a; display:inline-block; width:20%; text-align:left; letter-spacing:-1px;}
.wrap_star_info .graph_zone .gr{display:inline-block; width:60%;}
.wrap_star_info .graph_zone .gr .graph{ position:relative; display:inline-block; width: 100%;height: 6.4px; border-radius: 3.2px; background-color: #e5e5e5;}
.wrap_star_info .graph_zone .gr .graph .graph_rate{ position:absolute; display:inline-block; left:0px; top:0px; height: 6.4px;border-radius: 3.2px; }
.wrap_star_info .graph_zone .g_point{display:inline-block; font-size:12px; width:10%;}
.wrap_star_info .graph_zone .g_color1{background-color:#ff284b; }
.wrap_star_info .graph_zone .g_color2{background-color:#a603e6;}
 .wrap_star_info .graph_zone .g_color3{background-color:#9b9b9b;}
 .best {
    border: solid 1px #ff0000;
    color: #ff0000;
    display: inline-block;
    padding: 3px 5px;
    margin-right: 10px;
    font-size: 10px;
}
</style>

<div class="wrap_star_info">

	<div style="float:left; width:50%;">
		<div class="wrap_bigpoint">
			<b class="bigpoint"><?=$item[it_star_avg]?></b>
			<span class="bigpointmax">/5</span><br>
			<b class="staricon"><em style="width:<?=$item[it_star_avg]*20?>%;"></em></b>
		</div>
	</div>
	<div style="float:left; width:50%;">
			<div class="graph_zone">
				<span class="g_txt">만족</span>
				<div class="gr">
				<b class="graph"><em class="graph_rate g_color1" style="width:<?=$item[it_star_per1]?>%;"></em></b>
				</div>
				<span class="g_point" style="color:#ff284b"><?=$item[it_star_count1]?></span>
			</div>
			<div class="graph_zone">
				<span class="g_txt">보통</span>
				<div class="gr">
				<b class="graph"><em class="graph_rate g_color2" style="width:<?=$item[it_star_per2]?>%;"></em></b>
				</div>
				<span class="g_point" style="color:#a603e6;"><?=$item[it_star_count2]?></span>
			</div>
			<div class="graph_zone">
				<span class="g_txt">불만족</span>
				<div class="gr">
				<b class="graph"><em class="graph_rate g_color3" style="width:<?=$item[it_star_per3]?>%;"></em></b>
				</div>
				<span class="g_point" style="color:#9b9b9b;"><?=$item[it_star_count3]?></span>
			</div>
	</div>
</div>


<ul class="star_list">
<?php
for($i=0; $i<count($return["star_list"]); $i++){
	$star = $return["star_list"][$i];
?>
<li class="st_li" id="st_li_<?=$star[st_id]?>">
	<div class="name_date_wrap">
		<span class="star_list_name"><? if($star[st_best]){ ?><span class="best">베스트후기</span><? } ?><b class="staricon"><em style="width:<?=$star[st_star_per]?>%;"></em></b><?=$star[st_mb_id]?></span>
		<span class="star_list_date"><?=$star[st_insert_datetime]?></span>		
	</div>
	<div class="star_memo"><?=$star[st_memo]?></div>	
	<div class="star_image">
	<?
	for($p=1; $p<=4; $p++){
		if($star["st_img".$p]){
	?>
	<img src="<?="$nfor[path]/data/star/".$star["st_img".$p]?>" data-number="<?=$p-1?>" data-st_id="<?=$star[st_id]?>" width="86" height="58" class="st_img">
	<? 
		}
	}
	?>
	</div>
	<? if($star[st_access]=="1"){ ?>
	<div class="star_list_btn">
		<button type="button" class="update" data-st_id="<?=$star[st_id]?>">수정</button>
		<button type="button" class="delete" data-st_id="<?=$star[st_id]?>">삭제</button>
	</div>
	<? } ?>
</li>
<?php
	for($k=0; $k<count($star["reply"]); $k++){
		$star_reply = $star["reply"][$k];
?>
<li class="st_li" id="st_li_<?=$star_reply[st_id]?>">
	<div>
		<span class="star_list_name"><i></i><b>답변</b> &nbsp;<?=$star_reply[st_mb_id]?></span>
		<span class="star_list_date"><?=$star_reply[st_insert_datetime]?></span>
		<div style="clear:both;"></div>
	</div>
	<div class="star_comment_wrap" id="update_star_<?=$star_reply[st_id]?>"></div>
	<div class="star_memo" id="star_memo_<?=$star_reply[st_id]?>"><?=$star_reply[st_memo]?></div>
	<? if($star_reply[st_access]=="1"){ ?>
	<div class="star_list_btn">
		<button type="button" class="update" data-st_id="<?=$star_reply[st_id]?>">수정</button>
		<button type="button" class="delete" data-st_id="<?=$star_reply[st_id]?>">삭제</button>
	</div>
	<? } ?>
</li>
<?
	}

}
?>
</ul>
<? if(!$scroll_load){ ?><div class="page_center"><?=$pagelist?></div><? } ?>

<? if($total_page>1){ ?>
<a class="star_list_more">더보기 <b></b></a>
<? } ?>


<? if($scroll_load){ ?>
<div id="star_loading_wait" class="loading_wait">
	<img src="<?=$nfor[skin_path]?>img/ajax-loader.gif">
	<span>목록을 불러오고 있습니다</span>
	<p>잠시만 기다려 주세요</p>
</div>

<script type="text/html" id="star_item_list_script">
<li class="st_li" id="st_li_<%=st_id%>">
	<div class="name_date_wrap">
		<span class="star_list_name"><% if(st_best){ %><span class="best">베스트후기</span><% } %><b class="staricon"><em style="width:<%=st_star_per%>%;"></em></b><%=st_mb_id%></span>
		<span class="star_list_date"><%=st_insert_datetime%></span>
	</div>
	<div class="star_memo"><%=st_memo%></div>
	<% if(st_access=="1"){ %>
	<div class="star_list_btn">
		<button type="button" class="update" data-st_id="<%=st_id%>">수정</button>
		<button type="button" class="delete" data-st_id="<%=st_id%>">삭제</button>
	</div>
	<% } %>
</li>

<%
_(reply).each(function(data){
%>
<li class="st_li" id="st_li_<%=data.st_id%>">

	<div>
		<span class="star_list_name"><i></i><b>답변</b> &nbsp;<%=data.st_mb_id%></span>
		<span class="star_list_date"><%=data.st_insert_datetime%></span>
		<div style="clear:both;"></div>
	</div>

	<div class="star_comment_wrap" id="update_star_<%=data.st_id%>"></div>

	<div class="star_memo" id="star_memo_<%=data.st_id%>"><%=data.st_memo%></div>

	<% if(data.st_access=="1"){ %>
	<div class="star_list_btn">
		<button type="button" class="update" data-st_id="<%=data.st_id%>">수정</button>
		<button type="button" class="delete" data-st_id="<%=data.st_id%>">삭제</button>
	</div>
	<% } %>
	
</li>
<%
});
%>
</script>

<script>
var is_last_star = 0;
var star_page = 1;
var it_id = "<?=$item[it_id]?>";
var star_total_page = "<?=$total_page?>";

$(document).on("click", ".star_list_more", function (e){
	if(is_last_star==0){
		++star_page;
		item_star_list_load();
	}
	if(star_total_page <= star_page){
		$('.star_list_more').hide();
	}
});

function item_star_list_load(){
	$("#star_loading_wait").show();
	if(star_page==1){
		$(".star_list").html("");
	}
	$.ajax({
		type     : "get",
		url      : "item_star_list.php",
		data     : "json=list&page="+star_page+"&it_id="+it_id,
		dataType : "json",
		cache: false,
		success  : function(data) {
			console.log(data);
			if(data.count > 0){
				template = _.template($("#star_item_list_script").html());
				var template_html = "";
				for(var i=0; i<data.star_list.length; i++) {
					template_html +=  template({st_id: data.star_list[i].st_id
						, st_insert_datetime: data.star_list[i].st_insert_datetime
						, st_access: data.star_list[i].st_access
						, st_star_per: data.star_list[i].st_star_per						
						, st_mb_id: data.star_list[i].st_mb_id						
						, st_memo: data.star_list[i].st_memo
						, reply: data.star_list[i].reply});
				}
				$(".star_list").append(template_html);
			}

			$("#star_loading_wait").hide();		
			
			if(data.last_page == 1){
				is_last_star++;
			}
			
		},
		error: function(e){
			console.log(e);
			console.log("Ajax failed");
		}
	});
}
</script>
<? } ?>

<script>
$(document).on("click", ".star_list .delete", function (){
	var st_id = $(this).data("st_id");
	if(confirm("상품평 삭제시에는 복구 및 재등록은 불가능합니다.\n정말 삭제하시겠습니까?")){
		$.ajax({
			type: "post",
			data: {
				"mode":"delete",
				"st_id":st_id
			},
			url: "item_star_form.php",
			success: function(response){
				var json = $.parseJSON(response); 
				if(json["result"]=="ok"){
					$("#st_li_"+st_id).remove();
					if($(".star_list li").length < 1){
						star_page = 1;
						item_star_list_load();
					}
				} else{
					alert(json["msg"]);
				}
			}
		});
	}
});

$(document).on("click", ".star_list .update", function (){
	var st_id = $(this).data('st_id');
	location.href = "item_star_form.php?it_id="+it_id+"&st_id="+st_id;
});

$(document).on("click", ".star_memo", function (){

	if($(this).hasClass("on")){
		$(this).removeClass("on");
	} else{
		$(this).addClass("on");
	}

});
</script>












<style>
.bigimage_wrap { display:none; width:100%; height:100%; position:fixed; top:0; right:0;	z-index:99999; }
.bigimage_back { background-color:#000; height:100%; width:100%; opacity:1; }

.swiper-container {	width:100%;	height:100%; }
.swiper-slide { text-align:center;	font-size:18px;	background:#000; display:-webkit-box; display:-ms-flexbox; display:-webkit-flex; display:flex; -webkit-box-pack:center;	-ms-flex-pack:center; -webkit-justify-content:center;	justify-content:center;	-webkit-box-align:center; -ms-flex-align:center; -webkit-align-items:center; align-items:center; }
.swiper-slide img { width:86%; }
.swiper-pagination-bullet { background:#fff; }
.swiper-pagination-bullet-active { background:#e83862; }
.swiper-pagination-fraction, .swiper-pagination-custom, .swiper-container-horizontal > .swiper-pagination-bullets { bottom:15px; }
</style>

<div class="bigimage_wrap">
	<div class="bigimage_back">
		<div class="swiper-container">
			<div class="swiper-wrapper"></div>
			<div class="swiper-pagination"></div>
		</div>
	</div>
</div>

<script>
var star_swiper = null
$(document).on("click", ".swiper-slide img", function (){
	$(".bigimage_wrap").hide();
	star_swiper.destroy();
});

$(document).on("click", ".st_img", function (){

	var st_id = $(this).data("st_id");
	var print_html = "";
	$("#st_li_"+st_id+" .st_img").each(function(index) { 
		print_html += "<div class=\"swiper-slide\"><img src=\""+ $(this).attr("src") +"\"></div>";
	}); 
	$(".bigimage_wrap .swiper-wrapper").html(print_html);

	var number = $(this).data("number");
	$(".bigimage_wrap").show();

	star_swiper = new Swiper('.swiper-container', {
	  initialSlide: number,
	  loop: true,
	  pagination: {
		el: '.swiper-pagination',
	  },
	});
});
</script>

