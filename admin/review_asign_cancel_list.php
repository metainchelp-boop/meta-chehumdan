<?php
include_once "path.php";
include_once "inc_review_head.php";


if($mode=="list_asign"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		review_asign($_POST[$id][$k]);
	}
	json_return("신청서가 복구 완료되었습니다\n선택된 신청서는 리뷰어 선정목록 메뉴로 이동되었습니다","ok");
}


if($mode=="list_hidden"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		review_hidden($_POST[$id][$k]);
	}
	json_return("관리자모드에서 신청서가 숨김처리되었습니다","ok");
}


if($mode=="list_delete"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		review_delete($_POST[$id][$k]);
	}
	json_return("신청서가 삭제되었습니다","ok");
}


$sql_search = " where rv_step='6' and rv_delete='0' ";

include_once "inc_review_tail.php";
include_once "head.php";
include_once "inc_review_search.php";
?>

<form name="flist" id="flist" method="post">
<?=admin_hidden($hidden,"ex_id")?>
<?=admin_get()?>
<table class="table row_tbl margin0">
<tr>
	<th><?=admin_checkbox($row,"chkall")?></th>
	<th>신청번호</th>
	<th>캠페인명/캠페인코드</th>
	<th>회원정보</th>
	<th>신청채널</th>
	<th>배송지정보</th>
	<th>신청자 한마디/신청필수 정보/간단 리뷰설명</th>
	<th>신청일/<br>선정일</th>
	<?php if($member[mb_admin] >= $config[cf_review_form]){ ?>
	<th>수정</th>
	<?php } ?>
</tr>
<?php
for($i=0; $row=sql_fetch_array($result); $i++){	
	$row = nfor_tag_out($row);

	$row["chk[]"] = $i;
	$row["{$id}[{$i}]"] = $row[$id];
?>
<tr>
	<td><?=admin_checkbox($row,"chk[]","chk")?><?=admin_hidden($row,"{$id}[{$i}]")?></td>
	<td><?=$row[rv_id]?></td>
	<td><a href="<?=$nfor[path]?>/campaign.php?cp_id=<?=$row[rv_cp_id]?>" target="_blank"><?=$row[rv_cp_subject]?><br><?=$row[rv_cp_id]?></a></td>
	<td><a <?php if($member[mb_admin]>=7){ ?>href="javascript:member('<?=$row[rv_mb_no]?>')"<?php } ?>><?=campaign_member_info($row)?></a></td>
	<td class="textleft"><?=channel_url($row[rv_channel])?><?php if($row[rv_media]){ ?><div class="sns_icon_wrap"><span class="<?=$row[rv_media]?>_icon"><?=admin_echo($row,"rv_media")?></span></div><?php } ?></td>
	<td class="textleft">
	<?=$row[rv_dy_name]?> <?=$row[rv_dy_hp]?><br>
	<?=$row[rv_dy_zip]?> <?=$row[rv_dy_addr1]?> <?=$row[rv_dy_addr2]?>
	</td>
	<td><?=admin_textarea($row,"rv_msg")?><?=admin_textarea($row,"rv_memo")?><?=admin_textarea($row,"rv_review")?></td>
	<td><?=substr($row[rv_datetime],0,10)?><br><?=substr($row[rv_asign_datetime],0,10)?></td>
	<?php if($member[mb_admin] >= $config[cf_review_form]){ ?>
	<td><?=admin_a("edit", "수정", "btn btn-white btn-sm", "", "{$form}?{$qstr}&{$id}={$row[$id]}")?></td>
	<?php } ?>
</tr>
<?php
}
$pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");
?>
</table>

<div class="bottom_btn">
	
	<div class="form-inline">
	<?php if($member[mb_admin] >= $config[cf_review_admin_hidden]){ ?>
	<?=admin_button("list_hidden", "관리자모드에서 숨기기", "btn btn-lg btn-red")?>
	<?php } ?>

	<?php if($member[mb_admin] >= $config[cf_review_delete]){ ?>
	<?=admin_button("list_delete", "신청서 삭제(DB삭제)", "btn btn-lg btn-red")?>
	<?php } ?>

	<?php if($member[mb_admin] >= $config[cf_review_repair_asign]){ ?>
	<?=admin_button("list_asign", "신청서 복구(선정목록이동)", "btn btn-lg btn-black")?>
	<?php } ?>
	</div>

</div>

<div class="table_btn"><?=$pagelist?></div>

</form>

<script type="text/javascript">
<!--
$(document).on("click", "#chkall", function(){
	nfor_chk_all(this, 'chk');
});

$(document).on("click", "#list_hidden", function(){
	nfor_list_reload('관리자모드에서 숨기기','list_hidden');
});

$(document).on("click", "#list_delete", function(){
	nfor_list_reload('신청서 삭제','list_delete');
});

$(document).on("click", "#list_asign", function(){
	nfor_list_reload('신청서 복구(선정목록이동)','list_asign');
});
//-->
</script>

<?php
include_once "tail.php";
?>