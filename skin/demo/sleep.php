<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.sleep_wrap{width:560px;padding: 40px 38px 54px; margin:40px auto; border: 1px solid #e0e0e0; background: #ffffff;}
.sleep_wrap .sleep_tit{font-size:24px;display:block; margin:0px 0px 20px 0px;font-weight:200}
.sleep_wrap .sleep_tit2{font-size:16px;display:block; margin:20px 0px 0px 0px;}
.sleep_wrap .sleep_tit3{font-size:12px;display:block; margin:5px 0px 20px 0px;color:#999;}
.sleep_wrap .back{margin:20px 0px;}
.sleep_wrap .back span{width: 100px; margin-top: 6px; float: left; font-size:16px; line-height:56px;color:#999; font-weight:normal;}
.sleep_wrap .back input{ width:100%; padding-left:11px;margin-bottom:6px; ;height:56px;color:#aaa; font-size:14px; line-height:56px;border:1px solid #d7d7d7; background-color:#ffffff;}
.sleep_wrap .back input:hover{color:#ff5000;border:1px solid #ff5000; background-color:#ffffff;}


.sleep_wrap .sleep_btn{display:block;text-align:center;width:100%; height:56px; margin-top:10px; margin-bottom:10px;line-height:56px;border:1px solid #333;background:#333;color:#fff;font-size:14px; text-decoration:none;transition:color 0.2s, background 0.2s;   font-weight:normal;}
.sleep_wrap .sleep_btn:hover{color:#333;background:#fff;}

.sleep_wrap .back2{display:block; border:solid 1px #DCDCDC; background-color:#FAFAFA; padding:20px; margin-top:20px;}
.sleep_wrap .back2 span{display:block;font-size:11px; color:#767676;line-height:18px;}


.detail_wrap { display:none; }


.asign_number_wrap { display:none; overflow:hidden; }
.asign_number_wrap #asign_number { float:left; padding:0; width: 56%; height: 42px; font-size:16px; margin-top:15px; background-color:#fff;  border:solid 1px #666; color:#666; font-weight:100; letter-spacing:-1px }
.asign_number_wrap .asign_confirm { float:left; padding:0; width: 42%; height: 44px; font-size:16px; margin-top:15px; background-color:#666;  border:solid 1px #666; color:#FFF; font-weight:100; letter-spacing:-1px  }



.asign_type_wrap .sleep_tit2 { margin-bottom:10px;  }
.asign_type_wrap label { display:block; margin-bottom:10px; }

.sleep_common_btn { width: 100%; height: 44px; font-size:16px; margin-top:15px; background-color:#666;  border:solid 1px #666; color:#FFF; font-weight:100; letter-spacing:-1px}


</style>

<form name="sleep_form" id="sleep_form" method="post" autocomplete="off">
<input type="hidden" name="mb_no" id="mb_no" value="<?=$mb_no?>">
<div class="sleep_wrap">

	<div class="sleep_tit">휴면회원 해제</div>


	<?php if($member_config[cf_mb_again_asign]=="2"){ ?>
	
		<input type="hidden" name="mode" id="mode" value="info">
		<div class="sleep_tit2">회원가입 당시 입력하신 회원정보를 입력해 주세요</div>
		<div class="sleep_tit3">반드시 가입 정보가 모두 일치해야 휴면회원이 해제 됩니다</div>
		<div class="back">
			<?php if($member_config[cf_asign_mb_hp]){ ?><?=admin_text($write,"mb_hp","","placeholder=\"휴대폰번호\"")?><?php } ?>
			<?php if($member_config[cf_asign_mb_email]){ ?><?=admin_text($write,"mb_email","","placeholder=\"이메일\"")?><?php } ?>
		</div>
		<?=admin_submit("sleep_submit_btn", "휴면회원 해제하기", "sleep_btn")?>
	
	<?php } ?>
	<?php if($member_config[cf_mb_again_asign]=="3"){ ?>
		
		<input type="hidden" name="mode" id="mode" value="asign">

		<div class="asign_type_wrap">
			<div class="sleep_tit2">인증수단을 선택해주세요</div>
			<?=admin_radio($member_config,"asign_type","asign_type")?>
		</div>

		<div class="sms_email_detail detail_wrap">

			<input type="button" value="인증번호 발송하기" class="asign_send sleep_common_btn">
			<div class="asign_number_wrap">
			<input type="text" name="asign_number" id="asign_number" placeholder="인증번호를 입력해주세요"><input type="button" value="인증하기" class="asign_confirm">
			</div>

		</div>


		<div class="ipin_detail detail_wrap">
			<?php if($config[cf_ipin_use]=="1"){ ?><input type="button" value="아이핀인증" class="sleep_common_btn" onclick="javascript:kcb_ipin_sleep_win()"><?php } ?>
		</div>
		<div class="hp_detail detail_wrap">
			<?php if($config[cf_hp_use]=="1"){ ?><input type="button" value="휴대폰인증" class="sleep_common_btn"  onclick="javascript:kcb_hp_sleep_win()"><?php } ?>
		</div>		

	<?php } ?>

</div>
</form>

<script>

function kcb_hp_sleep_win(){
	window.open(nfor_path + "/okname/hp1_edit.php?mb_no=" + $("#mb_no").val(), "auth_popup", "width=430,height=590,scrollbar=yes");
}

function kcb_ipin_sleep_win(){
	window.open(nfor_path + "/okname/ipin1_edit.php?mb_no=" + $("#mb_no").val(), "kcbPop", "left=200, top=100, status=0, width=450, height=550");
}

$(document).on("click",".asign_confirm",function(){
	$("#mode").val("asign_confirm");
	$.ajax({
		type:"post",
		data :$("#sleep_form").serialize(),
		url:"sleep.php",
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
					$("#sleep_form #"+json["result"]).focus();
				}
			}
		}
	});
	event.preventDefault();
});

$(document).on("click",".asign_send",function(){
	$(".asign_send").hide();
	$(".asign_number_wrap").show();
	$(".asign_type_wrap").hide();

	$.ajax({
		type:"post",
		data :$("#sleep_form").serialize(),
		url:"sleep.php",
		success:function(response){
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
					$("#sleep_form #"+json["result"]).focus();
				}
			}
		}
	});
	event.preventDefault();

});

$(document).on("click",".asign_type",function(){
	$(".detail_wrap").hide();

	if($(this).val()=="sms" || $(this).val()=="email"){
		$(".sms_email_detail").show();
	} else{
		$("."+$(this).val()+"_detail").show();
	}
});

$(document).on("click","#sleep_submit_btn",function(){
	$.ajax({
		type:"post",
		data :$("#sleep_form").serialize(),
		url:"sleep.php",
		success:function(response){
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
					$("#sleep_form #"+json["result"]).focus();
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