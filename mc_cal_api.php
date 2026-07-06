<?php
/*
 * 메타체험단 → 전산(ERP) 캠페인 일정 연동용 읽기전용 JSON API
 * 2026-06-08 신설. 기존 기능 무영향(읽기 전용, SELECT만).
 * 호출: GET /mc_cal_api.php?token=<TOKEN>&from=YYYY-MM-DD&to=YYYY-MM-DD[&md_no=N]
 * 반환: 해당 기간에 걸친 캠페인의 일정 이벤트 목록(캠페인 1건당 최대 6개 일정).
 */
include_once "path.php";

// ───────── 토큰 인증 ─────────
$MC_CAL_TOKEN = "7725253d72ccb7213827956a64977aaeeedf1377dffbf207114aafb01f8a3e61";
header('Content-type: application/json; charset=utf-8');
if((string)$_GET['token'] !== (string)$MC_CAL_TOKEN || $MC_CAL_TOKEN===""){
	http_response_code(403);
	echo json_encode(array("ok"=>false,"error"=>"forbidden"));
	exit;
}

// ───────── 파라미터 ─────────
$from = preg_replace('/[^0-9\-]/','', $_GET['from']);   // YYYY-MM-DD
$to   = preg_replace('/[^0-9\-]/','', $_GET['to']);
$md   = preg_replace('/[^0-9]/','',  $_GET['md_no']);    // 숫자 mb_no (선택)

if(!preg_match('/^\d{4}-\d{2}-\d{2}$/',$from)) $from = date("Y-m-01");
if(!preg_match('/^\d{4}-\d{2}-\d{2}$/',$to))   $to   = date("Y-m-t", strtotime($from));
// 과도범위 방지: 최대 약 100일
if(strtotime($to) - strtotime($from) > 100*86400){ $to = date("Y-m-d", strtotime($from)+100*86400); }
if(strtotime($to) < strtotime($from)){ $tmp=$from; $from=$to; $to=$tmp; }

// 일정 컬럼 → 이벤트 종류 매핑 (campaign_calendar.php와 동일한 6종)
$dateCols = array(
	"cp_sdatetime"          => array("apply_start","신청 시작"),
	"cp_edatetime"          => array("apply_end",  "신청 마감"),
	"cp_pick_datetime"      => array("pick",       "선정자 발표"),
	"cp_contents_sdatetime" => array("review_start","리뷰등록 시작"),
	"cp_contents_edatetime" => array("review_end", "리뷰등록 마감"),
	"cp_result_datetime"    => array("result",     "결과 발표"),
);

// ───────── 조회 (campaign_calendar.php 로직 미러: 승인상태 불문 전체, 기간만 필터) ─────────
$where = " where 1 ";
if($md !== ""){ $where .= " and cp_md_no='".addslashes($md)."' "; }
$ors = array();
foreach($dateCols as $col=>$v){ $ors[] = "(date(`$col`) between '$from' and '$to')"; }
$where .= " and (".implode(" or ",$ors).") ";

$cols = "cp_id,cp_subject,cp_description,cp_md_no,cp_md_name,cp_supply_no,cp_recruit,cp_order,cp_asign,".implode(",",array_keys($dateCols));
$res = sql_query("select $cols from nfor_campaign $where order by cp_id desc limit 3000");

// 전산에서 열 수 있도록 절대 URL 사용 (서버상 $nfor[path]는 "."이라 상대경로가 됨)
$BASE = (isset($config['cf_url']) && $config['cf_url']) ? rtrim($config['cf_url'],"/") : "https://meta-chehumdan.com";

$events = array();
$nameCache = array();
while($row = sql_fetch_array($res)){
	// 담당자 이름: 등록폼에서 직접 입력한 cp_md_name 우선, 없으면 cp_md_no(회원번호)→이름 해석
	$mdName = trim((string)$row['cp_md_name']);
	if($mdName === "" && (string)$row['cp_md_no'] !== ""){
		if(!isset($nameCache[$row['cp_md_no']])){
			$m = sql_fetch("select mb_name from nfor_member where mb_no='".addslashes($row['cp_md_no'])."'");
			$nameCache[$row['cp_md_no']] = $m['mb_name'];
		}
		$mdName = $nameCache[$row['cp_md_no']];
	}
	foreach($dateCols as $col=>$v){
		$dt = $row[$col];
		if(!$dt || substr($dt,0,10)=="0000-00-00") continue;
		$d = substr($dt,0,10);
		if($d < $from || $d > $to) continue;
		$events[] = array(
			"cp_id"      => $row['cp_id'],
			"date"       => $d,
			"time"       => substr($dt,11,5),
			"type"       => $v[0],
			"type_label" => $v[1],
			"subject"    => $row['cp_subject'],
			"md_no"      => (string)$row['cp_md_no'],
			"md_name"    => $mdName,
			"asign"      => (int)$row['cp_asign'],
			"recruit"    => (int)$row['cp_recruit'],
			"order"      => (int)$row['cp_order'],
			"report_url" => $BASE."/admin/campaign_report.php?cp_id=".$row['cp_id'],
			"front_url"  => $BASE."/campaign.php?cp_id=".$row['cp_id'],
		);
	}
}

echo json_encode(array(
	"ok"=>true, "from"=>$from, "to"=>$to, "count"=>count($events), "events"=>$events
), JSON_UNESCAPED_UNICODE);
?>
