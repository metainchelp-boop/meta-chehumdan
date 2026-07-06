<?php
include_once "path.php";
ini_set('memory_limit', -1);

$order = sql_fetch("select * from nfor_campaign_order where co_id='$co_id'");

if($mode=="update"){

	if(!$price) alert("취소요청금액을 입력해주세요");

	$nam_price = $order[co_total_price]-$order[co_cancel_price]; // 남아 있는 금액
	if($price > $nam_price) alert("취소가능한 금액은 최대 ".number_format($nam_price)."원 입니다");

	$confirm_price = $order[co_pg_price] - $order[co_cancel_price];	// 남은 PG 취소금액(취소가능금액)
	if($order[co_payment_type]=="vbanking"){
		if($order[co_mb_no]){ // 회원
			$mb = member("mb_no",$order[co_mb_no]);		
			$refundbankcode = trim($mb[mb_bank_name]);
			$refundacctnum = str_number(trim($mb[mb_bank_account]));
			$refundacctname = trim($mb[mb_name]);
		}
		if(!$refundbankcode){ // 비회원
			$refundbankcode = trim($order[co_refund_bank]); // 환불계좌은행코드
			$refundacctnum = str_number(trim($order[co_refund_account])); // 환불계좌번호(숫자만입력)
			$refundacctname = trim($order[co_refund_name]); // 환불계좌주명	
		}	
		if(!$refundbankcode or !$refundacctnum or !$refundacctname){
			alert("환불계좌가 설정되지 않았습니다\n주문 상세페이지이동후 환불계좌를 등록해주세요");
		}
	}

	if($order[co_payment_type]=="banking"){
		
		$co_cancel_price = $order[co_cancel_price] + $price;

		if($order[co_total_price]==$co_cancel_price){
			$co_pay_step = "3";
			$add_sql = ", co_cancel_datetime=NOW()";
			$add_sql_campaign = ", cp_asign='1'";
		} else{
			$co_pay_step = "7";			
		}

		sql_query("update nfor_campaign_order set co_cancel_price='$co_cancel_price', co_pay_step='$co_pay_step' $add_sql where co_id='$co_id'");
		sql_query("update nfor_campaign set cp_pay_step='$co_pay_step' $add_sql_campaign where cp_id='$order[co_cp_id]'");

		alert_close_refresh("주문취소 처리되었습니다");
	}

	$confirm_price = $confirm_price-$price;	// 승인요청금액(최종남게되는결제금액)
	$buyeremail = $order[co_mb_email];

	/* INIrepay.php
	 *
	 * 이미 정상 승인된 거래에서 취소를 원하는 금액을 입력하여 다시 승인을 득하도록 요청한다.
	 * 
	 * [주의] 부분취소 요청 때마다  새 거래아이디가 반환되나, 부분취소 요청은 반드시 원거래 TID로 가능함에 유의
	 * [주의] 원거래가 신용카드 지불인 경우에만 가능
	 * (OK 캐쉬백 적립 등이 포함되어 있어도 불가)
	 * [주의] 반드시 취소할 금액을 입력하도록 함
	 * 
	 * Date : 2004/11
	 * Author : ts@inicis.com
	 * Project : INIpay for Unix
	 * 
	 * http://www.inicis.com
	 * Copyright (C) 2004 Inicis, Co. All rights reserved.
	 */

	/**************************
	 * 1. 라이브러리 인클루드 *
	 **************************/
	require($nfor[pg_path]."/libs/INILib.php");


	/***************************************
	 * 2. INIpay41 클래스의 인스턴스 생성 *
	 ***************************************/
	$inipay = new INIpay50;

		
	/***********************
	 * 3. 재승인 정보 설정 *
	 ***********************/
	$inipay->SetField("inipayhome", $nfor[pg_path]);  // 이니페이 홈디렉터리(상점수정 필요)

	if($order[co_payment_type]=="vbanking"){
		$inipay->SetField("type", "vacctrepay");      							// 고정 (절대 수정 불가)
		$inipay->SetField("refundbankcode", $refundbankcode);				// 가상계좌 부분환불 계좌 은행코드
		$inipay->SetField("refundflgremit", $refundflgremit);				// 가상계좌 부분환불 송금 처리 여부 (1: 송금환불사용)
		$inipay->SetField("refundacctnum", $refundacctnum);					// 부분취소 환불계좌번호
		$inipay->SetField("refundacctname", $refundacctname);				// 부분취소 환불계좌주명
	} else{
		$inipay->SetField("type", "repay");                             // 고정 (절대 수정 불가)
		$inipay->SetField("no_acct",$no_acct); //국민은행 부분취소 환불계좌번호
		$inipay->SetField("nm_acct",$nm_acct); //국민은행 부분취소 환불계좌주명
	}

	$inipay->SetField("pgid", "INIphpRPAY");                        // 고정 (절대 수정 불가)
	$inipay->SetField("subpgip","203.238.3.10");                    // 고정
	$inipay->SetField("debug", "true");                             // 로그모드("true"로 설정하면 상세로그가 생성됨.)
	$inipay->SetField("mid", $order[co_pg_id]);                                 // 상점아이디
	$inipay->SetField("admin", "1111");                             //비대칭 사용키 키패스워드
	$inipay->SetField("oldtid", $order[co_pg_tid]);                           // 취소할 거래의 거래아이디
	$inipay->SetField("currency", $currency);                       // 화폐단위
	$inipay->SetField("price", $price);                             //취소금액
	$inipay->SetField("confirm_price", $confirm_price);             //승인요청금액
	$inipay->SetField("buyeremail",$buyeremail);                    // 구매자 이메일 주소

	/******************
	 * 4. 재승인 요청 *
	 ******************/
	$inipay->startAction();

	/*********************************************************************
	 * 5. 재승인 결과                                                    *
	 *                                                                   *
	 * 신거래번호 : $inipay->getResult('TID')                            *
	 * 결과코드 : $inipay->getResult('ResultCode') ("00"이면 재승인 성공)*
	 * 결과내용 : $inipay->getResult('ResultMsg') (결과에 대한 설명)     *
	 * 원거래 번호 : $inipay->getResult('PRTC_TID')                      *
	 * 최종결제 금액 : $inipay->getResult('PRTC_Remains')                *
	 * 부분취소 금액 : $inipay->getResult('PRTC_Price')                  *
	 * 부분취소,재승인 구분값 : $inipay->getResult('PRTC_Type')          *
	 *                          ("0" : 재승인, "1" : 부분취소)           *
	 * 부분취소 요청횟수 : $inipay->getResult('PRTC_Cnt')                *
	 *********************************************************************/

	if($inipay->getResult('ResultCode')=="00"){
		$PRTC_Remains = $inipay->getResult('PRTC_Remains');
		$co_cancel_price = $order[co_total_price] - $PRTC_Remains;

		if($order[co_total_price]==$co_cancel_price){
			$co_pay_step = "3";
			$add_sql = ", co_cancel_datetime=NOW()";
			$add_sql_campaign = ", cp_asign='1'";
		} else{
			$co_pay_step = "7";
		}

		sql_query("update nfor_campaign_order set co_cancel_price='$co_cancel_price', co_pay_step='$co_pay_step' $add_sql where co_id='$co_id'");
		sql_query("update nfor_campaign set cp_pay_step='$co_pay_step' $add_sql_campaign where cp_id='$order[co_cp_id]'");

		alert_close_refresh("주문취소 처리되었습니다");
	} else{
		alert("주문취소에 실패하였습니다".$inipay->getResult('ResultCode')."|".iconv("euc-kr","utf-8",$inipay->getResult('ResultMsg')));
	}

}

