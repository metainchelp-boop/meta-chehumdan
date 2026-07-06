<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.hide { display:none; }
#payment_vbanking { display:none; }
#payment_hp { display:none; }
#payment_iche { display:none; }
#payment_banking { display:none; }

/*
.cart_wrap > prod_top > prod_title > info_long > info txt count
.cart_wrap > prod_title > btn_toggle > i
*/
.cart_wrap{padding:0px;}
.cart_wrap .prod_top{border-bottom: 1px solid #dfe2e6; background: #fff;border-bottom: 1px solid #dfe2e6; background: #fff;}
.cart_wrap .prod_top .prod_title{position: relative;height: 60px; padding: 0 43px 0 15px; text-align: right;  vertical-align: baseline;}
.cart_wrap .prod_top .prod_title:after { display: block; clear: both; content: '';}
.cart_wrap .prod_title .h_tit {float: left; margin-top: 21px; font-weight: 700; font-size: 18px; line-height: 18px; color: #16181a; letter-spacing:-0.0625em;}
.cart_wrap .prod_title .info_long{float: right; margin-top: 21px;}
.cart_wrap .prod_title .info_long .info {padding-right: 4px;}
.cart_wrap .prod_title .info_long .txt { overflow: hidden;  width: 65%; letter-spacing: -.06em;white-space: nowrap; text-overflow: ellipsis; display: inline-block; padding-right: 1px; color: #959da6; text-align: right;}
.cart_wrap .prod_title .info_long span.count {display: inline-block; color: #959da6;  vertical-align: top;}


.cart_wrap .prod_title .btn_toggle {display: block;position: absolute;  top: 0; right: 0; width: 38px;  height: 61px;overflow: hidden;  border: none; background-color: transparent; text-align: center; cursor: pointer;}
.btn_toggle i { width: 13px;  height: 7px;  background: url(../im/checkout/sp-checkout.png?v=20180810173818) no-repeat;   background-position: -237px -175px; background-size: 322px 316px;}

/*
.cart_wrap > prod_list > wr > op_area
.cart_wrap > prod_list > wr > op_result 
..cart_wrap > prod_list > wr > price_ls
*/
.prod_list{margin-top: -3px;border-top: 1px solid #dfe2e6; background: #f1f1f1;}
.prod_list .wr {margin-top:5px;}
.prod_list .wr>ul {background: #fff;}
.prod_list .wr>ul>li {padding-top: 15px;border-top: 1px solid #dfe2e6;}
.prod_list .wr>ul>li:first-child{border-top: 0;}
.prod_list .wr>ul>li a {display: block;position: relative;padding-left: 120px; min-height:50px;}
.prod_list .wr>ul>li a .thum { position: absolute; top: 0; left: 15px;; display:block;}
.prod_list .wr>ul>li a .info {font-size:14px; line-height: 16px; color: #16181a;}
.prod_list .wr .op_area {margin-top: 14px; padding-left: 0px;}
.prod_list .wr .op_area li {padding: 5px 20px; ; border-top: 1px dashed #dfe2e6; letter-spacing: -.06em;}
.prod_list .wr .op_area li:first-child{border-top: 0;}
.prod_list .wr .op_area li .op_name { padding-top: 4px;color: #959da6; font-size:12px;}
.prod_list .wr .op_result { height: 36px; font-size:12px;}
.prod_list .wr .op_result :after {display: block;clear: both;content: '';}
.prod_list .wr .op_result .count{float:left;margin-top: 10px; color: #959da6;}
.prod_list .wr .op_result .price {display: inline-block; float: right; height: 36px; margin-top: 10px; text-align: right;}
.prod_list .wr .price_ls { position: relative; padding: 20px 15px 19px;  background: #f8f8f8; border-bottom:solid 1px #DCDCDC;}
.prod_list .wr .price_ls>ul>li {border-top:none; padding:3px 0px; }
.prod_list .wr .price_ls>ul>li:after{display: block;clear: both;content: '';}
.prod_list .wr .price_ls>ul>li span:first-child {float: left;  color: #6c7580; font-size:13px;}
.prod_list .wr .price_ls>ul>li span:last-child {float: right; color: #6c7580; font-size:13px;}
.prod_list .wr .price_ls .txt_point{color: #f27935!important;} 

.delivery {position: relative; margin-top: 10px; border-bottom: 1px solid #dfe2e6;background: #fff;}
.delivery .delivery_title{height: 60px; padding: 0 15px; text-align: right;  vertical-align: baseline;}
.delivery .delivery_title:after { display: block;  clear: both; content: '';}
.delivery .delivery_title .h_tit {float: left; margin-top: 21px; font-weight: 700; font-size: 18px; line-height: 18px; color: #16181a;}
.delivery .delivery_title .delivery_change{float: right ;margin-top: 14px;display: inline-block; box-sizing: border-box;width: 48px; height: 38px;  border: 1px solid #b7bfc8; background-color: transparent;text-align: center;cursor: pointer; font-size: 16px;color: #6c7580;     height: 28px; font-size: 12px; color: #959da6;}
.delivery .delivery_cont { margin-bottom:10px; } 
.delivery .delivery_cont .address_zone { padding: 0 70px 0 15px;line-height: 20px; color: #16181a; font-size:14px;}

.common_order{position: relative; margin-top: 10px; border-bottom: 1px solid #dfe2e6;background: #fff;}
.common_order .corder_title{height: 60px;     padding: 0 15px; text-align: right;  vertical-align: baseline;}
.common_order .corder_title:after { display: block;  clear: both; content: '';}
.common_order .corder_title .h_tit {float: left; margin-top: 21px; font-weight: 700; font-size: 18px; line-height: 18px; color: #16181a; letter-spacing:-0.0625em;}
.common_order .coupon_cont { display: block; padding-bottom: 15px;} 
.common_order .coupon_cont .coupon_wr{ position: relative; padding: 0 15px 0 15px;line-height: 20px; color: #16181a; font-size:14px;}
.common_order .coupon_cont .coupon_wr .coupon_price_wrap { position: absolute;right: 20px;top: 15px; font-size: 14px; color: #666;}
#coupon_change{display:block;margin-top: 14px;display: inline-block; box-sizing: border-box;width: 80px; height: 38px;  border: 1px solid #b7bfc8; background-color: transparent;text-align: center;cursor: pointer; font-size: 16px;color: #6c7580;  height: 24px; font-size: 12px; color: #959da6;}

.common_order .coupon_wr p{color: #16181a; letter-spacing:-0.06em; margin-bottom:5px;}
.common_order .coupon_wr p em{color:#e83862; font-style:normal}
.common_order .coupon_wr div.cash { position: relative; box-sizing: border-box; width: 100%; color: #16181a; margin:0px;}
.common_order .coupon_wr .use { position: relative;margin-top: 13px;padding-right: 86px;}
.common_order .coupon_wr .cash input { box-sizing: border-box;width: 100%; height: 38px; padding-right: 23px;border: 1px solid #d0d5d9; font-weight: 600;font-size: 15px; text-align: right;}
.common_order .coupon_wr  span.won { display: inline-block;  position: absolute; top: 50%;margin-top:-10px; right: 8px;}
.common_order .coupon_wr .all_money_btn{ display: inline-block;box-sizing: border-box;position: absolute; top: 0;right: 0; width: 82px; font-size: 13px; color: #6c7580;line-height: 38px; height: 38px;border: 1px solid #b7bfc8; font-size: 16px;text-align:center;}

.common_order .coupon_cont .total_price {padding: 20px 15px 0;border-top: 1px solid #edeff0;}
.common_order .coupon_cont .total_price ul li {margin-top: 9px;color: #16181a; font-size:0.9em; letter-spacing:-0.06em;}
.common_order .coupon_cont .total_price ul li:after {display: block; clear: both;content: '';}
.common_order .coupon_cont .total_priceul li span:first-child {float: left;}
.common_order .coupon_cont .total_price ul li span:last-child {float: right;}

.common_order .corder_title .total {display: table; float: right; height: 60px; line-height: 60px; text-align: right;}
.common_order .corder_title .total .in{display: table-cell;vertical-align: middle;}
.common_order .corder_title .total .in .price{ color:#e83862; display: block; font-size: 18px; }
.common_order .corder_title .total .in .price em{font-style:normal; margin-top:-1px}
.common_order .coupon_cont .payment_type_menu{overflow:hidden; padding: 0px 15px;}
.common_order .coupon_cont .payment_type_menu li{float:left; width:50%; padding-top:10px; letter-spacing:-0.0652em; font-size:12px;}
#payment_card { width:100%;}
.common_order .coupon_cont .payment_wrap{ padding:15px;box-sizing:border-box; -webkit-box-sizing:border-box;}
.common_order .coupon_cont .card_menu { margin:0px; padding:0px; list-style:none; margin-bottom:10px; }
.common_order .coupon_cont .card_menu li { margin-bottom:10px;   text-align:center;  }
.common_order .coupon_cont .memo {color: #959da6; font-size: 12px;letter-spacing:-.0625em;}
.common_order .coupon_cont .tit{padding: 5px  0px; display:block;}
.common_order .coupon_cont .receipt_area {padding: 0px 15px 15px;;box-sizing:border-box; -webkit-box-sizing:border-box; margin-top:15px;}
.common_order .coupon_cont .inner {padding-top: 15px;border-top: 1px solid #edeff0;}
.common_order .coupon_cont .inner>h3 {font-size: 15px;color: #16181a;padding-bottom:15px;}
.common_order .coupon_cont .agree_wr {padding: 0px 15px 15px;;box-sizing:border-box; -webkit-box-sizing:border-box; margin-top:15px; }
.common_order .coupon_cont .agree_wr .agree{position: relative;letter-spacing:-0.0652em; font-size:12px;padding-bottom:5px;  line-height:20px; }
.common_order .coupon_cont .agree_wr .agree label{letter-spacing:-0.0652em;}
.popbtn{font-size: 12px; color: #959da6; display:inline-block; height:20px; line-height:20px;position:absolute; top: 3px;right: 0;} 
.common_order .coupon_cont .btn_w { margin-top: 8px;padding: 0 15px 15px;}
.popbtn:after {display: inline-block;width: 0; height: 0; margin: -3px 0 0 5px;border-width: 3px; border-style: solid;border-color: transparent transparent transparent #959da6; vertical-align: middle; content: "";}
#payment_btn { display:block; height:40px; line-height:40px; text-align:center; padding:0px; margin:10px 0px; font-size:16px; font-weight:bold; background-color:#e83862; border:solid 1px #e83862; color:#fff; cursor:pointer}


.od_cashreceipt_type { margin-bottom:10px; }
.od_cashreceipt_method_wrap { margin-top:10px; }

#od_refund_account { margin:10px 0px; }
#dy_msg { height:60px; margin-top:10px; }

.dy_msg_wrap { padding:0px 15px 15px; }
</style> 




<form name="fcart_order" id="fcart_order" method="post">
<input type="hidden" name="mode" value="insert">
<input type="hidden" name="ss_cart_id_new" value="<?=$ss_cart_id_new?>">
<div class="cart_wrap">


	<!-- 주문상품 -->
	<div class="prod_top">
		<div class="prod_title">
			<h2 class="h_tit">주문상품</h2>
			<div class="info_long"><p class="info txt">[펫스미스] 블랙라벨(4종)</p><span class="count"> 포함 3건</span></div> 
			<button class="btn_toggle"><i><span class="blind">열기/닫기</span></i></button>
		</div>
	</div>
	<!-- //주문상품 -->


	<!-- 주문리스트 -->
	<div class="prod_list">
		<div class="wr">
			<ul>
				<?
				$n = 0;
				$cart_que = sql_query("select * from nfor_cart where ct_cart_id='$ss_cart_id' and ct_opt_chk='1' group by ct_it_id order by ct_id desc");
				while($cart = sql_fetch_array($cart_que)){
					$item = item($cart[ct_it_id]);	
				?>
				<li>
					<a href="<?=$item[href]?>">
					<span class="thum"><img src="<?=$item[it_img]?thumbnail("$nfor[path]/data/list/$item[it_img]",86,58,0,1):"$nfor[skin_path]img/nomimg.png"?>"></span>
					<span class="info"><?=$item[it_name]?></span>
					</a>
					<div class="op_area">
						<ul class="op_ls">
							<?
							$ct_que = sql_query("select * from nfor_cart where ct_cart_id='$ss_cart_id' and ct_it_id='$item[it_id]' order by ct_id desc");
							while($ct = sql_fetch_array($ct_que)){
								$ct_sum[$item[it_id]] += $ct[ct_sprice2];
								$opt_name = str_replace("||","/",$ct[ct_value]);
							?>
							<li id="opt_id_<?=$ct[ct_opt_id]?>">
								<p class="op_name"><?=$opt_name?></p>
								<div class="op_result">
									<div class="count">수량 <?=number_format($ct[ct_opt_cnt])?>개</div>
									<div class="price"><?=number_format($ct[ct_price2]*$ct[ct_opt_cnt])?>원</div>
								</div>
							</li>
							<? 
								$n++;
							} 		
							
							$ct_delivery = ea_delivery_price($ss_cart_id, $item[it_id],$ct_sum[$item[it_id]]);	
							?>
						</ul>
						<div class="price_ls">
							<ul>
								<li>
									<span>상품금액</span>
									<span><?=number_format($ct_sum[$item[it_id]])?>원</span>
								</li>
								<li>
									<span>배송비</span>
									<span><?=$ct_delivery[price]?number_format($ct_delivery[price])."원":$ct_delivery[state]?></span>
								</li>
								<li>
									<span>할인금액</span>
									<span>0원</span>
								</li>
								<li>
									<span class="txt_point">결제금액</span>
									<span class="txt_point"><?=number_format($ct_sum[$item[it_id]]+$ct_delivery[price])?>원</span>
								</li>
							</ul>
						</div>
					</div>
				</li>
				<? 
					$item_total_price += $ct_sum[$cart[ct_it_id]];
				}

				$delivery_total_price = delivery_total_price($ss_cart_id);
				$total_price = $item_total_price + $delivery_total_price;
				?>
			</ul>
		</div>
	</div>
	<!-- //주문리스트 -->


	<!-- 배송지 -->
	<div class="delivery">

		<div class="delivery_title">
			<h2 class="h_tit">배송정보</h2>
			<button class="delivery_change">변경</button>
		</div>



		<style>
		.address_all_wrap { padding:10px; }

		.address_tab { border:1px solid #d0d5d9; margin-bottom:10px; }
		.address_tab li:first-child { border-left:0; }
		.address_tab li { float:left; position:relative; box-sizing:border-box; width:50%; border-left:1px solid #d0d5d9; }
		.address_tab:after { display:block; clear:both; content:''; }
		.address_tab li.on a { color:#f27935; }
		.address_tab li.on:before { width:100%; height:45px; border:1px solid #f27935; position:absolute; top:-1px; left:-1px; content:''; display:block; z-index:10; }
		.address_tab li a { width:100%; height:45px; line-height:45px; display:block; font-size:14px; color:#959da6; text-align:center; }

		#my_name { margin:10px 0px; }
		.my_zip_wrap { margin:10px 0px; position:relative; height:40px; }
		 #zipcode_btn  { cursor:pointer; position:absolute; right:0px; top:0px; height:38px; display:block; width:110px; text-align:center; line-height:40px; border:solid 1px #ccc; background:-webkit-gradient(linear,left top,left bottom,from(#fff),to(#ecebf0)); box-shadow:none; }

		#my_addr2 { margin:10px 0px; }

		.my_sel { position:absolute; left:0px; top:0px; }
		.my_sel_label { position:absolute; left:34px; top:0px; width:75%; }
		.myaddress_edit { position:absolute; right:0px; top:0px; display:inline-block; box-sizing:border-box; width:48px; height:28px; border:1px solid #b7bfc8; background-color:transparent; text-align:center; cursor:pointer; font-size:12px; color:#959da6; }
		.myaddress_del { position:absolute; right:0px; top:32px; display:inline-block; box-sizing:border-box; width:48px; height:28px; border:1px solid #b7bfc8; background-color:transparent; text-align:center; cursor:pointer; font-size:12px; color:#959da6; }

		.address_list li { border-bottom:1px solid #edeff0; padding:10px 0px; }
		.address_list li .address_li_div { position:relative; height:80px; }
		</style>

		<script>
		$(document).on("click",".address_tab li",function(){

			var tab_number = $(this).data("tab_number");

			if(tab_number=="1" && $(".address_list li").length == 0){
				return;				
			}

			$(".address_tab li").removeClass("on");
			$(this).addClass("on");

			$(".address_wrap").hide();
			$(".address_wrap"+tab_number).show();

		});
		</script>


		<div class="address_all_wrap">

			<ul class="address_tab">
				<li data-tab_number="1"><a>배송지목록</a></li>
				<li class="on" data-tab_number="2"><a>신규배송지</a></li>
			</ul>

			<div class="address_wrap address_wrap1 hide">
				
				<ul class="address_list">
				<?
				$que = sql_query("select * from nfor_myaddress where my_mb_no='$member[mb_no]'");
				while($data = sql_fetch_array($que)){
				?>
				<li>
					
					<div class="address_li_div">
						<input type="radio" name="my_sel" class="my_sel" id="my_sel<?=$data[my_id]?>">

						<label for="my_sel<?=$data[my_id]?>" class="my_sel_label">
							<?=$data[my_name]?> <span><?=$data[my_hp]?></span><br>
							<?=$data[my_zip]?> <?=$data[my_addr1]?> <?=$data[my_addr2]?>
						</label>
						
						<button type="button" class="myaddress_edit">수정</button>
						<button type="button" class="myaddress_del">삭제</button>
					</div>
					
				</li>
				<? } ?>
				</ul>

			</div>

			<div class="address_wrap address_wrap2">
				<input type="text" name="my_nick" id="my_nick" placeholder="배송지명">
				<input type="text" name="my_name" id="my_name" placeholder="받으시는분 이름">
				<input type="number" pattern="[0-9]*" name="my_hp" id="my_hp" placeholder="받으시는분 휴대전화번호">
				<div class="my_zip_wrap">
					<input type="number" pattern="[0-9]*" name="my_zip" id="my_zip" placeholder="우편번호" readonly>
					<a id="zipcode_btn">우편번호찾기</a>
				</div>
				<input type="text" name="my_addr1" id="my_addr1" placeholder="주소" readonly>
				<input type="text" name="my_addr2" id="my_addr2" placeholder="상세주소">
				<label><input type="checkbox" name="my_basic" id="my_basic">기본배송지로 선택</label>
			</div>

		</div>







	</div>

	<div class="common_order">
		<div class="corder_title">
			<h2 class="h_tit">배송메세지</h2>
		</div>
		<div class="dy_msg_wrap">
			<select name="dy_msg_type" id="dy_msg_type">
				<option value="">배송 메세지를 선택해 주세요</option>
				<option value="배송 전 연락바랍니다">배송 전 연락바랍니다</option>		
				<option value="무인 택배함에 놔주세요">무인 택배함에 놔주세요</option>
				<option value="경비실에 맡겨주세요">경비실에 맡겨주세요</option>
				<option value="집앞에 놔주세요">집앞에 놔주세요</option>
				<option value="직접 입력">직접 입력</option>
			</select>
			<textarea name="dy_msg" id="dy_msg" class="hide"></textarea>
		</div>
	</div>
	<!-- //배송메세지 -->


	<!--할인쿠폰 
	<div class="common_order">
		<div class="corder_title">
		<h2 class="h_tit"> 쿠폰사용</h2>
		</div>
		<div class="coupon_cont">
			<div class="coupon_wr">
					<input type="checkbox" name="use_coupon" id="use_coupon"> <label for="use_coupon">할인쿠폰</label>
					<a id="coupon_change">조회 및 사용</a>
					<div class="coupon_price_wrap"><span id="span_coupon_price" class="fotm">0</span> 원</div>
			</div>
			<div class="list_zone" style="margin-top:15px; padding:0px 15px 15px;">
				<select name="cp_id[]" class="coupon_select">
					<option value="">상품쿠폰선택
					</option>
				</select>
			</div>
		</div>
	</div>
	//할인쿠폰 -->


	<!--적립금 -->
	<div class="common_order">
		<div class="corder_title">
			<h2 class="h_tit">적립금사용</h2>
		</div>
		<div class="coupon_cont">
			<div class="coupon_wr">
				<p>
					사용가능 금액 <em><?=number_format(mb_money($member[mb_no]))?></em>원 
					<?
					$msg = "";
					if($config[cf_money_min]>0 and $config[cf_money_min] > $total_price){
						$msg .= "주문금액 ".number_format($config[cf_money_min])."원 이상 사용가능";
					}
					if($config[cf_money]>0 and $config[cf_money]>mb_money($member[mb_no])){
						if($msg) $msg .= ", ";
						$msg .= "보유 적립금이 ".number_format($config[cf_money])."원 이상일 경우 사용가능";
					}
					?>
					<? if($msg){ ?><span>(<?=$msg?>)</span><? } ?>	
				</p>
				<div class="use">
					<div class="cash">
						<input type="number" pattern="[0-9]*" name="money_price" id="money_price" value="0" <?=$msg?"disabled='disabled'":""?>>
						<span class="won">원</span>
					</div>
					<a class="all_money_btn">전액사용</a>
				</div>		
			</div>
		</div>
	</div>
	<!--//-적립금  -->


	<!-- 최종결제금액 -->
	<div class="common_order">
		<div  class="corder_title">
			<h2 class="h_tit">최종결제금액</h2>
			<div class="total">
				<div class="in">
					<strong class="price"><span id="span_pay_total_price"><?=number_format($total_price)?></span><em>원</em></strong>
				</div>
			</div>
		</div>
		<div class="coupon_cont">
			<div class="total_price">
			<ul>
				<li><span>상품금액</span><span><?=number_format($item_total_price)?>원</span></li>
				<li><span>배송비</span><span><?=number_format($delivery_total_price)?>원</span></li>
				<li><span>할인금액</span><span><a id="span_discount_price">0</a>원</span></li>
				<li><span>적립금 사용</span><span><a id="span_money_price">0</a>원</span></li>
			</ul>
			</div>
		</div>
	</div>
	<!-- //최종결제금액 -->


	<!-- 결제수단 -->
	<div class="common_order">
		<div class="corder_title">
			<h2 class="h_tit">결제수단 선택</h2>
		</div>

		<div class="coupon_cont">
			<ul class="payment_type_menu">
				<li><input type="radio" name="payment_type" class="payment_type" value="card" id="payment_type_card" checked> <label for="payment_type_card">신용카드</label></li>
				<li><input type="radio" name="payment_type" class="payment_type" value="iche" id="payment_type_iche"> <label for="payment_type_iche">계좌이체</label></li>
				<li><input type="radio" name="payment_type" class="payment_type" value="vbanking" id="payment_type_vbanking"> <label for="payment_type_vbanking">가상계좌</label></li>
				<li><input type="radio" name="payment_type" class="payment_type" value="hp" id="payment_type_hp"> <label for="payment_type_hp">휴대폰</label></li>
				<li><input type="radio" name="payment_type" class="payment_type" value="banking" id="payment_type_banking"> <label for="payment_type_banking">무통장입금</label></li>
			</ul>
			<div id="payment_card" class="payment_wrap">
				<ul class="card_menu" >
					<li>
						<select name="card_code" id="card_code">
						<option value="">카드선택
						<?
						$que = sql_query("select * from nfor_pg_code where pg_type='$nfor[pg_type]' and pg_payment_type='card'");
						while($data = sql_fetch_array($que)){
						?>
						<option value="<?=$data[pg_code]?>"><?=$data[pg_name]?>
						<? } ?>
						</select>
					</li>
					<li >
						<select name="card_quota" id="card_quota">
						<option value="">할부선택
						<option value="00">일시불
						<?
						for($i=2; $i<=12; $i++){		
						?>
						<option value="<?=sprintf("%02d",$i)?>"><?=$i?>개월
						<? } ?>
						</select>
					</li>
				</ul>
			</div>
			<div id="payment_vbanking" class="payment_wrap">
				<p class="memo">※ <?=date("Y년 m월 d일",strtotime("+".$config[cf_vbanking_limit]."day"))?>까지 미입금시 주문은 자동 취소됩니다.</p>
			</div>		
			<div id="payment_banking" class="payment_wrap">
				<label class="tit">입금은행</label>
				<select name="bank_number" id="bank_number">
				<?
				$que_banking = sql_query("select * from nfor_banking where bnk_use='1' order by bnk_rank asc");
				while($data = sql_fetch_array($que_banking)){
				?>
				<option value="<?=$data[bnk_bank]?> <?=$data[bnk_number]?> <?=$data[bnk_name]?>"><?=$data[bnk_bank]?> <?=$data[bnk_number]?> <?=$data[bnk_name]?></option>
				<?}?>
				</select>
				<label class="tit">입금자명</label>
				<input type="text" name="bank_name" id="bank_name" placeholder="입금하시는분의 이름" value="<?=$member[mb_name]?>">
				<p class="memo">※ <?=date("Y년 m월 d일",strtotime("+".$config[cf_banking_limit]."day"))?>까지 미입금시 주문은 자동 취소됩니다.</p>
			</div>
			<div class="receipt_area hide" id="cashreceipt_div">
				<div class="inner">
					<h3>현금영수증 신청</h3>
					<label><input type="radio" name="od_cashreceipt_method" class="od_cashreceipt_method" value="1" checked>소득공제</label>
					<label><input type="radio" name="od_cashreceipt_method" class="od_cashreceipt_method" value="2">지출증빙</label>
					<label><input type="radio" name="od_cashreceipt_method" class="od_cashreceipt_method" value="3">신청안함</label>
					<div id="od_cashreceipt_method_1" class="od_cashreceipt_method_wrap">
						 <select name="od_cashreceipt_type1" id="od_cashreceipt_type1" class="od_cashreceipt_type">
							<option value="1" selected>휴대폰번호
							<option value="2">현금영수증 카드번호
						 </select>
						 <input type="text" name="od_cashreceipt_val1" id="od_cashreceipt_val1" value="<?=$member[mb_hp]?>" >
					</div>
					<div id="od_cashreceipt_method_2" class="od_cashreceipt_method_wrap hide">
						 <select name="od_cashreceipt_type2" id="od_cashreceipt_type2" class="od_cashreceipt_type">
							<option value="3" selected>사업자등록번호
							<option value="4">현금영수증 카드번호
						 </select>
						 <input type="text" name="od_cashreceipt_val2" id="od_cashreceipt_val2">
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- //결제수단 -->


	<div class="common_order hide" id="refund_div">
		<div  class="corder_title">
			<h2 class="h_tit">환불계좌정보</h2>
		</div>
		<div class="coupon_cont">
			<div class="receipt_area">
			<select name="od_refund_bank" id="od_refund_bank">
			<option value="">은행선택
			<?
			$que = sql_query("select * from nfor_pg_code where pg_type='$nfor[pg_type]' and pg_payment_type='vbanking'");
			while($data = sql_fetch_array($que)){
			?>
			<option value="<?=$data[pg_code]?>" <?=$member[mb_bank_name]==$data[pg_code]?"selected":""?>><?=$data[pg_name]?>(<?=$data[pg_code]?>)
			<? } ?>
			</select>
			<input type="text" name="od_refund_account" id="od_refund_account" value="<?=$member[mb_bank_account]?>" placeholder="환불계좌번호">
			<input type="text" name="od_refund_name" id="od_refund_name" value="<?=$member[mb_name]?>" placeholder="계좌번호 예금주">
			</div>
		</div>
	</div>


	<div class="common_order">
		<div class="corder_title">
			<h2 class="h_tit"><label><?=admin_checkbox($row,"chkall")?>  약관 전체 동의하기</label></h2>
		</div>

		<div class="coupon_cont">
			<div class="agree_wr">
				<div class="agree"><label for="chk1"><?=admin_checkbox($row,"chk1","chk chk1")?> 개인정보 제3자 제공에 동의합니다.</label><a class="popbtn" data-num="1">보기</a></div>
				<div class="agree"><label for="chk2"><?=admin_checkbox($row,"chk2","chk chk2")?> 결제대행서비스 이용약관에 동의합니다.</label><a class="popbtn" data-num="2">보기</a></div>
				<div class="agree"><label for="chk3"><?=admin_checkbox($row,"chk3","chk chk3")?> 상품 구매조건 확인 및 취소·환불 규정 동의</label></div>
			</div>
			<div class="btn_w"><a id="payment_btn">결제하기</a></div>
		</div>
	</div>

</div>
</form>


<div id="zipcode_wrap"></div>

















<script>
// 회원정보
var mb_name = "<?=$member[mb_name]?>";
var mb_hp = "<?=$member[mb_hp]?>";
var mb_tel = "<?=$member[mb_tel]?>";
var mb_zip = "<?=$member[mb_zipcode]?>";
var mb_addr1 = "<?=$member[mb_addr1]?>";
var mb_addr2 = "<?=$member[mb_addr2]?>";

// 최근배송지정보
var last_name = "<?=$last[dy_name]?>";
var last_hp = "<?=$last[dy_hp]?>";
var last_tel = "<?=$last[dy_tel]?>";
var last_zip = "<?=$last[dy_zip]?>";
var last_addr1 = "<?=$last[dy_addr1]?>";
var last_addr2 = "<?=$last[dy_addr2]?>";

// 기본배송지정보
var my_name = "<?=$chk_myaddress[my_name]?>";
var my_hp = "<?=$chk_myaddress[my_hp]?>";
var my_tel = "<?=$chk_myaddress[my_tel]?>";
var my_zip = "<?=$chk_myaddress[my_zip]?>";
var my_addr1 = "<?=$chk_myaddress[my_addr1]?>";
var my_addr2 = "<?=$chk_myaddress[my_addr2]?>";

var cart_total_price = parseInt(<?=(int)$total_price?>);
var mb_money = parseInt(<?=(int)mb_money($member[mb_no])?>);

var currentScroll = "";

// 배송지정보 불러오기
$(document).on("click",".dy_type",function(){
	var dy_type = $('.dy_type:checked').val();
	if(dy_type=="1"){ // 구매자정보와동일
		$("#dy_name").val($("#od_name").val());
		$("#dy_hp").val($("#od_hp").val());
		$("#dy_tel").val($("#od_tel").val());
		$("#dy_zip").val($("#od_zip").val());
		$("#dy_addr1").val($("#od_addr1").val());
		$("#dy_addr2").val($("#od_addr2").val());
	} else if(dy_type=="2"){ // 회원 정보
		$("#dy_name").val(mb_name);
		$("#dy_hp").val(mb_hp);
		$("#dy_tel").val(mb_tel);
		$("#dy_zip").val(mb_zip);
		$("#dy_addr1").val(mb_addr1);
		$("#dy_addr2").val(mb_addr2);
	} else if(dy_type=="3"){ // 최근배송지 정보
		$("#dy_name").val(last_name);
		$("#dy_hp").val(last_hp);
		$("#dy_tel").val(last_tel);
		$("#dy_zip").val(last_zip);
		$("#dy_addr1").val(last_addr1);
		$("#dy_addr2").val(last_addr2);
	} else if(dy_type=="4"){ // 새로운 배송지
		$("#dy_name").val("");
		$("#dy_hp").val("");
		$("#dy_tel").val("");
		$("#dy_zip").val("");
		$("#dy_addr1").val("");
		$("#dy_addr2").val("");
	} else if(dy_type=="5"){ // 기본배송지 정보
		$("#dy_name").val(my_name);
		$("#dy_hp").val(my_hp);
		$("#dy_tel").val(my_tel);
		$("#dy_zip").val(my_zip);
		$("#dy_addr1").val(my_addr1);
		$("#dy_addr2").val(my_addr2);
	} else{

	}
});

// 약관동의 체크박스
$(document).on("click", "#chkall", function(){
	nfor_chk_all(this, 'chk');
});

$(document).on("click", ".chk", function(){
	if($(".chk:checked").length=="3"){
		$("#chkall").prop("checked",true);
	} else{
		$("#chkall").prop("checked",false);
	}
});

// 결제수단 클릭
$(document).on("click",".payment_type",function(){
	var payment_type = $('.payment_type:checked').val();
	// 현금영수증 체크
	if(payment_type=="iche" || payment_type=="vbanking" || payment_type=="banking"){
		$("#cashreceipt_div").removeClass("hide");
	} else{
		$("#cashreceipt_div").addClass("hide");
	}
	// 환불계좌 체크
	if(payment_type=="vbanking" || payment_type=="banking"){		
		$("#refund_div").removeClass("hide");
	} else{
		$("#refund_div").addClass("hide");
	}
	$(".payment_wrap").hide();
	$("#payment_"+payment_type).show();
});

// 배송메시지 선택
$(document).on("change", "#dy_msg_type", function(){
	var dy_msg_type = $(this).val();
	if(dy_msg_type=="직접 입력"){
		$("#dy_msg").removeClass("hide").val("").focus();
	} else{
		$("#dy_msg").addClass("hide").val(dy_msg_type);
	}
});

// 현금영수증 선택
$(document).on("click","input[name=od_cashreceipt_method]",function(){ 
	nfor_radio_click("od_cashreceipt_method",this.value);
});

$(document).on("change","#od_cashreceipt_type1",function(){ 
	$("#od_cashreceipt_val1").val("").focus();
});

$(document).on("change","#od_cashreceipt_type2",function(){ 
	$("#od_cashreceipt_val2").val("").focus();
});

// 적립금사용
$(document).on("click",".all_money_btn",function(){
	if($("#money_price").attr("disabled") != "disabled"){
		var money_price = 0;
		if(mb_money > cart_total_price){
			money_price = cart_total_price;
		} else{
			money_price = mb_money;
		}
		$("#money_price").val(money_price);
		check_money();
	}
});

$(document).on("change","#money_price",function(){
	check_money();
});

function check_money(){
	var money_price = parseInt($("#money_price").val());
	if(!money_price || isNaN(money_price) || money_price<0 || mb_money < money_price || cart_total_price < money_price){
		if(mb_money < money_price){
			alert("보유하신 적립금이 입력하신 적립금액보다 적습니다.");
		}
		if(cart_total_price < money_price){
			alert("결제 금액 만큼만 적립금을 입력해주세요.");
		}
		$("#money_price").val("0");
		money_price = 0;
	}

	var pay_total_price = cart_total_price - money_price;
	var discount_total_price = money_price;

	$("#span_money_price").html(number_format(money_price));
	$("#span_discount_price").html(number_format(discount_total_price));
	$("#span_pay_total_price").html(number_format(pay_total_price));
}

// 우편번호찾기
$(document).on("click","#zipcode_btn, #my_zip, #my_addr1",function(){
	currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
	zipcode("my_zip","my_addr1","my_addr2");
	$(".cart_wrap").hide();
	$(".btn_back").attr("href","javascript:zipcode_close()");
});

function zipcode_close(){
	var element_wrap = document.getElementById('zipcode_wrap');
	element_wrap.style.display = 'none';
	$(".cart_wrap").show();
	// 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
	document.body.scrollTop = currentScroll;
	$(".btn_back").attr("href","javascript:history.back()");
}

function zipcode(zipcode,addr1,addr2){
	var element_wrap = document.getElementById('zipcode_wrap');

	// 현재 scroll 위치를 저장해놓는다.
	new daum.Postcode({
		oncomplete: function(data) {
			// 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

			// 각 주소의 노출 규칙에 따라 주소를 조합한다.
			// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
			var fullAddr = data.address; // 최종 주소 변수
			var extraAddr = ''; // 조합형 주소 변수

			// 기본 주소가 도로명 타입일때 조합한다.
			if(data.addressType === 'R'){
				//법정동명이 있을 경우 추가한다.
				if(data.bname !== ''){
					extraAddr += data.bname;
				}
				// 건물명이 있을 경우 추가한다.
				if(data.buildingName !== ''){
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
				fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
			}

			// 우편번호와 주소 정보를 해당 필드에 넣는다.
			document.getElementById(zipcode).value = data.zonecode; //5자리 새우편번호 사용
			document.getElementById(addr1).value = fullAddr;
			$(".cart_wrap").show();

			// iframe을 넣은 element를 안보이게 한다.
			// (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
			element_wrap.style.display = 'none';


			// 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
			document.body.scrollTop = currentScroll;

			document.getElementById(addr2).focus();

		},
		// 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분. iframe을 넣은 element의 높이값을 조정한다.
		onresize : function(size) {
			element_wrap.style.height = size.height+'px';
		},
		width : '100%',
		height : '100%'
	}).embed(element_wrap);

	// iframe을 넣은 element를 보이게 한다.
	element_wrap.style.display = 'block';
}






















// 결제하기 클릭
$(document).on("click","#payment_btn",function(){

	var payment_type = $('.payment_type:checked').val();

	<? if($is_delivery){  ?>
	if(!$("#dy_name").val()){
		alert("배송지 이름을 입력해주세요");
		$("#dy_name").focus();
		return;
	}
	if(!$("#dy_hp").val()){
		alert("배송지 휴대폰번호를 입력해주세요");
		$("#dy_hp").focus();
		return;
	}
	if(!$("#dy_zip").val()){
		alert("배송지 우편번호를 입력해주세요");
		$("#dy_zip").focus();
		return;
	}

	if($("#dy_zip").val().length > 5){
		alert("우편번호찾기를 통해서 5자리 우편번호를 사용해주세요");
		$("#dy_zip").focus();
		return;
	}

	if(!$("#dy_addr1").val()){
		alert("배송지 주소를 입력해주세요");
		$("#dy_addr1").focus();
		return;
	}
	if(!$("#dy_addr2").val()){
		alert("배송지 상세 주소를 입력해주세요");
		$("#dy_addr2").focus();
		return;
	}
	<? } ?>

	if(payment_type=="card"){		
		if(!$("#card_code").val()){
			alert("카드종류를 선택해 선택해주세요");
			$("#card_code").focus();
			return;
		}
		if(!$("#card_quota").val()){
			alert("할부기간을 선택해주세요");
			$("#card_quota").focus();
			return;
		}	
	} else if(payment_type=="banking"){
		if(!$("#bank_number").val()){
			alert("입금계좌를 선택해주세요");
			$("#bank_number").focus();
			return;
		}
		if(!$("#bank_name").val()){
			alert("입금하시는 분의 이름을 입력해주세요");
			$("#bank_name").focus();
			return;
		}
	} else{

	}

	// 환불계좌 체크
	if(payment_type=="vbanking" || payment_type=="banking"){		
		if(!$("#od_refund_bank").val()){
			alert("환불은행을 선택해주세요");
			$("#od_refund_bank").focus();
			return;
		}
		if(!$("#od_refund_account").val()){
			alert("환불계좌번호를 입력해주세요");
			$("#od_refund_account").focus();
			return;
		}
		if(!$("#od_refund_name").val()){
			alert("환불계좌의 예금주를 입력해주세요");
			$("#od_refund_name").focus();
			return;
		}
	}

	// 현금영수증 체크
	if(payment_type=="iche" || payment_type=="vbanking" || payment_type=="banking"){		
		var od_cashreceipt_method = $('.od_cashreceipt_method:checked').val();		
		var od_cashreceipt_type = $('#od_cashreceipt_type'+od_cashreceipt_method).val();
		var alert_str = "";
		if(od_cashreceipt_type=="1"){
			alert_str = "휴대폰번호";
		} else if(od_cashreceipt_type=="2"){
			alert_str = "현금영수증 카드번호";
		} else if(od_cashreceipt_type=="3"){
			alert_str = "사업자등록번호";
		} else if(od_cashreceipt_type=="4"){
			alert_str = "현금영수증 카드번호";
		} else{

		}
		if(od_cashreceipt_method == "1" || od_cashreceipt_method == "2"){
			if(!$("#od_cashreceipt_val"+od_cashreceipt_method).val()){
				alert("현금영수증 발급을 위한 "+alert_str+"를 입력해주세요");
				$("#od_cashreceipt_val"+od_cashreceipt_method).focus();
				return;
			}
		}
	}

	if(!$('#chk1').is(":checked")){
		alert("개인정보 제3자 제공에 동의하셔야 진행가능합니다");
		$('#chk1').focus();
		return;
    }

	if(!$('#chk2').is(":checked")){
		alert("결제대행서비스 이용약관에 동의하셔야 진행가능합니다");
		$('#chk2').focus();
		return;
    }

	if(!$('#chk3').is(":checked")){
		alert("상품 구매조건 확인 및 취소환불 규정에 동의하셔야 진행가능합니다");
		$('#chk3').focus();
		return;
    }

	if(payment_type=="banking"){	
		$("#payment_btn").hide();
	}

	$.ajax({ 
		type : "post"
		, url : "cart_order.php"
		, cache : false  
		, data : $("#fcart_order").serialize() 
		, success : function(response){ 
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){

				if(json["payment_type"]=="banking" || json["payment_type"]=="money" || json["payment_type"]=="coupon"){
					location.href = "cart_order_result.php?od_id="+json["od_id"];
				} else{
					$("#od_id").val(json["od_id"]);
					$("#pay_price").val(json["pay_price"]);

					<?php
					if($nfor[pg_type] == "inipay"){	// 이니시스
					?>
					$("#timestamp").val(json["timestamp"]);
					$("#signature").val(json["signature"]);
					<? } ?>

					$("#pg_id").val(json["pg_id"]);

					cart_payment();
				}

			} else{
				$("#payment_btn").show();
				alert(json["msg"]);
			}
		}
	});

});



</script>








<?php
include_once "pg_".$nfor[pg_type]."_mobile.php";

include_once $nfor[skin_path]."tail.php";
?>