<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
/* =========================================================================
   캠페인 상세 — 모던 리뉴얼 (Toss/29CM 톤) 2026-06-08 / 모바일(nfor)
   기능 클래스(.btn_campaign_order/.btn_campaign_zzim/#countdown/.tabmenu/
   .tab-cont/.cp_memo_wrap/.campaign_memo_more/#map/.gond_item/.swiper-*)는
   JS 의존이라 그대로 유지하고 외형만 재정의한다.
   ========================================================================= */
:root{
  --mc-bg:#f3f4f6; --mc-card:#fff; --mc-line:#edf0f3; --mc-line2:#f1f3f5;
  --mc-ink:#0f172a; --mc-ink2:#4b5563; --mc-muted:#9ca3af;
  --mc-accent:#0d9488; --mc-accent2:#14b8a6; --mc-soft:#effbf9; --mc-purple:#8b5cf6; --mc-red:#dc2626;
  --mc-shadow:0 1px 2px rgba(15,23,42,.04),0 6px 18px rgba(15,23,42,.06);
}
.mc-cd{ background:var(--mc-bg); padding:0 0 110px; color:var(--mc-ink); line-height:1.6;
  font-family:-apple-system,BlinkMacSystemFont,"Apple SD Gothic Neo","Pretendard","Malgun Gothic",sans-serif; -webkit-font-smoothing:antialiased; }
.mc-cd .num,.mc-cd .txt_num{ font-variant-numeric:tabular-nums; }
.mc-cd img{ max-width:100%; }

/* 카드 공통 */
.mc-card{ background:var(--mc-card); border-radius:16px; box-shadow:var(--mc-shadow); margin:12px; padding:18px; }
.mc-sec-tit{ display:flex; align-items:center; gap:8px; font-size:16px; font-weight:800; letter-spacing:-.02em; margin:0 0 12px; }
.mc-sec-tit:before{ content:""; width:4px; height:16px; background:var(--mc-accent); border-radius:2px; }

