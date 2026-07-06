<?php
/* ─────────────────────────────────────────────────────────────────────────
   선정(step2) 후 영업일 N일 경과 & 미신고(rv_buy_chk='0') 회원에게
   '상품 구매 독촉' 카카오 알림톡을 발송 대기열(nfor_sms_log)에 넣는다.
   crontab/campaign.php (하루 1회 실행) 에서 include 됨.  요청: 양근형 2026-06-30

   ★★ 안전장치 ★★
   - $BUY_REMIND_TPL(승인된 알림톡 템플릿코드)가 비어 있으면 아무것도 발송하지 않음(기본=안전).
     → 카카오 템플릿 검수 승인 후 이 값을 채워야 실제 발송이 시작됨.
   - 1인 1회만 발송(rv_buy_remind_datetime로 중복 방지).
   - 최근 14일 이내 선정건만 대상(과거 누적 미신고 대량발송 방지).
   - 회원이 [구매 완료]를 눌러 rv_buy_chk<>'0'이 되면 대상에서 자동 제외.
   ───────────────────────────────────────────────────────────────────────── */

if(!function_exists('sql_query')) return;   // 반드시 campaign.php(=path.php 포함)에서 include

$BUY_REMIND_ON     = true;         // 마스터 on/off
$BUY_REMIND_TPL    = 'UJ_1580';    // ★카카오 승인된 알림톡 템플릿코드(구매 독촉 안내). 2026-07-03 가동.
$BUY_REMIND_FROM   = '2026-07-03'; // ★B안(신규만): 이 날짜 이후 선정자만 대상(기존 백로그 제외). 빈값이면 미적용.
$BUY_REMIND_DAYS   = 3;            // 선정 후 영업일 기준
$BUY_REMIND_WINDOW = 14;           // 최근 N일 이내 선정건만 (과거 백로그 대량발송 방지)
$BUY_REMIND_LIMIT  = 500;          // 1회 최대 처리 건수(안전 상한)

if(!function_exists('mc_bizdays_since')){
	function mc_bizdays_since($from_dt){       // 주말 제외 경과 영업일수 (공휴일 미반영)
		$from = strtotime(substr((string)$from_dt,0,10)." 00:00:00");
		if(!$from) return 0;
		$today = strtotime(date("Y-m-d")." 00:00:00");
		$n = 0;
		for($t = $from + 86400; $t <= $today; $t += 86400){
			$w = (int)date("w", $t);            // 0=일, 6=토
			if($w !== 0 && $w !== 6) $n++;
		}
		return $n;
	}
}

if($BUY_REMIND_ON && $BUY_REMIND_TPL !== ''){
	global $config;
	$win = (int)$BUY_REMIND_WINDOW;
	$from_cond = ($BUY_REMIND_FROM!=='') ? " and a.rv_asign_datetime >= '".addslashes($BUY_REMIND_FROM)." 00:00:00' " : "";
	$rq = sql_query("select a.rv_id, a.rv_asign_datetime, a.rv_cp_subject, m.mb_hp, m.mb_nick, m.mb_name
		from nfor_review a left join nfor_member m on a.rv_mb_no = m.mb_no left join nfor_campaign b on a.rv_cp_id = b.cp_id
		where a.rv_step='2' and a.rv_delete='0' and a.rv_buy_chk='0'
		  and (a.rv_buy_remind_datetime is null or a.rv_buy_remind_datetime='0000-00-00 00:00:00')
		  and a.rv_asign_datetime is not null and a.rv_asign_datetime<>'0000-00-00 00:00:00'
		  and (case when a.rv_cp_contents_edatetime is not null and a.rv_cp_contents_edatetime<>'0000-00-00 00:00:00' then a.rv_cp_contents_edatetime else b.cp_contents_edatetime end) >= CURDATE()
		  and a.rv_asign_datetime >= DATE_SUB(NOW(), INTERVAL ".$win." DAY)
		  ".$from_cond."
		order by a.rv_id asc
		limit ".(int)$BUY_REMIND_LIMIT);
	while($d = sql_fetch_array($rq)){
		$rid = (int)$d[rv_id];
		if(mc_bizdays_since($d[rv_asign_datetime]) < $BUY_REMIND_DAYS) continue;   // 아직 기한 전
		$hp = preg_replace('/[^0-9]/','', (string)$d[mb_hp]);
		if(!$hp){ sql_query("update nfor_review set rv_buy_remind_datetime=NOW(), rv_buy_remind_by='auto' where rv_id='$rid'"); continue; } // 번호없음→스킵기록
		$nick = $d[mb_nick] ? $d[mb_nick] : $d[mb_name];
		$cp   = $d[rv_cp_subject];
		/* ★발송 문안은 승인된 템플릿과 100% 동일해야 함(변수 #{닉네임},#{캠페인명} 치환) */
		$msg  = "[메타체험단] 상품 구매 안내\n"
		      . $nick."님, '".$cp."' 체험단에 선정되셨습니다.\n"
		      . "선정 후 3일이 경과되었으나 아직 상품 구매가 확인되지 않았습니다.\n"
		      . "기한 내 구매 후, 마이페이지 > 선정된 캠페인에서 [구매 완료] 버튼을 눌러주세요!\n"
		      . "미진행 시 선정이 취소될 수 있습니다.\n"
		      . "문의: 02-2082-2005";
		sql_query("insert nfor_sms_log set sl_msg='".addslashes($msg)."', sl_hp='".addslashes($hp)."', sl_send_hp='".addslashes($config[cf_tel])."', sl_datetime=NOW(), sl_send='0', sl_templt_code='".addslashes($BUY_REMIND_TPL)."', sl_subject='구매 안내'");
		sql_query("update nfor_review set rv_buy_remind_datetime=NOW(), rv_buy_remind_by='auto' where rv_id='$rid'");
	}
}
?>
