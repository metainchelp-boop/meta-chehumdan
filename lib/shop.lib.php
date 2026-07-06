<?php
function it_icon($row){		
	global $nfor;
	$it_icon = "";
	$it_icon_always = explode("||",$row[it_icon_always]);
	for($k=0; $k<count($it_icon_always); $k++){
		if($it_icon_always[$k]){
			$it_icon .= "<img src='$nfor[path]/data/item/icon/".$it_icon_always[$k]."'>";
		}
	}

	if($row[it_icon_sdate] and $row[it_icon_edate]){
		if(strtotime($row[it_icon_sdate]) <= time() and strtotime($row[it_icon_edate]) >= time()){
			$it_icon_period = explode("||",$row[it_icon_period]);
			for($y=0; $y<count($it_icon_period); $y++){
				if($it_icon_period[$y]){
					$it_icon .= "<img src='$nfor[path]/data/item/icon/".$it_icon_period[$y]."'>";
				}
			}
		}
	}
	return $it_icon;
}

function it_qna_cnt_update($qa_it_id_gp){
	$it_qna_cnt = sql_fetch("select count(*) as cnt from nfor_item_qna where qa_parent='0' and qa_it_id_gp='$qa_it_id_gp' and qa_view='1'");
	sql_query("update nfor_item set it_qna_cnt='$it_qna_cnt[cnt]' where it_id_gp='$qa_it_id_gp'");
}

function it_star_cnt_avg_update($st_it_id_gp){
	$it_star_cnt = sql_fetch("select count(*) as cnt from nfor_item_star where st_parent='0' and st_it_id_gp='$st_it_id_gp' and st_view='1'");
	$st_star_avg = sql_fetch("select avg(st_star) as st_star_avg from nfor_item_star where st_parent='0' and st_it_id_gp='$st_it_id_gp' and st_view='1'");
	$it_star_avg = sprintf("%d",$st_star_avg[st_star_avg]);

	
	$it_star_cnt_detail = "";
	$que = sql_query("select st_star, count(*) as cnt from nfor_item_star where st_parent='0' and st_it_id_gp='$st_it_id_gp' and st_view='1' group by st_star");
	while($data = sql_fetch_array($que)){
		if($it_star_cnt_detail) $it_star_cnt_detail .= "||"; 
		$it_star_cnt_detail .= $data[st_star]."/".$data[cnt];
	}

	sql_query("update nfor_item set it_star_cnt='$it_star_cnt[cnt]', it_star_avg='$it_star_avg', it_star_cnt_detail='$it_star_cnt_detail' where it_id_gp='$st_it_id_gp'");
}

function chk_category_coupon($str,$find){
	$search = 0;
	$strlen = strlen($find);
	$it_category_exp = explode("||",trim($str));
	for($k=0; $k<count($it_category_exp); $k++){
		if(substr($it_category_exp[$k],0,$strlen)==$find){
			$search++;
		}
	}
	return $search;
}


// 쿠폰 번호 생성
function coupon_number(){

	$ar = array(A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,1,2,3,4,5,6,7,8,9);
	$ap_len = count($ar);

	$cp1 = rand(0,$ap_len);
	$cp2 = rand(0,$ap_len);
	$cp3 = rand(0,$ap_len);
	$cp4 = rand(0,$ap_len);
	$cp5 = rand(0,$ap_len);
	$cp6 = rand(0,$ap_len);
	$cp7 = rand(0,$ap_len);
	$cp8 = rand(0,$ap_len);
	$cp9 = rand(0,$ap_len);
	$cp10 = rand(0,$ap_len);
	$cp11 = rand(0,$ap_len);
	$cp12 = rand(0,$ap_len);
	$cp13 = rand(0,$ap_len);
	$cp14 = rand(0,$ap_len);
	$cp15 = rand(0,$ap_len);
	$cp16 = rand(0,$ap_len);

	$coupon_1 = $ar[$cp1].$ar[$cp2].$ar[$cp3].$ar[$cp4];
	$coupon_2 = $ar[$cp5].$ar[$cp6].$ar[$cp7].$ar[$cp8];
	$coupon_3 = $ar[$cp9].$ar[$cp10].$ar[$cp11].$ar[$cp12];
	$coupon_4 = $ar[$cp13].$ar[$cp14].$ar[$cp15].$ar[$cp16];

	$cp_number = $coupon_1 . "-" . $coupon_2 . "-" . $coupon_3 . "-" . $coupon_4;

	$cp_number = sql_fetch(" select cp_number from nfor_coupon where cp_number = '$cp_number' ");

	// 쿠폰번호가 이미 존재한다면 쿠폰 번호를 다시 구함
	if ($cp_number[cp_number])
		return coupon_number();

	return $cp_number;
}






/* 관리자 주문 변경 */

function order_wait_cancel_expire(){ // 무통장입금 자동취소
	global $nfor;
	$que = sql_query("select od_id from nfor_order where od_pay_step='4' and od_bank_expire < '$nfor[ymd]'");
	while($order = sql_fetch_array($que)){
		order_wait_cancel($order[od_id]);
	}
}

function order_wait_cancel($od_id,$ct_cancel_why="관리자임의취소"){ // 무통장입금 취소
	$order = sql_fetch("select * from nfor_order where od_id='$od_id'");


	sql_query("update nfor_order set od_pay_step='5', od_cancel_datetime=NOW(), od_cancelrequest_datetime=NOW() where od_id='$order[od_id]'");	// 주문상태를 입금대기취소(5) 으로 변경
	sql_query("update nfor_cart set ct_pay_step='5', ct_cancel_datetime=NOW(), ct_cancelrequest_datetime=NOW(), ct_cancel_why='$ct_cancel_why', ct_cancel_memo='무통장미입금주문' where ct_cart_id='$order[od_cart_id]'");
	
	
	coupon_again($order[od_id]);	
	if($order[od_money_price]) insert_money($order[od_mb_no],$order[od_money_price],"적립금 상품구매 취소","7",$order[od_id]);
	order_stock_update_cancel($order);
	it_sales_volume_update($order[od_cart_id]);
	order_mail($order,"order_cancel");
}



function order_wait_asign($od_id){ // 무통장 입금 확인처리
	global $nfor;
	$order = sql_fetch("select * from nfor_order where od_id='$od_id'");
	sql_query("update nfor_order set od_pay_step='1', od_pay_datetime=NOW(), od_datetime=NOW() where od_id='$order[od_id]'");
	sql_query("update nfor_cart set ct_pay_step='1', ct_pay_datetime=NOW(), ct_datetime=NOW(), ct_dy_step='$nfor[delivery_step]' where ct_cart_id='$order[od_cart_id]'");
	
	ticket_exp_update($order[od_cart_id]);

	order_mail($order,"order_wait_asign");
}

function order_cancelrequest($od_id){ // 주문취소신청
	$order = sql_fetch("select * from nfor_order where od_id='$od_id'");
	sql_query("update nfor_order set od_is_cancel='1', od_pay_step='2', od_cancelrequest_datetime=NOW() where od_id='$order[od_id]'");	// 주문상태를 취소신청(2) 으로 변경

	$ct_cancel_why = "관리자임의취소";
	$ct_cancel_memo = "";
	sql_query("update nfor_cart set ct_pay_step='2', ct_cancelrequest_datetime=NOW(), ct_cancel_why='$ct_cancel_why', ct_cancel_memo='$ct_cancel_memo' where ct_cart_id='$order[od_cart_id]' and ct_pay_step='1'");
	sql_query("update nfor_cart set ct_dy_step='3' where ct_cart_id='$order[od_cart_id]' and ct_dy_step='2'");	// 배송완료(2)일경우 반품신청(3) 처리

	sql_query("update nfor_cart set ct_dy_step='3' where ct_cart_id='$order[od_cart_id]' and (ct_dy_step='5' or ct_dy_step='6')");	// 교환대기(5)/교환완료(6)일경우 반품신청(3) 처리



	order_mail($order,"order_cancelrequest");
}




function order_cancel_check($od_id){ // 주문취소처리체크
	$order = sql_fetch("select * from nfor_order where od_id='$od_id'");

	$delivery_chk = sql_fetch("select ct_id from nfor_cart where ct_cart_id='$order[od_cart_id]' and (ct_dy_step='2' or ct_dy_step='3')");
	if($delivery_chk[ct_id]){
		json_return("배송된 상품이 존재하여 취소가 불가능합니다\\n반품 처리후 진행해주세요","is_delivery");
	}
	$cancel_chk = sql_fetch("select count(*) as cnt from nfor_cart where ct_cart_id='$order[od_cart_id]' and ct_pay_step='3'");
	if($cancel_chk[cnt]){
		json_return("이미 부분취소된 상품이 존재하여 취소가 불가능합니다\\n개별주문관리를 통해서 취소를 진행해주세요","is_cancel");
	}

}


function order_cancel($od_id){ // 주문취소처리
	$order = sql_fetch("select * from nfor_order where od_id='$od_id'");

	sql_query("update nfor_order set od_pay_step='3', od_cancel_datetime=NOW(), od_cancel_price=od_total_price where od_id='$order[od_id]'");
	sql_query("update nfor_cart set ct_pay_step='3', ct_cancel_datetime=NOW() where ct_cart_id='$order[od_cart_id]' and ct_pay_step='2'");
	
	coupon_again($order[od_id]);
	if($order[od_money_price]) insert_money($order[od_mb_no],$order[od_money_price],"적립금 상품구매 취소","7",$order[od_id]);
	order_stock_update_cancel($order);
	it_sales_volume_update($order[od_cart_id]);
	order_mail($order,"order_cancel");
}






function order_asign($od_id){ // 주문취소신청된주문을완료로변경
	$order = sql_fetch("select * from nfor_order where od_id='$od_id'");
	sql_query("update nfor_order set od_pay_step='1', od_cancelrequest_datetime='', od_cancel_datetime='' where od_id='$order[od_id]'");
	sql_query("update nfor_cart set ct_dy_step='2' where ct_cart_id='$order[od_cart_id]' and (ct_dy_step='3' or ct_dy_step='4')");	//  반품신청(3) 또는 반품완료(4)일경우 배송완료(2) 처리
	sql_query("update nfor_cart set ct_pay_step='1', ct_cancel_why='', ct_cancel_memo='', ct_cancelrequest_datetime='' where ct_cart_id='$order[od_cart_id]' and ct_pay_step='2'");	//  주문완료 수정

	$is_cancel = sql_fetch("select count(*) as cnt from nfor_cart where ct_cart_id='$order[od_cart_id]' and (ct_pay_step='2' or ct_pay_step='3')");
	if($is_cancel[cnt]){
		sql_query("update nfor_order set od_is_cancel='1' where od_id='$order[od_id]'");
	} else{
		sql_query("update nfor_order set od_is_cancel='0' where od_id='$order[od_id]'");
	}
}

