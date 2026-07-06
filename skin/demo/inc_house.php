<style>
.top_menu_tab{overflow:hidden; margin:20px 0px;}
.top_menu_tab li{float:left; width:180px; text-align:center; line-height:55px; border:solid 1px #e0e0e0; margin-left:-0px; font-size:18px; letter-spacing:-1px;}
.top_menu_tab li + li{margin-left:-1px;}
.top_menu_tab .on{background-color:#565eb6; color:#FFF; border:solid 1px #565eb6;}
.top_menu_tab .on a{color:#fff;}
.top_menu_tab li a{display:block;}
.top_menu_title{margin:40px 0px 20px; font-size:30px; font-weight:200; line-height:45px; letter-spacing:-1px;}
.cout{overflow:hidden; border:solid 1px #e0e0e0; letter-spacing:-1px}
.cout .l_co{float:left; width:50%; padding:30px; border-right:1px solid #e0e0e0;}
.cout .l_co .l_co_tit{position: relative;display:block; font-size:21px; line-height:35px; margin-bottom:10px; padding-left:40px;}
.cout .l_co .l_co_tit:after{ content: "";  display: block;   position: absolute;   height: 29px;   width: 32px;   background: url(/skin/demo/img/cu_ico.png)center;   left: 0px; top:3px;}

.cout .r_co span{display:block; line-height:33px; color:#666;}
.cout .l_co span{display:block; line-height:28px; color:#666;}
.cout .r_co{float:left; width:50%; padding:30px;}
.cout .r_co b{display: inline-block; line-height:25px; width:70px; font-weight:normal; text-align:center; ;letter-spacing:-1px; font-size:14px;  margin-right:10px;}
.cout .r_co .ing{border:solid 1px #ff0000; color:#ff0000}
.cout .r_co .end{border:solid 1px #444040; color:#444040}
.cout .r_co .back{border:solid 1px #627bbd; color:#627bbd}
</style>
<div class="top_menu_tab">
	<ul>
		<li <?=basename($PHP_SELF)=="house_form.php"?"class='on '":"class=''"?>><a href="house_form.php">방내놓기</a></li>
		<li <?=basename($PHP_SELF)=="house_list.php"?"class='on '":"class=''"?>><a href="house_list.php">내방관리</a></li>
		<!-- <li><a href="house_map.php">지도보기</a></li> -->
	</ul>
</div>
<div class="top_menu_title"><?=$nfor[title]?></div>

<div class="cout">
	<div class="l_co">
		<span class="l_co_tit">매물등록시 유의사항</span>
		<span>※ 일반회원은 1개의 매물만 등록이 가능합니다. </span>
		<span>※ 등록한 매물은 최대 30일까지 공개 후 거래완료됩니다.</span>
	</div>
	<div class="r_co">
		<span><b class="ing">광고중</b> 내가 등록한 매물이 공개중인 상태</span>
		<span><b class="end">거래완료</b> 등록한 매물이 거래완료된 상태</span>
		<span><b class="back">검수반려 </b> 운영원칙 위배 또는 신고로 비공개된 상태</span>
	</div>
</div>