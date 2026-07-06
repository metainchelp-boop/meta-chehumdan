<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.member_confirm_wrap { background-color:#FFF; padding:20px; min-height:400px; }
.member_confirm_wrap .title{ padding: 5px 20px 18px 0px;  font-weight: 700;  font-size: 15px;  line-height: 24px; color: #16181a;}
.member_confirm_wrap .sub_txt { margin-top:10px; margin-bottom: 28px; font-size: .752em; line-height: 16px;color: #959da6; letter-spacing: -.5px;  text-align: left;word-break: keep-all;}
#mc_submit_btn { background-color:#ff3478; color:#fff; margin-top:10px; font-size:16px; height:45px;  line-height:45px; font-weight:bold; padding:0; }
</style>

<div class="member_confirm_wrap">

	<b class="title">회원정보확인</b>
	<p class="sub_txt">정보를 안전하게 보호하기 위해 비밀번호를 다시 한번 입력해주세요</p>
	<form name="member_confirm" id="member_confirm" method="post" autocomplete="off">
		<?=admin_hidden($write,"mode")?>
		<?=admin_password($write,"mb_password","","placeholder=\"비밀번호\"")?>
		<?=admin_submit("mc_submit_btn", "확인")?>
	</form>

</div>

<script>
$(document).on("click","#mc_submit_btn",function(){
	$.ajax({
		type:"post",
		data :$("#member_confirm").serialize(),
		url:"member_confirm.php",
		success:function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
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