function order_delete($od_id){ // 주문서삭제
	$order = sql_fetch("select * from nfor_order where od_id='$od_id'");
	sql_query("delete from nfor_order where od_id='$order[od_id]'");
	sql_query("delete from nfor_cart where ct_cart_id='$order[od_cart_id]'");
}







function cart_cancelrequest($ct_id){

	$cancel_why = "관리자임의취소";
	$ct_cancel_memo = "";
	sql_query("update nfor_cart set ct_pay_step='2', ct_cancelrequest_datetime=NOW(), ct_cancel_why='$cancel_why', ct_cancel_memo='$ct_cancel_memo' where ct_id='$ct_id'");
	sql_query("update nfor_cart set ct_dy_step='3' where ct_id='$ct_id' and ct_dy_step='2'");	// 배송완료(2)일경우 반품신청(3) 처리
	sql_query("update nfor_cart set ct_dy_step='3' where ct_id='$ct_id' and (ct_dy_step='5' or ct_dy_step='6')");	// 교환대기(5)/교환완료(6)일경우 반품신청(3) 처리




	$ct = sql_fetch("select * from nfor_cart where ct_id='$ct_id'");
	$order = sql_fetch("select * from nfor_order where od_cart_id='$ct[ct_cart_id]'");
	$chk_cart = sql_fetch("select count(*) as cnt from nfor_cart where ct_cart_id='$order[od_cart_id]' and ct_pay_step='1'");
	if(!$chk_cart[cnt]){
		sql_query("update nfor_order set od_pay_step='2', od_cancelrequest_datetime=NOW() where od_id='$order[od_id]'");	// 주문상태를 취소신청(2) 으로 변경
	}

}

function cart_asign($ct_id){
	sql_query("update nfor_cart set ct_pay_step='1', ct_cancel_memo='', ct_cancel_why='', ct_cancelrequest_datetime='' where ct_id='$ct_id'");	//  주문완료 수정
	sql_query("update nfor_cart set ct_dy_step='2' where ct_id='$ct_id' and (ct_dy_step='3' or ct_dy_step='4')");	//  반품신청(3) 또는 반품완료(4)일경우 배송완료(2) 처리

	$ct = sql_fetch("select * from nfor_cart where ct_id='$ct_id'");
	sql_query("update nfor_order set od_pay_step='1', od_cancel_datetime='' where od_cart_id='$ct[ct_cart_id]'");

	$is_cancel = sql_fetch("select count(*) as cnt from nfor_cart where ct_cart_id='$ct[ct_cart_id]' and (ct_pay_step='2' or ct_pay_step='3')");
	if($is_cancel[cnt]){
		sql_query("update nfor_order set od_is_cancel='1' where od_cart_id='$ct[ct_cart_id]'");
	} else{
		sql_query("update nfor_order set od_is_cancel='0' where od_cart_id='$ct[ct_cart_id]'");
	}	
}


function cart_cancel_check($ct_id){
	$delivery_chk = sql_fetch("select * from nfor_cart where ct_id='$ct_id' and (ct_dy_step='2' or ct_dy_step='3')");
	if($delivery_chk[ct_cart_id]){
		json_return("배송된 상품이 존재하여 취소가 불가능합니다\\n반품 처리후 진행해주세요","is_delivery");
	}
}


function cart_cancel($ct_id){

	$cancel_price = return_price($ct_id);
	sql_query("update nfor_cart set ct_pay_step='3', ct_cancel_datetime=NOW(), ct_cancel_price='$cancel_price' where ct_id='$ct_id'");

	$ct = sql_fetch("select * from nfor_cart where ct_id='$ct_id'");
	$total_ct1 = sql_fetch("select count(*) as cnt from nfor_cart where ct_cart_id='$ct[ct_cart_id]'");
	$total_ct2 = sql_fetch("select count(*) as cnt from nfor_cart where ct_cart_id='$ct[ct_cart_id]' and ct_pay_step='3'");
	if($total_ct1[cnt]==$total_ct2[cnt]){
		sql_query("update nfor_order set od_pay_step='3', od_cancel_datetime=NOW() where od_cart_id='$ct[ct_cart_id]'");
	}

	$order = sql_fetch("select * from nfor_order where od_cart_id='$ct[ct_cart_id]'");

	$total_ct3 = sql_fetch("select count(*) as cnt from nfor_cart where ct_cart_id='$ct[ct_cart_id]' and ct_it_id='$ct[ct_it_id]'");
	$total_ct4 = sql_fetch("select count(*) as cnt from nfor_cart where ct_cart_id='$ct[ct_cart_id]' and ct_it_id='$ct[ct_it_id]' and ct_pay_step='3'");
	if($total_ct3[cnt]==$total_ct4[cnt]){
		coupon_again($order[od_id],$ct[ct_it_id]);
	}
// 확인필요
	sql_query("update nfor_cart set ct_my_cancel_price='".return_money_coupon($ct[ct_id])."' where ct_id='$ct[ct_id]'");
	insert_money($order[od_mb_no],return_money_coupon($ct[ct_id]),"적립금 상품구매 부분취소","8",$order[od_id],$ct[ct_id]);
// 확인필요
	$cancel_price = $order[od_cancel_price]+return_price($ct_id);	// 취소된금액 + 지금취소되는금액
	sql_query("update nfor_order set od_cancel_price='$cancel_price' where od_cart_id='$ct[ct_cart_id]'");


	order_stock_update_cancel($order,$ct_id);

	it_sales_volume_update($order[od_cart_id]);
}


function cart_send($ct_id, $ct_dy_cp, $ct_dy_num, $ct_tk_num=""){

	$ct = sql_fetch("select * from nfor_cart where ct_id='$ct_id'");
	$order = sql_fetch("select * from nfor_order where od_cart_id='$ct[ct_cart_id]'");

	if($ct_tk_num){
		sql_query("update nfor_cart set ct_tk_send=ct_tk_send+1, ct_tk_num='$ct_tk_num' where ct_id='$ct_id'");
		nfor_send("ticket",$order[od_mb_email],$order[od_mb_hp],$order[od_mb_no],$order[od_id], $ct[ct_id]);
	} else{
		$chk_cnt = sql_fetch("select count(*) as cnt from nfor_cart where ct_cart_id='$ct[ct_cart_id]' and ct_dy_step='2' and ct_dy_num='{$ct_dy_num}' and ct_dy_cp='{$ct_dy_cp}'");
		sql_query("update nfor_cart set ct_dy_step='2', ct_dy_num='{$ct_dy_num}', ct_dy_cp='{$ct_dy_cp}', ct_delivery_datetime=NOW() where ct_id='$ct_id'");
		if(!$chk_cnt[cnt]){
			nfor_send("delivery",$order[od_mb_email],$order[od_mb_hp],$order[od_mb_no],$order[od_id],$ct[ct_id]);
		}
	}
}

/* 관리자 주문 변경 */





function option_change_delete($ss_cart_id){
	global $config;
	
	$change_count = 0;

	$que = sql_query("select * from nfor_cart where ct_cart_id='$ss_cart_id'");
	while($cart = sql_fetch_array($que)){
		$option = sql_fetch("select * from nfor_item_option where opt_id='$cart[ct_opt_id]'");
		$item = sql_fetch("select * from nfor_item where it_id='$cart[ct_it_id]'");

		// 가격이 달라졌거나, 솔드아웃이거나, 구매수량설정이 제한되있으면서 최소구매수량이 옵션재고보다 클경우, 판매재고가 재고에 연동되면서 옵션재고가 구매수량보다 작을경우
		if( $cart[ct_price2] <> $option[opt_price2] or $option[opt_soldout]=="2" or ($item[it_buy_qty_type]=="2" and $option[opt_stock] < $item[it_buy_qty_min]) or ($item[it_stock_type]=="2" and $option[opt_stock] < $cart[ct_opt_cnt]) ){
			sql_query("delete from nfor_cart where ct_id='$cart[ct_id]'");
			$change_count++;
		}

		if($item[it_shopping]=="2"){
			if(strtotime($item[it_paydate]) <= time() and strtotime($item[it_payenddate]) >= time()){ // 기간안에 포함되면
				
			} else{ // 기간을 벚어났으면
				sql_query("delete from nfor_cart where ct_id='$cart[ct_id]'");
				$change_count++;
			}		
		}

	}

	return $change_count;

}


function old_delete($mb_no=""){

	global $config;

	// 오래된 최근본상품 제거
	$recent_datetime = date("Y-m-d H:i:s", strtotime("-{$config[cf_item_recent_hour]} hour"));
	$sql = "delete from nfor_item_view where iv_datetime < '$recent_datetime'";
	if($mb_no){
		$sql .= " and iv_mb_no='$mb_no'";
	}
	sql_query($sql);

	if($config[cf_cart_save_type]=="2" and $config[cf_cart_save_day] >= 1){
		// 장바구니에 담은지 오래된 상품 제거 
		$cart_datetime = date("Y-m-d H:i:s", strtotime("-{$config[cf_cart_save_day]} day"));
		$sql = "delete from nfor_cart where ct_pay_step='0' and ct_state='0' and ct_insert_datetime < '$cart_datetime'";
		if($mb_no){
			$sql .= " and ct_mb_no='$mb_no'";
		}
		sql_query($sql);
	}

}


