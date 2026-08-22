<?php
/* 광고주용 결과보고서 생성 모듈.
 * 관리자 화면·일일 크론·리뷰 승인 흐름이 이 인터페이스 하나를 같이 쓴다. */
include_once dirname(dirname(__FILE__))."/mc_share.php";

function mc_campaign_report_result($ok, $error="", $extra=array()){
	return array_merge(array("ok"=>(bool)$ok, "error"=>(string)$error), $extra);
}

function mc_campaign_report_query($sql){
	$result = sql_query($sql, false);
	if($result === false) throw new Exception("보고서 집계 DB 조회에 실패했습니다");
	return $result;
}

function mc_campaign_report_fetch($sql){
	$result = mc_campaign_report_query($sql);
	return sql_fetch_array($result);
}

function mc_campaign_report_mask_name($n){
	$n = trim($n);
	$len = mb_strlen($n, 'UTF-8');
	if($len <= 1) return $n ? $n : "비공개";
	if($len == 2) return mb_substr($n,0,1,'UTF-8')."○";
	return mb_substr($n,0,1,'UTF-8').str_repeat("○",$len-2).mb_substr($n,$len-1,1,'UTF-8');
}

function mc_campaign_report_http_url($url){
	$url = trim((string)$url);
	if($url === "" || !filter_var($url, FILTER_VALIDATE_URL)) return "";
	$scheme = strtolower((string)parse_url($url, PHP_URL_SCHEME));
	return ($scheme === "http" || $scheme === "https") ? $url : "";
}

function mc_campaign_report_base_url(){
	global $config;
	$base = isset($config['cf_url']) ? trim($config['cf_url']) : "";
	if($base !== "" && preg_match('#^https?://#i', $base)) return rtrim($base, "/");
	$host = isset($_SERVER['HTTP_HOST']) ? preg_replace('/[^A-Za-z0-9.:-]/', '', $_SERVER['HTTP_HOST']) : "";
	if($host === "") $host = "meta-chehumdan.com";
	$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off') ? "https" : "http";
	return $scheme."://".$host;
}

function mc_campaign_report_canonical_path($cp_id){
	$cp_id = preg_replace('/[^0-9]/', '', $cp_id);
	if($cp_id === "" || !function_exists('mc_share_token')) return "";
	$webroot = dirname(dirname(__FILE__));
	return $webroot."/report/cr_".$cp_id."_".mc_share_token($cp_id).".html";
}

function mc_campaign_report_existing_paths($cp_id){
	$cp_id = preg_replace('/[^0-9]/', '', $cp_id);
	if($cp_id === "") return array();
	$dir = dirname(dirname(__FILE__))."/report";
	$paths = glob($dir."/cr_".$cp_id."_*.html");
	if(!is_array($paths)) return array();
	$paths = array_values(array_filter($paths, 'is_file'));
	usort($paths, function($a, $b){ return @filemtime($b) - @filemtime($a); });
	return $paths;
}

function mc_campaign_report_path($cp_id){
	$canonical = mc_campaign_report_canonical_path($cp_id);
	if($canonical !== "" && is_file($canonical)) return $canonical;
	$existing = mc_campaign_report_existing_paths($cp_id);
	return !empty($existing) ? $existing[0] : $canonical;
}

function mc_campaign_report_exists($cp_id){
	$path = mc_campaign_report_path($cp_id);
	return $path !== "" && is_file($path);
}

function mc_campaign_report_write_atomic($path, $html){
	$dir = dirname($path);
	$tmp = @tempnam($dir, ".mc_report_");
	if($tmp === false) return mc_campaign_report_result(false, "임시 보고서 파일을 만들 수 없습니다");
	$written = @file_put_contents($tmp, $html, LOCK_EX);
	if($written === false || $written < strlen($html)){
		@unlink($tmp);
		return mc_campaign_report_result(false, "보고서 파일을 끝까지 저장하지 못했습니다");
	}
	@chmod($tmp, 0644);
	if(!@rename($tmp, $path)){
		@unlink($tmp);
		return mc_campaign_report_result(false, "새 보고서 파일로 교체하지 못했습니다");
	}
	return mc_campaign_report_result(true);
}

function mc_campaign_report_metatech_rows($cp_id){
	$cp_id = preg_replace('/[^0-9]/', '', $cp_id);
	if(!$cp_id) return array();
	if(!mc_campaign_report_fetch("show tables like 'nfor_metatech'")) return array();
	$has_users = (bool)mc_campaign_report_fetch("show tables like 'nfor_metatech_user'");
	$rows = array();
	$query = mc_campaign_report_query("select * from nfor_metatech where m_cp_id='$cp_id' order by idx desc");
	while($mission = sql_fetch_array($query)){
		$count = array("c"=>0, "done"=>0, "wait"=>0);
		if($has_users){
			$idx = (int)$mission['idx'];
			$count = mc_campaign_report_fetch("select count(*) as c, "
				."sum(case when status='1' then 1 else 0 end) as done, "
				."sum(case when status='0' then 1 else 0 end) as wait "
				."from nfor_metatech_user where connect_idx='$idx'");
		}
		$mission['join_cnt'] = (int)$count['c'];
		$mission['done_cnt'] = (int)$count['done'];
		$mission['wait_cnt'] = (int)$count['wait'];
		$rows[] = $mission;
	}
	return $rows;
}

