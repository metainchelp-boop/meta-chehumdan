<?php
include_once $nfor['skin_path']."head.php";
?>

<style>
.customer_view_wrap { margin:0px; padding:15px; width:100%; box-sizing:border-box; -webkit-box-sizing:border-box;  }
.customer_view_wrap .customer_view_box{ position:relative; background-color:#FFF; padding:20px;  box-sizing:border-box; -webkit-box-sizing:border-box; margin-bottom:10px; }
.customer_view_wrap .customer_view_box .titq{ display:inline-block; font-size:.7em; background-color: #666; color:#fff; padding: 3px 5px;border-radius:2px; margin-bottom:5px;}
.customer_view_wrap .customer_view_box .cs_insert_datetime {position:absolute; top:10px; right:10px; font-size:11px; color:#999; margin-top:5px; }
.customer_view_wrap .customer_view_box .cs_subject { font-size:.9em; color:#000; line-height:18px; font-weight:bold;  padding-bottom:5px; margin-bottom:5px;  letter-spacing:-.0625em;}

.customer_view_wrap .customer_view_box .files { font-size:.8em; padding:20px 0px; }

.customer_view_wrap .customer_view_box .cs_memo { font-size:12px; color:#555; margin-bottom:15px;}
.customer_view_wrap .customer_view_box .tita{display:inline-block; font-size:.7em; border-radius:2px; margin-bottom:5px; font-size:.7em; background-color: #ff3478; color:#fff; padding: 3px 5px;margin-bottom:5px;}
.customer_view_wrap .customer_view_box .tita:before { display:inline-block; position:relative; top:-1px; width:6px; height:5px; margin-right:3px; border-bottom:1px solid #fff; border-left:1px solid #fff; vertical-align:middle; content: ""; }
.customer_view_wrap .customer_view_box .cs_ing { padding:15px; font-size:.9em; color:#666;  letter-spacing:-.0625em; text-align:center; }
.reply_wrap { border-top:solid 1px #EFEFEF; margin-bottom:10px; padding-top:10px; position:relative; }
</style>

<div class="customer_view_wrap">

	<div class="customer_view_box">

		<span class="titq"><?=$customer['cs_category']?></span>
		<p class="cs_insert_datetime"><?=$customer['cs_insert_datetime']?></p>
		<p class="cs_subject"><?=$customer['cs_subject']?></p>
		<p class="cs_memo"><?=$customer['cs_memo']?></p>

		<?php if($customer['cs_file_name']){ ?>
		<div class="files">
			<?php	
			for($i=0; $i<count($customer['cs_file_name']); $i++){
				if($i) echo ", ";
				echo "<a href='".$customer['cs_file_href'][$i]."'>";
				echo "#첨부파일 ".$customer['cs_file_name'][$i];
				echo "</a>";
			}
			?>
		</div>
		<?php } ?>

		<div class="reply_wrap">
			<span class="tita">답변내용</span>
			<?php if($customer['cs_reply_state']=="2"){ ?>
				<p class="cs_insert_datetime"><?=$customer['cs_reply_datetime']?></p>
				<p class="cs_memo"><?=$customer['cs_reply_memo']?></p>
			<?php } else{ ?>
				<p class="cs_ing">답변을 준비중입니다.</p>
			<?php } ?>
		</div>

	</div>

	<a class="btn_customer_back basic_btn color">목록보기</a>

</div>

<script>
$(document).on("click", ".btn_customer_back", function(){
	history.back();
});
</script>

<?php
include_once $nfor['skin_path']."tail.php";
?>