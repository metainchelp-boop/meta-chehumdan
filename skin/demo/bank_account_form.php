<?php
include_once $nfor[skin_path]."mypage_head.php";
?>


<form name="bank_account" id="bank_account" method="post" autocomplete="off">
<input type="hidden" name="mode" value="update">

<table cellpadding="0" cellspacing="0" border="0" class="tb_form">
<colgroup>
	<col width="160">
	<col width="*">
</colgroup>
<tr>
	<th>예금주</th>
	<td><?=admin_text($write,"mb_name",""," readonly placeholder=\"예금주\"")?></td>
</tr>
<tr>
	<th>은행선택</th>
	<td>
		<?=admin_select($write,"mb_bank_name","","","0")?>
	</td>
</tr>
<tr>
	<th>계좌번호</th>
	<td><?=admin_text($write,"mb_bank_account",""," placeholder=\"계좌번호\"")?></td>
</tr>

<tr>
	<td colspan="2" class="agreememt_wrap">
	<div class="agreememt"><?=agreement("bank_account")?></div>
	</td>
</tr>
<tr>
	<td colspan="2" class="cp_agree"><?=admin_checkbox($write,"mb_bank_agree","",$member[mb_bank_agree]?"checked":"","개인정보 수집, 이용에 동의합니다")?>
	<a class="agree_view">수집동의 약관보기</a></td>
</tr>
</table>

<div class="coution">
	<p> ※ 계좌이체 또는 무통장입금 결제 후 주문 취소가 발생할 경우 등록된 무통장 계좌로 환불해드립니다.</p>
	<p> ※ 환불계좌는 반드시 본인 명의의 계좌만 설정 가능합니다</p>
</div>

<div class="bottom_btn"><span class="btn_pack"><?=admin_submit("ba_submit_btn", "등록하기","btn_lg black")?></span></div>

</form>

<script>
$(document).on("click",".agree_view",function(){
	$(".agreememt_wrap").toggle();
});

$(document).on("click","#ba_submit_btn",function(){
	$.ajax({
		type:"post",
		data :$("#bank_account").serialize(),
		url:"bank_account_form.php",
		success:function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				if(json["msg"]){
					alert(json["msg"]);
				}
				if(json["url"]){
					location.href = json["url"];
				}
			} else{
				if(json["msg"]){
					alert(json["msg"]);
				}
				if(json["result"]){
					$("#"+json["result"]).focus();
				}
			}
		}
	});
	event.preventDefault();
});
</script>

<?php
include_once $nfor[skin_path]."mypage_tail.php";
?>