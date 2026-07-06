<?php
include_once $nfor[skin_path]."head.php";
include_once "inc_opt_popup_js.php"; // 옵션 자바스크립트
?>

<style>
.hide { display:none; }


.sub_item_wrap{ background:#fff;  padding:0px; margin-bottom:50px;} 
.sub_item_wrap .item_thum{float:left; position:relative; text-align:center;  }
.sub_item_wrap .item_thum .thum{width:550px ; height:550px;}
.sub_item_wrap .item_thum .thum img{width:100%;}
/* 상품대표이미지 */
#it_img {display:block; width:550px; height:550px; }
#it_img img{width:100%;}

/* 상품기타이미지 */
.it_img_thumb { margin:0px; padding:0px; display:block; margin-top:10px; }
.it_img_thumb li{ margin:0px; padding:0px; display:inline; }
.it_img_s { width:80px; height:80px; cursor:pointer; border:solid 2px #eeeeee; }
.it_img_s_on{ border:solid 2px #d32f2f; }
.sub_item_wrap .item_info{float:right; position:relative; display:block; width:580px; min-height:544px;  }
.sub_item_wrap .item_info:after { content:""; display: block; clear: both; }
.sub_item_wrap .item_info .ct_wrp{min-height: 22px;padding-top: 0; padding-bottom: 15px; padding-left: 0;border-bottom: 1px solid #e3e5e8; position: relative;}
.sub_item_wrap .item_info .ct_wrp .sns_gong{ position: absolute; top: 5px; right:0; font-size:13px;}
.sub_item_wrap .item_info .ct_wrp .sns_gong i{display: inline-block; width: 15px; height: 18px; margin: 0px 4px 0 0; background: url(skin/demo/img/sns_gong.png) no-repeat ; vertical-align: top;}





.nfor_layer_popup_wrap2 { position:absolute; top:35px; right:0px; width:206px; height:150px; z-index:1000; border:solid 1px #ccc; background-color:#fff; z-index:99999; }
.nfor_layer_popup_wrap2 .lay_tit { height: 38px; padding: 0 15px; border-bottom: 1px solid #dadada; font-size: 14px; font-weight:normal;  color: #333;  line-height: 34px;}
.nfor_layer_popup_wrap2 .lay_conts{padding: 15px 0 10px; font-size: 14px; line-height: 1.6; color: #666;}
.nfor_layer_popup_wrap2 .lay_conts .sns_type{overflow:hidden;padding: 0 0 15px 0px; }
.nfor_layer_popup_wrap2 .lay_conts .sns_type li { float:left; margin-left:15px; }
.nfor_layer_popup_wrap2 .lay_conts .sns_type .facebook{ display: block;width: 33px; height: 32px; background: url('skin/demo/img/sns_face.png') no-repeat;}
.nfor_layer_popup_wrap2 .lay_conts .sns_type .twitter{ display: block;width: 33px; height: 32px; background: url('skin/demo/img/sns_twee.png') no-repeat;}
.nfor_layer_popup_wrap2 .lay_conts .sns_type .kakao{ display: block;width: 33px; height: 32px; background: url('skin/demo/img/sns_kaka.png') no-repeat;}
.nfor_layer_popup_wrap2 .lay_conts .sns_type .naver{ display: block;width: 33px; height: 32px; background: url('skin/demo/img/sns_naver.png') no-repeat;}

.nfor_layer_popup_wrap2 .sns_url { padding: 10px 15px 0;border-top: 1px solid #dadada;}
.nfor_layer_popup_wrap2 .sns_url input[type='text'] {  width:124px; padding: 3px 4px 0;  padding-top: 5px \0/IE8;  height:25px; padding-bottom: 2px \0/IE8; color: #666;  font-size: 12px;  border: 1px solid #dadada;  border-right: 0; line-height: 25px;   vertical-align: middle;}
.nfor_layer_popup_wrap2 .sns_url  a.copy_btn {display: inline-block; width: 41px; height: 23px; border: 1px solid #555;  background-color: #555;  font-size: 12px; line-height: 20px; color: #fff;  text-align: center; vertical-align: middle;}
#popup_close_btn2 { position:absolute;right: 4px; top: 5px;  width: 24px;  height: 24px; border:none; cursor:pointer;  background: url('skin/demo/img/close_btn_sns.png') no-repeat;} 





.sub_item_wrap .item_info .ct_wrp span.del{border: solid 1px #a54211; color: #a54211;display: inline-block;-webkit-box-sizing: border-box;-moz-box-sizing: border-box; box-sizing: border-box;height: 22px; padding: 0 6px; background-color: #fff; font-size: 11px; font-family: "돋움",dotum,"Apple SD Gothic Neo",sans-serif; line-height: 22px;letter-spacing: -.3px;}
.sub_item_wrap .item_info .ct_wrp span.card_benefit{color:#8d9ebd; border:1px solid #8d9ebd;}
.sub_item_wrap:after { content:""; display: block; clear: both; }
.sub_item_wrap .pro_title{padding: 24px 0 12px; border-bottom: 0;}
.sub_item_wrap .pro_title .itdes {display: block; padding: 0 0 8px 0;  font-size: 16px; line-height: 1.5;color: #7d7e80;}
.sub_item_wrap .pro_title .itname {font-weight: 400; font-size: 30px; line-height: 1.27;   letter-spacing: -.6px;  word-wrap: break-word; word-break: keep-all;font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;}
.sub_item_wrap .p_info {position: relative;padding: 14px 0 14px 120px;  border-bottom: 1px solid #f0f2f5;}
.sub_item_wrap .p_info:after { display: block; clear: both; content: '';}
.sub_item_wrap .p_info .tit_align_left { position: absolute; top: 14px; left: 0;line-height: 26px;font-weight: 400; font-size: 14px; color: #16181a;}
.sub_item_wrap .p_info .ct {position: relative; font-size: 14px;line-height: 26px;color: #7d7e80;}
.sub_item_wrap .p_info .ct .price1 {display: inline-block; font-size: 16px; vertical-align: middle;color: #a6a9ad; text-decoration: line-through;}
.sub_item_wrap .p_info .ct .itdisco{display: inline-block;margin-left: 10px; font-weight: 600;  font-size: 32px;   color: #e83862; vertical-align: top;font-family: Tahoma,"돋움",Dotum,sans-serif;}
.sub_item_wrap .p_info .ct .itdisco span{ font-size: 20px; }
.sub_item_wrap .p_info .ct .price2 {display: inline-block; margin-left: 0px; font-weight: 600;  font-size: 32px;  color: #16181a; vertical-align: top;font-family: Tahoma,"돋움",Dotum,sans-serif;}
.sub_item_wrap .p_info .ct .del{font-size:16px;}
.sub_item_wrap .p_info .ct .price2 span{font-family:'NanumGothicBold'; vertical-align:8px;font-size: 20px; color: #16181a;}
.sub_item_wrap .p_info .ct .coupon{ font-size:16px;}
.sub_item_wrap .p_info .ct .coupon .num{font-family:'NanumGothicBold'; color:#ff0000;}
.sub_item_wrap .total_price{margin-top:10px;}
.sub_item_wrap .total_price:after{content:""; display: block; clear: both; }
.sub_item_wrap .total_price .tit{float:left; margin-top:10px;}
.sub_item_wrap .total_price .t_price{float:right}
.sub_item_wrap .total_price .t_price .opt_cal_total_span{display: inline-block; position: relative; margin-right: 1px;font-size: 28px; font-weight:bold; font-family: Tahoma,"돋움",Dotum,sans-serif;}

.item_info .opt_cart_order_btn{ overflow:hidden; }
.item_info .opt_cart_order_btn li{float:left; padding-left:6px; position:relative; }
.item_info .item_btn{display: inline-block;-webkit-box-sizing: border-box;-moz-box-sizing: border-box; box-sizing: border-box; height: 34px; padding: 0 16px; background-color: #e83862; font-size: 14px;  line-height: 33px;  color: #fff; text-align: center; -ms-box-sizing: border-box;-o-box-sizing: border-box; height: 64px; font-size: 18px; line-height: 64px; font-family:'NanumGothicBold'; cursor: pointer}
.item_info .item_btn_cart {width:236px; border: 1px solid #e83862;background-color: #fff;color: #e83862; } 
.item_info .item_btn_cart:hover{color:#e83862;}
.item_info .item_btn_buy{width:236px;  border: 1px solid #e83862;}
.item_info .item_btn_buy:hover{color:#FFF;}
.item_info .btn_zzim{position:relative; display:block; width:90px; border:1px solid #e3e5e8; background-color:#eee; color:#666; background: url('<?=$nfor[skin_path]?>img/btn_deal.png?time=<?=time()?>')center; background-position:-0px -0px;}
.item_info .btn_zzim:hover, .btn_zzim.on {  background-position:-0px -65px; width:90px;}
.item_info .btn_zzim .txt{position:absolute; display:block; bottom:5px; font-size:11px; left:50%; line-height:12px; height:12px;}


.button_wrap { overflow:hidden; margin-top:30px; }
.button_wrap .btn_zzim { float:left; }
.button_wrap .opt_cart_order_btn { float:left; }





/*많이본상품*/
.same_item_wrap{overflow:hidden; border-top:none; background:#fff;padding:0px 0px 0px 0px; margin-top:60px; margin-bottom:60px;}
/* .same_item_wrap .blank_line{margin-top:40px;padding-top:35px;border-top:1px solid #efefef} */
.same_item_wrap .tit{font-size:18px; margin-bottom:20px; font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;}
.same_item_wrap .tit em{font-size:18px;color:#ff3300}

.same_item_wrap .sam_item_list{margin-left:0px;}
.same_item_wrap .sam_item_list ul{overflow:hidden; margin-left: -35px;}
.same_item_wrap .sam_item_list li{float:left; padding-left:35px; height:auto;margin:0 0px 25px 0}
.same_item_wrap .sam_item_list li .thmb img{width:167px;height:167px; border: 1px solid #f0f2f5;}
.same_item_wrap .sam_item_list li .detail{margin-top:10px;}
.same_item_wrap .sam_item_list li .detail .subject{display:block;font-weight:normal;font-size:12px;line-height:16px;color:#464646;width:164px;letter-spacing:0;text-overflow:ellipsis; overflow:hidden; white-space:nowrap}

.same_item_wrap .sam_item_list li .detail .price1{text-decoration: line-through;color:#999; font-size:11px;}
.same_item_wrap .sam_item_list li .detail .price1 em{font-family:Verdana;font-weight:bold;}

.same_item_wrap .sam_item_list li .detail .price2{color:#ff3300; font-size:11px;}
.same_item_wrap .sam_item_list li .detail .price2 em{font-family:Verdana;font-weight:bold;}

</style>





<div class="item">
	<div class="subpath">	
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
	</div>
	<div class="sub_item_wrap">
		<div class="item_thum">
			<div class="thum">
			<img src="<?=$item["it_img1"]?>" id="it_img">
			</div>
			<ul class="it_img_thumb">
			<?
			for($i=1; $i<=5; $i++){
				if($item["it_img".$i]){
			?>
			<li><img src="<?=$item["it_img".$i]?>" class="it_img_s <?=$i==1?"it_img_s_on":""?>"></li>
			<? 
				}
				} 
			?>
			</ul>
		</div>
		<div class="item_info">



			<div class="ct_wrp">
				<span class="del"><?=delivery_type($item)?></span>
				<?			
					$cb_year = date("Y");
					$cb_month = date("n");
					$data = sql_fetch("select * from nfor_card_benefit where cb_year='$cb_year' and cb_month='$cb_month'");
					if($data[cb_year]){
				?>
				<span class="del card_benefit">무이자 할부</span>
				<?
					}
				?>


				<script type="text/javascript">
				<!--
				$(document).on("click", ".card_benefit", function(){
					$('#nfor_layer_popup_wrap').show();
					$('#nfor_popup_content').animate( { scrollTop : 0 }, 0 );
				});
				//-->
				</script>

				<!-- 무이자팝업 -->
				<style>
				.nfor_layer_popup_wrap{ position:absolute; top:35px; left:0; width:580px; height:350px; z-index:1000; border:solid 1px #ccc; background-color:#fff; z-index:99999;  }
				.nfor_layer_popup_wrap .fg{padding:10px 20px; }
				.nfor_layer_popup_wrap .card_tit{position:relative; padding:20px 20px 20px; border-bottom:solid 1px #DCDCDC; background-color:#f8f8f8;font-size:13px; font-family:'NanumGothicBold'; }
				#popup_close_btn { position:absolute; top:20px; right:20px; text-align:right; cursor:pointer; } 
				#nfor_popup_content { overflow-y:auto; height:280px; }
				</style>
				<div id="nfor_layer_popup_wrap" class="nfor_layer_popup_wrap" style="display:none;">
					<div class="card_tit">카드 혜택안내
					<div onclick="$('#nfor_layer_popup_wrap').hide()" id="popup_close_btn"><img src="<?=$nfor[skin_path]?>img/closeicon.gif"></div>
					</div>
					<div class="fg">
						<div id="nfor_popup_content"><?=$data[cb_memo]?></div>
					</div>
					
				</div>
				<!-- //무이자팝업 -->
				<div class="sns_gong"><i></i> 공유하기</div>
				<!-- 공유하기 팝업 -->
				<script type="text/javascript">
				<!--
				$(document).on("click", ".sns_gong", function(){
					$('#nfor_layer_popup_wrap2').show();
					$('#nfor_popup_content2').animate( { scrollTop : 0 }, 0 );
				});
				//-->
				</script>
				<div id="nfor_layer_popup_wrap2" class="nfor_layer_popup_wrap2" style="display:none;">
					<h3 class="lay_tit">공유하기</h3>
					<div class="lay_conts">
						<ul class="sns_type">
							<li><a href="javascript:sns_link('naver')" class="naver" title="네이버 공유" ><span class="hide">naver</span></a></li>
							<li><a href="javascript:sns_link('facebook')" class="facebook" title="페이스북 공유" ><span class="hide">facebook</span></a></li>
							<li><a href="javascript:sns_link('twitter')" class="twitter" title="트위터에 보내기"><span class="hide">twitter</span></a></li>
							<li><a href="javascript:sns_link('kakaostory')" class="kakao" title="카카오스토리 공유"><span class="hide">카카오 스토리</span></a></li>
						</ul>
						<div class="sns_url">
							<input type="text" id="copy_msg" value="<?=$nfor[url]."/item.php?it_id=".$item[it_id]?>" class="text" title="URL 복사" readonly="" style="box-sizing:border-box; -webkit-box-sizing:border-box;"><a data-clipboard-target="#copy_msg" class="copy_btn">복사</a>
						</div>
						<div onclick="$('#nfor_layer_popup_wrap2').hide()" id="popup_close_btn2"><span class="hide">레이어 닫기</span></div>
					</div>					
				</div>
				<script>
				new ClipboardJS('.copy_btn');
				</script>
				<!-- //공유하기 팝업 -->
			</div>
			<div class="pro_title">
				<span class="itdes"><?=$item[it_description]?> <?=item_icon($item)?>	</span>
			 <span class="itname"><?=$item[it_name]?></span>
			</div>
			<div class="p_info">
				<h3 class="tit_align_left">판매가격</h3>
				<div class="ct">					
					<p class="price1"><?=number_format($item[it_price1])?>원</p><br>
					<p class="price2"><?=number_format($item[it_price2])?><span class="won">원</span></p>
					<p class="itdisco"><?=$item[it_discount_rate]?><span>%</span></p>
				</div>
			</div>
			<div class="p_info">
				<h3 class="tit_align_left">배송정보</h3>
				<div class="ct">					
				<p class="del"><?=delivery_type($item)?></p>
				</div>
			</div>
			
			<div class="p_info">
				<h3 class="tit_align_left">기타정보</h3>
				<div class="ct">	
				<p class="del"><span ><?=number_format(it_sales_volume_new($item))?>개 구매</span></p>
				</div>
			</div>




			<div  >
			<? if($item[it_shopping]=="2"){ // 기간판매상품이면 ?>
				<div class="box" style="border:solid 1px #efefef; margin-top:24px; height:55px; line-height:55px; text-align:center; background-color:#FAFAFA; font-family:nskr" >
				<?=$item[it_countdown_html]?>
				</div>
				<script>
				$(function () {
					var austDay = new Date(<?=strtotime($item[it_payenddate])*1000?>);
					$("#defaultCountdown").countdown({until: austDay, layout:'<? if($item[it_countdown_d]>0){ ?>{dn}일<? } else{ ?> {hnn} : {mnn} : {snn}<? } ?>'});
				});
				</script>
			<? } ?>
			</div>





			<div style="margin:20px 0px;">
				<?php				
				include "opt_popup.php";
				?>
			</div>
			<div class="opt_cal_total">
				<div class="total_price">
					<div class="tit">총구매금액</div> 
					<div class="t_price"><span class="opt_cal_total_span"><?=$opt_cal_total_span?></span><span>원</span></div>
				</div>
			</div>			
			<div class="button_wrap">
				<a class="item_btn btn_zzim <?=$item[it_zzim_is]?"on":""?>" data-it_id="<?=$item[it_id]?>"><span class="txt"><?=$item[it_zzim]?></span></a>
				<div class="opt_cart_order_btn <?=$item[it_opt_use]=="2"?"on":""?>">
					<ul>
						<li><a class="item_btn item_btn_cart opt_cart_btn">장바구니</a></li>
						<li><a class="item_btn item_btn_buy opt_order_btn">구매하기</a></li>
					</ul>
				</div>
			</div>






			<?php
			if($REMOTE_ADDR	== "119.196.166.38" and $config[cf_naverpay_use]=="1"){
				include_once "naverpay_button.php";
			}
			?>






		</div>
	</div>
</div>
<!-- 관련상품 -->
<?
if(count($return["item"][it_etc_item])>0){

	$relation_max_page = ceil(count($return["item"][it_etc_item])/6);

?>
<div class="same_item_wrap">
	<div class="blank_line"></div>


	<div style="position:relative;">
		<h3 class="tit">관련 <em>상품</em></h3> 
		<div class="relation_page_wrap"> <span id="relation_page">1</span>/<?=$relation_max_page?> <a class="relation_left"><img src="<?=$nfor[skin_path]?>img/pprree.png"></a><a class="relation_right"><img src="<?=$nfor[skin_path]?>img/nneexxtt.png"></a></div>
	</div>


	<div class="sam_item_list">
		<ul>
			<?
			$k = 0;
			for($i=0; $i<count($return["item"][it_etc_item]); $i++){
				$it_etc_item = $return["item"][it_etc_item][$i];
				if($i%6==0) $k++;
			?>
			<li class="nfor_line nfor_line<?=$k?>">
				<a href="item.php?it_id=<?=$it_etc_item[it_id]?>" class="thmb">
					<img src="<?=$it_etc_item[it_img]?>" width="164" height="167">
				</a>
				<div class="detail">
					<a href="item.php?it_id=<?=$it_etc_item[it_id]?>" class="subject"><?=$it_etc_item[it_name]?></a>
					<span class="price1"><em><?=$it_etc_item[it_price1]?></em>원</span>
					<span class="price2"><em><?=$it_etc_item[it_price2]?></em>원</span>
				</div>
			</li>
			<? } ?>
		</ul>
	</div>
</div>



<style>
.relation_page_wrap { position:absolute; right:0; top:0; font-size:13px; color:#666; } 

.relation_left { vertical-align:-6px; }
.relation_right { vertical-align:-6px; }

.sam_item_list ul li { display:none; }
.sam_item_list ul li.nfor_line1 { display:block; }
</style>


<script>
var relation_page = 1;
var relation_max_page = <?=$relation_max_page?$relation_max_page:1?>;

$(document).on("click", ".relation_left", function(){
	relation_page = relation_page - 1;
	if(relation_page < 1){
		relation_page = 1;
	}
	$(".nfor_line").hide();
	$(".nfor_line"+relation_page).show();
	$("#relation_page").html(relation_page);
});
$(document).on("click", ".relation_right", function(){
	relation_page = relation_page + 1;
	if(relation_page > relation_max_page){
		relation_page = relation_max_page;
	}
	$(".nfor_line").hide();
	$(".nfor_line"+relation_page).show();
	$("#relation_page").html(relation_page);
});
</script>


<?
} 
?>

<!--// 관련상품 -->
<style>
.tab-wrap {position: relative;z-index: 200; width: 100%;height: 58px;}
.tab-wrap .tab-inner { position: absolute; top: 0; left: 0;  width: 100%; border-top: 1px solid #e3e5e8; border-bottom: 2px solid #30343b;border-left:solid 1px #e3e5e8;border-right:solid 1px #e3e5e8; background-color: #fff;}
.tab-wrap .tab-inner .tabmenu { width: 1200px;margin: -1px auto 0;}
.tab-wrap .tab-inner .tabmenu li { float: left; position: relative; width: 196px; text-align: center;}
.tab-wrap .tab-inner .tabmenu li a { cursor:pointer; font-family:'NanumGothicBold'; display: block; width: 100%; font-weight: 600; font-size: 16px;  line-height: 56px; color: #7d7e80;}
.fixed {top: 0px; position: fixed!important; z-index: 300!important }
.tab-wrap .tab-inner .tabmenu li.active a { background-color: #30343b; color: #fff;}
</style>

<div class="nfor_line"></div>


<div class="tab-wrap">
	<div class="tab-inner" > <!-- fixed 상단으로 올라갔을때 클래스명 -->
		<ul class="tabmenu">
			<li class="active"><a data-tab="#tab1">상품설명</a></li>
			<li><a data-tab="#tab2">구매정보</a></li>
			<li><a data-tab="#tab3">구매후기<?=$item[it_star_cnt]?" <em  class='cer'>".number_format($item[it_star_cnt])."</em>":""?></a></li>

			<li><a data-tab="#tab4">상품문의 <?=$item[it_qna_cnt]?"<em class='cer'>".number_format($item[it_qna_cnt])."</em> ":""?></a></li>
		</ul>
	</div>
</div>


<style>
.nfor_line { height:1px; }

.deals_container { position: relative;width: 1200px; margin: -58px auto 0; padding-top: 58px;  border-right: 1px solid #e3e5e8; border-bottom: 1px solid #e3e5e8; }
.tab-cont {width: 860px; padding:0px 0px; box-sizing:border-box; -webkit-box-sizing:border-box; -moz-box-sizing:border-box;  border-left: 1px solid #e3e5e8; border-right: 1px solid #e3e5e8;min-height:1000px;}
.deals_floating_opt { position: absolute; top: 58px; right: 0;width: 313px;background-color: #fff;}



.deals_floating_opt .total_price{margin:10px 0px;}
.deals_floating_opt .total_price:after{content:""; display: block; clear: both; }
.deals_floating_opt .total_price .tit{float:left; margin-top:10px;}
.deals_floating_opt .total_price .t_price{float:right}
.deals_floating_opt .total_price .t_price .opt_cal_total_span{display: inline-block; position: relative; margin-right: 1px;font-size: 28px; font-weight:bold; font-family: Tahoma,"돋움",Dotum,sans-serif;}



.deals_floating_opt .opt_cart_order_btn li{ padding:0px 0px 5px; }
.deals_floating_opt .item_btn { border:1px solid #e83862; display:block; box-sizing:border-box; -webkit-box-sizing:border-box; -moz-box-sizing:border-box; -ms-box-sizing:border-box; -o-box-sizing:border-box; height:64px; line-height:64px; padding:0 16px; font-size:18px; color:#fff; text-align:center; font-family:'NanumGothicBold'; cursor:pointer; }




.deals_floating_opt .item_btn_cart { background-color:#fff; color:#e83862; } 
.deals_floating_opt .item_btn_cart:hover{color:#e83862;}
.deals_floating_opt .item_btn_buy { background-color:#e83862; }
.deals_floating_opt .item_btn_buy:hover{color:#fff;}




.order_top { overflow:hidden; min-height:550px; height:auto; margin:20px 0px; box-sizing:border-box; -webkit-box-sizing:border-box; -moz-box-sizing:border-box; }
.order_bottom { overflow:visible; position:absolute; bottom:0; left:0; width:100%; background-color:#fff; border-top:solid 1px #ccc; box-sizing:border-box; -webkit-box-sizing:border-box; -moz-box-sizing:border-box; }

.right_order { position:relative; top:auto; bottom:auto; height:0px; min-height:571px; width:300px;  }
.right_order.junadown { position:fixed; top:58px; bottom:0px; height:auto; max-height:774px!important; }


.right_order .opt_cal_list { overflow-y:auto; min-height:200px; }

.tab-cont { display:none; }
.pa30{padding:30px;}
#tab1 { display:block; }
</style>
<div class="deals_container">






	<div id="tab1" class="tab-cont">
	<? if($item[it_youtube_use]=="1"){ ?>	
	<iframe width="<?=$item[it_youtube_width]?>" height="<?=$item[it_youtube_height]?>" src="<?=$item[it_youtube_link]?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>	
	<? } ?>
	<? if($nfor[test]){ ?><img src="/skin/demo/img/56.png"><? } else{ ?><?=$item[it_memo]?><? } ?>
	</div>
	
	<div id="tab2" class="tab-cont pa30" >
		<div class="item_coution"><?=$item[it_rule]?></div>





		<style>
		.item_coution{ font-size: 12px; font-family:'Nanum Gothic'; color:#a0a8b1!important;;line-height:20px;} 
		.it_info_val_tit{font-size:17px; padding:20px 0px; font-family: 'NanumGothicBold';}
		.it_info_val_tbl{border-top:solid 2px #333; margin-bottom:20px; line-height:20px;}
		.it_info_val_tbl th { font-size: 12px; background-color:#f8f8f8; border-bottom:solid 1px #ccc; height:30px;  padding:15px;text-align:left; font-weight:normal;color:#666; }
		.it_info_val_tbl td { font-size: 12px; border-bottom:solid 1px #ccc; height:30px;  padding:15px; color:#a0a8b1;  }
		</style>
		<div >




			<h3 class="it_info_val_tit">상품정보제공고시</h3>
			<table class="table it_info_val_tbl" cellpadding="0" cellspacing="0">
			<colgroup>
				<col width="230">
			</colgroup>
			<th>항목</th>
			<td>내용</td>
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


			<? if($item[it_add_info]){ ?>
			<h3 class="it_info_val_tit">추가항목</h3>
			<table class="table it_info_val_tbl" cellpadding="0" cellspacing="0">
			<colgroup>
				<col width="230">
			</colgroup>
			<th>항목</th>
			<td>내용</td>
			<?
			$it_info = json_decode($item[it_add_info], true);
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
			<? } ?>




		</div>
	</div>

	<div id="tab3" class="tab-cont pa30"><? include_once "item_star_list.php"; ?></div>

	<div id="tab4" class="tab-cont pa30"><? include_once "item_qna_list.php"; ?></div>


	
	<div class="deals_floating_opt">


		<div class="inner right_order">
			

				<div class="order_top">
					<?php
					include "opt_popup.php";
					?>
				</div>

				<div class="order_bottom">
					<div class="opt_cal_total">
						<div class="total_price">
							<div class="tit">총구매금액</div> 
							<div class="t_price"><span class="opt_cal_total_span"><?=$opt_cal_total_span?></span><span>원</span></div>
						</div>
					</div>			
					<div class="opt_cart_order_btn <?=$item[it_opt_use]=="2"?"on":""?>">
						<ul>
							<li><a class="item_btn item_btn_cart opt_cart_btn">장바구니</a></li>
							<li><a class="item_btn item_btn_buy opt_order_btn">구매하기</a></li>
						</ul>
					</div>
				</div>


		</div>



	</div>
</div>



<style>
.tab-wrap.top { position:fixed; top:0px; left:0px; height:58px; width:100%; }
</style>


<script>

$(window).scroll( function(){

	var scrollTop = $(document).scrollTop();

	// 스크롤 상단위치가 탭위치로 오면
	if(scrollTop >= $('.nfor_line').offset().top + 1){
		$(".right_order").addClass("junadown").css("height","");

		$(".tab-wrap").addClass("top");

	} else{

		var scrollBottom = $(window).scrollTop() + $(window).height();

		bottomtop = scrollBottom-$('.nfor_line').offset().top;
		bottomtop = bottomtop - 90;

		$(".right_order").removeClass("junadown").css("height",bottomtop+"px");

		$(".right_order .opt_cal_list").css("height",(bottomtop/2) + "px");


		$(".tab-wrap").removeClass("top");
	}

	var scrollHeight = $(document).height();
	var scrollPosition = $(window).height() + $(window).scrollTop()

	bottom_height = scrollHeight - scrollPosition;
	
	$(".junadown").css("height",bottom_height+"px");

});

</script>


<span id="bottom"></span>







<style>

/* 장바구니담기 팝업 */
#cart_popup { position:fixed; left:0px; top:-280px; width:100%; z-index:99999; display:none; } 
#cart_popup div { background-color:rgba(0,0,0,0.8); margin:0 auto; width:300px; text-align:center; padding:30px 0px; font-family:NSKL;}
#cart_popup p { margin-bottom:10px; display:block; font-size:16px; color:#fff; font-weight:bold; }
#cart_popup a { background-color:#e83862; height:30px; line-height:30px; display:block; width:150px; color:#fff; margin:0 auto; font-family:NSKL; }


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

</style>

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
var it_id = "<?=$item[it_id]?>";
var it_buy_qty = parseInt("<?=$item[it_buy_qty]?>");
var it_opt_depth = parseInt("<?=$item[it_opt_depth]?>");
var it_url = location.href;	
var it_img = "<?=$item[it_img_url]?>";
var it_name = "<?=$item[it_name]?>";
var it_description = "<?=$item[it_description]?>";

$(document).on("click", ".tabmenu li", function(){
	$(".tabmenu li").removeClass("active");
	$(this).addClass("active");
	$(".tab-cont").hide();
	$($(this).find("a").data("tab")).show();
});

$(document).on("click", ".it_img_s", function(){
	$(".it_img_s").removeClass("it_img_s_on");
	$(this).addClass("it_img_s_on");

	$("#it_img").attr("src", $(this).attr("src"));
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
				} else if(ty=="naverpay"){
					location.href="naverpay_order.php?it_id="+it_id;
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

<?php
include_once $nfor[skin_path]."tail.php";
?>