function cart_info($ss_cart_id){
	$item_total_price = 0;
	$delivery_total_price = 0;
	$i = 0;
	$que = sql_query("select ct_it_id from nfor_cart where ct_cart_id='$ss_cart_id' group by ct_it_id");
	while($data = sql_fetch_array($que)){
		$ea_item_price = sql_fetch("select sum(ct_sprice2) as ea_item_price from nfor_cart where ct_cart_id='$ss_cart_id' and ct_it_id='$data[ct_it_id]' and ct_opt_chk='1'");
		$ea_item_price[ea_item_price] = $ea_item_price[ea_item_price]+0;
		$dyinfo = ea_delivery_price($ss_cart_id, $data[ct_it_id],$ea_item_price[ea_item_price]);
		$item_total_price += $ea_item_price[ea_item_price];
		$delivery_total_price += $dyinfo[price];
		//$return[item][$i][delivery_price] = $dyinfo[price];
		$return[item][$i][item_price] = number_format($ea_item_price[ea_item_price]);
		$return[item][$i][total_price] = number_format($ea_item_price[ea_item_price]+$dyinfo[price]);

		$return[item][$i][it_id] = $data[ct_it_id];
		$return[item][$i][delivery_text] = $dyinfo[price]?number_format($dyinfo[price])."원":$dyinfo[state];
		$i++;
	}
	$cart_total_price = $item_total_price + $delivery_total_price;
	$return[item_total_price] = number_format($item_total_price);
	$return[delivery_total_price] = number_format($delivery_total_price);
	$return[cart_total_price] = number_format($cart_total_price);
	$return[result] = "ok";
	die(json_encode($return));
}






function ticket_number($row){
	if($row[ct_tk_num]){
		$str = $row[ct_tk_num];
	} else{
		$str = strtoupper(sql_old_password($row[ct_id]));
	}
	return $str;
}


function od_sale_echo($row){
	$str = "";
	if($row[od_money_price]){
		$str .= "적립금(".number_format($row[od_money_price]).")<br>";
	}
	if($row[od_coupon_price]){
		$str .= "할인쿠폰(".number_format($row[od_coupon_price]).")<br>";
	}
	return $str;
}


function order_period_echo($od_datetime){
	$time = time()-strtotime($od_datetime);
	return ceil($time/86400);
}






function it_sales_volume_real($it_id,$ct_opt_id=''){
	$sql = "select sum(ct_opt_cnt) as it_sales_volume from nfor_cart where ct_it_id='$it_id' and (ct_pay_step='1' or ct_pay_step='4')";
	if($ct_opt_id) $sql .= " and ct_opt_id='$ct_opt_id'";
	$count = sql_fetch($sql);	// 결제완료, 입금대기
	return $count[it_sales_volume]+0;
}





function it_sales_volume($it_id,$opt_id=''){
	$sql = "select sum(opt_stock_now) as it_sales_volume from nfor_item_option where opt_it_id='$it_id'";
	if($opt_id) $sql .= " and opt_id='$opt_id'";
	$count = sql_fetch($sql);	// 결제완료, 입금대기

	$count[it_sales_volume] = $count[it_sales_volume] + 0;
	return $count[it_sales_volume];
}







function order_mail($order,$step){

	if($step=="order"){

		nfor_send("order",$order[od_mb_email],$order[od_mb_hp],$order[od_mb_no],$order[od_id]);
		$que = sql_query("select * from nfor_cart where ct_cart_id='$order[od_cart_id]' and ct_it_type='2'");
		while($ct = sql_fetch_array($que)){
			$item = sql_fetch("select * from nfor_item where it_id='$ct[ct_it_id]'");
			if($item[it_ticket_type]=="1"){
				nfor_send("ticket",$order[od_mb_email],$order[od_mb_hp],$order[od_mb_no],$order[od_id], $ct[ct_id]);
			}
		}

	} elseif($step=="banking_request"){

		nfor_send("banking_request",$order[od_mb_email],$order[od_mb_hp],$order[od_mb_no],$order[od_id]);

	} elseif($step=="order_wait_asign"){

		nfor_send("order_wait_asign",$order[od_mb_email],$order[od_mb_hp],$order[od_mb_no],$order[od_id]);
		$que = sql_query("select * from nfor_cart where ct_cart_id='$order[od_cart_id]' and ct_it_type='2'");
		while($ct = sql_fetch_array($que)){
			$item = sql_fetch("select * from nfor_item where it_id='$ct[ct_it_id]'");
			if($item[it_ticket_type]=="1"){
				nfor_send("ticket",$order[od_mb_email],$order[od_mb_hp],$order[od_mb_no],$order[od_id], $ct[ct_id]);
			}
		}
	} elseif($step=="order_cancelrequest"){
		nfor_send("order_cancelrequest",$order[od_mb_email],$order[od_mb_hp],$order[od_mb_no],$order[od_id]);
	} elseif($step=="order_cancel"){
		nfor_send("order_cancel",$order[od_mb_email],$order[od_mb_hp],$order[od_mb_no],$order[od_id]);	
	} elseif($step=="ticket_send"){

		$que = sql_query("select * from nfor_cart where ct_cart_id='$order[od_cart_id]' and ct_it_type='2'");
		while($ct = sql_fetch_array($que)){
			$item = sql_fetch("select * from nfor_item where it_id='$ct[ct_it_id]'");
			if($item[it_ticket_type]=="1"){
				nfor_send("ticket",$order[od_mb_email],$order[od_mb_hp],$order[od_mb_no],$order[od_id], $ct[ct_id]);
			}
		}

	} else{

	}

}


function order_update($order,$step,$add_sql=""){
	global $nfor, $_SESSION;

	if($step=="1"){
		sql_query("update nfor_order set od_pay_step='1', od_datetime=NOW(), od_pay_datetime=NOW() $add_sql where od_id='$order[od_id]'");
		sql_query("update nfor_cart set ct_pay_step='1', ct_datetime=NOW(), ct_pay_datetime=NOW(), ct_dy_step='$nfor[delivery_step]' where ct_cart_id='$order[od_cart_id]'");
	}

	if($step=="4"){
		sql_query("update nfor_order set od_pay_step='4', od_datetime=NOW() $add_sql where od_id='$order[od_id]'");
		sql_query("update nfor_cart set ct_pay_step='4', ct_datetime=NOW(), ct_dy_step='$nfor[delivery_step]' where ct_cart_id='$order[od_cart_id]'");
	}

	coupon_use($order[od_id]);

	if($order[od_money_price]) insert_money($order[od_mb_no],$order[od_money_price]*-1,"상품구매","2",$order[od_id]);

	order_stock_update($order);

	cart_history_clear($order[od_mb_no]);

	order_count_update($order[od_mb_no]);

	it_sales_volume_update($order[od_cart_id]);

	ticket_exp_update($order[od_cart_id]);

	sql_query("delete from nfor_cart where ct_cart_id='$_SESSION[cart_id]' and ct_pay_step='0'");
	$_SESSION[cart_id] = "";
}

function order_count_update($mb_no){
	$data = sql_fetch("select count(*) as cnt from nfor_order where od_mb_no='$mb_no' and od_pay_step > 0");
	$mb_order_count = $data[cnt];

	$od_total_price_s = sql_fetch("select sum(od_total_price) as od_total_price_s from nfor_order where od_mb_no='$mb_no' and od_pay_step > 0");
	$mb_order_price = $od_total_price_s[od_total_price_s];

	sql_query("update nfor_member set mb_order_price='$mb_order_price', mb_order_count='$mb_order_count' where mb_no='$mb_no'");

}


function order_stock_update_cancel($order,$ct_id=""){

	$sql = "select * from nfor_cart where ct_cart_id='$order[od_cart_id]'";
	if($ct_id) $sql .= " and ct_id='$ct_id'";
	$que = sql_query($sql);
	while($data = sql_fetch_array($que)){
		$stock_now = it_sales_volume($data[ct_it_id],$data[ct_opt_id]);
		sql_query("update nfor_item_option set opt_stock=opt_stock+$data[ct_opt_cnt], opt_stock_now='$stock_now' where opt_id='$data[ct_opt_id]'");

		$chk_opt = sql_fetch("select * from nfor_item_option where opt_id='$data[ct_opt_id]'");
		if($chk_opt[opt_type]=="1"){
			sql_query("update nfor_item set it_stock='$chk_opt[opt_stock]' where it_id='$chk_opt[opt_it_id]'");
		} elseif($chk_opt[opt_type]=="0"){
			$stock_s = sql_fetch("select sum(opt_stock) as stock_s from nfor_item_option where opt_it_id='$data[ct_it_id]' and opt_type='0'");
			sql_query("update nfor_item set it_stock='$stock_s[stock_s]' where it_id='$chk_opt[opt_it_id]'");
		} else{

		}
	}

}

function order_stock_update($order){

	$que = sql_query("select * from nfor_cart where ct_cart_id='$order[od_cart_id]'");
	while($data = sql_fetch_array($que)){
		$stock_now = it_sales_volume($data[ct_it_id],$data[ct_opt_id]);
		sql_query("update nfor_item_option set opt_stock=opt_stock-$data[ct_opt_cnt], opt_stock_now='$stock_now' where opt_id='$data[ct_opt_id]'");

		$chk_opt = sql_fetch("select * from nfor_item_option where opt_id='$data[ct_opt_id]'");
		if($chk_opt[opt_type]=="1"){
			sql_query("update nfor_item set it_stock='$chk_opt[opt_stock]' where it_id='$chk_opt[opt_it_id]'");
		} elseif($chk_opt[opt_type]=="0"){
			$stock_s = sql_fetch("select sum(opt_stock) as stock_s from nfor_item_option where opt_it_id='$data[ct_it_id]' and opt_type='0'");
			sql_query("update nfor_item set it_stock='$stock_s[stock_s]' where it_id='$chk_opt[opt_it_id]'");
		} else{

		}
	}
 
}

function cart_history_clear($mb_no){
	if($mb_no){
		sql_query("delete from nfor_cart where ct_mb_no='$mb_no' and ct_pay_step='0'");
	}
}



