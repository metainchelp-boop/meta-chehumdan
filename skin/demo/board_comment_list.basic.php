<style>
/* 상품문의리스트*/
 textarea{padding:10px; box-sizing:border-box; -webkit-box-sizing:border-box; -moz-box-sizing:border-box;}
</style>


<!-- 신규 -->
<form name="comment_form" id="comment_form" method="post">
<input type="hidden" name="mode" value="insert">
<input type="hidden" name="tbl" value="<?=$board[bo_tbl]?>">
<input type="hidden" name="ct_nf_id" value="<?=$write[nf_id]?>">

<div >
	<h3 style="font-size:24px; font-weight:normal; margin-bottom:10px;">댓글</h3>
	<div class="reply_input">
		<div class="avatar" <?php if($write[mb_photo]){ ?>style="background:url(<?=$write[mb_photo]?>)"<?php } ?>></div>
		<div class="right">
			<textarea name="ct_memo" class="ct_memo" placeholder="내용을 입력해주세요"></textarea>
		</div>
	<input type="button" value="댓글등록" class="btn_comment_submit reply_submit">
	</div>
</div>
</form>
<!-- //신규 -->


<!-- 수정답변삭제 -->
<form name="comment_list_form" id="comment_list_form" method="post">
<input type="hidden" name="mode" id="mode">
<input type="hidden" name="tbl" value="<?=$board[bo_tbl]?>">
<input type="hidden" name="ct_nf_id" value="<?=$write[nf_id]?>">
<input type="hidden" name="ct_parent" id="ct_parent">
<input type="hidden" name="ct_id" id="ct_id">

<ul class="comment_list">
<?php
for($i=0; $i<count($return["comment_list"]); $i++){
	$comment = $return["comment_list"][$i];
?>
<li class="q_li" id="ct_li_<?=$comment[ct_id]?>">
	<div class="avatar" <?php if($comment[ct_mb_photo]){ ?>style="background:url(<?=$comment[ct_mb_photo]?>)"<?php } ?>></div>
	<div  style="padding-left:100px;">
		<div>
				<span class="comment_list_name"><b>질문</b> <?=$comment[ct_mb_nick] ? $comment[ct_mb_nick] : $comment[ct_mb_nick]?></span>
				<span class="comment_list_date txt_num"><?=$comment[ct_insert_datetime]?></span>
				<div style="clear:both;"></div>
		</div>
		<div class="comment_wrap" id="update_comment_<?=$comment[ct_id]?>"></div>
		<div class="comment_memo" id="comment_memo_<?=$comment[ct_id]?>"><?=$comment[ct_memo]?></div>
	</div>
	
	<div class="comment_list_btn">
		<span class="btn_pack"><button type="button" class="reply btn_sm white" data-ct_id="<?=$comment[ct_id]?>">답변</button></span>
		<?php if($comment[ct_access]){ ?>
		<span class="btn_pack"><button type="button" class="update btn_sm white" data-ct_id="<?=$comment[ct_id]?>">수정</button></span>
		<span class="btn_pack"><button type="button" class="delete btn_sm white" data-ct_id="<?=$comment[ct_id]?>">삭제</button></span>
		<?php } ?>
	</div>
	
	<div class="comment_wrap" id="reply_comment_<?=$comment[ct_id]?>"></div>
</li>
<?php
	for($k=0; $k<count($comment["reply"]); $k++){
		$comment_reply = $comment["reply"][$k];
?>
<li class="a_li" id="ct_li_<?=$comment_reply[ct_id]?>">
	<div class="re"><i></i></div>
	<div class="avatar" <?php if($comment_reply[ct_mb_photo]){ ?>style="background:url(<?=$comment_reply[ct_mb_photo]?>)"<?php } ?>></div>
	<div  style="padding-left:100px;">
	<div>
		<span class="comment_list_name"><?=$comment_reply[ct_mb_nick]?></span>
		<span class="comment_list_date"><?=$comment_reply[ct_insert_datetime]?></span>
		<div style="clear:both;"></div>
	</div>
	<div class="comment_wrap" id="update_comment_<?=$comment_reply[ct_id]?>"></div>
	<div class="comment_memo" id="comment_memo_<?=$comment_reply[ct_id]?>"><?=$comment_reply[ct_memo]?></div>
</div>
	<? if($comment_reply[ct_access]){ ?>
	<div class="comment_list_btn">
		<span class="btn_pack"><button type="button" class="update btn_sm white" data-ct_id="<?=$comment_reply[ct_id]?>">수정</button></span>
		<span class="btn_pack"><button type="button" class="delete btn_sm white" data-ct_id="<?=$comment_reply[ct_id]?>">삭제</button></span>
	</div>
	<? } ?>
</li>
<?
	}

}
?>
</ul>
<? if(!$scroll_load){ ?><div class="page_center"><?=$pagelist?></div><? } ?>

