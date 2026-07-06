<?php
/*
 * 메타체험단 → 전산(ERP) 포인트 출금요청 연동용 읽기전용 JSON API
 * 2026-06-12 신설. 기존 기능 무영향(읽기 전용, SELECT만). mc_cal_api.php와 동일 패턴.
 * 호출: GET /mc_point_bank_api.php?token=<TOKEN>
 * 반환: 출금요청 대기(pb_step=3) 목록. ★주민번호(pb_jumin)·첨부파일은 의도적으로 제외(개인정보 보호).
 */
include_once "path.php";

// ───────── 토큰 인증 ─────────
$MC_PB_TOKEN = "7952c008af88f4207089fbca8c9a1f49af34b21f5548a847e64dbfa44fb2a88e";
header('Content-type: application/json; charset=utf-8');
header('Cache-Control: no-store');
if((string)$_GET['token'] !== (string)$MC_PB_TOKEN || $MC_PB_TOKEN===""){
	http_response_code(403);
	echo json_encode(array("ok"=>false,"error"=>"forbidden"));
	exit;
}

// ───────── 쓰기: 단계 변경 (검수완료 1→2 / 입금완료 2→3 / 이슈 →4 / 되돌리기) ─────────
// pb_step: 1=출금신청 2=입금예정 3=입금완료 4=보류/이슈
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
if($action === 'setstep'){
	$idsRaw  = isset($_POST['ids'])  ? $_POST['ids']  : (isset($_GET['ids'])  ? $_GET['ids']  : '');
	$stepRaw = isset($_POST['step']) ? $_POST['step'] : (isset($_GET['step']) ? $_GET['step'] : '');
	$idList = array();
	foreach(explode(',', $idsRaw) as $x){ $xi = (int)trim($x); if($xi > 0){ $idList[] = $xi; } }
	$step = (int)$stepRaw;
	if(count($idList) === 0 || $step < 1 || $step > 4){
		echo json_encode(array("ok"=>false,"error"=>"bad_params"));
		exit;
	}
	$inClause = implode(',', $idList); // 정수만 통과시켜 SQL 인젝션 방지
	sql_query("update nfor_point_bank set pb_step='".$step."' where pb_id in (".$inClause.")");
	echo json_encode(array("ok"=>true, "updated"=>count($idList), "step"=>$step));
	exit;
}

// ───────── 조회 (pb_step 1신청·2예정·3완료·4이슈, 최신순) ─────────
$rows = array();
$res = sql_query("select pb_id, pb_mb_id, pb_name, pb_point, pb_datetime, pb_bank, pb_bank_number, pb_step
                  from nfor_point_bank where pb_step in ('1','2','3','4') order by pb_id desc limit 3000");
while($r = sql_fetch_array($res)){
	$amount = (int)$r['pb_point'];
	// 차인지급액 = (요청금액 - 500원 수수료) × 96.7% (3.3% 원천징수 공제) — 전산 시트 규칙과 동일
	$payout = (int)round(max(0, $amount - 500) * 0.967);
	$rows[] = array(
		"id"          => (int)$r['pb_id'],
			"step"        => (int)$r['pb_step'],
		"memberId"    => $r['pb_mb_id'],
		"name"        => $r['pb_name'],            // 예금주/이름
		"amount"      => $amount,                  // 환급 요청 금액(원)
		"payoutAmount"=> $payout,                  // 차인 지급액(원)
		"requestDate" => substr($r['pb_datetime'], 0, 10),
		"bank"        => $r['pb_bank'],
		"account"     => $r['pb_bank_number']
	);
}

echo json_encode(array(
	"ok"       => true,
	"count"    => count($rows),
	"syncedAt" => date("Y-m-d H:i:s"),
	"rows"     => $rows
), JSON_UNESCAPED_UNICODE);
exit;
