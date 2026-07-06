<?php
/* 사용하지 않는(고아) 관리자 화면 — 2026-06-18 정리.
   원본은 로컬 admin_src 백업 보관. 깨진 500/SQL오류 대신 깔끔한 안내만 출력. */
include "path.php";
include "head.php";
?>
<div style="max-width:760px;margin:40px auto;padding:48px 32px;background:#fff;border:1px solid #eaedf1;border-radius:14px;text-align:center;font-family:'Pretendard','Apple SD Gothic Neo',sans-serif;">
  <div style="font-size:34px;margin-bottom:10px;">🗂️</div>
  <h2 style="color:#191f28;margin:0 0 10px;font-size:19px;font-weight:800;">현재 사용하지 않는 화면입니다</h2>
  <p style="color:#6b7684;line-height:1.7;margin:0;font-size:14px;">
    이 페이지는 운영 메뉴에서 제외되어 정리되었습니다.<br>
    (구버전 구글 애널리틱스 통계 · 미사용 기능 잔재 정리 · 2026-06-18)<br>
    왼쪽 메뉴의 사용 중인 화면을 이용해 주세요.
  </p>
  <p style="margin-top:24px;">
    <a href="index.php" style="display:inline-block;background:#03c75a;color:#fff;text-decoration:none;font-weight:700;padding:11px 22px;border-radius:9px;font-size:14px;">대시보드로 이동</a>
  </p>
</div>
<?php include "tail.php"; ?>
