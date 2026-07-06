<?php
/* ============================================================
   광고주용 공유 보고서 생성기 (관리자 전용)
   - 캠페인 결과 보고서를 "로그인 없이 열람 가능한 정적 HTML"로
     웹루트 /report/ 에 생성하고, 공개 링크를 발급한다.
   - 개인정보 보호: 신청자 이름 마스킹, 회원정보/연락처/체크박스/선정버튼 제외.
   - 2026 리디자인
   ============================================================ */
include_once "path.php";
include_once $nfor[path]."/mc_share.php";   // 공유 토큰 헬퍼(서버 비밀키 HMAC) 2026-06-18

// 관리자만 생성 가능
if(!$member[mb_admin]){
	die("접근 권한이 없습니다. 관리자 로그인이 필요합니다.");
}

$cp_id = preg_replace('/[^0-9]/', '', $_GET[cp_id]);
if(!$cp_id){ die("캠페인 번호(cp_id)가 필요합니다."); }

$write = sql_fetch("select * from nfor_campaign where cp_id='$cp_id'");
if(!$write[cp_id]){ die("해당 캠페인을 찾을 수 없습니다."); }

// ── 메타테크 참여 현황 (선택 포함) 2026-06-16 ──
include_once $nfor[path]."/lib/z_function.lib.php";
$inc_mt  = ($_GET['mt']=='1');
$mt_rows = metatech_report_by_cp($cp_id);
$mt_has  = !empty($mt_rows);

/* ===== 집계 데이터 (campaign_report.php 와 동일 로직) ===== */
// 성별
$que = sql_query("select rv_mb_sex, count(*) as cnt from nfor_review where rv_cp_id='$write[cp_id]' group by rv_mb_sex");
while($data = sql_fetch_array($que)){
	if($data[rv_mb_sex]=="M"){ $text="남자"; } elseif($data[rv_mb_sex]=="F"){ $text="여자"; } else{ $text="미입력"; }
	$sex_data[text][]=$text; $sex_data[cnt][]=$data[cnt];
}
// 연령별
$que = sql_query("select rv_mb_age, count(*) as cnt from nfor_review where rv_cp_id='$write[cp_id]' group by rv_mb_age");
while($data = sql_fetch_array($que)){
	$age_data[text][]=$data[rv_mb_age]?$data[rv_mb_age]."대":"미입력"; $age_data[cnt][]=$data[cnt];
}
// 지역별
$que = sql_query("select rv_mb_area, count(*) as cnt from nfor_review where rv_cp_id='$write[cp_id]' group by rv_mb_area");
while($data = sql_fetch_array($que)){
	$area_data[text][]=$data[rv_mb_area]?$data[rv_mb_area]:"미입력"; $area_data[cnt][]=$data[cnt];
}
// 등급명 맵 + 레벨별
$admin[mb_level][""]="미입력";
$que = sql_query("select * from nfor_level where 1 order by lv_rank asc");
while($row = sql_fetch_array($que)){ $admin[mb_level][$row[lv_id]]=$row[lv_name]; }
$que = sql_query("select rv_mb_level, count(*) as cnt from nfor_review where rv_cp_id='$write[cp_id]' group by rv_mb_level");
while($data = sql_fetch_array($que)){ $level_data[text][]=$admin[mb_level][$data[rv_mb_level]]; $level_data[cnt][]=$data[cnt]; }

