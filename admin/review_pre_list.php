<?php
include_once "path.php";
include_once "inc_review_head.php";

// 1차 후보 목록 (rv_step=8) — 회원 미알림. 여기서 2차 확정(→선정·알림) 또는 제외(→신청목록).

if($mode=="list_exclude"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		review_pre_exclude($_POST[$id][$k]);
	}
	json_return("선택한 후보를 제외했습니다 (신청목록으로 이동 · 회원 알림 없음)","ok");
}

if($mode=="list_delete"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		review_delete($_POST[$id][$k]);
	}
	json_return("신청서가 삭제되었습니다","ok");
}

// 2차 확정 (→ rv_step 2 선정 + 회원 알림). 과선정 경고 포함(모집인원 초과 시 확인).
if($mode=="list_asign"){
	demo_check_json();

	if($_POST['force'] != "1"){
		$__add = array();
		for($i=0; $i<count($chk); $i++){
			$k = $_POST['chk'][$i];
			$__r = sql_fetch("select rv_cp_id from nfor_review where rv_id='{$_POST[$id][$k]}'");
			if($__r['rv_cp_id']) $__add[$__r['rv_cp_id']] = $__add[$__r['rv_cp_id']] + 1;
		}
		$__warn = "";
		foreach($__add as $__cpid => $__n){
			$__c = sql_fetch("select cp_subject, cp_recruit, cp_review_asign from nfor_campaign where cp_id='".addslashes($__cpid)."'");
			if($__c['cp_recruit'] > 0 && ($__c['cp_review_asign'] + $__n) > $__c['cp_recruit']){
				$__warn .= "· ".$__c['cp_subject']." : 모집 ".$__c['cp_recruit']."명 / 확정 예정 ".($__c['cp_review_asign'] + $__n)."명\n";
			}
		}
		if($__warn){
			json_return("⚠ 아래 캠페인이 모집인원을 초과합니다.\n\n".$__warn."\n초과 확정해도 괜찮으면 [확인]을 누르세요.","over_recruit");
		}
	}

	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		review_asign($_POST[$id][$k]);
	}
	json_return("2차 확정 완료 — 회원에게 선정 알림이 발송되었습니다.\n해당 건은 '선정목록'으로 이동되었습니다.","ok");
}

if($mode=="asign"){
	demo_check_json();
	review_asign($$id);
	json_return("2차 확정 완료 — 회원에게 선정 알림이 발송되었습니다.","ok");
}

// 명단 엑셀(CSV) 추출 — 현재 1차 후보 (캠페인 필터 반영)
if($mode=="csv"){
	$cpf = preg_replace('/[^0-9]/', '', $rv_cp_id);
	$w = "rv_step='8' and rv_delete='0'".($cpf ? " and rv_cp_id='".$cpf."'" : "");
	$q = sql_query("select rv_id, rv_cp_id, rv_cp_subject, rv_mb_nick, rv_mb_id, rv_media, rv_channel, rv_datetime from nfor_review where ".$w." order by rv_id desc");
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename="1cha_candidates.csv"');
	echo "\xEF\xBB\xBF"; // 엑셀 한글 깨짐 방지(BOM)
	echo "신청번호,캠페인,닉네임,아이디,채널,블로그URL,신청일\n";
	while($r = sql_fetch_array($q)){
		$cols = array($r['rv_id'], $r['rv_cp_subject'], ($r['rv_mb_nick'] ? $r['rv_mb_nick'] : $r['rv_mb_id']), $r['rv_mb_id'], $r['rv_media'], $r['rv_channel'], substr($r['rv_datetime'],0,10));
		$cols = array_map(function($v){ return '"'.str_replace('"','""',$v).'"'; }, $cols);
		echo implode(",", $cols)."\n";
	}
	exit;
}

// 광고주 제외요청(파일 저장) 로드 헬퍼 — data/adv_exclude/<cp_id>.json
$GLOBALS['__adv_ex_cache'] = array();
function adv_excluded($cp_id){
	global $nfor;
	if(isset($GLOBALS['__adv_ex_cache'][$cp_id])) return $GLOBALS['__adv_ex_cache'][$cp_id];
	$arr = array();
	$f = $nfor['path']."/data/adv_exclude/".$cp_id.".json";
	if(is_file($f)){ $j = json_decode(@file_get_contents($f), true); if(isset($j['excluded'])) $arr = array_map('intval', $j['excluded']); }
	return $GLOBALS['__adv_ex_cache'][$cp_id] = $arr;
}

$sql_search = " where rv_step='8' and rv_delete='0' ";

include_once "inc_review_tail.php";
include_once "head.php";
include_once "inc_review_search.php";
?>

<div style="background:#fff7ed; border:1px solid #fed7aa; color:#9a3412; border-radius:9px; padding:11px 14px; margin:6px 0 12px; font-size:13.5px; line-height:1.6;">
🔒 <b>1차 후보 단계</b> — 이 회원들은 아직 <u>선정 알림을 받지 않았습니다.</u> 데이터 비교·광고주 검토 후 <b>[2차 확정]</b>하면 그때 회원에게 알림이 가고 리뷰 권한이 열립니다. <b>[제외]</b>해도 회원은 알 수 없습니다.
</div>

