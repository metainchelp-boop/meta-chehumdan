<ul>
<?php
for($i=0; $i<count($return["list"]); $i++){
	$house = $return["list"][$i];
?>
<li>

	
		<div class="box">
			<div class="house_zzim_btn <?=$house[ho_zzim_is]?>" data-ho_id="<?=$house[ho_id]?>"></div>

			<a href="house.php?ho_id=<?=$house[ho_id]?>" target="_blank">
			<div class="thumb">
				<img src="<?=$house[ho_image]?>">
				<div class="opt"><? if($house[ho_premium]){ ?><span class="plus">프리미엄 매물</span><? } ?><? if($house[ho_confirm_date]){ ?><span class="check_house">확인매물 <?=$house[ho_confirm_date]?></span><? } ?></div>
			</div>

			<div class="house_info">
				<div class="opt">
					<span class="house_id">매물번호 :<b class="num_mon"> <?=$house[ho_id]?></b></span> 
					<span class="adver"><b class="num_mon"><?=$house[ho_exp_day]?></b></span>
				</div>
				<div class="opt2">
					<span class="ho_type main_color"><?=$house[ho_type]?></span>
				</div>
				<span class="ho_rent_detail"><?=$house[ho_rent_detail]?></span>
				<p class="ho_detail"><?=$house[ho_detail]?></p>
				<span class="ho_subject"><?=$house[ho_subject]?></span>
				<span class="ho_address1"><?=$house[ho_address1]?></span>
			</div>

			<div class="more_info">
				<span class="ho_datetime num_mon"><?=$house[ho_datetime]?></span>
				<div class="right_info">
					<span class="ho_hit">조회 <b class="num_mon"><?=$house[ho_hit]?></b></span>
					<span class="ho_zzim">찜 <b class="num_mon"><?=$house[ho_zzim]?></b></span>
				</div>
			</div>
			</a>
		</div>
	
	
</li>
<?php } ?>	
</ul>