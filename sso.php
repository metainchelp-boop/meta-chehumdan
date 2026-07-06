<?php
/*
 * 전산(ERP) → 메타체험단 admin 자동로그인 통로 (2026-06-08 신설)
 * 전산이 서명한 일회용 토큰(HMAC, 시간제한)을 검증하면 공용 admin(mb_no=1)으로 로그인시키고 목적지로 이동.
 * - 비밀번호를 주고받지 않음. 비밀키는 MC_CAL_TOKEN에서 파생(양쪽 동일 계산).
 * - goto는 /admin/ 경로만 허용(오픈리다이렉트 차단).
 * 호출: /sso.php?ts=<epoch초>&t=<hmac>&goto=</admin/...>
 */
include_once "path.php";   // 세션 시작 + DB + $nfor

// MC_CAL_TOKEN(공유 토큰)에서 SSO 전용 키 파생 — mc_cal_api.php 토큰과 동일 값 사용
$MC_CAL_TOKEN = "7725253d72ccb7213827956a64977aaeeedf1377dffbf207114aafb01f8a3e61";
$SSO_SECRET   = hash('sha256', $MC_CAL_TOKEN . '|metacrew-sso');

function sso_fail($msg){
	header('Content-type: text/html; charset=utf-8');
	echo '<!doctype html><meta charset="utf-8"><div style="font-family:sans-serif;max-width:420px;margin:80px auto;text-align:center;color:#334155">';
	echo '<h3 style="color:#dc2626">자동 로그인 실패</h3><p>'.htmlspecialchars($msg).'</p>';
	echo '<p style="color:#94a3b8;font-size:13px">전산 캘린더에서 다시 눌러주세요.</p></div>';
	exit;
}

$ts   = (int)$_GET['ts'];
$t    = (string)$_GET['t'];
$goto = (string)$_GET['goto'];

if(!$ts || $t==="" || $goto===""){ sso_fail("잘못된 접근입니다."); }
if(abs(time() - $ts) > 3600){ sso_fail("링크가 만료되었습니다. (1시간 경과)"); }

$expected = hash_hmac('sha256', $ts . '|' . $goto, $SSO_SECRET);
if(!hash_equals($expected, $t)){ sso_fail("유효하지 않은 접근입니다."); }

// 오픈리다이렉트 차단: 같은 사이트의 /admin/ 경로만 허용
if(!preg_match('#^/admin/[A-Za-z0-9_./?=&%\-]*$#', $goto)){ $goto = "/admin/index.php"; }

// 공용 admin(mb_no=1)으로 로그인 — nfor.php가 $_SESSION[ss_mb_no]로 $member 로딩
$_SESSION['ss_mb_no'] = 1;

header("Location: " . $goto);
exit;
?>
