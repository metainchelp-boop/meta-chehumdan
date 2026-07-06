<link rel="stylesheet" href="mc_review.css?v=<?=@filemtime(dirname(__FILE__)."/mc_review.css")?>"><!-- 리뷰목록 공통 UI 현대화 2026-06-11 -->
<script src="mc_blog_count.js?v=<?=@filemtime(dirname(__FILE__)."/mc_blog_count.js")?>"></script><!-- 블로그 등급/방문수 공통 2026-06-12 -->
<script src="mc_select_search.js?v=<?=@filemtime(dirname(__FILE__)."/mc_select_search.js")?>"></script><!-- 입점업체/캠페인 검색형 드롭다운 2026-06-12 -->
<script src="mc_msg_modal.js?v=<?=@filemtime(dirname(__FILE__)."/mc_msg_modal.js")?>"></script><!-- 신청자 한마디 모달 팝업 2026-06-12 -->
<!-- 캠페인 진행 보드 진입 (2026-06-13) -->
<div style="margin-bottom:12px"><a href="review_board.php<?php if($rv_cp_id){ echo '?rv_cp_id='.preg_replace('/[^0-9]/','',$rv_cp_id); } ?>" style="display:inline-flex;align-items:center;gap:8px;background:#eafaf0;border:1px solid #c3eed5;color:#02b150;font-weight:800;font-size:13.5px;padding:10px 16px;border-radius:11px;text-decoration:none">📋 캠페인 진행 보드 <span style="font-weight:600;color:#6b7684;font-size:11.5px">· 신청→선정→검수→완료 한 화면에서</span></a></div>
<form name="fsearch" id="fsearch" method="get">

<table class="table cols_tbl">
<?php if($is_admin){ ?>
<tr>
	<th>입점업체</th> 
	<td><?=admin_select($_GET,"rv_supply_no","width-150p","","0")?></td>
	<th>캠페인 관리자</th> 
	<td><?=admin_select($_GET,"rv_md_no","width-150p","","0")?></td>
</tr>
<?php } ?>
<tr>
	<th>캠페인</th> 
	<td><?=admin_select($_GET,"rv_cp_id","width-150p","","0")?></td>
	<th>접속기기</th> 
	<td><?=admin_radio($_GET,"rv_is_mobile","","","0")?></td>
</tr>
<tr>
	<th>검색어</th>
	<td colspan="3">
		<div class="form-inline">
		<?=admin_select($_GET,"keyword_type","","","0")?>
		<?=admin_text($_GET,"keyword","","maxlength=\"30\"")?>
		</div>
	</td>
</tr>
<tr>
	<th>기간검색</th>
	<td colspan="3">
	<div class="form-inline">
		<?=admin_select($_GET,"period_type","","","0")?>
		<?=admin_text($_GET,"period_sdate","width-100p datepicker-here","data-language=\"ko\"")?> ~ <?=admin_text($_GET,"period_edate","width-100p datepicker-here","data-language=\"ko\"")?>
		<?=period_day_echo($_GET[period_day])?>
	</div>
	</td>
</tr>
</table>

<div class="table_btn"><div class="form-inline"><?=admin_submit("fsubmit", "검색하기", "btn-lg btn-black btn")?> <?=admin_button("exceldown", "엑셀다운", "btn-lg btn exceldown")?></div></div>

