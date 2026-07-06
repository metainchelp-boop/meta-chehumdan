<?
if(basename($PHP_SELF)=="company.php"){
?>
<? } else { ?>
<style>
.mypage_title{width:248px; float:left;}

.mypage_title .left_menu .txt{height:150px; line-height:150px; text-align:center; font-size:26px; }
.mypage_title .left_menu .bar{border-top: solid 3px #ff0000; line-height:40px; display:inline-block;}
.mypage_title .left_menu{ width:230px; border:solid 1px #efefef; background-color:#FFF;}
.mypage_title .left_menu li{ font-size:13px; padding: 5px 20px; }
.mypage_title .left_menu li .tab{display:block; line-height:25px; color:#555;}
.mypage_title .left_menu li .hit{display:block; line-height:25px; color:#ff0000;}
.mypage_title .left_menu .left_tit{padding: 10px 15px; border-bottom:solid 1px #efefef; border-top:solid 1px #efefef; font-size:16px;}
.mypage_title .left_menu .left_box{padding: 28px  20px;}
.mypage_title .bannerbox{width:190px;border:solid 1px #efefef; background-color:#FFF; margin-top:15px; padding:20px; height:200px;}
.mypage_title .bannerbox span{display:block}
.mypage_title .bannerbox .t1{font-size:19px; margin-bottom:15px; }
.mypage_title .bannerbox .t2{ font-size:12px; color:#555; letter-spacing:-1px; }
.mypage_title .bannerbox .tel{font-family:"Rubik"; font-size:35px; margin-top:10px; margin-bottom:10px;}
.mypage_title .customer_banner1{margin-top:15px;}
</style>

<div class="mypage_title">
	


<div class="left_menu">
		<ul >
		<li class="txt"><span class="bar fotr">이용정보</span></li>
		<li class="left_tit fotr">사이트 이용관련</li>
		<li class="left_box">
		<a href="company.php" <?=basename($PHP_SELF)=="company.php"?"class='tab hit '":"class='tab'"?>>회사소개</a>
		<a href="agreement.php?code=privacy" <?=$code=="privacy"?"class='tab hit '":"class='tab'"?>>개인정보취급방침</a>
		<a href="agreement.php?code=agreement"<?=$code=="agreement"?"class='tab hit '":"class='tab'"?>>이용약관</a>
		<a href="agreement.php?code=safe" <?=$code=="safe"?"class='tab hit '":"class='tab'"?>>청소년보호정책</a>
		<a href="guide.php" <?=basename($PHP_SELF)=="guide.php"?"class='tab hit '":"class='tab'"?>>이용안내</a>
		</li>
	</ul>
</div>


<div class="bannerbox">
<span class="t1 fotr">솔루션 및 홈페이지<br> <b style="color:#ff9900; font-weight:normal;">제작문의 및 상담</b></span>
<span class="t2">전화주시면 전문 상담원이 <br>친절하게 상담해드립니다.</span>
<span class="tel">1899-0320</span>
 <span class="t2 btn_pack"><a href="mailto:nfor@nate.com" class="btn_md white">nfor@nate.com 메일 보내기</a></span>
</div>
<div class="customer_banner1">
<a href="/customer_list.php"><img alt="" src="/skin/demo/img/cu_banner.png"></a>
</div>

</div>
<? } ?>