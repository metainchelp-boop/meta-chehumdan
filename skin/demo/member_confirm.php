<?php
include_once $nfor[skin_path]."mypage_head.php";
?>
<style>

.member_confirm { width:100%; margin:0px auto 0px; text-align:center; padding-top:100px;:}
.member_confirm .title {display:block; margin-top:20px; text-align:center;}
.member_confirm .h_txt{font-size:30px; margin:30px 0px 10px 0px;}
.member_confirm .m_txt{font-size:24px; margin:5px; color:#000;  font-weight: 300; }
.member_confirm .s_txt{font-size:18px; margin:0px; color:#666;}
.member_confirm input[type=password] {height:35px;width:335px;border:solid 1px #DCDCDC; padding-left:10px;}
.okbtn { display:block; margin-top:10px; margin-bottom:10px; width:350px; height:45px; font-size:1.1em; color:#fff;cursor: pointer; background: #747c8a;border:0px;  margin-left:0px !important;}

</style>
<div class="member_confirm">
	<div class="m_txt ">정보를 안전하게 보호하기위해 비밀번호를 다시 확인합니다.</div>
	<div class="s_txt ">회원님의 비밀번호가 타인에게 노출되지 않도록 주의하세요</div>
	<div class="title">
	<div style="border:solid 1px #DCDCDC; padding:25px;width:350px;margin:25px auto 120px; background-color:#FFFFFF;">
	<form name="member_confirm" id="member_confirm" method="post" autocomplete="off">
	<?=admin_hidden($write,"mode")?>
	<?=admin_password($write,"mb_password","","placeholder=\"비밀번호\"")?>
	<span class="btn_pack"><?=admin_submit("mc_submit_btn", "확인","btn_lg black okbtn")?></span>
</form>
	</div>
	</div>
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
include_once $nfor[skin_path]."mypage_tail.php";
?>