function mc_campaign_report_generate($cp_id, $include_metatech=false){
	try {
		return mc_campaign_report_generate_inner($cp_id, $include_metatech);
	} catch(Exception $e) {
		return mc_campaign_report_result(false, $e->getMessage(), array("cp_id"=>(int)$cp_id));
	}
}

function mc_campaign_report_generate_inner($cp_id, $include_metatech=false){
	global $admin;
	$cp_id = preg_replace('/[^0-9]/', '', $cp_id);
	if(!$cp_id) return mc_campaign_report_result(false, "캠페인 번호가 없습니다");

	$write = mc_campaign_report_fetch("select * from nfor_campaign where cp_id='$cp_id'");
	if(!$write[cp_id]) return mc_campaign_report_result(false, "캠페인을 찾을 수 없습니다");
	$chart_path = dirname(dirname(__FILE__))."/js/Chart.min.js";
	if(!is_file($chart_path) || !is_readable($chart_path)) return mc_campaign_report_result(false, "Chart.min.js 파일을 읽을 수 없습니다");

// ── 메타테크 참여 현황 (선택 포함) 2026-06-16 ──
$inc_mt  = (bool)$include_metatech;
$mt_rows = mc_campaign_report_metatech_rows($cp_id);
$mt_has  = !empty($mt_rows);

$sex_data=$age_data=$area_data=$level_data=$device_data=$time_data=$dayw_data=$month_data=$day_data=$blogid_data=array("text"=>array(),"cnt"=>array());

/* ===== 집계 데이터 (campaign_report.php 와 동일 로직) ===== */
// 성별
$que = mc_campaign_report_query("select rv_mb_sex, count(*) as cnt from nfor_review where rv_cp_id='$write[cp_id]' and rv_delete='0' group by rv_mb_sex");
while($data = sql_fetch_array($que)){
	if($data[rv_mb_sex]=="M"){ $text="남자"; } elseif($data[rv_mb_sex]=="F"){ $text="여자"; } else{ $text="미입력"; }
	$sex_data[text][]=$text; $sex_data[cnt][]=$data[cnt];
}
// 연령별
$que = mc_campaign_report_query("select rv_mb_age, count(*) as cnt from nfor_review where rv_cp_id='$write[cp_id]' and rv_delete='0' group by rv_mb_age");
while($data = sql_fetch_array($que)){
	$age_data[text][]=$data[rv_mb_age]?$data[rv_mb_age]."대":"미입력"; $age_data[cnt][]=$data[cnt];
}
// 지역별
$que = mc_campaign_report_query("select rv_mb_area, count(*) as cnt from nfor_review where rv_cp_id='$write[cp_id]' and rv_delete='0' group by rv_mb_area");
while($data = sql_fetch_array($que)){
	$area_data[text][]=$data[rv_mb_area]?$data[rv_mb_area]:"미입력"; $area_data[cnt][]=$data[cnt];
}
// 등급명 맵 + 레벨별
$admin[mb_level][""]="미입력";
$que = mc_campaign_report_query("select * from nfor_level where 1 order by lv_rank asc");
while($row = sql_fetch_array($que)){ $admin[mb_level][$row[lv_id]]=$row[lv_name]; }
$que = mc_campaign_report_query("select rv_mb_level, count(*) as cnt from nfor_review where rv_cp_id='$write[cp_id]' and rv_delete='0' group by rv_mb_level");
while($data = sql_fetch_array($que)){ $level_data[text][]=$admin[mb_level][$data[rv_mb_level]]; $level_data[cnt][]=$data[cnt]; }

// 트래픽 테이블
$traffic_y_w = date("Y_W",strtotime($write[cp_insert_datetime]));
$traffic_table = "nfor_traffic_".$traffic_y_w;
$traffic_exists = mc_campaign_report_fetch("show tables like '".addslashes($traffic_table)."'");
if($traffic_exists){
	// 디바이스
	$que = mc_campaign_report_query("select tr_device, count(*) as tot_device_count from {$traffic_table} where tr_cp_id = '$write[cp_id]' group by tr_device;");
	while($data = sql_fetch_array($que)){ $text=($data[tr_device]=="pc")?"PC":"MOBILE"; $device_data[text][]=$text; $device_data[cnt][]=$data[tot_device_count]; }
	// 시간대
	$sql = "select ifNull(T2.hour, T1.n) as hour, ifNull(T2.tot_hour_count, 0) as tot_hour_count
from ( select @N:=@N+1 as n from {$traffic_table}, (select @N:=-1 from dual) NN limit 24 ) as T1
left outer join ( select hour(tr_datetime) as hour, count(*) as tot_hour_count from {$traffic_table} where tr_cp_id = '$write[cp_id]' group by hour ) as T2 on T1.n = T2.hour
order by T1.n asc";
	$que = mc_campaign_report_query($sql);
	while($data = sql_fetch_array($que)){ $time_data[text][]=$data[hour]."시"; $time_data[cnt][]=$data[tot_hour_count]; }
	// 요일
	$sql = "select case dayofweek(tr_datetime) when 1 then '일요일' when 2 then '월요일' when 3 then '화요일' when 4 then '수요일' when 5 then '목요일' when 6 then '금요일' when 7 then '토요일' end as DateRange, count(*) as tot_dayw_count from {$traffic_table} where tr_cp_id = '$write[cp_id]' group by dayofweek(tr_datetime)";
	$que = mc_campaign_report_query($sql);
	while($data = sql_fetch_array($que)){ $dayw_data[text][]=$data[DateRange]; $dayw_data[cnt][]=$data[tot_dayw_count]; }
	// 월별
	$sql = "SELECT DATE_FORMAT(tr_date, '%Y-%m') AS date, count(*) AS tot_month_count FROM {$traffic_table} where tr_cp_id = '$write[cp_id]' GROUP BY DATE_FORMAT(tr_date, '%Y-%m')";
	$que = mc_campaign_report_query($sql);
	while($data = sql_fetch_array($que)){ $month_data[text][]=$data[date]; $month_data[cnt][]=$data[tot_month_count]; }
	// 일별
	$sql = "SELECT tr_date AS date, count(*) AS tot_day_count FROM {$traffic_table} where tr_cp_id = '$write[cp_id]' GROUP BY tr_date order by tr_date asc";
	$que = mc_campaign_report_query($sql);
	while($data = sql_fetch_array($que)){ $day_data[text][]=$data[date]; $day_data[cnt][]=$data[tot_day_count]; }
	// 누적성과
	$sql = "SELECT count(*) AS tot_mem_count, B.mb_name FROM {$traffic_table} A inner join nfor_member B on A.tr_mb_no = B.mb_no where tr_cp_id = '$write[cp_id]' GROUP BY B.mb_name";
	$que = mc_campaign_report_query($sql);
	while($data = sql_fetch_array($que)){ $blogid_data[text][]=mc_campaign_report_mask_name($data[mb_name]); $blogid_data[cnt][]=$data[tot_mem_count]; }
}

/* ===== 표시용 파생값 ===== */
$rate_apply = ($write[cp_recruit] > 0) ? round($write[cp_order] / $write[cp_recruit] * 100) : 0;
$rate_post  = ($write[cp_review_asign] > 0) ? round($write[cp_review_post] / $write[cp_review_asign] * 100) : 0;

/* ===== 정적 HTML 생성 (광고주 열람용) ===== */
ob_start();
?><!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?=htmlspecialchars($write[cp_subject])?> · 캠페인 결과 보고서</title>
<meta name="robots" content="noindex, nofollow">
<script><?=file_get_contents($chart_path)?></script>
<style>
:root{--bg:#f1f5f9;--card:#fff;--line:#e8ebf0;--line2:#eef1f5;--ink:#0f172a;--ink2:#475569;--muted:#94a3b8;--accent:#4f46e5;--accent-soft:#eef2ff;--shadow:0 1px 2px rgba(15,23,42,.04),0 2px 8px rgba(15,23,42,.05);}
*{box-sizing:border-box;}
body{margin:0;background:var(--bg);color:var(--ink);line-height:1.55;-webkit-font-smoothing:antialiased;font-family:-apple-system,BlinkMacSystemFont,"Apple SD Gothic Neo","Pretendard","Malgun Gothic","맑은 고딕",sans-serif;}
.num{font-variant-numeric:tabular-nums;}
.wrap{max-width:1080px;margin:0 auto;padding:28px 20px 64px;}
.topbar{display:flex;align-items:center;gap:8px;font-size:12px;color:var(--muted);margin-bottom:14px;}
.topbar .ro{background:#0f172a;color:#fff;font-weight:700;padding:3px 10px;border-radius:999px;}
.mc-head{background:var(--card);border:1px solid var(--line);border-radius:14px;box-shadow:var(--shadow);padding:24px 26px;margin-bottom:18px;display:flex;justify-content:space-between;align-items:flex-start;gap:24px;flex-wrap:wrap;}
.eyebrow{font-size:12px;font-weight:700;letter-spacing:.12em;color:var(--accent);text-transform:uppercase;}
.mc-head h1{margin:8px 0 12px;font-size:24px;font-weight:800;letter-spacing:-.02em;line-height:1.3;}
.mc-head h1 small{display:block;font-size:14px;font-weight:500;color:var(--ink2);margin-top:4px;}
.chips{display:flex;gap:6px;flex-wrap:wrap;}
.chip{font-size:12px;font-weight:600;padding:5px 11px;border-radius:999px;border:1px solid var(--line);color:var(--ink2);background:#fff;}
.chip.code{background:#0f172a;color:#fff;border-color:#0f172a;}
.chip.point{background:var(--accent-soft);color:var(--accent);border-color:transparent;}
.chip.ch-blog{color:#16a34a;border-color:#bbf7d0;background:#f0fdf4;}
.chip.ch-insta{color:#db2777;border-color:#fbcfe8;background:#fdf2f8;}
.chip.ch-youtube{color:#dc2626;border-color:#fecaca;background:#fef2f2;}
.mc-head .gen{text-align:right;font-size:12px;color:var(--muted);white-space:nowrap;}
.mc-head .gen b{display:block;font-size:13px;color:var(--ink2);font-weight:700;}
.kpi{display:grid;grid-template-columns:repeat(6,1fr);gap:12px;margin-bottom:22px;}
.kpi>*{min-width:0;}
.kpi .cell{background:var(--card);border:1px solid var(--line);border-radius:12px;padding:16px;box-shadow:var(--shadow);}
.kpi .lab{font-size:12px;color:var(--ink2);font-weight:600;margin-bottom:8px;}
.kpi .val{font-size:25px;font-weight:800;letter-spacing:-.02em;}
.kpi .val span{font-size:13px;font-weight:600;color:var(--muted);margin-left:2px;}
.kpi .sub{font-size:11px;margin-top:5px;color:var(--muted);}
.kpi .sub b{color:#059669;font-weight:700;}
.kpi .cell.hl{background:linear-gradient(180deg,#fff,#fafaff);border-color:#e0e0fb;}
.kpi .cell.hl .val{color:var(--accent);}
.sec{margin-top:30px;}
.sec-h{display:flex;align-items:center;gap:10px;margin-bottom:14px;}
.sec-h .bar{width:4px;height:18px;border-radius:2px;background:var(--accent);}
.sec-h h2{margin:0;font-size:16px;font-weight:800;}
.sec-h .desc{font-size:12px;color:var(--muted);margin-left:auto;}
.steps{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;}
.steps>*{min-width:0;}
.step{position:relative;background:var(--card);border:1px solid var(--line);border-radius:12px;padding:16px;box-shadow:var(--shadow);}
.step .ph{font-size:12px;font-weight:700;color:var(--accent);margin-bottom:8px;display:flex;align-items:center;gap:6px;}
.step .ph .n{display:inline-flex;width:18px;height:18px;border-radius:50%;background:var(--accent-soft);color:var(--accent);font-size:11px;align-items:center;justify-content:center;font-weight:800;}
.step .date{font-size:13px;font-weight:700;color:var(--ink);}
.step .cnt{font-size:12px;color:var(--ink2);margin-top:6px;}
.step .cnt b{color:var(--accent);font-weight:800;font-size:15px;}
.step:not(:last-child):after{content:"\203A";position:absolute;right:-10px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:20px;}
.grid{display:grid;gap:14px;}
.grid.g2{grid-template-columns:repeat(2,1fr);}
.grid.g4{grid-template-columns:repeat(4,1fr);}
.grid>*{min-width:0;}
canvas{max-width:100%;}
.card{background:var(--card);border:1px solid var(--line);border-radius:12px;box-shadow:var(--shadow);padding:18px 18px 14px;min-width:0;}
.card h3{margin:0 0 4px;font-size:13.5px;font-weight:700;}
.card .hint{font-size:11px;color:var(--muted);margin-bottom:10px;}
.chart-box{position:relative;height:230px;}
.chart-box.tall{height:260px;}
.span2{grid-column:span 2;}
.tbl-wrap{background:var(--card);border:1px solid var(--line);border-radius:12px;box-shadow:var(--shadow);overflow-x:auto;}
table.t{width:100%;border-collapse:collapse;font-size:13px;min-width:640px;}
table.t thead th{background:#f8fafc;color:var(--ink2);font-weight:700;font-size:12px;text-align:left;padding:12px 14px;border-bottom:1px solid var(--line);white-space:nowrap;}
table.t tbody td{padding:13px 14px;border-bottom:1px solid var(--line2);color:var(--ink2);}
table.t tbody tr:last-child td{border-bottom:none;}
.badge-lv{display:inline-block;font-size:11px;font-weight:700;padding:2px 8px;border-radius:6px;background:var(--accent-soft);color:var(--accent);white-space:nowrap;}
.tag{display:inline-block;font-size:11px;font-weight:700;padding:2px 8px;border-radius:6px;white-space:nowrap;}
.tag.ok{background:#ecfdf5;color:#059669;}.tag.review{background:#eff6ff;color:#2563eb;}.tag.asign{background:#eef2ff;color:#4f46e5;}.tag.wait{background:#fff7ed;color:#d97706;}
.lnk{color:var(--accent);text-decoration:none;font-size:12px;word-break:break-all;}
.lnk:hover{text-decoration:underline;}
.dt{font-variant-numeric:tabular-nums;font-size:12px;}.dt .s{color:var(--muted);}
.foot{margin-top:34px;text-align:center;color:var(--muted);font-size:12px;line-height:1.8;}
.foot b{color:var(--ink2);}
@media(max-width:900px){.kpi{grid-template-columns:repeat(3,1fr);}.steps{grid-template-columns:repeat(2,1fr);}.grid.g2,.grid.g4{grid-template-columns:1fr;}.span2{grid-column:auto;}.step:after{display:none;}}
@media(max-width:640px){.wrap{padding:16px 12px 44px}.mc-head{padding:18px}.mc-head .gen{text-align:left}.kpi{grid-template-columns:repeat(2,minmax(0,1fr));gap:8px}.kpi .cell{padding:13px}.kpi .val{font-size:22px}.steps{grid-template-columns:1fr}.card{padding:15px}.sec{margin-top:22px}.sec-h{align-items:flex-start;flex-wrap:wrap}.sec-h .desc{width:100%;margin-left:14px}.tbl-wrap{max-width:100%}}
</style>
</head>
<body>
<div class="wrap">
	<div class="topbar"><span class="ro">읽기 전용</span> 광고주 열람용 캠페인 결과 보고서</div>

	<div class="mc-head">
		<div>
			<div class="eyebrow">캠페인 결과 보고서</div>
			<h1><?=htmlspecialchars($write[cp_subject])?><?php if($write[cp_description]){ ?><small><?=htmlspecialchars($write[cp_description])?></small><?php } ?></h1>
			<div class="chips">
				<span class="chip code"># <?=$write[cp_id]?></span>
				<?php if($nfor[cp_type][$write[cp_type]]){ ?><span class="chip"><?=$nfor[cp_type][$write[cp_type]]?></span><?php } ?>
				<?php if($write[cp_point]){ ?><span class="chip point">+ <?=number_format($write[cp_point])?>P</span><?php } ?>
				<? if($write[cp_media_blog]){ ?><span class="chip ch-blog">블로그</span><? } ?>
				<? if($write[cp_media_instagram]){ ?><span class="chip ch-insta">인스타그램</span><? } ?>
				<? if($write[cp_media_youtube]){ ?><span class="chip ch-youtube">유튜브</span><? } ?>
			</div>
		</div>
		<div class="gen"><b>메타체험단</b> 발행일 <?=date("Y-m-d")?></div>
	</div>

	<div class="kpi">
		<div class="cell"><div class="lab">조회수</div><div class="val num"><?=number_format($write[cp_click])?></div><div class="sub">캠페인 페이지</div></div>
		<div class="cell hl"><div class="lab">신청</div><div class="val num"><?=number_format($write[cp_order])?> <span>명</span></div><div class="sub">모집 <?=number_format($write[cp_recruit])?>명 대비 <b><?=$rate_apply?>%</b></div></div>
		<div class="cell"><div class="lab">모집</div><div class="val num"><?=number_format($write[cp_recruit])?> <span>명</span></div><div class="sub">목표 인원</div></div>
		<div class="cell"><div class="lab">선정</div><div class="val num"><?=number_format($write[cp_review_asign])?> <span>명</span></div><div class="sub">리뷰어 확정</div></div>
		<div class="cell"><div class="lab">리뷰 등록</div><div class="val num"><?=number_format($write[cp_review_post])?> <span>명</span></div><div class="sub">선정 대비 <b><?=$rate_post?>%</b></div></div>
		<div class="cell"><div class="lab">등록 확인</div><div class="val num"><?=number_format($write[cp_review_post_asign])?> <span>명</span></div><div class="sub">최종 완료</div></div>
	</div>

	<div class="sec">
		<div class="sec-h"><span class="bar"></span><h2>캠페인 일정</h2></div>
		<div class="steps">
			<div class="step"><div class="ph"><span class="n">1</span>신청 기간</div><div class="date num"><?=substr($write[cp_sdatetime],0,10)?> ~ <?=substr($write[cp_edatetime],0,10)?></div><div class="cnt">신청 <b class="num"><?=number_format($write[cp_review_wait])?></b>명</div></div>
			<div class="step"><div class="ph"><span class="n">2</span>선정자 발표</div><div class="date num"><?=substr($write[cp_pick_datetime],0,10)?></div><div class="cnt">선정 <b class="num"><?=number_format($write[cp_review_asign])?></b>명</div></div>
			<div class="step"><div class="ph"><span class="n">3</span>리뷰 등록</div><div class="date num"><?=substr($write[cp_contents_sdatetime],0,10)?> ~ <?=substr($write[cp_contents_edatetime],0,10)?></div><div class="cnt">등록 <b class="num"><?=number_format($write[cp_review_post])?></b>명</div></div>
			<div class="step"><div class="ph"><span class="n">4</span>결과 발표</div><div class="date num"><?=substr($write[cp_result_datetime],0,10)?></div><div class="cnt">확인 <b class="num"><?=number_format($write[cp_review_post_asign])?></b>명</div></div>
		</div>
	</div>

	<div class="sec">
		<div class="sec-h"><span class="bar"></span><h2>참여자 분석</h2><span class="desc">신청 리뷰어 기준</span></div>
		<div class="grid g4">
			<div class="card"><h3>성별</h3><div class="hint">신청자 성별 비율</div><div class="chart-box"><canvas id="sex_chart"></canvas></div></div>
			<div class="card"><h3>등급</h3><div class="hint">인플루언서 등급 분포</div><div class="chart-box"><canvas id="level_chart"></canvas></div></div>
			<div class="card"><h3>연령대</h3><div class="hint">신청자 연령 분포</div><div class="chart-box"><canvas id="age_chart"></canvas></div></div>
			<div class="card"><h3>지역</h3><div class="hint">신청자 거주 지역</div><div class="chart-box"><canvas id="area_chart"></canvas></div></div>
		</div>
	</div>

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

	<div class="sec">
		<div class="sec-h"><span class="bar"></span><h2>리뷰 등록 현황</h2><span class="desc">개인정보 보호를 위해 이름은 일부만 표시</span></div>
		<div class="tbl-wrap">
		<table class="t">
			<thead><tr><th>리뷰어</th><th>등급</th><th>리뷰 채널 / 게시글</th><th>등록일 / 확인일</th><th>상태</th></tr></thead>
			<tbody>
			<?php
			$result = mc_campaign_report_query("select * from nfor_review where rv_cp_id='$write[cp_id]' and rv_delete='0'");
			$row_cnt = 0;
			while($row = sql_fetch_array($result)){
				$row_cnt++;
				$result_mem = mc_campaign_report_fetch("select * from nfor_member where mb_no='".addslashes($row[rv_mb_no])."' limit 1");
				if($row[rv_confirm_datetime] && substr($row[rv_confirm_datetime],0,4)!="0000"){ $st="ok"; $st_t="완료"; }
				elseif($row[rv_reg_datetime] && substr($row[rv_reg_datetime],0,4)!="0000"){ $st="review"; $st_t="검수중"; }
				elseif($row[rv_asign_datetime] && substr($row[rv_asign_datetime],0,4)!="0000"){ $st="asign"; $st_t="선정"; }
				else { $st="wait"; $st_t="대기"; }
				$lv_name = $admin[mb_level][$result_mem[mb_level]] ? $admin[mb_level][$result_mem[mb_level]] : $result_mem[mb_level];
				$channel_url = mc_campaign_report_http_url($row[rv_channel]);
				$review_url = mc_campaign_report_http_url($row[rv_url]);
			?>
			<tr>
				<td><b><?=mc_campaign_report_mask_name($result_mem[mb_name])?></b></td>
				<td><span class="badge-lv"><?=$lv_name?></span></td>
				<td><?php if($channel_url){ ?><a href="<?=htmlspecialchars($channel_url)?>" target="_blank" rel="noopener" class="lnk"><?=htmlspecialchars($channel_url)?></a><?php } else { ?><span class="s">채널 주소 미등록</span><?php } ?><?php if($review_url){ ?><br><a href="<?=htmlspecialchars($review_url)?>" target="_blank" rel="noopener" class="lnk">(리뷰글 보기 &#8599;)</a><?php } ?></td>
				<td class="dt num"><?=substr($row[rv_reg_datetime],0,10)?><br><span class="s"><?=substr($row[rv_confirm_datetime],0,10)?></span></td>
				<td><span class="tag <?=$st?>"><?=$st_t?></span></td>
			</tr>
			<?php } if(!$row_cnt){ ?>
			<tr><td colspan="5" style="text-align:center;color:var(--muted);padding:30px;">등록된 리뷰가 없습니다.</td></tr>
			<?php } ?>
			</tbody>
		</table>
		</div>
	</div>

	<?php if($inc_mt && $mt_has){ ?>
	<div class="sec">
		<div class="sec-h"><span class="bar"></span><h2>메타테크 참여 현황</h2><span class="desc">모바일 부업 미션 참여(유입) 활동</span></div>
		<div class="tbl-wrap">
		<table class="t">
			<thead><tr><th>미션 유형</th><th>검색키워드 / URL</th><th>참여</th><th>완료</th><th>지급 포인트</th><th>진행기간</th></tr></thead>
			<tbody>
			<?php foreach($mt_rows as $m){ ?>
			<tr>
				<td><b><?=htmlspecialchars($m[meta_op_b])?></b></td>
				<td><?=htmlspecialchars($m[m_keyword] ? $m[m_keyword] : $m[m_url])?></td>
				<td class="num"><?=number_format($m[join_cnt])?>명</td>
				<td class="num"><?=number_format($m[done_cnt])?>명</td>
				<td class="num"><?=number_format($m[m_point])?>P</td>
				<td class="dt num"><?=$m[m_sdate]?> ~ <?=$m[m_edate]?></td>
			</tr>
			<?php } ?>
			</tbody>
		</table>
		</div>
		<div style="margin-top:8px;font-size:12px;color:var(--muted)">※ 실제 이용자가 모바일 네이버 앱으로 직접 미션을 수행한 참여 기록입니다.</div>
	</div>
	<?php } ?>

	<div class="foot">
		<b>메타체험단</b> · 블로거와 업주를 연결하는 체험·리뷰 플랫폼<br>
		본 보고서는 열람용으로 제공되며, 데이터는 발행일 기준입니다.
	</div>
</div>

<script>
(function(){
	var PAL=['#4f46e5','#06b6d4','#f59e0b','#10b981','#ef4444','#8b5cf6','#ec4899','#14b8a6','#64748b'];
	if(window.Chart&&Chart.defaults&&Chart.defaults.global){
		Chart.defaults.global.defaultFontFamily="-apple-system,BlinkMacSystemFont,'Apple SD Gothic Neo','Malgun Gothic',sans-serif";
		Chart.defaults.global.defaultFontColor='#64748b';Chart.defaults.global.defaultFontSize=12;
	}
	function gridOpts(h){return{responsive:true,maintainAspectRatio:false,legend:{display:false},title:{display:false},tooltips:{backgroundColor:'#0f172a',cornerRadius:8,padding:10,displayColors:false},scales:{xAxes:[{gridLines:{display:!!h,color:'#eef1f5',drawBorder:false},ticks:{fontColor:'#94a3b8'}}],yAxes:[{gridLines:{display:!h,color:'#eef1f5',drawBorder:false},ticks:{fontColor:'#94a3b8',beginAtZero:true}}]}};}
	function pieOpts(){return{responsive:true,maintainAspectRatio:false,cutoutPercentage:62,legend:{position:'bottom',labels:{boxWidth:10,padding:14,fontColor:'#475569'}},tooltips:{backgroundColor:'#0f172a',cornerRadius:8,padding:10}};}
	function el(id){return document.getElementById(id);}
	function mkRound(id,type,l,d){if(!el(id))return;new Chart(el(id),{type:type,data:{labels:l,datasets:[{data:d,backgroundColor:PAL,borderWidth:2,borderColor:'#fff'}]},options:pieOpts()});}
	function mkBar(id,l,d,c,h){if(!el(id))return;new Chart(el(id),{type:h?'horizontalBar':'bar',data:{labels:l,datasets:[{data:d,backgroundColor:c,maxBarThickness:h?22:34}]},options:gridOpts(h)});}

	<?php $json_flags=JSON_UNESCAPED_UNICODE|JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT; ?>
	var sex_l=<?=json_encode($sex_data[text],$json_flags)?>,sex_d=<?=json_encode($sex_data[cnt],$json_flags)?>;
	var level_l=<?=json_encode($level_data[text],$json_flags)?>,level_d=<?=json_encode($level_data[cnt],$json_flags)?>;
	var age_l=<?=json_encode($age_data[text],$json_flags)?>,age_d=<?=json_encode($age_data[cnt],$json_flags)?>;
	var area_l=<?=json_encode($area_data[text],$json_flags)?>,area_d=<?=json_encode($area_data[cnt],$json_flags)?>;
	var dev_l=<?=json_encode($device_data[text],$json_flags)?>,dev_d=<?=json_encode($device_data[cnt],$json_flags)?>;
	var time_l=<?=json_encode($time_data[text],$json_flags)?>,time_d=<?=json_encode($time_data[cnt],$json_flags)?>;
	var dayw_l=<?=json_encode($dayw_data[text],$json_flags)?>,dayw_d=<?=json_encode($dayw_data[cnt],$json_flags)?>;
	var month_l=<?=json_encode($month_data[text],$json_flags)?>,month_d=<?=json_encode($month_data[cnt],$json_flags)?>;
	var day_l=<?=json_encode($day_data[text],$json_flags)?>,day_d=<?=json_encode($day_data[cnt],$json_flags)?>;
	var blog_l=<?=json_encode($blogid_data[text],$json_flags)?>,blog_d=<?=json_encode($blogid_data[cnt],$json_flags)?>;

	window.onload=function(){
		mkRound('sex_chart','doughnut',sex_l,sex_d);
		mkRound('level_chart','pie',level_l,level_d);
		mkBar('age_chart',age_l,age_d,'#8b5cf6',false);
		mkBar('area_chart',area_l,area_d,'#06b6d4',true);
		mkRound('device_chart','doughnut',dev_l,dev_d);
		mkBar('dayw_chart',dayw_l,dayw_d,'#10b981',false);
		mkBar('time_chart',time_l,time_d,'#4f46e5',false);
		mkBar('day_chart',day_l,day_d,'#f59e0b',false);
		mkBar('month_chart',month_l,month_d,'#4f46e5',false);
		mkBar('blogid_chart',blog_l,blog_d,'#4f46e5',true);
	};
})();
</script>
</body>
</html>
<?php
$html = ob_get_clean();

/* ===== 공개 폴더에 저장 ===== */
$webroot = dirname(dirname(__FILE__));
$report_dir = $webroot . "/report";
if(!is_dir($report_dir) && !@mkdir($report_dir, 0755, true)){
	return mc_campaign_report_result(false, "/report 폴더를 만들 수 없습니다", array("subject"=>$write[cp_subject]));
}

$existing_paths = mc_campaign_report_existing_paths($cp_id);
$fpath = !empty($existing_paths) ? $existing_paths[0] : mc_campaign_report_canonical_path($cp_id);
if($fpath === "") return mc_campaign_report_result(false, "공개 보고서 경로를 만들 수 없습니다", array("subject"=>$write[cp_subject]));
$targets = !empty($existing_paths) ? $existing_paths : array($fpath);
foreach($targets as $target){
	$write_result = mc_campaign_report_write_atomic($target, $html);
	if(!$write_result['ok']){
		return mc_campaign_report_result(false, $write_result['error'], array("subject"=>$write[cp_subject]));
	}
}

$fname = basename($fpath);
$public_url = mc_campaign_report_base_url()."/report/".$fname;
return mc_campaign_report_result(true, "", array(
	"cp_id"=>(int)$cp_id,
	"subject"=>(string)$write[cp_subject],
	"public_url"=>$public_url,
	"path"=>$fpath,
	"metatech_has"=>$mt_has,
	"metatech_included"=>$inc_mt,
	"metatech_rows"=>$mt_rows
));
}

function mc_campaign_report_preserve_metatech($cp_id){
	$path = mc_campaign_report_path($cp_id);
	if($path === "" || !is_file($path)) return false;
	$head = @file_get_contents($path, false, null, 0, 200000);
	return $head !== false && strpos($head, "메타테크 참여 현황") !== false;
}

function mc_campaign_report_refresh_after_review($cp_id){
	try {
		$cp_id = preg_replace('/[^0-9]/', '', $cp_id);
		if(!$cp_id) return mc_campaign_report_result(false, "캠페인 번호가 없습니다");
		$campaign = mc_campaign_report_fetch("select cp_id, cp_result_datetime from nfor_campaign where cp_id='$cp_id'");
		if(!$campaign[cp_id]) return mc_campaign_report_result(false, "캠페인을 찾을 수 없습니다");
		$finished = $campaign[cp_result_datetime]
			&& $campaign[cp_result_datetime] !== '0000-00-00 00:00:00'
			&& strtotime($campaign[cp_result_datetime]) <= time();
		if(!$finished && !mc_campaign_report_exists($cp_id)){
			return mc_campaign_report_result(true, "", array("skipped"=>true, "cp_id"=>(int)$cp_id));
		}
		return mc_campaign_report_generate($cp_id, mc_campaign_report_preserve_metatech($cp_id));
	} catch(Exception $e) {
		return mc_campaign_report_result(false, $e->getMessage(), array("cp_id"=>(int)$cp_id));
	}
}

function mc_campaign_report_queue_dir(){
	return dirname(dirname(__FILE__))."/report/.queue";
}

function mc_campaign_report_enqueue($cp_id){
	$cp_id = preg_replace('/[^0-9]/', '', $cp_id);
	if(!$cp_id) return mc_campaign_report_result(false, "캠페인 번호가 없습니다");
	$dir = mc_campaign_report_queue_dir();
	if(!is_dir($dir) && !@mkdir($dir, 0755, true)){
		return mc_campaign_report_result(false, "보고서 갱신 대기열을 만들지 못했습니다", array("cp_id"=>(int)$cp_id));
	}
	$path = $dir."/".$cp_id.".pending";
	$tmp = $path.".".getmypid().".tmp";
	if(@file_put_contents($tmp, date('c'), LOCK_EX) === false || !@rename($tmp, $path)){
		@unlink($tmp);
		return mc_campaign_report_result(false, "보고서 갱신 요청을 저장하지 못했습니다", array("cp_id"=>(int)$cp_id));
	}
	return mc_campaign_report_result(true, "", array("cp_id"=>(int)$cp_id, "queued"=>true));
}

function mc_campaign_report_recover_stale_claims($max_age=3600){
	$dir = mc_campaign_report_queue_dir();
	if(!is_dir($dir)) return 0;
	$claims = glob($dir."/*.processing");
	if(!is_array($claims)) return 0;
	$recovered = 0;
	$deadline = time() - max(300, (int)$max_age);
	foreach($claims as $claim){
		$mtime = @filemtime($claim);
		if($mtime === false || $mtime > $deadline) continue;
		$pending = preg_replace('/\.pending\.[0-9]+\.processing$/', '.pending', $claim);
		if($pending === $claim) continue;
		if(is_file($pending)){
			@unlink($claim); // 더 최신 요청이 이미 있으므로 고아 claim만 정리한다.
			$recovered++;
		} elseif(@rename($claim, $pending)) {
			$recovered++;
		}
	}
	return $recovered;
}

function mc_campaign_report_process_queue($limit=20){
	$limit = max(1, min(50, (int)$limit));
	$dir = mc_campaign_report_queue_dir();
	if(!is_dir($dir)) return array("ok"=>true, "attempted"=>0, "generated"=>0, "failed"=>0, "errors"=>array());
	mc_campaign_report_recover_stale_claims();
	$files = glob($dir."/*.pending");
	if(!is_array($files)) $files = array();
	usort($files, function($a, $b){
		$am = @filemtime($a); $bm = @filemtime($b);
		if($am == $bm) return strnatcmp($a, $b);
		return $am < $bm ? -1 : 1;
	});
	$attempted = 0; $generated = 0; $failed = 0; $errors = array();
	foreach($files as $path){
		if($attempted >= $limit) break;
		$cp_id = preg_replace('/[^0-9]/', '', basename($path, '.pending'));
		if(!$cp_id){ @unlink($path); continue; }
		$processing = $path.".".getmypid().".processing";
		if(!@rename($path, $processing)) continue;
		$attempted++;
		$result = mc_campaign_report_refresh_after_review($cp_id);
		if($result['ok']){ $generated++; @unlink($processing); }
		else {
			$failed++;
			$errors[] = array("cp_id"=>(int)$cp_id, "error"=>$result['error']);
			if(is_file($path)) @unlink($processing); // 생성 중 들어온 최신 요청은 그대로 둔다.
			else { @rename($processing, $path); @touch($path); }
		}
	}
	return array("ok"=>$failed===0, "attempted"=>$attempted, "generated"=>$generated, "failed"=>$failed, "errors"=>$errors);
}

function mc_campaign_report_generate_missing($limit=20){
	$limit = max(1, min(50, (int)$limit));
	$attempted = 0; $generated = 0; $failed = 0; $errors = array();
	try {
		$rows = mc_campaign_report_query("select cp_id from nfor_campaign
			where cp_result_datetime is not null and cp_result_datetime<>''
			and cp_result_datetime<>'0000-00-00 00:00:00' and cp_result_datetime<=NOW()
			order by cp_result_datetime desc, cp_id desc");
	} catch(Exception $e) {
		return array("ok"=>false, "attempted"=>0, "generated"=>0, "failed"=>1,
			"errors"=>array(array("cp_id"=>0, "error"=>$e->getMessage())));
	}
	while($campaign = sql_fetch_array($rows)){
		$cp_id = $campaign[cp_id];
		if(mc_campaign_report_exists($cp_id)) continue;
		$attempted++;
		$result = mc_campaign_report_generate($cp_id, false);
		if($result['ok']) $generated++;
		else { $failed++; $errors[] = array("cp_id"=>(int)$cp_id, "error"=>$result['error']); }
		if($attempted >= $limit) break;
	}
	return array("ok"=>$failed===0, "attempted"=>$attempted, "generated"=>$generated, "failed"=>$failed, "errors"=>$errors);
}
