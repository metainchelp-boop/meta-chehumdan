<?php
/* ============================================================================
   advertiser_list.php — 광고주 목록 (회사 직접 관리, 업체명만 등록) 2026-06-17
   광고주 = nfor_member(mb_admin='1') 중 mb_id 접두사 'adv_' 로 구분.
   캠페인 연결은 기존 cp_supply_no 그대로 사용 → 회차 이력 모아보기는 캠페인목록 입점업체 필터로.
   ★입점업체목록(supply_list)에서는 adv_ 를 제외해 두 목록을 분리.
   ============================================================================ */
include_once "path.php";

$form = $_SERVER['PHP_SELF'];

// ── 광고주 등록 (업체명만) ──
if($mode=="ad_insert"){
	$nm = trim($ad_name);
	if($nm===''){ json_return("업체명을 입력해 주세요.","fail"); }
	$new_id = 'adv_'.date("YmdHis").substr((string)microtime(),2,4);
	// nfor_member 는 NOT NULL·기본값 없는 컬럼이 매우 많음(50개+).
	// 누락 시 INSERT 실패하므로, 필수 컬럼을 모두 안전 기본값(문자열='' / 숫자=0)으로 먼저 채운다.
	$cols = array();
	$cq = sql_query("SHOW COLUMNS FROM nfor_member");
	while($c = sql_fetch_array($cq)){
		if(strpos($c['Extra'],'auto_increment')!==false) continue;  // PK(mb_no) 제외
		if($c['Null']=='NO' && $c['Default']===null){
			$cols[$c['Field']] = preg_match('/int|decimal|float|double|bit/i',$c['Type']) ? "0" : "";
		}
	}
	// 광고주 값으로 덮어쓰기
	$cols['mb_id']        = $new_id;
	$cols['mb_password']  = md5(uniqid('', true));   // 로그인 안 하는 관리용 계정
	$cols['mb_name']      = $nm;
	$cols['mb_nick']      = $nm;
	$cols['mb_cp_name']   = $nm;
	$cols['mb_admin']     = '1';
	$cols['mb_asign']     = '1';
	$cols['mb_level']     = '1';
	$cols['mb_timestamp'] = date("YmdHis");
	$cols['mb_datetime']  = date("Y-m-d H:i:s");
	$set = array();
	foreach($cols as $k=>$v){ $set[] = "`".$k."`='".addslashes($v)."'"; }
	sql_query("insert into nfor_member set ".implode(", ", $set));
	json_return("광고주 '".$nm."' 가 등록되었습니다.","ok");
}

// ── 광고주 삭제 (adv_ 계정만, 연결 캠페인 없을 때만) ──
if($mode=="ad_delete"){
	$del_no = (int)$mb_no;
	$m = sql_fetch("select mb_id from nfor_member where mb_no='$del_no'");
	if(strpos($m['mb_id'],'adv_')!==0){ json_return("광고주 계정이 아닙니다.","ok"); }
	$cc = sql_fetch("select count(*) as c from nfor_campaign where cp_supply_no='$del_no'");
	if((int)$cc['c'] > 0){ json_return("연결된 캠페인이 ".(int)$cc['c']."건 있어 삭제할 수 없습니다.\n캠페인의 입점업체를 먼저 변경하세요.","ok"); }
	sql_query("delete from nfor_member where mb_no='$del_no'");
	json_return("삭제되었습니다.","ok");
}

include_once "head.php";

// 목록
$total = sql_fetch("select count(*) as c from nfor_member where mb_admin='1' and mb_id like 'adv\\_%'");
$total_count = (int)$total['c'];
$result = sql_query("select * from nfor_member where mb_admin='1' and mb_id like 'adv\\_%' order by mb_no desc");
?>
<div style="font-family:'Pretendard','Apple SD Gothic Neo',sans-serif">
<h4 class="title" style="margin-bottom:4px">📣 광고주 목록</h4>
<div style="color:#8b95a1;font-size:13px;margin-bottom:16px">회사가 직접 관리하는 광고주 계정입니다. <b>업체명만</b> 등록하면 되고, 캠페인 등록 시 「입점업체」 칸에서 이 광고주를 선택하면 그 광고주의 <b>회차별 캠페인 이력</b>이 모입니다.</div>

