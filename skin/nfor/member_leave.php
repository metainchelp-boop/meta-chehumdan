<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.member_leave_wrap { padding:10px; background-color:#fff; }
#mb_password { margin-bottom:5px; }
#leave_submit_btn { display:block; width:100%; height:45px; line-height:45px; text-align:center; padding:0px; margin:0px; margin:20px 0px; font-size:16px; font-weight:bold; background-color:#ff3478; border:solid 1px #ff3478; color:#fff; }

.coution{padding:10px;}
.title {
    margin-top: 1px;
    font-weight: 700;
    font-size: 14px;
    color: #16181a;
}
.coution dl dt {
    margin-top: 19.5px;
    font-weight: 700;
    font-size: 14px;
    color: #6c7580;
}
.coution dl dd {
    margin-top: 3px;
    font-size: 13px;
    line-height: 21px;
    color: #6c7580;
}
</style>


<div class="member_leave_wrap">

	<div class="coution">
			<h3 class="title">회원 탈퇴 안내사항을 반드시 확인해 주세요.</h3>
			<dl>
				<dt>1. 회원탈퇴 시 처리내용</dt>
				<dd>
					<ul>
						<li>
							<p>(1)적립금: 잔여 금액은 소멸되며 환불되지 않습니다.</p>
						</li>
						<li>
							<p>(2) 구매 정보가 삭제됩니다.<br>단, 티켓 구매 특성상 유효기간이 남은 미사용 티켓 관련 정보는 삭제되지 아니하고 유효기간 만료일까지 보관됩니다.</p>
						</li>
						<li>
							<p>(3) 소비자보호에 관한 법률 제6조(거래기록의 보전 등) 및 동법 시행령 제6조에 의거,</p>
							<p class="asterisk">계약 또는 청약철회 등에 관한 기록은 5년,<br>대금결제 및 재화 등의 공급에 관한 기록은 5년,</p>
							<p class="asterisk">소비자의 불만 또는 분쟁처리에 관한 기록은 3년 동안<br>보관됩니다.</p>
							<p>동 개인정보는 법률에 의한 경우가 아니고서는 보유 되어지는 이외의 다른 목적으로는 이용되지 않습니다.</p>
						</li>
						<li>
							<p>(4) 회원 정보: 회원탈퇴 완료 시 당사 사이트 이용 권한이 삭제되며, 기존 구매 티켓에 대한 본인인증 필요성 등을 위해 회원가입에 따른 사용자정보는 6개월 동안 보관됩니다.</p>
						</li>
					</ul>
				</dd>
				<dt>2. 회원탈퇴 시 게시물 관리</dt>
				<dd>
					회원탈퇴 후 당사 사이트에 입력하신 게시물 및 댓글은 삭제되지 않으며, 회원정보 삭제로 인해 작성자 본인을 확인할 수 없으므로 게시물 편집 및 삭제 처리가 원천적으로 불가능합니다. 게시물 삭제를 원하시는 경우에는 먼저 해당 게시물을 삭제 하신 후, 탈퇴를 신청하시기 바랍니다.
				</dd>
				<dt>3. 회원탈퇴 후 재가입 규정</dt>
				<dd>
					탈퇴 회원이 재가입하더라도 기존의 적립금은 이미 소멸되었으므로 현재의 적립금에 양도되지 않습니다.
				</dd>
			</dl>
	</div>

	<br>

	<form name="member_leave" id="member_leave" method="post" autocomplete="off">
	<input type="hidden" name="mode" value="update">
		<?=admin_checkbox($write,"mb_agree","",""," 회원탈퇴 규정에 대한 사항에 동의합니다")?>
		<? if(!sns_id_check($member)){ ?>
		<?=admin_password($write,"mb_password","","placeholder=\"비밀번호\"")?>
		<? } ?>
		<?=admin_textarea($write,"mb_secession", "","placeholder=\"탈퇴사유\"")?>
		<?=admin_submit("leave_submit_btn", "회원탈퇴")?>
	</form>

</div>


<script>
$(document).on("click","#leave_submit_btn",function(){
	$.ajax({
		type:"post",
		data :$("#member_leave").serialize(),
		url:"member_leave.php",
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
include_once $nfor[skin_path]."tail.php";
?>