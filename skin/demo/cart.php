<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.cart .cart_item_list .cart_item_wrap .pro input[type='checkbox'].hide { display:none; }

.cart{position:relative; overflow:hidden; padding:0px 40px;}
.cart .cart_top{ margin-top:60px;  position:relative}
.cart .cart_top h3{ font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal; font-size:35px; margin:20px 0px;;}
.cart .cart_top h3 span{font-size:24px;color:#ff0000}
.cart .cart_top .sub_txt{font-size:14px; color:#666; line-height:20px;}
.cart .cart_top .pross{overflow:hidden; position:absolute; top:40px; right:0;  font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal; }
.cart .cart_top .pross li{float:left; padding:10px 10px;}
.cart .cart_top .pross li b{font-size: 18px;}
.cart .cart_top .pross li span{font-size: 18px;}
.cart .cart_top .pross .on{color:#ff0000}
.cart .select_all_wrap{margin:20px 0px; position:relative;}
.cart .select_all_wrap .select_sec{ font-size: 11px;font-family: Dotum; line-height: 1; color: #5b6065; letter-spacing: -.08em;}
.cart .select_all_wrap .select_sec .select_all{border:solid 1px #959da6; height:15px; width:15px; vertical-align:-3px;}
#sel_opt_del_btn {position:absolute; top:0px; right:0; margin-top:-1px;padding: 8px 20px;;  border: 1px solid #959da6; font-size: 11px; font-family: Dotum; line-height: 1;  color: #5b6065;  letter-spacing: -.08em;}
#sel_opt_del_btn:hover {border:solid 1px #ff5000; color:#ff5000;}

.cart .cart_item_list { border-top: 1px solid #d6dadd; }
.cart .cart_item_list .cart_item_wrap{ position: relative;width: 100%; background: #d6dadd;border-bottom: 1px solid #d6dadd; min-height:160px; }
.cart .cart_item_list .cart_item_wrap .pro{ position: relative;width: 600px;padding: 30px 30px 30px 204px; background-color: #fff; z-index: 100; border-top: 1px solid #d6dadd; min-height:100px;}
.cart .cart_item_list .cart_item_wrap .pro:first-child { border-top-width: 0;}
.cart .cart_item_list .cart_item_wrap .pro .chk_item {display: inline-block;overflow: hidden; position: absolute;top: 20px;  left: 15px; ;}
.cart .cart_item_list .cart_item_wrap .pro input[type='checkbox']{width: 15px; height: 15px; background-color:#FFF; border:solid 1px #DCDCD;}
.cart .cart_item_list .cart_item_wrap .pro .it_info{position: relative; padding: 7px 0 10px; margin-right: 5px; font-size: 14px;line-height: 20px; color: #16181a; font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;}
.cart .cart_item_list .cart_item_wrap .pro .it_info .it_description{display:block; margin-top:5px; font-size: 11px; color: #959da6; font-family: "돋움",dotum,"Apple SD Gothic Neo",sans-serif;}
.cart .cart_item_list .cart_item_wrap .pro .it_img_wrap {   overflow: hidden; position: absolute; top: 0; left: -150px;width: 120px; height: 80px;}
.cart .cart_item_list .cart_item_wrap .pro .it_img_wrap img {width:100%; height:auto;}
.opt_chg_btn { position:absolute; top:90px; left: -150px; display:block; overflow: visible; padding: 8px 40px;border: 1px solid #959da6; font-size: 11px; font-family: Dotum;  line-height: 1; color: #5b6065; letter-spacing: -.08em;}

.cart .it_opt_wrap{position: relative;}
.cart .it_opt_wrap .opt_id_wrap{padding: 5px; border-bottom:solid 1px #efefef; position: relative;}
.cart .it_opt_wrap .opt_id_wrap input[type='checkbox']{vertical-align:-3px;}
.cart .it_opt_wrap .opt_id_wrap .opt_name{display: inline-block; width: 330px; margin-top: 5px;  margin-right: 20px;  font-size: 12px; line-height: 1.5;color: #5b6065;letter-spacing: -.02em; vertical-align: top; word-wrap: break-word;}
.cart .it_opt_wrap .opt_id_wrap .opt_count {display: inline-block; width: 95px; vertical-align: middle; overflow: hidden;}
.cart .it_opt_wrap .opt_id_wrap .opt_count .opt_cnt{display: inline-block;float: left;width: 32px; height: 24px; border: 1px solid #c2c7cc;  border-right: 0 none; border-left: 0 none;  line-height: 24px; color: #000; text-align: center; vertical-align: top;}
.count_plus {cursor:pointer; float: left;width: 24px; height: 24px; border: 1px solid #c2c7cc; vertical-align: top; background:url(<?=$nfor[skin_path]?>img/pl_mi.png) no-repeat; background-position: -24px -0px; }
.count_minus {cursor:pointer; float: left;width: 24px; height: 24px; border: 1px solid #c2c7cc; vertical-align: top; background:url(<?=$nfor[skin_path]?>img/pl_mi.png) no-repeat;background-position: -0px -0px;}
.cart .it_opt_wrap .opt_id_wrap .opt_total_price_wrap { display: inline-block; width: 80px;font-size: 12px;text-align: right; vertical-align: middle; }
.cart .it_opt_wrap .opt_id_wrap .opt_delete { position:absolute; top:5px; right:5px; cursor:pointer; padding:0px; width:26px; height:26px; background: url("<?=$nfor[skin_path]?>img/cart_del.png") no-repeat;}

.cart .it_item_price_wrap{display: -ms-flexbox;display: flex; background: #fff; -ms-flex-align: center;  align-items: center; -ms-flex-pack: center;justify-content: center; right: 132px; width: 153px;position: absolute; top: 0; bottom: 0;height: 100%; min-height: 100%;}
.cart .it_delivery_price_wrap{ right: 0; width: 131px;    position: absolute; top: 0;  bottom: 0; height: 100%; min-height: 100%; display: -ms-flexbox; display: flex; background: #fff;-ms-flex-align: center;  align-items: center;-ms-flex-pack: center; justify-content: center;}
.cart .it_item_price_wrap .it_item_price{display: inline-block;font-family: Tahoma; font-weight: 700;font-size: 14px;color: #16181a}
.cart .it_delivery_price_wrap .it_delivery_price {font-size:14px; line-height:55px;}

.cart .cart_none{border:solid 1px #efefef; padding:180px 30px 0px;; height:230px; text-align:center}
.cart .cart_none .txt{display:block; font-size:24px;  font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal; margin-bottom:20px; letter-spacing:-1px;}
.cart .cart_none .txt2{color:#6c7580;letter-spacing:-1px;}

.cart .total_price_wrap { border-top:solid 1px #666;  border-bottom:solid 1px #DCDCDC; background-color:#fff; padding:10px;}
.cart .total_price_wrap .total_price_left{float:left; width:50%;}
.cart .total_price_wrap .total_price_left .total_title{font-size:25px; display:inline-block; margin-top:30px;font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;}
.cart .total_price_wrap .total_price_right{float:right; width:300px; padding:0px 0px;}
.cart .total_price_wrap .total_price_right ul{width:100%;}
.cart .total_price_wrap .total_price_right ul li {border-bottom:solid 1px #DCDCDC;padding:15px 0px;}
.cart .total_price_wrap .total_price_right .tit{width:109px; display:inline-block; font-size:15px;}

.cart .total_price_wrap .total_price_right ul li:last-child { border-bottom:none; }

#item_total_price { width:150px; display:inline-block;text-align:right;color:#4d5454; font-size:16px; font-weight:bold; color:#000;font-size:19px; font-family:tahoma;}
#delivery_total_price { width:150px; display:inline-block;text-align:right;color:#4d5454; font-size:12px; font-family:arial; font-weight:bold;  color:#000;font-size:19px; font-family:tahoma;}

.cart .last_price{border-top: solid 1px #efefef;padding:25px 0px;; overflow:hidden;}
.cart .last_price .pricetit{float:left;font-size:25px; color:#e61b62; font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;}
.cart .last_price .ct_tot{float:right;  color:#e61b62; font-size:24px; padding:0px 10px 0px 0px }
#cart_total_price { color:#e61b62; font-size:36px;   font-weight:bold; font-family:tahoma; }

.btn_wrap{padding: 107px 0 80px;  position: relative;width: 100%;font-size: 24px; text-align: center;}
.btn_wrap .shop_ing_btn { display:inline-block; width: 276px; height: 64px;  line-height: 64px;  border:solid 1px #e61b62; color:#e61b62; font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal; }
.btn_wrap .cart_order_btn {display:inline-block;; width: 276px; height: 64px; line-height: 64px; margin-left: 8px; background-color:#e61b62;  border:solid 1px #e61b62; color:#FFF; font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;}
.btn_wrap .cart_order_btn:hover{color:#FFF;}
</style>

<div class="cart">

	<div class="cart_top">
		<h3 class=""><?=$nfor[title]?><span>(<?=$return["count"]?>)</span></h3> 
		<div class="sub_txt">결제하기 전 옵션 및 수량 등을 꼭 확인해주세요.</div>
		<div class="pross">
			<ul>
				<li><b class="on">01</b> <span class="on">장바구니</span></li>
				<li class="on">></li>
				<li><b>02</b> <span>주문 /결제</span></li>
				<li>></li>
				<li><b>03</b> <span>결제완료</span></li>
			</ul>	
		</div>
	</div>
	
	<form method="post" id="fcart" autocomplete="off">
	<input type="hidden" name="mode" id="mode" value="checkbox">

	<div class="select_all_wrap">
		<div class="select_sec">
			<input type="checkbox" checked="checked" id="select_all" class="chk select_all"> 
			<label for="select_all">전체선택 ( 총 <strong class="cnt"><i class="num chk_count"><?=$return["count"]?></i> / <?=$return["count"]?></strong>개 )</label>
		</div>
		<a id="sel_opt_del_btn"> 전체삭제</a>	
	</div>

	<?php
	if($return["count"] > 0){
	?>
	<div class="cart_item_list">
		<?php
		for($i=0; $i<count($return["list"]); $i++){
			$cart = $return["list"][$i];
		?>
		<div class="cart_item_wrap" id="it_id_<?=$cart[it_id]?>">
			<div class="pro">
				<input type="checkbox" class="chk chk_item" value="<?=$cart[it_id]?>" checked="checked">
				<div class="it_info">
					<a href="item.php?it_id=<?=$cart[it_id]?>">
						<span class="it_name"><?=$cart[it_name]?></span>
						<span class="it_description"><?=$cart[it_description]?></span>
						<div class="it_img_wrap"><img src="<?=$cart[it_img]?>" class="it_img"></div>
						<a class="opt_chg_btn" data-it_id="<?=$cart[it_id]?>" data-it_img="<?=$cart[it_img]?>" data-it_name="<?=$cart[it_name]?>" data-it_description="<?=$cart[it_description]?>">옵션변경</a>
					</a>
				</div>
				<div class="it_opt_wrap">
					<?php
					for($k=0; $k<count($cart["reply"]); $k++){
						$ct = $cart["reply"][$k];
					?>
					<div id="opt_id_<?=$ct[ct_opt_id]?>" class="opt_id_wrap">
						<input type="hidden" name="opt_id[]" class="opt_id" value="<?=$ct[ct_opt_id]?>">
						<input type="checkbox" name="chk[]" value="<?=$ct[n]?>" id="chk_<?=$ct[ct_opt_id]?>" class="chk chk_item_<?=$ct[it_id]?> hide" checked="checked">
						<span class="opt_name"><?=$ct[opt_name]?></span>						
						<p class="opt_count">
							<a class="count_minus opt_cnt_btn"></a>
							<input type="number" pattern="[0-9]*" name="opt_cnt[]" value="<?=$ct[ct_opt_cnt]?>" class="opt_cnt opt_cnt_btn" data-it_buy_qty_min="<?=$ct[it_buy_qty_min]?>" data-it_buy_qty_max="<?=$ct[it_buy_qty_max]?>" data-opt_id="<?=$ct[ct_opt_id]?>" data-price="<?=$ct[ct_price2]?>" data-stock="<?=$ct[opt_stock]?>" data-it_opt_cnt="<?=$ct[it_opt_cnt]?>" data-it_gp_cnt="<?=$ct[it_gp_cnt]?>" data-it_buy_qty_type="<?=$ct[it_buy_qty_type]?>" data-it_stock_type="<?=$ct[it_stock_type]?>" data-it_id="<?=$ct[it_id]?>">
							<a class="count_plus opt_cnt_btn"></a>
						</p>
						<div class="opt_total_price_wrap"><span class="opt_total_price"><?=$ct[ct_sprice2]?></span>원</div>
						<a class="opt_delete" data-it_id="<?=$ct[it_id]?>" data-opt_id="<?=$ct[ct_opt_id]?>" data-opt_name="<?=$ct[opt_name]?>"></a>
					</div>
					<?php } ?>
				</div>
			</div>
			<div class="it_item_price_wrap"><em class="it_item_price"><?=$cart[it_item_price]?></em>원</div>
			<div class="it_delivery_price_wrap"><em class="it_delivery_price"><?=$cart[it_delivery_price]?></em></div>
		</div>
		<?php }	?>
	</div>
	<?php } else{ ?>
	<div class="cart_none">
		<span class="txt">카트에 담긴 상품이 없습니다.</span>
		<span class="txt2">로그인을 하시면 카트에 보관된 상품을 확인하실 수 있습니다.</span>
	</div>
	<?php } ?>
	</form>

	<div class="total_price_wrap">
		<div class="total_price_left">
			<span class="total_title">총 주문금액</span>
		</div>
		<div class="total_price_right">
			<ul>
				<li>
					<span class="tit">총 상품금액</span>
					<em id="item_total_price"><?=$return[item_total_price]?></em>원
				</li>
				<li>
					<span class="tit">총 배송비</span>
					<em id="delivery_total_price"><?=$return[delivery_total_price]?></em>원
				</li>
			</ul>
		</div>
		<div style="clear:both;"></div>
		<div class="last_price">
			<div class="pricetit">결제 예상금액</div>
			<div class="ct_tot"><em id="cart_total_price"><?=$return[cart_total_price]?></em> 원</div>
		</div>
	</div>

	<div class="btn_wrap">
		<a href="index.php" class="shop_ing_btn">쇼핑계속하기</a> <a href="cart_order.php" class="cart_order_btn">구매하기</a>
	</div>




	<?php
	if($REMOTE_ADDR	== "119.196.166.38" and $config[cf_naverpay_use]=="1"){
		include_once "naverpay_button.php";
	}
	?>

</div>


<script>
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
	var it_id = $(this).data("it_id");
	var opt_id = $(this).data("opt_id");
	var opt_name = $(this).data("opt_name");
	if(confirm("선택하신 옵션을 삭제하시겠습니까?\n"+opt_name)){
		$.post("cart.php",{ "mode":"opt_delete", "it_id":it_id, "opt_id":opt_id }, function(response){
			var json = $.parseJSON(response);
			if(json["result"]=="ok"){
				document.location.reload();
			} else{
				alert("상품 삭제에 실패하였습니다");
			}
		});
	}
});

// 체크박스 상태변경
$(document).on("click",".chk",function(){
	if(!$(this).is(":checked")){
		$("#sel_opt_del_btn").html("선택삭제");
	}
	$(".chk_count").html($(".chk_item:checked").length);
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

// 옵션수량변경
$(document).on("click blur change", ".cart_item_list .opt_cnt_btn", function(){
	if($(this).hasClass("opt_cnt")){ // 직접변경
		var opt_obj = $(this);
	} else{
		var opt_obj = $(this).parent().find(".opt_cnt");
	}
	var it_buy_qty_min = parseInt(opt_obj.data("it_buy_qty_min")); // 최소구매수량
	var it_buy_qty_max = parseInt(opt_obj.data("it_buy_qty_max")); // 최대구매수량
	var it_buy_qty_type = opt_obj.data("it_buy_qty_type"); // 1제한없음 2구매제한
	var it_stock_type = opt_obj.data("it_stock_type"); // 1무한정판매 2재고량에따름
	var it_gp_cnt = parseInt(opt_obj.data("it_gp_cnt")); // 몇개씩 증가
	var it_opt_cnt = parseInt(opt_obj.data("it_opt_cnt")); // 기본옵션갯수
	var ea_opt_cnt = parseInt(opt_obj.val()); // 수량
	var ea_stock = parseInt(opt_obj.data('stock')); // 재고수량
	var opt_id = opt_obj.data("opt_id"); // 옵션아이디
	var opt_price = opt_obj.data("price"); // 판매가격
	var it_id = opt_obj.data("it_id"); // 상품코드
	if($(this).hasClass("count_plus")){ // 더하기
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
	}
	if($(this).hasClass("count_minus")){ // 빼기
		ea_opt_cnt = ea_opt_cnt-it_gp_cnt;
		if(ea_opt_cnt<it_buy_qty_min){
			ea_opt_cnt = it_buy_qty_min;
		}
	}
	if($(this).hasClass("opt_cnt")){ // 직접변경
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
	}
	opt_obj.val(ea_opt_cnt);
	opt_total_price = opt_price*ea_opt_cnt;
	$("#opt_id_"+opt_id+" .opt_total_price").html(number_format(opt_total_price));
	$.ajax({
		type: "post",
		url: "cart.php",
		data: {
			"mode":"opt_change",
			"opt_id": opt_id,
			"opt_cnt": ea_opt_cnt
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
});

// 전송방지
$(document).on("submit", "#fcart", function(){
   event.preventDefault();
});

// 카트 업데이트
function cart_info(json){
	if(json["item"]){
		$.each(json["item"], function(key, value){
			it_id = value["it_id"];
			$("#it_id_"+it_id+" .it_item_price").html(value["item_price"]);
			$("#it_id_"+it_id+" .it_delivery_price").html(value["delivery_text"]);
			$("#it_id_"+it_id+" .it_total_price").html(value["total_price"]);
		});
	}
	$("#item_total_price").html(json["item_total_price"]);
	$("#delivery_total_price").html(json["delivery_total_price"]);
	$("#cart_total_price").html(json["cart_total_price"]);
}
</script>

<?php
include_once $nfor[skin_path]."inc_option_layer.php";
?>

<?php
include_once $nfor[skin_path]."tail.php";
?>