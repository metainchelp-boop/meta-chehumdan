<form name="subscribe" id="subscribe">
<table width="517" height="392" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td background="<?=$nfor[skin_path]?>img/email.png" valign="top" align="center">
		
		<table border="0" cellspacing="0" cellpadding="0" align="center" style=" margin-top:20px; width:450px;">
		<tr>
			<td align="right">
			<a href="javascript:subscribe_popup('close')"><img src="<?=$nfor[skin_path]?>img/modal_close.png" alt="창닫기"></a>
			</td>
		</tr>
		</table>
		<div style="border:solid 1px #DCDCDC; backgriund-color:#FAFAFA;  width:380px; height:91px; margin:110px auto 0px;;" >
		<table  cellspacing="0" cellpadding="0" align="center" border="0" style="margin-top:20px;">
		<tr>
			<td ><input type="text" name="subscribe_popup_wr_email" id="subscribe_popup_wr_email" style="border:solid 1px #d32f2f;width:227px; height:37px; line-height:37px; font-size:12px;" placeholder="이메일을 입력해주세요"></td>
			<td ><a href="javascript:subscribe_popup_check()">&nbsp;<img src="<?=$nfor[skin_path]?>img/subscribe_input_03.png"></a></td>
		</tr>
		</table>
		</div>
	
		<table style="font-size:11px; margin-top:10px;  width:380px; color:#999999;">
		<tr>
			<td style="height:25px;"><input type="checkbox" name="subscribe_popup_agree_chk" id="subscribe_popup_agree_chk" value="1" checked> <label for="subscribe_popup_agree_chk">할인 정보 안내를 위한 이메일 수집 및 광고메일 수신에 동의합니다. </label></td>
		</tr>
		<tr>
			<td>※ 회원가입 수집 및 이용 목적 : 광고 메일 발송 및 마케팅 활용</td>
		</tr>
		<tr>
			<td>※ 수집항목 : 이메일</td>
		</tr>
		<tr> 
			<td>※ 보유 및 이용기간 : 광고메일 수신거부 시 및 회원의 경우 회원탈퇴 시</td>
		</tr>
		</table>

	</td>
</tr>
</table>
</form>
