<?php
/*
 * 메타체험단 → 전산(ERP) 광고주(입점업체)별 캠페인 누적 집계 읽기전용 JSON API
 * 2026-07-06 신설. 기존 기능 무영향(읽기 전용, SELECT 만). mc_cal_api.php / mc_ad_api.php 와 동일 패턴.
 *
 * 호출:
 *   1) 광고주 목록  : GET /mc_advertiser_api.php?token=<TOKEN>&mode=advertisers
 *   2) 광고주별 집계: GET /mc_advertiser_api.php?token=<TOKEN>&supply_no=<mb_no>
 *
 * 집계 규칙:
 *   - 광고주 = nfor_member.mb_admin='1' AND mb_id 'adv_' 접두 (campaign_form.php 판정과 동일).
 *   - 회차(round) = 그 광고주 캠페인을 시작일(cp_sdatetime, 없으면 cp_insert_datetime) 오름차순 순번(1=최초).
 *   - 신청/선정/리뷰 = nfor_review.rv_step 재계산(카운터 컬럼 부정확 → campaign_report.php 와 동일 정의).
 *       rv_step: 1=신청 2=선정(리뷰대기) 3=리뷰제출 4=등록확인(완료) 5=미선정 6=선정후취소
 *       신청 = 전체 접수(rv_delete='0') / 선정 = step 2·3·4 / 리뷰 = step 3·4
 *   - 리뷰 링크 = rv_url 이 있는 것(rv_delete='0').
 */
include_once "path.php";
include_once "mc_share.php";   // mc_share_secret() — 서버 전용 비밀키(mc_share_key.php) 로더 재사용

// ───────── 토큰 인증 (기존 서버 전용 비밀키 재사용 — 소스에 하드코딩하지 않음) ─────────
$MC_ADV_TOKEN = function_exists('mc_share_secret') ? (string)mc_share_secret() : "";
header('Content-type: application/json; charset=utf-8');
header('Cache-Control: no-store');
$token_in = isset($_GET['token']) ? (string)$_GET['token'] : "";
if($MC_ADV_TOKEN === "" || $token_in !== $MC_ADV_TOKEN){
    http_response_code(403);
    echo json_encode(array("ok"=>false,"error"=>"forbidden"));
    exit;
}

// 0000-00-00 등 빈 일시 → 빈 문자열, 아니면 날짜(YYYY-MM-DD)
if(!function_exists('mc_adv_ymd')){
    function mc_adv_ymd($v){
        $v = (string)$v;
        if($v === "" || substr($v,0,4) === "0000") return "";
        return substr($v,0,10);
    }
}
// 일시가 오늘보다 과거로 지났는지(0000 방어)
if(!function_exists('mc_adv_passed')){
    function mc_adv_passed($v, $now){
        return $v && $v !== '0000-00-00 00:00:00' && $v < $now;
    }
}
// 오늘 기준 캠페인 진행 상태 라벨
if(!function_exists('mc_adv_status')){
    function mc_adv_status($c){
        $now = date("Y-m-d H:i:s");
        if(mc_adv_passed($c['cp_result_datetime'],   $now)) return "완료";
        if(mc_adv_passed($c['cp_contents_edatetime'], $now)) return "결과대기";
        if(mc_adv_passed($c['cp_contents_sdatetime'], $now)) return "리뷰등록중";
        if(mc_adv_passed($c['cp_pick_datetime'],     $now)) return "리뷰대기";
        if(mc_adv_passed($c['cp_edatetime'],         $now)) return "선정중";
        if(mc_adv_passed($c['cp_sdatetime'],         $now)) return "모집중";
        return "준비중";
    }
}

$mode = isset($_GET['mode']) ? preg_replace('/[^a-z_]/','',$_GET['mode']) : "";

/* ═══════════════════════════════════════════════
 *  모드 1) 광고주 목록 — 전산 매핑(연결하기) 드롭다운용
 *  광고주 = nfor_member.mb_admin='1' AND mb_id 'adv_' 접두 · 미탈퇴
 * ═══════════════════════════════════════════════ */