<? if($total_page>1){ ?>
<a class="comment_list_more">더보기 <b></b></a>
<? } ?>

</form>
<!-- //수정답변삭제 -->


<!-- 답변수정폼 -->
<div id="comment_reply_edit_form"  >
	<div class="comment_form_wrap ">
		<div class="ct_memo_wr">
		<textarea name="ct_memo" class="ct_memo" placeholder="문의 내용을 입력해주세요"></textarea>
		</div>
		<input type="button" value="답변달기" class="btn_comment_button">
	</div>
</div>
<!-- //답변수정폼 -->



<? if($scroll_load){ ?>
<div id="comment_loading_wait" class="loading_wait">
	<img src="<?=$nfor[skin_path]?>img/ajax-loader.gif">
	<span>목록을 불러오고 있습니다</span>
	<p>잠시만 기다려 주세요</p>
</div>

<script type="text/html" id="item_list_script">
<li class="q_li" id="ct_li_<%=ct_id%>">
	<div class="avatar" <% if(ct_mb_photo){ %>style="background:url(<%=ct_mb_photo%>)"<% } %>></div>
	<div  style="padding-left:100px;">
	<div>
		<span class="comment_list_name"><b>질문</b> <%=ct_mb_nick%></span>  
		<span class="comment_list_date"><%=ct_insert_datetime%></span>
		<div style="clear:both;"></div>
	</div>
	<div class="comment_wrap" id="update_comment_<%=ct_id%>"></div>
	<div class="comment_memo" id="comment_memo_<%=ct_id%>"><%=ct_memo%></div>
	</div>
	
	<div class="comment_list_btn">
		<span class="btn_pack"><button type="button" class="reply btn_sm white" data-ct_id="<%=ct_id%>">답변</button></span>
		<% if(ct_access=="1"){ %>
		<span class="btn_pack"><button type="button" class="update btn_sm white" data-ct_id="<%=ct_id%>">수정</button></span>
		<span class="btn_pack"><button type="button" class="delete btn_sm white" data-ct_id="<%=ct_id%>">삭제</button></span>
		<% } %>
	</div>
	
	<div class="comment_wrap" id="reply_comment_<%=ct_id%>"></div>
</li>
<%
_(reply).each(function(data){
%>
<li class="a_li" id="ct_li_<%=data.ct_id%>">
	<div class="re"><i></i></div>
	<div class="avatar" <% if(data.ct_mb_photo){ %>style="background:url(<%=data.ct_mb_photo%>)"<% } %>></div>
	<div  style="padding-left:100px;">
	<div>
		<span class="comment_list_name"><%=data.ct_mb_nick%></span>
		<span class="comment_list_date"><%=data.ct_insert_datetime%></span>
		<div style="clear:both;"></div>
	</div>

	<div class="comment_wrap" id="update_comment_<%=data.ct_id%>"></div>

	<div class="comment_memo" id="comment_memo_<%=data.ct_id%>"><%=data.ct_memo%></div>
	</div>
	<% if(data.ct_access=="1"){ %>
	<div class="comment_list_btn">
		<span class="btn_pack"><button type="button" class="update btn_sm white" data-ct_id="<%=data.ct_id%>">수정</button></span>
		<span class="btn_pack"><button type="button" class="delete btn_sm white" data-ct_id="<%=data.ct_id%>">삭제</button></span>
	</div>
	<% } %>
	
</li>
<%
});
%>
</script>

<script>
var is_last = 0;
var page = 1;
var nf_id = "<?=$write[nf_id]?>";
var total_page = "<?=$total_page?>";
var tbl = "<?=$board[bo_tbl]?>";

$(document).on("click", ".comment_list_more", function (e){
	if(is_last==0){
		++page;
		item_list_load();
	}
	if(total_page <= page){
		$('.comment_list_more').hide();
	}
});

function item_list_load(){

	$("#comment_loading_wait").show();
	if(page==1){
		$(".comment_list").html("");
	}
	$.ajax({
		type     : "get",
		url      : "board_comment_list.php",
		data     : "json=list&page="+page+"&nf_id="+nf_id+"&tbl="+tbl,
		dataType : "json",
		cache: false,
		success  : function(data) {
			if(data.count > 0){
				template = _.template($("#item_list_script").html());
				var template_html = "";
				for(var i=0; i<data.comment_list.length; i++) {
					template_html +=  template({ct_id: data.comment_list[i].ct_id
						, ct_insert_datetime: data.comment_list[i].ct_insert_datetime
						, ct_access: data.comment_list[i].ct_access
						, ct_mb_nick: data.comment_list[i].ct_mb_nick						
						, ct_memo: data.comment_list[i].ct_memo
						, ct_mb_photo: data.comment_list[i].ct_mb_photo
						, reply: data.comment_list[i].reply});
				}
				$(".comment_list").append(template_html);
			}

			$("#comment_loading_wait").hide();		
			
			if(data.last_page == 1){
				is_last++;
			}
			
		},
		error: function(e){
			console.log(e);
			console.log("Ajax failed");
		}
	});
}
</script>
<? } ?>

