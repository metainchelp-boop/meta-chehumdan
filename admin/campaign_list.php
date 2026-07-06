<?php
include_once "path.php";
include_once "inc_campaign_head.php";

if($mode=="list_asign1"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		sql_query("update $table set cp_asign='1' where $id='{$_POST[$id][$k]}'");
	}
	json_return("정상적으로 미승인되었습니다","ok");
}

if($mode=="list_asign2"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		sql_query("update $table set cp_asign='2' where $id='{$_POST[$id][$k]}'");
	}
	json_return("정상적으로 승인되었습니다","ok");
}

if($mode=="list_asign3"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		sql_query("update $table set cp_asign='3' where $id='{$_POST[$id][$k]}'");
	}
	json_return("정상적으로 보류되었습니다","ok");
}

if($mode=="list_delete"){
	demo_check_json();

	$is_review = 0;
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];		
		$chk_review = sql_fetch("select * from nfor_review where rv_cp_id='{$_POST[$id][$k]}'");
		if(!$chk_review[rv_cp_id]){
			sql_query("delete from $table where $id='{$_POST[$id][$k]}'");
			sql_query("delete from nfor_campaign_zzim where zz_cp_id='{$_POST[$id][$k]}'");
			sql_query("delete from nfor_campaign_view where cv_cp_id='{$_POST[$id][$k]}'");
			//sql_query("delete from nfor_campaign_order where co_cp_id='{$_POST[$id][$k]}'");
			//sql_query("delete from nfor_review where rv_cp_id='{$_POST[$id][$k]}'");
		} else{
			$is_review++;
		}		
	}
	json_return($is_review?"리뷰 참여가 있는 ".$is_review."건을 제외하고 나머지를 삭제하였습니다":"정상적으로 삭제되었습니다","ok");
}

if($mode=="delete"){
	demo_check_json();
	$chk_review = sql_fetch("select * from nfor_review where rv_cp_id='{$$id}'");
	if(!$chk_review[rv_cp_id]){
		sql_query("delete from $table where $id='{$$id}'");
		sql_query("delete from nfor_campaign_zzim where zz_cp_id='{$$id}'");
		sql_query("delete from nfor_campaign_view where cv_cp_id='{$$id}'");
		//sql_query("delete from nfor_campaign_order where co_cp_id='{$$id}'");
		//sql_query("delete from nfor_review where rv_cp_id='{$$id}'");
		json_return("정상적으로 삭제되었습니다","ok");
	} else{
		json_return("리뷰 참여가 있는 캠페인은 삭제할수 없습니다","ok");
	}
}

include_once "inc_campaign_tail.php";
include_once "head.php";
include_once "inc_campaign_search.php";

// 담당자/진행중 필터를 페이징·수정·다음회차 링크에 유지 (2026-06-17 — 2페이지 이동 시 필터 풀리던 문제 수정)
if($cp_md_name!=='' && strpos((string)$qstr,'cp_md_name=')===false) $qstr .= "&cp_md_name=".urlencode($cp_md_name);
if($mc_ing!=='' && strpos((string)$qstr,'mc_ing=')===false) $qstr .= "&mc_ing=".urlencode($mc_ing);
if($mc_phase!=='' && strpos((string)$qstr,'mc_phase=')===false) $qstr .= "&mc_phase=".urlencode($mc_phase);
?>

<form name="flist" id="flist" method="post">
<?=admin_hidden($hidden,"ex_id")?>
<?=admin_get()?>
<table class="table row_tbl margin0">
<tr>
	<th><?=admin_checkbox($row,"chkall")?></th>
	<th>대표이미지</th>
	<th>캠페인코드</th>
	<th>제목</th>
	<th>승인상태</th>
	<th>노출상태</th>
	<th>모집/신청</th>
	<th>신청기간/<br>신청인원</th>
	<th>1차 후보</th>
	<th>선정자 발표/<br>선정인원</th>
	<th>리뷰 등록기간/<br>등록인원</th>
	<th>캠페인 결과발표/<br>등록확인인원</th>
	<th>등록일/<br>수정일</th>
	<!-- <th>조회수</th> -->

	<?php if($config[cf_campaign_payment_use]=="1"){ ?>
	<th>결제하기<br>(별도구매)</th>
	<?php } ?>
	
	<?php if($member[mb_admin] >= $config[cf_campaign_report]){ ?>
	<th>결과보고서</th>
	<?php } ?>

	<?php if($member[mb_admin] >= $config[cf_campaign_edit]){ ?>
	<th>수정</th>
	<?php } ?>

	<?php if($member[mb_admin] >= $config[cf_campaign_edit]){ ?>
	<th>다음회차</th>
	<?php } ?>

	<?php if($member[mb_admin] >= $config[cf_campaign_delete]){ ?>
	<th>삭제</th>
	<?php } ?>
