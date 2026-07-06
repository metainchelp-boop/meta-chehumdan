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

if($mode=="list_cancel"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		review_asign_cancel($_POST[$id][$k]);
	}
	json_return("신청목록으로 이동 처리었습니다","ok");
}

// 상품 구매 확인 토글 (요청:양근형 2026-06-30) — 선정~리뷰등록 사이 실제 구매여부 추적
if($mode=="buy_toggle"){
	demo_check_json();
	$rid = (int)$_POST['rv_id'];
	$bcur = sql_fetch("select rv_buy_chk from nfor_review where rv_id='$rid'");
	if($bcur[rv_buy_chk]=='2'){
		sql_query("update nfor_review set rv_buy_chk='0', rv_buy_chk_datetime=null, rv_buy_chk_admin='' where rv_id='$rid'");
		json_return("구매완료(확인)를 해제했습니다","off");
	} else{
		$ban = addslashes($member[mb_name] ? $member[mb_name] : $member[mb_id]);
		sql_query("update nfor_review set rv_buy_chk='2', rv_buy_chk_datetime=NOW(), rv_buy_chk_admin='$ban' where rv_id='$rid'");
		json_return("구매 확인 완료 처리했습니다","on");
	}
}

$sql_search = " where rv_step='2' and rv_delete='0' ";
// 미구매만 보기 필터
$mc_buyf = isset($_GET['buyf']) ? preg_replace('/[^a-z]/','',$_GET['buyf']) : '';
if($mc_buyf=='req') $sql_search .= " and rv_buy_chk='1' ";
else if($mc_buyf=='none') $sql_search .= " and rv_buy_chk='0' ";
else if($mc_buyf=='done') $sql_search .= " and rv_buy_chk='2' ";

include_once "inc_review_tail.php";
include_once "head.php";
include_once "inc_review_search.php";

// 진행자별 마감 연장 — 이력 테이블 존재 여부(미존재 시 칩만 생략, 페이지는 정상) 2026-06-23
$mc_ext = sql_fetch("select count(*) as c from information_schema.tables where table_schema=database() and table_name='nfor_review_extend'");
$mc_ext_on = ($mc_ext[c] > 0);

