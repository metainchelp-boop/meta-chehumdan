<?php
/* ============================================================================
   mc_analytics_chart.php — 통계/데이터(GA4) 공통 시각화 카드 (Chart.js v2)
   analytics_*.php 에서 inc_analytics.php(데이터) + head.php 뒤에 include.
   $row1=라벨, $row2=방문자수, $sum_count, $max, $dimensions 사용.
   ============================================================================ */
if(isset($row1) && is_array($row1) && count($row1)>0){

  // 요일 한글 매핑
  $wmap = array('Sunday'=>'일','Monday'=>'월','Tuesday'=>'화','Wednesday'=>'수','Thursday'=>'목','Friday'=>'금','Saturday'=>'토');

  $labels=array(); $data=array();
  for($ci=0; $ci<count($row1); $ci++){
    $v = $row1[$ci];
    switch($dimensions){
      case 'date':          $lab = date("Y-m-d", strtotime($v)); break;
      case 'yearMonth':     $lab = substr($v,0,4)."-".substr($v,4,2); break;
      case 'hour':          $lab = ((int)$v)."시"; break;
      case 'dayOfWeekName': $lab = isset($wmap[$v]) ? $wmap[$v] : $v; break;
      default:              $lab = $v; break;   // sessionSource 등
    }
    $labels[] = $lab;
    $data[]   = (int)$row2[$ci];
  }

  // 차원별 차트 타입
  if($dimensions=='sessionSource')      $ctype='horizontalBar';
  elseif($dimensions=='dayOfWeekName')  $ctype='bar';
  else                                  $ctype='line';   // date / yearMonth / hour

  $is_line = ($ctype=='line');
  $is_hbar = ($ctype=='horizontalBar');
  $peak = ($max>0)? number_format($max) : '0';
  $tot  = isset($sum_count)? number_format($sum_count) : '0';
?>
<div style="background:#fff;border:1px solid #eaedf1;border-radius:14px;padding:18px 18px 8px;margin-bottom:16px;box-shadow:0 1px 2px rgba(16,24,40,.04)">
  <div style="display:flex;flex-wrap:wrap;gap:10px;align-items:center;margin-bottom:14px">
    <b style="font-size:15px;color:#191f28">📈 방문자 추이</b>
    <span style="margin-left:auto;display:inline-flex;gap:8px;flex-wrap:wrap">
      <span style="background:#eafaf0;color:#02b150;border-radius:999px;padding:5px 12px;font-weight:700;font-size:12.5px">합계 <?=$tot?></span>
      <span style="background:#fff7e6;color:#b8860b;border-radius:999px;padding:5px 12px;font-weight:700;font-size:12.5px">최고 <?=$peak?></span>
    </span>
  </div>
  <div style="height:<?=$is_hbar? (max(220, count($labels)*26)) : 300?>px;position:relative"><canvas id="mc_an_chart"></canvas></div>
</div>
<script>
(function(){
  function draw(){
    if(typeof Chart==='undefined'){ return setTimeout(draw,200); }
    var ctx=document.getElementById('mc_an_chart'); if(!ctx) return;
    var labels=<?=json_encode($labels, JSON_UNESCAPED_UNICODE)?>;
    var data=<?=json_encode($data)?>;
    var GREEN='#03c75a';
    new Chart(ctx.getContext('2d'),{
      type:'<?=$ctype?>',
      data:{labels:labels,datasets:[{
        label:'방문자수', data:data,
        <?php if($is_line){ ?>
        borderColor:GREEN, backgroundColor:'rgba(3,199,90,.12)', fill:true, lineTension:.3,
        pointRadius:3, pointBackgroundColor:GREEN, pointBorderColor:'#fff', pointBorderWidth:1, borderWidth:2.5
        <?php } else { ?>
        backgroundColor:GREEN, hoverBackgroundColor:'#02b150', borderWidth:0, maxBarThickness:34
        <?php } ?>
      }]},
      options:{
        responsive:true, maintainAspectRatio:false,
        legend:{display:false},
        tooltips:{callbacks:{label:function(t,d){var v=(<?=$is_hbar?'t.xLabel':'t.yLabel'?>)||0;return '방문자 '+Number(v).toLocaleString()+'명';}}},
        scales:{
          xAxes:[{gridLines:{display:<?=$is_hbar?'true':'false'?>, color:'#f1f3f5'}, ticks:{fontColor:'#6b7684', <?=$is_hbar?'beginAtZero:true,':''?> callback:function(v){return (typeof v==='number')? v.toLocaleString(): v;}}}],
          yAxes:[{ticks:{<?=$is_hbar?'':'beginAtZero:true,'?> fontColor:'#8b95a1', callback:function(v){return (typeof v==='number')? v.toLocaleString(): v;}}, gridLines:{color:'#f1f3f5', display:<?=$is_hbar?'false':'true'?>}}]
        }
      }
    });
  }
  draw();
})();
</script>
<?php } ?>
