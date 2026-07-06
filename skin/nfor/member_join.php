<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.mb_join_wrap { width:100%; padding:10px 20px; box-sizing:border-box; -webkit-box-sizing:border-box;  background-color:#FFF; letter-spacing:-0.0625em}
.mb_join_wrap p{font-weight:bold; margin:10px 0px;}

.mb_join_wrap .mb_join_row { margin-top:5px; }

#asign_input_div { display:none; }

.mb_row_p { margin-top:5px; font-size:0.7em; color: #9ea5ae;}
.mb_row_p label { font-size:0.7em; }

select:focus, input[type=text]:focus, input[type=email]:focus, input[type=password]:focus, input[type=number]:focus { border:solid 1px red; }

.p_msg { font-size: 12px; line-height: 16px;  color: #9ea5ae; }

#mb_hp_msg { margin:5px 0px; }  
#mb_hp_asign { display:none; }
#asign_send_btn { cursor:pointer; display:block; height:40px; line-height:40px; font-size:15px; text-align:center;  color:red; border:solid 2px red; background-color:#fff; }

#asign_input_div { margin:5px 0px; }
#asign_number { float:left; width:70%; }

#asign_confirm_wrap { float:right; width:30%; }
#asign_confirm { width:100%; height:40px; line-height:40px; font-size:15px; background-color:#e24f6f; color:#fff; display:block; text-align:center; cursor:pointer; }



#mb_birthday_type { float:left; width:25%; margin-right:1%; } 

#mb_birthday_1 { float:left; width:24%; margin-right:1%; }
#mb_birthday_2 { float:left; width:24%; margin-right:1%; }
#mb_birthday_3 { float:left; width:24%; }


#mb_addr1 { margin:5px 0px; }  

.mb_join_line { border-top:solid 1px #ccc; margin-top:10px; }
.mb_join_title { margin-top:20px; margin-bottom:6px; font-size:16px; }


.mb_agree { overflow-y:scroll; -webkit-overflow-scrolling:touch; height:100px; border:solid 1px #e5e5e5; background-color:#fff; color:#555; padding:10px; font-size:12px; }

.mb_privacy { table-layout: fixed;width: 100%;border: 1px solid #dfe2e6;border-collapse: collapse; }
.mb_privacy th{ height: 25px;padding: 0 10px; border-bottom: 1px solid #dfe2e6; font-size: 9px; color: #6c7580; text-align: left; background-color: #f2f4f5;}
.mb_privacy td{ height: 25px;  padding: 0 10px; border-bottom: 1px solid #dfe2e6; font-size: 9px;color: #6c7580; text-align: left;}

#zipcode_wrap { display:none; width:100%; height:300px; position:relative; }

.zipcode_btn  { cursor:pointer; position:absolute; right:0px; top:0px; height:38px; display:block; width:110px; text-align:center; font-size:0.8em; line-height:40px; border:solid 1px #ccc; background:-webkit-gradient(linear,left top,left bottom,from(#fff),to(#ecebf0)); box-shadow:none; }

.zip_wrap { width:100%; height:40px; position:relative; }
</style>


<form name="member_join" id="member_join" method="post" autocomplete="off">
<input type="hidden" name="mode" value="insert">

<?=admin_hidden($write,"mb_timestamp")?>
<?=admin_hidden($write,"mb_no")?>

<?=admin_hidden($write,"mb_naver_id")?>
<?=admin_hidden($write,"mb_facebook_id")?>
<?=admin_hidden($write,"mb_kakao_id")?>
<?=admin_hidden($write,"mb_google_id")?>
<?=admin_hidden($write,"mb_apple_id")?>

<div class="mb_join_wrap" id="member_info">

	<p>기본정보</p>
	<? if($member_config[mb_name_use]){ ?>
	<div class="mb_join_row"><?=admin_text($write,"mb_name","",($write[mb_name]?"readonly":"")." placeholder=\"이름\"")?></div>
	<p id="mb_name_msg" class="p_msg"></p>
	<? } ?>

	<? if($member_config[mb_birthday_use]){ ?>
	<div class="mb_join_row">
	
		<? if($member_config[mb_birthday_type_use]){ ?>	
		<?=admin_select($write,"mb_birthday_type","mb_birthday","")?>
		<? } ?>

		<?=admin_select($write,"mb_birthday_1","mb_birthday","","0")?>
		<?=admin_select($write,"mb_birthday_2","mb_birthday","","0")?>
		<?=admin_select($write,"mb_birthday_3","mb_birthday","","0")?>
		<div style="clear:both;"></div>
	</div>
	<p id="mb_birthday_msg" class="p_msg"></p>
	<? } ?>

	<? if($member_config[mb_sex_use]){ ?>
	<div class="mb_join_row">
		<?=admin_select($write,"mb_sex","","","0")?>
	</div>
	<p id="mb_sex_msg" class="p_msg"></p>
	<? } ?>

	<? if($member_config[mb_hp_use]){ ?>
	<div class="mb_join_row"><?=admin_text($write,"mb_hp","",($write[mb_hp]?"readonly":"")." placeholder=\"휴대폰번호(-없이 입력)\"")?></div>
	<p id="mb_hp_msg" class="p_msg"></p>
	<? } ?>


	<div id="mb_hp_asign">
		<a id="asign_send_btn">인증번호전송</a>
		<div id="asign_input_div">
			<?=admin_text($write,"asign_number","","placeholder=\"인증번호\"")?>
			<div class="asign_confirm_wrap"><a id="asign_confirm">인증번호확인</a></div>
		</div>
	</div>

	<p class="mb_row_p">휴대폰번호는 아이디/비밀번호를 찾기 위해 반드시 필요한 정보이므로 정확하게 입력해주세요.</p>


	<? if($member_config[mb_tel_use]){ ?>
	<div class="mb_join_row"><?=admin_text($write,"mb_tel","","placeholder=\"전화번호\"")?></div>
	<p id="mb_tel_msg" class="p_msg"></p>
	<? } ?>

	<div class="mb_join_line"></div>

	<? if(!$_SESSION[sns_login]){ ?>

	<? if($member_config[mb_id_use]){ ?>
	<? if($member_config[cf_mb_id_type]=="mb_id"){ ?>
	<div class="mb_join_row"><?=admin_text($write,"mb_id","","placeholder=\"아이디\"")?></div>
	<p id="mb_id_msg" class="p_msg"></p>
	<? } ?>
	<? } ?>

	<? } ?>

	<? if($member_config[mb_nick_use]){ ?>
	<div class="mb_join_row"><?=admin_text($write,"mb_nick","","placeholder=\"닉네임\"")?></div>
	<p id="mb_nick_msg" class="p_msg"></p>
	<? } ?>

	<? if($member_config[mb_email_use]){ ?>
	<div class="mb_join_row"><?=admin_text($write,"mb_email","","placeholder=\"이메일\"")?></div>
	<p id="mb_email_msg" class="p_msg"></p>
	<? } ?>




	<? if(!$_SESSION[sns_login]){ ?>

	<div class="mb_join_row"><?=admin_password($write,"mb_password","","placeholder=\"비밀번호\"")?></div>
	<p id="mb_password_msg" class="mb_row_p">영문/숫자 또는 특수문자 조합 6~16자리로 입력해 주세요.</p>

	<div class="mb_join_row"><?=admin_password($write,"mb_password_confirm","","placeholder=\"비밀번호확인\"")?></div>
	<p id="mb_password_confirm_msg" class="p_msg"></p>

	<? } ?>






	<div class="mb_join_line"></div>

	<? if($member_config[mb_address_use]){ ?>
	<p class="mb_join_title">주소</p>

	<div class="mb_join_row">

		<div class="zip_wrap">
			<?=admin_text($write,"mb_zipcode","","readonly  pattern=\"[0-9]*\" placeholder=\"우편번호\"")?>
			<a id="zipcode_btn" class="zipcode_btn">우편번호찾기</a>
		</div>

		<?=admin_text($write,"mb_addr1","","readonly placeholder=\"주소\"")?>
		<?=admin_text($write,"mb_addr2","","placeholder=\"상세주소\"")?>

	</div>

	<div class="mb_join_line"></div>
	<? } ?>


	<? if($member_config[mb_friend_use]){ ?>
	<p class="mb_join_title">추천인</p>

	<div class="mb_join_row"><?=admin_text($write,"mb_friend","","placeholder=\"추천인\"")?></div>
	<p id="mb_friend_msg" class="p_msg"></p>
	<? } ?>



<style>
.innpputt{border: 1px solid #e0e0e0; padding-left:10px;   height: 56px; line-height:54px;  box-sizing:border-box; -webkit-box-sizing:border-box; }
.innpputt input[type="text"] {display:inline-block; width:38%; height: 56px;margin: -2px 0px 0px;  border: 0px solid #e0e0e0; color: #666;  background: none; /* outline: none; */  box-shadow: none;-webkit-appearance: none;  -webkit-appearance: none; -moz-appearance: none;  letter-spacing: -1px;vertical-align: middle;padding-left:0px; box-sizing:border-box; -webkit-box-sizing:border-box; }
</style>


	<? if($member_config[mb_blog_use] or $member_config[mb_instagram_use] or $member_config[mb_youtube_use]){ ?>	

	<p>운영채널</p>

	<? if($member_config[mb_blog_use]){ ?>	
	<div class="mb_join_row">
		<div class="innpputt">
		https://blog.naver.com /<?=admin_text($write,"mb_blog","","placeholder=\" 블로그 아이디\"")?>
		</div>
		
	</div>
	<p id="mb_blog_msg" class="p_msg"></p>
	<? } ?>
	
	<? if($member_config[mb_instagram_use]){ ?>	
	<div class="mb_join_row">
		<div class="innpputt">
			https://instagram.com /<?=admin_text($write,"mb_instagram","inppt","placeholder=\" 인스타그램 아이디\"")?>
		</div>
	</div>
	<p id="mb_instagram_msg" class="p_msg"></p>
	<? } ?>

	<? if($member_config[mb_youtube_use]){ ?>	
	<div class="mb_join_row">
		<div class="innpputt">
			https://youtube.com/channel /<?=admin_text($write,"mb_youtube","inppt","placeholder=\" 채널 아이디\"")?>
		</div>
	</div>
	<p id="mb_youtube_msg" class="p_msg"></p>
	<? } ?>
	
	<div class="mb_join_line"></div>
	<? } ?>



	<? if($_GET[mb_type]){ ?>
	<?=admin_hidden($_GET,"mb_type")?>

	<? if($member_config[mb_cp_name_use] or $member_config[mb_cp_ceo_use] or $member_config[mb_cp_number_use] or $member_config[mb_cp_type1_use] or $member_config[mb_cp_type2_use] or $member_config[mb_cp_address_use]){ ?>	

	<p>사업자정보</p>

	<? if($member_config[mb_cp_name_use]){ ?>	
	<div class="mb_join_row"><?=admin_text($write,"mb_cp_name","","placeholder=\"상호\"")?></div>
	<p id="mb_cp_name_msg" class="p_msg"></p>
	<? } ?>


	<? if($member_config[mb_cp_ceo_use]){ ?>	
	<div class="mb_join_row"><?=admin_text($write,"mb_cp_ceo","","placeholder=\"대표자명\"")?></div>
	<p id="mb_cp_ceo_msg" class="p_msg"></p>
	<? } ?>


	<? if($member_config[mb_cp_number_use]){ ?>	
	<div class="mb_join_row"><?=admin_text($write,"mb_cp_number","","placeholder=\"사업자번호\"")?></div>
	<p id="mb_cp_number_msg" class="p_msg"></p>
	<? } ?>


	<? if($member_config[mb_cp_type1_use]){ ?>	
	<div class="mb_join_row"><?=admin_text($write,"mb_cp_type1","","placeholder=\"업태\"")?></div>
	<p id="mb_cp_type1_msg" class="p_msg"></p>
	<? } ?>

	<? if($member_config[mb_cp_type2_use]){ ?>	
	<div class="mb_join_row"><?=admin_text($write,"mb_cp_type2","","placeholder=\"업종\"")?></div>
	<p id="mb_cp_type2_msg" class="p_msg"></p>
	<? } ?>


	<? if($member_config[mb_cp_address_use]){ ?>	
	<p class="mb_join_title">사업장 주소</p>

	<div class="mb_join_row">

		<div class="zip_wrap">
		<?=admin_text($write,"mb_cp_zipcode","","readonly  pattern=\"[0-9]*\" placeholder=\"우편번호\"")?>
		<a id="zipcode_cp_btn" class="zipcode_btn">우편번호찾기</a>
		</div>

		<?=admin_text($write,"mb_cp_addr1","","readonly placeholder=\"주소\"")?>
		<?=admin_text($write,"mb_cp_addr2","","placeholder=\"상세주소\"")?>

	</div>

	<div class="mb_join_line"></div>
	<? } ?>

	<? } ?>
	<? } ?>














	<? if($member_config[mb_mailling_use] or $member_config[mb_sms_use]){ ?>
	<p class="mb_join_title">수신동의</p>

	<div class="mb_join_row">
		<? if($member_config[mb_mailling_use]){ ?>
		<div><?=admin_checkbox($checkbox,"mb_mailling","",$write[mb_mailling]?"checked":"","이메일을 통한 정보수신에 동의합니다.")?></div>
		<? } ?>
		<? if($member_config[mb_sms_use]){ ?>
		<div style="margin-top:5px;"><?=admin_checkbox($checkbox,"mb_sms","",$write[mb_sms]?"checked":"","SMS/전화를 통한 정보수신에 동의합니다.")?></div>
		<? } ?>
	</div>
	<? } ?>

	<?=admin_submit("mb_join_btn", "동의하고 회원가입")?>

	<div class="mb_join_line"></div>

	<p class="mb_join_title">이용약관</p>

	
	<div class="mb_agree">
	<?php
	$agreement = sql_fetch("select * from nfor_agreement where ag_code='join_agreement'");
	echo $agreement[ag_memo];
	?>	
	</div>

	<p class="mb_join_title">개인정보 수집 및 이용</p>

	<table cellpadding="0" cellspacing="0" class="mb_privacy">
	<colgroup>
	<col width="15%">
	<col width="30%">
	<col width="25%">
	<col width="30%">
	</colgroup>
	<thead>
	<tr>
		<th>&nbsp;</th>
		<th>수집항목</th>
		<th>수집목적</th>
		<th>보유기간</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<th>가입시</th>
		<td>ID(이메일), PW, 이름, 전화번호, 성별, 생년월일</td>
		<td>회원식별 및 연락</td>
		<td>회원탈퇴 후 3개월</td>
	</tr>
	<tr>
		<th>거래발생시(추가)</th>
		<td>주소, 결제수단정보, 수령인 성명/주소/연락처</td>
		<td>결제 및 배송처리</td>
		<td>전상법 등 관련법률에 의한 보관기간</td>
	</tr>
	</tbody>
	</table>


</form>

</div>


<div id="zipcode_wrap"></div>


<script>
$(document).on("click","#asign_send_btn",function(){

	var mb_hp = $("#mb_hp").val();
	$.ajax({
		type: "post",
		data : "mode=hp_asign&mb_hp="+mb_hp,
		url: "json.php",
		success: function(response){
			var json = $.parseJSON(response);			
			if(json["result"]=="ok"){
				$("#asign_send_btn").html("인증번호 재전송");
				alert(json["msg"]);
				$("#asign_input_div").show();
			} else{
				alert(json["msg"]);
			}
		}
	});

});

$(document).on("click","#asign_confirm",function(){

	var mb_hp = $("#mb_hp").val();
	var asign_number = $("#asign_number").val();
	$.ajax({
		type: "post",
		data : "mode=hp_asign_confirm&mb_hp="+mb_hp+"&asign_number="+asign_number,
		url: "json.php",
		success: function(response){
			var json = $.parseJSON(response);			
			if(json["result"]=="ok"){
				alert(json["msg"]);
				$("#mb_hp_asign").hide();
				$("#asign_input_div").hide();

				$("#mb_hp_msg").html("");
				$("#mb_hp").attr("readonly","readonly").removeAttr('id');;


			} else{
				alert(json["msg"]);
			}
		}
	});

});

var currentScroll = "";

$(document).on("click","#zipcode_btn, #mb_zipcode, #mb_addr1",function(){
	currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
	zipcode("mb_zipcode","mb_addr1","mb_addr2");
	$("#member_info").hide();
	$(".btn_back").attr("href","javascript:zipcode_close()");
});

$(document).on("click","#zipcode_cp_btn, #mb_cp_zipcode, #mb_cp_addr1",function(){
	currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
	zipcode("mb_cp_zipcode","mb_cp_addr1","mb_cp_addr2");
	$("#member_info").hide();
	$(".btn_back").attr("href","javascript:zipcode_close()");
});

function zipcode_close(){
	var element_wrap = document.getElementById('zipcode_wrap');
	element_wrap.style.display = 'none';
	$("#member_info").show();
	// 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
	document.body.scrollTop = currentScroll;
	$(".btn_back").attr("href","javascript:history.back()");
}

function zipcode(zipcode,addr1,addr2){
	var element_wrap = document.getElementById('zipcode_wrap');

	// 현재 scroll 위치를 저장해놓는다.
	new daum.Postcode({
		oncomplete: function(data) {
			// 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

			// 각 주소의 노출 규칙에 따라 주소를 조합한다.
			// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
			var fullAddr = data.address; // 최종 주소 변수
			var extraAddr = ''; // 조합형 주소 변수

			// 기본 주소가 도로명 타입일때 조합한다.
			if(data.addressType === 'R'){
				//법정동명이 있을 경우 추가한다.
				if(data.bname !== ''){
					extraAddr += data.bname;
				}
				// 건물명이 있을 경우 추가한다.
				if(data.buildingName !== ''){
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
				fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
			}

			// 우편번호와 주소 정보를 해당 필드에 넣는다.
			document.getElementById(zipcode).value = data.zonecode; //5자리 새우편번호 사용
			document.getElementById(addr1).value = fullAddr;
			$("#member_info").show();

			// iframe을 넣은 element를 안보이게 한다.
			// (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
			element_wrap.style.display = 'none';


			// 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
			document.body.scrollTop = currentScroll;

			document.getElementById(addr2).focus();

		},
		// 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분. iframe을 넣은 element의 높이값을 조정한다.
		onresize : function(size) {
			element_wrap.style.height = size.height+'px';
		},
		width : '100%',
		height : '100%'
	}).embed(element_wrap);

	// iframe을 넣은 element를 보이게 한다.
	element_wrap.style.display = 'block';
}

$(document).on("blur","#mb_blog, #mb_instagram, #mb_youtube, #mb_id, #mb_nick, #mb_email, #mb_password, #mb_name, #mb_hp, #mb_tel, #mb_zipcode, #mb_addr1, #mb_addr2, #mb_mailling, #mb_sms, #mb_sex, #mb_birthday_type, #mb_birthday, #mb_friend, #mb_valid_date, #mb_bank_name, #mb_bank_account, #mb_cp_name, #mb_cp_ceo, #mb_cp_number, #mb_cp_type1, #mb_cp_type2, #mb_cp_zipcode, #mb_cp_addr1, #mb_cp_addr2, #mb_cp_bank_name, #mb_cp_bank_account, #mb_cp_bank_account_holder, #mb_birthday_1, #mb_birthday_2, #mb_birthday_3, #mb_password_confirm",function(){
	json_check(this.id);
});

$(document).on("click","#mb_join_btn",function(){
	$.ajax({
		type:"post",
		data :$("#member_join").serialize(),
		url:"member_join.php",
		success:function(response){
			console.log(response);
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				if(json["msg"]){
					alert(json["msg"]);
				}
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
			}
		}
	});
	event.preventDefault();
});
</script>

<?php
include_once $nfor[skin_path]."tail.php";
?>