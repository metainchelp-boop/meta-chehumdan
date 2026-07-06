<?php
include_once "path.php";

$nfor[title] = "배너";

if(!$bn_code) json_return("배너코드가 전달되지 않았습니다","bn_code");

$bn_code_exp = explode("||",$bn_code);
for($e=0; $e<count($bn_code_exp); $e++){

	$result = sql_query("select * from nfor_banner where bn_use='1' and bn_code='{$bn_code_exp[$e]}' and ((bn_period_use='1') or (bn_period_use='2' and bn_sdatetime <='$nfor[ymdhis]' and bn_edatetime >='$nfor[ymdhis]')) order by bn_rank desc");
	for($i=0; $row=sql_fetch_array($result); $i++){
		$res = array();
		$res[bn_alt] = $row[bn_alt];
		$res[bn_img] = $nfor[url]."/data/banner/".$row[bn_img];	
		$res[bn_href] = $row[bn_href];
		$return[$bn_code_exp[$e]]["list"][] = $res;
	}
	$return[$bn_code_exp[$e]]["total_count"] = $i;

}

if($json=="list"){
	json_return($nfor[title],"ok");
}

?>