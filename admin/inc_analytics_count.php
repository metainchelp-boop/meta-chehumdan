<?php
if(!$api['api_google_analytics_json'] or !$api['api_google_analytics_json']) alert("구글애널리틱스 API설정후 이용가능합니다","api_list.php");

$json_path = "/data/google/".$api['api_google_analytics_json'];

if(!$_GET['period_sdate']) $_GET['period_sdate'] = date("Y-m-d");
if(!$_GET['period_edate']) $_GET['period_edate'] = date("Y-m-d");

$exp_path = explode("/admin", __DIR__ );
define("__GOOGLE_PATH__", $exp_path[0]);

$KEY_FILE_LOCATION = __GOOGLE_PATH__.$json_path;

$client = new Google_Client();
$client->setApplicationName("Hello Analytics Reporting");
$client->setAuthConfig($KEY_FILE_LOCATION);
$client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
$analytics = new Google_Service_AnalyticsReporting($client);

$dateRange = new Google_Service_AnalyticsReporting_DateRange();
$dateRange->setStartDate($_GET['period_sdate']);
$dateRange->setEndDate($_GET['period_edate']);

$sessions = new Google_Service_AnalyticsReporting_Metric();
$sessions->setExpression("ga:sessions");

$browser = new Google_Service_AnalyticsReporting_Dimension();
$browser->setName($setName);

$request = new Google_Service_AnalyticsReporting_ReportRequest();
$request->setViewId($api['api_google_analytics_viewid']);
$request->setDateRanges($dateRange);
$request->setDimensions(array($browser));
$request->setMetrics(array($sessions));

$ordering = new Google_Service_AnalyticsReporting_OrderBy();
if(!$setFieldName) $setFieldName = "ga:sessions";
$ordering->setFieldName($setFieldName);
$ordering->setOrderType("VALUE");   
$ordering->setSortOrder("DESCENDING");
$request->setOrderBys($ordering);

$body = new Google_Service_AnalyticsReporting_GetReportsRequest();
$body->setReportRequests( array( $request) );

$response = $analytics->reports->batchGet( $body );
?>