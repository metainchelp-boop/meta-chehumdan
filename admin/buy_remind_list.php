<?php
/* 독촉 관리 — 선정 후 3영업일 미신고자 모아보기 + 수동(개별/일괄) 구매독촉 알림톡 발송.
   리뷰통합관리 그룹(nfor_access gp_id=36). 요청:양근형 2026-07-03. 템플릿 UJ_1580(자동발송 buy_remind.php와 동일 문안). */
include_once "path.php";

$MC_TPL = 'UJ_1580';   // ★카카오 승인 알림톡 템플릿코드 (buy_remind.php와 동일)

/* ── 수동 발송 처리 (AJAX) ── */
if($mode=='send_one' || $mode=='send_bulk'){
	demo_check_json();
	$ids = array();
	if($mode=='send_one'){ $ids[] = (int)$_POST['rv_id']; }
	else { $chk = isset($_POST['chk']) ? $_POST['chk'] : array(); foreach((array)$chk as $v) $ids[] = (int)$v; }
	$by = addslashes($member[mb_name] ? $member[mb_name] : $member[mb_id]);
	$sent = 0;
	foreach($ids as $rid){
		if($rid <= 0) continue;
		$rv = sql_fetch("select a.rv_id, a.rv_step, a.rv_buy_chk, a.rv_cp_subject, a.rv_buy_remind_datetime, m.mb_hp, m.mb_nick, m.mb_name from nfor_review a left join nfor_member m on a.rv_mb_no=m.mb_no where a.rv_id='$rid'");
		if(!$rv[rv_id] || $rv[rv_step]!='2' || $rv[rv_buy_chk]!='0') continue;                                   // 대상 아님(선정+미신고만)
		if($rv[rv_buy_remind_datetime] && $rv[rv_buy_remind_datetime]!='0000-00-00 00:00:00') continue;          // 이미 발송(중복 방지)
		$hp = preg_replace('/[^0-9]/','', (string)$rv[mb_hp]); if(!$hp) continue;                                 // 번호 없음
		$nick = $rv[mb_nick] ? $rv[mb_nick] : $rv[mb_name]; $cp = $rv[rv_cp_subject];
		$msg = "[메타체험단] 상품 구매 안내\n".$nick."님, '".$cp."' 체험단에 선정되셨습니다.\n선정 후 3일이 경과되었으나 아직 상품 구매가 확인되지 않았습니다.\n기한 내 구매 후, 마이페이지 > 선정된 캠페인에서 [구매 완료] 버튼을 눌러주세요!\n미진행 시 선정이 취소될 수 있습니다.\n문의: 02-2082-2005";
		sql_query("insert nfor_sms_log set sl_msg='".addslashes($msg)."', sl_hp='".addslashes($hp)."', sl_send_hp='".addslashes($config[cf_tel])."', sl_datetime=NOW(), sl_send='0', sl_templt_code='".addslashes($MC_TPL)."', sl_subject='구매 안내'");
		sql_query("update nfor_review set rv_buy_remind_datetime=NOW(), rv_buy_remind_by='".$by."' where rv_id='$rid'");
		$sent++;
	}
	json_return($sent."명에게 구매 독촉 알림톡을 발송했습니다","ok");
}

/* ── 대상 조회 ── */
$f = isset($_GET['f']) ? preg_replace('/[^a-z]/','',$_GET['f']) : 'todo';   // todo(미발송)/done(발송됨)/all
$scope = '';
if($member[mb_admin]=='1') $scope = " and a.rv_supply_no='".addslashes($member[mb_no])."'";
else if($member[mb_admin]=='2') $scope = " and a.rv_md_no='".addslashes($member[mb_no])."'";

function mc_bizd($from){ $ff=strtotime(substr((string)$from,0,10)." 00:00:00"); if(!$ff)return 0; $t=strtotime(date("Y-m-d")." 00:00:00"); $n=0; for($x=$ff+86400;$x<=$t;$x+=86400){$w=(int)date("w",$x); if($w!==0&&$w!==6)$n++;} return $n; }

