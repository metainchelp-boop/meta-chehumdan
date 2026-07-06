<?php
/* 진행자별 리뷰 마감 연장 — AJAX 엔드포인트 (admin/)
   mode=hist : rv_id 의 연장 이력 조회
   mode=save : 새 마감일로 연장 + 이력 기록
   권한: 운영자 전원(로그인한 관리자). 2026-06-23 */
include_once "path.php";   // 관리자 세션/권한($member,$config), sql_*, demo_check_json
header('Content-Type: application/json; charset=utf-8');

function rex_out($arr){ echo json_encode($arr); exit; }

// 권한: 운영자 전원(이 페이지 접근 가능한 관리자면 OK)
if(empty($member[mb_no]) || (int)$member[mb_admin] < 1){
    rex_out(array('result'=>'err','message'=>'권한이 없습니다. 다시 로그인해 주세요.'));
}

$mode  = isset($_REQUEST['mode'])  ? $_REQUEST['mode'] : '';
$rv_id = isset($_REQUEST['rv_id']) ? (int)$_REQUEST['rv_id'] : 0;
if($rv_id <= 0) rex_out(array('result'=>'err','message'=>'잘못된 요청입니다.'));

/* ── 이력 조회 ── */
if($mode == 'hist'){
    $list = array();
    $r = sql_query("select re_old_edate, re_new_edate, re_reason, re_admin_name, re_datetime from nfor_review_extend where re_rv_id='{$rv_id}' order by re_id desc");
    while($x = sql_fetch_array($r)){
        $list[] = array(
            'old'    => substr($x['re_old_edate'],0,16),
            'neo'    => substr($x['re_new_edate'],0,16),
            'reason' => $x['re_reason'],
            'admin'  => $x['re_admin_name'],
            'at'     => substr($x['re_datetime'],0,16),
        );
    }
    rex_out(array('result'=>'ok','list'=>$list));
}

/* ── 저장(연장) ── */
if($mode == 'save'){
    demo_check_json();   // 데모모드 쓰기 차단

    $new_raw = isset($_POST['new_edate']) ? trim($_POST['new_edate']) : '';
    $reason  = isset($_POST['reason'])    ? trim($_POST['reason'])    : '';

    // datetime-local('YYYY-MM-DDTHH:MM') → 'YYYY-MM-DD HH:MM:00'
    $new_raw = str_replace('T', ' ', $new_raw);
    if(!preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}(:\d{2})?$/', $new_raw))
        rex_out(array('result'=>'err','message'=>'새 마감일 형식이 올바르지 않습니다.'));
    if(strlen($new_raw) == 16) $new_raw .= ':00';
    if($reason === '')
        rex_out(array('result'=>'err','message'=>'연장 사유를 입력해 주세요.'));
    if(mb_strlen($reason,'UTF-8') > 500) $reason = mb_substr($reason,0,500,'UTF-8');

    $rv = sql_fetch("select rv_id, rv_cp_id, rv_mb_no, rv_cp_contents_edatetime from nfor_review where rv_id='{$rv_id}'");
    if(empty($rv['rv_id']))
        rex_out(array('result'=>'err','message'=>'진행자 정보를 찾을 수 없습니다.'));

    $old_edate  = $rv['rv_cp_contents_edatetime'];
    $admin_name = $member[mb_name] ? $member[mb_name] : $member[mb_id];

    $reason_e = addslashes($reason);
    $admin_e  = addslashes($admin_name);
    $cpid_e   = addslashes($rv['rv_cp_id']);
    $old_e    = addslashes($old_edate);

    sql_query("update nfor_review set rv_cp_contents_edatetime='{$new_raw}' where rv_id='{$rv_id}'");
    sql_query("insert into nfor_review_extend set
        re_rv_id='{$rv_id}',
        re_cp_id='{$cpid_e}',
        re_mb_no='".(int)$rv['rv_mb_no']."',
        re_old_edate='{$old_e}',
        re_new_edate='{$new_raw}',
        re_reason='{$reason_e}',
        re_admin_no='".(int)$member[mb_no]."',
        re_admin_name='{$admin_e}',
        re_datetime=NOW()");

    rex_out(array('result'=>'ok','message'=>'마감이 연장되었습니다.','new_edate'=>substr($new_raw,0,16)));
}

rex_out(array('result'=>'err','message'=>'알 수 없는 요청입니다.'));
