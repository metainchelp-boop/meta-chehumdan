<?
include_once "path.php";
?>

<div class="index_main1_con"> 
	<div class="layout_inner">
		<div class="index_title"><b class="point_color4">오늘의</b> 캠페인</div>
				<div class="item_list_wrap">
				<div class="item_box_list">
					<?php
					$return["list"] = array();
					$return["list"] = $return["new_campaign_list"];
					include $nfor[skin_path]."inc_index_list_item.php";
					?>
				</div>	
			</div>
	</div>
</div>
