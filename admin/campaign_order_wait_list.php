<?php
include_once "path.php";

if($mode=="asign"){
	sql_query("update nfor_campaign_order set co_pay_step='1', co_pay_datetime=NOW() where co_id='$co_id'");

	$write = sql_fetch("select * from nfor_campaign_order where co_id='$co_id'");
	sql_query("update nfor_campaign set cp_pay_step='1' where cp_id='$write[co_cp_id]'");

	json_return("결제상태를 결제완료로 변경하였습니다","ok");
}

include_once "inc_campaign_order_head.php";

$sql_search = " where co_pay_step='4' ";

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
	<th>주문일자</th>
	<th>결제방법</th>
	<th>입금계좌/입금자명/입금기한</th>
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
	<td><?=substr($row[co_datetime],0,10)?></td>
	<td><?=admin_echo($row,"co_payment_type")?></td>
	<td><?=$row[co_bank_number]?><br><?=$row[co_bank_name]?><br><?=$row[co_bank_expire]?></td>
	<td><?=admin_echo($row,"co_pay_step")?></td>
	<?php if($is_admin){ ?>
	<td>
		<? if($row[co_pay_step]=="4" and $row[co_payment_type]=="banking"){ ?>
		<?=admin_a("asign_btn", "주문완료처리", "btn btn-white btn-sm nfor_button", "data-confirm=\"주문완료처리대상\n$msg\n\n확인 버튼을 누르실경우 주문서 상태가 주문완료상태로 변경됩니다 그래도 진행하시겠습니까?\" data-data=\"mode=asign&{$id}={$row[$id]}\"")?>
		<? } ?>
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

<?php
include_once "tail.php";
?>