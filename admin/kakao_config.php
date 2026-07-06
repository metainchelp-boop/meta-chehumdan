<?php
include_once "path.php";

$form = $_SERVER['PHP_SELF'];

if($mode=="update"){
	demo_check_json();
	sql_query("update nfor_config set cf_tel='$cf_tel', cf_kakao_apikey='$cf_kakao_apikey', cf_kakao_userid='$cf_kakao_userid', cf_kakao_senderkey='$cf_kakao_senderkey'");
	json_return("정상적으로 수정되었습니다","ok");
}

if($config['cf_kakao_apikey'] and $config['cf_kakao_userid'] and $config['cf_kakao_senderkey']) $cash = kakao_alarm_cash();

include_once "head.php";
?>

<?=admin_help("- 카카오톡을 통한 알림톡(문자)을 발송할 경우 이용되는 서비스 입니다<br>- 서비스 신청후 템플릿 등록/검수가 완료된 상태에서만 정상 발송이 가능합니다","line50 notice_gray")?>

<form name="fwrite" id="fwrite" method="post" autocomplete="off">
<?=admin_hidden($config,"mode")?>

<table class="table cols_tbl" >
<colgroup>
	<col class="width-150p ">
	<col >
</colgroup>
<tr>
	<th>대표전화</th>
	<td><div class="form-inline"><?=admin_text($config,"cf_tel","width-100p")?> <?=admin_help("대표전화는 등록된발송번호와 일치해야 발송이 가능합니다.")?></div></td>
</tr>
<tr>
	<th>발송 서버 IP</th>
	<td><?=$_SERVER['SERVER_ADDR']?></td>
</tr>
</table>

<?=admin_title("알리고 설정" ,"title_tbl")?>
<table class="table cols_tbl" >
<colgroup>
	<col class="width-150p ">
	<col >
</colgroup>
<?php if(isset($cash['code']) and $cash['code']=="0"){ ?>
<tr>
	<th>전송가능건수</th>
	<td><?=$cash['list']['ALT_CNT']?>건</td>
</tr>
<?php } ?>
<tr>
	<th>발급받은 API 키(apikey)</th>
	<td><div class="form-inline"><?=admin_text($config,"cf_kakao_apikey","width-200p")?><?=admin_a("a", "정보확인", "btn-gray btn-sm", " target=\"_blank\"", "https://smartsms.aligo.in/shop/kakaoauth.html")?></div></td>
</tr>
<tr>
	<th>사용중이신 아이디(userid)</th>
	<td><div class="form-inline"><?=admin_text($config,"cf_kakao_userid","width-200p")?><?=admin_a("a", "정보확인", "btn-gray btn-sm", " target=\"_blank\"", "https://smartsms.aligo.in/shop/kakaoauth.html")?></div></td>
</tr>
<tr>
	<th>발신프로필키(senderkey)</th>
	<td><div class="form-inline"><?=admin_text($config,"cf_kakao_senderkey","width-200p")?><?=admin_a("a", "정보확인", "btn-gray btn-sm", " target=\"_blank\"", "https://smartsms.aligo.in/shop/kakaoauth.html")?></div></td>
</tr>
</table>

<div class="bottom_btn">
	<div class="form-inline"><?=admin_submit("fsubmit_btn", "설정하기", "btn btn-lg btn-red")?></div>
</div>

</form>

<script type="text/javascript">
<!--
$(document).on("click","#fsubmit_btn",function(){
	$.ajax({
		type:"post",
		data :$("#fwrite").serialize(),
		url:"<?=basename($_SERVER['PHP_SELF'])?>",
		success:function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				if(json["msg"]){
					alert(json["msg"]);
				}
				if(json["url"]){
					location.href = json["url"];
				}
				location.reload();
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
//-->
</script>

<?php
include_once "tail.php";
?>