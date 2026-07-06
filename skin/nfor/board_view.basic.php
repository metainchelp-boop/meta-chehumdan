<div class="board_view">
	<ul>
		<li class="subject">
			<div class="view_title">
				<strong class="name"><?=$write[nf_mb_nick]?></strong>
				<div class="avatar" <?php if($write[nf_mb_photo]){ ?>style="background:url(<?=$write[nf_mb_photo]?>)"<?php } ?>></div>
				<p class="tit"><?=$write[nf_subject]?></p>
				<span class="reple">댓글  <b class="txt_num"><?=$write[nf_comment]?></b></span>
				<span class="count">조회수 <b class="txt_num"><?=$write[nf_hit]?></b></span>
				<span class="txt_num"><?=$write[nf_datetime]?></span>
			</div>
		</li>
		<li class="content"><?=$write[nf_memo]?></li>
	</ul>
</div>
<div  class="thumb_wrap">
	<ul>
		<?php
		for($k=0; $k<count($write[nf_img_thumb]); $k++){
		?>
		<li><a href="<?=$write[nf_img][$k]?>"><img src="<?=$write[nf_img_thumb][$k]?>"></a></li>
		<?php } ?>
	</ul>
</div>
<div class="like_wrap">
	<a class="btn_like <?=$write[nf_like_is]?"on":""?>" data-nf_id="<?=$write[nf_id]?>"><i></i> 좋아요<span><?=$write[nf_like]?></span></a>
</div>
<div class="board_btn_zone paddinglr paddingtb">
	<ul>
		<li><span class="btn_pack"><a href="board_list.php?tbl=<?=$board[bo_tbl]?>&nf_category=<?=$nf_category?>&page=<?=$page?>" class="btn_md black">목록보기</a></span></li>
		<?php if($member[mb_no] and ($is_admin or $write[nf_mb_no]==$member[mb_no])){ ?>
		<li><span class="btn_pack"><a href="board_form.php?tbl=<?=$board[bo_tbl]?>&nf_id=<?=$write[nf_id]?>" class="btn_md white">수정하기</a></span></li>
		<li><span class="btn_pack"><a class="btn_md white del_btn" data-nf_id="<?=$write[nf_id]?>">삭제하기</a></span>
		<?php } ?>
	</ul>
</div>
<a name="comment_list"></a>

<?php include_once "board_comment_list.php"; ?>

<script>
$(document).on("click", ".del_btn", function(){
	var nf_id = $(this).data("nf_id");
	
	if(confirm("삭제하신 데이터는 복구할수 없습니다\n정말 삭제하시겠습니까?")){
		$.ajax({
			type: "post",
			data : "mode=delete&tbl="+tbl+"&nf_id="+nf_id,
			url: "<?=basename($PHP_SELF)?>",
			success: function(response){
				var json = $.parseJSON(response);
				if(json["result"]=="ok"){
					location.href="board_list.php?tbl="+tbl;
				} else{
					alert(json["msg"]);
				}
			}
		});
	}
});

$(document).on("click", ".btn_like", function(){
	var nf_id = $(this).data("nf_id");
	var btn_like = $(this);
	$.ajax({
		type: "post",
		data : "mode=like&tbl="+tbl+"&nf_id="+nf_id,
		url: "<?=basename($PHP_SELF)?>",
		success: function(response){
			var json = $.parseJSON(response);			
			if(json["result"]=="login"){
				alert("로그인하셔야 이용가능합니다");		
			} else if(json["result"]=="delete"){
				btn_like.removeClass("on");
				btn_like.find("span").html(json["nf_like"]);
			} else if(json["result"]=="insert"){
				btn_like.addClass("on");
				btn_like.find("span").html(json["nf_like"]);
			} else{
				alert(json["msg"]);
			}
		}
	});
});
</script>