// 트래픽 테이블
$traffic_y_w = date("Y_W",strtotime($write[cp_insert_datetime]));
$traffic_table = "nfor_traffic_".$traffic_y_w;
// 디바이스
$que = sql_query("select tr_device, count(*) as tot_device_count from {$traffic_table} where tr_cp_id = '$write[cp_id]' group by tr_device;");
while($data = sql_fetch_array($que)){ $text=($data[tr_device]=="pc")?"PC":"MOBILE"; $device_data[text][]=$text; $device_data[cnt][]=$data[tot_device_count]; }
// 시간대
$sql = "select ifNull(T2.hour, T1.n) as hour, ifNull(T2.tot_hour_count, 0) as tot_hour_count
from ( select @N:=@N+1 as n from {$traffic_table}, (select @N:=-1 from dual) NN limit 24 ) as T1
left outer join ( select hour(tr_datetime) as hour, count(*) as tot_hour_count from {$traffic_table} where tr_cp_id = '$write[cp_id]' group by hour ) as T2 on T1.n = T2.hour
order by T1.n asc";
$que = sql_query($sql);
while($data = sql_fetch_array($que)){ $time_data[text][]=$data[hour]."시"; $time_data[cnt][]=$data[tot_hour_count]; }
// 요일
$sql = "select case dayofweek(tr_datetime) when 1 then '일요일' when 2 then '월요일' when 3 then '화요일' when 4 then '수요일' when 5 then '목요일' when 6 then '금요일' when 7 then '토요일' end as DateRange, count(*) as tot_dayw_count from {$traffic_table} where tr_cp_id = '$write[cp_id]' group by dayofweek(tr_datetime)";
$que = sql_query($sql);
while($data = sql_fetch_array($que)){ $dayw_data[text][]=$data[DateRange]; $dayw_data[cnt][]=$data[tot_dayw_count]; }
// 월별
$sql = "SELECT DATE_FORMAT(tr_date, '%Y-%m') AS date, count(*) AS tot_month_count FROM {$traffic_table} where tr_cp_id = '$write[cp_id]' GROUP BY DATE_FORMAT(tr_date, '%Y-%m')";
$que = sql_query($sql);
while($data = sql_fetch_array($que)){ $month_data[text][]=$data[date]; $month_data[cnt][]=$data[tot_month_count]; }
// 일별
$sql = "SELECT tr_date AS date, count(*) AS tot_day_count FROM {$traffic_table} where tr_cp_id = '$write[cp_id]' GROUP BY tr_date order by tr_date asc";
$que = sql_query($sql);
while($data = sql_fetch_array($que)){ $day_data[text][]=$data[date]; $day_data[cnt][]=$data[tot_day_count]; }
// 누적성과
$sql = "SELECT count(*) AS tot_mem_count, B.mb_name FROM {$traffic_table} A inner join nfor_member B on A.tr_mb_no = B.mb_no where tr_cp_id = '$write[cp_id]' GROUP BY B.mb_name";
$que = sql_query($sql);
while($data = sql_fetch_array($que)){ $blogid_data[text][]=$data[mb_name]; $blogid_data[cnt][]=$data[tot_mem_count]; }

/* ===== 표시용 파생값 ===== */
$rate_apply = ($write[cp_recruit] > 0) ? round($write[cp_order] / $write[cp_recruit] * 100) : 0;
$rate_post  = ($write[cp_review_asign] > 0) ? round($write[cp_review_post] / $write[cp_review_asign] * 100) : 0;

/* 이름 마스킹 (개인정보 보호) */
function mask_name($n){
	$n = trim($n);
	$len = mb_strlen($n, 'UTF-8');
	if($len <= 1) return $n ? $n : "비공개";
	if($len == 2) return mb_substr($n,0,1,'UTF-8')."○";
	return mb_substr($n,0,1,'UTF-8').str_repeat("○",$len-2).mb_substr($n,$len-1,1,'UTF-8');
}

