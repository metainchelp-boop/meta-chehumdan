<?php
/*
 * 메타체험단 → 전산(ERP) 광고주(입점업체)별 캠페인 누적 집계 읽기전용 JSON API
 * 2026-07-06 신설. 기존 기능 무영향(읽기 전용, SELECT만).
 *
 * 호출:
 *   1) 광고주 목록  : GET /mc_advertiser_api.php?token=<TOKEN>&mode=advertisers
 *   2) 광고주별 집계: GET /mc_advertiser_api.php?token=<TOKEN>&supply_no=<mb_no>
 *
 * 집계 규칙(운영자 확정 2026-07-06):
 *   - 회차 = 그 광고주의 캠페인을 시작일(cp_sdatetime, 없으면 cp_insert_datetime) 오름차순 순번(1,2,3…).
 *   - 신청/선정/리뷰 = nfor_review.rv_step 재계산(카운터 컬럼 부정확 → campaign_report.php 방식).
 *   - 리뷰 = rv_url 전체 링크(등록 리뷰), rv_delete='0'.
 *   - 집계 범위 = 그 광고주의 메타체험단 전 캠페인.
 *   - 유입/노출 통계 = 조회수(cp_click 합) + 방문(nfor_traffic_주차) + 유입매체 분류.
 */
include_once "path.php";
include_once dirname(__FILE__)."/mc_share.php";   // mc_share_token() — 공개 보고서 주소 계산(2026-08-21)

// ───────── 토큰 인증 (서버 전용 파일 mc_api_token.php 에서 로드 — 저장소 미포함) ─────────
$MC_ADV_TOKEN = "";
$__tf = dirname(__FILE__)."/mc_api_token.php";      // <?php return '<랜덤토큰>'; (.gitignore 등재)
if(is_readable($__tf)){ $__t = @include $__tf; if(is_string($__t)) $MC_ADV_TOKEN = trim($__t); }

header('Content-type: application/json; charset=utf-8');
header('Cache-Control: no-store');
if($MC_ADV_TOKEN==="" || (string)$_GET['token'] !== (string)$MC_ADV_TOKEN){
    http_response_code(403);
    echo json_encode(array("ok"=>false,"error"=>"forbidden"));
    exit;
}

$BASE = (isset($config['cf_url']) && $config['cf_url']) ? rtrim($config['cf_url'],"/") : "https://meta-chehumdan.com";
$mode = isset($_GET['mode']) ? preg_replace('/[^a-z_]/','',$_GET['mode']) : "";

/* ═══════════════════════════════════════════════
 *  모드 1) 광고주 목록 — 전산 매핑(연결하기) 드롭다운용
 *  광고주 = nfor_member.mb_admin='1' AND mb_id LIKE 'adv_%'
 * ═══════════════════════════════════════════════ */
