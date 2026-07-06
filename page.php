<?php // 페이지
include_once "path.php";




// 보안패치
$pg_code_array = array();
$que = sql_query("select * from nfor_page where 1");
while($row = sql_fetch_array($que)){
	$pg_code_array[$row['pg_code']] = $row['pg_code'];
}
if($pg_code){
	if (!array_key_exists($pg_code, $pg_code_array)) {
		$pg_code = "";
	}
}
// 보안패치





$pg_code = str_eng_under_number2($pg_code);

$page = sql_fetch("select * from nfor_page where pg_code='$pg_code'");

$nfor[title] = $page[pg_name];

if($json=="view"){
	$return = $page;
	json_return($nfor[title],"ok");
}

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>