<?php
include_once $nfor[skin_path]."head.php";
?>
<script type="text/javascript">
<!--
// 전체삭제
$(document).on("click", "#sel_opt_del_btn", function(){
	if(!$(".it_opt_wrap .chk:checked").length){
		alert("삭제할 옵션을 선택해주세요");
	} else{
		if(confirm("선택하신 옵션을 삭제하시겠습니까?")){
			$("#mode").val("chk_opt_delete");
			$.ajax({
				type: "post",
				data : $("#fcart").serialize(),
				url: "cart.php",
				success: function(response){
					var json = $.parseJSON(response); 
					if(json["result"]=="ok"){
						document.location.reload();
					}
				}
			});
		}
	}
});

// 전체선택
$(document).on("click", "#select_all", function(){
	$(".chk").prop("checked", this.checked );
	if($("#select_all").is(":checked")){
		$("#sel_opt_del_btn").html("전체삭제");
	} else{
		$("#sel_opt_del_btn").html("선택삭제");
	}
});

// 상품선택
$(document).on("click", ".chk_item", function(){
	$(".chk_item_"+$(this).val()).prop("checked", this.checked);
});

// 옵션삭제
$(document).on("click",".opt_delete",function(){
	var opt_id = $(this).data("opt_id");
	var opt_name = $(this).data("opt_name");
	if(confirm("선택하신 옵션을 삭제하시겠습니까?\n"+opt_name)){
		$.post("cart.php",{ "mode":"opt_delete", "opt_id":opt_id }, function(response){
			var json = $.parseJSON(response);
			if(json["result"]=="ok"){
				document.location.reload();
			} else{
				alert("상품 삭제에 실패하였습니다");
			}
		});
	}
});

// 옵션수량증가
$(document).on("click", ".cart_item_list .count_plus", function(){
	var it_buy_qty_min = parseInt($(this).parent().find(".opt_cnt").data("it_buy_qty_min")); // 최소구매수량
	var it_buy_qty_max = parseInt($(this).parent().find(".opt_cnt").data("it_buy_qty_max")); // 최대구매수량
	var it_buy_qty_type = $(this).parent().find(".opt_cnt").data("it_buy_qty_type"); // 1제한없음 2구매제한
	var it_stock_type = $(this).parent().find(".opt_cnt").data("it_stock_type"); // 1무한정판매 2재고량에따름
	var it_gp_cnt = parseInt($(this).parent().find(".opt_cnt").data("it_gp_cnt")); // 몇개씩 증가
	var it_opt_cnt = parseInt($(this).parent().find(".opt_cnt").data("it_opt_cnt")); // 기본옵션갯수
	var ea_opt_cnt = parseInt($(this).parent().find(".opt_cnt").val()); // 수량
	var ea_stock = parseInt($(this).parent().find(".opt_cnt").data('stock')); // 재고수량
	var opt_id = $(this).parent().parent().find(".opt_cnt").data("opt_id"); // 옵션아이디
	var opt_price = $(this).parent().parent().find(".opt_cnt").data("price"); // 판매가격
	var it_id = $(this).parent().parent().find(".opt_cnt").data("it_id"); // 상품코드

	ea_opt_cnt = ea_opt_cnt+it_gp_cnt;

	if(it_buy_qty_type==2 && (opt_cnt_total('cart_item_list',it_id)+it_gp_cnt) > it_buy_qty_max){
		alert('해당상품의 1인당 최대 구매 수량은 '+it_buy_qty_max+'개 입니다.\n구매 수량을 확인해주세요.');
		ea_opt_cnt = ea_opt_cnt-it_gp_cnt;
	}
	
	if(ea_opt_cnt > ea_stock){
		ea_stock = ea_stock-it_buy_qty_min;
		ea_stock = ( Math.floor(ea_stock/it_gp_cnt) * it_gp_cnt) + it_buy_qty_min;
		alert("해당옵션은 최대 "+ea_stock+"개 까지 구매가능합니다");
		ea_opt_cnt = ea_stock;
	}

	$(this).parent().find(".opt_cnt").val(ea_opt_cnt);

	opt_total_price = opt_price*ea_opt_cnt;
	$("#opt_id_"+opt_id+" .opt_total_price").html(number_format(opt_total_price)+"원");

	opt_change(opt_id, ea_opt_cnt);
});

