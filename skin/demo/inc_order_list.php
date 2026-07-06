<?php
for($i=0; $i<count($print); $i++){
	$data = $print[$i];
?>
<div class="order_info">

	<a href="item.php?it_id=<?=$data[ct_it_id]?>" class="go_to_item">
		<div class="thumb">
			<img src="<?=$data[ct_it_img]?>" width="100" height="100" >
		</div>
		<div class="info">
			<h3 class="title"><?=$data[ct_it_name]?></h3>
							
			<div class="del_num_area">
				<?php if($data[ct_dy_num]){ ?>
				<span>송장번호 | </span>
				<b><?=$data[ct_dy_num]?></b>
				<? } ?>
				
				<?php if($data[ct_expiry_date]){ ?>
				<span>유효기간 | </span>
				<b><?=$data[ct_expiry_date]?></b>
				<? } ?>

				<p><?=$data[ct_order_state]?></p>
			</div>
		</div>
	</a>

	<ul class="buy_list">
		<?php
		for($k=0; $k<count($data["option"]); $k++){
			$option = $data["option"][$k];
		?>
		<li class="<?=$option[ct_cancel]?>">
			<?=$option[ct_value]?>
			<div class="count">
				구매 <span><?=$option[ct_opt_cnt]?></span>개
				<? if($option[ct_tk_used]>0){ ?> / 사용 <span><?=$option[ct_tk_used]?></span>개<? } ?>
			</div>
			<? if($option[ct_ticket_number]){ ?><span class="tiketnum"><?=$option[ct_ticket_number]?></span><? } ?>
		</li>
		<?php } ?>
	</ul>
	
	<? if(basename($PHP_SELF)<>"order_cancel.php"){ ?>
	<div class="order_btn_wrap">	
		<?php
		for($u=0; $u<count($data["button"]); $u++){
			$button = $data["button"][$u];
		?>
		<div><a href="<?=$button[href]?>" <?=$button[etc]?>><?=$button[text]?></a></div>
		<?php } ?>
	</div>
	<? } ?>
	
</div>
<?php } ?>