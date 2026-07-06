<?php
$i = 0;
$que = sql_query("select * from nfor_board where bo_use='1' order by bo_rank desc");
while($data = sql_fetch_array($que)){
	$tbl_list[$i][bo_tbl] = $data[bo_tbl];
	$tbl_list[$i][bo_name] = $data[bo_name];
	$i++;
}
?>
<?
if(basename($PHP_SELF)=="board_form.php"){
?>
<? } else{ ?>
<div class="board_top">
		<select name="move_url" onchange="location.href=this.value">
		<?php
		for($i=0; $i<count($tbl_list); $i++){
		?>
		<option value="board_list.php?tbl=<?=$tbl_list[$i][bo_tbl]?>" <?=$tbl==$tbl_list[$i][bo_tbl]?"selected":""?>><?=$tbl_list[$i][bo_name]?>
		<?php } ?>
		</select>
		<div class="attendance">
			<a href="attendance.php" class="attendance_btn">출석체크하기</a>
			<!-- <div class="attendance_btn_ok">
				<span class="mon_att">이번달출석 <b class="num_att txt_num point_color4">4</b></span>
				<span class="all_att">총누적 출석 <b class="num_att txt_num point_color4"><?=number_format($member[mb_attendance])?></b></span>
			</div>	 -->
		</div>
	</div>
<?}?>