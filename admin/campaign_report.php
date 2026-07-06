<?php
include_once "path.php";

$form = $_SERVER[PHP_SELF];
$list = str_replace("form","list",$form);
$table = "nfor_campaign";
$id = "cp_id";

$id_value = $$id;

if($id_value){
	$write = sql_fetch("select * from $table where $id='{$id_value}'");
}

//리뷰어 선정
if($mode=="list_asign"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		review_asign($_POST[rv_id][$k]);
	}
	json_return("리뷰어 선정이 완료되었습니다\n선택된 신청서는 리뷰어 선정목록 메뉴로 이동되었습니다","ok");
}
//리뷰어 미선정
if($mode=="list_cancel"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		review_cancel($_POST[rv_id][$k]);
	}
	json_return("리뷰어 미선정 처리었습니다","ok");
}

// ▼ 리뷰 미등록자 독촉 알림톡 발송 (2026-06-07) — 기존 nfor_send 와 동일하게 nfor_sms_log 대기열에 등록 → 크론이 알리고 알림톡 발송
if($mode=="send_review_reminder"){
	demo_check_json();
	$cp = sql_fetch("select * from nfor_campaign where cp_id='".addslashes($id_value)."'");
	$send_row = sql_fetch("select * from nfor_send where sd_templt_code='TJ_3528'");   // 컨텐츠등록마감알림 템플릿
	$tpl_msg = $send_row[sd_msg] ? $send_row[sd_msg] : "[#{캠페인명}] 컨텐츠 등록 마감까지 #{마감일}일 남았습니다. 서둘러 컨텐츠를 등록해주세요.";
	$tcode   = $send_row[sd_templt_code] ? $send_row[sd_templt_code] : "TJ_3528";
	$snm     = $send_row[sd_name] ? $send_row[sd_name] : "컨텐츠 등록 마감 안내";
	$dleft = ceil((strtotime($cp[cp_contents_edatetime]) - time())/86400);
	if($dleft < 0) $dleft = 0;
	$ok=0; $skip=0; $fail=array();
	foreach((array)$_POST['rv_id'] as $rid){
		$rid = preg_replace('/[^0-9]/','',$rid); if(!$rid) continue;
		$rv = sql_fetch("select * from nfor_review where rv_id='$rid' and rv_cp_id='$cp[cp_id]'");
		if(!$rv[rv_id]) continue;
		if($rv[rv_url]){ $skip++; continue; }                 // 이미 등록자는 제외(안전장치)
		if($rv[rv_step]!='2'){ $skip++; continue; }           // 선정(rv_step=2) 상태만 발송 — 단순 신청자/미선정자 차단
		$mb = sql_fetch("select mb_name, mb_hp from nfor_member where mb_no='$rv[rv_mb_no]'");
		$hp = preg_replace('/[^0-9]/','',$mb[mb_hp]);
		if(!$hp){ $fail[] = $mb[mb_name]."(번호없음)"; continue; }
		$msg = $tpl_msg;
		$msg = str_replace("#{캠페인명}", $cp[cp_subject], $msg);
		$msg = str_replace("#{마감일}", $dleft, $msg);
		$msg = str_replace("#{이름}", $mb[mb_name], $msg);
		$msg = str_replace("#{상점명}", $config[cf_name], $msg);
		sql_query("insert nfor_sms_log set sl_msg='".addslashes($msg)."', sl_hp='".addslashes($hp)."', sl_send_hp='".addslashes($config[cf_tel])."', sl_datetime=NOW(), sl_send='0', sl_templt_code='".addslashes($tcode)."', sl_subject='".addslashes($snm)."'");
		$ok++;
	}
	$m = "리뷰 독촉 알림톡 발송 등록 완료: {$ok}명";
	if($skip) $m .= "\n(이미 등록자 제외 {$skip}명)";
	if($fail) $m .= "\n(휴대폰번호 없음 ".count($fail)."명: ".implode(", ", $fail).")";
	$m .= "\n\n발송 대기열에 등록되어 잠시 후 순차 발송됩니다.";
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode(array('ok'=>$ok,'skip'=>$skip,'fail'=>count($fail),'msg'=>$m), JSON_UNESCAPED_UNICODE);
	exit;
}
// ▲ 리뷰 미등록자 독촉 알림톡 발송

// 성별
$que = sql_query("select rv_mb_sex, count(*) as cnt from nfor_review where rv_cp_id='$write[cp_id]' and rv_delete='0' group by rv_mb_sex");
while($data = sql_fetch_array($que)){

	if($data[rv_mb_sex]=="M"){
		$text = "남자";
	} elseif($data[rv_mb_sex]=="F"){
		$text = "여자";
	} else{
		$text = "미입력";
	}

	$sex_data[text][] = $text;
	$sex_data[cnt][] = $data[cnt];
}


// 연령별
$que = sql_query("select rv_mb_age, count(*) as cnt from nfor_review where rv_cp_id='$write[cp_id]' and rv_delete='0' group by rv_mb_age");
while($data = sql_fetch_array($que)){
	$age_data[text][] = $data[rv_mb_age]?$data[rv_mb_age]."대":"미입력";
	$age_data[cnt][] = $data[cnt];
}


// 지역별
$que = sql_query("select rv_mb_area, count(*) as cnt from nfor_review where rv_cp_id='$write[cp_id]' and rv_delete='0' group by rv_mb_area");
while($data = sql_fetch_array($que)){
	$area_data[text][] = $data[rv_mb_area]?$data[rv_mb_area]:"미입력";
	$area_data[cnt][] = $data[cnt];
}



$admin[mb_level][""] = "미입력";
$que = sql_query("select * from nfor_level where 1 order by lv_rank asc");
while($row = sql_fetch_array($que)){
	$admin[mb_level][$row[lv_id]] = $row[lv_name];
}

// 레벨
$que = sql_query("select rv_mb_level, count(*) as cnt from nfor_review where rv_cp_id='$write[cp_id]' and rv_delete='0' group by rv_mb_level");
while($data = sql_fetch_array($que)){
	$level_data[text][] = $admin[mb_level][$data[rv_mb_level]];
	$level_data[cnt][] = $data[cnt];
}


//공통 - 트래픽 테이블 추출 2023-01-20
$traffic_y_w = date("Y_W",strtotime($write[cp_insert_datetime]));
$traffic_table = "nfor_traffic_".$traffic_y_w;
//echo "<!-- traffic_table : ". $traffic_table ." -->";

// 디바이스별 2023-01-20 / 2026 수정: 저장된 tr_device가 모바일을 PC로 오기록 → UA(tr_agent)로 재판별
$que = sql_query("select case when tr_agent regexp 'Mobi|Android|iPhone|iPad|iPod' then 'MOBILE' else 'PC' end as dv, count(*) as tot_device_count from {$traffic_table} where tr_cp_id = '$write[cp_id]' group by dv order by tot_device_count desc");
while($data = sql_fetch_array($que)){
	$device_data[text][] = $data[dv];
	$device_data[cnt][] = $data[tot_device_count];
}

// 시간대별 2023-01-20
$sql = "select ifNull(T2.hour, T1.n) as hour, ifNull(T2.tot_hour_count, 0) as tot_hour_count
from (
	select @N:=@N+1 as n from {$traffic_table},
    (select @N:=-1 from dual) NN limit 24
) as T1
left outer join (
	select hour(tr_datetime) as hour, count(*) as tot_hour_count from {$traffic_table} where tr_cp_id = '$write[cp_id]' group by hour
    ) as T2 on T1.n = T2.hour
order by T1.n asc";
$que = sql_query($sql);
while($data = sql_fetch_array($que)){
	$time_data[text][] = $data[hour]."시";
	$time_data[cnt][] = $data[tot_hour_count];
}

// 요일별 2023-01-20
$sql = "select
	case dayofweek(tr_datetime)
		when 1 then '일요일'
        when 2 then '월요일'
        when 3 then '화요일'
        when 4 then '수요일'
        when 5 then '목요일'
        when 6 then '금요일'
        when 7 then '토요일'
	end as DateRange, count(*) as tot_dayw_count