if($mode === "advertisers"){
    $res = sql_query("select mb_no, mb_cp_name, mb_name, mb_datetime,
            (select count(*) from nfor_campaign where cp_supply_no = M.mb_no) as campaign_count
        from nfor_member M
        where mb_admin='1' and mb_id like 'adv\\_%' and mb_leave_datetime=''
        order by mb_no desc limit 2000");
    $rows = array();
    while($r = sql_fetch_array($res)){
        $rows[] = array(
            "supply_no"      => (int)$r['mb_no'],
            "company"        => (string)$r['mb_cp_name'],
            "manager"        => (string)$r['mb_name'],
            "campaign_count" => (int)$r['campaign_count'],
            "joined"         => mc_adv_ymd($r['mb_datetime']),
        );
    }
    echo json_encode(array(
        "ok"       => true,
        "count"    => count($rows),
        "syncedAt" => date("Y-m-d H:i:s"),
        "advertisers" => $rows,
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

/* ═══════════════════════════════════════════════
 *  모드 2) 광고주별 누적 집계 (전 캠페인)
 * ═══════════════════════════════════════════════ */
$supply_no = preg_replace('/[^0-9]/','', isset($_GET['supply_no']) ? $_GET['supply_no'] : "");
if($supply_no === ""){
    http_response_code(400);
    echo json_encode(array("ok"=>false,"error"=>"supply_no required"));
    exit;
}

// 광고주 기본 정보
$adv = sql_fetch("select mb_no, mb_cp_name from nfor_member where mb_no='".addslashes($supply_no)."'");
if(!$adv){
    http_response_code(404);
    echo json_encode(array("ok"=>false,"error"=>"advertiser not found"));
    exit;
}

// 이 광고주의 전 캠페인 (시작일 오름차순 → 회차 부여)
$cres = sql_query("select cp_id, cp_subject, cp_recruit, cp_click,
        cp_sdatetime, cp_edatetime, cp_pick_datetime, cp_contents_sdatetime, cp_contents_edatetime, cp_result_datetime,
        cp_insert_datetime
    from nfor_campaign
    where cp_supply_no='".addslashes($supply_no)."'
    order by (case when cp_sdatetime is null or cp_sdatetime='0000-00-00 00:00:00' then cp_insert_datetime else cp_sdatetime end) asc, cp_id asc");
$camps = array();
$cpIds = array();
while($c = sql_fetch_array($cres)){ $camps[] = $c; $cpIds[] = $c['cp_id']; }

// 퍼널(신청/선정/리뷰) — rv_step 재계산, 캠페인 IN 일괄, rv_delete='0'
$funnel = array();   // cp_id → [step => cnt]
if(count($cpIds)){
    $inIds = "'".implode("','", array_map('addslashes',$cpIds))."'";
    $fres = sql_query("select rv_cp_id, rv_step, count(*) as c
        from nfor_review where rv_cp_id in ($inIds) and rv_delete='0' group by rv_cp_id, rv_step");
    while($f = sql_fetch_array($fres)){
        $funnel[$f['rv_cp_id']][(int)$f['rv_step']] = (int)$f['c'];
    }
}

$total = array("campaigns"=>count($camps), "recruit"=>0, "apply"=>0, "select"=>0, "review"=>0, "click"=>0);
$campaigns_out = array();
$round = 0;
foreach($camps as $c){
    $round++;
    $F  = isset($funnel[$c['cp_id']]) ? $funnel[$c['cp_id']] : array();
    $s2 = isset($F[2]) ? $F[2] : 0;
    $s3 = isset($F[3]) ? $F[3] : 0;
    $s4 = isset($F[4]) ? $F[4] : 0;
    $apply  = array_sum($F);      // 신청 = 전체 접수
    $select = $s2 + $s3 + $s4;    // 선정(이후 단계 포함)
    $review = $s3 + $s4;          // 리뷰 등록(제출)

    $campaigns_out[] = array(
        "round"      => $round,
        "cp_id"      => $c['cp_id'],
        "subject"    => (string)$c['cp_subject'],
        "status"     => mc_adv_status($c),
        "apply_end"  => mc_adv_ymd($c['cp_edatetime']),
        "review_end" => mc_adv_ymd($c['cp_contents_edatetime']),
        "recruit"    => (int)$c['cp_recruit'],
        "apply"      => $apply,
        "select"     => $select,
        "review"     => $review,
        "click"      => (int)$c['cp_click'],
    );

    $total['recruit'] += (int)$c['cp_recruit'];
    $total['apply']   += $apply;
    $total['select']  += $select;
    $total['review']  += $review;
    $total['click']   += (int)$c['cp_click'];
}

// 등록 리뷰 링크 (rv_url 있는 것, rv_delete='0') — 최신순
$reviews_out = array();
if(count($cpIds)){
    $inIds = "'".implode("','", array_map('addslashes',$cpIds))."'";
    $rres = sql_query("select rv_cp_id, rv_url, rv_in_media, rv_reg_datetime
        from nfor_review
        where rv_cp_id in ($inIds) and rv_delete='0' and rv_url<>''
        order by rv_reg_datetime desc limit 2000");
    while($r = sql_fetch_array($rres)){
        $reviews_out[] = array(
            "cp_id" => $r['rv_cp_id'],
            "url"   => (string)$r['rv_url'],
            "media" => (string)$r['rv_in_media'],
            "date"  => mc_adv_ymd($r['rv_reg_datetime']),
        );
    }
}

echo json_encode(array(
    "ok"        => true,
    "supply_no" => (int)$supply_no,
    "company"   => (string)$adv['mb_cp_name'],
    "syncedAt"  => date("Y-m-d H:i:s"),
    "total"     => $total,
    "campaigns" => $campaigns_out,
    "reviews"   => $reviews_out,
), JSON_UNESCAPED_UNICODE);
