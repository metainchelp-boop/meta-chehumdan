<?php
include_once "path.php";
include_once $nfor[path]."/lib/z_function.lib.php";   // 메타테크 연동(함수·$nfor[meta_op_b]) 2026-06-16


$admin[cp_buyer] = array("전체","회원전체","특정회원등급");


$admin[cp_use] = array("전체","노출","미노출");
$admin[cp_map_use] = array("전체","노출","미노출");
$admin[cp_media] = $nfor[cp_media];
$admin[cp_type] = $nfor[cp_type];
$admin[cp_reward_type] = array("전체","제품/서비스","포인트","포인트 + 제품/서비스");



$form = $_SERVER[PHP_SELF];
$list = str_replace("form","list",$form);
$table = "nfor_campaign";
$id = "cp_id";

$id_value = $$id;

if($id_value){
	$write = sql_fetch("select * from $table where $id='{$id_value}'");
}

// ▼▼ 다음 회차 복사 등록: 원본 캠페인을 불러와 '신규'로 (일정만 비움) ▼▼
if(!$id_value && $_GET['copy']){
	$src = sql_fetch("select * from $table where $id='".addslashes($_GET['copy'])."'");
	if($src[$id]){
		$write = $src;
		$write['cp_id']     = "";                                                 // 비우면 신규(insert) + line148에서 새 코드 자동생성
		$write['cp_id_gp']  = $src['cp_id_gp'] ? $src['cp_id_gp'] : $src['cp_id']; // 회차 그룹 유지
		$write['cp_sdatetime']          = "";  // 모집 시작
		$write['cp_edatetime']          = "";  // 모집 종료
		$write['cp_pick_datetime']      = "";  // 선정 발표
		$write['cp_contents_sdatetime'] = "";  // 리뷰 시작
		$write['cp_contents_edatetime'] = "";  // 리뷰 종료
		$write['cp_result_datetime']    = "";  // 결과 발표
		$mc_copy_mode = true;
	}
}
// ▲▲ 다음 회차 복사 등록 ▲▲

