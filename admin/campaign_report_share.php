<?php
/* 광고주용 공유 보고서 수동 생성 화면. 실제 생성은 공용 모듈에 위임한다. */
include_once "path.php";
include_once $nfor[path]."/lib/mc_campaign_report.lib.php";

if(!$member[mb_admin]) die("접근 권한이 없습니다. 관리자 로그인이 필요합니다.");

$cp_id = preg_replace('/[^0-9]/', '', isset($_GET[cp_id]) ? $_GET[cp_id] : "");
if(!$cp_id) die("캠페인 번호(cp_id)가 필요합니다.");

$inc_mt = isset($_GET['mt']) && $_GET['mt']==='1';
$result = mc_campaign_report_generate($cp_id, $inc_mt);
$subject = isset($result['subject']) ? $result['subject'] : "캠페인 #".$cp_id;
$public_url = isset($result['public_url']) ? $result['public_url'] : "";
$mt_has = !empty($result['metatech_has']);
$error = isset($result['error']) ? $result['error'] : "알 수 없는 오류";
?><!DOCTYPE html>
<html lang="ko"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>광고주 공유 보고서</title>
<style>
body{margin:0;background:#f1f5f9;font-family:-apple-system,BlinkMacSystemFont,"Apple SD Gothic Neo","Malgun Gothic",sans-serif;color:#0f172a;}
.box{max-width:640px;margin:48px auto;background:#fff;border:1px solid #e8ebf0;border-radius:14px;box-shadow:0 2px 8px rgba(15,23,42,.05);padding:30px;}
h1{font-size:19px;margin:0 0 6px;}.sub{color:#64748b;font-size:13px;margin-bottom:22px;}.label{font-size:12px;font-weight:700;color:#475569;margin:18px 0 6px;}
.urlrow{display:flex;gap:8px;}input.url{flex:1;min-width:0;padding:12px 14px;border:1px solid #e8ebf0;border-radius:10px;font-size:13px;color:#0f172a;background:#f8fafc;}
.btn{border:none;border-radius:10px;padding:12px 18px;font-size:13px;font-weight:700;cursor:pointer;text-decoration:none;display:inline-block;}.btn.p{background:#4f46e5;color:#fff;}.btn.g{background:#fff;color:#475569;border:1px solid #e8ebf0;}
.btns{display:flex;gap:8px;margin-top:14px;flex-wrap:wrap;}.tip{margin-top:22px;font-size:12px;color:#64748b;line-height:1.7;background:#f8fafc;border-radius:10px;padding:14px 16px;}.err{background:#fef2f2;color:#dc2626;border:1px solid #fecaca;border-radius:10px;padding:14px 16px;font-size:13px;}.ok{display:inline-block;background:#ecfdf5;color:#059669;font-weight:700;font-size:12px;padding:3px 10px;border-radius:999px;margin-bottom:10px;}
@media(max-width:700px){.box{margin:0;min-height:100vh;border:0;border-radius:0;padding:24px 18px}.urlrow{display:block}.urlrow .btn{width:100%;margin-top:8px}.btns .btn{flex:1;text-align:center}}
</style></head><body><div class="box">
<?php if($result['ok']){ ?>
	<div class="ok">생성 완료</div>
	<h1>광고주 공유 보고서가 만들어졌습니다</h1>
	<div class="sub"><?=htmlspecialchars($subject)?> (#<?=$cp_id?>)</div>
	<div class="label">공유 링크 (로그인 없이 열람 가능)</div>
	<div class="urlrow"><input class="url" id="shareurl" type="text" readonly value="<?=htmlspecialchars($public_url)?>"><button class="btn p" onclick="cp()">링크 복사</button></div>
	<div class="btns"><a class="btn g" href="<?=htmlspecialchars($public_url)?>" target="_blank" rel="noopener">새 창에서 열기</a><a class="btn g" href="<?=htmlspecialchars($public_url)?>" download>HTML 파일 다운로드</a></div>
	<?php if($mt_has){ ?><div class="label">📱 메타테크 부업 미션</div><div class="tip">현재 보고서에 <b><?=$inc_mt?'포함됨':'미포함'?></b>.<br><a class="btn <?=$inc_mt?'g':'p'?>" style="padding:7px 13px;margin-top:8px" href="?cp_id=<?=$cp_id?><?=$inc_mt?'':'&mt=1'?>">메타테크 <?=$inc_mt?'빼고':'포함하여'?> 다시 생성</a></div><?php } ?>
	<div class="tip">• 같은 캠페인은 다시 생성해도 주소가 바뀌지 않습니다.<br>• 리뷰 승인·취소 뒤에는 자동으로 최신 수치가 반영됩니다.<br>• 이름은 마스킹되고 검색엔진에는 노출되지 않습니다.</div>
<?php } else { ?>
	<h1>보고서 생성 실패</h1><div class="err"><?=htmlspecialchars($error)?></div><div class="tip">기존 공개 보고서가 있었다면 그대로 보존됩니다. 오류를 확인한 뒤 다시 시도해 주세요.</div>
<?php } ?>
</div><script>function cp(){var i=document.getElementById('shareurl');i.select();i.setSelectionRange(0,99999);try{document.execCommand('copy');alert('링크가 복사되었습니다.');}catch(e){alert('복사 실패 — 링크를 길게 눌러 복사하세요.');}}</script></body></html>
