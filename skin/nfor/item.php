<?php
include_once $nfor[skin_path]."head.php";

$hide= "1";
$item[it_memo]=preg_replace("/ zzstyle=([^\"\']+) /"," ",$item[it_memo]);
$item[it_memo]=preg_replace("/ style=(\"|\')?([^\"\']+)(\"|\')?/","",$item[it_memo]);
?>
<style>
.item_wrap{min-height:1000px;}

.item_wrap .box_ct{margin-right: 0px; margin-left: 0px; position: relative;  z-index: 30;  background: #fff; padding: 16px 15px 15px; letter-spacing:-0.065em; box-sizing: border-box; -webkit-box-sizing: border-box;}
.item_wrap .box_ct ul{ box-sizing: border-box; -webkit-box-sizing: border-box;}
.item_wrap .box_ct li{margin-bottom:5px; position: relative;}
.item_wrap .box_ct .sub_tit{font-size:0.8em;color: #7d7e80; display:block;}
.item_wrap .box_ct .tit{display:block; font-size:0.9em; line-height:1.25em; margin-bottom: 22px;}
.item_wrap .box_ct .price1{display:block;  font-size: 0.8rem; color: #c2c7cc; text-decoration: line-through; margin-left:60px; box-sizing: border-box; -webkit-box-sizing: border-box;}
.item_wrap .box_ct .price2{display: inline-block; font-weight: 700;font-size: 1rem;  vertical-align: bottom;margin-left:60px;}
.item_wrap .box_ct .rate{ position: absolute; top: 0px; left: 0px; font-weight: 700; font-size: 1.7rem;color: #e83862;}
.item_wrap .box_ct .title1{  position: absolute; top: 0px;  left: 0; font-weight: 700;  font-size: 12px;}
.item_wrap .box_ct .cont1{display:block; padding-left:75px; font-size: 12px; color:#9ea5ae}
.item_wrap .box_ct .cont1 .label_del {border: 1px solid #36a77c; background-color: #fff;color: #36a77c; padding:3px 5px;display: inline-block; font-weight: 700;font-size: 11px; margin-bottom:5px;}
.item_wrap .box_ct .wrap_item_ico { position: absolute; top: 15px; right: 0px; display:inline-block; margin:0 auto; }
.item_wrap .box_ct .wrap_item_ico .item_ico { display:table-cell; text-align:center; width:30px; cursor:pointer; }
.btn_zzim { display:block; margin:0 auto; width:30px; height:26px; background:url("<?=$nfor[skin_path]?>img/item.png") no-repeat center -1px; background-size:26px auto; text-align:center; }
.btn_zzim.on { display:block; margin:0 auto; width:30px; height:26px; background:url("<?=$nfor[skin_path]?>img/item.png") no-repeat center -27px; background-size:26px auto; text-align:center; }
.btn_zzim span { display:block; padding-top:31px; font-size:12px; color:#999; }

.btn_alarm { display:block; margin:0 auto; width:30px; height:26px; background:url("<?=$nfor[skin_path]?>img/item.png") no-repeat center -80px; background-size:26px auto; text-align:center; }
.btn_alarm.on { display:block; margin:0 auto; width:30px; height:26px; background:url("<?=$nfor[skin_path]?>img/item.png") no-repeat center -106px; background-size:26px auto; text-align:center; }
.btn_alarm span { display:block; padding-top:31px; font-size:12px; color:#999; }

.btn_sns { display:block; margin:0 auto; width:30px; height:26px; background:url("<?=$nfor[skin_path]?>img/item.png") no-repeat center  -130px; background-size:26px auto; text-align:center; }
.btn_sns span { display:block; padding-top:31px; font-size:12px; color:#999; }


.item_wrap .buy_box{ position: relative; border-bottom:solid 1px #e5e5e5;  border-top:none; padding: 10px 15px; margin-right: 0px; margin-left: px; margin-bottom: 10px;background-color:#FFF;  box-sizing: border-box; -webkit-box-sizing: border-box;}
.item_wrap .buy_box:after{display: block;clear: both;content: '';}
.item_wrap .buy_box .title1{  position: absolute; top: 17px;  left: 15; font-weight: 700;  font-size: 12px;}
.item_wrap .buy_box .time{float:left;  font-size: .7rem; color: #959da6;}
.item_wrap .buy_box .buy {float:right; font-size: .7rem; color: #959da6;}
.item_wrap .buy_box .coupons{margin-left:85px;  background-color:#e83862; padding:3px 10px; height:25px; display:inline-block; border-radius:3px;}
.item_wrap .buy_box .coupons i{font-style:normal; color:#FFF; font-size:11px;  letter-spacing:-0.065em}

.tab-cont { display:none; background-color:#fff; min-height:100px; }
#tab1 img { width:100%; }
#tab1 { display:block; }
.tabmenu{ clear:both; overflow:hidden; width:100%; background:#fff; transform:translateZ(0px); -webkit-transform: translateZ(0); box-sizing: border-box; -webkit-box-sizing: border-box; }
.tabmenu li{ float:left; width:24%; height:45px; text-align:center; border-bottom:1px solid #e5e5e5; }
.tabmenu li:nth-child(3), .tabmenu li:nth-child(4){ width:26%; }
.tabmenu li div{ overflow:hidden; position:relative; line-height:43px; display:block; bottom:-1px; }
.tabmenu li a{ display:block; color:#444; height:45px; font-size:0.8rem; letter-spacing:-1px; }
.tabmenu li a em{ position:relative; top:-1px; padding:0 1px 0 2px; font-size:11px; font-weight:normal; letter-spacing:-1px; }
.tabmenu li a em b{ font-weight:normal; font-style:normal; letter-spacing:-1px; }
.tabmenu li.active div:after { content:""; display:block; position:relative; border-bottom:3px solid #e83862; bottom:3px; font-weight:normal; font-size:15px; }
.tabmenu li.active a{ font-weight:bold; color:#e83862; }
.nav_tabmenu { display:block; }
.top_show { display:block; position:fixed; top:54px; left:0px; width:100%; z-index:900; opacity:.95;  }
.tabmenu:after { content:""; display:block; clear:both; }
</style>






<!-- item_wrap -->
<div class="item_wrap">


	<?php
	include_once $nfor[skin_path]."inc_item_top.php";
	?>


	<div class="box_ct">
		<ul>
			<li><span class="sub_tit"><?=$item[it_description]?></span></li>
			<li><span class="tit"><?=$item[it_name]?></span></li>
			<li>
				<span class="price1"><?=number_format($item[it_price1])?>원</span>
				<span class="price2"><?=number_format($item[it_price2])?>원</span>
				<span class="rate"><?=$item[it_discount_rate]?>%</span>
				<div class="wrap_item_ico">
					<div class="item_ico"><a class="btn_zzim <?=$item[it_zzim_is]?"on":""?>"></a></div>
					<div class="item_ico"><a class="btn_alarm <?=$item[it_alarm_is]?"on":""?>"></a></div> 
					<div class="item_ico"><a class="btn_sns"></a></div>
				</div>
			</li>
		</ul>
	</div>


	<div class="buy_box" >
		<div class="time">

			<? if($item[it_shopping]=="2"){ // 기간판매상품이면 ?>
			<?=$item[it_countdown_html]?>
			<script>
			$(function () {
				var austDay = new Date(<?=strtotime($item[it_payenddate])*1000?>);
				$("#defaultCountdown").countdown({until: austDay, layout:'<? if($item[it_countdown_d]>0){ ?>{dn}일<? } else{ ?> {hnn} : {mnn} : {snn}<? } ?>'});
			});
			</script>
			<? } ?>

		</div>
		<div class="buy"><?=number_format(it_sales_volume_new($item))?>개 구매</div>
	</div>





	<nav class="nav_tabmenu">
		<ul class="tabmenu">
			<li class="active"><div><a data-tab="#tab1">상세설명</a></div></li>
			<li><div><a data-tab="#tab2">구매정보</a></div></li>
			<li><div><a data-tab="#tab3">구매후기<em><b><?=$item[it_star_cnt]?number_format($item[it_star_cnt]):""?></b></em></a></div></li>
			<li><div><a data-tab="#tab4">상품문의<em><b><?=$item[it_qna_cnt]?number_format($item[it_qna_cnt]):""?></b></em></a></div></li>
		</ul>
	</nav>


	<div id="tab1" class="tab-cont">
	<? if($item[it_youtube_use]=="1"){ ?>	
	<iframe width="100%" height="auto" src="<?=$item[it_youtube_link]?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>	
	<? } ?>
	<? if($nfor[test]){ ?><img src="/skin/demo/img/56.png"><? } else{ ?><?=$item[it_memo]?><? } ?>
	</div>
	
	<div id="tab2" class="tab-cont">
		<div class="coution"><?=$item[it_rule]?></div>
		<style>
		.coution{ font-size: .7rem; color:#a0a8b1; padding:20px; border-bottom:solid 1px #DCDCDC;}
		.it_info_val_tit{font-size:0.9em; margin:10px 0px;letter-spacing:-.08em;;}
		.it_info_val_tbl{border-top:solid 2px #333; margin-bottom:20px;}
		.it_info_val_tbl th {  font-size: .7rem; background-color:#f6f6f6; border-top:solid 1px #ccc; border-bottom:solid 1px #ccc; height:30px;  width:25%; padding:8px; text-align:left; font-weight:normal;color:#666; letter-spacing:-.08em;}
		.it_info_val_tbl td { font-size: .7rem; border-top:solid 1px #ccc; border-bottom:solid 1px #ccc; height:30px;  padding:8px; color:#a0a8b1; letter-spacing:-.08em;}
		</style>
		<div style="padding:15px;">


			<h3 class="it_info_val_tit">상품정보제공고시</h3>
			<table class="table it_info_val_tbl">
			<th>항목</th>
			<td>내용</td>
			<?
			$it_info = json_decode($item[it_info], true);
			if($it_info[title]){
				foreach ($it_info[title] as $key => $row) { 
			?>
			<tr id="<?=$key?>">
			<? if(isset($it_info[title][$key][0])){ ?>
				<th><?=$it_info[title][$key][0]?></th>
				<td<? if(!isset($it_info[title][$key][1])) echo " colspan='3'"; ?>><?=$it_info[text][$key][0]?></td>
			<? } ?>
			<? if(isset($it_info[title][$key][1])){ ?>
				<th><?=$it_info[title][$key][1]?></th>
				<td><?=$it_info[text][$key][1]?></td>
			<? } ?>
			</tr>
			<?
				}
			}
			?>
			</table>


			<? if($item[it_add_info]){ ?>
			<h3 class="it_info_val_tit">추가항목</h3>
			<table class="table it_info_val_tbl">
			<th>항목</th>
			<td>내용</td>
			<?
			$it_info = json_decode($item[it_add_info], true);
			if($it_info[title]){
				foreach ($it_info[title] as $key => $row) { 
			?>
			<tr id="<?=$key?>">
			<? if(isset($it_info[title][$key][0])){ ?>
				<th><?=$it_info[title][$key][0]?></th>
				<td<? if(!isset($it_info[title][$key][1])) echo " colspan='3'"; ?>><?=$it_info[text][$key][0]?></td>
			<? } ?>
			<? if(isset($it_info[title][$key][1])){ ?>
				<th><?=$it_info[title][$key][1]?></th>
				<td><?=$it_info[text][$key][1]?></td>
			<? } ?>
			</tr>
			<?
				}
			}
			?>
			</table>
			<? } ?>



		</div>
	</div>

	<div id="tab3" class="tab-cont"><? include_once "item_star_list.php"; ?></div>

	<div id="tab4" class="tab-cont"><? include_once "item_qna_list.php"; ?></div>

	<?php
	$location = sql_fetch("select * from nfor_item_location where lo_it_id='$item[it_id]' order by lo_id asc");
	if($location[lo_id]){
	?>
	<div id="tab5" class="tab-cont5" style="display:none;">

		<style>
		.item_location_wrap { background-color:#fff; padding:10px; }
		
		</style>

		<div class="item_location_wrap"><? include_once $nfor[skin_path]."inc_item_location.php"; ?></div>

	</div>
	<? } ?>


	<? if($item_return[it_etc_item]){ ?>

		<div class="gond_item2"></div>

		<div class="other_item_list">
			<p class="other_item_title">관련 상품</p>
			<div class="swiper-container swiper-container-b">
				<div class="swiper-wrapper">
					<?
					for($i = 0; $i < count($item_return[it_etc_item]); $i++){
						$data = $item_return[it_etc_item][$i];
						?>
						<div class="swiper-slide">
							<a href="item.php?it_id=<?=$data[it_id]?>" style="display:block;">
								<div><img src="<?=$data[it_img]?>" class="other_item_img"></div>
								<div>
									<p class="other_item_name"><?=$data[it_name]?></p>
									<b class="other_item_price2"><?=$data[it_price2]?><span class="other_item_price1">원</span></b>
								</div>
							</a>
						</div>
					<? } ?>
				</div>
			</div>
		</div>

		<script>
		  $(document).ready(function () {
			var swiperB = new Swiper('.swiper-container-b', {
			  freeMode: true,
			  spaceBetween: 10,
			  slidesPerView: 3
			});
		  });
		</script>
	<? } ?>

</div>

<!-- 창열기(구매하기버튼) -->
<div class="option_open_btn_wrap">
	<div class="option_open_pointer"></div>
	<div class="option_open_btn">구매하기</div>
</div>
<!-- //창열기(구매하기버튼) -->
<style>

.gond_item2 {
	border-top: solid 1px #d9d9d9;
	background: #f4f4f4;
	width: 100%;
	height: 10px;
}

.other_item_list {
	background-color:#fff;
	padding: 15px;
	border-top: solid 1px #f8f8f8;
}

.other_item_title {
	color: #555;
	font-size: 14px;
	margin-bottom: 10px;
	letter-spacing: -1px;
}

.other_item_name {
	font-size: 13px;
	line-height: 18px;
	padding-top: 5px;
	padding-bottom: 2px;
	color: #444;
	letter-spacing: -1px;
	overflow: hidden;
	text-overflow: ellipsis;
	-webkit-line-clamp: 1;
	display: -webkit-box;
	-webkit-box-orient: vertical;
}

.other_item_price2 {
	font-size: 15px;
	color: #ec1d28;
	font-weight: normal;
	letter-spacing: -0.05em;
}

.other_item_price1 {
	font-size: 14px;
	font-weight: normal;
}

.other_item_img {
	width: 100%;
	border: solid 1px #F8F8F8;
}

.swiper-container-b .swiper-slide {
	background-color: #fff;
}

.opt_cnt{display: inline-block;float: left;width: 32px; height: 26px!important; border: 1px solid #c2c7cc;  border-right: 0 none; border-left: 0 none;  line-height: 24px; color: #000; text-align: center; vertical-align: top;}
.opt_cal_total{margin:3px 0px; font-size:14px; }
.opt_cal_total_span{display: inline-block; vertical-align: 1px;; color: #d32f2f;font-size: 14px; font-family: tahoma;  font-weight: bold; line-height: 31px;  height: 31px;}


.opt_bg_color { display:none; position:fixed; left:0px; top:0px; width:100%; height:100%; background-color:rgba(0,0,0,0.5); z-index:9977; }
.option_close_btn_wrap { z-index:9999; }
</style>




<!-- 옵션창 -->
<div class="opt_bg_color"></div>
<div class="option_close_btn_wrap">
	<div class="option_close_pointer"></div>
	<div class="wrap_optbar">

		<?php
		include_once "opt_popup.php";
		?>

		<div class="opt_cal_total <?=$item[it_opt_use]=="1"?"hide":""?>">
		총구매금액 <span class="opt_cal_total_span"><?=number_format($item[it_price2]*$item[it_buy_qty_min])?></span>원
		</div>

		<ul class="opt_cart_order_btn <?=$item[it_opt_use]=="2"?"on":""?>">
			<li><a class="item_btn item_btn_cart opt_cart_btn">장바구니</a></li>
			<li><a class="item_btn item_btn_buy opt_order_btn">구매하기</a></li>
		</ul>

	</div>
</div>
<!-- //옵션창 -->

<!-- 공유하기팝업 -->
<div id="sns_popup">
	<div class="sns_wrap">
		<div class="sns_title">
			공유하기
			<a class="sns_close_btn"></a>
		</div>
		<ul class="sns_list">
			<li><a href="javascript:sns_link('kakaotalk')"><i class="btn_kakaotalk"></i>카카오톡</a></li>
			<li><a href="javascript:sns_link('kakaostory')"><i class="btn_kakaostory"></i>카카오스토리</a></li>
			<li><a href="javascript:sns_link('twitter')"><i class="btn_twitter"></i>트위터</a></li>
			<li><a href="javascript:sns_link('facebook')"><i class="btn_facebook"></i>페이스북</a></li>
			<li><a href="javascript:sns_link('naver')"><i class="btn_cafe"></i>네이버공유</a></li>
			<li><a href="javascript:sns_link('sms')"><i class="btn_sms"></i>SMS</a></li>
			<li><a href="javascript:sns_link('naverblog')"><i class="btn_blog"></i>링크공유</a></li>
			<li><a href="javascript:sns_link('naverline')"><i class="btn_line"></i>라인</a></li>
		</ul>
	</div>
</div>
<!-- //공유하기팝업 -->

<!-- 찜하기팝업 -->
<div id="zzim_popup">
	<div class="zzim_msg off"><p>찜하기팝업</p></div>
</div>
<!-- //찜하기팝업 -->

<!-- 판매알림팝업 -->
<div id="alarm_popup">
	<div class="alarm_msg off"><p>판매알림팝업</p></div>
</div>
<!-- //판매알림팝업 -->

<!-- 장바구니담기팝업 -->
<div id="cart_popup">
	<div>
		<p>장바구니에 상품이 담겼습니다.</p>
		<a href="cart.php">구매하러가기</a>
	</div>
</div>
<!-- 장바구니담기팝업 -->

<script type="text/javascript">
<!--
var it_id = "<?=$item[it_id]?>";
var it_buy_qty = parseInt("<?=$item[it_buy_qty]?>");
var it_opt_depth = parseInt("<?=$item[it_opt_depth]?>");
var it_url = location.href;	
var it_img = "<?=$item[it_img_url]?>";
var it_name = "<?=$item[it_name]?>";
var it_description = "<?=$item[it_description]?>";

$(document).on("click", ".tabmenu li", function(){
	$(".tabmenu li").removeClass("active");
	$(this).addClass("active");
	$(".tab-cont").hide();
	$($(this).find("a").data("tab")).show();
});

$(document).on("click", ".btn_zzim", function(){

	var this_obj = $(this);

	$.ajax({
		type: "post",
		data : "mode=zzim&it_id="+it_id,
		url: "item.php",
		success: function(response){
			var json = $.parseJSON(response); 
			
			if(json["result"]=="login"){
				login("item.php?it_id="+it_id);
			} else if(json["result"]=="delete"){
				this_obj.removeClass("on");
				$(".zzim_msg").removeClass("on").addClass('off');	
				$('#zzim_popup').show();
				setTimeout(function(){
					$('#zzim_popup').hide();
				},2000);
			} else if(json["result"]=="insert"){
				this_obj.removeClass("off").addClass("on");
				$(".zzim_msg").removeClass("off").addClass('on');
				$('#zzim_popup').show();
				setTimeout(function(){
					$('#zzim_popup').hide();
				},2000);
			} else{
				alert(json["msg"]);
			}
		}
	});

});

$(document).on("click", ".btn_alarm", function(){

	$.ajax({
		type: "post",
		data : "mode=alarm&it_id="+it_id,
		url: "item.php",
		success: function(response){
			var json = $.parseJSON(response); 
			
			if(json["result"]=="login"){
				login("item.php?it_id="+it_id);
			} else if(json["result"]=="delete"){
				$(".btn_alarm").removeClass("on");
				$(".alarm_msg").removeClass("on").addClass('off');	
				$('#alarm_popup').show();
				setTimeout(function(){
					$('#alarm_popup').hide();
				},2000);
			} else if(json["result"]=="insert"){
				$(".btn_alarm").removeClass("off").addClass("on");
				$(".alarm_msg").removeClass("off").addClass('on');
				$('#alarm_popup').show();
				setTimeout(function(){
					$('#alarm_popup').hide();
				},2000);
			} else{
				alert(json["msg"]);
			}
		}
	});

});



/*
$(window).scroll( function(){
	var scrollTop = $(document).scrollTop();
	if(scrollTop >= $('.gond_item').position().top){
		$(".tabmenu").addClass("top_show");
	} else{
		$(".tabmenu").removeClass("top_show");
	}
});
*/


$(document).on("click", ".btn_sns", function(){
	$("#sns_popup").show();
});

$(document).on("click", ".sns_close_btn", function(){
	$('#sns_popup').hide();
});

$(document).on("click", ".opt_cart_order_btn", function(){
	if(!$(this).hasClass("on")){
		alert("옵션을 선택해주세요");
	}
});

$(document).on("click", ".option_open_btn_wrap", function(){ // 구매하기버튼클릭
	$(".opt_bg_color").show();
	$("body").css("overflow-y","hidden");
	$(this).hide();
	$(".option_close_btn_wrap").animate({"bottom":"0px"}, 500 ).show();
});

$(document).on("click", ".opt_cart_order_btn.on .opt_cart_btn", function(){
	nfor_cart_order("cart");
});

$(document).on("click", ".opt_cart_order_btn.on .opt_order_btn", function(){
	nfor_cart_order("order");
});

function nfor_cart_order(ty){

	$('#move').val(ty);	
	$.ajax({ 
		type : "post"
		, url : "cart.php"
		, cache : false  
		, data : $("#item_frm").serialize()
		, success : function(response){			
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){

				if(ty=="order"){
					<?
					if($config[cf_buy_with_cart]=="2"){	// 현제상품군만 구매
					?>
					location.href="cart_order.php?it_id="+it_id;
					<? 
					} else{ // 장바구니 상품과 함께구매
					?>
					location.href="cart_order.php";
					<?
					}
					?>
				} else if(ty=="cart"){
					<?
					if($config[cf_cart_in_show]=="2"){ // 이동
					?>
					location.href="cart.php";
					<? 
					} else{ // 레이어팝업
					?>
					$("#cart_cnt").html(json["cart_cnt"]);

					$("#cart_popup").animate({"top":"100px"}, 500 ).show();
					setTimeout(function(){
						$('#cart_popup').animate({"top":"-280px"}, 500 );
					},5000);

					<? 
					}
					?>
				} else if(ty=="naverpay"){
					location.href="naverpay_order.php?it_id="+it_id;
				} else{
					
				}

			} else{
				alert(json["msg"]);
			}	
		} 
	});

}

$(document).on("click", ".option_close_pointer, .opt_bg_color", function(){ // 닫기버튼클릭
	$(".opt_bg_color").hide();
	$("body").css("overflow-y","scroll");
	$(".option_close_btn_wrap").animate({"bottom":"-280px"}, 500 ).hide();
	$('.option_open_btn_wrap').show();
});
//-->
</script>


<script>
$(function(){
	var lastScroll = 0;
	$(window).scroll(function(event){
		var st = $(this).scrollTop();
		if(st<200){
			$("#sub_gnb").addClass("item_gnb");
		} else{
			$("#sub_gnb").removeClass("item_gnb");
		}		
		lastScroll = st;
	});
});
</script>

<?php
include_once "inc_opt_popup_js.php"; // 옵션 자바스크립트
include_once $nfor[skin_path]."tail.php";
?>