<?php
include_once $nfor[skin_path]."note_head.php";
?>

<?php
include_once $nfor[skin_path]."note_inc.php";
?>

<form name="note_form" id="note_form" method="post" autocomplete="off">
<input type="hidden" name="mode" value="insert">
<div class="note_write">
	<table cellpadding="0" >
	<tr>
		<th>아이디</th>
		<td><?=admin_text($write,"no_receive_id")?></td>
	</tr>
	<tr>
		<th>내용</th>
		<td><?=admin_textarea($write,"no_memo")?></td>
	</tr>
	</table>
</div>
<div class="board_btn_zone">
<span class="btn_pack"><?=admin_submit("no_submit_btn", "쪽지보내기","btn_lg black")?></span>
</div>
</form>

<script>
$(document).on("click","#no_submit_btn",function(){
	$.ajax({
		type:"post",
		data :$("#note_form").serialize(),
		url:"note_form.php",
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
include_once $nfor[skin_path]."note_tail.php";
?>