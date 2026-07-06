<?php
include_once $nfor[skin_path]."head.php";
?>

<?php
include_once $nfor[skin_path]."note_inc.php";
?>

<div class="note_wrap">
<div class="note_receive_top">
<span>받은쪽지  <b class="txt_num point-color1"><?=$total_count?>통</b></span> 
<span>읽지 않은 쪽지 <b class="txt_num point-color1"><?=number_format($member[mb_note])?></b>통</span> 
<div class="allchk"><?=admin_checkbox($row,"chkall")?> 전체선택</div>
</div>
<form name="flist" id="flist" method="post">
<input type="hidden" name="mode" id="mode">



<?php
for($i=0; $i<count($return["list"]); $i++){
	$row = $return["list"][$i];
	$row["chk[]"] = $i;
	$row["no_id[{$i}]"] = $row[no_id];
?>
<div class="note_box">
	<span class="chck"><?=admin_checkbox($row,"chk[]","chk")?><?=admin_hidden($row,"no_id[{$i}]")?></span>
	<a href="note_receive_view.php?no_id=<?=$row[no_id]?>" >
	<span class="memo"><?=$row[no_memo]?></span>
	<div class="bottom_info">
	<span class="name"><?=$row[no_send_id]?></span>
	<span class="time txt_num">보낸시간 : <?=$row[no_send_datetime]?></span> /
	<span class="time txt_num">받은시간: <?=$row[no_receive_datetime]?></span>
	</div>
	</a>
</div>
<?php
} 
if(!$i){
?>
<div class="sch_no_data">
		<p>등록된 쪽지가 없습니다.</p>
</div>

<?php } ?>

<div class="board_btn_zone mar_top10"><span class="btn_pack"><?=admin_button("list_delete", "선택삭제", "btn_lg black")?></span></div>

<div class="page_center"><?=$pagelist?></div>

</form>
</div>
<?php
include_once $nfor[skin_path]."tail.php";
?>