<?php
/* ── 단계 빠른이동 탭 — 캠페인 작업 중 신청→1차후보→선정→검수→완료를 메뉴 재탐색 없이 전환 (요청:양근형 2026-06-23) ── */
$mc_cur = basename($PHP_SELF);
$mc_cp  = preg_replace('/[^0-9]/','',(string)$rv_cp_id);
$mc_q = '';
if($mc_cp!=='') $mc_q .= '&rv_cp_id='.$mc_cp;
$mc_sn = preg_replace('/[^0-9]/','',(string)$rv_supply_no); if($mc_sn!=='') $mc_q .= '&rv_supply_no='.$mc_sn;
$mc_mn = preg_replace('/[^0-9]/','',(string)$rv_md_no);     if($mc_mn!=='') $mc_q .= '&rv_md_no='.$mc_mn;
$mc_q = $mc_q!=='' ? '?'.ltrim($mc_q,'&') : '';
$mc_stages = array(
  array('review_wait_list.php',       '신청자',   "'1','6'"),
  array('review_pre_list.php',        '1차후보',  "'8'"),
  array('review_asign_list.php',      '최종선정', "'2'"),
  array('review_post_list.php',       '검수요청', "'3'"),
  array('review_post_asign_list.php', '등록완료', "'4'"),
);
?>
<style>
.mc-stage-nav{display:flex;flex-wrap:wrap;align-items:center;gap:7px;margin:14px 0 6px;padding:11px 13px;background:#f7f9fb;border:1px solid #e9edf2;border-radius:12px}
.mc-stage-nav .lab{font-size:12.5px;font-weight:800;color:#6b7684;margin-right:2px}
.mc-stage-nav .lab b{color:#02b150}
.mc-stage-nav a.mc-stage{display:inline-flex;align-items:center;gap:6px;text-decoration:none;font-size:13px;font-weight:700;color:#4e5968;background:#fff;border:1px solid #dbe1e8;border-radius:9px;padding:7px 13px;transition:.12s}
.mc-stage-nav a.mc-stage:hover{border-color:#03c75a;color:#02b150}
.mc-stage-nav a.mc-stage.on{background:#03c75a;border-color:#03c75a;color:#fff}
.mc-stage-nav a.mc-stage em{font-style:normal;font-weight:800;font-size:12px;background:rgba(0,0,0,.06);color:inherit;padding:1px 7px;border-radius:999px}
.mc-stage-nav a.mc-stage.on em{background:rgba(255,255,255,.25)}
.mc-stage-nav .arrow{color:#c5ccd4;font-size:12px}
</style>
<div class="mc-stage-nav">
  <span class="lab">단계 이동<?php if($mc_cp!==''){ ?> <b>· 이 캠페인</b><?php } ?> :</span>
  <?php foreach($mc_stages as $idx=>$s){
    if($idx>0) echo '<span class="arrow">&rsaquo;</span>';
    $on = ($mc_cur === $s[0]) ? ' on' : '';
    $badge = '';
    if($mc_cp!==''){ $cc = sql_fetch("select count(*) as c from nfor_review where rv_cp_id='".$mc_cp."' and rv_step in (".$s[2].") and rv_delete='0'"); $badge = '<em>'.number_format($cc['c']).'</em>'; }
  ?><a class="mc-stage<?=$on?>" href="<?=$s[0].$mc_q?>"><?=$s[1]?><?=$badge?></a><?php } ?>
</div>

<div class="ofw martop20">
	<div class="flol">
	전체 <span class="txt_red"> <?=number_format($total_count)?></span>건 / 검색 <span class="txt_red"><?=number_format($search_count)?></span>건
	</div>
	<div class="flor">
	<?=admin_sort($admin[period_type],$sort)?>
	<?=admin_page_row($page_row)?>
	</div>
</div>
</form>


<script>
$(document).on("change", "#rv_supply_no", function(){
	var cp_supply_no = $(this).val();

	$.ajax({
		type     : "get",
		url      : nfor_path + "/json.php",
		data     : "mode=campaign_list&cp_supply_no="+cp_supply_no,
		dataType : 'json',
		cache: false,
		success  : function(data) {
			var output = '<option value="">선택</option>';
			if(data.cnt>0){
				for(var i=0; i<data.data.length; i++) {
					output += '<option value="' + data.data[i].cp_id + '">' + data.data[i].cp_subject + '</option>';
				}
			}
			$('#rv_cp_id').empty().append(output);
		},
		error: function(){
			console.log("Ajax failed");
		}
	});

});


$(document).on("click", ".review_edit", function(){
	var data_str = $(this).data("data");
	window.open("review_edit.php?"+data_str, "review_edit", "width=630,height=550,scrollbar=yes");
});

$(document).on("click", "#traffic", function(){
	var data = $(this).data("data");
    window.open("traffic_detail.php?"+data, "traffic_detail", "left=50,top=50,width=900,height=800,scrollbars=1");
});

$(document).on("click", "#review_singo", function(){
	var data = $(this).data("data");
    window.open("review_singo.php?"+data, "review_singo", "left=50,top=50,width=630,height=550,scrollbars=1");
});

</script>