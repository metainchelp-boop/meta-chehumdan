<?php
/* ============================================================================
   mc_share.php — 광고주 공유 링크 토큰 헬퍼 (서버 비밀키 기반 HMAC-SHA256)
   2026-06-18 신설. campaign_report_share.php / review_board.php / ad_progress.php 공용.
   - 토큰 = hash_hmac('sha256', cp_id, 비밀키)의 앞 24자(96bit) → 추측·열거 불가.
   - 비밀키는 같은 폴더의 mc_share_key.php(소스 비노출, return 문자열)에서 로드.
   - 함수 중복정의 방지 가드 포함(여러 곳에서 include_once 안전).
   ============================================================================ */
if(!function_exists('mc_share_secret')){
  function mc_share_secret(){
    static $k = null;
    if($k !== null) return $k;
    $f = dirname(__FILE__).'/mc_share_key.php';
    if(is_file($f)){ $v = @include $f; if(is_string($v) && strlen($v) >= 32){ $k = $v; return $k; } }
    $k = 'metacrew_share_2026_salt';   // 키파일 누락 시 폴백(구토큰 호환)
    return $k;
  }
}
if(!function_exists('mc_share_token')){
  function mc_share_token($id){
    return substr(hash_hmac('sha256', (string)$id, mc_share_secret()), 0, 24);
  }
}
