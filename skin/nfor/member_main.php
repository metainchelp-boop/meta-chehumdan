<?php
include_once $nfor[skin_path]."head.php";
?>
<style>
.mem_txt{font-size:16px;color:#000; font-weight:300;letter-spacing:-2px; text-align:center; margin:50px 0px 20px;}
.mem_txt .mem_txt2{font-size:20px; display:block;} 
.mem_txt .mem_txt2 b{font-weight:300; letter-spacing:-3px;}
.mem_main_btn{overflow:hidden; margin:0px auto; padding:30px; cursor:pointer;}
.mem_main_btn .login_box{float:left; display:block;width:50%;  padding: 0px 30px;  box-sizing:border-box; -webkit-box-sizing:border-box;}
.mem_main_btn .login_box + .login_box{float:right}
.mem_main_btn .login_box .icon_img1{display:block; width:80px; height:80px; background:url(/skin/demo/img/icon_img1.png); margin:0px auto; opacity:0.4; }
.mem_main_btn .login_box .icon_img2{display:block; width:80px; height:80px; background:url(/skin/demo/img/icon_img2.png); margin:0px auto; opacity:0.4; }
.mem_main_btn .login_box span{display:block; text-align:center; letter-spacing:-1.5px;}
.mem_main_btn .login_box .b_txt{font-size:14px; margin:10px 0px; }
.mem_main_btn .login_box .s_txt{font-size:11px; display:none;}
</style>

<div class="member_main_wrap">

	<div class="title_m">당신의 마케팅을 도와 주는 서비스 </div>
	<div class="title_s">본인에게 알맞은 유형을 선택하고, 서비스를 경험해보세요.</div>
	<div class="mamber_join_btn_wrap">
		<ul>
			<li>
				<a href="member_join.php"><div class="reviewer_btn"><img src="<?=$nfor[skin_path]?>img/review_ico.png"><a class="button" href="member_join.php"><span>개인회원가입</span></a></div></a>
			</li>
			<li>
				<a href="member_join.php?mb_type=company"><div class="coper_btn"><img src="<?=$nfor[skin_path]?>img/cooper_ico.png" ><a class="button" href="member_join.php?mb_type=company"><span>광고주회원가입</span></a></div></a>
			</li>
		</ul>
	</div>

</div>

<?php
include_once $nfor[skin_path]."tail.php";
?>