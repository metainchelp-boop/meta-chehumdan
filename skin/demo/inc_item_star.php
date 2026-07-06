
<div class="item_star_wrap">
	<div class="item_star_info">
		<div class="left">
			<div class="average">
				<span class="tit fotm"> 평균만족도</span>
				<span class="num">(<?=number_format($item[it_star])?>개)</span>
				<div class="star">
				<b class="staricon"><em style="width:<?=$item[it_star_avg]*20?>%;"></em></b>
				<strong class="grade"><?=$item[it_star_avg]?></strong>
				</div>
				<a href="item_star_form.php?it_id=<?=$item[it_id]?>"><span class="btn">후기작성하기</span></a>
			</div>
		</div>
		<div class="right">
		
		<div class="star-rate">
			
			<?
			for($i=5; $i>0; $i--){

				$st_star = sql_fetch("select count(*) as cnt from nfor_item_star where st_it_id='$item[it_id]' and st_insert_datetime <= '$nfor[ymdhis]' and st_parent='0' and st_view='1' and st_star='$i'");
				
			?>
			<div class="star">
				<a href="#" class="selected">
					<b class="staricon"><em style="width:<?=$i*20?>%;"></em></b>
					<span class="bar"><i style="width:0%;"></i></span>
				</a>
				<span class="num"><em><?=number_format($st_star[cnt])?></em>개</span>
			</div>
			<? } ?>

		</div>
		
		
		</div>
	</div>



	<form name="item_star_list" class="form_star_reply form_star_delete" method="post">
	<input type="hidden" name="mode" id="mode_star">
	<input type="hidden" name="it_id" id="it_id" value="<?=$item[it_id]?>">
	<input type="hidden" name="wr_reply" id="wr_reply_star">
	<input type="hidden" name="st_id" id="st_id_star">
	<ul class="star_list"><? include_once "item_star_list.php"; ?></ul>
	</form>
</div>

<SCRIPT LANGUAGE="JavaScript">
<!--
$(document).ready(function() {

	$(document).on("click", ".star_update", function (){
		var st_id = $(this).data('st_id');
		location.href = "item_star_form.php?it_id="+it_id+"&st_id="+st_id;
	});

	$(document).on("click", ".star_delete", function (){
		var st_id = $(this).data('st_id');
		var mode = $(this).data('mode');
		$("#mode_star").val(mode);
		$('#st_id_star').val(st_id);
		$('#wr_reply_star').val('');
		if(confirm("상품평 삭제시에는 복구 및 재등록은 불가능합니다.\n정말 삭제하시겠습니까?")){
			$.ajax({
				type: "post",
				data : $(".form_star_"+mode).serialize(),
				url: "item_star_form.php",
				success: function(response){
					var json = $.parseJSON(response); 
					if(json["result"]=="ok"){
						item_star_load(it_id,'1');
					} else{
						alert(json["msg"]);
					}
				}
			});
		}
	});

});
//-->
</SCRIPT>