function order_state($cart){
	global $config;

	if($cart[ct_pay_step]=="1"){ // 결제완료

		if($cart[ct_dy_step]=="1"){
			$str = "배송준비중";
		} elseif($cart[ct_dy_step]=="2"){

			if($config[cf_delivery_auto_use]=="1" and date("Ymd",strtotime("+ $config[cf_delivery_auto_day] day", strtotime($cart[ct_delivery_datetime]))) >= date("Ymd")){
				$str = "배송중";
			} else{
				$str = "발송완료";
			}

		} elseif($cart[ct_dy_step]=="5"){
			$str = "교환대기";	
		} elseif($cart[ct_dy_step]=="6"){
			$str = "교환완료";	
		} else{
			$str = "결제완료";
		}
		
	} elseif($cart[ct_pay_step]=="2"){ // 취소신청

		if($cart[ct_dy_step]=="3"){
			$str = "반품접수완료";
		} elseif($cart[ct_dy_step]=="4"){
			$str = "반품완료";
		} else{
			$str = "취소접수완료";
		}

	} elseif($cart[ct_pay_step]=="3"){
		$str = "취소완료";
	} elseif($cart[ct_pay_step]=="4"){
		$str = "입금대기";
	} elseif($cart[ct_pay_step]=="5"){
		$str = "입금전취소";
	} elseif($cart[ct_pay_step]=="0"){
		$str = "결제대기";
	} else{
		$str = "";
	}
	return $str;
}

function cart_it_name($cart_id,$supply_no=""){

	if($supply_no){
		$cart = sql_fetch("select count(*) as cnt, z.ct_it_id from (select ct_it_id from nfor_cart where ct_cart_id='$cart_id' and ct_opt_chk='1' and ct_supply_no='$supply_no' group by ct_it_id) as z");
	} else{
		$cart = sql_fetch("select count(*) as cnt, z.ct_it_id from (select ct_it_id from nfor_cart where ct_cart_id='$cart_id' and ct_opt_chk='1' group by ct_it_id) as z");
	}

	$item = sql_fetch("select it_name from nfor_item where it_id='$cart[ct_it_id]'");
	$str = $item[it_name];
	if($cart[cnt]>1){
		$str .= "외 ".($cart[cnt]-1)."건";
	}
	return $str;
}


function it_sales_volume_update($od_cart_id){

	$que = sql_query("select * from nfor_cart where ct_cart_id='$od_cart_id' group by ct_it_id");
	while($data = sql_fetch_array($que)){
		$it_sales_volume = it_sales_volume_real($data[it_id]);
		sql_query("update nfor_item set it_sales_volume='$it_sales_volume' where it_id='$data[it_id]'");
	}

}
		
function it_stock_update($it_id){
	$opt_stock_s = sql_fetch("select sum(opt_stock) as opt_stock_s from nfor_item_option where opt_it_id='$it_id' and opt_type='0'");
	sql_query("update nfor_item set it_stock='$opt_stock_s[opt_stock_s]' where it_id='$it_id'");
}

function it_sales_volume_new($item){
	$it_sales_volume = 0 + it_sales_volume_real($item[it_id]) + it_change_volume($item[it_id]);
	return $it_sales_volume;
}

function ticket_exp_update($od_cart_id){


	$que = sql_query("select * from nfor_cart where ct_cart_id='$od_cart_id' and ct_it_type='2' and ct_pay_step='1'");
	while($data = sql_fetch_array($que)){

		$item = sql_fetch("select * from nfor_item where it_id='$data[ct_it_id]'");
		if($item[it_expiry_type]=="1"){ // 구매일기준
			$ct_tk_exp_sdate = date("Y-m-d");
			$ct_tk_exp_edate = date("Y-m-d",strtotime("+{$item[it_expiry_day]} day"));
		} elseif($item[it_expiry_type]=="2"){ // 지정일기준
			$ct_tk_exp_sdate = $item[it_startdate];
			$ct_tk_exp_edate = $item[it_enddate];
		} else{

		}

		sql_query("update nfor_cart set ct_tk_exp_sdate='$ct_tk_exp_sdate', ct_tk_exp_edate='$ct_tk_exp_edate' where ct_id='$data[ct_id]'");

	}


}


function zzim_cnt($mb_no){
	if($mb_no){
		$zzim = sql_fetch("select count(*) as cnt from nfor_zzim where zz_mb_no='$mb_no'");
	} else{
		$zzim[cnt] = 0;
	}
	return $zzim[cnt];
}


function od_it_name_echo($od_it_name){
	$exp = explode("||",$od_it_name);
	$cnt = count($exp);
	if($cnt>1){
		$cnt = $cnt - 1;
		$str_add = " 외 {$cnt}건";
	}
	$str = $exp[0].$str_add;
	return $str;	
}


function it_stock_fnc($row){
	if($row[it_stock_type]=="1"){
		$return = "무제한";
	} else{
		$return = number_format($row[it_stock]);
	}
	return $return;
}


function expiry_chk($order,$item){
	global $nfor;

	if($order[ct_id]){
		$order[od_pay_datetime] = $order[ct_pay_datetime];
	}

	if($item[it_expiry_type]=="1"){
		$ymd = date("Y-m-d",strtotime("+{$item[it_expiry_day]} day", strtotime($order[od_pay_datetime])));
		if($nfor[ymdhis] <= $ymd) {
			$str = "1";
		}
	} elseif($item[it_expiry_type]=="2"){
		if($nfor[ymdhis] <= $item[it_enddate]) {
			$str = "1";
		}
	} else{

	}
	return $str;
}

function expiry_date($order,$item){
	global $nfor;

	if($order[ct_id]){
		$order[od_pay_datetime] = $order[ct_pay_datetime];
	}

	if($item[it_expiry_type]=="1"){
		$ymd = date("Y-m-d",strtotime("+{$item[it_expiry_day]} day", strtotime($order[od_pay_datetime])));
		$str = substr($order[od_pay_datetime],0,10)." ~ ".$ymd;
	} elseif($item[it_expiry_type]=="2"){
		$str = substr($item[it_startdate],0,10)." ~ ".substr($item[it_enddate],0,10);
	} else{

	}
	return $str;
}

function card_info($data){
	global $nfor;
	$pg_code = $data[pg_card_info1];
	$card = sql_fetch("select pg_name from nfor_pg_code where pg_type='$nfor[pg_type]' and pg_payment_type='card' and pg_code='$pg_code'");
	return $card[pg_name];
}


function print_mb_info($data,$ing=array()){
	global $item, $member, $nfor, $star, $member_config;

	if($data[qa_insert_id]){
		$mb_id = $data[qa_insert_id];
	} elseif($data[st_insert_id]){
		$mb_id = $data[st_insert_id];
	} else{

	}

	$mb = sql_fetch("select mb_admin,mb_id,mb_email from nfor_member where mb_no='$mb_id'");
	if($item[it_supply_no]==$mb_id){	// 공급업체
		$ico = "$nfor[path]/img/qna02.png";
	} elseif($item[it_md_no]==$mb_id){	// MD
		$ico = "$nfor[path]/img/qna03.png";
	} elseif($mb[mb_admin] >= "7"){	// 관리자
		$ico = "$nfor[path]/img/qna04.png";
	} else{	// 일반회원
		$ico = "$nfor[path]/img/qna01.png";
	}

	if($data[fake_id]){
		$str = $data[fake_id];
	} else{
		$str = $mb[$member_config[cf_mb_id_type]];
	}

	if($member[mb_no] and ($member[mb_admin] >= "7" or $item[it_supply_no]==$member[mb_no] or $item[it_md_no]==$member[mb_no] or $mb_id==$member[mb_no] or $ing[mb_no]==$member[mb_no])){ // 관리자이거나 입점업체 또는 MD라면 또는 본인글이라면
		$return = $str;
	} else{ // 일반회원일때
		if($nfor[id_secret]){ // 아이디 비공개이면
			if($member_config[cf_mb_id_type]=="mb_id"){
				$strlen = strlen($str);
				$str = substr($str,0,-3);
				for($i=0; $i<=$strlen-3; $i++){
					$str .= "*";
				}
			} else{
				$exp = explode("@",$str);
				$strlen = strlen($exp[0]);
				$str = substr($exp[0],0,-3);
				for($i=0; $i<=$strlen-3; $i++){
					$str .= "*";
				}
				$str = $str."@".substr($exp[1],0,2);
			}
			$return = $str;
		} else{ // 공개이면
			$return = $str;
		}
	}

	// 최고관리자일경우 아이디 미표시
	//if($mb[mb_admin]=="10"){
	//	$return = "";
	//}

	return $return;
}

function item_star_memo($str){
	global $item, $is_admin, $member;
	if($item[it_star_secret]){
		if($member[mb_no]){
			$return = strip_tags(nl2br($str[st_memo]),"<br>");
		} else{
				$return = cut_str(strip_tags($str[st_memo]),50);
				$return .= "<br>***********************************************************************<br>******************************************************************";
		}
	} else{
		$return = strip_tags(nl2br($str[st_memo]),"<br>");
	}
	return $return;
}


function item_qna_memo($str,$ing=array()){
	global $item, $is_admin, $member;

	if($item[it_qna_secret]){

		if($is_admin){
			$return = $str[qa_memo];
		} elseif(($member[mb_no] and $ing[mb_no]==$member[mb_no])){
			$return = $str[qa_memo];
		} elseif(($member[mb_no] and $str[mb_no]==$member[mb_no])){
			$return = $str[qa_memo];
		} else{
			$return = "상품문의는 고객님과 관리자만 확인할수 있습니다";
		}
	} else{
		$return = $str[qa_memo];
	}
	return $return;
}

function order_view_link($row){
	global $PHP_SELF, $qstr;
	return str_replace("list","form",basename($PHP_SELF))."?od_id=$row[od_id]&".$qstr;
}

function item_link($it_id){
	global $nfor;
	return "$nfor[path]/item.php?it_id=$it_id";
}

function nfor_echo($type,$val){
	global $is_admin;
	if($is_admin){
		$str = $val;
	} else{
		if($type=="od_hp" or $type=="mb_hp"){
			$exp = explode("-",$val);
			$str = $exp[0]."-****-".$exp[2];
		} elseif($type=="od_email" or $type=="mb_email"){
			$exp = explode("@",$val);
			$strlen = strlen($exp[0]);
			$str = substr($exp[0],0,-3);
			for($i=0; $i<=$strlen-3; $i++){
				$str .= "*";
			}
			$str = $str."@".substr($exp[1],0,2);
		} else{

		}
	}
	return $str;
}


function order_us($row){
	global $member;

	$str = $row[od_name];
	$str .= "<br>";
	if($member[mb_admin]=="1"){
		$exp = explode("-",$row[od_hp]);
		$str .= $exp[0]."-****-".$exp[2];
	} else{
		$str .= $row[od_hp];
	}
	$str .= "<br>";
	$str .= $row[od_email];
	return $str;
}

