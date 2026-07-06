<!-- 우측 Sticky 신청 카드 (모던 리뉴얼 2026-06-08) — #btn_order/.campaign_zzim_btn/#countdown/.nfor_scroll 훅 유지 -->
<div class="mc-applycard">

	<div class="thumb" style="display:none;"><img src="<?=$campaign[cp_img]?>"></div>

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
	</div>

	<div class="itname"><?=$campaign[cp_subject]?></div>
	<div class="it_description"><?=$campaign[cp_description]?></div>

	<!-- 모집 현황 -->
	<span class="item_num">모집 현황 &nbsp; 신청 <b class="txt_num point_color4"><?=number_format($campaign[cp_order])?></b> / 모집 <b class="txt_num"><?=number_format($campaign[cp_recruit])?></b>명</span>
	<?php $rate = $campaign[cp_recruit]>0 ? round($campaign[cp_order]/$campaign[cp_recruit]*100) : 0; ?>
	<div class="mc-progress"><i style="width:<?=min(100,$rate)?>%"></i></div>
	<div class="mc-progress-sub">모집 인원의 <b class="txt_num"><?=$rate?>%</b> 신청<?php if($rate>=100){ ?> 🔥<?php } ?></div>

	<!-- 카운트다운 -->
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
			$('#countdown').countdown({until: austDay,  layout: "<? if($d){ ?><b class='txt_num'><?=$d?></b>일<? } ?> <b class='txt_num'>{hnn}</b>시간 <b class='txt_num'>{mnn}</b>분 <b class='txt_num'>{snn}</b>초" });
		});
		</script>
		<div id="countdown">
			<? if($d){ ?><b class="txt_num"><?=$d?></b>일 <? } ?>
			<b class="txt_num"><?=sprintf("%02d",$h)?></b>시간
			<b class="txt_num"><?=sprintf("%02d",$i)?></b>분
			<b class="txt_num"><?=sprintf("%02d",$s)?></b>초
		</div>
		<?php } else{ ?>
		<div id="countdown" style="font-weight:800;color:#dc2626;">접수 마감</div>
		<?php } ?>
	</div>

	<!-- 일정 -->
	<div class="review_step_wrap">
		<span class="step"><em class="point_color4">리뷰어 신청기간</em> <b class="txt_num point_color4"><?=date("m.d",strtotime($campaign[cp_sdatetime]))?>(<?=yoil($campaign[cp_sdatetime])?>) ~ <?=date("m.d",strtotime($campaign[cp_edatetime]))?>(<?=yoil($campaign[cp_edatetime])?>)</b></span>
		<span class="step"><em>선정자 발표</em> <b class="txt_num"><?=date("m.d",strtotime($campaign[cp_pick_datetime]))?>(<?=yoil($campaign[cp_pick_datetime])?>)</b></span>
		<span class="step"><em>리뷰 등록기간</em> <b class="txt_num"><?=date("m.d",strtotime($campaign[cp_contents_sdatetime]))?>(<?=yoil($campaign[cp_contents_sdatetime])?>) ~ <?=date("m.d",strtotime($campaign[cp_contents_edatetime]))?>(<?=yoil($campaign[cp_contents_edatetime])?>)</b></span>
		<?php if($campaign[my_ext_edatetime]){ ?><span class="step" style="background:#fff7e6;border-radius:8px;padding-left:8px;padding-right:8px"><em style="color:#c8780a">📅 내 리뷰 등록 마감</em> <b class="txt_num" style="color:#c8780a"><?=date("m.d",strtotime($campaign[my_ext_edatetime]))?>(<?=yoil($campaign[my_ext_edatetime])?>) <span style="font-size:10px;background:#fff;border:1px solid #ffcf8a;border-radius:999px;padding:1px 6px;font-weight:800;color:#c8780a">연장됨</span></b></span><?php } ?>
		<span class="step"><em>캠페인 결과발표</em> <b class="txt_num"><?=date("m.d",strtotime($campaign[cp_result_datetime]))?></b></span>
	</div>

	<!-- 빠른 이동 -->
	<div class="menu_list">
		<a href="#nfor_page1" class="nfor_scroll">캠페인정보</a>
		<a href="#nfor_page22" class="nfor_scroll">리뷰가이드</a>
		<a href="#nfor_page2" class="nfor_scroll">제공내역</a>
		<a href="#nfor_page3" class="nfor_scroll">키워드</a>
		<a href="#nfor_page33" class="nfor_scroll">해시태그</a>
		<a href="#nfor_page4" class="nfor_scroll">포스팅 안내</a>
		<a href="#nfor_page5" class="nfor_scroll">추가 안내</a>
	</div>

	<!-- 신청/찜 -->
	<div class="order_bottom">
		<div class="btn_zone_floatings">
			<ul>
				<li><a class="review_req b_btn" id="btn_order">리뷰 신청하기</a></li>
				<li><a class="b_btn scrap_btn campaign_zzim_btn <?=$campaign[cp_zzim_is]?"on":""?>" data-cp_id="<?=$campaign[cp_id]?>">찜하기</a></li>
			</ul>
		</div>
	</div>

</div>

<script>
$(document).on("click",".nfor_scroll",function(event){
	$(".tabmenu li").removeClass("active");
	$("#tab1_li").addClass("active");
	$(".tab-cont").hide();
	$("#tab1").show();
	event.preventDefault();
	$('html,body').animate({scrollTop:$(this.hash).offset().top - 70}, 500);
});
</script>