/* HERO */
.mc-hero{ position:relative; background:#e9edf2; }
.mc-hero .it_img{ width:100%; display:block; aspect-ratio:1/1; object-fit:cover; }
.mc-hero .mc-dday{ position:absolute; left:12px; top:12px; background:rgba(15,23,42,.78); color:#fff;
  font-size:12px; font-weight:800; padding:6px 12px; border-radius:999px; backdrop-filter:blur(2px); }

/* 헤더 카드 */
.mc-head-card{ margin-top:-22px; position:relative; z-index:2; border-radius:18px 18px 16px 16px; }
.mc-badges{ display:flex; flex-wrap:wrap; gap:6px; margin-bottom:12px; }
.mc-badges span{ display:inline-flex; align-items:center; height:23px; padding:0 10px; border-radius:999px;
  font-size:11.5px; font-weight:700; line-height:1; letter-spacing:-.02em; border:0; }
.mc-badges .blog{ background:#e7f6ee; color:#16a34a; }
.mc-badges .instagram{ background:#fde7f3; color:#c026a3; }
.mc-badges .youtube{ background:#fee2e2; color:#dc2626; }
.mc-badges .shop{ background:#fef9c3; color:#a16207; }
.mc-badges .gumepyung{ background:#fef9c3; color:#a16207; }
.mc-badges .payback{ background:#fee2e2; color:#dc2626; }
.mc-badges .cp_ohouse{ background:#e0f2fe; color:#0284c7; }
.mc-badges .cp_coupang{ background:#fee2e2; color:#d84d32; }
.mc-badges .cp_media_receipt{ background:#eef2f6; color:#475569; }
.mc-badges .cp_media_carrot{ background:#ffedd5; color:#ea580c; }
.mc-badges .nor_box{ background:#eef2f6; color:#475569; }
.mc-badges .point_color4{ background:var(--mc-purple); color:#fff; }
.mc-head-card .itname{ font-size:21px; font-weight:800; letter-spacing:-.03em; line-height:1.4; margin:0 0 6px; color:var(--mc-ink); }
.mc-head-card .it_description{ font-size:13.5px; color:var(--mc-muted); margin:0 0 16px; }

/* 핵심 지표(신청현황 + 남은시간) */
.mc-metrics{ border-top:1px solid var(--mc-line); padding-top:15px; }
.mc-apply-row{ display:flex; align-items:baseline; justify-content:space-between; margin-bottom:9px; }
.mc-apply-row .lab{ font-size:13px; font-weight:700; color:var(--mc-ink2); }
.mc-apply-row .val{ font-size:14px; font-weight:700; color:var(--mc-ink2); }
.mc-apply-row .val b{ color:var(--mc-accent); font-size:19px; font-weight:800; }
.mc-progress{ height:8px; background:#eef1f5; border-radius:999px; overflow:hidden; }
.mc-progress i{ display:block; height:100%; background:linear-gradient(90deg,var(--mc-accent),var(--mc-accent2)); border-radius:999px; }
.mc-progress-sub{ font-size:11.5px; color:var(--mc-muted); text-align:right; margin-top:5px; }
.mc-progress-sub b{ color:var(--mc-accent); }
.mc-countdown{ margin-top:13px; background:var(--mc-soft); border:1px solid #cdebe6; border-radius:12px;
  padding:11px 14px; display:flex; align-items:center; justify-content:center; flex-wrap:wrap; gap:5px; }
.mc-countdown .cl{ font-size:12.5px; color:#0f766e; font-weight:800; margin-right:5px; }
.mc-countdown #countdown{ display:inline; color:#0f766e; font-size:13px; font-weight:700; }
.mc-countdown #countdown b{ background:#fff; border:1px solid #b9e8e0; border-radius:7px; padding:3px 7px; margin:0 1px;
  font-size:15px; font-weight:800; color:#0d9488; }

/* 일정 타임라인 */
.review_step_wrap{ display:flex; flex-direction:column; }
.review_step_wrap .step{ display:flex; align-items:center; justify-content:space-between; gap:12px;
  padding:13px 0; border-bottom:1px solid var(--mc-line2); }
.review_step_wrap .step:last-child{ border-bottom:0; }
.review_step_wrap .step em{ font-style:normal; font-size:13px; font-weight:600; color:var(--mc-ink2); }
.review_step_wrap .step em.point_color4{ color:var(--mc-accent); font-weight:800; }
.review_step_wrap .step b{ font-size:13.5px; font-weight:800; color:var(--mc-ink); }
.review_step_wrap .step b.point_color4{ color:var(--mc-accent); }

/* 탭 */
.gond_item{ height:1px; }
.nav_tabmenu{ background:#fff; margin:12px 0 0; position:sticky; top:0; z-index:15; box-shadow:0 1px 0 var(--mc-line); }
.nav_tabmenu .tabmenu{ display:flex; list-style:none; margin:0; padding:0 6px; }
.nav_tabmenu .tabmenu li{ flex:1; }
.nav_tabmenu .tabmenu li div{ }
.nav_tabmenu .tabmenu li a{ display:block; text-align:center; padding:14px 0; font-size:14px; font-weight:700;
  color:var(--mc-muted); text-decoration:none; border-bottom:2.5px solid transparent; }
.nav_tabmenu .tabmenu li.active a{ color:var(--mc-accent); border-bottom-color:var(--mc-accent); }
.nav_tabmenu .tabmenu li a .txt_num{ color:var(--mc-accent); margin-left:2px; }
.sns_popup_open_btn{ display:none; }

.tab-cont{ display:none; }
#tab1.tab-cont{ display:block; }

/* 캠페인 정보 본문 */
.cp_memo_wrap{ font-size:14px; color:var(--mc-ink2); }
.cp_memo_wrap img{ border-radius:10px; }
.cp_memo_wrap.overflowhidden{ max-height:520px; overflow:hidden; -webkit-mask-image:linear-gradient(#000 78%,transparent); mask-image:linear-gradient(#000 78%,transparent); }
.campaign_memo_more{ display:block; border:1px solid var(--mc-line); border-radius:10px; padding:12px; text-align:center;
  font-size:13.5px; font-weight:700; color:var(--mc-ink2); margin-top:12px; cursor:pointer; background:#fff; }

/* 제공내역/키워드/해시태그/미션 — 카드 리스트 */
.basic_form ul{ list-style:none; margin:0; padding:0; }
.basic_form li{ background:var(--mc-card); border-radius:14px; box-shadow:var(--mc-shadow); margin:12px; padding:16px 18px; position:relative; }
.basic_form li.mc-reward{ background:linear-gradient(180deg,#f0fdfa,#fff); border:1px solid #cdebe6; }
.basic_form .tit_basic{ display:flex; align-items:center; gap:7px; font-size:14.5px; font-weight:800; color:var(--mc-ink); margin-bottom:9px; }
.basic_form .tit_basic:before{ content:""; width:4px; height:14px; background:var(--mc-accent); border-radius:2px; }
.basic_form li.mc-reward .tit_basic{ font-size:16px; }
.basic_form .content{ display:block; font-size:13.5px; color:var(--mc-ink2); word-break:break-word; }
.basic_form li.mc-reward .content{ font-size:15px; font-weight:700; color:var(--mc-ink); }
.basic_form .mc-copy{ position:absolute; top:14px; right:14px; border:1px solid var(--mc-accent); background:var(--mc-soft);
  color:var(--mc-accent); border-radius:9px; padding:6px 12px; font-size:11.5px; font-weight:800; cursor:pointer; }
.basic_form .mc-note{ display:block; margin-top:9px; padding:9px 11px; background:#f8fafc; border-radius:9px;
  font-size:12px; color:var(--mc-ink2); line-height:1.55; }

/* 지도 */
.map_wrap{ background:var(--mc-card); border-radius:14px; box-shadow:var(--mc-shadow); margin:12px; padding:16px 18px; }
.map_wrap .map_tit{ display:block; font-size:14.5px; font-weight:800; margin-bottom:8px; }
.map_wrap .map_con{ display:block; font-size:13px; color:var(--mc-ink2); margin-bottom:6px; }
.map_wrap .q_wrap{ margin-bottom:12px; display:flex; gap:6px; }
.map_wrap .q_campain,.map_wrap .go_note{ display:inline-block; padding:7px 13px; border-radius:8px; font-size:12px; font-weight:700; text-decoration:none; }
.map_wrap .q_campain{ background:var(--mc-accent); color:#fff; } .map_wrap .go_note{ background:#eef2f6; color:#475569; }
#map{ width:100%; height:230px; border-radius:12px; overflow:hidden; margin-top:8px; }

/* 추천 캠페인 */
.other_item_wrap{ background:#fff; margin:12px; border-radius:16px; box-shadow:var(--mc-shadow); padding:18px; }
.other_item_wrap .title_wrap{ font-size:16px; font-weight:800; margin-bottom:12px; }
.other_item_wrap .point-color1{ color:var(--mc-accent); }
.swiper-container-b .swiper-slide img{ width:100%; border-radius:10px; }
.swiper-container-b .swiper-slide .cp_subject{ display:block; font-size:12px; color:var(--mc-ink); margin:5px 0 2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.swiper-container-b .swiper-slide .cp_description{ display:block; font-size:11px; color:var(--mc-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

/* 하단 플로팅 신청바 */
.campaign_open_btn_wrap{ position:fixed; left:0; right:0; bottom:0; z-index:60; background:#fff;
  border-top:1px solid var(--mc-line); box-shadow:0 -4px 18px rgba(15,23,42,.08); padding:10px 14px calc(10px + env(safe-area-inset-bottom)); }
.btn_campaign_wrap{ display:flex; gap:10px; max-width:480px; margin:0 auto; align-items:stretch; }
.btn_campaign_zzim{ flex:0 0 58px; border:1px solid var(--mc-line); border-radius:13px; background:#fff;
  display:flex; flex-direction:column; align-items:center; justify-content:center; gap:2px; cursor:pointer;
  font-size:0; color:var(--mc-ink2); text-decoration:none; position:relative; }
.btn_campaign_zzim:before{ content:"♡"; font-size:22px; line-height:1; }
.btn_campaign_zzim:after{ content:"찜"; font-size:10px; }
.btn_campaign_zzim.on{ border-color:var(--mc-red); color:var(--mc-red); background:#fff5f5; }
.btn_campaign_zzim.on:before{ content:"♥"; color:var(--mc-red); }
.btn_campaign_order{ flex:1; border:0; border-radius:13px; background:var(--mc-accent); color:#fff;
  font-size:16.5px; font-weight:800; letter-spacing:-.02em; display:flex; align-items:center; justify-content:center;
  cursor:pointer; text-decoration:none; box-shadow:0 6px 16px rgba(13,148,136,.32); }
.btn_campaign_order:active{ transform:translateY(1px); }

/* 공유 팝업(유지) */
#sns_popup{ display:none; position:fixed; inset:0; background:rgba(15,23,42,.45); z-index:100; }
#sns_popup .sns_wrap{ position:absolute; left:0; right:0; bottom:0; background:#fff; border-radius:18px 18px 0 0; padding:18px; }
#sns_popup .sns_title{ font-size:16px; font-weight:800; margin-bottom:14px; display:flex; justify-content:space-between; }
#sns_popup .sns_close_btn:after{ content:"✕"; cursor:pointer; color:var(--mc-muted); }
#sns_popup .sns_list{ list-style:none; margin:0; padding:0; display:grid; grid-template-columns:repeat(4,1fr); gap:14px 6px; text-align:center; }
#sns_popup .sns_list a{ font-size:12px; color:var(--mc-ink2); text-decoration:none; }

@media (min-width:760px){
  .mc-cd{ max-width:680px; margin:0 auto; }
}
</style>

<div class="mc-cd">

	<!-- HERO -->
	<div class="mc-hero">
		<img src="<?=$campaign[cp_img]?>" alt="<?=$campaign[cp_subject]?>" class="it_img">
		<?php $d_left = ceil((strtotime($campaign[cp_edatetime]) - time())/86400); if($d_left>=0){ ?>
		<span class="mc-dday"><?php if($d_left==0){ ?>오늘 마감<?php } else{ ?>D-<?=$d_left?><?php } ?></span>
		<?php } ?>
	</div>

	<!-- 헤더 카드 -->
	<div class="mc-card mc-head-card">
		<div class="mc-badges iteminfo_top">
			<?php if($campaign[cp_media_blog]){ ?><span class="blog">네이버 블로그</span><?php } ?>
			<?php if($campaign[cp_media_instagram]){ ?><span class="instagram">인스타그램</span><?php } ?>
			<?php if($campaign[cp_media_youtube]){ ?><span class="youtube">유튜브</span><?php } ?>
			<?php if($campaign[cp_media_shop]){ ?><span class="shop">네이버 쇼핑</span><?php } ?>
			<?php if($campaign[cp_check1]){ ?><span class="gumepyung">구매평</span><?php } ?>
			<?php if($campaign[cp_check2]){ ?><span class="payback">페이백</span><?php } ?>
			<?php if($campaign[cp_ohouse]){ ?><span class="cp_ohouse">오늘의집</span><?php } ?>
			<?php if($campaign[cp_coupang]){ ?><span class="cp_coupang">쿠팡</span><?php } ?>
			<?php if($campaign[cp_media_receipt]){ ?><span class="cp_media_receipt">영수증</span><?php } ?>
			<?php if($campaign[cp_media_carrot]){ ?><span class="cp_media_carrot">당근</span><?php } ?>
			<?php if($campaign[cp_type_text] == "배송형"){ ?><span class="nor_box">배송형</span>
			<?php } else if($campaign[cp_type_text] == "방문형"){ ?><span class="nor_box">방문형</span>
			<?php } else if($campaign[cp_type_text] == "배송형+리워드"){ ?><span class="nor_box">배송형</span> <span class="nor_box">리워드</span>
			<?php } else if($campaign[cp_type_text] == "방문형+리워드"){ ?><span class="nor_box">방문형</span> <span class="nor_box">리워드</span>
			<?php } ?>
			<?php if($campaign[cp_point]){ ?><span class="point_color4 txt_num">+<?=$campaign[cp_point]?>P</span><?php } ?>
		</div>
		<h1 class="itname"><?=$campaign[cp_subject]?></h1>
		<p class="it_description"><?=$campaign[cp_description]?></p>

		<div class="mc-metrics">
			<div class="mc-apply-row">
				<span class="lab">모집 현황</span>
				<span class="val"><b class="txt_num"><?=number_format($campaign[cp_order])?></b>명 신청 / 모집 <b class="txt_num" style="color:inherit;font-size:14px;"><?=number_format($campaign[cp_recruit])?></b>명</span>
			</div>
			<?php $rate = $campaign[cp_recruit]>0 ? round($campaign[cp_order]/$campaign[cp_recruit]*100) : 0; ?>
			<div class="mc-progress"><i style="width:<?=min(100,$rate)?>%"></i></div>
			<div class="mc-progress-sub">모집 인원의 <b class="txt_num"><?=$rate?>%</b> 신청<?php if($rate>=100){ ?> 🔥<?php } ?></div>

			<div class="mc-countdown">
				<span class="cl">신청 마감까지</span>
				<?php
				$gap = strtotime($campaign[cp_edatetime])-time();
				if($gap>0){
					sscanf(date('His',$gap-32400),'%2d%2d%2d',$h,$i,$s);
					$d = floor($gap/86400);
				?>
				<script type="text/javascript">
				$(function (){
				var austDay = new Date(<?=strtotime($campaign[cp_edatetime])*1000?>);
				$('#countdown').countdown({until: austDay,  layout: "<?php if($d){ ?><b class='txt_num'><?=$d?></b>일<?php } ?> <b class='txt_num'>{hnn}</b>시간 <b class='txt_num'>{mnn}</b>분 <b class='txt_num'>{snn}</b>초" });
				});
				</script>
				<div id="countdown">
					<?php if($d){ ?><b class="txt_num"><?=$d?></b>일 <?php } ?>
					<b class="txt_num"><?=sprintf("%02d",$h)?></b>시간
					<b class="txt_num"><?=sprintf("%02d",$i)?></b>분
					<b class="txt_num"><?=sprintf("%02d",$s)?></b>초
				</div>
				<?php } else{ ?>
				<div id="countdown" style="font-weight:800;color:#dc2626;">접수 마감</div>
				<?php } ?>
			</div>
		</div>
	</div>

	<!-- 일정 -->
	<div class="mc-card">
		<div class="mc-sec-tit">캠페인 일정</div>
		<div class="review_step_wrap">
			<span class="step"><em class="point_color4">리뷰어 신청기간</em> <b class="txt_num point_color4"><?=date("m.d",strtotime($campaign[cp_sdatetime]))?>(<?=yoil($campaign[cp_sdatetime])?>) ~ <?=date("m.d",strtotime($campaign[cp_edatetime]))?>(<?=yoil($campaign[cp_edatetime])?>)</b></span>
			<span class="step"><em>선정자 발표</em> <b class="txt_num"><?=date("m.d",strtotime($campaign[cp_pick_datetime]))?>(<?=yoil($campaign[cp_pick_datetime])?>)</b></span>
			<span class="step"><em>리뷰 등록기간</em> <b class="txt_num"><?=date("m.d",strtotime($campaign[cp_contents_sdatetime]))?>(<?=yoil($campaign[cp_contents_sdatetime])?>) ~ <?=date("m.d",strtotime($campaign[cp_contents_edatetime]))?>(<?=yoil($campaign[cp_contents_edatetime])?>)</b></span>
			<?php if($campaign[my_ext_edatetime]){ ?><span class="step" style="background:#fff7e6;border-radius:8px;padding-left:8px;padding-right:8px"><em style="color:#c8780a">📅 내 리뷰 등록 마감</em> <b class="txt_num" style="color:#c8780a"><?=date("m.d",strtotime($campaign[my_ext_edatetime]))?>(<?=yoil($campaign[my_ext_edatetime])?>) <span style="font-size:10px;background:#fff;border:1px solid #ffcf8a;border-radius:999px;padding:1px 6px;font-weight:800;color:#c8780a">연장됨</span></b></span><?php } ?>
			<span class="step"><em>캠페인 결과발표</em> <b class="txt_num"><?=date("m.d",strtotime($campaign[cp_result_datetime]))?>(<?=yoil($campaign[cp_result_datetime])?>)</b></span>
		</div>
	</div>

	<!-- 탭 -->
	<div class="gond_item"></div>
	<nav class="nav_tabmenu">
		<ul class="tabmenu">
			<li class="active"><div><a href="#tab1">캠페인정보</a></div></li>
			<li><div><a href="#tab2">신청자 한마디<span class="txt_num"><?=$campaign[cp_order_text]?></span></a></div></li>
			<?php if($config[cf_realtime_review_use]=="1"){ ?>
			<li><div><a href="#tab3">리뷰<span class="txt_num"><?=$campaign[cp_review_text]?></span></a></div></li>
			<?php } ?>
		</ul>
		<a class="sns_popup_open_btn"></a>
	</nav>

	<div id="tab1" class="tab-cont">
		<div class="mc-card">
			<div class="mc-sec-tit">캠페인 정보</div>
			<div class="cp_memo_wrap overflowhidden"><?=$campaign[cp_memo]?></div>
			<a class="campaign_memo_more">+ 더보기</a>
		</div>

		<div class="basic_form">
			<ul>
				<li class="mc-reward">
					<span class="tit_basic">🎁 제공 내역</span>
					<span class="content"><?=$campaign[cp_reward]?></span>
					<span class="mc-note" style="color:#b91c1c;">※ 제공 서비스 외 추가 비용은 자부담입니다.</span>
				</li>
				<li>
					<span class="tit_basic">키워드</span>
					<button type="button" class="mc-copy" data-copy-target="mc-kw">복사</button>
					<span class="content" id="mc-kw"><?=$campaign[cp_keyword]?></span>
					<span class="mc-note" style="color:#15803d;">블로그 참여자는 필수키워드를 제목에 1회, 본문에 5회 이상 서브키워드로 기재해주세요.</span>
				</li>
				<li>
					<span class="tit_basic">해시태그</span>
					<button type="button" class="mc-copy" data-copy-target="mc-hash">복사</button>
					<span class="content" id="mc-hash"><?=$campaign[cp_hashtag]?></span>
					<span class="mc-note" style="color:#be185d;">인스타그램 참여자는 #협찬 #메타체험단 태그와 안내된 해시태그를 포함하여 업로드해주세요.</span>
				</li>
				<li>
					<span class="tit_basic">리뷰 미션</span>
					<span class="content"><?=$campaign[cp_guide_add]?></span>
				</li>
				<li>
					<span class="tit_basic">추가 안내</span>
					<span class="content"><?=$campaign[cp_guide]?></span>
				</li>
				<li>
					<span class="tit_basic">유의 사항</span>
					<span class="content"><?=$campaign[cp_notice]?></span>
				</li>
			</ul>
		</div>

		<!-- 지도(방문형) -->
		<?php if($campaign[cp_lat] and $campaign[cp_lng] and $campaign[cp_type]=="2" and $campaign[cp_map_use]=="1"){ ?>
		<div class="map_wrap">
			<div class="q_wrap">
				<?php if($member[mb_no]){ ?>
				<a href="campaign_qna_form.php?cp_id=<?=$campaign[cp_id]?>" class="q_campain">캠페인 문의하기</a><?php if($campaign[cp_supply_no]){ ?><a href="note_form.php?no_receive_id=<?=$campaign[cp_supply_no]?>" class="go_note">쪽지보내기</a><?php } ?>
				<?php } ?>
			</div>
			<span class="map_tit">업체 정보</span>
			<span class="map_con">📍 <?=$campaign[cp_addr1]?> <?=$campaign[cp_addr2]?><br>☎ <?=$campaign[cp_tel]?></span>
			<div id="map"></div>
		</div>
		<?php } ?>
	</div>

	<div id="tab2" class="tab-cont"><?php include_once "campaign_order_list.php"; ?></div>
	<?php if($config[cf_realtime_review_use]=="1"){ ?>
	<div id="tab3" class="tab-cont"><?php include_once "campaign_review_list.php"; ?></div>
	<?php } ?>

	<?php if(count($return[etc_list])){ ?>
	<div class="other_item_wrap">
		<div class="title_wrap"><b class="point-color1">이런</b> 캠페인 어때요?</div>
		<div class="swiper-container swiper-container-b">
			<div class="swiper-wrapper">
				<?php for($i=0; $i<count($return[etc_list]); $i++){ $row = $return[etc_list][$i]; ?>
				<div class="swiper-slide">
					<a href="campaign.php?cp_id=<?=$row[cp_id]?>">
						<img src="<?=$row[cp_img]?>">
						<p class="cp_subject"><?=$row[cp_subject]?></p>
						<b class="cp_description"><?=$row[cp_description]?></b>
					</a>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php } ?>

</div>

<!-- 하단 플로팅 신청바 (기능 클래스 유지) -->
<div class="campaign_open_btn_wrap">
	<div class="btn_campaign_wrap">
		<a class="btn_campaign_zzim <?=$campaign[cp_zzim_is]?"on":""?>" data-cp_id="<?=$campaign[cp_id]?>">스크랩하기</a>
		<a class="btn_campaign_order">리뷰어 신청하기</a>
	</div>
</div>

<!-- 공유하기팝업 -->
<div id="sns_popup">
	<div class="sns_wrap">
		<div class="sns_title">공유하기 <a class="sns_close_btn"></a></div>
		<ul class="sns_list">
			<li><a href="javascript:sns_link('kakaotalk')"><i class="btn_kakaotalk"></i>카카오톡</a></li>
			<li><a href="javascript:sns_link('kakaostory')"><i class="btn_kakaostory"></i>카카오스토리</a></li>
			<li><a href="javascript:sns_link('twitter')"><i class="btn_twitter"></i>트위터</a></li>
			<li><a href="javascript:sns_link('facebook')"><i class="btn_facebook"></i>페이스북</a></li>
			<li><a href="javascript:sns_link('naver')"><i class="btn_cafe"></i>네이버공유</a></li>
			<li><a href="javascript:sns_link('copy')"><i class="btn_sms"></i>주소복사</a></li>
			<li><a href="javascript:sns_link('naverblog')"><i class="btn_blog"></i>링크공유</a></li>
			<li><a href="javascript:sns_link('naverline')"><i class="btn_line"></i>라인</a></li>
		</ul>
	</div>
</div>

<script type="text/javascript">
<!--
var cp_id = "<?=$campaign[cp_id]?>";
var sns_title = "<?=$campaign[cp_subject]?>";
var sns_description = "<?=$campaign[cp_description]?>";
var sns_url = "<?=$campaign[cp_url]?>";
var sns_img = "<?=$campaign[cp_img_url]?>";

$(document).on("click", ".campaign_memo_more", function(){
	if($(".cp_memo_wrap").hasClass("overflowhidden")){
		$(".cp_memo_wrap").removeClass("overflowhidden");
		$(this).html("- 화면접기");
	} else{
		$(".cp_memo_wrap").addClass("overflowhidden");
		$(this).html("+ 더보기");
	}
});

/* 키워드/해시태그 복사 */
$(document).on("click", ".mc-copy", function(){
	var t = document.getElementById($(this).data("copy-target"));
	if(!t) return;
	var txt = t.innerText || t.textContent;
	var btn = $(this);
	function done(){ btn.text("복사됨 ✓"); setTimeout(function(){ btn.text("복사"); }, 1500); }
	if(navigator.clipboard && navigator.clipboard.writeText){ navigator.clipboard.writeText(txt).then(done, function(){}); }
	else{ var ta=document.createElement('textarea'); ta.value=txt; document.body.appendChild(ta); ta.select(); try{document.execCommand('copy');}catch(e){} document.body.removeChild(ta); done(); }
});

$(document).on("click", ".btn_campaign_order", function(){
	<?php if($error[msg]){ ?>
	alert("<?=$error[msg]?>");
	<?php if($error[url]){ ?>
	location.href="<?=$error[url]?>";
	<?php } ?>
	<?php } else{ ?>
	location.href="review_order.php?cp_id=<?=$campaign[cp_id]?>";
	<?php } ?>
});

$(document).ready(function() {
	var swiperB = new Swiper('.swiper-container-b', {
		freeMode: true,
		spaceBetween: 10,
		slidesPerView: 4
	});

	$(".tabmenu li").click(function(){
		$(".tabmenu li").removeClass("active");
		$(this).addClass("active");
		$(".tab-cont").hide();
		$($(this).find("a").attr("href")).show();
		return false;
	});
	var swiper = new Swiper('.swiper-container-a', {
		pagination: '.swiper-pagination-a',
		slidesPerView: 1,
		paginationClickable: true,
		loop: true
	});
});

$(document).on("click", ".sns_popup_open_btn", function(){
	$("#sns_popup").show();
});
$(document).on("click", ".sns_close_btn", function(){
	$('#sns_popup').hide();
});

$(".btn_campaign_zzim").on("click", function(){
	var cp_id = $(this).data("cp_id");
	var campaign_zzim_btn = $(this);
	$.ajax({
		type: "post",
		data : "mode=zzim&cp_id="+cp_id,
		url: "campaign.php",
		success: function(response){
			var json = $.parseJSON(response);
			if(json["result"]=="login"){
				location.replace("login.php?url=campaign.php?cp_id="+cp_id);
			} else if(json["result"]=="delete"){
				campaign_zzim_btn.removeClass("on").addClass('off');
			} else if(json["result"]=="insert"){
				campaign_zzim_btn.removeClass("off").addClass('on');
			} else{
				alert(json["msg"]);
			}
		}
	});
});

<?php if($campaign[cp_lat] and $campaign[cp_lng] and $campaign[cp_type]=="2" and $campaign[cp_map_use]=="1"){ ?>
var cp_lat = "<?=$campaign[cp_lat]?>";
var cp_lng = "<?=$campaign[cp_lng]?>";
var mapContainer = document.getElementById('map'),
    mapOption = { center: new daum.maps.LatLng(cp_lat, cp_lng), level: 3 };
var map = new daum.maps.Map(mapContainer, mapOption);
var markerPosition  = new daum.maps.LatLng(cp_lat, cp_lng);
var marker = new daum.maps.Marker({ position: markerPosition });
var mapTypeControl = new daum.maps.MapTypeControl();
map.addControl(mapTypeControl, daum.maps.ControlPosition.TOPRIGHT);
var zoomControl = new daum.maps.ZoomControl();
map.addControl(zoomControl, daum.maps.ControlPosition.RIGHT);
marker.setMap(map);
<?php } ?>
//-->
</script>

<?php
include_once $nfor[skin_path]."tail.php";
?>
