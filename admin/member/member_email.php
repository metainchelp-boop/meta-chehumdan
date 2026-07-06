<?php
include_once "path.php";

if($mode=="update"){
	demo_check_json();
	if(!$ma_email) json_return("받는 메일주소를 입력해주세요","ma_email");
	if(!$ma_subject) json_return("제목을 입력해주세요","ma_subject");
	if(!$ma_memo) json_return("내용을 입력해주세요","ma_memo");
	$nfor['sd_subject'] = $ma_subject;
	$nfor['sd_memo'] = nl2br($ma_memo);
	$nfor['cf_email'] = $config['cf_email'];
	nfor_send("send_mail",$ma_email);
	json_return("발송하였습니다","ok");
}

$nfor['title'] = "메일발송";

include_once "head.php";

$write['mb_no'] = $mb['mb_no'];
$write['ma_email'] = $mb['mb_email'];
?>

<form name="fwrite" id="fwrite" method="post" autocomplete="off">
<?=admin_hidden($write,"mode")?>
<?=admin_hidden($write,"mb_no")?>

<table class="table cols_tbl">	
<colgroup>
	<col class="width-150p">
	<col>
	<col class="width-150p">
	<col>
</colgroup>
<tr>
	<th>받는메일주소</th> 
	<td><?=admin_text($write,"ma_email","width-150p")?></td>
</tr>
<tr>
	<th>제목</th> 
	<td><?=admin_text($write,"ma_subject")?></td>
</tr>
<tr>
	<th>내용</th> 
	<td><?=admin_textarea($write,"ma_memo","","rows=\"15\"")?></td>
</tr>
</table>

<div class="bottom_btn"><?=admin_submit("fsubmit_btn", "발송하기", "btn btn-lg btn-red")?></div>
</form>

<script>
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
include_once "tail.php";
?>