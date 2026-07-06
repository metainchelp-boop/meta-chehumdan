<?php
if($member[mb_no]){
	$pt_point_sum = sql_fetch("select sum(pt_point) as pt_point_sum from nfor_point where pt_mb_no='$member[mb_no]' and pt_type<>'700'");
}
?>

<div class="point">
	<ul>
		<li><span class="tit">보유포인트</span><b><span class="color1 "><?=number_format($member[mb_point])?>p</span></b><a href="javascript:get_point_win()" class="btn"><span  >+ 출금 신청</span>하기</a></li>
		<li><span class="tit">누적포인트</span><b> <span class="color2 "><?=number_format($pt_point_sum[pt_point_sum])?>p</span></b></li>	
	</ul>
		<?php
		if(basename($PHP_SELF)<>"get_point.php"){
		?>
		<div style="margin-top:10px"><span class="btn_pack"><a href="get_point.php" id="money_msg_btn" class="btn_lg color">출금 신청하기</a></span></div>
		<?php } ?>
</div>

<div class="point_teb">
	<ul>
		<li><a href="point_list.php" <? if(basename($PHP_SELF)=="point_list.php"){ ?>class="on"<? } ?>><span >적립</span> 내역 보기</a></li>
		<li><a href="point_bank_list.php" <? if(basename($PHP_SELF)=="point_bank_list.php"){ ?>class="on"<? } ?>><span >출금 </span>내역보기</a></li>
	</ul>
</div>