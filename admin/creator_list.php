<?php
include_once "path.php";
include_once "inc_member_head.php";   // 회원섹션 인증 + $member + $id=mb_no + $admin[mb_admin]

// nfor_creator_fav 테이블·그룹컬럼 보장(찜/그룹 공통)
function cf_ensure(){
  sql_query("CREATE TABLE IF NOT EXISTS nfor_creator_fav (cf_id int(11) NOT NULL AUTO_INCREMENT, cf_mb_no int(11) NOT NULL DEFAULT 0, cf_group varchar(50) NOT NULL DEFAULT '', cf_admin varchar(50) NOT NULL DEFAULT '', cf_datetime datetime, PRIMARY KEY(cf_id), KEY idx_mb(cf_mb_no))");
  $c = sql_query("show columns from nfor_creator_fav like 'cf_group'");
  if(!sql_fetch_array($c)) sql_query("alter table nfor_creator_fav add column cf_group varchar(50) NOT NULL DEFAULT ''");
}

// nfor_creator_proposal 테이블·pr_note_id 컬럼 보장(역제안 기록/상태)
function pr_ensure(){
  sql_query("CREATE TABLE IF NOT EXISTS nfor_creator_proposal (pr_id int(11) NOT NULL AUTO_INCREMENT, pr_mb_no int(11) NOT NULL DEFAULT 0, pr_cp_id varchar(20) NOT NULL DEFAULT '', pr_note_id int(11) NOT NULL DEFAULT 0, pr_admin varchar(50) NOT NULL DEFAULT '', pr_datetime datetime, PRIMARY KEY(pr_id), KEY idx_mb(pr_mb_no))");
  $c = sql_query("show columns from nfor_creator_proposal like 'pr_note_id'");
  if(!sql_fetch_array($c)) sql_query("alter table nfor_creator_proposal add column pr_note_id int(11) NOT NULL DEFAULT 0");
}

// ── 찜(즐겨찾기) 토글 — 저장공간 nfor_creator_fav 자동생성(최초1회). 회원 레코드 무변경. ──
if($mode=="cf_toggle"){
  demo_check_json(); cf_ensure();
  $m = (int)$_POST['mb_no'];
  if($m){
    $ex = sql_fetch("select cf_id from nfor_creator_fav where cf_mb_no='$m'");
    if($ex[cf_id]){ sql_query("delete from nfor_creator_fav where cf_mb_no='$m'"); json_return("찜 해제했습니다","off"); }
    else { sql_query("insert into nfor_creator_fav set cf_mb_no='$m', cf_admin='".addslashes($member[mb_id])."', cf_datetime=NOW()"); json_return("찜했습니다","on"); }
  }
  json_return("대상이 없습니다","fail");
}

// ── 찜 그룹 지정/변경 — 그룹 지정 시 찜이 아니면 자동 찜 ──
if($mode=="cf_group"){
  demo_check_json(); cf_ensure();
  $m = (int)$_POST['mb_no']; $g = addslashes(trim($_POST['cf_group']));
  if($m){
    $ex = sql_fetch("select cf_id from nfor_creator_fav where cf_mb_no='$m'");
    if($ex[cf_id]) sql_query("update nfor_creator_fav set cf_group='$g' where cf_mb_no='$m'");
    else sql_query("insert into nfor_creator_fav set cf_mb_no='$m', cf_group='$g', cf_admin='".addslashes($member[mb_id])."', cf_datetime=NOW()");
    json_return($g?("'".$g."' 그룹으로 지정했습니다"):"그룹을 비웠습니다","ok");
  }
  json_return("대상이 없습니다","fail");
}

// ── 크리에이터 메모/태그 저장(관리자 전용 · 채팅 사이드 메모 대응) — nfor_creator_note 자동생성. 1인 1메모(업서트). ──
if($mode=="cn_save"){
  demo_check_json();
  sql_query("CREATE TABLE IF NOT EXISTS nfor_creator_note (cn_id int(11) NOT NULL AUTO_INCREMENT, cn_mb_no int(11) NOT NULL DEFAULT 0, cn_memo text, cn_tags varchar(255) NOT NULL DEFAULT '', cn_admin varchar(50) NOT NULL DEFAULT '', cn_datetime datetime, PRIMARY KEY(cn_id), UNIQUE KEY uniq_mb(cn_mb_no))");
  $m = (int)$_POST['mb_no'];
  $memo = addslashes($_POST['cn_memo']);
  $tags = addslashes($_POST['cn_tags']);
  $adm  = addslashes($member[mb_id]);
  if($m){
    $ex = sql_fetch("select cn_id from nfor_creator_note where cn_mb_no='$m'");
    if($ex[cn_id]) sql_query("update nfor_creator_note set cn_memo='$memo', cn_tags='$tags', cn_admin='$adm', cn_datetime=NOW() where cn_mb_no='$m'");
    else           sql_query("insert into nfor_creator_note set cn_mb_no='$m', cn_memo='$memo', cn_tags='$tags', cn_admin='$adm', cn_datetime=NOW()");
    json_return("메모를 저장했습니다","ok");
  }
  json_return("대상이 없습니다","fail");
}

