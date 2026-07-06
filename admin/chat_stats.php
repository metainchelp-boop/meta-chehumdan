<?php
/* 고객센터 상담 통계 (관리자). 일별 문의·평균 첫응답·담당자별·유형별·미응대. 2026-06-24 */
include_once "path.php";
if(empty($member[mb_no]) || (int)$member[mb_admin] < 1){ alert("권한이 없습니다."); }

/* KPI */
$k_un   = sql_fetch("select count(*) as c from nfor_chat where ct_ad_unread>0");
$k_today= sql_fetch("select count(*) as c from nfor_chat where date(ct_datetime)=curdate()");
$k_total= sql_fetch("select count(*) as c from nfor_chat");
$k_done = sql_fetch("select count(*) as c from nfor_chat where ct_status='1'");
$k_msg  = sql_fetch("select count(*) as c from nfor_chat_msg");
$k_resp = sql_fetch("select avg(TIMESTAMPDIFF(MINUTE, fm.t, fa.t)) as m from
  (select cm_ct_id, min(cm_datetime) t from nfor_chat_msg where cm_sender='M' group by cm_ct_id) fm
  join (select cm_ct_id, min(cm_datetime) t from nfor_chat_msg where cm_sender='A' group by cm_ct_id) fa
  on fm.cm_ct_id=fa.cm_ct_id where fa.t>=fm.t");
$avg_min = $k_resp['m']!==null ? round($k_resp['m']) : null;

/* 일별 14일 */
$daymap=array();
$rd=sql_query("select date(ct_datetime) as d, count(*) as c from nfor_chat where ct_datetime>=date_sub(curdate(), interval 13 day) group by date(ct_datetime)");
while($x=sql_fetch_array($rd)) $daymap[$x['d']]=(int)$x['c'];
$days=array(); $maxc=1;
for($i=13;$i>=0;$i--){ $d=date('Y-m-d', strtotime("-$i day")); $c=isset($daymap[$d])?$daymap[$d]:0; $days[]=array('d'=>$d,'c'=>$c); if($c>$maxc)$maxc=$c; }

/* 담당자별 */
$mds=array();
$rm=sql_query("select (case when ct_md_name='' then '미지정/일반' else ct_md_name end) as md, count(*) as t, sum(ct_status='1') as r, sum(ct_ad_unread>0) as u from nfor_chat group by md order by t desc");
while($x=sql_fetch_array($rm)) $mds[]=$x;

/* 유형별 */
$cates=array();
$rc=sql_query("select (case when ct_cate='' then '미선택' else ct_cate end) as cate, count(*) as c from nfor_chat group by cate order by c desc");
while($x=sql_fetch_array($rc)) $cates[]=$x;

include_once "head.php";
?>
<style>
.cs-wrap{margin-top:10px;font-family:'Pretendard','Apple SD Gothic Neo',sans-serif}
.cs-h{font-size:20px;font-weight:800;margin:4px 0 14px;display:flex;align-items:center;gap:10px}
.cs-h .sub{font-size:12px;color:#8b95a1;font-weight:600}
.cs-kpis{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:18px}
.cs-card{background:#fff;border:1px solid #eaedf1;border-radius:14px;padding:16px 18px;box-shadow:0 1px 2px rgba(0,0,0,.03)}
.cs-card .lab{font-size:12.5px;color:#6b7684;font-weight:700}
.cs-card .val{font-size:26px;font-weight:800;color:#191f28;margin-top:6px}
.cs-card .val small{font-size:13px;color:#8b95a1;font-weight:700}
.cs-card.alert .val{color:#e0392f}
.cs-row{display:grid;grid-template-columns:1.4fr 1fr;gap:16px;margin-bottom:18px}
.cs-box{background:#fff;border:1px solid #eaedf1;border-radius:14px;padding:18px}
.cs-box h3{font-size:14px;font-weight:800;margin:0 0 16px}
.cs-bars{display:flex;align-items:flex-end;gap:6px;height:160px}
.cs-bars .bar{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:flex-end;gap:5px;height:100%}
.cs-bars .bar .b{width:60%;max-width:26px;background:linear-gradient(#34d97b,#03c75a);border-radius:5px 5px 0 0;min-height:2px}
.cs-bars .bar .n{font-size:10.5px;font-weight:800;color:#4e5968}
.cs-bars .bar .dl{font-size:9.5px;color:#aab1ba}
table.cs-t{width:100%;border-collapse:collapse;font-size:13px}
table.cs-t th{text-align:left;color:#8b95a1;font-weight:700;font-size:12px;padding:8px 6px;border-bottom:1px solid #eaedf1}
table.cs-t td{padding:9px 6px;border-bottom:1px solid #f4f6f8}
table.cs-t td .pill{background:#fdecef;color:#e0392f;font-weight:800;font-size:11px;border-radius:999px;padding:1px 7px}
.cs-cate{display:flex;flex-direction:column;gap:9px}
.cs-cate .it{display:flex;align-items:center;gap:9px;font-size:12.5px}
.cs-cate .it .nm{width:78px;flex:0 0 auto;font-weight:700;color:#4e5968}
.cs-cate .it .track{flex:1;background:#eef1f4;border-radius:999px;height:14px;overflow:hidden}
.cs-cate .it .fill{height:100%;background:#7a4ddb;border-radius:999px}
.cs-cate .it .c{width:34px;text-align:right;font-weight:800;color:#191f28}
</style>
<div class="cs-wrap">
  <div class="cs-h">📊 고객센터 상담 통계 <span class="sub">· 실시간 집계 (새로고침 시 갱신)</span></div>

  <div class="cs-kpis">
    <div class="cs-card<?=$k_un['c']>0?' alert':''?>"><div class="lab">미응대</div><div class="val"><?=number_format($k_un['c'])?><small>건</small></div></div>
    <div class="cs-card"><div class="lab">오늘 문의</div><div class="val"><?=number_format($k_today['c'])?><small>건</small></div></div>
    <div class="cs-card"><div class="lab">평균 첫응답</div><div class="val"><?=($avg_min===null?'-':number_format($avg_min))?><small><?=($avg_min===null?'':'분')?></small></div></div>
    <div class="cs-card"><div class="lab">해결 완료</div><div class="val"><?=number_format($k_done['c'])?><small>건</small></div></div>
    <div class="cs-card"><div class="lab">누적 문의 / 메시지</div><div class="val"><?=number_format($k_total['c'])?><small> / <?=number_format($k_msg['c'])?></small></div></div>
  </div>

  <div class="cs-row">
    <div class="cs-box">
      <h3>최근 14일 문의 수</h3>
      <div class="cs-bars">
        <?php foreach($days as $dd){ $hp = $dd['c']>0 ? max(4, round($dd['c']/$maxc*140)) : 2; ?>
        <div class="bar"><div class="n"><?=$dd['c']?></div><div class="b" style="height:<?=$hp?>px"></div><div class="dl"><?=substr($dd['d'],5)?></div></div>
        <?php } ?>
      </div>
    </div>
    <div class="cs-box">
      <h3>문의 유형 분포</h3>
      <div class="cs-cate">
        <?php $cmax=1; foreach($cates as $c){ if($c['c']>$cmax)$cmax=$c['c']; } foreach($cates as $c){ ?>
        <div class="it"><span class="nm"><?=$c['cate']?></span><span class="track"><span class="fill" style="width:<?=round($c['c']/$cmax*100)?>%"></span></span><span class="c"><?=number_format($c['c'])?></span></div>
        <?php } if(!count($cates)){ echo '<div style="color:#b5bdc6;font-size:12px">데이터 없음</div>'; } ?>
      </div>
    </div>
  </div>

  <div class="cs-box">
    <h3>담당자별 상담 처리</h3>
    <table class="cs-t">
      <tr><th>담당자</th><th>총 상담</th><th>해결</th><th>미응대</th></tr>
      <?php foreach($mds as $m){ ?>
      <tr><td><b><?=$m['md']?></b></td><td><?=number_format($m['t'])?></td><td><?=number_format($m['r'])?></td><td><?=$m['u']>0?'<span class="pill">'.number_format($m['u']).'</span>':'0'?></td></tr>
      <?php } if(!count($mds)){ echo '<tr><td colspan="4" style="color:#b5bdc6;text-align:center;padding:20px">아직 상담 데이터가 없습니다</td></tr>'; } ?>
    </table>
  </div>
</div>
<?php include_once "tail.php"; ?>
