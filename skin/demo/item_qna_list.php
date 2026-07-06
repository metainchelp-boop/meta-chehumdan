<style>
/* 상품문의리스트*/
.qna_list { border-bottom:solid 1px #e5e5e5; }
.qna_list .q_li { padding:15px 10px; border-top:solid 1px #e5e5e5; }
.qna_list .a_li { padding:15px 10px 15px 25px; border-top:dashed 1px #e5e5e5; }

.qna_list .qna_memo { color:#444; font-size:13px; padding:15px 10px 15px; line-height:20px; }

.qna_list .q_li .qna_list_name { position:relative; font-size:13px; color:#444; padding-left:38px; letter-spacing:-1px; }
.qna_list .q_li .qna_list_name b { width:32px; height:22px; top:0px; background-color:#2fcbe0; color:#fff; line-height:22px; text-align:center;left:0px; border-radius:10px; font-size:11px;position:absolute; font-weight:normal;} 

.qna_list .a_li .qna_list_name { position:relative; font-size:13px; color:#444; padding-left:45px; letter-spacing:-1px; }
.qna_list .a_li .qna_list_name b { width:32px; height:22px; top:0px; left:10px; background-color:#9ea5ae; color:#fff; line-height:22px; text-align:center; border-radius:10px; font-size:11px; position:absolute; font-weight:normal;}}
.qna_list .a_li .qna_list_name i { width:6px; height:6px; top:5px; left:0px; position:absolute;  background:url('/skin/nfor/img/layout.png') no-repeat ;  background-position:-100px -350px; background-size:320px auto; }

.qna_list .qna_list_date { height:22px;  font-size:11px; color:#999;} 

.qna_list .qna_list_btn { width:100%; text-align:right; margin-top:0px; }
.qna_list .qna_list_btn button { padding:0px; margin:0px;  width:40px; height:25px; line-height:25px; text-align:center; font-size:12px; text-decoration:none; cursor:pointer; }

.reply { border:solid 1px #9ea5ae; background:#fff; color:#9ea5ae; }
.update { border:solid 1px #9ea5ae; background:#fff; color:#9ea5ae; }
.delete { border:solid 1px #e83862; background:#fff; color:#e83862; }

/* 더보기버튼 */
.qna_list_more { display:block; letter-spacing:-1px; color:#999; height:40px; line-height:40px; font-size:14px; text-align:center; border-top:solid 1px #f4f4f4; }
.qna_list_more b { display:inline-block; width:11px; height:7px; background:url('/skin/nfor/img/layout.png') no-repeat; background-position:-200px -250px; background-size:320px auto; } 

#qna_form { display:none; }
.qna_form_wrap { padding:10px;  position:relative;  }
.qna_form_wrap h3{font-size: 17px;margin: 20px 0px 10px;; font-family: 'NanumGothicBold';}
.qna_form_wrap .qa_inner{position:relative; }
.caution { margin-top:10px;margin-bottom:20px }
.caution li { font-size:12px; color:#9ea5ae;line-height:18px; ;}
.qna_form_wrap .qa_memo { display:inline-block;  width:655px;;height:80px; margin-bottom:10px;  border: 1px solid #cacaca;font-family: 'Nanum Gothic'; padding:10px;box-sizing:border-box; -webkit-box-sizing:border-box; -moz-box-sizing:border-box; }
.qna_form_wrap .btn_item_qna_submit{position:absolute; width:133px; height:80px; border:solid 1px #e83862; background-color:#fff; color:#e83862; letter-spacing:-.07em; display:inline-block; }
.list_button { overflow:hidden; position:absolute; top:10px;right:10px; }
.list_button li { float:left; width:50%; padding-right:3px; -webkit-box-sizing:border-box; -moz-box-sizing:border-box; box-sizing:border-box; }
.list_button li:nth-child(2) { padding:0px; }
.list_button li .btn_item_qna_cancel_button {display:inline-block; margin-right:5px; border:solid 1px #9ea5ae;; background-color:#fff; color:#9ea5ae; letter-spacing:-.07em; }
.list_button li .btn_item_qna_button { margin-left:0px; border:solid 1px #e83862; background-color:#fff; color:#e83862; letter-spacing:-.07em; width:111px;height:80px; }
</style>


<!-- 신규 -->
<form name="item_qna_form" id="item_qna_form" method="post">
<input type="hidden" name="mode" value="insert">
<input type="hidden" name="qa_it_id" value="<?=$item[it_id]?>">
<div class="qna_form_wrap">
	<h3>상품문의</h3>
		<ul class="caution">
		<li>전화번호, 성명, 주소, 이메일을 남기시면 타인에 의해 도용될 수 있습니다. Q&A에 개인정보를 남기지 말아주세요.</li>
		<li>상품과 관련없는 글(비방, 홍보, 도배 등)은 예고없이 삭제됩니다.</li>
	</ul>
	<div class="qa_inner">
	<textarea name="qa_memo" class="qa_memo" placeholder="문의 내용을 입력해주세요"></textarea>
	<input type="button" value="상품문의등록" class="btn_item_qna_submit">
	</div>
</div>
</form>
<!-- //신규 -->


<!-- 수정답변삭제 -->
<form name="item_qna_list" id="item_qna_list" method="post">
<input type="hidden" name="mode" id="mode">
<input type="hidden" name="qa_it_id" value="<?=$item[it_id]?>">
<input type="hidden" name="qa_parent" id="qa_parent">
<input type="hidden" name="qa_id" id="qa_id">

<ul class="qna_list">
<?php
for($i=0; $i<count($return["qna_list"]); $i++){
	$qna = $return["qna_list"][$i];
?>
<li class="q_li" id="qa_li_<?=$qna[qa_id]?>">
	<div>
		<span class="qna_list_name"><b>질문</b> <?=$qna[qa_mb_id]?>&nbsp; | &nbsp;</span>  
		<span class="qna_list_date"><?=$qna[qa_insert_datetime]?></span>
		<div style="clear:both;"></div>
	</div>
	<div class="qna_comment_wrap" id="update_qna_<?=$qna[qa_id]?>"></div>
	<div class="qna_memo" id="qna_memo_<?=$qna[qa_id]?>"><?=$qna[qa_memo]?></div>
	<? if($qna[qa_access]=="1"){ ?>
	<div class="qna_list_btn">
		<button type="button" class="reply" data-qa_id="<?=$qna[qa_id]?>">답변</button>
		<button type="button" class="update" data-qa_id="<?=$qna[qa_id]?>">수정</button>
		<button type="button" class="delete" data-qa_id="<?=$qna[qa_id]?>">삭제</button>
	</div>
	<? } ?>
	<div class="qna_comment_wrap" id="reply_qna_<?=$qna[qa_id]?>"></div>
</li>
<?php
	for($k=0; $k<count($qna["reply"]); $k++){
		$qna_reply = $qna["reply"][$k];
?>
<li class="a_li" id="qa_li_<?=$qna_reply[qa_id]?>">
	<div>
		<span class="qna_list_name"><i></i><b>답변</b> &nbsp;<?=$qna_reply[qa_mb_id]?></span>
		<span class="qna_list_date"><?=$qna_reply[qa_insert_datetime]?></span>
		<div style="clear:both;"></div>
	</div>
	<div class="qna_comment_wrap" id="update_qna_<?=$qna_reply[qa_id]?>"></div>
	<div class="qna_memo" id="qna_memo_<?=$qna_reply[qa_id]?>"><?=$qna_reply[qa_memo]?></div>
	<? if($qna_reply[qa_access]=="1"){ ?>
	<div class="qna_list_btn">
		<button type="button" class="update" data-qa_id="<?=$qna_reply[qa_id]?>">수정</button>
		<button type="button" class="delete" data-qa_id="<?=$qna_reply[qa_id]?>">삭제</button>
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
<a class="qna_list_more">더보기 <b></b></a>
<? } ?>

</form>
<!-- //수정답변삭제 -->


<!-- 답변수정폼 -->
<div id="qna_form">
	<div class="qna_form_wrap">
		<textarea name="qa_memo" class="qa_memo" placeholder="문의 내용을 입력해주세요"></textarea>		
		<ul class="list_button">
			<li style="display:none;"><input type="button" value="취소하기" class="btn_item_qna_cancel_button"></li>
			<li><input type="button" value="답변달기" class="btn_item_qna_button"></li>
		</ul>		
	</div>
</div>
<!-- //답변수정폼 -->


<? if($scroll_load){ ?>
<div id="qna_loading_wait" class="loading_wait">
	<img src="<?=$nfor[skin_path]?>img/ajax-loader.gif">
	<span>목록을 불러오고 있습니다</span>
	<p>잠시만 기다려 주세요</p>
</div>

<script type="text/html" id="item_qna_list_script">
<li class="q_li" id="qa_li_<%=qa_id%>">
	<div>
		<span class="qna_list_name"><b>질문</b> <%=qa_mb_id%>&nbsp; | &nbsp;</span>  
		<span class="qna_list_date"><%=qa_insert_datetime%></span>
		<div style="clear:both;"></div>
	</div>
	<div class="qna_comment_wrap" id="update_qna_<%=qa_id%>"></div>
	<div class="qna_memo" id="qna_memo_<%=qa_id%>"><%=qa_memo%></div>
	<% if(qa_access=="1"){ %>
	<div class="qna_list_btn">
		<button type="button" class="reply" data-qa_id="<%=qa_id%>">답변</button>
		<button type="button" class="update" data-qa_id="<%=qa_id%>">수정</button>
		<button type="button" class="delete" data-qa_id="<%=qa_id%>">삭제</button>
	</div>
	<% } %>
	<div class="qna_comment_wrap" id="reply_qna_<%=qa_id%>"></div>
</li>
<%
_(reply).each(function(data){
%>
<li class="a_li" id="qa_li_<%=data.qa_id%>">

	<div>
		<span class="qna_list_name"><i></i><b>답변</b> &nbsp;<%=data.qa_mb_id%></span>
		<span class="qna_list_date"><%=data.qa_insert_datetime%></span>
		<div style="clear:both;"></div>
	</div>

	<div class="qna_comment_wrap" id="update_qna_<%=data.qa_id%>"></div>

	<div class="qna_memo" id="qna_memo_<%=data.qa_id%>"><%=data.qa_memo%></div>

	<% if(data.qa_access=="1"){ %>
	<div class="qna_list_btn">
		<button type="button" class="update" data-qa_id="<%=data.qa_id%>">수정</button>
		<button type="button" class="delete" data-qa_id="<%=data.qa_id%>">삭제</button>
	</div>
	<% } %>
	
</li>
<%
});
%>
</script>

<script>
var is_last_qna = 0;
var qna_page = 1;
var qna_total_page = "<?=$total_page?>";

$(document).on("click", ".qna_list_more", function (e){
	if(is_last_qna==0){
		++qna_page;
		item_qna_list_load();
	}
	if(qna_total_page <= qna_page){
		$('.qna_list_more').hide();
	}
});

function item_qna_list_load(){
	$("#qna_loading_wait").show();
	if(qna_page==1){
		$(".qna_list").html("");
	}
	$.ajax({
		type     : "get",
		url      : "item_qna_list.php",
		data     : "json=list&page="+qna_page+"&it_id="+it_id,
		dataType : "json",
		cache: false,
		success  : function(data) {
			if(data.count > 0){
				template = _.template($("#item_qna_list_script").html());
				var template_html = "";
				for(var i=0; i<data.qna_list.length; i++) {
					template_html +=  template({qa_id: data.qna_list[i].qa_id
						, qa_insert_datetime: data.qna_list[i].qa_insert_datetime
						, qa_access: data.qna_list[i].qa_access
						, qa_mb_id: data.qna_list[i].qa_mb_id						
						, qa_memo: data.qna_list[i].qa_memo
						, reply: data.qna_list[i].reply});
				}
				$(".qna_list").append(template_html);
			}

			$("#qna_loading_wait").hide();		
			
			if(data.last_page == 1){
				is_last_qna++;
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
$(document).on("click", ".btn_item_qna_cancel_button", function (){
	$("#mode").val("");
	$("#qa_id").val("");
	$("#qa_parent").val("");
	$(".qna_comment_wrap").html("");
	$(".qna_memo").show();
	$(".qna_list_btn").show();
});

$(document).on("click", ".qna_list .update", function (){
	var qa_id = $(this).data("qa_id");
	$("#mode").val("update");
	$("#qa_id").val(qa_id);
	$(".qna_comment_wrap").html("");
	$("#update_qna_"+qa_id).html($("#qna_form").html());
	$(".qna_list_btn").show();
	$("#qa_li_"+qa_id+" .qna_list_btn").hide();
	$("#item_qna_list .btn_item_qna_button").val("수정하기");
	$(".qna_memo").show();
	$("#qna_memo_"+qa_id).hide();
	$("#item_qna_list .qa_memo").val($("#qna_memo_"+qa_id).html()).focus();
});	

$(document).on("click", ".reply", function (){
	var qa_id = $(this).data("qa_id");
	$("#mode").val("reply");
	$("#qa_parent").val(qa_id);
	$(".qna_comment_wrap").html("");
	$("#reply_qna_"+qa_id).html($("#qna_form").html());
	$(".qna_list_btn").show();
	$("#qa_li_"+qa_id+" .qna_list_btn").hide();
	$("#item_qna_list .btn_item_qna_button").val("답변달기");
	$(".qna_memo").show();
	$("#item_qna_list .qa_memo").focus();
});

$(document).on("click", ".qna_list .delete", function (){
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
						qna_page = 1;
						item_qna_list_load();
					}
				} else{
					alert(json["msg"]);
				}
			}
		});
	}
});

$(document).on("click", ".btn_item_qna_button", function(){
	var qa_memo = $("#item_qna_list .qa_memo").val();
	if(!qa_memo){
		alert("내용을 입력해주세요");
		$("#item_qna_list .qa_memo").focus();
		return;
	}
	$.ajax({
		type: "post",
		data : $("#item_qna_list").serialize(),
		url: "item_qna_form.php",
		success: function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				$(".qa_memo").val("");
				qna_page = 1;
				item_qna_list_load();
			} else{
				alert(json["msg"]);
			}
		}
	});
});	

$(document).on("click", ".btn_item_qna_submit", function(){
	var qa_memo = $("#item_qna_form .qa_memo").val();
	if(!qa_memo){
		alert("내용을 입력해주세요");
		$("#item_qna_form .qa_memo").focus();
		return;
	}
	$.ajax({
		type: "post",
		data : $("#item_qna_form").serialize(),
		url: "item_qna_form.php",
		success: function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				$(".qa_memo").val("");
				qna_page = 1;
				item_qna_list_load();
				$("#item_qna_form .qa_memo").focus();
			} else{
				alert(json["msg"]);
			}
		}
	});
});	
</script>