<?php
include_once "path.php";

if($mode=="appdown"){
	if(!$app_hp) json_return("휴대폰번호를 입력해주세요","app_hp");
	nfor_send("app_download","",$app_hp,$member['mb_no']);
	json_return("고객님의 핸드폰번호로 문자를 전송하였습니다.","ok");
}

goto_url($nfor['app_download_url']);
?>