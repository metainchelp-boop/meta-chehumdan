<?php
include_once $nfor['skin_path']."head.php";
?>

<style>
.login_wrap { width:400px;margin:0px auto; overflow:hidden; padding:150px 0px;  font-family: 'notokr-demilight';}
.login_wrap  h2{}
.auto_login_wrap { margin:15px 0px; }
.guest_login { display:none; }
.login-divide {position:relative;width:100%;height:50px;padding-left:1px;border-bottom:1px solid #efefef; margin-bottom:10px;}
.login-divide-pannel {float:left;width:50%;margin-left:-1px;border:1px solid #efefef;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;}
.login-divide-pannel.on {position:relative;border-bottom-color:#fff;z-index:1;}
.login-divide-pannel > button {display:block;width:100%;height:50px;background:#f9f9f9;font-size:18px;color:#ccc;text-align:center;outline:0;filter:chroma(color=#000000); border:0px; font-family: 'notokr-demilight'; position:relative;}
.login-divide-pannel > button > span{display:block; padding-left:18px;}
.login-divide-pannel.on > button {border-bottom-color:#fff;background:#fff;  font-size:18px; color:#000;}
.login-divide-pannel.on > button > span{display:block; padding-left:18px;}
.login-divide-pannel.on > .reviewer:before{position:absolute; display:block; content:"";  clear:both; width:18px; height:18px; top:18px; left:55px; background:url(<?=$nfor['skin_path']?>img/review_on_ico.png) }
.login-divide-pannel .reviewer:before{position:absolute; display:block; content:"";  clear:both; width:18px; height:18px; top:18px; left:55px; background:url(<?=$nfor['skin_path']?>img/review_off_ico.png) }
.login-divide-pannel.on > .cooper:before{position:absolute; display:block; content:"";  clear:both; width:18px; height:18px; top:18px; left:55px; background:url(<?=$nfor['skin_path']?>img/cooper_on_ico.png) }
.login-divide-pannel .cooper:before{position:absolute; display:block; content:"";  clear:both; width:18px; height:18px; top:18px; left:55px; background:url(<?=$nfor['skin_path']?>img/cooper_off_ico.png) }


.btn_login{display:block;text-align:center;width:100%; height:45px; margin-top:10px; margin-bottom:10px;line-height:46px;border:1px solid #18a8f1; background: #18a8f1;color:#fff;font-size:14px; text-decoration:none;transition:color 0.2s, background 0.2s; }
.btn_login:hover{color:#18a8f1;background:#fff;}

.login_box{width: 400px; padding: 20px 0px 0px; background-color:#FFFFFF;  } 
.login_box .login_input{padding-left: 11px; margin-bottom:10px; width:100%; height:46px; color:#555;  font-size:14px; line-height:46px;border:1px solid #efefef; background-color:#FFF; color:#ccc; box-sizing: border-box; -webkit-box-sizing: border-box; }
.login_box .auto_login_wrap{position:relative;  font-size:14px; }
.login_box .auto_login_wrap .find_pass{position:absolute; right:0px; overflow:hidden; top:0px; font-size:14px;}
.login_box .auto_login_wrap .find_pass li{float:left; position:relative; }
.login_box .auto_login_wrap .find_pass li + li:before{position:absolute; display:block; content:""; width:1px; height:14px;background-color:#fff;  clear:both; top:2px; right:0px;}
.login_box .auto_login_wrap .find_pass li:before{position:absolute; display:block; content:""; width:1px; height:14px;background-color:#ccc;  clear:both; top:3px; right:-7px;}
.login_box .auto_login_wrap .find_pass li a{ display:block; padding:0px 0px 0px 15px;  font-family: 'notokr-demilight';} 
.login_box .sns_login a{display:block; border:solid 1px #efefef; height:46px; margin-bottom:5px; text-align:center;line-height:46px; font-size:16px; letter-spacing:-1px;}
.login_box .sns_login a:hover{color:#18a8f1; border:solid 1px #18a8f1;}
.login_box .sns_login .naver_login{position:relative; }
.login_box .sns_login .naver_login:before{position:absolute; display:block; content:""; top:13px; left:20px;width:22px; height:22px; background:url(<?=$nfor['skin_path']?>img/naver_login_icon.png)center no-repeat;}
.login_box .sns_login .inst_login{position:relative; }
.login_box .sns_login .inst_login:before{position:absolute; display:block; content:""; top:13px; left:20px;width:22px; height:22px; background:url(<?=$nfor['skin_path']?>img/insta_login_ico.png)center no-repeat;}
.login_box .sns_login .face_login{position:relative; }
.login_box .sns_login .face_login:before{position:absolute; display:block; content:""; top:13px; left:20px;width:22px; height:22px; background:url(<?=$nfor['skin_path']?>img/facebook_login_ico.png)center no-repeat;}

.login_box .sns_login .kakao_login{position:relative; }
.login_box .sns_login .kakao_login:before{position:absolute; display:block; content:""; top:13px; left:20px;width:22px; height:22px; background:url(<?=$nfor['skin_path']?>img/kakao_login_ico.png)center no-repeat;}

.login_box .sns_login .google_login{position:relative; }
.login_box .sns_login .google_login:before{position:absolute; display:block; content:""; top:13px; left:20px;width:22px; height:22px; background:url(<?=$nfor['skin_path']?>img/google_login_ico.png)center no-repeat;}

.login_box .sns_login .apple_login{position:relative; }
.login_box .sns_login .apple_login:before{position:absolute; display:block; content:""; top:13px; left:20px;width:22px; height:22px; background:url(<?=$nfor['skin_path']?>img/apple_login_ico.png)center no-repeat;  background-size:22px auto; }


.member_join{overflow:hidden; margin:20px 0px;}
.member_join li{ float:left;background-color:#fff; display:block; border:solid 1px #efefef; width:50%; ;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box; margin-left:-0px; height:90px;}
.member_join li + li{margin-left:-1px;}
.member_join li span{padding:25px 0px 25px 55px;display:block; font-size:16px; letter-spacing:-1px; line-height:18px;}
.member_join li .reviewer{position:relative;}
.member_join li .reviewer:before{position:absolute; display:block; content:"";  clear:both; width:18px; height:18px; top:35px; left:25px; background:url(<?=$nfor['skin_path']?>img/review_on_ico.png) }
.member_join li .cooper{position:relative;}
.member_join li .cooper:before{position:absolute; display:block; content:"";  clear:both; width:18px; height:18px; top:35px; left:25px; background:url(<?=$nfor['skin_path']?>img/cooper_on_ico.png);}
</style>

<div class="login_wrap">

	<ul class="login-divide">
		<li class="login-divide-pannel on"><button type="button" class="reviewer"><span>리뷰어</span></button></li>
		<li class="login-divide-pannel"><button type="button" class="cooper"><span>광고주</span></button></li>
	</ul> 

	<div class="login_box login_commnon member_login">
		<form name="login" id="login" method="post" autocomplete="off">
			<input type="hidden" name="mode" value="login">
			<?=admin_hidden($write,"url")?>
			<?=admin_text($write,$member_config['cf_mb_id_type'],"login_input","tabindex=\"1\" placeholder=\"아이디\"")?>
			<?=admin_password($write,"mb_password","login_input","tabindex=\"2\" placeholder=\"패스워드\"")?>
			<?=admin_submit("login_submit_btn", "로그인", "btn_login")?>
			
			<div class="auto_login_wrap">
				<div class="auto_login_wrap basic_chk"><label for='chkbox'><input type="checkbox" id="chkbox"><em></em>자동로그인</div>
				<ul class="find_pass">
					<li><a href="find_id.php">아이디찾기</a></li>
					<li><a href="find_password.php">패스워드찾기</a></li>
				</ul>
			</div>
			<div class="sns_login">
				<?php if($api['api_naver_use']=="1"){ ?><a href="naver_login.php" class="naver_login">네이버  간편 로그인</a><?php } ?>
				<?php if($api['api_facebook_use']=="1"){ ?><a href="facebook_login.php" class="face_login">페이스북  간편 로그인</a><?php } ?>
				<?php if($api['api_kakao_use']=="1"){ ?><a href="kakao_login.php" class="kakao_login">카카오  간편 로그인</a><?php } ?>
				<?php if($api['api_google_use']=="1"){ ?><a href="google_login.php" class="google_login">구글 간편 로그인</a><?php } ?>
				<?php if($api['api_apple_use']=="1"){ ?><a href="apple_login.php" class="apple_login">애플 간편 로그인</a><?php } ?>
			</div>		
		</form>
	</div>

	<div class="member_join">
		<ul>
		<li><a href="member_join.php" class="reviewer"><span>리뷰어<br>회원가입하기</span><b class="arrow_login"></b></a></li>
		<li><a href="member_join.php?mb_type=company" class="cooper"><span>광고주<br>회원가입하기</span><b class="arrow_login"></b></a></li>
		</ul>
	</div>

</div>



<script>
$(document).on("click","#login_submit_btn",function(){
	$.ajax({
		type:"post",
		data :$("#login").serialize(),
		url:"login.php",
		success:function(response){
			console.log(response);
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
				if(json["url"]){
					location.href = json["url"];
				}
			}
		}
	});
	event.preventDefault();
});

$(document).on("click",".login-divide-pannel",function(){
	$(".login-divide-pannel").removeClass('on');
	$(this).addClass('on');
	if($(this).index()=="1"){
		$("#url").val("/admin");
		$(".sns_login").hide();
	} else{
		$("#url").val("");
		$(".sns_login").show();
	}
});
</script>

<?php
include_once $nfor['skin_path']."tail.php";
?>