// 상품 구매 현황 집계 (캠페인/권한 범위 기준) 2026-06-30
$mc_bw = " where rv_step='2' and rv_delete='0' ";
if($member[mb_admin]=="1") $mc_bw .= " and rv_supply_no='".(int)$member[mb_no]."'";
else if($member[mb_admin]=="2") $mc_bw .= " and rv_md_no='".(int)$member[mb_no]."'";
$mc_cpf = preg_replace('/[^0-9]/','',(string)$rv_cp_id);
if($mc_cpf!=='') $mc_bw .= " and rv_cp_id='".$mc_cpf."'";
$mc_bc = sql_fetch("select count(*) as t, sum(rv_buy_chk='2') as d, sum(rv_buy_chk='1') as r from nfor_review ".$mc_bw);
$mc_btotal=(int)$mc_bc[t]; $mc_bdone=(int)$mc_bc[d]; $mc_breq=(int)$mc_bc[r]; $mc_bnone=$mc_btotal-$mc_bdone-$mc_breq;
$mc_bpct = $mc_btotal? round($mc_bdone/$mc_btotal*100) : 0;
$mc_q2 = preg_replace('/(^|&)(buyf|page)=[^&]*/','',(string)$qstr); $mc_q2 = ltrim($mc_q2,'&');
?>
<style>
/* 상품 구매 확인 — 2026-06-30 */
.mc-buy-stat{display:flex;align-items:center;gap:13px;flex-wrap:wrap;background:#fff;border:1px solid #eaedf1;border-radius:12px;padding:12px 16px;margin:6px 0 12px}
.mc-buy-stat .s{font-size:12.5px;color:#6b7684}
.mc-buy-stat .s b{font-size:17px;font-weight:800;color:#191f28;margin-left:2px}
.mc-buy-stat .s.done b{color:#02b350}
.mc-buy-stat .s.req b{color:#f59e0b}
.mc-buy-stat .s.todo b{color:#f04452}
.mc-buy-stat .bar{flex:1;min-width:140px;height:9px;border-radius:99px;background:#eef1f4;overflow:hidden}
.mc-buy-stat .bar > i{display:block;height:100%;background:#03c75a;border-radius:99px;transition:.3s}
.mc-buy-stat .filt{display:inline-flex;gap:6px;margin-left:auto}
.mc-buy-stat .filt a{font-size:12px;font-weight:700;color:#6b7684;text-decoration:none;border:1px solid #e1e5ea;border-radius:8px;padding:6px 11px}
.mc-buy-stat .filt a.on{background:#191f28;border-color:#191f28;color:#fff}
.mc-buy-cell{min-width:150px}
.mc-buy-badge{display:inline-flex;align-items:center;gap:4px;font-weight:800;font-size:12px;padding:5px 10px;border-radius:999px}
.mc-buy-badge.done{background:#e7f9ef;color:#02b350;border:1px solid #c7ecd4}
.mc-buy-badge.req{background:#fff7e6;color:#c8780a;border:1px solid #ffe2a8}
.mc-buy-badge.todo{background:#fff4f4;color:#d83a48;border:1px dashed #f3a6b1}
.mc-buy-meta{color:#8b95a1;font-size:11px;margin-top:4px}
.mc-buy-undo{color:#b0b8c1;font-size:11px;text-decoration:underline;cursor:pointer;margin-left:3px}
.mc-buy-undo:hover{color:#f04452}
.mc-buy-btn{margin-top:6px}
.mc-buy-proof{display:inline-block;margin-top:5px;font-size:10.5px;color:#2b6fe0;background:#eef3ff;border-radius:6px;padding:2px 7px}
.mc-buy-remind{display:inline-block;margin-top:5px;font-size:10.5px;font-weight:700;color:#c8780a;background:#fff3e0;border:1px solid #ffe2a8;border-radius:6px;padding:2px 7px}
</style>

<div class="mc-buy-stat">
	<span class="s">진행 전체 <b><?=number_format($mc_btotal)?></b>명</span>
	<span class="s done">구매확인 <b id="mc-buy-done"><?=number_format($mc_bdone)?></b>명</span>
	<span class="s req">회원신고 <b id="mc-buy-req"><?=number_format($mc_breq)?></b>명</span>
	<span class="s todo">미신고 <b id="mc-buy-none"><?=number_format($mc_bnone)?></b>명</span>
	<span class="bar"><i id="mc-buy-bar" style="width:<?=$mc_bpct?>%"></i></span>
	<span class="filt">
		<a href="?<?=$mc_q2?>" class="<?=($mc_buyf===''?'on':'')?>">전체</a>
		<a href="?<?=($mc_q2!==''?$mc_q2.'&':'')?>buyf=req" class="<?=($mc_buyf==='req'?'on':'')?>">🔔 회원신고</a>
		<a href="?<?=($mc_q2!==''?$mc_q2.'&':'')?>buyf=none" class="<?=($mc_buyf==='none'?'on':'')?>">미신고</a>
	</span>
</div>

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
	<th>상품 구매</th>
	<th>신청 정보</th>
	<th>리뷰제출지연</th>
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
	<td><a <?php if($member[mb_admin]>=7){ ?>href="javascript:member('<?=$row[rv_mb_no]?>')"<?php } ?>><?=campaign_member_info($row)?></a><span class="mc-mi-meta"><?=admin_a("review_singo", "신고", "btn btn-white btn-sm mc-report-link", "data-data=\"rv_id=$row[rv_id]\"", "#self")?></span></td>
	<td class="textleft mc-channel-cell">
		<?=channel_btn($row[rv_channel], $row[rv_media])?>
		<?php $rm = sql_fetch("select mb_blog from nfor_member where mb_no='{$row[rv_mb_no]}'"); if($rm[mb_blog]){ ?>
		<span class="mc-ch-meta"><span id="blog_totalcount2_<?=$rm[mb_blog]?>">브론즈</span> <span id="blog_totalcount_<?=$rm[mb_blog]?>" data-ref="<?=$rm[mb_blog]?>">0</span> 방문</span>
		<?php } ?>
	</td>
	<td class="textleft">
	<?=$row[rv_dy_name]?> <?=$row[rv_dy_hp]?><br>
	<?=$row[rv_dy_zip]?> <?=$row[rv_dy_addr1]?> <?=$row[rv_dy_addr2]?>
	</td>
	<td class="mc-buy-cell"><?php $bchk=$row[rv_buy_chk]; ?>
		<div class="mc-buy-wrap" data-rv="<?=(int)$row[rv_id]?>"><?php if($bchk=='2'){ ?>
			<span class="mc-buy-badge done">✅ 구매완료</span>
			<div class="mc-buy-meta"><?=substr($row[rv_buy_chk_datetime],5,5)?> · <?=$row[rv_buy_chk_admin]?> <a href="javascript:void(0)" class="mc-buy-undo" onclick="mcBuyToggle(<?=(int)$row[rv_id]?>)">취소</a></div>
		<?php } else if($bchk=='1'){ ?>
			<span class="mc-buy-badge req">🔔 회원 구매신고</span>
			<div class="mc-buy-meta"><?=substr($row[rv_buy_req_datetime],5,5)?> 신고</div>
			<div><button type="button" class="btn btn-white btn-sm mc-buy-btn" onclick="mcBuyToggle(<?=(int)$row[rv_id]?>)">✓ 확인</button></div>
		<?php } else{ ?>
			<span class="mc-buy-badge todo">● 미신고</span>
			<div><button type="button" class="btn btn-white btn-sm mc-buy-btn" onclick="mcBuyToggle(<?=(int)$row[rv_id]?>)">구매 확인</button></div>
			<?php if(!empty($row[rv_buy_remind_datetime]) && $row[rv_buy_remind_datetime]!='0000-00-00 00:00:00'){ ?><div class="mc-buy-remind">⏰ 독촉 발송 <?=substr($row[rv_buy_remind_datetime],5,5)?></div><?php } ?>
		<?php } ?></div>
		<?php if($row[rv_buy_img]){ ?><span class="mc-buy-proof">📷 구매캡쳐 있음</span><?php } ?>
	</td>
	<td class="mc-msg-cell"><?=admin_textarea($row,"rv_msg")?><?=admin_textarea($row,"rv_memo")?><button type="button" class="btn btn-white btn-sm mc-msg-btn">신청해요</button></td>
	<td><?=cp_contents_exp($row[rv_cp_contents_edatetime])?>
		<?php $rvx_c = 0; if($mc_ext_on){ $rvx = sql_fetch("select count(*) as c from nfor_review_extend where re_rv_id='".(int)$row[rv_id]."'"); $rvx_c = (int)$rvx[c]; } ?>
		<div class="mc-ext-wrap">
			<button type="button" class="btn btn-white btn-sm mc-ext-btn"
				data-rv="<?=(int)$row[rv_id]?>"
				data-edate="<?=htmlspecialchars(substr($row[rv_cp_contents_edatetime],0,16))?>"
				data-nick="<?=htmlspecialchars(strip_tags(campaign_member_info($row)))?>"
				data-camp="<?=htmlspecialchars($row[rv_cp_subject]." (".$row[rv_cp_id].")")?>"
				onclick="mcExtOpen(<?=(int)$row[rv_id]?>)">📅 마감 연장</button>
			<?php if($rvx_c > 0){ ?><span class="mc-ext-chip" onclick="mcExtOpen(<?=(int)$row[rv_id]?>)">연장 <?=$rvx_c?>회</span><?php } ?>
		</div>
	</td>
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
	<?php if($member[mb_admin] >= $config[cf_review_wait_move]){ ?>
	<?=admin_button("list_cancel", "신청목록이동", "btn btn-lg btn-red")?>
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

$(document).on("click", "#list_cancel", function(){
	nfor_list_reload('신청목록이동','list_cancel');
});

$(document).on("click", "#list_cancel2", function(){
	nfor_list_reload('리뷰 선정후 취소','list_cancel2');
});

/* 상품 구매 확인 토글 — 2026-06-30 (2단계: 회원신고 → 관리자확인) */
function mcBuyToggle(rid){
	$.post(location.href, {mode:'buy_toggle', rv_id:rid}, function(d){
		if(d && (d.result==='on' || d.result==='off')){ location.reload(); }
		else { alert((d&&(d.msg||d.message))?(d.msg||d.message):'처리 실패'); }
	}, 'json');
}
//-->
</script>

<?php
include_once "inc_review_extend.php";   // 진행자별 마감 연장 모달 2026-06-23
include_once "tail.php";
?>