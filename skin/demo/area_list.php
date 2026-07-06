<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.area_wrap .tit{font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal; padding-top: 61px;padding-bottom: 32px; border-bottom: 0; font-size: 34px; text-align:center;}
.area_map{margin:30px 0px;}
.area_tab{display:flex; }
.area_name{display:block; flex:1; text-align:center; border:solid 1px #DCDCDC; height:45px; line-height:45px; color: #222222; font-size: 16px; background: #fafafa;cursor: pointer;border: 1px solid #dcdfe5; margin-left:-1px;}
.area_name:nth-child(1){margin:0px;}
.on{ background: #fff;cursor: pointer; border-bottom:solid 1px #FFF;}
.area_content{border: 1px solid #dcdfe5;  border-top:none; background-color:#FFF; }
.area_content .inner{padding:20px 20px; border-bottom:1px solid #dcdfe5; overflow:hidden;}
.area_content .inner:last-child{border-bottom:none;}
.area_title{ cursor:pointer; width:18%; float:left; font-size:12px;}
.area_content .inner .area{float:left; width:82%;}
.area_content .inner .area a { cursor:pointer; display:inline-block; width:180px; height:28px; line-height:28px; font-size:12px;}
.area_content .inner .area a:hover{ color:#ff3300; }
.area_content .inner .area a.over { color:#ff3300; }
.area_content .inner .area a:before{content:" - "; font-size:12px; color:#888;}
.area_content .inner .area a span{color:#888; font-size:11px;}

.category_wrap { display:none; }
.category_wrap.on { display:block; }
</style>

<div class="area_wrap">

	<div class="area_map">
		<div class="area_tab">
			<?php
			for($i=0; $i<count($admin["category"]); $i++){
				$row = $admin["category"][$i];
			?>
			<a data-category_id="<?=$row[category_id]?>" class="area_name <?=!$area_id && $i==0 || substr($area_id,0,3)==$row[category_id]?"on":""?>"><?=$row[cg_category]?></a>
			<? } ?>
		</div>
		<div class="area_content">

			<?php
			for($i=0; $i<count($admin["category"]); $i++){
				$row = $admin["category"][$i];
			?>
			<div class="category_wrap <?=!$area_id && $i==0 || substr($area_id,0,3)==$row[category_id]?"on":""?> category_wrap_<?=$row[category_id]?>">
				
				<?php
				for($k=0; $k<count($row[category]); $k++){
					$data = $row[category][$k];
				?>
				<div class="inner">
					<div class="area_title" data-category_id="<?=$data[category_id]?>"><?=$data[cg_category]?></div>
					<div class="area">
						<?php
						for($t=0; $t<count($data[category]); $t++){
							$rows = $data[category][$t];
						?>
						<a <?php if(!$scroll_load){ ?>href="<?=$PHP_SELF?>?area_id=<?=$rows[category_id]?>"<?php } ?> class="<?=$rows[category_id]==$area_id?"over":""?>" data-category_id="<?=$rows[category_id]?>"><?=$rows[cg_category]?></a>
						<? } ?>
					</div>
				</div>
				<? } ?>

			</div>
			<? } ?>

		</div>
	</div>

	<div class="item_list_wrap"><?php include_once $nfor[skin_path]."inc_item_list.php"; ?></div>
	
</div>

<script>
$(document).on("click", ".area_tab a", function(){
	area_id = $(this).data("category_id");
	$(".area_tab a").removeClass("on");
	$(this).addClass("on");

	$(".category_wrap").hide();
	$(".category_wrap_"+area_id).show();

	<?php if($scroll_load){ ?>
	page = 1;
	item_list_load();
	<?php } ?>

});
<?php if($scroll_load){ ?>
$(document).on("click", ".area a", function(){
	area_id = $(this).data("category_id");
	$(".area a").removeClass("over");
	$(this).addClass("over");
	page = 1;
	item_list_load();
});

$(document).on("click", ".area_title", function(){
	area_id = $(this).data("category_id");
	$(".area_title").removeClass("over");
	$(this).addClass("over");
	page = 1;
	item_list_load();
});
<?php } ?>
</script>

<?php
include_once $nfor[skin_path]."tail.php";
?>