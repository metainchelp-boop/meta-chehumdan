<?php
include_once "path.php";

$goodname = $write[cp_subject];
$buyername = $member[mb_name];
$buyeremail = $member[mb_email];
$buyertel = $member[mb_hp];
$vbank_expire = date("Ymd",strtotime("+1 day")); // 가상계좌 입금기한


require_once($nfor[pg_path].'/libs/INIStdPayUtil.php');
$SignatureUtil = new INIStdPayUtil();
/*
  //*** 위변조 방지체크를 signature 생성 ***

  oid, price, timestamp 3개의 키와 값을

  key=value 형식으로 하여 '&'로 연결한 하여 SHA-256 Hash로 생성 된값

  ex) oid=INIpayTest_1432813606995&price=819000&timestamp=2012-02-01 09:19:04.004


 * key기준 알파벳 정렬

 * timestamp는 반드시 signature생성에 사용한 timestamp 값을 timestamp input에 그대로 사용하여야함
 */

//############################################
// 1.전문 필드 값 설정(***가맹점 개발수정***)
//############################################
// 여기에 설정된 값은 Form 필드에 동일한 값으로 설정
$mid = $nfor[pg_id];  // 가맹점 ID(가맹점 수정후 고정)					
//인증



$cardNoInterestQuota = "";  // 가맹점이 수수료부담 - 카드 무이자 여부 설정(가맹점에서 직접 설정) 11-2:3:,34-5:12,14-6:12:24,12-12:36,06-9:12,01-3:4
$quotabase = "2:3:4:5:6:11:12:24:36";  // 가맹점에서 사용할 할부 개월수 설정
//###################################
// 2. 가맹점 확인을 위한 signKey를 해시값으로 변경 (SHA-256방식 사용)
//###################################


$acceptmethod = "vbank($vbank_expire):HPP(2):no_receipt:va_receipt:vbanknoreg(0):below1000";


// 기본결제수단
$gopaymethod = "Card";
?>

<!-- 이니시스 표준결제 js -->
<!--
  연동시 유의 사항!!
  1) 테스트 URL(stgstdpay.inicis.com) - 샘플에 제공된 테스트 MID 전용으로 실제 가맹점 MID 사용 시 에러가 발생 할 수 있습니다.
  2) 상용 URL(stdpay.inicis.com) - 실제 가맹점 MID 로 테스트 및 오픈 시 해당 URL 변경하여 사용합니다.
  3) 가맹점의 URL이 http: 인경우 js URL도 https://stgstdpay.inicis.com/stdjs/INIStdPay.js 로 변경합니다.	
  4) 가맹점에서 사용하는 케릭터셋이 EUC-KR 일 경우 charset="UTF-8"로 UTF-8 일 경우 charset="UTF-8"로 설정합니다.
-->	

<? if($mid=="INIpayTest"){ ?>
<!-- 테스트 JS(샘플에 제공된 테스트 MID 전용) -->
<script language="javascript" type="text/javascript" src="https://stgstdpay.inicis.com/stdjs/INIStdPay.js" charset="UTF-8"></script>
<? } else{ ?>
<!-- 상용 JS(가맹점 MID 변경 시 주석 해제, 테스트용 JS 주석 처리 필수!) -->
<script language="javascript" type="text/javascript" src="https://stdpay.inicis.com/stdjs/INIStdPay.js" charset="UTF-8"></script>
<? } ?>


<script type="text/javascript">
<!--
$(document).on("click",".payment_type",function(){

	var payment_type = $(".payment_type:checked").val();
	var paymethod = "";

	if(payment_type=="card"){
		paymethod = "Card";
	} else if(payment_type=="iche"){
		paymethod = "DirectBank";
	} else if(payment_type=="vbanking"){
		paymethod = "VBank";
	} else if(payment_type=="hp"){
		paymethod = "HPP";
	} else{
		paymethod = "";
	}

	$("#gopaymethod").val(paymethod);

});

function cart_payment(){
	INIStdPay.pay('SendPayForm_id');
	$("#payment_btn").show();
}
//-->
</script>




<form id="SendPayForm_id" name="" method="POST">
<input type="hidden" name="version" value="1.0" >
<input type="hidden" name="mid" id="pg_id" value="<?=$mid?>" >




<input type="hidden" name="goodname" value="<?=$goodname?>" >
<input type="hidden" name="oid" value="<?=$cp_id?>" >
<input type="hidden" name="price" id="pay_price" value="<?=$total_price?>">
<input type="hidden" name="currency" value="WON" >
<input type="hidden" name="buyername" id="buyername" value="<?=$buyername?>" >
<input type="hidden" name="buyertel" id="buyertel" value="<?=$buyertel?>" >
<input type="hidden" name="buyeremail" id="buyeremail" value="<?=$buyeremail?>" >


<input type="hidden" name="timestamp" id="timestamp" >
<input type="hidden" name="signature" id="signature" >
<input type="hidden" name="returnUrl" value="<?=$nfor[url]?>/admin/pg_inipay_update.php" >
<input type="hidden" name="mKey" id="mKey" value="<?=$mKey?>" >


<input type="hidden" name="gopaymethod" id="gopaymethod" value="<?=$gopaymethod?>">

<input type="hidden" name="offerPeriod" value="">
<input type="hidden" name="acceptmethod" id="acceptmethod" value="<?=$acceptmethod?>">


<input type="hidden" name="languageView" value="ko" >
<input type="hidden" name="charset" value="UTF-8" >
<input type="hidden" name="payViewType" value="overlay" >

<!-- closeUrl : payViewType='overlay','popup'시 취소버튼 클릭시 창닥기 처리 URL(가맹점에 맞게 설정)
close.jsp 샘플사용(생략가능, 미설정시 사용자에 의해 취소 버튼 클릭시 인증결과 페이지로 취소 결과를 보냅니다.) -->
<input type="hidden" name="closeUrl" value="<?=$nfor[url]?>/admin/pg_inipay_close.php" >

<!-- popupUrl : payViewType='popup'시 팝업을 띄울수 있도록 처리해주는 URL(가맹점에 맞게 설정)
popup.jsp 샘플사용(생략가능,payViewType='popup'으로 사용시에는 반드시 설정) -->
<input type="hidden" name="popupUrl" value="<?=$nfor[url]?>/admin/pg_inipay_popup.php" >


<input type="hidden" name="nointerest" value="<?=$cardNoInterestQuota?>" >
<input type="hidden" name="quotabase" id="quotabase" value="<?=$quotabase?>" >	

<input type="hidden" name="vbankRegNo" value="" >
<input type="hidden" name="merchantData" value="" >

</form>