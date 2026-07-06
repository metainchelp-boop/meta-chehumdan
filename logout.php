<?php // 로그아웃
include "path.php";

session_unset();
session_destroy();

set_cookie("ck_mb_no", "", 0);
set_cookie("ck_auto", "", 0);

if($url){
    $p = parse_url($url);
    if($p['scheme'] || $p['host']) {
        alert("도메인 지정이 되지 않았습니다");
    }
    $link = $url;
} else{
    $link = $nfor[path];
}
goto_url($link);
?>