// 옵션수량차감
$(document).on("click", ".cart_item_list .count_minus", function(){
	var it_buy_qty_min = parseInt($(this).parent().find(".opt_cnt").data("it_buy_qty_min")); // 최소구매수량
	var it_buy_qty_max = parseInt($(this).parent().find(".opt_cnt").data("it_buy_qty_max")); // 최대구매수량
	var it_buy_qty_type = $(this).parent().find(".opt_cnt").data("it_buy_qty_type"); // 1제한없음 2구매제한
	var it_stock_type = $(this).parent().find(".opt_cnt").data("it_stock_type"); // 1무한정판매 2재고량에따름
	var it_gp_cnt = parseInt($(this).parent().find(".opt_cnt").data("it_gp_cnt")); // 몇개씩 증가
	var it_opt_cnt = parseInt($(this).parent().find(".opt_cnt").data("it_opt_cnt")); // 기본옵션갯수
	var ea_opt_cnt = parseInt($(this).parent().find(".opt_cnt").val()); // 수량
	var ea_stock = parseInt($(this).parent().find(".opt_cnt").data('stock')); // 재고수량
	var opt_id = $(this).parent().parent().find(".opt_cnt").data("opt_id"); // 옵션아이디
	var opt_price = $(this).parent().parent().find(".opt_cnt").data("price"); // 판매가격
	var it_id = $(this).parent().parent().find(".opt_cnt").data("it_id"); // 상품코드

	ea_opt_cnt = ea_opt_cnt-it_gp_cnt;

	if(ea_opt_cnt<it_buy_qty_min){
		ea_opt_cnt = it_buy_qty_min;
	}

	$(this).parent().find(".opt_cnt").val(ea_opt_cnt);

	opt_total_price = opt_price*ea_opt_cnt;
	$("#opt_id_"+opt_id+" .opt_total_price").html(number_format(opt_total_price)+"원");

	opt_change(opt_id, ea_opt_cnt);
});

// 옵션수량변경
$(document).on('blur change','.cart_item_list .opt_cnt',function(){
	var it_buy_qty_min = parseInt($(this).data("it_buy_qty_min")); // 최소구매수량
	var it_buy_qty_max = parseInt($(this).data("it_buy_qty_max")); // 최대구매수량
	var it_buy_qty_type = $(this).data("it_buy_qty_type"); // 1제한없음 2구매제한
	var it_stock_type = parseInt($(this).data("it_stock_type")); // 1무한정판매 2재고량에따름
	var it_gp_cnt = parseInt($(this).data("it_gp_cnt")); // 몇개씩 증가
	var it_opt_cnt = parseInt($(this).data("it_opt_cnt")); // 기본옵션갯수
	var ea_opt_cnt = parseInt($(this).val()); // 수량
	var ea_stock = parseInt($(this).data('stock')); // 재고수량
	var opt_id = $(this).data("opt_id"); // 옵션아이디
	var opt_price = parseInt($(this).data("price")); // 판매가격
	var it_id = $(this).data("it_id"); // 상품코드

	if(isNaN(ea_opt_cnt) || ea_opt_cnt<it_buy_qty_min || !ea_opt_cnt){ // 숫자가아니거나 최소구매수량보다 작거나 값이없으면
		ea_opt_cnt = it_buy_qty_min;
	}

	if((ea_opt_cnt-it_buy_qty_min)%it_gp_cnt != 0){
		alert("옵션은 "+it_gp_cnt+"개 단위로 주문이 가능합니다");
		ea_opt_cnt = it_buy_qty_min;
	}

	if(it_buy_qty_type==2 && opt_cnt_total('cart_item_list',it_id) > it_buy_qty_max){
		alert('해당상품의 1인당 최대 구매 수량은 '+it_buy_qty_max+'개 입니다.\n구매 수량을 확인해주세요.');
		ea_opt_cnt = it_buy_qty_min;
	}
	
	if(ea_opt_cnt > ea_stock){
		ea_stock = ea_stock-it_buy_qty_min;
		ea_stock = ( Math.floor(ea_stock/it_gp_cnt) * it_gp_cnt) + it_buy_qty_min;
		alert("해당옵션은 최대 "+ea_stock+"개 까지 구매가능합니다");
		ea_opt_cnt = ea_stock;
	}

	$(this).val(ea_opt_cnt);

	opt_total_price = opt_price*ea_opt_cnt;
	$("#opt_id_"+opt_id+" .opt_total_price").html(number_format(opt_total_price)+"원");

	opt_change(opt_id, ea_opt_cnt);	
});

