<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.find_id_wrap { margin:0px; padding:20px; width:100%; background-color:#FFF; box-sizing:border-box; -webkit-box-sizing:border-box; letter-spacing:-.0625em; }

.find_tit {display: block; font-weight:normal; font-size:16px; color: #333; margin-bottom:5px}
.find_sub_tit {padding-left: 0px;display: block;  font-size: 0.7em; color: #999; margin-bottom:20px;}

.find_id_wrap .tbl{ margin-bottom:20px;}
.find_id_wrap .tbl th{ text-align:left; padding-left:0px; font-size: .8em; line-height: 1.43em;  color: #959da6; font-weight:normal; letter-spacing:-.0625em; }
.find_id_wrap .tbl td{padding:3px; }

.find_id_wrap .mb_hp { margin:10px 0px; }
.find_id_wrap .mb_email { margin:10px 0px; }

.find_id_wrap .find_btn { background-color:#e83862; border:solid 1px #e83862; color:#fff; font-size:0.9em; font-weight:bold; height:40px; margin-top:20px;}
.find_id_wrap .txt_lst li {position: relative;margin-left: 2px; padding-left: 6px;}
.find_id_wrap .txt_lst li:before {display: block; position: absolute; top: 5px;left: -1px;width: 2px; height: 2px;background-color: #828282; content: '';}
.find_id_wrap .btm {margin-top:30px; padding-top: 6px; border-top: 1px solid #e4e4e4;}
.find_id_wrap .txt_lst { clear: both; line-height: 16px;color: #999;font-size: 0.7em;}
#find_id_email { margin-top:20px; }
</style>

<div class="find_id_wrap">

	<form name="find_id_hp" id="find_id_hp" method="post" autocomplete="off">
	<input type="hidden" name="mode" value="find_id_hp">

		<b class="find_tit">휴대폰번호로 아이디찾기</b>
		<p class="find_sub_tit">가입시 입력한 휴대폰번호와 이름을 입력해 주세요</p>
		<table class="tbl" cellpadding="0" cellspacing="0" width="100%;">
		<colgroup>
		<col width="25%">
		</colgroup>
		<tr>
			<th>이름</th>
			<td><?=admin_text($write,"mb_name","","placeholder=\"이름\"")?></td>
		</tr>
		<tr>
			<th>휴대폰번호</th>
			<td><?=admin_text($write,"mb_hp","","placeholder=\"휴대폰번호\"")?></td>
		</tr>
		</table>
		
		<?=admin_submit("hp_submit_btn", "휴대폰번호로 찾기", "basic_btn color")?>

		
	</form>

	<? if($member_config[cf_mb_id_type] == "mb_id"){ ?>
	<form name="find_id_email" id="find_id_email" method="post" autocomplete="off">
	<input type="hidden" name="mode" value="find_id_email">

		<b class="find_tit">이메일주소로 아이디찾기</b>
		<p class="find_sub_tit">가입시 입력한 이메일주소와 이름을 입력해 주세요</p>
		<table class="tbl" cellpadding="0" cellspacing="0" width="100%;">
		<colgroup>
		<col width="25%">
		</colgroup>
		<tr>
			<th>이름</th>
			<td><?=admin_text($write,"mb_name","","placeholder=\"이름\"")?></td>
		</tr>
		<tr>
			<th>이메일</th>
			<td><?=admin_text($write,"mb_email","","placeholder=\"이메일\"")?></td>
		</tr>
		</table>

		<?=admin_submit("email_submit_btn", "이메일로 찾기", "basic_btn color")?>
			
	</form>
	<? } ?>

	<ul class="btm txt_lst">
		<li>본인 인증 시 제공되는 정보는 인증 이외의 용도로 이용 또는 저장하지 않습니다.</li>
		<li>인증문자/이메일이 발송되지 않을 경우 연락처가 스팸으로 분류되어 있는지 확인 바랍니다.</li>
	</ul>

</div>


<script>
$(document).on("click","#hp_submit_btn",function(){
	$.ajax({
		type:"post",
		data :$("#find_id_hp").serialize(),
		url:"find_id.php",
		success:function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				if(json["msg"]){
					alert(json["msg"]);
				}
			} else{
				if(json["msg"]){
					alert(json["msg"]);
				}
				if(json["result"]){
					$("#find_id_hp #"+json["result"]).focus();
				}
			}
		}
	});
	event.preventDefault();
});
$(document).on("click","#email_submit_btn",function(){
	$.ajax({
		type:"post",
		data :$("#find_id_email").serialize(),
		url:"find_id.php",
		success:function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				if(json["msg"]){
					alert(json["msg"]);
				}
			} else{
				if(json["msg"]){
					alert(json["msg"]);
				}
				if(json["result"]){
					$("#find_id_email #"+json["result"]).focus();
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