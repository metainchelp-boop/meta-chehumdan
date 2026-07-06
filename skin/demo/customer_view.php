<?php
include_once $nfor['skin_path']."mypage_head.php";
?>

<div class="bstyle1_view">
<ul>
	<li>
		<div class="tit_wrap">
			<span class="cate"><?=$customer['cs_category']?></span>
			<span class="subject"><?=$customer['cs_subject']?></span>
		</div>
		<div class="count_wrap">
			<span class="date">등록일  : <b> <?=$customer['cs_insert_datetime']?></b></span>
		</div>
	</li>
	<?php if($customer['cs_file_name']){ ?>
	<li class="files">
		<?php	
		for($i=0; $i<count($customer['cs_file_name']); $i++){
			if($i) echo ", ";
			echo "<a href='".$customer['cs_file_href'][$i]."'>";
			echo "#첨부파일 ".$customer['cs_file_name'][$i];
			echo "</a>";
		}
		?>
	</li>
	<?php } ?>
	<li class="con">
		<?=$customer['cs_memo']?>
	</li>
	<li class="answer">
		<?php if($customer['cs_reply_state']=="2"){ ?>
			<span class="memo"><b class="ans">답변내용</b> <div class="cs_reply_memo"><?=$customer['cs_reply_memo']?></div></span>
			<span class="reply_datetime txt_num"><?=$customer['cs_reply_datetime']?></span>
		<?php } else{ ?>
			<span class="ing">답변을 준비중입니다.</span>
		<?php } ?>
	</li>
</ul>
</div>

<div class="board_btn_zone"><span class="btn_pack"><a class="btn_customer_back btn_lg black">목록보기</a></span></div>

<script>
$(document).on("click", ".btn_customer_back", function(){
	history.back();
});
</script>

<?php
include_once $nfor['skin_path']."mypage_tail.php";
?>