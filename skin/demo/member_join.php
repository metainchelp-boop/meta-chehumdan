<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.s_join_wrap { overflow: hidden; width: 650px; margin: 0 auto; padding-top: 37px; background-color: #fff;}
.mb_join_wrap .join_tit { font-size:30px; font-weight:200; }
.mb_join_wrap .mb_join_title { margin-top:20px; margin-bottom:15px; font-size:18px; }
.mb_join_wrap { width:100%; padding:10px; box-sizing:border-box; -webkit-box-sizing:border-box; }
.mb_join_wrap .mb_join_row { margin-top:5px; }

.mb_join_wrap input[type="text"]{width:100%; padding-left:10px;}
.mb_join_wrap input[type="password"]{width:100%; padding-left:10px;}
.mb_join_wrap input[type="text"] {
    height: 56px;
    margin: 0;
    border: 1px solid #e0e0e0;
    color: #666;
    background: none;
    /* outline: none; */
    box-shadow: none;
    -webkit-appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    letter-spacing: -0.5px;
    vertical-align: middle;
	 box-sizing:border-box; -webkit-box-sizing:border-box; 
}
.mb_join_wrap input[type="password"] {
    height: 56px;
    margin: 0;
    border: 1px solid #e0e0e0;
    color: #666;
    background: none;
    /* outline: none; */
    box-shadow: none;
    -webkit-appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    letter-spacing: -0.5px;
    vertical-align: middle;
}
#asign_input_div { display:none; }

.mb_row_p { margin:15px 0px 10px; font-size:15px; color:#666; }
.mb_row_p label { font-size:13px; }

#zipcode_btn  { cursor:pointer; position:absolute; right:-2px; top:0px; font-size:12px; height:56px; display:block; width:90px; text-align:center; line-height:56px; border:solid 1px #ccc; background-color:#ffffff; box-sizing:border-box; -webkit-box-sizing:border-box;}
#zipcode_cp_btn  { cursor:pointer; position:absolute; right:-2px; top:0px; font-size:12px; height:56px; display:block; width:90px; text-align:center; line-height:56px; border:solid 1px #ccc;  background-color:#ffffff; box-sizing:border-box; -webkit-box-sizing:border-box;}



.mb_join_wrap select:focus, input[type=text]:focus, input[type=email]:focus, input[type=password]:focus, input[type=number]:focus { border:solid 1px; }
.mb_join_wrap select { appearance: none; -webkit-appearance: none; }
.mb_join_wrap select::-ms-expand { display:none; }
.mb_join_wrap select {height:56px; border:1px solid #e0e0e0;  padding-left:10px;background: url('skin/demo/img/select_background.png') no-repeat 98% 50%; font-size:14px; -webkit-appearance: none;  -moz-appearance: none; appearance: none; box-sizing:border-box; -webkit-box-sizing:border-box;  } 
.p_msg { font-size:13px; color:#de1d5a; padding:5px 0px; }

#mb_hp_msg { margin:5px 0px;}  
#mb_hp_asign { display:none; }
#asign_send_btn { cursor:pointer; display:block; height:40px; line-height:40px; font-size:15px; text-align:center;  color:red; border:solid 2px red; background-color:#fff; }

#asign_input_div { margin:5px 0px; }
#asign_number { float:left; width:70%; height:40px; padding:0px; border:solid 2px #ccc; }

#asign_confirm_wrap { float:right; width:30%; }
#asign_confirm { width:150px; height:40px; line-height:40px; font-size:15px; background-color:#e24f6f; color:#fff; display:block; text-align:center; cursor:pointer; }

.mb_sex { height:56px; border:1px solid #e0e0e0; width:100%; color:#666;;}


.mb_birthday { height:56px; border:1px solid #e0e0e0; padding-left:5px; color:#666; }
 
#mb_birthday_type { float:left; width:25%; margin-right:1%; } 

#mb_birthday_1 { float:left; width:24%; margin-right:1%; }
#mb_birthday_2 { float:left; width:24%; margin-right:1%; }
#mb_birthday_3 { float:left; width:24%; }

.mb_join_line { border-top:solid 1px #ccc; margin-top:10px; }



#mb_join_btn {display:inline-block; width: 100%; height: 64px; font-size:24px; line-height: 64px; margin-top:20px; background-color:#666;  border:solid 1px #666; color:#FFF; font-weight:200; letter-spacing:-1px}



.mb_agree { overflow-y:scroll; -webkit-overflow-scrolling:touch; height:100px; border:solid 1px #e5e5e5; background-color:#fff; color:#555; padding:10px; font-size:12px; }

.mb_privacy { width:100%; margin:0 0 10px; background:#fff; border-top:solid 1px #e5e5e5; border-left:solid 1px #e5e5e5; }
.mb_privacy th{ padding:7px; text-align:left; color:#666; font-size:12px; border-bottom:solid 1px #e5e5e5; border-right:solid 1px #e5e5e5; background-color:#f4f4f4; font-weight:normal; }
.mb_privacy td{ padding:7px; text-align:left; color:#222; font-size:12px; border-bottom:solid 1px #e5e5e5; border-right:solid 1px #e5e5e5; }


#mb_addr1 { margin-bottom:5px; }
.zip_wrap { width:180px; height:50px; position:relative; margin-bottom:15px; }




</style>


<div class="s_join_wrap">
<div class="mb_join_wrap">

<b class="join_tit">회원가입</b>


<form name="member_join" id="member_join" method="post" autocomplete="off">
<input type="hidden" name="mode" value="insert">
<?=admin_hidden($write,"mb_timestamp")?>
<?=admin_hidden($write,"mb_no")?>

<?=admin_hidden($write,"mb_naver_id")?>
<?=admin_hidden($write,"mb_facebook_id")?>
<?=admin_hidden($write,"mb_kakao_id")?>
<?=admin_hidden($write,"mb_google_id")?>
<?=admin_hidden($write,"mb_apple_id")?>

	<p class="mb_join_title">기본정보</p>

	<? if($member_config[mb_name_use]){ ?>
	<div class="mb_join_row"><?=admin_text($write,"mb_name","inppt",($write[mb_name]?"readonly":"")." placeholder=\"이름\"")?></div>
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
		<?=admin_select($write,"mb_sex","mb_sex","","0")?>
	</div>
	<p id="mb_sex_msg" class="p_msg"></p>
	<? } ?>

	<? if($member_config[mb_hp_use]){ ?>
	<div class="mb_join_row"><?=admin_text($write,"mb_hp","inppt",($write[mb_hp]?"readonly":"")." placeholder=\"휴대폰번호\"")?></div>
	<p id="mb_hp_msg" class="p_msg"></p>
	<? } ?>


	<div id="mb_hp_asign">
		<a id="asign_send_btn">인증번호전송</a>
		<div id="asign_input_div">
			<?=admin_text($write,"asign_number","","placeholder=\"인증번호\"")?>
			<div class="asign_confirm_wrap"><a id="asign_confirm">인증번호확인</a></div>
		</div>
	</div>
	<p class="mb_row_p" style="color:#ff0000">※ 휴대폰번호는 아이디/비밀번호를 찾기 위해 반드시 필요한 정보이므로 정확하게 입력해주세요.</p>







	<? if($member_config[mb_tel_use]){ ?>
	<div class="mb_join_row"><?=admin_text($write,"mb_tel","inppt","placeholder=\"전화번호\"")?></div>
	<p id="mb_tel_msg" class="p_msg"></p>
	<? } ?>


	<div class="mb_join_line"></div>

	<p class="mb_join_title">계정 설정</p>


	<? if(!$_SESSION[sns_login]){ ?>
	
	<? if($member_config[mb_id_use]){ ?>
	<? if($member_config[cf_mb_id_type]=="mb_id"){ ?>
	<div class="mb_join_row"><?=admin_text($write,"mb_id","inppt","placeholder=\"아이디\"")?></div>
	<p id="mb_id_msg" class="p_msg"></p>
	<? } ?>
	<? } ?>

	<? } ?>

	<? if($member_config[mb_nick_use]){ ?>
	<div class="mb_join_row"><?=admin_text($write,"mb_nick","inppt","placeholder=\"닉네임\"")?></div>
	<p id="mb_nick_msg" class="p_msg"></p>
	<? } ?>

	<? if($member_config[mb_email_use]){ ?>
	<div class="mb_join_row"><?=admin_text($write,"mb_email","inppt","placeholder=\"이메일\"")?></div>
	<p id="mb_email_msg" class="p_msg"></p>
	<? } ?>




	<? if(!$_SESSION[sns_login]){ ?>

	<div class="mb_join_row"><?=admin_password($write,"mb_password","inppt","placeholder=\"비밀번호\"")?></div>
	<p id="mb_password_msg" class="mb_row_p">영문/숫자 또는 특수문자 조합 6~16자리로 입력해 주세요.</p>

	<div class="mb_join_row"><?=admin_password($write,"mb_password_confirm","inppt","placeholder=\"비밀번호확인\"")?></div>
	<p id="mb_password_confirm_msg" class="p_msg"></p>

	<? } ?>





	<div class="mb_join_line"></div>
	
	<? if($member_config[mb_address_use]){ ?>
	<p class="mb_join_title">주소</p>

	<div class="mb_join_row">

		<div class="zip_wrap">
		<?=admin_text($write,"mb_zipcode","inppt","readonly  pattern=\"[0-9]*\" placeholder=\"우편번호\"")?>
		<a id="zipcode_btn">우편번호찾기</a>
		</div>

		<?=admin_text($write,"mb_addr1","inppt","readonly placeholder=\"주소\"")?>
		<?=admin_text($write,"mb_addr2","inppt","placeholder=\"상세주소\"")?>

	</div>

	<div class="mb_join_line"></div>
	<? } ?>









	<? if($member_config[mb_friend_use]){ ?>	
	<p class="mb_row_p">추천인</p>
	<div class="mb_join_row"><?=admin_text($write,"mb_friend","inppt","placeholder=\"추천인\"")?></div>
	<p id="mb_friend_msg" class="p_msg"></p>
	<? } ?>






<style>
.innpputt{border: 1px solid #e0e0e0; padding-left:45px;   height: 56px; line-height:54px;  box-sizing:border-box; -webkit-box-sizing:border-box; }
.innpputt input[type="text"] {display:inline-block;width:150px; height: 56px;margin: -2px 0px 0px;;   border: 0px solid #e0e0e0; color: #666;  background: none; /* outline: none; */  box-shadow: none;-webkit-appearance: none;  -webkit-appearance: none; -moz-appearance: none;  letter-spacing: -0.5px;vertical-align: middle;padding-left:0px; box-sizing:border-box; -webkit-box-sizing:border-box; }
.blog{position:relative;}
.blog:after{display: inline-block; position: absolute; left:8px;  top:13px;clear: both; content: ''; width:30px; height:30px; background:url(/skin/demo/img/blog.png)center no-repeat; background-size:30px;}
.instagram{position:relative;}
.instagram:after{display: inline-block; position: absolute; left:8px;  top:13px;clear: both; content: ''; width:30px; height:30px; background:url(/skin/demo/img/instgram.png)center no-repeat; background-size:30px;}
.youtube{position:relative;}
.youtube:after{display: inline-block; position: absolute; left:8px;  top:13px;clear: both; content: ''; width:30px; height:30px; background:url(/skin/demo/img/youtube.png)center no-repeat; background-size:30px;}
</style>



	<? if($member_config[mb_blog_use] or $member_config[mb_instagram_use] or $member_config[mb_youtube_use]){ ?>	
	<p class="mb_join_title">운영 채널</p>

	<? if($member_config[mb_blog_use]){ ?>	
	<div class="mb_join_row">
		<div class="innpputt blog txt_num">
		https://blog.naver.com /<?=admin_text($write,"mb_blog","","placeholder=\" 블로그 아이디\"")?>
		</div>
		
	</div>
	<p id="mb_blog_msg" class="p_msg"></p>
	<? } ?>
	
	<? if($member_config[mb_instagram_use]){ ?>	
	<div class="mb_join_row">
		<div class="innpputt instagram txt_num">
			https://instagram.com /<?=admin_text($write,"mb_instagram","inppt","placeholder=\" 인스타그램 아이디\"")?>
		</div>
	</div>
	<p id="mb_instagram_msg" class="p_msg"></p>
	<? } ?>

	<? if($member_config[mb_youtube_use]){ ?>	
	<div class="mb_join_row">
		<div class="innpputt youtube txt_num">
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
	
	<p class="mb_row_p">사업자정보</p>

	<? if($member_config[mb_cp_name_use]){ ?>	
	<div class="mb_join_row"><?=admin_text($write,"mb_cp_name","inppt","placeholder=\"상호\"")?></div>
	<p id="mb_cp_name_msg" class="p_msg"></p>
	<? } ?>


	<? if($member_config[mb_cp_ceo_use]){ ?>	
	<div class="mb_join_row"><?=admin_text($write,"mb_cp_ceo","inppt","placeholder=\"대표자명\"")?></div>
	<p id="mb_cp_ceo_msg" class="p_msg"></p>
	<? } ?>


	<? if($member_config[mb_cp_number_use]){ ?>	
	<div class="mb_join_row"><?=admin_text($write,"mb_cp_number","inppt","placeholder=\"사업자번호\"")?></div>
	<p id="mb_cp_number_msg" class="p_msg"></p>
	<? } ?>


	<? if($member_config[mb_cp_type1_use]){ ?>	
	<div class="mb_join_row"><?=admin_text($write,"mb_cp_type1","inppt","placeholder=\"업태\"")?></div>
	<p id="mb_cp_type1_msg" class="p_msg"></p>
	<? } ?>

	<? if($member_config[mb_cp_type2_use]){ ?>	
	<div class="mb_join_row"><?=admin_text($write,"mb_cp_type2","inppt","placeholder=\"업종\"")?></div>
	<p id="mb_cp_type2_msg" class="p_msg"></p>
	<? } ?>


	<? if($member_config[mb_cp_address_use]){ ?>	
	<p class="mb_join_title">사업장 주소</p>

	<div class="mb_join_row">

		<div class="zip_wrap">
		<?=admin_text($write,"mb_cp_zipcode","inppt","readonly  pattern=\"[0-9]*\" placeholder=\"우편번호\"")?>
		<a id="zipcode_cp_btn">우편번호찾기</a>
		</div>

		<?=admin_text($write,"mb_cp_addr1","inppt","readonly placeholder=\"주소\"")?>
		<?=admin_text($write,"mb_cp_addr2","inppt","placeholder=\"상세주소\"")?>

	</div>

	<div class="mb_join_line"></div>
	<? } ?>

	<? } ?>
	<? } ?>



















	<? if($member_config[mb_mailling_use] or $member_config[mb_sms_use]){ ?>
	<p class="mb_join_title">수신동의</p>

	<div class="mb_join_row">
		<? if($member_config[mb_mailling_use]){ ?>
		<div class="basic_chk"><?=admin_checkbox_basic_chk($checkbox,"mb_mailling","",$write[mb_mailling]?"checked":"","이메일을 통한 정보수신에 동의합니다.")?></div>
		<? } ?>
		<? if($member_config[mb_sms_use]){ ?>
		<div class="basic_chk"><?=admin_checkbox_basic_chk($checkbox,"mb_sms","",$write[mb_sms]?"checked":"","SMS/전화를 통한 정보수신에 동의합니다.")?></div>
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
</div>



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

$(document).on("click","#zipcode_btn, #mb_zipcode, #mb_addr1",function(){
	zipcode("mb_zipcode","mb_addr1","mb_addr2");
});

$(document).on("click","#zipcode_cp_btn, #mb_cp_zipcode, #mb_cp_addr1",function(){
	zipcode("mb_cp_zipcode","mb_cp_addr1","mb_cp_addr2");
});

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