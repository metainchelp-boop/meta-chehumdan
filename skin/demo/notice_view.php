<?php
include_once $nfor[skin_path]."cus_head.php";
?>
<div class="bstyle1_view">
	<ul>
		<li>
			<div class="tit_wrap">
				<span class="cate">[<?=$notice[no_category]?>] </span>
				<span class="subject"><?=$notice[no_subject]?></span>
			</div>
			<div class="count_wrap">
				<span class="date">날짜 <b><?=$notice[no_insert_datetime]?></b></span>
				<span class="count">조회수 <b><?=$notice[no_hit]?></b></span>
			</div>
		</li>
		<li class="con">	<?=$notice[no_memo]?></li>
	</ul>
	</div>
	<div class="board_btn_zone">
	<span class="btn_pack"><a href="notice_list.php" class="btn_lg black">목록보기</a></span>
</div>

<?php
include_once $nfor[skin_path]."cus_tail.php";
?>