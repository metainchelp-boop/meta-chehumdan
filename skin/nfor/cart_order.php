<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
/* 전체 고통 */
.cart_wrap { margin:0px; padding:0px; }
.hide { display:none; }

/* 타이틀 공통 */
.cart_order_title_wrap { height:60px; position:relative; padding:0 45px 0 15px; background-color:#fff; border-top:solid 1px #e6e6e6; border-bottom:solid 1px #e6e6e6; margin-top:10px; text-align:right; }
.cart_order_title_wrap .cart_order_title { float:left; height:60px; position:relative; line-height:60px; font-weight:bold; font-size:18px; letter-spacing:-.06em;  }
.cart_order_title_wrap .cart_order_title_value_wrap { float:right; display:block; max-width:71%; height:60px; line-height:60px; text-align:right; font-size:13px;  }
.cart_order_title_wrap .cart_order_title_value_wrap .cart_order_title_value { display:inline-block; overflow:hidden; white-space:nowrap; text-overflow:ellipsis; max-width:100%; color:#959da6; }
.cart_order_title_wrap .cart_order_title_value_wrap .cart_order_title_value_count { display:inline-block; color:#959da6; vertical-align:top; }
.cart_order_title_wrap .cart_order_title_btn { display:block; position:absolute; top:0px; right:0px; width:40px; height:60px; line-height:60px; cursor:pointer; text-align:center; }
.cart_order_title_wrap .cart_order_title_btn i {display: inline-block; width:14px; height:7px; background:url(<?=$nfor[skin_path]?>img/toggle_btn.png) no-repeat; background-position:0px -10px;  background-size:14px; }
.cart_order_title_wrap:after { display:block; clear:both; content:''; }
.cart_order_title_click { cursor:pointer; }

/* 주문상품 */
.item_title { width:71%;  }
.item_title .cart_order_title_value { width:65%; }

.item_detail{ background:#f1f1f1; }
.item_detail_ul { background: #f7f7f7; }
.item_detail_ul li { padding-top:15px; border-top:solid 1px #dfe2e6; background-color:#fff; }
.item_detail_ul li:first-child{ border-top:0; }
.item_detail_ul li a { display:block; position:relative; padding-left:120px; height:58px; }
.item_detail_ul li a .thumnail { position:absolute; top:0; left:15px; display:block; }
.item_detail_ul li a .thumnail img { width:87px; height:59px; }
.item_detail_ul li a .it_name { font-size:14px; line-height:16px; color:#16181a; }

.item_detail .option_wrap { margin-top:15px; }
.item_detail .option_wrap li { padding:5px 20px; border-top:dashed 1px #dfe2e6; letter-spacing:-.06em;}
.item_detail .option_wrap li:first-child{ border-top:0px; }
.item_detail .option_wrap li .op_name { padding-top: 4px; color:#959da6; font-size:12px; }
.item_detail .option_count_price { height: 36px; font-size:12px;}
.item_detail .option_count_price :after {display: block;clear: both;content: '';}
.item_detail .option_count_price .count{float:left;margin-top: 10px; color: #959da6;}
.item_detail .option_count_price .price {display: inline-block; float: right; height: 36px; margin-top: 10px; text-align: right;}
.item_detail .option_price_total { position:relative; padding:20px 15px 20px; border-bottom:solid 1px #DCDCDC; border-top:dashed 1px #e6e6e6; }
.item_detail .option_price_total ul li { border-top:none; padding:3px 0px; }
.item_detail .option_price_total ul li:after{ display:block; clear:both; content:''; }
.item_detail .option_price_total ul li span:first-child { float:left; color:#6c7580; font-size:13px; }
.item_detail .option_price_total ul li span:last-child { float:right; color:#6c7580; font-size:13px; }
.item_detail .option_price_total .point_color{ color:#f27935 !important; } 

/* 티켓사용자 */
.ticket_detail { padding:15px 15px; border-bottom:solid 1px #e6e6e6; background-color:#fff; }
#od_pass { margin:0px 0px 10px; }
#od_hp { margin:10px 0px; }

/* 배송지 */
.cart_order_title_delivery_wrap { border-bottom:dashed 1px #e6e6e6; }
.delivery_change{ position:absolute; top:17px; right:10px; width:48px; height:28px; line-height:28px; cursor:pointer; text-align:center; box-sizing:border-box; border:solid 1px #b7bfc8; background-color:transparent; font-size:12px; color: #959da6;}
.delivery_detail { padding:15px 15px; border-bottom:solid 1px #e6e6e6; background-color:#fff; }
.address_select_wrap { font-size:14px; color:#959da6; }
.address_tab { border:1px solid #d0d5d9; margin-bottom:10px; }
.address_tab li:first-child { border-left:0; }
.address_tab li { float:left; position:relative; box-sizing:border-box; width:50%; border-left:1px solid #d0d5d9; }
.address_tab:after { display:block; clear:both; content:''; }
.address_tab li.on a { color:#f27935; }
.address_tab li.on:before { width:100%; height:45px; border:1px solid #f27935; position:absolute; top:-1px; left:-1px; content:''; display:block; z-index:10; }
.address_tab li a { width:100%; height:45px; line-height:45px; display:block; font-size:14px; color:#959da6; text-align:center; }
#dy_name { margin:10px 0px; }
.dy_zip_wrap { margin:10px 0px; position:relative; height:40px; }
 #zipcode_btn  { cursor:pointer; position:absolute; right:0px; top:0px; height:38px; display:block; width:110px; text-align:center; line-height:40px; border:solid 1px #ccc; background:-webkit-gradient(linear,left top,left bottom,from(#fff),to(#ecebf0)); box-shadow:none; }
#dy_addr2 { margin:10px 0px; }
.my_id { position:absolute; left:0px; top:0px; }
.my_id_label { position:absolute; left:34px; top:0px; width:75%; }
.myaddress_update { position:absolute; right:0px; top:0px; display:inline-block; box-sizing:border-box; width:48px; height:28px; border:1px solid #b7bfc8; background-color:transparent; text-align:center; cursor:pointer; font-size:12px; color:#959da6; }
.myaddress_delete { position:absolute; right:0px; top:32px; display:inline-block; box-sizing:border-box; width:48px; height:28px; border:1px solid #b7bfc8; background-color:transparent; text-align:center; cursor:pointer; font-size:12px; color:#959da6; }
.address_list li { border-bottom:1px solid #edeff0; padding:10px 0px; }
.address_list li .address_li_div { position:relative; height:80px; }

/* 배송메시지 */
.od_dy_msg_title { width:66%; }
.od_dy_msg_detail { padding:15px 15px; border-bottom:solid 1px #e6e6e6; background-color:#fff; }
.od_dy_msg_detail #od_dy_msg { height:60px; margin-top:10px; }

/* 개인통관고유부호 */
.od_global_detail { padding:15px 15px; border-bottom:solid 1px #e6e6e6; background-color:#fff; }


/* 적립금 */
.money_detail { padding:15px 15px; background-color:#fff; border-bottom:solid 1px #DCDCDC; color:#16181a; font-size:14px; }
.money_detail .mb_money{ color:#e83862; }
.money_detail .all_money_btn{ display:inline-block; box-sizing:border-box; position:absolute; top:0; right:0; width:82px; font-size:13px; color:#6c7580; height:38px; line-height:38px; border:solid 1px #b7bfc8; font-size: 16px; text-align:center; }
.money_detail .money_price_div { position:relative; margin-top:10px; padding-right:90px;}
.money_detail .money_price_wrap { position:relative; color:#16181a; }
.money_detail #money_price { box-sizing: border-box;width: 100%; height: 38px; padding-right: 23px;border: 1px solid #d0d5d9; font-weight: 600;font-size: 15px; text-align: right;}
.money_detail .money_price_won { display:inline-block; position:absolute; top:50%; margin-top:-10px; right:8px; }

/* 최종결제금액 */
.price_detail { padding:5px 15px 15px; border-bottom:solid 1px #e6e6e6; background-color:#fff; }
.price_detail ul li { margin-top:10px; color:#16181a; font-size:0.9em; }
.price_detail ul li:after { display:block; clear:both; content:''; }
.price_detail p { float:left; }
.price_detail span { float:right; }

/* 결제수단 */
.payment_detail { padding:15px 15px; border-bottom:solid 1px #e6e6e6; background-color:#fff; }
.payment_type_wrap { overflow:hidden; }
.payment_type_wrap label { float:left; width:50%; padding-bottom:10px; font-size:12px; }

#payment_card select:first-child { float:left; width:49%; }  
#payment_card select:last-child { float:right; width:49%; }  
#payment_card:after { display:block; clear:both; content:''; }
#payment_vbanking { display:none; padding-bottom:10px; }
#payment_hp { display:none; }
#payment_iche { display:none; }
#payment_banking { display:none; padding-bottom:10px; }
.payment_wrap .memo { color:#959da6; font-size:12px; }
#bank_name { margin:10px 0px; }

/* 환불계좌 */
#refund_div { border-top: 1px solid #edeff0; padding-top:15px; }
#refund_div p { font-size:15px; margin-bottom:10px; }
.od_refund_bank_wrap { margin:10px 0px; }
.od_refund_bank_wrap select { float:left; width:49%; }
.od_refund_bank_wrap input { float:right; width:49%; padding:7px 10px 7px; }
.od_refund_bank_wrap:after { display:block; clear:both; content:''; }

/* 현금영수증 */
#cashreceipt_div { border-top: 1px solid #edeff0; padding-top:15px; padding-bottom:15px; }
#cashreceipt_div p { font-size:15px; margin-bottom:10px; }
#cashreceipt_div label input[type="radio"] { margin:0px; }
#cashreceipt_div .radio-inline { margin-right:10px; }
.od_cashreceipt_method_wrap { margin-top:10px; }
.od_cashreceipt_method_wrap select { float:left; width:49%; }
.od_cashreceipt_method_wrap input { float:right; width:49%; padding:7px 10px 7px; }
.od_cashreceipt_method_wrap:after { display:block; clear:both; content:''; }

/* 약관동의 */
.agree_detail { padding:15px 15px; border-bottom:solid 1px #e6e6e6; background-color:#fff; }
.agree_detail .agree { position:relative; font-size:12px; margin-bottom:5px; height:24px; line-height:24px; letter-spacing:-.06em; }
.agree_popup_btn { position:absolute; top:3px; right:0px; font-size:12px; color:#959da6; display:inline-block; height:20px; line-height:20px; } 
.agree_popup_btn:after {display: inline-block;width: 0; height: 0; margin: -3px 0 0 5px;border-width: 3px; border-style: solid;border-color: transparent transparent transparent #959da6; vertical-align: middle; content: "";}
.agree_detail { padding:15px 15px; border-bottom:solid 1px #e6e6e6; background-color:#fff; }

.item_detail_li {  margin-bottom:10px; }
</style> 

<form name="fcart_order" id="fcart_order" method="post">
<input type="hidden" name="mode" value="insert">
<?=admin_hidden($write,"ss_cart_id_new")?>

<?=admin_hidden($write,"od_it_id")?>
<?=admin_hidden($write,"od_it_name")?>
<?=admin_hidden($write,"od_is_ticket")?>
<?=admin_hidden($write,"od_is_delivery")?>
<?=admin_hidden($write,"od_is_global")?>


<div class="cart_wrap">
	
	<!-- 주문상품 -->
	<div class="cart_order_title_wrap cart_order_title_click" data-title="item_title" data-detail="item_detail">
		<div class="cart_order_title">주문상품</div>
		<div class="cart_order_title_value_wrap item_title">
			<p class="cart_order_title_value"><?=$return[od_it_name]?></p>
			<p class="cart_order_title_value_count">포함 <?=$return[od_it_count]?>건</p>
		</div>
		<a class="cart_order_title_btn"><i></i></a>
	</div>
	<!-- //주문상품 -->
	
	<!-- 주문리스트 -->
	<div class="item_detail" style="display:none;">

		<ul class="item_detail_ul">
			<?php
			for($i=0; $i<count($return["list"]); $i++){
				$cart = $return["list"][$i];
			?>
			<li class="item_detail_li">

				<a href="item.php?it_id=<?=$cart[it_id]?>">
					<span class="thumnail"><img src="<?=$cart[it_img]?>"></span>
					<span class="it_name"><?=$cart[it_name]?></span>
				</a>

				<div class="option_wrap">
					<ul class="op_ls">
						<?php
						for($k=0; $k<count($cart["reply"]); $k++){
							$ct = $cart["reply"][$k];
						?>
						<li id="opt_id_<?=$ct[ct_opt_id]?>">
							<p class="op_name"><?=$ct[opt_name]?></p>
							<div class="option_count_price">
								<div class="count">수량 <?=$ct[ct_opt_cnt]?>개</div>
								<div class="price"><?=$ct[ct_sprice2]?>원</div>
							</div>
						</li>
						<?php } ?>
					</ul>
					<div class="option_price_total">
						<ul>
							<li>
								<span>상품금액</span>
								<span><?=$cart[it_item_price]?>원</span>
							</li>
							<li>
								<span>배송비</span>
								<span><?=$cart[it_delivery_price]?></span>
							</li>
							<li>
								<span class="point_color">합산금액</span>
								<span class="point_color"><?=$cart[it_total_price]?>원</span>
							</li>
						</ul>
					</div>
				</div>
			</li>
			<?php } ?>
		</ul>

	</div>
	<!-- //주문리스트 -->


	<?php if($write[od_is_ticket] or !$member[mb_no]){  ?>
	<!-- 사용자정보 -->
	<div class="cart_order_title_wrap cart_order_title_click" data-title="ticket_title" data-detail="ticket_detail">
		<div class="cart_order_title">사용자정보</div>
		<div class="cart_order_title_value_wrap ticket_title">
			<p class="cart_order_title_value"><? if($member[mb_no]){ ?><?=$write[od_name]?> / <?=$write[od_hp]?><? } ?></p>
		</div>
		<a class="cart_order_title_btn"><i></i></a>
	</div>
	<div class="ticket_detail" <? if($member[mb_no]){ ?>style="display:none;"<? } ?>>

		<? if(!$member[mb_no]){ ?>
		<?=admin_text($write,"od_pass","","placeholder=\"주문 패스워드\"")?>
		<? } ?>

		<?=admin_text($write,"od_name","","placeholder=\"이름\"")?>
		<?=admin_text($write,"od_hp","","placeholder=\"휴대폰번호\"")?>
		<?=admin_text($write,"od_email","","placeholder=\"이메일\"")?>
	</div>
	<!-- //사용자정보 -->
	<? } ?>
	
	<?php if($write[od_is_delivery]){ ?>
	<!-- 배송지 -->
	<div class="cart_order_title_wrap cart_order_title_delivery_wrap">
		<div class="cart_order_title">배송지</div>
		<div class="cart_order_title_value_wrap delivery_title">
			<p class="cart_order_title_value"></p>
		</div>
		<a class="delivery_change <? if(!$return[myaddress]){ ?>hide<? } ?>">변경</a>
	</div>
	<div class="delivery_detail">
		<?=admin_hidden($write,"dy_info")?>
		<div class="address_all_wrap <? if($return[myaddress]){ ?>hide<? } ?>">


			<? if($member[mb_no]){ ?>
			<ul class="address_tab">
				<li <? if($return[myaddress]){ ?>class="on"<? } ?> data-dy_info="1"><a>배송지목록</a></li>
				<li <? if(!$return[myaddress]){ ?>class="on"<? } ?> data-dy_info="2"><a>신규배송지</a></li>
			</ul>
			<? } ?>


			<div class="address_wrap address_wrap1 <? if(!$return[myaddress]){ ?>hide<? } ?>">
				
				<ul class="address_list">
				<?php 
				for($i=0; $i<count($return[myaddress]); $i++){ 
					$myaddress = $return[myaddress][$i];
				?>
				<li id="address_list_li_<?=$myaddress[my_id]?>">
					
					<div class="address_li_div">
						<input type="radio" name="my_id" class="my_id" id="my_id<?=$myaddress[my_id]?>" value="<?=$myaddress[my_id]?>" <?=$i==0?"checked":""?>>

						<label for="my_id<?=$myaddress[my_id]?>" class="my_id_label">
							(<?=$myaddress[my_nick]?>) <?=$myaddress[my_name]?> <span><?=$myaddress[my_hp]?></span><br>
							<?=$myaddress[my_zip]?> <?=$myaddress[my_addr1]?> <?=$myaddress[my_addr2]?>
							<?=$myaddress[my_basic]?"기본배송지":""?>
						</label>
						
						<button type="button" class="myaddress_update" data-my_id="<?=$myaddress[my_id]?>" data-my_nick="<?=$myaddress[my_nick]?>" data-my_name="<?=$myaddress[my_name]?>" data-my_hp="<?=$myaddress[my_hp]?>" data-my_zip="<?=$myaddress[my_zip]?>" data-my_addr1="<?=$myaddress[my_addr1]?>" data-my_addr2="<?=$myaddress[my_addr2]?>" data-my_basic="<?=$myaddress[my_basic]?>">수정</button>
						<button type="button" class="myaddress_delete" data-my_id="<?=$myaddress[my_id]?>" data-my_nick="<?=$myaddress[my_nick]?>">삭제</button>
					</div>
					
				</li>
				<?php } ?>
				</ul>

			</div>

			<div class="address_wrap address_wrap2 <? if($return[myaddress]){ ?>hide<? } ?>">
				<?=admin_text($write,"dy_nick","","placeholder=\"배송지명\"")?>
				<?=admin_text($write,"dy_name","","placeholder=\"받으시는분 이름\"")?>
				<?=admin_text($write,"dy_hp","","placeholder=\"받으시는분 휴대전화번호\"")?>
				<div class="dy_zip_wrap">
					<?=admin_text($write,"dy_zip","","readonly placeholder=\"우편번호\"")?>
					<a id="zipcode_btn">우편번호찾기</a>
				</div>
				<?=admin_text($write,"dy_addr1","","readonly placeholder=\"주소\"")?>
				<?=admin_text($write,"dy_addr2","","placeholder=\"상세주소\"")?>
				<? if($member[mb_no]){ ?><?=admin_checkbox($write,"dy_basic","","","기본배송지로 선택")?><? } ?>
			</div>

		</div>

		<div class="address_select_wrap <? if(!$return[myaddress]){ ?>hide<? } ?>">
			(<?=$return[myaddress][0][my_nick]?>) <?=$return[myaddress][0][my_name]?> <span><?=$return[myaddress][0][my_hp]?></span><br>
			<?=$return[myaddress][0][my_zip]?> <?=$return[myaddress][0][my_addr1]?> <?=$return[myaddress][0][my_addr2]?>
			<?=$return[myaddress][0][my_basic]?"기본배송지":""?>
		</div>
	</div>
	<!-- //배송지 -->

	<!-- 배송메시지 -->
	<div class="cart_order_title_wrap cart_order_title_click" data-title="od_dy_msg_title" data-detail="od_dy_msg_detail">
		<div class="cart_order_title">배송메시지</div>
		<div class="cart_order_title_value_wrap od_dy_msg_title">
			<p class="cart_order_title_value">배송메시지를 선택해 주세요</p>
		</div>
		<a class="cart_order_title_btn"><i></i></a>
	</div>
	<div class="od_dy_msg_detail" style="display:none;">
		<?=admin_select($write,"od_dy_msg_type","","","0")?>
		<?=admin_textarea($write, "od_dy_msg", "hide")?>
	</div>
	<!-- //배송메시지 -->

	<?php if($write[od_is_global]){ ?>
	<!-- 해외배송상품 추가정보 -->
	<div class="cart_order_title_wrap cart_order_title_click" data-title="od_global_title" data-detail="od_global_detail">
		<div class="cart_order_title">개인통관고유부호</div>
		<div class="cart_order_title_value_wrap od_global_title">
			<p class="cart_order_title_value">개인통관고유부호를 입력해 주세요</p>
		</div>
		<a class="cart_order_title_btn"><i></i></a>
	</div>
	<div class="od_global_detail" style="display:none;">
		<?=admin_text($write,"od_global_number","","placeholder=\"개인통관고유부호를 입력해주세요\"")?>
		<?=admin_checkbox($write, "chk_global", "", "", "해외배송상품 수입신고를 위해 개인 통관부호 수집 및 판매자 제공에 동의합니다.")?>
	</div>
	<!-- //해외배송상품 추가정보 -->
	<?php } ?>



	<? } ?>




	<? if($member[mb_no]){ ?>
	<!--적립금 -->
	<div class="cart_order_title_wrap cart_order_title_click" data-title="money_title" data-detail="money_detail">
		<div class="cart_order_title">적립금사용</div>
		<div class="cart_order_title_value_wrap money_title">
			<p class="cart_order_title_value"><?=$write[money_price]?></p>
			<p class="cart_order_title_value_count">원</p>
		</div>
		<a class="cart_order_title_btn"><i></i></a>
	</div>
	<div class="money_detail" style="display:none;">
		사용가능 금액 <b class="mb_money"><?=$return[mb_money]?></b>원 
		<p><?=$return[memo][money]?></p>		
		<div class="money_price_div">
			<div class="money_price_wrap">
				<?=admin_number($write,"money_price")?>
				<span class="money_price_won">원</span>
			</div>
			<a class="all_money_btn">전액사용</a>
		</div>
	</div>
	<!--//-적립금  -->
	<? } ?>





	<? if($member[mb_no]){ ?>
	<!-- 쿠폰 -->
	<script>
	$(document).on("change",".coupon_select",function(){
		var cp_id = $(this).val();
		var is_cp_id = 0;
		if(cp_id){
			$('.coupon_select').each(function(){
				if(cp_id==$(this).val()){
					is_cp_id++;
				}		
			});
			if(is_cp_id>1){
				$(this).val("");
			}
		}
	});

	$(document).on("click","#coupon_use_btn",function(){
		if($("#coupon_use_btn").html()=="닫기"){
			$("#coupon_list_wrap").hide();
			$("#coupon_use_btn").html("조회 및 사용");
		} else{
			$("#coupon_list_wrap").show();
			$("#coupon_use_btn").html("닫기");
		}
	});

	$(document).on("click","#coupon_cancel",function(){

		$("#coupon_list_wrap").hide();
		$("#coupon_use_btn").html("조회 및 사용");

		if($("#use_coupon").is(":checked")){
			$("#use_coupon").prop("checked", false);	
		}

		check_money();
	});

	$(document).on("click","#use_coupon",function(){
		if($("#use_coupon").is(":checked")) {
			$("#coupon_list_wrap").show();
			$("#coupon_use_btn").html("닫기");
		} else{
			$("#coupon_list_wrap").hide();
			$("#coupon_use_btn").html("조회 및 사용");
			check_money();
		}
	});

	$(document).on("click", "#coupon_apply", function(){	

		var use_coupon = 0;
		$(".coupon_select option:selected").each(function(){
			if($(this).val()){
				use_coupon++;
			}
		});
		
		if(use_coupon){
			$("#use_coupon").prop("checked", true);
		} else{
			$("#use_coupon").prop("checked", false);
		}

		$("#coupon_list_wrap").hide();
		$("#coupon_use_btn").html("조회 및 사용");

		check_money();

	});
	</script>

	<div class="cart_order_title_wrap cart_order_title_click" data-title="coupon_title" data-detail="coupon_detail">
		<div class="cart_order_title">쿠폰사용</div>
		<div class="cart_order_title_value_wrap coupon_title">
			<p class="cart_order_title_value span_coupon_price" id="span_coupon_price">0</p>
			<p class="cart_order_title_value_count">원</p>
		</div>
		<a class="cart_order_title_btn"><i></i></a>
	</div>
	<div class="coupon_detail" style="display:none;">
		

<style>
.coupon_detail { position:relative; padding:15px 15px; background-color:#fff; border-bottom:solid 1px #DCDCDC; color:#16181a; font-size:14px; }
.coupon_detail #coupon_use_btn{ display:inline-block; box-sizing:border-box; position:absolute; top:15px; right:15px; width:102px; font-size:13px; color:#6c7580; height:30px; line-height:30px; border:solid 1px #b7bfc8; font-size: 14px; text-align:center; }

#coupon_list p { font-size:13px; margin-bottom:5px;  }
#coupon_list li { padding:10px 0px; border-bottom:solid 1px #ececec; }


#coupon_apply { display:block; color:#fff; text-align:center; padding:10px 0px; margin-top:10px; background-color:#e83862; }
</style>


		<div class="coupon_wrap">
			<input type="checkbox" name="use_coupon" id="use_coupon" style="vertical-align:middle;"> <label for="use_coupon">할인쿠폰</label>
			<a id="coupon_use_btn">조회 및 사용</a>
		</div>

		<div id="coupon_list_wrap" style="display:none;">

			<ul id="coupon_list" class="couponlist">
			<?php
			for($i=0; $i<count($return["list"]); $i++){
				$cart = $return["list"][$i];
			?>
				<li>
					<p><?=$cart[it_name]?></p>
					<input type="hidden" name="cp_it_id[]" value="<?=$cart[it_id]?>">
					<select name="cp_id[]" class="coupon_select">
					<option value="" data-it_id="<?=$cart[it_id]?>">상품쿠폰선택
					<?php
					for($k=0; $k<count($cart["reply2"]); $k++){
						$cp = $cart["reply2"][$k];
					?>
					<option value="<?=$cp[cp_id]?>" data-discount_price="<?=$cp[discount_price]?>" data-it_id="<?=$cart[it_id]?>"><?=$cp[discount_price_text]?>
					<? 
					}
					?>
					</select>
					<input type="hidden" name="cp_discount_price[]" class="cp_discount_price" id="cp_<?=$cart[it_id]?>" value="">
					<input type="hidden" name="cp_it_price[]" class="cp_it_price" value="<?=$cp[cp_it_price]?>">
				</li>
			<? } ?>
			</ul>


			<div id="coupon_btn">
				<a id="coupon_apply">쿠폰사용하기</a>
			</div>

		</div>






	</div>
	<!-- //쿠폰  -->
	<? } ?>



	<!-- 최종결제금액 -->
	<div class="cart_order_title_wrap cart_order_title_click" data-detail="price_detail">
		<div class="cart_order_title">최종결제금액</div>
		<div class="cart_order_title_value_wrap price_title">
			<p class="cart_order_title_value" id="span_pay_total_price"><?=$return[cart_total_price]?></p>
			<p class="cart_order_title_value_count">원</p>
		</div>
		<a class="cart_order_title_btn"><i></i></a>
	</div>
	<div class="price_detail" style="display:none;">
		<ul>
			<li><p>상품금액</p><span><?=$return[item_total_price]?>원</span></li>
			<li><p>배송비</p><span><?=$return[delivery_total_price]?>원</span></li>
			<li style="display:none;"><p>할인금액</p><span><a id="span_discount_price">0</a>원</span></li>
			<li><p>적립금 사용</p><span><a id="span_money_price">0</a>원</span></li>
			<li><p>쿠폰 사용</p><span><a id="span_coupon_price" class="span_coupon_price">0</a>원</span></li>
		</ul>
	</div>
	<!-- //최종결제금액 -->


	<!-- 결제수단 -->
	<div class="cart_order_title_wrap cart_order_title_click" data-title="payment_title" data-detail="payment_detail">
		<div class="cart_order_title">결제수단</div>
		<div class="cart_order_title_value_wrap payment_title">
			<p class="cart_order_title_value">신용카드</p>
			<p class="cart_order_title_value_count"></p>
		</div>
		<a class="cart_order_title_btn"><i></i></a>
	</div>
	<div class="payment_detail" style="display:none;">

		<div class="payment_type_wrap"><?=admin_radio($write,"payment_type","payment_type","","0")?></div>

		<div id="payment_card" class="payment_wrap">
			<?=admin_select($write,"card_code","","","0")?>
			<?=admin_select($write,"card_quota","","","0")?>
		</div>

		<div id="payment_vbanking" class="payment_wrap">
			<p class="memo"><?=$return[memo][vbanking]?></p>
		</div>
		
		<div id="payment_banking" class="payment_wrap">
			<?=admin_select($write,"bank_number","","","0")?>
			<?=admin_text($write,"bank_name","","placeholder=\"입금자명\"")?>
			<p class="memo"><?=$return[memo][banking]?></p>
		</div>

		<div id="payment_hp" class="payment_wrap">
			<p class="memo"><?=$return[memo][hp]?></p>
		</div>

		<div class="hide" id="cashreceipt_div">
			<p>현금영수증 신청</p>
			<?=admin_radio($write,"od_cashreceipt_method")?>
			<div class="od_cashreceipt_method_wrap">
				<?=admin_select($write,"od_cashreceipt_type","","","1")?>
				<?=admin_text($write,"od_cashreceipt_val")?>
			</div>
		</div>

		<div class="hide" id="refund_div">
			<p>환불계좌정보</p>
			<div class="od_refund_bank_wrap">
				<?=admin_select($write,"od_refund_bank","","","0")?>
				<?=admin_text($write,"od_refund_name","","placeholder=\"계좌번호 예금주\"")?>
			</div>
			<?=admin_text($write,"od_refund_account","","placeholder=\"환불계좌번호\"")?>
		</div>

	</div>
	<!-- //결제수단 -->

	<!-- 약관동의 -->
	<div class="cart_order_title_wrap">
		<div class="cart_order_title"><?=admin_checkbox($write, "chkall", "", "", "약관 전체 동의하기")?></div>
		<a class="cart_order_title_btn cart_order_title_click" data-title="agree_title" data-detail="agree_detail"><i></i></a>
	</div>
	<div class="agree_detail">
		<div class="agree"><?=admin_checkbox($write,"chk1","chk chk1", "", "개인정보 제3자 제공에 동의")?><a class="agree_popup_btn" data-popup_id="1">보기</a></div>
		<div class="agree"><?=admin_checkbox($write,"chk2","chk chk2", "", "결제대행서비스 이용약관에 동의")?><a class="agree_popup_btn" data-popup_id="2">보기</a></div>
		<div class="agree"><?=admin_checkbox($write,"chk3","chk chk3", "", "구매조건 확인 및 취소·환불 규정에 동의")?></div>
	</div>
	<!-- //약관동의 -->
	
	<a id="payment_btn">결제하기</a>
	
</div>
</form>

<div id="zipcode_wrap"></div>

<script>
var cart_total_price = parseInt(<?=str_number($return[cart_total_price])?>);
var mb_money = parseInt(<?=str_number($return[mb_money])?>);
var current_scroll = 0;

$(document).on("click","input[name=od_cashreceipt_method]",function(){ 
	$("#od_cashreceipt_type option").remove();
	if(this.value=="1"){
		$("#od_cashreceipt_type").append("<option value='1'>휴대폰번호</option>");
		$("#od_cashreceipt_type").append("<option value='2'>현금영수증 카드번호</option>");
		$(".od_cashreceipt_method_wrap").show();
	} 
	if(this.value=="2"){
		$("#od_cashreceipt_type").append("<option value='3'>사업자등록번호</option>");
		$("#od_cashreceipt_type").append("<option value='4'>현금영수증 카드번호</option>");
		$(".od_cashreceipt_method_wrap").show();
		$("#od_cashreceipt_val").val("");
	}
	if(this.value=="3"){
		$(".od_cashreceipt_method_wrap").hide();
		$("#od_cashreceipt_val").val("");
	}
});

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

$(document).on("click",".payment_type",function(){
	var payment_type = $('.payment_type:checked').val();
	if(payment_type=="iche" || payment_type=="vbanking" || payment_type=="banking"){
		$("#cashreceipt_div").removeClass("hide");
	} else{
		$("#cashreceipt_div").addClass("hide");
	}
	if(payment_type=="vbanking" || payment_type=="banking"){		
		$("#refund_div").removeClass("hide");
	} else{
		$("#refund_div").addClass("hide");
	}
	$(".payment_wrap").hide();
	$("#payment_"+payment_type).show();
});

$(document).on("change", "#od_dy_msg_type", function(){
	var od_dy_msg_type = $(this).val();
	if(od_dy_msg_type=="직접 입력"){
		$("#od_dy_msg").removeClass("hide").val("").focus();
	} else{
		$("#od_dy_msg").addClass("hide").val(od_dy_msg_type);
	}
});

$(document).on("click",".all_money_btn",function(){
	if($("#money_price").attr("disabled") != "disabled"){
		var money_price = 0;
		if(mb_money > cart_total_price-total_coupon_price){
			money_price = cart_total_price-total_coupon_price;
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

var total_coupon_price = 0;

function check_money(){
	
	if($("#use_coupon").is(":checked")) {
		total_coupon_price = 0;
		$(".coupon_select option:selected").each(function(){
			if($(this).data("discount_price")){
				total_coupon_price += parseInt($(this).data("discount_price"));
				$("#cp_"+$(this).data("it_id")).val($(this).data("discount_price"));
			} else{
				$("#cp_"+$(this).data("it_id")).val("");
			}
		});
		$(".span_coupon_price").html(number_format(total_coupon_price));
	} else{
		total_coupon_price = 0;
		$(".span_coupon_price").html("0");
		$(".coupon_select").val("");
		$(".cp_discount_price").val("");
	}

	var money_price = parseInt($("#money_price").val());
	if(!money_price || isNaN(money_price) || money_price<0 || mb_money < money_price || cart_total_price < money_price || cart_total_price < money_price + total_coupon_price){
		if(mb_money < money_price){
			alert("보유하신 적립금이 입력하신 적립금액보다 적습니다.");
		}
		if(cart_total_price < money_price || cart_total_price < money_price + total_coupon_price){
			if(money_price > 0){
				alert("결제 금액 만큼만 적립금을 입력해주세요.");
			} else{
				alert("결제 금액 만큼만 쿠폰을 선택해주세요.");

				total_coupon_price = 0;
				$(".span_coupon_price").html("0");
				$(".coupon_select").val("");
				$(".cp_discount_price").val("");
				$("#use_coupon").prop("checked", false);
			}
		}
		$("#money_price").val("0");
		money_price = 0;
	}

	var pay_total_price = cart_total_price - money_price - total_coupon_price;
	var discount_total_price = money_price;

	$("#span_money_price").html(number_format(money_price));
	$("#span_pay_total_price").html(number_format(pay_total_price));
	$("#span_discount_price").html(number_format(discount_total_price));

}

$(document).on("click","#zipcode_btn, #dy_zip, #dy_addr1",function(){
	current_scroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
	zipcode("dy_zip","dy_addr1","dy_addr2");
	$(".cart_wrap").hide();
	$(".btn_back").attr("href","javascript:zipcode_close()");
});

function zipcode_close(){
	var element_wrap = document.getElementById('zipcode_wrap');
	element_wrap.style.display = 'none';
	$(".cart_wrap").show();
	document.body.scrollTop = current_scroll;
	$(".btn_back").attr("href","javascript:history.back()");
}

function zipcode(zipcode,addr1,addr2){
	var element_wrap = document.getElementById('zipcode_wrap');
	new daum.Postcode({
		oncomplete: function(data) {
			var fullAddr = data.address;
			var extraAddr = '';
			if(data.addressType === 'R'){
				if(data.bname !== ''){
					extraAddr += data.bname;
				}
				if(data.buildingName !== ''){
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
			}
			document.getElementById(zipcode).value = data.zonecode;
			document.getElementById(addr1).value = fullAddr;
			$(".cart_wrap").show();
			element_wrap.style.display = 'none';
			document.body.scrollTop = current_scroll;
			document.getElementById(addr2).focus();
		},
		onresize : function(size) {
			element_wrap.style.height = size.height+'px';
		},
		width : '100%',
		height : '100%'
	}).embed(element_wrap);

	element_wrap.style.display = 'block';
}

// 펼쳐보기
$(document).on("click",".cart_order_title_click",function(){
	var title = $(this).data("title");
	var detail = $(this).data("detail");
	if($(this).data("title") == "ticket_title"){
		var od_pass = $("#od_pass").val();
		var od_name = $("#od_name").val();
		var od_hp = $("#od_hp").val();
		var od_email = $("#od_email").val();
		
		<? if(!$member[mb_no]){ ?>
		if(!od_pass){
			alert("주문 패스워드를 입력해주세요");
			$("#od_pass").focus();
			return;
		}	
		<? } ?>

		if(!od_name){
			alert("사용자 이름을 입력해주세요");
			$("#od_name").focus();
			return;
		}		
		if(!od_hp){
			alert("사용자 휴대폰번호를 입력해주세요");
			$("#od_hp").focus();
			return;
		}		
		if(!od_email){
			alert("사용자 이메일주소를 입력해주세요");
			$("#od_email").focus();
			return;
		}
		var cart_order_title_value = od_name + " / " + od_hp;
		$(".ticket_title .cart_order_title_value").html(cart_order_title_value);
	}
	if($(this).data("title") == "od_dy_msg_title"){
		var od_dy_msg_type = $("#od_dy_msg_type").val();
		var cart_order_title_value = "";
		if(od_dy_msg_type=="직접 입력"){
			cart_order_title_value = $("#od_dy_msg").val();
		} else{
			cart_order_title_value = $("#od_dy_msg_type option:selected").text();
		}
		$(".od_dy_msg_title .cart_order_title_value").html(cart_order_title_value);
	}
	if($(this).data("title") == "payment_title"){
		var cart_order_title_value = $('.payment_type:checked').closest("label").text();
		$(".payment_title .cart_order_title_value").html(cart_order_title_value);
	}
	if($(this).data("title") == "money_title"){
		var cart_order_title_value = $("#span_money_price").html();
		$(".money_title .cart_order_title_value").html(cart_order_title_value);
	}
	$("."+title).toggle();
	$("."+detail).toggle();
});

function address_tab_select(ty){
	$(".address_tab li").removeClass("on");
	$(".address_tab li:nth-child("+ty+")").addClass("on");
	$(".address_wrap").hide();
	$(".address_wrap"+ty).show();
	$("#dy_info").val(ty);		
}

$(document).on("click",".delivery_change",function(){
	$(".delivery_change").addClass("hide");
	$(".address_all_wrap").removeClass("hide");
	$(".address_select_wrap").addClass("hide");
	return;
});

$(document).on("click",".address_tab li",function(){
	var dy_info = $(this).data("dy_info");
	if(dy_info=="1" && $(".address_list li").length == 0){
		return;				
	}
	if(dy_info=="1"){
		$("#dy_nick").val("");
		$("#dy_name").val("");
		$("#dy_hp").val("");
		$("#dy_zip").val("");
		$("#dy_addr1").val("");
		$("#dy_addr2").val("");
		$("#dy_basic").prop("checked", false);
	}
	address_tab_select(dy_info);	
});

$(document).on("click",".myaddress_update",function(){
	var my_id = $(this).data("my_id");
	var my_nick = $(this).data("my_nick");
	var my_name = $(this).data("my_name");
	var my_hp = $(this).data("my_hp");
	var my_zip = $(this).data("my_zip");
	var my_addr1 = $(this).data("my_addr1");
	var my_addr2 = $(this).data("my_addr2");
	var my_basic = $(this).data("my_basic");
	$("#dy_nick").val(my_nick);
	$("#dy_name").val(my_name);
	$("#dy_hp").val(my_hp);
	$("#dy_zip").val(my_zip);
	$("#dy_addr1").val(my_addr1);
	$("#dy_addr2").val(my_addr2);
	if(my_basic=="1"){
		$("#dy_basic").prop("checked", true);
	} else{
		$("#dy_basic").prop("checked", false);
	}
	address_tab_select("2");
});

$(document).on("click",".myaddress_delete",function(){
	var my_id = $(this).data("my_id");
	var my_nick = $(this).data("my_nick");
	if(confirm("배송지목록중 '"+my_nick+"'을 삭제하시겠습니까?")){
		$.ajax({
			type: "post",
			data : "mode=myaddress_delete&my_id="+my_id,
			url: "cart_order.php",
			success: function(response){
				var json = $.parseJSON(response); 
				if(json["result"]=="delete"){
					$("#address_list_li_"+my_id).remove();								
					if($(".address_list li").length == 0){
						address_tab_select("2");												
					}
				} else{
					alert(json["msg"]);
				}
			}
		});
	}
});

$(document).on("click","#payment_btn",function(){
	var payment_type = $('.payment_type:checked').val();
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
					<?php } ?>
					$("#pg_id").val(json["pg_id"]);
					cart_payment();
				}
			} else{
				$("#payment_btn").show();
				if(json["msg"]){
					alert(json["msg"]);
				}
				if(json["result"]){
					$("#"+json["result"]).focus();
				}
			}
		}
	});
});
</script>





















<script>

$(document).on("click", ".agree_popup_btn", function(){
	var popup_id = $(this).data("popup_id");
	$(".dimed").hide();
	$(".app_popup"+popup_id).show();
});

$(document).on("click", ".pop_layer .close, .pop_layer .btn", function(){
	$(".dimed").hide();
});
</script>

<style>
.app_popup.dimed { background: rgba(0,0,0,.6);}
.app_popup { position: fixed; top:0; right:0;bottom:0;  left:0; z-index:99999;}
.pop_layer { display: block; position: absolute;  top: 20%;   right: 10px;  left: 10px;  -webkit-box-sizing: border-box;  -moz-box-sizing: border-box;  box-sizing: border-box;   padding: 20px; border:solid 1px #efefef;  background: #fff;}
.app_popup .pop_layer .close { position: absolute; top:0px; right:0px;  width: 15px;   height: 15px;background: url(/skin/nfor/img/ccooll.png) no-repeat; font-size:0; }
.app_popup .pop_layer .wp{position: relative; }
.app_popup .pop_layer .wp > h3 { position: relative; top: -2px;  margin-bottom: 20px; font-weight: 700; font-size: 17px; line-height: 24px;color: #16181a; word-break: keep-all; }
.app_popup .pop_layer .cont{line-height: 16px; font-size:12px; color: #6c7580; margin-bottom:20px; height:150px; overflow-y:scroll}
.app_popup .pop_layer .btn { display:block; height: 47px; border-color: #f27935;background: #e83862; line-height: 47px; color: #fff; text-align:center;}
</style>
<div class="app_popup app_popup1 dimed" style="display:none;">
	<div class="app_popup" style="display: block;">

		<div class="pop_layer">
			<div class="wp">
				<h3>개인정보 제 3자 제공 동의</h3>				
				<div class="cont">
				<?
				$agreement = sql_fetch("select * from nfor_agreement where ag_code='order_privacy'");
				echo $agreement[ag_memo];
				?>
				</div>
				<a class="btn">확인</a>
				<a class="close">닫기</a>
			</div>
		</div>

	</div>
</div>

<div class="app_popup app_popup2 dimed" style="display:none;">
	<div class="app_popup" style="display: block;">

		<div class="pop_layer">
			<div class="wp">
				<h3>결제대행서비스 이용약관</h3>				
				<div class="cont">
				<?
				$agreement = sql_fetch("select * from nfor_agreement where ag_code='order_agreement'");
				echo $agreement[ag_memo];
				?>
				</div>
				<a class="btn">확인</a>
				<a class="close">닫기</a>
			</div>
		</div>

	</div>
</div>






<?php
include_once "pg_".$nfor[pg_type]."_mobile.php";

include_once $nfor[skin_path]."tail.php";
?>