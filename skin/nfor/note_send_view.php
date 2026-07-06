<?php
include_once $nfor[skin_path]."head.php";
?>

<?php
include_once $nfor[skin_path]."note_inc.php";
?>
<div class="note_wrap">
<div class="note_receive_top">
	<span>보낸사람  <b class="txt_num"><?=$note[no_receive_id]?></b></span>
	<span>받은시간 <b class="txt_num"><?=$note[no_send_datetime]?></b></span>
</div>

<div class="view_memo"><?=$note[no_memo]?></div>

<div class="board_btn_zone mar_top10">
<ul>
<li><span class="btn_pack"><a href="note_send_list.php" class="btn_lg black">목록보기</a></span></li>
<li><span class="btn_pack"><a data-confirm="삭제하시겠습니까?" data-data="mode=delete&no_id=<?=$note[no_id]?>" class="nfor_button btn_lg white">삭제</a></span></li>
</ul>
</div>

</div>



<?php
include_once $nfor[skin_path]."tail.php";
?>