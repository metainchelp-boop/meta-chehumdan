<style>
.appdown_wrap{ overflow:hidden; width:100%; }
.appdown_wrap .app_tel{ text-align:left; padding:0; margin:0; }
.appdown_wrap .app_tel .txt1 { font-size:20px; margin-bottom:5px; }
.appdown_wrap .app_tel .txt2 { font-size:16px; margin-bottom:10px; }
.appdown_wrap .app_tel .txt3 { font-size:14px; margin-bottom:15px; margin-top:10px; color:#888; }
.appdown_wrap .app_tel input { border:solid 1px #ccc; height:44px; width:320px; padding:0px 0px 0px 10px; -webkit-box-sizing:content-box; -moz-box-sizing:content-box; box-sizing:content-box; }
.appdown_wrap .appdown_btn { display:block; background-color:#000; font-size:0px; color:#fff; text-align:center; cursor:pointer; height:45px; line-height:45px; background:transparent url('/skin/demo/img/appbtn.png') 0px 0px no-repeat; }
</style>

<div id="appdown_layer" class="hide">
	<div class="appdown_wrap">

		<form name="appdown_form" id="appdown_form" method="post" autocomplete="off">
		<input type="hidden" name="mode" value="appdown">

		<div class="app_tel">
		<div class="fotl txt1">APP 설치주소를 휴대폰으로 전송해드립니다.</div>
		<div class="fotl txt2">휴대폰 번호를 입력하고 전송하기 버튼을 눌러주세요</div>
		<?=admin_text($write,"app_hp",""," placeholder=\"- 없이 전화번호를 입력해주세요\"")?>
		<div class="fotl txt3">
			※ 문자전송은 무료입니다.<br>
			※ 입력하신 휴대폰번호는 저장되지 않습니다.
		</div>
		
		<?=admin_submit("appdown_btn", "전송하기","appdown_btn")?>

		</div>

		</form>

	</div>
</div>

<script>
$(document).on("click",".appdown_btn",function(){
	$.ajax({
		type:"post",
	    data :$("#appdown_form").serialize(),
		url:"appdown.php",
		success:function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				if(json["msg"]){
					alert(json["msg"]);
				}
				$('.layer_popup_bg').fadeOut();
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