<?php
include_once "path.php";
include_once "inc_review_head.php";

if($mode=="list_hidden"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		review_hidden($_POST[$id][$k]);
	}
	json_return("관리자모드에서 신청서가 숨김처리되었습니다","ok");
}

if($mode=="list_asign1"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		sql_query("update $table set rv_asign_show='1' where $id='{$_POST[$id][$k]}'");

		$data = sql_fetch("select * from $table where $id='{$_POST[$id][$k]}'");
		campaign_count($data[rv_cp_id]);
	}
	json_return("실시간리뷰 노출처리되었습니다","ok");
}

if($mode=="list_asign2"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		sql_query("update $table set rv_asign_show='2' where $id='{$_POST[$id][$k]}'");

		$data = sql_fetch("select * from $table where $id='{$_POST[$id][$k]}'");
		campaign_count($data[rv_cp_id]);
	}
	json_return("실시간리뷰 미노출처리되었습니다","ok");
}

if($mode=="list_post_cancel"){
	demo_check_json();

	// 마이너스 잔액 경고: 회수액 > 회원 보유포인트인 건이 있으면 force 없을 때 경고 (확인 시 force=1로 진행)
	if($_POST['force'] != "1"){
		$__warn = "";
		for($i=0; $i<count($chk); $i++){
			$k = $_POST['chk'][$i];
			$__rv = sql_fetch("select rv_mb_no, rv_cp_id from nfor_review where rv_id='{$_POST[$id][$k]}'");
			$__cp = sql_fetch("select cp_point, cp_reward_type from nfor_campaign where cp_id='".addslashes($__rv['rv_cp_id'])."'");
			if(($__cp['cp_reward_type']=="2" or $__cp['cp_reward_type']=="3") and $__cp['cp_point'] > 0){
				$__mb = sql_fetch("select mb_name, mb_point from nfor_member where mb_no='".addslashes($__rv['rv_mb_no'])."'");
				if($__mb['mb_point'] < $__cp['cp_point']){
					$__warn .= "· ".$__mb['mb_name']." : 보유 ".number_format($__mb['mb_point'])."P / 회수 ".number_format($__cp['cp_point'])."P → 잔액 ".number_format($__mb['mb_point'] - $__cp['cp_point'])."P\n";
				}
			}
		}
		if($__warn){
			json_return("⚠ 아래 회원은 보유 포인트가 회수액보다 적어 잔액이 마이너스가 됩니다.\n\n".$__warn."\n그래도 회수하려면 [확인]을 누르세요.","minus_point");
		}
	}

	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		review_post_cancel($_POST[$id][$k]); // 등록완료 취소 + 지급 포인트 회수 (step4→3 등록인원/검수요청, 2026-06-17)
	}
	json_return("등록완료가 취소되고 지급 포인트가 회수되었습니다.\n해당 건은 '등록인원(검수요청목록)'으로 이동되었습니다.","ok");
}

$sql_search = " where rv_step='4' and rv_delete='0' ";

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
	<th>리뷰URL등록일/<br>리뷰등록확인일</th>
	<th>실시간리뷰노출</th>
	<?php if($member[mb_admin] >= $config[cf_review_form]){ ?>
	<th>수정</th>
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
		<?php if($row[rv_buy_img]){ ?><br><a href="<?=$nfor[path]?>/data/review/<?=$row[rv_buy_img]?>" target="_blank" class="btn btn-white btn-sm mc-channel-btn">🛍 상품 리뷰 보기</a><?php } ?>
		<?php $rm = sql_fetch("select mb_blog from nfor_member where mb_no='{$row[rv_mb_no]}'"); if($rm[mb_blog]){ ?>
		<span class="mc-ch-meta"><span id="blog_totalcount2_<?=$rm[mb_blog]?>">브론즈</span> <span id="blog_totalcount_<?=$rm[mb_blog]?>" data-ref="<?=$rm[mb_blog]?>">0</span> 방문</span>
		<?php } ?>
	</td>
	<td class="mc-msg-cell"><?=admin_textarea($row,"rv_review")?><button type="button" class="btn btn-white btn-sm mc-msg-btn">리뷰 보기</button></td>
	<td><?=substr($row[rv_datetime],0,10)?><br><?=substr($row[rv_asign_datetime],0,10)?></td>
	<td><?=substr($row[rv_reg_datetime],0,10)?><br><?=substr($row[rv_confirm_datetime],0,10)?></td>
	<td><?=admin_echo($row,"rv_asign_show")?></td>
	<?php if($member[mb_admin] >= $config[cf_review_form]){ ?>
	<td><?=admin_a("edit", "수정", "btn btn-white btn-sm", "", "{$form}?{$qstr}&{$id}={$row[$id]}")?></td>
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
	<?php if($member[mb_admin] >= $config[cf_review_show]){ ?>
	<?=admin_button("list_asign1", "실시간리뷰 노출", "btn btn-lg btn-red")?>
	<?php } ?>

	<?php if($member[mb_admin] >= $config[cf_review_hidden]){ ?>
	<?=admin_button("list_asign2", "실시간리뷰 미노출", "btn btn-lg btn-black")?>
	<?php } ?>

	<?php if($member[mb_admin] >= $config[cf_review_admin_hidden]){ ?>
	<?=admin_button("list_hidden", "관리자모드에서 숨기기", "btn btn-lg btn-red")?>
	<?php } ?>

	<?php if($member[mb_admin] >= $config[cf_review_confirm]){ ?>
	<?=admin_button("list_post_cancel", "등록완료 취소(포인트 회수)", "btn btn-lg btn-black")?>
	<?php } ?>
	</div>

</div>

<div class="table_btn"><?=$pagelist?></div>

</form>

<script type="text/javascript">
<!--
$(document).on("click", "#traffic", function(){
	var data = $(this).data("data");
    window.open("traffic_detail.php?"+data, "ticket_chage", "left=50,top=50,width=900,height=800,scrollbars=1");
});

$(document).on("click", "#chkall", function(){
	nfor_chk_all(this, 'chk');
});

$(document).on("click", "#list_asign1", function(){
	nfor_list_reload('실시간리뷰 노출','list_asign1');
});

$(document).on("click", "#list_asign2", function(){
	nfor_list_reload('실시간리뷰 미노출','list_asign2');
});

$(document).on("click", "#list_hidden", function(){
	nfor_list_reload('관리자모드에서 숨기기','list_hidden');
});

$(document).on("click", "#list_post_cancel", function(){
	var chk = document.getElementsByName("chk[]");
	var any = false;
	for(var i=0;i<chk.length;i++){ if(chk[i].checked) any = true; }
	if(!any){ alert("취소할 자료를 하나 이상 선택하세요"); return; }
	if(!confirm("선택한 '등록완료' 건을 취소하고 지급된 포인트를 회수합니다.\n해당 건은 바로 전 단계인 '등록인원(검수요청목록)'으로 되돌아갑니다.\n진행하시겠습니까?")) return;
	$('#mode').val('list_post_cancel');
	function doCancel(force){
		$.ajax({
			type:"post",
			data: $('#flist').serialize() + (force ? "&force=1" : ""),
			url:"<?=$PHP_SELF?>",
			success:function(response){
				var json = $.parseJSON(response);
				if(json["result"]=="minus_point"){
					if(confirm(json["msg"])){ doCancel(true); }
					return;
				}
				if(json["msg"]){ alert(json["msg"]); }
				if(json["result"]=="ok"){ location.reload(); }
			}
		});
	}
	doCancel(false);
});
//-->
</script>

<?php
include_once "tail.php";
?>