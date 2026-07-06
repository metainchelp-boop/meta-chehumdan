<?php
/* ============================================================================
   ad_progress.php — 광고주용 캠페인 실시간 진행현황 대시보드 (로그인 없이 토큰 열람)
   ----------------------------------------------------------------------------
   링크: /ad_progress.php?cp=<cp_id>&k=<token>
   token = substr(md5(cp_id . "metacrew_share_2026_salt"), 0, 10)  ← 공유보고서와 동일 체계
   - 4탭: ①신청자(+선정제외) ②선정자 ③리뷰현황 ④보고서, 회차 마감 전 실시간 현황용.
   - 광고주 액션은 "선정 제외(=즉시 미선정)" 1개만. 그 외 전부 읽기전용.
   - ★개인정보 보호: 이름 마스킹(김○○), 연락처/주소 미노출. 채널·지표만.
   2026-06-16 신설.
   ============================================================================ */
include_once "path.php";
include_once $nfor[path]."/mc_share.php";   // 공유 토큰 헬퍼(서버 비밀키 HMAC) 2026-06-18

$cp = preg_replace('/[^0-9]/', '', isset($_REQUEST['cp']) ? $_REQUEST['cp'] : '');
$k  = preg_replace('/[^a-zA-Z0-9]/', '', isset($_REQUEST['k']) ? $_REQUEST['k'] : '');

function ad_token($cp){ return mc_share_token($cp); }   // 서버 비밀키 HMAC 24자 2026-06-18

// ── 토큰 검증 ──
if (!$cp || !$k || !hash_equals(ad_token($cp), $k)) {
    http_response_code(403);
    header('Content-Type: text/html; charset=utf-8');
    echo '<meta charset="utf-8"><div style="font-family:sans-serif;padding:60px;text-align:center;color:#555">유효하지 않은 링크입니다.<br>담당자에게 다시 문의해 주세요.</div>';
    exit;
}

$cpd = sql_fetch("select * from nfor_campaign where cp_id='".addslashes($cp)."'");
if (!$cpd['cp_id']) { http_response_code(404); die('캠페인을 찾을 수 없습니다.'); }