function star_mb_echo($row){
	$str = "<a href=\"javascript:member('$row[st_insert_id]')\">";
	if($row[st_mb_name]){
		$str .= $row[st_mb_name]."<br>";
	}
	if($row[st_mb_hp]){
		$str .= $row[st_mb_hp]."<br>";
	}
	if($row[st_mb_id]){
		$str .= $row[st_mb_id]."<br>";
	}
	if($row[st_mb_black]){
		$str .= "(블랙컨슈머)";
	}
	$str .= "</a>";
	return $str;
}

function qna_mb_echo($row){
	$str = "<a href=\"javascript:member('$row[qa_insert_id]')\">";
	if($row[qa_mb_name]){
		$str .= $row[qa_mb_name]."<br>";
	}
	if($row[qa_mb_hp]){
		$str .= $row[qa_mb_hp]."<br>";
	}
	if($row[qa_mb_id]){
		$str .= $row[qa_mb_id]."<br>";
	}
	if($row[qa_mb_black]){
		$str .= "(블랙컨슈머)";
	}
	$str .= "</a>";
	return $str;
}

function order_mb_echo($row){
	global $member;
	$str = "<a href=\"javascript:member('$row[od_mb_no]')\">";
	if($row[od_mb_name]){
		$str .= "$row[od_mb_name]<br>";
	}
	if($row[od_mb_hp]){
		if($member[mb_admin]=="1"){
			$exp = explode("-",$row[od_mb_hp]);
			$str .= $exp[0]."-****-".$exp[2];
		} else{
			$str .= $row[od_mb_hp];
		}
		$str .= "<br>";
	}
	if($row[od_mb_id]){
		$str .= "$row[od_mb_id]<br>";
	}

	if($row[od_mb_black]){
		$str .= "<span style='color:red;'>(블랙컨슈머)</span><br>";
	}

	//if($row[od_mb_no]){
//		$str .= "(회원)<br>";
//	} else{
//		$str .= "(비회원)<br>";
//	}

	$str .= "</a>";
	return $str;
}

function cart_mb_echo($row){
	global $member;
	$str = "<a href=\"javascript:member('$row[ct_mb_no]')\">";
	if($row[ct_mb_name]){
		$str .= "$row[ct_mb_name]<br>";
	}
	if($row[ct_mb_hp]){
		//if($member[mb_admin]=="1"){
		//	$exp = explode("-",$row[ct_mb_hp]);
		//	$str .= $exp[0]."-****-".$exp[2];
		//} else{
			$str .= $row[ct_mb_hp];
		//}
		$str .= "<br>";
	}
	if($row[ct_mb_id]){
		$str .= "$row[ct_mb_id]<br>";
	}


	if($row[ct_mb_black]){
		$str .= "<span style='color:red;'>(블랙컨슈머)</span><br>";
	}

	//if($row[ct_mb_no]){
	//	$str .= "(회원)<br>";
	//} else{
	//	$str .= "(비회원)<br>";
	//}

	$str .= "</a>";
	return $str;
}


function cart_supply_echo($row){
	global $admin;
	$str = "";
	if($row[ct_supply_no]){
		$str .= $admin[ct_supply_no][$row[ct_supply_no]]."<br>";
		$str .= $admin[ct_supply_tel][$row[ct_supply_no]]."<br>";
	}
	return $str;
}


function mb_info($mb_no){
	global $member_config;
	$row = sql_fetch("select * from nfor_member where mb_no='$mb_no'");

	$str = "<a href=\"javascript:member('$row[mb_no]')\">";
	if($row[mb_name]){
		$str .= $row[mb_name]."<br>";
	}
	if($row[mb_hp]){
		$str .= $row[mb_hp]."<br>";
	}
	if($row[$member_config[cf_mb_id_type]]){
		$str .= $row[$member_config[cf_mb_id_type]]."<br>";
	}

	$str .= "</a>";
	return $str;
}

function item_delete($it_id){
	global $member;
	item_access($it_id);
	sql_query("delete from nfor_item where it_id='$it_id'");
	sql_query("delete from nfor_item_option where opt_it_id='$it_id'");
	sql_query("delete from nfor_item_view where iv_it_id='$it_id'");
	sql_query("delete from nfor_zzim where zz_it_id='$it_id'");
	sql_query("delete from nfor_alarm where al_it_id='$it_id'");	
	sql_query("delete from nfor_item_location where lo_it_id='$it_id'");
}

function it_img_del($folder,$filename,$item){
	global $nfor;
	@unlink($nfor[path]."/data/".$folder."/".$item[$filename]);
}

function it_img_upload($folder,$filename){
	global $nfor, $_FILES;
	if($filename_add = file_upload($nfor[path]."/data/".$folder."/", $_FILES[$filename])){
		return " , $filename='$filename_add' ";
	}
}

function it_img_copy($folder,$filename){
	global $nfor, $item, $_FILES;
	$it_img_upload = it_img_upload($folder,$filename);
	if($it_img_upload){
		return $it_img_upload;
	} else{
		if($item[$filename]){
			copy($nfor[path]."/data/".$folder."/".$item[$filename],$nfor[path]."/data/".$folder."/".time().$item[$filename]);
			$item[$filename] = time().$item[$filename];
			return " , $filename='".$item[$filename]."' ";
		}
		
	}
}


function item_access($it_id){
	global $member;
	if($it_id){
		$item = sql_fetch("select * from nfor_item where it_id='$it_id'");
		if($member[mb_admin]=="1" and $item[it_supply_no]<>$member[mb_no]) alert("권한이 없습니다");
		if($member[mb_admin]=="2" and $item[it_md_no]<>$member[mb_no]) alert("권한이 없습니다");
	}
}


function item_access_json($it_id){
	global $member;
	if($it_id){
		$item = sql_fetch("select * from nfor_item where it_id='$it_id'");
		if($member[mb_admin]=="1" and $item[it_supply_no]<>$member[mb_no]) json_return("권한이 없습니다","no_access");
		if($member[mb_admin]=="2" and $item[it_md_no]<>$member[mb_no]) json_return("권한이 없습니다","no_access");
	}
}



function category_item_count($category_id) {
	$add_item = sql_fetch("select count(*) as cnt from nfor_item where it_category like '%$category_id%'");
	return $add_item[cnt];
}

function area_item_count($area_id) {
	$add_item = sql_fetch("select count(*) as cnt from nfor_item where it_area like '%$area_id%'");
	return $add_item[cnt];
}

function brand_item_count($it_bcategory_id) {
	$add_item = sql_fetch("select count(*) as cnt from nfor_item where it_bcategory_id like '$it_bcategory_id%'");
	return $add_item[cnt];
}

function coupon_again($od_id, $cu_it_id=""){

	if($it_id){
		$add_sql = " and cu_it_id='$cu_it_id'";
	}

	sql_query("update nfor_coupon_use set cu_state='2' where cu_od_id='$od_id' $add_sql");
	$que = sql_query("select * from nfor_coupon_use where cu_od_id='$od_id' and cu_state='2' $add_sql");
	while($data = sql_fetch_array($que)){
		sql_query("update nfor_coupon set cp_use=cp_use-1 where cp_id='$data[cu_cp_id]'");
	}

}

function coupon_use($od_id){
	sql_query("update nfor_coupon_use set cu_state='1' where cu_od_id='$od_id'");
	$que = sql_query("select * from nfor_coupon_use where cu_od_id='$od_id' and cu_state='1'");
	while($data = sql_fetch_array($que)){
		sql_query("update nfor_coupon set cp_use=cp_use+1 where cp_id='$data[cu_cp_id]'");
	}
}

function category_sql($category_id){
	$sql_search = " and (";
	for($i=1; $i<=10; $i++){
		if($i>1) $sql_search .= " or ";
		$sql_search .= "it_category_id{$i} like '$category_id%'";
	}
	$sql_search .= ")";
	return $sql_search;
}




function category_sql_or($category_id1,$category_id2,$category_id3){
	$sql_search = " and (";
	for($i=1; $i<=10; $i++){
		if($i>1) $sql_search .= " or ";
		$sql_search .= "(category_id{$i} like '$category_id1%' or category_id{$i} like '$category_id2%' or category_id{$i} like '$category_id3%')";
	}
	$sql_search .= ")";
	return $sql_search;
}



function bank_code($str){	// 이니시스 무통장 은행코드

	if($str=="03"){
		$bank = "기업은행";
	} elseif($str=="04"){
		$bank = "국민은행";
	} elseif($str=="05"){
		$bank = "외환은행";
	} elseif($str=="07"){
		$bank = "수협중앙회";
	} elseif($str=="11"){
		$bank = "농협중앙회";
	} elseif($str=="20"){
		$bank = "우리은행";
	} elseif($str=="23"){
		$bank = "SC제일은행";
	} elseif($str=="31"){
		$bank = "대구은행";
	} elseif($str=="32"){
		$bank = "부산은행";
	} elseif($str=="34"){
		$bank = "광주은행";
	} elseif($str=="37"){
		$bank = "전북은행";
	} elseif($str=="39"){
		$bank = "경남은행";
	} elseif($str=="53"){
		$bank = "한국씨티은행";
	} elseif($str=="71"){
		$bank = "우체국";
	} elseif($str=="81"){
		$bank = "하나은행";
	} elseif($str=="88"){
		$bank = "통합신한은행 (신한,조흥은행)";
	} elseif($str=="D1"){
		$bank = "동양종합금융증권";
	} elseif($str=="D2"){
		$bank = "현대증권";
	} elseif($str=="D3"){
		$bank = "미래에셋증권";
	} elseif($str=="D4"){
		$bank = "한국투자증권";
	} elseif($str=="D5"){
		$bank = "우리투자증권";
	} elseif($str=="D6"){
		$bank = "하이투자증권";
	} elseif($str=="D7"){
		$bank = "HMC투자증권";
	} elseif($str=="D8"){
		$bank = "SK증권";
	} elseif($str=="D9"){
		$bank = "대신증권";
	} elseif($str=="DA"){
		$bank = "하나대투증권";
	} elseif($str=="DB"){
		$bank = "굿모닝신한증권";
	} elseif($str=="DC"){
		$bank = "동부증권";
	} elseif($str=="DD"){
		$bank = "유진투자증권";
	} elseif($str=="DE"){
		$bank = "메리츠증권";
	} elseif($str=="DF"){
		$bank = "신영증권";
	} else{

	}
	return $bank;
}



