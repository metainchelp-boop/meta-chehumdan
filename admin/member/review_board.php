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

include_once "head.php";              // 관리자 공통 레이아웃

// ============================================================================
//  캠페인 진행 보드 — 브랜드커넥트풍 4탭 허브 (2026-06-13 확장)
//   캠페인 1개 선택 → ①캠페인정보 ②신청자조회·선정 ③진행관리 ④정산  한 화면에서.
//   기존 목록 페이지는 무수정. 실제 선정/검수 처리는 위 검증된 함수 재사용(또는 기존 목록 연결).
// ============================================================================

$cp  = preg_replace('/[^0-9]/', '', $rv_cp_id);                 // 선택 캠페인 (검색폼과 동일 파라미터)
$tab = preg_replace('/[^a-z]/', '', $_GET['tab']); if(!$tab) $tab = 'info';
$st  = preg_replace('/[^0-9]/', '', $_GET['st']);  if($st==='') $st = '1';
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
    <a class="btn btn-white btn-sm gobtn" href="<?=$nfor[path]?>/campaign.php?cp_id=<?=$cp?>" target="_blank">캠페인 보기 ↗</a>
  </div>

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
    </div>

    <div class="blist-h" style="margin-bottom:10px">
      <h3><?=$admin[rv_step][$st]?> <span style="color:#8b95a1;font-weight:600;font-size:13px">· <?=number_format(_bc($cnt,$st))?>명</span></h3>
      <?php if(isset($manage[$st])){ ?>
        <a class="manage" href="<?=$manage[$st]?>?rv_cp_id=<?=$cp?>">목록에서 자세히 관리 →</a>
      <?php } ?>
    </div>

    <?php
    $rows = sql_query("select * from nfor_review where rv_cp_id='".addslashes($cp)."' and rv_step='".addslashes($st)."' and rv_delete='0' order by rv_id desc limit 300");
    $has = false;
    while($row = sql_fetch_array($rows)){ $has = true; $row = nfor_tag_out($row);
      $rm = sql_fetch("select mb_blog from nfor_member where mb_no='{$row[rv_mb_no]}'");
      $msg = trim($row[rv_msg]);
    ?>
      <div class="icard">
        <div class="pf"><?=mb_substr($row[rv_mb_name]?$row[rv_mb_name]:$row[rv_mb_nick],0,1,'utf-8')?></div>
        <div class="main">
          <div class="nm"><?=$row[rv_mb_name]?$row[rv_mb_name]:$row[rv_mb_nick]?></div>
          <div class="meta"><?=$row[rv_mb_hp]?$row[rv_mb_hp]:'-'?><?=$row[rv_mb_nick]&&$row[rv_mb_name]?' · '.$row[rv_mb_nick]:''?></div>
          <div class="chans">
            <?=channel_btn($row[rv_channel], $row[rv_media])?>
            <?php if($rm[mb_blog]){ ?><span class="mc-ch-meta"><span id="blog_totalcount2_<?=$rm[mb_blog]?>">브론즈</span> · <span id="blog_totalcount_<?=$rm[mb_blog]?>" data-ref="<?=$rm[mb_blog]?>">0</span> 방문</span><?php } ?>
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
    <?php if(!$has){ ?><div class="bempty">이 단계에 해당하는 신청자가 없습니다.</div><?php } ?>
  </div>

<?php /* ============ ③ 진행 관리 ============ */ } elseif($tab=='manage'){ ?>
  <div class="pane on">
    <div class="blist-h" style="margin-bottom:12px">
      <h3>선정자 진행 현황 <span style="color:#8b95a1;font-weight:600;font-size:13px">· <?=number_format(_bc($cnt,'2')+_bc($cnt,'3')+_bc($cnt,'4')+_bc($cnt,'7'))?>명</span></h3>
      <a class="manage" href="review_post_list.php?rv_cp_id=<?=$cp?>">검수 목록에서 자세히 →</a>
    </div>
    <?php
    $rows = sql_query("select * from nfor_review where rv_cp_id='".addslashes($cp)."' and rv_step in('2','3','4','7') and rv_delete='0' order by field(rv_step,'3','7','2','4'), rv_id desc limit 500");
    $has = false;
    ?>
    <table class="mtable">
      <thead><tr>
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
          <td><span class="pill <?=$pill[$s][1]?>"><?=$pill[$s][0]?></span></td>
          <td class="who-l"><div class="nm"><?=$row[rv_mb_name]?$row[rv_mb_name]:$row[rv_mb_nick]?></div><div class="hp"><?=$row[rv_mb_hp]?></div></td>
          <td><a class="tbtn review_edit" href="javascript:;" data-data="<?=$id?>=<?=$row[$id]?>">조회</a></td>
          <td><?php if($row[rv_channel]){ ?><?=channel_btn($row[rv_channel], $row[rv_media])?><?php }else{ echo '<span style="color:#c0c6cd">-</span>'; } ?></td>
          <td><?php if($submitted){ ?><div class="prog">1 / 1<small>제출 완료</small></div><?php }else{ ?><div class="prog" style="color:#b5740a">0 / 1<small>제출 대기</small></div><?php } ?></td>
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
          </td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
    <?php if(!$has){ ?><div class="bempty" style="margin-top:10px">아직 선정된 크리에이터가 없습니다. 신청자 선정 탭에서 먼저 선정해 주세요.</div><?php } ?>
  </div>

<?php /* ============ ④ 정산 ============ */ } elseif($tab=='pay'){
    $done = _bc($cnt,'4'); $sel = _bc($cnt,'2')+_bc($cnt,'3')+_bc($cnt,'4')+_bc($cnt,'7');
    $pt = (int)$cpd[cp_point];
?>
  <div class="pane on">
    <div class="scards">
      <div class="scard"><div class="sk">선정 인원</div><div class="sv"><?=number_format($sel)?><small>명</small></div></div>
      <div class="scard g"><div class="sk">등록 완료(지급 대상)</div><div class="sv"><?=number_format($done)?><small>명</small></div></div>
      <div class="scard"><div class="sk">1인 지급 포인트</div><div class="sv"><?=$pt?'+'.number_format($pt):'0'?><small>P</small></div></div>
      <div class="scard g"><div class="sk">등록완료 지급 합계</div><div class="sv"><?=number_format($pt*$done)?><small>P</small></div></div>
    </div>
    <div class="erp-link">
      <div style="font-size:26px">🏦</div>
      <div class="txt">
        <b>포인트 출금·정산은 회사 전산(ERP) 「정산관리」로 통합되었습니다.</b><br>
        회원이 신청한 포인트 출금 요청은 전산 정산관리 화면에서 취합·확정·이체합니다. 이 탭은 캠페인별 지급 현황 요약입니다.
        <?php if($pt && $done){ ?><br>이 캠페인 등록완료자 <b><?=$done?>명</b>에게 1인 <b><?=number_format($pt)?>P</b>씩, 총 <b><?=number_format($pt*$done)?>P</b> 지급 예정입니다.<?php } ?>
      </div>
    </div>
  </div>
<?php } ?>

<?php } /* end if($cp) */ ?>
</div>

<script>
// 진행관리 '신청정보 조회' → 기존 신청서 편집 팝업 재사용
$(document).on("click", "#mc-board .review_edit", function(){
  var d = $(this).data("data");
  window.open("review_edit.php?"+d, "review_edit", "width=630,height=550,scrollbars=yes");
});
</script>

<?php include_once "tail.php"; ?>
