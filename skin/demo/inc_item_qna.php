<div class="item_qna_wrap">

	<div class="comment_wrap">

		<form name="item_qna_form" class="form_qna_insert" method="post">
		<input type="hidden" name="mode" value="insert">
		<input type="hidden" name="qa_it_id" value="<?=$item[it_id]?>">

		<div class="btnset"><a href="customer_list.php"><img src="<?=$nfor[skin_path]?>img/onebyone.png"></a> <a href="faq.php"><img src="<?=$nfor[skin_path]?>img/qnago.png"></a></div>
		<div class="comment_textarea_wrap">
			<img src="<?=$nfor[skin_path]?>img/comment_im.png" >
			<textarea name="qa_memo" id="item_qna_memo_insert" class="comment_textarea" data-mode="insert" <? if(!$member[mb_no]) echo "readonly"; ?>><?=$member[mb_no]?"":"로그인후 이용해주세요."?></textarea>
			<em class="comment_count" id="comment_count_insert"><span>0</span> / 500</em>
		</div>
		<img src="<?=$nfor[skin_path]?>img/submit_qa.png" class="btn_item_qna_submit" data-mode="insert">
		</form>

	</div>

	<form name="item_qna_list" class="form_qna_reply form_qna_delete" method="post">
	<input type="hidden" name="mode">
	<input type="hidden" name="qa_it_id" value="<?=$item[it_id]?>">
	<input type="hidden" name="qa_parent">
	<input type="hidden" name="qa_id">
	<ul class="qna_list">
	<?php
	include_once "item_qna_list.php";
	?>
	</ul>
	</form>

</div>

<div id="reply_div_qna" style="display:none;">

	<div class="comment_wrap2">
		<div class="comment_textarea_wrap2">
		<textarea name="qa_memo" id="item_qna_memo_reply" class="item_qna_memo_reply comment_textarea" data-mode="reply" <? if(!$member[mb_no]) echo "readonly"; ?>><?=$member[mb_no]?"":"로그인후 이용해주세요."?></textarea>
		<em class="comment_count" id="comment_count_reply"><span>0</span> / 500</em>
		</div>
		<img src="<?=$nfor[skin_path]?>img/submit_qa2.png" class="btn_item_qna_submit" data-mode="reply">
	</div>

</div>

<script type="text/javascript">
<!--
var max_length = 500;

$(document).on("keyup keydown", ".comment_textarea", function (){
	var mode = $(this).data("mode");
	var content = $(this).val();
	var content_cnt = content.length;
	if(content_cnt > max_length){
		$(this).val($(this).val().substring(0, max_length));
		$(this).focus();
	}
	$("#comment_count_" + mode).html("<span>" + content_cnt + "</span> / " + max_length);
}); 

$(document).on("click", ".btn_item_qna_submit", function(){
	var mode = $(this).data("mode");
	if(is_member=="0"){
		alert("로그인하셔야 이용가능합니다");
		return;
	}
	if($.trim($("#item_qna_memo_"+mode).val()).length < 1){
		alert("내용을 입력하셔야 이용가능합니다");
		return;
	}
	if(confirm("입력하신 내용을 등록하시겠습니까?")){
		$.ajax({
			type: "post",
			data : $(".form_qna_" + mode).serialize(),
			url: "item_qna_form.php",
			success: function(response){
				var json = $.parseJSON(response); 
				if(json["result"]=="ok"){
					$("#item_qna_memo_"+mode).val("");
					$(".comment_count").html("<span>0</span> / "+max_length);
					item_qna_load(it_id,"1");
				} else{
					alert(json["msg"]);
				}
			}
		});
	}
});

$(document).on("click", ".delete", function (){
	var qa_id = $(this).data("qa_id");
	if(confirm("삭제하시겠습니까?")){
		$.ajax({
			type: "post",
			data: {
				"mode":"delete",
				"qa_id":qa_id
			},
			url: "item_qna_form.php",
			success: function(response){
				var json = $.parseJSON(response); 
				if(json["result"]=="ok"){
					$("#qa_li_"+qa_id).remove();
					if($(".qna_list li").length < 1){
						item_qna_load(it_id,"1");
					}
				} else{
					alert(json["msg"]);
				}
			}
		});
	}
});

$(document).on("click", ".update", function (){
	var qa_id = $(this).data("qa_id");
	$(".qna_comment_wrap").html("");
	$("[name='item_qna_list'] [name='mode']").val("update");
	$("[name='item_qna_list'] [name='qa_id']").val(qa_id);
	$("[name='item_qna_list'] [name='qa_parent']").val();
	$(".qna_comment_wrap").html("");
	$("#update_qna_"+qa_id).html($("#reply_div_qna").html());
	$("[name='item_qna_list'] [name='qa_memo']").val($("#qna_memo_"+qa_id).html()).focus();
});

$(document).on("click", ".reply", function (){
	var qa_id = $(this).data("qa_id");
	$(".qna_comment_wrap").html("");
	$("[name='item_qna_list'] [name='mode']").val("reply");
	$("[name='item_qna_list'] [name='qa_id']").val();
	$("[name='item_qna_list'] [name='qa_parent']").val(qa_id);
	$(".qna_comment_wrap").html("");	
	$("#reply_qna_"+qa_id).html($("#reply_div_qna").html());
	$("[name='item_qna_list'] [name='qa_memo']").val("").focus();		
});
//-->
</script>