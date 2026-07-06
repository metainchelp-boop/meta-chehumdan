<?php
include_once "path.php";

if(basename($PHP_SELF)=="memo.php"){

	if($mode=="update"){
		sql_query("update nfor_member set mb_memo='$mb_memo', mb_memo_datetime='$nfor[ymdhis]' where mb_no='$member[mb_no]'");
		$return[mb_memo_datetime] = $nfor[ymdhis];
		json_return("수정되었습니다","ok");
	}

}
$memo[mb_memo] = $member[mb_memo];
?>
<style>
#admin_memo_form #mb_memo { height:150px; }
#admin_memo_form #memo_submit_btn { width:100%; margin:10px 0px; }
.mb_date_wrap { font-family:돋움; font-size:11px; }
.mb_date_wrap b { letter-spacing:-1px; }
</style>

<form id="admin_memo_form" method="post">
<input type="hidden" name="mode" value="update">
<?=admin_textarea($memo,"mb_memo")?>
<?=admin_submit("memo_submit_btn", "메모저장", "btn btn-black")?>
<div class="mb_date_wrap"><b>최종저장</b> <span id="admin_memo_datetime"><?=$member[mb_memo_datetime]?></a></div>
</form>

<script>
$(document).on("click","#memo_submit_btn",function(){
	$.ajax({
		type:"post",
		data :$("#admin_memo_form").serialize(),
		url:"memo.php",
		success:function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				$("#admin_memo_datetime").html(json["mb_memo_datetime"]);
			} else{
				if(json["msg"]){
					alert(json["msg"]);
				}
			}
		}
	});
	event.preventDefault();
});
</script>