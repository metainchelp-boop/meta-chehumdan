<?php
include_once "path.php";

$write = sql_fetch("select * from nfor_campaign where cp_id='$cp_id'");

$admin[cp_type] = $nfor[cp_type];
$admin[payment_type] = array( "card" => "신용카드", "iche"=>"계좌이체", "vbanking"=>"가상계좌");  //"banking"=>"무통장입금",

$admin[cp_reward_type] = array("전체","제품/서비스","포인트","포인트 + 제품/서비스");

$write[payment_type] = "banking";


$total_price = $config[cf_campaign_price]*$write[cp_recruit];
if($write[cp_point]){
	$total_price += $write[cp_point]*$write[cp_recruit];
}

if($mode=="update"){

	if(!$payment_type) json_return("결제수단을 선택해주세요","payment_type");

	$return[pay_price] = $total_price;
	
	if($payment_type=="banking"){
		if(!$bank_number) json_return("입금하실 계좌를 선택해주세요","bank_number");
		if(!$bank_name)	json_return("입금자명을 입력해주세요","bank_name");
		
		$bank_number_exp = explode(" ",$bank_number);
		$bank_user = $bank_number_exp[2]; // 예금주명
		$bank_date = date("Y-m-d",strtotime("+1 day"));

		$add_sql = ", co_pay_step='4', co_bank_number='$bank_number', co_bank_user='$bank_user', co_bank_name='$bank_name', co_bank_expire='$bank_date'";
		
		$campaign = sql_fetch("select * from nfor_campaign where cp_id='$cp_id'");
		$co_cp_subject = addslashes($campaign[cp_subject]);

		$co_campaign_price = $config[cf_campaign_price]*$campaign[cp_recruit];
		$co_point_price = $campaign[cp_point]*$campaign[cp_recruit];
		$co_total_price = $co_campaign_price+$co_point_price;

		$co_payment_type = "banking";
		
		sql_query("insert nfor_campaign_order set co_payment_type='$co_payment_type', co_supply_no='$campaign[cp_supply_no]', co_md_no='$campaign[cp_md_no]', co_mb_no='$member[mb_no]', co_total_price='$co_total_price', co_point_price='$co_point_price', co_campaign_price='$co_campaign_price', co_cp_type='$campaign[cp_type]', co_cp_reward_type='$campaign[cp_reward_type]', co_cp_recruit='$campaign[cp_recruit]', co_cf_campaign_price='$config[cf_campaign_price]', co_cp_point='$campaign[cp_point]', co_mb_black='$member[mb_black]', co_mb_id='$member[mb_id]', co_mb_name='$member[mb_name]', co_mb_nick='$member[mb_nick]', co_mb_hp='$member[mb_hp]', co_mb_email='$member[mb_email]', co_cp_subject='$co_cp_subject', co_cp_id='$cp_id', co_datetime=NOW() $add_sql");
		
		sql_query("update nfor_campaign set cp_pay_step='4' where cp_id='$cp_id'");
	}

	/* 이니시스설정 */
	if($nfor[pg_type]=="inipay" and ($payment_type=="card" or $payment_type=="iche" or $payment_type=="vbanking" or $payment_type=="hp")){


		if($payment_type=="vbanking"){
			$pg_id = $nfor[pg_id_ies];
			$signKey = $nfor[pg_signkey_ies]; // 가맹점에 제공된 웹 표준 사인키(가맹점 수정후 고정)
		} else{
			$pg_id = $nfor[pg_id];
			$signKey = $nfor[pg_signkey]; // 가맹점에 제공된 웹 표준 사인키(가맹점 수정후 고정)
		}

		$return[pg_id] = $pg_id;

		require_once($nfor[pg_path]."/libs/INIStdPayUtil.php");
		$SignatureUtil = new INIStdPayUtil();
		$timestamp = $SignatureUtil->getTimestamp();   // util에 의해서 자동생성
		$params = array(
			"oid" => $cp_id,
			"price" => $total_price,
			"timestamp" => $timestamp
		);
		$sign = $SignatureUtil->makeSignature($params, "sha256");
		$return[timestamp] = $timestamp;
		$return[signature] = $sign;
		$mKey = $SignatureUtil->makeHash($signKey, "sha256");
		$return[mKey] = $mKey;

	}
	/* 이니시스설정 */


	$return[payment_type] = $payment_type;

	json_return("주문서가 저장되었습니다","ok");
}

