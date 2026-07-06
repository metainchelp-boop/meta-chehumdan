<?php
include_once $nfor[skin_path]."mypage_head.php";
?>
<form name="member_form" id="member_form" method="post" autocomplete="off">
<input type="hidden" name="mode" value="update">
<?=admin_hidden($write,"mb_no")?>


<div class="mb_wrap">
	<div class="mb_join_wrap">
		<div class="mem_info">
			<div class="inner_tit">기본정보</div>
			<div class="box_mem">
				<ul>
					<? if($member_config[mb_name_use]){ ?>
					<li>이름</li>
					<li>
					<?=admin_text($write,"mb_name","",($write[mb_name]?"readonly":"")." placeholder=\"이름\"")?>
					<p id="mb_name_msg" class="p_msg"></p>
					</li>
					<?php } ?>
					<? if($member_config[mb_birthday_use]){ ?>
					<li>생년월일</li>
					<li>
					<? if($member_config[mb_birthday_type_use]){ ?>	
					<?=admin_select($write,"mb_birthday_type","mb_birthday","")?>
					<?php } ?>
					<?=admin_select($write,"mb_birthday_1","mb_birthday","","0")?>
					<?=admin_select($write,"mb_birthday_2","mb_birthday","","0")?>
					<?=admin_select($write,"mb_birthday_3","mb_birthday","","0")?>
					<p id="mb_birthday_msg" class="p_msg"></p>
					</li>
					<?php } ?>
					<? if($member_config[mb_sex_use]){ ?>
					<li>성별</li>
					<li>
					<?=admin_select($write,"mb_sex","mb_sex","","0")?>
					<p id="mb_sex_msg" class="p_msg"></p>
					</li>
					<?php } ?>	
					<? if($member_config[mb_hp_use]){ ?>
					<li>휴대폰번호</li>
					<li>
					<?=admin_text($write,"mb_hp",""," placeholder=\"휴대폰번호(-없이 입력)\"")?><p id="mb_hp_msg" class="p_msg"></p>
					<div id="mb_hp_asign">
						<a id="asign_send_btn">인증번호전송</a>
						<div id="asign_input_div">
							<?=admin_text($write,"asign_number","","placeholder=\"인증번호\"")?>
							<div class="asign_confirm_wrap"><a id="asign_confirm">인증번호확인</a></div>
						</div>
					</div>
					</li>
					<?php } ?>
					<? if($member_config[mb_tel_use]){ ?>
					<li>전화번호</li>
					<li>
					<?=admin_text($write,"mb_tel","","placeholder=\"전화번호\"")?>
					<p id="mb_tel_msg" class="p_msg"></p>
					</li>
					<?php } ?>
					<? if($member_config[mb_id_use]){ ?>
					<? if($member_config[cf_mb_id_type]=="mb_id"){ ?>
					<li>아이디</li>
					<li>
					<?=admin_text($write,"mb_id","",($write[mb_id]?"readonly":"")." placeholder=\"아이디\"")?>
					<p id="mb_id_msg" class="p_msg"></p>
					</li>
					<?php } ?>
					<?php } ?>
					<? if($member_config[mb_nick_use]){ ?>
					<li>닉네임</li>
					<li>
					<?=admin_text($write,"mb_nick","","placeholder=\"닉네임\"")?>
					<p id="mb_nick_msg" class="p_msg"></p>
					</li>
					<?php } ?>
					<? if($member_config[mb_email_use]){ ?>
					<li>이메일</li>
					<li>
					<?=admin_text($write,"mb_email","","placeholder=\"이메일\"")?>
					<p id="mb_email_msg" class="p_msg"></p>
					</li>
					<?php } ?>
					<? if($member_config[mb_address_use]){ ?>
					<li>주소</li>
					<li>
					<?=admin_text($write,"mb_zipcode","mb_zipcode","readonly  pattern=\"[0-9]*\" placeholder=\"우편번호\"")?>
					<a id="zipcode_btn" class="zipcode_btn">우편번호찾기</a>
					<?=admin_text($write,"mb_addr1","mb_addr1","readonly placeholder=\"주소\"")?>
					<?=admin_text($write,"mb_addr2","mb_addr2","placeholder=\"상세주소\"")?>
					</li>
					<?php } ?>
				</ul>
			</div>
		</div>



