<?php
include_once "path.php";

if(!$is_admin) alert("잘못된 접근입니다");

ob_end_clean();

$filepath = $nfor[path].'/data/'.$dirname.'/'.$filename;
$filepath = addslashes($filepath);
if (!is_file($filepath) || !file_exists($filepath)) alert('파일이 존재하지 않습니다.');

$original = iconv('utf-8', 'euc-kr', $orgname);

if(preg_match("/msie/i", $_SERVER['HTTP_USER_AGENT']) && preg_match("/5\.5/", $_SERVER['HTTP_USER_AGENT'])) {
    header("content-type: doesn/matter");
    header("content-length: ".filesize("$filepath"));
    header("content-disposition: attachment; filename=\"$original\"");
    header("content-transfer-encoding: binary");
} else {
    header("content-type: file/unknown");
    header("content-length: ".filesize("$filepath"));
    header("content-disposition: attachment; filename=\"$original\"");
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