/* ===== 정적 HTML 생성 (광고주 열람용) ===== */
ob_start();
?><!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?=htmlspecialchars($write[cp_subject])?> · 캠페인 결과 보고서</title>
<meta name="robots" content="noindex, nofollow">
<script><?php $mc_cjs=dirname(dirname(__FILE__))."/js/Chart.min.js"; echo is_file($mc_cjs)?file_get_contents($mc_cjs):"/* Chart.min.js 누락 */"; ?></script>
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
			$result = sql_query("select * from nfor_review where rv_cp_id='$write[cp_id]'");
			$row_cnt = 0;
			while($row = sql_fetch_array($result)){
				$row_cnt++;
				$result_mem = sql_fetch("select * from nfor_member where mb_no='".$row[rv_mb_no]."' limit 1");
				if($row[rv_confirm_datetime] && substr($row[rv_confirm_datetime],0,4)!="0000"){ $st="ok"; $st_t="완료"; }
				elseif($row[rv_reg_datetime] && substr($row[rv_reg_datetime],0,4)!="0000"){ $st="review"; $st_t="검수중"; }
				elseif($row[rv_asign_datetime] && substr($row[rv_asign_datetime],0,4)!="0000"){ $st="asign"; $st_t="선정"; }
				else { $st="wait"; $st_t="대기"; }
				$lv_name = $admin[mb_level][$result_mem[mb_level]] ? $admin[mb_level][$result_mem[mb_level]] : $result_mem[mb_level];
			?>
			<tr>
				<td><b><?=mask_name($result_mem[mb_name])?></b></td>
				<td><span class="badge-lv"><?=$lv_name?></span></td>
				<td><a href="<?=htmlspecialchars($row[rv_channel])?>" target="_blank" rel="noopener" class="lnk"><?=htmlspecialchars($row[rv_channel])?></a><?php if($row[rv_url]){ ?><br><a href="<?=htmlspecialchars($row[rv_url])?>" target="_blank" rel="noopener" class="lnk">(리뷰글 보기 &#8599;)</a><?php } ?></td>
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

	var sex_l=[<?php for($i=0;$i<count($sex_data[text]);$i++){echo "'".$sex_data[text][$i]."',";}?>],sex_d=[<?php for($i=0;$i<count($sex_data[text]);$i++){echo $sex_data[cnt][$i].",";}?>];
	var level_l=[<?php for($i=0;$i<count($level_data[text]);$i++){echo "'".$level_data[text][$i]."',";}?>],level_d=[<?php for($i=0;$i<count($level_data[text]);$i++){echo $level_data[cnt][$i].",";}?>];
	var age_l=[<?php for($i=0;$i<count($age_data[text]);$i++){echo "'".$age_data[text][$i]."',";}?>],age_d=[<?php for($i=0;$i<count($age_data[text]);$i++){echo $age_data[cnt][$i].",";}?>];
	var area_l=[<?php for($i=0;$i<count($area_data[text]);$i++){echo "'".$area_data[text][$i]."',";}?>],area_d=[<?php for($i=0;$i<count($area_data[text]);$i++){echo $area_data[cnt][$i].",";}?>];
	var dev_l=[<?php for($i=0;$i<count($device_data[text]);$i++){echo "'".$device_data[text][$i]."',";}?>],dev_d=[<?php for($i=0;$i<count($device_data[text]);$i++){echo $device_data[cnt][$i].",";}?>];
	var time_l=[<?php for($i=0;$i<count($time_data[text]);$i++){echo "'".$time_data[text][$i]."',";}?>],time_d=[<?php for($i=0;$i<count($time_data[text]);$i++){echo $time_data[cnt][$i].",";}?>];
	var dayw_l=[<?php for($i=0;$i<count($dayw_data[text]);$i++){echo "'".$dayw_data[text][$i]."',";}?>],dayw_d=[<?php for($i=0;$i<count($dayw_data[text]);$i++){echo $dayw_data[cnt][$i].",";}?>];
	var month_l=[<?php for($i=0;$i<count($month_data[text]);$i++){echo "'".$month_data[text][$i]."',";}?>],month_d=[<?php for($i=0;$i<count($month_data[text]);$i++){echo $month_data[cnt][$i].",";}?>];
	var day_l=[<?php for($i=0;$i<count($day_data[text]);$i++){echo "'".$day_data[text][$i]."',";}?>],day_d=[<?php for($i=0;$i<count($day_data[text]);$i++){echo $day_data[cnt][$i].",";}?>];
	var blog_l=[<?php for($i=0;$i<count($blogid_data[text]);$i++){echo "'".$blogid_data[text][$i]."',";}?>],blog_d=[<?php for($i=0;$i<count($blogid_data[text]);$i++){echo $blogid_data[cnt][$i].",";}?>];

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
$webroot = dirname(dirname(__FILE__));      // /admin 의 상위 = 웹루트(/www)
$report_dir = $webroot . "/report";
if(!is_dir($report_dir)){ @mkdir($report_dir, 0755, true); }

$token = mc_share_token($cp_id);   // 서버 비밀키 기반 HMAC 24자(추측 불가) 2026-06-18
$fname = "cr_" . $cp_id . "_" . $token . ".html";
$fpath = $report_dir . "/" . $fname;
// 같은 캠페인의 구(舊)토큰 노출파일 제거 — 항상 현재 강력토큰 1개만 유지
foreach((array)glob($report_dir."/cr_".$cp_id."_*.html") as $old){ if($old !== $fpath) @unlink($old); }

$ok = @file_put_contents($fpath, $html);

/* 공개 URL */
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off') ? "https" : "http";
$public_url = $scheme . "://" . $_SERVER['HTTP_HOST'] . "/report/" . $fname;

/* ===== 관리자 안내 화면 ===== */
?><!DOCTYPE html>
<html lang="ko"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>광고주 공유 보고서</title>
<style>
body{margin:0;background:#f1f5f9;font-family:-apple-system,BlinkMacSystemFont,"Apple SD Gothic Neo","Malgun Gothic",sans-serif;color:#0f172a;}
.box{max-width:640px;margin:48px auto;background:#fff;border:1px solid #e8ebf0;border-radius:14px;box-shadow:0 2px 8px rgba(15,23,42,.05);padding:30px;}
h1{font-size:19px;margin:0 0 6px;}
.sub{color:#64748b;font-size:13px;margin-bottom:22px;}
.label{font-size:12px;font-weight:700;color:#475569;margin:18px 0 6px;}
.urlrow{display:flex;gap:8px;}
input.url{flex:1;padding:12px 14px;border:1px solid #e8ebf0;border-radius:10px;font-size:13px;color:#0f172a;background:#f8fafc;}
.btn{border:none;border-radius:10px;padding:12px 18px;font-size:13px;font-weight:700;cursor:pointer;text-decoration:none;display:inline-block;}
.btn.p{background:#4f46e5;color:#fff;}
.btn.g{background:#fff;color:#475569;border:1px solid #e8ebf0;}
.btns{display:flex;gap:8px;margin-top:14px;flex-wrap:wrap;}
.tip{margin-top:22px;font-size:12px;color:#94a3b8;line-height:1.7;background:#f8fafc;border-radius:10px;padding:14px 16px;}
.err{background:#fef2f2;color:#dc2626;border:1px solid #fecaca;border-radius:10px;padding:14px 16px;font-size:13px;}
.ok-badge{display:inline-block;background:#ecfdf5;color:#059669;font-weight:700;font-size:12px;padding:3px 10px;border-radius:999px;margin-bottom:10px;}
</style></head>
<body>
<div class="box">
<?php if($ok !== false){ ?>
	<div class="ok-badge">생성 완료</div>
	<h1>광고주 공유 보고서가 만들어졌습니다</h1>
	<div class="sub"><?=htmlspecialchars($write[cp_subject])?> (#<?=$write[cp_id]?>)</div>

	<div class="label">공유 링크 (로그인 없이 열람 가능)</div>
	<div class="urlrow">
		<input class="url" id="shareurl" type="text" readonly value="<?=htmlspecialchars($public_url)?>">
		<button class="btn p" onclick="cp()">링크 복사</button>
	</div>
	<div class="btns">
		<a class="btn g" href="<?=htmlspecialchars($public_url)?>" target="_blank" rel="noopener">새 창에서 열기</a>
		<a class="btn g" href="<?=htmlspecialchars($public_url)?>" download>HTML 파일 다운로드</a>
	</div>

	<?php if($mt_has){ $mt_sum=0; foreach($mt_rows as $m){ $mt_sum+=$m['join_cnt']; } ?>
	<div class="label">📱 메타테크 부업 미션</div>
	<div class="tip" style="background:#f1faf4;color:#0f766e">
		이 캠페인에 연결된 메타테크 참여 <b><?=number_format($mt_sum)?>명</b> ·
		현재 보고서에 <b><?=$inc_mt?'포함됨':'미포함'?></b>.<br>
		<?php if($inc_mt){ ?>
			<a class="btn g" style="padding:7px 13px;margin-top:8px" href="?cp_id=<?=$cp_id?>">메타테크 빼고 다시 생성</a>
		<?php } else { ?>
			<a class="btn p" style="padding:7px 13px;margin-top:8px" href="?cp_id=<?=$cp_id?>&mt=1">메타테크 포함하여 다시 생성</a>
		<?php } ?>
		<div style="margin-top:8px;font-size:11.5px;color:#94a3b8">참여가 적으면 미포함을 권장합니다. (같은 링크로 갱신됩니다)</div>
	</div>
	<?php } ?>

	<div class="tip">
		• 이 링크를 광고주에게 보내면 <b>로그인 없이 읽기 전용</b>으로 열람합니다.<br>
		• 개인정보 보호를 위해 리뷰어 <b>이름은 마스킹</b>(예: 김○영), 연락처·회원정보는 제외됩니다.<br>
		• 캠페인 데이터가 갱신되면 이 버튼을 다시 눌러 <b>같은 링크로 최신화</b>됩니다.<br>
		• 검색엔진 비노출(noindex) 처리되어 있습니다.
	</div>
<?php } else { ?>
	<h1>보고서 생성 실패</h1>
	<div class="err">
		<b>/report</b> 폴더에 파일을 저장하지 못했습니다.<br>
		서버의 쓰기 권한 문제일 수 있습니다. 경로: <?=htmlspecialchars($fpath)?>
	</div>
	<div class="tip">FTP에서 웹루트에 <b>report</b> 폴더를 만들고 권한을 707(또는 755)로 설정한 뒤 다시 시도해 주세요.</div>
<?php } ?>
</div>
<script>
function cp(){var i=document.getElementById('shareurl');i.select();i.setSelectionRange(0,99999);try{document.execCommand('copy');alert('링크가 복사되었습니다.');}catch(e){alert('복사 실패 — 링크를 길게 눌러 복사하세요.');}}
</script>
</body>
</html>
