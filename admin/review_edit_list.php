<?php
include_once "path.php";
include_once "inc_review_head.php";

if($mode=="list_cancel2"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		review_asign_cancel_after($_POST[$id][$k]);
	}
	json_return("리뷰 선정후 취소 처리었습니다","ok");
}

if($mode=="list_asign"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		review_confirm($_POST[$id][$k]);
	}
	json_return("등록완료처리 되었습니다\n선택된 신청서는 등록완료목록으로 이동되었습니다","ok");
}

if($mode=="asign"){
	demo_check_json();
	review_confirm($$id);
	json_return("리뷰어 선정이 완료되었습니다\n선택된 신청서는 리뷰어 선정목록 메뉴로 이동되었습니다","ok");
}

$sql_search = " where rv_step='7' and rv_delete='0' ";

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
	<th>신청채널/리뷰URL</th>
	<th>간단 리뷰설명</th>
	<th>신청일/<br>선정일</th>
	<th>리뷰URL등록일</th>
	<?php if($member[mb_admin] >= $config[cf_review_form]){ ?>
	<th>수정</th>
	<?php } ?>

	<?php if($member[mb_admin] >= $config[cf_review_edit]){ ?>
	<th>수정요청</th>
	<?php } ?>

	<?php if($member[mb_admin] >= $config[cf_review_traffic]){ ?>
	<th>유입분석</th>
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
	<td><a <?php if($member[mb_admin]>=7){ ?>href="javascript:member('<?=$row[rv_mb_no]?>')"<?php } ?>><?=campaign_member_info($row)?></a><span class="mc-mi-meta"><?=admin_a("review_singo", "신고", "btn btn-white btn-sm mc-report-link", "data-data=\"rv_id=$row[rv_id]\"", "#self")?></span></td>
	<td class="textleft mc-channel-cell">
		<?=channel_btn($row[rv_channel], $row[rv_media])?>
		<?php if($row[rv_url]){ ?><br><a href="<?=$row[rv_url]?>" target="_blank" class="btn btn-white btn-sm mc-channel-btn mc-review-btn">리뷰 보러가기</a><?php } ?>
		<?php $rm = sql_fetch("select mb_blog from nfor_member where mb_no='{$row[rv_mb_no]}'"); if($rm[mb_blog]){ ?>
		<span class="mc-ch-meta"><span id="blog_totalcount2_<?=$rm[mb_blog]?>">브론즈</span> <span id="blog_totalcount_<?=$rm[mb_blog]?>" data-ref="<?=$rm[mb_blog]?>">0</span> 방문</span>
		<?php } ?>
	</td>
	<td><?=admin_textarea($row,"rv_review")?></td>
	<td><?=substr($row[rv_datetime],0,10)?><br><?=substr($row[rv_asign_datetime],0,10)?></td>
	<td><?=substr($row[rv_reg_datetime],0,10)?></td>
	<?php if($member[mb_admin] >= $config[cf_review_form]){ ?>
	<td><?=admin_a("edit", "수정", "btn btn-white btn-sm", "", "{$form}?{$qstr}&{$id}={$row[$id]}")?></td>
	<?php } ?>

	<?php if($member[mb_admin] >= $config[cf_review_edit]){ ?>
	<td><?=admin_a("asign", "리뷰 수정요청", "btn btn-white btn-sm review_edit", "data-data=\"{$id}={$row[$id]}\"")?></td>
	<?php } ?>

	<?php if($member[mb_admin] >= $config[cf_review_traffic]){ ?>
	<td><?=$row[rv_media]=="blog"?admin_a("traffic", "유입분석", "btn btn-white btn-sm", "data-data=\"rv_cp_id=$row[rv_cp_id]&rv_mb_no=$row[rv_mb_no]&window=1\"", "#self"):""?></td>
	<?php } ?>
</tr>
<?php
}
$pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");
?>
</table>

<div class="bottom_btn">
	
	<div class="form-inline">
	<?php if($member[mb_admin] >= $config[cf_review_confirm]){ ?>
	<?=admin_button("list_asign", "등록완료처리", "btn btn-lg btn-red")?>
	<?php } ?>

	<?php if($member[mb_admin] >= $config[cf_review_asign_cancel]){ ?>
	<?=admin_button("list_cancel2", "리뷰 선정후 취소", "btn btn-lg btn-black")?>
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
	nfor_list_reload('등록완료처리','list_asign');
});

$(document).on("click", "#list_cancel2", function(){
	nfor_list_reload('리뷰 선정후 취소','list_cancel2');
});
//-->
</script>

<?php
include_once "tail.php";
?>