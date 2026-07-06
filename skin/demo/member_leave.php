<?php
include_once $nfor[skin_path]."mypage_head.php";
?>


<form name="member_leave" id="member_leave" method="post" autocomplete="off">
<input type="hidden" name="mode" value="update">


<div  class="board_write">
<table border="0" cellpadding="0" cellspacing="0" >
<colgroup>
	<col style="width:160px">
	<col>
</colgroup>
<tbody>
<? if(!sns_id_check($member)){ ?>
<tr>
	<th>비밀번호</th>
	<td><?=admin_password($write,"mb_password","","placeholder=\"비밀번호\"")?></td>
</tr>
<? } ?>
<tr>
	<th>탈퇴사유</th>
	<td><?=admin_textarea($write,"mb_secession", "","placeholder=\"탈퇴사유\"")?></td>
</tr>
<tr>
	<td colspan="2" class="agreememt_wrap" >
	<div class="agreememt"><?=agreement("member_leave")?></div>
	</td>
</tr>
<tr>
	<td colspan="2" class="cs_agree" style="position:relative; "><?=admin_checkbox($write,"mb_agree","",""," 회원탈퇴 규정에 대한 사항에 동의합니다")?> <a class="agree_view">탈퇴동의 약관보기</a></td>
</tr>
</table>
<div class="board_btn_zone"><span class="btn_pack"><?=admin_submit("leave_submit_btn", "회원탈퇴","btn_lg black")?></span></div>
</div>
</form>


<script>
$(document).on("click",".agree_view",function(){
	$(".agreememt_wrap").toggle();
});

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
include_once $nfor[skin_path]."mypage_tail.php";
?>