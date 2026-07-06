<?php
include_once $nfor[skin_path]."head.php";
?>

<?php
include_once $nfor[skin_path]."note_inc.php";
?>
<div class="note_wrap">
<form name="note_form" id="note_form" method="post" autocomplete="off">
<input type="hidden" name="mode" value="insert">
<div class="write_f">
	<ul>
		<li>아이디 </li>
		<li class="line"><?=admin_text($write,"no_receive_id")?></li>
		<li>내용 </li>
		<li><?=admin_textarea($write,"no_memo")?></li>
	</ul>
	<div class="board_btn_zone mar_top10"><span class="btn_pack"><?=admin_submit("no_submit_btn", "쪽지보내기","btn_lg black")?></span></div>
</form>
</div>
</div>
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
include_once $nfor[skin_path]."tail.php";
?>