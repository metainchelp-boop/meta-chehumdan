<?php
include_once "path.php";
include_once "inc_review_head.php";   // 인증($member)+$admin[rv_step]+$admin[rv_cp_id](캠페인옵션)+$nfor

// ── 보드 내 액션 (기존 검증된 함수 그대로 재사용 · 단건 처리 · 확인창은 버튼 data-confirm) ──
//  ★ review_asign(2차확정)·review_confirm(검수승인)은 회원 알림/포인트 발생 = 실제 쓰기. 신청목록 등과 100% 동일 로직.
//    페이지 자체가 nfor_access(관리자 권한)로 게이트되므로 권한자만 도달.
if($mode=="bd_pre")    { demo_check_json(); review_pre_asign($$id);   json_return("1차 후보로 보냈습니다 (회원 알림 없음)","ok"); }
if($mode=="bd_asign")  { demo_check_json(); review_asign($$id);       json_return("2차 확정 완료 — 회원에게 선정 알림이 발송되었습니다","ok"); }
if($mode=="bd_exclude"){ demo_check_json(); review_pre_exclude($$id); json_return("제외 — 신청목록으로 이동했습니다","ok"); }
if($mode=="bd_confirm"){ demo_check_json(); review_confirm($$id);     json_return("검수 승인 — 등록완료 처리되었습니다","ok"); }
if($mode=="bd_cancel") { demo_check_json(); review_cancel($$id);      json_return("미선정 처리되었습니다","ok"); }

// 일괄 검수 승인(2026-06-15) — 체크한 검수요청(rv_step=3)만 review_confirm 반복. 단건 bd_confirm과 동일 로직.
if($mode=="bd_confirm_bulk"){
  demo_check_json();
  $arr = array_filter(array_map('intval', explode(',', (string)$_POST['rv_ids'])));
  $n = 0;
  foreach($arr as $rid){
    $chk = sql_fetch("select rv_step from nfor_review where rv_id='".(int)$rid."' and rv_delete='0'");
    if($chk[rv_step]=='3'){ review_confirm($rid); $n++; }   // 현재 검수요청 건만(중복·오처리 방지)
  }
  json_return($n."명 일괄 검수 승인 완료 (등록완료 처리)","ok");
}

// 크리에이터 리뷰(별점·태그) 저장 (2026-06-13) — 저장공간 nfor_creator_review 자동생성(최초 1회, 기존 nfor와 동일 charset).
//  기존 회원/리뷰 레코드는 건드리지 않고 별도 평가 테이블에만 기록. 다음 선정 때 참고용.
if($mode=="cr_save"){
  demo_check_json();
  sql_query("CREATE TABLE IF NOT EXISTS nfor_creator_review (
    cr_id int(11) NOT NULL AUTO_INCREMENT, cr_mb_no int(11) NOT NULL DEFAULT 0,
    cr_cp_id varchar(20) NOT NULL DEFAULT '', cr_rv_id int(11) NOT NULL DEFAULT 0,
    cr_grade varchar(10) NOT NULL DEFAULT '', cr_tags varchar(255) NOT NULL DEFAULT '',
    cr_memo text, cr_admin varchar(50) NOT NULL DEFAULT '', cr_datetime datetime,
    PRIMARY KEY(cr_id), KEY idx_mb(cr_mb_no))");
  $cr_mb_no = (int)$_POST['cr_mb_no'];
  $cr_rv_id = (int)$_POST['cr_rv_id'];
  $cr_cp_id = addslashes(preg_replace('/[^0-9]/','',$_POST['cr_cp_id']));
  $cr_grade = addslashes($_POST['cr_grade']);
  $cr_tags  = addslashes($_POST['cr_tags']);
  $cr_memo  = addslashes($_POST['cr_memo']);
  $cr_admin = addslashes($member[mb_id]);
  if($cr_mb_no && ($cr_grade=='best'||$cr_grade=='good'||$cr_grade=='soso')){
    sql_query("insert into nfor_creator_review set cr_mb_no='$cr_mb_no', cr_cp_id='$cr_cp_id', cr_rv_id='$cr_rv_id', cr_grade='$cr_grade', cr_tags='$cr_tags', cr_memo='$cr_memo', cr_admin='$cr_admin', cr_datetime=NOW()");
    json_return("크리에이터 평가가 저장되었습니다","ok");
  }
  json_return("평가 등급(최고/좋음/아쉬움)을 선택해주세요","fail");
}

include_once "head.php";              // 관리자 공통 레이아웃

// ============================================================================
//  캠페인 진행 보드 — 브랜드커넥트풍 4탭 허브 (2026-06-13 확장)
//   캠페인 1개 선택 → ①캠페인정보 ②신청자조회·선정 ③진행관리 ④정산  한 화면에서.
//   기존 목록 페이지는 무수정. 실제 선정/검수 처리는 위 검증된 함수 재사용(또는 기존 목록 연결).
// ============================================================================

$cp  = preg_replace('/[^0-9]/', '', $rv_cp_id);                 // 선택 캠페인 (검색폼과 동일 파라미터)
$tab = preg_replace('/[^a-z]/', '', $_GET['tab']); if(!$tab) $tab = 'info';
$st  = preg_replace('/[^0-9]/', '', $_GET['st']);  if($st==='') $st = '1';
$fage = preg_replace('/[^0-9p]/', '', $_GET['fage']);          // ②선정 연령 필터(서버) — '20'·'30'·'40'·'50p'
$age_sql = '';
if($fage==='50p') $age_sql = " and rv_mb_age >= '50' ";
elseif($fage!=='') $age_sql = " and rv_mb_age='".addslashes($fage)."' ";
$can_act = ($member[mb_admin] >= $config[cf_review_asign]);     // 선정·검수 권한

// 단계 → 기존 관리 페이지 매핑(딥링크)
$manage = array('1'=>'review_wait_list.php','8'=>'review_pre_list.php','2'=>'review_asign_list.php',
                '3'=>'review_post_list.php','4'=>'review_post_asign_list.php','5'=>'review_cancel_list.php',
                '6'=>'review_cancel_list.php','7'=>'review_edit_list.php');

function _bc($cnt,$k){ return isset($cnt[$k]) ? (int)$cnt[$k] : 0; }
function _d($dt){ $t=@strtotime($dt); return $t ? date("Y-m-d",$t) : '-'; }

