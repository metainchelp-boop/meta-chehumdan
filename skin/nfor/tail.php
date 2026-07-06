<?php
if (!defined("IMJUNA")) exit;
?>	</div>
	<!-- //container -->
	<footer class="footer">
			<div class="f_logo"></div>
        	<div class="footer_link">
        		<a href="agreement.php?code=agreement" class="f_menu">이용약관</a>
				<a href="agreement.php?code=privacy" class="f_menu">개인정보취급방침</a>
				<a href="agreement.php?code=safe" class="f_menu">청소년보호정책</a>
				<a href="http://ftc.go.kr/info/bizinfo/communicationViewPopup.jsp?wrkr_no=<?=str_number($config[cf_cp_number1])?>" target="_blank">사업자정보확인</a>
            </div>
			<div class="company">
<br>상호 : 메타체험단　　　사업자등록번호 :111-15-16387<br>
<br>통신판매업신고 :2021-서울관악-0273 <br>
<br>고객센터 : 02-2082-2005　　개인정보보호책임자 : 박치훈<br>

			
			

<br>메타체험단은 체험단 중개 서비스를 제공합니다.<br><br>

사이트 내 페이백 캠페인의 배송, 민원, 환급 등의 처리는<br>
메타체험단이 진행합니다.<br>
사이트 이용자 회원분들은 카카오채널을 통한 문의 부탁드립니다.<br>
<br>
체험단 담당자 : 박치훈 (직통 번호 : 010-3899-9893)
<br>
ㆍ업무 제안/제휴 meta-chehumdan@nate.com<br><br>



			</div>
<style>
.bottom_sns{margin:20px 0px;}
.bottom_sns .sns{display:inline-block; width:30px; height:30px; margin-right:3px; background-color:#666;}
.bottom_sns .blog{background: url('/skin/nfor/img/blog_tr.png') center no-repeat; background-size:30px;}
.bottom_sns .facebook{background: url('/skin/nfor/img/face_tr.png') center no-repeat; background-size:30px;}
.bottom_sns .instagram{background: url('/skin/nfor/img/instgram_tr.png') center no-repeat; background-size:30px;}
.bottom_sns .youtube{background: url('/skin/nfor/img/youtube_tr.png') center no-repeat; background-size:30px;}
</style>
			<div class="bottom_sns">
				<?php if($config[cf_link_blog]){ ?><a href="<?=$config[cf_link_blog]?>" class="blog sns" target="_blank"></a><?php } ?>
				<?php if($config[cf_link_facebook]){ ?><a href="<?=$config[cf_link_facebook]?>" class="facebook sns" target="_blank"></a><?php } ?>
				<?php if($config[cf_link_instagram]){ ?><a href="<?=$config[cf_link_instagram]?>" class="instagram sns" target="_blank"></a><?php } ?>
				<?php if($config[cf_link_youtube]){ ?><a href="<?=$config[cf_link_youtube]?>" class="youtube sns" target="_blank"></a><?php } ?>
			</div>
			<div class="copyright">Copyright © <?=date("Y")?> <?=$config[cf_cp_name]?> Inc. All rights reserved</div>
			<div class="count_cp">
				<? if($config[cf_campaign_info]=="1"){ ?>
				<?php
				$influencer_count = sql_fetch("select count(*) as cnt from nfor_member where mb_leave_datetime='' and mb_admin='0'");
				$campaign_count = sql_fetch("select count(*) as cnt from nfor_campaign where cp_asign='2'");
				$review_count = sql_fetch("select count(*) as cnt from nfor_review where 1");
				?>
				<ul>
					<li><span class="txt_num">Influencer</span><b class="point-color1"><?=number_format($influencer_count[cnt])?></b></li>
					<li><span class="txt_num">Campaign</span><b class="point-color1"><?=number_format($campaign_count[cnt])?></b></li>
					<li><span class="txt_num">Review </span><b class="point-color1"><?=number_format($review_count[cnt])?></b></li>
					<li>
						<span class="cucenter">customer<br>center</span>
						<a href="tel:<?=$config[cf_tel]?>" class="tel"><?=$config[cf_tel]?></a>
					</li>
				</ul>
				<? } ?>
			</div>
	</footer>

	<a class="top_move_btn"></a>
	<!-- <a href="" class="naver_tt"></a> -->
</div>
<!-- //wrap -->
<?php @include_once $_SERVER['DOCUMENT_ROOT'].'/mc_chat_widget.php'; /* 고객센터 채팅 위젯 (카카오 대체) 2026-06-24 */ ?>
<?php if($config[cf_naver_talk_use]=="1"){ ?>
<div class="naver_tt">
<a class="talktalk_btn" data-talktalk_url="<?=$config[cf_naver_talk]?>"><img src="skin/demo/img/naver_tt.png" style="width:50px;"></a>
</div>
<?php } ?>
<? if(!$hide){ ?>
<footer id="footer">
	<a href="index.php">
		<i class="btn_home <?=basename($PHP_SELF)=="index.php"?"on":""?>"></i>
		<span class="<?=basename($PHP_SELF)=="index.php"?"on":""?>">홈</span>
	</a>
	<a href="review_wait_list.php">
		<i class="btn_campain <?=basename($PHP_SELF)=="review_wait_list.php"?"on":""?>"></i>
		<span class="<?=basename($PHP_SELF)=="review_wait_list.php"?"on":""?>">내캠페인</span>
	</a>
	<a href="receive_message.php">
		<i class="btn_message <?=basename($PHP_SELF)=="receive_message.php"?"on":""?>"></i>
		<span class="<?=basename($PHP_SELF)=="receive_message.php"?"on":""?>">받은메세지</span>
	</a>
	<a href="board_list.php?tbl=free">
		<i class="btn_community <?=substr(basename($PHP_SELF),0,5)=="board"?"on":""?>"></i>
		<span class="<?=basename($PHP_SELF)=="board_list.php?tbl=free"?"on":""?>">커뮤니티</span>
	</a>
	<a href="mypage.php">
		<i class="btn_mypage <?=basename($PHP_SELF)=="mypage.php"?"on":""?>"></i>
		<span class="<?=basename($PHP_SELF)=="mypage.php"?"on":""?>">마이페이지</span>
	</a>
</footer>
<? } ?>

<script>
$(document).on("click", ".talktalk_btn", function(){
	var talktalk_url = $(this).data("talktalk_url");
    window.open(talktalk_url, "talktalk_win", "left=50,top=50,width=470,height=800,scrollbars=1");

});
$(document).on("click", ".searchbar_wrap", function(){
	location.href="search.php";
});
$(document).on("click", ".btn_back", function(){
	history.back();
});
<?php if($is_index or basename($PHP_SELF)=="item_list.php"){ ?>
$(function(){
	var lastScroll = 0;
	$(window).scroll(function(event){
		var st = $(this).scrollTop();
		if(st<56){
			$("#header").removeClass("upmove");
		} else{
			if(st > lastScroll){
				$("#header").addClass("upmove");
			} else{
				$("#header").removeClass("upmove");
			}
		}		
		lastScroll = st;
	});
});
<?php } ?>
$(document).on("click", ".top_move_btn", function(){
	$('html,body').animate({scrollTop:0}, 1000);   
});
$(window).scroll(function(){
	var scrollTop = $(document).scrollTop();
	if(scrollTop > 200){
		$('.top_move_btn').fadeIn();
	} else{
		$('.top_move_btn').fadeOut();
	}
});
</script>
















<!-- 찜하기팝업 -->
<script type="text/javascript">
<!--
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
<!-- //찜하기팝업 -->
<?php
include_once $nfor[path]."/html_tail.php";
?>