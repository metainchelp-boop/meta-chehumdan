
<div class="itname"><?=$campaign[cp_subject]?></div>
<div class="it_description"><?=$campaign[cp_description]?></div>
 <div class="iteminfo_top">
	<?php if($campaign[cp_media_blog]){ ?><span class="blog">블로그</span><?php } ?>
	<?php if($campaign[cp_media_instagram]){ ?><span class="instagram">인스타그램</span><?php } ?>
	<?php if($campaign[cp_media_youtube]){ ?><span class="youtube">유튜브</span><?php } ?>
	<?php if($campaign[cp_media_shop]){ ?><span class="shop">쇼핑몰</span><?php } ?>
	<?php if($campaign[cp_check1]){ ?><span class="gumepyung">구매평</span><?php } ?>
	<?php if($campaign[cp_check2]){ ?><span class="payback">페이백</span><?php } ?>


	<span class="nor_box"><?=$campaign[cp_type_text]?></span>
	<?php if($campaign[cp_point]){ ?><span class="nor_box point_color4 txt_num">+<?=$campaign[cp_point]?>P</span><?php } ?>
</div>
<div class="review_step_wrap">
	<span class="step">
		<em class="point_color4">리뷰어 신청기간</em> 
		<b class="txt_num point_color4"><?=date("m.d",strtotime($campaign[cp_sdatetime]))?> ~ <?=date("m.d",strtotime($campaign[cp_edatetime]))?></b>
	</span>
	<span class="step">
		<em>선정자 발표</em> 
		<b class="txt_num"><?=date("m.d",strtotime($campaign[cp_pick_datetime]))?></b>
	</span>
	<span class="step">
		<em >리뷰 등록기간</em> 
		<b class="txt_num"><?=date("m.d",strtotime($campaign[cp_contents_sdatetime]))?> ~ <?=date("m.d",strtotime($campaign[cp_contents_edatetime]))?></b>
	</span>
	<span class="step">
		<em>캠페인 결과발표</em> 
		<b class="txt_num"><?=date("m.d",strtotime($campaign[cp_result_datetime]))?></b>
	</span>
</div>

<div class="thumb"><img src="<?=$campaign[cp_img]?>"></div> 