// ── 역제안: 캠페인 코드로 캠페인명 미리보기(읽기) ──
if($mode=="cp_info"){
  $code = preg_replace('/[^0-9]/','',$_POST['cp_id']);
  $c = sql_fetch("select cp_subject from nfor_campaign where cp_id='".addslashes($code)."'");
  if($c[cp_subject]) json_return($c[cp_subject],"ok");
  json_return("해당 코드의 캠페인을 찾을 수 없습니다","fail");
}

// ── 역제안: 캠페인 이름/코드 검색(읽기) → 목록 반환 ──
if($mode=="cp_search"){
  $kw = trim($_POST['kw']); $k = addslashes($kw); $code = preg_replace('/[^0-9]/','',$kw);
  $cond = "cp_subject like '%$k%'"; if($code!=='') $cond .= " or cp_id='".addslashes($code)."'";
  $q = sql_query("select cp_id, cp_subject from nfor_campaign where ($cond) order by cp_id desc limit 20");
  $arr = array(); while($c = sql_fetch_array($q)){ $arr[] = array('id'=>$c[cp_id], 'subject'=>$c[cp_subject]); }
  echo json_encode(array('result'=>'ok','list'=>$arr)); exit;
}

// ── 역제안 발송: 크리에이터에게 캠페인 추천 쪽지(nfor_note) + 기록(nfor_creator_proposal) ──
//   note_form.php와 동일하게 쪽지 insert + 회원 안읽음 카운트(mb_note) 갱신. 관리자가 직접 [보내기] 클릭 시 발송.
if($mode=="rp_send"){
  demo_check_json(); pr_ensure();
  $m = (int)$_POST['mb_no'];
  $code = preg_replace('/[^0-9]/','',$_POST['cp_id']);
  $memo = htmlspecialchars(strip_tags($_POST['rp_memo']));
  if(!$m || $memo===''){ json_return("대상·메시지를 확인해주세요","fail"); }
  $rm = sql_fetch("select mb_id from nfor_member where mb_no='$m'");
  if(!$rm[mb_id]){ json_return("회원 아이디를 찾을 수 없습니다","fail"); }
  $rid = addslashes($rm[mb_id]);
  sql_query("insert nfor_note set no_send_id='".addslashes($member[mb_id])."', no_receive_id='$rid', no_memo='".addslashes($memo)."', no_send_datetime=NOW()");
  $nid = sql_fetch("select last_insert_id() as id"); $note_id = (int)$nid['id'];   // 보낸 쪽지 id(읽음 추적용)
  $cq = sql_fetch("select count(*) as cnt from nfor_note where no_receive_id='$rid' and no_receive_datetime='0000-00-00 00:00:00' and no_receive_del='0'");
  sql_query("update nfor_member set mb_note='".(int)$cq[cnt]."' where mb_id='$rid'");
  sql_query("insert into nfor_creator_proposal set pr_mb_no='$m', pr_cp_id='".addslashes($code)."', pr_note_id='$note_id', pr_admin='".addslashes($member[mb_id])."', pr_datetime=NOW()");
  json_return("역제안 쪽지를 발송했습니다 (회원 쪽지함으로 전달)","ok");
}

include_once "head.php";

