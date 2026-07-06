<?php
include_once $nfor[skin_path]."head.php";
?>
<style>
.house_info .opt { display:none; }
.more_info { display:none; }
.ho_address1 { height:38px; line-height:19px; font-size:12px; color:rgb(102, 102, 102); overflow:hidden; text-overflow:ellipsis; display:-webkit-box; -webkit-line-clamp:2;  -webkit-box-orient:vertical; }
</style>
<? if(count($return["list"]) > 0){ ?>
<div class="house_list">
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
include_once $nfor[skin_path]."tail.php";
?>