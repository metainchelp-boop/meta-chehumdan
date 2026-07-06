<?php
include_once $nfor[skin_path]."head.php";
?>

<div class="item">


	<!-- 네비게이션 -->
	<!--
	<!--<div class="subpath">	
		<a href="index.php"><img src="<?=$nfor[skin_path]?>img/homenavbtn.png" alt="홈"> 홈</a> 
		<?
		for($i=0; $i < count($navi[category_id]); $i++){
			echo "<img src=\"".$nfor[skin_path]."img/arrow.png\">";
			if($i){
				$link = "item_list.php";
			} else{
				$link = "item_list_main.php";
			}
			echo " <a href=\"$link?category_id=".$navi[category_id][$i]."\">".$navi[cg_category][$i]."</a> ";
		}
		?>
	</div> -->
	<!-- //네비게이션 -->


    <div class="sub_item_wrap">
        <div class="item_thum">

			<img src="<?=thumbnail("$nfor[path]/data/main/".$item["it_img1"], 430, 430, 0, 1)?>" id="it_img">

			<ul class="it_img_thumb">
			<?
			for($i=1; $i<=5; $i++){
				if($item["it_img".$i]){
			?>
			<li><img src="<?=thumbnail("$nfor[path]/data/main/".$item["it_img".$i], 430, 430, 0, 1)?>" class="it_img_s <?=$i==1?"it_img_s_on":""?>"></li>
			<? 
				}
			} 						
			?>
			</ul>


        </div>
        <div class="item_info">




            <span class="del"><?=delivery_type($item)?></span>

			<div style="text-align:right; font-size:12px; color:#333; font-family: 'Nanum Gothic'; float: right;">상품코드 : <?=$item[it_id]?></div>


            <span class="itdes fotr"><?=$item[it_description]?> <?=item_icon($item)?>	</span>	
            <span class="itname fotm"><?=$item[it_name]?></span>
			<div style="position:relative;">
			 <span class="itdisco"><?=$item[it_discount_rate]?><span>%</span></span>
			 <div style="display:inline-block; margin-left:5px;">
			 <span class="price1"><em><?=number_format($item[it_price1])?></em><span class="won">원</span></span>
			 <span class="price2"><?=number_format($item[it_price2])?><span class="won">원</span></span>
			 </div>
			 
			 
			 <? if($cp[cp_id]){ ?>
			 <span class="itcoupon">


				<? 
				if($cp[cp_pay_type]=="1"){
					$cp_price = $item[it_price2]-$cp[cp_coupon_price];
				?>
				<span class="coupon_price1">쿠폰가 <?=number_format($cp[cp_coupon_price])?>원 할인</span>
				<? 
				} elseif($cp[cp_pay_type]=="2"){ 
					$cp_price = $item[it_price2] - (($item[it_price2]/100)*$cp[cp_coupon_per]);
					$cp_price = substr($cp_price,0,-1)."0";				
				?>
				<span class="coupon_price1">쿠폰가 <?=number_format($cp[cp_coupon_per])?>% 할인</span>
				<? 
				} else{
					$cp_price = "0";
				?>

				<? } ?>
				<span class="coupon_price2"><?=number_format($cp_price)?><span style="font-size:16px; color:#000;font-family:nskr;">원</span></span>
			</span>
			<? } ?>
			</div>
			
			





						<?php // 개별상품할인

						$coupon_name = "";
						$que = sql_query("select * from nfor_coupon where cp_type='1' and cp_sdate<='$nfor[ymdhis]' and cp_edate>='$nfor[ymdhis]' and (cp_all='1' or cp_mb_no='$member[mb_no]') and cp_it_id='$item[it_id]'");
						while($cp1 = sql_fetch_array($que)){
							$chk_cp = sql_fetch("select * from nfor_coupon_use where cu_mb_no='$member[mb_no]' and cu_cp_id='$cp1[cp_id]' and cu_state='1'");
							if(!$chk_cp[cu_id]){
								if($cp1[cp_pay_type]=="1"){ // 할인형태가 원/%
									$discount_price = number_format($cp1[cp_coupon_price])."원";
								} else{
									$discount_price = $cp1[cp_coupon_per]."%";
								}

								$coupon_name = $cp1[cp_name]." ($discount_price 할인)";
				
							}
						}
						
						// 카테고리할인
						if(trim($item[it_category])){
							$add_sql = "";
							$it_category_exp = explode("||",trim($item[it_category]));
							for($k=0; $k<count($it_category_exp)-1; $k++){
								if($k) $add_sql .= " or ";
								$cp_category_id = $it_category_exp[$k];
								$add_sql .= "cp_category_id='$cp_category_id' ";
							}
							if($add_sql) $add_sql = " and ( $add_sql )";
							$sql = "select * from nfor_coupon where cp_type='2' and cp_sdate<='$nfor[ymdhis]' and cp_edate>='$nfor[ymdhis]' and (cp_all='1' or cp_mb_no='$member[mb_no]') $add_sql";
							$que = sql_query($sql);
							while($cp2 = sql_fetch_array($que)){
								$chk_cp = sql_fetch("select * from nfor_coupon_use where cu_mb_no='$member[mb_no]' and cu_cp_id='$cp2[cp_id]' and cu_state='1'");
								if(!$chk_cp[cu_id]){
									if($cp2[cp_pay_type]=="1"){
										$discount_price = number_format($cp2[cp_coupon_price])."원";
									} else{
										$discount_price = $cp2[cp_coupon_per]."%";
									}

									$coupon_name = $cp2[cp_name]." ($discount_price 할인)";
				
								}
							}
						}
						?>






			<?
			if($coupon_name){
			?>
			<div class="coupon" style="display:none">
				<span class="lef">Coupon</span>
				<span class="right"><?=$coupon_name?></span>
			</div> 
			<? } ?>



			<b class="under_line"></b>
            <span class="buy_num"><b><?=number_format(it_sales_volume_new($item))?></b><span class="fotm">개 구매</span></span>
			<?
			include_once $nfor[skin_path]."opt_popup.php";
			?>

			<b class="underline"></b>

			<div class="opt_cal_total <?=$item[it_opt_use]=="1"?"hide":""?>">
			<span style="display:block; font-size:30px; color:#d32f2f; line-height:40px; font-weight:bold;margin-top:10px;margin-bottom:10px;text-align:right;"><span style="font-size:14px; color:#000; line-height:16px;">총구매금액</span> <span class="opt_cal_total_span"><?=number_format($item[it_price2]*$item[it_buy_qty_min])?></span><span style="font-size:14px; color:#000; line-height:16px;">원</span></span>
			</div>


			<div class="opt_cart_order_btn <?=$item[it_opt_use]=="2"?"on":""?>">
				<a class="item_btn btn_zzim <?=$item[zzim_is]?"on":""?>" data-it_id="<?=$item[it_id]?>"><span><?=$item[it_zzim]?></span></a>
				<a class="item_btn item_btn_cart opt_cart_btn"></a>
				<a class="item_btn item_btn_buy opt_order_btn"></a>
			</div>


    </div>
    <div style="clear:both;"></div>
