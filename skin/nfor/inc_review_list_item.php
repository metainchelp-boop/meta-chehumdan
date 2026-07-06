<ul>
	<?php
	for($i=0; $i<count($return["list"]); $i++){
		$campaign = $return["list"][$i];
	?>
	<li>
	<div class="box">	
		<a href="<?=$campaign[rv_url]?>" class="thum" target="_blank">
			<img src="<?=$campaign[rv_img]?>" class="it_img">
		</a>
		<div class="review_info">
			<a href="campaign.php?cp_id=<?=$campaign[cp_id]?>" class="top_info">
				<img src="<?=$campaign[cp_img]?>" class="review_img">
				<div class="review_des">
					<span class="review_cop"><?=$campaign[cp_subject]?></span>
					<span class="review_name"><?=$campaign[cp_description]?></span>
				</div>
			</a>
			<div class="review_description"><?=$campaign[rv_review]?></div>
			<div class="review_bottom">
				<span class="sns <?=$campaign[rv_media]?>"><?=$campaign[rv_media_text]?></span>
				<span class="id"><?=$campaign[rv_mb_nick]?></span>
			</div>
		</div>
	</div>
	</li>
	<?php } ?>
</ul>