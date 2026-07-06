<?php
include_once "path.php";
include_once "inc_campaign_order_head.php";

$sql_search = " where co_pay_step='7' ";

include_once "inc_campaign_order_tail.php";
include_once "head.php";
include_once "inc_campaign_order_search.php";
?>

<form name="flist" id="flist" method="post">
<?=admin_hidden($hidden,"ex_id")?>
<?=admin_get()?>
<table class="table row_tbl margin0">
<tr>
	<th><?=admin_checkbox($row,"chkall")?></th>
	<th>회원정보</th>
	<th>캠페인코드</th>
	<th>캠페인명</th>
	<th>캠페인 형태</th> 
	<th>제공내역 형태</th> 
	<th>모집인원</th>
	<th>캠페인등록비</th>
	<th>포인트지급비</th>
	<th>합산결제금액</th>
	<th>취소금액</th>
	<th>주문일자</th>
	<th>결제일자</th>
	<th>결제방법</th>
	<th>입금계좌/입금자명</th>
	<th>결제상태</th>
	<?php if($is_admin){ ?>
	<th>상태변경</th>
	<?php } ?>
</tr>
<?php
for($i=0; $row=sql_fetch_array($result); $i++){	
	$row["chk[]"] = $i;
	$row["{$id}[{$i}]"] = $row[$id];

	$msg = addslashes($row[co_cp_subject])."(".$row[co_cp_id].")";
?>
<tr>
	<td><?=admin_checkbox($row,"chk[]","chk")?><?=admin_hidden($row,"{$id}[{$i}]")?></td>
	<td><a href="javascript:member('<?=$row[co_mb_no]?>')"><?=campaign_member_info($row)?></a></td>
	<td><?=$row[co_cp_id]?></td>
	<td><?=$row[co_cp_subject]?></td>
	<td><?=admin_echo($row,"co_cp_type")?></td>
	<td><?=admin_echo($row,"co_cp_reward_type")?></td>	
	<td><?=number_format($row[co_cp_recruit])?>명</td>
	<td><?=number_format($row[co_campaign_price])?>원</td>
	<td><?=number_format($row[co_point_price])?>원</td>
	<td><?=number_format($row[co_total_price])?>원</td>
	<td><?=number_format($row[co_cancel_price])?>원</td>
	<td><?=substr($row[co_datetime],0,10)?></td>
	<td><?=substr($row[co_pay_datetime],0,10)?></td>
	<td><?=admin_echo($row,"co_payment_type")?></td>
	<td><?=$row[co_bank_number]?><br><?=$row[co_bank_name]?></td>
	<td><?=admin_echo($row,"co_pay_step")?></td>
	<?php if($is_admin){ ?>
	<td>
		<?php if($row[co_total_price]<>$row[co_cancel_price]){ ?>
		<?=admin_a("ea_cancel_btn", "부분취소처리", "btn btn-white btn-sm nfor_window", "data-url=\"pg_inipay_cancel.php\" data-data=\"{$id}={$row[$id]}\"")?>
		<?php } ?>
	</td>
	<?php } ?>
</tr>
<?php
}
$pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");
?>
</table>

<div class="table_btn"><?=$pagelist?></div>

</form>

<script>
$(document).on("click", ".nfor_window", function(){
	var data_str = $(this).data("data");
	var url_str = $(this).data("url");
	window.open(url_str+"?"+data_str, "nfor_window", "left=50,top=50,width=540,height=600,scrollbars=1");
});
</script>

<?php
include_once "tail.php";
?>