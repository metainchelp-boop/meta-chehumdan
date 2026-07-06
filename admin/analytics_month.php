<?php
include_once "path.php";

$dimensions = "yearMonth";

include_once "inc_analytics.php";

include_once "head.php";
include_once "inc_analytics_search.php";
?>
<?php include_once "mc_analytics_chart.php"; ?>

<table class="table row_tbl">
<colgroup>
	<col class="width-150p">
	<col class="width-150p">
	<col class="width-150p">
	<col style="width:850px; ">
</colgroup>
<tr>
    <th>년-월</th>
    <th>방문자수</th>
    <th>비율(%)</th>
    <th>그래프</th>
</tr>
<?php
for($i=0; $i<count($row1); $i++){
	$count = $row2[$i];
	$rate = ($count / $sum_count * 100);
	$s_rate = number_format($rate, 1);
	$bar = (int)($count / $max * 100);
?>
<tr>
	<td><?=substr($row1[$i],0,4)?>-<?=substr($row1[$i],4,2)?></td>
	<td><?=number_format($count)?></td>
	<td><?=$s_rate?></td>
	<td class="graph_left" >
		<div class="progress">
			<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width:<?=$bar?>%">
		  </div>
		</div>
	</td>
</tr>
<?php
}
?>
</table>

<?php
include_once "tail.php";
?>