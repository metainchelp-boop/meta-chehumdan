<?php
if($member[mb_no]){
	$pt_point_sum = sql_fetch("select sum(pt_point) as pt_point_sum from nfor_point where pt_mb_no='$member[mb_no]' and pt_type<>'700'");
}
?>
<div class="point">
	<ul >
		<li><span class="tit">보유포인트</span><b><span class="color1 "><?=number_format($member[mb_point])?>p</span></b><a href="get_point.php" class="btn"><span>+ 출금 신청</span>하기</a></li>
		<li><span class="tit">누적포인트</span><b> <span class="color2 "><?=number_format($pt_point_sum[pt_point_sum])?>p</span></b>※ 다양한 캠페인 참여로 포인트를 모으시고 현금으로 전환하세요</li>		
	</ul>
</div>

<div class="point_teb">
	<ul>
		<li><a href="point_list.php"><span class="color<?=basename($PHP_SELF)<>"point_list.php"?"2":"1"?>">적립</span> 내역 보기</a></li>
		<li><a href="point_bank_list.php"><span class="color<?=basename($PHP_SELF)<>"point_bank_list.php"?"2":"1"?>">출금 </span>내역보기</a></li>
		<li><a href="get_point.php"><span class="color<?=basename($PHP_SELF)<>"get_point.php"?"2":"1"?>">출금 </span>신청하기</a></li>
	</ul>
</div>
