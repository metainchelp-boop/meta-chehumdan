<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.plan{text-align:center;}
.plan img { width:100%; margin:0 auto; }
.sub_title {position:relative;  font-size:26px; margin-top:30px; font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal; padding-top: 61px;padding-bottom: 40px; border-bottom: 0; font-size: 34px; text-align:center;}
.sub_title .more{position: absolute; bottom:34px; right: 0;display:inline-block; border:solid 1px #303030; background-color:#303030; padding: 10px; font-size:11px; color:#FFF;}
</style>

<? if($plan[pl_img_top_pc]){ ?>
<div class="plan"><img src="<?="$nfor[path]/data/plan/$plan[pl_img_top_pc]"?>"></div>
<? } ?>

<div class="layout_inner">
	<div class="sub_title"><?=$plan[pl_subject]?><a href="plan_list.php" class="more">+ 다른기획전보러가기</a></div>
	<div class="item_list_wrap"><?php include_once $nfor[skin_path]."inc_item_list.php"; ?></div>
</div>

<?php
include_once $nfor[skin_path]."tail.php";
?>