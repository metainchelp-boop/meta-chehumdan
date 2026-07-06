<?php
include_once "path.php";
?>
<div class="top_area">
	<div class="tab_area">
		<?
		$k = 0;
		$que = sql_query("select * from nfor_item_category where cg_use='1' and cg_depth_id='0' order by cg_rank asc limit 5");
		while($data = sql_fetch_array($que)){
			if(!$category_id and !$k) $category_id = $data[category_id];

			if($category_id==$data[category_id]){
				$category_tit = $data[cg_category];
			}
		?>
		<a href="#none" data-category_id="<?=$data[category_id]?>" class="main_ctg_tab btn_tab fotl <?=$category_id==$data[category_id]?"btn_hit":""?>"><?=$data[cg_category]?><span class="line"></span></a>
		<? 
			$nfor[category_box][] = $data[category_id];
			$k++;
		}		
		?>
	</div>
	<a href="item_list_main.php?category_id=<?=$category_id?>" class="btn_more_view"><strong class="ctg_name" id="main_ctg_title"><?=$category_tit?></strong> 전체보기</a>
</div>
<div class="itemlist_box">
	<div class="item_list">
		<div class="item_box">
		<?php
		$sql = "select * from nfor_item where it_view='1' and ((it_shopping='1') or (it_shopping='2' and it_paydate <='$nfor[ymdhis]' and it_payenddate >='$nfor[ymdhis]'))";
		if($category_id and in_array($category_id, $nfor[category_box])){
			$sql .= " and it_category like '%$category_id%' ";
		}
		$sql .= " limit 9";

		$result = sql_query($sql);
		include $nfor[skin_path]."inc_index_list_item.php";
		?>
		</div>
	</div>
</div>