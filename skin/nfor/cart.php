<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.hide { display:none; } 

.cart_wrap{min-height:100%;margin: 0;padding: 0; letter-spacing: -.06em;}
.cart_wrap .select_all_wrap{ position: relative; padding:15px; border-bottom: 1px solid #dfe2e6; background-color: #fff;}

.cart_wrap .cart_list{border-top: 1px solid #dfe2e6; border-bottom: 1px solid #dfe2e6;background-color: #fff; display: block;margin-top: 10px; padding: 0 15px 20px 47px;}
.cart_wrap .cart_list .pro_list .prod .chk_item{position: absolute; top: 15px; left: -32px;}
.cart_wrap .cart_list .pro_list .prod{position: relative; padding:15px 0 15px 102px; }
.cart_wrap .cart_list .pro_list .prod .it_info{position: relative; min-height: 59px;}
.prod .it_info .it_img_wrap {position: absolute; top: 0; left: -102px; width: 87px; height: 59px; background-repeat: no-repeat; background-position: 50% 0; background-size: 87px 59px;}
.prod .it_info .it_img_wrap:after {position: absolute; top: 0; left: 0; box-sizing: border-box; width: 100%; height: 100%; border: 1px solid rgba(221,221,221,.3); content: "";}
.prod .it_info .it_name { display: block; overflow: hidden;  padding: 3px 0;  font-weight: 700;  font-size: 13px;  color: #16181a;  letter-spacing: -.04em;  word-break: break-all;}
.cart_wrap .cart_list .pro_list .opt_id_wrap { padding:0px 0px 5px;}
.opt_id_wrap .op_name { font-size:12px; color:#656b73; letter-spacing:-.04em; display:inline; line-height:16px; word-break:break-all; display:block; padding-bottom:5px; }
.opt_id_wrap .info{position: relative;padding-right: 30px;}
.opt_id_wrap .info:after {display: block;clear: both; content: '';}
.opt_id_wrap .info .opt_total_price_wrap { float: right; height: 25px; color: #656b73; text-align: right; font-size: 12px; letter-spacing: -.06em; line-height:25px; }
.opt_id_wrap .info .opt_total_price_wrap .opt_total_price { font-size: 11px; letter-spacing: -.06em;;}
.opt_id_wrap .info .opt_count {display: inline-block; position: relative; top: 0px; left: 0px; width: 70px; height:24px;  border: 1px solid #b7bfc8;box-sizing:border-box; -webkit-box-sizing:border-box; }
.opt_id_wrap .info .opt_count input[type="number"] { color: #666; font-size: 14px; line-height: 20px; letter-spacing: -1px; margin-top:-2px; padding:0px;border:none; text-align: center;}
.opt_id_wrap .info .opt_count .opt_cnt {height: 20px; line-height: 22px; width: 65px; text-align: center;}
.opt_id_wrap .info .opt_count .count_plus {position: absolute; cursor: pointer; display: block; padding:0px; border :1px solid #b7bfc8; width:23px; height:22px; line-height:22px; right:-1px; top:-1px; background-color:#FFF; text-align:center; font-size:18px; color:#b7bfc8;}
.opt_id_wrap .info .opt_count .count_minus {position:absolute; cursor:pointer; display:block; padding:0px; border:1px solid #b7bfc8; width:23px; height:22px; line-height: 22px; left:-1px; top:-1px; background-color:#FFF; text-align:center; font-size:18px; color:#b7bfc8 }
.opt_delete {position: absolute;  top: 0px;right: 0px; cursor: pointer; padding: 0px; width: 22px; height: 22px;border:1px solid #b7bfc8;  background: url('<?=$nfor[skin_path]?>img/opt_del.png') center no-repeat; background-size: 10px auto; }

.prd_price { margin:10px 0px 0px; padding: 0;letter-spacing: -.06em; font-size: 12px;}
.prd_price{border-top:dashed 1px #b7bfc8}
.prd_price .price_info{ display:block; padding-top:9px; }
.prd_price .price_info li{margin-top:9px;}
.prd_price .price_info li:after { display: block; clear: both; content: '';}
.prd_price .price_info li .price_tit1 { float: left; clear: both;    font-weight: 700; line-height: 1; color: #656b73; letter-spacing: -.04em;;}
.prd_price .price_info  li .price_amt { float: right;text-align: right;  }
.prd_price .price_info  li .price_amt em { padding-right: 1px; font-weight: 700;font-size: 13.2px;   vertical-align: -1px;	font-style:normal;}
.prd_price .price_info li span{font-weight: 700; line-height: 1; color: #656b73; letter-spacing: -.04em; margin-top:.5px;}
.prd_price .price_info li .txt{font-weight:normal;  font-size: 10px; letter-spacing: -.04em;}
.prd_price .price_info .total span{ font-size: 14.5px; color: #e83862;}
.prd_price .price_info .total .price_amt em  {font-size: 16.5px; letter-spacing: -.02em; margin-top:-1px;}

.cart_wrap .total_price_zone{padding: 0 15px 25px 15px; margin-top:10px; border-top: 1px solid #dfe2e6; border-bottom: 1px solid #dfe2e6; background-color: #fff;}
.cart_wrap .total_price_zone .total_prd_price{margin-top: 15px;}
.cart_wrap .total_price_zone .total_prd_price .total{border-top:dashed 1px #dfe2e6; padding-top:10px;}
.cart_wrap .total_price_zone .total_prd_price li{margin-top:5px;}
.cart_wrap .total_price_zone .total_prd_price li span{color:#656b73}
.cart_wrap .total_price_zone .total_prd_price li:after { display: block; clear: both; content: '';}
.cart_wrap .total_price_zone .total_prd_price li .tit1{float: left; clear: both; font-weight: 700; line-height: 1; color: #656b73; letter-spacing: -.04em;}
.cart_wrap .total_price_zone .total_prd_price li .amt{ float: right;text-align: right;  }
.cart_wrap .total_price_zone .total_prd_price .amt em  {font-size: 16.5px; letter-spacing: -.02em; margin-top:-1px; font-style:normal; font-weight:800; }
.pink{color:#e83862!important; font-weight:800}
.cart_wrap .total_price_zone .op_txt_msg{margin-top: 25px; margin-bottom: 30px;  position: relative; padding-left: 0px; letter-spacing: -.04em;font-size: 11px;color: #7a838e;word-break: keep-all;}

.select_sec {  }
#sel_opt_del_btn { position:absolute; top:15px; right:10px; margin-top:-1px;padding: 8px 20px;  border: 1px solid #959da6; font-size: 11px; font-family: Dotum; line-height: 1;  color: #5b6065;  letter-spacing: -.08em;}

</style>


<form method="post" id="fcart" autocomplete="off">
<input type="hidden" name="mode" id="mode" value="checkbox">
<div class="cart_wrap">

	<div class="select_all_wrap">
		<div class="select_sec">
			<input type="checkbox" checked="checked" id="select_all" class="chk select_all">
			<label for="select_all">전체선택 ( 총 <strong class="cnt"><i class="num chk_count"><?=$return["count"]?></i> / <?=$return["count"]?></strong>개 )</label>
		</div>
		<a id="sel_opt_del_btn"> 전체삭제</a>	
	</div>

	<!-- 상품리스트 -->
	<div class="cart_item_list">
	<?php
	for($i=0; $i<count($return["list"]); $i++){
		$cart = $return["list"][$i];
	?>
	<div class="cart_list" id="it_id_<?=$cart[it_id]?>">
		<div class="pro_list">

			<div class="prod">
				<input type="checkbox" class="chk chk_item" value="<?=$cart[it_id]?>" checked="checked">
				<div class="it_info">
					<a href="item.php?it_id=<?=$cart[it_id]?>">
						<div class="it_img_wrap" style="background-image:url('<?=$cart[it_img]?>')"></div>
						<p class="it_name"><?=$cart[it_name]?></p>
					</a>
				</div>
			</div>

			<div class="it_opt_wrap">
			<?php
			for($k=0; $k<count($cart["reply"]); $k++){
				$ct = $cart["reply"][$k];
			?>
			<div id="opt_id_<?=$ct[ct_opt_id]?>" class="opt_id_wrap">
				<input type="hidden" name="opt_id[]" class="opt_id" value="<?=$ct[ct_opt_id]?>">
				<input type="checkbox" name="chk[]" value="<?=$ct[n]?>" id="chk_<?=$ct[ct_opt_id]?>" class="chk chk_item_<?=$ct[it_id]?> hide" checked="checked">
				<p class="op_name"><?=$ct[opt_name]?></p>
				<div class="info">
					<p class="opt_count">
						<a class="count_minus opt_cnt_btn">-</a>
						<input type="number" pattern="[0-9]*" name="opt_cnt[]" value="<?=$ct[ct_opt_cnt]?>" class="opt_cnt opt_cnt_btn" data-it_buy_qty_min="<?=$ct[it_buy_qty_min]?>" data-it_buy_qty_max="<?=$ct[it_buy_qty_max]?>" data-opt_id="<?=$ct[ct_opt_id]?>" data-price="<?=$ct[ct_price2]?>" data-stock="<?=$ct[opt_stock]?>" data-it_opt_cnt="<?=$ct[it_opt_cnt]?>" data-it_gp_cnt="<?=$ct[it_gp_cnt]?>" data-it_buy_qty_type="<?=$ct[it_buy_qty_type]?>" data-it_stock_type="<?=$ct[it_stock_type]?>" data-it_id="<?=$ct[it_id]?>">
						<a class="count_plus opt_cnt_btn">+</a>
					</p>
					<div class="opt_total_price_wrap"><span class="opt_total_price"><?=$ct[ct_sprice2]?></span>원</div>
					<a class="opt_delete" data-it_id="<?=$ct[it_id]?>" data-opt_id="<?=$ct[ct_opt_id]?>" data-opt_name="<?=$ct[opt_name]?>"></a>
				</div>
			</div>
			<?php }	?>
			</div>

			<div class="prd_price">
				<ul class="price_info">
					<li>
						<span class="price_tit1">상품금액</span>
						<span class="price_amt"><em class="it_item_price"><?=$cart[it_item_price]?></em>원</span>
					</li>			
					<li>
						<span class="price_tit1">배송비</span>	
						<span class="price_amt"><em class="it_delivery_price"><?=$cart[it_delivery_price]?></em></span>
					</li>				
					<li class="total">
						<span class="price_tit1">주문금액</span>
						<span class="price_amt"><em class="it_total_price"><?=$cart[it_total_price]?></em>원</span>
					</li>
				</ul>
			</div>

		</div>
	</div>
	<?php } ?>
	</div>
	<!-- //상품리스트 -->

	<!-- 총상품금액 -->
	<div class="total_price_zone">
		<div class="total_prd_price">
			<ul>
				<li>
					<span class="tit1">총 상품금액</span>
					<span class="amt"><em id="item_total_price"><?=$return[item_total_price]?></em>원</span>
				</li>
				<li>
					<span class="tit1">총 배송비</span>
					<span class="amt"><em id="delivery_total_price"><?=$return[delivery_total_price]?></em>원</span>
				</li>
				<li class="total">
					<span class="pink">결제 예상 금액</span>
					<span class="amt pink"><em id="cart_total_price"><?=$return[cart_total_price]?></em>원</span>
				</li>
			</ul>
		</div>
		<p class="op_txt_msg">카트에 담긴 상품은 최대 30일까지 보관되며 종료되거나 매진될 경우 자동으로 삭제됩니다</p>
		<div class="btn_zone">
			<span class="btwp"><a href="index.php" class="bt_continue">쇼핑 계속하기</a></span>
			<span class="btwp"><a href="cart_order.php"><button type="button" class="bt_submit">구매하기</button></a></span>
		</div>
	</div>
	<!-- //총상품금액 -->

</div>
</form>

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
include_once $nfor[skin_path]."tail.php";
?>