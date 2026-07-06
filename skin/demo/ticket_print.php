<?php
include_once "html_head.php";
?>


<select id="lo_id">
<option value="" disabled>매장을 선택하세요
<?php
$que = sql_query("select * from nfor_item_location where lo_it_id='$it_id' and lo_use='1' order by lo_id asc");
while($data = sql_fetch_array($que)){
?>
<option value="<?=$data[lo_id]?>"><?=$data[lo_name]?>
<? } ?>
</select>


<div id="print_area">

	주문상품 : <?=$item[it_name]?>

	<table border="1">
	<tr>
		<td>주문옵션</td>
		<td>구매수량</td>
		<td>사용수량</td>
		<td>티켓번호</td>
	</tr>
	<?php
	$que = sql_query("select * from nfor_cart where ct_cart_id='$order[od_cart_id]' and ct_it_id='$item[it_id]'");
	while($ct = sql_fetch_array($que)){
	?>
	<tr>
		<td><?=str_replace("||","/",$ct[ct_value])?></td>
		<td><?=number_format($ct[ct_opt_cnt])?>개</td>
		<td><?=number_format($ct[ct_tk_used])?>개</td>
		<td><?=ticket_number($ct)?></td>
	</tr>
	<?php
	}
	?>
	</table>

	<table border="1">
	<tr>
		<th>구매자 이름</th>
		<td><?=$order[od_name]?></td>
	</tr>
	<tr>
		<th>구매자 연락처</th>
		<td><?=$order[od_hp]?></td>
	</tr>
	<tr>
		<th>구매자 이메일</th>
		<td><?=$order[od_email]?></td>
	</tr>
	</table>

	<div id="item_location_info"><? include_once $nfor[skin_path]."inc_ticket_print.php"; ?></div>

</div>



<a href="javascript:printarea()">티켓 프린트</a>



<script type="text/javascript">
<!--
$(document).on("change", "#lo_id", function(){
	$.get("<?=$nfor[skin_path]?>inc_ticket_print.php",{
		lo_id : $("#lo_id").val()
	}, function(data){
		$("#item_location_info").html(data);
	});
});


var initBody 
function beforePrint(){ 
	initBody = document.body.innerHTML; 
	document.body.innerHTML = print_area.innerHTML; 
} 
function afterPrint(){ 
	document.body.innerHTML = initBody; 
} 
function printarea(){ 
	window.print(); 
} 
window.onbeforeprint = beforePrint; 
window.onafterprint = afterPrint; 
//-->
</script>

<?php
include_once "html_tail.php";
?>