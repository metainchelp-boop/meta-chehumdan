<?php
/*
 * 메타체험단 → 메타아이앤씨 광고센터(대시보드) 연동용 읽기전용 JSON API
 * 2026-06-12 신설. 기존 사이트 기능 무영향(SELECT 전용).
 * ★회원(리뷰어) 개인정보는 반환하지 않음 — 광고주(업체)별 "캠페인 결과 요약"만 제공.
 *
 * 인증: 모든 요청에 ?token=<TOKEN> 필요(mc_cal_api와 동일 토큰 재사용).
 *
 * 엔드포인트:
 *   ?mode=advertisers
 *       → 광고주(공급사) 목록: supply_no, 표시명, 캠페인수, 최근 캠페인 제목.
 *         (대시보드에서 어떤 업체를 어느 광고주에 연결할지 고르는 용도)
 *   ?mode=campaigns&supply_no=N            (또는 &cp_ids=1,2,3  또는 &q=검색어)
 *       → 해당 광고주의 캠페인별 결과 요약(모집/신청/선정/리뷰등록/확인 + 유입매체 + 일정).
 *   ?mode=summary&supply_no=N              (또는 &cp_ids= / &q=)
 *       → 광고주 단위 합계(캠페인수, 총 모집/신청/리뷰게시, 평균 전환율, 유입매체 합계).
 */
include_once "path.php";
header('Content-type: application/json; charset=utf-8');

// ───────── 토큰 인증 ─────────
$TOKEN = "7725253d72ccb7213827956a64977aaeeedf1377dffbf207114aafb01f8a3e61";
if ((string)$_GET['token'] !== (string)$TOKEN || $TOKEN === "") {
    http_response_code(403);
    echo json_encode(array("ok" => false, "error" => "forbidden"));
    exit;
}

$mode = preg_replace('/[^a-z]/', '', (string)$_GET['mode']);
if ($mode === "") { $mode = "advertisers"; }

$BASE = (isset($config['cf_url']) && $config['cf_url']) ? rtrim($config['cf_url'], "/") : "https://meta-chehumdan.com";

// ───────── 대상 캠페인 집합 결정 (supply_no | cp_ids | q) ─────────
function target_where() {
    $supply = preg_replace('/[^0-9]/', '', (string)$_GET['supply_no']);
    $ids    = preg_replace('/[^0-9,]/', '', (string)$_GET['cp_ids']);
    $q      = trim((string)$_GET['q']);
    if ($supply !== "") {
        return " cp_supply_no='" . addslashes($supply) . "' ";
    }
    if ($ids !== "") {
        $arr = array_filter(array_map('intval', explode(',', $ids)));
        if (count($arr)) { return " cp_id in (" . implode(',', $arr) . ") "; }
    }
    if ($q !== "") {
        return " cp_subject like '%" . addslashes($q) . "%' ";
    }
    return "";  // 대상 미지정
}

// rv_step 펀널 한 캠페인 집합에 대해 집계 (campaign_report.php 로직과 동일)
// rv_step: 1신청 2선정(리뷰대기) 3리뷰제출 4등록확인(완료) 5미선정 6선정후취소
function funnel_for($cpIds) {
    $res = array("applied" => 0, "selected" => 0, "reviewed" => 0, "confirmed" => 0);
    if (!count($cpIds)) return $res;
    $in = implode(',', array_map('intval', $cpIds));
    $r = sql_query("select rv_cp_id, rv_step, count(*) c from nfor_review where rv_cp_id in ($in) and rv_delete='0' group by rv_cp_id, rv_step");
    $per = array();
    while ($w = sql_fetch_array($r)) {
        $cid = $w['rv_cp_id']; $st = (string)$w['rv_step']; $c = (int)$w['c'];
        if (!isset($per[$cid])) $per[$cid] = array("applied"=>0,"selected"=>0,"reviewed"=>0,"confirmed"=>0);
        $per[$cid]['applied'] += $c;
        if ($st=='2'||$st=='3'||$st=='4') $per[$cid]['selected'] += $c;
        if ($st=='3'||$st=='4')           $per[$cid]['reviewed'] += $c;
        if ($st=='4')                     $per[$cid]['confirmed'] += $c;
    }
    return $per;  // cp_id => funnel
}

// 유입매체 분포
function media_for($cpIds) {
    $out = array();
    if (!count($cpIds)) return $out;
    $in = implode(',', array_map('intval', $cpIds));
    $r = sql_query("select rv_in_media, count(*) c from nfor_review where rv_cp_id in ($in) and rv_in_media<>'' and rv_delete='0' group by rv_in_media order by c desc");
    while ($w = sql_fetch_array($r)) { $out[] = array("media" => $w['rv_in_media'], "count" => (int)$w['c']); }
    return $out;
}

function type_label($t) {
    if ((string)$t === "2") return "방문형";
    if ((string)$t === "1") return "배송형";
    return "기타";
}