from {$traffic_table}
where tr_cp_id = '$write[cp_id]'
group by dayofweek(tr_datetime)";
$que = sql_query($sql);
while($data = sql_fetch_array($que)){
	$dayw_data[text][] = $data[DateRange];
	$dayw_data[cnt][] = $data[tot_dayw_count];
}

// 월별 2023-01-20
$sql = "SELECT DATE_FORMAT(tr_date, '%Y-%m') AS date, count(*) AS tot_month_count
FROM {$traffic_table}
where tr_cp_id = '$write[cp_id]'
GROUP BY DATE_FORMAT(tr_date, '%Y-%m')";
$que = sql_query($sql);
while($data = sql_fetch_array($que)){
	$month_data[text][] = $data[date];
	$month_data[cnt][] = $data[tot_month_count];
}

// 일별 2023-01-20
$sql = "SELECT tr_date AS date, count(*) AS tot_day_count
FROM {$traffic_table}
where tr_cp_id = '$write[cp_id]'
GROUP BY tr_date order by tr_date asc";
$que = sql_query($sql);
while($data = sql_fetch_array($que)){
	$day_data[text][] = $data[date];
	$day_data[cnt][] = $data[tot_day_count];
}

// 누적성과통계 2023-01-20
$sql = "SELECT count(*) AS tot_mem_count, B.mb_name
FROM {$traffic_table} A	inner join nfor_member B on A.tr_mb_no = B.mb_no
where tr_cp_id = '$write[cp_id]' GROUP BY B.mb_name";
//echo "<!-- sql : ". $sql ." -->";
$que = sql_query($sql);
while($data = sql_fetch_array($que)){
	$blogid_data[text][] = $data[mb_name];
	$blogid_data[cnt][] = $data[tot_mem_count];
}

// 유입 매체 분류 (referer 기반) 2026 ⓐ
$sql = "SELECT CASE
 WHEN tr_referer='' OR tr_referer IS NULL THEN '직접/앱'
 WHEN tr_referer LIKE '%blog.naver%' THEN '네이버블로그'
 WHEN tr_referer LIKE '%cafe.naver%' THEN '네이버카페'
 WHEN tr_referer LIKE '%search.naver%' THEN '네이버검색'
 WHEN tr_referer LIKE '%naver.%' THEN '네이버기타'
 WHEN tr_referer LIKE '%instagram%' THEN '인스타그램'
 WHEN tr_referer LIKE '%youtu%' THEN '유튜브'
 WHEN tr_referer LIKE '%google.%' THEN '구글'
 WHEN tr_referer LIKE '%daum.net%' OR tr_referer LIKE '%kakao%' THEN '다음/카카오'
 WHEN tr_referer LIKE '%meta-chehumdan.com%' THEN '자사사이트'
 ELSE '기타' END as m, count(*) as cnt
 FROM {$traffic_table} where tr_cp_id='$write[cp_id]' group by m order by cnt desc";
$que = sql_query($sql);
while($data = sql_fetch_array($que)){
	$media_data[text][] = $data[m];
	$media_data[cnt][] = $data[cnt];
}

// 상위 유입 출처 (블로그 글/도메인) 2026 ⓐ
$que = sql_query("select tr_referer, count(*) as cnt from {$traffic_table} where tr_cp_id='$write[cp_id]' and tr_referer<>'' group by tr_referer order by cnt desc limit 8");
while($data = sql_fetch_array($que)){
	$rf = $data[tr_referer];
	if(preg_match('~[?&]blogId=([A-Za-z0-9_-]+)~', $rf, $mm)){ $lbl = "블로그 @".$mm[1]; }
	elseif(preg_match('~blog\.naver\.com/([A-Za-z0-9_-]+)~', $rf, $mm) && strtolower($mm[1])!='postview.naver'){ $lbl = "블로그 @".$mm[1]; }
	else { $h = parse_url($rf, PHP_URL_HOST); $lbl = $h ? $h : $rf; }
	if(mb_strlen($lbl,'UTF-8') > 24) $lbl = mb_substr($lbl,0,24,'UTF-8')."…";
	$refsrc_data[text][] = $lbl;
	$refsrc_data[cnt][] = $data[cnt];
}

// 방문/전환 요약 (IP 기반) 2026 ⓑ
$vstat = sql_fetch("select count(*) as tot, count(distinct tr_ip) as uniq from {$traffic_table} where tr_cp_id='$write[cp_id]'");
$visit_total  = (int)$vstat[tot];
$visit_uniq   = (int)$vstat[uniq];
$visit_return = $visit_total - $visit_uniq; if($visit_return < 0) $visit_return = 0;
$rate_visit_signup = $visit_uniq > 0 ? round($write[cp_order] / $visit_uniq * 100, 1) : 0;
$rate_return       = $visit_total > 0 ? round($visit_return / $visit_total * 100) : 0;

// 국내/해외 분류 (KR IP 대역 기준) 2026 ⓒ
if(!function_exists('mc_ip_is_kr')){
	function mc_ip_is_kr($ip, $ranges){
		$x = ip2long($ip);
		if($x === false) return true;        // 판단불가(IPv6 등)는 국내로 보수적 처리
		$x = $x & 0xFFFFFFFF;
		$lo = 0; $hi = count($ranges) - 1;
		while($lo <= $hi){
			$mid = intval(($lo + $hi) / 2);
			if($x < $ranges[$mid][0]) $hi = $mid - 1;
			elseif($x > $ranges[$mid][1]) $lo = $mid + 1;
			else return true;
		}
		return false;
	}
}
$kr_ranges = array();
$kr_file = dirname(__FILE__).'/geo_kr.txt';
if(is_readable($kr_file)){
	foreach(file($kr_file, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES) as $cidr){
		if(strpos($cidr, '/') === false) continue;
		list($net, $bits) = explode('/', trim($cidr));
		$n = ip2long($net); if($n === false) continue;
		$bits = (int)$bits;
		$mask = $bits == 0 ? 0 : ((~((1 << (32 - $bits)) - 1)) & 0xFFFFFFFF);
		$start = ($n & $mask) & 0xFFFFFFFF;
		$end   = ($start | (~$mask & 0xFFFFFFFF)) & 0xFFFFFFFF;
		$kr_ranges[] = array($start, $end);
	}
	usort($kr_ranges, function($a, $b){ return ($a[0] < $b[0]) ? -1 : (($a[0] > $b[0]) ? 1 : 0); });
}
$geo_dom = 0; $geo_ovs = 0;
if(count($kr_ranges)){
	$que = sql_query("select tr_ip, count(*) as c from {$traffic_table} where tr_cp_id='$write[cp_id]' group by tr_ip");
	while($d = sql_fetch_array($que)){
		if($d[tr_ip] != '' && !mc_ip_is_kr($d[tr_ip], $kr_ranges)) $geo_ovs += $d[c];
		else $geo_dom += $d[c];
	}
}
$geo_total = $geo_dom + $geo_ovs;
$rate_ovs  = $geo_total > 0 ? round($geo_ovs / $geo_total * 100, 1) : 0;

