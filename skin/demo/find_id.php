<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.findid-divide {position:relative;width:100%;height:43px;padding-left:1px;border-bottom:1px solid #d9dbe2; margin-bottom:10px;}
.findid-divide-pannel {float:left;width:50%;margin-left:-1px;border:1px solid #d9dbe2;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;}
.findid-divide-pannel.on {position:relative;border-bottom-color:#fff;z-index:1;}
.findid-divide-pannel>a{display:block;width:100%;height:42px;line-height:42px;background:#f9f9f9;font-size:14px;color:#333;text-align:center;outline:0;filter:chroma(color=#000000); border:0px;}
.findid-divide-pannel.on>a { font-weight:normal;border-bottom-color:#fff;background:#fff;}

.findid_wrap{width:560px;padding: 40px 38px 54px; margin:40px auto; border: 1px solid #e0e0e0; background: #ffffff;}
.findid_wrap .find_tit{font-size:24px;display:block; margin:0px 0px 20px 0px;font-weight:200}
.findid_wrap .find_tit2{font-size:16px;display:block; margin:20px 0px 0px 0px;}
.findid_wrap .find_tit3{font-size:12px;display:block; margin:5px 0px 20px 0px;color:#999;}
.findid_wrap .back{margin:20px 0px;}
.findid_wrap .back span{width: 100px; margin-top: 6px; float: left; font-size:16px; line-height:56px;color:#999; font-weight:normal;}
.findid_wrap .back input{ width:100%; padding-left:11px;margin-bottom:6px; ;height:56px;color:#aaa; font-size:14px; line-height:56px;border:1px solid #d7d7d7; background-color:#ffffff;}
.findid_wrap .back input:hover{color:#ff5000;border:1px solid #ff5000; background-color:#ffffff;}


.findid_wrap .find_btn{display:block;text-align:center;width:100%; height:56px; margin-top:10px; margin-bottom:10px;line-height:56px;border:1px solid #333;background:#333;color:#fff;font-size:14px; text-decoration:none;transition:color 0.2s, background 0.2s;   font-weight:normal;}
.findid_wrap .find_btn:hover{color:#333;background:#fff;}

.findid_wrap .back2{display:block; border:solid 1px #DCDCDC; background-color:#FAFAFA; padding:20px; margin-top:20px;}
.findid_wrap .back2 span{display:block;font-size:11px; color:#767676;line-height:18px;}
</style>


<div class="findid_wrap">

	<div class="find_tit">아이디찾기</div>

	<ul class="findid-divide">
		<li class="findid-divide-pannel on"><a href="find_id.php" >아이디찾기</a></li>
		<li class="findid-divide-pannel"><a href="find_password.php" >비밀번호 찾기</a></li>
	</ul>

	<form name="find_id_hp" id="find_id_hp" method="post" autocomplete="off">
	<input type="hidden" name="mode" value="find_id_hp">

		<div class="find_tit2">휴대전화로 찾기</div>
		<div class="find_tit3">가입시 입력한 휴대폰번호와 이름을 입력해 주세요</div>

		<div class="back">
			<span>이름</span>
			<?=admin_text($write,"mb_name","","placeholder=\"이름\"")?>
			<span>휴대폰번호</span>
			<?=admin_text($write,"mb_hp","","placeholder=\"휴대폰번호\"")?>
		</div>

		<?=admin_submit("hp_submit_btn", "휴대폰번호로 찾기", "find_btn")?>

	</form>

	<? if($member_config[cf_mb_id_type] == "mb_id"){ ?>
	<form name="find_id_email" id="find_id_email" method="post" autocomplete="off">
	<input type="hidden" name="mode" value="find_id_email">

		<div class="find_tit2 ">이메일로 찾기</div>
		<div class="find_tit3 ">가입 당시 입력한 이메일 주소를 통해 아이디를 찾을 수 있습니다.</div>

		<div class="back">
			<span>이름</span>	
			<?=admin_text($write,"mb_name","","placeholder=\"이름\"")?>
			<span>이메일</span>
			<?=admin_text($write,"mb_email","","placeholder=\"이메일\"")?>
		</div>

		<?=admin_submit("email_submit_btn", "이메일로 찾기", "find_btn")?>

	</form>
	<? } ?>

	<div class="back2">
		<span>※ 본인 인증시 제공되는 정보는 인증 이외의 용도로 이용 또는 저장하지 않습니다.</span>
		<span>※ 아이디가 기억나지 않으면 '아이디 찾기'를 이용하거나고객센터(<?=$config[cf_tel]?>)로 문의해주세요.</span>
	</div>

</div>


<script>
$(document).on("click","#hp_submit_btn",function(){
	$.ajax({
		type:"post",
		data :$("#find_id_hp").serialize(),
		url:"find_id.php",
		success:function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				if(json["msg"]){
					alert(json["msg"]);
				}
			} else{
				if(json["msg"]){
					alert(json["msg"]);
				}
				if(json["result"]){
					$("#find_id_hp #"+json["result"]).focus();
				}
			}
		}
	});
	event.preventDefault();
});
$(document).on("click","#email_submit_btn",function(){
	$.ajax({
		type:"post",
		data :$("#find_id_email").serialize(),
		url:"find_id.php",
		success:function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				if(json["msg"]){
					alert(json["msg"]);
				}
			} else{
				if(json["msg"]){
					alert(json["msg"]);
				}
				if(json["result"]){
					$("#find_id_email #"+json["result"]).focus();
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