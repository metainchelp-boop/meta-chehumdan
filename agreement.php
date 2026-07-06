<?php
include_once "path.php";


// 보안패치
$ag_code = array();
$que = sql_query("select * from nfor_agreement where 1");
while($row = sql_fetch_array($que)){
	$ag_code[$row['ag_code']] = $row['ag_code'];
}
if($code){
	if (!array_key_exists($code, $ag_code)) {
		$code = "";
	}
}
// 보안패치




$code = str_eng_under_number2($code);


if(!$code) $code = "agreement";

$agreement = sql_fetch("select * from nfor_agreement where ag_code='$code'");

$nfor[title] = $agreement[ag_name];

$que = sql_query("select * from nfor_agreement where ag_use='1' and ag_group='2'");
while($row = sql_fetch_array($que)){
	$admin[ag_name][$row[ag_code]] = $row[ag_name];
}

if($json=="form"){
	$return["form"] = $admin;
	json_return($nfor[title], "ok");
}

if($json=="view"){
	$return = $agreement;
	json_return($nfor[title],"ok");
}

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>