$ver = @filemtime(dirname(__FILE__)."/mc_review.css");
?>
<link rel="stylesheet" href="mc_review.css?v=<?=$ver?>">
<link rel="stylesheet" href="mc_board.css?v=<?=@filemtime(dirname(__FILE__).'/mc_board.css')?>">
<script src="mc_blog_count.js?v=<?=@filemtime(dirname(__FILE__).'/mc_blog_count.js')?>"></script>
<script src="mc_select_search.js?v=<?=@filemtime(dirname(__FILE__).'/mc_select_search.js')?>"></script>

<div id="mc-board">

  <div style="font-size:12.5px;color:#8b95a1;margin:6px 2px 8px">신청서관리 › 리뷰통합관리 › 캠페인 진행 보드</div>
  <div style="font-size:22px;font-weight:800;letter-spacing:-.5px;margin:0 2px 16px">📋 캠페인 진행 보드</div>

  <!-- 캠페인 선택 -->
  <form name="fsearch" id="fsearch" method="get" class="bsel">
    <label>캠페인</label>
    <?=admin_select($_GET,"rv_cp_id","","onchange=\"this.form.submit()\"","0")?>
    <input type="hidden" name="tab" value="<?=$tab?>">
    <button type="submit" class="btn btn-white btn-sm" style="padding:8px 16px">보기</button>
    <span style="color:#8b95a1;font-size:12.5px">캠페인을 검색·선택하면 진행 현황이 표시됩니다.</span>
  </form>

