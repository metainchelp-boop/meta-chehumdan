<?php
include_once $nfor[skin_path]."mypage_head.php";
?>

<style>
.tb_form { margin-bottom:20px; }


#ct_cancel_why { width:300px; padding:6px 5px 5px; margin:10px 0px;  height:30px; vertical-align:middle;  border:solid 1px #d0d0d0; color:#828284; background:url(<?=$nfor[skin_path]?>img/select_background.png) no-repeat 100% 50%; font-size:12px; -webkit-appearance:none; -moz-appearance:none; appearance:none; }

#ct_cancel_memo { width:100%; height:60px; border:1px solid;border-color:#bebec0 #d6d6d6 #eaeaea #d6d6d6; color:#666; font-size:12px; padding:10px; }

.order_info { margin:0px 0px 20px; border:0; }
.order_info .go_to_item { padding:0; width:100%; border:0; box-sizing:border-box; -webkit-box-sizing:border-box; }
.order_info .buy_list { width:100%; border:0; box-sizing:border-box; -webkit-box-sizing:border-box; }
</style>

<!-- 주문상품 -->
<?php
$print = $return["list"];
include $nfor[skin_path]."inc_order_list.php";
?>
<!-- //주문상품 -->

<!-- 환불정보 -->
<table cellpadding="0" cellspacing="0" border="0" class="tb_form">
<colgroup>
	<col style="width:25%">
	<col>
</colgroup>
<?php 
for($i=0; $i<count($return["view"]); $i++){
	$cancel = $return["view"][$i];
?>
<tr>
	<th><?=$cancel[text]?></th>
	<td><?=$cancel[value]?></td>
</tr>
<?php } ?>
</table>
<!-- //환불정보 -->

<!-- 취소사유 -->
<form name="forder_cancelrequest" id="forder_cancelrequest" method="post">
<input type="hidden" name="mode" value="order_cancelrequest">
<?=admin_hidden($write,"od_id")?>
<?=admin_hidden($write,"it_id")?>
<table cellpadding="0" cellspacing="0" border="0" class="tb_form">
<colgroup>
	<col style="width:25%">
	<col>
</colgroup>
<tr>
	<th>취소사유</th>
	<td><?=admin_select($write,"ct_cancel_why","","","0")?></td>
</tr>
<tr>
	<th>상세사유</th>
	<td><?=admin_textarea($write,"ct_cancel_memo",""," placeholder=\"상세사유를 입력해주세요\"")?></td>
</tr>
</table>
</form>
<!-- //취소사유 -->

<div class="bottom_btn">
	<span class="btn_pack"><a class="btn_lg black" href="javascript:history.back()">뒤로가기</a></span>
	<span class="btn_pack"><a class="btn_lg white" id="order_cancel_submit_btn">주문취소</a></span>
</div>

<script>
$(document).on("click","#order_cancel_submit_btn",function(){
	$.ajax({
		type:"post",
		data :$("#forder_cancelrequest").serialize(),
		url:"order_cancel.php",
		success:function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				if(json["msg"]){
					alert(json["msg"]);
				}
				if(json["url"]){
					location.href = json["url"];
				}
			} else{
				if(json["msg"]){
					alert(json["msg"]);
				}
				if(json["result"]){
					$("#"+json["result"]).focus();
				}
			}
		}
	});
	event.preventDefault();
});
</script>

<?php
include_once $nfor[skin_path]."mypage_tail.php";
?>