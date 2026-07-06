<?php
include_once "path.php";
include_once "inc_review_head.php";

if($mode=="list_cancel"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		review_cancel($_POST[$id][$k]);
	}
	json_return("리뷰어 미선정 처리었습니다","ok");
}

if($mode=="list_delete"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		review_delete($_POST[$id][$k]);
	}
	json_return("신청서가 삭제되었습니다","ok");
}

if($mode=="list_asign"){ // 1차 선정 → 1차 후보(rv_step 8)로 이동, 회원 알림 없음
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		review_pre_asign($_POST[$id][$k]);
	}
	json_return("1차 후보로 이동했습니다 (회원 알림 없음)\n'1차 후보' 메뉴에서 검토 후 2차 확정하세요","ok");
}

if($mode=="asign"){
	demo_check_json();
	review_pre_asign($$id);
	json_return("1차 후보로 이동했습니다 (회원 알림 없음)","ok");
}

// 신청(1) + 선정후취소(6) 함께 표시 — 선정후 취소된 사람도 신청목록에서 다시 보이고 재선정 가능(요청: 양근형 2026-06-23). 취소이력은 '선정후취소목록'(step6)에 그대로 유지됨.
$sql_search = " where rv_step in ('1','6') and rv_delete='0' ";

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
	<?php if($member[mb_admin]>=7){ ?>
	<th>배송지정보</th>
	<?php } ?>
	<th>신청 정보</th>
	<th>신청일</th>
	<?php if($member[mb_admin] >= $config[cf_review_form]){ ?>
	<th>수정</th>
	<?php } ?>

	<?php if($member[mb_admin] >= $config[cf_review_asign]){ ?>
	<th>상태변경</th>
	<?php } ?>
</tr>
<?php
for($i=0; $row=sql_fetch_array($result); $i++){
	$row = nfor_tag_out($row);

	$row["chk[]"] = $i;
	$row["{$id}[{$i}]"] = $row[$id];
?>
<tr<?php if($row[rv_step]=='6'){ ?> style="background:#fff7f8;"<?php } ?>>
	<td><?=admin_checkbox($row,"chk[]","chk")?><?=admin_hidden($row,"{$id}[{$i}]")?></td>
	<td><?=$row[rv_id]?><?php if($row[rv_step]=='6'){ ?><br><span style="display:inline-block;margin-top:4px;background:#fdecef;color:#d83a48;border:1px solid #f3a6b1;font-size:11px;font-weight:800;padding:2px 7px;border-radius:999px;white-space:nowrap;">선정후취소</span><?php } ?></td>
	<td><a href="<?=$nfor[path]?>/campaign.php?cp_id=<?=$row[rv_cp_id]?>" target="_blank"><?=$row[rv_cp_subject]?><br><?=$row[rv_cp_id]?></a></td>
	<td>
		<a <?php if($member[mb_admin]>=7){ ?>href="javascript:member('<?=$row[rv_mb_no]?>')"<?php } ?>>
			<?=campaign_member_info($row)?>
		</a>
		<?php
		$sql = sql_query("select * from `nfor_member` where mb_no = '{$row[rv_mb_no]}' ");
		$result_mem = sql_fetch_array($sql);
		?>
		<span class="mc-mi-meta"><?=admin_a("review_singo", "신고", "btn btn-white btn-sm mc-report-link", "data-data=\"rv_id=$row[rv_id]\"", "#self")?></span>
	</td>
	<td class="textleft mc-channel-cell">
		<?=channel_btn($row[rv_channel], $row[rv_media])?>
		<?php if($result_mem[mb_blog]) { ?>
		<span class="mc-ch-meta"><span id="blog_totalcount2_<?=$result_mem[mb_blog]?>">브론즈</span> <span id="blog_totalcount_<?=$result_mem[mb_blog]?>" data-ref="<?=$result_mem[mb_blog]?>">0</span> 방문</span>
		<?php } ?>
	</td>
	<?php if($member[mb_admin]>=7){ ?>
	<td class="textleft">
	<?=$row[rv_dy_name]?> <?=$row[rv_dy_hp]?><br>
	<?=$row[rv_dy_zip]?> <?=$row[rv_dy_addr1]?> <?=$row[rv_dy_addr2]?>
	</td>
	<?php } ?>
	<td class="mc-msg-cell"><?=admin_textarea($row,"rv_msg")?><?=admin_textarea($row,"rv_memo")?><button type="button" class="btn btn-white btn-sm mc-msg-btn">신청해요</button></td>
	<td class="mc-date"><?=substr($row[rv_datetime],0,10)?></td>
	<?php if($member[mb_admin] >= $config[cf_review_form]){ ?>
	<td><?=admin_a("edit", "수정", "btn btn-white btn-sm", "", "{$form}?{$qstr}&{$id}={$row[$id]}")?></td>
	<?php } ?>

	<?php if($member[mb_admin] >= $config[cf_review_asign]){ ?>
	<td><?=admin_a("asign", "1차 선정", "btn btn-white btn-sm nfor_button", "data-confirm=\"1차 후보로 보내시겠습니까?\n(회원 알림 없음 · 2차 확정 때 알림)\" data-data=\"mode=asign&{$id}={$row[$id]}\"")?></td>
	<?php } ?>
</tr>
<?php
}
$pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");
?>
</table>

<div class="bottom_btn">

	<div class="form-inline">
	<?php if($member[mb_admin] >= $config[cf_review_asign]){ ?>
	<?=admin_button("list_asign", "▶ 1차 선정 (후보로 이동)", "btn btn-lg btn-red")?>
	<?php } ?>

	<?php if($member[mb_admin] >= $config[cf_review_delete]){ ?>
	<?=admin_button("list_delete", "신청서 삭제(DB삭제)", "btn btn-lg btn-black")?>
	<?php } ?>

	<?php if($member[mb_admin] >= $config[cf_review_cancel]){ ?>
	<?=admin_button("list_cancel", "리뷰어 미선정", "btn btn-lg btn-black")?>
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

$(document).on("click", "#list_asign", function(){
	// 1차 선정 — 1차 후보로 이동(회원 알림 없음). 정원 무관하게 넉넉히 추리므로 과선정 경고 없음(경고는 2차 확정에서).
	nfor_list_reload('1차 선정(후보로 이동)','list_asign');
});

$(document).on("click", "#list_delete", function(){
	nfor_list_reload('신청서 삭제','list_delete');
});

$(document).on("click", "#list_cancel", function(){
	nfor_list_reload('리뷰어 미선정','list_cancel');
});
//-->
</script>
<?php
// 블로그 등급/방문수 표시는 공통 JS(mc_blog_count.js, inc_review_search.php에서 로드)로 이동함
include_once "tail.php";
?>