<?php
include_once $nfor['skin_path']."head.php";
?>

<style>
.member_check{ overflow:hidden; width:650px; margin:60px auto; padding-top:37px; background-color:#fff; }
.member_btn{ overflow:hidden; display: -ms-flexbox; display: -moz-flex; display: -webkit-flex; display: -webkit-box;  display: flex; -webkit-box-sizing: border-box;-moz-box-sizing: border-box; box-sizing: border-box; width:100%; margin:0 -3px; margin-bottom:16px; }
.member_btn input { display:block; height:48px; margin:0 3px; border-radius:3px; background-color:#18a8f1; border:none; color:#fff; float:left; -webkit-flex-grow: 1;-webkit-box-flex: 1;-ms-flex-positive: 1;  flex-grow: 1;}
.member_check h5 { margin:0 0 15px 0; font-weight:700; font-size:28px; color:#16181a; letter-spacing:-1px; text-align:center; }
.member_check p { margin-bottom:22px; color:#959da6; letter-spacing:-1px; text-align:center; }
.member_check .description { text-align:left; padding-top:16px; border-top:1px solid #f0f0f0;  font-size:12px; color:#c2c7cc; letter-spacing:-1px; }
.member_check .description p { margin-bottom:10px; font-size:14px; text-align:left; }
</style>

<div class="member_check">

	<h5>원하시는 인증방법을 선택해주세요</h5>
	<p>회원가입을 위해서는 실명인증을 반드시 진행해주셔야 합니다</p>

	<div class="member_btn">		
		<? if($config[cf_hp_use]=="1"){ ?><input type="button" value="휴대폰인증"  onclick="javascript:kcb_hp_win()"><? } ?>
		<? if($config[cf_ipin_use]=="1"){ ?><input type="button" value="아이핀인증"" onclick="javascript:kcb_ipin_win()"><? } ?>
	</div>

	<div class="description">
		! 꼭읽어주세요
		<p>정보통신망 이용 촉진 및 정보보호 등에 관한 법류 제 23조의 2(주민등록번호의 사용 제한)에 따라 아이핀 서비스를 이용하여 주민등록번호를 입력하지 않고도 사이트 가입할수 있습니다.</p>
		<br>
		<p>휴대폰 본인인증 또는 아이핀에 문제가 발생하면 아래의 고객센터로 문의해 주시기 바랍니다.</p>
		<p>아이핀 서비스는 신용평가회사인 KCB가 제공합니다.</p>
		<p>문의전화 : 02-708-1000, 팩스 : 02-708-1111</p>
	</div>
	
</div>

<?php
include_once $nfor['skin_path']."tail.php";
?>