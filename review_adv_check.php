<?php
include_once "path.php";

// ── 광고주 1차 후보 검토 페이지 (로그인 불필요·토큰 보호) ──
// 광고주가 받은 링크로 1차 후보 명단을 보고, 제외할 블로거를 체크해 제출. 결과는 파일(JSON)로 저장.

$cp_id = preg_replace('/[^0-9]/', '', $_GET['cp']);
$token = preg_replace('/[^a-f0-9]/', '', $_GET['t']);
$valid = ($cp_id !== '' && $token === substr(md5($cp_id."metacrew_pre_salt_2026"), 0, 12));

header('Content-Type: text/html; charset=utf-8');
if(!$valid){ echo "<div style='font-family:sans-serif;padding:40px;text-align:center;color:#888;'>유효하지 않거나 만료된 링크입니다.</div>"; exit; }

$campaign = sql_fetch("select cp_subject, cp_recruit from nfor_campaign where cp_id='".addslashes($cp_id)."'");
$dir  = $nfor['path']."/data/adv_exclude";
if(!is_dir($dir)) @mkdir($dir, 0707, true);
$file = $dir."/".$cp_id.".json";

// 제출 처리
if($_POST['mode']=="submit"){
	$ex = array();
	if(isset($_POST['ex']) && is_array($_POST['ex'])){ foreach($_POST['ex'] as $v){ $ex[] = (int)$v; } }
	@file_put_contents($file, json_encode(array('excluded'=>$ex, 'datetime'=>date('Y-m-d H:i:s'), 'count'=>count($ex))));
	?>
	<!doctype html><html lang="ko"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>검토 완료</title></head>
	<body style="font-family:'Apple SD Gothic Neo','Malgun Gothic',sans-serif;background:#f1f5f9;margin:0;">
	<div style="max-width:520px;margin:60px auto;background:#fff;border-radius:14px;padding:34px 26px;text-align:center;box-shadow:0 10px 30px rgba(0,0,0,.08);">
		<div style="font-size:46px;">✅</div>
		<h2 style="margin:12px 0 6px;">검토 의견이 전달되었습니다</h2>
		<p style="color:#475569;font-size:14px;line-height:1.7;"><?=count($ex)?>명을 제외 요청하셨습니다.<br>메타크루 담당자가 확인 후 최종 선정합니다. 감사합니다.</p>
	</div></body></html>
	<?php
	exit;
}

// 기존 제출값(있으면 체크 유지)
$prevEx = array();
if(is_file($file)){ $j = json_decode(@file_get_contents($file), true); if(isset($j['excluded'])) $prevEx = $j['excluded']; }

$rs = sql_query("select rv_id, rv_mb_nick, rv_mb_id, rv_media, rv_channel, rv_msg from nfor_review where rv_cp_id='".addslashes($cp_id)."' and rv_step='8' and rv_delete='0' order by rv_id desc");
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>1차 후보 검토 — <?=htmlspecialchars($campaign['cp_subject'])?></title>
<style>
  body{ font-family:"Apple SD Gothic Neo","Malgun Gothic",sans-serif; background:#f1f5f9; margin:0; color:#1f2937; }
  .wrap{ max-width:640px; margin:0 auto; padding:0 0 60px; }
  .head{ background:#0f172a; color:#fff; padding:18px 20px; }
  .head h1{ font-size:17px; margin:0; }
  .head .sub{ font-size:12px; opacity:.8; margin-top:4px; }
  .body{ padding:16px; }
  .note{ background:#eff6ff; border:1px solid #bfdbfe; color:#1e40af; font-size:13.5px; padding:12px 14px; border-radius:10px; line-height:1.7; margin-bottom:14px; }
  .cand{ border:1px solid #e3e8ef; border-radius:11px; padding:12px 14px; margin-bottom:9px; display:flex; gap:12px; align-items:flex-start; background:#fff; }
  .cand.ex{ background:#fff5f5; border-color:#fecaca; }
  .cand input{ width:20px; height:20px; margin-top:2px; flex:0 0 auto; }
  .cand .info{ flex:1; min-width:0; }
  .cand .nm{ font-weight:700; font-size:15px; }
  .cand .meta{ font-size:12.5px; color:#6b7280; margin:3px 0; }
  .cand .msg{ font-size:12.5px; color:#374151; background:#f8fafc; border-radius:7px; padding:6px 8px; margin-top:5px; }
  .cand a{ color:#2563eb; text-decoration:none; word-break:break-all; font-size:12.5px; }
  .bar{ position:sticky; bottom:0; background:#fff; border-top:1px solid #e3e8ef; padding:13px 16px; display:flex; gap:8px; align-items:center; }
  .bar .cnt{ font-size:13px; color:#6b7280; flex:1; }
  .btn{ background:#dc2626; color:#fff; border:none; border-radius:10px; padding:13px 22px; font-size:15px; font-weight:700; cursor:pointer; }
  .empty{ text-align:center; color:#9ca3af; padding:40px 0; }
</style>
</head>
<body>
<div class="wrap">
  <div class="head">
    <h1>📋 1차 후보 검토 요청</h1>
    <div class="sub">메타크루 · <?=htmlspecialchars($campaign['cp_subject'])?> (모집 <?=(int)$campaign['cp_recruit']?>명)</div>
  </div>
  <div class="body">
    <div class="note">아래는 1차로 추린 후보입니다.<br><b>제외를 원하는 블로거에만 체크</b>하고 맨 아래 <b>[제외 요청 보내기]</b>를 눌러주세요. (체크 안 하면 전원 진행 의견으로 전달됩니다)</div>
    <form method="post">
      <input type="hidden" name="mode" value="submit">
      <?php $n=0; while($r = sql_fetch_array($rs)){ $n++; $ex = in_array((int)$r['rv_id'], array_map('intval',$prevEx)); ?>
      <label class="cand<?=$ex?' ex':''?>">
        <input type="checkbox" name="ex[]" value="<?=(int)$r['rv_id']?>" <?=$ex?'checked':''?>>
        <div class="info">
          <div class="nm"><?=htmlspecialchars($r['rv_mb_nick'] ? $r['rv_mb_nick'] : $r['rv_mb_id'])?></div>
          <div class="meta">채널: <?=htmlspecialchars($r['rv_media'] ? $r['rv_media'] : '-')?></div>
          <?php if($r['rv_channel']){ ?><div><a href="<?=htmlspecialchars($r['rv_channel'])?>" target="_blank" rel="noopener noreferrer">🔗 <?=htmlspecialchars($r['rv_channel'])?></a></div><?php } ?>
          <?php if(trim($r['rv_msg'])){ ?><div class="msg"><?=nl2br(htmlspecialchars($r['rv_msg']))?></div><?php } ?>
        </div>
      </label>
      <?php } ?>
      <?php if(!$n){ ?><div class="empty">현재 검토할 1차 후보가 없습니다.</div><?php } ?>
      <?php if($n){ ?>
      <div class="bar">
        <span class="cnt">총 <?=$n?>명 · 체크한 블로거만 제외 요청됩니다</span>
        <button type="submit" class="btn">제외 요청 보내기</button>
      </div>
      <?php } ?>
    </form>
  </div>
</div>
</body>
</html>
