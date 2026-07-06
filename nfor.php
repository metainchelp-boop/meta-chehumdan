<?php
ini_set('display_errors',0); // 보안(2026-06-11): 운영 에러화면 노출 차단(경로·쿼리 유출 방지). 디버그시 1

//error_reporting(0);
error_reporting(E_ALL ^ E_NOTICE);

header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');

if(!isset($set_time_limit)) $set_time_limit = 0;
@set_time_limit($set_time_limit);

if(isset($HTTP_POST_VARS) && !isset($_POST)){
	$_POST   = &$HTTP_POST_VARS;
	$_GET    = &$HTTP_GET_VARS;
	$_SERVER = &$HTTP_SERVER_VARS;
	$_COOKIE = &$HTTP_COOKIE_VARS;
	$_ENV    = &$HTTP_ENV_VARS;
	$_FILES  = &$HTTP_POST_FILES;

    if(!isset($_SESSION)){
		$_SESSION = &$HTTP_SESSION_VARS;
	}
}

if(!get_magic_quotes_gpc()){
	if(is_array($_GET)){
		while(list($k, $v) = each($_GET)){
			if(is_array($_GET[$k])){
				while(list($k2, $v2) = each($_GET[$k])){
					$_GET[$k][$k2] = @addslashes($v2);
				}
				@reset($_GET[$k]);
			} else{
				$_GET[$k] = @addslashes($v);
			}
		}
		@reset($_GET);
	}

	if(is_array($_POST)){
		while(list($k, $v) = each($_POST)){
			if(is_array($_POST[$k])){
				while(list($k2, $v2) = each($_POST[$k])){
					$_POST[$k][$k2] = @addslashes($v2);
				}
				@reset($_POST[$k]);
			} else{
				$_POST[$k] = @addslashes($v);
			}
		}
		@reset($_POST);
	}

	if(is_array($_COOKIE)){
		while(list($k, $v) = each($_COOKIE)){
			if(is_array($_COOKIE[$k])){
				while(list($k2, $v2) = each($_COOKIE[$k])){
					$_COOKIE[$k][$k2] = @addslashes($v2);
				}
				@reset($_COOKIE[$k]);
			} else{
				$_COOKIE[$k] = @addslashes($v);
			}
		}
		@reset($_COOKIE);
	}
}

if($_GET['path'] || $_POST['path'] || $_COOKIE['path']) {
    unset($_GET['path']);
    unset($_POST['path']);
    unset($_COOKIE['path']);
    unset($path);
}

$ext_arr = array('PHP_SELF', '_ENV', '_GET', '_POST', '_FILES', '_SERVER', '_COOKIE', '_SESSION', '_REQUEST', 'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_POST_FILES', 'HTTP_SERVER_VARS', 'HTTP_COOKIE_VARS', 'HTTP_SESSION_VARS', 'GLOBALS');
$ext_cnt = count($ext_arr);
for($i=0; $i<$ext_cnt; $i++){
    if(isset($_GET[$ext_arr[$i]])) unset($_GET[$ext_arr[$i]]);
}

@extract($_GET);
@extract($_POST);
@extract($_SERVER);

$nfor = array();
$config = array();
$member = array();

if(!$path || preg_match("/:\/\//", $path)) die("path error");

$nfor[path] = $path;

unset($path);

include_once "$nfor[path]/config.php";
include_once "$nfor[path]/lib/function.lib.php";
include_once "$nfor[path]/lib/shop.lib.php";
include_once "$nfor[path]/lib/api.lib.php";
include_once "$nfor[path]/lib/form.lib.php";
include_once "$nfor[path]/lib/campaign.lib.php";
include_once "$nfor[path]/lib/kakaotalk.lib.php";

//20240621 함수추가
include_once "$nfor[path]/lib/z_function.lib.php";

if(!$nfor['url']){
    $nfor['url'] = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'];
    $dir = dirname($HTTP_SERVER_VARS["PHP_SELF"]);
    if(!file_exists("nfor.php"))
        $dir = dirname($dir);
    $cnt = substr_count($nfor[path], "..");
    for ($i=2; $i<=$cnt; $i++)
        $dir = dirname($dir);
    $nfor[url] .= $dir;
}
$nfor[url] = strtr($nfor[url], "\\", "/");
$nfor[url] = preg_replace("/\/$/", "", $nfor[url]);









$connect_db = mysqli_connect($nfor[sql_host], $nfor[sql_user], $nfor[sql_password], $nfor[sql_db]);
if(mysqli_connect_errno()){
    printf("DB 연결 실패: %s\n", mysqli_connect_error());
    exit();
}

 mysqli_set_charset($connect_db, "utf8");








ini_set("session.use_trans_sid", 0);
ini_set("url_rewriter.tags","");

session_save_path("$nfor[path]/data/session");

if(isset($SESSION_CACHE_LIMITER)){
	@session_cache_limiter($SESSION_CACHE_LIMITER);
} else{
	@session_cache_limiter("no-cache, must-revalidate");
}

$config = sql_fetch("select * from nfor_config");

