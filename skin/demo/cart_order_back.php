<?php
include_once $nfor[skin_path]."head.php";
?>
<!-- 회원정보 -->
<input type="hidden" id="mb_name" value="<?=$member[mb_name]?>">
<input type="hidden" id="mb_hp" value="<?=$member[mb_hp]?>">
<input type="hidden" id="mb_tel" value="<?=$member[mb_tel]?>">
<input type="hidden" id="mb_zip" value="<?=$member[mb_zipcode]?>">
<input type="hidden" id="mb_addr1" value="<?=$member[mb_addr1]?>">
<input type="hidden" id="mb_addr2" value="<?=$member[mb_addr2]?>">
<!-- //회원정보 -->

<!-- 최근배송지정보 -->
<input type="hidden" id="last_name" value="<?=$last[dy_name]?>">
<input type="hidden" id="last_hp" value="<?=$last[dy_hp]?>">
<input type="hidden" id="last_tel" value="<?=$last[dy_tel]?>">
<input type="hidden" id="last_zip" value="<?=$last[dy_zip]?>">
<input type="hidden" id="last_addr1" value="<?=$last[dy_addr1]?>">
<input type="hidden" id="last_addr2" value="<?=$last[dy_addr2]?>">
<!-- //최근배송지정보 -->


<!-- 기본배송지정보 -->
<input type="hidden" id="my_name" value="<?=$chk_myaddress[my_name]?>">
<input type="hidden" id="my_hp" value="<?=$chk_myaddress[my_hp]?>">
<input type="hidden" id="my_tel" value="<?=$chk_myaddress[my_tel]?>">
<input type="hidden" id="my_zip" value="<?=$chk_myaddress[my_zip]?>">
<input type="hidden" id="my_addr1" value="<?=$chk_myaddress[my_addr1]?>">
<input type="hidden" id="my_addr2" value="<?=$chk_myaddress[my_addr2]?>">
<!-- //기본배송지정보 -->
<script type="text/javascript">
<!--

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
		$("#dy_name").val($("#mb_name").val());
		$("#dy_hp").val($("#mb_hp").val());
		$("#dy_tel").val($("#mb_tel").val());
		$("#dy_zip").val($("#mb_zip").val());
		$("#dy_addr1").val($("#mb_addr1").val());
		$("#dy_addr2").val($("#mb_addr2").val());
	} else if(dy_type=="3"){ // 최근배송지 정보
		$("#dy_name").val($("#last_name").val());
		$("#dy_hp").val($("#last_hp").val());
		$("#dy_tel").val($("#last_tel").val());
		$("#dy_zip").val($("#last_zip").val());
		$("#dy_addr1").val($("#last_addr1").val());
		$("#dy_addr2").val($("#last_addr2").val());
	} else if(dy_type=="4"){ // 새로운 배송지
		$("#dy_name").val("");
		$("#dy_hp").val("");
		$("#dy_tel").val("");
		$("#dy_zip").val("");
		$("#dy_addr1").val("");
		$("#dy_addr2").val("");
	} else if(dy_type=="5"){ // 기본배송지 정보
		$("#dy_name").val($("#my_name").val());
		$("#dy_hp").val($("#my_hp").val());
		$("#dy_tel").val($("#my_tel").val());
		$("#dy_zip").val($("#my_zip").val());
		$("#dy_addr1").val($("#my_addr1").val());
		$("#dy_addr2").val($("#my_addr2").val());
	} else{

	}

});

//-->
</script>