// 유입 기여 분석 (rv_in_* 기반) 2026 ④
$ref_signup = array();
$que = sql_query("select rv_in_mb_no, count(*) as c from nfor_review where rv_cp_id='$write[cp_id]' and rv_in_mb_no<>'' and rv_delete='0' group by rv_in_mb_no");
while($d = sql_fetch_array($que)){ $ref_signup[$d[rv_in_mb_no]] = $d[c]; }
$ref_visit = array();
$que = sql_query("select tr_mb_no, count(*) as c from {$traffic_table} where tr_cp_id='$write[cp_id]' group by tr_mb_no");
while($d = sql_fetch_array($que)){ $ref_visit[$d[tr_mb_no]] = $d[c]; }
$inflow_signup_total = array_sum($ref_signup);
$inflow_rows = array();
if($inflow_signup_total > 0){
	$allmb = array_unique(array_merge(array_keys($ref_signup), array_keys($ref_visit)));
	// 회원명 N+1 제거: 1쿼리(IN) 선로드
	$__inflow_names = array(); $__inflow_ids = array();
	foreach($allmb as $mb){ if($mb !== '') $__inflow_ids[$mb] = 1; }
	if(count($__inflow_ids)){
		$__inq = sql_query("select mb_no, mb_name from nfor_member where mb_no in ('".implode("','", array_keys($__inflow_ids))."')");
		while($__in = sql_fetch_array($__inq)) $__inflow_names[$__in[mb_no]] = $__in[mb_name];
	}
	foreach($allmb as $mb){
		if($mb === '') continue;
		$v = isset($ref_visit[$mb]) ? $ref_visit[$mb] : 0;
		$s = isset($ref_signup[$mb]) ? $ref_signup[$mb] : 0;
		if($s == 0 && $v == 0) continue;
		$__nm = isset($__inflow_names[$mb]) ? $__inflow_names[$mb] : '';
		$inflow_rows[] = array('name'=>($__nm?$__nm:$mb), 'visit'=>$v, 'signup'=>$s, 'rate'=>($v>0?round($s/$v*100,1):0));
	}
	usort($inflow_rows, function($a,$b){ if($b['signup'] != $a['signup']) return $b['signup'] - $a['signup']; return $b['visit'] - $a['visit']; });
	$inflow_rows = array_slice($inflow_rows, 0, 15);
}
$inmedia_data = array();
$que = sql_query("select rv_in_media, count(*) as c from nfor_review where rv_cp_id='$write[cp_id]' and rv_in_media<>'' and rv_delete='0' group by rv_in_media order by c desc");
while($d = sql_fetch_array($que)){ $inmedia_data[text][] = ($d[rv_in_media]?$d[rv_in_media]:'기타'); $inmedia_data[cnt][] = $d[c]; }

include_once "head.php";

// ===== 표시용 파생값 (기존 데이터 기반, 데이터 변경 없음) 2026 리디자인 =====
$rate_apply = ($write[cp_recruit] > 0) ? round($write[cp_order] / $write[cp_recruit] * 100) : 0;
$rate_post  = ($write[cp_review_asign] > 0) ? round($write[cp_review_post] / $write[cp_review_asign] * 100) : 0;
?>

<style>
/* ============================================================
   캠페인 결과 보고서 — 모던 리디자인 (2026)
   * 데이터/기능 변경 없음. 레이아웃·CSS·차트 스타일만 현대화.
   * 모든 선택자는 .mc-report 로 스코프(관리자 부트스트랩과 충돌 방지)
   ============================================================ */
.mc-report{
  --bg:#f1f5f9; --card:#ffffff; --line:#e8ebf0; --line2:#eef1f5;
  --ink:#0f172a; --ink2:#475569; --muted:#94a3b8;
  --accent:#4f46e5; --accent-soft:#eef2ff;
  --shadow:0 1px 2px rgba(15,23,42,.04), 0 2px 8px rgba(15,23,42,.05);
  font-family:-apple-system,BlinkMacSystemFont,"Apple SD Gothic Neo","Pretendard","Malgun Gothic","맑은 고딕",sans-serif;
  color:var(--ink); line-height:1.55; width:100%; max-width:none; margin:0; padding:8px 0 24px;
  -webkit-font-smoothing:antialiased;
}
.mc-report *{box-sizing:border-box;}
.mc-report .num{font-variant-numeric:tabular-nums; font-feature-settings:"tnum";}

/* 헤더 */
.mc-report .mc-head{background:var(--card); border:1px solid var(--line); border-radius:14px;
  box-shadow:var(--shadow); padding:24px 26px; margin-bottom:18px;
  display:flex; justify-content:space-between; align-items:flex-start; gap:24px; flex-wrap:wrap;}
