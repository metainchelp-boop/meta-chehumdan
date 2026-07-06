<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.order select { padding-left:10px; height:32px;  border:solid 1px #e7e7e9; background: url(skin/demo/img/select_background.png) no-repeat 100% 50%;  font-size: 12px; -webkit-appearance: none;  -moz-appearance: none;  appearance: none; vertical-align:2px;}
.order select { appearance: none; -webkit-appearance: none;}
.order select::-ms-expand { display:none; }
.width150{width:250px;}
.order .order_top{ margin-top:60px; margin-bottom:50px; position:relative}
.order .order_top h3{ font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;  font-size:35px; margin:20px 0px;;}
.order .order_top h3 span{font-size:24px;color:#ff0000}
.order .order_top .sub_txt{font-size:14px; color:#666; line-height:20px;}
.order .order_top .pross{overflow:hidden; position:absolute; top:40px; right:0;  font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;}
.order .order_top .pross li{float:left; padding:10px 10px;}
.order .order_top .pross li b{font-size: 18px;}
.order .order_top .pross li span{font-size: 18px;}
.order .order_top .pross .on{color:#ff0000}

/*구매 cart_order*/
.cart_wrap{ width:1000px;}
.cart_wrap .cart_inner{position:relative; height:130px;width:100%;}
.cart_wrap .cart_inner .my_title{position:absolute;top:20px; left:0px; font-size:30px;}
.cart_wrap .cart_inner .my_title_sub{position:absolute;top:90px; left:0px;font-size:14px; color:#999;}
.cart_wrap .cart_inner .cartimg{display:inline-block;position:absolute; width:307px;top:70px;right:0px;}
.cart_wrap .order_infor{width:100%;}

.order_cation .h_txt, 
.payment_infor .h_txt, 
.delivery_infor .h_txt, 
.point_infor .h_txt,
.order_product .h_txt,
.order_infor .h_txt{display:block;margin-left:-1px; margin-top:50px; margin-bottom:20px;height:24px;font-size:18px;color:#16181a; font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;}

.payment_infor .h_sub_txt,
.point_infor .h_sub_txt,
.delivery_infor .h_sub_txt,
.order_infor .h_sub_txt{display:inline-block;padding-left:20px;color:#666; }

.order_product .tbl,
.point_infor .tbl,
.delivery_infor .tbl,
.order_infor .tbl{width:100%;margin-top:10px;border-top:2px #959da6 solid;border-bottom:0px #dfe2e6 solid; margin-bottom:20px; font-size:12px;}

.order_product .tbl th,
.point_infor .tbl th,
.delivery_infor .tbl th,
.order_infor .tbl th{padding: 20px 0px 20px 20px; ;border-bottom:1px #dfe2e6 solid; color:#5b5e63; background-color:#fafafa;text-align:left;vertical-align:middle} 

.order_product .tbl td,
.point_infor .tbl td,
.delivery_infor .tbl td,
.order_infor .tbl td{padding:11px 0 11px 11px;border-bottom:1px #dfe2e6 solid; color:#333;background-color:#fff; text-align:left; line-height:25px;} 

.order_product .tbl td input[type=radio],
.point_infor .tbl td input[type=radio],
.delivery_infor .tbl td input[type=radio],
.order_infor .tbl td input[type=radio]{vertical-align:-3px; margin-right:5px;}

.order_product .tbl td input[type=radio],
.point_infor .tbl td input[type=radio],
.delivery_infor .tbl td input[type=radio],
.order_infor .tbl td input[type=radio]{vertical-align:-3px; margin-right:5px;}

.order_product .tbl td input[type=text],
.point_infor .tbl td input[type=text],
.delivery_infor .tbl td input[type=text],
.order_infor .tbl td input[type=text]{border:solid 1px #e7e7e9;height:30px;padding-left:5px;vertical-align:-0px; color:#666;}


.order_product .tbl td label,
.point_infor .tbl td label,
.delivery_infor .tbl td label,
.order_infor .tbl td label{margin-right:10px;}

.point_infor .tbl .frst th,.point_infor .tbl .frst td,
.delivery_infor .tbl .frst th,.delivery_infor .tbl .frst td,
.order_infor .tbl .frst th,.order_infor .tbl .frst td{color:#16181a} 

.order_product .tbl td,
.point_infor .last th,.point_infor .tbl .last td,
.delivery_infor .tbl .last th,.delivery_infor .tbl .last td,
.order_infor .tbl .last th,.order_infor .tbl .last td{border-bottom:1px solid #dbdee6} 
/**공통사용 테이블 **/

.order_input{height:16px;margin-bottom:2px;padding:4px 6px 0; height:24px;border:1px solid;border:1px #c2c7cc solid;vertical-align:middle;color:#666;}

.delivery_infor input{margin-bottom:2px;padding:4px 6px 0; height:24px;border:1px solid;border:1px #c2c7cc solid;vertical-align:middle;color:#666;}
.delivery_infor input[type="checkbox"] { display: inline-block; width: 14px;  height: 14px;-webkit-appearance: none;border-radius: 0;  border: 0!important;   margin: 0px 0px 0px !important;  padding: 0px 0px 0px;  cursor: pointer; color: red; vertical-align: -3px;}
.delivery_infor label{margin-right:15px;}

#zipcode_btn  { cursor:pointer; position:absolute; left:85px; top:0px; height:34px; display:block; width:100px; text-align:center; line-height:34px; border:solid 1px #959da6; background-color:#FFFFFF; background:-webkit-gradient(linear,left top,left bottom,from(#fff),to(#ecebf0)); box-shadow:none; }

#zipcode_btn.hide  { display:none; }
#zipcode_wrap { display:none; width:100%; height:300px; position:relative; }
#od_zipcode_btn  { cursor:pointer; position:absolute; left:85px; top:0px; height:28px; display:block; width:100px; text-align:center; line-height:28px; border:solid 1px #959da6; background-color:#FFFFFF; background:-webkit-gradient(linear,left top,left bottom,from(#fff),to(#ecebf0)); box-shadow:none; }

.money_wrap { width:100%; min-height:26px; vertical-align:top; }
.money_price_wrap {display:inline-block!important; font-size:14px; color:#666; margin-bottom:10px; }
.money_price_wrap .all_btn{ display:inline-block;width: 96px; text-align:center;padding: 10px 15px;border: 1px solid #959da6; font-size: 11px;  font-family: Dotum,sans-serif; line-height: 1;  color: #5b6065; letter-spacing: -.08em;}
#money_price { padding:2px 3px; width:200px; text-align:right; letter-spacing:0px; height: 24px;  padding-left: 13px; border: 1px #c2c7cc solid; line-height: 26px; color: #16181a;}
.txt2{color: #575e66; font-size:12px;}

.coupon_wrap { position:relative; width:500px; height:26px; }
.coupon_price_wrap { position:absolute; right:20px; top:0px; font-size:16px; color:#666;font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;}
#coupon_use_btn { display:inline-block; padding:0px 20px; text-align:center; height:26px; line-height:26px;border:1px solid #959da6;color:#5b6065;background-color:#fff;font-size:12px;vertical-align:middle;cursor:pointer; }
#coupon_list_wrap { display:none;  margin:0px 0px; padding:10px 20px 0px; width:480px; } 
.couponlist{ width:100%; border-top:1px solid #4f525c;}
.couponlist li{position:relative; line-height:35px; color:#666; border-bottom:solid 1px #DCDCDC; background-color:#FAFAFA;padding-left:10px;}
.coupon_select{ right:10px;top:5px;height:25px; border:solid 1px #DCDCDC; background-color:#FFFFFF; color:#666; width:90%;}
#coupon_btn { text-align:center;padding:10px; }
#coupon_cancel { display:inline-block; line-height:25px; color:#777; border:solid 1px #bbb; background-color:#f7f7f7; padding:8px 10px; font-size:12px; cursor:pointer; border-radius:3px; width:40%;} 
#coupon_apply { display:inline-block;  line-height:25px; color:#fff; border:solid 1px #666; background-color:#666; padding:8px 10px; font-size:12px; cursor:pointer; border-radius:3px; width:40%;}

.order_product .h_sub_txt{display:inline-block;color:#666;}
.order_product .tbl_cart{width:100%;margin-top:10px;border-top:2px #959da6 solid ;margin-bottom:0px; font-size:14px;}
.order_product .tbl_cart th{padding:20px 0 20px;border-bottom:1px solid #e7e7e9; border-left:1px solid #edeff4;background-color:#f5f5f5;text-align:center;vertical-align:middle}
.order_product .tbl_cart td{padding:20px;border-bottom:1px #a6a9ad solid;border-left:1px solid #edeff4;color:#333;background-color:#fff;text-align:center;}
.order_product .tbl_cart th:first-child{border-left:none;}
.order_product .tbl_cart td:first-child{border-left:none;}
.delivery_infor .delbtn{display:inline-block; width:110px; height:26px;border:1px solid #959da6;color:#5b6065;background-color:#fff;font-size:12px;padding:3px 7px 3px 7px;vertical-align:middle;cursor:pointer;}

.it_name_wrap { position:relative;  padding:0px 10px; }
.it_name_wrap a { display:block; position:relative; box-sizing:border-box; -webkit-box-sizing:border-box; }

.it_name_wrap .cart_item_img { display:block; position:absolute; width:80px; height:80px;  border:solid 1px #DCDCdc}
.it_name_wrap .it_name { display:block; font-size:12px; position:relative; margin-left:120px;padding:15px 0px;;  display:flex; color:#3d4058;}
.it_name_wrap .it_name span { overflow:hidden;text-align:left; text-overflow:ellipsis; -webkit-line-clamp:2; display:-webkit-box; -webkit-box-orient:vertical; font-size:14px;font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal; }

.it_option_wrap{ display:block; font-size:14px; position:relative; margin-left:120px; margin-top:0px; margin-bottom:20px;}
.it_option_wrap .inner { display:block; border-bottom:1px #dfe2e6 solid; padding:10px 5px; background-color:#fff; text-align:left; text-overflow:ellipsis; -webkit-line-clamp:2; display:-webkit-box; -webkit-box-orient:vertical;}

.option_count_wrap { position:relative; width:100%; }
.option_count_wrap .opt_name{display:inline-block;width:60%; font-size:11px; color:#5b6065;line-height:18px; font-family: "돋움",dotum,"Apple SD Gothic Neo",sans-serif; letter-spacing:-.8px;}
.option_count_wrap .opt_cnt{display:inline-block;width:20%;font-size:11px; color:#5b6065;line-height:18px;  font-family: "돋움",dotum,"Apple SD Gothic Neo",sans-serif; letter-spacing:-.8px;}
.option_count_wrap .option_price{position:absolute;right:0px;font-size:12px; color:#ff0000;line-height:18px; top:50%; margin-top:-5px;font-family: tahoma; font-weight:bold;}

.it_total_price{color:#333;font-size:14px; font-family:tahoma;font-weight:bold;}
.it_delivery_price{color:#333;font-size:14px; font-family:tahoma;font-weight:bold;}


#coupon_price { padding:2px 3px; width:70px; text-align:right; letter-spacing:0px; }


.payment_infor{}
.payment_inner{position:relative;overflow:hidden;z-index:20;width:100%; margin-top:10px;border:solid #d5d6df;border-width:2px 0 1px;border-top-color:#4f525c; border-bottom:solid 1px #666;font-size:14px;}
.payment_inner .paymemt_wrap1{float:left;width:750px;overflow:hidden; border-right:solid 1px #DCDCDC}
.paymemt_wrap1 .left_tit{float:left; width:100%;padding:20px;font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;}
.paymemt_wrap1 .right_con{float:left;width:100%;min-height:185px; }
.paymemt_wrap1 .h_txt{display:block;margin-left:-1px; margin-top:30px; margin-bottom:15px;height:24px;font-size:18px;color:#16181a;;}


.payment_wrap{padding:15px 20px;}
#payment_btn { display:inline-block;; width: 276px; height: 64px; font-size:24px; line-height: 64px; margin-left: 8px; background-color:#e61b62;  border:solid 1px #e61b62; color:#FFF; font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;}
#payment_btn:hover { background-color:#FFFFFF; border:solid 1px #e83862; color:#e83862; }



.payment_type_wrap { margin:0px; padding:10px; list-style:none; overflow:hidden; border-top:solid 1px #eee; border-bottom:solid 1px #eee; background-color:#fafafa; }
.payment_type_wrap label { margin:0px; padding:10px; float:left; position:relative; }
.payment_type_wrap label input { vertical-align:-3px; }




.pay_tbl{width:100%;margin-top:0px;border-top:1px solid #e7e7e9;border-bottom:1px solid #dbdee6; margin-bottom:0px;font-size:13px;}
.pay_tbl th{padding:15px;border-bottom:1px solid #e7e7e9;background-color:#f5f5f5;text-align:left;vertical-align:middle}
.pay_tbl td{padding:15px 10px;border-bottom:1px solid #edeff4;color:#333;background-color:#fff;}

.total_pay{display:inline-block;font-family:tahoma;font-weight:bold;font-size:25px;line-height:25px;color:#ff0000;vertical-align:middle}
#payment_card { width:100%; }
.card_menu { margin:0px; padding:0px; list-style:none; overflow:hidden; border-bottom:solid 1px #eee; }
.card_menu li { margin:0px; padding:0px 0px 10px 0px; float:left; width:50%; text-align:center;  }


#payment_vbanking { display:none; }
.card_menu select { width:96%; }

.paymemt_wrap2{float:left;width:30%; margin:10px 0px;}
.paymemt_wrap2 .tbl2{width:100%;}
.paymemt_wrap2 .tbl2 th{padding:15px;text-align:left;vertical-align:middle;}
.paymemt_wrap2 .tbl2 td{padding:15px;color:#333;text-align:right;}
.won{display:inline-block;position:relative;top:7px;margin-left:1px;font-size:14px;font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;color:#ff0000;}
.pay_tbl input[type='radio']{vertical-align:-3px;}
.pay_tbl label{margin-right:10px;}
.pay_tbl select{border:solid 1px #e7e7e9;height:30px;padding-left:5px;vertical-align:-1px;}
.pay_tbl input[type='text']{border:solid 1px #e7e7e9;height:28px;padding-left:5px;}
.pay_tbl2 select{border:solid 1px #e7e7e9;height:30px;padding-left:5px;;}


#payment_hp { display:none; }
#payment_iche { display:none; }
#payment_banking { display:none; }
.memo {display:block;font-family:Malgun Gothic,Dotum,applegothic,sans-serif,arial !important;font-size:11px; color:#767676;line-height:15px; border:solid 1px #e7e7e9;background-color:#FAFAFA; padding:10px;}
.memo2 {display:block;font-size:14px; color:#666;line-height:15px; padding:10px;}
.pay_sel{border:solid 1px #e7e7e9;height:30px;padding-left:5px;width:350px;}


/**개인정보 제3자 제공 및 주의사항 동의 **/
.order_cation_inner{position:relative;padding:20px 19px;border-top:2px solid #4f525c;background-color:#FFFFFF; font-size:12px;}
.order_cation_inner .clause_lst{overflow:hidden;margin-bottom:-1px;background-color:transparent} 
.order_cation_inner .clause_lst li{float:left;width:239px;height:30px;margin-right:-1px;border:1px solid #e7e7e9;background-color:#fdfdfd} 
.order_cation_inner .clause_lst li:first-child{width:239px} 
.order_cation_inner .clause_lst li a{display:block;width:100%;height:20px;padding-top:10px;color:#707070;text-align:center;letter-spacing:-1px} 
.order_cation_inner .clause_lst li.on{position:relative;border-color:#d2d2d7;border-bottom-color:#e9e9eb} 
.order_cation_inner .clause_lst li.on a{background:#f3f3f4;font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;color:#8f8f97} 


.order_cation_inner .clause_ct{overflow:hidden;overflow-y:auto;position:relative;height:135px;padding:25px 18px 0;*padding-right:25px;border:1px solid #e7e7e9;background-color:#fff;font-size:11px;line-height:18px;color:#868687}
.order_cation_inner .clause_chk{display:block;line-height:24px;margin-top:10px; color:#666;}


/******개인정보 제3자 제공 및 주의사항 동의 **/
.order_cation_inner{}
.order_cation_inner input[type=checkbox]{border:solid 1px #DCDCDC; height:15px; width:15px; vertical-align:-5px; margin-right:10px;}
.order_cation_inner .allagree{border-bottom:solid 1px #DCDCDC; height:45px; line-height:45px; margin-bottom:15px; font-size:16px;}
.order_cation_inner .otheragree{padding:10px 20px; font-size:13px;  color:#666;}
.order_cation_inner .popbtn{display:inline-block; width:50px; height:20px; line-height:20px;border:1px solid #d0d0d0;color:#4c4c4c;background-color:#fff; letter-spacing:-1px;font-size:11px;vertical-align:middle;cursor:pointer; text-align:center;}

#display_pay_button { text-align:center; width:100%;margin-top:20px; }


.total_price_wrap { border-top:solid 0px #ccc;  border-bottom:solid 1px #666; background-color:#fff; padding:10px; background-color:#FAFAFA;}
.total_price_wrap .total_price_left{float:left; width:50%;}
.total_price_wrap .total_price_left .total_title{font-size:25px; display:inline-block; margin-top:30px;font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;}
.total_price_wrap .total_price_right{float:right; width:300px; padding:0px 0px;}
.total_price_wrap .total_price_right ul{width:100%;}
.total_price_wrap .total_price_right ul li {border-bottom:solid 1px #DCDCDC;padding:15px 0px;}
.total_price_wrap .total_price_right .tit{width:109px; display:inline-block; font-size:15px;}
#item_total_price { width:150px; display:inline-block;text-align:right;color:#4d5454; font-size:16px; font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal; color:#000;font-size:19px; font-family:tahoma;}
#delivery_total_price { width:150px; display:inline-block;text-align:right;color:#4d5454; font-size:12px; font-family:arial; font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;  color:#000;font-size:19px; font-family:tahoma;}

.last_price{border-top: solid 1px #efefef;padding:25px 0px;; overflow:hidden;}
.last_price .pricetit{float:left;font-size:25px; color:#e61b62; font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;}
.last_price .ct_tot{float:right;  color:#e61b62; font-size:24px; padding:0px 10px 0px 0px }
#cart_total_price { color:#e61b62; font-size:36px;   font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal; font-family:tahoma; }

.tbl td a.od_global_number_link { display:inline-block; width:110px; line-height:34px; height:34px;border:1px solid #959da6;color:#5b6065;background-color:#fff;font-size:12px;vertical-align:-11px;; text-align:center; cursor:pointer; }

.dy_zip_wrap { position:relative; }

.my_address_btn{display:inline-block!important; padding:3px 15px; border:solid 1px #DCDCDC; vertical-align:-10px;}
.new_address_btn{display:inline-block!important; padding:3px 15px; border:solid 1px #DCDCDC; vertical-align:-10px;}
.all_money_btn{display:inline-block!important; padding:3px 15px; border:solid 1px #DCDCDC; vertical-align:-10px;}
#dy_addr1{width:250px;}
</style>
<div class="order" style="padding:0px 0px;">

	<!-- 네비게이션 -->
	<div class="order_top">
		<h3><?=$nfor[title]?></h3> 
		<div class="sub_txt">주문하신 상품의 자세한 옵션과 가격은 아래 상품정보에서 확인하실 수 있습니다.</div>
		<div class="pross">
			<ul>
				<li><b>01</b> <span>장바구니</span></li>
				<li> > </li>
				<li><b class="on">02</b> <span class="on">주문 /결제</span></li>
				<li class="on"> > </li>
				<li><b>03</b> <span>결제완료</span></li>
			</ul>	
		</div>
	</div>
	<!-- //네비게이션 -->

	<form name="fcart_order" id="fcart_order" method="post">
	<input type="hidden" name="mode" value="insert">
	<?=admin_hidden($write,"ss_cart_id_new")?>
	<?=admin_hidden($write,"od_it_id")?>
	<?=admin_hidden($write,"od_it_name")?>
	<?=admin_hidden($write,"od_is_ticket")?>
	<?=admin_hidden($write,"od_is_delivery")?>
	<?=admin_hidden($write,"od_is_global")?>





	<?php if($write[od_is_ticket] or !$member[mb_no]){  ?>
	<!-- 사용자정보 -->
	<div class="order_infor">
		<div class="h_txt">사용자 정보</div>
		<table class="tbl" cellspacing="0" border="0" summary="사용자 정보">
		<colgroup>
			<col width="134">
			<col width="*">
		</colgroup>

		<? if($member[mb_no]){ ?>
		<tr class="frst">
			<th><span class="h_sub_txt">사용자 선택</span></th>
			<td><?=admin_radio($write,"od_user","od_user","","0")?></td>
		</tr>
		<? } else{ ?>

		<tr class="frst">
			<th><span class="h_sub_txt">주문 패스워드</span></th>
			<td><?=admin_text($write,"od_pass","","placeholder=\"주문 패스워드\"")?></td>
		</tr>

		<? } ?>

		<tr>
			<th><span class="h_sub_txt">이름</span></th>
			<td class="od_user_info"><span><?=$write[od_name]?></span><?=admin_text($write,"od_name",$member[mb_no]?"hide":"","placeholder=\"사용자 이름\"")?></td>
		</tr>
		<tr>
			<th><span class="h_sub_txt">휴대전화</span></th>
			<td class="od_user_info"><span><?=$write[od_hp]?></span><?=admin_text($write,"od_hp",$member[mb_no]?"hide":"","placeholder=\"사용자 휴대전화\"")?></td>
		</tr>
		<tr class="last">
			<th><span class="h_sub_txt">이메일</span></th>
			<td class="od_user_info"><span><?=$write[od_email]?></span><?=admin_text($write,"od_email",$member[mb_no]?"hide":"","placeholder=\"사용자 이메일\"")?></td>
		</tr>




		</table>
	</div>
	<!-- //사용자정보 -->
	<?php } ?>




	<? if($write[od_is_delivery]){  ?>

	<?
	if(count($return[myaddress])<1){
		$write[dy_info] = "2";
		$write[my_id] = "";
	}
	?>

	<!-- 배송지정보 -->
	<div class="delivery_infor">
		<div class="h_txt">배송지 입력</div>
			<table class="tbl" cellspacing="0" border="0" summary="배송지정보">
			<colgroup>
				<col width="134">
				<col width="*">
			</colgroup>

			<? if($member[mb_no]){ ?>
			<tr>
				<th class="frst"><span class="h_sub_txt">배송지선택</span></th>
				<td>
				
				<?=admin_hidden($write,"dy_info")?>

				<select name="my_id" class="my_id width150">
				<option value="">배송지를 선택해주세요
				<?php 
				for($i=0; $i<count($return[myaddress]); $i++){ 
					$myaddress = $return[myaddress][$i];
				?>
				<option value="<?=$myaddress[my_id]?>" <?=$write[my_id]==$myaddress[my_id]?"selected":""?> data-my_nick="<?=$myaddress[my_nick]?>" data-my_name="<?=$myaddress[my_name]?>" data-my_hp="<?=$myaddress[my_hp]?>" data-my_zip="<?=$myaddress[my_zip]?>" data-my_addr1="<?=$myaddress[my_addr1]?>" data-my_addr2="<?=$myaddress[my_addr2]?>" data-my_basic="<?=$myaddress[my_basic]?>"><?=$myaddress[my_nick]?>
				<?php } ?>
				</select>

				<a class="my_address_btn">배송지관리</a>
				<a class="new_address_btn">새로운주소</a>

				</td>
			</tr>
			<? } ?>

			<tr>
				<th><span class="h_sub_txt">배송지명</span></th>
				<td class="od_delivery_info"><span class="dy_nick_span <?=count($return[myaddress])<1?"hide":""?>"><?=$return[myaddress][0][my_nick]?></span><?=admin_text($write,"dy_nick",count($return[myaddress])<1?"":"hide","placeholder=\"배송지명\"")?></td>
			</tr>
			<tr>
				<th><span class="h_sub_txt">이름</span></th>
				<td class="od_delivery_info"><span class="dy_name_span <?=count($return[myaddress])<1?"hide":""?>"><?=$return[myaddress][0][my_name]?></span><?=admin_text($write,"dy_name",count($return[myaddress])<1?"":"hide","placeholder=\"받으시는분 이름\"")?></td>
			</tr>
			<tr>
				<th><span class="h_sub_txt">휴대전화</span></th>
				<td class="od_delivery_info"><span class="dy_hp_span <?=count($return[myaddress])<1?"hide":""?>"><?=$return[myaddress][0][my_hp]?></span><?=admin_text($write,"dy_hp",count($return[myaddress])<1?"":"hide","placeholder=\"받으시는분 휴대전화번호\"")?></td>
			</tr>
			<tr>
				<th><span class="h_sub_txt">주소</span></th>
				<td class="od_delivery_info">
					<div class="dy_zip_wrap">
						<span class="dy_zip_span <?=count($return[myaddress])<1?"hide":""?>"><?=$return[myaddress][0][my_zip]?></span><?=admin_text($write,"dy_zip",count($return[myaddress])<1?"":"hide","readonly placeholder=\"우편번호\"")?><a id="zipcode_btn" class="<?=count($return[myaddress])<1?"":"hide"?>">우편번호찾기</a>
					</div>
					<p>
						<span class="dy_addr1_span <?=count($return[myaddress])<1?"hide":""?>"><?=$return[myaddress][0][my_addr1]?></span><?=admin_text($write,"dy_addr1",count($return[myaddress])<1?"":"hide","readonly placeholder=\"주소\"")?>
						<span class="dy_addr2_span <?=count($return[myaddress])<1?"hide":""?>"><?=$return[myaddress][0][my_addr2]?></span><?=admin_text($write,"dy_addr2",count($return[myaddress])<1?"":"hide","placeholder=\"상세주소\"")?>
					</p>
				</td>
			</tr>
			<tr class="last">
				<th><span class="h_sub_txt">배송메시지</span></th>
				<td><?=admin_select($write,"od_dy_msg_type","width150","","0")?> <?=admin_text($write, "od_dy_msg", "hide width150")?></td>
			</tr>
			</table>

			<?php if($write[od_is_global]){ ?>
			<!-- 해외배송상품 추가정보 -->
			<div class="h_txt fotm">해외배송상품 추가정보</div>
			<table class="tbl" cellspacing="0" border="0" summary="해외배송상품 추가정보">
			<colgroup>
				<col width="134">
				<col width="*">
			</colgroup>
			<tr>
				<th rowspan="2"><span class="h_sub_txt">개인통관고유부호</span></th>		
				<td><?=admin_text($write,"od_global_number")?> <a href="https://unipass.customs.go.kr/csp/persIndex.do" target="_blank" class="od_global_number_link">발급하러가기</a></td>
			</tr>
			<tr>
				<td><?=admin_checkbox($write, "chk_global", "", "", "해외배송상품 수입신고를 위해 개인 통관부호 수집 및 판매자 제공에 동의합니다.")?></td>
			</tr>
			</table>
			<!-- //해외배송상품 추가정보 -->
			<?php } ?>
	</div>
	<!-- //배송지정보 -->
	<?php } ?>


	
	<!-- 주문상품 -->
	<div class="order_product">

		<div class="h_txt">주문상품</div>
		<table border="0" class="tbl_cart" cellspacing="0" summary="배송지정보">
		<colgroup>
			<col width="70%">
			<col width="*">
		</colgroup>
		<tr>
			<th><span class="h_sub_txt">주문상품 및 옵션</span></th>
			<th><span class="h_sub_txt">상품금액</span></th>
			<th><span class="h_sub_txt">배송비</span></th>
		</tr>
		<?php
		for($i=0; $i<count($return["list"]); $i++){
			$cart = $return["list"][$i];
		?>
		<tr>
			<td style="padding-bottom:0px;">

				<div class="it_name_wrap">
					<a href="item.php?it_id=<?=$cart[it_id]?>">
						<img src="<?=$cart[it_img]?>" class="cart_item_img">
						<div class="it_name"><span><?=$cart[it_name]?></span></div>
						
						<div class="it_option_wrap" >
						<?php
						for($k=0; $k<count($cart["reply"]); $k++){
							$ct = $cart["reply"][$k];
						?>
						<div class="inner">
							<div class="option_count_wrap">
							<span class="opt_name"><?=$ct[opt_name]?></span>
							<span class="opt_cnt">수량 <?=$ct[ct_opt_cnt]?>개</span>
							<span class="option_price"><?=$ct[ct_sprice2]?>원</span>
							</div>
						</div>
						<?php } ?>
						</div>
					</a>
				</div>

			</td>
			<td><span class="it_total_price"><?=$cart[it_item_price]?></span>원</td>
			<td><span class="it_delivery_price"><?=$cart[it_delivery_price]?></td>
		</tr>
		<?php } ?>
		</table>

	</div>
	<!-- //주문상품 -->



	<!-- 결제예상금액 -->
	<div class="total_price_wrap">
		<div class="total_price_left">
			<span class="total_title">총 주문금액</span>
		</div>
		<div class="total_price_right">
			<ul>
				<li>
					<span class="tit">총 상품금액</span>
					<span id="item_total_price"><?=$return[item_total_price]?></span> <span>원</span>
				</li>
				<li style="border-bottom:none;">
					<span class="tit">총 배송비</span>
					<span id="delivery_total_price"><?=$return[delivery_total_price]?></span> <span>원</span>
				</li>
			</ul>
		</div>
		<div style="clear:both;"></div>

		<div class="last_price">
			<div class="pricetit">결제 예상금액</div>
			<div class="ct_tot"><span id="cart_total_price" class="span_pay_total_price"><?=$return[cart_total_price]?></span> 원</div>
		</div>
	</div>
	<!-- //결제예상금액 -->






	<? if($member[mb_no]){ ?>

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
			alert("다른상품에 이미 선택된 쿠폰입니다");
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
	<!-- 적립금/쿠폰 -->
	<div class="point_infor">
		<div class="h_txt fotm">적립금/쿠폰 사용</div>


			<table class="tbl" cellspacing="0" border="0" summary="적립금/쿠폰정보">
			<colgroup>
				<col width="134">
				<col width="530">
				<col width="134">
			</colgroup>
			<tr>
				<th class="frst"><span class="h_sub_txt">적립금</span></th>
				<td style="vertical-align:top;">
					<span style="font-size:16px; display:block; padding:10px 0px 0px;">사용가능 금액 <b class="mb_money" style="font-size:16px;"><?=$return[mb_money]?></b>원 </span><br>
					<?=$return[memo][money]?>
			
				<div class="money_price_div">
					<div class="money_price_wrap">
						<?=admin_number($write,"money_price")?>
						<span class="money_price_won">원</span>
					</div>
					<a class="all_money_btn">전액사용</a>
				</div>

				</td>

				<th style="border-left:solid 1px #efefef;"><span class="h_sub_txt">할인쿠폰</span></th>
				<td >
					<div class="coupon_wrap">
					<input type="checkbox" name="use_coupon" id="use_coupon" style="vertical-align:-3px;"> <label for="use_coupon">할인쿠폰</label>
					<a id="coupon_use_btn">조회 및 사용</a>
					<div class="coupon_price_wrap"><span id="span_coupon_price" class="span_coupon_price fotm">0</span> 원</div>
					</div>

					<div id="coupon_list_wrap">

					<ul id="coupon_list" class="couponlist">
					<?php
					for($i=0; $i<count($return["list"]); $i++){
						$cart = $return["list"][$i];
					?>
						<li style="padding:10px;">
						<?=$cart[it_name]?><br>
						<input type="hidden" name="cp_it_id[]" value="<?=$cart[it_id]?>">
						<select name="cp_id[]" class="coupon_select" style=" padding-left:10px;">
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
						<a id="coupon_cancel">취소</a>
						<a id="coupon_apply">쿠폰사용하기</a>
					</div>
					</div>
				</td>
			</tr>
			</table>


		</div>
	</div>
	<!-- //적립금/쿠폰 -->
	<? } ?>





	




	<!-- 결제정보입력 -->
	<div class="payment_infor" >
		<div class="h_txt fotm">결제정보입력</div>
		<div class="payment_inner">
			<div class="paymemt_wrap1">
				<div class="left_tit">결제수단 선택</div>
				
				<!-- bankin_info -->
				<div class="right_con">

					<div class="payment_type_wrap"><?=admin_radio($write,"payment_type","payment_type","","0")?></div>

					<!-- 신용카드 -->
					<div id="payment_card" class="payment_wrap">
						<table class="pay_tbl" cellspacing="0" border="0">
						<tr>
							<th><span class="h_sub_txt">카드종류</span></th>
							<td><?=admin_select($write,"card_code","","","0")?></td>
						</tr>
						<tr>
							<th><span class="h_sub_txt">할부기간</span></th>
							<td><?=admin_select($write,"card_quota","","","0")?></td>
						</tr>
						</table>
					</div>
					<!-- //신용카드 -->

					<!-- 가상계좌 -->
					<div id="payment_vbanking" class="payment_wrap">
						<div class="memo"><?=$return[memo][vbanking]?></div>
					</div>
					<!-- //가상계좌 -->

					<!-- 무통장입금 -->
					<div id="payment_banking" class="payment_wrap">
						<table class="pay_tbl" cellspacing="0" border="0">
						<tr>
							<th><span class="h_sub_txt">입금은행</span></th>
							<td><?=admin_select($write,"bank_number","","","0")?></td>
						</tr>
						<tr>
							<th><span class="h_sub_txt">입금자명</span></th>
							<td><?=admin_text($write,"bank_name")?></td>
						</tr>
						</table>
						<div class="memo"><?=$return[memo][banking]?></div>
					</div>
					<!-- //무통장입금 -->
					
					<!-- 휴대폰 -->
					<div id="payment_hp" class="payment_wrap">
						<div class="memo"><?=$return[memo][hp]?></div>
					</div>
					<!-- //휴대폰 -->
					
					<!-- 현금영수증 -->
					<div class="hide" id="cashreceipt_div" style="padding:10px;">
						<table class="pay_tbl" cellpadding="0" cellspacing="0" border="0">
						<div class="memo2">현금영수증</div>
						<tr>
							<th><?=admin_radio($write,"od_cashreceipt_method")?></th>
						</tr>
						<tr>
							<td>
							<div class="od_cashreceipt_method_wrap">
								<?=admin_select($write,"od_cashreceipt_type","","","1")?>
								<?=admin_text($write,"od_cashreceipt_val")?>
							</div>
							</td>
						</tr>
						</table>
					</div>
					<!-- //현금영수증 -->
					
					<!-- 환불계좌 -->
					<div class="hide" id="refund_div" style="padding:10px;">
						<div class="memo2">환불계좌정보</div>
						<table class="pay_tbl" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<th>환불 은행</th>
							<td><?=admin_select($write,"od_refund_bank","","","0")?></td>
						</tr>
						<tr>
							<th>환불 계좌번호</th>
							<td><?=admin_text($write,"od_refund_account")?></td>
						</tr>
						<tr>
							<th>환불 예금주</th>
							<td><?=admin_text($write,"od_refund_name")?></td>
						</tr>
						</table>
					</div>
					<!-- //환불계좌 -->
				</div>
			</div>
			
			<!-- 결제예상금액 -->
			<div class="paymemt_wrap2">
				<table cellpadding="0" border="0" style="width:100%;" class="tbl2">
				<tr>
					<th>총 상품금액</th>
					<td><em><?=$return[item_total_price]?></em><span>원</span></td>
				</tr>
				<tr>
					<th>배송비</th>
					<td><em><?=$return[delivery_total_price]?></em><span>원</span></td>
				</tr>
				<tr>
					<th>적립금사용</th>
					<td><em id="span_money_price" class="span_money_price">0</em><span>원</span></td>
				</tr>
				<tr>
					<th>쿠폰사용</th>
					<td><em id="span_coupon_price" class="span_coupon_price">0</em><span>원</span></td>
				</tr>
				<tr>
					<th>총결제금액</th>
					<td><em id="span_pay_total_price" class="total_pay span_pay_total_price"><?=$return[cart_total_price]?></em><span class="won">원</span></td>
				</tr>
				</table>
			</div>
			<!-- //결제예상금액 -->
			
		</div>
	</div>
	<!-- //결제정보입력 -->


	<!-- 개인정보 제3자 제공 및 주의사항 동의 -->
	<div class="order_cation"  >
		<div class="h_txt fotm">약관동의</div>
		<div class="order_cation_inner">
			<div class="allagree"><?=admin_checkbox($write, "chkall", "", "", "약관 전체 동의하기")?></div>
			<div class="otheragree"><?=admin_checkbox($write,"chk1","chk chk1", "", "개인정보 제3자 제공에 동의")?>  <a class="popbtn agree_popup_btn" data-popup_id="1">상세보기</a> </div>
			<div class="otheragree"><?=admin_checkbox($write,"chk2","chk chk2", "", "결제대행서비스 이용약관에 동의")?> <a class="popbtn agree_popup_btn" data-popup_id="2">상세보기</a> </div>
			<div class="otheragree"><?=admin_checkbox($write,"chk3","chk chk3", "", "구매조건 확인 및 취소·환불 규정에 동의")?> </div>
		</div>
	</div>
	<!-- //개인정보 제3자 제공 및 주의사항 동의 -->


	<div id="display_pay_button">
		<a id="payment_btn">결제하기</a>
	</div>


	</form>


</div>




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
	var discount_total_price = money_price + total_coupon_price;

	$("#span_money_price").html(number_format(money_price));
	$("#span_pay_total_price").html(number_format(pay_total_price));
	$("#span_discount_price").html(number_format(discount_total_price));

}
// 수정된부분

$(document).on("click","#zipcode_btn, #dy_zip, #dy_addr1",function(){
	zipcode("dy_zip","dy_addr1","dy_addr2");
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
			console.log(response);
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

$(document).on("click", ".od_user", function(){
	if($(this).val()=="1"){
		$(".od_user_info span").addClass("hide");
		$(".od_user_info input").removeClass("hide");
	} else{
		$(".od_user_info span").removeClass("hide");
		$(".od_user_info input").addClass("hide");
	}
});

$(document).on("click", ".my_address_btn", function(){
	my_address()
});

$(document).on("click", ".new_address_btn", function(){

	$("#dy_info").val("2");
	$(".my_id").val("");

	$(".od_delivery_info span").addClass("hide");
	$(".od_delivery_info input").removeClass("hide");
	$("#zipcode_btn").removeClass("hide");
});

$(document).on("change", ".my_id", function(){
	var my_id = $(this).val();
	if(my_id){

		$("#dy_info").val("1");


		$(".dy_nick_span").html( $(".my_id option:selected").data("my_nick") );

		$(".dy_name_span").html( $(".my_id option:selected").data("my_name") );
		$(".dy_hp_span").html( $(".my_id option:selected").data("my_hp") );
		$(".dy_zip_span").html( $(".my_id option:selected").data("my_zip") );
		$(".dy_addr1_span").html( $(".my_id option:selected").data("my_addr1") );
		$(".dy_addr2_span").html( $(".my_id option:selected").data("my_addr2") );

		$(".od_delivery_info span").removeClass("hide");
		$(".od_delivery_info input").addClass("hide");
		$("#zipcode_btn").addClass("hide");
	} else{

		$("#dy_info").val("2");

		$(".od_delivery_info span").addClass("hide");
		$(".od_delivery_info input").removeClass("hide");
		$("#zipcode_btn").removeClass("hide");
	}
});
</script>















<script>
$(document).on("click", ".bg", function(){
	$("#divpopwrap_temp").hide();
});
$(document).on("click", ".agree_popup_btn", function(){
	var popup_id = $(this).data("popup_id");
	$(".app_popup").hide();
	$(".app_popup"+popup_id).show();

	$("#divpopwrap_temp").show();
});

$(document).on("click", ".pop_layer .close, .pop_layer .btn", function(){
	$("#divpopwrap_temp").hide();
});
</script>

<style>
.app_popup { display: none; }
.pop_layer { width:400px; font-size:14px;  line-height:18px; display:inline-block; position:relative;  z-index:500; padding:38px 30px; border:1px #c2c7cc solid; background-color:#fff; }
.pop_layer .close { position: absolute; top:38px; right:30px;  width: 15px;   height: 15px; background: url(/skin/nfor/img/ccooll.png) no-repeat; font-size:0; }
.pop_layer h3 { margin-bottom: 27px; font-weight:normal; font-size: 25px; letter-spacing:-2px; }
.pop_layer .cont{line-height: 16px; font-size:12px; color: #6c7580; margin-bottom:20px; height:150px; overflow-y:scroll}
.pop_layer .btn { display:block; height: 47px; border-color: #f27935;background: #f27935; line-height: 47px; color: #fff; text-align:center;}

.divpopwrap{position:fixed;_position:absolute;top:0;left:0;width:100%;height:100%; z-index:99999;}
.divpopwrap .bg{position:absolute;top:0;left:0;width:100%;height:100%;background:#000;opacity:.5;filter:alpha(opacity=50)}
.divpopwrap .fg{position:absolute;top:50%;left:50%;width:460px;height:460px;margin:-230px 0 0 -230px; }
</style>
<div id="divpopwrap_temp" class="divpopwrap" style="display:none;">
	<div class="bg"></div>
	<div class="fg">
	
			
			<div class="pop_layer app_popup app_popup1">
				
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

			<div class="pop_layer app_popup app_popup2">
				
					<h3>결제대행서비스 이용약관 동의</h3>

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








<?php
include_once "pg_".$nfor[pg_type].".php";

include_once $nfor[skin_path]."tail.php";
?>