<style>
.innpputt{border: 1px solid #e0e0e0; padding-left:10px; width:400px;   height: 43px; line-height:43px; margin:5px 0px; box-sizing:border-box; -webkit-box-sizing:border-box; }
.innpputt input[type="text"] {display:inline-block;width:150px; height: 43px;margin: -2px 0px 0px; border: none!important; color: #666;  background: none; /* outline: none; */  box-shadow: none;-webkit-appearance: none;  -webkit-appearance: none; -moz-appearance: none;  letter-spacing: -0.5px;vertical-align: middle; padding-left:0px!important; box-sizing:border-box; -webkit-box-sizing:border-box; }
</style>





		<div class="mem_info">
		<? if($member_config[mb_blog_use] or $member_config[mb_instagram_use] or $member_config[mb_youtube_use]){ ?>
			<div class="inner_tit">운영정보</div>

			<div class="box_mem">
				<ul>
					<? if($member_config[mb_blog_use]){ ?>	
					<li><img src="<?=$nfor[skin_path]?>img/sns_blog_ico.png" class="sns_ico">블로그</li>
					<li>
					<div class="innpputt">
						https://blog.naver.com /<?=admin_text($write,"mb_blog","","placeholder=\"블로그 아이디\"")?>
					</div>
					<p id="mb_blog_msg" class="p_msg"></p>
					</li>
					<?php } ?>
					<? if($member_config[mb_instagram_use]){ ?>
					<li><img src="<?=$nfor[skin_path]?>img/sns_insta_ico.png" class="sns_ico">인스타그램</li>
					<li>
					<div class="innpputt">
					https://instagram.com /<?=admin_text($write,"mb_instagram","","placeholder=\"인스타그램 아이디\"")?>
					</div>
					<p id="mb_instagram_msg" class="p_msg"></p>
					</li>
					<?php } ?>
					<? if($member_config[mb_youtube_use]){ ?>
					<li><img src="<?=$nfor[skin_path]?>img/sns_youtube_ico.png" class="sns_ico">유튜브</li>
					<li>
					<div class="innpputt">
					https://youtube.com/channel /<?=admin_text($write,"mb_youtube","","placeholder=\"채널 아이디\"")?>
					</div>
					<p id="mb_youtube_msg" class="p_msg"></p>
					</li>
					<?php } ?>
				</ul>
			</div>
		<?php } ?>
		</div>
		<? if(!sns_id_check($member)){ ?>
		<div class="mem_info">		
			<div class="inner_tit">비밀번호 변경</div>
			<div class="box_mem">
				<ul>
					<li>현재 비밀번호</li>
					<li><?=admin_password($write,"mb_password_now","mb_password_now","placeholder=\"현재 비밀번호를 입력해 주세요\"")?></li>
					<li>새 비밀번호</li>
					<li>
					<?=admin_password($write,"mb_password","mb_password","placeholder=\"새 비밀번호를 입력해주세요\"")?>
					<p id="mb_password_msg" class="mb_row_p">영문/숫자 또는 특수문자 조합 6~16자리로 입력해 주세요.</p>
					</li>
					<li>새 비밀번호 확인</li>
					<li>
					<?=admin_password($write,"mb_password_confirm","mb_password_confirm","placeholder=\"새 비밀번호를 한번더 입력해주세요\"")?>
					<p id="mb_password_confirm_msg" class="p_msg"></p>
					</li>
				</ul>
			</div>
		</div>	
		<?php } ?>

		<?php if($member[mb_admin]){ ?>
		<?php if($member_config[mb_cp_name_use] or $member_config[mb_cp_ceo_use] or $member_config[mb_cp_number_use] or $member_config[mb_cp_type1_use] or $member_config[mb_cp_type2_use] or $member_config[mb_cp_address_use]){ ?>	
		<div class="mem_info">		
			<div class="inner_tit">사업자정보</div>
			<div class="box_mem">
				<ul>
					<? if($member_config[mb_cp_name_use]){ ?>	
					<li>상호</li>
					<li>	
					<?=admin_text($write,"mb_cp_name","","placeholder=\"상호\"")?>
					<p id="mb_cp_name_msg" class="p_msg"></p>
					</li>
					<?php } ?>
					<? if($member_config[mb_cp_ceo_use]){ ?>
					<li>대표자명</li>
					<li>
					<?=admin_text($write,"mb_cp_ceo","","placeholder=\"대표자명\"")?>
					<p id="mb_cp_ceo_msg" class="p_msg"></p>	
					</li>
					<?php } ?>
					<? if($member_config[mb_cp_number_use]){ ?>	
					<li>사업자번호</li>
					<li>
					<?=admin_text($write,"mb_cp_number","","placeholder=\"사업자번호\"")?>
					<p id="mb_cp_number_msg" class="p_msg"></p>	
					</li>
					<?php } ?>
					<? if($member_config[mb_cp_type1_use]){ ?>	
					<li>업태</li>
					<li>
					<?=admin_text($write,"mb_cp_type1","","placeholder=\"업태\"")?>
					<p id="mb_cp_type1_msg" class="p_msg"></p>	
					</li>
					<?php } ?>
					<? if($member_config[mb_cp_type2_use]){ ?>	
					<li>업종</li>
					<li>
					<?=admin_text($write,"mb_cp_type2","","placeholder=\"업종\"")?>
					<p id="mb_cp_type2_msg" class="p_msg"></p></li>
					<?php } ?>
					<? if($member_config[mb_cp_address_use]){ ?>
						<li>사업장 주소</li>
						<li>
						<?=admin_text($write,"mb_cp_zipcode","","readonly  pattern=\"[0-9]*\" placeholder=\"우편번호\"")?>
						<a id="zipcode_cp_btn" class="zipcode_btn">우편번호찾기</a>
						<?=admin_text($write,"mb_cp_addr1","mb_addr1","readonly placeholder=\"주소\"")?>
						<?=admin_text($write,"mb_cp_addr2","mb_addr2","placeholder=\"상세주소\"")?>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>	
		<?php } ?>
		<?php } ?>


		<? if($member_config[mb_mailling_use] or $member_config[mb_sms_use]){ ?>
		<div class="mem_info">		
			<div class="inner_tit">수신동의</div>
			<div class="box_mem">
			<ul>
			<? if($member_config[mb_mailling_use]){ ?>
			<li>이메일 수신</li>
			<li><?=admin_checkbox($checkbox,"mb_mailling","",$write[mb_mailling]?"checked":"","이메일을 통한 정보수신에 동의합니다.")?></li>
			<?php } ?>
			<? if($member_config[mb_sms_use]){ ?>
			<li>SMS/전화 수신</li>
			<li><?=admin_checkbox($checkbox,"mb_sms","",$write[mb_sms]?"checked":"","SMS/전화를 통한 정보수신에 동의합니다.")?></li>
			<?php } ?>
			</ul>
			</div>
		</div>
		<?php } ?>
	</div>
</div>

<div class="board_btn_zone">
	<span class="btn_pack"><?=admin_submit("mb_join_btn","회원정보수정","btn_lg black")?></span>
	<span class="btn_pack"><a href="member_leave.php" class="btn_lg white">회원탈퇴</a></span>
</div>

</form>
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

$(document).on("click","#zipcode_cp_btn, #mb_cp_zipcode, #mb_cp_addr1",function(){
	zipcode("mb_cp_zipcode","mb_cp_addr1","mb_cp_addr2");
});

$(document).on("click","#zipcode_btn, #mb_zipcode, #mb_addr1",function(){
	zipcode("mb_zipcode","mb_addr1","mb_addr2");
});

$(document).on("blur","#mb_blog, #mb_instagram, #mb_youtube, #mb_id, #mb_nick, #mb_email, #mb_password, #mb_name, #mb_hp, #mb_tel, #mb_zipcode, #mb_addr1, #mb_addr2, #mb_mailling, #mb_sms, #mb_sex, #mb_birthday_type, #mb_birthday, #mb_friend, #mb_valid_date, #mb_cp_name, #mb_cp_ceo, #mb_cp_number, #mb_cp_type1, #mb_cp_type2, #mb_cp_zipcode, #mb_cp_addr1, #mb_cp_addr2, #mb_cp_bank_name, #mb_cp_bank_account, #mb_cp_bank_account_holder, #mb_birthday_1, #mb_birthday_2, #mb_birthday_3, #mb_password_confirm",function(){
	json_check(this.id);
});

$(document).on("click","#mb_join_btn",function(){
	$.ajax({
		type:"post",
		data :$("#member_form").serialize(),
		url:"member_form.php",
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
include_once $nfor[skin_path]."mypage_tail.php";
?>