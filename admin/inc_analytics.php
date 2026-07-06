<?php
//include_once "../vendor7/autoload.php";

if(!$api['api_google_analytics_propertyid'] or !$api['api_google_analytics_json']) alert("구글애널리틱스 API설정후 이용가능합니다","api_list.php");

if(!$_GET['period_sdate']) $_GET['period_sdate'] = date("Y-m-d"); // ,strtotime("-30day")
if(!$_GET['period_edate']) $_GET['period_edate'] = date("Y-m-d");

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

$response = $client->runReport([
    'property' => 'properties/' . $api['api_google_analytics_propertyid'],
    'dateRanges' => [
        new DateRange([
            'start_date' => $_GET['period_sdate'],
            'end_date' => $_GET['period_edate'],
        ]),
    ],
    'dimensions' => [new Dimension(
        [
            'name' => $dimensions,
        ]
    ),
    ],
    'metrics' => [new Metric(
        [
            'name' => 'sessions',
        ]
    )
    ],
	'orderBys' => [
        new OrderBy([
            'dimension' => new OrderBy\DimensionOrderBy([
                'dimension_name' => $dimensions,
                'order_type' => OrderBy\DimensionOrderBy\OrderType::NUMERIC
            ]),
            'desc' => true,
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

?>