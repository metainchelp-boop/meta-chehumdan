<style>
.pass_ch_bg { position:fixed; left:0; top:0; width:100%; height:100%; z-index:1000; overflow-y:auto;  }

.pass_ch{position:absolute; top:50%; left:50%; margin:-330px 0 0 -354px; width:708px; height:660px; border:solid 2px #000000; background-color:#FFF; z-index:1001; }
.pass_ch .innerpa{padding:25px; }
.pass_ch .innerpa .topimg{overflow:hidden;padding:30px 0px 0px 0px;;}
.pass_ch .innerpa .topimg li:nth-child(1){width:60%;}
.pass_ch .innerpa .topimg li{float:left; width:40%;}
.pass_ch .innerpa .topimg li .ss_txr{font-size:16px; color:#666;}
.pass_ch .innerpa .topimg li .ss_txr2{font-size:40px; color:#000; letter-spacing:-1px; line-height:45px; margin-top:20px;}
.pass_ch .innerpa .topimg li .ss_txr2 span{color:#ec6464}
.pass_ch .innerpa .topimg li .ss_txr3{font-size:14px; color:#666;  letter-spacing:-1px; margin-top:60px;}
.pass_ch .closebtn{position:absolute; right:10px; top:10px; cursor:pointer;}
.pass_ch .innerpa .boxpass{background-color:#f5f5f5; border-top: solid 1px #dcdcdc; border-bottom: solid 1px #dcdcdc; border-left:none;border-right:none; height:190px;}
.pass_ch .innerpa .boxpass table{ font-size:12px;font-family:Nanum Gothic; color:#666; padding:15px;}
.pass_ch .innerpa .boxpass table td{padding:5px;}
.pass_ch .innerpa .boxpass table td span{color:#ff0000}
.pass_ch .innerpa .boxpass table input.input_txt{width:165px;height:20px;padding:0 4px;border:1px solid #ccc;line-height:20px}
.pass_ch .innerpa .boxpass .txt{font-size:11px; font-family:돋움; color:#777; line-height:15px;margin-top:5px;}
.pass_ch .innerpa .txt{font-size:11px; font-family:돋움; color:#777; line-height:15px;margin-top:5px;}
.pass_ch .innerpa .btn01{display:inline-block;width:220px; height:60px; text-align:center; line-height:60px; color:#FFF; background-color:#ed6d46;}
.pass_ch .innerpa .btn02{display:inline-block;width:220px; height:60px; text-align:center; line-height:60px; color:#FFF; background-color:#525252;}
</style>





<div class="pass_ch_bg">



<div class="pass_ch">
	<div class="closebtn password_change_next"><img src="<?=$nfor[skin_path]?>img/closeicon.gif"></div>
	<div class="innerpa">
		<ul class="topimg">
			<li>
				<div class="ss_txr fotm">고객님 <?=round((time()-strtotime($member[mb_password_change_datetime]))/(86400*30))."개월"?>간 동일한 비밀번호를 사용하고 계십니다</div>
				<div class="ss_txr2 fotm"><span>비밀번호</span>를 다시한번<br>점검해주세요</div>
				<div class="ss_txr3 fotl">고객님의 개인정보를 보호하고 개인정보 도용으로 인한 피해를 막기 위해비밀번호의 변경 안내를 도와 드리고 있습니다<br></div>
			</li>
			<li><img src="<?=$nfor[skin_path]?>img/pass_ch1.png"></li>
		</ul>
		
	

		<form id="password_change_popup_form" method="post">
		<input type="hidden" name="mode" value="change">
		<div class="boxpass">
			<table>
			<tr>
				<td>현재비밀번호</td>
				<td><input type="password" name="mb_password_now" id="mb_password_now" class="input_txt"></td>
			</tr>
			<tr>
				<td><span>*</span>새 비밀번호<br><br><br></td>
				<td>
					<input type="password" name="mb_password" id="mb_password" class="input_txt">
					<p class="txt">영문/숫자 또는 특수문자 조합 6~16자리로 입력해주세요</p>
				</td>
			</tr>
			<tr>
				<td><span>*</span>새 비밀번호 확인<br><br><br></td>
				<td>
					<input type="password" name="mb_password_confirm" id="mb_password_confirm" class="input_txt">
					<p class="txt">설정하신 비밀번호를 한번 더 입력해 주세요.</p>
				</td>
			</tr>
			</table>
		</div>
		<p class="txt">※ 다음에 변경하기를 선택하신 경우 앞으로 <?=$config[cf_password_day_again]?><?=$config[cf_password_day_again_type]=="month"?"개월":"일"?> 동안 변경 없이 사용 가능합니다.</p>
		<div style="text-align:center; margin-top:30px">
			<a class="btn01 fotm" href="javascript:password_change_popup_fnc()">비밀번호 변경</a>
			<a class="btn02 fotm password_change_next">다음에 변경하기</a>
		</div>
		</form>

	</div>
</div>










</div>


<script>
$(document).on("click",".password_change_next",function(){ 

	$.ajax({
		type: "post",
		url: nfor_path + "/password_change_popup.php",
		data: {
			"mode":"next"
		},
		cache: false,
		async: false,
		success: function(response){
			var json = $.parseJSON(response);
			if(json["result"]=="ok"){
				// 팝업창닫기액션
				alert(json["msg"]);
				$(".pass_ch_bg").hide();
			} else{
				alert(json["msg"]);
			}
		}
	});

});

function password_change_popup_fnc(){

	if(!$("#mb_password_now").val()){
		alert("현제 비밀번호를 입력해주세요");
		$("#mb_password_now").focus();
		return false;
	}

	$.ajax({
		type: "post",
		url: nfor_path + "/password_change_popup.php",
		data: $("#password_change_popup_form").serialize(),
		cache: false,
		async: false,
		success: function(response){
			var json = $.parseJSON(response);
			if(json["result"]=="ok"){
				// 팝업창닫기액션
				alert(json["msg"]);
				$(".pass_ch_bg").hide();
				return false;
			} else{
				alert(json["msg"]);
				$("#"+json["result"]).focus();
				return false;
			}
		}
	});
    
}	
</script>