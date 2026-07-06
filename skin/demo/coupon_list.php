<?php
include_once $nfor[skin_path]."mypage_head.php";
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
$(document).on("click", "#money_msg_btn", function(){
	if($("#money_msg_wrap").css('display')=="none"){
		$("#money_msg_wrap").show();
	} else{
		$("#money_msg_wrap").hide();	
	}
});
//-->
</SCRIPT>
<style>
.coupon_stat {overflow:hidden; border:solid 3px #ccc; background-color:#fff; margin-bottom:30px;}
.coupon_stat li { float:left; width:50%; height:60px; padding:20px; font-size:16px; color:#000;position:relative; box-sizing:border-box; -webkit-box-sizing:border-box; }
.coupon_stat li:hover {background-color:#FAFAFA; }
.coupon_stat li:first-child { border-right:solid 1px #ececec; margin-left:-1px; }
.coupon_stat span { position:absolute; right:20px;top:25px; color:#ec3940; font-size:14px; font-family:'NanumGothicBold';}


.tbl{width:100%;margin-top:10px; ; font-size:13px; overflow:hidden;}
.tbl li{float:left; width:50%; box-sizing:border-box; -webkit-box-sizing:border-box; }


.caution{border:solid 1px #DCDCDC; font-size:12px; margin-top:20px;}
.caution .title{border-bottom:solid 1px #DCDCDC;padding:20px 20px;font-size:18px;}
.caution .money_msg{padding:15px 20px;}
.caution .money_msg ul li{line-height:24px;color:#666;}

.coupon_list { margin:5px 10px; text-align:center;}
.coupon_list b{font-size:3em; display:inline-block; margin-bottom:10px; color:#e83862; }
.coupon_list span{display:block; color:#e83862}
.coupon_list .tit{height:48px;}
.coupon_list {position:relative;  background-color:#e83862;  border-top:solid 1px #dfe3e6;border-bottom:solid 1px #dfe3e6;border-left:solid 1px #dfe3e6;border-right:solid 1px #dfe3e6; background-color:#fff;  padding:20px; margin-bottom:5px; font-size:13px; border-radius:5px;}
.coupon_list li:last-child { border-bottom:solid 1px #dfe3e6; }

.coupon_list  .pc{display:inline-block;font-size:11px;color:#ff0000; width:40px; border:solid 1px #ff0000; padding:2px; margin-top:10px;}
.coupon_list  .mobile{display:inline-block;font-size:11px;color:#3366ff; width:40px; border:solid 1px #3366ff;  padding:2px;  margin-top:10px;}
.coupon_list  .app{display:inline-block;font-size:11px;color:#339900; width:40px; border:solid 1px #339900; padding:2px; margin-top:10px;}
.punch { position: absolute; top: 50%;  right: -5px; height: 102px;  margin-top: -51px;}
.punch i { display: block; width: 10px; height: 10px;background:url(./skin/nfor/img/punch.png);margin-bottom:3px;}
.coupon_list li p { color:#333;  font-size:.85em; padding-bottom:10px; letter-spacing:-.0652em;}
.cp_exp { color:#ff9900; font-size:11px; display:block; padding-top:10px; border-top:dashed 1px #ddd}
</style>


<ul class="coupon_stat">
	<li><b class="fotr">사용가능</b><span><?=$write[mb_coupon]?>개</span></li>
	<li><b class="fotr">마감임박</b><span><?=$write[expire_mb_coupon]?>개</span></li>
</ul>

<ul class="tbl coupon_list_ul">
<?php
for($i=0; $i<count($return["list"]); $i++){
	$coupon = $return["list"][$i];
?>
<li>
	<div class="coupon_list">
		<span><?=$coupon[cp_name]?></span>
		<b><?=$coupon[cp_sale]?></b>
		<p class="tit"><?=$coupon[cp_sale_text]?></p>
		<span class="cp_exp"><?=$coupon[cp_dayofuse]?></span>
		<? if($coupon[cp_is_pc]){ ?><span class="pc">PC</span><? } ?>
		<? if($coupon[cp_is_mobile]){ ?><span class="mobile">모바일</span><? } ?>
		<? if($coupon[cp_is_app]){ ?><span class="app">어플</span><? } ?>
		<div class="punch"><i></i><i></i><i></i><i></i><i></i><i></i></div>
	</div>
</li>
<?php } ?>
</ul>
<? if(!$scroll_load){ ?><div class="page_center"><?=$pagelist?></div><? } ?>

<div class="caution">
	<div class="title fotm">! 할인쿠폰 이용시 주의사항</div>
	<div class="money_msg">
		<ul>
			<li> • 할인쿠폰에 따라 적용대상이 다를수 있으며 쿠폰 1개당 한개의 상품에서만 이용이 가능합니다.</li>
			<li> • 조건에 따라 유효기간이 다를수 있으며 유효기간이 만료된 쿠폰은 자동 소멸됩니다.</li>
			<li> • 이곳에서 발급받으신 쿠폰의 금액, 내역, 기간을 확인하실 수 있습니다.</li>
		</ul>
	</div>
</div>


<?php if($scroll_load){ ?>
<div class="loading_wait">
	<img src="<?=$nfor[skin_path]?>img/ajax-loader.gif">
	<span>목록을 불러오고 있습니다</span>
	<p>잠시만 기다려 주세요</p>
</div>

<script type="text/html" id="item_list_script">
<li>
	<div class="coupon_list">
		<span><%=cp_name%></span>
		<b><%=cp_sale%></b>
		<p class="tit"><%=cp_sale_text%></p>
		<span class="cp_exp"><%=cp_dayofuse%></span>
		<% if($coupon[cp_is_pc]){ %><span class="pc">PC</span><% } %>
		<% if($coupon[cp_is_mobile]){ %><span class="mobile">모바일</span><% } %>
		<% if($coupon[cp_is_app]){ %><span class="app">어플</span><% } %>
		<div class="punch"><i></i><i></i><i></i><i></i><i></i><i></i></div>
	</div>
</li>
</script>

<script>
var is_last = 0;
var page = 1;

$(window).scroll(function() {
	if ($(window).scrollTop() == $(document).height() - $(window).height()) {
		if(is_last==0){
			++page;
			item_list_load();
		}
	}
});

function item_list_load(){
	$(".loading_wait").show();
	if(page==1){
		$(".coupon_list_ul").html("");
	}
	$.ajax({
		type     : "get",
		url      : "coupon_list.php",
		data     : "json=list&page="+page,
		dataType : "json",
		cache: false,
		success  : function(data) {
			if(data.count > 0){
				template = _.template($("#item_list_script").html());
				var template_html = "";
				for(var i=0; i<data.list.length; i++) {
					template_html +=  template({cp_name: data.list[i].cp_name
						, cp_sale: data.list[i].cp_sale						 
						, cp_sale_text: data.list[i].cp_sale_text
						, cp_dayofuse: data.list[i].cp_dayofuse
						, cp_is_pc: data.list[i].cp_is_pc
						, cp_is_app: data.list[i].cp_is_app
						, cp_is_mobile: data.list[i].cp_is_mobile});
				}
				$(".coupon_list_ul").append(template_html);
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
include_once $nfor[skin_path]."mypage_tail.php";
?>