</div>



<!-- 관련상품 -->
<?
if($item[it_relation_use]=="2" or $item[it_relation_use]=="3"){
	if($item[it_relation_use]=="2"){ // 자동
		$que = sql_query("select * from nfor_item where it_view='1' and ((it_shopping='1') or (it_shopping='2' and it_paydate <='$nfor[ymdhis]' and it_payenddate >='$nfor[ymdhis]')) and it_id<>'$item[it_id]' and it_category like '%$category_id%' limit 5");
	} elseif($item[it_relation_use]=="3"){ // 수동
		$que = sql_query("select * from nfor_item_relation a, nfor_item b where a.re_relation_it_id=b.it_id and a.re_it_id='$item[it_id]' and b.it_view='1' and ((b.it_shopping='1') or (b.it_shopping='2' and b.it_paydate <='$nfor[ymdhis]' and b.it_payenddate >='$nfor[ymdhis]')) and b.it_id<>'$item[it_id]' limit 5");	
	} else{

	}
	if(sql_num_rows($que)){
?>
<div class="same_item_wrap">
	<div class="blank_line"></div>
	<h3 class="tit fotr">히트 <em>상품</em></h3>
	<div class="sam_item_list">
		<ul>
			<?
			while($data = sql_fetch_array($que)){
			?>
			<li>
				<a href="item.php?it_id=<?=$data[it_id]?>" class="thmb">
					<img src="<?=$data[it_img]?thumbnail("$nfor[path]/data/list/$data[it_img]",324,324,0,1):"$nfor[path]/img/item_list_noimg.jpg"?>" width="164" height="167">
				</a>
				<div class="detail">
					<a href="item.php?it_id=<?=$data[it_id]?>" class="subject"><?=$data[it_name]?></a>
					<span class="price1"><em><?=number_format($data[it_price1])?></em>원</span>
					<span class="price2"><em><?=number_format($data[it_price2])?></em>원</span>
				</div>
			</li>
			<? } ?>
		</ul>
	</div>
</div>
<?
	}
} 
?>
<!--// 관련상품 -->



<style>