if($mode=="insert" or $mode=="update"){
	demo_check();

	// ▼ 캠페인 담당자 이름 필수 (2026-06-16) — 비우면 등록/수정 차단(서버 방어)
	if(trim($cp_md_name)===''){ alert("캠페인 담당자 이름을 입력해주세요. (전산 직원명과 동일하게 입력)"); }

	// ▼ 다음회차 복사 등록: 원본 cp_id (POST hidden 우선, GET 폴백 — fsubmit가 action에서 ?copy= 제거함)
	$__copy_src = $_POST['mc_copy_src'] ? $_POST['mc_copy_src'] : $_GET['copy'];
	// 안전장치: 대상 cp_id가 아직 없으면 신규 INSERT 강제
	if($__copy_src && $mode=="update"){
		$__exist = sql_fetch("select cp_id from $table where cp_id='{$id_value}'");
		if(!$__exist['cp_id']) $mode = "insert";
	}
	// ▲

	$add_sql = "";
	$where_sql = "";

	if($cp_reward_type=="1"){
		$cp_point = "0";
	}


	$cp_category = "";
	for($i=0; $i<count($cp_category_val); $i++){
		if($i) $cp_category .= "||";
		$cp_category .= $cp_category_val[$i];
	}
	for($i=0; $i<10; $i++){
		$k = $i+1;
		$add_sql .= ", cp_category_id{$k} = '{$cp_category_val[$i]}'";
	}



	$cp_buyer_level = "";
	for($i=0; $i<count($cp_buyer_level_val); $i++){
		if($i) $cp_buyer_level .= "||";
		$cp_buyer_level .= $cp_buyer_level_val[$i];
	}



	$add_sql .= admin_upload($write,"cp_img","campaign","$table"," where $id='{$id_value}'");

	// ▼ 다음회차 복사 등록: 새 이미지를 안 올렸으면 원본 대표이미지를 복제해 승계
	if($__copy_src && $mode=="insert" && strpos($add_sql,"cp_img=")===false && !$_POST['cp_img_del']){
		$__srcrow = sql_fetch("select cp_img from $table where cp_id='".addslashes($__copy_src)."'");
		$__img = $__srcrow['cp_img'];
		if($__img){
			$__srcfile = $nfor[path]."/data/campaign/".$__img;
			if(is_file($__srcfile)){
				$__ext = strrchr($__img, ".");
				$__newname = $cp_id."_copy_".substr(md5(uniqid("",true)),0,8).$__ext;
				if(@copy($__srcfile, $nfor[path]."/data/campaign/".$__newname)) $__img = $__newname; // 물리 복제 성공 → 새 파일 참조
			}
			$add_sql .= " , cp_img='".addslashes($__img)."' ";
		}
	}
	// ▲

	if($mode=="insert"){
		$msg = "정상적으로 등록 되었습니다";
		$move = "$list";
		$add_sql .= ", cp_insert_id='$member[mb_no]', cp_insert_datetime=NOW(), cp_update_datetime=NOW()";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$move = "$form?{$qstr}&{$id}={$id_value}";
		$where_sql = "  where $id='{$id_value}'";
		$add_sql .= ", cp_update_id='$member[mb_no]', cp_update_datetime=NOW()";
	} else{

	}
	$common_sql = " $table set cp_check1='$cp_check1', cp_check2='$cp_check2', cp_ohouse='$cp_ohouse', cp_coupang='$cp_coupang', cp_guide_add='$cp_guide_add', cp_hashtag='$cp_hashtag', cp_id_gp='$cp_id_gp', cp_buyer_level='$cp_buyer_level', cp_buyer='$cp_buyer', cp_map_use='$cp_map_use', cp_lat='$cp_lat', cp_lng='$cp_lng', cp_zipcode='$cp_zipcode', cp_addr1='$cp_addr1', cp_addr2='$cp_addr2', cp_tel='$cp_tel', cp_media_blog='$cp_media_blog', cp_media_instagram='$cp_media_instagram', cp_media_shop='$cp_media_shop', cp_media_youtube='$cp_media_youtube', cp_media_receipt='$cp_media_receipt', cp_media_carrot='$cp_media_carrot', cp_id='$cp_id', cp_subject='$cp_subject', cp_description='$cp_description', cp_sdatetime='$cp_sdatetime', cp_edatetime='$cp_edatetime', cp_pick_datetime='$cp_pick_datetime', cp_contents_sdatetime='$cp_contents_sdatetime', cp_contents_edatetime='$cp_contents_edatetime', cp_result_datetime='$cp_result_datetime', cp_point='$cp_point', cp_use='$cp_use', cp_reward='$cp_reward', cp_keyword='$cp_keyword', cp_guide='$cp_guide', cp_notice='$cp_notice', cp_memo='$cp_memo', cp_type='$cp_type', cp_reward_type='$cp_reward_type', cp_supply_no='$cp_supply_no', cp_md_no='$cp_md_no', cp_md_name='$cp_md_name', cp_recruit='$cp_recruit', cp_category='$cp_category', cp_category_id='$cp_category_id'";

	sql_query("$mode $common_sql $add_sql $where_sql");

	// ▼ 메타테크 부업 미션 자동 연동 (2026-06-16) — 캠페인에 묶어 등록. 캠페인 저장 뒤라 실패해도 캠페인 등록엔 영향 없음.
	if($_POST['mt_enable']=="1" && trim($_POST['mt_point'])!==""){
		$mt_b = trim($_POST['mt_op_b']);
		if($mt_b!=="" && $mt_b!=="선택"){
			$mt_c = (strpos($mt_b,'정답맞추기')!==false || strpos($mt_b,'공유미션')!==false) ? "text" : "pic";
			$mt_edate = $cp_contents_edatetime ? $cp_contents_edatetime : $cp_edatetime;
			// 노출 사진(선택) 업로드
			$mt_rank_img = '';
			if(isset($_FILES['mt_rank_img']) && $_FILES['mt_rank_img']['name']){
				$__rf = file_upload($nfor[path]."/data/metatech/", $_FILES['mt_rank_img']);   // 2026-06-17 fix2: admin_upload(cp_img)와 동일하게 $nfor[path]."/data/" (검증된 관리자 업로드 경로)
				if($__rf) $mt_rank_img = $__rf;
			}
			$mt_data = array(
				'meta_op_a'   => ($_POST['mt_op_a'] ? $_POST['mt_op_a'] : '스토어'),
				'meta_op_b'   => $mt_b,
				'meta_op_c'   => $mt_c,
				'm_name'      => $cp_subject,
				'm_keyword'   => trim($_POST['mt_keyword']),
				'm_url'       => trim($_POST['mt_url']),
				'm_answer'    => trim($_POST['mt_answer']),
				'm_point'     => (int)$_POST['mt_point'],
				'm_sdate'     => substr($cp_sdatetime,0,10),
				'm_edate'     => substr($mt_edate,0,10),
				'm_day_conut' => ($_POST['mt_day_count'] ? (int)$_POST['mt_day_count'] : 100),
				'm_cp_id'     => $cp_id,
				'm_rank_area' => trim($_POST['mt_rank_area']),
				'm_rank'      => trim($_POST['mt_rank']),
				'm_rank_img'  => $mt_rank_img,
				'm_md_name'   => $cp_md_name,   // 캠페인 담당자를 메타테크 담당자로 (2026-06-17)
			);
			upsert_table_nfor_metatech_by_cp($mt_data);
		}
	}
	// ▲




	if($mode=="insert"){
		$traffic_table = "nfor_traffic_".date("Y_W");
		$row_exist = sql_fetch("SHOW TABLES LIKE '$traffic_table'");
		if(!$row_exist){			
			
$que = sql_query("
CREATE TABLE IF NOT EXISTS `$traffic_table` (
  `tr_cp_id` varchar(50) NOT NULL,
  `tr_rv_id` varchar(50) NOT NULL,
  `tr_mb_no` varchar(50) NOT NULL,
  `tr_device` varchar(10) NOT NULL,
  `tr_ip` varchar(20) NOT NULL,
  `tr_referer` varchar(255) NOT NULL,
  `tr_agent` varchar(255) NOT NULL,
  `tr_browser` varchar(50) NOT NULL,
  `tr_os` varchar(50) NOT NULL,
  `tr_date` date NOT NULL,
  `tr_datetime` datetime NOT NULL,
  KEY `tr_cp_id` (`tr_cp_id`,`tr_mb_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

		}
	}

	if($mode=="update" and $write[cp_asign]=="3" and $member[mb_admin]=="1") sql_query("update $table set cp_asign='1' where $id='{$id_value}'");

	alert($msg,$move);
}

include_once "head.php";
?>
<link rel="stylesheet" href="mc_review.css?v=<?=@filemtime(dirname(__FILE__).'/mc_review.css')?>"><!-- 입점업체/관리자 검색형 드롭다운 스타일 -->
<script src="mc_select_search.js?v=<?=@filemtime(dirname(__FILE__).'/mc_select_search.js')?>"></script><!-- 캠페인 등록 입점업체 검색 활성화 2026-06-15 -->
<?php
// 입점업체 / 광고주(adv_) 선택칸 분리 (2026-06-17)
$admin[sel_supply][""] = "선택 안 함";
$admin[sel_adv][""]    = "선택 안 함";
$que = sql_query("select mb_no, mb_id, mb_name, mb_cp_name from nfor_member where mb_leave_datetime='' and mb_admin='1' order by ( case when mb_cp_name between '가' and  '金' then 1 when mb_cp_name between 'a' and 'z'  THEN 3 when mb_cp_name between 'A' and 'Z' then 4  when mb_cp_name between '0' and '9' then 5  when mb_cp_name between '!' and '.' then 6 else 0  end ), mb_cp_name asc");
while($row = sql_fetch_array($que)){
	if(strpos($row[mb_id],'adv_')===0) $admin[sel_adv][$row[mb_no]] = $row[mb_cp_name];
	else $admin[sel_supply][$row[mb_no]] = $row[mb_cp_name]."($row[mb_name])";
}
// 현재 저장값(수정모드)이 광고주인지 판별 → 알맞은 칸에 미리 선택
$__cur_supply = $write[cp_supply_no]; $__cur_is_adv = false;
if($__cur_supply){ $__cm = sql_fetch("select mb_id from nfor_member where mb_no='".addslashes($__cur_supply)."'"); $__cur_is_adv = (strpos($__cm[mb_id],'adv_')===0); }
$write[sel_supply] = $__cur_is_adv ? '' : $__cur_supply;
$write[sel_adv]    = $__cur_is_adv ? $__cur_supply : '';

$admin[cp_md_no][""] = "선택";
$que = sql_query("select mb_no, mb_name, mb_cp_name from nfor_member where mb_leave_datetime='' and mb_admin='2' order by ( case when mb_cp_name between '가' and  '金' then 1 when mb_cp_name between 'a' and 'z'  THEN 3 when mb_cp_name between 'A' and 'Z' then 4  when mb_cp_name between '0' and '9' then 5  when mb_cp_name between '!' and '.' then 6 else 0  end ), mb_cp_name asc");
while($row = sql_fetch_array($que)){
	$admin[cp_md_no][$row[mb_no]] = $row[mb_name];
}

$admin[campaign_category_1][""] = "==== 1차 분류 ====";
$que = sql_query("select * from nfor_campaign_category where cg_depth='0' order by cg_rank asc");
while($row = sql_fetch_array($que)){
	$admin[campaign_category_1][$row[category_id]] = $row[cg_category];
}

$admin[campaign_category_2][""] = "==== 2차 분류 ====";
$admin[campaign_category_3][""] = "==== 3차 분류 ====";
$admin[campaign_category_4][""] = "==== 4차 분류 ====";
?>
<style>
#map { height:350px; width:100%; margin-top:10px; }

/* ===== 캠페인 등록폼 디자인 현대화 (화면만 / 저장·항목 불변) 2026 ===== */
form[name="fwrite"]{ --cf-accent:#0d9488; --cf-soft:#effbf9; --cf-ink:#0f172a; --cf-ink2:#475569; --cf-line:#e6eaf0; --cf-line2:#eef1f5; }
form[name="fwrite"] .table.cols_tbl{ background:#fff; border:1px solid var(--cf-line); border-radius:16px; box-shadow:0 1px 2px rgba(15,23,42,.04),0 4px 14px rgba(15,23,42,.05); overflow:hidden; border-collapse:separate !important; border-spacing:0; margin-bottom:18px; }
form[name="fwrite"] .cols_tbl > tbody > tr > th, form[name="fwrite"] .cols_tbl > tbody > tr > td,
form[name="fwrite"] .cols_tbl > tr > th, form[name="fwrite"] .cols_tbl > tr > td{ border-top:1px solid var(--cf-line2) !important; padding:13px 18px !important; vertical-align:middle; }
form[name="fwrite"] .cols_tbl th{ background:#fafbfc !important; color:var(--cf-ink2) !important; font-weight:700 !important; font-size:13.5px !important; }
form[name="fwrite"] input[type=text], form[name="fwrite"] input[type=number], form[name="fwrite"] input[type=password], form[name="fwrite"] select, form[name="fwrite"] textarea{
  border:1px solid #d8dee7 !important; border-radius:9px !important; padding:8px 11px !important; font-size:13.5px !important; color:var(--cf-ink) !important; box-shadow:none !important; height:auto !important; line-height:1.4 !important; }
form[name="fwrite"] input:focus, form[name="fwrite"] select:focus, form[name="fwrite"] textarea:focus{ border-color:var(--cf-accent) !important; box-shadow:0 0 0 3px var(--cf-soft) !important; outline:none !important; }
form[name="fwrite"] textarea{ min-height:80px; }
form[name="fwrite"] .btn{ border-radius:9px !important; }
form[name="fwrite"] .btn-red{ background:#dc2626 !important; border-color:#dc2626 !important; font-weight:800 !important; }
/* 섹션 구분 행(2단계에서 사용) */
form[name="fwrite"] tr.mc-sec > td{ background:#f8fafc !important; border-top:2px solid var(--cf-accent) !important; padding:11px 18px !important; }
form[name="fwrite"] tr.mc-sec .t{ font-size:14px; font-weight:800; color:var(--cf-ink); letter-spacing:-.01em; }
form[name="fwrite"] tr.mc-sec .d{ color:#94a3b8; font-size:12px; font-weight:500; margin-left:8px; }
form[name="fwrite"] tr.mc-presets > td{ background:#f0fdfa !important; }
form[name="fwrite"] .mc-pre-wrap{ display:flex; flex-wrap:wrap; align-items:center; gap:8px; }
form[name="fwrite"] .mc-pre-label{ font-weight:800; color:#0f766e; font-size:13px; }
form[name="fwrite"] button.mc-pre{ border:1px solid #99e6da; background:#d7f5f0; color:#0d9488; border-radius:999px; padding:7px 14px; font-size:12.5px; font-weight:700; cursor:pointer; }
form[name="fwrite"] button.mc-pre:hover{ background:#bdeee6; }
form[name="fwrite"] .mc-pre-hint{ color:#64748b; font-size:11.5px; }
/* 일정 날짜칸 폭 — "2026-06-18 00:00" 전체가 안 짤리게 2026-06-18 */
form[name="fwrite"] input.datepicker-here{ width:170px !important; min-width:170px !important; }

/* ===== 좁은 화면 대응 (이 admin 페이지 한정 고정폭 해제) ===== */
.lnb_zone{ min-width:0 !important; width:auto !important; }
form[name="fwrite"] .table.cols_tbl{ width:100% !important; max-width:100% !important; }
form[name="fwrite"] td{ word-break:break-word; }
form[name="fwrite"] select{ max-width:100%; }
form[name="fwrite"] select[multiple]{ border:1px solid #d8dee7 !important; border-radius:9px !important; padding:6px !important; }
form[name="fwrite"] img, form[name="fwrite"] iframe{ max-width:100%; }

/* ===== 라디오 · 체크박스 → 알약 버튼 (현대화) ===== */
form[name="fwrite"] td label:has(> input[type="radio"]),
form[name="fwrite"] td label:has(> input[type="checkbox"]){
  display:inline-flex !important; align-items:center; gap:7px; cursor:pointer;
  border:1px solid #d8dee7; border-radius:999px; padding:7px 14px; margin:0 7px 7px 0;
  font-size:13px; font-weight:600; color:var(--cf-ink2); background:#fff; transition:all .12s; vertical-align:middle;
}
form[name="fwrite"] td label:has(> input[type="radio"]:checked),
form[name="fwrite"] td label:has(> input[type="checkbox"]:checked){
  background:var(--cf-soft) !important; border-color:var(--cf-accent) !important; color:var(--cf-accent) !important; font-weight:800;
}
form[name="fwrite"] td label:has(> input[type="radio"]):hover,
form[name="fwrite"] td label:has(> input[type="checkbox"]):hover{ border-color:var(--cf-accent2); }
/* ===== CHEditor 외형 정리 (틀만, 기능·아이콘 불변) ===== */
form[name="fwrite"] .cheditor-container{ border:1px solid #d8dee7 !important; border-radius:10px !important; overflow:hidden !important; box-shadow:0 1px 3px rgba(15,23,42,.05) !important; }
form[name="fwrite"] .cheditor-tb-wrapper{ background:#f8fafc !important; border-bottom:1px solid #eef1f5 !important; }

/* ===== 키워드 메인/서브 분리 칸 ===== */
form[name="fwrite"] .mc-kw{ display:flex; flex-direction:column; gap:10px; }
form[name="fwrite"] .mc-kw-field{ display:flex; align-items:flex-start; gap:10px; }
form[name="fwrite"] .mc-kw-label{ flex:0 0 86px; font-size:12.5px; font-weight:700; color:var(--cf-accent); background:var(--cf-soft); border:1px solid #cdebe6; border-radius:8px; padding:9px 8px; text-align:center; }
form[name="fwrite"] .mc-kw-input{ flex:1 1 auto; min-width:0; }
</style>

<form name="fwrite" method="post" onsubmit="return fsubmit(this)" enctype="multipart/form-data" autocomplete="off">
<?php if($mc_copy_mode){ ?>
<input type="hidden" name="mode" id="mode" value="insert"><!-- 다음회차 복사는 신규 INSERT 강제 (admin_hidden은 $write가 채워져 있으면 update로 잘못 판단함) -->
<input type="hidden" name="mc_copy_src" value="<?=htmlspecialchars($_GET['copy'])?>"><!-- 복사 원본 cp_id: fsubmit가 action에서 쿼리스트링(?copy=)을 제거하므로 POST로 전달 -->
<?php } else { ?>
<!-- mode 명확화(2026-06-18): URL에 기존 cp_id가 있을 때만 update, 신규는 insert. (admin_hidden이 신규 폼에서도 update로 잘못 판단해 등록이 0건 update로 헛돌던 버그 수정) -->
<input type="hidden" name="mode" id="mode" value="<?=$id_value ? 'update' : 'insert'?>">
<?php } ?>
<?php if($mc_copy_mode){ ?>
<div style="margin:12px 0;padding:12px 16px;background:#eef2ff;border:1px solid #c7d2fe;border-radius:8px;color:#3730a3;font-size:13px;line-height:1.6;">
📋 <b>다음 회차 복사 등록</b> — 기존 캠페인 정보를 그대로 불러왔습니다.
<b>일정(모집기간·선정발표·리뷰기간·결과발표)</b>만 새로 입력한 뒤 <b>[등록하기]</b>를 누르면, <u>새 캠페인코드로 신규 등록</u>됩니다. (기존 회차는 그대로 보존)
</div>
<?php } ?>

<table class="table cols_tbl margin0">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr class="mc-sec"><td colspan="2"><span class="t">📝 기본 정보</span><span class="d">캠페인 식별 · 담당자 정보</span></td></tr>
<tr>
	<th>캠페인코드</th> 
	<td>
	<?php
	if(!$write[cp_id]) $write[cp_id] = time();
	?>
	<div class="form-inline">
	<?=admin_text($write,"cp_id","width-150p","readonly")?>
	<? if($_GET[cp_id]){ ?>
		<?=admin_a("cp_link", "캠페인상세 보기", "btn btn-default btn-sm", " target=\"_blank\"", $nfor[path]."/campaign.php?cp_id=".$write[cp_id])?>
	<? } ?>
	</td>
</tr>
<tr style="display:none;">
	<th>상품그룹코드</th>
	<td>
	<?php
	if(!$write[cp_id_gp]) $write[cp_id_gp] = $write[cp_id];
	?>
	<?=admin_text($write,"cp_id_gp","width-150p")?>
	</td>
</tr>





<tr class="<?=$member[mb_admin]=="1"?"hide":""?>">
	<th>입점업체 / 광고주</th>
	<td>
	<?php
	if(!$write[cp_insert_datetime] and $member[mb_admin]=="1"){
		$write[cp_supply_no] = $member[mb_no];
	}
	?>
	<?php if($member[mb_admin]=="1"){ ?>
		<?=admin_hidden($write,"cp_supply_no")?>
	<?php } else { ?>
		<?=admin_hidden($write,"cp_supply_no")?>
		<div class="form-inline" style="display:flex;gap:22px;flex-wrap:wrap;align-items:flex-start">
			<div><div style="font-size:12.5px;font-weight:700;color:#475569;margin-bottom:4px">🏢 입점업체</div><?=admin_select($write,"sel_supply","width-300p","","0")?></div>
			<div><div style="font-size:12.5px;font-weight:700;color:#0d9488;margin-bottom:4px">📣 광고주</div><?=admin_select($write,"sel_adv","width-300p","","0")?></div>
		</div>
		<div style="font-size:12px;color:#8b95a1;margin-top:6px">입점업체 또는 광고주 중 <b>하나만</b> 선택하세요. (한쪽을 고르면 다른 쪽은 자동 해제됩니다)</div>
	<?php } ?>
	</td>
</tr>
<tr class="<?=$member[mb_admin]=="1" || $member[mb_admin]=="2"?"hide":""?>">
	<th>캠페인 담당자 <span style="color:#e8590c">*</span></th>
	<td>
	<?php
	if(!$write[cp_insert_datetime] and $member[mb_admin]=="2"){
		$write[cp_md_no] = $member[mb_no];
	}
	?>
	<?=$member[mb_admin]=="2"?admin_hidden($write,"cp_md_no"):admin_select($write,"cp_md_no","width-300p","","0")?>
	<input type="text" name="cp_md_name" value="<?=$write[cp_md_name]?>" placeholder="담당자 이름 (전산 직원명과 동일하게 · 예: 신요섭)" style="width:300px;margin-top:6px;" maxlength="60">
	<div style="margin-top:5px;color:#64748b;font-size:12px;">전산 캘린더에서 담당자별로 보려면 <b style="color:#0d9488;">전산 직원 이름과 똑같이</b> 입력하세요. <b style="color:#e8590c">(필수 입력)</b></div>
	</td>
</tr>





<tr class="mc-sec"><td colspan="2"><span class="t">🗂️ 분류 · 형태 · 매체</span><span class="d">카테고리 · 캠페인 유형 · 리뷰 채널 · 모집</span></td></tr>
<tr>
	<th>캠페인분류</th>
	<td>

	<div class="form-inline">
		<?=admin_hidden($write,"insert_cate_id")?>
		<div class="form-group">
			<?=admin_select($write,"campaign_category_1","campaign_multi_select","size=\"6\"","0")?>	
		</div>
		<div class="form-group">
			<?=admin_select($write,"campaign_category_2","campaign_multi_select","size=\"6\"","0")?>
		</div>
		<div class="form-group">
			<?=admin_select($write,"campaign_category_3","campaign_multi_select","size=\"6\"","0")?>
		</div>
		<div class="form-group">
			<?=admin_select($write,"campaign_category_4","campaign_multi_select","size=\"6\"","0")?>
		</div>
	</div>

	<div id="span_campaign_select_preview" class="hide">

		<div class="form-inline">
		선택된 캠페인분류 : 
		<span id="campaign_select_preview"></span>
		<?=admin_button("campaign_category_add_btn","추가","btn btn-default btn-xs")?>
		</div>

	</div>

	<table id="campaign_category_add_list" class="table table-hover table-condensed">
	<tr>
		<th><?=admin_checkbox($check, "category_all", "category_all")?></th>
		<th>선택캠페인분류</th>
		<th>분류코드</th>
		<th>대표분류설정</th>
		<th>삭제</th>
	</tr>
	<tbody>
	<?
	if($write[cp_category]){
		$cp_category = explode("||",trim($write[cp_category]));
		for($i=0; $i<count($cp_category); $i++){
			$row["cp_category_val[]"] = $cp_category[$i];
	?>
	<tr>
		<td><?=admin_hidden($row,"cp_category_val[]","cp_category_val")?><?=admin_checkbox($check, "category_ea", "category_ea")?></td>
		<td><?=campaign_category_id_name($cp_category[$i])?></td>
		<td><?=$cp_category[$i]?></td>
		<td><input type="radio" name="cp_category_id" value="<?=$cp_category[$i]?>" <?=$cp_category[$i]==$write[cp_category_id]?"checked":""?>></td>
		<td><?=admin_button("category_del", "삭제", "btn btn-default btn-xs tr_remove")?></td>
	</tr>
	<?
		}
	}
	?>
	</tbody>
	</table>
	<div class="form-inline">
	<?=admin_button("sel_category_del_btn","선택분류삭제","btn btn-default btn-xs")?>
	</div>
	</td>
</tr>




<tr>
	<th>캠페인 형태</th> 
	<td>
	<?php
	if(!$write[cp_type]) $write[cp_type] = "1";
	?>
	<div class="form-inline"><?=admin_radio($write,"cp_type")?></div>
	</td>
</tr>



<tr class="map_tr <?php if($write[cp_type]=="1" || $write[cp_type]=="3") {?>hide<?php } ?>">
	<th>지도노출여부</th>
	<td>
	<?php
	if(!$write[cp_map_use]) $write[cp_map_use] = "1";
	?>
	<div class="form-inline"><?=admin_radio($write,"cp_map_use")?></div>
	
	</td>
</tr>
<tr class="map_tr <?php if($write[cp_type]=="1" || $write[cp_type]=="3") {?>hide<?php } ?>">
	<th>방문지 지도</th>
	<td>

	<?=admin_hidden($write,"cp_lat")?>
	<?=admin_hidden($write,"cp_lng")?>
	<div class="form-inline">
		<?=admin_text($write,"xy_address","width-400p","placeholder=\"주소로 지도 검색 : 입력 예) 인천 서구 솔빛로 93\"")?>
		<?=admin_button("xy_search","검색","btn-gray btn-sm")?>
	</div>

	<div id="map"></div>

	</td>
</tr>
<tr class="map_tr <?php if($write[cp_type]=="1" || $write[cp_type]=="3") {?>hide<?php } ?>">
	<th>방문지 주소</th>
	<td>
	<div class="marbottom5"><div class="form-inline"><?=admin_text($write,"cp_zipcode","width-80p")?> <?=admin_button("find_cp_zipcode","우편번호찾기","btn-gray")?></div></div>
	<div class="form-inline"><?=admin_text($write,"cp_addr1","width-200p")?> <?=admin_text_span($write,"cp_addr2","width-380p")?></div>
	</td>
</tr>
<tr class="map_tr <?php if($write[cp_type]=="1" || $write[cp_type]=="3") {?>hide<?php } ?>">
	<th>방문지 전화번호</th>
	<td>
	<?=admin_text($write,"cp_tel","width-100p")?>
	</td>
</tr>

<tr>
	<th>리뷰 매체</th> 
	<td>
		<?=admin_checkbox($checkbox, "cp_media_blog", "cp_media", $write[cp_media_blog]?"checked":"", "네이버 블로그")?>
		<?=admin_checkbox($checkbox, "cp_media_instagram", "cp_media", $write[cp_media_instagram]?"checked":"", "인스타그램")?>
		<?=admin_checkbox($checkbox, "cp_media_youtube", "cp_media", $write[cp_media_youtube]?"checked":"", "유튜브")?>
		<?=admin_checkbox($checkbox, "cp_media_shop", "cp_media", $write[cp_media_shop]?"checked":"", "네이버 쇼핑몰")?>
		<?=admin_checkbox($checkbox, "cp_media_receipt", "cp_media", $write[cp_media_receipt]?"checked":"", "영수증")?>
		<?=admin_checkbox($checkbox, "cp_media_carrot", "cp_media", $write[cp_media_carrot]?"checked":"", "당근")?>
	</td>
</tr>




<tr>
	<th>기타</th> 
	<td>
	<?=admin_checkbox($checkbox, "cp_check1", "cp_media", $write[cp_check1]?"checked":"", "구매평")?>
	<?=admin_checkbox($checkbox, "cp_check2", "cp_media", $write[cp_check2]?"checked":"", "페이백")?>
	<?=admin_checkbox($checkbox, "cp_ohouse", "cp_media", $write[cp_ohouse]?"checked":"", "오늘의집")?>
	<?=admin_checkbox($checkbox, "cp_coupang", "cp_media", $write[cp_coupang]?"checked":"", "쿠팡")?>
	</td>
</tr>






<tr>
	<th>모집인원</th> 
	<td><div class="form-inline"><?=admin_text($write,"cp_recruit","width-80p")?>명 모집</div></td>
</tr>

<tr>
	<th>참여권한</th>
	<td>
	<?php
	if(!$write[cp_buyer]) $write[cp_buyer] = "1";
	?>
	<div class="form-inline">
	<?=admin_radio($write,"cp_buyer")?>
	<?=admin_button("cp_buyer_btn", "레벨선택","btn btn-default btn-sm",  $write[cp_buyer]<>"3"?"disabled=\"disabled\"":"")?>
	</div>
	<ul id="layer_level_append">
		<?php
		if($write[cp_buyer_level]){
			$cp_buyer_level = explode("||",trim($write[cp_buyer_level]));
			for($i=0; $i<count($cp_buyer_level); $i++){
				$data = sql_fetch("select lv_name from nfor_level where lv_id='{$cp_buyer_level[$i]}'");
		?>
		<li id="level_<?=$cp_buyer_level[$i]?>">
			<span><?=$data[lv_name]?></span>
			<input type="hidden" name="cp_buyer_level_val[]" value="<?=$cp_buyer_level[$i]?>">
			<img src="img/color_del.png" alt="삭제" class="li_remove">
		</li>
		<?php
			}
		}
		?>
	</ul>
	</td>
</tr>

<tr class="mc-sec"><td colspan="2"><span class="t">✏️ 캠페인 소개</span><span class="d">제목과 한 줄 설명</span></td></tr>
<tr>
	<th>캠페인명</th> 
	<td><?=admin_text($write,"cp_subject")?></td>
</tr>
<tr>
	<th>캠페인 간단설명</th> 
	<td><?=admin_text($write,"cp_description")?></td>
</tr>
<tr class="mc-sec"><td colspan="2"><span class="t">📅 일정</span><span class="d">★ 다음 회차마다 이 부분만 새로 입력하세요</span></td></tr>
<tr class="mc-presets"><td colspan="2">
<div class="mc-pre-wrap">
<span class="mc-pre-label">⚡ 빠른 설정</span>
<button type="button" class="mc-pre" data-mc-preset="standard">표준 일정 자동채움 ✨</button>
<button type="button" class="mc-pre" data-mc-days="7">신청 7일</button>
<button type="button" class="mc-pre" data-mc-days="14">신청 14일</button>
<button type="button" class="mc-pre" data-mc-preset="clear">전체 비우기</button>
<span class="mc-pre-hint">오늘 시작 · 신청 N일 → 발표 +1일 → 리뷰 14일 → 결과 +3일 자동 입력</span>
</div>
</td></tr>
<tr>
	<th>캠페인 신청기간</th> 
	<td>
	<div class="form-inline"><?=admin_text($write,"cp_sdatetime","width-130p datepicker-here","data-timepicker=\"true\" data-language=\"ko\"")?>~<?=admin_text($write,"cp_edatetime","width-130p datepicker-here","data-timepicker=\"true\" data-language=\"ko\"")?></div>
	</td>
</tr>		
<tr>
	<th>선정자 발표</th> 
	<td>
	<div class="form-inline"><?=admin_text($write,"cp_pick_datetime","width-130p datepicker-here","data-timepicker=\"true\" data-language=\"ko\"")?></div>
	</td>
</tr>		
<tr>
	<th>리뷰 등록기간</th> 
	<td>
	<div class="form-inline"><?=admin_text($write,"cp_contents_sdatetime","width-130p datepicker-here","data-timepicker=\"true\" data-language=\"ko\"")?>~<?=admin_text($write,"cp_contents_edatetime","width-130p datepicker-here","data-timepicker=\"true\" data-language=\"ko\"")?></div>
	</td>
</tr>
<tr>
	<th>캠페인 결과발표</th> 
	<td>
	<div class="form-inline"><?=admin_text($write,"cp_result_datetime","width-130p datepicker-here","data-timepicker=\"true\" data-language=\"ko\"")?></div>
	</td>
</tr>



<tr class="mc-sec"><td colspan="2"><span class="t">🎁 제공내역 · 미션</span><span class="d">혜택 · 키워드 · 리뷰 가이드</span></td></tr>
<tr>
	<th>캠페인 정보</th> 
	<td><?=admin_editor($write,"cp_memo")?></td>
</tr>

<tr>
	<th>제공내역 형태</th> 
	<td>
	<?php
	if(!$write[cp_reward_type]) $write[cp_reward_type] = "1";
	?>
	<div class="form-inline"><?=admin_radio($write,"cp_reward_type")?></div>
	<br>
	<div style="margin-top:10px;" class="cp_point_wrap form-inline <?=$write[cp_reward_type]=="1"?"hide":""?>"><?=admin_text($write,"cp_point","width-100p")?> 포인트를 리뷰 등록 확인시 지급합니다</div>
	</td>
</tr>
<tr>
	<th>제공내역</th> 
	<td>
	<?=admin_editor($write,"cp_reward")?>
	</td>
</tr>
<tr>
	<th>키워드</th>
	<td>
		<div class="mc-kw">
			<div class="mc-kw-field"><span class="mc-kw-label">메인 키워드</span><input type="text" id="mc_kw_main" class="mc-kw-input" placeholder="예) 갈치조림"></div>
			<div class="mc-kw-field"><span class="mc-kw-label">서브 키워드</span><textarea id="mc_kw_sub" class="mc-kw-input" rows="3" placeholder="예) 제주은갈치조림 , 갈치조림밀키트 , 30년 맛집   (추가 안내문구도 여기에 작성)"></textarea></div>
		</div>
		<div style="display:none;"><?=admin_textarea($write,"cp_keyword")?></div>
	</td>
</tr>
<script>
(function(){
	var hid=document.querySelector('form[name="fwrite"] [name="cp_keyword"]');
	var main=document.getElementById('mc_kw_main'), sub=document.getElementById('mc_kw_sub');
	if(!hid||!main||!sub) return;
	var v=hid.value||'';
	var mm=v.match(/메인\s*키워드\s*[:：]\s*(.*)/);
	var ss=v.match(/서브\s*키워드\s*[:：]\s*([\s\S]*)/);
	if(mm) main.value=mm[1].trim();
	if(ss) sub.value=ss[1].replace(/^\s+/,'').replace(/\s+$/,'');
	if(!mm && !ss && v.trim()) sub.value=v.trim();
	function recombine(){ hid.value='메인 키워드 : '+main.value.trim()+'\n서브 키워드 : '+sub.value.trim(); }
	main.addEventListener('input',recombine);
	sub.addEventListener('input',recombine);
})();
</script>

<tr>
	<th>해시태그</th> 
	<td><?=admin_textarea($write,"cp_hashtag")?></td>
</tr>

<tr>
	<th>리뷰 미션</th> 
	<td><?=admin_editor($write,"cp_guide_add")?></td>
</tr>


<tr>
	<th>추가 안내<!-- (리뷰작성시 안내사항) --></th> 
	<td><?=admin_editor($write,"cp_guide")?></td>
</tr>
<tr>
	<th>유의 사항</th> 
	<td><?=admin_editor($write,"cp_notice")?></td>
</tr>









<tr class="mc-sec"><td colspan="2"><span class="t">🖼️ 이미지 · 노출</span><span class="d">대표 이미지와 공개 설정</span></td></tr>
<tr>
	<th>캠페인 목록 이미지</th>
	<td colspan="3">
	<?=admin_file($write,"cp_img","campaign")?>
	</td>
</tr>
		
<tr>
	<th>노출여부</th> 
	<td>
	<?php
	if(!$write[cp_use]) $write[cp_use] = "1";
	?>
	<?=admin_radio($write,"cp_use")?>
	</td>
</tr>
<? if($write[cp_insert_datetime]){ ?>
<tr>
	<th>등록일시</th> 
	<td><?=$write[cp_insert_datetime]?></td>
</tr>
<tr>
	<th>최종수정일시</th> 
	<td><?=$write[cp_update_datetime]?></td>
</tr>
<? } ?>
</table>

<?php
// ── 메타테크 부업 미션 (선택) — 캠페인에 묶어 등록 2026-06-16 ──
$mt_x = array();
if($write[cp_id]) $mt_x = sql_fetch("select * from nfor_metatech where m_cp_id='".addslashes($write[cp_id])."' order by idx desc limit 1");
$mt_on = !empty($mt_x['idx']);
?>
<table class="table cols_tbl margin0" style="margin-top:18px">
<colgroup><col class="width-150p"><col></colgroup>
<tr class="mc-sec"><td colspan="2" style="background:#f1faf4">
	<label style="cursor:pointer;font-weight:800;color:#0d9488;font-size:14px">
		<input type="checkbox" name="mt_enable" id="mt_enable" value="1" <?=$mt_on?'checked':''?>>
		📱 메타테크 부업 미션 추가 <span style="color:#8b95a1;font-weight:600;font-size:12px">(켜면 이 캠페인에 모바일 부업 미션이 함께 등록됩니다)</span>
	</label>
</td></tr>
<tbody id="mt_fields" style="<?=$mt_on?'':'display:none'?>">
<tr>
	<th>구분</th>
	<td>
		<label class="radio-inline"><input type="radio" name="mt_op_a" value="스토어" <?=($mt_x['meta_op_a']!=='플레이스')?'checked':''?>> 스토어</label>
		<label class="radio-inline"><input type="radio" name="mt_op_a" value="플레이스" <?=($mt_x['meta_op_a']==='플레이스')?'checked':''?>> 플레이스</label>
	</td>
</tr>
<tr>
	<th>유형</th>
	<td>
		<select name="mt_op_b" class="form-control width-300p">
			<option value="">선택</option>
			<?php foreach((array)$nfor[meta_op_b] as $opt){ ?>
			<option value="<?=$opt?>" <?=($mt_x['meta_op_b']===$opt)?'selected':''?>><?=$opt?></option>
			<?php } ?>
		</select>
		<div style="color:#8b95a1;font-size:12px;margin-top:4px">정답맞추기·공유미션 = 정답 입력형 / 저장하기·알림받기·상품찜 = 스크린샷 검수형</div>
	</td>
</tr>
<tr>
	<th>검색키워드</th>
	<td><input type="text" name="mt_keyword" class="form-control width-300p" value="<?=htmlspecialchars($mt_x['m_keyword'])?>" placeholder="검색형 미션일 때 (예: 콜라겐 앰플)"></td>
</tr>
<tr>
	<th>URL</th>
	<td><input type="text" name="mt_url" class="form-control width-300p" value="<?=htmlspecialchars($mt_x['m_url'])?>" placeholder="스토어/상품 URL"></td>
</tr>
<tr>
	<th>정답</th>
	<td><input type="text" name="mt_answer" class="form-control width-300p" value="<?=htmlspecialchars($mt_x['m_answer'])?>" placeholder="정답형일 때 (예: 대표상품 가격) · 공백·대소문자 무시"></td>
</tr>
<tr>
	<th>현재 노출 위치</th>
	<td>
		<?php $mt_areas=array('통합검색','네이버쇼핑','네이버플레이스','블로그탭'); ?>
		<select name="mt_rank_area" class="form-control" style="display:inline-block;width:auto">
			<option value="">검색영역</option>
			<?php foreach($mt_areas as $a){ ?><option value="<?=$a?>" <?=($mt_x['m_rank_area']===$a)?'selected':''?>><?=$a?></option><?php } ?>
		</select>
		<input type="text" name="mt_rank" class="form-control" style="display:inline-block;width:200px" value="<?=htmlspecialchars($mt_x['m_rank'])?>" placeholder="순위/위치 (예: 7위, 2페이지 상단)">
		<div style="color:#8b95a1;font-size:12px;margin-top:4px">참여자가 검색결과에서 빨리 찾도록 알려줍니다.</div>
	</td>
</tr>
<tr>
	<th>노출 사진</th>
	<td>
		<input type="file" name="mt_rank_img" accept="image/*">
		<?php if($mt_x['m_rank_img']){ ?><div style="margin-top:6px"><img src="<?=$nfor[path]?>/data/metatech/<?=htmlspecialchars($mt_x['m_rank_img'])?>" style="max-width:160px;border:1px solid #e2e8f0;border-radius:8px"><div style="font-size:11px;color:#8b95a1">현재 등록된 사진 (새로 올리면 교체)</div></div><?php } ?>
		<div style="color:#8b95a1;font-size:12px;margin-top:4px">검색결과 화면 캡처(선택) — 참여자에게 보여집니다.</div>
	</td>
</tr>
<tr>
	<th>지급 포인트</th>
	<td><input type="text" name="mt_point" class="form-control width-150p" value="<?=$mt_x['m_point']?$mt_x['m_point']:''?>" placeholder="숫자"> P
		<span style="color:#8b95a1;font-size:12px;margin-left:8px">진행기간은 캠페인 일정(신청시작 ~ 리뷰마감)을 자동 사용합니다.</span>
	</td>
</tr>
</tbody>
</table>

<div class="bottom_btn">
	<div class="form-inline">
		<?=admin_submit("fsubmit_btn", "등록하기", "btn btn-lg btn-red")?>
		<?=admin_a("list", "목록보기", "btn btn-lg btn-black", "", $list."?".$qstr)?>
	</div>
</div>

<script>
$(function(){
	$(document).on("change","#mt_enable",function(){ $("#mt_fields").toggle(this.checked); });
	$(document).on("keyup","input[name=mt_point]",function(){ this.value=this.value.replace(/[^0-9]/g,""); });

	// 입점업체/광고주 상호배제 → 히든 cp_supply_no 연결 (2026-06-17)
	function mcSupSync(changedId, otherId){
		var c=document.getElementById(changedId);
		if(!c) return;
		c.addEventListener('change', function(){
			var hid=document.querySelector('input[name=cp_supply_no]'), o=document.getElementById(otherId);
			if(c.value){
				if(hid) hid.value=c.value;
				if(o){ o.selectedIndex=0; var w=o.nextElementSibling; var oi=w?w.querySelector('.mc-ss-input'):null; if(oi) oi.value=(o.options[0]?o.options[0].text:''); }
			} else {
				if(hid && (!o || !o.value)) hid.value='';
			}
		});
	}
	mcSupSync('sel_supply','sel_adv');
	mcSupSync('sel_adv','sel_supply');
});
</script>

</form>

<script>
(function(){
  function p(n){return (n<10?'0':'')+n;}
  function fmt(d,end){return d.getFullYear()+'-'+p(d.getMonth()+1)+'-'+p(d.getDate())+' '+(end?'23:59':'00:00');}
  function add(d,days){var x=new Date(d.getTime()); x.setDate(x.getDate()+days); return x;}
  function setv(name,val){var el=document.querySelector('form[name="fwrite"] [name="'+name+'"]'); if(el){ el.value=val; }}
  var F=['cp_sdatetime','cp_edatetime','cp_pick_datetime','cp_contents_sdatetime','cp_contents_edatetime','cp_result_datetime'];
  function clearAll(){ F.forEach(function(n){ setv(n,''); }); }
  function standard(days){
    var t=new Date(); t.setHours(0,0,0,0);
    var aEnd=add(t,days), pick=add(aEnd,1), rStart=add(pick,1), rEnd=add(rStart,14), result=add(rEnd,3);
    setv('cp_sdatetime',fmt(t,false)); setv('cp_edatetime',fmt(aEnd,true));
    setv('cp_pick_datetime',fmt(pick,false));
    setv('cp_contents_sdatetime',fmt(rStart,false)); setv('cp_contents_edatetime',fmt(rEnd,true));
    setv('cp_result_datetime',fmt(result,false));
  }
  document.addEventListener('click',function(e){
    var b=e.target.closest('[data-mc-preset],[data-mc-days]'); if(!b) return; e.preventDefault();
    if(b.getAttribute('data-mc-preset')==='clear'){ clearAll(); return; }
    if(b.getAttribute('data-mc-preset')==='standard'){ standard(7); return; }
    var dd=b.getAttribute('data-mc-days'); if(dd){ standard(parseInt(dd)); }  // 신청 N일 → 발표/등록/결과까지 전체 자동계산 2026-06-18
  });
})();
</script>




<!-- 상품분류 -->
<script type="text/javascript">
<!--
$(document).on("click","#sel_category_del_btn",function(){ 
	if(!$("input.category_ea:checked").length){
		alert("삭제할 상품분류를 선택해주세요");
		return;
	}
	$("input.category_ea:checked").each(function(){
	   $(this).closest("tr").remove();
	});
});

$(document).on("click",".category_all",function(){ 
	$(".category_ea").prop("checked",this.checked);
});

$(document).on("click","#campaign_category_add_btn",function(){ 
	var first_check = "";
	if($(".cp_category_val").length=="0"){
		first_check = "checked";
	} else{
		first_check = "";
	}
	if($(".cp_category_val").length >= 10){
		alert("옵션은 db성능개선을 위해 10개까지만 추가 가능합니다");
		return;
	}
	var cp_category_val = $("#insert_cate_id").val();
	var is_select = 0;
	$(".cp_category_val").each(function(){
		if($(this).val()==cp_category_val){
			is_select = 1;
		}
	});
	if(is_select){
		alert("이미 선택한 항목입니다");
		return;
	}
	var campaign_select_preview = "";
	for(var k = 1; k <= 4; k++){
		if($("#campaign_category_"+k+" option:selected").val()){
			if(k>1){
				campaign_select_preview = campaign_select_preview + " > ";
			}
			campaign_select_preview = campaign_select_preview + $("#campaign_category_"+k+" option:selected").text();
		}
	}
	$("#campaign_category_add_list > tbody:last").append('<tr><td><input type="hidden" class="cp_category_val" name="cp_category_val[]" value="'+cp_category_val+'"><input type="checkbox" class="category_ea"></td><td>'+campaign_select_preview+'</td><td>'+cp_category_val+'</td><td><input type="radio" name="cp_category_id" value="'+cp_category_val+'" '+first_check+'></td><td><button type="button" class="btn btn-default btn-xs tr_remove">삭제</button></td></tr>');
});

$(document).on("change",".campaign_multi_select",campaign_category_change);
$(document).on("change",".campaign_multi_select",campaign_category_preview);
//-->
</script>
<!-- //상품분류 -->

<SCRIPT LANGUAGE="JavaScript">
<!--
$(document).on("click",".cp_media",function(){ 
	var cp_media = $(this).attr("name");
	if(cp_media=="cp_media_shop"){
		$("#cp_media_blog").attr("checked", false);
		$("#cp_media_instagram").attr("checked", false);
		$("#cp_media_youtube").attr("checked", false);
		$("#cp_media_receipt").attr("checked", false);
		$("#cp_media_carrot").attr("checked", false);
	} else{
		$("#cp_media_shop").attr("checked", false);
	}
});

$(document).on("click", "#find_cp_zipcode", function(){
	zipcode('cp_zipcode', 'cp_addr1', 'cp_addr2');
});

$(document).on("click","input[name=cp_reward_type]",function(){ 
	var type = this.value;
	if(type=="2" || type=="3"){
		$(".cp_point_wrap").removeClass("hide");	
	} else{	
		$(".cp_point_wrap").addClass("hide");
	}			
});

$(document).on("click","input[name=cp_type]",function(){ 
	var type = this.value;
	if(type=="2" || type=="4"){
		$(".map_tr").removeClass("hide");
	} else{
		$(".map_tr").addClass("hide");
	}			
	map.relayout();
});

$(document).on("click", "#xy_search", function(){
	var geocoder = new daum.maps.services.Geocoder();
	geocoder.addressSearch($('#xy_address').val(), function(result, status) {
		 if(status === daum.maps.services.Status.OK){
			$("#cp_lat").val(result[0].y);
			$("#cp_lng").val(result[0].x);
			var coords = new daum.maps.LatLng(result[0].y, result[0].x);
			marker.setPosition(coords);  
			map.setCenter(coords);
		} else{
			alert("검색결과가 없습니다");
		}
	});
});

var mapContainer = document.getElementById('map'),
	mapOption = { 
		center: new daum.maps.LatLng(<?=$write[cp_lat]?$write[cp_lat]:$nfor[default_lat]?>, <?=$write[cp_lng]?$write[cp_lng]:$nfor[default_lng]?>), // 지도의 중심좌표
		level: 6
	};

var map = new daum.maps.Map(mapContainer, mapOption);

var marker = new daum.maps.Marker({ 
	position: map.getCenter() 
}); 

marker.setMap(map);

daum.maps.event.addListener(map, 'click', function(mouseEvent) {        
	var latlng = mouseEvent.latLng; 
	marker.setPosition(latlng);    
	$("#cp_lat").val(latlng.getLat());
	$("#cp_lng").val(latlng.getLng());
});

function fsubmit(f){
	if(!$('#cp_id').val()){
		alert("캠페인코드를 입력해주세요");
		$('#cp_id').focus();
        return false;
	}
	if(!$('#cp_subject').val()){
		alert("캠페인명을 입력해주세요");
		$('#cp_subject').focus();
        return false;
	}
	if(!$.trim($('input[name=cp_md_name]').val())){
		alert("캠페인 담당자 이름을 입력해주세요.\n(전산 직원명과 동일하게 입력)");
		$('input[name=cp_md_name]').focus();
        return false;
	}
	if(!$('#cp_description').val()){
		alert("캠페인 간단설명을 입력해주세요");
		$('#cp_description').focus();
        return false;
	}
	if(!$('#cp_sdatetime').val()){
		alert("캠페인 신청기간을 입력해주세요");
		$('#cp_sdatetime').focus();
        return false;
	}
	if(!$('#cp_edatetime').val()){
		alert("캠페인 신청기간을 입력해주세요");
		$('#cp_edatetime').focus();
        return false;
	}
	if(!$('#cp_pick_datetime').val()){
		alert("선정자 발표일을 입력해주세요");
		$('#cp_pick_datetime').focus();
        return false;
	}
	if(!$('#cp_contents_sdatetime').val()){
		alert("리뷰 등록기간을 입력해주세요");
		$('#cp_contents_sdatetime').focus();
        return false;
	}
	if(!$('#cp_contents_edatetime').val()){
		alert("리뷰 등록기간을 입력해주세요");
		$('#cp_contents_edatetime').focus();
        return false;
	}
	if(!$('#cp_result_datetime').val()){
		alert("캠페인 결과발표일을 입력해주세요");
		$('#cp_result_datetime').focus();
        return false;
	}

	<?=admin_editor_update("cp_memo")?>

		
	<?=admin_editor_update("cp_guide")?>


		
	<?=admin_editor_update("cp_reward")?>

		
	<?=admin_editor_update("cp_notice")?>


	<?=admin_editor_update("cp_guide_add")?>






	f.action = "<?=$form?>";
	return true;	    
}	
//-->
</SCRIPT>













<!-- 레벨 -->
<script type="text/javascript">
<!--
$(document).on("click","#cp_buyer_btn",function(){ 
	cp_buyer_fnc(2);
});

$(document).on("click","input[name=cp_buyer]",function(){ 
	cp_buyer_fnc(this.value);
});

function cp_buyer_fnc(val){
	if(val == "2"){
		$('input:radio[name=cp_buyer]:input[value='+val+']').attr("checked", true);
		$("#layer_level_append").show();
		$("#cp_buyer_btn").attr("disabled",false);
		nfor_layer("level","","레벨선택");
	} else{
		$("#layer_level_append").html("").hide();
		$("#cp_buyer_btn").attr("disabled",true);
	}
}

$(document).on("click","#layer_level #insert_btn",function(){ 

	var str_lv_name = "";
	var lv_id = "";
	var lv_name = "";

	if($("#layer_level .chk:checked").length<1){
		alert("등급을 선택해주세요");
		return false;
	}

	$("#layer_level .chk:checked").each(function(index, item){
		lv_id = item.value;
		lv_name = $(this).data("lv_name");
		if(!$("#layer_level_append #level_"+lv_id).length){
			template = _.template($('#layer_level_script').html());
			template_html =  template({lv_name: lv_name, lv_id: lv_id});
			$("#layer_level_append").append(template_html);
		} else{
			if(str_lv_name) str_lv_name += ", ";
			str_lv_name += lv_name;
		}
	});

	if(str_lv_name){
		BootstrapDialog.alert("선택하신 "+str_lv_name+"은 이미 추가된 레벨입니다");
	}

	$('div.bootstrap-dialog-close-button').click();

});

$(document).on("click","#layer_level #allchk",function(){ 
	nfor_chk_all(this, 'chk');
});

$(document).on("click","#layer_level #search_btn",function(){ 
	layer_level_search();
});

$(document).on("keydown","#layer_level #keyword",function(e){ 
	if(e.keyCode == 13){
		layer_level_search();
		return false;
	}
});

function layer_level_search(){
	$.get("layer_level.php", $("#layer_level #fsearch").serialize(), function (data) {
		$("#layer_level").html(data);
	});
}

$(document).on("click","#layer_level .nfor_pagination a",function(){ 
	var page = $(this).data("page");
	$("#layer_level #layer_page").val(page);
	layer_level_search();
});
//-->
</script>
<script type="text/html" id="layer_level_script">
    <li id="level_<%=lv_id%>">
        <span><%=lv_name%></span>
        <input type="hidden" name="cp_buyer_level_val[]" value="<%=lv_id%>">
		<img src="img/color_del.png" alt="삭제" class="li_remove">
    </li>
</script>
<!-- //레벨 -->


<?php
include_once "tail.php";
?>