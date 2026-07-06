<?php
include_once $nfor[skin_path]."head.php";
?>
<div class="container_cus">
	<?php include_once $nfor[skin_path]."inc_my.php"; ?>
	<div class="cusinfor">

		<div class="page_title">
		<b class="point_color4">
		<?php if(basename($PHP_SELF)=="point_bank_list.php"){ ?>
		<?=$member[mb_nick]?></b>님의<br><?=$nfor[title]?> 확인해보세요.
		<?php } else if(basename($PHP_SELF)=="point_list.php"){ ?>
		<?=$member[mb_nick]?></b>님의<br><?=$nfor[title]?> 확인해보세요.
		<?php } else { ?>
		<?=$member[mb_nick]?></b>님<br><?=$nfor[title]?> 입니다.
		<?php } ?>
		</div>