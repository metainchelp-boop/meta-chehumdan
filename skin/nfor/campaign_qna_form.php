<?php
include_once "head.php";
?>
<style>
.campaign_qna{position:relative;  width:100%;padding:0px;  -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; background-color:#ffffff;}
.campaign_qna .title{ padding: 20px 0 15px; margin: 0 20px; font-size: 18px; font-weight: 700; color: #222; line-height: 1.5;border-bottom: 1px solid #333;  text-align: left; c;}
.campaign_qna .inner{padding: 10px 0; margin: 0 20px; position:relative; }
.close_btn{margin-left:7px; display:none; }
.close_btn{overflow: hidden; position: absolute; top: 15px; right:15px; width: 30px; height: 30px;  padding-top: 0px;  border: 5px solid #fafafa; background: #fafafa url(/skin/demo/img/btn_layer_close.png) no-repeat center; transform: rotate(0);transition: .5s;  border-radius: 60px;}
.close_btn:hover{transform: rotate(180deg); transition:.3s;}

.campaign_qna_write{border-top:1px solid #fff;padding: 0; margin:20px 0px;}
.campaign_qna_write textarea { position: relative;  z-index: 3; width: 100%; height: 350px; padding: 10px; border: 1px solid #d9d9d9;vertical-align: top;  font-size: 16px; color: #333;   line-height: 1.5; font-weight: 333; background: transparent;   -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}
.toptop .itname{font-size:18px; color:#000; margin-top:15px;}
.toptop .it_description{font-size:14px; letter-spacing:-1px; margin-top:10px; margin-bottom:10px;}

</style>
<div class="campaign_qna">

	<div class="inner">
		<div class="toptop">
			<div class="itname"><?=$campaign[cp_subject]?></div>
			<div class="it_description" ><?=$campaign[cp_description]?></div>
		</div>
		<div class="campaign_qna_write">
			<form name="campaign_qna_form" id="campaign_qna_form" method="post">
				<input type="hidden" name="mode" value="insert">
				<input type="hidden" name="qa_cp_id" value="<?=$campaign[cp_id]?>">

			<textarea name="qa_memo" class="qa_memo" placeholder="문의 내용을 입력해주세요"></textarea>
			</div>
			<div class="board_btn_zone">
			<span class="btn_pack"><input type="button" value="문의하기" class="btn_item_qna_submit btn_lg black"></span>
			</div>
			
			</form>

	</div>
</div>
<script>
$(document).on("click", ".btn_item_qna_submit", function(){
	var qa_memo = $("#campaign_qna_form .qa_memo").val();
	if(!qa_memo){
		alert("내용을 입력해주세요");
		$("#campaign_qna_form .qa_memo").focus();
		return;
	}
	$.ajax({
		type: "post",
		data : $("#campaign_qna_form").serialize(),
		url: "campaign_qna_form.php",
		success: function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				$(".qa_memo").val("");
				qna_page = 1;
				alert("문의가 접수되었습니다\n답변확인은 마이페이지 > 캠페인문의/답변내역 메뉴에서 확인가능합니다");
				window.close();
			} else{
				alert(json["msg"]);
			}
		}
	});
});	
</script>
<?php
include_once "tail.php";
?>