function money_type($money_type){

	if($money_type=="1"){
		$str = "회원가입";
	} elseif($money_type=="2"){
		$str = "상품구매";
	} elseif($money_type=="7"){ 
		$str = "적립금 상품구매 취소";
	} elseif($money_type=="8"){
		$str = "적립금 상품구매 부분취소";
	} elseif($money_type=="9"){
		$str = "추천인입력";
	} elseif($money_type=="10"){
		$str = "추천받음";
	} elseif($money_type=="11"){
		$str = "회원탈퇴";
	} else{
		$str = "임의입력";
	}


	return $str;
}

function mb_dy($mb_no){
	$data = sql_fetch("select count(*) as cnt from ( select ct_id from nfor_cart where ct_mb_no='$mb_no' and ct_pay_step>'0' and ct_it_type='1' and ct_view='0' group by ct_cart_id ) as z");
	return $data[cnt];
}

function mb_ticket($mb_no){
	$data = sql_fetch("select count(*) as cnt from nfor_cart where ct_mb_no='$mb_no' and ct_pay_step='1' and ct_it_type='2' and ct_view='0' and ct_opt_cnt > ct_tk_used");
	return $data[cnt];
}

function mb_dy_ing($mb_no){
	global $config;
	$ct_delivery_datetime = date("Y-m-d",strtotime("-$config[cf_delivery_auto_day] day"));
	$data = sql_fetch("select count(*) as cnt from ( select ct_id from nfor_cart where ct_mb_no='$mb_no' and ct_pay_step='1' and ct_dy_step='2' and ct_it_type='1' and ct_view='0' and date_format(ct_delivery_datetime,'%Y-%m-%d') >= '$ct_delivery_datetime' group by ct_cart_id ) as z");
	return $data[cnt];
}

function mb_dy_ready($mb_no){
	$data = sql_fetch("select count(*) as cnt from ( select ct_id from nfor_cart where ct_mb_no='$mb_no' and ct_pay_step='1' and ct_dy_step='1' and ct_it_type='1' and ct_view='0' group by ct_cart_id ) as z");
	return $data[cnt];
}

function mb_dy_send($mb_no){
	global $config;
	$ct_delivery_datetime = date("Y-m-d",strtotime("-$config[cf_delivery_auto_day] day"));
	$data = sql_fetch("select count(*) as cnt from ( select ct_id from nfor_cart where ct_mb_no='$mb_no' and ct_pay_step='1' and ct_dy_step='2' and ct_it_type='1' and ct_view='0' and date_format(ct_delivery_datetime,'%Y-%m-%d') < '$ct_delivery_datetime' group by ct_cart_id ) as z");
	return $data[cnt];
}

function mb_order_wait($mb_no){
	$data = sql_fetch("select count(*) as cnt from ( select ct_id from nfor_cart where ct_mb_no='$mb_no' and ct_pay_step='4' and ct_view='0' group by ct_cart_id ) as z");
	return $data[cnt];
}

function mb_coupon($mb_no){
	global $nfor;
	$data1 = sql_fetch("select count(*) as cnt from nfor_coupon where cp_sdate<='$nfor[ymdhis]' and cp_edate>='$nfor[ymdhis]' and (cp_all='1' or cp_mb_no='$mb_no')");
	$data2 = sql_fetch("select count(*) as cnt from nfor_coupon_use a, nfor_coupon b where a.cu_mb_no='$mb_no' and a.cu_cp_id=b.cp_id and a.cu_state='1' and b.cp_sdate<='$nfor[ymdhis]' and b.cp_edate>='$nfor[ymdhis]'");

	$data = $data1[cnt] - $data2[cnt];
	return $data;
}


function expire_mb_coupon($mb_no){ // 1개월내 만료 예정 쿠폰
	global $nfor;
	$cp_edate = date("Y-m-d H:i:s",strtotime("+1 month"));
	$data = sql_fetch("select count(*) as cnt from nfor_coupon where cp_sdate<='$nfor[ymdhis]' and cp_edate>='$nfor[ymdhis]' and (cp_all='1' or cp_mb_no='$mb_no') and cp_edate < '$cp_edate'");
	return $data[cnt];
}

function expire_mb_money($mb_no){ // 1개월내 만료 예정 포인트
	global $nfor;
	$my_end_datetime = date("Y-m-d H:i:s",strtotime("+1 month"));
	$sum = sql_fetch("select sum(my_use_money) as s_money from nfor_money where my_mb_no='$mb_no' and my_end_datetime >= '$nfor[ymdhis]' and my_end_datetime < '$my_end_datetime'");
	return $sum[s_money];
}

function mb_money($mb_no){	// 적립금
	global $nfor;
	$sum = sql_fetch("select sum(my_use_money) as s_money from nfor_money where my_mb_no='$mb_no' and my_end_datetime >= '$nfor[ymdhis]'");
	$sum[s_money] = $sum[s_money] + 0;
	return $sum[s_money];
}

function insert_money($mb_no,$money,$memo,$money_type="",$od_id="",$ct_id="",$end_datetime=""){
	global $nfor, $member_config;
	$mb = sql_fetch("select * from nfor_member where mb_no = '$mb_no'");

	$my_mb_id = $mb[$member_config[cf_mb_id_type]];
	if(!$mb[mb_no] or $money == 0 or $mb_no == ""){ return 0; }

	if($money<0){
		$end_datetime = $nfor[ymdhis];
	} else{
		if(!$end_datetime){
			$end_datetime = $nfor[money_ymdhis];
		}
	}
	if($money>0){
		$use_money = $money;
	}
	if(!$end_datetime){
		$end_datetime = "2050-12-25 23:59:59";
	}
	sql_query("insert nfor_money set my_mb_no='$mb_no', my_money='$money', my_use_money='$use_money', my_memo='$memo', my_money_type='$money_type', my_od_id='$od_id', my_ct_id='$ct_id', my_datetime='$nfor[ymdhis]', my_end_datetime='$end_datetime', my_mb_id='$my_mb_id', my_mb_name='$mb[mb_name]', my_mb_nick='$mb[mb_nick]'");
	if($money < 0){
		$money = $money*-1;
		$que = sql_query("select * from nfor_money where my_mb_no='$mb_no' and my_end_datetime >= '$nfor[ymdhis]' and my_use_money > 0 order by my_end_datetime asc");
		while($data = sql_fetch_array($que)){
			if($data[my_use_money] >= $money){  // 레코드적립금이 까야할 적립금 보다 크거나 같으면
				$use_money_update = $data[my_use_money] - $money; // 레코드적립금 빼기 차감할적립금
			} else{
				$use_money_update = "0"; // 전체차감
			}
			sql_query("update nfor_money set my_use_money='$use_money_update' where my_id='$data[my_id]'");
			if($data[my_use_money] >= $money){
				break;
			}
			$money = $money - $data[my_use_money];
		}
	}

	$mb_money = mb_money($mb_no);
	sql_query("update nfor_member set mb_money='$mb_money' where mb_no='$mb_no'");

	return 1;
}


function return_money($ct_id){	// 이번에 취소할적립금

	$ct = sql_fetch("select * from nfor_cart where ct_id='$ct_id'");
	$order = sql_fetch("select * from nfor_order where od_cart_id='$ct[ct_cart_id]'");

	if($order[od_money_price] > $order[od_cancel_price]){	// 적립금으로 결제한금액이 취소된 금액보다 크면
		$max_money_price = $order[od_money_price] - $order[od_cancel_price];	// 남아있는 적립금
	} else{
		$max_money_price = 0;	// 남아있는 적립금
	}

	if($max_money_price > return_price($ct[ct_id])){	// 남아있는 적립금이 상품금액보다 크면
		$return_money_price = return_price($ct[ct_id]);
	} else{
		$return_money_price = $max_money_price;
	}

	/*
	echo "적립금 사용액 : ".$order[od_money_price];
	echo "<br>";
	echo "취소된적립금 : ".$order[od_cancel_price];
	echo "<br>";
	echo "남아있는 적립금 : ".$max_money_price;
	echo "<br>";
	echo "취소할상품금액 : ".return_price($ct[ct_id]);
	echo "<br>";
	echo "취소할적립금 : ".$return_money_price;
	*/
	return $return_money_price;

}


function return_money_coupon2($ct_id){	// 골마켓로직변경적용

	$ct = sql_fetch("select * from nfor_cart where ct_id='$ct_id'");
	$order = sql_fetch("select * from nfor_order where od_cart_id='$ct[ct_cart_id]'");

	$chk_order = sql_fetch("select * from nfor_cart where ct_pay_step='3' and ct_cart_id='$ct[ct_cart_id]' and ct_it_id='$ct[ct_it_id]'");
	if(!$chk_order[ct_id]){ // 이미 취소된게 없으면 쿠폰 전체 차감
	
		$coupon_price = sql_fetch("select * from nfor_coupon_use where cu_od_id='$order[od_id]' and cu_it_id='$ct[ct_it_id]' and cu_state='1'");
		if($coupon_price[cu_price]){
			$return_money_price = $coupon_price[cu_price];
		} 

	}

	/*
	echo "적립금 사용액 : ".$order[od_money_price];
	echo "<br>";
	echo "취소된적립금 : ".$order[od_cancel_price];
	echo "<br>";
	echo "남아있는 적립금 : ".$max_money_price;
	echo "<br>";
	echo "취소할상품금액 : ".return_price($ct[ct_id]);
	echo "<br>";
	echo "취소할적립금 : ".$return_money_price;
	*/
	return $return_money_price;

}



