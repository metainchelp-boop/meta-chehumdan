<?php
include_once $nfor[skin_path]."cus_head.php";
?>
<style>
.use_wrap{border:solid 1px #efefef; background-color:#FFF; min-height: 45px;padding:25px 20px; box-sizing:border-box; -webkit-box-sizing:border-box; min-height:1000px;}
.use_wrap .title{font-size:26px;}
.use_wrap .inner{overflow:hidden;}
.use_wrap .inner .fl{float:left; width:483px; margin-left:15px;}
.use_wrap .inner .fl .txt1{ display:block; font-size:33px; letter-spacing:-1px; margin-top:30px;}
.use_wrap .inner .fl .txt2{ display:block; font-size:12px; line-height:21px; letter-spacing:-1px; margin-top:15px; color:#555;}
.use_wrap .inner .fr{float:right; margin-top:30px}
.use_wrap .inner .fr ul{overflow:hidden;}
.use_wrap .inner .fr ul li{float:left;}
.use_wrap .inner .fr ul li span{display:block}
.use_wrap .inner .fr ul li .tel{font-family:"Rubik"; font-size:35px; margin-top:10px; margin-bottom:10px;}
.use_wrap .inner .fr ul li .txt1{font-size:12px; color:#555;}
.use_wrap .inner .fr ul li .mar{margin:20px 0px 10px 10px;}
.use_wrap .inner .fr .btn{width:196px; height:36px; text-align:center; font-size:12px; line-height:36px; display:inline-block; margin-top:10px;}
.use_wrap .inner .fr .col1{color:#FFF; background-color:#ff0000; border:solid 1px #ff0000;}
.use_wrap .inner .fr .col1:hover{color:#ff0000; background-color:#FFF; border:solid 1px #ff0000;}
.use_wrap .inner .fr .col2{color:#FFF; background-color:#2b2b2b; border:solid 1px #2b2b2b;}
.use_wrap .inner .fr .col2:hover{color:#2b2b2b; background-color:#FFF; border:solid 1px #2b2b2b;}

.use_wrap .use_box{overflow:hidden; margin-top:50px;}
.use_wrap .use_box li{float:left; margin-left:15px;}
.use_wrap .use_box li img{display:block; }
.use_wrap .use_box li span{font-size:12px; display:block; text-align:center; color:#555; margin-top:10px;}
</style>
<div class="use_wrap">
	<div class="title fotr"><?=$nfor[title]?></div>
	<div class="inner">
		<div class="fl"> 
			<span class="txt1 fotr">솔루션 구매 어렵지 않아요!!</span>
			<span class="txt2">솔루션에대한 적헙한 견적을 제시합니다.<br>방문상담이나 전화주시면 전문상담원이 적합한 견적을 제시합니다.<br>견적에 관한 궁금하신점이나 문의사항 있으시면  전화나 온라인 상담 신청하세요</span>
		</div>
		<div class="fr">
			<ul>
				<li><span class="txt1">대표번호</span><span class="tel">1899-0320</span></li>
				<li>
					<span class="txt1 mar">디자인관련 상담  : 070-4774-6012<br>프로그램관련상담 : 070-4774-6011<br>FAX  : 070-7815-7050
				</li>
			</ul>
			<a class="btn col1" href="solution_request.php">솔루션 온라인 신청문의</a><a class="btn col2" href="customer_list.php">1:1 문의하기</a>
		</div>
	</div>
	<div class="use_box">
			<ul>
				<li><img src="<?=$nfor[skin_path]?>img/use_ico1.png"><span>솔루션 결제 </span></a></li>
				<li><img src="<?=$nfor[skin_path]?>img/use_ico2.png"><span> 자료및 정보전달 </span></li>
				<li><img src="<?=$nfor[skin_path]?>img/use_ico3.png"><span>메인 시안 검수</span></li>
				<li><img src="<?=$nfor[skin_path]?>img/use_ico4.png"><span>서브시안 검수</span></li>
				<li><img src="<?=$nfor[skin_path]?>img/use_ico5.png"><span>코딩 및 변경</span></li>
				<li><img src="<?=$nfor[skin_path]?>img/use_ico6.png"><span>프로그램</span></li>
				<li><img src="<?=$nfor[skin_path]?>img/use_ico7.png"><span>솔루션 테스트</span></li>
				<li><img src="<?=$nfor[skin_path]?>img/use_ico8.png"><span>작업완료 </span></li>
		</ul>
	</div>
<style>
.pro_list{margin-top:60px; overflow:hidden;}
.pro_list li{float:left; margin-left:10px; margin-bottom:10px;}
.pro_list .pbox{border:solid 1px #efefef;width:287px; height:232px; padding:25px 20px; box-sizing:border-box; -webkit-box-sizing:border-box}
.pro_list li .pbox .tx1{display:block; font-size:24px;}
.pro_list li .pbox .tx1 .sale{color:#ff0000;  margin-left:10px; font-weight:normal;}
.pro_list li .pbox .tx1 .sale em{font-size:12px}
.pro_list li .pbox .tx2{font-size:12px; letter-spacing:-1px; display:block; margin-top:10px; margin-bottom:10px; color:#555; line-height:18px; height:36px;} 
.pro_list li .pbox .price1{font-size:12px;  text-decoration:line-through; color:#888; font-weight:normal;  font-family:"Rubik";}
.pro_list li .pbox .price2{font-size:18px;   color:#ff0000; font-weight:normal;  font-family:"Rubik";}
.pro_list li .pbox .price2 strong{font-family:"맑은고딕","malgun gothic"; font-weight:normal;}
.pro_list li .pbox .vat{font-size:12px; letter-spacing:-1px; color:#0099ff}
.pro_list li .pbox .btn{width:49%; border:solid 1px #DCDCDC; display:inline-block; text-align:center; font-size:12px; height:25px; line-height:25px; color:#555}
.cou{margin-left:20px;}
.cou .title{ font-size:18px; margin:50px 0px 10px;}
.cou .sub{font-size:12px; color:#555; line-height:18px;}
</style>
	<div class="pro_list">
		<ul>
			<li>
			<div class="pbox">
				<span class="tx1 fotr"> 소셜커머스<b class="sale">36 <em>% SALE</em></b></span>
				<span class="tx2">독립형 소셜커머스 +모바일 웹 +하이브리드앱+입점형 서비스</span>
				<span class="price1">560만원</span>
				<span class="price2">350<strong>만원</strong></span>
				<span class="vat">vat 별도</span><br><br>
				<a href="" class="btn">사용자체험</a><a href="" class="btn">관리자체험</a>
				<a href="" class="btn">온라인 문의</a><a href="" class="btn">솔루션신청</a>
			</div>	
			</li>
				<li>
			<div class="pbox">
				<span class="tx1 fotr"> 체험단솔루션<b class="sale">36 <em>% SALE</em></b></span>
				<span class="tx2">체험단 솔루션 +모바일 웹 +하이브리드앱</span>
				<span class="price1">560만원</span>
				<span class="price2">350<strong>만원</strong></span>
				<span class="vat">vat 별도</span><br><br>
				<a href="" class="btn">사용자체험</a><a href="" class="btn">관리자체험</a>
				<a href="" class="btn">온라인 문의</a><a href="" class="btn">솔루션신청</a>
			</div>	
			</li>
						<li>
			<div class="pbox">
				<span class="tx1 fotr"> 랜딩페이지<b class="sale">36 <em>% SALE</em></b></span>
				<span class="tx2">랜딩페이지 솔루션 +모바일 웹</span>
				<span class="price1">560만원</span>
				<span class="price2">350<strong>만원</strong></span>
				<span class="vat">vat 별도</span><br><br>
				<a href="" class="btn">사용자체험</a><a href="" class="btn">관리자체험</a>
				<a href="" class="btn">온라인 문의</a><a href="" class="btn">솔루션신청</a>
			</div>	
			</li>
		
		</ul>
	</div>
	<div class="cou">
		<div class="title fotr">솔루션 구매전 <strong style="color:#ff0000; font-weight:normal;">필독사항</strong></div>
		<div class="sub">결제확인후 작업에 필요한 기본정보자료 (사이트 기본색상 ,로고AI 파일, 참조사이트 ) 를  nfor@nate.com 으로 보내주시면 담당PM이 배정됩니다.<br>배정된 담당 PM 자료확인 후 전화 드리며 사이트 제작에 착수 하게됩니다.<br>  내부 작업일정이나 수정되는 부분은 작업량에 따라 변경될수 있으며, 완성예정일은 작업 착수시 미리계획 해드립니다.</div>
	
	</div>
</div>
<?
include_once $nfor[skin_path]."cus_tail.php";
?>