<?php
include_once $nfor[skin_path]."head.php";
?>
<style>
#rv_dy_zip{float:left;}
#allchk{}
</style>

<form name="review_form" id="review_form" method="post">
<input type="hidden" name="mode" value="insert">
<input type="hidden" name="cp_id" value="<?=$campaign[cp_id]?>">
<div class="application_wrap">
	<a class="btn_close" id="review_close_btn"></a>
	<h2>리뷰 신청하기</h2>
	<div class="app_list_wrap">
		<ul>
			<li><span class="txt_num">1.</span> <?=$campaign[cp_media_shop]?"쇼핑몰 아이디를 입력해주세요":"리뷰에 사용할 채널를 선택해주세요."?> <br><b class="point_color4 font_small">※ 리뷰어 선정시 변경불가</b></li>
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
				<div class="mar-b5"  style="height:35px; line-height:35px; overflow:hidden;">
					<?=admin_text($write,"rv_dy_zip","width_150","readonly placeholder='우편번호'")?><span class="btn_pack" style="float:left;"><a class="btn_md black find_zipcode_btn">우편번호찾기</a></span>
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

			<?=admin_checkbox_basic_chk($write,"allchk","allchk","","상기의 내용들에 전체 동의합니다")?>
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
	
	<a class="rv_submit_btn_wrap" id="rv_submit_btn">리뷰신청하기</a>
</div>
</form>

<script type="text/javascript">
<!--
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
include_once $nfor[skin_path]."tail.php";
?>