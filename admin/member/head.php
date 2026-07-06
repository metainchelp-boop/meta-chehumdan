<?php
$admin[order_type] = array(""=>"전체","od_id" => "주문번호", "od_name"=>"주문자명", "od_hp"=>"주문자 휴대전화", "od_tel"=>"주문자 일반전화", "od_email"=>"주문자 이메일", "od_dy_name"=>"배송지 이름", "od_dy_hp"=>"배송지 휴대전화", "od_dy_tel"=>"배송지 일반전화", "od_bank_name"=>"입금자명", "od_mb_id"=>"아이디", "od_mb_nick"=>"닉네임", "od_it_name"=>"상품명", "od_it_id"=>"상품코드", "od_pg_tid"=>"PG코드");
$admin[od_device] = array("전체","PC","모바일");
$admin[od_payment_type] = array(""=>"전체","card" => "신용카드", "iche"=>"계좌이체", "hp"=>"휴대폰결제", "vbanking"=>"가상계좌", "banking"=>"무통장입금", "money"=>"적립금", "coupon"=>"쿠폰");
$admin[od_mb_type] = array("전체","회원","비회원");
$admin[od_sale_type] = array("전체","쿠폰사용 주문","적립금 사용 주문");
$admin[ct_payment_type] = $admin[od_payment_type];
$admin[ct_device] = $admin[od_device];
$admin[ct_mb_type] = $admin[od_mb_type];
$admin[ct_sale_type] = $admin[od_sale_type];
$admin[ct_tk_state] = array("전체","사용","미사용");
$admin[ct_it_type] = array("전체","배송상품","티켓상품");
$admin[od_cashreceipt_method] = array("전체","개인소득공제용","지출증빙용","신청안함");
$admin[od_cashreceipt_type] = array("전체","휴대폰","현금영수증카드번호","사업자등록번호","사업자현금영수증카드번호");




$admin[mb_type] = array("전체","개인회원","사업자회원");
$admin[mb_asign] = array("전체","승인","미승인");
$admin[mb_sns_type] = array(""=>"전체","facebook" => "페이스북", "kakao"=>"카카오톡", "naver"=>"네이버");
$admin[mb_join_channel] = array("전체","PC","모바일");
$admin[mb_mailling] = array("전체","동의","미동의");
$admin[mb_sms] = array("전체","동의","미동의");
$admin[mb_sex] = array(""=>"","M" => "남자", "F"=>"여자");
$admin[mb_admin] = array(""=>"전체","0" => "일반회원", "1"=>"입점회원", "2"=>"MD", "7"=>"부관리자");
$admin[mb_valid_date] = array(""=>"전체","1 year" => "1년", "3 year"=>"3년", "5 year"=>"5년", "leave"=>"탈퇴시");
$admin[mb_birthday_type] = array("","양력","음력");

$mb = sql_fetch("select * from nfor_member where mb_no='$mb_no'");

$mb = nfor_tag_out($mb);
		
if(!$mb[mb_no] or ($mb[mb_leavedatetime] && $mb[mb_leavedatetime] <= $nfor[ymdhis])) alert_close("탈퇴한 회원 입니다");

$is_pop = true;
?>
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

	<link rel="stylesheet" href="/admin/nfor.css" type="text/css" />






	<!-- 달력 플러그인 -->
	<link href="<?=$nfor[path]?>/css/datepicker.css" rel="stylesheet" type="text/css">
	<script src="<?=$nfor[path]?>/js/datepicker.min.js"></script>
	<script src="<?=$nfor[path]?>/js/i18n/datepicker.ko.js"></script>
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

<script type="text/javascript" src="<?=$nfor[path]?>/js/nfor.js"></script>


<script type="text/javascript">
<!--
var nfor_path      = "<?=$nfor[path]?>";
//-->
</script>


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


<script type="text/javascript">
<!--

$(document).on("click",".member_form_move",function(){ 
	location.href="member_form.php?mb_no=<?=$mb[mb_no]?>";
});

$(document).on("click",".member_sms_move",function(){ 
	location.href="member_sms.php?mb_no=<?=$mb[mb_no]?>";
});

$(document).on("click",".member_email_move",function(){ 
	location.href="member_email.php?mb_no=<?=$mb[mb_no]?>";
});
//-->
</script>





<div id="content2"> 
	<div class=" crm">
		<h3>회원 관리</h3>

		<input type="button" class="btn close" >
	</div>
	<div class="member_zone">
		<div class="left">
			<h3><?=$mb[mb_name]?></h3> 
			<div class="btn_zone">
				<button type="button" class="btn btn-default member_email_move" aria-label="Left Align">
				 <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
				</button>
				<button type="button" class="btn btn-default member_sms_move" aria-label="Left Align">
				 <span class="glyphicon glyphicon-phone" aria-hidden="true"></span>
				</button>
				<button type="button" class="btn btn-default member_form_move" aria-label="Left Align">
				 <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
				</button>
			</div>
		</div>
		 <div class="right">
			<span>최종로그인일시 : <?=substr($mb[mb_login_datetime],0,-3)?></span>
		 </div>
	</div>
	<div class="member_info">
		<ul>
			<li>방문횟수  : <?=number_format($mb[mb_login_count])?>회</li>
			<li>포인트 : <?=number_format($mb[mb_point])?>원</li>
			<?php if($mb[mb_stop_date] and strtotime($mb[mb_stop_date])>time()){ ?>
			<li>회원정지 : <?=date("Y년 m월 d일",strtotime($mb[mb_stop_date]))."까지"?></li>
			<?php } ?>
		</ul>
		<button type="button" class="btn btn-sm btn-white member_form_move" aria-label="Left Align">
		<span class="glyphicon glyphicon-user" aria-hidden="true"></span> 회원 정보수정
		</button>
	</div>

	<div class="member_con">
		<ul class="navi">
			<li <? if(basename($PHP_SELF)=="index.php"){ ?>class="on"<? } ?>><a href="index.php?mb_no=<?=$mb[mb_no]?>">전체보기</a></li>
			<li <? if(basename($PHP_SELF)=="member_form.php"){ ?>class="on"<? } ?>><a href="member_form.php?mb_no=<?=$mb[mb_no]?>">회원정보</a></li>
			<li <? if(basename($PHP_SELF)=="member_order.php"){ ?>class="on"<? } ?>><a href="member_order.php?mb_no=<?=$mb[mb_no]?>">신청내역</a></li>

			<li <? if(basename($PHP_SELF)=="member_penalty.php" or basename($PHP_SELF)=="member_penalty_form.php"){ ?>class="on"<? } ?>><a href="member_penalty.php?mb_no=<?=$mb[mb_no]?>">패널티 내역</a></li>

			<li <? if(basename($PHP_SELF)=="member_sms.php"){ ?>class="on"<? } ?>><a href="member_sms.php?mb_no=<?=$mb[mb_no]?>">문자발송</a></li>
			<li <? if(basename($PHP_SELF)=="member_alarm.php"){ ?>class="on"<? } ?>><a href="member_alarm.php?mb_no=<?=$mb[mb_no]?>">알림발송</a></li>
			<li <? if(basename($PHP_SELF)=="member_email.php"){ ?>class="on"<? } ?>><a href="member_email.php?mb_no=<?=$mb[mb_no]?>">메일발송</a></li>

			

		</ul>
		<!-- 전체보기 -->
		<div class="con1">
			<h3><?=$nfor[title]?></h3>