/* 체험단 솔루션 */
$campaign_config = sql_fetch("select * from nfor_campaign_config");
$config = array_merge($config,$campaign_config);
/* 체험단 솔루션 */

ini_set("session.cache_expire", 180);
ini_set("session.gc_maxlifetime", 10800);
ini_set("session.gc_probability", 1);
ini_set("session.gc_divisor", 100);

session_set_cookie_params(0, "/");
ini_set("session.cookie_domain", $nfor[cookie_domain]);

//@session_start();

















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



















if($_REQUEST[PHPSESSID] && $_REQUEST[PHPSESSID] != session_id()) goto_url("$nfor[path]/logout.php");

$qstr = "";

if(isset($page)){
    $page = (int)$page;
	if($page<0){
		$page = $page*-1;
	}
    $qstr .= '&page='.urlencode($page);
}


if(isset($keyword_type)){
	$qstr .= "&keyword_type=".urlencode($keyword_type);
}


if(isset($keyword)){
	$qstr .= "&keyword=".urlencode($keyword);
}

if(isset($period_type)){
	$qstr .= "&period_type=".urlencode($period_type);
}

if(isset($period_sdate)){
	$qstr .= "&period_sdate=".urlencode($period_sdate);
}

if(isset($period_edate)){
	$qstr .= "&period_edate=".urlencode($period_edate);
}


if(isset($sort)){
	$qstr .= "&sort=".urlencode($sort);
}

if(isset($page_row)){
	$page_row = (int)$page_row;
	if($page_row<0){
		$page_row = $page_row*-1;
	}
	$qstr .= "&page_row=".urlencode($page_row);
}

if(isset($url)){
	$urlencode = urlencode($url);
} else{
    $urlencode = urlencode($_SERVER[REQUEST_URI]);
}


if(strpos($HTTP_USER_AGENT, "NFOR_APP") === false){
    $nfor[is_app] = "0";
} else{
    $nfor[is_app] = "1";
}


/* jwt토큰확인 */
require 'vendor/autoload.php';

if($jwt_check){
	$jwt = getBearerToken();
}

if($jwt){
	try {
		$decoded = JWT::decode($jwt, $nfor[jwt_key], array('HS256'));
		$_SESSION[ss_mb_no] = $decoded->mb_no;
	} catch (Exception $e) {
		$return[jwt_error] = $e->getMessage();
		json_return("인증에 실패하였습니다","jwt_error");
	}
}
/* jwt토큰확인 */


if($_SESSION[ss_mb_no]){

    $member = member("mb_no",$_SESSION[ss_mb_no]);

	if($member[mb_no]=="1") $is_admin = "super";

	$is_member = 1;
	$is_guest = 0;

	// 자동 로그아웃
	if(!$nfor[is_app]){
		if($member[mb_admin]){ // 관리자
			if($config[cf_logout_admin_use]=="1" and $_SESSION[last_access_time]){
				if(time() > strtotime("+{$config[cf_logout_admin_time]} minutes", $_SESSION[last_access_time])){
					logout();
					alert("장시간 활동이 없어 보안설정에 따라 로그아웃되었습니다","$nfor[path]/index.php");
				}
			}
		} else{ // 일반회원
			if($config[cf_logout_member_use]=="1" and $_SESSION[last_access_time]){
				if(time() > strtotime("+{$config[cf_logout_member_time]} minutes", $_SESSION[last_access_time])){
					logout();
					alert("장시간 활동이 없어 보안설정에 따라 로그아웃되었습니다","$nfor[path]/index.php");
				}
			}
		}
		$_SESSION[last_access_time] = time();


		if($member[mb_secession]){
			logout();
			alert("탈퇴처리된 회원입니다","$nfor[path]/index.php");
		}


		if($member[mb_asign] == "2"){
			logout();
			alert("관리자 승인후 이용가능합니다","$nfor[path]/index.php");
		}

	}
	
	// 로그인 정보 업데이트
    if(substr($member[mb_login_datetime], 0, 10) != $nfor[ymd]){
        sql_query("update nfor_member set mb_login_datetime=NOW(), mb_login_ip='$_SERVER[REMOTE_ADDR]', mb_login_count=mb_login_count+1 where mb_no='$member[mb_no]'");
    }

	// 비밀번호 변경안내 체크
	if(strtotime("+{$config[cf_password_day]} {$config[cf_password_day_type]}",strtotime($member[mb_password_change_datetime])) < time()){
		$nfor[password_change_popup] = "1"; //패스워드변경창 띄움
	}

	if($nfor[password_change_popup] and strtotime("+{$config[cf_password_day_again]} {$config[cf_password_day_again_type]}",strtotime($member[mb_password_next_datetime])) > time()){
		$nfor[password_change_popup] = "";
	}


} else{
	$is_member = 0;
	$is_guest = 1;
	$member[mb_level] = 1;

	if(!$nfor[is_app]){
		if($tmp_mb_no = get_cookie("ck_mb_no")){
			$row = sql_fetch(" select * from nfor_member where mb_no = '{$tmp_mb_no}' ");
			$key = md5($_SERVER['HTTP_USER_AGENT'] . $row['mb_password']);
			$key_chk2 = md5($row['mb_no'] . $row['mb_password']);
			$tmp_key = get_cookie("ck_auto");
			if ($tmp_key == $key && $tmp_key){
				$_SESSION[ss_mb_no] = $tmp_mb_no;
				echo "<script type='text/javascript'> window.location.reload(); </script>";
				exit;
			}
			if($tmp_key == $key_chk2 && $tmp_key){
				$_SESSION[ss_mb_no] = $tmp_mb_no;
				echo "<script type='text/javascript'> window.location.reload(); </script>";
				exit;
			}
			unset($row);
		}
	}
}