// ───────── 모드별 처리 ─────────
if ($mode === "advertisers") {
    // 광고주(공급사) 목록 — 업체명은 공급사 회원계정명(mb_name), 광고주=대표님 거래처(자사 데이터)
    $r = sql_query("select cp_supply_no, count(*) c, max(cp_id) last_id from nfor_campaign where cp_supply_no<>'' and cp_supply_no<>'0' group by cp_supply_no order by c desc");
    $list = array();
    while ($w = sql_fetch_array($r)) {
        $sn = $w['cp_supply_no'];
        $m = sql_fetch("select mb_name, mb_id from nfor_member where mb_no='" . addslashes($sn) . "'");
        $titles = array();
        $rt = sql_query("select cp_subject from nfor_campaign where cp_supply_no='" . addslashes($sn) . "' order by cp_id desc limit 3");
        while ($wt = sql_fetch_array($rt)) { $titles[] = $wt['cp_subject']; }
        $list[] = array(
            "supply_no" => $sn,
            "name"      => isset($m['mb_name']) ? $m['mb_name'] : "",
            "login_id"  => isset($m['mb_id']) ? $m['mb_id'] : "",
            "campaigns" => (int)$w['c'],
            "recent_titles" => $titles,
        );
    }
    echo json_encode(array("ok"=>true, "mode"=>"advertisers", "count"=>count($list), "advertisers"=>$list), JSON_UNESCAPED_UNICODE);
    exit;
}

// campaigns / summary 공통 — 대상 캠페인 로드
$tw = target_where();
if (trim($tw) === "") {
    http_response_code(400);
    echo json_encode(array("ok"=>false, "error"=>"need supply_no, cp_ids, or q"));
    exit;
}
$cols = "cp_id,cp_subject,cp_description,cp_type,cp_recruit,cp_point,cp_click,cp_supply_no,"
      . "cp_media_blog,cp_media_instagram,cp_media_youtube,"
      . "cp_sdatetime,cp_edatetime,cp_pick_datetime,cp_contents_sdatetime,cp_contents_edatetime,cp_result_datetime";
$rc = sql_query("select $cols from nfor_campaign where $tw order by cp_id desc limit 500");
$camps = array(); $cpIds = array();
while ($w = sql_fetch_array($rc)) { $camps[] = $w; $cpIds[] = $w['cp_id']; }

$funnel = funnel_for($cpIds);

if ($mode === "campaigns") {
    $rows = array();
    foreach ($camps as $c) {
        $cid = $c['cp_id'];
        $f = isset($funnel[$cid]) ? $funnel[$cid] : array("applied"=>0,"selected"=>0,"reviewed"=>0,"confirmed"=>0);
        $media = array();
        $rm = sql_query("select rv_in_media, count(*) c from nfor_review where rv_cp_id='" . addslashes($cid) . "' and rv_in_media<>'' and rv_delete='0' group by rv_in_media order by c desc");
        while ($wm = sql_fetch_array($rm)) { $media[] = array("media"=>$wm['rv_in_media'], "count"=>(int)$wm['c']); }
        $rate = ($f['selected'] > 0) ? round($f['reviewed'] / $f['selected'] * 100) : 0;
        $rows[] = array(
            "cp_id"      => $cid,
            "subject"    => $c['cp_subject'],
            "type"       => type_label($c['cp_type']),
            "recruit"    => (int)$c['cp_recruit'],
            "point"      => (int)$c['cp_point'],
            "views"      => (int)$c['cp_click'],
            "media_flags"=> array("blog"=>$c['cp_media_blog'], "instagram"=>$c['cp_media_instagram'], "youtube"=>$c['cp_media_youtube']),
            "funnel"     => array(
                "applied"   => $f['applied'],
                "selected"  => $f['selected'],
                "reviewed"  => $f['reviewed'],
                "confirmed" => $f['confirmed'],
                "review_rate" => $rate,  // 선정 대비 리뷰등록 %
            ),
            "inflow_media" => $media,
            "schedule" => array(
                "apply_start"  => substr($c['cp_sdatetime'],0,16),
                "apply_end"    => substr($c['cp_edatetime'],0,16),
                "pick"         => substr($c['cp_pick_datetime'],0,16),
                "review_start" => substr($c['cp_contents_sdatetime'],0,16),
                "review_end"   => substr($c['cp_contents_edatetime'],0,16),
                "result"       => substr($c['cp_result_datetime'],0,16),
            ),
            "report_url" => $BASE . "/admin/campaign_report.php?cp_id=" . $cid,
        );
    }
    echo json_encode(array("ok"=>true, "mode"=>"campaigns", "count"=>count($rows), "campaigns"=>$rows), JSON_UNESCAPED_UNICODE);
    exit;
}

if ($mode === "summary") {
    $tot = array("applied"=>0,"selected"=>0,"reviewed"=>0,"confirmed"=>0);
    $recruit_sum = 0; $views_sum = 0; $ongoing = 0; $ended = 0;
    $now = date("Y-m-d H:i:s");
    foreach ($camps as $c) {
        $cid = $c['cp_id'];
        $recruit_sum += (int)$c['cp_recruit'];
        $views_sum   += (int)$c['cp_click'];
        if ($f = (isset($funnel[$cid]) ? $funnel[$cid] : null)) {
            $tot['applied']+=$f['applied']; $tot['selected']+=$f['selected'];
            $tot['reviewed']+=$f['reviewed']; $tot['confirmed']+=$f['confirmed'];
        }
        $end = $c['cp_result_datetime'];
        if ($end && substr($end,0,10)!="0000-00-00" && $end < $now) { $ended++; } else { $ongoing++; }
    }
    $avg_rate = ($tot['selected']>0) ? round($tot['reviewed']/$tot['selected']*100) : 0;
    echo json_encode(array(
        "ok"=>true, "mode"=>"summary",
        "campaign_count"=>count($camps), "ongoing"=>$ongoing, "ended"=>$ended,
        "recruit_total"=>$recruit_sum, "views_total"=>$views_sum,
        "funnel_total"=>$tot, "avg_review_rate"=>$avg_rate,
        "inflow_media"=>media_for($cpIds),
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

http_response_code(400);
echo json_encode(array("ok"=>false, "error"=>"unknown mode"));
?>
