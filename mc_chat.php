<?php
/* 고객센터 채팅 — 회원(프론트) 엔드포인트. 웹루트 배치. 2026-06-24
   mode=campaigns : 참여중/완료 캠페인 목록
   mode=start     : (회원,캠페인) 스레드 생성/조회 → ct_id
   mode=poll      : ct_id 메시지 수신(after 이후) + 회원읽음 처리
   mode=send      : 회원 메시지 전송 */
include_once "path.php";   // 프론트: $member,$config,sql_*  (관리자 권한 불필요)
header('Content-Type: application/json; charset=utf-8');
function cx($a){ echo json_encode($a); exit; }
date_default_timezone_set('Asia/Seoul');
function mc_chat_status(){ // 상담 운영시간 상태 (KST)
  $w=(int)date('w'); $hm=(int)date('H')*60+(int)date('i');
  if($w==0||$w==6) return array('after','지금은 상담 운영시간이 아니에요(주말). 문의를 남겨주시면 영업일에 순차적으로 답변드리겠습니다. 🙏');
  if($hm>=690 && $hm<810) return array('lunch','점심시간(11:30~13:30)에는 상담이 어려워요. 문의를 남겨주시면 점심 이후 순차적으로 답변드리겠습니다. 🙏');
  if($hm<600 || $hm>=1080) return array('after','지금은 상담 운영시간(평일 10:00~18:00)이 아니에요. 문의를 남겨주시면 익일 순차적으로 답변드리겠습니다. 🙏');
  return array('open','안녕하세요! 메타체험단 고객센터입니다 😊 무엇을 도와드릴까요?');
}

$mb = (int)$member['mb_no'];
if($mb <= 0) cx(array('result'=>'login','message'=>'로그인 후 이용 가능합니다.'));
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';

/* ── 참여 캠페인 목록 ── */
if($mode=='campaigns'){
  $done=array(); $ing=array(); $doneIds=array();
  $r = sql_query("select distinct rv_cp_id, rv_cp_subject from nfor_review where rv_mb_no='$mb' and rv_delete='0' and rv_step='4' order by rv_id desc");
  while($x=sql_fetch_array($r)){ $cp=sql_fetch("select cp_subject,cp_md_name from nfor_campaign where cp_id='".addslashes($x['rv_cp_id'])."'"); $done[]=array('cp_id'=>$x['rv_cp_id'],'subject'=>($x['rv_cp_subject']?$x['rv_cp_subject']:$cp['cp_subject']),'md'=>($cp['cp_md_name']?$cp['cp_md_name']:'고객센터')); $doneIds[]=$x['rv_cp_id']; }
  $r = sql_query("select distinct rv_cp_id, rv_cp_subject from nfor_review where rv_mb_no='$mb' and rv_delete='0' and rv_step in ('2','3','7') order by rv_id desc");
  while($x=sql_fetch_array($r)){ if(in_array($x['rv_cp_id'],$doneIds)) continue; $cp=sql_fetch("select cp_subject,cp_md_name from nfor_campaign where cp_id='".addslashes($x['rv_cp_id'])."'"); $ing[]=array('cp_id'=>$x['rv_cp_id'],'subject'=>($x['rv_cp_subject']?$x['rv_cp_subject']:$cp['cp_subject']),'md'=>($cp['cp_md_name']?$cp['cp_md_name']:'고객센터')); }
  $st=mc_chat_status();
  // 진행 중인(메시지 있는) 최근 상담 — 위젯에서 '이어가기' 노출용
  $lt=sql_fetch("select ct_id, ct_cp_subject, ct_cate, ct_md_name, (select count(*) from nfor_chat_msg where cm_ct_id=nfor_chat.ct_id) as mc from nfor_chat where ct_mb_no='$mb' order by ct_id desc limit 1");
  $last = (!empty($lt['ct_id']) && $lt['mc']>0) ? array('ct_id'=>(int)$lt['ct_id'],'subject'=>($lt['ct_cp_subject']?$lt['ct_cp_subject']:'일반 문의'),'cate'=>$lt['ct_cate'],'md'=>($lt['ct_md_name']?$lt['ct_md_name']:'고객센터')) : null;
  cx(array('result'=>'ok','ing'=>$ing,'done'=>$done,'status'=>$st[0],'notice'=>$st[1],'last'=>$last));
}

