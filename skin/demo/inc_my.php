<style>
.cus_leftmenu{ float:left; width:248px; }
.cus_leftmenu ul{margin-top:10px;}
.cus_leftmenu li {height:53px; line-height:53px;background-color:#FFFFFF; border-bottom:solid 1px #efefef; border-top:solid 1px #efefef; position:relative; }
.cus_leftmenu li + li {height:53px; line-height:53px;background-color:#FFFFFF; border-bottom:solid 1px #efefef; border-top:solid 0px #efefef;}

.cus_leftmenu li:hover { color:#FFFFFF;}
.cus_leftmenu li a{ display:block; width:100%; padding-left:10px; color:#000; font-size:15px; letter-spacing:-1px;}
.cus_leftmenu li a:hover{ color:#18a8f1; background-color:#fafafa;}
.cus_leftmenu li.on{background-color:#efefef; color:#18a8f1; }
.cus_leftmenu .on a{color:#18a8f1;}
.cus_num{position: absolute; right: 20px; bottom: 0px; font-family:Montserrat;letter-spacing: -0.02em !important; color:#000;}
.cus_leftmenu li.on .cus_num{position: absolute; right: 20px; bottom: 0px; font-family:Montserrat;letter-spacing: -0.02em !important; color:#18a8f1;}
.cus_leftmenu li a:hover .cus_num{position: absolute; right: 20px; bottom: 0px; font-family:Montserrat;letter-spacing: -0.02em !important; color:#18a8f1;}
</style>

<div class="cus_leftmenu">

	<?php
	include_once $nfor[skin_path]."inc_photo.php";
	?>
	<ul>
		<li <?=basename($PHP_SELF)=="receive_message.php"?"class='on'":"class=''"?>><a href="receive_message.php">받은 메시지 <b class="cus_num"><?=number_format($receive_message[cnt])?></b></a></li>
		<li <?=basename($PHP_SELF)=="review_wait_list.php" || basename($PHP_SELF)=="review_asign_list.php" || basename($PHP_SELF)=="review_post_list.php" || basename($PHP_SELF)=="review_best_list.php"?"class='on'":"class=''"?>><a href="review_wait_list.php">나의 캠페인<b class="cus_num"><?=number_format($review_count[review_total_count])?></b></a></li>




		<li <?=basename($PHP_SELF)=="campaign_zzim_list.php"?"class='on'":"class=''"?>><a href="campaign_zzim_list.php">관심 캠페인<b class="cus_num"><?=number_format($campaign_zzim_list[cnt])?></b></a></li>
		<li <?=basename($PHP_SELF)=="my_campaign_qna_list.php"?"class='on'":"class=''"?>><a href="my_campaign_qna_list.php">캠페인 문의답변</a></li>
		<li <?=basename($PHP_SELF)=="my_review_list.php"?"class='on'":"class=''"?>><a href="my_review_list.php">등록리뷰<b class="cus_num"><?=number_format($review_count[review_count][4])?></b></a></li> 
		<li <?=basename($PHP_SELF)=="point_list.php" || basename($PHP_SELF)=="point_bank_list.php"?"class='on'":"class=''"?>><a href="point_list.php">나의 포인트</a></li>
		<li <?=basename($PHP_SELF)=="member_form.php" || basename($PHP_SELF)=="member_confirm.php"?"class='on'":"class=''"?>><a href="member_confirm.php">개인정보 수정</a></li>
		<li <?=basename($PHP_SELF)=="customer_list.php" || basename($_SERVER[PHP_SELF])=="customer_form.php" || basename($_SERVER[PHP_SELF])=="customer_view.php"?"class='on'":"class=''"?>><a href="customer_list.php">1:1문의</a></li>
	</ul> 

</div>