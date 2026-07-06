<ul>
<?php
for($i=0; $i<count($return["list"]); $i++){
	$row = $return["list"][$i];
?>
<li>
	<div class="box">
			<div class="thumb">
					<a href="campaign.php?cp_id=<?=$row[cp_id]?>"><img src="<?=$row[cp_img]?>" class="it_img"><? if($row[cp_day]=="모집마감"){ ?><div class="soldout"><span class="tit ">모집마감</span></div><?php } ?></a>
				<a class="zzim campaign_zzim_btn <?=$row[cp_zzim_is]?"on":""?>" data-cp_id="<?=$row[cp_id]?>"></a>
			</div>
			<a href="campaign.php?cp_id=<?=$row[cp_id]?>">
			<div class="it_info">
				<div class="top_info">

					<?php if($row[cp_media_blog]){ ?><span class="blog">네이버 블로그</span><?php } ?>
					<?php if($row[cp_media_instagram]){ ?><span class="instagram">인스타그램</span><?php } ?>
					<?php if($row[cp_media_youtube]){ ?><span class="youtube">유튜브</span><?php } ?>
					<?php if($row[cp_media_shop]){ ?><span class="shop">네이버 쇼핑</span><?php } ?>
					<?php if($row[cp_check1]){ ?><span class="gumepyung">구매평</span><?php } ?>
					<?php if($row[cp_check2]){ ?><span class="payback">페이백</span><?php } ?>
					<?php if($row[cp_ohouse]){ ?><span class="cp_ohouse">오늘의집</span><?php } ?>
					<?php if($row[cp_coupang]){ ?><span class="cp_coupang">쿠팡 </span><?php } ?>
					<?php if($row[cp_media_receipt]){ ?><span class="cp_coupang">영수증</span><?php } ?>
					<?php if($row[cp_media_carrot]){ ?><span class="cp_media_carrot">당근</span><?php } ?>

				<div class="option2">
					<?php if($row[cp_point]){ ?><span class="txt_num point_color4">+<?=$row[cp_point]?>P</span><?php } ?>
					<?php if($row[cp_type] == "배송형"){ ?>
						<span class="tag_ship">배송형</span>
					<?php } else if($row[cp_type] == "방문형"){ ?>
						<span class="tag_ship">방문형</span>
					<?php } else if($row[cp_type] == "배송형+리워드"){ ?>
						<span class="tag_ship">배송형</span> <span class="tag_reward">리워드</span>
					<?php } else if($row[cp_type] == "방문형+리워드"){ ?>
						<span class="tag_ship">방문형</span> <span class="tag_reward">리워드</span>
					<?php } ?>
					<?php if($row[cp_day] >= "1"){ ?>
						<span class="txt_num dday"><?=$row[cp_day]?>일 남음</span>
					<?php } else if($row[cp_day] == "0"){ ?>
						<span class="txt_num eday">오늘까지</span>
					<?php } ?>
				</div>

					<?php if(basename($PHP_SELF)=="review_asign_list.php" or basename($PHP_SELF)=="review_post_list.php" or basename($PHP_SELF)=="review_edit_list.php"){ ?>
					<a href="url_input_win.php?rv_id=<?=$row[rv_id]?>" class="url_input">리뷰<?=basename($PHP_SELF)=="review_asign_list.php"?"등록":"수정"?></a>
					<?php } ?>
					<?php /* 상품 구매 완료 신고 (선정된 캠페인 only) 2026-06-30 */ if(basename($PHP_SELF)=="review_asign_list.php"){ $bchk=$row[rv_buy_chk];
						if($bchk=='2'){ ?><a class="mc-buy-mb done" onclick="return false;">✅ 구매 확인됨</a><?php }
						else if($bchk=='1'){ ?><a href="javascript:void(0)" class="mc-buy-mb req" onclick="event.stopPropagation();mcBuyReq(<?=(int)$row[rv_id]?>,0);return false;">🛒 구매 신고됨 · 취소</a><?php }
						else{ ?><a href="javascript:void(0)" class="mc-buy-mb todo" onclick="event.stopPropagation();mcBuyReq(<?=(int)$row[rv_id]?>,1);return false;">🛒 구매 완료</a><?php } } ?>
					<?php /* 개인 리뷰마감 연장 표시 2026-06-30 */ if(basename($PHP_SELF)=="review_asign_list.php" && !empty($row[my_ext_edatetime])){ ?><div style="display:inline-block;margin-top:6px;font-size:11.5px;font-weight:700;color:#c8780a;background:#fff7e6;border:1px solid #ffe2a8;border-radius:8px;padding:5px 10px">📅 내 리뷰 마감 <?=date("m.d",strtotime($row[my_ext_edatetime]))?> · 연장됨</div><?php } ?>
					<?php if(basename($PHP_SELF)=="review_wait_list.php"){ ?>
					<a data-rv_id="<?=$row[rv_id]?>" class="campain_cencle">신청 취소</a>
					<?php } ?>
				</div>
				<span class="it_name"><?=$row[cp_subject]?></span>
				<span class="it_description"><?=$row[cp_description]?></span>
				<div class="option">
					<span>모집 인원 <b class="txt_num"><?=$row[cp_recruit]?></b>명 / 현재 신청자 <b class="txt_num"><?=$row[cp_order]?></b>명</span> 
					<span> </span>		
				</div>
			</div>
		</a>
	</div>

</li>
<? } ?>
</ul>
<?php if(basename($PHP_SELF)=="review_asign_list.php"){ /* 회원 상품구매 신고 JS/CSS (1회) 2026-06-30 */ ?>
<style>
.mc-buy-mb{display:inline-block;margin-top:6px;font-size:12px;font-weight:700;padding:6px 11px;border-radius:8px;cursor:pointer;text-decoration:none;line-height:1.1}
.mc-buy-mb.todo{background:#03c75a;color:#fff}
.mc-buy-mb.req{background:#fff;color:#02b350;border:1px solid #03c75a}
.mc-buy-mb.done{background:#eef7f0;color:#6b7684;cursor:default}
</style>
<script>
function mcBuyReq(rid,to){
	if(to){ if(!confirm('이 캠페인 상품을 구매하셨나요?\n구매 완료로 신고됩니다.')) return; }
	else { if(!confirm('구매 신고를 취소할까요?')) return; }
	var fd='mode='+(to?'req':'cancel')+'&rv_id='+rid;
	fetch('<?=$nfor[path]?>/mc_buy.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:fd})
		.then(function(r){return r.json();})
		.then(function(d){ if(d&&(d.result=='req'||d.result=='cancel')){ location.reload(); } else { alert((d&&d.msg)?d.msg:'처리에 실패했습니다'); } })
		.catch(function(){ alert('네트워크 오류가 발생했습니다'); });
}
</script>
<?php } ?>