</tr>
<?php
for($i=0; $row=sql_fetch_array($result); $i++){	
	$row["chk[]"] = $i;
	$row["{$id}[{$i}]"] = $row[$id];

	$row[cp_url] = "{$nfor[path]}/campaign.php?cp_id={$row[cp_id]}";
?>
<tr>
	<td><?=admin_checkbox($row,"chk[]","chk")?><?=admin_hidden($row,"{$id}[{$i}]")?></td>
	<td><a href="<?=$row[cp_url]?>" target="_blank"><?=admin_img("campaign",$row[cp_img],"50","50")?></a></td>
	<td><a href="<?=$row[cp_url]?>" target="_blank"><?=$row[cp_id]?></a></td>
	<td class="textleft">
		<a href="<?=$row[cp_url]?>" target="_blank"><?=$row[cp_subject]?><br><?=$row[cp_description]?></a>
		<div class="sns_icon_wrap">
		<? if($row[cp_media_blog]){ ?><span class="blog_icon">블로그</span><? } ?>
		<? if($row[cp_media_instagram]){ ?><span class="instagram_icon">인스타그램</span><? } ?>
		<? if($row[cp_media_youtube]){ ?><span class="youtube_icon">유튜브</span><? } ?>
		<? if($row[cp_media_shop]){ ?><span class="shop_icon">쇼핑몰</span><? } ?>
		<? if($row[cp_ohouse]){ ?><span class="ohouse_icon">오늘의집</span><? } ?>
		<? if($row[cp_coupang]){ ?><span class="coupang_icon">쿠팡</span><? } ?>
		</div>
		<br><?=$row[cp_supply_no]?admin_echo($row,"cp_supply_no"):""?>
	</td>
	<td><?=admin_echo($row,"cp_asign")?></td>
	<td><?=admin_echo($row,"cp_use")?></td>
	<td><?=number_format($row[cp_recruit])?>명/<?=number_format($row[cp_order])?>명</td>
	<td>
		<?=substr($row[cp_sdatetime],0,10)?><br><?=substr($row[cp_edatetime],0,10)?><br>
		<a href="review_wait_list.php?rv_cp_id=<?=$row[cp_id]?>"><?=number_format($row[cp_review_wait])?>명</a>
	</td>
	<td style="text-align:center">
		<?php $pre_cnt = sql_fetch("select count(*) as c from nfor_review where rv_cp_id='".addslashes($row[cp_id])."' and rv_step='8' and rv_delete='0'"); $pre_c=(int)$pre_cnt[c]; ?>
		<a href="review_pre_list.php?rv_cp_id=<?=$row[cp_id]?>" <?=$pre_c>0?'style="color:#e8590c;font-weight:800"':'style="color:#adb5bd"'?>><?=number_format($pre_c)?>명</a>
	</td>
	<td><?=substr($row[cp_pick_datetime],0,10)?><br><a href="review_asign_list.php?rv_cp_id=<?=$row[cp_id]?>"><?=number_format($row[cp_review_asign])?>명</a></td>
	<td><?=substr($row[cp_contents_sdatetime],0,10)?><br><?=substr($row[cp_contents_edatetime],0,10)?><br><a href="review_post_list.php?rv_cp_id=<?=$row[cp_id]?>"><?=number_format($row[cp_review_post])?>명</a></td>
	<td><?=substr($row[cp_result_datetime],0,10)?><br><a href="review_post_asign_list.php?rv_cp_id=<?=$row[cp_id]?>"><?=number_format($row[cp_review_post_asign])?>명</a></td>
	<td><?=substr($row[cp_insert_datetime],0,10)?><br><?=substr($row[cp_update_datetime],0,10)?></td>
	<!-- <td><?=number_format($row[cp_click])?></td> -->
	
	<?php if($config[cf_campaign_payment_use]=="1"){ ?>
	<td>
		<?php if(!$row[cp_pay_step] or $row[cp_pay_step]=="3" or $row[cp_pay_step]=="5"){ ?>
		<?=admin_a("payment", "결제하기", "btn btn-white btn-sm nfor_window", " data-file=\"campaign_payment.php\" data-data=\"{$id}={$row[$id]}\"")?>
		<?php } else{ ?>
		<?=admin_echo($row,"cp_pay_step")?>
		<? } ?>
	</td>
	<?php } ?>

	<?php if($member[mb_admin] >= $config[cf_campaign_report]){ ?>
	<td><?=admin_a("report", "결과보고서", "btn btn-white btn-sm nfor_window", " data-file=\"campaign_report.php\" data-data=\"{$id}={$row[$id]}\"")?></td>
	<?php } ?>

	<?php if($member[mb_admin] >= $config[cf_campaign_edit]){ ?>
	<td><?=admin_a("edit", "수정", "btn btn-white btn-sm", "", "{$form}?{$qstr}&{$id}={$row[$id]}")?></td>
	<?php } ?>

	<?php if($member[mb_admin] >= $config[cf_campaign_edit]){ ?>
	<td><?=admin_a("copy", "다음회차", "btn btn-sm", " style=\"background:#dc2626;border-color:#dc2626;color:#fff;white-space:nowrap;\" title=\"일정만 비우고 동일 정보로 신규 등록\"", "{$form}?{$qstr}&copy={$row[$id]}")?></td>
	<?php } ?>

	<?php if($member[mb_admin] >= $config[cf_campaign_delete]){ ?>
	<td><?=admin_a("delete", "삭제", "btn btn-white btn-sm nfor_button", "data-confirm=\"삭제하시겠습니까?\" data-data=\"mode=delete&{$id}={$row[$id]}\"")?></td>
	<?php } ?>
</tr>
<?php
}
$pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");
?>
</table>

