<?php
include_once $nfor[skin_path]."mypage_head.php";
?>

<style>
/* 간격재조정 */
.title_big { margin:0px; padding:0px; }

.order_view_wrap { padding:0px; background-color:#fff; }

/* 배송정보 */
.od_dy_wrap { margin-top:20px; }
.od_dy_wrap .od_dy_title { position:relative; height:30px; font-size:13px; line-height:30px; color:#252d35; font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;}
.od_dy_wrap .od_dy_title .delivery_chage_btn { position:absolute; top:-10px; right:0px; display:inline-block; padding:0px 10px; margin:10px 0px; border:solid 1px #dcdcdc; height:20px; line-height:20px; text-align:center; font-size:11px; text-decoration:none; color:#666; background-color:#ffffff; border-radius:3px; font-weight:normal; font-family:'NanumGothic'; }

/* 결제정보 */
.od_pay_wrap { margin-top:20px;  }
.od_pay_wrap .od_pay_title { position:relative; height:30px;  font-size:12px; line-height:30px; color:#252d35; font-family: 'NanumGothicBold';}

/* 즉시취소 */
.order_one_btn { margin-top:10px; }
.order_one_btn div { width:100%; padding:0px; }
</style>


<div class="order_view_wrap">


	<div class="order_view">
		<div class="order_info_top_wrap">
			<span class="od_datetime_wrap">
			주문일 : <b class="od_datetime"><?=$order[od_datetime]?></b>
			</span>
			<span class="od_id_wrap">주문번호 : <b class="od_id"><?=$order[od_id]?></b></span>
		</div>
		<?php
		$print = $return["list"];
		include $nfor[skin_path]."inc_order_list.php";
		?>
	</div>


	<? if($order[od_is_ticket]){ ?>
	<div class="od_dy_wrap">
		<div class="od_dy_title">사용자정보</div>
		<table class="tb_form" cellpadding="0" cellspacing="0">
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
		<div class="od_dy_title">
			배송정보
			<?php // 결제완료이고 배송상품이면서 배송대기인 상품이 있을때만 배송지변경가능
			if($order[delivery_chage_btn]){	
			?>
			<a class="delivery_chage_btn">배송지 변경</a>
			<? } ?>
		</div>
		<table class="tb_form" cellpadding="0" cellspacing="0">
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
	<? } ?>


	<? if($order[od_pay_step]=="4"){ ?>
	<div class="od_dy_wrap">
		<div class="od_dy_title">입금계좌</div>
		<table class="tb_form" cellpadding="0" cellspacing="0">
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
		<table class="tb_form" cellpadding="0" cellspacing="0">
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


	
	<div class="bottom_btn">
		<?php if($order[od_pay_step]=="4"){ ?>
		<span class="btn_pack"><a class="order_wait_cancel_btn btn_lg white">즉시취소</a></span>
		<?php } ?>
		<span class="btn_pack"><a href="<?=$back_btn_href?>" class="btn_lg black">주문목록</a></span>
		<?php if($order[order_hide_btn]){ ?>
		<span class="btn_pack"><a class="btn_lg white order_hide_btn">주문숨김</a></span>
		<?php } ?>
	</div>


</div>


<?php
include_once $nfor[skin_path]."inc_delivery_chage.php";
?>


<script>
var od_id = "<?=$order[od_id]?>";

$(document).on("click",".delivery_chage_btn",function(){
	$(".delivery_chage_wrap").show();
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
include_once $nfor[skin_path]."mypage_tail.php";
?>