<div class="cart_wrap">


	<div class="cart_inner">
		<div class="my_title fotm"><?=$nfor[title]?></div>
		<div class="my_title_sub fotr">주문하신 상품의 자세한 옵션과 가격은 아래 상품정보에서 확인하실 수 있습니다.</div>
		<div class="cartimg"><img src="<?=$nfor[skin_path]?>img/pay_pros_01.png"><img src="<?=$nfor[skin_path]?>img/pay_pros_02_ov.png"><img src="<?=$nfor[skin_path]?>img/pay_pros_03.png"></div>
	</div>


	<form name="fcart_order" id="fcart_order" method="post">
	<input type="hidden" name="ss_cart_id_new" value="<?=$ss_cart_id_new?>">


	<!-- 구매자정보 -->
	<div class="order_infor">

		<div class="h_txt fotm">구매자 정보</div>

			<table class="tbl" cellspacing="0" border="0" summary="구매자정보">
			<colgroup>
                <col width="134">
				<col width="*">
            </colgroup>
			<? if($member[mb_no]){ ?>
			<tr class="frst">
				<th><span class="h_sub_txt">이름</span></th>
				<td><?=$member[mb_name]?><input type="hidden" name="od_name" id="od_name" value="<?=$member[mb_name]?>"></td>
			</tr>
			<tr>
				<th><span class="h_sub_txt">이메일</span></th>
				<td><?=$member[mb_email]?><input type="hidden" name="od_email" id="od_email" value="<?=$member[mb_email]?>"></td>
			</tr>
			<tr class="last">
				<th><span class="h_sub_txt">휴대폰</span></th>
				<td><?=$member[mb_hp]?><input type="hidden" name="od_hp" id="od_hp" value="<?=$member[mb_hp]?>"></td>
			</tr>
			<tr class="hide">
				<th><span class="h_sub_txt">일반전화</span></th>
				<td><?=$member[mb_tel]?><input type="hidden" name="od_tel" id="od_tel" value="<?=$member[mb_tel]?>"></td>
			</tr>
			<tr class="hide">
				<th><span class="h_sub_txt">주소</span></th>
				<td>
					<?=$member[mb_zipcode]?> <?=$member[mb_addr1]?> <?=$member[mb_addr2]?>
					<input type="hidden" name="od_zip" id="od_zip" value="<?=$member[mb_zipcode]?>">
					<input type="hidden" name="od_addr1" id="od_addr1" value="<?=$member[mb_addr1]?>">
					<input type="hidden" name="od_addr2" id="od_addr2" value="<?=$member[mb_addr2]?>">
				</td>
			</tr>
			<? } else{ ?>
			<tr>
				<th><span class="h_sub_txt">이름</span></th>
				<td><input type="text" name="od_name" id="od_name" placeholder="이름" class="order_input"></td>
			</tr>
			<tr>
				<th><span class="h_sub_txt">이메일</span></th>
				<td><input type="text" name="od_email" id="od_email" placeholder="이메일" class="order_input"></td>
			</tr>
			<tr>
				<th><span class="h_sub_txt">휴대폰</span></th>
				<td><input type="text" name="od_hp" id="od_hp" placeholder="휴대폰" class="order_input"></td>
			</tr>
			<tr>
				<th><span class="h_sub_txt">일반전화</span></th>
				<td><input type="text" name="od_tel" id="od_tel" placeholder="일반전화" class="order_input"></td>
			</tr>
			<tr class="last">
				<th><span class="h_sub_txt">주소</span></th>
				<td>
					<div style="height:40px; position:relative;">
						<input type="text" name="od_zip" id="od_zip" class="order_input" placeholder="우편번호" readonly><a id="od_zipcode_btn">우편번호찾기</a>
					</div>
					<p>
						<input type="text" name="od_addr1" id="od_addr1" placeholder="주소" readonly style="width:250px;"  class="order_input">
						<input type="text" name="od_addr2" id="od_addr2" placeholder="상세주소" style="width:250px;"  class="order_input">
					</p>
				</td>
			</tr>
			<? } ?>
			</table>

	</div>
	<!-- //구매자정보 -->


	<? if($is_delivery){  ?>
	<!-- 배송지정보 -->
	<div class="delivery_infor">
		<div class="h_txt fotm">배송지 입력</div>

			<table class="tbl" cellspacing="0" border="0" summary="배송지정보">
			<colgroup>
				<col width="134">
				<col width="*">
			</colgroup>
			<tr>
				<th class="frst"><span class="h_sub_txt">배송지선택</span></th>
				<td>

	


					
					


					<? if($member[mb_no]){ ?>

						<label><input type="radio" name="dy_type" class="dy_type" value="2" checked> 회원정보와 동일</label> 	

						<label class="hide"><input type="radio" name="dy_type" class="dy_type" value="1"> 구매자정보와 동일</label> 

						<? if($last[dy_name]){ ?><label><input type="radio" name="dy_type" class="dy_type" value="3"> 최근배송지</label> <? } ?>

						<label><input type="radio" name="dy_type" class="dy_type" value="4"> 직접입력</label>			

						<label><input type="radio" name="dy_type" class="dy_type" value="5"> 기본배송지</label>

						<input type="button" value="배송지등록/선택" onclick="my_address()" class="delbtn"> 

					<? } else{ ?>

						<label><input type="radio" name="dy_type" class="dy_type" value="4" checked>직접입력</label>			
						<label><input type="radio" name="dy_type" class="dy_type" value="1">구매자정보와 동일</label>

					<? } ?>





				</td>
			</tr>
			<tr>
				<th><span class="h_sub_txt">이름</span></th>
				<td><input type="text" name="dy_name" id="dy_name" placeholder="받으시는분 이름" value="<?=$member[mb_name]?>"></td>
			</tr>
			<tr>
				<th><span class="h_sub_txt">휴대전화</span></th>
				<td><input type="text" name="dy_hp" id="dy_hp" value="<?=$member[mb_hp]?>" placeholder="받으시는분 휴대전화번호"></td>
			</tr>
			<tr>
				<th><span class="h_sub_txt">일반전화</span></th>
				<td><input type="text" name="dy_tel" id="dy_tel" value="<?=$member[mb_tel]?>" placeholder="받으시는분 휴대전화번호"></td>
			</tr>
			<tr>
				<th><span class="h_sub_txt">주소</span></th>
				<td>
					<div style="height:40px; position:relative;">
						<input type="text" name="dy_zip" id="dy_zip" placeholder="우편번호" value="<?=$member[mb_zipcode]?>" readonly><a id="zipcode_btn">우편번호찾기</a>
					</div>
					<p>
						<input type="text" name="dy_addr1" id="dy_addr1" placeholder="주소" value="<?=$member[mb_addr1]?>" readonly style="width:250px;">
						<input type="text" name="dy_addr2" id="dy_addr2" placeholder="상세주소" value="<?=$member[mb_addr2]?>" style="width:250px;">
					</p>
				</td>
			</tr>
			<tr class="last">
				<th><span class="h_sub_txt">배송메시지</span></th>
				<td><input type="text" name="dy_msg" id="dy_msg" style="width:500px" placeholder="이곳은 택배기사님이 보시는 입력란입니다.(주문 변경 불가)" class="order_input"></td>
			</tr>
			</table>


			<? if($is_global){ ?>
			<!-- 해외배송상품 추가정보 -->
			<div class="h_txt fotm">해외배송상품 추가정보</div>

			<table class="tbl" cellspacing="0" border="0" summary="해외배송상품 추가정보">
			<colgroup>
				<col width="134">
				<col width="*">
			</colgroup>
			<tr>
				<th><span class="h_sub_txt">개인통관고유부호</span></th>
				<td>
				<input type="text" name="od_global_number" id="od_global_number" placeholder="개인통관고유부호">
				<label style="font-size:12px;"><input type="checkbox" name="chk_global" class="chk_global" value="1"> 해외배송상품 수입신고를 위해 개인 통관부호 수집 및 판매자 제공에 동의합니다.</label> <br>발급하러가기<br><a href="https://unipass.customs.go.kr/csp/persIndex.do" target="_blank">https://unipass.customs.go.kr/csp/persIndex.do</a>
				</td>
			</tr>
			</table>
			<!-- //해외배송상품 추가정보 -->
			<? } ?>






	</div>
	<!-- //배송지정보 -->
	<? } ?>


	
	<? if($member[mb_no]){ ?>
	<!-- 적립금/쿠폰 -->
	<div class="point_infor">
		<div class="h_txt fotm">적립금/쿠폰 사용</div>


			<table class="tbl" cellspacing="0" border="0" summary="배송지정보" >
			<colgroup>
				<col width="134">
				<col width="*">
			</colgroup>
			<tr class="frist">
				<th><span class="h_sub_txt">할인쿠폰</span></th>
				<td>
					<div class="coupon_wrap">
					<input type="checkbox" name="use_coupon" id="use_coupon" style="vertical-align:-3px;"> <label for="use_coupon">할인쿠폰</label>
					<a id="coupon_use_btn">조회 및 사용</a>
					<div class="coupon_price_wrap"><span id="span_coupon_price" class="fotm">0</span> 원</div>
					</div>

					<div id="coupon_list_wrap">

					<ul id="coupon_list" class="couponlist">
					<?
					$cart_que = sql_query("select ct_it_id from nfor_cart where ct_cart_id='$ss_cart_id' and ct_opt_chk='1' group by ct_it_id order by ct_id desc");
					while($cart = sql_fetch_array($cart_que)){
						$item = item($cart[ct_it_id]);
						// 상품금액
						$ea_item_price = sql_fetch("select sum(ct_price2*ct_opt_cnt) as ea_item_price from nfor_cart where ct_cart_id='$ss_cart_id' and ct_it_id='$item[it_id]' and ct_opt_chk='1'");
					?>
						<li>
						<?=$item[it_name]?>
						<input type="hidden" name="cp_it_id[]" value="<?=$item[it_id]?>">
						<select name="cp_id[]" class="coupon_select">
						<option value="">상품쿠폰선택
						<?php // 개별상품할인
						$que = sql_query("select * from nfor_coupon where cp_is_pc='1' and cp_type='1' and cp_sdate<='$nfor[ymdhis]' and cp_edate>='$nfor[ymdhis]' and (cp_all='1' or cp_mb_no='$member[mb_no]') and cp_it_id='$item[it_id]'");
						while($cp1 = sql_fetch_array($que)){
							$chk_cp = sql_fetch("select * from nfor_coupon_use where cu_mb_no='$member[mb_no]' and cu_cp_id='$cp1[cp_id]' and cu_state='1'");
							if(!$chk_cp[cu_id]){
								if($cp1[cp_pay_type]=="1"){ // 할인형태가 원/%
									$discount_price = $cp1[cp_coupon_price];
								} else{
									$discount_price = ($ea_item_price[ea_item_price]/100)*$cp1[cp_coupon_per];
								}
						?>
						<option value="<?=$cp1[cp_id]?>" data-discount_price="<?=$discount_price?>"><?=number_format($discount_price)?>원 할인 (<?=date("~m.d",strtotime($cp1[cp_edate]))?>) <?=$cp1[cp_name]?>
						<? 
							}
						}
						?>


						<?php // 카테고리할인
						if(trim($item[it_category])){
							$add_sql = "";
							$it_category_exp = explode("||",trim($item[it_category]));
							for($k=0; $k<count($it_category_exp)-1; $k++){
								if($k) $add_sql .= " or ";
								$cp_category_id = $it_category_exp[$k];
								$add_sql .= "cp_category_id='$cp_category_id' ";
							}
							if($add_sql) $add_sql = " and ( $add_sql )";
							$sql = "select * from nfor_coupon where cp_is_pc='1' and cp_type='2' and cp_sdate<='$nfor[ymdhis]' and cp_edate>='$nfor[ymdhis]' and (cp_all='1' or cp_mb_no='$member[mb_no]') $add_sql";
							$que = sql_query($sql);
							while($cp2 = sql_fetch_array($que)){
								$chk_cp = sql_fetch("select * from nfor_coupon_use where cu_mb_no='$member[mb_no]' and cu_cp_id='$cp2[cp_id]' and cu_state='1'");
								if(!$chk_cp[cu_id]){
									if($cp2[cp_pay_type]=="1"){
										$discount_price = $cp2[cp_coupon_price];
									} else{
										$discount_price = ($ea_item_price[ea_item_price]/100)*$cp2[cp_coupon_per];
									}
						?>
						<option value="<?=$cp2[cp_id]?>" data-discount_price="<?=$discount_price?>"><?=number_format($discount_price)?>원 할인 (<?=date("~m.d",strtotime($cp2[cp_edate]))?>) <?=$cp2[cp_name]?>
						<? 
								}
							}
						}
						?>
						</select>
						</li>
					<? } ?>
					</ul>


					<div id="coupon_btn">
						<a id="coupon_cancel">취소</a>
						<a id="coupon_apply">쿠폰사용하기</a>
					</div>
					</div>
				</td>
			</tr>
			<tr class="last">
				<th><span class="h_sub_txt">적립금</span></th>
				<td>
					<div class="money_wrap">
						<input type="checkbox" name="use_money" id="use_money" value="1" <?=$config[cf_money]>mb_money($member[mb_no]) || $config[cf_money_min] > $total_price?"disabled='disabled'":""?>> <label for="use_money">적립금 (<?=number_format(mb_money($member[mb_no]))?>원 보유) </label>
						<div class="money_price_wrap"><input type="number" pattern="[0-9]*" name="money_price" id="money_price" value="0" disabled="disabled"> 원&nbsp;</div>
					</div>
				</td>
			</tr>
			</table>



		</div>
	</div>
	<!-- //적립금/쿠폰 -->
	<? } ?>
	


















	<div class="order_product">

		<div class="h_txt fotm">주문상품 </div>
        <div class="my_title_sub fotr">
		※ 장바구니에서만 수량 수정 가능합니다.
		
		(제주도 도서산간인경우 +3000원 추가 됩니다)
		
		</div>
		<table border="0" class="tbl_cart" cellspacing="0" summary="배송지정보">
		<tr>
			<th><span class="h_sub_txt">주문상품 및 옵션</span></th>
			<th><span class="h_sub_txt">상품금액</span></th>
			<th><span class="h_sub_txt">배송비</span></th>
		</tr>
		<?
		$n = 0;
		$cart_que = sql_query("select * from nfor_cart where ct_cart_id='$ss_cart_id' and ct_opt_chk='1' group by ct_it_id order by ct_id desc");
		while($cart = sql_fetch_array($cart_que)){
			$item = item($cart[ct_it_id]);	
		?>
		<tr>
			<td style="padding-bottom:0px;">

				<div class="it_name_wrap">

					<a href="item.php?it_id=<?=$item[it_id]?>">
						<img src="<?=$item[it_img]?thumbnail("$nfor[path]/data/list/$item[it_img]",75,75,0,1):"$nfor[path]/img/noimg_s.png"?>" class="cart_item_img">
						<div class="it_name"><span><?=$item[it_name]?></span></div>
					</a>
				</div>

				<ul class="it_option_wrap">
				<?
				$ct_que = sql_query("select * from nfor_cart where ct_cart_id='$ss_cart_id' and ct_it_id='$cart[ct_it_id]' and ct_opt_chk='1'");
				while($ct = sql_fetch_array($ct_que)){
					$ct_sum[$cart[ct_it_id]] += $ct[ct_price2]*$ct[ct_opt_cnt];
					$opt_name = str_replace("||","/",$ct[ct_value]);
				?>
				<li>
					<div class="option_count_wrap">
					<span class="opt_name" ><?=$opt_name?></span>
					<span class="opt_cnt">수량 <?=$ct[ct_opt_cnt]?>개</span>
					<span class="option_price"><?=number_format($ct[ct_price2]*$ct[ct_opt_cnt])?>원</span>
					</div>
				</li>
				<? 
					$n++;
				} 		
				
				$ct_delivery = ea_delivery_price($ss_cart_id, $cart[ct_it_id],$ct_sum[$cart[ct_it_id]]);	
				?>
				</ul>

			</td>
			<td><span class="it_total_price"><?=number_format($ct_sum[$cart[ct_it_id]])?>원</span></td>
			<td><span class="it_delivery_price"><?=$ct_delivery[price]?number_format($ct_delivery[price])."원":$ct_delivery[state]?></td>
			
		</tr>
		<? 
			$item_total_price += $ct_sum[$cart[ct_it_id]];
		}

		$delivery_total_price = delivery_total_price($ss_cart_id);
		$total_price = $item_total_price + $delivery_total_price;
		?>
		</table>

	</div>





