/* ── 스레드 시작/조회 ── */
if($mode=='start'){
  $cp_id = preg_replace('/[^0-9]/','', isset($_POST['cp_id'])?$_POST['cp_id']:'');
  $cate = isset($_POST['cate']) ? mb_substr(trim($_POST['cate']),0,20,'UTF-8') : '';
  $subject='일반 문의'; $md='고객센터';
  if($cp_id!==''){
    $cp=sql_fetch("select cp_subject,cp_md_name from nfor_campaign where cp_id='$cp_id'");
    if(empty($cp['cp_subject'])) cx(array('result'=>'err','message'=>'캠페인 정보를 찾을 수 없습니다.'));
    $subject=$cp['cp_subject']; $md=$cp['cp_md_name']?$cp['cp_md_name']:'고객센터';
  }
  $t = sql_fetch("select ct_id from nfor_chat where ct_mb_no='$mb' and ct_cp_id='$cp_id' order by ct_id desc limit 1");
  if(!empty($t['ct_id'])){ $ct_id=(int)$t['ct_id']; if($cate!=='') sql_query("update nfor_chat set ct_cate='".addslashes($cate)."' where ct_id='$ct_id'"); }
  else {
    $nick = addslashes($member['mb_nick'] ? $member['mb_nick'] : ($member['mb_name'] ? $member['mb_name'] : $member['mb_id']));
    sql_query("insert into nfor_chat set ct_mb_no='$mb', ct_mb_nick='$nick', ct_cp_id='$cp_id', ct_cp_subject='".addslashes($subject)."', ct_cate='".addslashes($cate)."', ct_md_name='".addslashes($md)."', ct_status='0', ct_last_msg='', ct_last_datetime=NOW(), ct_datetime=NOW()");
    $ins=sql_fetch("select last_insert_id() as id"); $ct_id=(int)$ins['id'];
  }
  $st=mc_chat_status();
  cx(array('result'=>'ok','ct_id'=>$ct_id,'subject'=>$subject,'md'=>$md,'status'=>$st[0],'notice'=>$st[1]));
}

/* ── 폴링(메시지 수신 + 읽음) ── */
if($mode=='poll'){
  $ct=(int)(isset($_REQUEST['ct_id'])?$_REQUEST['ct_id']:0);
  $after=(int)(isset($_REQUEST['after'])?$_REQUEST['after']:0);
  $own=sql_fetch("select ct_id from nfor_chat where ct_id='$ct' and ct_mb_no='$mb'");
  if(empty($own['ct_id'])) cx(array('result'=>'err'));
  $msgs=array();
  $r=sql_query("select cm_id,cm_sender,cm_msg,cm_admin_name,cm_datetime from nfor_chat_msg where cm_ct_id='$ct' and cm_id>'$after' order by cm_id asc");
  while($x=sql_fetch_array($r)){ $msgs[]=array('id'=>(int)$x['cm_id'],'me'=>($x['cm_sender']=='M'),'who'=>($x['cm_sender']=='M'?'':($x['cm_admin_name']?$x['cm_admin_name']:'고객센터')),'msg'=>$x['cm_msg'],'tm'=>substr($x['cm_datetime'],11,5),'d'=>substr($x['cm_datetime'],0,10)); }
  if($after>0 || count($msgs)) sql_query("update nfor_chat set ct_mb_unread=0 where ct_id='$ct'");
  cx(array('result'=>'ok','msgs'=>$msgs));
}

/* ── 전송 ── */
if($mode=='send'){
  $ct=(int)(isset($_POST['ct_id'])?$_POST['ct_id']:0);
  $msg=trim(isset($_POST['msg'])?$_POST['msg']:'');
  if($msg==='') cx(array('result'=>'err','message'=>'메시지를 입력하세요.'));
  $own=sql_fetch("select ct_id from nfor_chat where ct_id='$ct' and ct_mb_no='$mb'");
  if(empty($own['ct_id'])) cx(array('result'=>'err'));
  $msg=mb_substr($msg,0,1000,'UTF-8');
  sql_query("insert into nfor_chat_msg set cm_ct_id='$ct', cm_sender='M', cm_msg='".addslashes($msg)."', cm_datetime=NOW()");
  sql_query("update nfor_chat set ct_last_msg='".addslashes(mb_substr($msg,0,200,'UTF-8'))."', ct_last_datetime=NOW(), ct_last_sender='M', ct_ad_unread=ct_ad_unread+1, ct_status='0' where ct_id='$ct'");
  cx(array('result'=>'ok'));
}

/* ── 회원 전체 안읽음 수(닫힌 위젯 배지용, 가벼움) ── */
if($mode=='unread'){
  $u=sql_fetch("select coalesce(sum(ct_mb_unread),0) as n from nfor_chat where ct_mb_no='$mb'");
  cx(array('result'=>'ok','unread'=>(int)$u['n']));
}

cx(array('result'=>'err','message'=>'알 수 없는 요청'));
