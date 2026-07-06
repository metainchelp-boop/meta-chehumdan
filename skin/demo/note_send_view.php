<?php
include_once $nfor[skin_path]."note_head.php";
?>

<?php
include_once $nfor[skin_path]."note_inc.php";
?>

<div class="note_view">
	<ul>
		<li class="subj"><span>보낸사람 <b class="txt_num"> <?=$note[no_receive_id]?></b></span><span>받은시간 <b class="txt_num point_color3"><?=$note[no_send_datetime]?></b></span></li>
		<li class="cont"><span><?=$note[no_memo]?></span></li>
	</ul>
</div>

<div class="board_btn_zone">
	<span class="btn_pack"><a href="note_send_list.php" class="btn_lg black">목록보기</a></span>
	<span class="btn_pack"><a data-confirm="삭제하시겠습니까?" data-data="mode=delete&no_id=<?=$note[no_id]?>" class="nfor_button btn_lg white">삭제</a></span>
</div>



<?php
include_once $nfor[skin_path]."note_tail.php";
?>