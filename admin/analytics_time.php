<?php
include_once "path.php";

//require '../vendor7/autoload.php';

if(!$api['api_google_analytics_propertyid'] or !$api['api_google_analytics_json']) alert("구글애널리틱스 API설정후 이용가능합니다","api_list.php");

/*
https://developers.google.com/analytics/devguides/reporting/data/v1/realtime-api-schema
https://developers.google.com/analytics/devguides/reporting/data/v1/api-schema#dimensions
https://developers.google.com/analytics/devguides/migration/api/reporting-ua-to-ga4-dims-mets?hl=ko
*/

use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\OrderBy;
use Google\Analytics\Data\V1beta\OrderBy\DimensionOrderBy;
use Google\Analytics\Data\V1beta\OrderBy\DimensionOrderBy\OrderType;

putenv('GOOGLE_APPLICATION_CREDENTIALS='.$nfor['path']."/data/google/".$api['api_google_analytics_json']);

$client = new BetaAnalyticsDataClient();

$response = $client->runRealtimeReport([
    'property' => 'properties/' . $api['api_google_analytics_propertyid'],
    'dimensions' => [new Dimension(
        [
            'name' => 'minutesAgo',
        ]
    ),
    ],
    'metrics' => [new Metric(
        [
            'name' => 'activeUsers',
        ]
    )
    ],
	'orderBys' => [
        new OrderBy([
            'dimension' => new OrderBy\DimensionOrderBy([
                'dimension_name' => 'minutesAgo', // your dimension here
                'order_type' => OrderBy\DimensionOrderBy\OrderType::NUMERIC
            ]),
            'desc' => false,
        ]),
    ],
]);

$max = 0;
$sum_count = 0;
foreach($response->getRows() as $row) {
	$row1[] = $row->getDimensionValues()[0]->getValue();
	$row2[] = $row->getMetricValues()[0]->getValue();
	if($row->getMetricValues()[0]->getValue() > $max) $max = $row->getMetricValues()[0]->getValue();
	$sum_count += $row->getMetricValues()[0]->getValue();
}


include_once "head.php";
?>

<table class="table row_tbl">
<colgroup>
	<col class="width-150p">
	<col class="width-150p">
	<col class="width-150p">
	<col style="width:850px; ">
</colgroup>
<tr>
    <th>시간</th>
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
	<td><?=$row1[$i]?>분 전</td>
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