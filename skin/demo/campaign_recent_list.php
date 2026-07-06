<?php
include_once $nfor[skin_path]."mypage_head.php";
?>

<? if(count($return["list"]) > 0){ ?>
<div class="house_list mar30">
<?
include $nfor[skin_path]."inc_house_list.php";
?>
</div>
<div class="page_center"><?=$pagelist?></div>
<? } else { ?>
<div class="sch_no_data">
	<p>최근 본 매물이 존재하지 않습니다.</p>
</div>
<? } ?>

<?php
include_once $nfor[skin_path]."mypage_tail.php";
?>