function return_money_coupon($ct_id){	// 이번에 취소할적립금

	$ct = sql_fetch("select * from nfor_cart where ct_id='$ct_id'");
	$order = sql_fetch("select * from nfor_order where od_cart_id='$ct[ct_cart_id]'");
	$s_money = sql_fetch("select sum(my_money) as s_money from nfor_money where my_od_id='$order[od_id]' and my_money_type='8'"); // 부분취소된 적립금

	if($order[od_money_price] > $s_money[s_money]){ // 사용한 적립금이 취소된 적립금보다 크면
		$max_money_price = $order[od_money_price] - $s_money[s_money];	// 남아있는 적립금
	} else{
		$max_money_price = 0;	// 남아있는 적립금
	}

	if($max_money_price > return_price_coupon($ct[ct_id])){	// 남아있는 적립금이 상품금액보다 크면
		$return_money_price = return_price_coupon($ct[ct_id]);
	} else{
		$return_money_price = $max_money_price;
	}

	/*
	echo "적립금 사용액 : ".$order[od_money_price];
	echo "<br>";
	echo "취소된적립금 : ".$order[od_cancel_price];
	echo "<br>";
	echo "남아있는 적립금 : ".$max_money_price;
	echo "<br>";
	echo "취소할상품금액 : ".return_price($ct[ct_id]);
	echo "<br>";
	echo "취소할적립금 : ".$return_money_price;
	*/
	return $return_money_price;

}



function return_price_coupon($ct_id){	// 이번에 취소될 금액(쿠폰)

	$ct = sql_fetch("select * from nfor_cart where ct_id='$ct_id'");
	$order = sql_fetch("select * from nfor_order where od_cart_id='$ct[ct_cart_id]'");


	$coupon_price = sql_fetch("select * from nfor_coupon_use where cu_od_id='$order[od_id]' and cu_it_id='$ct[ct_it_id]'");
	$return_price = return_price($ct_id) - $coupon_price[cu_price]; // 취소될금액 - 쿠폰금액


	return $return_price;
}


function return_price($ct_id){	// 이번에 취소될 금액

	$cancel_delivery_price = "0";
	$ct = sql_fetch("select * from nfor_cart where ct_id='$ct_id'");
	if($ct[ct_it_type]=="1"){	// 배송상품이면서
		// 같은 주문서의 상품에서 본인꺼 빼고 완료 또는 신청에 1건이라도 있으면 배송비는 나중에 깜 (0원)
		$chk_delivery = sql_fetch("select count(*) as cnt from nfor_cart where ct_cart_id='$ct[ct_cart_id]' and ct_it_id='$ct[ct_it_id]' and ct_id<>'$ct[ct_id]' and (ct_pay_step = '1' or ct_pay_step = '2')");
		if(!$chk_delivery[cnt]){ // 본건이 마지막 취소이면
			$dy = sql_fetch("select * from nfor_dy_price where cart_id='$ct[ct_cart_id]' and it_id='$ct[ct_it_id]'");
			if($dy[dy_price]){
				$cancel_delivery_price = $dy[dy_price];
			}
		}

	}
	$return_price = ($ct[ct_price2]*$ct[ct_opt_cnt])+$cancel_delivery_price;

	return $return_price;
}


function ea_delivery_price($cart_id, $it_id,$item_price=0,$zipcode=""){ // 상품코드, 상품합산가격

	global $nfor;

	$item = sql_fetch("select * from nfor_item where it_id='$it_id'");

	if($nfor[dy_group]=="1" and $item[it_supply_no]){ // 묶음배송이고 공급업체설정이 되어 있으면

		// 무료배송있는지 체크
		$chk1 = sql_fetch("select ct_it_id from nfor_cart where ct_cart_id='$cart_id' and ct_supply_no='$item[it_supply_no]' and ct_delivery_type='1' and ct_opt_chk='1'");

		// 조건부배송중에 무료배송 있는지 체크
		$chk3[ct_it_id] = "";
		$que = sql_query("select ct_it_id, ct_delivery_total from nfor_cart where ct_cart_id='$cart_id' and ct_supply_no='$item[it_supply_no]' and ct_delivery_type='3' and ct_opt_chk='1' group by ct_it_id");
		while($ct = sql_fetch_array($que)){
			$ea_item_price = sql_fetch("select sum(ct_sprice2) as ea_item_price from nfor_cart where ct_cart_id='$cart_id' and ct_it_id='$ct[ct_it_id]' and ct_opt_chk='1'");
			if($ct[ct_delivery_total] <= $ea_item_price[ea_item_price]){
				$chk3[ct_it_id] = $ct[ct_it_id];	// 무료배송인 상품이 있다면 변수에 저장
			}
		}

		// 조건부배송중에 무료배송이 없을경우 장바구니에 담긴 상품들중 해당업체의 최소 조건부 배송 금액과 합산금액보다 큰게 있는지 체크
		if(!$chk3[ct_it_id]){
			$chk3_1_chk = sql_fetch("select ct_it_id, ct_delivery_total from nfor_cart where ct_cart_id='$cart_id' and ct_supply_no='$item[it_supply_no]' and ct_delivery_type='3' and ct_opt_chk='1' group by ct_it_id order by ct_delivery_total asc");

			$total_item_price = sql_fetch("select sum(ct_sprice2) as total_item_price from nfor_cart where ct_cart_id='$cart_id' and ct_supply_no='$item[it_supply_no]' and ct_opt_chk='1'");
			if($total_item_price[total_item_price] >= $chk3_1_chk[ct_delivery_total]){
				$chk3[ct_it_id] = $chk3_1_chk[ct_it_id];
			}

		}

		// 조건부배송중에 무료배송이 없을경우 유료배송 있는지 체크
		if(!$chk3[ct_it_id]){
			$chk3_1_chk = sql_fetch("select ct_it_id from nfor_cart where ct_cart_id='$cart_id' and ct_supply_no='$item[it_supply_no]' and ct_delivery_type='3' and ct_opt_chk='1' group by ct_it_id order by ct_delivery_price asc");
			if($chk3_1_chk[ct_it_id]){
				$chk3_1[ct_it_id] = $chk3_1_chk[ct_it_id];
			}
		}

		// 유료배송이 있는지 체크
		$chk4 = sql_fetch("select ct_it_id from nfor_cart where ct_cart_id='$cart_id' and ct_supply_no='$item[it_supply_no]' and ct_delivery_type='4' and ct_opt_chk='1'");

		// 착불배송이 있는지 체크
		$chk2 = sql_fetch("select ct_it_id from nfor_cart where ct_cart_id='$cart_id' and ct_supply_no='$item[it_supply_no]' and ct_delivery_type='2' and ct_opt_chk='1'");

		if($chk1[ct_it_id]){	// 무료배송인 상품이 있다면 그상품 빼고 나머지는 다 묶음배송

			if($chk1[ct_it_id]==$it_id){
				$dyinfo[price] = 0;
				$dyinfo[state] = "무료배송";
			} else{
				$dyinfo[price] = 0;
				$dyinfo[state] = "묶음배송";
			}

		} elseif($chk3[ct_it_id]){ // 조건부배송에 따른 무료배송상품이 있다면 그상품 빼고 나머지는 다 묶음배송

			if($chk3[ct_it_id]==$it_id){
				$dyinfo[price] = 0;
				$dyinfo[state] = "무료배송";
			} else{
				$dyinfo[price] = 0;
				$dyinfo[state] = "묶음배송";
			}

		} elseif($chk3_1[ct_it_id]){ // 조건부배송에 따른 무료배송상품이 없고 조건부 배송상품만 존재한다면 그상품 빼고 나머지는 다 묶음배송

			if($chk3_1[ct_it_id]==$it_id){
				$dyinfo[price] = $item[it_delivery_price];
				$dyinfo[state] = number_format($dyinfo[price])."원";
			} else{
				$dyinfo[price] = 0;
				$dyinfo[state] = "묶음배송";
			}

		} elseif($chk4[ct_it_id]){ // 유료배송상품이 있다면 그상품 빼고 나머지는 다 묶음배송

			if($chk4[ct_it_id]==$it_id){
				$dyinfo[price] = $item[it_delivery_price];
				$dyinfo[state] = number_format($dyinfo[price])."원";
			} else{
				$dyinfo[price] = 0;
				$dyinfo[state] = "묶음배송";
			}

		} elseif($chk2[ct_it_id]){ // 착불배송상품이 있다면 그상품 빼고 나머지는 다 묶음배송

			if($chk2[ct_it_id]==$it_id){
				$dyinfo[price] = 0;
				$dyinfo[state] = "착불배송";
			} else{
				$dyinfo[price] = 0;
				$dyinfo[state] = "묶음배송";
			}

		} else{


		}

	} else{	// 개별배송이면

		$dyinfo[price] = 0;
		$item_check = sql_fetch("select count(*) as cnt from nfor_cart where ct_cart_id='$cart_id' and ct_it_id='$it_id' and ct_opt_chk='1'");
		if(!$item_check[cnt]){
			$dyinfo[price] = "0";
			$dyinfo[state] = "-";
		} elseif($item[it_delivery_type]=="1"){
			$dyinfo[price] = 0;
			$dyinfo[state] = "무료배송";
		} elseif($item[it_delivery_type]=="2"){
			$dyinfo[price] = 0;
			$dyinfo[state] = "착불배송";
		} elseif($item[it_delivery_type]=="3"){
			if($item[it_delivery_total] > $item_price){
				$dyinfo[price] = $item[it_delivery_price];
				$dyinfo[state] = number_format($dyinfo[price])."원";
			} else{
				$dyinfo[price] = 0;
				$dyinfo[state] = "무료배송";
			}

		} elseif($item[it_delivery_type]=="4"){
			$dyinfo[price] = $item[it_delivery_price];
			$dyinfo[state] = number_format($dyinfo[price])."원";
		} else{
			$dyinfo[state] = "-";
		}


	}

	// 도서산간 체크
	if($zipcode){
		$zipcode = preg_replace("/[^0-9]*/s", "", $zipcode);
		$chk_zipcode = sql_fetch("select * from nfor_dy_zipcode where zip_szipcode <='$zipcode' and zip_ezipcode >='$zipcode'");
		if($chk_zipcode[zip_price]){	// 도서산간

			if($dyinfo[state]=="무료배송"){
				$dyinfo[price] = $chk_zipcode[zip_price];
				$dyinfo[state] = "도서산간배송";
			} elseif($item[state]=="착불배송"){

			} elseif($dyinfo[state]=="묶음배송"){

			} else{	// 유료배송
				$dyinfo[price] = $dyinfo[price]+$chk_zipcode[zip_price];
				$dyinfo[state] = "도서산간배송";
			}

		}
	}


	if($item[it_type]=="2"){	// 티켓상품이면
		$dyinfo[price] = "0";
		$dyinfo[state] = "없음";
	}

	return $dyinfo;
}



