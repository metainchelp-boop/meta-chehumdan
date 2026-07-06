<?php
if($member[mb_no]){
	$campaign_zzim_list = sql_fetch("select count(*) as cnt from nfor_campaign_zzim a left join nfor_campaign b on ( a.zz_cp_id = b.cp_id ) where a.zz_mb_no='$member[mb_no]'");
	$receive_message[cnt] = 0;
	$que = sql_query("select * from nfor_message where mg_mb_no='$member[mb_no]' and mg_read_datetime=''");
	while($data = sql_fetch_array($que)){
		$res = array();
		$res[mg_id] = $data[mg_id];
		$res[mg_msg] = $data[mg_msg];
		$res[mg_datetime] = date("y.m.d H:i",strtotime($data[mg_datetime]));
		$receive_message["list"][] = $res;
		$receive_message[cnt]++;
	}

	$review_count = review_count($member[mb_no]);
}

// 카테고리 N+1 제거: depth 0+1 전체를 1쿼리로 로드 후 PHP에서 트리 구성 (LIKE prefix 동일, cg_rank 순서 보존)
$__cat_all = sql_query("select * from nfor_campaign_category where cg_use='1' and ((cg_depth='0' and cg_depth_id='0') or cg_depth='1') order by cg_rank asc");
$__cat_top = array(); $__cat_sub = array();
while($__c = sql_fetch_array($__cat_all)){
	if($__c[cg_depth]=='0') $__cat_top[] = $__c; else $__cat_sub[] = $__c;
}
for($i=0; $i<count($__cat_top); $i++){
	$row = $__cat_top[$i];
	$res = array();
	$res[category_id] = $row[category_id];
	$res[category_href] = "campaign_list.php?category_id=$row[category_id]";
	$res[category_name] = $row[cg_category];

	$reply_arr = array();
	for($j=0; $j<count($__cat_sub); $j++){
		$data = $__cat_sub[$j];
		if(strpos($data[category_id], $row[category_id]) === 0){
			$reply = array();
			$reply[category_id] = $data[category_id];
			$reply[category_href] = "campaign_list.php?category_id=$data[category_id]";
			$reply[category_name] = $data[cg_category];
			$reply_arr[] = $reply;
		}
	}
	$res[reply] = $reply_arr;


	$return["category_list"][] = $res;
}

include_once $nfor[path]."/html_head.php";
?>

<?php // 레이어팝업 
include_once $nfor[skin_path]."layer_popup.php";
?>
<?php // 상단배너
if($apply_good){
	include_once "inc_banner_top.php";
}
?>

<script type="text/javascript">
<!--
$(document).ready(function() {
	$('#msgboxwrap').mouseover(function() {
		$("#msgbox").show();  
	});
	$('#msgboxwrap').mouseout(function() {
		$("#msgbox").hide();  
	});

	$('#msgbox').mouseover(function() {
		$("#msgbox").show();
	});

	$('#msgbox').mouseout(function() {
		$("#msgbox").hide();
	});
});

