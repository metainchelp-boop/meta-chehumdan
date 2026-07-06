<?php
include_once $nfor[skin_path]."note_head.php";
?>

<?php
include_once $nfor[skin_path]."note_inc.php";
?>
<div class="note_receive_top">
	<span>받은쪽지 <b class="txt_num point_color3"><?=$total_count?> </b>통</span>  <span>읽지 않은 쪽지 <b class="txt_num point_color3"><?=number_format($member[mb_note])?> </b>통</span>
</div>
<form name="flist" id="flist" method="post">
	<input type="hidden" name="mode" id="mode">
	<div class="data_table thead_colored">
		<table cellpadding="0" cellspacing="0" border="0">
			<colgroup>
				<col style="width:10%;">
				<col style="width:10%">
				<col >
				<col style="width:10%">
				<col style="width:10%">
			</colgroup>
		<tr>
			<th><?=admin_checkbox($row,"chkall")?></th>
			<th>보낸사람</th>
			<th>내용</th>
			<th>보낸시간</th>
			<th>읽은시간</th>
		</tr>
		<tbody class="note_list">
		<?php
		for($i=0; $i<count($return["list"]); $i++){
			$row = $return["list"][$i];
			$row["chk[]"] = $i;
			$row["no_id[{$i}]"] = $row[no_id];
		?>
		<tr>
			<td><?=admin_checkbox($row,"chk[]","chk")?><?=admin_hidden($row,"no_id[{$i}]")?></td>
			<td class="txt_num date"><?=$row[no_send_id]?></td>
			<td class="left_txt"><a href="note_receive_view.php?no_id=<?=$row[no_id]?>"><?=$row[no_memo]?></a></td>
			<td><a href="note_receive_view.php?no_id=<?=$row[no_id]?>" class="txt_num date"><?=$row[no_send_datetime]?></a></td>
			<td><a href="note_receive_view.php?no_id=<?=$row[no_id]?>" class="txt_num date"><?=$row[no_receive_datetime]?></a></td>
		</tr>
		<?php
		} 
		if(!$i){
		?>
		<tr>
			<td colspan="5">등록된 쪽지가 없습니다.</td>
		</tr>
		<?php } ?>
		</tbody>
		</table>
	</div>
<div class="board_btn_zone">
	<span class="btn_pack"><?=admin_button("list_delete", "선택삭제", "btn_lg black")?></span>
</div>

<div class="page_center"><?=$pagelist?></div>

</form>

<?php
include_once $nfor[skin_path]."note_tail.php";
?>
