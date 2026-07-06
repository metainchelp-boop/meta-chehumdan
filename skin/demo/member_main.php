<?php
include_once $nfor[skin_path]."head.php";
?>
<style>
.member_main_wrap { width:600px;margin:0px auto; overflow:hidden; padding-top:100px;  padding-bottom:100px; font-family: 'notokr-demilight'; letter-spacing:-1px;  }
.member_main_wrap .title_b{font-size:45px;font-family: 'notokr-demilight';color:#000; text-align:center; margin-bottom:10px;}
.member_main_wrap .title_m{font-size:35px;font-family: 'notokr-demilight';color:#000; text-align:center; margin-top:30px;}
.member_main_wrap .title_s{font-size:14px;font-family: 'notokr-demilight';color:#000; text-align:center; margin-bottom:40px;}
.member_main_wrap .mamber_join_btn_wrap{overflow:hidden; margin-top:30px; border:solid 1px #dcdcdc}
.member_main_wrap .mamber_join_btn_wrap li{float:left; width:50%; text-align:center; padding:5px; box-sizing:border-box; -webkit-box-sizing:border-box; }
.member_main_wrap .mamber_join_btn_wrap li + li{float:left; border-left:solid 1px #dcdcdc;}
.member_main_wrap .mamber_join_btn_wrap .reviewer_btn{display:block; padding:50px 10px 10px; vertical-align: middle; text-align: center; line-height:18px; font-size:16px;}

.member_main_wrap .mamber_join_btn_wrap .coper_btn{display:block; padding:50px 10px 10px; vertical-align: middle; text-align: center; line-height:18px; font-size:16px; }

.member_main_wrap .mamber_join_btn_wrap .btn_gogo{background-color:#747c8a; color:#FFF; display:block; padding:20px 10px; font-size:14px; width:100%; margin-top:20px;  -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}
.member_main_wrap .mamber_join_btn_wrap .coper_btn img{display:block; margin:0px auto 10px;}
.member_main_wrap .mamber_join_btn_wrap .reviewer_btn img{display:block; margin:0px auto 10px;}
.member_main_wrap .mamber_join_btn_wrap .button{margin:20px auto ; width:60%;}
</style>

<div class="member_main_wrap">
	<div class="title_m">당신의 마케팅을 도와 주는 서비스 </div>
	<div class="title_s">본인에게 알맞은 유형을 선택하고, 서비스를 경험해보세요.</div>
	<div class="mamber_join_btn_wrap">
		<ul>
			<li>
				<a href="member_join.php" class="reviewer_btn"><img src="<?=$nfor[skin_path]?>img/review_ico.png"><span>리뷰어/ 개인회원<br> 회원가입하기</span> <a href="member_join.php" class="button"><span>개인회원가입</span></a></a>
			</li>
			<li>
				<a href="member_join.php?mb_type=company" class="coper_btn"><img src="<?=$nfor[skin_path]?>img/cooper_ico.png" ><span>광고주/기업회원<br> 회원가입하기</span> <a href="member_join.php?mb_type=company" class="button"><span>광고주회원가입</span></a></a>
			</li>
		</ul>
	</div>
</div>

<?php
include_once $nfor[skin_path]."tail.php";
?>