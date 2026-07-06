<?
include_once "path.php";
$item = sql_fetch("select * from nfor_item where it_id='$it_id'");

if($opt_id){
	$add_sql = "";
	$opt = sql_fetch("select * from nfor_item_option where opt_id='$opt_id'");	
	for($i=1; $i<$depth; $i++){
		$add_sql .= " and opt_val{$i} ='".$opt["opt_val".$i]."'";
	}
}

$sql = "select * from nfor_item_option where opt_it_id='$it_id' and opt_type='0' and opt_view='1' and opt_select='0' ";
$sql .= $add_sql;
$sql .= " group by opt_val{$depth} order by opt_rank asc";

$que = sql_query($sql);
while($data = sql_fetch_array($que)){
	$chk_last = sql_fetch("select count(*) as cnt from nfor_item_option where opt_it_id='$item[it_id]' and opt_price2 <> '$item[it_price2]'");
?>
<li <? if($item[it_opt_cnt]==$depth){ ?> id="opt_li_<?=$data[opt_id]?>" <?=$data[opt_stock]?"":"class=\"opt_soldout\" "?>data-opt_stock="<?=$data[opt_stock]?>"<? } ?> data-depth="<?=$depth?>" data-opt_id="<?=$data[opt_id]?>" data-opt_name="<?=$data["opt_val".$depth]?>" data-opt_price2="<?=$data[opt_price2]?>" data-basic="1">
	<div><?=$data[opt_stock]?"":"(재고없음) "?><?=$data["opt_val".$depth]?></div>
	<? if($item[it_opt_cnt]==$depth and $chk_last[cnt]){ ?><b><?=number_format($data[opt_price2])?><span>원</span></b><? } ?>
</li>
<?
}
?>