// ============================================================================
//  역제안 발송 이력 화면 (?view=proposals)
// ============================================================================
if($_GET['view']=='proposals'){
  $plist = array();
  if(sql_fetch("show tables like 'nfor_creator_proposal'")){
    pr_ensure();
    $pq = sql_query("select p.pr_datetime, p.pr_cp_id, p.pr_admin, p.pr_mb_no, p.pr_note_id,
        m.mb_name, m.mb_nick, c.cp_subject, n.no_receive_datetime as read_dt,
        (select count(*) from nfor_review r where r.rv_mb_no=p.pr_mb_no and r.rv_cp_id=p.pr_cp_id and r.rv_delete='0' and r.rv_datetime>=p.pr_datetime) as applied
      from nfor_creator_proposal p
      left join nfor_member m on m.mb_no=p.pr_mb_no
      left join nfor_campaign c on c.cp_id=p.pr_cp_id
      left join nfor_note n on n.no_id=p.pr_note_id
      order by p.pr_id desc limit 300");
    while($pr = sql_fetch_array($pq)) $plist[] = $pr;
  }
  $cssv = @filemtime(dirname(__FILE__).'/mc_board.css');
?>
<link rel="stylesheet" href="mc_review.css?v=<?=@filemtime(dirname(__FILE__).'/mc_review.css')?>">
<link rel="stylesheet" href="mc_board.css?v=<?=$cssv?>">
<div id="mc-board">
  <div style="font-size:12.5px;color:#8b95a1;margin:6px 2px 8px">회원/발송관리 › 크리에이터 목록 › 역제안 이력</div>
  <div class="cl-nav" style="margin:0 2px 16px">
    <a href="creator_list.php" class="subtab">🧑‍🎨 크리에이터 목록</a>
    <a href="creator_list.php?view=proposals" class="subtab on">📣 역제안 이력</a>
  </div>
  <div style="font-size:18px;font-weight:800;margin:0 2px 12px">📣 역제안 발송 이력 <span style="font-size:13px;font-weight:600;color:#8b95a1">· 최근 <?=number_format(count($plist))?>건</span></div>
<?php if(!$plist){ ?>
  <div class="bempty">아직 역제안 발송 내역이 없습니다. 크리에이터 목록에서 「📣 역제안」으로 보낼 수 있어요.</div>
<?php } else { ?>
  <table class="mtable">
    <thead><tr><th>발송일시</th><th style="text-align:left">크리에이터</th><th style="text-align:left">추천 캠페인</th><th>읽음</th><th>신청 전환</th><th>보낸 관리자</th></tr></thead>
    <tbody>
    <?php foreach($plist as $pr){ $pr = nfor_tag_out($pr);
      $read = ($pr[read_dt] && substr($pr[read_dt],0,4)!=='0000');
      $applied = ((int)$pr[applied] > 0);
    ?>
      <tr>
        <td><?=substr($pr[pr_datetime],0,16)?></td>
        <td class="who-l"><div class="nm"><?=$pr[mb_name]?$pr[mb_name]:$pr[mb_nick]?></div></td>
        <td class="who-l"><?=$pr[cp_subject]?$pr[cp_subject]:'(삭제된 캠페인)'?> <span style="color:#8b95a1;font-size:11.5px"><?=$pr[pr_cp_id]?'· '.$pr[pr_cp_id]:''?></span></td>
        <td><?=$read?'<span class="pill done">읽음</span>':'<span class="pill gray">안읽음</span>'?></td>
        <td><?=$applied?'<span class="pill done">✓ 신청함</span>':'<span class="pill gray">미신청</span>'?></td>
        <td><?=$pr[pr_admin]?></td>
      </tr>
    <?php } ?>
    </tbody>
  </table>
  <div style="font-size:12px;color:#8b95a1;margin:12px 2px 0">ℹ️ <b>읽음</b>=회원이 쪽지를 확인함 · <b>신청 전환</b>=역제안 후 해당 캠페인에 실제 신청함</div>
<?php } ?>
</div>
<?php include_once "tail.php"; exit; }

// ============================================================================
//  크리에이터 목록 (2026-06-15 신설) — 브랜드커넥트 '크리에이터 목록' 풍.
//   nfor_review(rv_mb_* 덴орм- 필드) 집계 → 참여 이력 있는 회원=크리에이터. 회원/리뷰 무수정.
// ============================================================================
$kw   = trim($_GET['kw']);
$fsex = preg_replace('/[^MF]/','',$_GET['fsex']);
$fav  = ($_GET['fav']=='1') ? '1' : '';
$sort = preg_replace('/[^a-z_]/','',$_GET['sort']); if(!$sort) $sort='recent';
$page = max(1,(int)$_GET['page']); $rows_per = 20; $offset = ($page-1)*$rows_per;

$GROUPS = array('푸드','뷰티','리빙','육아','패션','반려','기타');
$fgrp = trim($_GET['fgrp']);
$fav_exists = sql_fetch("show tables like 'nfor_creator_fav'") ? true : false;
$fav_grp = false;
if($fav_exists){ $gc = sql_query("show columns from nfor_creator_fav like 'cf_group'"); if(sql_fetch_array($gc)) $fav_grp = true; }
// 전체 그룹 목록 = 프리셋 + DB의 커스텀 그룹
$allgroups = $GROUPS;
if($fav_grp){ $gq = sql_query("select distinct cf_group from nfor_creator_fav where cf_group<>'' order by cf_group"); while($gg = sql_fetch_array($gq)){ if(!in_array($gg[cf_group],$allgroups)) $allgroups[] = $gg[cf_group]; } }

$where = " where rv_delete='0' and rv_mb_no > 0 ";
if($kw !== ''){ $k = addslashes($kw); $where .= " and (rv_mb_name like '%$k%' or rv_mb_nick like '%$k%' or rv_mb_hp like '%$k%') "; }
if($fsex){ $where .= " and rv_mb_sex='".addslashes($fsex)."' "; }
if($fav && $fav_exists){ $where .= " and rv_mb_no in (select cf_mb_no from nfor_creator_fav) "; }
if($fgrp!=='' && $fav_grp){ $where .= " and rv_mb_no in (select cf_mb_no from nfor_creator_fav where cf_group='".addslashes($fgrp)."') "; }

$order = "last_join desc";
if($sort=='join') $order = "join_cnt desc";
elseif($sort=='done') $order = "done_cnt desc";
elseif($sort=='reco') $order = "(sum(rv_step='4')/count(*)) desc, done_cnt desc";   // 추천순=완료율(참여3+ 우선)

$trow = sql_fetch("select count(*) as c from (select rv_mb_no from nfor_review $where group by rv_mb_no) t");
$total = (int)$trow['c'];
$total_page = max(1, ceil($total/$rows_per));

$having = ($sort=='reco') ? " having join_cnt>=3 " : "";   // 추천순은 신뢰도 위해 참여 3회+ 만
$list = sql_query("select rv_mb_no,
    max(rv_mb_name) as nm, max(rv_mb_nick) as nick, max(rv_mb_hp) as hp, max(rv_mb_sex) as sex, max(rv_mb_age) as age,
    count(*) as join_cnt, sum(rv_step='4') as done_cnt, max(rv_datetime) as last_join
  from nfor_review $where group by rv_mb_no $having order by $order limit $offset, $rows_per");

// 현재 페이지 mb_no 수집 → 찜/평가 일괄 로드
$mbs = array(); $buf = array();
while($r = sql_fetch_array($list)){ $buf[] = $r; $mbs[] = (int)$r[rv_mb_no]; }
$favset = array(); $crmap = array(); $notemap = array(); $propmap = array(); $cr_glabel = array('best'=>'⭐최고','good'=>'👍좋음','soso'=>'😐아쉬움');
if($mbs){
  $in = implode(',', $mbs);
  if($fav_exists){ $gsel = $fav_grp ? "cf_mb_no, cf_group" : "cf_mb_no, '' as cf_group"; $fq = sql_query("select $gsel from nfor_creator_fav where cf_mb_no in ($in)"); while($f=sql_fetch_array($fq)) $favset[$f[cf_mb_no]]=array('on'=>1,'group'=>$f[cf_group]); }
  if(sql_fetch("show tables like 'nfor_creator_review'")){
    $cq = sql_query("select cr_mb_no, cr_grade, cr_tags from nfor_creator_review where cr_mb_no in ($in) order by cr_id desc");
    while($cr=sql_fetch_array($cq)){ $m=$cr[cr_mb_no];
      if(!isset($crmap[$m])) $crmap[$m]=array('cnt'=>0,'last'=>$cr[cr_grade],'tags'=>$cr[cr_tags]);
      $crmap[$m]['cnt']++;
    }
  }
  if(sql_fetch("show tables like 'nfor_creator_note'")){
    $nq = sql_query("select cn_mb_no, cn_memo, cn_tags from nfor_creator_note where cn_mb_no in ($in)");
    while($nn=sql_fetch_array($nq)){ $notemap[$nn[cn_mb_no]] = array('memo'=>$nn[cn_memo], 'tags'=>$nn[cn_tags]); }
  }
  if(sql_fetch("show tables like 'nfor_creator_proposal'")){
    $pq = sql_query("select pr_mb_no, count(*) as c from nfor_creator_proposal where pr_mb_no in ($in) group by pr_mb_no");
    while($pp=sql_fetch_array($pq)){ $propmap[$pp[pr_mb_no]] = (int)$pp[c]; }
  }
}
$ver = @filemtime(dirname(__FILE__)."/mc_review.css");
?>
<link rel="stylesheet" href="mc_review.css?v=<?=$ver?>">
<link rel="stylesheet" href="mc_board.css?v=<?=@filemtime(dirname(__FILE__).'/mc_board.css')?>">
<script src="mc_blog_count.js?v=<?=@filemtime(dirname(__FILE__).'/mc_blog_count.js')?>"></script>

<div id="mc-board">
  <div style="font-size:12.5px;color:#8b95a1;margin:6px 2px 8px">회원/발송관리 › 크리에이터 목록</div>
  <div class="cl-nav" style="margin:0 2px 14px">
    <a href="creator_list.php" class="subtab on">🧑‍🎨 크리에이터 목록</a>
    <a href="creator_list.php?view=proposals" class="subtab">📣 역제안 이력</a>
  </div>
  <div style="font-size:22px;font-weight:800;letter-spacing:-.5px;margin:0 2px 16px">🧑‍🎨 크리에이터 목록 <span style="font-size:13px;font-weight:600;color:#8b95a1">· 참여 이력 있는 회원 <?=number_format($total)?>명</span></div>

  <form method="get" class="mc-filter" style="margin-bottom:16px">
    <input type="text" name="kw" value="<?=htmlspecialchars($kw)?>" placeholder="이름·닉네임·전화 검색" style="border:1px solid #d8dee7;border-radius:9px;padding:8px 12px;font-size:13px;min-width:200px">
    <select name="fsex"><option value="">성별 전체</option><option value="F"<?=$fsex=='F'?' selected':''?>>여성</option><option value="M"<?=$fsex=='M'?' selected':''?>>남성</option></select>
    <select name="sort"><option value="recent"<?=$sort=='recent'?' selected':''?>>최근 참여순</option><option value="reco"<?=$sort=='reco'?' selected':''?>>⭐ 추천순(완료율)</option><option value="join"<?=$sort=='join'?' selected':''?>>참여 많은순</option><option value="done"<?=$sort=='done'?' selected':''?>>완료 많은순</option></select>
    <label style="display:flex;align-items:center;gap:5px;font-size:13px;font-weight:700;color:#475569;cursor:pointer"><input type="checkbox" name="fav" value="1"<?=$fav?' checked':''?> onchange="this.form.submit()"> ⭐ 찜만</label>
    <?php if($fav_grp){ ?><select name="fgrp" onchange="this.form.submit()"><option value="">찜그룹 전체</option><?php foreach($allgroups as $g){ ?><option value="<?=htmlspecialchars($g)?>"<?=$fgrp==$g?' selected':''?>>🏷 <?=htmlspecialchars($g)?></option><?php } ?></select><?php } ?>
    <button type="submit" class="btn btn-white btn-sm" style="padding:8px 16px">검색</button>
    <span class="f-count"><?=number_format($total)?>명</span>
  </form>

<?php if(!$buf){ ?>
  <div class="bempty">조건에 맞는 크리에이터가 없습니다.</div>
<?php } else {
  foreach($buf as $row){ $row = nfor_tag_out($row); $mb = (int)$row[rv_mb_no];
    $rm = sql_fetch("select mb_blog, mb_point from nfor_member where mb_no='$mb'");
    $dsx=($row[sex]=='M'?'남':($row[sex]=='F'?'여':'')); $dag=($row[age]?$row[age].'대':'');
    $demo=trim($dag.($dag&&$dsx?' · ':'').$dsx);
    $on = isset($favset[$mb]);
    $grp = $on ? $favset[$mb]['group'] : '';
    $nt = isset($notemap[$mb]) ? $notemap[$mb] : array('memo'=>'','tags'=>'');
    $rate = $row[join_cnt]>0 ? round($row[done_cnt]/$row[join_cnt]*100) : 0;     // 완료율
    $is_top = ($rate>=80 && $row[done_cnt]>=3);                                   // 우수 크리에이터
?>
  <div class="icard">
    <div class="pf"><?=mb_substr($row[nm]?$row[nm]:$row[nick],0,1,'utf-8')?></div>
    <div class="main">
      <div class="nm"><a href="javascript:;" class="cl-detail" data-mb="<?=$mb?>" style="color:inherit;text-decoration:none"><?=$row[nm]?$row[nm]:$row[nick]?></a>
        <?php if($is_top){ ?><span class="cr-badge" style="margin-left:6px;background:#fff3bf;border-color:#ffe066;color:#a5790a">🏅 우수 <?=$rate?>%</span><?php } ?>
        <?php if($cm=$crmap[$mb]){ ?><span class="cr-badge" style="margin-left:6px">🤝 평가 <?=$cm['cnt']?>회 · <?=$cr_glabel[$cm['last']]?></span><?php } ?>
        <?php if($grp){ ?><span class="cf-grp-chip">🏷 <?=$grp?></span><?php } ?>
      </div>
      <div class="meta"><?=$demo?'<b style="color:#475569">'.$demo.'</b> · ':''?><?=$row[hp]?$row[hp]:'-'?><?=$row[nick]&&$row[nm]?' · '.$row[nick]:''?></div>
      <div class="chans">
        <?php if($rm[mb_blog]){ ?><a href="https://blog.naver.com/<?=$rm[mb_blog]?>" target="_blank" class="btn btn-white btn-sm mc-channel-btn">블로그 보러가기</a>
        <span class="mc-ch-meta"><span id="blog_totalcount2_<?=$rm[mb_blog]?>">브론즈</span> · <span id="blog_totalcount_<?=$rm[mb_blog]?>" data-ref="<?=$rm[mb_blog]?>">0</span> 방문</span>
        <span class="mc-spark" id="mc-spark_<?=$rm[mb_blog]?>"></span><?php }else{ ?><span style="color:#c0c6cd;font-size:12px">블로그 미연동</span><?php } ?>
      </div>
      <div class="stats">
        <div class="stat"><div class="sk">참여</div><div class="sv"><?=number_format($row[join_cnt])?>회</div></div>
        <div class="stat"><div class="sk">등록완료</div><div class="sv"><?=number_format($row[done_cnt])?>회</div></div>
        <div class="stat"><div class="sk">보유 포인트</div><div class="sv"><?=number_format($rm[mb_point])?>P</div></div>
        <div class="stat"><div class="sk">최근 참여</div><div class="sv" style="font-size:12.5px"><?=substr($row[last_join],0,10)?></div></div>
      </div>
      <?php if($nt['memo'] || $nt['tags']){ ?>
      <div class="cl-note">📝 <?php if($nt['tags']){ ?><b><?=htmlspecialchars(str_replace(',',' · ',$nt['tags']))?></b> <?php } ?><?=nl2br(htmlspecialchars($nt['memo']))?></div>
      <?php } ?>
    </div>
    <div class="right">
      <button type="button" class="mc-fav<?=$on?' on':''?>" data-mb="<?=$mb?>"><?=$on?'⭐ 찜됨':'☆ 찜하기'?></button>
      <select class="cf-grp" data-mb="<?=$mb?>" title="찜 그룹 지정"><option value=""<?=$grp===''?' selected':''?>>🏷 그룹 없음</option><?php $opts=$allgroups; if($grp!=='' && !in_array($grp,$opts)) $opts[]=$grp; foreach($opts as $g){ ?><option value="<?=htmlspecialchars($g)?>"<?=$grp==$g?' selected':''?>><?=htmlspecialchars($g)?></option><?php } ?><option value="__custom__">+ 직접 입력…</option></select>
      <button type="button" class="tbtn cl-memo" data-mb="<?=$mb?>" data-nm="<?=htmlspecialchars($row[nm]?$row[nm]:$row[nick])?>" data-memo="<?=htmlspecialchars($nt['memo'])?>" data-tags="<?=htmlspecialchars($nt['tags'])?>"><?=($nt['memo']||$nt['tags'])?'📝 메모 ✓':'📝 메모'?></button>
      <button type="button" class="mc-propose cl-rp" data-mb="<?=$mb?>" data-nm="<?=htmlspecialchars($row[nm]?$row[nm]:$row[nick])?>">📣 역제안<?=$propmap[$mb]?' ('.$propmap[$mb].')':''?></button>
      <button type="button" class="tbtn cl-detail" data-mb="<?=$mb?>">회원정보</button>
    </div>
  </div>
<?php } } ?>

  <?php if($total_page>1){ $qs="kw=".urlencode($kw)."&fsex=$fsex&sort=$sort&fav=$fav"; ?>
  <div class="mc-paging">
    <?php if($page>1){ ?><a href="?<?=$qs?>&page=<?=$page-1?>">‹ 이전</a><?php } ?>
    <span class="cur"><?=$page?> / <?=$total_page?></span>
    <?php if($page<$total_page){ ?><a href="?<?=$qs?>&page=<?=$page+1?>">다음 ›</a><?php } ?>
  </div>
  <?php } ?>
</div>

<!-- 크리에이터 메모 모달 (관리자 전용) -->
<div id="cn-modal" class="cr-modal">
  <div class="cr-dim"></div>
  <div class="cr-box">
    <div class="cr-head">📝 크리에이터 메모 <button type="button" class="cn-x">×</button></div>
    <div class="cr-sub">크리에이터 : <b id="cn-name"></b> <span style="color:#8b95a1;font-weight:600">· 관리자만 보는 메모입니다</span></div>
    <div class="cr-q">태그 <span style="color:#8b95a1;font-weight:600;font-size:12px">(복수 선택)</span></div>
    <div class="cr-tags" id="cn-tags">
      <button type="button" class="cr-tag">우수</button>
      <button type="button" class="cr-tag">재협업</button>
      <button type="button" class="cr-tag">사진 우수</button>
      <button type="button" class="cr-tag">연락 빠름</button>
      <button type="button" class="cr-tag">연락 느림</button>
      <button type="button" class="cr-tag">기한 지연</button>
      <button type="button" class="cr-tag">주의</button>
    </div>
    <div class="cr-q">메모</div>
    <textarea id="cn-memo" class="cr-memo" placeholder="이 크리에이터에 대한 메모 (예: 사진 퀄리티 최상, 연락은 카톡이 빠름 …)"></textarea>
    <button type="button" id="cn-save-btn" class="cr-save-btn">저장하기</button>
  </div>
</div>

<!-- 캠페인 역제안 모달 -->
<div id="rp-modal" class="cr-modal">
  <div class="cr-dim"></div>
  <div class="cr-box">
    <div class="cr-head">📣 캠페인 역제안 <button type="button" class="rp-x">×</button></div>
    <div class="cr-sub"><b id="rp-name"></b> 님에게 캠페인 참여를 추천합니다 <span style="color:#8b95a1;font-weight:600">· 회원 쪽지함으로 전달</span></div>
    <div class="cr-q">캠페인 선택 <span style="color:#8b95a1;font-weight:600;font-size:12px">· 이름으로 검색하거나 코드를 직접 입력</span></div>
    <div class="rp-search">
      <input type="text" id="rp-code" placeholder="캠페인명 검색 (예: 토마토)  또는  코드 입력 (예: 1780967908)" autocomplete="off">
      <button type="button" id="rp-load" class="mc-btn">🔍 검색</button>
    </div>
    <div id="rp-results" class="rp-results"></div>
    <div id="rp-cpname" style="font-size:13.5px;font-weight:800;color:#02b150;margin-top:10px;min-height:18px"></div>
    <div class="cr-q">메시지</div>
    <textarea id="rp-memo" class="cr-memo" style="min-height:100px" placeholder="캠페인을 불러오면 추천 메시지가 자동으로 채워집니다. 수정해서 보내세요."></textarea>
    <button type="button" id="rp-send-btn" class="cr-save-btn">📣 역제안 보내기</button>
  </div>
</div>

<script>
// 찜 토글
$(document).on('click','#mc-board .mc-fav',function(){
  var $b=$(this), mb=$b.data('mb');
  $.ajax({type:'post',url:'creator_list.php',dataType:'json',data:{mode:'cf_toggle',mb_no:mb},
    success:function(r){ if(r&&r.result=='on'){ $b.addClass('on').text('⭐ 찜됨'); } else if(r&&r.result=='off'){ $b.removeClass('on').text('☆ 찜하기'); } else { alert((r&&r.msg)||'오류'); } },
    error:function(){ alert('처리 중 오류'); }
  });
});
// 찜 그룹 지정 (그룹 선택 시 자동 찜 · '직접 입력'은 커스텀 그룹명)
$(document).on('change','#mc-board .cf-grp',function(){
  var $s=$(this), mb=$s.data('mb'), g=$s.val(), $card=$s.closest('.icard');
  if(g==='__custom__'){
    var name=(prompt('새 그룹명을 입력하세요 (예: 맛집, VIP, 단골)')||'').trim();
    if(!name){ $s.val(''); return; }
    if(!$s.find('option').filter(function(){ return this.value===name; }).length){
      $('<option>').attr('value',name).text(name).insertBefore($s.find('option[value="__custom__"]'));
    }
    $s.val(name); g=name;
  }
  $.ajax({type:'post',url:'creator_list.php',dataType:'json',data:{mode:'cf_group',mb_no:mb,cf_group:g},
    success:function(r){
      if(r&&r.result=='ok'){
        var $fav=$card.find('.mc-fav'); $fav.addClass('on').text('⭐ 찜됨');
        var $chip=$card.find('.cf-grp-chip');
        if(g){ if($chip.length) $chip.text('🏷 '+g); else $card.find('.nm').append(' <span class="cf-grp-chip">🏷 '+g+'</span>'); }
        else { $chip.remove(); }
      } else { alert((r&&r.msg)||'그룹 저장 오류'); }
    },
    error:function(){ alert('그룹 저장 중 오류'); }
  });
});
// 회원정보 팝업 (기존 회원 수정폼 재사용)
$(document).on('click','#mc-board .cl-detail',function(){
  window.open('member_form.php?mb_no='+$(this).data('mb'), 'member_form', 'width=820,height=760,scrollbars=yes');
});

// 크리에이터 메모 모달
var cnMB=0;
$(document).on('click','#mc-board .cl-memo',function(){
  var $b=$(this); cnMB=$b.data('mb');
  $('#cn-name').text($b.data('nm'));
  $('#cn-memo').val($b.attr('data-memo')||'');
  var tags=(($b.attr('data-tags')||'').split(',')).map(function(t){return t.trim();});
  $('#cn-tags .cr-tag').each(function(){ $(this).toggleClass('on', tags.indexOf($(this).text())>-1); });
  $('#cn-modal').css('display','flex');
});
$(document).on('click','#cn-modal .cn-x, #cn-modal .cr-dim',function(){ $('#cn-modal').hide(); });
$(document).on('click','#cn-modal .cr-tag',function(){ $(this).toggleClass('on'); });
$(document).on('click','#cn-save-btn',function(){
  var tags=[]; $('#cn-modal .cr-tag.on').each(function(){ tags.push($(this).text()); });
  var $b=$(this); $b.prop('disabled',true).text('저장 중…');
  $.ajax({type:'post',url:'creator_list.php',dataType:'json',data:{mode:'cn_save',mb_no:cnMB,cn_memo:$('#cn-memo').val(),cn_tags:tags.join(',')},
    success:function(r){ alert((r&&r.msg)||'저장됨'); if(r&&r.result=='ok'){ location.reload(); } else { $b.prop('disabled',false).text('저장하기'); } },
    error:function(){ alert('저장 중 오류'); $b.prop('disabled',false).text('저장하기'); }
  });
});

// 역제안 모달
var rpMB=0, rpSelCode='';
function rpEsc(s){ return (s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
function rpSelect(code, subject){   // 캠페인 확정 → 안내문구 자동작성
  rpSelCode = String(code);
  $('#rp-code').val(subject);
  $('#rp-results').empty().hide();
  $('#rp-cpname').css('color','#02b150').text('✔ '+subject+'  (코드 '+code+')');
  $('#rp-memo').val('안녕하세요 '+$('#rp-name').text()+'님! 😊\n\n‘'+subject+'’ 캠페인이 잘 어울리실 것 같아 추천드려요.\n관심 있으시면 신청 부탁드립니다.\n\n▶ 신청: https://meta-chehumdan.com/campaign.php?cp_id='+code);
}
$(document).on('click','#mc-board .cl-rp',function(){
  rpMB=$(this).data('mb'); rpSelCode=''; $('#rp-name').text($(this).data('nm'));
  $('#rp-code').val(''); $('#rp-cpname').text(''); $('#rp-memo').val(''); $('#rp-results').empty().hide();
  $('#rp-modal').css('display','flex'); setTimeout(function(){ $('#rp-code').focus(); },50);
});
$(document).on('click','#rp-modal .rp-x, #rp-modal .cr-dim',function(){ $('#rp-modal').hide(); });
// 검색(이름) 또는 직접 로드(숫자 코드)
function rpDoSearch(){
  var kw=($('#rp-code').val()||'').trim();
  if(!kw){ alert('캠페인명 또는 코드를 입력하세요.'); return; }
  rpSelCode='';
  if(/^[0-9]+$/.test(kw)){   // 숫자만 → 코드 직접 로드
    $.ajax({type:'post',url:'creator_list.php',dataType:'json',data:{mode:'cp_info',cp_id:kw},
      success:function(r){ if(r&&r.result=='ok'){ rpSelect(kw, r.msg); } else { $('#rp-results').hide(); $('#rp-cpname').css('color','#f03e3e').text('✘ '+((r&&r.msg)||'코드를 찾을 수 없습니다')); } },
      error:function(){ alert('조회 중 오류'); }});
    return;
  }
  $.ajax({type:'post',url:'creator_list.php',dataType:'json',data:{mode:'cp_search',kw:kw},
    success:function(r){
      var list=(r&&r.list)||[];
      if(!list.length){ $('#rp-results').html('<div class="rp-empty">검색 결과가 없습니다.</div>').show(); return; }
      var h=''; for(var i=0;i<list.length;i++){ h+='<button type="button" class="rp-result" data-id="'+list[i].id+'" data-subject="'+rpEsc(list[i].subject).replace(/"/g,'&quot;')+'"><span class="rs-nm">'+rpEsc(list[i].subject)+'</span><span class="rs-code">'+list[i].id+'</span></button>'; }
      $('#rp-results').html(h).show();
    },
    error:function(){ alert('검색 중 오류'); }});
}
$(document).on('click','#rp-load',rpDoSearch);
$(document).on('keydown','#rp-code',function(e){ if(e.key==='Enter'){ e.preventDefault(); rpDoSearch(); } });
$(document).on('click','#rp-modal .rp-result',function(){ rpSelect($(this).data('id'), $(this).attr('data-subject')); });
$(document).on('click','#rp-send-btn',function(){
  var code=rpSelCode || ($('#rp-code').val()||'').replace(/[^0-9]/g,''), memo=$('#rp-memo').val();
  if(!code){ alert('먼저 캠페인을 검색·선택하세요.'); return; }
  if(!memo.trim()){ alert('메시지를 입력하세요.'); return; }
  if(!confirm($('#rp-name').text()+'님에게 역제안 쪽지를 보낼까요?')) return;
  var $b=$(this); $b.prop('disabled',true).text('보내는 중…');
  $.ajax({type:'post',url:'creator_list.php',dataType:'json',data:{mode:'rp_send',mb_no:rpMB,cp_id:code,rp_memo:memo},
    success:function(r){ alert((r&&r.msg)||'발송됨'); if(r&&r.result=='ok'){ $('#rp-modal').hide(); location.reload(); } else { $b.prop('disabled',false).text('📣 역제안 보내기'); } },
    error:function(){ alert('발송 중 오류'); $b.prop('disabled',false).text('📣 역제안 보내기'); }
  });
});
</script>

<?php include_once "tail.php"; ?>
