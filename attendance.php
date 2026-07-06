<?php
$jwt_check = "1";

include_once "path.php";

$nfor[title] = "출석체크";

if($mode=="insert"){
	if(!$member[mb_no]) json_return("로그인하셔야 이용가능합니다");

	$at_datetime = date("Y-m-d");
	$chk_at = sql_fetch("select * from nfor_attendance where at_mb_no='$member[mb_no]' and date_format(at_datetime,'%Y-%m-%d') ='$at_datetime'");
	if($chk_at[at_mb_no]) json_return("이미 출석체크하였습니다");
	
	sql_query("insert nfor_attendance set at_mb_no='$member[mb_no]', at_datetime='$at_datetime'");

	$mb_attendance = sql_fetch("select count(*) as cnt from nfor_attendance where at_mb_no='$member[mb_no]'");
	sql_query("update nfor_member set mb_attendance='$mb_attendance[cnt]' where mb_no='$member[mb_no]'");
	
	if($config[cf_at_money]) insert_money($member[mb_no], $config[cf_at_money], "출석체크", "90");
	if($config[cf_at_point]) insert_point($member[mb_no], $config[cf_at_point], "출석체크", "100",$member[mb_no]."_".$ftimestamp);

	json_return("출석체크 하였습니다","ok");
}

if(!$year) $year = date('Y'); 
if(!$month) $month = date('m');

$time = strtotime($year.'-'.$month.'-01');
list($tday, $sweek) = explode('-', date('t-w', $time));  // 총 일수, 시작요일 
$tweek = ceil(($tday + $sweek) / 7);  // 총 주차 
$lweek = date('w', strtotime($year.'-'.$month.'-'.$tday));  // 마지막요일 


$ymd_prev = strtotime("-1 month",$time);
$ymd_next = strtotime("+1 month",$time);

$return[year_prev] = date("Y",$ymd_prev);
$return[month_prev] = date("m",$ymd_prev);

$return[year_next] = date("Y",$ymd_next);
$return[month_next] = date("m",$ymd_next);

$return[year] = $year;
$return[month] = $month;

for($n=1,$i=0; $i<$tweek; $i++){
	for($k=0; $k<7; $k++){
		$res = array();
		if(($i == 0 && $k < $sweek) || ($i == $tweek-1 && $k > $lweek)){
			$res[day] = "";
			$return["list"][] = $res;
			continue;
		}

		$res[day] = $n;

		$at_datetime = $year."-".sprintf("%02d",$month)."-".sprintf("%02d",$n);
		$chk_at = sql_fetch("select * from nfor_attendance where at_mb_no='$member[mb_no]' and date_format(at_datetime,'%Y-%m-%d') ='$at_datetime'");
		if($chk_at[at_mb_no]){
			$res[check] = "on";
		}
		$n++;
		$return["list"][] = $res;
	} 
}

if($json=="list"){
	json_return($nfor[title],"ok");
}

if(!$member[mb_no]) goto_url("login.php?url=".basename($_SERVER[PHP_SELF]));

include_once $nfor[skin_path].basename($_SERVER[PHP_SELF]);
?>