<!DOCTYPE html>
<html lang="ko">
  <head>
    <meta charset="<?=$nfor[charset]?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$config[cf_title]?></title>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="<?=$nfor[path]?>/js/html5shiv.min.js"></script>
      <script src="<?=$nfor[path]?>/js/respond.min.js"></script>
    <![endif]-->
	<script src="<?=$nfor[path]?>/js/jquery-1.9.1.min.js"></script>




	<!-- 부트스트랩 -->
	<link rel="stylesheet" href="<?=$nfor[path]?>/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?=$nfor[path]?>/css/bootstrap-theme.min.css">
	<script src="<?=$nfor[path]?>/js/bootstrap.min.js"></script>
	<!-- //부트스트랩 -->
	<!-- 부트스트랩 플러그인 -->
	<link rel="stylesheet" href="<?=$nfor[path]?>/css/bootstrap-dialog.min.css" type="text/css" />
	<script src="<?=$nfor[path]?>/js/bootstrap-dialog.min.js"></script>
	<!-- //부트스트랩 플러그인 -->


	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<script src="http://code.jquery.com/jquery-latest.min.js"></script>

	<link rel="stylesheet" href="/admin/nfor.css?time=<?=time()?>" type="text/css" />






	<!-- 달력 플러그인 -->
	<link href="<?=$nfor[path]?>/css/datepicker.css" rel="stylesheet" type="text/css">
	<script src="<?=$nfor[path]?>/js/datepicker.min.js"></script>
	<script src="<?=$nfor[path]?>/js/i18n/datepicker.ko.js?time=<?=time()?>"></script>
	<!-- //달력 플러그인 -->






	<script type="text/javascript" src="<?=$nfor[path]?>/js/bootstrap-maxlength.js"></script>

	<script src="<?=$nfor[path]?>/js/jstree.min.js"></script>


	<script type="text/javascript" src="<?=$nfor[path]?>/js/underscore-min.js"></script>


	<script type="text/javascript" src="<?=$nfor[path]?>/js/jquery.countdown.min.js"></script>


	<script type="text/javascript" src="<?=$nfor[path]?>/editor/cheditor.js"></script>


<? if($_SERVER['REQUEST_SCHEME']=="https"){ ?>
<script src="https://ssl.daumcdn.net/dmaps/map_js_init/postcode.v2.js"></script>
<? } else{ ?>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<? } ?>

<script type="text/javascript" src="<?=$nfor[path]?>/js/nfor.js?time=<?=time()?>"></script>





<style>
#content2{width:1000px; top:-34px;padding-left:30px; padding-right:30px;}
#content2 .crm{margin-top:24px; margin-bottom:25px; border-bottom: 2px solid #888888; position:relative;}
#content2 .crm h3{font-size:22px; color:#222222; font-weight:bold; margin-top:17px; margin-bottom:17px;}
#content2 .crm .close{position:absolute; right:0px; top:0px; background: url(/admin/img/closeicon.gif) no-repeat 50% 50%;width:22px; height:22px; opacity: 1; border:none;}

#content2 .member_zone{overflow:hidden;}
#content2 .member_zone .left{float:left; overflow:hidden; height:45px;}
#content2 .member_zone .left h3{ float:left; display:inline-block;color: #222222;  text-decoration: none;  margin:0px; font-size: 24px; line-height:45px; font-weight: bold;}
#content2 .member_zone .left .btn_zone{float:right; margin-top:10px; margin-left:10px;}
#content2 .member_zone .right{float:right; padding-top:20px;}
#content2 .member_info{border:solid 1px #ccc; padding:10px; margin-top:10px; overflow:hidden; position:relative; background-color:#FAFAFA;}
#content2 .member_info li{ float:left; padding:0px 10px; border-left:solid 1px #DCDCDC;}
#content2 .member_info li:nth-child(1){ border-left:none; padding-left:0px;}
#content2 .member_info li b{color:#ff0000}
#content2 .member_info button{position:absolute; right:10px; top:6px;}


#content2 .member_con .navi{margin:20px 0 20px; overflow:hidden; border-bottom: 1px solid #888888;}
#content2 .member_con .navi li{ float:left; background-color: #F6F6F6; margin-right: -1px; border: 1px solid #E4E4E4; border-radius:0;  }
#content2 .member_con .navi li:hover{ background-color: #FFF; }
#content2 .member_con .navi li a{display:block; width:100%; padding: 10px 15px; text-decoration:none;}
#content2 .member_con .navi li a:hover{color:#666;}
#content2 .member_con .navi .on {color:#222222;font-size:12px;  margin-right: -1px;background-color: #FFFFFF; border: 1px solid #888888; border-bottom-color: transparent;font-weight: bold;  z-index: 2;}
#content2 .member_con .navi .on a{border-right:1px solid #888; display:block; width:100%; padding: 10px 15px; }


