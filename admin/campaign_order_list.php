<?php
include_once "path.php";

if($mode=="cancel"){
	$order = sql_fetch("select * from nfor_campaign_order where co_id='$co_id'");
	if($order[co_payment_type]=="banking"){
		sql_query("update nfor_campaign_order set co_pay_step='3', co_cancel_datetime=NOW(), co_cancel_price=co_total_price where co_id='$co_id'");
		sql_query("update nfor_campaign set cp_pay_step='3', cp_asign='1' where cp_id='$order[co_cp_id]'");
		json_return("주문취소 처리되었습니다","ok");
	}
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
			json_return("환불계좌가 설정되지 않았습니다\n주문 상세페이지이동후 환불계좌를 등록해주세요","no_refund");
		}
	}
	require($nfor[pg_path]."/libs/INILib.php");
	$inipay = new INIpay50;
	$inipay->SetField("inipayhome", $nfor[pg_path]); // 이니페이 홈디렉터리(상점수정 필요)
	$inipay->SetField("debug", "true");        // 로그모드("true"로 설정하면 상세로그가 생성됨.)
	$inipay->SetField("mid", $order[co_pg_id]);                                 // 상점아이디
	$inipay->SetField("tid", $order[co_pg_tid]);                                 // 취소할 거래의 거래아이디
	$inipay->SetField("admin", "1111");                            
	$inipay->SetField("cancelmsg", "관리자임의취소");                           // 취소사유
	if($order[co_payment_type]=="vbanking"){ // 가상계좌 취소일경우
		$inipay->SetField("type", "refund"); // 고정 (절대 수정 불가)
		$inipay->SetField("pgn", $pgn);
		$inipay->SetField("racctnum", $refundacctnum); // 환불계좌번호(숫자만입력)    
		$inipay->SetField("rbankcode", $refundbankcode); // 환불계좌은행코드
		$inipay->SetField("racctname", $refundacctname); // 환불계좌주명
	} else{
		$inipay->SetField("type", "cancel"); // 고정 (절대 수정 불가)
	}
	if($code != ""){
		$inipay->SetField("cancelcode", $code);			//취소사유코드
	}
	$inipay->startAction();
	if($inipay->getResult('ResultCode')=="00"){
		sql_query("update nfor_campaign_order set co_pay_step='3', co_cancel_datetime=NOW(), co_cancel_price=co_total_price where co_id='$co_id'");
		sql_query("update nfor_campaign set cp_pay_step='3', cp_asign='1' where cp_id='$order[co_cp_id]'");
		json_return("주문취소 처리되었습니다","ok");
	} else{
		$msg = iconv("euc-kr","utf-8",$inipay->getResult('ResultMsg'))."_".$inipay->getResult('ResultCode');
		json_return("다음사유로실패하였습니다".$msg,"not");
	}
}

include_once "inc_campaign_order_head.php";

$sql_search = " where co_pay_step='1' ";

include_once "inc_campaign_order_tail.php";
include_once "head.php";
include_once "inc_campaign_order_search.php";
?>

<form name="flist" id="flist" method="post">
<?=admin_hidden($hidden,"ex_id")?>
<?=admin_get()?>
<table class="table row_tbl margin0">
<tr>
	<th><?=admin_checkbox($row,"chkall")?></th>
	<th>회원정보</th>
	<th>캠페인코드</th>
	<th>캠페인명</th>
	<th>캠페인 형태</th> 
	<th>제공내역 형태</th> 
	<th>모집인원</th>
	<th>캠페인등록비</th>
	<th>포인트지급비</th>
	<th>합산결제금액</th>
	<th>주문일자</th>
	<th>결제일자</th>
	<th>결제방법</th>
	<th>입금계좌/입금자명</th>
	<th>결제상태</th>
	<?php if($is_admin){ ?>
	<th>상태변경</th>
	<?php } ?>
</tr>
<?php
for($i=0; $row=sql_fetch_array($result); $i++){	
	$row["chk[]"] = $i;
	$row["{$id}[{$i}]"] = $row[$id];

	$msg = addslashes($row[co_cp_subject])."(".$row[co_cp_id].")";
?>
<tr>
	<td><?=admin_checkbox($row,"chk[]","chk")?><?=admin_hidden($row,"{$id}[{$i}]")?></td>
	<td><a href="javascript:member('<?=$row[co_mb_no]?>')"><?=campaign_member_info($row)?></a></td>
	<td><?=$row[co_cp_id]?></td>
	<td><?=$row[co_cp_subject]?></td>
	<td><?=admin_echo($row,"co_cp_type")?></td>
	<td><?=admin_echo($row,"co_cp_reward_type")?></td>	
	<td><?=number_format($row[co_cp_recruit])?>명</td>
	<td><?=number_format($row[co_campaign_price])?>원</td>
	<td><?=number_format($row[co_point_price])?>원</td>
	<td><?=number_format($row[co_total_price])?>원</td>
	<td><?=substr($row[co_datetime],0,10)?></td>
	<td><?=substr($row[co_pay_datetime],0,10)?></td>
	<td><?=admin_echo($row,"co_payment_type")?></td>
	<td><?=$row[co_bank_number]?><br><?=$row[co_bank_name]?></td>
	<td><?=admin_echo($row,"co_pay_step")?></td>
	<?php if($is_admin){ ?>
	<td>
		<? if($row[co_pay_step]=="4" and $row[co_payment_type]=="banking"){ ?>
		<?=admin_a("asign_btn", "주문완료처리", "btn btn-white btn-sm nfor_button", "data-confirm=\"주문완료처리대상\n$msg\n\n확인 버튼을 누르실경우 주문서 상태가 주문완료상태로 변경됩니다 그래도 진행하시겠습니까?\" data-data=\"mode=asign&{$id}={$row[$id]}\"")?>
		<? } ?>

		<? if($row[co_pay_step]=="1"){ ?>
		<?=admin_a("cancel_btn", "주문취소처리", "btn btn-white btn-sm nfor_button", "data-confirm=\"주문취소처리대상\n$msg\n\n확인 버튼을 누르실경우 결제가 취소 됩니다 그래도 진행하시겠습니까?\" data-data=\"mode=cancel&{$id}={$row[$id]}\"")?>
		<? } ?>

		<?php if($row[co_pay_step]=="1" or $row[co_pay_step]=="7"){ ?>
			<?php if($row[co_total_price]<>$row[co_cancel_price]){ ?>
			<?=admin_a("ea_cancel_btn", "부분취소처리", "btn btn-white btn-sm nfor_window", "data-url=\"pg_inipay_cancel.php\" data-data=\"{$id}={$row[$id]}\"")?>
			<?php } ?>
		<?php } ?>
	</td>
	<?php } ?>
</tr>
<?php
}
$pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");
?>
</table>

<div class="table_btn"><?=$pagelist?></div>

</form>

<script>
$(document).on("click", ".nfor_window", function(){
	var data_str = $(this).data("data");
	var url_str = $(this).data("url");
	window.open(url_str+"?"+data_str, "nfor_window", "left=50,top=50,width=540,height=600,scrollbars=1");
});
</script>

<?php
include_once "tail.php";
?>