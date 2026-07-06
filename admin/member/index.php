<?php
include_once "path.php";

$nfor[title] = "전체보기";

include_once "head.php";
?>



	<div class="title">회원정보</div>
	<table class="table cols_tbl">
	<tr>
		<th>회원 구분</th>
		<td><?=admin_echo($mb,"mb_admin")?></td>
		<th>회원가입일시</th>
		<td><?=$mb[mb_datetime]?></td>
	</tr>
	<tr>
		<th>아이디</th>
		<td><?=$mb[mb_id]?></td>
		<th>전화번호</th>
		<td><?=$mb[mb_tel]?></td>
	</tr>
	<tr>
		<th>이름</th>
		<td><?=$mb[mb_name]?></td>
		<th>휴대폰</th>
		<td><?=$mb[mb_hp]?></td>
	</tr>
	<tr>
		<th>주소</th>
		<td colspan="3"><?=$mb[mb_zipcode]?> <?=$mb[mb_addr1]?> <?=$mb[mb_addr2]?></td>
	</tr>
	<tr>
		<th>성별</th>
		<td><?=admin_echo($mb,"mb_sex")?></td>
		<th>생년월일</th>
		<td><?=$mb[mb_birthday]?> <? if($mb[mb_birthday_type]){ ?>(<?=admin_echo($mb,"mb_birthday_type")?>)<? } ?></td>
	</tr>
	</table>




	<div class="title">신청내역</div>
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
	$result = sql_query("select * from nfor_review where rv_mb_no='$mb[mb_no]' and rv_delete='0' order by rv_id desc limit 5");
	for($i=0; $row=sql_fetch_array($result); $i++){	
		
		$row = nfor_tag_out($row);
	?>
	<tr>
		<td><?=$row[rv_id]?></td>
		<td><a href="<?=$nfor[path]?>/campaign.php?cp_id=<?=$row[rv_cp_id]?>" target="_blank"><?=$row[rv_cp_subject]?><br><?=$row[rv_cp_id]?></a></td>
		<td><?=campaign_member_info($row)?></td>
		<td class="textleft"><a href="<?=$row[rv_channel]?>" target="_blank"><?=$row[rv_channel]?></a></td>
		<td class="textleft">
		<?=$row[rv_dy_name]?> <?=$row[rv_dy_hp]?><br>
		<?=$row[rv_dy_zip]?> <?=$row[rv_dy_addr1]?> <?=$row[rv_dy_addr2]?>
		</td>
		<td><?=substr($row[rv_datetime],0,10)?></td>
	</tr>
	<?php
	}
	if(!$i){
	?>
	<tr>
		<td colspan="6">신청내역이 없습니다.</td>	
	</tr>
	<?php } ?>
	</table>



	<div class="title">1:1문의내역</div>
	<table class="row_tbl">
	<tr>
		<th>번호</th>
		<th>제목</th>
		<th>작성일</th>
		<th>답변상태</th>
		<th>답변</th>
	</tr>
	<?php	
	$i = 0;
	$que = sql_query("select * from nfor_customer where cs_insert_id='$mb[mb_no]' order by cs_id desc limit 5");
	$total_count = sql_num_rows($que);
	while($row = sql_fetch_array($que)){
		$row[num] = $total_count - ($page - 1) * $rows - $i;
		
		$row = nfor_tag_out($row);
	?>
	<tr>
		<td><?=$row[num]?></td>
		<td><?=$row[cs_subject]?></td>
		<td><?=date("y.m.d",strtotime($row[cs_insert_datetime]))?></td>
		<td><?=admin_echo($row,"cs_reply_state")?></td>
		<td>답변하기</td>
	</tr>
	<?php 
		$i++;
	}
	if(!$i){
	?>
	<tr>
		<td colspan="5">등록된 1:1문의가 없습니다.</td>
	</tr>
	<? } ?>
	</table>




<?php
include_once "tail.php";
?>