function opt_change(opt_id, opt_cnt){

console.log('testestsetset');
	$.ajax({
		type: "post",
		url: "cart.php",
		data: {
			"mode":"opt_change",
			"opt_id": opt_id,
			"opt_cnt": opt_cnt
		},
		cache: false,
		async: false,
		success: function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){				
				cart_info(json);
			}
		}
	});

}



































$(document).on("click",".chk",function(){

	if(!$(this).is(":checked")){
		$("#sel_opt_del_btn").html("선택삭제");
	}

	$.ajax({
		type: "post",
		data : $("#fcart").serialize(),
		url: "cart.php",
		success: function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){				
				cart_info(json);
			}
		}
	});

});

function cart_info(json){

	console.log(json["item"]);
	
	if(json["item"]){

		$.each(json["item"], function(key, value){
			it_id = value["it_id"];
			item_price = parseInt(value["item_price"]);
			delivery_state = value["delivery_state"];

			$("#it_id_"+it_id+" .it_total_price").html(number_format(item_price)+"원");
			$("#it_id_"+it_id+" .it_delivery_price").html(delivery_state);

		});

	}

	var item_total_price = parseInt(json["item_total_price"]);
	var delivery_total_price = parseInt(json["delivery_total_price"]);
	var cart_total_price = parseInt(json["cart_total_price"]);

	$("#item_total_price").html(number_format(item_total_price));
	$("#delivery_total_price").html(number_format(delivery_total_price));
	$("#cart_total_price").html(number_format(cart_total_price));
	
}

$(document).on("click",".opt_chg_close_btn",function(){
	$(".opt_chg_bg").hide();
	$(".opt_chg_result").html("");
});

$(document).on("click",".opt_chg_btn",function(){
	var it_id = $(this).data("it_id");
	$.ajax({
		type: "post",
		url: "opt_popup.php",
		data: {
			"it_id": it_id
		},
		cache: false,
		async: false,
		success: function(response){
			$(".opt_chg_bg").show();
			$(".opt_chg_result").html(response);
		}
	});
});

$(document).on("click",".opt_chg_submit_btn",function(){

	$('#move').val("opt_popup");	
	$.ajax({ 
		type : "post"
		, url : "cart.php"
		, cache : false  
		, data : $("#item_frm").serialize()
		, success : function(response){			
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				document.location.reload();
			} else{
				alert(json["msg"]);
			}	
		} 
	});

});
//-->
</script>
<style>
/*장바구니cart */
.cart_wrap{ width:1200px;}



