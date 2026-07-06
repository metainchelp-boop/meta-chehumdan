<?php
include_once "path.php";
?>

<style>
.index_main_review{background-color:#f9f9f9; padding:30px 0px 0px; margin:0px auto;}
.index_main_review .index_title{font-size:23px; color:#000; margin-bottom:20px; letter-spacing:-1px; font-weight:400;}
.index_main_review .index_title b{font-weight:400;}
</style>

<div class="index_main_review"> 
	<div class="layout_inner">
		<div class="index_title">체험단 생생 <b class="point_color4">후기</b></div>
		<div class="review_list_wrap">
			<div class="review_box_list">
				<?php
				$return["list"] = array();
				$return["list"] = $return["realtime_review_list"];
				include $nfor[skin_path]."inc_review_list_item.php";
				?>
			</div>	
		</div>
	</div>
</div>
