<?php
include_once "path.php";

if($mode=="update"){
	demo_check_json();
	if(!$mg_msg) json_return("내용을 입력해주세요","mg_msg");
	nfor_message($mb_no, $mg_msg, $mg_url);
	json_return("발송하였습니다","ok");
}

$nfor['title'] = "알림발송";

include_once "head.php";

$write['mb_no'] = $mb['mb_no'];
?>

<form name="fwrite" id="fwrite" method="post" autocomplete="off">
<?=admin_hidden($write,"mode")?>
<?=admin_hidden($write,"mb_no")?>

<table class="table cols_tbl">	
<colgroup>
	<col class="width-150p">
	<col>
</colgroup>
<tr>
	<th>내용</th> 
	<td><?=admin_textarea($write,"mg_msg","","rows=\"5\"")?></td>
</tr>
<tr>
	<th>이동주소</th> 
	<td><?=admin_text($write,"mg_url")?></td>
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