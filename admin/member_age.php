<?php	// 나이통계
include_once "path.php";

for($i=1; $i<10; $i++){

	$age = $i*10;
	$sdate = (date("Y")-($age+9))."1231";
	$edate = (date("Y")-$age)."0101";
	$m_age = sql_fetch("select count(*) as cnt from nfor_member where mb_sex='M' and mb_birthday > '$sdate' and mb_birthday < '$edate'");
	$f_age = sql_fetch("select count(*) as cnt from nfor_member where mb_sex='F' and mb_birthday > '$sdate' and mb_birthday < '$edate'");
	$row[$i][age] = $age;
	$row[$i][m] = $m_age[cnt];
	$row[$i][f] = $f_age[cnt];
	$row[$i][total] = $m_age[cnt]+$f_age[cnt];


	$total_m += $m_age[cnt];
	$total_f += $f_age[cnt];
}

include_once "head.php";

// 차트용 데이터 (PHP → JS)
$js_labels=array(); $js_m=array(); $js_f=array();
for($i=1;$i<10;$i++){ $js_labels[]="'".$row[$i][age]."대'"; $js_m[]=(int)$row[$i][m]; $js_f[]=(int)$row[$i][f]; }
$grand = $total_m+$total_f;
$pct_m = $grand>0 ? round($total_m/$grand*100,1) : 0;
$pct_f = $grand>0 ? round($total_f/$grand*100,1) : 0;
?>
<!-- ── 연령·성별 시각화 카드 (Chart.js v2) ── -->
<div style="background:#fff;border:1px solid #eaedf1;border-radius:14px;padding:18px 18px 8px;margin-bottom:16px;box-shadow:0 1px 2px rgba(16,24,40,.04)">
  <div style="display:flex;flex-wrap:wrap;gap:10px;align-items:center;margin-bottom:14px">
    <b style="font-size:15px;color:#191f28">📊 연령대별 가입 현황</b>
    <span style="margin-left:auto;display:inline-flex;gap:8px;flex-wrap:wrap">
      <span style="background:#eef4ff;color:#2b6cff;border-radius:999px;padding:5px 12px;font-weight:700;font-size:12.5px">남성 <?=number_format($total_m)?>명 · <?=$pct_m?>%</span>
      <span style="background:#fdeef4;color:#e64980;border-radius:999px;padding:5px 12px;font-weight:700;font-size:12.5px">여성 <?=number_format($total_f)?>명 · <?=$pct_f?>%</span>
      <span style="background:#eafaf0;color:#02b150;border-radius:999px;padding:5px 12px;font-weight:700;font-size:12.5px">전체 <?=number_format($grand)?>명</span>
    </span>
  </div>
  <div style="height:300px;position:relative"><canvas id="mc_age_chart"></canvas></div>
</div>
<script>
(function(){
  function draw(){
    if(typeof Chart==='undefined'){ return setTimeout(draw,200); }
    var ctx=document.getElementById('mc_age_chart');
    if(!ctx) return;
    new Chart(ctx.getContext('2d'),{
      type:'bar',
      data:{
        labels:[<?=implode(',',$js_labels)?>],
        datasets:[
          {label:'남성',data:[<?=implode(',',$js_m)?>],backgroundColor:'#4c8dff',borderRadius:6,maxBarThickness:34},
          {label:'여성',data:[<?=implode(',',$js_f)?>],backgroundColor:'#f06595',borderRadius:6,maxBarThickness:34}
        ]
      },
      options:{
        responsive:true, maintainAspectRatio:false,
        legend:{position:'top', labels:{boxWidth:14, fontColor:'#475569', fontStyle:'bold'}},
        tooltips:{callbacks:{label:function(t,d){var v=d.datasets[t.datasetIndex].data[t.index];return d.datasets[t.datasetIndex].label+': '+v.toLocaleString()+'명';}}},
        scales:{
          xAxes:[{gridLines:{display:false}, ticks:{fontColor:'#6b7684'}}],
          yAxes:[{ticks:{beginAtZero:true, fontColor:'#8b95a1', callback:function(v){return v.toLocaleString();}}, gridLines:{color:'#f1f3f5'}}]
        }
      }
    });
  }
  draw();
})();
</script>
<table class="table row_tbl">
<colgroup>
	<col class="width-150p">
	<col>
	<col>
	<col class="width-150p">
</colgroup>
<tr>
    <th>연령</td>
    <th>남자</th>
    <th>여자</th>
    <th>합산</th>
</tr>
<?
for($i=1; $i<10; $i++){
?>
<tr>
	<td><?=$row[$i][age]?>대</td>
	<td><?=number_format($row[$i][m])?>명</td>
	<td><?=number_format($row[$i][f])?>명</td>
	<td><?=number_format($row[$i][total])?>명</td>	
</tr>
<? } ?>
<tr>
	<td>전체</td>
	<td><?=number_format($total_m)?>명</td>
	<td><?=number_format($total_f)?>명</td>
	<td><?=number_format($total_m+$total_f)?>명</td>	
</tr>
</table>
<?
include_once "tail.php";
?>