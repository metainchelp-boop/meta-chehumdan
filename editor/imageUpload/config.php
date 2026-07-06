<?php
include_once "path.php";
// ---------------------------------------------------------------------------
// 이미지가 저장될 디렉토리의 전체 경로를 설정합니다.
// 끝에 슬래쉬(/)는 붙이지 않습니다.
// 주의: 이 경로의 접근 권한은 쓰기, 읽기가 가능하도록 설정해 주십시오.



@mkdir("$nfor[path]/data/editor/", 0707);
@chmod("$nfor[path]/data/editor/", 0707);

$ym = date("Ym");

define("SAVE_DIR", "$nfor[path]/data/editor/$ym");

@mkdir(SAVE_DIR, 0707);
@chmod(SAVE_DIR, 0707);

# 위에서 설정한 'SAVE_DIR'의 URL을 설정합니다.
# 끝에 슬래쉬(/)는 붙이지 않습니다.

define("SAVE_URL", "$nfor[url]/data/editor/$ym");


?>
