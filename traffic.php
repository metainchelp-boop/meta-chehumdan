<?php
$nfor[path] = ".";

include_once "$nfor[path]/config.php";

ini_set("session.cache_expire", 180);
ini_set("session.gc_maxlifetime", 10800);
ini_set("session.gc_probability", 1);
ini_set("session.gc_divisor", 100);

session_set_cookie_params(0, "/");
ini_set("session.cookie_domain", $nfor[cookie_domain]);

// Chrome 80 대응
// https://developers-kr.googleblog.com/2020/01/developers-get-ready-for-new.html?fbclid=IwAR0wnJFGd6Fg9_WIbQPK3_FxSSpFLqDCr9bjicXdzy--CCLJhJgC9pJe5ss
if(!function_exists('session_start_samesite')) {
	function session_start_samesite($options = array())
	{
		$res = @session_start($options);

		// IE 브라우저 또는 엣지브라우저 일때는 secure; SameSite=None 을 설정하지 않습니다.
		if( preg_match('/Edge/i', $_SERVER['HTTP_USER_AGENT']) || preg_match('~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT']) || preg_match('~Trident/7.0(; Touch)?; rv:11.0~',$_SERVER['HTTP_USER_AGENT']) ){
			return $res;
		}

		$headers = headers_list();
		krsort($headers);
		foreach ($headers as $header) {
			if (!preg_match('~^Set-Cookie: PHPSESSID=~', $header)) continue;
			$header = preg_replace('~; secure(; HttpOnly)?$~', '', $header) . '; secure; SameSite=None';
			header($header, false);
			break;
		}
		return $res;
	}
}

session_start_samesite();



include_once "$nfor[path]/lib/function.lib.php";


if(!$_SESSION["traffic_{$_GET[code]}"]){
	$_SESSION["traffic_{$_GET[code]}"] = TRUE;

	$connect_db = mysqli_connect($nfor[sql_host], $nfor[sql_user], $nfor[sql_password], $nfor[sql_db]);
	if(mysqli_connect_errno()){
		exit;
	}

	mysqli_set_charset($connect_db, "utf8");

	$exp = explode("||",nfor_decrypt($_GET[code]));

	$table = "nfor_traffic_".$exp[2];

	$tr_cp_id = $exp[1];

	$tr_mb_no = $exp[0];

	$tr_rv_id = $exp[3];


	$tr_device = mobile_check();

	$tr_ip = $_SERVER["REMOTE_ADDR"];

	$tr_referer = $_SERVER["HTTP_REFERER"];

	$tr_agent = $_SERVER["HTTP_USER_AGENT"];

	$tr_browser = nfor_get_browser($tr_agent);

	$tr_os = nfor_get_os($tr_agent);

	sql_query("insert $table set tr_cp_id='$tr_cp_id', tr_mb_no='$tr_mb_no', tr_device='$tr_device', tr_ip='$tr_ip', tr_referer='$tr_referer', tr_agent='$tr_agent', tr_browser='$tr_browser', tr_os='$tr_os', tr_date=NOW(), tr_datetime=NOW(), tr_rv_id='$tr_rv_id'");

	// 유입경로 기록용 — 어느 리뷰글(rv_id)을 통해 들어왔는지 세션 저장 2026
	$_SESSION["mc_inflow_".$tr_cp_id] = $tr_rv_id;
	$_SESSION["mc_insrc_".$tr_cp_id]  = "pixel";
}


header('Content-type: image/png');

$rewidth    = "280";
$reheight   = "60";
$dst = @imageCreatetrueColor($rewidth,$reheight);
@imagetruecolortopalette($dst, false, 255); 
$back = @imagecolorallocatealpha($dst, 255, 255, 255, 127);  // 투명배경을 씌운다   
@imagefilledrectangle($dst, 0, 0, $rewidth,$reheight, $back);

$src = imagecreatefrompng('./img/traffic.png');

@imagecopyResampled($dst, $src,0,0,0,0,$rewidth,$reheight,ImageSX($src),ImageSY($src));
@Imagepng($dst,null,0);

imagedestroy($src);









?>