$(document).ready(function() {
	$('#msgboxwrap2').mouseover(function() {
		$("#msgbox2").show();  
	});
	$('#msgboxwrap2').mouseout(function() {
		$("#msgbox2").hide();  
	});

	$('#msgbox2').mouseover(function() {
		$("#msgbox2").show();
	});

	$('#msgbox2').mouseout(function() {
		$("#msgbox2").hide();
	});
});
</script>	
<?
//include_once $nfor[skin_path]."inc_top_banner.php"; // 배너
?>
<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css"
/>
<div id="wrap">

	<div id="header">
		<div class="layout_inner top35">
			<div class="top_menu">
					<div class="fl_left"> </div>
					<div class="fl_right">
					<?php
					$i = 0;
					$que = sql_query("select * from nfor_board where bo_use='1' order by bo_rank desc");
					while($data = sql_fetch_array($que)){
						$tbl_list[$i][bo_tbl] = $data[bo_tbl];
						$tbl_list[$i][bo_name] = $data[bo_name];
						$i++;
					}
					?>
					<span class="sub_menu">
					<a href="board_list.php?tbl=<?=$tbl_list[0][bo_tbl]?>" class="tit">커뮤니티</a>
					<span class="over">
						<?php
						for($i=0; $i<count($tbl_list); $i++){
						?>
						<a href="board_list.php?tbl=<?=$tbl_list[$i][bo_tbl]?>" class="sub_btn"><?=$tbl_list[$i][bo_name]?></a>
						<?php } ?>
						<a href="attendance.php" class="sub_btn">출석부</a>
					</span>
					</span>
					<span class="line"></span>
					<span class="sub_menu">
						<a href="faq.php" class="tit">고객센터</a>
						<span class="over">
							<a href="faq.php" class="sub_btn">자주묻는질문</a>
							<a href="help_form.php" class="sub_btn">1:1문의</a>
							<a href="notice_list.php" class="sub_btn">공지사항</a>
							<!-- <a href="page.php?pg_code=guide" class="sub_btn">이용안내</a> -->
							<a href="cooperation_form.php" class="sub_btn">제휴문의</a>
						</span>
					</span>
					<? if(!$member[mb_no]){ ?>
					<span class="line"></span>
					<span class="sub_menu">
						<a href="login.php" class="tit"><b><img src="<?=$nfor[skin_path]?>img/login_ico.png" style="vertical-align:-3px;"> </b>로그인</a>
					</span>
					<span class="line"></span>
					<span class="sub_menu">
						<a href="member_main.php" class="tit">회원가입</a>
					</span>
					<?php } else{ ?>



					<span class="line"></span>
					<span class="sub_menu">
						<a href="review_wait_list.php" class="tit">마이페이지</a>
						<span class="over">
							<a href="review_wait_list.php" class="sub_btn">나의캠페인</a>
							<a href="campaign_zzim_list.php" class="sub_btn">관심캠페인</a>
							<a href="my_review_list.php" class="sub_btn">등록리뷰</a>
							<a href="point_list.php" class="sub_btn">나의포인트</a>
							<a href="member_form.php" class="sub_btn">개인정보수정</a>
							<a href="customer_list.php" class="sub_btn">1:1문의</a>
						</span>
					</span>




					<span class="line"></span>
					<span class="sub_menu">
						<span class="tit" id="msgboxwrap">
							<b><img src="<?=$nfor[skin_path]?>img/login_ico.png" style="vertical-align:-3px;"> </b><?=$member[mb_nick]?$member[mb_nick]:$member[$member_config[cf_mb_id_type]]?>
							<div id="msgbox">
								<div class="arrow_msgbox"><img src="<?=$nfor[skin_path]?>img/serarrow.png"></div>
								<div class="box">
									<ul>
										<li><span style="line-height:18px!important;; font-size:14px; display:inline-block; width:150px; margin-top:15px;"><b class="point_color1"><?=$member[mb_nick]?></b>님 환영합니다.</span>
										<span class="box_right">
										<a href="review_wait_list.php" class="mypage"><img src="<?=$nfor[skin_path]?>img/my_ico.png">마이페이지</a>
										<a  href="logout.php" class="logout" ><img src="<?=$nfor[skin_path]?>img/logout_ico.png">로그아웃</a>
										</span>
										</li>
										<li><a href="review_wait_list.php"><b class="my_cam_ico"></b><b class="point_color2">나의 캠페인</b> 활동 <span class="box_num"><?=number_format($review_count[review_total_count])?></span></a></li>
										<li><a href="point_list.php"><b class="my_point_ico"></b><b class="point_color3">포인트</b> 관리<span class="box_num"><?=number_format($member[mb_point])?>P</span></a></li>
										<li><a href="campaign_zzim_list.php"><b class="zzim_cam_ico"></b><b class="point_color2">관심 캠페인</b> 관리<span class="box_num"><?=number_format($campaign_zzim_list[cnt])?></span></a></li>
									</ul>
								</div>
							</div>
						</span>
					</span>


					<?php if($member[mb_no]){ ?>
					<span class="line"></span>
					<span class="sub_menu"><a href="javascript:note()" class="tit">쪽지<b class="note_count"><?=$member[mb_note]?></b></a></span>
					<?php } ?>

					<span class="line"></span>
					<span class="sub_menu">
						<span class="tit" id="msgboxwrap2">
							<a href="receive_message.php"><b><img src="<?=$nfor[skin_path]?>img/alram_on_ico.png" style="vertical-align:-2px;height:15px;"> </b>알림</a>
							<div id="msgbox2">
								<div  class="arrow_msgbox"><img src="<?=$nfor[skin_path]?>img/serarrow.png"></div>
								<div class="box">
							
									<h2>새로운알림 <b class="point_color3 txt_num"> <?=number_format($receive_message[cnt])?> </b>개 있습니다  <img src="<?=$nfor[skin_path]?>img/new_icco.png" style="vertical-align:-2px;"></h2>
									
									<?php if($receive_message[cnt]){ ?>
									<div class="list_box">
									<?php
									for($i=0; $i<count($receive_message["list"]); $i++){
										$message = $receive_message["list"][$i];
									?>
									<a href="receive_message.php?mode=read&mg_id=<?=$message[mg_id]?>" class="msg_list"><span class="msgtxt"><?=$message[mg_msg]?></span><span class="box_num"><?=$message[mg_datetime]?></span></a>
									<?php } ?>
									</div>
									<?php } else{ ?>
									<span class="nodate">신규 메시지가 없습니다</span>
									<?php } ?>
									<a href="receive_message.php" class="view_more">알림 더보기 +</a>
								</div>
							
							</div>
						</span>
					</span>
					<?php if($member[mb_admin]){ ?>
					<span class="line"></span>
					<span class="sub_menu">
						<a href="<?=$nfor[path]?>/admin/" class="tit" target="_blank" style="color:#3a4ca8; font-weight:bold;">광고주 페이지</a>
					</span>
					<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="vertical_line"></div>
		<div class="layout_inner top_top">
			<h3 class="logo"><a href="index.php"><img src="<?=$nfor[skin_path]?>img/logo.png"></a></h3>
			
			<div class="gnb">
				<ul class="nfor_menu_ul">
					<?php
					for($i=0; $i<count($return["category_list"]); $i++){
						$category = $return["category_list"][$i];
					?>
					<li>
						<a href="<?=$category[category_href]?>" class="menu <?php if(substr($category_id,0,4)==$category[category_id]){ ?>on<?php } ?>"><?=$category[category_name]?></a>
						<?php if(count($category[reply])){ ?>
						<div class="gnb_sub_menu">							
							<a href="<?=$category[category_href]?>" class="menu_sub">전체</a>							
							<?php
							for($k=0; $k<count($category[reply]); $k++){
								$sub_category = $category[reply][$k];
							?>
							<a href="<?=$sub_category[category_href]?>" class="menu_sub"><?=$sub_category[category_name]?></a>
							<?php } ?>
						</div>
						<?php } ?>
					</li>
					<?php } ?>
					<?php if($config[cf_realtime_review_use]=="1"){ ?><li><a href="realtime_review_list.php" class="menu <?=basename($PHP_SELF)=="realtime_review_list.php"?"on":""?>">실시간 리뷰</a></li><?php } ?>
					<?php if($config[cf_realtime_review_use]=="1"){ ?><li><a href="banner_list.php" class="menu <?=basename($PHP_SELF)=="banner_list.php"?"on":""?>">기획전</a></li><?php } ?>
					<?php if($config[cf_map_campaign_use]=="1"){ ?><li><a href="/cooperation_form.php" class="menu" target="_blank" style="color:#5D5D5D;> <?=basename($PHP_SELF)=="https://meta-chehumdan.com/cooperation_form.php"?"on":""?>">제휴문의</a></li><?php } ?>
					<?php if($config[cf_map_campaign_use]=="1"){ ?><li><a href="/notice_list.php" class="menu" target="_blank" style="color:#FF0000;> <?=basename($PHP_SELF)=="https://meta-chehumdan.com/notice_list.php"?"on":""?>">공지사항</a></li><?php } ?>
					<?php if($config[cf_map_campaign_use]=="1"){ ?><li><a href="/admin/" class="menu" target="_blank" style="color:#3162C7;> <?=basename($PHP_SELF)=="https://meta-chehumdan.com/admin/"?"on":""?>">광고주 센터</a></li><?php } ?>
				</ul>
			</div>
			<div style="clear:both;"></div>




			<a class="search_icon"></a>
			<!-- <a href="#menu" class="all"><span></span></a> -->
			
		</div>
		<div style="clear:both;"></div>
	</div>
	<div style="clear:both;"></div>
	<div class="search" id="searchwrap">
			<form method="get" id="fitem_search" action="search.php" autocomplete="off">
				<a class="close"></a>
				<div id="searchbox">
					<div class="inner">
					<input type="text" name="keyword" value="" class="search_text" placeholder="검색어를 입력해주세요">
					<input type="submit" class="ser_btn">
					</div>
					<div class="fa_keyword_main">
					<?php
					$que = sql_query("select * from nfor_keyword where 1 order by ke_rank asc limit 20"); // 최근데이터를 가져옴
					while($data = sql_fetch_array($que)){
					?>
					<a href="search.php?keyword=<?=$data[ke_keyword]?>">#<?=$data[ke_keyword]?></a>
					<?php } ?>
					</div>
			</div>
		</form>
	</div>
	<?php
	if(basename($PHP_SELF)=="index.php"){ // 인덱스 메인 배너 
	?>
	
	<div  style="min-height:650px;">
	<?php } else if(basename($PHP_SELF)=="guide.php" or basename($PHP_SELF)=="company.php" or basename($PHP_SELF)=="premier.php" or basename($PHP_SELF)=="realtime_review_list.php"or basename($PHP_SELF)=="map_campaign_list.php"){ ?>
	<div >
	<?php } else { ?>
	<div class="layout_inner">
	<?php } ?>




<script>
$(document).on("click",".search_icon",function(){
	$(".search").show();
});
$(document).on("click",".close",function(){
	$(".search").hide();
});
</script>
