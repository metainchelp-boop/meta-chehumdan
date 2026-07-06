<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
/* =========================================================================
   캠페인 상세 — 모던 리뉴얼 (Toss/29CM 톤) 2026-06-08 / PC(demo) · 2단 그리드
   기능 클래스/JS 훅 전부 유지: #btn_order, .campaign_zzim_btn, #countdown,
   .tabmenu[data-tab], .tab-cont(#tab1~3), .cp_memo_wrap/.campaign_memo_more,
   .key_copy(ClipboardJS,#cp_keyword/#cp_hashtag), #map, .swiper-container-b,
   .sns_popup_open_btn/#sns_popup/.cp_url_copy_btn, .floating(stick_in_parent),
   .menu_list/.nfor_scroll(#nfor_pageX), 우측 include inc_right_floating.php
   ========================================================================= */
:root{
  --mc-bg:#f3f4f6; --mc-card:#fff; --mc-line:#edf0f3; --mc-line2:#f1f3f5;
  --mc-ink:#0f172a; --mc-ink2:#4b5563; --mc-muted:#9ca3af;
  --mc-accent:#0d9488; --mc-accent2:#14b8a6; --mc-soft:#effbf9; --mc-purple:#8b5cf6; --mc-red:#dc2626;
  --mc-shadow:0 1px 2px rgba(15,23,42,.04),0 6px 18px rgba(15,23,42,.06);
}
.mc-cd{ background:var(--mc-bg); color:var(--mc-ink); line-height:1.6; padding:22px 0 50px;
  font-family:-apple-system,BlinkMacSystemFont,"Apple SD Gothic Neo","Pretendard","Malgun Gothic",sans-serif; -webkit-font-smoothing:antialiased; }
.mc-cd .num,.mc-cd .txt_num{ font-variant-numeric:tabular-nums; }
.mc-cd img{ max-width:100%; }
.mc-grid{ display:grid; grid-template-columns:minmax(0,1fr) 360px; gap:24px; align-items:start; max-width:1180px; margin:0 auto; padding:0 18px; }
.mc-main{ min-width:0; }

/* 카드 공통 */
.mc-card{ background:var(--mc-card); border-radius:16px; box-shadow:var(--mc-shadow); margin-bottom:16px; padding:22px; }
.mc-sec-tit{ display:flex; align-items:center; gap:8px; font-size:17px; font-weight:800; letter-spacing:-.02em; margin:0 0 14px; }
.mc-sec-tit:before{ content:""; width:4px; height:17px; background:var(--mc-accent); border-radius:2px; }