if($mode === "advertisers"){
    $q = isset($_GET['q']) ? trim($_GET['q']) : "";
    $where = " where mb_admin='1' and mb_id like 'adv\\_%' and mb_leave_datetime='' ";
    if($q !== ""){
        $qs = addslashes($q);
        $where .= " and (mb_cp_name like '%$qs%' or mb_name like '%$qs%' or mb_id like '%$qs%') ";
    }
    // 광고주별 캠페인 수 = nfor_campaign.cp_supply_no
    $res = sql_query("select mb_no, mb_id, mb_cp_name, mb_name, mb_datetime,
        (select count(*) from nfor_campaign where cp_supply_no = M.mb_no) as campaign_count
        from nfor_member M $where order by mb_no desc limit 500");
    $rows = array();
    while($r = sql_fetch_array($res)){
        $rows[] = array(
            "supply_no"      => (int)$r['mb_no'],
            "company"        => (string)$r['mb_cp_name'],
            "manager"        => (string)$r['mb_name'],
            "campaign_count" => (int)$r['campaign_count'],
            "joined"         => substr((string)$r['mb_datetime'],0,10),
        );
    }
    echo json_encode(array("ok"=>true,"count"=>count($rows),"syncedAt"=>date("Y-m-d H:i:s"),"advertisers"=>$rows), JSON_UNESCAPED_UNICODE);
    exit;
}

/* ═══════════════════════════════════════════════
 *  모드 2) 광고주별 누적 집계
 * ═══════════════════════════════════════════════ */
$supply_no = preg_replace('/[^0-9]/','', isset($_GET['supply_no']) ? $_GET['supply_no'] : "");
if($supply_no === ""){
    http_response_code(400);
    echo json_encode(array("ok"=>false,"error"=>"supply_no required"));
    exit;
}

// 광고주 기본 정보
$adv = sql_fetch("select mb_no, mb_cp_name, mb_name from nfor_member where mb_no='".addslashes($supply_no)."'");
if(!$adv){
    http_response_code(404);
    echo json_encode(array("ok"=>false,"error"=>"advertiser not found"));
    exit;
}

// 캠페인 전체 (시작일 오름차순 → 회차 부여)
$cres = sql_query("select cp_id, cp_subject, cp_recruit, cp_click, cp_use,
    cp_sdatetime, cp_edatetime, cp_pick_datetime, cp_contents_sdatetime, cp_contents_edatetime, cp_result_datetime,
    cp_insert_datetime
    from nfor_campaign where cp_supply_no='".addslashes($supply_no)."'
    order by (case when cp_sdatetime is null or cp_sdatetime='0000-00-00 00:00:00' then cp_insert_datetime else cp_sdatetime end) asc, cp_id asc");
$camps = array();
$cpIds = array();
while($c = sql_fetch_array($cres)){ $camps[] = $c; $cpIds[] = $c['cp_id']; }

// 퍼널 집계 (rv_step) — 캠페인 IN 한 번에, rv_delete='0'
$funnel = array();   // cp_id → [step=>cnt]
if(count($cpIds)){
    $inIds = "'".implode("','", array_map('addslashes',$cpIds))."'";
    $fres = sql_query("select rv_cp_id, rv_step, count(*) as c from nfor_review
        where rv_cp_id in ($inIds) and rv_delete='0' group by rv_cp_id, rv_step");
    while($f = sql_fetch_array($fres)){
        $funnel[$f['rv_cp_id']][(int)$f['rv_step']] = (int)$f['c'];
    }
}
// step: 1신청 2선정(대기) 3리뷰제출 4등록확인 5미선정 6취소
function mc_adv_funnel($F){
    $s2=isset($F[2])?$F[2]:0; $s3=isset($F[3])?$F[3]:0; $s4=isset($F[4])?$F[4]:0;
    $apply = array_sum($F);                 // 신청 = 전체
    return array(
        "apply"  => $apply,
        "select" => $s2+$s3+$s4,            // 선정
        "review" => $s3+$s4,               // 리뷰 등록
        "confirm"=> $s4,                    // 등록 확인(완료)
    );
}

// 트래픽 집계 — 캠페인별 주차 테이블(nfor_traffic_YYYY_WW). 없으면 skip.
// 유입매체 분류는 campaign_report.php CASE 미러.
function mc_adv_media_label($ref){
    if($ref==='' || $ref===null) return '직접/앱';
    if(strpos($ref,'blog.naver')!==false)   return '네이버블로그';
    if(strpos($ref,'cafe.naver')!==false)   return '네이버카페';
    if(strpos($ref,'search.naver')!==false) return '네이버검색';
    if(strpos($ref,'naver.')!==false)       return '네이버기타';
    if(strpos($ref,'instagram')!==false)    return '인스타그램';
    if(strpos($ref,'youtu')!==false)        return '유튜브';
    if(strpos($ref,'google.')!==false)      return '구글';
    if(strpos($ref,'daum.net')!==false || strpos($ref,'kakao')!==false) return '다음/카카오';
    if(strpos($ref,'meta-chehumdan.com')!==false) return '자사사이트';
    return '기타';
}
function mc_adv_public_url($url){
    $url = trim((string)$url);
    if($url === '' || !filter_var($url, FILTER_VALIDATE_URL)) return '';
    $scheme = strtolower((string)parse_url($url, PHP_URL_SCHEME));
    return ($scheme === 'http' || $scheme === 'https') ? $url : '';
}
$tableExists = array();
function mc_adv_table_ok($t){
    global $tableExists;
    if(isset($tableExists[$t])) return $tableExists[$t];
    $r = sql_fetch("show tables like '".addslashes($t)."'");
    return $tableExists[$t] = ($r ? true : false);
}

$total = array("campaigns"=>count($camps),"recruit"=>0,"apply"=>0,"select"=>0,"review"=>0,"click"=>0,
               "visit"=>0,"visit_uniq"=>0);
$media_total = array();      // 유입매체 → 방문수
$campaigns_out = array();
$round = 0;
foreach($camps as $c){
    $round++;
    $F = isset($funnel[$c['cp_id']]) ? $funnel[$c['cp_id']] : array();
    $fn = mc_adv_funnel($F);

    // 트래픽(방문/유입) — 이 캠페인의 주차 테이블
    $vt = 0; $vu = 0;
    $tbl = "nfor_traffic_".date("Y_W", strtotime($c['cp_insert_datetime']));
    if(mc_adv_table_ok($tbl)){
        $cid = addslashes($c['cp_id']);
        $vstat = sql_fetch("select count(*) as t, count(distinct tr_ip) as u from `$tbl` where tr_cp_id='$cid'");
        $vt = (int)$vstat['t']; $vu = (int)$vstat['u'];
        // 유입매체 분류(누적)
        $mres = sql_query("select tr_referer, count(*) as c from `$tbl` where tr_cp_id='$cid' group by tr_referer");
        while($m = sql_fetch_array($mres)){
            $lbl = mc_adv_media_label($m['tr_referer']);
            $media_total[$lbl] = (isset($media_total[$lbl])?$media_total[$lbl]:0) + (int)$m['c'];
        }
    }

    // 진행 상태 라벨(간단): 오늘 기준 단계
    $status = "진행중";
    $today = date("Y-m-d H:i:s");
    if($c['cp_result_datetime'] && $c['cp_result_datetime']!=='0000-00-00 00:00:00' && $c['cp_result_datetime'] < $today) $status="완료";
    elseif($c['cp_contents_edatetime'] && $c['cp_contents_edatetime'] < $today) $status="결과대기";
    elseif($c['cp_contents_sdatetime'] && $c['cp_contents_sdatetime'] < $today) $status="리뷰등록";
    elseif($c['cp_pick_datetime'] && $c['cp_pick_datetime'] < $today) $status="리뷰대기";
    elseif($c['cp_edatetime'] && $c['cp_edatetime'] < $today) $status="선정중";
    else $status="모집중";

    /* 공개 보고서 존재 확인 — 결과일 자동 생성 또는 관리자 재생성으로 실제 파일이 있는 회차만 공개한다. */
    $rp_url = ""; $rp_at = "";
    $rp_dir = dirname(__FILE__)."/report";
    if(function_exists('mc_share_token')){
        $rp_path = $rp_dir."/cr_".$c['cp_id']."_".mc_share_token($c['cp_id']).".html";
        if(!is_file($rp_path)) $rp_path = "";
    } else {
        $rp_path = "";
    }
    /* ⚠️ 옛 보고서는 토큰이 짧다(2026-06-18 이전 10자 → 이후 24자). 정확 매칭만 하면
     *    그 전에 공유한 회차가 통째로 「보고서 없음」이 된다(실측: 기존 10건 중 9건).
     *    그래서 캠페인 번호로 한 번 더 찾는다 — 어차피 관리자가 공유해 공개해 둔 그 파일이다.
     *    같은 캠페인 파일은 재발행 시 하나만 남으므로 여럿이면 가장 최근 것을 쓴다. */
    if($rp_path === ""){
        $rp_cands = glob($rp_dir."/cr_".$c['cp_id']."_*.html");
        if($rp_cands){
            usort($rp_cands, function($a,$b){ return filemtime($b) - filemtime($a); });
            $rp_path = $rp_cands[0];
        }
    }
    if($rp_path !== "" && is_file($rp_path)){
        $candidate_url = mc_adv_public_url($BASE."/report/".basename($rp_path));
        if($candidate_url !== ""){
            $rp_url = $candidate_url;
            $rp_at  = date("Y-m-d", filemtime($rp_path));
        }
    }

    $campaigns_out[] = array(
        "round"      => $round,
        "cp_id"      => $c['cp_id'],
        "status"     => $status,
        "recruit"    => (int)$c['cp_recruit'],
        "apply"      => $fn['apply'],
        "select"     => $fn['select'],
        "review"     => $fn['review'],
        "click"      => (int)$c['cp_click'],
        "visit"      => $vt,
        "apply_end"  => substr((string)$c['cp_edatetime'],0,10),
        "review_end" => substr((string)$c['cp_contents_edatetime'],0,10),
        /* ── 광고주 공유용 결과 보고서 (2026-08-21 신설) ────────────────────────
         * 아래 두 칸은 자동 생성된 로그인 불필요 정적 보고서다.
         *
         * ⚠️ 파일이 실제로 있을 때만 주소를 준다 — 주소만 주고 파일이 없으면
         *    광고주가 눌렀을 때 「찾을 수 없음」이 뜬다. 없으면 빈 문자열이고,
         *    전산은 값이 있는 회차만 화면에 그린다.
         * ⚠️ 토큰은 캠페인 번호를 서버 키로 해싱한 고정값이라 매번 같은 주소가 나온다
         *    (이미 만들어 둔 보고서의 주소가 바뀌지 않는다).
         */
        "report_public_url" => $rp_url,
        "report_at"         => $rp_at,
    );

    $total['recruit'] += (int)$c['cp_recruit'];
    $total['apply']   += $fn['apply'];
    $total['select']  += $fn['select'];
    $total['review']  += $fn['review'];
    $total['click']   += (int)$c['cp_click'];
    $total['visit']   += $vt;
    $total['visit_uniq'] += $vu;
}

// 등록 리뷰 전체 링크 (rv_url) — 최신순
$reviews_out = array();
if(count($cpIds)){
    $inIds = "'".implode("','", array_map('addslashes',$cpIds))."'";
    $rres = sql_query("select rv_cp_id, rv_url, rv_in_media, rv_reg_datetime from nfor_review
        where rv_cp_id in ($inIds) and rv_delete='0' and rv_url<>'' and rv_step in ('3','4')
        order by rv_reg_datetime desc limit 1000");
    while($r = sql_fetch_array($rres)){
        $review_url = mc_adv_public_url($r['rv_url']);
        if($review_url === '') continue;
        $reviews_out[] = array(
            "cp_id" => $r['rv_cp_id'],
            "url"   => $review_url,
            "media" => (string)$r['rv_in_media'],
            "date"  => substr((string)$r['rv_reg_datetime'],0,10),
        );
    }
}

// 유입매체 정렬(내림차순)
arsort($media_total);
$media_out = array();
foreach($media_total as $k=>$v){ $media_out[] = array("media"=>$k, "visit"=>$v); }

echo json_encode(array(
    "ok"        => true,
    "supply_no" => (int)$supply_no,
    "company"   => (string)$adv['mb_cp_name'],
    "syncedAt"  => date("Y-m-d H:i:s"),
    "total"     => $total,
    "campaigns" => $campaigns_out,
    "media"     => $media_out,
    "reviews"   => $reviews_out,
), JSON_UNESCAPED_UNICODE);
?>