if($member[mb_no]){

} else{

	if(!$_SESSION[guest_no]){
		$micro_exp = explode(" ", microtime());
		$_SESSION[guest_no] = $micro_exp[1].rand("1","9").substr($micro_exp[0],2).rand("1","9").str_number($_SERVER[REMOTE_ADDR]).rand("1","9");
		$_SESSION[guest_recent_save] = "1";
	}
}


if(!$_SESSION[cart_id]){
	$micro_exp = explode(" ", microtime());
    $_SESSION[cart_id] = $micro_exp[1].rand("1","9").substr($micro_exp[0],2).rand("1","9").str_number($_SERVER[REMOTE_ADDR]).rand("1","9");
}

require_once "$nfor[path]/lib/mail.lib.php";
include_once "$nfor[path]/lib/thumb.lib.php";



if(mobile_check() == "mobile"){ 
    $nfor[skin_path] = $nfor[m_skin_path];
	$is_mobile = "1";
	$config[cf_write_pages] = "4";
}

if(substr($HTTP_HOST,0,4)=="www."){
	$host_url = substr($HTTP_HOST,4);
} else{
	$host_url = $HTTP_HOST;
}
$api = sql_fetch("select * from nfor_api where api_domain='$host_url'");
if(!$api[api_domain]){ // 일치하는 도메인이 없을경우 아무거나 불러옴
	$api = sql_fetch("select * from nfor_api");
}

// 사용자 접속 IP 제한
if(!$nfor[is_app]){
	if($config[cf_shop_ip_use]=="1" and $config[cf_shop_ip]<>"null" and in_array($_SERVER[REMOTE_ADDR], json_decode($config[cf_shop_ip]))) die("접근이 차단된 아이피입니다 $_SERVER[REMOTE_ADDR]");
	$chk_ip = sql_fetch("select bd_ip from nfor_bad_ip where bd_ip='$_SERVER[REMOTE_ADDR]'");
	if($chk_ip[bd_ip]) die("접근이 차단된 아이피입니다 $_SERVER[REMOTE_ADDR]");
}
// 사용자 접속 IP 제한


// 회원 환경설정
$member_config = sql_fetch("select * from nfor_member_config");

if(stripos($_SERVER['HTTP_USER_AGENT'], "iPod") or stripos($_SERVER['HTTP_USER_AGENT'], "iPhone") or stripos($_SERVER['HTTP_USER_AGENT'], "iPad")){
	$nfor[os_type] = "ios";
	$nfor[app_download_url] = $config[cf_ios_url];
	$nfor[app_schema] = $config[cf_ios_schema];
	$nfor[app_package] = $config[cf_ios_package];
	$nfor[app_use] = $config[cf_ios_use];
} else{
	$nfor[os_type] = "android";
	$nfor[app_download_url] = $config[cf_android_url];
	$nfor[app_schema] = $config[cf_android_schema];
	$nfor[app_package] = $config[cf_android_package];
	$nfor[app_use] = $config[cf_android_use];
}




if($_SERVER['REQUEST_SCHEME']=="https"){
	$nfor[is_https] = "1";
} else{
	$nfor[is_https] = "0";
}

// 페이징 설정
$menu_file = basename($_SERVER[PHP_SELF]);
$menu_config = sql_fetch("select * from nfor_menu where menu_file='$menu_file'");
if($menu_config[menu_id]){
	if($is_mobile){
		$menu_config[menu_rows] = $menu_config[menu_mobile_rows];
		$menu_config[menu_scroll] = $menu_config[menu_mobile_scroll];
		$menu_config[menu_limit] = $menu_config[menu_mobile_limit];
	} else{
		$menu_config[menu_rows] = $menu_config[menu_pc_rows];
		$menu_config[menu_scroll] = $menu_config[menu_pc_scroll];
		$menu_config[menu_limit] = $menu_config[menu_pc_limit];
	}	
} else{
	$menu_config[menu_rows] = "15";
	$menu_config[menu_scroll] = "15";
	$menu_config[menu_limit] = "0";
}

$nfor_sql = "";

define("IMJUNA",TRUE);

if($HTTP_HOST<>"meta-chehumdan.com"){
	goto_url("https://meta-chehumdan.com");
	exit;
}

/*
if($REMOTE_ADDR=="183.109.26.7"){
	$REMOTE_ADDR="211.44.181.152";
}
*/


$apply_good = "1";
$apply_goods = "1";



?>