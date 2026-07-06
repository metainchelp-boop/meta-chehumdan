<?php
include_once "path.php";

// ── 보안 패치(2026-06-11): 무인증 임의파일 다운로드 / 경로조작(../) / 민감폴더 유출 차단 ──
$folder = preg_replace('/[^A-Za-z0-9_]/', '', $folder);                 // 폴더명: 영숫자_ 만 허용(../·슬래시 제거)
$file   = basename(str_replace("\0", '', $file));                       // 파일명만 추출(경로·널바이트 제거)
$__blocked = array('jumin','bank','customer','cooperation','session');  // 개인정보 첨부는 file_download.php(권한검사) 경유 전용 → 직접 차단
if($folder === '' || $file === '' || in_array(strtolower($folder), $__blocked)) alert('잘못된 접근입니다.');

ob_end_clean();

$__base   = realpath("$nfor[path]/data");
$filepath = realpath("$nfor[path]/data/$folder/$file");
if($__base === false || $filepath === false || strpos($filepath, $__base) !== 0) alert('파일이 존재하지 않습니다.');   // data 폴더 밖 차단
if(!is_file($filepath)) alert('파일이 존재하지 않습니다.');

if(!$name){
	$name = $file;
}
$name = str_replace(array("\r","\n","\0",'"'), '', basename($name));    // 헤더 인젝션 방지

if(preg_match("/msie/i", $_SERVER['HTTP_USER_AGENT']) && preg_match("/5\.5/", $_SERVER['HTTP_USER_AGENT'])) {
    header("content-type: doesn/matter");
    header("content-length: ".filesize("$filepath"));
    header("content-disposition: attachment; filename=\"$name\"");
    header("content-transfer-encoding: binary");
} else {
    header("content-type: file/unknown");
    header("content-length: ".filesize("$filepath"));
    header("content-disposition: attachment; filename=\"$name\"");
    header("content-description: php generated data");
}
header("pragma: no-cache");
header("expires: 0");
flush();

$fp = fopen($filepath, 'rb');

$download_rate = 10;

while(!feof($fp)) {
    print fread($fp, round($download_rate * 1024));
    flush();
    usleep(1000);
}
fclose ($fp);
flush();
?>