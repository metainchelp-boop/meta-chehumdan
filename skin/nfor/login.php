<?php
include_once $nfor['skin_path']."head.php";
?>

<style>
.login_wrap{padding: 20px 20px 40px 20px; background-color:#FFF;}

.keypad_btn { cursor:pointer; display:block; text-align:right; color:#999; font-size:12px; margin:10px 0px; }
.keypad_btn:after { background:url('<?=$nfor['skin_path']?>img/layout.png') no-repeat; background-size:320px auto; background-position: -100px -200px; width:10px; height:5px; margin-left:3px; display:inline-block; overflow:hidden; content:''; }

.keypad_btn.on { color:#ec3940; }
.keypad_btn.on:after { background:url('<?=$nfor['skin_path']?>img/layout.png') no-repeat ; background-size:320px auto; background-position: -150px -200px; width:10px; height:5px; margin-left:3px; display:inline-block; overflow:hidden; content:''; }

.keypad { display:none; text-align:center; margin-bottom:10px; }
.keypad img { width:95%; }

.auto_login_wrap {margin:20px  0px;}

.login_input { margin:0px 0px 10px 0px; }
.social_line{position: relative; text-align: center; margin: 6px 0 22px; color: #959da6; font-size: 12px}

.sns_list {margin: 20px auto 0; text-align: center; font-size: 0;}
.sns_list ul {list-style: none;padding: 0;  text-align: center; font-size: 0;}
.sns_list ul li{display: inline-block; font-size: 13px; text-align: center;  color: #8a8a8a;  text-decoration: none;  margin-left: 15px;  padding-left: 15px;}
.sns_list ul li:nth-child(1){margin-left: 0px;}
.sns_list. ul:after {content: ""; display: block;clear: both;}

.sns_btn_icon {display: inline-block;  width: 30px;  height: 30px;  text-indent: -99999px; margin-left: -15px; border-radius: 50%; background-repeat: no-repeat;background-position: center; background-size: 30px auto;}

.sns_btn_icon {width: 50px;  height: 50px;}
.sns_btn { position: relative; height: 36px;font-family: HelveticaNeue-Light,AppleSDGothicNeo-Light,"Malgun Gothic","\B9D1\C740 \ACE0\B515",sans-serif;}
.sns_btn_icon_kakao { background-color: #ffd400;   background-image: url(<?=$nfor['skin_path']?>img/sns_icon_type1_kakao.png);}
.sns_btn_kakao {color: #e6ad00;}
.sns_btn_icon_naver { background-color: #1ec800;   background-image: url(<?=$nfor['skin_path']?>img/sns_icon_type1_naver.png);}
.sns_btn_naver{color: #e6ad00;}
.sns_btn_icon_facebook{background-color: #405d9a;  background-image: url(<?=$nfor['skin_path']?>img/sns_icon_type1_facebook.png);}
.sns_btn_facebook {color: #32599d;}


.sns_btn_icon_google{background-color:#db3f2c; background-image:url('./skin/demo/img/sns_icon_type1_google.png');}
.sns_btn_google {color:#db3f2c;}

.sns_btn_icon_apple{background-color:#fff; border:solid 1px #333; background-image:url('./skin/nfor/img/sns_icon_type1_apple.png');}
.sns_btn_apple {color:#fff;}


.mb_common_btn { list-style:none; overflow:hidden; padding:0px; margin-top:20px;}
.mb_common_btn li { float:left; width:33.33%; text-align:left; }
.mb_common_btn li:nth-child(2) { text-align:center; }
.mb_common_btn li:last-child { text-align:right; }
.mb_common_btn li a { background-color:#fff; display:block; border:solid 1px #ccc; height:30px; line-height:30px; text-align:center; width:95%; font-size:12px; color:#555; display:inline-block;  } 
</style>


<div class="login_wrap">

	<a class="keypad_btn">한글자판보기</a>
	<div class="keypad"><img src="<?=$nfor['skin_path']?>img/keypad.png"></div>

	<div class="login_commnon login">

		<form name="login" id="login" method="post" autocomplete="off">
		<input type="hidden" name="mode" value="login">
		<?=admin_hidden($write,"url")?>
		<?=admin_text($write,$member_config['cf_mb_id_type'],"login_input","tabindex=\"1\" placeholder=\"아이디\"")?>
		<?=admin_password($write,"mb_password","login_input","tabindex=\"2\" placeholder=\"패스워드\"")?>
		<?=admin_submit("login_submit_btn", "로그인", "btn_submit")?>
		<div class="auto_login_wrap basic_chk"><?=admin_checkbox($checkbox,"auto_login","",$write['auto_login']?"checked":"","자동로그인설정")?></div>
		</form>

		<div class="social_line">SNS 계정으로 간편하게 회원가입/로그인 하세요.</div>

		<div class="sns_list">
			<ul>
				<?php if($api['api_naver_use']=="1"){ ?><li><a class="sns_btn_naver" href="naver_login.php"><span class="sns_btn_icon sns_btn_icon_naver"></span></a></li><?php } ?>
				<?php if($api['api_kakao_use']=="1"){ ?><li><a class="sns_btn_kakao" href="kakao_login.php"><span class="sns_btn_icon sns_btn_icon_kakao"></span></a></li><?php } ?>
				<?php if($api['api_facebook_use']=="1"){ ?><li><a class="sns_btn_facebook" href="facebook_login.php"><span class="sns_btn_icon sns_btn_icon_facebook"></span></a></li><?php } ?>
				<?php if($api['api_google_use']=="1"){ ?><li><a class="sns_btn_google" href="google_login.php"><span class="sns_btn_icon sns_btn_icon_google"></span></a></li><?php } ?>
				<?php if($api['api_apple_use']=="1"){ ?><li><a class="sns_btn_apple" href="apple_login.php"><span class="sns_btn_icon sns_btn_icon_apple"></span></a></li><?php } ?>
			</ul>
		</div>

		<ul class="mb_common_btn">
			<li><a href="find_id.php">아이디찾기</a></li>
			<li><a href="find_password.php">패스워드찾기</a></li>
			<li><a href="member_main.php">회원가입</a></li>
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

$(document).on("click",".keypad_btn",function(){
	if($(this).hasClass('on')){
		$(this).html("한글자판보기").removeClass('on');
		$(".keypad").hide();
	} else{
		$(this).html("한글자판닫기").addClass('on');
		$(".keypad").show();
	}
});
</script>

<?php
include_once $nfor['skin_path']."tail.php";
?>