#content2 .member_con .con1 h3{font-size:16px; font-weight:bold; color:#222; border-bottom:2px solid #888888; padding:10px 0px;}
#content2 .member_con .con1 .title{padding:10px 0px; font-size:14px; }
</style>
</head>
<body style="background-color:#FFF;"> 
<div id="content2"> 
	<div class=" crm">
		<h3>회원 관리</h3>

		<input type="button" class="btn close" >
	</div>
	<div class="member_zone">
		<div class="left">
			<h3>임준아</h3> 
			<div class="btn_zone">
				<button type="button" class="btn btn-default" aria-label="Left Align">
				 <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
				</button>
				<button type="button" class="btn btn-default" aria-label="Left Align">
				 <span class="glyphicon glyphicon-phone" aria-hidden="true"></span>
				</button>
				<button type="button" class="btn btn-default" aria-label="Left Align">
				 <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
				</button>
			</div>
		</div>
		 <div class="right">
			<span>일반회원 최종로그인일: 2016-05-10</span>
		 </div>
	</div>
	<div class="member_info">
		<ul>
			<li>총결재금액 : <b>285,000</b>원</li>
			<li>구매건수  :7건</li>
			<li>문의 건수 : 7건</li>
			<li>사용가능 적립금 : 2,150원</li>
			<li>소멸 적립금 : 2,150원</li>
			<li>쿠폰 0/<b>0</b></li>
		</ul>
		<button type="button" class="btn btn-sm btn-white" aria-label="Left Align">
		<span class="glyphicon glyphicon-user" aria-hidden="true"></span> 회원 정보수정
		</button>
	</div>

	<div class="member_con">
		<ul class="navi">
			<li class="on"><a href="">전체보기</a></li>
			<li><a href="">회원정보</a></li>
			<li><a href="">주문내역</a></li>
			<li><a href="">적립금내역</a></li>
			<li><a href="">쿠폰내역</a></li>
			<li><a href="">SMS 발송내역</a></li>
			<li><a href="">메일발송내역</a></li>
			<li ><a href="">문의내역</a></li>
		</ul>
		<!-- 전체보기 -->
		<div class="con1">
			<h3>전체보기</h3>
			<div class="title">회원정보</div>
			<table  class="table cols_tbl">
			<tr>
				<th>회원 구분</th>
				<td>일반회원</td>
				<th>이름</th>
				<td>손원영</td>
			</tr>
			<tr>
				<th>가입일</th>
				<td>2017.5.7</td>
				<th>아이디</th>
				<td>nfor</td>
			</tr>
			<tr>
				<th>휴대폰</th>
				<td>010-2774-4531</td>
				<th>전화번호</th>
				<td>010-2774-4531</td>
			</tr>
			<tr>
				<th>주소</th>
				<td colspan="3">인천시 서구 경서동 청라 더샵레이크 파크 451동 3104호</td>
			</tr>
			<tr>
				<th>성별</th>
				<td>남</td>
				<th>나이</th>
				<td>25</td>
			</tr>
			
			</table>
			<div class="title">주문내역</div>
			<table class="row_tbl">
			<tr>
				<th>번호</th>
				<th>주문일시</th>
				<th>주문번호</th>
				<th>주문상품</th>
				<th>수량</th>
				<th>총결재금액</th>
				<th>처리상태</th>
				<th>송장번호</th>
				<th>공급사</th>
			</tr>
			<tr>
				<td>1</td>
				<td>18.01.01</td>
				<td>212585452</td>
				<td>클럽모나코 모던 블라우스 외 2 건</td>
				<td>1</td>
				<td>384,000원</td>
				<td>구매확정</td>
				<td>452545245</td>
				<td>nfor</td>
			</tr>
			</table>
			<div class="title">1:1문의내역</div>
			<table class="row_tbl">
			<tr>
				<th>번호</th>
				<th>제목</th>
				<th>작성일</th>
				<th>조회</th>
				<th>답변상태</th>
				<th>답변</th>

			</tr>
			<tr>
				<td>1</td>
				<td>18.01.01</td>
				<td>212585452</td>
				<td>클럽모나코 모던 블라우스 외 2 건</td>
				<td>1</td>
				<td>384,000원</td>

			</tr>
			</table>
		</div>
		<!-- 전체보기 -->
		<!-- 회원정보 -->
		<div class="con1">
			<h3>회원정보</h3>
			<table  class="table cols_tbl">
			<tr>
				<th>회원 구분</th>
				<td>일반회원</td>
				<th>이름</th>
				<td>손원영</td>
			</tr>
			<tr>
				<th>가입일</th>
				<td>2017.5.7</td>
				<th>아이디</th>
				<td>nfor</td>
			</tr>
			<tr>
				<th>휴대폰</th>
				<td>010-2774-4531</td>
				<th>전화번호</th>
				<td>010-2774-4531</td>
			</tr>
			<tr>
				<th>주소</th>
				<td colspan="3">인천시 서구 경서동 청라 더샵레이크 파크 451동 3104호</td>
			</tr>
			<tr>
				<th>성별</th>
				<td>남</td>
				<th>나이</th>
				<td>25</td>
			</tr>
			
			</table>
			
		</div>
		<!-- 전체보기 -->
	</div>

</div>

</body>
</html>