<?php if(!$cp){ ?>
  <div class="bempty">상단에서 캠페인을 선택하세요. 신청 → 선정 → 검수 → 완료까지 한 화면에서 확인할 수 있습니다.</div>
<?php
} else {
  $cpd = sql_fetch("select * from nfor_campaign where cp_id='".addslashes($cp)."'");
  // 단계별 집계
  $cnt = array();
  $q = sql_query("select count(*) as c, rv_step from nfor_review where rv_cp_id='".addslashes($cp)."' and rv_delete='0' group by rv_step");
  while($r = sql_fetch_array($q)){ $cnt[$r['rv_step']] = (int)$r['c']; }
  $total_apply = array_sum($cnt);  // 누적 총 신청(전 단계 합)

  // 크리에이터 과거 평가 이력 로드 (이 캠페인 신청자들의 mb_no 기준 · 테이블 없으면 건너뜀)
  $crmap = array();
  if(sql_fetch("show tables like 'nfor_creator_review'")){
    $cq = sql_query("select cr_mb_no, cr_grade, cr_tags from nfor_creator_review where cr_mb_no in (select rv_mb_no from nfor_review where rv_cp_id='".addslashes($cp)."' and rv_delete='0') order by cr_id desc");
    while($cr = sql_fetch_array($cq)){ $m = $cr[cr_mb_no];
      if(!isset($crmap[$m])) $crmap[$m] = array('cnt'=>0,'best'=>0,'good'=>0,'soso'=>0,'lastgrade'=>$cr[cr_grade],'lasttags'=>$cr[cr_tags]);
      $crmap[$m]['cnt']++;
      if($cr[cr_grade]=='best') $crmap[$m]['best']++; elseif($cr[cr_grade]=='good') $crmap[$m]['good']++; elseif($cr[cr_grade]=='soso') $crmap[$m]['soso']++;
    }
  }
  $cr_glabel = array('best'=>'⭐ 최고','good'=>'👍 좋음','soso'=>'😐 아쉬움');

  // 크리에이터 메모 로드 (선정 카드 표시용 · creator_list에서 작성한 관리자 메모)
  $notemap = array();
  if(sql_fetch("show tables like 'nfor_creator_note'")){
    $nq = sql_query("select cn_mb_no, cn_memo, cn_tags from nfor_creator_note where cn_mb_no in (select rv_mb_no from nfor_review where rv_cp_id='".addslashes($cp)."' and rv_delete='0')");
    while($nn = sql_fetch_array($nq)){ $notemap[$nn[cn_mb_no]] = array('memo'=>$nn[cn_memo], 'tags'=>$nn[cn_tags]); }
  }
?>
  <!-- 캠페인 헤더 -->
  <div class="bhead">
    <div class="th">📋</div>
    <div>
      <div class="nm"><?=$cpd[cp_subject]?></div>
      <div class="mt">
        <span class="btag live">● 진행</span>
        <span class="btag" style="background:#eef3ff;color:#3182f6">총 신청 <?=number_format($total_apply)?>명</span>
        <span class="btag">모집 <?=number_format($cpd[cp_recruit])?>명</span>
        <?php if($cpd[cp_type_text]){ ?><span class="btag"><?=$cpd[cp_type_text]?></span><?php } ?>
        <?php if($cpd[cp_point]){ ?><span class="btag" style="background:#f3eaff;color:#7c3aed">+<?=number_format($cpd[cp_point])?>P</span><?php } ?>
        <span class="btag">코드 <?=$cp?></span>
      </div>
    </div>
    <?php @include_once $nfor[path]."/mc_share.php"; $ad_share_url = rtrim($nfor[url],"/")."/ad_progress.php?cp=".$cp."&k=".mc_share_token($cp); ?>
    <div style="display:flex;gap:6px;flex-wrap:wrap">
      <button type="button" class="btn btn-sm gobtn" style="background:#0d9488;border-color:#0d9488;color:#fff" onclick="mcAdShare('<?=$ad_share_url?>')">🔗 광고주 진행현황 링크</button>
      <a class="btn btn-white btn-sm gobtn" href="<?=$nfor[path]?>/campaign.php?cp_id=<?=$cp?>" target="_blank">캠페인 보기 ↗</a>
    </div>
  </div>
  <script>
  function mcAdShare(u){
    if(navigator.clipboard && navigator.clipboard.writeText){
      navigator.clipboard.writeText(u).then(function(){ alert("광고주에게 보낼 진행현황 링크가 복사되었습니다.\n\n"+u+"\n\n※ 로그인 없이 열람되며, 신청자 이름은 마스킹됩니다.\n※ 광고주가 '선정 제외'하면 즉시 미선정 처리됩니다."); },
        function(){ window.prompt("아래 링크를 복사해 광고주에게 보내세요", u); });
    } else { window.prompt("아래 링크를 복사해 광고주에게 보내세요", u); }
  }
  </script>

  <!-- 4탭 -->
  <div class="tabs">
    <a class="tab <?=($tab=='info'?'on':'')?>"   href="?rv_cp_id=<?=$cp?>&tab=info">캠페인 정보<small>상품·조건·가이드</small></a>
    <a class="tab <?=($tab=='select'?'on':'')?>" href="?rv_cp_id=<?=$cp?>&tab=select&st=<?=$st?>">신청자 조회·선정<small>신청·후보·선정</small></a>
    <a class="tab <?=($tab=='manage'?'on':'')?>" href="?rv_cp_id=<?=$cp?>&tab=manage">진행 관리<small>채널·콘텐츠·검수</small></a>
    <a class="tab <?=($tab=='pay'?'on':'')?>"    href="?rv_cp_id=<?=$cp?>&tab=pay">정산<small>지급·확정</small></a>
  </div>

<?php /* ============ ① 캠페인 정보 ============ */ if($tab=='info'){ ?>
  <div class="pane on">
    <!-- 퍼널 -->
    <div class="funnel">
      <?php $flow = array('1'=>'신청','8'=>'1차후보','2'=>'선정','3'=>'검수요청','4'=>'등록완료');
      foreach($flow as $k=>$lbl){ ?>
        <a class="fstep" href="?rv_cp_id=<?=$cp?>&tab=select&st=<?=$k?>">
          <div class="k"><?=$lbl?></div>
          <div class="v"><?=number_format(_bc($cnt,$k))?><small>명</small></div>
          <span class="ar">→</span>
        </a>
      <?php } ?>
    </div>
    <div style="font-size:12px;color:#8b95a1;margin:-10px 2px 16px">ℹ️ 각 단계에 <b>현재 머물러 있는</b> 인원 · 단계를 누르면 신청자 선정 탭으로 이동합니다 · 전 단계 합 = 총 신청 <?=number_format($total_apply)?>명</div>

    <div class="ic">
      <h4>📦 진행 상품 / 제공 내역</h4>
      <div class="kv">
        <div class="r"><span class="lab">캠페인명</span><span class="val"><?=$cpd[cp_subject]?></span></div>
        <div class="r"><span class="lab">유형</span><span class="val"><?=$cpd[cp_type_text]?$cpd[cp_type_text]:'-'?></span></div>
        <div class="r"><span class="lab">제공 내역</span><span class="val"><?=$cpd[cp_reward]?$cpd[cp_reward]:'-'?></span></div>
        <div class="r"><span class="lab">지급 포인트</span><span class="val"><?=$cpd[cp_point]?'+'.number_format($cpd[cp_point]).'P':'-'?></span></div>
        <?php if($cpd[cp_url]){ ?><div class="r" style="width:100%"><span class="lab">상품 URL</span><span class="val"><a href="<?=$cpd[cp_url]?>" target="_blank" style="color:#3182f6"><?=$cpd[cp_url]?> ↗</a></span></div><?php } ?>
        <?php if($cpd[cp_addr]){ ?><div class="r" style="width:100%"><span class="lab">방문 주소</span><span class="val">📍 <?=$cpd[cp_addr]?> <?=$cpd[cp_tel]?'/ ☎ '.$cpd[cp_tel]:''?></span></div><?php } ?>
      </div>
    </div>

    <div class="ic">
      <h4>📝 발행 조건 / 일정</h4>
      <div style="margin-bottom:12px" class="chanbadges">
        <?php if($cpd[cp_media_blog]){ ?><span class="blog">네이버 블로그</span><?php } ?>
        <?php if($cpd[cp_media_instagram]){ ?><span class="insta">인스타그램</span><?php } ?>
        <?php if($cpd[cp_media_youtube]){ ?><span class="you">유튜브</span><?php } ?>
        <?php if($cpd[cp_media_shop]){ ?><span class="shop">네이버 쇼핑</span><?php } ?>
        <?php if($cpd[cp_media_receipt]){ ?><span class="etc">영수증</span><?php } ?>
        <?php if($cpd[cp_media_carrot]){ ?><span class="etc">당근</span><?php } ?>
      </div>
      <div class="kv">
        <div class="r"><span class="lab">리뷰어 신청</span><span class="val"><?=_d($cpd[cp_sdatetime])?> ~ <?=_d($cpd[cp_edatetime])?></span></div>
        <div class="r"><span class="lab">선정자 발표</span><span class="val"><?=_d($cpd[cp_pick_datetime])?></span></div>
        <div class="r"><span class="lab">리뷰 등록</span><span class="val"><?=_d($cpd[cp_contents_sdatetime])?> ~ <?=_d($cpd[cp_contents_edatetime])?></span></div>
        <div class="r"><span class="lab">결과 발표</span><span class="val"><?=_d($cpd[cp_result_datetime])?></span></div>
        <?php if($cpd[cp_keyword]){ ?><div class="r" style="width:100%"><span class="lab">필수 키워드</span><span class="val"><?=$cpd[cp_keyword]?></span></div><?php } ?>
        <?php if($cpd[cp_hashtag]){ ?><div class="r" style="width:100%"><span class="lab">해시태그</span><span class="val"><?=$cpd[cp_hashtag]?></span></div><?php } ?>
      </div>
    </div>

    <?php if(trim(strip_tags($cpd[cp_guide])) || trim(strip_tags($cpd[cp_guide_add]))){ ?>
    <div class="ic">
      <h4>📋 진행 가이드</h4>
      <?php if($cpd[cp_guide_add]){ ?><div class="guidebox" style="margin-bottom:10px"><?=$cpd[cp_guide_add]?></div><?php } ?>
      <div class="guidebox"><?=$cpd[cp_guide]?></div>
    </div>
    <?php } ?>
  </div>

<?php /* ============ ② 신청자 조회·선정 ============ */ } elseif($tab=='select'){ ?>
  <div class="pane on">
    <!-- 서브탭(신청/후보/선정) -->
    <div class="subtabs">
      <a class="subtab <?=($st=='1'?'on':'')?>" href="?rv_cp_id=<?=$cp?>&tab=select&st=1">신청<span class="b"><?=_bc($cnt,'1')?></span></a>
      <a class="subtab <?=($st=='8'?'on':'')?>" href="?rv_cp_id=<?=$cp?>&tab=select&st=8">1차후보<span class="b"><?=_bc($cnt,'8')?></span></a>
      <a class="subtab <?=($st=='2'?'on':'')?>" href="?rv_cp_id=<?=$cp?>&tab=select&st=2">선정<span class="b"><?=_bc($cnt,'2')?></span> <span style="font-weight:600;opacity:.8">/ 모집 <?=number_format($cpd[cp_recruit])?></span></a>
      <a class="subtab <?=($st=='5'?'on':'')?>" href="?rv_cp_id=<?=$cp?>&tab=select&st=5" style="margin-left:auto">미선정<span class="b"><?=_bc($cnt,'5')?></span></a>
      <a class="subtab" href="creator_list.php" style="background:#f4ecff;border-color:#e4d4fb;color:#7c3aed">🧑‍🎨 전체 크리에이터 찾기</a>
    </div>

    <div class="blist-h" style="margin-bottom:10px">
      <h3><?=$admin[rv_step][$st]?> <span style="color:#8b95a1;font-weight:600;font-size:13px">· <?=number_format(_bc($cnt,$st))?>명</span></h3>
      <?php if(isset($manage[$st])){ ?>
        <a class="manage" href="<?=$manage[$st]?>?rv_cp_id=<?=$cp?>">목록에서 자세히 관리 →</a>
      <?php } ?>
    </div>

    <?php
    // 신청자 성별·연령 분포 (전체 신청 기준 · 결과보고서와 동일 집계 rv_mb_sex/rv_mb_age)
    $dsex=array('M'=>0,'F'=>0,''=>0); $dage=array(); $dtot=0;
    $dq=sql_query("select rv_mb_sex, rv_mb_age from nfor_review where rv_cp_id='".addslashes($cp)."' and rv_delete='0'");
    while($dr=sql_fetch_array($dq)){ $dtot++;
      $sx=($dr[rv_mb_sex]=='M'||$dr[rv_mb_sex]=='F')?$dr[rv_mb_sex]:''; $dsex[$sx]++;
      $ag=$dr[rv_mb_age]?$dr[rv_mb_age]:''; if(!isset($dage[$ag])) $dage[$ag]=0; $dage[$ag]++;
    }
    ksort($dage);
    $dmax=1; foreach($dage as $v){ if($v>$dmax) $dmax=$v; }
    $smax=max($dsex['M'],$dsex['F'],$dsex[''],1);
    if($dtot>0){ ?>
    <div class="ic demo-card">
      <h4>📊 신청자 성별·연령 분포 <span style="font-weight:600;color:#8b95a1;font-size:12px">· 전체 신청 <?=number_format($dtot)?>명 기준</span></h4>
      <div class="demobox">
        <div class="democol">
          <div class="demott">연령대</div>
          <?php foreach($dage as $k=>$v){ ?>
            <div class="demorow"><span class="dl"><?=$k?$k.'대':'미입력'?></span><span class="demobar"><i style="width:<?=round($v/$dmax*100)?>%"></i></span><span class="dn"><?=$v?></span></div>
          <?php } ?>
        </div>
        <div class="democol">
          <div class="demott">성별</div>
          <div class="demorow"><span class="dl">여성</span><span class="demobar"><i class="f" style="width:<?=round($dsex['F']/$smax*100)?>%"></i></span><span class="dn"><?=$dsex['F']?></span></div>
          <div class="demorow"><span class="dl">남성</span><span class="demobar"><i class="m" style="width:<?=round($dsex['M']/$smax*100)?>%"></i></span><span class="dn"><?=$dsex['M']?></span></div>
          <?php if($dsex['']){ ?><div class="demorow"><span class="dl">미입력</span><span class="demobar"><i style="width:<?=round($dsex['']/$smax*100)?>%;background:#cbd3da"></i></span><span class="dn"><?=$dsex['']?></span></div><?php } ?>
        </div>
      </div>
    </div>
    <?php } ?>

    <?php
    $rows = sql_query("select * from nfor_review where rv_cp_id='".addslashes($cp)."' and rv_step='".addslashes($st)."' and rv_delete='0' $age_sql order by rv_id desc limit 300");
    $has = false;
    if(_bc($cnt,$st)>0){ $agehref="?rv_cp_id=".$cp."&tab=select&st=".$st."&fage="; ?>
    <div class="mc-filter">
      <select id="f-sort">
        <option value="new">최신 신청순</option>
        <option value="old">오래된 신청순</option>
        <option value="visit-desc">방문수 많은순</option>
        <option value="visit-asc">방문수 적은순</option>
      </select>
      <select id="f-age" onchange="location.href='<?=$agehref?>'+this.value"><option value="">연령 전체</option><option value="20"<?=$fage=='20'?' selected':''?>>20대</option><option value="30"<?=$fage=='30'?' selected':''?>>30대</option><option value="40"<?=$fage=='40'?' selected':''?>>40대</option><option value="50p"<?=$fage=='50p'?' selected':''?>>50대 이상</option></select>
      <select id="f-sex"><option value="">성별 전체</option><option value="F">여성</option><option value="M">남성</option></select>
      <select id="f-media"><option value="">채널 전체</option><option value="blog">블로그</option><option value="instagram">인스타</option><option value="youtube">유튜브</option></select>
      <span id="f-count" class="f-count"><?=$fage?'<span style="color:#02b150">연령 '.($fage=='50p'?'50대 이상':$fage.'대').' 필터 중</span>':''?></span>
    </div>
    <?php } ?>
    <div id="mc-cards">
    <?php
    while($row = sql_fetch_array($rows)){ $has = true; $row = nfor_tag_out($row);
      $rm = sql_fetch("select mb_blog from nfor_member where mb_no='{$row[rv_mb_no]}'");
      $msg = trim($row[rv_msg]);
      $mtype = $row[rv_media] ? $row[rv_media] : (($row[rv_channel] && function_exists('media_type')) ? media_type($row[rv_channel]) : '');
    ?>
      <div class="icard" data-rvid="<?=(int)$row[rv_id]?>" data-age="<?=preg_replace('/[^0-9]/','',$row[rv_mb_age])?>" data-sex="<?=$row[rv_mb_sex]?>" data-media="<?=$mtype?>" data-blog="<?=$rm[mb_blog]?>">
        <div class="pf"><?=mb_substr($row[rv_mb_name]?$row[rv_mb_name]:$row[rv_mb_nick],0,1,'utf-8')?></div>
        <div class="main">
          <div class="nm"><?=$row[rv_mb_name]?$row[rv_mb_name]:$row[rv_mb_nick]?></div>
          <?php $dsx=($row[rv_mb_sex]=='M'?'남':($row[rv_mb_sex]=='F'?'여':'')); $dag=($row[rv_mb_age]?$row[rv_mb_age].'대':''); $dmo=$dag.($dag&&$dsx?' · ':'').$dsx; ?>
          <div class="meta"><?=$dmo?'<b style="color:#475569">'.$dmo.'</b> · ':''?><?=$row[rv_mb_hp]?$row[rv_mb_hp]:'-'?><?=$row[rv_mb_nick]&&$row[rv_mb_name]?' · '.$row[rv_mb_nick]:''?></div>
          <div class="chans">
            <?=channel_btn($row[rv_channel], $row[rv_media])?>
            <?php if($rm[mb_blog]){ ?><span class="mc-ch-meta"><span id="blog_totalcount2_<?=$rm[mb_blog]?>">브론즈</span> · <span id="blog_totalcount_<?=$rm[mb_blog]?>" data-ref="<?=$rm[mb_blog]?>">0</span> 방문</span> <span class="mc-spark" id="mc-spark_<?=$rm[mb_blog]?>"></span><?php } ?>
            <?php if($cm=$crmap[$row[rv_mb_no]]){ ?><span class="cr-badge" title="과거 협업 평가">🤝 평가 <?=$cm['cnt']?>회 · <?=$cr_glabel[$cm['lastgrade']]?><?php if($cm['lasttags']){ ?> · <?=htmlspecialchars(str_replace(',',' / ',$cm['lasttags']))?><?php } ?></span><?php } ?>
            <?php if(($nt2=$notemap[$row[rv_mb_no]]) && ($nt2['memo']||$nt2['tags'])){ ?><span class="cr-badge mc-note-badge" title="<?=htmlspecialchars($nt2['memo'])?>">📝 <?=$nt2['tags']?htmlspecialchars(str_replace(',',' · ',$nt2['tags'])):'메모'?></span><?php } ?>
          </div>
          <?php if($msg){ ?><div class="applymsg"><?=nl2br(htmlspecialchars(mb_substr($msg,0,200,'utf-8')))?><?=(mb_strlen($msg,'utf-8')>200?'…':'')?></div><?php } ?>
        </div>
        <div class="right">
          <div class="dt"><?=substr($row[rv_datetime],0,10)?> 신청</div>
          <?php if($can_act){ ?>
            <?php if($st=='1'){ ?>
              <?=admin_a("bd_pre","✓ 1차 선정","btn nfor_button mc-act mc-act-go","data-confirm=\"[{$row[rv_mb_name]}] 1차 후보로 보내시겠습니까?\n(회원 알림 없음 · 2차 확정 때 알림)\" data-data=\"mode=bd_pre&{$id}={$row[$id]}\"")?>
              <?=admin_a("bd_cancel","미선정","btn nfor_button mc-act","data-confirm=\"미선정 처리할까요?\" data-data=\"mode=bd_cancel&{$id}={$row[$id]}\"")?>
            <?php } elseif($st=='8'){ ?>
              <?=admin_a("bd_asign","★ 2차 확정","btn nfor_button mc-act mc-act-go","data-confirm=\"[{$row[rv_mb_name]}] 2차 확정하시겠습니까?\n★회원에게 선정 알림이 발송됩니다.\" data-data=\"mode=bd_asign&{$id}={$row[$id]}\"")?>
              <?=admin_a("bd_exclude","제외","btn nfor_button mc-act","data-confirm=\"제외(신청목록으로)하시겠습니까?\" data-data=\"mode=bd_exclude&{$id}={$row[$id]}\"")?>
            <?php } ?>
          <?php } ?>
        </div>
      </div>
    <?php } ?>
    </div><!-- /#mc-cards -->
    <div id="mc-noresult" class="bempty" style="display:none;margin-top:10px">조건에 맞는 신청자가 없습니다. 필터를 바꿔보세요.</div>
    <?php if(!$has){ ?><div class="bempty">이 단계에 해당하는 신청자가 없습니다.</div><?php } ?>
  </div>

<?php /* ============ ③ 진행 관리 ============ */ } elseif($tab=='manage'){ ?>
  <div class="pane on">
    <div class="blist-h" style="margin-bottom:12px">
      <h3>선정자 진행 현황 <span style="color:#8b95a1;font-weight:600;font-size:13px">· <?=number_format(_bc($cnt,'2')+_bc($cnt,'3')+_bc($cnt,'4')+_bc($cnt,'7'))?>명</span></h3>
      <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
        <?php if($can_act && _bc($cnt,'3')>0){ ?><button type="button" id="bulk-confirm" class="mc-bulk-btn">✓ 선택 일괄 검수 승인</button><?php } ?>
        <a class="manage" href="review_post_list.php?rv_cp_id=<?=$cp?>">검수 목록에서 자세히 →</a>
      </div>
    </div>
    <?php
    $rows = sql_query("select * from nfor_review where rv_cp_id='".addslashes($cp)."' and rv_step in('2','3','4','7') and rv_delete='0' order by field(rv_step,'3','7','2','4'), rv_id desc limit 500");
    $has = false;
    ?>
    <table class="mtable">
      <thead><tr>
        <?php if($can_act){ ?><th style="width:34px"><input type="checkbox" id="bulk-all" title="검수요청 전체 선택"></th><?php } ?>
        <th>진행 상태</th><th style="text-align:left">크리에이터</th><th>신청정보</th>
        <th>콘텐츠 채널</th><th>콘텐츠 현황</th><th>처리</th>
      </tr></thead>
      <tbody>
      <?php while($row = sql_fetch_array($rows)){ $has = true; $row = nfor_tag_out($row);
        $s = $row[rv_step];
        $pill = array('2'=>array('선정','ing'),'3'=>array('검수요청','wait'),'4'=>array('등록완료','done'),'7'=>array('수정요청','wait'));
        $submitted = ($s=='3'||$s=='4');   // 검수요청·등록완료 = 콘텐츠 제출됨
      ?>
        <tr>
          <?php if($can_act){ ?><td><?php if($s=='3'){ ?><input type="checkbox" class="bulk-chk" value="<?=(int)$row[rv_id]?>"><?php } ?></td><?php } ?>
          <td><span class="pill <?=$pill[$s][1]?>"><?=$pill[$s][0]?></span></td>
          <td class="who-l"><div class="nm"><?=$row[rv_mb_name]?$row[rv_mb_name]:$row[rv_mb_nick]?></div><div class="hp"><?=$row[rv_mb_hp]?></div>
            <?php if(($nt3=$notemap[$row[rv_mb_no]]) && ($nt3['memo']||$nt3['tags'])){ ?><div class="cr-badge mc-note-badge" title="<?=htmlspecialchars($nt3['memo'])?>" style="margin-top:4px;display:inline-block">📝 <?=$nt3['tags']?htmlspecialchars(str_replace(',',' · ',$nt3['tags'])):'메모'?></div><?php } ?>
          </td>
          <td><a class="tbtn review_edit" href="javascript:;" data-data="<?=$id?>=<?=$row[$id]?>">조회</a></td>
          <td>
            <?php if($row[rv_channel]){ ?><?=channel_btn($row[rv_channel], $row[rv_media])?><?php }else{ echo '<span style="color:#c0c6cd">-</span>'; } ?>
            <?php if($row[rv_url]){ ?><br><a href="<?=$row[rv_url]?>" target="_blank" class="tbtn" style="margin-top:6px">리뷰 보러가기 ↗</a><?php } ?>
          </td>
          <td>
            <?php
              $cc_img   = $row[rv_buy_img] ? ($nfor[path].'/data/review/'.$row[rv_buy_img]) : '';
              $cc_mtype = $row[rv_media] ? $row[rv_media] : (($row[rv_channel] && function_exists('media_type')) ? media_type($row[rv_channel]) : '');
            ?>
            <?php if($submitted){ ?>
              <?php if($row[rv_buy_img]){ ?><a href="<?=$nfor[path]?>/data/review/<?=$row[rv_buy_img]?>" target="_blank" class="mc-thumb"><img src="<?=thumbnail($nfor[path]."/data/review/".$row[rv_buy_img],72,72,0,1)?>" alt="제출 인증"></a><br><?php } ?>
              <div class="prog" style="margin-top:5px">1 / 1<small>제출 완료</small></div>
            <?php }else{ ?>
              <div class="prog" style="color:#b5740a">0 / 1<small>제출 대기</small></div>
            <?php } ?>
            <button type="button" class="tbtn cc-open" style="margin-top:6px"
              data-nm="<?=htmlspecialchars($row[rv_mb_name]?$row[rv_mb_name]:$row[rv_mb_nick])?>" data-sub="<?=$submitted?1:0?>"
              data-ch="<?=$cc_mtype?>" data-url="<?=htmlspecialchars($row[rv_url])?>" data-img="<?=htmlspecialchars($cc_img)?>"
              data-review="<?=htmlspecialchars($row[rv_review])?>" data-date="<?=substr($row[rv_datetime],0,10)?>">현황 보기</button>
          </td>
          <td>
            <?php if($s=='3' && $can_act){ ?>
              <?=admin_a("bd_confirm","✓ 검수 승인","btn nfor_button tbtn go","data-confirm=\"[{$row[rv_mb_name]}] 검수 승인 → 등록완료 처리할까요?\" data-data=\"mode=bd_confirm&{$id}={$row[$id]}\"")?>
            <?php } elseif($s=='4'){ ?>
              <span class="pill done">완료</span>
            <?php } elseif($s=='7'){ ?>
              <a class="tbtn" href="review_edit_list.php?rv_cp_id=<?=$cp?>">수정요청 확인</a>
            <?php } else { ?>
              <span class="pill gray">검수 대기</span>
            <?php } ?>
            <div style="margin-top:7px">
              <?php $crd=$crmap[$row[rv_mb_no]]; ?>
              <button type="button" class="tbtn cr-open" data-mb="<?=$row[rv_mb_no]?>" data-rv="<?=$row[rv_id]?>" data-nm="<?=htmlspecialchars($row[rv_mb_name]?$row[rv_mb_name]:$row[rv_mb_nick])?>"><?=$crd?'평가 ✓'.($crd['cnt']>1?' ('.$crd['cnt'].')':''):'⭐ 평가'?></button>
            </div>
          </td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
    <?php if(!$has){ ?><div class="bempty" style="margin-top:10px">아직 선정된 크리에이터가 없습니다. 신청자 선정 탭에서 먼저 선정해 주세요.</div><?php } ?>
    <script>window.MC_CC={ period:"<?=_d($cpd[cp_contents_sdatetime])?> ~ <?=_d($cpd[cp_contents_edatetime])?>", point:"<?=$cpd[cp_point]?'+'.number_format($cpd[cp_point]).'P':'-'?>" };</script>
  </div>

<?php /* ============ ④ 정산 ============ */ } elseif($tab=='pay'){
    $done = _bc($cnt,'4'); $sel = _bc($cnt,'2')+_bc($cnt,'3')+_bc($cnt,'4')+_bc($cnt,'7');
    $pt = (int)$cpd[cp_point];
    $is_point = (($cpd[cp_reward_type]=='2' || $cpd[cp_reward_type]=='3') && $pt>0);  // 포인트 지급형(검수승인 시 자동 적립)
?>
  <div class="pane on">
    <div class="scards">
      <div class="scard"><div class="sk">선정 인원</div><div class="sv"><?=number_format($sel)?><small>명</small></div></div>
      <div class="scard g"><div class="sk">등록 완료</div><div class="sv"><?=number_format($done)?><small>명</small></div></div>
      <div class="scard"><div class="sk">1인 지급 포인트</div><div class="sv"><?=$is_point?'+'.number_format($pt).'<small>P</small>':'<small style="font-size:15px">상품제공</small>'?></div></div>
      <div class="scard g"><div class="sk"><?=$is_point?'지급 완료 합계':'포인트 지급'?></div><div class="sv"><?=$is_point?number_format($pt*$done).'<small>P</small>':'<small style="font-size:15px">없음</small>'?></div></div>
    </div>
    <div class="erp-link">
      <div style="font-size:26px">🏦</div>
      <div class="txt">
        <b>포인트는 「진행 관리」에서 검수 승인하는 즉시 자동 적립(정산)됩니다.</b><br>
        <?php if($is_point){ ?>이 캠페인은 1인 <b><?=number_format($pt)?>P</b> 지급형 — 등록완료 <b><?=$done?>명</b>에게 총 <b><?=number_format($pt*$done)?>P</b> 적립 완료.<?php }else{ ?>이 캠페인은 <b>상품 제공형</b>(포인트 지급 없음)입니다.<?php } ?><br>
        회원의 <b>포인트 출금</b> 신청·확정·이체는 회사 전산(ERP) 「정산관리」에서 처리합니다.
      </div>
      <a href="http://metainc01.cafe24.com" target="_blank" class="mc-bulk-btn" style="white-space:nowrap;text-decoration:none">전산 정산관리 ↗</a>
    </div>

    <!-- 등록완료자 지급 내역 -->
    <div class="blist-h" style="margin:20px 2px 12px">
      <h3>등록완료자 <?=$is_point?'포인트 지급 내역':'목록'?> <span style="color:#8b95a1;font-weight:600;font-size:13px">· <?=number_format($done)?>명<?=$is_point?' · 1인 +'.number_format($pt).'P':''?></span></h3>
      <a class="manage" href="?rv_cp_id=<?=$cp?>&tab=manage">진행 관리에서 일괄 검수 승인 →</a>
    </div>
    <?php
    $payq = sql_query("select * from nfor_review where rv_cp_id='".addslashes($cp)."' and rv_step='4' and rv_delete='0' order by rv_id desc limit 500");
    $payhas = false;
    ?>
    <table class="mtable">
      <thead><tr><th style="text-align:left">크리에이터</th><th>콘텐츠 채널</th><th><?=$is_point?'지급 포인트':'리워드'?></th><th>상태</th></tr></thead>
      <tbody>
      <?php while($prow = sql_fetch_array($payq)){ $payhas = true; $prow = nfor_tag_out($prow); ?>
        <tr>
          <td class="who-l"><div class="nm"><?=$prow[rv_mb_name]?$prow[rv_mb_name]:$prow[rv_mb_nick]?></div><div class="hp"><?=$prow[rv_mb_hp]?></div></td>
          <td><?php if($prow[rv_channel]){ ?><?=channel_btn($prow[rv_channel], $prow[rv_media])?><?php }else{ echo '-'; } ?></td>
          <td style="font-weight:800;color:#7c3aed"><?=$is_point?'+'.number_format($pt).'P':'상품'?></td>
          <td><?=$is_point?'<span class="pill done">✓ 지급 완료</span>':'<span class="pill gray">상품 제공</span>'?></td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
    <?php if(!$payhas){ ?><div class="bempty" style="margin-top:10px">아직 등록완료 크리에이터가 없습니다. 검수 승인 시 표시되며, 포인트 지급형이면 그때 자동 적립됩니다.</div><?php } ?>
    <div style="font-size:12px;color:#8b95a1;margin:12px 2px 0">ℹ️ <b>포인트 적립(정산)</b>=검수 승인 시 자동 완료 · <b>출금 확정·이체</b>=전산 정산관리에서 처리.</div>
  </div>
<?php } ?>

<?php } /* end if($cp) */ ?>
</div>

<!-- 콘텐츠 제출 현황 모달 (브랜드커넥트풍) -->
<div id="cc-modal" class="cr-modal">
  <div class="cr-dim"></div>
  <div class="cr-box">
    <div class="cr-head">📤 콘텐츠 제출 현황 <button type="button" class="cc-x">×</button></div>
    <div class="cr-sub">크리에이터 : <b id="cc-name"></b></div>
    <div class="cc-summary">
      <div class="cc-s"><div class="k">목표</div><div class="v">1건</div></div>
      <div class="cc-s"><div class="k">제출</div><div class="v" id="cc-done">0건</div></div>
      <div class="cc-s"><div class="k">미제출</div><div class="v" id="cc-undone">1건</div></div>
      <div class="cc-s"><div class="k">콘텐츠 리워드</div><div class="v" id="cc-point">-</div></div>
    </div>
    <div id="cc-body"></div>
  </div>
</div>

<!-- 크리에이터 평가 모달 (브랜드커넥트풍) -->
<div id="cr-modal" class="cr-modal">
  <div class="cr-dim"></div>
  <div class="cr-box">
    <div class="cr-head">크리에이터 평가 <button type="button" class="cr-x">×</button></div>
    <div class="cr-sub">크리에이터 : <b id="cr-name"></b> <span style="color:#8b95a1;font-weight:600">· 평가는 크리에이터에게 노출되지 않습니다</span></div>
    <div class="cr-q">협업이 어떠셨나요?</div>
    <div class="cr-grades">
      <button type="button" class="cr-grade" data-g="best">⭐ 최고예요</button>
      <button type="button" class="cr-grade" data-g="good">👍 좋았어요</button>
      <button type="button" class="cr-grade" data-g="soso">😐 아쉬웠어요</button>
    </div>
    <div class="cr-q">어떤 점이 좋았나요? <span style="color:#8b95a1;font-weight:600;font-size:12px">(복수 선택)</span></div>
    <div class="cr-tags">
      <button type="button" class="cr-tag">진행 가이드 준수</button>
      <button type="button" class="cr-tag">일정 준수</button>
      <button type="button" class="cr-tag">콘텐츠 퀄리티</button>
      <button type="button" class="cr-tag">연락 원활</button>
      <button type="button" class="cr-tag">노출 잘 됨</button>
      <button type="button" class="cr-tag">또 협업하고 싶음</button>
      <button type="button" class="cr-tag">열정적</button>
    </div>
    <textarea id="cr-memo" class="cr-memo" placeholder="메모 (선택) — 다음 선정 때 참고할 내용"></textarea>
    <button type="button" id="cr-save-btn" class="cr-save-btn">저장하기</button>
  </div>
</div>

<script>
function mcEsc(s){ return (s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

// 진행관리 '신청정보 조회' → 기존 신청서 편집 팝업 재사용
$(document).on("click", "#mc-board .review_edit", function(){
  var d = $(this).data("data");
  window.open("review_edit.php?"+d, "review_edit", "width=630,height=550,scrollbars=yes");
});

// 콘텐츠 제출 현황 모달
$(document).on('click','#mc-board .cc-open',function(){
  var d=$(this), sub=(d.attr('data-sub')=='1');
  $('#cc-name').text(d.attr('data-nm')||'');
  $('#cc-done').text(sub?'1건':'0건');
  $('#cc-undone').text(sub?'0건':'1건');
  $('#cc-point').text((window.MC_CC&&MC_CC.point)||'-');
  var chMap={blog:'블로그',youtube:'유튜브',instagram:'인스타그램'};
  var ch=chMap[d.attr('data-ch')]||(d.attr('data-ch')||'-');
  var url=d.attr('data-url')||'', img=d.attr('data-img')||'', rev=d.attr('data-review')||'', dt=d.attr('data-date')||'';
  var period=(window.MC_CC&&MC_CC.period)||'-';
  var html='';
  if(sub){
    html+='<table class="cc-tbl">';
    html+='<tr><th>발행 채널</th><td>'+mcEsc(ch)+'</td></tr>';
    html+='<tr><th>제출일</th><td>'+mcEsc(dt)+'</td></tr>';
    html+='<tr><th>리뷰 등록기간</th><td>'+mcEsc(period)+'</td></tr>';
    html+='<tr><th>게시글</th><td>'+(url?'<a href="'+encodeURI(url)+'" target="_blank" style="color:#3182f6;font-weight:700">리뷰 보러가기 ↗</a>':'-')+'</td></tr>';
    html+='<tr><th>구매 인증</th><td>'+(img?'<a href="'+encodeURI(img)+'" target="_blank"><img src="'+encodeURI(img)+'" style="max-width:170px;border:1px solid #e5e8eb;border-radius:8px"></a>':'-')+'</td></tr>';
    html+='<tr><th>리뷰 내용</th><td style="white-space:pre-line;max-height:170px;overflow:auto">'+(rev?mcEsc(rev):'-')+'</td></tr>';
    html+='</table>';
  } else {
    html='<div class="bempty" style="margin-top:14px">아직 콘텐츠가 제출되지 않았습니다 (제출 대기).</div>';
  }
  $('#cc-body').html(html);
  $('#cc-modal').css('display','flex');
});
$(document).on('click','#cc-modal .cc-x, #cc-modal .cr-dim',function(){ $('#cc-modal').hide(); });

// 크리에이터 평가 모달 (별점·태그 → nfor_creator_review 저장)
var crMB=0, crRV=0, crGrade='';
$(document).on('click', '#mc-board .cr-open', function(){
  crMB=$(this).data('mb'); crRV=$(this).data('rv'); crGrade='';
  $('#cr-name').text($(this).data('nm'));
  $('.cr-grade,.cr-tag').removeClass('on'); $('#cr-memo').val('');
  $('#cr-modal').css('display','flex');
});
$(document).on('click', '#cr-modal .cr-x, #cr-modal .cr-dim', function(){ $('#cr-modal').hide(); });
$(document).on('click', '#cr-modal .cr-grade', function(){ $('#cr-modal .cr-grade').removeClass('on'); $(this).addClass('on'); crGrade=$(this).data('g'); });
$(document).on('click', '#cr-modal .cr-tag', function(){ $(this).toggleClass('on'); });
$(document).on('click', '#cr-save-btn', function(){
  if(!crGrade){ alert('최고/좋음/아쉬움 중 하나를 선택해주세요.'); return; }
  var tags=[]; $('#cr-modal .cr-tag.on').each(function(){ tags.push($(this).text()); });
  var $b=$(this); $b.prop('disabled',true).text('저장 중…');
  $.ajax({ type:'post', url:'review_board.php', dataType:'json',
    data:{ mode:'cr_save', cr_mb_no:crMB, cr_rv_id:crRV, cr_cp_id:'<?=$cp?>', cr_grade:crGrade, cr_tags:tags.join(','), cr_memo:$('#cr-memo').val() },
    success:function(r){ alert((r&&r.msg)?r.msg:'저장되었습니다.'); if(r&&r.result=='ok'){ $('#cr-modal').hide(); location.reload(); } else { $b.prop('disabled',false).text('저장하기'); } },
    error:function(){ alert('저장 중 오류가 발생했습니다.'); $b.prop('disabled',false).text('저장하기'); }
  });
});

// ②선정 신청자 카드 정렬·필터 (클라이언트 · 방문수는 비동기 로드값을 읽어 정렬)
function crVisit(el){ var b=el.getAttribute('data-blog'); if(!b) return 0; var s=document.getElementById('blog_totalcount_'+b); var t=s?s.textContent:'0'; return parseInt((t||'0').replace(/[^0-9]/g,''),10)||0; }
function applyCardFilter(){
  var $box=$('#mc-cards'); if(!$box.length) return;
  var age=$('#f-age').val(), sex=$('#f-sex').val(), media=$('#f-media').val(), sort=$('#f-sort').val();
  var shown=0;
  $box.children('.icard').each(function(){
    var c=$(this), ok=true, a=parseInt(c.attr('data-age'),10)||0;
    if(age){ ok = (age=='50p') ? (a>=50) : (''+a===age); }
    if(ok && sex){ ok = (c.attr('data-sex')===sex); }
    if(ok && media){ ok = (c.attr('data-media')===media); }
    c.toggle(ok); if(ok) shown++;
  });
  var arr=$box.children('.icard:visible').get();
  arr.sort(function(a,b){
    if(sort=='old') return (+$(a).attr('data-rvid'))-(+$(b).attr('data-rvid'));
    if(sort=='visit-desc') return crVisit(b)-crVisit(a);
    if(sort=='visit-asc') return crVisit(a)-crVisit(b);
    return (+$(b).attr('data-rvid'))-(+$(a).attr('data-rvid')); // new(기본)
  });
  arr.forEach(function(el){ $box.append(el); });
  $('#f-count').text(shown+'명 표시');
  $('#mc-noresult').toggle(shown===0 && $box.children('.icard').length>0);
}
$(document).on('change','#f-sort,#f-sex,#f-media',applyCardFilter);  // f-age는 서버 리로드(onchange inline)
$(function(){ if($('#mc-cards').length){ $('#f-count').text($('#mc-cards .icard').length+'명 표시'); } });

// ③진행 관리 일괄 검수 승인
$(document).on('change','#bulk-all',function(){ $('#mc-board .bulk-chk').prop('checked', this.checked); });
$(document).on('click','#bulk-confirm',function(){
  var ids=$('#mc-board .bulk-chk:checked').map(function(){ return this.value; }).get();
  if(!ids.length){ alert('검수 승인할 검수요청 건을 체크하세요.'); return; }
  if(!confirm(ids.length+'명을 일괄 검수 승인할까요?\n검수요청 → 등록완료로 처리됩니다.')) return;
  var $b=$(this); $b.prop('disabled',true).text('처리 중…');
  $.ajax({ type:'post', url:'review_board.php', dataType:'json', data:{ mode:'bd_confirm_bulk', rv_ids:ids.join(',') },
    success:function(r){ alert((r&&r.msg)?r.msg:'완료'); if(r&&r.result=='ok'){ location.reload(); } else { $b.prop('disabled',false).text('✓ 선택 일괄 검수 승인'); } },
    error:function(){ alert('처리 중 오류가 발생했습니다.'); $b.prop('disabled',false).text('✓ 선택 일괄 검수 승인'); }
  });
});
</script>

<?php include_once "tail.php"; ?>
