<ul>
	<?php							
	for($i=0; $item=sql_fetch_array($result); $i++){
	?>
	<li>
		<div class="border">
			<?
			if(basename($PHP_SELF)=="best.php"){
			?>
			<div class="ranking">
				<span class="top"><?=$i+1?></span>
			</div>
			<? } ?>
			<a href="item.php?it_id=<?=$item[it_id]?>">
			<div class="thumb">
				<img src="<?=$item[it_img]?thumbnail("$nfor[path]/data/list/$item[it_img]",300,300,0,1):"$nfor[path]/img/item_list_noimg.jpg"?>" class="it_img">
			</div>
			<div class="it_info">
				<p class="it_description"><?=$item[it_description]?></p>
				<p class="it_name"><?=$item[it_name]?></p>
				<div class="it_price_info">
					<span class="it_discount_rate">
						<strong><?=$item[it_discount_rate]?></strong>
						<span>%</span>
					</span>
					<span class="it_price">
						<span class="it_price1"><?=number_format($item[it_price1])?><span>원</span></span>
						<span class="it_price2"><?=number_format($item[it_price2])?><span>원</span></span>
					</span>
				</div>
			</div>
			</a>
			<div class="option">
				<span class="coupon_price">쿠폰가 10,000원 할인</span>
				<span class="besong">무료배송</span>
				<span class="it_sales_volume"><b><?=number_format($item[it_sales_volume])?></b>개 구매</span>
				<div class="zzim">
					<a class="btn_zzim"></a> <span><?=number_format($item[it_zzim])?></span>
				</div>
			</div>
		</div>
	</li>
	<? } ?>
</ul>