$que_banking = sql_query("select * from nfor_campaign_banking where bnk_use='1' order by bnk_rank asc");
while($data = sql_fetch_array($que_banking)){
	$admin[bank_number]["$data[bnk_bank] $data[bnk_number] $data[bnk_name]"] = "$data[bnk_bank] $data[bnk_number] $data[bnk_name]";
}

include_once "head.php";
?>

<form name="fcampaign_payment" id="fcampaign_payment" method="post" onsubmit="return false">
<?=admin_hidden($write,"mode")?>
<?=admin_hidden($write,"cp_id")?>



<table class="table cols_tbl margin0">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>캠페인명</th> 
	<td>
		<?=$write[cp_subject]?>
	</td>
</tr>
<tr>
	<th>캠페인 형태</th> 
	<td>
		<?=admin_echo($write,"cp_type")?>
	</td>
</tr>
<tr>
	<th>제공내역 형태</th> 
	<td>
		<?=admin_echo($write,"cp_reward_type")?>
	</td>
</tr>
<tr>
	<th>모집인원</th> 
	<td>
		<?=number_format($write[cp_recruit])?>명
	</td>
</tr>
<tr>
	<th>캠페인등록 비용</th> 
	<td>
		<?=number_format($config[cf_campaign_price]*$write[cp_recruit])?>원(캠페인 등록금액 <?=number_format($config[cf_campaign_price])?>원 x 모집인원 <?=number_format($write[cp_recruit])?>명)
	</td>
</tr>
<?php if($write[cp_point]){ ?>
<tr>
	<th>사용자 지급비용</th> 
	<td><?=number_format($write[cp_point]*$write[cp_recruit])?>원(사용자 지급비용 <?=number_format($write[cp_point])?>원 x 모집인원 <?=number_format($write[cp_recruit])?>명)</td>
</tr>
<?php } ?>
<tr>
	<th>합산 결제금액</th> 
	<td><?=number_format($total_price)?>원</td>
</tr>
<tr>
	<th>결제수단</th> 
	<td>
	<?=admin_radio($write,"payment_type","payment_type","","0")?>
	</td>
</tr>
<tr id="payment_banking" class="payment_wrap" style="display:none;">
	<th>무통장 입금정보</th>
	<td>
		<div class="form-inline">
		입금은행 <?=admin_select($write,"bank_number","","","0")?> 
		입금자명 <?=admin_text($write,"bank_name")?>
		</div>
	</td>
</tr>
</table>

<div class="bottom_btn">
	<div class="form-inline">
		<?=admin_button("payment_btn", "결제하기", "btn btn-lg btn-red")?>
	</div>
</div>

</form>



<script>

$(document).on("click","#payment_btn",function(){
	var payment_type = $('.payment_type:checked').val();
	if(payment_type=="banking"){	
		$("#payment_btn").hide();
	}
	$.ajax({ 
		type : "post"
		, url : "campaign_payment.php"
		, cache : false  
		, data : $("#fcampaign_payment").serialize() 
		, success : function(response){			
			console.log(response);
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				if(json["payment_type"]=="banking"){
					location.href = "campaign_order_wait_list.php";
				} else{
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


$(document).on("click",".payment_type",function(){
	var payment_type = $('.payment_type:checked').val();
	$(".payment_wrap").hide();
	$("#payment_"+payment_type).show();
});

</script>

<?php
include_once "pg_".$nfor[pg_type].".php";
include_once "tail.php";
?>