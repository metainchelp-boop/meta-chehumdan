<?php
include_once $nfor[skin_path]."mypage_head.php";
include_once $nfor[path]."/lib/div.lib.php";
?>
<style>

/*취소/교환/환불 refund_view*/
.refund_view{width:100%; border:solid 1px #efefef; background-color:#FFF; min-height: 45px;padding:25px 20px; box-sizing:border-box; -webkit-box-sizing:border-box; min-height:1000px;}
.refund_view .order_list_top_wrap { padding:0px 0px; }
.refund_view .order_list_tab  {border-top:solid 1px #999999; border-left:solid 1px #999999; overflow:hidden;background-color:#f5f5f5;  border-bottom:2px solid #666; border-left:1px solid #dadddf; border-right:1px solid #dadddf; border-top:1px solid #dadddf;}
.refund_view .order_list_tab li {margin:0px 0px 0px -2px; background-color:#f5f5f5; float:left; width:150px;  border-right:solid 1px #dadddf; border-left:solid 1px #dadddf; }
.refund_view .order_list_tab li a{display:block; height:45px; line-height:45px;  text-align:center; font-size:14px; letter-spacing:0px; text-decoration:none; color:#999999; }
.refund_view .order_list_tab li.on {background-color:#FFFFFf; }
.refund_view .order_list_tab li.on a {color:#333; font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;}


.refund_view .money_serch{margin:0px 0 15px;padding:19px 30px;border:1px solid #dadddf;background-color:#f5f5f5;} 
.refund_view .money_serch .month_area{border:1px solid #d5d8dc}
.refund_view .money_serch .month_list{display:table;*overflow:hidden;width:100%;table-layout:fixed;zoom:1;}
.refund_view .money_serch .month_list li{display:table-cell;*float:left;border-left:1px solid #f0f5f8;background-color:#fff;line-height:30px;text-align:center;zoom:1;}
.refund_view .money_serch .month_list li:first-child{border-left:none}
.refund_view .money_serch .month_list li a{display:block;*padding:0 8px;font-size:11px;color:#808080;}
.refund_view .money_serch .month_list li.on a{background-color:#5f5f5f;color:#fff}
.refund_view .money_serch .date{position:relative;margin:0 10px;color:#808080}
.refund_view .money_serch .date:after{display:block;clear:both;content:''}
.refund_view .money_serch .date .d_bx{display:inline-block;float:left;position:relative;width:102px;height:30px;border:1px solid #d5d8dc;background-color:#fff;line-height:30px;font-family:Tahoma,Geneva,sans-serif;font-size:12px;color:#808080;text-decoration:none;text-indent:10px}
.refund_view .money_serch .date .d_bx .ico{display:inline-block;position:absolute;top:50%;right:10px;width:15px;height:14px;margin-top:-7px;background-position:0 0;background:url(/skin/demo/img/cal_ico2.png) no-repeat;}
.refund_view .money_serch .date .dash{float:left;width:15px;line-height:30px;text-align:center}


.refund_view .tbl{width:100%;margin-top:10px;border-top:2px solid #4f525c;border-bottom:1px solid #dbdee6; margin-bottom:20px; font-size:12px;}
.refund_view .tbl th{padding:11px 0 11px;border-bottom:1px solid #e7e7e9;background-color:#f5f5f5;text-align:center;vertical-align:middle;color:#666;  font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;}
.refund_view .tbl td{padding:11px 0 11px 0px;border-bottom:1px solid #edeff4;color:#333;background-color:#fff; text-align:center;} 
.refund_view .tbl td em{display:block;font-size:12px;font-family:verdana;color:#8c8f9a;}
.refund_view .tbl td .bank_date{display:block;font-size:11px;font-family:verdana;color:#8c8f9a;letter-spacing:0px;}
.refund_view .tbl td .bank_info{display:block;font-size:11px;font-family:verdana;color:#8c8f9a;letter-spacing:0px;}
.refund_view .tbl td strong{display:block; line-height:35px;font-size:12px;font-family:verdana;color:#545456;}
.refund_view .tbl td .price{font-family: tahoma;color:#ff5711;font-weight:bold;}
.refund_view .product_info{overflow:hidden;}
.refund_view .product_info .thumb{float:left;overflow:hidden;width:106px;height:83px;}
.refund_view .product_info .thumb img{float:left;width:100%;;}
.refund_view .product_info .info{float:left;text-align:left;padding:10px 5px;}
.refund_view .product_info .info .pro_name{display:block;color:#3d4058;height:20px; font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;} 
.refund_view .product_info .info .addr{display:block;font-size:11px;width:322px;}
.refund_view .opt_info{margin-top:5px;margin-bottom:1px;padding:8px 0 5px 19px;background:#eaebf0;letter-spacing:0px;line-height:17px;text-align:left;}
.refund_view .opt_info .opt_name{display:block;color:#3d4058; font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;}
.refund_view .opt_info .opt_num{display:block;font-size:11px;color:#616372;}
.refund_view .opt_info .opt_ticket{display:block;font-size:11px;color:#616372;}


.refund_view .tbl td .btn01{display:block; width:70px;padding:5px; margin:5px auto;border:solid 1px #dcdcdc;border-radius:3px;font-size:11px;letter-spacing:0px;color:#666;}
.refund_view .tbl td .btn02{display:block; width:70px;padding:5px; margin:0px auto 5px;border:solid 1px #9297a8; background-color:#9297a8;border-radius:3px;font-size:11px;letter-spacing:0px;color:#FFF;}
.refund_view .tbl td .btn03{display:block; width:70px;padding:5px; margin:0px auto 5px;border:solid 1px #ff6600; background-color:#ff6600;border-radius:3px;font-size:11px;letter-spacing:0px;color:#FFF;}
.refund_view .tbl td .btn04{display:block; width:70px;padding:5px; margin:0px auto 5px;border:solid 1px #3399ff; background-color:#3399ff;border-radius:3px;font-size:11px;letter-spacing:0px;color:#FFF;}


.delivery_info{margin-top:20px;}
.delivery_info .h_txt{font-size:20px; line-height:45px;}
.delivery_info .tbl{table-layout:auto; border-top:2px solid #4f525c;}
.delivery_info .tbl td {height:2px; text-align:left;}
.delivery_info .tbl th {width:149px;height:20px;font-size:12px;color:#666;background-color:#f5f5f5;border-right:1px solid #ddd;border-bottom:1px solid #ddd;}  
.delivery_info .tbl td {padding-left:20px;width:520px;font-size:12px;color:#333;border-bottom:1px solid #ddd;}
.delivery_info .tbl th.frt {width:149px;height:20px;font-size:12px;color:#666;background-color:#f5f5f5;border-left:1px solid #ddd;border-bottom:1px solid #ddd;border-right:0px solid #ddd;;}
.delivery_info .tbl td.frt {padding-left:20px;width:520px;font-size:12px;color:#666;border-bottom:1px solid #ddd;border-left:1px solid #ddd; vertical-align:top; line-height:18px;}
.delivery_info .tbl th.last {width:149px;font-size:12px;color:#666;background:#f7f7f7;border-right:1px solid #ddd;border-bottom:1px solid #bcbcb8;}
.delivery_info .tbl td.last {padding-left:20px;width:520px;font-weight:bold;font-size:12px;color:#333;border-bottom:1px solid #bcbcb8;}

.payment_info{margin-top:20px;}
.payment_info .h_txt{font-size:20px; line-height:45px;}
.payment_info .tbl{table-layout:auto; border-top:2px solid #4f525c;}
.payment_info .tbl td {height:20px; text-align:left;}
.payment_info .tbl th {height:20px;font-size:12px;color:#666;background-color:#f5f5f5;border-bottom:1px solid #ddd;  font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;} 
.payment_info .tbl td {padding-left:20px;width:520px;font-size:12px;color:#333;border-bottom:1px solid #ddd; line-height:20px;}
.payment_info .tbl th.frt {height:20px;font-size:12px;color:#666;background-color:#f5f5f5;border-left:1px solid #ddd;border-bottom:1px solid #ddd;border-right:0px solid #ddd;  font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;}
.payment_info .tbl td.frt {padding:10px 20px;font-size:12px;color:#666;border-bottom:1px solid #ddd;border-left:1px solid #ddd; vertical-align:top; line-height:18px;}
.payment_info .tbl th.last {font-size:12px;color:#666;background:#f7f7f7;border-right:1px solid #ddd;border-bottom:1px solid #bcbcb8;}
.payment_info .tbl td.last {padding-left:20px;width:520px;font-weight:bold;font-size:12px;color:#333;border-bottom:1px solid #bcbcb8;}
.payment_info .tbl td.f9{background-color:#F9F9F9;}
.payment_info .inner{overflow:hidden;padding:5px 0px;}
.payment_info .inner .left{float:left;;color:#333;  font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;}
.payment_info .inner .right{float:right;}
.payment_info .inner b{font-family:verdana}


</style>
<div class="refund_view">
	<div class="order_list_top_wrap">

		<ul class="order_list_tab">
			<li<?=!$type?" class='on'":""?>><a href="order_list.php" >전체</a></li>
			<li<?=$type=="delivery"?" class='on'":""?>><a href="order_list.php?type=delivery" >배송상품</a></li>
			<li<?=$type=="ticket"?" class='on'":""?>><a href="order_list.php?type=ticket" >티켓상품</a></li>
		</ul>

	</div>



	<div class="money_serch"> 
		<table cellpadding="0" cellspacing="0" border="0" style="background-color:#edeff2;" >
			<colgroup><col><col width="243"><col width="86"></colgroup>
			<tr>
			<td>
				<div class="month_area">
					<ul class="month_list">
							<li class="on"><a href="#">전체</a></li>
							<li><a href="#">오늘</a></li>
							<li><a  href="#">1주일</a></li>
							<li><a href="#">1개월</a></li>
							<li><a href="#">3개월</a></li>
					</ul>
				</div>
			</td>
			<td>
				<div  class="date">
					<a href="#"  class="d_bx"><span >2016.05.13</span><span class="ico"></span><input type="hidden" value="2016.05.13" readonly=""></a> 
					<span class="dash">~</span>
					<a href="#" class="d_bx"><span>2016.11.13</span><span class="ico"></span><input type="hidden" value="2016.11.13" readonly=""></a>
				</div>
			</td>

			<td><a href="#"><img src="/skin/demo/img/serbtn_mone.png"></a></td>
			</tr>

		</table>
	</div>

	<b class="my_title fotm"><?=$nfor[title]?></b>

	<table class="tbl" cellspacing="0" border="0">
		<colgroup> <col width="170"><col width="500"><col width="121"><col width="139"> <col width="139">
		</colgroup>
		<tr>
			<th>날짜/주문번호</th>
			<th>상품명/옵션</th>
			<th>환불금액</th>
			<th>취소접수일/상태</th>
		</tr>
		<!-- 배송상품 -->
		<tr>
			<td>

				<strong>2016.08.25</strong>
				<em class="order_num">1272631890</em>
				<a class="btn01" href="order_view.php">반품상세보기</a>
			</td>
			<td>
				<div class="product_info">
					<div class="thumb"><img src="/skin/demo/img/nonoimg.png"></div>
					<div class="info">
						<span class="pro_name">최대2천원★무봉제 김장버무리매트</span>
						<span class="addr">(405-230)인천 남동구 간석동 현광아파트 다동 605호</span>
					</div>
				</div>
				<div class="opt_info">
					<span class="opt_name">03 김장버무리 원형_블루_140</span>
					<span style="opt_num">(구매수량<b style="color:#ff0000;"> 1 </b>개 )</span>
				</div>
			</td>
			<td><b class="price">3,900원</b></td>
			<td>
				<strong>2016.08.25</strong>
				<a class="btn01">반품대기</a>
			</td>
			
		</tr>

		<!-- <tr>
			<td colspan="5" class="none">구매내역이 없습니다.</td>
		</tr> -->
		<!-- //티켓상품 -->
	</table>



		<div  class="delivery_info">
		<div class="h_txt fotm">주문취소 정보</div>
			<table  cellspacing="0" cellpadding="0" border="0" class="tbl">
			<tr>
				<th >신청일시</th>
				<td>임준아</td>
			
			</tr>
			<tr>
				<th>처리일시</th>
				<td>010-2313-4531</td>
			
			</tr>
			<tr>
				<th>취소사유</th>
				<td>맘에안듬</td>	
			</tr>
			<tr>
				<th >환불수단</th>
				<td>신용카드</td>	
			</tr>

			</table>
	</div>

	<div class="payment_info">
		<div class="h_txt fotm">환불금액 확인</div>
		<table cellpadding="0" cellspacing="0" border="0" class="tbl">
		<colgroup><col><col ></colgroup>
		<tr>
			<th>결제수단</th>
			<th class="frt">상품 가격</th>
		</tr>
		<tr>
			<td>
			<div>[신용카드]</div>
			<span>KB국민카드</span>
			<span>일시불</span>
			
			</td>
			<td  class="frt">
				<div class="inner">
					<div class="left">총 상품 가격</div>
					<div class="right"><b>13,900</b>원</div>
				</div>
				<div class="inner">
					<div class="left">할인금액</div>
					<div class="right"><img src="/skin/demo/img/m_ico.png" style="vertical-align:-2px;"> <b>0원</div>
				</div>
				
				<div class="inner">
					<div class="left">배송비</div>
					<div class="right"><img src="/skin/demo/img/p_ico.png" style="vertical-align:-2px;"> <b>0</b>원</div>
				</div>
				<div class="inner">
					<div class="left">쿠폰사용</div>
					<div class="right"><img src="/skin/demo/img/m_ico.png" style="vertical-align:-2px;"> <b>0</b>원</div>
				</div>
			</td>
		</tr>
		<tr>
			<td></td>
			<td  class="frt f9">
				<div class="inner">
					<div class="left">신용카드</div>
					<div class="right"><b>13,900</b>원</div>
				</div>
				<div class="inner">
					<div class="left">총 취소금액</div>
					<div class="right"><img src="/skin/demo/img/eq_ico.png" style="vertical-align:-4px;"> <b class="price">13,900</b><span class="price">원</span></div>
				</div>
			</td>
		</tr>
		</table>	
	</div>
	<style>
	.btn_set{margin:30px auto; width:275px;overflow:hidden;}
	.btn_set .btn { display:inline-block;; width: 269px; height: 64px; font-size:24px; line-height: 64px;background-color:#e61b62;  border:solid 1px #e61b62; color:#FFF; font-family:'NanumGothicBold';text-align:center; }
	.btn_set .btn:hover { background-color:#FFFFFF; border:solid 1px #e61b62; color:#e61b62 }

	</style>
	<div class="btn_set">
	<a href="/refund_list.php" class="btn">목록으로</a>
	<div style="clear:both;"></div>
	</div>
</div>
<?
include_once $nfor[skin_path]."mypage_tail.php";
?>