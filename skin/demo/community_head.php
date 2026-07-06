<?php
include_once $nfor[skin_path]."head.php";
?>

<div class="container_cus">
	<?php
	include_once $nfor[skin_path]."inc_community.php";
	?>

	<div class="cusinfor">
		<div class="page_title">

		<?php if(basename($PHP_SELF)=="board_list.php"){ ?>
			<? if(!$member[mb_no]){ ?>
			<?=$nfor[title]?> 
			<?php } else{ ?>
			<b class="pointcolor_1"><?=$member[mb_nick]?></b>님의<br><?=$nfor[title]?> 확인해보세요.
			<?}?>
		<?php } else if(basename($PHP_SELF)=="point_list.php"){ ?>
		<? if(!$member[mb_no]){ ?><? } else{ ?><b class="point_color4"><?=$member[mb_nick]?$member[mb_nick]:$member[$member_config[cf_mb_id_type]]?></b> <? }?> <br> <?=$board[bo_name]?>
		<?php } else { ?>
			<? if(!$member[mb_no]){ ?>
			<?=$nfor[title]?> 입니다.
			<?php } else{ ?>
			<b class="pointcolor_1"><?=$member[mb_nick]?></b>님<br><?=$nfor[title]?> 입니다.
			<?}?>

		
		<?php } ?>
		</div>


<?php
if($apply_good){
	include_once $nfor[skin_path]."inc_board_list_banner.php";
}
?>