<?php
$__cpf = preg_replace('/[^0-9]/', '', $rv_cp_id);
$__advurl = "";
if($__cpf){ $__advurl = $nfor['url']."/review_adv_check.php?cp=".$__cpf."&t=".substr(md5($__cpf."metacrew_pre_salt_2026"),0,12); }
?>
<div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:9px; padding:11px 14px; margin:0 0 12px; font-size:13px;">
	📨 <b>광고주 검토 보내기</b> &nbsp;
	<?php if($__cpf){ ?>
		<input type="text" readonly id="advurl" value="<?=htmlspecialchars($__advurl)?>" style="width:46%; padding:6px 9px; border:1px solid #cbd5e1; border-radius:7px; font-size:12px;" onclick="this.select()">
		<button type="button" class="btn btn-white btn-sm" onclick="navigator.clipboard.writeText(document.getElementById('advurl').value); alert('광고주 공유 링크가 복사되었습니다.\n카톡/메일로 광고주에게 보내세요.\n(광고주가 제외 체크 후 제출하면 이 목록에 🚫로 표시됩니다)')">🔗 링크 복사</button>
		<a href="<?=htmlspecialchars($__advurl)?>" target="_blank" class="btn btn-white btn-sm">미리보기</a>
		<a href="?rv_cp_id=<?=$__cpf?>&mode=csv" class="btn btn-white btn-sm">📊 명단 엑셀(CSV)</a>
	<?php } else { ?>
		<span style="color:#64748b;">상단 검색에서 <b>캠페인을 선택</b>하면 광고주 공유 링크가 생성됩니다.</span>
		<a href="?mode=csv" class="btn btn-white btn-sm" style="margin-left:8px;">📊 전체 명단 엑셀(CSV)</a>
	<?php } ?>
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
<tr>
	<td><?=admin_checkbox($row,"chk[]","chk")?><?=admin_hidden($row,"{$id}[{$i}]")?></td>
	<td><?=$row[rv_id]?></td>
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
		<?php if(in_array((int)$row[rv_id], adv_excluded($row[rv_cp_id]))){ ?>
		<br><span style="background:#fee2e2;color:#b91c1c;font-size:11px;padding:2px 8px;border-radius:999px;font-weight:700;display:inline-block;margin-top:4px;">🚫 광고주 제외요청</span>
		<?php } ?>
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
	<td><?=admin_a("asign", "2차 확정", "btn btn-white btn-sm nfor_button", "data-confirm=\"2차 확정하시겠습니까?\n회원에게 선정 알림이 발송됩니다.\" data-data=\"mode=asign&{$id}={$row[$id]}\"")?></td>
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
	<?=admin_button("list_asign", "✓ 2차 확정 (회원 알림 발송)", "btn btn-lg btn-red")?>
	<?php } ?>

	<?php if($member[mb_admin] >= $config[cf_review_cancel]){ ?>
	<?=admin_button("list_exclude", "✕ 제외 (신청목록으로)", "btn btn-lg btn-black")?>
	<?php } ?>

	<?php if($member[mb_admin] >= $config[cf_review_delete]){ ?>
	<?=admin_button("list_delete", "신청서 삭제(DB삭제)", "btn btn-lg btn-black")?>
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
	// 2차 확정 — 과선정 경고 지원: 서버가 over_recruit 응답 시 confirm 후 force=1로 재전송하여 진행
	var chk = document.getElementsByName("chk[]");
	var any = false;
	for(var i=0;i<chk.length;i++){ if(chk[i].checked) any = true; }
	if(!any){ alert("2차 확정할 후보를 하나 이상 선택하세요"); return; }
	if(!confirm("선택한 후보를 2차 확정합니다.\n회원에게 선정 알림이 발송됩니다. 진행하시겠습니까?")) return;
	$('#mode').val('list_asign');
	function doAsign(force){
		$.ajax({
			type:"post",
			data: $('#flist').serialize() + (force ? "&force=1" : ""),
			url:"<?=$PHP_SELF?>",
			success:function(response){
				var json = $.parseJSON(response);
				if(json["result"]=="over_recruit"){
					if(confirm(json["msg"])){ doAsign(true); }
					return;
				}
				if(json["msg"]){ alert(json["msg"]); }
				if(json["result"]=="ok"){ location.reload(); }
			}
		});
	}
	doAsign(false);
});

$(document).on("click", "#list_exclude", function(){
	nfor_list_reload('제외(신청목록으로 이동)','list_exclude');
});

$(document).on("click", "#list_delete", function(){
	nfor_list_reload('신청서 삭제','list_delete');
});
//-->
</script>
<?php
// 블로그 등급/방문수 표시는 공통 JS(mc_blog_count.js)로 이동함
include_once "tail.php";
?>