/* ───────────────────────── 선정 제외 (즉시 미선정) ───────────────────────── */
if (isset($_POST['mode']) && $_POST['mode'] === 'exclude') {
    header('Content-Type: application/json; charset=utf-8');
    $rv_id = preg_replace('/[^0-9]/', '', isset($_POST['rv_id']) ? $_POST['rv_id'] : '');
    // 이 캠페인 소속 + 아직 확정 전(신청1/후보8/선정2)만 제외 허용
    $rv = sql_fetch("select rv_id, rv_step from nfor_review
                     where rv_id='".addslashes($rv_id)."' and rv_cp_id='".addslashes($cp)."' and rv_delete='0'");
    if (!$rv['rv_id']) { echo json_encode(array('ok'=>false,'msg'=>'대상을 찾을 수 없습니다.')); exit; }
    if (!in_array($rv['rv_step'], array('1','8','2'), true)) {
        echo json_encode(array('ok'=>false,'msg'=>'이미 처리된 신청이라 제외할 수 없습니다.')); exit;
    }
    review_cancel($rv['rv_id']);   // rv_step → 5(미선정), 보드 미선정과 동일 로직
    echo json_encode(array('ok'=>true));
    exit;
}

/* ───────────────────────── 집계 ───────────────────────── */
$cnt = array();
$q = sql_query("select count(*) as c, rv_step from nfor_review where rv_cp_id='".addslashes($cp)."' and rv_delete='0' group by rv_step");
while ($r = sql_fetch_array($q)) { $cnt[$r['rv_step']] = (int)$r['c']; }
function _bc($cnt,$s){ return isset($cnt[$s]) ? $cnt[$s] : 0; }

$n_apply = _bc($cnt,'1') + _bc($cnt,'8');                          // 신청 대기(신청+후보)
$n_sel   = _bc($cnt,'2') + _bc($cnt,'3') + _bc($cnt,'4') + _bc($cnt,'7'); // 선정(선정~등록완료~수정요청)
$n_done  = (int)sql_fetch("select count(*) as c from nfor_review where rv_cp_id='".addslashes($cp)."' and rv_delete='0' and rv_step in('2','3','4','7') and rv_url!=''")['c'];
$n_wait  = $n_sel - $n_done;
$n_total_apply = $n_apply + $n_sel + _bc($cnt,'5') + _bc($cnt,'6');  // 전체 신청 누계

// 신청자 성별·연령 분포(전체 신청 기준)
$dsex = array('M'=>0,'F'=>0,''=>0); $dage = array();
$dq = sql_query("select rv_mb_sex, rv_mb_age from nfor_review where rv_cp_id='".addslashes($cp)."' and rv_delete='0'");
while ($dr = sql_fetch_array($dq)) {
    $sx = ($dr['rv_mb_sex']=='M'||$dr['rv_mb_sex']=='F') ? $dr['rv_mb_sex'] : '';
    $dsex[$sx]++;
    $ag = $dr['rv_mb_age'] ? $dr['rv_mb_age'] : '';
    if(!isset($dage[$ag])) $dage[$ag]=0; $dage[$ag]++;
}
$dtot = max(1, array_sum($dsex));
ksort($dage);

// 헬퍼
function _d($dt){ return (!$dt || substr($dt,0,4)=='0000') ? '-' : date('Y.m.d', strtotime($dt)); }
function mask_name($nm,$nick){
    $nm = trim($nm); if($nm===''){ return $nick ? $nick : '익명'; }
    $len = mb_strlen($nm,'utf-8');
    if($len<=1) return $nm.'○';
    return mb_substr($nm,0,1,'utf-8') . str_repeat('○', $len-1);
}
function media_label($url){
    if(preg_match('#blog\.naver#i',$url)) return '네이버 블로그';
    if(preg_match('#instagram#i',$url)) return '인스타그램';
    if(preg_match('#youtu#i',$url)) return '유튜브';
    if(preg_match('#smartstore|shopping\.naver#i',$url)) return '네이버쇼핑';
    return '채널';
}

// 진행 단계 라벨(헤더 상태)
$now = time();
$phase = '진행중';
if($cpd['cp_edatetime'] && strtotime($cpd['cp_edatetime']) > $now) $phase='모집중';
elseif($cpd['cp_contents_edatetime'] && strtotime($cpd['cp_contents_edatetime']) < $now) $phase='리뷰마감';
elseif($cpd['cp_pick_datetime'] && strtotime($cpd['cp_pick_datetime']) > $now) $phase='선정대기';

$brand = '';
if($cpd['cp_supply_no']){
    $sp = sql_fetch("select mb_name, mb_nick, mb_company from nfor_member where mb_no='".addslashes($cpd['cp_supply_no'])."'");
    $brand = $sp['mb_company'] ? $sp['mb_company'] : ($sp['mb_nick'] ? $sp['mb_nick'] : '');
}
$rate = $n_sel>0 ? round($n_done/$n_sel*100) : 0;
?><!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="robots" content="noindex,nofollow">
<title><?=htmlspecialchars($cpd['cp_subject'])?> — 진행 현황</title>
<style>
:root{--accent:#0d9488;--accent2:#14b8a6;--soft:#effbf9;--line:#e9edf2;--ink:#0f172a;--ink2:#475569;--muted:#94a3b8;--bg:#f4f7f9;--card:#fff;--warn:#e11d48;--warnbg:#fff1f2;--ok:#16a34a;--okbg:#e7f6ee;--shadow:0 1px 2px rgba(15,23,42,.04),0 6px 18px rgba(15,23,42,.06);}
*{box-sizing:border-box;-webkit-tap-highlight-color:transparent;}
body{margin:0;background:var(--bg);color:var(--ink);font-family:-apple-system,BlinkMacSystemFont,"Apple SD Gothic Neo","Pretendard","Malgun Gothic",sans-serif;line-height:1.5;-webkit-font-smoothing:antialiased;}
.wrap{max-width:760px;margin:0 auto;padding:0 0 60px;}
.hd{background:linear-gradient(135deg,#0d9488,#14b8a6);color:#fff;padding:22px 18px 20px;}
.hd .brand{font-size:12px;font-weight:700;opacity:.9;display:flex;align-items:center;gap:6px;}
.hd .live{display:inline-flex;align-items:center;gap:5px;background:rgba(255,255,255,.18);padding:3px 9px;border-radius:999px;font-size:11px;font-weight:800;margin-left:auto;}
.hd .live .dot{width:7px;height:7px;border-radius:50%;background:#7dffce;animation:pulse 1.6s infinite;}
@keyframes pulse{0%{box-shadow:0 0 0 0 rgba(125,255,206,.6)}70%{box-shadow:0 0 0 7px rgba(125,255,206,0)}100%{box-shadow:0 0 0 0 rgba(125,255,206,0)}}
.hd h1{margin:10px 0 4px;font-size:21px;font-weight:800;letter-spacing:-.02em;}
.hd .sub{font-size:12.5px;opacity:.92;}
.hd .updated{font-size:11px;opacity:.82;margin-top:8px;}
.kpis{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;padding:14px;margin-top:-14px;}
.kpi{background:var(--card);border:1px solid var(--line);border-radius:14px;padding:12px 8px;text-align:center;box-shadow:var(--shadow);}
.kpi .n{font-size:21px;font-weight:800;}.kpi .l{font-size:11px;color:var(--muted);margin-top:2px;font-weight:600;}.kpi.a .n{color:var(--accent);}
.sched{margin:2px 14px 0;background:var(--card);border:1px solid var(--line);border-radius:14px;padding:12px 14px;box-shadow:var(--shadow);font-size:12.5px;color:var(--ink2);}
.sched .row{display:flex;justify-content:space-between;padding:5px 0;}.sched .row+.row{border-top:1px dashed #eef1f5;}.sched b{color:var(--ink);font-weight:700;}.sched .now{color:var(--accent);font-weight:800;}
.tabs{display:flex;gap:6px;padding:14px 14px 0;position:sticky;top:0;background:var(--bg);z-index:5;}
.tab{flex:1;padding:11px 4px;border:1px solid var(--line);background:var(--card);border-radius:11px 11px 0 0;text-align:center;font-size:12.5px;font-weight:700;color:var(--ink2);cursor:pointer;border-bottom:none;}
.tab .c{display:inline-block;min-width:18px;font-size:11px;background:#eef2f6;color:var(--ink2);border-radius:999px;padding:0 5px;margin-left:3px;}
.tab.on{background:var(--accent);color:#fff;border-color:var(--accent);}.tab.on .c{background:rgba(255,255,255,.25);color:#fff;}
.panel{background:var(--card);border:1px solid var(--line);border-radius:0 0 14px 14px;margin:0 14px;box-shadow:var(--shadow);overflow:hidden;display:none;}.panel.on{display:block;}
.note{font-size:12px;color:var(--muted);padding:11px 14px;background:#fafcfd;border-bottom:1px solid var(--line);}
.empty{padding:40px 14px;text-align:center;color:var(--muted);font-size:13px;}
.item{display:flex;align-items:center;gap:11px;padding:13px 14px;border-bottom:1px solid #f1f4f7;}.item:last-child{border-bottom:none;}
.av{width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,#cdeee6,#e8f7f3);color:var(--accent);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:16px;flex:0 0 auto;}
.who{flex:1;min-width:0;}.who .nm{font-size:14px;font-weight:700;}.who .meta{font-size:11.5px;color:var(--muted);margin-top:1px;}
.tag{display:inline-block;font-size:10.5px;font-weight:700;padding:2px 7px;border-radius:999px;margin-right:4px;margin-top:4px;}
.tag.ch{background:var(--soft);color:var(--accent);}.tag.visit{background:#fff7e6;color:#b8860b;}
.chan{font-size:11.5px;color:var(--accent);text-decoration:none;font-weight:700;}
.btn-x{flex:0 0 auto;background:var(--warnbg);color:var(--warn);border:1px solid #fecdd3;border-radius:9px;padding:8px 11px;font-size:12px;font-weight:800;cursor:pointer;white-space:nowrap;}
.btn-x:active{transform:scale(.96);}.excluded{opacity:.45;}.excluded .btn-x{background:#f1f3f5;color:#94a3b8;border-color:#e2e8f0;cursor:default;}
.badge-done{font-size:11px;font-weight:800;color:var(--ok);background:var(--okbg);border-radius:999px;padding:4px 9px;white-space:nowrap;}
.badge-wait{font-size:11px;font-weight:800;color:#b8860b;background:#fff7e6;border-radius:999px;padding:4px 9px;white-space:nowrap;}
.prog{padding:14px;border-bottom:1px solid var(--line);}.prog .lab{display:flex;justify-content:space-between;font-size:12.5px;font-weight:700;color:var(--ink2);margin-bottom:7px;}
.prog .bar{height:12px;background:#eef2f6;border-radius:999px;overflow:hidden;}.prog .fill{height:100%;background:linear-gradient(90deg,#0d9488,#14b8a6);border-radius:999px;}
.rep{padding:14px;}.rep h3{font-size:13px;font-weight:800;margin:18px 0 8px;}.rep h3:first-child{margin-top:2px;}
.funnel{display:grid;grid-template-columns:repeat(5,1fr);gap:6px;text-align:center;}
.funnel .f{background:var(--soft);border:1px solid #cdece6;border-radius:11px;padding:11px 4px;}.funnel .f .n{font-size:18px;font-weight:800;color:var(--accent);}.funnel .f .l{font-size:10.5px;color:var(--ink2);margin-top:2px;}
.dist{display:flex;flex-direction:column;gap:6px;}.dist .d{display:flex;align-items:center;gap:8px;font-size:12px;}
.dist .dl{width:62px;color:var(--ink2);font-weight:600;flex:0 0 auto;}.dist .db{flex:1;height:18px;background:#eef2f6;border-radius:6px;overflow:hidden;}.dist .df{height:100%;background:var(--accent2);border-radius:6px;}.dist .dv{width:54px;text-align:right;color:var(--muted);font-weight:700;flex:0 0 auto;}
.foot{text-align:center;font-size:11px;color:var(--muted);padding:22px 14px 0;}
.toast{position:fixed;left:50%;bottom:24px;transform:translateX(-50%) translateY(20px);background:#0f172a;color:#fff;font-size:13px;font-weight:700;padding:11px 18px;border-radius:999px;opacity:0;transition:.25s;pointer-events:none;z-index:50;}.toast.show{opacity:1;transform:translateX(-50%) translateY(0);}
</style>
</head>
<body>
<div class="wrap">
  <div class="hd">
    <div class="brand">🧪 메타체험단 캠페인 진행 현황 <span class="live"><span class="dot"></span>LIVE</span></div>
    <h1><?=htmlspecialchars($cpd['cp_subject'])?></h1>
    <div class="sub"><?=$brand?'브랜드: '.htmlspecialchars($brand).' · ':''?>모집 <?=number_format($cpd['cp_recruit'])?>명 · <?=$phase?></div>
    <div class="updated">⟳ 실시간 자동 갱신 · <?=date('m.d H:i')?> 기준</div>
  </div>

  <div class="kpis">
    <div class="kpi a"><div class="n"><?=number_format($n_total_apply)?></div><div class="l">신청</div></div>
    <div class="kpi"><div class="n"><?=number_format($n_sel)?></div><div class="l">선정</div></div>
    <div class="kpi"><div class="n"><?=number_format($n_done)?></div><div class="l">리뷰등록</div></div>
    <div class="kpi"><div class="n"><?=number_format($n_wait<0?0:$n_wait)?></div><div class="l">미등록</div></div>
  </div>

  <div class="sched">
    <div class="row"><span>리뷰어 신청</span><b><?=_d($cpd['cp_sdatetime'])?> ~ <?=_d($cpd['cp_edatetime'])?></b></div>
    <div class="row"><span>선정자 발표</span><b><?=_d($cpd['cp_pick_datetime'])?></b></div>
    <div class="row"><span>체험 · 리뷰 등록</span><span class="now"><?=_d($cpd['cp_contents_sdatetime'])?> ~ <?=_d($cpd['cp_contents_edatetime'])?></span></div>
    <div class="row"><span>결과 발표</span><b><?=_d($cpd['cp_result_datetime'])?></b></div>
  </div>

  <div class="tabs">
    <div class="tab on" data-t="apply">신청자<span class="c"><?=$n_apply?></span></div>
    <div class="tab" data-t="sel">선정자<span class="c"><?=$n_sel?></span></div>
    <div class="tab" data-t="rev">리뷰현황<span class="c"><?=$n_done?>/<?=$n_sel?></span></div>
    <div class="tab" data-t="rep">보고서</div>
  </div>

  <!-- ① 신청자 -->
  <div class="panel on" id="p-apply">
    <div class="note">⬇ 제외할 신청자는 <b>[선정 제외]</b>를 눌러주세요. 누르면 즉시 미선정 처리됩니다.</div>
    <?php
    $rows = sql_query("select r.*, m.mb_blog from nfor_review r left join nfor_member m on m.mb_no=r.rv_mb_no
                       where r.rv_cp_id='".addslashes($cp)."' and r.rv_step in('1','8') and r.rv_delete='0'
                       order by r.rv_id desc limit 500");
    $has=false;
    while($row = sql_fetch_array($rows)){ $has=true;
      $disp = mask_name($row['rv_mb_name'],$row['rv_mb_nick']);
      $dsx=($row['rv_mb_sex']=='M'?'남':($row['rv_mb_sex']=='F'?'여':'')); $dag=($row['rv_mb_age']?$row['rv_mb_age'].'대':'');
      $meta=trim($dag.($dag&&$dsx?' · ':'').$dsx.(($dag||$dsx)&&$row['rv_mb_area']?' · ':'').$row['rv_mb_area']);
      $blog = $row['mb_blog'] ? $row['mb_blog'] : '';
    ?>
    <div class="item" id="ap<?=(int)$row['rv_id']?>">
      <div class="av"><?=htmlspecialchars(mb_substr($disp,0,1,'utf-8'))?></div>
      <div class="who">
        <div class="nm"><?=htmlspecialchars($disp)?></div>
        <div class="meta"><?=htmlspecialchars($meta)?></div>
        <div>
          <?php if($row['rv_channel']){ ?><a class="chan" href="<?=htmlspecialchars($row['rv_channel'])?>" target="_blank" rel="noopener"><?=media_label($row['rv_channel'])?> ↗</a><?php }else{ ?><span style="color:#c0c6cd;font-size:11.5px">채널 미등록</span><?php } ?>
          <?php if($blog){ ?> <span class="tag visit" data-blog="<?=htmlspecialchars($blog)?>" style="display:none"></span><?php } ?>
        </div>
      </div>
      <button class="btn-x" onclick="excl(<?=(int)$row['rv_id']?>)">선정 제외</button>
    </div>
    <?php } if(!$has){ ?><div class="empty">아직 신청자가 없습니다.</div><?php } ?>
  </div>

  <!-- ② 선정자 -->
  <div class="panel" id="p-sel">
    <div class="note">최종 선정된 크리에이터입니다. (읽기 전용)</div>
    <?php
    $rows = sql_query("select r.*, m.mb_blog from nfor_review r left join nfor_member m on m.mb_no=r.rv_mb_no
                       where r.rv_cp_id='".addslashes($cp)."' and r.rv_step in('2','3','4','7') and r.rv_delete='0'
                       order by r.rv_id desc limit 500");
    $has=false;
    while($row = sql_fetch_array($rows)){ $has=true;
      $disp = mask_name($row['rv_mb_name'],$row['rv_mb_nick']);
      $dsx=($row['rv_mb_sex']=='M'?'남':($row['rv_mb_sex']=='F'?'여':'')); $dag=($row['rv_mb_age']?$row['rv_mb_age'].'대':'');
      $meta=trim($dag.($dag&&$dsx?' · ':'').$dsx.(($dag||$dsx)&&$row['rv_mb_area']?' · ':'').$row['rv_mb_area']);
    ?>
    <div class="item">
      <div class="av"><?=htmlspecialchars(mb_substr($disp,0,1,'utf-8'))?></div>
      <div class="who"><div class="nm"><?=htmlspecialchars($disp)?></div><div class="meta"><?=htmlspecialchars($meta)?></div>
        <?php if($row['rv_channel']){ ?><div><a class="chan" href="<?=htmlspecialchars($row['rv_channel'])?>" target="_blank" rel="noopener"><?=media_label($row['rv_channel'])?> ↗</a></div><?php } ?>
      </div>
      <span class="badge-done">선정</span>
    </div>
    <?php } if(!$has){ ?><div class="empty">아직 선정자가 없습니다.</div><?php } ?>
  </div>

  <!-- ③ 리뷰현황 -->
  <div class="panel" id="p-rev">
    <div class="prog">
      <div class="lab"><span>리뷰 등록 진행률</span><span><?=$n_done?> / <?=$n_sel?> 명 (<?=$rate?>%)</span></div>
      <div class="bar"><div class="fill" style="width:<?=$rate?>%"></div></div>
    </div>
    <div class="note">체험 기간 중 리뷰 등록 현황입니다. (읽기 전용 · 실시간)</div>
    <?php
    $rows = sql_query("select * from nfor_review where rv_cp_id='".addslashes($cp)."' and rv_step in('2','3','4','7') and rv_delete='0' order by (rv_url!='') desc, rv_id desc limit 500");
    $has=false;
    while($row = sql_fetch_array($rows)){ $has=true;
      $disp = mask_name($row['rv_mb_name'],$row['rv_mb_nick']);
      $done = ($row['rv_url']!='');
    ?>
    <div class="item">
      <div class="av"><?=htmlspecialchars(mb_substr($disp,0,1,'utf-8'))?></div>
      <div class="who"><div class="nm"><?=htmlspecialchars($disp)?></div>
        <div class="meta"><?=$done? (substr($row['rv_datetime'],0,10).' 등록') : '리뷰 미등록'?></div></div>
      <?php if($done){ ?><a class="chan" href="<?=htmlspecialchars($row['rv_url'])?>" target="_blank" rel="noopener" style="margin-right:8px">리뷰 보기 ↗</a><span class="badge-done">등록완료</span><?php }else{ ?><span class="badge-wait">미등록</span><?php } ?>
    </div>
    <?php } if(!$has){ ?><div class="empty">선정자가 확정되면 표시됩니다.</div><?php } ?>
  </div>

  <!-- ④ 보고서 -->
  <div class="panel" id="p-rep">
    <div class="rep">
      <h3>📊 진행 퍼널</h3>
      <div class="funnel">
        <div class="f"><div class="n"><?=number_format($cpd['cp_recruit'])?></div><div class="l">모집</div></div>
        <div class="f"><div class="n"><?=number_format($n_total_apply)?></div><div class="l">신청</div></div>
        <div class="f"><div class="n"><?=number_format($n_sel)?></div><div class="l">선정</div></div>
        <div class="f"><div class="n"><?=number_format($n_done)?></div><div class="l">리뷰등록</div></div>
        <div class="f"><div class="n"><?=number_format(_bc($cnt,'4'))?></div><div class="l">등록완료</div></div>
      </div>
      <h3>성별 분포 (신청자)</h3>
      <div class="dist">
        <div class="d"><span class="dl">여성</span><span class="db"><span class="df" style="width:<?=round($dsex['F']/$dtot*100)?>%"></span></span><span class="dv"><?=number_format($dsex['F'])?>명</span></div>
        <div class="d"><span class="dl">남성</span><span class="db"><span class="df" style="width:<?=round($dsex['M']/$dtot*100)?>%"></span></span><span class="dv"><?=number_format($dsex['M'])?>명</span></div>
        <?php if($dsex['']){ ?><div class="d"><span class="dl">미입력</span><span class="db"><span class="df" style="width:<?=round($dsex['']/$dtot*100)?>%"></span></span><span class="dv"><?=number_format($dsex[''])?>명</span></div><?php } ?>
      </div>
      <h3>연령대 분포</h3>
      <div class="dist">
        <?php foreach($dage as $ag=>$c){ if(!$ag) continue; ?>
        <div class="d"><span class="dl"><?=htmlspecialchars($ag)?>대</span><span class="db"><span class="df" style="width:<?=round($c/$dtot*100)?>%"></span></span><span class="dv"><?=number_format($c)?>명</span></div>
        <?php } ?>
      </div>
      <div class="note" style="margin-top:18px;border:none;background:none;padding:0">상세 결과 보고서는 회차 마감 후 별도로 발송됩니다.</div>
    </div>
  </div>

  <div class="foot">메타체험단 · 본 페이지는 캠페인 진행 기간 동안 실시간 갱신됩니다.<br>개인정보 보호를 위해 신청자 이름은 마스킹 처리됩니다.</div>
</div>
<div class="toast" id="toast"></div>

<script>
var CP="<?=$cp?>", K="<?=$k?>";
// 탭 (해시로 자동갱신 시 유지)
function showTab(t){
  document.querySelectorAll('.tab').forEach(function(x){x.classList.toggle('on',x.dataset.t===t);});
  document.querySelectorAll('.panel').forEach(function(x){x.classList.remove('on');});
  var p=document.getElementById('p-'+t); if(p)p.classList.add('on');
}
document.querySelectorAll('.tab').forEach(function(t){ t.onclick=function(){ location.hash=t.dataset.t; showTab(t.dataset.t); }; });
if(location.hash){ showTab(location.hash.replace('#','')); }

// 선정 제외 → 즉시 미선정
function excl(id){
  var el=document.getElementById('ap'+id);
  if(!el||el.classList.contains('excluded'))return;
  if(!confirm('이 신청자를 미선정 처리할까요?\n(되돌리려면 메타체험단 담당자에게 문의해 주세요.)'))return;
  var btn=el.querySelector('.btn-x'); btn.textContent='처리중…'; btn.disabled=true;
  var body='mode=exclude&cp='+encodeURIComponent(CP)+'&k='+encodeURIComponent(K)+'&rv_id='+encodeURIComponent(id);
  fetch(location.pathname,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:body})
   .then(function(r){return r.json();})
   .then(function(d){
      if(d.ok){ el.classList.add('excluded'); btn.textContent='미선정됨'; toast('미선정 처리되었습니다.'); }
      else { btn.textContent='선정 제외'; btn.disabled=false; alert(d.msg||'처리에 실패했습니다.'); }
   }).catch(function(){ btn.textContent='선정 제외'; btn.disabled=false; alert('통신 오류가 발생했습니다.'); });
}
function toast(m){var t=document.getElementById('toast');t.textContent=m;t.classList.add('show');setTimeout(function(){t.classList.remove('show');},1900);}

// 블로그 방문수 lazy 조회(네이버만, 실패시 숨김)
document.querySelectorAll('.tag.visit[data-blog]').forEach(function(sp){
  var b=sp.getAttribute('data-blog'); if(!b)return;
  fetch('/admin/ajax_blog_count_chart.php?blog_id='+encodeURIComponent(b)).then(function(r){return r.text();}).then(function(t){
    var p=t.split('|:|'); if(p[0]==='SUCCESS' && p[3] && parseInt(p[3],10)>0){ sp.textContent='최근 방문 '+parseInt(p[3],10).toLocaleString(); sp.style.display=''; }
  }).catch(function(){});
});

// 실시간: 2분마다 조용히 갱신(현재 탭 유지)
setTimeout(function(){ location.reload(); }, 120000);
</script>
</body>
</html>
