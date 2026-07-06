<div class="category_popup">

	<ul class="all_menu_wrap">
	<?php
	$que = sql_query("select * from nfor_item_category where cg_use='1' and cg_depth_id='0' and cg_depth='0' order by cg_rank asc");
	while($data = sql_fetch_array($que)){
	?>	
	<li>
		<h2><a href="item_list.php?category_id=<?=$data[category_id]?>"><?=$data[cg_category]?></a></h2>
		<?php
		$que1 = sql_query("select * from nfor_item_category where cg_use='1' and cg_depth_id='$data[cg_id]' and cg_depth='1' order by cg_rank asc");
		while($data1 = sql_fetch_array($que1)){
		?>
		<div class="sub_menu_box">
			<p class="sub_depth"><a href="item_list.php?category_id=<?=$data1[category_id]?>"><?=$data1[cg_category]?></a></p>
			<ul>
				<?php
				$que2 = sql_query("select * from nfor_item_category where cg_use='1' and cg_depth_id='$data1[cg_id]' and cg_depth='2' order by cg_rank asc");
				while($data2 = sql_fetch_array($que2)){
				?>
				<li><a href="item_list.php?category_id=<?=$data2[category_id]?>"><?=$data2[cg_category]?></a></li>
				<? } ?>
			</ul>
		</div>
		<? } ?>
	</li>
	<? } ?>
	</ul>

	<button class="category_popup_close_btn">전체 카테고리 창 닫기</button>

</div>




<!-- 서브메뉴-->	
<div class="menu_list" <? if(basename($PHP_SELF)<>"index.php"){ ?>style="display:none; background-color:#30343b; z-index:2000;"<? } ?>>
	<ul>
		<?php
		$que = sql_query("select * from nfor_item_category where cg_use='1' and cg_depth_id='0' and cg_depth='0' order by cg_rank asc");
		while($data = sql_fetch_array($que)){
		?>
		<li>
			<a href="item_list.php?category_id=<?=$data[category_id]?>" class="main_menu"><i class="cate1"></i><?=$data[cg_category]?></a>
			
			<ul class="sub_cate_list">
			<?php
			$que1 = sql_query("select * from nfor_item_category where cg_use='1' and cg_depth_id='$data[cg_id]' and cg_depth='1' order by cg_rank asc");
			while($data1 = sql_fetch_array($que1)){
			?>
			<li>
				<a href="item_list.php?category_id=<?=$data1[category_id]?>" class="sub_menu"><?=$data1[cg_category]?></a>
				<ul class="third_cate_list">
					<?php
					$que2 = sql_query("select * from nfor_item_category where cg_use='1' and cg_depth_id='$data1[cg_id]' and cg_depth='2' order by cg_rank asc");
					while($data2 = sql_fetch_array($que2)){
					?>
					<li><a href="item_list.php?category_id=<?=$data2[category_id]?>"><?=$data2[cg_category]?></a></li>
					<? } ?>
				</ul>
			</li>
			<? } ?>
			</ul>

		</li>
		<? } ?>
	</ul>
</div>
<!-- // 서브메뉴-->		











<script>

$(document).on("click",".category_popup_open_btn",function(){ 
	<? if(basename($PHP_SELF)=="index.php"){ ?>
	if($(".category_popup").css('display')=="none"){
		$(".category_popup").show();
		$(this).addClass("on");
	} else{
		$(".category_popup").hide();
		$(this).removeClass("on");
	}
	<? } else{ ?>
	if($(".menu_list").css('display')=="none"){
		$(".menu_list").show();
	} else{
		$(".menu_list").hide();
	}
	<? } ?>
});


$(document).on("click",".category_popup_close_btn",function(){ 
	$(".category_popup").hide();
	$(".category_popup_open_btn").removeClass("on");
});
</script>