.cart_wrap .cart_inner{position:relative; height:130px;width:1200px;}
.cart_wrap .cart_inner .my_title{position:absolute;top:20px; left:0px; font-size:30px;}
.cart_wrap .cart_inner .my_title_sub{position:absolute;top:90px; left:0px;font-size:14px; color:#999;}
.cart_wrap .cart_inner .cartimg{display:inline-block;position:absolute; width:307px;top:70px;right:0px;}

.select_all_wrap{ width:100%; height:35px; position:relative; margin-bottom:10px; }
.select_all_wrap .select_sec{position:absolute;top:10px;left:0px}
#sel_opt_del_btn {position:absolute;top:0px;right:0px;height:26px;padding: 0 20px;border:1px solid #959da6;color: #5b6065; background-color:#FFFFFF;font-family:'Dotum';font-size: 11px;letter-spacing: -0.08em;line-height:26px;display:inline-block; cursor:pointer; }
#sel_opt_del_btn:hover {border:solid 1px #ff5000; color:#ff5000;}


.cart_wrap .cart_item_wrap{border:solid 1px #d6dadd;  padding:40px; background-color:#FFF; margin-bottom:15px;}
.cart_wrap .cart_item_wrap .it_name_wrap{ position:relative; width:100%; height:95px; padding-bottom:20px; border-bottom:solid 0px #DCDCDC; }

.cart_wrap .cart_item_wrap .it_name_wrap .chk_item { position:absolute; left:0px; top:35px; }
.it_name_wrap a { display:block; position:relative; margin-left:30px; box-sizing:border-box; -webkit-box-sizing:border-box; }
.cart_item_img { display:block; width:75px; height:75px; border:solid 1px #d6dadd;}

.cart_wrap .it_name { display:block; font-size:15px; position:absolute; left:85px; top:-10px; height:75px; display:flex;font-weight:bold;  }
.cart_wrap .it_name span { align-self:center; overflow:hidden; text-overflow:ellipsis; -webkit-line-clamp:4; display:-webkit-box; -webkit-box-orient:vertical;color:#000;}
.cart_wrap .it_description  {display:block; font-size:11px; position:absolute; left:85px; top:10px; height:75px; display:flex;  }
.cart_wrap .it_description span {align-self:center; overflow:hidden; text-overflow:ellipsis; -webkit-line-clamp:4; display:-webkit-box; -webkit-box-orient:vertical;color:#999; }
.cart_wrap .it_description a span {color:#333; }
.cart_wrap .opt_name_wrap { padding:15px 0px; } 
.cart_wrap .it_opt_wrap li { border-top:1px solid #dededb; padding-bottom:10px; }
.cart_wrap .opt_name { font-size:12px; line-height:25px;color:#666;}



.opt_count_price_wrap { position:relative; height:31px; width:100%; }
.opt_count_price_wrap .opt_count { display:inline-block; position:absolute; top:0px; left:0px; width:117px; } 
.opt_count_price_wrap .opt_total_price { position:absolute; top:0px; right:41px; font-size:16px; line-height:31px; }
.opt_count_price_wrap .opt_delete { position:absolute; top:0px; right:0px; cursor:pointer; padding:0px; width:31px; height:31px; background: url("/skin/m_demo/img/layout.png") no-repeat; background-size: 320px auto; background-position: -100px -450px; }

.ea_sum_box { border:solid 1px #e5e5e5; width:100%; }
.ea_sum_box b { display:block; background-color:#f4f4f4; border-bottom:solid 1px #e5e5e5; font-size:13px; padding:5px 10px; text-align:center; } 
.ea_sum_box1 { float:left; width:50%; text-align:center; line-height:25px;}
.ea_sum_box1 .it_total_price{ font-size:18px; line-height:55px; color:#ff6600; font-family:tahoma;}
.ea_sum_box2 { float:left; width:50%; text-align:center; margin-left:-1px; border-left:solid 1px #e5e5e5; line-height:25px;}
.ea_sum_box2 .it_delivery_price {font-size:18px; line-height:55px;}






.count_plus { cursor:pointer; display:block; padding:0px; border:solid 1px #c9c9c9; width:33px; height:29px; position:absolute; right:0px; top:0px; background:url(/skin/m_demo/img/layout.png) no-repeat;background-size: 320px auto; background-position: -0px -450px; }
.count_minus {cursor:pointer; display:block; padding:0px; border:solid 1px #c9c9c9; width:33px; height:29px; position:absolute; left:0px; top:0px; background:url(/skin/m_demo/img/layout.png) no-repeat;background-size: 320px auto;background-position: -50px -450px;}


.total_price_wrap { border-top:solid 1px #666;  border-bottom:solid 1px #DCDCDC; background-color:#fff; padding:10px;}
.total_price_wrap .total_price_left{float:left; width:50%;}
.total_price_wrap .total_price_left .total_title{font-size:20px; display:inline-block; margin-top:30px;font-family:'NSKM','Nanum Gothic','돋움', 'Dotum', 'AppleGothic', 'sans-serif'; }
.total_price_wrap .total_price_right{float:right; width:300px; padding:0px 0px;}
.total_price_wrap .total_price_right ul{width:100%;}
.total_price_wrap .total_price_right ul li {border-bottom:solid 1px #DCDCDC;padding:15px 0px;}
.total_price_wrap .total_price_right .tit{width:109px; display:inline-block; font-size:15px;}
#item_total_price { width:173px; display:inline-block;text-align:right;color:#4d5454; font-size:16px; font-weight:bold; }
#delivery_total_price { width:173px; display:inline-block;text-align:right;color:#4d5454; font-size:12px; font-family:arial; font-weight:bold; }
.t_price {color:#000;font-size:19px; font-family:tahoma;}
.last_price{border-top: solid 1px #efefef;padding:25px 0px;; overflow:hidden;}
.last_price .pricetit{float:left;font-size:25px; color:#ff6600;}
.last_price .ct_tot{float:right;  color:#fa0d3e; font-size:24px; padding:0px 10px 0px 0px }
#cart_total_price { color:#ff0000; font-size:36px;   font-weight:bold; font-family:tahoma; }
.cart_order_btn { float:left;display:block; width:267px; height:64px; line-height:64px; text-align:center; padding:0px;margin:20px 5px; font-size:16px; font-weight:bold; background-color:#ff6600; border:solid 1px #ff6600; color:#fff; border-radius:3px;}
.cart_order_btn:hover { background-color:#FFFFFF; border:solid 1px #ff6600; color:#ff6600; }
.cart_order_btn2 { background-color:#FFFFFF; float:left; display:block; width:267px; height:64px; line-height:64px; text-align:center; padding:0px; margin:0px; margin:20px 0px; font-size:16px; font-weight:bold; border:solid 1px #DCDCDC; color:#666; border-radius:3px; }

.cart_none{border:solid 1px #DCDCDC; height:350px; background-color:#FFFFFF; margin-bottom:50px;}
.cart_none .txt{display:block; font-size:25px; text-align:center; margin-top:150px;}
.cart_none .txt2{display:block; font-size:15px; text-align:center; color:#999;}



.opt_cnt { height:29px; width:115px; text-align:center;border:solid 1px #DCDCDC; }
.opt_chg_btn{display:block; width:75px; padding:3px 0 2px 0px;margin:5px auto 5px;border:solid 1px #9297a8; background-color:#9297a8;border-radius:3px;font-size:11px;letter-spacing:-1px;color:#FFF; text-align:center;}
.opt_chg_btn:hover{color:#FFF;}
.opt_chg_bg { position:fixed; left:0; top:0; width:100%; height:100%; z-index:1000; overflow-y:auto; display:none;  }
.opt_chg_box { position: absolute; margin:-250px 0 0 -250px; top:50%; left:50%; background-color:#fff; border:2px solid #555; width:500px; height:500px; z-index:1001; padding:20px; }

.opt_chg_close_wrap { text-align:right; }
.opt_chg_submit_wrap { text-align:center; }
.opt_title1{margin-top:30px; font-size:16px; color:#000;padding-left:5px;}
.opt_chg_result{margin-top:10px;}
.opt_chg_submit_btn{display: block; width:161px; height:46px; background: #383838; font-family:nskr; color: #fff; font-weight:normal;border:0;border-radius:3px; line-height:46px; margin:20px auto; font-size: 14px; text-align:center;}
.opt_chg_submit_btn:hover{color:#FFF}
.opt_chg_close_btn{ position: absolute;right:10px; top:10px;   background: url(/admin/img/closeicon.gif) no-repeat 50% 50%; text-indent: -9000px;width: 22px; height: 22px; opacity: 1;}
</style>







<div class="cart_wrap">


	<div class="cart_inner">
		<div class="my_title"><?=$nfor[title]?></div>
		<div class="my_title_sub fotl">결제하기 전 옵션 및 수량 등을 꼭 확인해주세요.</div>
		<div class="cartimg"><img src="<?=$nfor[skin_path]?>img/pay_pros_01_ov.png"><img src="<?=$nfor[skin_path]?>img/pay_pros_02.png"><img src="<?=$nfor[skin_path]?>img/pay_pros_03.png"></div>
	</div>


	<div style="color:red; font-size:12px;">
	환율에 의해 일부 상품은 결제 전 가격이 변동 될 수 있습니다.(제주도 도서산간인경우 +3000원 추가 됩니다)
	</div>

	<form method="post" id="fcart">
	<input type="hidden" name="mode" id="mode" value="checkbox">

	<div class="select_all_wrap">
		<div class="select_sec"><input type="checkbox" id="select_all" class="chk select_all" checked> <label for="select_all" > 전체선택</label></div>
		<a id="sel_opt_del_btn" > 전체삭제</a>	
	</div>

	<?
	if(sql_num_rows($result)){
	?>
	<ul class="cart_item_list">
	<?
	$n = 0;
	while($cart = sql_fetch_array($result)){
		$item = item($cart[ct_it_id]);
	?>
	<li class="cart_item_wrap" id="it_id_<?=$item[it_id]?>">
		 
		<div class="it_name_wrap">
			<input type="checkbox" class="chk chk_item" value="<?=$item[it_id]?>" checked>
			<a href="<?=$item[href]?>" class="cart_img">
				<img src="<?=$item[it_img]?thumbnail("$nfor[path]/data/list/$item[it_img]",77,77,0,1):"$nfor[path]/img/noimg_s.png"?>" class="cart_item_img">
				<div class="it_name"><span><?=$item[it_name]?></span></div>
				<div class="it_description"><span><?=$item[it_description]?></span></div>			
			</a>
			<a class="opt_chg_btn" data-it_id="<?=$item[it_id]?>">옵션변경</a>
		</div>

		<ul class="it_opt_wrap">
		<?
		$ct_que = sql_query("select * from nfor_cart where ct_cart_id='$ss_cart_id' and ct_it_id='$item[it_id]' order by ct_id desc");
		while($ct = sql_fetch_array($ct_que)){
			$ct_sum[$item[it_id]] += $ct[ct_sprice2];
			$opt_name = str_replace("||","/",$ct[ct_value]);

			$option = sql_fetch("select * from nfor_item_option where opt_id='$ct[ct_opt_id]'");
			if($item[it_stock_type]=="1"){ // 판매재고 무한정판매일경우
				$option[opt_stock] = "9999999";
			}
		?>
		<li id="opt_id_<?=$ct[ct_opt_id]?>">
			
			<input type="hidden" name="opt_id[]" class="opt_id" value="<?=$ct[ct_opt_id]?>">
			
			<div class="opt_name_wrap">
				<input type="checkbox" name="chk[]" value="<?=$n?>" id="chk_<?=$ct[ct_opt_id]?>" class="chk chk_item_<?=$item[it_id]?>" checked>
				<span class="opt_name"><?=$opt_name?></span>
			</div>

			<div class="opt_count_price_wrap">
				<p class="opt_count">
					<input type="number" pattern="[0-9]*" name="opt_cnt[]" value="<?=$ct[ct_opt_cnt]?>" class="opt_cnt" data-it_buy_qty_min="<?=$item[it_buy_qty_min]?>" data-it_buy_qty_max="<?=$item[it_buy_qty_max]?>" data-opt_id="<?=$ct[ct_opt_id]?>" data-price="<?=$ct[ct_price2]?>" data-stock="<?=$option[opt_stock]?>" data-it_opt_cnt="<?=$item[it_opt_cnt]?>" data-it_gp_cnt="<?=$item[it_gp_cnt]?>" data-it_buy_qty_type="<?=$item[it_buy_qty_type]?>" data-it_stock_type="<?=$item[it_stock_type]?>" data-it_id="<?=$item[it_id]?>" autofocus="autofocus">
					<a class="count_plus"></a>
					<a class="count_minus"></a>
				</p>
				<span class="opt_total_price"><?=number_format($ct[ct_sprice2])?>원</span>
				<a class="opt_delete" data-opt_id="<?=$ct[ct_opt_id]?>" data-opt_name="<?=$opt_name?>"></a>
			</div>

		</li>
		<? 
			$n++;
		} 		
		
		$ct_delivery = ea_delivery_price($ss_cart_id, $item[it_id],$ct_sum[$item[it_id]]);	
		?>
		</ul>

		<div class="ea_sum_box">

			<div class="ea_sum_box1">
				<b>상품합계</b>
				<span class="it_total_price"><?=number_format($ct_sum[$item[it_id]])?>원</span>
			</div>
			<div class="ea_sum_box2">
				<b>배송비</b>
				<span class="it_delivery_price"><?=$ct_delivery[price]?number_format($ct_delivery[price])."원":$ct_delivery[state]?></span>
			</div>
			<div style="clear:both;"></div>

		</div>
		
	</li>
	<? 
		$item_total_price += $ct_sum[$item[it_id]];
	}

	$delivery_total_price = delivery_total_price($ss_cart_id);
	$cart_total_price = $item_total_price + $delivery_total_price;
	?>
	</ul>
	<? } else{ ?>
	<div class="cart_none">
		<span class="txt">카트에 담긴 상품이 없습니다.</span>
		<span class="txt2">로그인을 하시면 카트에 보관된 상품을 확인하실 수 있습니다.</span>
	</div>
	<? } ?>
	</form>









	<div class="total_price_wrap">
			
		<div class="total_price_left">
			<span class="total_title">총 주문금액</span>
		</div>
		<div class="total_price_right">
			<ul>
				<li>
					<span class="tit fotr">총 상품금액</span>
					<span id="item_total_price" ><b class="t_price"><?=number_format($item_total_price)?></b> 원</span>
				</li>
				<li style="border-bottom:none;">
					<span class="tit fotr">총 배송비</span>
					<span id="delivery_total_price" ><b class="t_price"><?=number_format($delivery_total_price)?></b> 원</span>
				</li>
			</ul>
		</div>
		<div style="clear:both;"></div>

		<div class="last_price">
			<div class="pricetit">결제 예상금액</div>
			<div class="ct_tot"><span id="cart_total_price" ><?=number_format($cart_total_price)?></span> 원</div>
		</div>

	</div>

	<div style="margin:0px auto; width:550px; overflow:hidden;">
		<a href="index.php" class="cart_order_btn2">쇼핑계속하기</a> <a href="cart_order.php" class="cart_order_btn">구매하기</a>
	</div>

	<?php
	if($config[cf_naverpay_use]=="1"){
		include_once "naverpay_button.php";
	}
	?>

</div>

















<!-- 옵션변경레이어 -->
<div class="opt_chg_bg">

	<div class="opt_chg_box">
		
		<div class="opt_chg_close_wrap"><a class="opt_chg_close_btn"></a></div>
		<div class="opt_title1">옵션변경</div>
		<div class="opt_chg_result">결과값</div>		
		<div class="opt_chg_submit_wrap">
			<a class="opt_chg_submit_btn">변경</a>
			<a class="opt_chg_close_btn">취소</a>
		</div>

	</div>

</div>
<!-- //옵션변경레이어 -->




<?php
include_once $nfor[skin_path]."tail.php";
?>