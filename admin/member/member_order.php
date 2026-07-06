<?php
include_once "path.php";

$nfor['title'] = "신청내역";

include_once "head.php";
?>


<table class="table row_tbl margin0">
<tr>
	<th>신청번호</th>
	<th>캠페인명/캠페인코드</th>
	<th>회원정보</th>
	<th>신청채널</th>
	<th>배송지정보</th>
	<th>신청일</th>
</tr>
<?php
$result = sql_query("select * from nfor_review where rv_delete='0' and rv_mb_no='$mb_no' order by rv_id desc");
for($i=0; $row=sql_fetch_array($result); $i++){	
		
		$row = nfor_tag_out($row);
?>
<tr>
	<td><?=$row['rv_id']?></td>
	<td><a href="<?=$nfor['path']?>/campaign.php?cp_id=<?=$row['rv_cp_id']?>" target="_blank"><?=$row['rv_cp_subject']?><br><?=$row['rv_cp_id']?></a></td>
	<td><?=campaign_member_info($row)?></td>
	<td class="textleft"><a href="<?=$row['rv_channel']?>" target="_blank"><?=$row['rv_channel']?></a></td>
	<td class="textleft">
	<?=$row['rv_dy_name']?> <?=$row['rv_dy_hp']?><br>
	<?=$row['rv_dy_zip']?> <?=$row['rv_dy_addr1']?> <?=$row['rv_dy_addr2']?>
	</td>
	<td><?=substr($row['rv_datetime'],0,10)?></td>
</tr>
<?php 
	$i++;
}
if(!$i){
?>
<tr>
	<td colspan="6">신청내역이 없습니다.</td>	
</tr>
<?php } ?>
</table>



<?php
include_once "tail.php";
?>