include_once $nfor[path]."/html_head.php";
?>
<link rel="stylesheet" href="/admin/nfor.css?time=<?=time()?>" type="text/css" />
<style>
body { background-color:#FFFFFF; }
.btn{border:none; font-weight:normal;}
</style>

<div style="padding:20px;">
<form name="fwrite" method="post" onsubmit="return fsubmit(this)">
<?=admin_hidden($order,"mode")?>
<?=admin_hidden($order,"co_id")?>

<table class="table row_tbl  margin0">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>캠페인코드</th> 
	<td><?=$order[co_cp_id]?></td>
</tr>
<tr>
	<th>캠페인명</th> 
	<td><?=$order[co_cp_subject]?></td>
</tr>
<tr>
	<th>결제금액</th> 
	<td><?=number_format($order[co_total_price])?>원</td>
</tr>
<tr>
	<th>취소된금액</th> 
	<td><?=number_format($order[co_cancel_price])?>원</td>
</tr>
<tr>
	<th>남아있는금액</th> 
	<td><?=number_format($order[co_total_price]-$order[co_cancel_price])?>원</td>
</tr>
<tr>
	<th>취소요청금액</th> 
	<td><?=admin_text($order,"price")?>원</td>
</tr>
</table>

<div class="bottom_btn">
	<div class="form-inline">
		<?=admin_submit("fsubmit_btn", "부분취소하기", "btn btn-lg btn-red")?>
	</div>
</div>

</form>
</div>
<SCRIPT LANGUAGE="JavaScript">
<!--
function fsubmit(f){
	if(!$('#price').val()){
		alert("취소요청금액을 입력해주세요");
		$('#price').focus();
        return false;
	}
	f.action = "<?=$form?>";
	return true;	    
}	
//-->
</SCRIPT>

<?php
include_once $nfor[path]."/html_tail.php";
?>