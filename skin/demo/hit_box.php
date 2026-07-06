<?php
include_once "path.php";

$nfor[hit_box] = array("it_good","it_hot","it_new");
?>
<div class="main_list">
<div class="layout_inner">

	<div class="main_tab">
		<a href="#none" data-type="it_good" class="btn main_product_list_tab fotm <?=$type=="it_good"?"btn_hit":""?>">오늘오픈</a>
		<a href="#none" data-type="it_hot" class="btn main_product_list_tab fotm <?=$type=="it_hot"?"btn_hit":""?>">TODAY'S HOT</a>
		<a href="#none" data-type="it_new" class="btn main_product_list_tab fotm <?=$type=="it_new"?"btn_hit":""?>">NEW ARRIVAL</a>
	</div>

	<div class="item_list_area">
		<div class="item_list">
			<div class="item_box03">
			<?php
			$sql = "select * from nfor_item where it_view='1' and ((it_shopping='1') or (it_shopping='2' and it_paydate <='$nfor[ymdhis]' and it_payenddate >='$nfor[ymdhis]'))";
			if(in_array($type, $nfor[hit_box])){
				$sql .= " and $type > 0 order by $type desc";
			}
			$sql .= " limit 9";
			$result = sql_query($sql);
			include $nfor[skin_path]."inc_index_list_item.php";
			?>
			</div>
		</div>
	</div>

</div>
</div>