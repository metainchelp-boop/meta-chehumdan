<?php
include_once $nfor[skin_path]."head.php";
?>

<div class="sicon_lst">
	<ul>
		<?php
		foreach($admin[category_id] as $key => $value){
		?>
		<li><a <?php if(!$scroll_load){ ?>href="<?=$PHP_SELF?>?category_id=<?=$key?>"<?php } ?> data-category_id="<?=$key?>" <?=$key==$category_id?"class=\"on\"":""?>><img src="<?=$admin[category_img][$key]?>"><?=$value?></a></li>
		<? } ?>
	</ul>
</div>

<script>
$(function() {
	var position = $(".sicon_lst ul .on").offset().left;
	$(".sicon_lst ul").scrollLeft(position);
});
</script>

<div class="item_list_wrap"><?php include_once $nfor[skin_path]."inc_item_list.php"; ?></div>

<?php if($scroll_load){ ?>
<script>
$(document).on("click",".sicon_lst li a",function(){
	$(".sicon_lst li a").removeClass("on");
	$(this).addClass("on");
	category_id = $(this).data("category_id");
	page = 1;
	is_last = 0;

	item_list_load();
});
</script>
<?php } ?>

<?php
include_once $nfor[skin_path]."tail.php";
?>