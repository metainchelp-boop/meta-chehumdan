<?php
include_once "path.php";
header('Content-type: text/plain; charset=utf-8');
if($member[mb_admin] < 9){ echo 'super admin only'; exit; }
// '캠페인 진행 보드' 메뉴를 리뷰통합관리 맨 위로 (사이드바는 access_rank DESC 정렬 → 최댓값+1)
$m = sql_fetch("select max(access_rank+0) as m from nfor_access where gp_id='36'");
$top = (int)$m['m'] + 1;
$r = sql_query("update nfor_access set access_rank='$top' where gp_id='36' and access_file='review_board.php'");
echo "OK — '캠페인 진행 보드' access_rank -> ".$top." (맨 위로 이동)\n";
echo "관리자 화면 새로고침하면 리뷰통합관리 맨 위에 표시됩니다.";
@unlink(__FILE__);