<!-- 주문금액 -->

	<div class="calculate_area">
		<div class="price">
			<span class="h_area">상품금액</span>
			<span class="result_area"><em ><?=number_format($item_total_price)?></em><span class="won">원</span></span>
		</div>
		<span class="plus" ><img src="/skin/demo/img/pluse_ic.png"></span>
		<div class="der"><span class="h_area">배송비</span><span class="result_area"><em ><?=number_format($delivery_total_price)?></em><span class="won">원</span></div>
		<span class="minus"><img src="/skin/demo/img/mi_ic.png"></span>
		<div class="sale"><span class="h_area">할인금액</span><span class="result_area"><em class="span_discount_price" >0</em><span class="won">원</span></span></div>
		<span class="equal"><img src="/skin/demo/img/eq_ic.png"></span>
		<div class="total"><span class="h_area">결제 예상 금액</span><span class="result_area"><em class="span_pay_total_price" ><?=number_format($total_price)?></em><span class="won">원</span></span></div>
		</div>
	



<div class="payment_infor">
	<div class="h_txt fotm">결제정보입력</div>
	<div class="payment_inner">
		<div class="paymemt_wrap1">
		<div class="left_tit"><b class="h_sub_txt">결제수단 선택</b></div>
			
				<!-- bankin_info -->
				<div class="right_con">
				<ul class="payment_type_menu">
					<li><input type="radio" name="payment_type" class="payment_type" value="card" id="payment_type_card" checked> <label for="payment_type_card">신용카드</label></li>
					<li><input type="radio" name="payment_type" class="payment_type" value="iche" id="payment_type_iche"> <label for="payment_type_iche">계좌이체</label></li>
					<li><input type="radio" name="payment_type" class="payment_type" value="vbanking" id="payment_type_vbanking"> <label for="payment_type_vbanking">가상계좌</label></li>
					<!-- <li><input type="radio" name="payment_type" class="payment_type" value="hp" id="payment_type_hp"> <label for="payment_type_hp">휴대폰</label></li> -->
					<li><input type="radio" name="payment_type" class="payment_type" value="banking" id="payment_type_banking"> <label for="payment_type_banking">무통장입금</label></li>
				</ul>
				<div style="clear:both;"></div>

				 <div id="payment_card" class="payment_wrap">

					<table class="pay_tbl" cellspacing="0" border="0">
						<tr>
						<th><span class="h_sub_txt">카드종류</span></th>
						<td>
							<select name="card_code" id="card_code" style="border:solid 1px #e7e7e9;height:25px;padding-left:5px;width:180px;">
							<option value="">카드선택
							<?
							$que = sql_query("select * from nfor_pg_code where pg_type='$nfor[pg_type]' and pg_payment_type='card'");
							while($data = sql_fetch_array($que)){
							?>
							<option value="<?=$data[pg_code]?>"><?=$data[pg_name]?>
							<? } ?>
						</td>
						</tr>
						<tr style="display:none;">
						<th><span class="h_sub_txt">할부기간</span></th>
						<td>

							<select name="card_quota" id="card_quota" style="border:solid 1px #e7e7e9;height:25px;padding-left:5px;width:180px;">
							<option value="">할부선택
							<option value="1">일시불
							<?
							for($i=2; $i<=12; $i++){		
							?>
							<option value="<?=$i?>"><?=$i?>개월
							<? } ?>
							</select>

						</td>
						</tr>
					</table>




				</div>

				<style>
				.memo {display:block;font-family:Malgun Gothic,Dotum,applegothic,sans-serif,arial !important;font-size:11px; color:#767676;line-height:15px; border:solid 1px #e7e7e9;background-color:#FAFAFA; padding:10px;}
				.memo2 {display:block;font-family:Malgun Gothic,Dotum,applegothic,sans-serif,arial !important;font-size:11px; color:#767676;line-height:15px;  padding:5px;}
				</style>

				<div id="payment_vbanking" class="payment_wrap">

					<div class="memo">
						<p>※ <?=date("Y년 m월 d일",strtotime("+".$config[cf_vbanking_limit]."day"))?>까지 미입금시 주문은 자동 취소됩니다.</p>
						<p>※ 결제완료 이후 주문취소가 발생할경우 무통장입금을 통해서 환불이 진행되며 환불계좌설정은 마이페이지 > 무통장 환불계좌 설정 메뉴를 통해서 설정가능합니다.</p>
					</div>

				</div>



				 <div id="payment_banking" class="payment_wrap">
					<table class="pay_tbl" cellspacing="0" border="0">
						<tr>
						<th><span class="h_sub_txt">입금은행</span></th>
						<td>
							<select name="bank_number" id="bank_number" style="border:solid 1px #e7e7e9;height:25px;padding-left:5px;">
							<? if($item[it_category]!="035201"){
							$que_banking = sql_query("select * from nfor_banking where bnk_use='1' order by bnk_rank asc");
							while($data = sql_fetch_array($que_banking)){
							?>
							<option value="<?=$data[bnk_bank]?> <?=$data[bnk_number]?> <?=$data[bnk_name]?>"><?=$data[bnk_bank]?> <?=$data[bnk_number]?> <?=$data[bnk_name]?></option>
							<? } 
							}else{?>
							<option value="하나은행 353-910026-26604 (주)골마켓">하나은행 353-910026-26604 (주)골마켓</option>
							<?}?>
							</select>

						</td>
						</tr>
						<tr>
						<th><span class="h_sub_txt">입금자명</span></th>
						<td><input type="text" name="bank_name" id="bank_name" placeholder="입금하시는분의 이름" value="<?=$member[mb_name]?>" style="border:solid 1px #e7e7e9;height:25px;padding-left:5px;"></td>
						</tr>
						<tr>
						<th><span class="h_sub_txt">입금기한</span></th>
						<td>
							<?=date("Y년 m월 d일",strtotime("+".$config[cf_banking_limit]."day"))?> 까지
						</td>
						</tr>
					</table>
					<div class="memo2">
						<p>※ <?=date("Y년 m월 d일",strtotime("+".$config[cf_banking_limit]."day"))?>까지 미입금시 주문은 자동 취소됩니다.</p>
						<p>※ 결제완료 이후 주문취소가 발생할경우 무통장입금을 통해서 환불이 진행되며 환불계좌설정은 마이페이지 > 무통장 환불계좌 설정 메뉴를 통해서 설정가능합니다.</p>
					</div>

				</div>


				<div id="payment_hp" class="payment_wrap">
					<div class="memo">
						<p>※ 휴대폰결제는 최대 20만원까지 결제 가능하며, 고객님께서 사용하시는 이동통신사 및 결제 등급에 따라 결제한도가 제한될수 있습니다.</p>
						<p>※ 휴대폰결제 취소의 경우 당월에만 가능합니다.</p>
					</div>
				</div>
				<?if($item[it_category]!="035201"){?>
				
				<!-- 현금영수증 -->
				<div class="hide" id="cashreceipt_div" style="padding:10px;">
				<table class="pay_tbl" cellpadding="0" cellspacing="0" border="0">
				<div class="memo2">현금영수증</div>
				<tr>
					<th style="padding:10px 0px;">
					<label><input type="radio" name="od_cashreceipt_method" class="od_cashreceipt_method" value="1"checked>소득공제</label>&nbsp;
					<label><input type="radio" name="od_cashreceipt_method" class="od_cashreceipt_method" value="2">지출증빙</label>&nbsp;
					<label><input type="radio" name="od_cashreceipt_method" class="od_cashreceipt_method" value="3">신청안함</label>
					</th>
				</tr>
				<tr>
					<td>
					<div id="od_cashreceipt_method_1">
						 <select name="od_cashreceipt_type1" id="od_cashreceipt_type1"  style="border:solid 1px #e7e7e9;height:25px;padding-left:5px;">
							<option value="1" selected>휴대폰번호
							<option value="2">현금영수증 카드번호
						 </select>
						 <input type="text" name="od_cashreceipt_val1" id="od_cashreceipt_val1" value="<?=$member[mb_hp]?>"  style="border:solid 1px #e7e7e9;height:25px;padding-left:5px;">
					 </div>


					 <div id="od_cashreceipt_method_2" class="hide">
						 <select name="od_cashreceipt_type2" id="od_cashreceipt_type2" style="border:solid 1px #e7e7e9;height:25px;padding-left:5px;">
							<option value="3" selected>사업자등록번호
							<option value="4">현금영수증 카드번호
						 </select>
						 <input type="text" name="od_cashreceipt_val2" id="od_cashreceipt_val2"  style="border:solid 1px #e7e7e9;height:25px;padding-left:5px;">
					 </div>
					</td>
				</tr>
				</table>

				
				 
				
				<script type="text/javascript">
				<!--
				$(document).on("click","input[name=od_cashreceipt_method]",function(){ 
					nfor_radio_click("od_cashreceipt_method",this.value);
				});

				$(document).on("change","#od_cashreceipt_type1",function(){ 
					$("#od_cashreceipt_val1").val("");
				});

				$(document).on("change","#od_cashreceipt_type2",function(){ 
					$("#od_cashreceipt_val2").val("");
				});
				//-->
				</script>
				</div>
				<!-- 현금영수증 -->		
				
				<?} else {?>&nbsp<?}?>
				
				<!-- 환불계좌 -->
				<div class="hide" id="refund_div" style="padding:10px;">
				<div  class="memo2">환불계좌정보</div>
				<table class="pay_tbl" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th>환불 은행</th>
					<td>
						<select name="od_refund_bank" id="od_refund_bank" style="border:solid 1px #e7e7e9;height:25px;padding-left:5px;">
						<option value="">은행선택
						<?
						$que = sql_query("select * from nfor_pg_code where pg_type='$nfor[pg_type]' and pg_payment_type='vbanking'");
						while($data = sql_fetch_array($que)){
						?>
						<option value="<?=$data[pg_code]?>" <?=$member[mb_bank_name]==$data[pg_code]?"selected":""?>><?=$data[pg_name]?>(<?=$data[pg_code]?>)
						<? } ?>
						</select>
					</td>
				</tr>
				<tr>
					<th>환불 계좌번호</th>
					<td><input type="text" name="od_refund_account" id="od_refund_account" value="<?=$member[mb_bank_account]?>" style="width:200px;border:solid 1px #e7e7e9;height:25px;padding-left:5px;" placeholder="환불계좌번호"></td>
				</tr>
				<tr>
					<th>환불 예금주</th>
					<td><input type="text" name="od_refund_name" id="od_refund_name" value="<?=$member[mb_name]?>" style="width:100px;border:solid 1px #e7e7e9;height:25px;padding-left:5px;" placeholder="계좌번호 예금주"></td>
				</tr>
				</table>
			

				</div>
				<!-- //환불계좌 -->





				</div>
				</div>




		<!-- <div class="price">
			<span class="h_area">상품금액</span>
			<span class="result_area"><em ><?=number_format($item_total_price)?></em><span class="won">원</span></span>
		</div>
		<span class="plus" ><img src="/skin/demo/img/pluse_ic.png"></span>
		<div class="der"><span class="h_area">배송비</span><span class="result_area"><em ><?=number_format($delivery_total_price)?></em><span class="won">원</span></div>
		<span class="minus"><img src="/skin/demo/img/mi_ic.png"></span>
		<div class="sale"><span class="h_area">할인금액</span><span class="result_area"><em id="span_discount_price" >0</em><span class="won">원</span></span></div>
		<span class="equal"><img src="/skin/demo/img/eq_ic.png"></span>
		<div class="total"><span class="h_area">결제 예상 금액</span><span class="result_area"><em class="span_pay_total_price" ><?=number_format($total_price)?></em><span class="won">원</span></span></div>
		</div>
	

 -->



					<div class="paymemt_wrap2">
						<table cellpadding="0" border="0" style="width:100%;" class="tbl2">
							<tr>
								<th>상품금액</th>
								<td><em ><?=number_format($item_total_price)?></em><span >원</span></td>
							</tr>
							<tr>
								<th>배송비</th>
								<td><em ><?=number_format($delivery_total_price)?></em><span >원</span></td>
							</tr>
							<tr>
								<th>할인금액</th>
								<td><em id="span_discount_price" class="span_discount_price">0</em><span >원</span></td>
							</tr>
							<tr>
								<th>총결제금액</th>
								<td><em id="span_pay_total_price" class="total_pay span_pay_total_price"><?=number_format($total_price)?></em><span class="won">원</span></td>
							</tr>
						</table>
					</div>
		
				</div>
				<!-- //bankin_info 코딩 -->






<style>
.od_cashreceipt_method{vertical-align:-3px;}

</style>









	



<style>
.order_cation_inner{}
.order_cation_inner input[type=checkbox]{border:solid 1px #DCDCDC; height:15px; width:15px; vertical-align:-5px; margin-right:10px;}
.order_cation_inner .allagree{border-bottom:solid 1px #DCDCDC; height:45px; line-height:45px; margin-bottom:15px; font-size:16px; font-family:'NSKR';}
.order_cation_inner .otheragree{padding:10px 20px; font-size:13px; font-family:'NSKR'; color:#666;}
.order_cation_inner .popbtn{display:inline-block; width:50px; height:20px; line-height:20px;border:1px solid #d0d0d0;color:#4c4c4c;background-color:#fff; letter-spacing:-1px;font-size:11px;vertical-align:middle;cursor:pointer; text-align:center;}

</style>




		<!-- 개인정보 제3자 제공 및 주의사항 동의 -->
		<div class="order_cation">
			<div class="h_txt fotm">약관동의</div>
			<div class="order_cation_inner">

				<div class="allagree"><?=admin_checkbox($row,"chkall")?> <label for="chkall">전체동의</label></div>
				<div class="otheragree"><?=admin_checkbox($row,"chk1","chk chk1")?> <label for="chk1">개인정보 제3자 제공에 동의합니다.</label>  <a href="http://golmarket.co.kr/privacy.php" class="popbtn">상세보기</a> </div>
				<div class="otheragree"><?=admin_checkbox($row,"chk2","chk chk2")?> <label for="chk2">결제대행서비스 이용약관에 동의합니다.</label> <a href="http://golmarket.co.kr/agreement.php" class="popbtn">상세보기</a> </div>
				<div class="otheragree"><?=admin_checkbox($row,"chk3","chk chk3")?> <label for="chk3">주문할 상품설명에 명시된 내용과 사용조건을 확인하였으며, 취소. 환불규정에 동의합니다.</label> </div>

			</div>
		</div>
		<!-- //개인정보 제3자 제공 및 주의사항 동의 -->


		<div style="text-align:center; width:100%;margin-top:20px;" id="display_pay_button" >
			<a id="payment_btn" class="fotm">결제하기</a>
		</div>





	</form>


</div>






<!-- 결제하기 -->


</div>































<script type="text/javascript">
<!--

$(document).on("click", ".chk", function(){
	if($(".chk:checked").length=="3"){
		$("#chkall").prop("checked",true);
	} else{
		$("#chkall").prop("checked",false);
	}
});


$(document).on("click", "#chkall", function(){
	nfor_chk_all(this, 'chk');
});





var cart_total_price = parseInt(<?=(int)$total_price?>);
var mb_money = parseInt(<?=(int)mb_money($member[mb_no])?>);

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

$(document).on("click","#use_money",function(){
	if($("#use_money").is(":checked")) {
		$("#money_price").removeAttr("disabled");
		$("#money_price").val('');
		$("#money_price").focus();
	} else {
		$("#money_price").attr("disabled", true);
		$("#money_price").val(0);
		check_money();
	}
});

$(document).on("change","#money_price",function(){
	check_money();
});

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
			alert("다른상품에 이미 선택된 쿠폰입니다");
			$(this).val("");
			return;
		}
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

function check_money(){

	var money_prce = parseInt($("#money_price").val());

	if(!money_prce){
		$("#money_price").val("0");
		money_prce = 0;
	}

	if(isNaN(money_prce) || money_prce<0){
		alert("숫자만 입력하셔야합니다");
		$("#money_price").val("0");
		money_prce = 0;
	}

	if(mb_money < money_prce){
		alert("보유하신 적립금이 입력하신 적립금액보다 적습니다.");
		$("#money_price").val("0");
		money_prce = 0;
	}

	if(cart_total_price < money_prce){
		alert("결제 금액 만큼만 적립금을 입력해주세요.");
		$("#money_price").val("0");
		money_prce = 0;
	}

	if($("#use_coupon").is(":checked")) {

		var total_coupon_price = 0;
		$(".coupon_select option:selected").each(function(){
			if($(this).data("discount_price")){
				total_coupon_price += parseInt($(this).data("discount_price"));
			}
		});

		$("#span_coupon_price").html(number_format(total_coupon_price));

	} else{

		var total_coupon_price = 0;
		$("#span_coupon_price").html("0");

	}

	if(cart_total_price < money_prce + total_coupon_price){
		alert("결제 금액 만큼만 적립금을 입력해주세요.");
		$("#money_price").val("0");
		money_prce = 0;
	}

	var pay_total_price = cart_total_price - money_prce - total_coupon_price;

	var discount_total_price = money_prce+total_coupon_price;
	
	$(".span_discount_price").html(number_format(discount_total_price));
	$(".span_pay_total_price").html(number_format(pay_total_price));

}

// 결제하기버튼클릭
$(document).on("click","#payment_btn",function(){

	<? if($is_global){ ?>
	if(!$("#od_global_number").val()){
		alert("고유통관부호를 반드시 입력해주세요");
		$("#od_global_number").focus();
		return;
	}
	<? } ?>

	var payment_type = $('.payment_type:checked').val();

	<?
	if($is_guest){	
	?>
	if(!$("#od_name").val()){
		alert("구매자 이름을 입력해주세요");
		$("#od_name").focus();
		return;
	}
	if(!$("#od_email").val()){
		alert("구매자 이메일을 입력해주세요");
		$("#od_email").focus();
		return;
	}
	if(!$("#od_hp").val()){
		alert("구매자 휴대폰번호를 입력해주세요");
		$("#od_hp").focus();
		return;
	}
	<? } ?>

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

	if(  $("#dy_zip").val().length > 5  ){
		alert("우편번호찾기를 통해서 5자리 우편번호를 사용해주세요");
		$("#dy_zip").focus();
		return;
	}
	<? } ?>



	if(payment_type=="card"){		
		if(!$("#card_code").val()){
			alert("카드종류를 선택해 선택해주세요");
			$("#card_code").focus();
			return;
		}

		/*
		if(!$("#card_quota").val()){
			alert("할부기간을 선택해주세요");
			$("#card_quota").focus();
			return;
		}
		*/

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
		alert("취소. 환불규정에 동의하셔야 진행가능합니다");
		$('#chk3').focus();
		return;
    }

	//if(payment_type=="banking"){	
		$("#payment_btn").hide();
	//}

	$.ajax({ 
		type : "post"
		, url : "cart_order_update.php"
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
					$("#mKey").val(json["mKey"]);					
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

// 우편번호창 띄움
$(document).on("click","#zipcode_btn, #dy_zip, #dy_addr1",function(){
	zipcode("dy_zip","dy_addr1","dy_addr2");
});
$(document).on("click","#od_zipcode_btn, #od_zip, #od_addr1",function(){
	zipcode("od_zip","od_addr1","od_addr2");
});

function zipcode(zipcode,addr1,addr2){
	new daum.Postcode({
		oncomplete: function(data) {
			// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

			// 도로명 주소의 노출 규칙에 따라 주소를 조합한다.
			// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
			var fullRoadAddr = data.roadAddress; // 도로명 주소 변수
			var extraRoadAddr = ''; // 도로명 조합형 주소 변수

			// 법정동명이 있을 경우 추가한다. (법정리는 제외)
			// 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
			if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
				extraRoadAddr += data.bname;
			}
			// 건물명이 있고, 공동주택일 경우 추가한다.
			if(data.buildingName !== '' && data.apartment === 'Y'){
			   extraRoadAddr += (extraRoadAddr !== '' ? ', ' + data.buildingName : data.buildingName);
			}
			// 도로명, 지번 조합형 주소가 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
			if(extraRoadAddr !== ''){
				extraRoadAddr = ' (' + extraRoadAddr + ')';
			}
			// 도로명, 지번 주소의 유무에 따라 해당 조합형 주소를 추가한다.
			if(fullRoadAddr !== ''){
				fullRoadAddr += extraRoadAddr;
			}

			// 우편번호와 주소 정보를 해당 필드에 넣는다.
			document.getElementById(zipcode).value = data.zonecode; //5자리 새우편번호 사용
			document.getElementById(addr1).value = fullRoadAddr;
		   // document.getElementById('sample4_jibunAddress').value = data.jibunAddress;

		}
	}).open();
}
//-->
</script>





<?php
include_once "pg_".$nfor[pg_type].".php";

include_once $nfor[skin_path]."tail.php";
?>