.refund_return_area .tb_type1{width:760px; margin:20px auto;  border-top:solid 1px #e3e3e3;}
.refund_return_area .tb_type1 th{background-color:#f9f9f9;padding:12px 18px 12px 18px;color:#303030;text-align:left; font-size:12px; border-bottom: 1px solid #e3e3e3;}
.refund_return_area .tb_type1 th{padding-left:18px;text-align:left}
.refund_return_area .tb_type1 td{border-bottom:1px solid #e3e3e3; border-left: 1px solid #e3e3e3; color: #656871; font-size:12px; padding:10px 15px 10px 15px;line-height:16px; text-align:left;}
</style>

<!-- item_detail -->
	
<div class="item_detail">

    <ul class="tabmenu">
        <li class="active"><a href="#tab1" class="fotm">상품정보</a></li>
        <li><a href="#tab3" class="fotm">상품문의 <?=$item[it_qna]?"<em class='cer'>".number_format($item[it_qna])."</em> ":""?></a></li>
        <li><a href="#tab4" class="fotm">구매후기<?=$item[it_star]?" <em  class='cer'>".number_format($item[it_star])."</em>":""?></a></li>
		<li><a href="#tab5" class="fotm">환불교환/상품고시</a></li>
    </ul>

    <div id="tab1" class="tab-cont">



		<div class="refund_return_area">

				<table class="tb_type1" border="0" cellspacing="0">
				<colgroup>
				<col width="25%">
				<col width="75%">
				</colgroup>
				<tbody>
				<?
				$it_add_info = json_decode($item[it_add_info], true);
				if($it_add_info[title]){
					foreach ($it_add_info[title] as $key => $row) { 
				?>
				<tr id="<?=$key?>">
				<? if(isset($it_add_info[title][$key][0])){ ?>
					<th><?=$it_add_info[title][$key][0]?></th>
					<td><?=$it_add_info[text][$key][0]?></td>
				<? } ?>
				</tr>
				<?
					}
				}
				?>
				</tbody>
				</table>

		</div>





        <?=$item[it_memo]?>
        <?=$item[it_payment]?>
    </div>
    <div id="tab2" class="tab-cont">
        <?=$item[it_rule]?>
    </div>
    <div id="tab3" class="tab-cont">
    <?php
	include_once $nfor[skin_path]."inc_item_qna.php";
	?>
    </div>
    <div id="tab4" class="tab-cont">
    <?php
	include_once $nfor[skin_path]."inc_item_star.php";
	?>
    </div>
	<div id="tab5" class="tab-cont">
     


		<style>
		 .it_info_val_tbl{border-bottom:solid 1px #CCC; width:760px; margin:20px auto;}
		.it_info_val_tbl th { background-color:#f6f6f6; border-top:solid 1px #ccc; height:30px; font-size:12px; padding-left:10px; text-align:left; color:#666; }
		.it_info_val_tbl td { border-top:solid 1px #ccc; border-left:solid 1px #ccc;  height:30px; font-size:12px; color:#666; padding:5px;  }
		</style>


		<div style="padding:10px;">

			<table class="table it_info_val_tbl" cellpadding="0" cellspacing="0">
				<colgroup>
				<col width="35%">
				<col width="75%">
				</colgroup>
			<?
			$it_info = json_decode($item[it_info], true);
			if($it_info[title]){
				foreach ($it_info[title] as $key => $row) { 
			?>
			<tr id="<?=$key?>">
			<? if(isset($it_info[title][$key][0])){ ?>
				<th><?=$it_info[title][$key][0]?></th>
				<td<? if(!isset($it_info[title][$key][1])) echo " colspan='3'"; ?>><?=$it_info[text][$key][0]?></td>
			<? } ?>
			<? if(isset($it_info[title][$key][1])){ ?>
				<th><?=$it_info[title][$key][1]?></th>
				<td><?=$it_info[text][$key][1]?></td>
			<? } ?>
			</tr>
			<?
				}
			}
			?>
			</table>

		</div>






		<img src="<?=$nfor[skin_path]?>img/van_sam.png">	

    </div>
	<br><br>
</div>
<!-- //item_detail -->
</div>


<div class="aside">
	<div class="lstarea">
		<h3 class="tit fotr"><span>HIT&nbsp;</span><em>상품</em></h3>


		<!-- 관련상품 -->
		<?
		if($item[it_relation_use]=="2" or $item[it_relation_use]=="3"){
			if($item[it_relation_use]=="2"){ // 자동
				$que = sql_query("select * from nfor_item where it_view='1' and ((it_shopping='1') or (it_shopping='2' and it_paydate <='$nfor[ymdhis]' and it_payenddate >='$nfor[ymdhis]')) and it_id<>'$item[it_id]' and it_category like '%$category_id%' limit 5");
			} elseif($item[it_relation_use]=="3"){ // 수동
				$que = sql_query("select * from nfor_item_relation a, nfor_item b where a.re_relation_it_id=b.it_id and a.re_it_id='$item[it_id]' and b.it_view='1' and ((b.it_shopping='1') or (b.it_shopping='2' and b.it_paydate <='$nfor[ymdhis]' and b.it_payenddate >='$nfor[ymdhis]')) and b.it_id<>'$item[it_id]' limit 5");	
			} else{

			}
			if(sql_num_rows($que)){
		?>
		<ul class="aside_lst" id="aSideArea">
			<?
			while($data = sql_fetch_array($que)){
			?>
            <li>
			<a href="item.php?it_id=<?=$data[it_id]?>">      
				<img src="<?=$data[it_img]?thumbnail("$nfor[path]/data/list/$data[it_img]",324,324,0,1):"$nfor[path]/img/item_list_noimg.jpg"?>" width="142" height="145">   
				<span class="dsc"><?=$data[it_name]?></span>       
				<span class="rate"><strong><?=$data[it_discount_rate]?></strong>%</span>   
			</a>  
			</li>
			<? } ?>
		</ul>
		<?
			}
		} 
		?>
		<!--// 관련상품 -->


	</div>
</div>
<div style="clear:both;"></div>


<!-- 공유하기팝업 -->
<div id="sns_popup">
	<div class="sns_wrap">
		<div class="sns_title">
			공유하기
			<a class="sns_close_btn"></a>
		</div>
		<ul class="sns_list">
			<li><a href="javascript:sns_link('kakaotalk');"><i class="btn_kakaotalk"></i>카카오톡</a></li>
			<li><a href="javascript:sns_link('kakaostory')"><i class="btn_kakaostory"></i>카카오스토리</a></li>
			<li><a href="javascript:sns_link('twitter')"><i class="btn_twitter"></i>트위터</a></li>
			<li><a href="javascript:sns_link('facebook')"><i class="btn_facebook"></i>페이스북</a></li>
			<li><a href="javascript:sns_link('naver')"><i class="btn_cafe"></i>카페</a></li>
			<li><a href="javascript:sns_link('sms')"><i class="btn_sms"></i>SMS</a></li>
			<li><a href="javascript:sns_link('naverblog')"><i class="btn_blog"></i>블로그</a></li>
			<li><a href="javascript:sns_link('naverline')"><i class="btn_line"></i>라인</a></li>
		</ul>
	</div>
</div>
<!-- //공유하기팝업 -->


<!-- 찜하기팝업 -->
<div id="zzim_popup">
	<div class="zzim_msg off"><p>찜하기팝업</p></div>
</div>
<!-- //찜하기팝업 -->


<!-- 판매알림팝업 -->
<div id="alarm_popup">
	<div class="alarm_msg off"><p>판매알림팝업</p></div>
</div>
<!-- //판매알림팝업 -->


<!-- 장바구니담기팝업 -->
<div id="cart_popup">
	<div>
		<p>장바구니에 상품이 담겼습니다.</p>
		<a href="cart.php">구매하러가기</a>
	</div>
</div>
<!-- 장바구니담기팝업 -->


<script type="text/javascript">
<!--

var cf_name = "<?="$config[cf_name]"?>";
var it_url = location.href;	
var it_img = "<?="$nfor[url]/data/main/$item[it_img_m1]"?>";
var it_name = "<?=$item[it_name]?>";
var it_description = "<?=$item[it_description]?>";
var it_id = "<?=$item[it_id]?>";
var app_schema = "net.nfor.demo";
var app_name = "nfor";
var kakao_key = "eb2b85038c91eacb325347827375f553";

$(document).on("click", ".tabmenu li", function(){
	$(".tabmenu li").removeClass("active");
	$(this).addClass("active");
	$(".tab-cont").hide();
	$($(this).find("a").attr("href")).show();
	return false;
});

$(document).on("click", ".it_img_s", function(){
	$(".it_img_s").removeClass("it_img_s_on");
	$(this).addClass("it_img_s_on");

	$("#it_img").attr("src", $(this).attr("src"));
});

$(document).ready(function() {
	$(".tabmenu li").click(function(){
		$(".tabmenu li").removeClass("active");
		$(this).addClass("active");
		$(".tab-cont").hide();
		$($(this).find("a").attr("href")).show();
		return false;
	});

});

$(document).on("click", ".btn_sns", function(){
	$("#sns_popup").show();
});

$(document).on("click", ".sns_close_btn", function(){
	$('#sns_popup').hide();
});


$(document).on("click", ".opt_cart_order_btn", function(){
	if(!$(this).hasClass("on")){
		alert("옵션을 선택해주세요");
	}
});

$(document).on("click", ".opt_cart_order_btn.on .opt_cart_btn", function(){
	nfor_cart_order("cart");
});

$(document).on("click", ".opt_cart_order_btn.on .opt_order_btn", function(){
	nfor_cart_order("order");
});

function nfor_cart_order(ty){

	$('#move').val(ty);	
	$.ajax({ 
		type : "post"
		, url : "cart.php"
		, cache : false  
		, data : $("#item_frm").serialize()
		, success : function(response){			
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){

				if(ty=="order"){
					<?
					if($config[cf_buy_with_cart]=="2"){	// 현제상품군만 구매
					?>
					location.href="cart_order.php?it_id="+it_id;
					<? 
					} else{ // 장바구니 상품과 함께구매
					?>
					location.href="cart_order.php";
					<?
					}
					?>
				} else if(ty=="cart"){
					<?
					if($config[cf_cart_in_show]=="2"){ // 이동
					?>
					location.href="cart.php";
					<? 
					} else{ // 레이어팝업
					?>
					$("#cart_cnt").html(json["cart_cnt"]);

					$("#cart_popup").animate({"top":"100px"}, 500 ).show();
					setTimeout(function(){
						$('#cart_popup').animate({"top":"-280px"}, 500 );
					},5000);

					<? 
					}
					?>
				} else{
					
				}

			} else{
				alert(json["msg"]);
			}	
		} 
	});

}
//-->
</script>


<style>
.hide { display:none; }


.sub_item_wrap{ border:1px solid #cfcfcf;  background:#fff;  padding:20px 24px 0px 20px;;}
.sub_item_wrap .item_thum{float:left; position:relative; width:430px; min-height:500px; text-align:center;  }
.sub_item_wrap .item_info{float:right; position:relative; display:block; width:480px; min-height:544px;  }
.sub_item_wrap:after { content:""; display: block; clear: both; }


.item_info .del{ background-color:#ff5100; padding:5px 10px; color:#fff; font-size:11px; font-family:'돋움';letter-spacing:-1px;}
.item_info .itname{display:block;font-size:25px; line-height:30px; letter-spacing:-1px; color:#202327;margin-top:5px;}
.item_info .itdes{display:block;font-size:16px; line-height:16px;color:#x48535f;margin-top:15px;}

.item_info .itdisco{display:inline-block;top:0px;height:auto;background:none;font-family:tahoma;font-weight:bold;font-size:40px;color:#ff5100;}
.item_info .itdisco span { font-size:25px; color:#ff5100;line-height:30px; font-weight:bold;margin-top:0px;}
.item_info .price1{display:block; font-size:14px;font-family:Tahoma;margin-top:25px;text-decoration: line-through;color:#999; margin-bottom:0px; }
.item_info .price1 .won{font-size:14px; color:#999; line-height:16px;font-family:'NSKR'; line-height:16px;top:0px;}
.item_info .price2{display:block;font-size:30px; font-weight:bold;line-height:30px;font-family:Tahoma;color:#333; line-height:30px;}
.item_info .price2 .won{font-size:16px; font-weight:800; color:#000; line-height:16px; font-family:'NSKR'; top:0px;}
.item_info .under_line{height:10px; width:100%; border-bottom:solid 1px #DCDCDC; display:block;}
.item_info .opt_cart_order_btn {margin:10px 0px;}
.item_info .coupon{margin-top:25px;background-color:#999;display:inline-block;padding:3px 3px 3px 10px;}
.item_info .coupon .lef{color:#FFFFFF;font-weight:bold; font-size:11px;letter-spacing:-1px;}
.item_info .coupon .right{display:inline-block;padding:5px 10px;background-color:#FFFFFF;margin-left:10px;font-size:11px;color:#666;}
.item_info .coupon .right em{color:#999;font-weight:bold;}
.item_info .itcoupon{display:inline-block;  margin-left:10px;;height:auto;}
.item_info .itcoupon .coupon_price1{display:block; font-size:14px; font-family: NSKR; color:#999;}
.item_info .itcoupon .coupon_price2{display:block; font-size:24px; font-family:Tahoma; font-weight:bold; color:#ff0000;}

.item_info .buy_num{display:block;margin-top:10px; margin-bottom:10px;}
.item_info .buy_num b{color:#ff6600; font-family:tahoma;}


.item_btn{ background: url('<?=$nfor[skin_path]?>img/btn_deal.png'); cursor: pointer;}

.item_info .btn_zzim{ display:inline-block; position:relative; background-position:-0px -0px; width:83px;height:65px;}
.item_info .btn_zzim span{ display:inline-block; position:absolute;top:45px; left:50%;margin-left:-3px;font-family:Verdana;font-weight:bold;font-size:11px;letter-spacing:-1px; }

.item_info .item_btn_cart{  display:inline-block;  background-position:-83px -0px; width:190px;height:65px;}
.item_info .item_btn_buy{  display:inline-block; background-position:-273px -0px; width:189px;height:65px;}

.item_info .btn_zzim:hover, .btn_zzim.on {  background-position:-0px -65px; width:83px;height:65px;}
.item_info .item_btn_cart:hover{ background-position:-83px -65px; width:190px;height:65px;}
.item_info .item_btn_buy:hover{  background-position:-273px -65px; width:189px;height:65px;}


/*common*/
.staricon{ display:inline-block; position:relative; width:80px; height:15px; background:url('./skin/demo/img/star.png'); background-repeat:no-repeat; background-position: 0px -15px;  vertical-align:middle; }
.staricon em{ position:absolute; left:0px; top:0px; height:15px; background:url('./skin/demo/img/star.png'); background-repeat:no-repeat; background-position:0px 0px;   }
/**/



/* 상품대표이미지 */
#it_img { border:solid 1px #c8c8c8; display:block; width:430px; height:430px; }

/* 상품기타이미지 */
.it_img_thumb { margin:0px; padding:0px; display:block; margin-top:10px; }
.it_img_thumb li{ margin:0px; padding:0px; display:inline; }
.it_img_s { width:50px; height:50px; cursor:pointer; border:solid 2px #eeeeee; }
.it_img_s_on{ border:solid 2px #d32f2f; }

/* 상품대표이미지 및 평점후기 */
.review{overflow:hidden;margin:20px 10px;;}
.review .left{float:left; line-height:25px;}
.review .left .tit{line-height:25px;font-weight:bold;color:#666;font-size:12px; vertical-align:-3px;}

.review .right{float:right;color:#666;}
.review .right em{color:#ff6600; font-family:tahoma; font-size:16px;}
.review .right b{font-weight:bold;line-height:25px;}
.review .right .btn{display:inline-block;border:solid 1px #cfcfcf;padding:3px 5px;font-size:11px;color:#666;border-radius:3px;}

/*많이본상품*/
.same_item_wrap{overflow:hidden;border:1px solid #cfcfcf; border-top:none; background:#fff;padding:10px 18px 0px 30px;}
/* .same_item_wrap .blank_line{margin-top:40px;padding-top:35px;border-top:1px solid #efefef} */
.same_item_wrap .tit{font-size:17px; margin-bottom:20px;}
.same_item_wrap .tit em{font-size:16px;color:#ff3300}

.same_item_wrap .sam_item_list{margin-left:0px;}
.same_item_wrap .sam_item_list ul{overflow:hidden;}
.same_item_wrap .sam_item_list li{float:left; width:164px;height:auto;margin:0 26px 30px 0}
.same_item_wrap .sam_item_list li .thmb img{width:164px;height:167px}
.same_item_wrap .sam_item_list li .detail{margin-top:10px;}
.same_item_wrap .sam_item_list li .detail .subject{display:block;font-weight:normal;font-size:12px;line-height:16px;color:#464646;width:164px;letter-spacing:0;text-overflow:ellipsis; overflow:hidden; white-space:nowrap}

.same_item_wrap .sam_item_list li .detail .price1{text-decoration: line-through;color:#999; font-size:11px;}
.same_item_wrap .sam_item_list li .detail .price1 em{font-family:Verdana;font-weight:bold;}

.same_item_wrap .sam_item_list li .detail .price2{color:#ff3300; font-size:11px;}
.same_item_wrap .sam_item_list li .detail .price2 em{font-family:Verdana;font-weight:bold;}

.cen_banner{margin-top:25px;}

.item_detail { display:block; float:left; width:809px; margin-top:10px; border-top:2px solid #3e4c63;}
.item_detail_left { width:100%; } 

.tabmenu { width:100%;height:49px; border-left:solid 1px #ccc;}
.tabmenu li { float:left; padding:0px 0px; width:201px;height:50px; line-height:50px; color:#888888;  text-align:center; border-top:solid 1px #ccc; border-right:solid 1px #ccc; font-size:15px;  background-color:#efefef; border-bottom:solid 1px #ccc;}
.tabmenu .cer{display:inline-block;background-color:#999; padding:3px 10px;color:#fff;font-size:11px;line-height:12px; border-radius:10px;vertical-align:1px;}
.tabmenu .active {background-color:#FFFFFF; border-bottom:solid 1px #FFFFFF;}
.tabmenu .active a { color:#393939;}

.tab-cont { display:none; width:807px; border-bottom:solid 1px #ccc; border-left:solid 1px #ccc; border-right:solid 1px #ccc; overflow:hidden; background-color:#fff; } 

#tab1 { display:block; text-align:center; }

.aside{float:right;overflow:hidden;width:190px;margin:10px 0 0 0px;border-top:2px solid #3e4c63;}
.aside .lstarea{width:188px;margin-bottom:10px;border:1px solid #cfcfcf;background:#fff}
.aside .lstarea .tit{margin:14px 0px 17px; text-align: center;}
.aside .lstarea .tit span{color:#ff6600;}
/* .aside .aside_lst{margin-left:14px} */
.aside .aside_lst li{margin-bottom:19px; text-align: center;}
.aside .aside_lst a{display:inline-block;position:relative;width:142px;color:#333;letter-spacing:-1px}
.aside .aside_lst .dsc{display:block;margin-top:8px;line-height:16px; font-size:11px; color:#666; -webkit-line-clamp:2; -webkit-box-orient:vertical; word-wrap: break-word; height:32px; overflow:hidden;}
.aside .aside_lst .rate{display:inline-block;position:absolute;top:0;right:0;width:36px;height:25px;font-family:Tahoma;font-size:9px; background-color:#ff6600; color:#fff;text-align:center;line-height:22px;letter-spacing:0}
.aside .aside_lst .rate strong{padding-right:1px;font-size:13px}


.opt_cal_total { margin:5px 0px; font-size:14px; }
.opt_cal_total_span {font-weight:bold;line-height:30px;font-family:Tahoma;color:#333;}


.wrap_item_ico_table {  border-top:solid 1px #f4f4f4; border-bottom:solid 1px #d9d9d9; width:100%; height:50px; padding:10px 0px; }
.wrap_item_ico { display:table; margin:0 auto; }
.wrap_item_ico .item_ico { display:table-cell; text-align:center; width:80px; cursor:pointer; }
.gond_item { height:8px; background-color:#ebebeb; }

.btn_alarm { display:block; margin:0 auto; width:50px; height:26px; background:url(/skin/m_demo/img/item.png) no-repeat center -80px; background-size:26px auto; text-align:center; }
.btn_alarm.on { display:block; margin:0 auto; width:50px; height:26px; background:url(/skin/m_demo/img/item.png) no-repeat center -106px; background-size:26px auto; text-align:center; }
.btn_alarm span { display:block; padding-top:31px; font-size:12px; color:#999; }

.btn_sns { display:block; margin:0 auto; width:50px; height:26px; background:url('/skin/mm_demo/img/item.png') no-repeat center  -130px; background-size:26px auto; text-align:center; }
.btn_sns span { display:block; padding-top:31px; font-size:12px; color:#999; }
.btn_cal { display:block; margin:0 auto; width:50px; height:26px; background:url('/skin/mm_demo/img/item.png') no-repeat center -130px; background-size:26px auto; text-align:center; }
.btn_cal span { display:block; padding-top:31px; font-size:12px; color:#999; }
.wrap_item_info { padding:15px 15px;  position:relative;}
.wrap_item_info .it_name { font-size:17px; color:#333; display:block; width:100%; padding-bottom:5px; }
.wrap_item_info .it_description { font-size:12px; color:#999; display:block; width:100%;padding-bottom:5px;  }
.wrap_item_info .card_info { box-sizing:border-box; -webkit-box-sizing:border-box; height:25px; line-height:25px; font-size:12px; color:#ff7f00; letter-spacing:-1px; display:block; width:100%; position:relative; padding-left:20px; }
.wrap_item_info .delivery_type { box-sizing:border-box; -webkit-box-sizing:border-box; height:25px; line-height:25px; font-size:12px; color:#00aad0; letter-spacing:-1px; display:block; width:100%; position:relative; padding-left:20px; }

.it_price_wrap { width:100%; padding:10px 0px 8px 0px; box-sizing:border-box; -webkit-box-sizing:border-box; overflow:hidden; }
.it_price_wrap .it_discount_rate { float:left; display:block; height:24px; font-size:24px; line-height:24px; color:#ec3940; font-weight:normal; letter-spacing:-1px; margin-right:5px; }
.it_price_wrap .it_discount_rate b { font-size:17px; font-weight:normal;}
.it_price_wrap .it_price { float:left;  }
.it_price_wrap .it_price1 { font-size:12px; letter-spacing:-0.5px; color:#999; text-decoration:line-through;margin-right:4px; }
.it_price_wrap .it_price1 b { font-weight:normal; } 
.it_price_wrap .it_price2 { font-size:20px; color:#111; font-weight:bold; letter-spacing:-1px;margin-right:4px; }
.it_price_wrap .it_price2 b { font-weight:normal; font-size:14px; vertical-align:middle; }
.it_price_wrap:after { content:""; display:block; clear:both; }
.sales_volume { position:absolute; right:0px; bottom:5px; font-size:12px;float:left; padding:0px 20px 15px;color:#999; }

.gond_item2 { border-top:solid 1px #d9d9d9; background:#f4f4f4; width:100%; height:10px; }
.gond_item3 { background:#ebebeb; height:8px; }

.other_item_list { padding:15px; border-top:solid 1px #f8f8f8; border-bottom:solid 1px #d9d9d9; }
.other_item_title { color:#555; font-size:14px; margin-bottom:10px;letter-spacing:-1px; }
.other_item_name { font-size:13px; line-height:18px;padding-top:5px; padding-bottom:2px; color:#444; letter-spacing:-1px; overflow:hidden; text-overflow:ellipsis; -webkit-line-clamp:1; display:-webkit-box; -webkit-box-orient:vertical; }
.other_item_price2 { font-size:15px; color:#ec1d28;  font-weight:normal;letter-spacing:-0.05em; }
.other_item_price1 { font-size:14px; font-weight:normal; }
.other_item_img { width:100%; border:solid 1px #F8F8F8; }

.item_wrap { width:100%; background-color:#fff; }
.it_img_wrap { width:100%; overflow:hidden; }
.it_img_wrap .it_img { width:100%; }

.card_info b { background:url("/skin/mm_demo/img/layout.png") no-repeat; background-size:320px auto; background-position: -0px -200px;  position:absolute; left:0px; top:-1px; width:25px; height:25px; }
.delivery_type b { background:url("/skin/mm_demo/img/layout.png") no-repeat; background-size:320px auto;  background-position: -50px -200px; position:absolute; left:0px; top:-1px; width:25px; height:25px; }


/* 장바구니담기 팝업 */
#cart_popup { position:fixed; left:0px; top:-280px; width:100%; z-index:99999; display:none; } 
#cart_popup div { background-color:rgba(0,0,0,0.8); margin:0 auto; width:300px; text-align:center; padding:30px 0px; font-family:NSKL;}
#cart_popup p { margin-bottom:10px; display:block; font-size:16px; color:#fff; font-weight:bold; }
#cart_popup a { background-color:#22b300; height:30px; line-height:30px; display:block; width:150px; color:#fff; margin:0 auto; font-family:NSKL; }


/* 찜하기 팝업 */
#zzim_popup { display:none; z-index:9999; }
#zzim_popup .zzim_msg { position:fixed; left:50%; top:50%; width:150px; height:150px; margin-top:-95px; margin-left:-75px; z-index:9999; }
#zzim_popup .zzim_msg.on p {  background:url('/skin/mm_demo/img/zzim_on.png') no-repeat 50% 50%; background-size:150px auto; width:150px; height:150px; position:relative; overflow:hidden; text-indent:-999px; animation-name:zzim-animate; animation-duration:.5s; animation-fill-mode:both; } 
#zzim_popup .zzim_msg.off p {  background:url('/skin/mm_demo/img/zzim_off.png') no-repeat 50% 50%; background-size:150px auto; width:150px; height:150px; position:relative; overflow:hidden; text-indent:-999px; animation-name:zzim-animate; animation-duration:.5s; animation-fill-mode:both; } 
@-webkit-keyframes zzim-animate {
	from {-webkit-transform: scale3d(1, 1, 1);transform: scale3d(1, 1, 1);}
	50% {-webkit-transform: scale3d(1.05, 1.05, 1.05);transform: scale3d(1.05, 1.05, 1.05);}
	to {-webkit-transform: scale3d(1, 1, 1);transform: scale3d(1, 1, 1);}
}


/* 판매알림 팝업 */
#alarm_popup { display:none; z-index:9999; }
#alarm_popup .alarm_msg { position:fixed; left:50%; top:50%; width:150px; height:150px; margin-top:-95px; margin-left:-75px; z-index:9999; }
#alarm_popup .alarm_msg.on p {  background:url('/skin/mm_demo/img/alarm_on.png') no-repeat 50% 50%; background-size:150px auto; width:150px; height:150px; position:relative; overflow:hidden; text-indent:-999px; animation-name:alarm-animate; animation-duration:.5s; animation-fill-mode:both; } 
#alarm_popup .alarm_msg.off p {  background:url('/skin/mm_demo/img/alarm_off.png') no-repeat 50% 50%; background-size:150px auto; width:150px; height:150px; position:relative; overflow:hidden; text-indent:-999px; animation-name:alarm-animate; animation-duration:.5s; animation-fill-mode:both; } 
@-webkit-keyframes alarm-animate {
	from {-webkit-transform: scale3d(1, 1, 1);transform: scale3d(1, 1, 1);}
	50% {-webkit-transform: scale3d(1.05, 1.05, 1.05);transform: scale3d(1.05, 1.05, 1.05);}
	to {-webkit-transform: scale3d(1, 1, 1);transform: scale3d(1, 1, 1);}
}

/* 공유하기 팝업 */
#sns_popup { display:none; position:fixed; left:0px; top:0px; width:100%; height:1000px; background-color:rgba(0,0,0,0.5); z-index:9999; }
#sns_popup .sns_wrap { position:absolute; left:50%; margin-top:150px; width:290px; margin-left:-145px; background-color:#fff; }
#sns_popup .sns_title { position:relative; padding:18px 0 17px 0px; color:#333; font-size:16px; text-align:center; }
#sns_popup .sns_close_btn { cursor:pointer; position:absolute; width:14px; height:14px; right:20px; top:22px; background:url(/skin/mm_demo/img/layout.png) no-repeat; background-position: -100px -700px; background-size:320px auto; }
#sns_popup .sns_list { display:block; overflow:hidden; margin-bottom:20px; }
#sns_popup .sns_list li { float:left; width:25%; margin-bottom:10px; text-align:center; font-size:12px; color:#111; }
#sns_popup i { display:block; background:url(/skin/mm_demo/img/layout.png) no-repeat 0 0; background-size: 320px auto; width:46px; height:50px; margin:0 auto; }
#sns_popup .btn_kakaotalk {  background-position: -0px -650px;}
#sns_popup .btn_kakaostory { background-position: -50px -650px;}
#sns_popup .btn_twitter { background-position: -100px -650px;}
#sns_popup .btn_facebook { background-position: -150px -650px;}
#sns_popup .btn_cafe { background-position: -200px -650px;}
#sns_popup .btn_sms { background-position: -250px -650px;}
#sns_popup .btn_blog { background-position: -0px -700px;}
#sns_popup .btn_line { background-position: -50px -700px;}
</style>


<?php
include_once $nfor[skin_path]."tail.php";
?>