<?php
if (!defined("IMJUNA")) exit;
?>
	</div>
</div>
<!-- //wrap -->

<div id="footer">
	<div class="doc_block">
		<div class="layout_inner">
			<a href="page.php?pg_code=company" class="btn">회사소개</a>
			<span class="line"></span>
			<a href="cooperation_form.php" class="btn">입점/제휴문의</a>
			<span class="line"></span>
			<a href="agreement.php?code=agreement" class="btn">이용약관</a>
			<span class="line"></span>
			<a href="agreement.php?code=privacy" class="btn"><strong>개인정보처리방침</strong></a>
			<span class="line"></span>
			<a href="agreement.php?code=safe" class="btn">청소년 보호정책</a>
		</div>
	</div>
	<div class="doc_wrap">
		<div class="layout_inner">
			<div class="fm">
				<p><img src="<?=$nfor[skin_path]?>img/flogo.png" style="margin-bottom:20px;"> </p>
				<br>상호 : 메타체험단　　　사업자등록번호 :111-15-16387 　　　통신판매업신고 :2021-서울관악-0273 <br>
고객센터 : 02-2082-2005　　개인정보보호책임자 : 박치훈<br>


				<!-- <?=$config[cf_cp_zip]?> <?=$config[cf_cp_addr1]?> <?=$config[cf_cp_addr2]?> --> </p><br>

메타체험단은 체험단 중개 서비스를 제공합니다.<br>
<br>
사이트 내 페이백 캠페인의 배송, 민원, 환급 등의 처리는 메타체험단이 진행합니다.<br>
사이트 이용자 회원분들은 카카오채널을 통한 문의 부탁드립니다.<br>
<br>
체험단 담당자 : 박치훈　 (직통 번호 : 010-3899-9893)<br>
<br>
ㆍ업무 제안/제휴　 meta-chehumdan@nate.com <br><br>


				
				
				
				<div class="copyright txt_num">Copyright © <?=date("Y")?> <?=$config[cf_name_eng]?> Inc. All rights reserved </div>
			</div>
			<div class="f2">
				<? if($config[cf_campaign_info]=="1"){ ?>
				<ul>
					<?php
					$influencer_count = sql_fetch("select count(*) as cnt from nfor_member where mb_leave_datetime='' and mb_admin='0'");
					$campaign_count = sql_fetch("select count(*) as cnt from nfor_campaign where cp_asign='2'");
					$review_count = sql_fetch("select count(*) as cnt from nfor_review where 1");
					?>
					<li><span class="txt_num">Influencer</span><b class="txt_num"><?=number_format($influencer_count[cnt])?></b></li>
					<li><span class="txt_num">Campaign</span><b class="txt_num"><?=number_format($campaign_count[cnt])?></b></li>
					<li><span class="txt_num">Review </span><b class="txt_num"><?=number_format($review_count[cnt])?></b></li>
				</ul>
				<? } ?>
			</div>
			<div class="fl_right">고객센터 : <b class="txt_num font-30 point_color5"><?=$config[cf_tel]?></b> <span>· 상담시간 : <?=$config[cf_call_time]?></span></div>
			<div class="bottom_sns">
				<?php if($config[cf_link_blog]){ ?><a href="<?=$config[cf_link_blog]?>" class="blog sns" target="_blank"></a><?php } ?>
				<?php if($config[cf_link_facebook]){ ?><a href="<?=$config[cf_link_facebook]?>" class="facebook sns" target="_blank"></a><?php } ?>
				<?php if($config[cf_link_instagram]){ ?><a href="<?=$config[cf_link_instagram]?>" class="instagram sns" target="_blank"></a><?php } ?>
				<?php if($config[cf_link_youtube]){ ?><a href="<?=$config[cf_link_youtube]?>" class="youtube sns" target="_blank"></a><?php } ?>
			</div>
		</div>
	</div>
</div>
<!-- //footer -->
<?php @include_once $_SERVER['DOCUMENT_ROOT'].'/mc_chat_widget.php'; /* 고객센터 채팅 위젯 (카카오 대체) 2026-06-24 */ ?>
<?php if($config[cf_naver_talk_use]=="1"){ ?>

<div class="naver_tt">
<a class="talktalk_btn" data-talktalk_url="<?=$config[cf_naver_talk]?>"><img src="<?=$nfor[skin_path]?>img/naver_tt.png" style="width:50px;"></a>
</div>
<?php } ?>

<?php // 패스워드변경안내
if($nfor[password_change_popup]=="1" and $config[cf_password_member_use]=="1"){
	include_once $nfor[skin_path]."password_change_popup.php";
}
?>

<?
if(basename($PHP_SELF)=="index.php"){
	include_once $nfor[skin_path]."popup.php";
}
?>

<script type="text/javascript">
<!--
$(document).on("click", ".talktalk_btn", function(){
	var talktalk_url = $(this).data("talktalk_url");
    window.open(talktalk_url, "talktalk_win", "left=50,top=50,width=470,height=800,scrollbars=1");

});

$(document).on("click", ".campaign_zzim_btn", function(){

	var cp_id = $(this).data("cp_id");
	var campaign_zzim_btn = $(this);
	$.ajax({
		type: "post",
		data : "mode=zzim&cp_id="+cp_id,
		url: "campaign.php",
		success: function(response){
			console.log(response);
			var json = $.parseJSON(response);			
			if(json["result"]=="login"){
				alert("로그인하셔야 이용가능합니다");		
			} else if(json["result"]=="delete"){
				campaign_zzim_btn.removeClass("on");
			} else if(json["result"]=="insert"){
				campaign_zzim_btn.addClass("on");
			} else{
				alert(json["msg"]);
			}
		}
	});
});
//-->
</script>

<?php
include_once $nfor[path]."/html_tail.php";
?>