<div class="bottom_btn">

	<div class="form-inline">
	<?php if($member[mb_admin] >= $config[cf_campaign_reject]){ ?>
	<?=admin_button("list_asign1", "미승인변경", "btn btn-lg")?>
	<?php } ?>

	<?php if($member[mb_admin] >= $config[cf_campaign_asign]){ ?>
	<?=admin_button("list_asign2", "승인변경", "btn btn-lg")?>
	<?php } ?>

	<?php if($member[mb_admin] >= $config[cf_campaign_hold]){ ?>
	<?=admin_button("list_asign3", "보류변경", "btn btn-lg")?>
	<?php } ?>

	<?php if($member[mb_admin] >= $config[cf_campaign_delete]){ ?>
	<?=admin_button("list_delete", "선택삭제", "btn btn-lg btn-red")?>
	<?php } ?>
	
	<?php
	$access_level = sql_fetch("select * from nfor_access where access_file='campaign_form.php'");
	if($member[mb_admin] >= $access_level[access_level]){
	?>
	<?=admin_a("form", "등록하기", "btn-lg btn-black btn", "", $form)?>
	<?php } ?>
	</div>

</div>

<div class="table_btn"><?=$pagelist?></div>

</form>

<script type="text/javascript">
<!--
$(document).on("click", ".nfor_window", function(){

	var data_str = $(this).data("data");
	var file_str = $(this).data("file");
	location.href = file_str+"?"+data_str;

});

$(document).on("click", "#chkall", function(){
	nfor_chk_all(this, 'chk');
});
$(document).on("click", "#list_delete", function(){
	nfor_list_reload('삭제','list_delete');
});
$(document).on("click", "#list_asign1", function(){
	nfor_list_reload('미승인','list_asign1');
});
$(document).on("click", "#list_asign2", function(){
	nfor_list_reload('승인','list_asign2');
});
$(document).on("click", "#list_asign3", function(){
	nfor_list_reload('보류','list_asign3');
});
//-->
</script>

<?php
include_once "tail.php";
?>