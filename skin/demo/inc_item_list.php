<div class="deal_list_type type_1">
<ul>
	<?php
	for($i=0; $i<count($return["list"]); $i++){
		$item = $return["list"][$i];
	?>
	<li>
		<div class="border">
			<?
			if(basename($PHP_SELF)=="best_list.php"){
			?>
			<div class="ranking">
				<span class="top"><?=$item[it_num]?></span>
			</div>
			<? } ?>
			<?
			if(basename($PHP_SELF)=="recent_list.php"){
			?>
			<div class="check">
				<div class="off"></div>
				<input type="checkbox" name="chk[]" class="chk" value="<?=$item[it_id]?>"><input type="hidden" name="it_id[<?=$item[it_id]?>]" class="it_id" value="<?=$item[it_id]?>">
			</div>
			<? } ?>
			<a href="item.php?it_id=<?=$item[it_id]?>">
			<div class="thumb">
				<img src="<?=$item[it_img]?>" class="it_img">
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
						<span class="it_price1"><?=$item[it_price1]?><span>원</span></span>
						<span class="it_price2"><?=$item[it_price2]?><span>원</span></span>
					</span>
				</div>
			</div>
			</a>
			<div class="option">
				<? if($item[it_delivery_type]){ ?><span class="besong"><?=$item[it_delivery_type]?></span><? } ?>
				<span class="it_sales_volume"> <b><?=number_format(it_sales_volume_new($item))?></b> 개 구매</span>
				<div class="zzim">
					<a class="btn_zzim"></a> <span><?=$item[it_zzim]?></span>
				</div>
			</div>
		</div>
	</li>
	<? } ?>
</ul>
</div>
<div style="clear:both;"></div>

<? if(!$scroll_load){ ?><div class="page_center"><?=$pagelist?></div><? } ?>

<div class="loading_wait">
	<img src="<?=$nfor[skin_path]?>img/ajax-loader.gif">
	<span>상품목록을 불러오고 있습니다</span>
	<p>잠시만 기다려 주세요</p>
</div>

<script type="text/html" id="item_list_script">
<li>
	<div class="border">
		<?php if(basename($PHP_SELF)=="best_list.php"){	?>
		<div class="ranking">
			<span class="top"><%=it_num%></span>
		</div>
		<?php } ?>
		<?php if(basename($PHP_SELF)=="recent_list.php"){ ?>
		<div class="check">
			<div class="off"></div>
			<input type="checkbox" name="chk[]" class="chk" value="<%=it_id%>"><input type="hidden" name="it_id[<%=it_id%>]" class="it_id" value="<%=it_id%>">
		</div>
		<?php } ?>
		<a href="item.php?it_id=<%=it_id%>">
		<div class="thumb">
			<img src="<%=it_img%>" class="it_img">
		</div>
		<div class="it_info">
			<p class="it_description"><%=it_description%></p>
			<p class="it_name"><%=it_name%></p>
			<div class="it_price_info">
				<span class="it_discount_rate">
					<strong><%=it_discount_rate%></strong>
					<span>%</span>
				</span>
				<span class="it_price">
					<span class="it_price1"><%=it_price1%><span>원</span></span>
					<span class="it_price2"><%=it_price2%><span>원</span></span>
				</span>
			</div>
		</div>
		</a>
		<div class="option">
			<% if(it_delivery_type){ %>
			<span class="besong"><%=it_delivery_type%></span>
			<% } %>
			<span class="it_sales_volume"><b><%=it_sales_volume%></b> 개 구매</span>
			<div class="zzim">
				<a class="btn_zzim"></a> <span><%=it_zzim%></span>
			</div>
		</div>
	</div>
</li>
</script>

<script>
var is_last = 0;
var page = 1;
var category_id = <?=json_encode((string)$category_id, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT)?>;
var it_bcategory_id = "<?=$it_bcategory_id?>";
var area_id = "<?=$area_id?>";
var pl_id = "<?=$plan[pl_id]?>";
var keyword = <?=json_encode((string)$keyword, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT)?>;

<?php if($scroll_load){ ?>
$(window).scroll(function() {
	if ($(window).scrollTop() == $(document).height() - $(window).height()) {
		if(is_last==0){
			++page;
			item_list_load();
		}
	}
});
<?php } ?>

function item_list_load(){
	$(".loading_wait").show();
	if(page==1){
		$(".deal_list_type ul").html("");
	}
	$.ajax({
		type     : "get",
		url      : "<?=basename($PHP_SELF)?>",
		data     : "json=list&category_id="+category_id+"&it_bcategory_id="+it_bcategory_id+"&area_id="+area_id+"&pl_id="+pl_id+"&keyword="+keyword+"&page="+page,
		dataType : "json",
		cache: false,
		success  : function(data) {
			if(data.count > 0){
				template = _.template($("#item_list_script").html());
				var template_html = "";
				for(var i=0; i<data.list.length; i++) {
					template_html +=  template({it_id: data.list[i].it_id
						, it_img: data.list[i].it_img
						, it_description: data.list[i].it_description
						, it_name: data.list[i].it_name
						, it_discount_rate: data.list[i].it_discount_rate
						, it_zzim: data.list[i].it_zzim
						, it_price1: data.list[i].it_price1
						, it_price2: data.list[i].it_price2
						, it_delivery_type: data.list[i].it_delivery_type
						, it_sales_volume: data.list[i].it_sales_volume
						, it_num: data.list[i].it_num});
				}
				$(".deal_list_type ul").append(template_html);
			}
			$(".loading_wait").hide();		
			if(data.last_page == 1){
				is_last++;
			}			
		},
		error: function(e){
			console.log(e);
			console.log("Ajax failed");
		}
	});
}
</script>