<script>


$(document).on("click", ".btn_comment_cancel_button", function (){
	$("#mode").val("");
	$("#ct_id").val("");
	$("#ct_parent").val("");
	$(".comment_wrap").html("");
	$(".comment_memo").show();
	$(".comment_list_btn").show();
});

$(document).on("click", ".comment_list .update", function (){
	var ct_id = $(this).data("ct_id");
	$("#mode").val("update");
	$("#ct_id").val(ct_id);
	$(".comment_wrap").html("");
	$("#update_comment_"+ct_id).html($("#comment_reply_edit_form").html());
	$(".comment_list_btn").show();
	$("#ct_li_"+ct_id+" .comment_list_btn").hide();
	$("#comment_list_form .btn_comment_button").val("수정하기");
	$(".comment_memo").show();
	$("#comment_memo_"+ct_id).hide();
	$("#comment_list_form .ct_memo").val($("#comment_memo_"+ct_id).html()).focus();
});	

$(document).on("click", ".reply", function (){
	var ct_id = $(this).data("ct_id");
	$("#mode").val("reply");
	$("#ct_parent").val(ct_id);
	$(".comment_wrap").html("");
	$("#reply_comment_"+ct_id).html($("#comment_reply_edit_form").html());
	$(".comment_list_btn").show();
	$("#ct_li_"+ct_id+" .comment_list_btn").hide();
	$("#comment_list_form .btn_comment_button").val("답변달기");
	$(".comment_memo").show();
	$("#comment_list_form .ct_memo").focus();
});

$(document).on("click", ".comment_list .delete", function (){
	var ct_id = $(this).data("ct_id");
	if(confirm("삭제하시겠습니까?")){
		$.ajax({
			type: "post",
			data: {
				"mode":"delete",
				"ct_id":ct_id,
				"tbl":tbl
			},
			url: "board_comment_form.php",
			success: function(response){
				var json = $.parseJSON(response); 
				if(json["result"]=="ok"){

					if(json["ct_delete"]=="1"){
						page = 1;
						item_list_load();
					} else{

						$("#ct_li_"+ct_id).remove();
						if($(".comment_list li").length < 1){
							page = 1;
							item_list_load();
						}

					}

				} else{
					alert(json["msg"]);
				}
			}
		});
	}
});

$(document).on("click", ".btn_comment_button", function(){
	var ct_memo = $("#comment_list_form .ct_memo").val();
	if(!ct_memo){
		alert("내용을 입력해주세요");
		$("#comment_list_form .ct_memo").focus();
		return;
	}
	$.ajax({
		type: "post",
		data : $("#comment_list_form").serialize(),
		url: "board_comment_form.php",
		success: function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				$(".ct_memo").val("");
				page = 1;
				item_list_load();
			} else{
				alert(json["msg"]);
			}
		}
	});
});	

$(document).on("click", ".btn_comment_submit", function(){
	var ct_memo = $("#comment_form .ct_memo").val();
	if(!ct_memo){
		alert("내용을 입력해주세요");
		$("#comment_form .ct_memo").focus();
		return;
	}
	$.ajax({
		type: "post",
		data : $("#comment_form").serialize(),
		url: "board_comment_form.php",
		success: function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				$(".ct_memo").val("");
				page = 1;
				item_list_load();
				$("#comment_form .ct_memo").focus();
			} else{
				alert(json["msg"]);
			}
		}
	});
});	
</script>