/* HERO */
.mc-hero{ position:relative; border-radius:16px; overflow:hidden; box-shadow:var(--mc-shadow); margin-bottom:16px; background:#e9edf2; }
.mc-hero .it_img{ width:100%; display:block; aspect-ratio:16/10; object-fit:cover; }
.mc-hero .mc-dday{ position:absolute; left:14px; top:14px; background:rgba(15,23,42,.78); color:#fff; font-size:13px; font-weight:800; padding:7px 13px; border-radius:999px; }

/* 배지 */
.iteminfo_top{ display:flex; flex-wrap:wrap; gap:6px; align-items:center; margin-bottom:14px; }
.iteminfo_top span{ display:inline-flex; align-items:center; height:24px; padding:0 11px; border-radius:999px; font-size:12px; font-weight:700; line-height:1; letter-spacing:-.02em; }
.iteminfo_top .blog{ background:#e7f6ee; color:#16a34a; }
.iteminfo_top .instagram{ background:#fde7f3; color:#c026a3; }
.iteminfo_top .youtube{ background:#fee2e2; color:#dc2626; }
.iteminfo_top .shop{ background:#fef9c3; color:#a16207; }
.iteminfo_top .gumepyung{ background:#fef9c3; color:#a16207; }
.iteminfo_top .payback{ background:#fee2e2; color:#dc2626; }
.iteminfo_top .cp_ohouse{ background:#e0f2fe; color:#0284c7; }
.iteminfo_top .cp_coupang{ background:#fee2e2; color:#d84d32; }
.iteminfo_top .cp_media_carrot{ background:#ffedd5; color:#ea580c; }
.iteminfo_top .nor_box{ background:#eef2f6; color:#475569; }
.iteminfo_top .point_color4{ background:var(--mc-purple); color:#fff; }
.iteminfo_top .sns_popup_open_btn{ margin-left:auto; height:auto; padding:6px 12px; border:1px solid var(--mc-line); border-radius:999px; background:#fff; color:var(--mc-ink2); font-size:12.5px; font-weight:700; cursor:pointer; position:relative; }

.mc-main .itname{ font-size:24px; font-weight:800; letter-spacing:-.03em; line-height:1.4; margin:0 0 8px; }
.mc-main .it_description{ font-size:14.5px; color:var(--mc-muted); margin:0 0 4px; }

/* 탭 */
.item_detail{ margin-top:4px; }
.tab-wrap{ display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid var(--mc-line); background:#fff; border-radius:14px 14px 0 0; padding:0 8px; }
.tab-wrap .tabmenu{ display:flex; list-style:none; margin:0; padding:0; }
.tab-wrap .tabmenu li{ }
.tab-wrap .tabmenu li a{ display:block; padding:16px 18px; font-size:15px; font-weight:700; color:var(--mc-muted); cursor:pointer; border-bottom:2.5px solid transparent; text-decoration:none; }
.tab-wrap .tabmenu li.active a{ color:var(--mc-accent); border-bottom-color:var(--mc-accent); }
.tab-wrap .tabmenu li a .txt_num{ color:var(--mc-accent); margin-left:2px; }
.tab-wrap .q_wrap{ display:flex; gap:6px; padding-right:6px; }
.tab-wrap .q_wrap a{ font-size:12.5px; font-weight:700; padding:7px 13px; border-radius:8px; text-decoration:none; }
.tab-wrap .q_wrap .q_campain{ background:var(--mc-accent); color:#fff; } .tab-wrap .q_wrap .go_note{ background:#eef2f6; color:#475569; }
.deals_container{ background:#fff; border-radius:0 0 14px 14px; padding:22px; box-shadow:var(--mc-shadow); }
.tab-cont{ display:none; } #tab1.tab-cont{ display:block; }

/* 캠페인 정보 본문 */
.cp_memo_wrap{ font-size:14.5px; color:var(--mc-ink2); }
.cp_memo_wrap img{ border-radius:10px; }
.cp_memo_wrap.overflowhidden{ max-height:760px; overflow:hidden; -webkit-mask-image:linear-gradient(#000 82%,transparent); mask-image:linear-gradient(#000 82%,transparent); }
.campaign_memo_more{ display:block; border:1px solid var(--mc-line); border-radius:10px; padding:13px; text-align:center; font-size:14px; font-weight:700; color:var(--mc-ink2); margin-top:14px; cursor:pointer; }

/* 제공/키워드/해시/미션 카드 */
.basic_form ul{ list-style:none; margin:24px 0 0; padding:0; }
.basic_form li{ border:1px solid var(--mc-line); border-radius:14px; margin-bottom:14px; padding:18px 20px; position:relative; }
.basic_form li#nfor_page2{ background:linear-gradient(180deg,#f0fdfa,#fff); border-color:#cdebe6; }
.basic_form .tit_basic{ display:flex; align-items:center; gap:8px; font-size:15.5px; font-weight:800; margin-bottom:10px; }
.basic_form .tit_basic:before{ content:""; width:4px; height:15px; background:var(--mc-accent); border-radius:2px; }
.basic_form li#nfor_page2 .tit_basic{ font-size:17px; }
.basic_form .content{ display:block; font-size:14px; color:var(--mc-ink2); word-break:break-word; }
.basic_form li#nfor_page2 > .content:first-of-type{ font-size:15.5px; font-weight:700; color:var(--mc-ink); }
.basic_form .key_copy{ position:absolute; top:18px; right:20px; margin:0; border:1px solid var(--mc-accent); background:var(--mc-soft); color:var(--mc-accent); border-radius:9px; padding:7px 13px; font-size:12px; font-weight:800; cursor:pointer; width:auto; }
#cp_keyword,#cp_hashtag{ border:0; width:1px; height:1px; padding:0; opacity:0; position:absolute; }

/* 지도 */
.map_wrap{ border:1px solid var(--mc-line); border-radius:14px; padding:18px 20px; margin-top:14px; }
.map_wrap .map_tit{ display:block; font-size:15px; font-weight:800; margin-bottom:6px; }
.map_wrap .map_con{ display:block; font-size:13.5px; color:var(--mc-ink2); margin-bottom:10px; }
.map_wrap .map_con b{ display:block; }
#map{ width:100%; height:260px; border-radius:12px; overflow:hidden; }

/* 우측 Sticky 신청 카드 (inc_right_floating.php) */
.iteminfo_right_fix{ position:sticky; top:16px; align-self:start; }
.iteminfo_right_fix .floating{ width:360px; }
.iteminfo_right_fix .mc-applycard{ background:var(--mc-card); border-radius:16px; box-shadow:var(--mc-shadow); padding:22px; }
.iteminfo_right_fix .thumb{ display:none !important; }
.iteminfo_right_fix .itname{ font-size:18px; font-weight:800; letter-spacing:-.02em; line-height:1.45; margin:0 0 6px; }
.iteminfo_right_fix .it_description{ font-size:13px; color:var(--mc-muted); margin:0 0 14px; }
.iteminfo_right_fix .item_num{ display:block; font-size:14px; font-weight:700; color:var(--mc-ink2); margin-bottom:8px; }
.iteminfo_right_fix .item_num b{ color:var(--mc-accent); font-size:18px; }
.mc-progress{ height:8px; background:#eef1f5; border-radius:999px; overflow:hidden; }
.mc-progress i{ display:block; height:100%; background:linear-gradient(90deg,var(--mc-accent),var(--mc-accent2)); border-radius:999px; }
.mc-progress-sub{ font-size:11.5px; color:var(--mc-muted); text-align:right; margin:5px 0 0; }
.mc-progress-sub b{ color:var(--mc-accent); }
.mc-countdown{ margin-top:14px; background:var(--mc-soft); border:1px solid #cdebe6; border-radius:12px; padding:12px; text-align:center; }
.mc-countdown .cl{ display:block; font-size:12px; color:#0f766e; font-weight:800; margin-bottom:5px; }
.mc-countdown #countdown{ color:#0f766e; font-size:13px; font-weight:700; }
.mc-countdown #countdown b{ background:#fff; border:1px solid #b9e8e0; border-radius:7px; padding:3px 7px; margin:0 1px; font-size:15px; font-weight:800; color:#0d9488; }
.iteminfo_right_fix .review_step_wrap{ margin-top:16px; border-top:1px solid var(--mc-line); padding-top:6px; }
.iteminfo_right_fix .review_step_wrap .step{ display:flex; align-items:center; justify-content:space-between; gap:10px; padding:11px 0; border-bottom:1px solid var(--mc-line2); }
.iteminfo_right_fix .review_step_wrap .step:last-child{ border-bottom:0; }
.iteminfo_right_fix .review_step_wrap .step em{ font-style:normal; font-size:12.5px; font-weight:600; color:var(--mc-ink2); }
.iteminfo_right_fix .review_step_wrap .step em.point_color4{ color:var(--mc-accent); font-weight:800; }
.iteminfo_right_fix .review_step_wrap .step b{ font-size:12.5px; font-weight:800; }
.iteminfo_right_fix .review_step_wrap .step b.point_color4{ color:var(--mc-accent); }
.iteminfo_right_fix .menu_list{ margin-top:14px; display:flex; flex-wrap:wrap; gap:6px; }
.iteminfo_right_fix .menu_list a{ font-size:12px; font-weight:600; color:var(--mc-ink2); background:#f3f4f6; border-radius:999px; padding:6px 12px; text-decoration:none; }
.iteminfo_right_fix .menu_list a:hover{ background:#e5e7eb; }
.iteminfo_right_fix .order_bottom{ margin-top:16px; }
.iteminfo_right_fix .btn_zone_floatings ul{ display:flex; gap:10px; list-style:none; margin:0; padding:0; }
.iteminfo_right_fix .btn_zone_floatings li:first-child{ flex:1; }
.iteminfo_right_fix .review_req{ display:flex; align-items:center; justify-content:center; height:54px; border-radius:13px; background:var(--mc-accent); color:#fff; font-size:17px; font-weight:800; cursor:pointer; text-decoration:none; box-shadow:0 6px 16px rgba(13,148,136,.3); }
.iteminfo_right_fix .review_req:hover{ background:#0b8378; }
.iteminfo_right_fix .scrap_btn{ display:flex; flex-direction:column; align-items:center; justify-content:center; width:60px; height:54px; border:1px solid var(--mc-line); border-radius:13px; background:#fff; color:var(--mc-ink2); font-size:0; cursor:pointer; text-decoration:none; }
.iteminfo_right_fix .scrap_btn:before{ content:"♡"; font-size:22px; } .iteminfo_right_fix .scrap_btn:after{ content:"찜"; font-size:10px; margin-top:1px; }
.iteminfo_right_fix .scrap_btn.on{ border-color:var(--mc-red); color:var(--mc-red); background:#fff5f5; }
.iteminfo_right_fix .scrap_btn.on:before{ content:"♥"; color:var(--mc-red); }

/* 추천 캠페인 */
.other_item_wrap{ max-width:1180px; margin:16px auto 0; padding:0 18px; }
.other_item_wrap .title_wrap{ display:flex; justify-content:space-between; align-items:center; font-size:18px; font-weight:800; margin-bottom:14px; }
.other_item_wrap .point_color4,.other_item_wrap .point-color1{ color:var(--mc-accent); }
.other_item_wrap .right a{ font-size:13px; color:var(--mc-muted); cursor:pointer; margin-left:8px; }
.swiper-container-b .swiper-slide img{ width:100%; border-radius:12px; }
.swiper-container-b .swiper-slide .cp_subject{ display:block; font-size:14px; color:var(--mc-ink); margin:7px 0 2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.swiper-container-b .swiper-slide .cp_description{ display:block; font-size:12px; color:var(--mc-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.swiper-container-b .swiper-slide .cp_day{ display:block; font-size:12px; color:var(--mc-accent); font-weight:700; margin-top:7px; }

/* 공유 팝업(유지) */
.sns_popup{ position:absolute; top:38px; right:0; left:auto; width:230px; z-index:1000; border:1px solid var(--mc-line); border-radius:12px; background:#fff; box-shadow:var(--mc-shadow); padding:14px; }
.sns_popup.hide{ display:none; }
.sns_popup .sns_popup_title{ font-size:14px; font-weight:800; margin-bottom:10px; }
.sns_popup .sns_type{ list-style:none; display:flex; gap:10px; padding:0; margin:0 0 10px; }
.sns_popup .sns_url{ display:flex; gap:6px; }
.sns_popup .cp_url_copy_btn{ display:inline-flex; align-items:center; padding:0 12px; background:var(--mc-accent); color:#fff; border-radius:8px; font-size:12px; cursor:pointer; }
.sns_popup_close_btn{ position:absolute; right:10px; top:12px; cursor:pointer; color:var(--mc-muted); }
.sns_popup_close_btn:after{ content:"✕"; }

@media (max-width:980px){
  .mc-grid{ grid-template-columns:1fr; }
  .iteminfo_right_fix{ position:static; }
  .iteminfo_right_fix .floating{ width:auto; }
}
</style>

<div class="mc-cd">
<div class="mc-grid">

	<!-- ===== 좌측 메인 ===== -->
	<div class="mc-main">

		<!-- 히어로 -->
		<div class="mc-hero">
			<img src="<?=$campaign[cp_img]?>" alt="<?=$campaign[cp_subject]?>" class="it_img">
			<?php $d_left = ceil((strtotime($campaign[cp_edatetime]) - time())/86400); if($d_left>=0){ ?>
			<span class="mc-dday"><?php if($d_left==0){ ?>오늘 마감<?php } else{ ?>D-<?=$d_left?><?php } ?></span>
			<?php } ?>
		</div>

		<!-- 헤더(배지/제목) -->
		<div class="mc-card">
			<div class="iteminfo_top">
				<?php if($campaign[cp_media_blog]){ ?><span class="blog">네이버 블로그</span><?php } ?>
				<?php if($campaign[cp_media_instagram]){ ?><span class="instagram">인스타그램</span><?php } ?>
				<?php if($campaign[cp_media_youtube]){ ?><span class="youtube">유튜브</span><?php } ?>
				<?php if($campaign[cp_media_shop]){ ?><span class="shop">네이버 쇼핑</span><?php } ?>
				<?php if($campaign[cp_check1]){ ?><span class="gumepyung">구매평</span><?php } ?>
				<?php if($campaign[cp_check2]){ ?><span class="payback">페이백</span><?php } ?>
				<?php if($campaign[cp_ohouse]){ ?><span class="cp_ohouse">오늘의집</span><?php } ?>
				<?php if($campaign[cp_coupang]){ ?><span class="cp_coupang">쿠팡</span><?php } ?>
				<?php if($campaign[cp_media_receipt]){ ?><span class="cp_coupang">영수증</span><?php } ?>
				<?php if($campaign[cp_media_carrot]){ ?><span class="cp_media_carrot">당근</span><?php } ?>
				<?php if($campaign[cp_type_text] == "배송형"){ ?><span class="nor_box">배송형</span>
				<?php } else if($campaign[cp_type_text] == "방문형"){ ?><span class="nor_box">방문형</span>
				<?php } else if($campaign[cp_type_text] == "배송형+리워드"){ ?><span class="nor_box">배송형</span> <span class="nor_box">리워드</span>
				<?php } else if($campaign[cp_type_text] == "방문형+리워드"){ ?><span class="nor_box">방문형</span> <span class="nor_box">리워드</span>
				<?php } ?>
				<?php if($campaign[cp_point]){ ?><span class="point_color4 txt_num">+<?=$campaign[cp_point]?>P</span><?php } ?>
				<div class="sns_popup_open_btn"><i></i> 공유하기</div>
				<!-- 공유하기 팝업 -->
				<div id="sns_popup" class="sns_popup hide">
					<h3 class="sns_popup_title">공유하기</h3>
					<div class="sns_popup_contents">
						<ul class="sns_type">
							<li><a href="javascript:sns_link('naver')" class="blog" title="네이버 공유">N</a></li>
							<li><a href="javascript:sns_link('facebook')" class="facebook" title="페이스북 공유">f</a></li>
							<li><a href="javascript:sns_link('twitter')" class="twitter" title="트위터에 보내기">t</a></li>
							<li><a href="javascript:sns_link('kakaostory')" class="kakao" title="카카오스토리 공유">k</a></li>
						</ul>
						<div class="sns_url"><?=admin_text($campaign,"cp_url","text","readonly")?><a data-clipboard-target="#cp_url" class="cp_url_copy_btn">복사</a></div>
						<div class="sns_popup_close_btn"></div>
					</div>
				</div>
			</div>
			<div class="itname"><?=$campaign[cp_subject]?></div>
			<div class="it_description"><?=$campaign[cp_description]?></div>
		</div>

		<!-- 탭 + 콘텐츠 -->
		<div class="item_detail">
			<div class="tab-wrap">
				<div class="tab-inner" id="nfor_page1">
					<ul class="tabmenu">
						<li class="active" id="tab1_li"><a data-tab="#tab1">캠페인정보</a></li>
						<li><a data-tab="#tab2">신청자 한마디<span class="txt_num"><?=$campaign[cp_order_text]?></span></a></li>
						<?php if($config[cf_realtime_review_use]=="1"){ ?>
						<li><a data-tab="#tab3">리뷰<span class="txt_num"><?=$campaign[cp_review_text]?></span></a></li>
						<?php } ?>
					</ul>
				</div>
				<div class="q_wrap">
					<?php if($member[mb_no]){ ?>
					<a href="javascript:campaign_qna_form('<?=$campaign[cp_id]?>')" class="q_campain">캠페인 문의하기</a><?php if($campaign[cp_supply_no]){ ?><a href="javascript:note('<?=$campaign[cp_supply_no]?>')" class="go_note">쪽지보내기</a><?php } ?>
					<?php } ?>
				</div>
			</div>
			<div class="deals_container">

				<!-- 캠페인정보 -->
				<div id="tab1" class="tab-cont">
					<div class="mc-sec-tit">캠페인 정보</div>
					<div class="cp_memo_wrap overflowhidden"><?=$campaign[cp_memo]?></div>
					<a class="campaign_memo_more">+ 더보기</a>

					<div class="basic_form">
						<ul>
							<li id="nfor_page2">
								<span class="tit_basic">🎁 제공 내역</span>
								<span class="content"><?=$campaign[cp_reward]?></span>
								<br>
								<span class="content" style="color:#b91c1c;">※ 제공 서비스 외 추가 비용은 자부담입니다.</span>
							</li>
							<li id="nfor_page3">
								<span class="tit_basic">키워드</span><b data-clipboard-target="#cp_keyword" class="key_copy">복사</b>
								<span class="content"><?=$campaign[cp_keyword]?></span>
								<br>
								<span class="content" style="color:#15803d;">블로그 참여자는 필수키워드를 제목에 1회, 본문에 5회 이상 서브키워드로 기재해주세요.</span>
							</li>
							<li id="nfor_page33">
								<span class="tit_basic">해시태그</span><b data-clipboard-target="#cp_hashtag" class="key_copy">복사</b>
								<span class="content"><?=$campaign[cp_hashtag]?></span>
								<br>
								<span class="content" style="color:#be185d;">인스타그램 참여자는 #협찬 #메타체험단 태그와 안내된 해시태그를 포함하여 업로드해주세요.</span>
							</li>
							<li id="nfor_page22">
								<span class="tit_basic">리뷰 미션</span>
								<span class="content"><?=$campaign[cp_guide_add]?></span>
							</li>
							<li id="nfor_page4">
								<span class="tit_basic">추가 안내</span>
								<span class="content"><?=$campaign[cp_guide]?></span>
							</li>
							<li id="nfor_page5">
								<span class="tit_basic">유의 사항</span>
								<span class="content"><?=$campaign[cp_notice]?></span>
							</li>
						</ul>
						<?=admin_textarea($campaign,"cp_keyword","","readonly")?>
						<?=admin_textarea($campaign,"cp_hashtag","","readonly")?>
					</div>

					<!-- 지도 -->
					<?php if($campaign[cp_lat] and $campaign[cp_lng] and $campaign[cp_type]=="2" and $campaign[cp_map_use]=="1"){ ?>
					<div class="map_wrap">
						<span class="map_tit">📍 업체 정보</span>
						<span class="map_con">
							<b class="address"><?=$campaign[cp_addr1]?> <?=$campaign[cp_addr2]?></b>
							<b class="call">☎ <?=$campaign[cp_tel]?></b>
						</span>
						<div id="map"></div>
					</div>
					<?php } ?>
				</div>

				<div id="tab2" class="tab-cont"><?php include_once "campaign_order_list.php"; ?></div>
				<?php if($config[cf_realtime_review_use]=="1"){ ?>
				<div id="tab3" class="tab-cont"><?php include_once "campaign_review_list.php"; ?></div>
				<?php } ?>

			</div>
		</div>
	</div>

	<!-- ===== 우측 Sticky 신청 카드 ===== -->
	<div class="iteminfo_right_fix">
		<div class="floating">
		<?php include $nfor[skin_path]."inc_right_floating.php"; ?>
		</div>
	</div>

</div>
</div>

<?php if(count($return[etc_list])){ ?>
<div class="other_item_wrap">
	<div class="title_wrap">
		<div class="left"><b class="point_color4">이런</b> 캠페인 어때요?</div>
		<div class="right"><a class="swiper-button-prev-b">‹</a><a class="swiper-button-next-b">›</a></div>
	</div>
	<div class="swiper-container swiper-container-b">
		<div class="swiper-wrapper">
			<?php for($i=0; $i<count($return[etc_list]); $i++){ $row = $return[etc_list][$i]; ?>
			<div class="swiper-slide">
				<a href="campaign.php?cp_id=<?=$row[cp_id]?>">
					<img src="<?=$row[cp_img]?>">
					<p class="cp_day"><?=$row[cp_day]?></p>
					<p class="cp_subject"><?=$row[cp_subject]?></p>
					<b class="cp_description"><?=$row[cp_description]?></b>
				</a>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
<?php } ?>

<script>
var cp_id = "<?=$campaign[cp_id]?>";
var sns_title = "<?=$campaign[cp_subject]?>";
var sns_description = "<?=$campaign[cp_description]?>";
var sns_url = "<?=$campaign[cp_url]?>";

new ClipboardJS('.cp_url_copy_btn');
new ClipboardJS('.key_copy');

$(document).on("click", ".sns_popup_open_btn", function(){
	$('#sns_popup').removeClass("hide");
});
$(document).on("click", ".sns_popup_close_btn", function(){
	$('#sns_popup').addClass("hide");
});

$(document).ready(function() {
	var swiperB = new Swiper('.swiper-container-b', {
		freeMode: true,
		spaceBetween: 14,
		slidesPerView: 5,
		loop: true,
		navigation: { nextEl: '.swiper-button-next-b', prevEl: '.swiper-button-prev-b' }
	});
});

$(document).on("click", ".campaign_memo_more", function(){
	if($(".cp_memo_wrap").hasClass("overflowhidden")){
		$(".cp_memo_wrap").removeClass("overflowhidden");
		$(this).html("- 화면접기");
	} else{
		$(".cp_memo_wrap").addClass("overflowhidden");
		$(this).html("+ 더보기");
	}
});

/* 우측 카드 고정은 CSS position:sticky로 처리 (stick_in_parent 미사용) */

$(document).on("click", ".tabmenu li", function(){
	$(".tabmenu li").removeClass("active");
	$(this).addClass("active");
	$(".tab-cont").hide();
	$($(this).find("a").data("tab")).show();
});

$(document).on("click", "#btn_order", function(){
	<?php if($error[msg]){ ?>
	alert("<?=$error[msg]?>");
	<?php if($error[url]){ ?>
	location.href="<?=$error[url]?>";
	<?php } ?>
	<?php } else{ ?>
	location.href="review_order.php?cp_id="+cp_id;
	<?php } ?>
});

$(document).on("click", ".review_order_popup_bg", function(){
	$(".review_order_popup").fadeOut();
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
</script>

<?php
include_once $nfor[skin_path]."tail.php";
?>