<!-- 등록 (nfor 폼 자동 AJAX 충돌 방지를 위해 form이 아닌 명시적 AJAX) -->
<div class="cols_tbl" style="background:#f1faf4;border:1px solid #c3eed5;border-radius:14px;padding:16px 18px;display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:18px">
	<b style="color:#0d9488">＋ 새 광고주 등록</b>
	<input type="text" id="ad_name" placeholder="업체명 입력 (예: 더마뷰랩)" style="flex:1;min-width:220px;border:1px solid #d8dee7;border-radius:9px;padding:9px 12px" maxlength="60">
	<button type="button" id="ad-add-btn" class="btn btn-lg" style="background:#0d9488;border-color:#0d9488;color:#fff;border-radius:9px;font-weight:700">등록하기</button>
</div>

<div class="ofw" style="margin-bottom:8px">전체 <span class="txt_red"><?=number_format($total_count)?></span>개 광고주</div>

<table class="table row_tbl margin0">
<tr>
	<th style="width:80px">번호</th>
	<th>업체명</th>
	<th style="width:140px">연결 캠페인</th>
	<th style="width:120px">등록일</th>
	<th style="width:90px">관리</th>
</tr>
<?php
$n = $total_count;
while($row = sql_fetch_array($result)){
	$cc = sql_fetch("select count(*) as c from nfor_campaign where cp_supply_no='".$row['mb_no']."'");
	$camp_cnt = (int)$cc['c'];
?>
<tr>
	<td style="text-align:center"><?=$n?></td>
	<td><b><?=htmlspecialchars($row['mb_cp_name'])?></b></td>
	<td style="text-align:center">
		<?php if($camp_cnt>0){ ?><a href="campaign_list.php?cp_supply_no=<?=$row['mb_no']?>" style="color:#02b150;font-weight:700"><?=number_format($camp_cnt)?>건 보기 →</a><?php }else{ ?><span style="color:#adb5bd">0건</span><?php } ?>
	</td>
	<td style="text-align:center"><?=substr($row['mb_datetime'],0,10)?></td>
	<td style="text-align:center"><input type="button" value="삭제" class="btn btn-white btn-sm ad-del" data-no="<?=$row['mb_no']?>" data-nm="<?=htmlspecialchars($row['mb_cp_name'])?>"></td>
</tr>
<?php
	$n--;
}
if(!$total_count){ ?>
<tr><td colspan="5" style="text-align:center;color:#adb5bd;padding:30px">등록된 광고주가 없습니다. 위에서 업체명을 입력해 등록해 주세요.</td></tr>
<?php } ?>
</table>
</div>

<script>
$(document).on("click","#ad-add-btn",function(){
	var nm = $.trim($("#ad_name").val());
	if(!nm){ alert("업체명을 입력해 주세요."); $("#ad_name").focus(); return; }
	var $b=$(this); $b.prop("disabled",true).text("등록 중…");
	$.ajax({ type:"post", url:"advertiser_list.php", dataType:"json", data:{mode:"ad_insert", ad_name:nm},
		success:function(r){ alert(r.msg); if(/등록되었습니다/.test(r.msg)){ location.reload(); } else { $b.prop("disabled",false).text("등록하기"); } },
		error:function(){ alert("등록 중 통신 오류가 발생했습니다."); $b.prop("disabled",false).text("등록하기"); }
	});
});
$("#ad_name").on("keypress",function(e){ if(e.which===13){ e.preventDefault(); $("#ad-add-btn").click(); } });

$(document).on("click",".ad-del",function(){
	var no=$(this).data("no"), nm=$(this).data("nm");
	if(!confirm("광고주 '"+nm+"' 를 삭제할까요?")) return;
	$.ajax({ type:"post", url:"advertiser_list.php", dataType:"json", data:{mode:"ad_delete", mb_no:no},
		success:function(r){ alert(r.msg); if(/삭제/.test(r.msg)) location.reload(); }
	});
});
</script>

<?php
include_once "tail.php";
?>