$q = sql_query("select a.rv_id, a.rv_asign_datetime, a.rv_cp_subject, a.rv_cp_id, a.rv_buy_remind_datetime, a.rv_buy_remind_by, m.mb_hp, m.mb_nick, m.mb_name
	from nfor_review a left join nfor_member m on a.rv_mb_no=m.mb_no left join nfor_campaign b on a.rv_cp_id=b.cp_id
	where a.rv_step='2' and a.rv_delete='0' and a.rv_buy_chk='0' $scope
	  and a.rv_asign_datetime is not null and a.rv_asign_datetime<>'0000-00-00 00:00:00'
	  and (case when a.rv_cp_contents_edatetime is not null and a.rv_cp_contents_edatetime<>'0000-00-00 00:00:00' then a.rv_cp_contents_edatetime else b.cp_contents_edatetime end) >= CURDATE()
	order by a.rv_asign_datetime asc limit 2000");
$all = array(); $cnt_todo = 0; $cnt_done = 0;
while($d = sql_fetch_array($q)){
	$od = mc_bizd($d[rv_asign_datetime]);
	if($od < 3) continue;                                        // 3영업일 경과만
	$sent = ($d[rv_buy_remind_datetime] && $d[rv_buy_remind_datetime]!='0000-00-00 00:00:00');
	if($sent) $cnt_done++; else $cnt_todo++;
	$all[] = array('rv'=>(int)$d[rv_id],'nick'=>$d[mb_nick],'name'=>$d[mb_name],'hp'=>$d[mb_hp],'cp'=>$d[rv_cp_subject],'cp_id'=>$d[rv_cp_id],
		'picked'=>substr($d[rv_asign_datetime],0,10),'over'=>$od,'sent'=>$sent,'sent_at'=>substr($d[rv_buy_remind_datetime],5,5),'by'=>$d[rv_buy_remind_by],
		'hasHp'=>(preg_replace('/[^0-9]/','',(string)$d[mb_hp])!==''));
}
include_once "head.php";
?>
<style>
.mcbr-wrap{max-width:1120px}
.mcbr-note{background:#fff7e6;border:1px solid #ffe2a8;color:#8a6d00;border-radius:11px;padding:11px 15px;font-size:12.5px;line-height:1.6;margin-bottom:14px}
.mcbr-stat{display:flex;align-items:center;gap:14px;flex-wrap:wrap;background:#fff;border:1px solid #eaedf1;border-radius:13px;padding:13px 17px;margin-bottom:13px}
.mcbr-stat .s{font-size:13px;color:#6b7684}.mcbr-stat .s b{font-size:18px;font-weight:800;margin-left:3px}
.mcbr-stat .s.todo b{color:#ff8a00}.mcbr-stat .s.done b{color:#02b350}
.mcbr-filt{display:inline-flex;gap:6px;margin-left:4px}
.mcbr-filt a{font-size:12.5px;font-weight:700;color:#6b7684;text-decoration:none;border:1px solid #e1e5ea;border-radius:8px;padding:6px 11px;background:#fff}
.mcbr-filt a.on{background:#191f28;border-color:#191f28;color:#fff}
.mcbr-sp{flex:1}
.mcbr-bulk{background:#03c75a;border:0;color:#fff;font-weight:800;font-size:13px;padding:10px 16px;border-radius:9px;cursor:pointer}
.mcbr-bulk:disabled{background:#c8d0d8;cursor:not-allowed}
.mcbr-card{background:#fff;border:1px solid #eaedf1;border-radius:14px;overflow:hidden}
.mcbr-tb{width:100%;border-collapse:collapse;font-size:13px}
.mcbr-tb th{background:#fafbfc;color:#4e5968;font-weight:700;text-align:left;padding:11px 12px;border-bottom:1px solid #eaedf1;white-space:nowrap}
.mcbr-tb td{padding:11px 12px;border-bottom:1px solid #eef1f4;vertical-align:middle}
.mcbr-tb tr:last-child td{border-bottom:none}
.mcbr-tb tr:hover{background:#fafdff}
.mcbr-nick{font-weight:700}.mcbr-rn{color:#d6336c;font-weight:700;font-size:.92em;margin-left:4px}.mcbr-mut{color:#8b95a1;font-size:12px}
.mcbr-over{display:inline-block;font-weight:800;font-size:12px;padding:3px 9px;border-radius:999px;background:#fdecef;color:#d83a48}
.mcbr-over.soon{background:#fff3e0;color:#c8780a}
.mcbr-todo{display:inline-flex;align-items:center;gap:4px;background:#fff4f4;color:#d83a48;border:1px dashed #f3a6b1;font-weight:800;font-size:11.5px;padding:4px 9px;border-radius:999px}
.mcbr-sent{display:inline-flex;align-items:center;gap:4px;font-weight:800;font-size:11.5px;padding:4px 9px;border-radius:999px}
.mcbr-sent.auto{background:#eef3ff;color:#2b6fe0;border:1px solid #cfe0ff}
.mcbr-sent.man{background:#fff3e0;color:#c8780a;border:1px solid #ffe2a8}
.mcbr-send{background:#fff;border:1px solid #03c75a;color:#02b350;font-weight:700;font-size:12.5px;padding:7px 12px;border-radius:8px;cursor:pointer}
.mcbr-send:hover{background:#f1fcf5}
.mcbr-nohp{color:#b0b8c1;font-size:11.5px}
.mcbr-empty{padding:28px;text-align:center;color:#b0b8c1;font-size:13px}
</style>

<div class="mcbr-wrap">
	<div class="mcbr-note">📢 <b>구매 독촉</b> — <b>진행중 캠페인</b>(리뷰 등록 마감 미도래) 중 <b>선정 후 3영업일 경과 + 구매 미신고</b> 회원입니다. 이미 종료된 캠페인은 자동 제외됩니다. 매일 <b>자동 발송</b>(오늘 이후 선정자)되며, 아래에서 <b>직접 발송</b>도 가능합니다. 회원이 [구매 완료]를 누르거나 관리자가 [구매 확인]하면 목록에서 자동으로 빠집니다.</div>

	<form name="fbr" id="fbr" method="post">
	<div class="mcbr-stat">
		<span class="s todo">미발송 대상 <b><?=number_format($cnt_todo)?></b>명</span>
		<span class="s done">발송 완료 <b><?=number_format($cnt_done)?></b>명</span>
		<span class="mcbr-filt">
			<a href="?f=todo" class="<?=($f=='todo'?'on':'')?>">미발송 대상</a>
			<a href="?f=done" class="<?=($f=='done'?'on':'')?>">발송됨</a>
			<a href="?f=all"  class="<?=($f=='all'?'on':'')?>">전체</a>
		</span>
		<span class="mcbr-sp"></span>
		<button type="button" class="mcbr-bulk" id="mcbrBulk" onclick="mcbrBulk()" disabled>✅ 선택 <span id="mcbrSelN">0</span>명 일괄 독촉 보내기</button>
	</div>

	<div class="mcbr-card">
	<table class="mcbr-tb">
		<thead><tr>
			<th style="width:32px"><input type="checkbox" id="mcbrAll" onclick="mcbrToggleAll(this)"></th>
			<th>회원</th><th>연락처</th><th>캠페인</th><th>선정일</th><th>경과</th><th>구매</th><th>독촉 상태</th><th style="width:140px">발송</th>
		</tr></thead>
		<tbody>
		<?php if(!count($all)){ ?>
			<tr><td colspan="9" class="mcbr-empty">해당 대상이 없습니다 🎉</td></tr>
		<?php } foreach($all as $r){
			if($f=='todo' && $r['sent']) continue;
			if($f=='done' && !$r['sent']) continue;
		?>
			<tr>
				<td><?php if(!$r['sent'] && $r['hasHp']){ ?><input type="checkbox" class="mcbr-chk" value="<?=$r['rv']?>" onclick="mcbrUpd()"><?php } ?></td>
				<td><span class="mcbr-nick"><?=$r['nick']?></span><?php if($r['name'] && $r['name']!=$r['nick']){ ?><span class="mcbr-rn"><?=$r['name']?></span><?php } ?></td>
				<td class="mcbr-mut"><?=$r['hp']?$r['hp']:'<span class="mcbr-nohp">번호없음</span>'?></td>
				<td><a href="<?=$nfor[path]?>/campaign.php?cp_id=<?=$r['cp_id']?>" target="_blank"><?=$r['cp']?></a></td>
				<td class="mcbr-mut"><?=$r['picked']?></td>
				<td><span class="mcbr-over<?=($r['over']>=5?'':' soon')?>"><?=($r['over']>=5?'+':'')?><?=$r['over']?>영업일</span></td>
				<td><span class="mcbr-todo">● 미신고</span></td>
				<td><?php if($r['sent']){ if($r['by']==='auto' || $r['by']===''){ ?><span class="mcbr-sent auto">🤖 자동 <?=$r['sent_at']?></span><?php } else { ?><span class="mcbr-sent man">✋ <?=$r['by']?> <?=$r['sent_at']?></span><?php } } else { ?><span class="mcbr-mut">미발송</span><?php } ?></td>
				<td><?php if($r['sent']){ ?><span class="mcbr-mut">완료</span><?php } else if(!$r['hasHp']){ ?><span class="mcbr-nohp">번호없음</span><?php } else { ?><button type="button" class="mcbr-send" onclick="mcbrSend(<?=$r['rv']?>)">지금 독촉 보내기</button><?php } ?></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	</div>
	</form>
</div>

<script>
function mcbrToggleAll(c){ var b=document.querySelectorAll('.mcbr-chk'); for(var i=0;i<b.length;i++) b[i].checked=c.checked; mcbrUpd(); }
function mcbrUpd(){ var n=document.querySelectorAll('.mcbr-chk:checked').length; document.getElementById('mcbrSelN').textContent=n; document.getElementById('mcbrBulk').disabled=(n===0); }
function mcbrPost(data, okmsg){
	$.post(location.pathname+location.search, data, function(d){
		if(d && d.result==='ok'){ alert((d.msg||okmsg)); location.reload(); }
		else { alert((d&&d.msg)?d.msg:'발송 실패'); }
	}, 'json');
}
function mcbrSend(rv){ if(!confirm('이 회원에게 구매 독촉 알림톡을 지금 보낼까요?')) return; mcbrPost({mode:'send_one', rv_id:rv}, '발송했습니다'); }
function mcbrBulk(){
	var ids=[]; var b=document.querySelectorAll('.mcbr-chk:checked'); for(var i=0;i<b.length;i++) ids.push(b[i].value);
	if(!ids.length) return;
	if(!confirm(ids.length+'명에게 구매 독촉 알림톡을 일괄 발송할까요?')) return;
	var data={mode:'send_bulk'}; for(var j=0;j<ids.length;j++) data['chk['+j+']']=ids[j];
	mcbrPost(data, ids.length+'명에게 발송했습니다');
}
</script>

<?php include_once "tail.php"; ?>