function category_print($category_id, $top_cate=""){
	$top_str = strlen($top_cate)/3;
	$abs = $top_str+1;
	$substr = substr($category_id,0,(3*$abs));
	$cate = sql_fetch("select cg_category from nfor_item_category where category_id='$substr'");
	$str = $cate[cg_category];
	return $str;
}



function delivery_price_insert($cart_id,$dy_zip){


	$que = sql_query("select ct_it_id, ct_delivery_type from nfor_cart where ct_cart_id='$cart_id' and ct_opt_chk='1' group by ct_it_id");
	while($data = sql_fetch_array($que)){

		// 상품금액
		$ea_item_price = sql_fetch("select sum(ct_sprice2) as ea_item_price from nfor_cart where ct_cart_id='$cart_id' and ct_it_id='$data[ct_it_id]' and ct_opt_chk='1'");

		// 상품배송금액
		$dyinfo = ea_delivery_price($cart_id, $data[ct_it_id],$ea_item_price[ea_item_price],$dy_zip);

		$total_price = $dyinfo[price]+$ea_item_price[ea_item_price];

		$chk_dy = sql_fetch("select * from nfor_dy_price where cart_id='$cart_id' and it_id='$data[ct_it_id]'");
		if($chk_dy[dy_id]){
			sql_query("update nfor_dy_price set total_price='$total_price', dy_price='$dyinfo[price]', dy_state='$dyinfo[state]', dy_type='$data[ct_delivery_type]',it_price='$ea_item_price[ea_item_price]' where cart_id='$cart_id' and it_id='$data[ct_it_id]'");
		} else{
			sql_query("insert nfor_dy_price set total_price='$total_price', cart_id='$cart_id', it_id='$data[ct_it_id]', dy_price='$dyinfo[price]', dy_state='$dyinfo[state]', dy_type='$data[ct_delivery_type]',it_price='$ea_item_price[ea_item_price]'");
		}
	}


}

function delivery_total_price($cart_id,$dy_zip=""){
	$delivery_total_price = 0;
	$que = sql_query("select ct_it_id, ct_delivery_type from nfor_cart where ct_cart_id='$cart_id' and ct_opt_chk='1' group by ct_it_id");
	while($data = sql_fetch_array($que)){
		// 상품금액
		$ea_item_price = sql_fetch("select sum(ct_sprice2) as ea_item_price from nfor_cart where ct_cart_id='$cart_id' and ct_it_id='$data[ct_it_id]' and ct_opt_chk='1'");
		// 상품배송금액
		$dyinfo = ea_delivery_price($cart_id, $data[ct_it_id],$ea_item_price[ea_item_price],$dy_zip);
		$delivery_total_price += $dyinfo[price];
	}
	return $delivery_total_price;
}

function opt_stock($all,$sale){
	$stock = $all - $sale;
	if($stock<0){
		$stock = 0;
	}
	return $stock;
}


function delivery_link($dy_name,$ct_dy_num){
	$wr_url = sql_fetch("select dy_url from nfor_delivery where dy_name='$dy_name'");
	$str = $wr_url[dy_url].$ct_dy_num;
	return $str;
}

function category_id_name($category_id){
	$str = "";
	for($k=1; $k <= strlen($category_id)/3; $k++){
		if($k>1) $str .= " > ";
		$category_id_str = substr($category_id,0,(3*$k));
		$catename = sql_fetch("select cg_category from nfor_item_category where category_id='$category_id_str'");
		$str .= $catename[cg_category];
	}
	return $str;
}

function area_id_name($category_id){
	$str = "";
	for($k=1; $k <= strlen($category_id)/3; $k++){
		if($k>1) $str .= " > ";
		$category_id_str = substr($category_id,0,(3*$k));
		$catename = sql_fetch("select cg_category from nfor_area_category where category_id='$category_id_str'");
		$str .= $catename[cg_category];
	}
	return $str;
}

function item($it_id){
	global $nfor;
	$item = sql_fetch("select * from nfor_item where it_id='$it_id'");

	// 판매량
	$item[it_sales_volume] = it_change_volume($item[it_id])+$item[it_sales_volume];

	// 남은수량정의
	$item[it_stock_now] = $item[it_stock]-$item[it_sales_volume];

	$item[href] = "$nfor[path]/item.php?it_id=".$item[it_id];

	if($item[it_buy_qty_type]=="1"){ // 구매수량설정이 제한없음일 경우
		$item[it_buy_qty_min] = "1";
		$item[it_buy_qty_max] = "9999999";
	}



	if($item[it_shopping]=="2"){ 
		$gap = strtotime($item[it_payenddate])-time();
		$item[it_countdown_d] = floor($gap/86400);

		if($gap>0){
			$item[it_countdown_html] = "남은시간 <span id='defaultCountdown'></span>";
		} else{ 
			$item[it_countdown_html] = "판매종료";
		} 
	} 


	$item[it_img_url] = $nfor[url]."/data/list/".$item[it_img];

	for($i=1; $i<=5; $i++){
		if($item["it_img".$i]){
			$item["it_img".$i] = thumbnail("$nfor[path]/data/main/".$item["it_img".$i],550,550,0,1);
		}
	}


	return $item;
}


function option($option_id){
	$option = sql_fetch("select * from nfor_item_option where option_id='$option_id'");
	return $option;

}

function cart_cnt($cart_id){
	$cnt = sql_num_rows(sql_query("select distinct ct_it_id from nfor_cart where ct_cart_id='$cart_id' and ct_pay_step='0'"));
	return $cnt;
}


function delivery_type($item){

	$str = "";
	if($item[it_type]=="1"){

		if($item[it_delivery_type]=="1"){
			$str = "무료배송";
		} elseif($item[it_delivery_type]=="2"){
			$str = "착불배송";
		} elseif($item[it_delivery_type]=="3"){
			$str = number_format($item[it_delivery_total])."원 이상 무료배송"; // 조건부무료배송
		} elseif($item[it_delivery_type]=="4"){
			$str = "배송비 ".number_format($item[it_delivery_price])."원"; // 자동결제
		} else{
			$str = "배송상품";
		}

	} else{

		$str = "티켓상품";

	}

	return $str;
}

function get_od_id(){

	global $member;

    sql_query(" LOCK TABLES nfor_order READ, nfor_order WRITE ", FALSE);
    $row = sql_fetch(" select max(od_id) as max_od_id from nfor_order where SUBSTRING(od_id, 1, 6) = '".date("ymd")."' ");
    $od_id = $row[max_od_id];
    if($od_id){
		$od_id = (int)substr($od_id, -6);
        $od_id++;
	} else{
		$od_id = 1;
    }
    $od_id = date("ymd").substr("000000".$od_id,-6);

	sql_query(" UNLOCK TABLES ", FALSE);


	//$od_id = "77".time().$member[mb_no];

    return $od_id;
}



function nfor_price($it_price_type,$type){
	$data = sql_fetch("select * from nfor_price where wr_id='$it_price_type'");
	if($type){
		if($data[wr_icon_img]){
			$str = "<img src='$nfor[path]/data/price/$data[wr_icon_img]' alt='$data[wr_icon]'>";
		} else{
			$str = $data[wr_icon];
		}
	} else{
		$str = $data[wr_icon];
	}
	return $str;
}



function it_change_volume($it_id){
	global $nfor;
	$chg_rp_cnt = sql_fetch("select sum(rp_cnt) as chg_rp_cnt from nfor_item_repair where rp_it_id='$it_id' and rp_change_datetime <= '$nfor[ymdhis]'");
	return $chg_rp_cnt[chg_rp_cnt];
}


function option_select($row){
	$str = "";
	for($i=1; $i<=4; $i++){
		if($i>1) $str .= " ";
		$str .= $row["option_select".$i];
	}
	return $str;
}



function it_option($option_id,$ct_id=''){
	$opt = sql_fetch("select * from nfor_item_option where option_id='$option_id'");
	$item = sql_fetch("select * from nfor_item where it_id='$opt[it_id]'");
	for($i=1; $i<=$item[it_opt_depth]; $i++){
		if($i>1){
			$print .= " <br> ";
		}
		$print .= $opt["option_name".$i];
	}


	if($ct_id){
		$price_data = sql_fetch("select ct_price2 from nfor_cart where ct_id='$ct_id'");
		$print .=  "(".number_format($price_data[ct_price2])."원)";
	} else{
		$print .=  "(".number_format($opt[price])."원)";
	}

	return $print;
}


function item_name($it_id){
	$item = sql_fetch("select it_name from nfor_item where it_id='$it_id'");
	return $item[it_name];
}

function it_name($od_id,$ct_id=""){

	if($ct_id){
		$ct = sql_fetch("select ct_it_id from nfor_cart where ct_id='$ct_id'");
		$item = sql_fetch("select it_name from nfor_item where it_id='$ct[ct_it_id]'");
		$str = $item[it_name];
	} else{
		$order = sql_fetch("select od_cart_id from nfor_order where od_id='$od_id'");
		$cart = sql_fetch("select count(*) as cnt, z.ct_it_id from (select * from nfor_cart where ct_cart_id='$order[od_cart_id]') as z");	//  group by it_id
		$item = sql_fetch("select it_name from nfor_item where it_id='$cart[ct_it_id]'");
		$str = $item[it_name];
		if($cart[cnt]>1){
			$str .= "외 ".($cart[cnt]-1)."건";
		}
	}
	return $str;
}



function pay_step($step){

	if($step=="1"){
		$str = "결제완료";
	} elseif($step=="2"){
		$str = "취소신청";
	} elseif($step=="3"){
		$str = "취소완료";
	} elseif($step=="4"){
		$str = "입금대기";
	} elseif($step=="5"){
		$str = "입금전취소";
	} else{
		$str = "결제대기";
	}
	return $str;

}


function dy_step($step){
	if($step=="1"){
		$str = "배송준비중";
	} elseif($step=="2"){
		$str = "발송완료";
	} elseif($step=="3"){
		$str = "반품대기";
	} elseif($step=="4"){
		$str = "반품완료";
	} elseif($step=="5"){
		$str = "교환대기";
	} elseif($step=="6"){
		$str = "교환완료";
	} elseif($step=="7"){
		$str = "주문취소";
	} else{
		$str = "배송대기";
	}
	return $str;
}

?>