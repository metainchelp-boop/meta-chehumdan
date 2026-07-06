<?php
include_once "head.php";
?>
<style>
#review_close_btn{display:none; position:absolute; right:35px; top:35px; width:30px; height:30px; background:url(<?=$nfor[skin_path]?>img/close_btn.png); background-size:30px; }
.application_wrap{position:relative;  width:100%; padding:30px; margin:0px auto;background-color:#FFF;-webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;  }
.application_wrap h2{font-size:25px; color:#000; font-weight:400; border-bottom:solid 1px #666;line-height:54px;}
.application_wrap  .app_list_wrap{margin-top:20px; letter-spacing:-1px;}
.application_wrap  .app_list_wrap .form_css{position:relative; font-size:14px; line-height:21px; }
.application_wrap  .app_list_wrap li{line-height:40px; font-size:16px; color:#000; position:relative;}
.application_wrap  .app_list_wrap .non{line-height:0px;}
.application_wrap  .app_list_wrap li select { min-width:90px; padding: 6px 5px 5px; height:35px; border: 1px solid #d0d0d0; color: #828284; vertical-align: middle;  background: url(<?=$nfor[skin_path]?>img/select_background.png) no-repeat 100% 50%; font-size: 15px; -webkit-appearance: none; -moz-appearance: none;  appearance: none;}
.application_wrap  .app_list_wrap li select { appearance: none; -webkit-appearance: none;}
.application_wrap  .app_list_wrap li select::-ms-expand { display:none; }
.application_wrap  .app_list_wrap li textarea { width: 100%; height: 120px; padding: 7px 8px; border: 1px solid #d0d0d0; line-height: 16px; color: #828284; ; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; }
.application_wrap  .app_list_wrap input[type=text] {padding-left: 11px;  font-size:14px; border:1px solid #e0e0e0; background-color:#FFF;  height:35px; line-height:35px;  box-sizing: border-box; color: #828284; vertical-align: middle; }
.application_wrap  .app_list_wrap input[type=radio]{vertical-align:0px;}
.application_wrap  .app_list_wrap label{display:inline-block; margin-right:10px;}
.application_wrap  .app_list_wrap .co_box{font-size:14px; line-height:24px; margin-top:20px;}
.application_wrap  .app_list_wrap .co_box label{display:block; color:#666}
.application_wrap  .app_list_wrap .co_box .inner{margin:20px 0px;}
.application_wrap  .app_list_wrap .co_box .inner span{display:block; font-size:16px; margin-bottom:20px;}

.box_tbl{width:100%; border:solid 1px #DCDCDC; background-color:#FFFFFF;}
.box_tbl th {font-weight:bold;color:#666; padding:15px 15px; background-color:#f4f4f4; border-right:solid 1px #DCDCDC; border-bottom:solid 1px #DCDCDC;}
.box_tbl td {color:#666; padding:15px 15px; border-right:solid 1px #DCDCDC; border-bottom:solid 1px #DCDCDC; line-height:20px;} 

.ok_sign{position:absolute; top:0px; right:0px}
.font_small{font-size:13px; font-weight:normal;}
.width_100{width:100%;}
.width_90{width:90%;}
.width_150{width:150px;}
.width_10{width:10%;}
.width_60{width:60%; }
.width_40{width:40%;}
.paddleft20{padding:0px 20px;}
.mar-b5{margin-bottom:5px;}

.btn_pack { vertical-align:middle; }

.rv_submit_btn_wrap { text-align:center; margin-top:15px; cursor:pointer; background-color:#747c8a; font-size:24px; color:#fff; display:block; padding:10px; }
.allchk_wrap { font-weight:400; font-size:16px; color:#000!important;;}

.sub_item_wrap{position:relative; width:100%; -ms-box-sizing: border-box; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; }
.iteminfo_list_wrap{width:calc(100% - 341px);padding: 20px 0px 0px 0;-ms-box-sizing: border-box; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box;  }
.iteminfo_right_fix{ width: 340px;  position: absolute; right: 0px;top: 0; bottom: 0; font-size: 16px;letter-spacing: -.4px; z-index: 30; padding-left: 0 px;  border-left: 1px solid #eee; background-color:#ffffff;min-height:650px;}
.sub_item_wrap .iteminfo_right_fix .thumb{width:100%;}
.sub_item_wrap .iteminfo_right_fix .thumb img{width:100%; border:solid 1px #efefef; box-sizing:border-box; -webkit-box-sizing:border-box; -moz-box-sizing:border-box; }
.sub_item_wrap .iteminfo_right_fix .itname{font-size:18px; color:#000; margin-top:15px;}
.sub_item_wrap .iteminfo_right_fix .it_description{font-size:14px; letter-spacing:-1px; margin-top:10px; margin-bottom:10px;}
.sub_item_wrap .iteminfo_right_fix .iteminfo_top{margin-bottom:15px;}
.sub_item_wrap .iteminfo_right_fix .iteminfo_top span{letter-spacing:-1px; margin-right:0px; font-size:12px; display:inline-block; padding:2px 5px; margin-right:3px;}
.sub_item_wrap .iteminfo_right_fix .iteminfo_top span + span{letter-spacing:-1px; margin-right:5px; font-family: 'Spoqa Han Sans';}
.sub_item_wrap .iteminfo_right_fix .iteminfo_top .nor_box{background-color:#efefef; font-size:12px; border:solid 1px #dcdcdc; font-family: 'Spoqa Han Sans';}
.sub_item_wrap .iteminfo_right_fix .iteminfo_top .instagram{color:#ff3478;  border:solid 1px #ff0055;  font-family: 'Spoqa Han Sans';}
.sub_item_wrap .iteminfo_right_fix .iteminfo_top .blog{color:#2ba406; border:solid 1px #2ba406; font-family: 'Spoqa Han Sans';}
.sub_item_wrap .iteminfo_right_fix .iteminfo_top .facebook{color:#415fc1; border:solid 1px #415fc1; font-family: 'Spoqa Han Sans';}
.sub_item_wrap .iteminfo_right_fix .iteminfo_top .youtube{color:#f41515;  border:solid 1px #f41515; font-family: 'Spoqa Han Sans';}
.sub_item_wrap .iteminfo_right_fix .iteminfo_top .kakaostory{color:#f3d710; border:solid 1px #f3d710; font-family: 'Spoqa Han Sans'; }
.sub_item_wrap .iteminfo_right_fix .iteminfo_top .shop{color:#f41515;  border:solid 1px #f41515; font-family: 'Spoqa Han Sans';}

.sub_item_wrap .iteminfo_right_fix .review_step_wrap{margin-top:10px; max-width:550px; margin-bottom:10px; font-size:14px; letter-spacing:-1px; padding:10px 10px; border-top:solid 1px #efefef;border-bottom:solid 1px #efefef; }
.sub_item_wrap .iteminfo_right_fix .review_step_wrap .step{display:block; height:25px; line-height:25px;}
.sub_item_wrap .iteminfo_right_fix .review_step_wrap .step em{display:inline-block; width:100px; position:relative; padding-left:10px;}
.sub_item_wrap .iteminfo_right_fix .review_step_wrap .step em:before{display: block;clear: both; content: ''; position:absolute; left:0px; top:13px; width:2px; height:2px;  background-color:#666; }

.coution{display:block; background-color:#fafafa; padding:20px; border:solid 1px #efefef;  margin-top:20px; }
.coution span{position:relative; display:block; font-size:14px; line-height:28px; color:#666; padding-left:14px; }
.coution span b{font-weight:normal;}
.coution span:before{display: block; clear: both; content: ''; position: absolute;left: 0px;top: 13px;width: 2px; height: 2px;background-color: #666; }

.floating{position:absolute;; top: 0px; width: 300px; min-height:550px;  margin:0px auto; padding:10px 15px; z-index:999; }
.sticky{top:0px!important; padding:15px 15px!important; }
.btn_zone_floating{}
.btn_zone_floating li{padding-bottom:5px;float:left;}
.btn_zone_floating li:nth-child(1){width:100%; float:left;}

.tabmenu{overflow:hidden; margin-top:10px;}
.tabmenu li{float:left;position:relative;}
.tabmenu li {display:block; position:relative; border: 1px solid #333; margin-left: -1px;   height: 40px; padding:0px 30px; border: 1px solid #999;  line-height: 40px;text-align: center; background-color: #f2f2f2;}
.tabmenu li a{color:#888;}
.tabmenu .active{background: #fff; color: #000; border: 1px solid #333;  margin-left: -1px;}
.tabmenu .active a{color:#000;}
.tabmenu li:nth-child(1) {margin-left: -0px;}

.tab-cont { display:none; border:solid 1px #dcdcdc;  padding:20px; min-height:140px;}
#tab1 { display:block; }
#tab1 textarea{font-family: "montserrat"; letter-spacing:0px; line-height:24px; font-size:16px; }
#tab2 span{display:block; font-size:14px; line-height:24px; color:#666; padding-left:0px;}
#tab2 img{border:solid 1px #efefef; padding:10px; margin:10px 0px;}



</style>

<div class="sub_item_wrap">
<div class="iteminfo_list_wrap">
<form name="review_form" id="review_form" method="post">
<input type="hidden" name="mode" value="insert">
<input type="hidden" name="cp_id" value="<?=$campaign[cp_id]?>">
<div class="application_wrap">
	<a class="btn_close" id="review_close_btn"></a>
	<h2>리뷰 신청하기</h2>
	<div class="app_list_wrap">
		<ul>
			<li><span class="txt_num">1.</span> <?=$campaign[cp_media_shop]?"쇼핑몰 아이디를 입력해주세요":"리뷰에 사용할 채널를 선택해주세요."?> <b class="point_color4 font_small">※ 리뷰어 선정시 변경불가</b></li>
			<li>
			<div class="form_css">
			<? if($campaign[cp_media_shop]){ ?>
				<?=admin_text($write,"rv_channel","width_100")?>
			<? } else{ ?>
				<?=admin_select($write,"rv_channel","width_90","","0")?>
				<span class="btn_pack ok_sign"><a href="member_form.php" class="btn_md black">채널 등록/수정</a></span>
			<? } ?>
			</div>
			</li>
			<li><span class="txt_num">2.</span> 신청자 한마디를 남겨주세요 <span class="txt_num point_color4 font_small">(<span id="rv_msg_counter">0</span>/50)</span></li>
			<li><?=admin_text($write,"rv_msg","width_100")?></li>
			<li><span class="txt_num">3. </span>캠페인 상세 내용을 확인 하신 뒤 ‘신청필수 정보’ 를 입력해주세요 <span class="txt_num point_color4 font_small">(<span id="rv_memo_counter">0</span>/250)</span></li>
			<!-- <li class="non">
				<div class="form_css paddleft20">
					<?=$campaign[cp_subject]?>
				</div>
			</li> -->
			<li>
				<?=admin_textarea($write,"rv_memo","width_100")?>
			</li>
			<?php if($campaign[cp_type]=="1"){  ?>
			<li>4. 리뷰 상품을 배송 받을 주소를 입력해주세요.</li>
			<li>
				<?php
				if(!$write[dy_adress_type]) $write[dy_adress_type] = "1";
				?>
				<?=admin_radio($write,"dy_adress_type","dy_adress_type","","0")?>
			</li>
			<li>
			<div class="form_css">
				<div class="mar-b5">
					<?=admin_text($write,"rv_dy_zip","width_150","readonly placeholder='우편번호'")?><span class="btn_pack"><a class="btn_md black find_zipcode_btn">우편번호찾기</a></span>
				</div>
				<?=admin_text($write,"rv_dy_addr1","width_100 mar-b5","placeholder='주소'")?>
				<?=admin_text($write,"rv_dy_addr2","width_100","placeholder='상세주소'")?>
			</div>
			</li>
			<li>받는분 이름</li>
			<li><?=admin_text($write,"rv_dy_name","width_100")?></li>
			<li>받는분 연락처 </li>
			<li><?=admin_text($write,"rv_dy_hp","width_100")?></li>
			<? } ?>



			<li>
			<div class="co_box">
			<h2>신청전에 꼭 확인해주세요!</h2>
			<div class="inner">
			<span>프로젝트 선정 이후 취소는 불가능하며, 취소 혹은 무단 미참여시 광고주 측에서 손해 배상을 요구할 수 있습니다.<br> 신청 시에는 리뷰어님께서 해당 내용에 동의한 것으로 간주됩니다.<br> 프로젝트의 상세 내용을 꼼꼼히 확인하시고, 신중한 참여 부탁드립니다.</span>

			<?=admin_checkbox_basic_chk($write,"rv_agree1","review_chk","","정당한 사유없이 콘텐츠 등록기간 내 리뷰콘텐츠를 작성하지않을 경우 제공상품 또는 용역의 대가를 환불해야 합니다")?>
			<?=admin_checkbox_basic_chk($write,"rv_agree2","review_chk","","관련법조항(형법 제347조)에 따라 법적 처벌대상이 될수 있습니다")?>
			<?=admin_checkbox_basic_chk($write,"rv_agree3","review_chk","","등록한 콘텐츠는 홍보나 필요에 의해 사용 될수 있습니다")?>
			<?=admin_checkbox_basic_chk($write,"rv_agree4","review_chk","","등록한 콘텐츠의 유지기간 (6개월) 미준수 시 제공내역에 대한 비용이 청구 될수 있습니다")?>
			<?=admin_checkbox_basic_chk($write,"rv_agree5","review_chk","","제공내역은 타인에게 양도 및 판매를 허용하지 않습니다")?>
			<?=admin_checkbox_basic_chk($write,"rv_agree6","review_chk","","개인정보 수집 및 이용에 동의 합니다")?>

			<b class="allchk_wrap"><?=admin_checkbox_basic_chk($write,"allchk","","","상기의 내용들에 전체 동의합니다")?></b>
			</div>
			<table class="box_tbl">
				<tr>
					<th>목적</th>
					<th>항목</th>
					<th>보유 및 이용기간</th>
				</tr>
				<tr>
					<td>캠페인 선정 및 진행시 사용</td>
					<td>회원정보( 이름,생년월일,휴대전화번호 )<br>배송정보( 이름,휴대번호, 주소 )</td>
					<td>회원정보( 이름,생년월일,휴대전화번호 )<br>:캠페인 종료 후 1개월<br>배송정보( 이름,휴대번호, 주소 )<br>:캠페인 신청후 3개월</td>
				</tr>
				</table>
			</div>
			</li>
		</ul>		
	</div>
	
	<a class="rv_submit_btn_wrap" id="rv_submit_btn" style="color:#fff;">리뷰신청하기</a>
</div>
</form>
</div>
<div class="iteminfo_right_fix">
		<div class="floating">
		<?php
		include $nfor[skin_path]."inc_right_floating_order.php";
		?>
		</div>
</div>
</div>
<script type="text/javascript">
<!--
$(document).on("click", ".tabmenu li", function(){
	$(".tabmenu li").removeClass("active");
	$(this).addClass("active");
	$(".tab-cont").hide();
	$($(this).find("a").data("tab")).show();
});

$(".floating").stick_in_parent({
    offset_top: 0
});

$(document).on("click", "#allchk", function(){
	nfor_chk_all(this, 'review_chk');
});

function nfor_chk_all(this_id, this_class){
	if($(this_id).is(":checked")){
		$("."+this_class).prop("checked",true);
	} else{
		$("."+this_class).prop("checked",false);
	}
}

$(document).on("click","#review_close_btn",function(){
	$(".review_order_popup").fadeOut();
});

$(document).on("click",".dy_adress_type",function(){
	var dy_adress_type = $(this).val();
	if(dy_adress_type=="1"){
		$("#rv_dy_name").val("");
		$("#rv_dy_zip").val("");
		$("#rv_dy_addr1").val("");
		$("#rv_dy_addr2").val("");
		$("#rv_dy_hp").val("");
	} else if(dy_adress_type=="2"){
		$("#rv_dy_name").val("<?=$last_dy[rv_dy_name]?>");
		$("#rv_dy_zip").val("<?=$last_dy[rv_dy_zip]?>");
		$("#rv_dy_addr1").val("<?=$last_dy[rv_dy_addr1]?>");
		$("#rv_dy_addr2").val("<?=$last_dy[rv_dy_addr2]?>");
		$("#rv_dy_hp").val("<?=$last_dy[rv_dy_hp]?>");
	} else if(dy_adress_type=="3"){
		$("#rv_dy_name").val("<?=$member[mb_name]?>");
		$("#rv_dy_zip").val("<?=$member[mb_zipcode]?>");
		$("#rv_dy_addr1").val("<?=$member[mb_addr1]?>");
		$("#rv_dy_addr2").val("<?=$member[mb_addr2]?>");
		$("#rv_dy_hp").val("<?=$member[mb_hp]?>");
	} else{
		
	}
});

$(document).on("click",".find_zipcode_btn",function(){
	zipcode('rv_dy_zip','rv_dy_addr1','rv_dy_addr2');
});

$(document).on("click","#rv_submit_btn",function(){

	$(this).hide();

	$.ajax({
		type:"post",
		data :$("#review_form").serialize(),
		url:"review_order.php",
		success:function(response){
			console.log(response);
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				if(json["msg"]){
					alert(json["msg"]);
				}
				if(json["back"]=="1"){
					location.href = document.referrer;
				} else if(json["url"]){
					location.href = json["url"];
				} else{
					location.reload();
				}
			} else{
				if(json["msg"]){
					alert(json["msg"]);
				}
				if(json["result"]){
					$("#"+json["result"]).focus();
				}
				$("#rv_submit_btn").show();
			}
		}
	});
	event.preventDefault();
});

$(document).on("keyup","#rv_msg",function(){
	$("#rv_msg_counter").html($(this).val().length);
	if($(this).val().length > 50){
		$(this).val($(this).val().substring(0, 50));
	}
});

$(document).on("keyup","#rv_memo",function(){
	$("#rv_memo_counter").html($(this).val().length);
	if($(this).val().length > 250){
		$(this).val($(this).val().substring(0, 250));
	}
});
//-->
</script>
<?php
include_once "tail.php";
?>