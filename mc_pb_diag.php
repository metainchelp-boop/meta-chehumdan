<?php
/* 1회용 진단(운영토큰 미사용·개인정보 미반환): nfor_point_bank 단계별 건수/날짜범위. 호출 후 자기삭제. */
include_once "path.php";
$DIAG_K = "pbstepdiag2026check";
header('Content-type: application/json; charset=utf-8');
header('Cache-Control: no-store');
if((string)$_GET['k'] !== $DIAG_K){ http_response_code(403); echo json_encode(array("ok"=>false)); @unlink(__FILE__); exit; }
$rows = array();
$res = sql_query("select pb_step, count(*) cnt, min(pb_datetime) mn, max(pb_datetime) mx from nfor_point_bank group by pb_step order by pb_step");
while($r = sql_fetch_array($res)){
  $rows[] = array("step"=>$r['pb_step'], "count"=>(int)$r['cnt'], "min_date"=>$r['mn'], "max_date"=>$r['mx']);
}
echo json_encode(array("ok"=>true, "today"=>date("Y-m-d"), "steps"=>$rows), JSON_UNESCAPED_UNICODE);
@unlink(__FILE__);
exit;
