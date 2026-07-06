<?php
include_once $nfor[skin_path]."head.php";
?>
<style>
.order_top{ margin-top:60px; margin-bottom:50px; position:relative}
.order_top h3{ font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;font-size:35px; margin:20px 0px;;}
.order_top h3 span{font-size:24px;color:#ff0000}
.order_top .sub_txt{font-size:14px; color:#666; line-height:20px;}
.order_top .pross{overflow:hidden; position:absolute; top:40px; right:0;  font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;}
.order_top .pross li{float:left; padding:10px 10px;}
.order_top .pross li b{font-size: 18px;}
.order_top .pross li span{font-size: 18px;}
.pross .on{color:#ff0000}
/*결재완료cart_order_result */

.cart_wrap{ width:1200px;}
.cart_wrap .cart_inner{position:relative; height:130px;width:100%;}
.cart_wrap .cart_inner .my_title{position:absolute;top:20px; left:0px; font-size:30px;}
.cart_wrap .cart_inner .my_title_sub{position:absolute;top:90px; left:0px;font-size:14px; color:#999;}
.cart_wrap .cart_inner .cartimg{display:inline-block;position:absolute; width:307px;top:70px;right:0px;}

.order_result_wrap{position:relative;width:636px;margin:70px auto 0;font-size:12px;}
.order_result_wrap .title{font-size:35px;text-align:center; margin-bottom:39px;}
.order_result_wrap table.tbl_order_result{table-layout:fixed;width:100%;margin:35px 0 15px;border-top:1px solid #cfcfd8;border-bottom:1px solid #cfcfd8; font-size:12px;}
.order_result_wrap table.tbl_order_result th{background-color:#FFFFFF;border-right:1px solid #dbdbe2;}
.order_result_wrap table.tbl_order_result th .order_num{display:block;height:18px;text-align:center;}
.order_result_wrap table.tbl_order_result th a.num{color:#111}
.order_result_wrap table.tbl_order_result th a.num:hover{text-decoration:underline}
.order_result_wrap table.tbl_order_result td{padding:9px 0;text-align:left}
.order_result_wrap table.tbl_order_result tr.total td{padding:13px 0 14px;border-bottom:1px solid #dbdbe2;background-color:#e9e9e9;}

.order_result_wrap table.tbl_order_result tr.payment td{border-bottom:1px solid #ededed;background-color:#FFFFFF;}
.order_result_wrap table.tbl_order_result tr.savemoney td{border-bottom:1px solid #ededed;background-color:#FFFFFF;}
.order_result_wrap table.tbl_order_result td.tit{font-size:11px;color:#767676;letter-spacing:-1px}
.order_result_wrap table.tbl_order_result td.tit span{padding-left:23px}
.order_result_wrap table.tbl_order_result td.price{width:auto;float:none;padding-right:28px !important;font-weight:bold;color:#000;text-align:right}
.order_result_wrap table.tbl_order_result td.price em{font-family:tahoma;font-size:13px}
.order_result_wrap table.tbl_order_result tr.total td .h_total{display:inline-block;overflow:hidden;width:100px;height:18px;font-size:14px; color:#000;}
.order_result_wrap table.tbl_order_result tr.total td em{font-size:23px;color:#e61b62;vertical-align:middle}
.order_result_wrap table.tbl_order_result tr.total td .won{display:inline-block;width:16px;}


.order_result_wrap .order_dsc{font-size:11px;letter-spacing:-1px;text-align:left;color:#7b7b7b}
.order_result_wrap .order_dsc .bu{font-size:12px}
.order_result_wrap .order_dsc a{color:#545456;text-decoration:underline}
.lpop_balloon,.lpop_saving{display:inline-block;*display:inline;position:absolute;height:24px;padding:0 7px;border:1px solid #cfcbc4;background-color:#fffff2;font-size:11px;line-height:23px;color:#5b5d6e;letter-spacing:-1px;zoom:1}
.cart_wrap .btn_section{padding-bottom:50px;overflow:hidden; text-align:center;}
.cart_wrap .bt_area {text-align:center;margin:0 auto; width:585px;}


.order_result_wrap table.tb_accout{width:100%;margin:35px 0 -17px;border:solid #d8d8df;border-width:1px 0;}
.order_result_wrap table.tb_accout th{padding:11px 0 9px;border-top:1px solid #ededed;border-right:1px solid #dcdbe3;background:#fdfdfd;font-weight:normal;font-size:11px;text-align:left;letter-spacing:-1px}
.order_result_wrap table.tb_accout th span{padding-left:11px}
.order_result_wrap table.tb_accout td{padding-left:23px;border-top:1px solid #ededed;color:#777}
.order_result_wrap table.tb_accout tr:first-child th,.order_result_wrap table.tb_accout tr:first-child td{border-top:0}
.order_result_wrap table.tb_accout td .sum{font-size:14px;color:#e61b62}
.order_result_wrap table.tb_accout td .sum em{position:relative;top:1px;font-family:tahoma;font-size:18px}
.order_result_wrap table.tb_accout td a.btn_banking_go{display:inline-block;border:solid 1px #DCDCDC;border-radius:3px;text-align:center;padding:3px 5px; font-size:11px;letter-spacing:-1px;vertical-align:middle;}
.bt_continue{float:left; display:inline-block; width: 276px; height: 64px;  line-height: 64px;  border:solid 1px #e61b62; color:#e61b62; font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;}
.bt_checkhist{float:left;display:inline-block;; width: 276px; height: 64px; line-height: 64px; margin-left: 8px; background-color:#e61b62;  border:solid 1px #e61b62; color:#FFF; font-family:'NanumGothicBold';}
.bt_checkhist:hover{color:#FFF;}
</style>
<div class="cart_wrap">

	<div class="order_top">
		<h3><?=$nfor[title]?></h3> 
		<div class="sub_txt">주문하신 상품의 자세한 옵션과 가격은 아래 상품정보에서 확인하실 수 있습니다.</div>
		<div class="pross">
			<ul>
				<li><b>01</b> <span>장바구니</span></li>
				<li> > </li>
				<li><b >02</b> <span>주문 /결제</span></li>
				<li class="on"> > </li>
				<li><b class="on">03</b> <span class="on">결제완료</span></li>
			</ul>	
		</div>
	</div>

	<div class="order_result_wrap">


		<h3 class="title fotm">구매가 정상적으로 완료되었습니다.</h3>
		<p class="order_dsc"><span class="bu">·</span> 자세한 구매내역 확인 및 문자발송, 배송지 변경 등의 서비스는 사이트 우측 상단의 <a href="order_list.php">마이페이지&gt;구매내역</a>에서 이용 가능합니다.비회원구매시 주문번호와 주문패스워드를 넣고 조회가가능합니다</p>
		<p class="order_dsc"><span class="bu">·</span> 카드사 포인트 사용 내역은 카드사 페이지에서 확인 가능합니다.</p>
		
		
		<?
		if($order[od_pay_step]=="4"){
		?>
		<!-- 무통장입금시 -->
		<table cellspacing="0" border="0" class="tb_accout">
		<colgroup>
			<col width="181">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row"><span>입금하실 금액</span></th>
			<td><strong class="sum"><em><?=number_format($order["od_".$order[od_payment_type]."_price"])?></em>원</strong> (<?=date("Y년 m월 d일 H시 까지",strtotime($order[od_bank_expire]))?>)</td>
		</tr>
		<tr>
			<th scope="row"><span>입금계좌</span></th>
			<td><?=$order[od_bank_number]?></td>
		</tr>
		</tbody>
		</table>
		<!-- //무통장입금시 -->
		<? } ?>


		<table cellspacing="0" border="0"  class="tbl_order_result">
		<colgroup>
			<col width="181"><col width="130"><col>
		</colgroup>
		<tbody>
		<tr class="total">
			<th scope="row" rowspan="4"><span class="order_num fotr">주문번호</span><a href="order_view.php?od_id=<?=$order[od_id]?>" class="num"><?=$order[od_id]?></a></th>
			<td class="tit"><span class="h_total fotr">총 결제 금액</span></td>
			<td class="price">
				<em><?=number_format($order[od_total_price])?></em><span class="won">원</span>
			</td>
		</tr>
		<? if($order[od_payment_type] <> "money" and $order[od_payment_type] <> "coupon"){ ?>
		<tr class="payment">
			<td class="tit"><span><?=admin_echo($order,"od_payment_type")?></span></td>
			<td class="price"><em><?=number_format($order["od_".$order[od_payment_type]."_price"])?></em>원</td>
		</tr>
		<? } ?>

		<? if($order[od_money_price]){ ?>
		<tr class="savemoney">
			<td class="tit"><span>적립금 사용</span></td>
			<td class="price"><em><?=number_format($order[od_money_price])?></em>원</td>
		</tr>
		<? } ?>
		<? if($order[od_coupon_price]){ ?>
		<tr class="savemoney">
			<td class="tit"><span>쿠폰 사용</span></td>
			<td class="price"><em><?=number_format($order[od_coupon_price])?></em>원</td>
		</tr>
		<? } ?>
		</tbody>
		</table>
	
	   </div>

	   <div class="btn_section">

			<div class="bt_area">
				<a href="index.php" class="bt_continue" >쇼핑 계속하기</a>
				<a href="order_view.php?od_id=<?=$order[od_id]?>" class="bt_checkhist" style="color:#FFF;">구매내역 확인</a>
			</div>

	   </div>


	</div>


</div>
<?
include_once $nfor[skin_path]."tail.php";
?>