.mc-report .mc-head .eyebrow{font-size:12px; font-weight:700; letter-spacing:.12em; color:var(--accent); text-transform:uppercase;}
.mc-report .mc-head h1{margin:8px 0 12px; font-size:24px; font-weight:800; letter-spacing:-.02em; line-height:1.3; color:var(--ink);}
.mc-report .mc-head h1 small{display:block; font-size:14px; font-weight:500; color:var(--ink2); margin-top:4px;}
.mc-report .chips{display:flex; gap:6px; flex-wrap:wrap;}
.mc-report .chip{font-size:12px; font-weight:600; padding:5px 11px; border-radius:999px; border:1px solid var(--line); color:var(--ink2); background:#fff; display:inline-block;}
.mc-report .chip.code{background:#0f172a; color:#fff; border-color:#0f172a; font-variant-numeric:tabular-nums;}
.mc-report .chip.point{background:var(--accent-soft); color:var(--accent); border-color:transparent;}
.mc-report .chip.ch-blog{color:#16a34a; border-color:#bbf7d0; background:#f0fdf4;}
.mc-report .chip.ch-insta{color:#db2777; border-color:#fbcfe8; background:#fdf2f8;}
.mc-report .chip.ch-youtube{color:#dc2626; border-color:#fecaca; background:#fef2f2;}
.mc-report .mc-head .gen{text-align:right; font-size:12px; color:var(--muted); white-space:nowrap;}
.mc-report .mc-head .gen b{display:block; font-size:13px; color:var(--ink2); font-weight:700;}
.mc-report .share-btn{display:inline-block; margin-top:12px; background:var(--accent); color:#fff; font-size:12px; font-weight:700; padding:9px 14px; border-radius:9px; text-decoration:none; white-space:nowrap;}
.mc-report .share-btn:hover{background:#4338ca; color:#fff;}

/* KPI */
.mc-report .kpi{display:grid; grid-template-columns:repeat(6,1fr); gap:12px; margin-bottom:22px;}
.mc-report .kpi > *{min-width:0;}
.mc-report .kpi .cell{background:var(--card); border:1px solid var(--line); border-radius:12px; padding:16px; box-shadow:var(--shadow);}
.mc-report .kpi .lab{font-size:12px; color:var(--ink2); font-weight:600; margin-bottom:8px;}
.mc-report .kpi .val{font-size:25px; font-weight:800; letter-spacing:-.02em; color:var(--ink);}
.mc-report .kpi .val span{font-size:13px; font-weight:600; color:var(--muted); margin-left:2px;}
.mc-report .kpi .sub{font-size:11px; margin-top:5px; color:var(--muted);}
.mc-report .kpi .sub b{color:#059669; font-weight:700;}
.mc-report .kpi .cell.hl{background:linear-gradient(180deg,#fff,#fafaff); border-color:#e0e0fb;}
.mc-report .kpi .cell.hl .val{color:var(--accent);}

/* 섹션 */
.mc-report .sec{margin-top:30px;}
.mc-report .sec-h{display:flex; align-items:center; gap:10px; margin-bottom:14px;}
.mc-report .sec-h .bar{width:4px; height:18px; border-radius:2px; background:var(--accent);}
.mc-report .sec-h h2{margin:0; font-size:16px; font-weight:800; letter-spacing:-.01em; color:var(--ink);}
.mc-report .sec-h .desc{font-size:12px; color:var(--muted); margin-left:auto;}

/* 일정 스텝 */
.mc-report .steps{display:grid; grid-template-columns:repeat(4,1fr); gap:12px;}
.mc-report .steps > *{min-width:0;}
.mc-report .step{position:relative; background:var(--card); border:1px solid var(--line); border-radius:12px; padding:16px; box-shadow:var(--shadow);}
.mc-report .step .ph{font-size:12px; font-weight:700; color:var(--accent); margin-bottom:8px; display:flex; align-items:center; gap:6px;}
.mc-report .step .ph .n{display:inline-flex; width:18px; height:18px; border-radius:50%; background:var(--accent-soft); color:var(--accent); font-size:11px; align-items:center; justify-content:center; font-weight:800;}
.mc-report .step .date{font-size:13px; font-weight:700; color:var(--ink); font-variant-numeric:tabular-nums;}
.mc-report .step .cnt{font-size:12px; color:var(--ink2); margin-top:6px;}
.mc-report .step .cnt b{color:var(--accent); font-weight:800; font-size:15px;}
.mc-report .step:not(:last-child):after{content:"\203A"; position:absolute; right:-10px; top:50%; transform:translateY(-50%); color:var(--muted); font-size:20px; z-index:1;}

/* 차트 카드 */
.mc-report .grid{display:grid; gap:14px;}
.mc-report .grid.g2{grid-template-columns:repeat(2,1fr);}
.mc-report .grid.g4{grid-template-columns:repeat(4,1fr);}
.mc-report .grid > *{min-width:0;}
.mc-report canvas{max-width:100%;}
.mc-report .card{background:var(--card); border:1px solid var(--line); border-radius:12px; box-shadow:var(--shadow); padding:18px 18px 14px; min-width:0;}
.mc-report .card h3{margin:0 0 4px; font-size:13.5px; font-weight:700; color:var(--ink);}
.mc-report .card .hint{font-size:11px; color:var(--muted); margin-bottom:10px;}
.mc-report .chart-box{position:relative; height:230px;}
.mc-report .chart-box.tall{height:260px;}
.mc-report .bignum{font-size:30px; font-weight:800; letter-spacing:-.02em; margin-top:8px; color:var(--ink);}
.mc-report .bignum small{font-size:14px; font-weight:600; color:var(--muted); margin-left:2px;}
.mc-report .card.hlcard{background:linear-gradient(180deg,#fff,#fafaff); border-color:#e0e0fb;}
.mc-report .span2{grid-column:span 2;}

/* 표 */
.mc-report .tbl-wrap{background:var(--card); border:1px solid var(--line); border-radius:12px; box-shadow:var(--shadow); overflow-x:auto;}
.mc-report table.mc-tbl{width:100%; border-collapse:collapse; font-size:13px; min-width:760px;}
.mc-report table.mc-tbl thead th{background:#f8fafc; color:var(--ink2); font-weight:700; font-size:12px; text-align:left; padding:12px 14px; border-bottom:1px solid var(--line); white-space:nowrap;}
.mc-report table.mc-tbl tbody td{padding:13px 14px; border-bottom:1px solid var(--line2); color:var(--ink2); vertical-align:middle;}
.mc-report table.mc-tbl tbody tr:last-child td{border-bottom:none;}
.mc-report table.mc-tbl tbody tr:hover{background:#fafbff;}
.mc-report .who b{color:var(--ink); font-weight:700;}
.mc-report .who .id{font-size:11px; color:var(--muted); font-variant-numeric:tabular-nums;}
.mc-report .badge-lv{display:inline-block; font-size:11px; font-weight:700; padding:2px 8px; border-radius:6px; background:var(--accent-soft); color:var(--accent); white-space:nowrap;}
.mc-report .tag{display:inline-block; font-size:11px; font-weight:700; padding:2px 8px; border-radius:6px; white-space:nowrap;}
.mc-report .tag.ok{background:#ecfdf5; color:#059669;}
.mc-report .tag.review{background:#eff6ff; color:#2563eb;}
.mc-report .tag.asign{background:#eef2ff; color:#4f46e5;}
.mc-report .tag.wait{background:#fff7ed; color:#d97706;}
.mc-report .lnk{color:var(--accent); text-decoration:none; font-size:12px; word-break:break-all;}
.mc-report .lnk:hover{text-decoration:underline;}
.mc-report .dt{font-variant-numeric:tabular-nums; font-size:12px;}
.mc-report .dt .s{color:var(--muted);}
.mc-report .blogbox{width:220px;}
.mc-report .blogbox .total{font-size:11px; font-weight:700; color:var(--accent); display:block; margin-top:4px;}

/* 하단 버튼 */
.mc-actions{width:100%; max-width:none; margin:22px 0 0; display:flex; gap:10px; justify-content:flex-end; padding:0;}
.mc-actions #list_asign, .mc-actions #list_cancel{
  border:none !important; border-radius:10px !important; padding:12px 22px !important;
  font-size:14px !important; font-weight:700 !important; cursor:pointer; letter-spacing:-.01em; line-height:1.2;
  display:inline-block; text-decoration:none;}
.mc-actions #list_asign{background:var(--accent) !important; color:#fff !important;}
.mc-actions #list_cancel{background:#fff !important; color:var(--ink2) !important; border:1px solid var(--line) !important;}

@media (max-width:900px){
  .mc-report .kpi{grid-template-columns:repeat(3,1fr);}
  .mc-report .steps{grid-template-columns:repeat(2,1fr);}
  .mc-report .grid.g2,.mc-report .grid.g4{grid-template-columns:1fr;}
  .mc-report .span2{grid-column:auto;}
  .mc-report .step:after{display:none;}
}
</style>


<div id="print_box">
<div class="mc-report">

	<!-- 헤더 -->
	<div class="mc-head">
		<div>
			<div class="eyebrow">캠페인 결과 보고서</div>
			<h1><?=$write[cp_subject]?><?php if($write[cp_description]){ ?><small><?=$write[cp_description]?></small><?php } ?></h1>
			<div class="chips">
				<span class="chip code"># <?=$write[cp_id]?></span>
				<?php if($nfor[cp_type][$write[cp_type]]){ ?><span class="chip"><?=$nfor[cp_type][$write[cp_type]]?></span><?php } ?>
				<?php if($write[cp_point]){ ?><span class="chip point">+ <?=number_format($write[cp_point])?>P</span><?php } ?>
				<? if($write[cp_media_blog]){ ?><span class="chip ch-blog">블로그</span><? } ?>
				<? if($write[cp_media_instagram]){ ?><span class="chip ch-insta">인스타그램</span><? } ?>
				<? if($write[cp_media_youtube]){ ?><span class="chip ch-youtube">유튜브</span><? } ?>
			</div>
		</div>
		<div class="gen">
			<b>메타체험단</b>
			생성일 <?=date("Y-m-d")?>
			<br><a href="campaign_report_share.php?cp_id=<?=$write[cp_id]?>" target="_blank" class="share-btn">＋ 광고주 공유 보고서 만들기</a>
		</div>
	</div>

	<?php
	// ▼ 실제 진행 funnel — rv_step 기준 재계산 (캠페인 누적 카운터가 부정확해 교체) 2026-06-07
	// rv_step: 1=신청 2=선정(리뷰대기) 3=리뷰제출 4=등록확인(완료) 5=미선정 6=선정후취소
	$F=array('1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0);
	$qF=sql_query("select rv_step, count(*) as c from nfor_review where rv_cp_id='$write[cp_id]' and rv_delete='0' group by rv_step");
	while($rF=sql_fetch_array($qF)){ $F[(string)$rF[rv_step]] = $rF[c]; }
	$f_apply   = array_sum($F);                 // 신청(전체 접수)
	$f_select  = $F['2']+$F['3']+$F['4'];        // 선정(이후 단계 포함)
	$f_review  = $F['3']+$F['4'];                // 리뷰 등록(제출)
	$f_confirm = $F['4'];                        // 등록 확인(완료)
	$f_pending = $F['2'];                        // 선정됐으나 리뷰 미등록(=독촉 대상)
	if(!function_exists('mc_pct')){ function mc_pct($n,$d){ return $d>0?round($n/$d*100):0; } }
	?>
	<!-- KPI 요약 (실제 진행 기준 · 현재/전체) -->
	<div class="kpi">
		<div class="cell"><div class="lab">조회수</div><div class="val num"><?=number_format($write[cp_click])?></div><div class="sub">캠페인 페이지를 본 수</div></div>
		<div class="cell hl"><div class="lab">신청 / 모집</div><div class="val num"><?=number_format($f_apply)?> <span>/ <?=number_format($write[cp_recruit])?>명</span></div><div class="sub">모집 대비 <b><?=mc_pct($f_apply,$write[cp_recruit])?>%</b> 신청</div></div>
		<div class="cell"><div class="lab">선정</div><div class="val num"><?=number_format($f_select)?> <span>/ <?=number_format($write[cp_recruit])?>명</span></div><div class="sub">모집 인원 중 리뷰어 확정</div></div>
		<div class="cell"><div class="lab">리뷰 등록 (체험완료)</div><div class="val num"><?=number_format($f_review)?> <span>/ <?=number_format($f_select)?>명</span></div><div class="sub">선정자 중 <b><?=mc_pct($f_review,$f_select)?>%</b> 등록</div></div>
		<div class="cell"><div class="lab">리뷰 미등록</div><div class="val num" style="color:#dc2626"><?=number_format($f_pending)?> <span>/ <?=number_format($f_select)?>명</span></div><div class="sub">선정됐으나 아직 미등록 <b>(독촉 대상)</b></div></div>
		<div class="cell"><div class="lab">등록 확인</div><div class="val num"><?=number_format($f_confirm)?> <span>/ <?=number_format($f_select)?>명</span></div><div class="sub">최종 완료</div></div>
	</div>

	<!-- 캠페인 일정 -->
	<div class="sec">
		<div class="sec-h"><span class="bar"></span><h2>캠페인 일정</h2></div>
		<div class="steps">
			<div class="step"><div class="ph"><span class="n">1</span>신청 기간</div><div class="date num"><?=substr($write[cp_sdatetime],0,10)?> ~ <?=substr($write[cp_edatetime],0,10)?></div><div class="cnt">신청 <b class="num"><?=number_format($f_apply)?></b>명</div></div>
			<div class="step"><div class="ph"><span class="n">2</span>선정자 발표</div><div class="date num"><?=substr($write[cp_pick_datetime],0,10)?></div><div class="cnt">선정 <b class="num"><?=number_format($f_select)?></b>명</div></div>
			<div class="step"><div class="ph"><span class="n">3</span>리뷰 등록</div><div class="date num"><?=substr($write[cp_contents_sdatetime],0,10)?> ~ <?=substr($write[cp_contents_edatetime],0,10)?></div><div class="cnt">등록 <b class="num"><?=number_format($f_review)?></b>명</div></div>
			<div class="step"><div class="ph"><span class="n">4</span>결과 발표</div><div class="date num"><?=substr($write[cp_result_datetime],0,10)?></div><div class="cnt">확인 <b class="num"><?=number_format($f_confirm)?></b>명</div></div>
		</div>
	</div>

	<!-- 참여자 분석 -->
	<div class="sec">
		<div class="sec-h"><span class="bar"></span><h2>참여자 분석</h2><span class="desc">신청 리뷰어 기준</span></div>
		<div class="grid g4">
			<div class="card"><h3>성별</h3><div class="hint">신청자 성별 비율</div><div class="chart-box"><canvas id="sex_chart"></canvas></div></div>
			<div class="card"><h3>등급</h3><div class="hint">인플루언서 등급 분포</div><div class="chart-box"><canvas id="level_chart"></canvas></div></div>
			<div class="card"><h3>연령대</h3><div class="hint">신청자 연령 분포</div><div class="chart-box"><canvas id="age_chart"></canvas></div></div>
			<div class="card"><h3>지역</h3><div class="hint">신청자 거주 지역</div><div class="chart-box"><canvas id="area_chart"></canvas></div></div>
		</div>
	</div>

	<!-- 유입 트래픽 분석 -->
	<div class="sec">
		<div class="sec-h"><span class="bar"></span><h2>유입 트래픽 분석</h2><span class="desc">캠페인 페이지 방문 기준</span></div>
		<div class="grid g4">
			<div class="card"><h3>디바이스</h3><div class="hint">PC / 모바일</div><div class="chart-box"><canvas id="device_chart"></canvas></div></div>
			<div class="card"><h3>요일별</h3><div class="hint">요일별 방문</div><div class="chart-box"><canvas id="dayw_chart"></canvas></div></div>
			<div class="card span2"><h3>시간대별</h3><div class="hint">0시 ~ 23시 방문 분포</div><div class="chart-box"><canvas id="time_chart"></canvas></div></div>
		</div>
		<div class="grid g2" style="margin-top:14px;">
			<div class="card"><h3>일별 추이</h3><div class="hint">일자별 방문 추이</div><div class="chart-box tall"><canvas id="day_chart"></canvas></div></div>
			<div class="card"><h3>월별</h3><div class="hint">월별 방문</div><div class="chart-box tall"><canvas id="month_chart"></canvas></div></div>
		</div>
		<div class="grid g2" style="margin-top:14px;">
			<div class="card span2"><h3>리뷰어별 누적 성과</h3><div class="hint">리뷰어가 유입시킨 누적 방문 수</div><div class="chart-box tall"><canvas id="blogid_chart"></canvas></div></div>
		</div>
	</div>

	<!-- 유입 출처·매체 분석 -->
	<div class="sec">
		<div class="sec-h"><span class="bar"></span><h2>유입 출처 · 매체 분석</h2><span class="desc">방문 referer 기준</span></div>
		<div class="grid g2">
			<div class="card"><h3>유입 매체</h3><div class="hint">방문자가 어디서 들어왔나 (referer)</div><div class="chart-box tall"><canvas id="media_chart"></canvas></div></div>
			<div class="card"><h3>상위 유입 출처</h3><div class="hint">유입을 많이 일으킨 블로그 글 · 사이트</div><div class="chart-box tall"><canvas id="refsrc_chart"></canvas></div></div>
		</div>
	</div>

	<!-- 방문·전환 요약 -->
	<div class="sec">
		<div class="sec-h"><span class="bar"></span><h2>방문 · 전환 요약</h2><span class="desc">방문 IP 기준 · 순방문자 = 고유 IP</span></div>
		<div class="grid g4">
			<div class="card"><h3>순 방문자</h3><div class="hint">고유 IP 기준</div><div class="bignum num"><?=number_format($visit_uniq)?> <small>명</small></div></div>
			<div class="card"><h3>총 방문</h3><div class="hint">재방문 포함</div><div class="bignum num"><?=number_format($visit_total)?> <small>회</small></div></div>
			<div class="card hlcard"><h3>유입 → 신청 전환율</h3><div class="hint">신청 <?=number_format($write[cp_order])?> / 순방문자</div><div class="bignum num" style="color:var(--accent);"><?=$rate_visit_signup?><small>%</small></div></div>
			<div class="card"><h3>신규 vs 재방문</h3><div class="hint">IP 기준 추정 (재방문 <?=$rate_return?>%)</div><div class="chart-box" style="height:175px;"><canvas id="visit_chart"></canvas></div></div>
		</div>
		<div class="grid g2" style="margin-top:14px;">
			<div class="card"><h3>국내 vs 해외 (방문)</h3><div class="hint">한국 IP 대역 기준</div><div class="chart-box" style="height:175px;"><canvas id="geo_chart"></canvas></div></div>
			<div class="card<?=($rate_ovs>20?' hlcard':'')?>"><h3>해외 유입 비중</h3><div class="hint">비정상적으로 높으면 봇·클릭어뷰징 의심 신호</div><div class="bignum num" style="color:<?=($rate_ovs>20?'#dc2626':'var(--accent)')?>;"><?=$rate_ovs?><small>%</small></div><div class="hint" style="margin-top:8px;">해외 <?=number_format($geo_ovs)?>회 / 전체 <?=number_format($geo_total)?>회</div></div>
		</div>
	</div>

	<!-- 유입 기여 분석 (리뷰어별) -->
	<div class="sec">
		<div class="sec-h"><span class="bar"></span><h2>유입 기여 분석 · 리뷰어별</h2><span class="desc">유입경로 기록 적용 후 신규 신청부터 집계</span></div>
		<?php if($inflow_signup_total > 0){ ?>
		<div class="card">
			<h3>리뷰어별 유입 → 신청</h3><div class="hint">유입(방문)을 실제 신청으로 연결한 리뷰어 · 상위 15명</div>
			<div style="overflow-x:auto; margin-top:8px;">
			<table class="mc-tbl">
				<thead><tr><th>리뷰어</th><th>유입(방문)</th><th>신청</th><th>전환율</th></tr></thead>
				<tbody>
				<?php foreach($inflow_rows as $r){ ?>
					<tr><td class="who"><b><?=$r['name']?></b></td><td class="num"><?=number_format($r['visit'])?></td><td class="num"><?=number_format($r['signup'])?></td><td><span class="badge-lv"><?=$r['rate']?>%</span></td></tr>
				<?php } ?>
				</tbody>
			</table>
			</div>
		</div>
		<div class="grid g2" style="margin-top:14px;">
			<div class="card"><h3>신청 유입 매체</h3><div class="hint">신청자가 어느 매체 리뷰어를 통해 들어왔나</div><div class="chart-box"><canvas id="inflow_media_chart"></canvas></div></div>
			<div class="card hlcard"><h3>유입경로 식별률</h3><div class="hint">전체 신청 중 유입 리뷰어가 파악된 비율</div><div class="bignum num" style="color:var(--accent);"><?=($write[cp_order]>0?round($inflow_signup_total/$write[cp_order]*100):0)?><small>%</small></div><div class="hint" style="margin-top:8px;">식별 <?=number_format($inflow_signup_total)?>건 / 전체 신청 <?=number_format($write[cp_order])?>건</div></div>
		</div>
		<?php } else { ?>
		<div class="card" style="text-align:center; color:var(--muted); padding:34px 18px;">아직 집계된 유입 기여 데이터가 없습니다.<br><span style="font-size:12px;">이 기능(유입경로 기록) 적용 후의 <b>신규 신청</b>부터 리뷰어별·매체별 유입→신청이 기록됩니다. 추적링크에 ref 적용(다음 단계) 시 정확도가 올라갑니다.</span></div>
		<?php } ?>
	</div>

	<!-- 신청자 목록 -->
	<div class="sec">
		<div class="sec-h"><span class="bar"></span><h2>신청자 목록</h2></div>

		<!-- ▼ 리뷰 등록 현황 2단 분리 (2026-06-07) ▼ -->
		<?php
		$rv_done=array(); $rv_todo=array();
		$rs_split=sql_query("select * from nfor_review where rv_cp_id='$write[cp_id]' and rv_delete='0'");
		// 회원정보 N+1 제거: 행 버퍼 후 회원 name/hp 1쿼리(IN) 선로드
		$__split_rows=array(); $__split_mb=array();
		while($r_s=sql_fetch_array($rs_split)){ $__split_rows[]=$r_s; if($r_s[rv_mb_no]) $__split_mb[$r_s[rv_mb_no]]=1; }
		$__split_minfo=array();
		if(count($__split_mb)){
			$__sq=sql_query("select mb_no, mb_name, mb_hp from nfor_member where mb_no in ('".implode("','", array_keys($__split_mb))."')");
			while($__sm=sql_fetch_array($__sq)) $__split_minfo[$__sm[mb_no]]=$__sm;
		}
		foreach($__split_rows as $r_s){
			$m_s=$__split_minfo[$r_s[rv_mb_no]];
			$r_s['_name']=$m_s[mb_name]; $r_s['_hp']=$m_s[mb_hp];
			if($r_s[rv_url]){ $rv_done[]=$r_s; }                       // 리뷰 등록 완료
			elseif($r_s[rv_step]=='2'){ $rv_todo[]=$r_s; }            // 선정됐으나 리뷰 미등록 = 독촉 대상 (단순 신청자/미선정자 제외)
		}
		?>
		<style>
		.rv-split{ display:grid; grid-template-columns:1fr 1fr; gap:16px; margin:6px 0 22px; }
		.rv-col{ border:1px solid #e6eaf0; border-radius:14px; overflow:hidden; background:#fff; box-shadow:0 1px 2px rgba(15,23,42,.04); }
		.rv-col-h{ display:flex; align-items:center; gap:10px; padding:12px 16px; border-bottom:1px solid #eef1f5; font-size:14px; flex-wrap:wrap; }
		.rv-col-done .rv-col-h{ background:#ecfdf5; } .rv-col-todo .rv-col-h{ background:#fff7ed; }
		.rv-col-h .cnt{ font-weight:800; color:#0d9488; } .rv-col-h .cnt.warn{ color:#dc2626; }
		.rv-col-b{ max-height:420px; overflow:auto; padding:6px 0; }
		.rv-item{ padding:9px 16px; border-bottom:1px solid #f3f5f8; font-size:13px; }
		.rv-item:last-child{ border-bottom:0; }
		.rv-nm b{ color:#0f172a; } .rv-ch{ color:#94a3b8; font-size:11.5px; margin-left:6px; word-break:break-all; }
		.rv-hp{ color:#475569; font-size:12px; margin-left:6px; }
		.rv-url{ display:inline-block; margin-top:3px; color:#0d9488; font-size:12px; font-weight:700; }
		.rv-chk{ display:flex; align-items:flex-start; gap:8px; cursor:pointer; }
		.rv-empty{ padding:24px; text-align:center; color:#94a3b8; font-size:13px; }
		.rv-send-btn{ margin-left:auto; border:1px solid #fecaca; background:#fee2e2; color:#b91c1c; border-radius:999px; padding:6px 13px; font-size:12px; font-weight:700; cursor:not-allowed; opacity:.7; }
		.rv-send-btn.live{ cursor:pointer; opacity:1; background:#dc2626; border-color:#dc2626; color:#fff; }
		.rv-send-btn.live:hover{ background:#b91c1c; }
		.rv-allchk{ font-size:12px; color:#475569; cursor:pointer; display:inline-flex; align-items:center; gap:4px; }
		@media(max-width:760px){ .rv-split{ grid-template-columns:1fr; } }
		</style>
		<div class="rv-split">
			<div class="rv-col rv-col-done">
				<div class="rv-col-h"><b>✅ 리뷰 등록 완료</b> <span class="cnt"><?=count($rv_done)?>명</span></div>
				<div class="rv-col-b">
					<?php if(!$rv_done){ ?><div class="rv-empty">아직 등록자가 없습니다</div><?php } ?>
					<?php foreach($rv_done as $d){ ?>
					<div class="rv-item">
						<div class="rv-nm"><b><?=htmlspecialchars($d['_name'])?></b><span class="rv-ch"><?=htmlspecialchars($d[rv_channel])?></span></div>
						<a href="<?=htmlspecialchars($d[rv_url])?>" target="_blank" class="rv-url">리뷰글 보기 ↗</a>
					</div>
					<?php } ?>
				</div>
			</div>
			<div class="rv-col rv-col-todo">
				<div class="rv-col-h"><b>⏳ 선정자 중 리뷰 미등록</b> <span class="cnt warn"><?=count($rv_todo)?>명</span>
					<?php if($rv_todo){ ?>
					<label class="rv-allchk"><input type="checkbox" id="rv_chk_all"> 전체선택</label>
					<button type="button" id="rv_send_btn" class="rv-send-btn live">🔔 선택자에게 리뷰 독촉 알림톡 발송</button>
					<?php } ?>
				</div>
				<div class="rv-col-b">
					<?php if(!$rv_todo){ ?><div class="rv-empty">선정자 모두 리뷰 등록 완료 🎉<br><span style="font-size:11px;">(독촉할 대상이 없습니다)</span></div><?php } ?>
					<?php foreach($rv_todo as $t){ ?>
					<div class="rv-item">
						<label class="rv-chk"><input type="checkbox" class="rv-todo-chk" data-rv_id="<?=$t[rv_id]?>" data-hp="<?=htmlspecialchars($t['_hp'])?>" data-name="<?=htmlspecialchars($t['_name'])?>">
						<span class="rv-nm"><b><?=htmlspecialchars($t['_name'])?></b><span class="rv-hp"><?=$t['_hp']?htmlspecialchars($t['_hp']):'(번호없음)'?></span></span></label>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<!-- ▲ 리뷰 등록 현황 2단 분리 ▲ -->
		<script>
		(function(){
			var all=document.getElementById('rv_chk_all');
			if(all) all.addEventListener('change',function(){ var c=this.checked; document.querySelectorAll('.rv-todo-chk').forEach(function(x){x.checked=c;}); });
			var btn=document.getElementById('rv_send_btn');
			if(!btn) return;
			btn.addEventListener('click',function(){
				var ids=[], names=[];
				document.querySelectorAll('.rv-todo-chk:checked').forEach(function(x){ ids.push(x.getAttribute('data-rv_id')); names.push(x.getAttribute('data-name')); });
				if(!ids.length){ alert('발송할 미등록자를 선택해주세요.'); return; }
				var preview=names.slice(0,8).join(', ')+(names.length>8?(' 외 '+(names.length-8)+'명'):'');
				if(!confirm('⚠️ 리뷰 독촉 알림톡 발송\n\n대상 '+ids.length+'명: '+preview+'\n\n발송당 비용이 발생합니다. 발송하시겠습니까?')) return;
				btn.disabled=true; var old=btn.textContent; btn.textContent='발송 등록 중...';
				var fd=new FormData(); fd.append('mode','send_review_reminder'); fd.append('cp_id','<?=$write[cp_id]?>');
				ids.forEach(function(i){ fd.append('rv_id[]', i); });
				fetch(location.pathname+location.search, {method:'POST', body:fd, credentials:'same-origin'})
					.then(function(r){ return r.json(); })
					.then(function(res){ alert((res&&res.msg)||'발송 등록 완료'); location.reload(); })
					.catch(function(){ alert('발송 요청 실패. 다시 시도해주세요.'); btn.disabled=false; btn.textContent=old; });
			});
		})();
		</script>

		<form name="flist" id="flist" method="post">
		<?=admin_get()?>
		<div class="tbl-wrap">
		<table class="mc-tbl">
			<thead>
			<tr>
				<th><?=admin_checkbox($row,"chkall")?> 신청번호/이름</th>
				<th>등급</th>
				<th>회원정보</th>
				<th>신청 채널 (리뷰 URL)</th>
				<th>신청일 / 선정일</th>
				<th>리뷰 등록 / 확인</th>
				<th>상태</th>
				<th>네이버 블로그 방문</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$result = sql_query("select * from nfor_review where rv_cp_id='$write[cp_id]'");
			// 회원정보 N+1 제거: 신청자 회원 1쿼리(IN) 선로드 (루프 내 행별 조회 대체)
			$__tbl_mb = array(); $__tbl_ids = array();
			$__tq0 = sql_query("select distinct rv_mb_no from nfor_review where rv_cp_id='$write[cp_id]'");
			while($__tx = sql_fetch_array($__tq0)){ if($__tx[rv_mb_no]) $__tbl_ids[$__tx[rv_mb_no]] = 1; }
			if(count($__tbl_ids)){
				$__tq1 = sql_query("select * from nfor_member where mb_no in ('".implode("','", array_keys($__tbl_ids))."')");
				while($__tm = sql_fetch_array($__tq1)) $__tbl_mb[$__tm[mb_no]] = $__tm;
			}
			for($i=0; $row=sql_fetch_array($result); $i++){

				//회원정보 추출
				$result_mem = isset($__tbl_mb[$row[rv_mb_no]]) ? $__tbl_mb[$row[rv_mb_no]] : array();

				//네이버 블로그ID 추출
				if(strpos($row[rv_channel], "blog.naver.com") == true) {
					$blog_id = substr(parse_url($row[rv_channel], PHP_URL_PATH), 1);
					$blog_id = str_replace("/", "", $blog_id);
				} else {
					$blog_id = "";
				}

				//리뷰어 선정
				$row["chk[]"] = $i;
				$row["rv_id[{$i}]"] = $row[rv_id];

				//상태 판정 (표시용 — 기존 일정 데이터 기반)
				if($row[rv_confirm_datetime] && substr($row[rv_confirm_datetime],0,4)!="0000"){ $st="ok"; $st_t="완료"; }
				elseif($row[rv_reg_datetime] && substr($row[rv_reg_datetime],0,4)!="0000"){ $st="review"; $st_t="검수중"; }
				elseif($row[rv_asign_datetime] && substr($row[rv_asign_datetime],0,4)!="0000"){ $st="asign"; $st_t="선정"; }
				else { $st="wait"; $st_t="대기"; }

				$lv_name = $admin[mb_level][$result_mem[mb_level]] ? $admin[mb_level][$result_mem[mb_level]] : $result_mem[mb_level];
			?>
			<tr class="<?php echo $blog_id;?>">
				<td class="who">
					<?=admin_checkbox($row,"chk[]","chk")?><?=admin_hidden($row,"rv_id[{$i}]")?>
					<b><?=$result_mem[mb_name]?></b><br><span class="id num">#<?=$row[rv_id]?></span>
				</td>
				<td><span class="badge-lv"><?=$lv_name?></span></td>
				<td><?=campaign_member_info($row)?></td>
				<td>
					<a href="<?=$row[rv_channel]?>" target="_blank" class="lnk"><?=$row[rv_channel]?></a>
					<?php if($row[rv_url]){ ?><br><a href="<?=$row[rv_url]?>" target="_blank" class="lnk">(리뷰글 보기 &#8599;)</a><?php } else { ?><br><span class="dt s">(미등록)</span><?php } ?>
				</td>
				<td class="dt num"><?=substr($row[rv_datetime],0,10)?><br><span class="s"><?=substr($row[rv_asign_datetime],0,10)?></span></td>
				<td class="dt num"><?=substr($row[rv_reg_datetime],0,10)?><br><span class="s"><?=substr($row[rv_confirm_datetime],0,10)?></span></td>
				<td><span class="tag <?=$st?>"><?=$st_t?></span></td>
				<td>
				<?php if($blog_id){ ?>
					<div class="blogbox">
						<canvas id="blog_chart_<?php echo $blog_id;?>" data-ref="<?php echo $blog_id;?>"></canvas>
						<span id="blog_totalcount_<?php echo $blog_id;?>" class="total"></span>
					</div>
				<?php } ?>
				</td>
			</tr>
			<?php } ?>
			</tbody>
		</table>
		</div>
		</form>
	</div>

</div>
</div>


<div class="mc-actions">
	<?php if($member[mb_admin] >= $config[cf_review_cancel]){ ?>
	<?=admin_button("list_cancel", "리뷰어 미선정", "btn1 btn-lg btn-black")?>
	<?php } ?>
	<?php if($member[mb_admin] >= $config[cf_review_asign]){ ?>
	<?=admin_button("list_asign", "리뷰어 선정", "btn1 btn-lg btn-red")?>
	<?php } ?>
</div>


<script>
/* ============================================================
   캠페인 결과 보고서 — 차트 (Chart.js v2.9.3)
   * 데이터는 PHP 집계값 그대로. 모양(색/축/범례)만 모던화.
   ============================================================ */
(function(){
	var PAL = ['#4f46e5','#06b6d4','#f59e0b','#10b981','#ef4444','#8b5cf6','#ec4899','#14b8a6','#64748b'];
	if(window.Chart && Chart.defaults && Chart.defaults.global){
		Chart.defaults.global.defaultFontFamily = "-apple-system,BlinkMacSystemFont,'Apple SD Gothic Neo','Malgun Gothic',sans-serif";
		Chart.defaults.global.defaultFontColor = '#64748b';
		Chart.defaults.global.defaultFontSize = 12;
	}
	function gridOpts(horizontal, titleText){
		return {
			responsive:true, maintainAspectRatio:false,
			legend:{display:false},
			title:{display:false, text:titleText},
			tooltips:{backgroundColor:'#0f172a', cornerRadius:8, padding:10, displayColors:false},
			scales:{
				xAxes:[{gridLines:{display:!!horizontal, color:'#eef1f5', drawBorder:false}, ticks:{fontColor:'#94a3b8'}}],
				yAxes:[{gridLines:{display:!horizontal, color:'#eef1f5', drawBorder:false}, ticks:{fontColor:'#94a3b8', beginAtZero:true}}]
			}
		};
	}
	function pieOpts(){
		return {responsive:true, maintainAspectRatio:false, cutoutPercentage:62,
			legend:{position:'bottom', labels:{boxWidth:10, padding:14, fontColor:'#475569'}},
			tooltips:{backgroundColor:'#0f172a', cornerRadius:8, padding:10}};
	}
	function el(id){ return document.getElementById(id); }
	function mkRound(id, type, labels, data){
		if(!el(id)) return;
		new Chart(el(id), {type:type, data:{labels:labels, datasets:[{data:data, backgroundColor:PAL, borderWidth:2, borderColor:'#fff'}]}, options:pieOpts()});
	}
	function mkBar(id, labels, data, color, horizontal){
		if(!el(id)) return;
		new Chart(el(id), {type:horizontal?'horizontalBar':'bar',
			data:{labels:labels, datasets:[{data:data, backgroundColor:color, maxBarThickness:horizontal?22:34}]},
			options:gridOpts(horizontal)});
	}

	/* === PHP 집계 데이터 (원본과 동일) === */
	var sex_l   = [<?php for($i=0;$i<count($sex_data[text]);$i++){ echo "'".$sex_data[text][$i]."',"; }?>];
	var sex_d   = [<?php for($i=0;$i<count($sex_data[text]);$i++){ echo $sex_data[cnt][$i].","; }?>];
	var level_l = [<?php for($i=0;$i<count($level_data[text]);$i++){ echo "'".$level_data[text][$i]."',"; }?>];
	var level_d = [<?php for($i=0;$i<count($level_data[text]);$i++){ echo $level_data[cnt][$i].","; }?>];
	var age_l   = [<?php for($i=0;$i<count($age_data[text]);$i++){ echo "'".$age_data[text][$i]."',"; }?>];
	var age_d   = [<?php for($i=0;$i<count($age_data[text]);$i++){ echo $age_data[cnt][$i].","; }?>];
	var area_l  = [<?php for($i=0;$i<count($area_data[text]);$i++){ echo "'".$area_data[text][$i]."',"; }?>];
	var area_d  = [<?php for($i=0;$i<count($area_data[text]);$i++){ echo $area_data[cnt][$i].","; }?>];
	var dev_l   = [<?php for($i=0;$i<count($device_data[text]);$i++){ echo "'".$device_data[text][$i]."',"; }?>];
	var dev_d   = [<?php for($i=0;$i<count($device_data[text]);$i++){ echo $device_data[cnt][$i].","; }?>];
	var time_l  = [<?php for($i=0;$i<count($time_data[text]);$i++){ echo "'".$time_data[text][$i]."',"; }?>];
	var time_d  = [<?php for($i=0;$i<count($time_data[text]);$i++){ echo $time_data[cnt][$i].","; }?>];
	var dayw_l  = [<?php for($i=0;$i<count($dayw_data[text]);$i++){ echo "'".$dayw_data[text][$i]."',"; }?>];
	var dayw_d  = [<?php for($i=0;$i<count($dayw_data[text]);$i++){ echo $dayw_data[cnt][$i].","; }?>];
	var month_l = [<?php for($i=0;$i<count($month_data[text]);$i++){ echo "'".$month_data[text][$i]."',"; }?>];
	var month_d = [<?php for($i=0;$i<count($month_data[text]);$i++){ echo $month_data[cnt][$i].","; }?>];
	var day_l   = [<?php for($i=0;$i<count($day_data[text]);$i++){ echo "'".$day_data[text][$i]."',"; }?>];
	var day_d   = [<?php for($i=0;$i<count($day_data[text]);$i++){ echo $day_data[cnt][$i].","; }?>];
	var blog_l  = [<?php for($i=0;$i<count($blogid_data[text]);$i++){ echo "'".$blogid_data[text][$i]."',"; }?>];
	var blog_d  = [<?php for($i=0;$i<count($blogid_data[text]);$i++){ echo $blogid_data[cnt][$i].","; }?>];
	var media_l = [<?php for($i=0;$i<count($media_data[text]);$i++){ echo "'".$media_data[text][$i]."',"; }?>];
	var media_d = [<?php for($i=0;$i<count($media_data[text]);$i++){ echo $media_data[cnt][$i].","; }?>];
	var refsrc_l= [<?php for($i=0;$i<count($refsrc_data[text]);$i++){ echo "'".str_replace("'","",$refsrc_data[text][$i])."',"; }?>];
	var refsrc_d= [<?php for($i=0;$i<count($refsrc_data[text]);$i++){ echo $refsrc_data[cnt][$i].","; }?>];
	var visit_l = ['신규(추정)','재방문'];
	var visit_d = [<?=$visit_uniq?>, <?=$visit_return?>];
	var geo_l = ['국내','해외'];
	var geo_d = [<?=$geo_dom?>, <?=$geo_ovs?>];
	var inmedia_l = [<?php for($i=0;$i<count($inmedia_data[text]);$i++){ echo "'".str_replace("'","",$inmedia_data[text][$i])."',"; }?>];
	var inmedia_d = [<?php for($i=0;$i<count($inmedia_data[text]);$i++){ echo $inmedia_data[cnt][$i].","; }?>];

	window.onload = function(){
		mkRound('sex_chart',   'doughnut', sex_l,   sex_d);
		mkRound('level_chart', 'pie',      level_l, level_d);
		mkBar('age_chart',  age_l,  age_d,  '#8b5cf6', false);
		mkBar('area_chart', area_l, area_d, '#06b6d4', true);

		mkRound('device_chart', 'doughnut', dev_l, dev_d);
		mkBar('dayw_chart',  dayw_l,  dayw_d,  '#10b981', false);
		mkBar('time_chart',  time_l,  time_d,  '#4f46e5', false);
		mkBar('day_chart',   day_l,   day_d,   '#f59e0b', false);
		mkBar('month_chart', month_l, month_d, '#4f46e5', false);
		mkBar('blogid_chart',blog_l,  blog_d,  '#4f46e5', true);

		mkRound('media_chart', 'doughnut', media_l, media_d);
		mkBar('refsrc_chart', refsrc_l, refsrc_d, '#06b6d4', true);

		mkRound('visit_chart', 'doughnut', visit_l, visit_d);
		mkRound('geo_chart', 'doughnut', geo_l, geo_d);
		mkRound('inflow_media_chart', 'doughnut', inmedia_l, inmedia_d);
	};
})();
</script>


<script>
	//블로그 최근 5일 방문자
	$("[id*='blog_chart_']").each(function(){

		var id = $(this).attr('id');
		var blog_id = $(this).data('ref');

		$.ajax({
			type     : "post",
			url      : nfor_path + "/admin/ajax_blog_count_chart.php",
			data     : "blog_id="+blog_id,
			async    : false,
			success  : function(data) {
				var arr = data.split("|:|");

				labels = arr[1].split(',');
				datas = arr[2].split(',');
				total_count = arr[3];

				if(arr[0] == 'SUCCESS') {

					var blog_data = {
						labels: labels,
						datasets: [{
							label: '최근 5일간 방문자 수',
							backgroundColor: '#c7d2fe',
							data: datas
						} ]
					};
					respondCanvas(id, blog_data);

					$('#blog_totalcount_'+blog_id).html('총 ' + total_count + '명');
				} else {

					$('#blog_totalcount_'+blog_id).html('데이터 없음');
				}

			},
			error: function(){
				console.log("Ajax failed");
			}
		});
	});

	function respondCanvas(id, blog_data) {
		var blog_chart = document.getElementById(id).getContext('2d');
		window.myBar = new Chart(blog_chart, {
			type: 'bar',
			data: blog_data,
			options: {
				legend:{display:false},
				tooltips: { mode: 'index', intersect: false, backgroundColor:'#0f172a', cornerRadius:6 },
				responsive: true,
				maintainAspectRatio: true,
				scales: {
					xAxes: [{ display:false, stacked: true, gridLines:{display:false} }],
					yAxes: [{ display:false, stacked: true, gridLines:{display:false}, ticks:{beginAtZero:true} }]
				}
			}
		});
	}
</script>


<script type="text/javascript">
<!--
$(document).on("click", "#chkall", function(){
	nfor_chk_all(this, 'chk');
});

$(document).on("click", "#list_asign", function(){
	nfor_list_reload('리뷰어 선정','list_asign');
});

$(document).on("click", "#list_cancel", function(){
	nfor_list_reload('리뷰어 미선정','list_cancel');
});
//-->
</script>
<?php
include_once "tail.php";
?>
