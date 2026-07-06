<div class="review_list_top_wrap">

	<ul class="review_list_tab">
		<li<?=basename($PHP_SELF)=="review_wait_list.php"?" class='on'":""?>><a href="review_wait_list.php">신청한 캠페인 <em class="txt_num re_num"><?=number_format($review_count[review_count][1])?></em></a></li>
		<li<?=$write[rv_step]=="2" || basename($PHP_SELF)=="review_asign_list.php"?" class='on'":""?>><a href="review_asign_list.php">선정된 캠페인 <em class="txt_num re_num"><?=number_format($review_count[review_count][2])?></em></a></li>
		<li<?=$write[rv_step]=="3" || basename($PHP_SELF)=="review_post_list.php"?" class='on'":""?>><a href="review_post_list.php">검수중 캠페인 <em class="txt_num re_num"><?=number_format($review_count[review_count][3])?></em></a></li>
		<li<?=$write[rv_step]=="7" || basename($PHP_SELF)=="review_edit_list.php"?" class='on'":""?>><a href="review_edit_list.php">수정요청 캠페인 <em class="txt_num re_num"><?=number_format($review_count[review_count][7])?></em></a></li>
		<li<?=basename($PHP_SELF)=="review_best_list.php"?" class='on'":""?>><a href="review_best_list.php">등록완료 캠페인<em class="txt_num re_num"><?=number_format($review_count[review_count][4])?></em></a></li>
		<li<?=basename($PHP_SELF)=="review_cancel_list.php"?" class='on'":""?>><a href="review_cancel_list.php">미선정 캠페인<em class="txt_num re_num"><?=number_format($review_count[review_count][5])?></em></a></li>
		<li<?=basename($PHP_SELF)=="review_asign_cancel_list.php"?" class='on'":""?>><a href="review_asign_cancel_list.php">선정후취소 캠페인<em class="txt_num re_num"><?=number_format($review_count[review_count][6])?></em></a></li>
	</ul>

</div>