<style>
.house_list{overflow:hidden; padding:10px 0px;  }
.house_list ul{margin-left:-15px}
.house_list li{float:left; width:25%; padding-left:15px; padding-bottom:15px;}
.house_list .thumb{width:100%;position: relative; height:175px; overflow:hidden;}
.house_list .thumb img{float:left; width:100%; }
.house_list .zzim_off{ background:url(/skin/demo/img/zzim_off.png);}
.house_list .zzim_on{ background:url(/skin/demo/img/zzim_on.png);}
.house_list .thumb .opt{position: absolute; bottom:0px; left:0px; font-size:11px;}
.house_list .thumb .opt .plus{ display:inline-block; background-color:#ff6600; color:#FFF; padding:3px 5px; text-align:center; }
.house_list .thumb .opt .check_house{display:inline-block; background-color:#565eb6;  color:#FFF; padding:3px 5px; font-size:11px;}

.house_list  .house_info{padding-top:10px;}
.house_list  .house_info .opt{display:none;}
.house_list  .house_info .ho_type{display:block; font-size:14px; }
.house_list  .house_info .ho_rent_detail{ font-size:25px; font-weight:300; margin-top:10px; letter-spacing:-1px;  font-family: 'montserrat', 'noto', 'Roboto', sans-serif;}
.house_list  .house_info .ho_rent_detail:hover{color:#000;}
.house_list  .house_info .ho_detail{font-size:14px; color:#000; margin-top:5px;}
.house_list  .house_info .ho_address1,
.house_list  .house_info .ho_subject{display:block; width: 100%; color: rgb(102, 102, 102);  font-size: 14px; font-weight: 300; line-height: 20px; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;}
.house_list  .box .more_info b{font-weight:normal;}
.house_list .box .more_info{ position:relative; margin-top:10px; font-size:12px; color:#888;}
.house_list .box .more_info .right_info{position: absolute;  top:0px;right:0px;}

.box { position:relative; }
.house_zzim_btn { position: absolute; width:42px; height:37px; top:10px; right:10px;  z-index:9990;}
</style>
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