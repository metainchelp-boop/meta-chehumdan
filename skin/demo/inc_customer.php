<style>
.cus_leftmenu{ float:left; width:248px; }
.cus_leftmenu .main_txt{font-size:23px; display:block; }
.cus_leftmenu .sub_txt{display:block; font-size:14px; color:#000;}
.cus_leftmenu ul{margin-top:20px;}
.cus_leftmenu li {height:53px; line-height:53px;background-color:#FFFFFF; border-bottom:solid 1px #efefef; border-top:solid 1px #efefef; position:relative; }
.cus_leftmenu li + li {height:53px; line-height:53px;background-color:#FFFFFF; border-bottom:solid 1px #efefef; border-top:solid 0px #efefef;}

.cus_leftmenu li:hover { color:#FFFFFF;}
.cus_leftmenu li a{ display:block; width:100%; padding-left:10px; color:#000; font-size:15px; letter-spacing:-1px;}
.cus_leftmenu li a:hover{ color:#18a8f1; background-color:#fafafa;}
.cus_leftmenu li.on{background-color:#efefef; color:#18a8f1; }
.cus_leftmenu .on a{color:#18a8f1;}
.cus_num{position: absolute; right: 20px; bottom: 0px; font-family:Montserrat;letter-spacing: -0.02em !important; color:#000;}
.cus_leftmenu li.on .cus_num{position: absolute; right: 20px; bottom: 0px; font-family:Montserrat;letter-spacing: -0.02em !important; color:#18a8f1;}
.cus_leftmenu li a:hover .cus_num{position: absolute; right: 20px; bottom: 0px; font-family:Montserrat;letter-spacing: -0.02em !important; color:#18a8f1;}
</style>
<div class="cus_leftmenu" >

	<?php
	//include_once $nfor[skin_path]."inc_photo.php";
	?>

	<span class="main_txt point_color4">고객센터</span>
	<span class="sub_txt">무엇을 도와드릴까요?</span>

	<ul >
		<li <?=basename($PHP_SELF)=="faq.php"?"class='on'":""?>><a href="faq.php">자주묻는질문</a></li>
		<li <?=basename($PHP_SELF)=="help_form.php"?"class='on'":""?>><a href="help_form.php">1:1문의</a></li>
		<li <?=$code=="agreement"?"class='on'":""?>><a href="agreement.php?code=agreement">이용약관</a></li>
		<li <?=$code=="privacy"?"class='on'":""?>><a href="agreement.php?code=privacy">개인정보처리방침</a></li>
		<li <?=$code=="safe"?"class='on'":""?>><a href="agreement.php?code=safe">청소년보호정책</a></li>
		<li <?=basename($PHP_SELF)=="notice_list.php" || basename($PHP_SELF)=="notice_view.php"?"class='on'":""?>><a href="notice_list.php"">공지사항</a></li>
		<li <?=basename($PHP_SELF)=="cooperation_form.php"?"class='on'":""?>><a href="cooperation_form.php">제휴문의</a></li>
	</ul>

</div>