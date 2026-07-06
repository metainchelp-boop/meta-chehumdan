<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.order_view_wrap { padding:10px 10px; background-color:#fff; }

/* 주문번호/주문일시 타이틀 */
.order_view_wrap .order_view_top_wrap { position:relative; padding-bottom:10px; border-bottom:2px solid #333; color:#70737b; font-size:.7em; }
.order_view_wrap .order_view_top_wrap .od_id_wrap {  vertical-align:middle; }
.order_view_wrap .order_view_top_wrap .od_id { margin-left:2px; color:#000; }
.order_view_wrap .order_view_top_wrap .od_datetime { position:absolute; right:0px; bottom:10px; letter-spacing:-1px; }

/* 배송정보 */
.od_dy_wrap { margin-top:20px; }
.od_dy_wrap .od_dy_title { position:relative; height:30px; border-top:2px solid #333; border-bottom:1px solid #eeeef0; font-size:.8em; line-height:30px; color:#252d35; }
.od_dy_tbl { width:100%; margin-top:10px; font-size:.75em; word-break:break-all; table-layout:fixed; }
.od_dy_tbl th{ max-width:52px; height:20px; font-weight:normal; color:#a8a9af; text-align:left; vertical-align:top; }
.od_dy_tbl td{ text-align:left; vertical-align:top; padding-bottom:7px;}

/* 결제정보 */
.od_pay_wrap { margin-top:20px;  }
.od_pay_wrap .od_pay_title { position:relative; height:30px; border-top:2px solid #333; border-bottom:1px solid #eeeef0; font-size:.8em; line-height:30px; color:#252d35; }
.od_pay_tbl { width:100%; margin-top:10px; font-size:.75em; word-break:break-all; table-layout:fixed; }
.od_pay_tbl th{ max-width:52px; height:20px; font-weight:normal; color:#a8a9af; text-align:left; vertical-align:top; }
.od_pay_tbl th .sub{ font-size:11px; color:#5f5f5f; }
.od_pay_tbl th .sub:before { display:inline-block; position:relative; top:-1px; width:5px; height:5px; margin-right:3px; border-bottom:1px solid #737373; border-left:1px solid #737373; vertical-align:middle; content:''; }
.od_pay_tbl td{ text-align:right; vertical-align:top; padding-bottom:7px;}
.od_pay_tbl .od_payment_price { font-size:1.6em; line-height:.8em; text-align:right; color:#e83862; }






.order_info .del_num_area{ height:14px; line-height:14px; margin-bottom:3px; font-size:.85em; }
.order_info span { font-size:.8em;color:#70737b; vertical-align:middle; font-style:normal; }
.order_info b { font-size:.8em; color:#000; vertical-align:middle;}
.order_info p { font-size:.8em; color:#f27935; }

.order_info { padding:13px 0 13px; border-bottom:1px solid #eeeef0; }
.order_info .go_to_item{display: block;min-height: 58px;}
.order_info .go_to_item:after{display: block; clear: both; content: '';}
.order_info .go_to_item .thumb{ float: left; position: relative; padding-right: 9px; ;}
.order_info .go_to_item .info{ margin-left:95px; }
.order_info .go_to_item .info .title { display:block; font-weight:bold; font-size:.75em; line-height:1.2; letter-spacing:-.07em; margin-bottom:3px; }


.order_info .buy_list { padding:0px; }
.order_info .buy_list li{ padding:5px 0px; border-bottom:dashed 1px #efefef; position:relative; }
.order_info .buy_list li:last-child{border:none;}
.order_info .buy_list li.cancel { text-decoration:line-through; }
.order_info .buy_list{font-size: .7em; color: #5f5f5f; margin-top:5px; letter-spacing: -.07em}
.order_info .buy_list span{color:#f27935}
.order_info .buy_list .count { position:absolute; top:4px; right:0px; }

.order_info .buy_list2{font-size: .7em; color: #5f5f5f; margin-top:5px; letter-spacing: -.07em}
.order_info .buy_list2 span{color:#f27935}









.btn_cancel {width: 100%; height: 33px; margin-bottom: 6px;  line-height: 30px;  text-align: center; background-color:#FAFAFA; border-radius:3px; border:solid 1px #DCDCDC; margin-top:20px;}


.tickets_wrap { margin-top:13px; }
.tickets_wrap .my_ticket{position: relative; margin-bottom: 6px; padding: 35px 14px 14px; border: 1px solid #d0d5d9;  border-top: 0;  border-radius: 4px;}
.tickets_wrap .my_ticket .my_ticket_status { display: block;  position: absolute;  top: 0;   right: 0;  z-index: 2; box-sizing: border-box;  height: 30px; padding: 10.5px 13.5px 4.5px 15px; font-weight: 700; font-size: 11px; line-height: 1; color: #fff;}
.tickets_wrap .my_ticket .my_ticket_no {  display: block;  position: absolute;  top: 0;  right: 0;  left: 0; height: 15px;  margin: 0 -1px; padding: 9.5px 15px 5.5px; border-top-right-radius: 4.5px;  border-bottom-right-radius: 1.5px;  border-bottom-left-radius: 1.5px;  border-top-left-radius: 4.5px;background-color: #e83862;  font-weight: 700; font-size: 12px; line-height: 1;  color: #fff;}
.tickets_wrap .my_ticket .my_ticket_tit {padding-top: 8px;  font-weight:bold;  font-size: 12px;  line-height: 20px;  color: #16181a; cursor: pointer; }


.tickets_wrap .my_ticket .my_ticket_tit.cancel { text-decoration:line-through; }

/*버튼*/
.order_btn_wrap { display:flex; flex-wrap:wrap; }


.order_btn_wrap div { width:50%; box-sizing:border-box; padding-bottom:6px;  }
.order_btn_wrap div.onebtn { width:100%; padding:0px; }

.order_btn_wrap div:nth-child(1) { padding-right:3px;  }
.order_btn_wrap div:nth-child(2) { padding-left:3px;  }
.order_btn_wrap div:nth-child(3) { padding-right:3px;  }
.order_btn_wrap div:nth-child(4) { padding-left:3px;  }
.order_btn_wrap a {  box-sizing:border-box; border:solid 1px #ccc; width:100%; display:block; text-align:center; height:36px; line-height:36px; font-size:13px; border-radius:4px; }


.order_one_btn { margin-top:10px; }
.order_one_btn div { width:100%; padding:0px; }



</style>



<div class="order_view_wrap">


	<!-- 주문번호/주문일시 -->
	<div class="order_view_top_wrap">
		<span class="od_id_wrap">
		주문번호 : <b class="od_id"><?=$order[od_id]?></b>
		</span>
		<span class="od_datetime"><?=$order[od_datetime]?> 구매</span>
	</div>
	<!-- //주문번호/주문일시 -->


	<?php
	$print = $return["list"];
	include $nfor[skin_path]."inc_order_list.php";
	?>


	<? if($order[od_is_ticket]){ ?>
	<div class="od_dy_wrap">
		<div class="od_dy_title">사용자정보</div>
		<table class="od_dy_tbl">
		<colgroup>
			<col style="width:25%">
		</colgroup>
		<tr>
			<th>사용자명</th>
			<td><?=$order[od_name]?></td>
		</tr>
		<tr>
			<th>연락처</th>
			<td><?=$order[od_hp]?></td>
		</tr>
		<tr>
			<th>이메일</th>
			<td><?=$order[od_email]?></td>
		</tr>
		</table>
	</div>
	<? } ?>




	<? if($order[od_is_delivery]){ ?>
	<div class="od_dy_wrap">
		<div class="od_dy_title">배송정보</div>
		<table class="od_dy_tbl">
		<colgroup>
			<col style="width:25%">
		</colgroup>
		<tr>
			<th>수령인</th>
			<td><?=$order[od_dy_name]?></td>
		</tr>
		<tr>
			<th>연락처</th>
			<td><?=$order[od_dy_hp]?></td>
		</tr>
		<? if($order[od_dy_tel]){ ?>
		<tr>
			<th>추가연락처</th>
			<td><?=$order[od_dy_tel]?></td>
		</tr>
		<? } ?>
		<tr>
			<th>배송지</th>
			<td>(<?=$order[od_dy_zip]?>) <?=$order[od_dy_addr1]?><br><?=$order[od_dy_addr2]?></td>
		</tr>
		<tr>
			<th>배송메모</th>
			<td><?=$order[od_dy_msg]?></td>
		</tr>
		</table>
	</div>



	<?php // 결제완료이고 배송상품이면서 배송대기인 상품이 있을때만 배송지변경가능
	if($order[delivery_chage_btn]){	
	?>
	<div class="order_btn_wrap order_one_btn">
		<div><a class="delivery_chage_btn">배송지 변경</a></div>
	</div>
	<? } ?>


	<? } ?>







	<? if($order[od_pay_step]=="4"){ ?>
	<div class="od_dy_wrap">
		<div class="od_dy_title">입금계좌</div>
		<table class="od_dy_tbl">
		<colgroup>
			<col style="width:25%">
		</colgroup>
		<tr>
			<th>입금계좌</th>
			<td><?=$order[od_bank_number]?></td>
		</tr>
		<tr>
			<th>입금금액</th>
			<td><?=number_format($order["od_".$order[od_payment_type]."_price"])?>원</td>
		</tr>
		<tr>
			<th>입금기한</th>
			<td><?=date("Y년 m월 d일 H시 까지",strtotime($order[od_bank_expire]))?></td>
		</tr>
		</table>
	</div>
	<? } ?>


	<div class="od_pay_wrap">
		<div class="od_pay_title">결제정보</div>
		<table class="od_pay_tbl">
		<colgroup>
			<col style="width:25%">
		</colgroup>
		<tr>
			<th>상품금액</th>
			<td><?=number_format($order[od_it_price])?>원</td>
		</tr>
		<tr>
			<th>배송금액</th>
			<td><?=number_format($order[od_delivery_price])?>원</td>
		</tr>
		<tr>
			<th>합산금액</th>
			<td><?=number_format($order[od_total_price])?>원</td>
		</tr>
		<tr>
			<th><span class="sub">쿠폰사용</span></th>
			<td><?=number_format($order[od_coupon_price])?>원</td>
		</tr>
		<tr>
			<th><span class="sub">적립금사용</span></th>
			<td><?=number_format($order[od_money_price])?>원</td>
		</tr>
		<tr>
			<td colspan="2"><hr></td>
		</tr>

		<? if($order[od_payment_type] <> "money" and $order[od_payment_type] <> "coupon"){ ?>
		<tr>
			<th>총 결재금액(<?=admin_echo($order,"od_payment_type")?>)</th>
			<td><b class="od_payment_price"><?=number_format($order["od_".$order[od_payment_type]."_price"])?>원</b></td>
		</tr>
		<? } ?>


		<? if($order[od_cancel_price]){ ?>
		<tr>
			<th>취소금액</th>
			<td><?=number_format($order[od_cancel_price])?>원</td>
		</tr>
		<? } ?>
		</table>
	</div>


	<? if($order[od_pay_step]=="4"){ ?>
	<div class="order_btn_wrap order_one_btn">
		<div><a class="order_wait_cancel_btn">즉시취소</a></div>
	</div>
	<? } ?>


	<?php if($order[order_hide_btn]){ ?>
	<div class="order_btn_wrap order_one_btn">
		<div><a class="order_hide_btn">주문내역 숨김</a></div>
	</div>
	<? } ?>




</div>







<?php
include_once $nfor[skin_path]."inc_delivery_chage.php";
?>





<script>
var od_id = "<?=$order[od_id]?>";


$(document).on("click",".div_back",function(){
	$(".txt_title").html("주문상세");
	$(".order_view_wrap").show();	
	$(".delivery_chage_wrap").hide();
	$(this).removeClass("div_back").addClass("btn_back");
});

$(document).on("click",".delivery_chage_btn",function(){
	$(".txt_title").html("배송지 변경");
	$(".order_view_wrap").hide();	
	$(".delivery_chage_wrap").show();
	$(".btn_back").addClass("div_back").removeClass("btn_back");
});

$(document).on("click",".order_hide_btn",function(){
	if(confirm("확인 버튼을 누르시면 구매내역에서 숨김처리되어 복구할 수 없습니다.\n주문내역을 숨김처리 하시겠습니까?")){
		$.ajax({ 
			type : "post"
			, url : "order_view.php"
			, cache : false  
			, data: {
				"mode":"order_hide",
				"od_id":od_id
			}
			, success : function(response){ 
				var json = $.parseJSON(response); 
				if(json["result"]=="ok"){
					location.href="order_list.php";
				} else{
					alert(msg);
				}
			}
		}); 
	}
});

$(document).on("click", ".order_wait_cancel_btn", function(){
	if(confirm("입금전 주문서 입니다\n해당 주문을 취소하시겠습니까?")){
		$.ajax({ 
			type : "post"
			, url : "order_cancel.php"
			, cache : false  
			, data: {
				"mode":"order_cancelrequest",
				"od_id":od_id,
				"ct_cancel_why":"입금전취소"
			}
			, success : function(response){			
				var json = $.parseJSON(response); 
				if(json["result"]=="ok"){
					alert("취소가 완료되었습니다");
					document.location.reload();
				} else{
					alert(json["msg"]);
				}	
			} 
		});
	}
});


$(document).on("click", ".ticket_send_btn", function(){
	var od_id = $(this).data("od_id");
	var it_id = $(this).data("it_id");
	$.ajax({
		type: "post",
		url: "order_list.php",
		data: {
			"mode":"ticket_send",
			"od_id":od_id,
			"it_id":it_id
		},
		cache: false,
		async: false,
		success: function(response){
			var json = $.parseJSON(response);
			if(json["result"]=="ok"){
				alert(json["msg"]);
			} else{
				alert(json["msg"]);
			}
		}
	});
});
</script>

<?php
include_once $nfor[skin_path]."tail.php";
?>