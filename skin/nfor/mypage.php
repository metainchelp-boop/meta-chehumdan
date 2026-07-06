<?php
include_once $nfor[skin_path]."head.php";

if($member[mb_no]){
	$review_count = review_count($member[mb_no]);
 
	$pt_point_sum = sql_fetch("select sum(pt_point) as pt_point_sum from nfor_point where pt_mb_no='$member[mb_no]' and pt_type<>'700'");
	$campaign_zzim_list = sql_fetch("select count(*) as cnt from nfor_campaign_zzim a left join nfor_campaign b on ( a.zz_cp_id = b.cp_id ) where a.zz_mb_no='$member[mb_no]'");
	$receive_message = sql_fetch("select count(*) as cnt from nfor_message where mg_mb_no='$member[mb_no]' and mg_read_datetime=''");
}
?>

<div class="mypage_wrap">


	<div class="mypage_top">
		
		<div class="avtar_zone">
			<a onclick="$('#mb_photo').click()" class="profile_img">
				<img class="profile_img" id="profile_img" src="<?=$member["mb_photo"]?thumbnail($nfor[path]."/data/member/mb/".$member["mb_photo"],60,60,0,1):"/skin/demo/img/pro.png"?>" alt="">
				<span class="set">설정변경</span>
			</a>
			<form name="multiform" id="multiform" action="json.php" method="post" enctype="multipart/form-data" style="display:none;">
				<input type="hidden" name="mode"  value="mb_photo_upload"/>
				<input type="file" name="mb_photo" id="mb_photo"/>
			</form>	
		</div>
		<div class="info_left">
			<div><?php if($member[mb_nick]){ ?><span class="m_name"><?=$member[mb_nick]?$member[mb_nick]:$member[mb_name]?>님</span><?php } ?> <span class="m_id txt_num"><?=$member[$member_config[cf_mb_id_type]]?></span></div>
			<div class="add_blog"> 
				<?php if($member[mb_blog]){ ?>
				<a href="<?=blog_make_url($member[mb_blog])?>" target="_blank"><b class="blog_on"></b><?=$member[mb_nick]?>님의 블로그</a>
				<?php } else{ ?>
				<a href="member_form.php"><b class="blog"></b>블로그를 등록해주세요</a>
				<?php } ?>
			</div>
			<div class="add_inst">
				<?php if($member[mb_instagram]){ ?>
				<a href="<?=instagram_make_url($member[mb_instagram])?>" target="_blank"><b class="inst_on"></b><?=$member[mb_id]?>님의 인스타그램</a>
				<?php } else{ ?>
				<a href="member_form.php"><b class="inst"></b>인스타그램을 등록해주세요</a> 
				<?php } ?>
			</div>
			<div class="add_inst">
				<?php if($member[mb_youtube]){ ?>
				<a href="<?=youtube_make_url($member[mb_youtube])?>" target="_blank"><b class="youtube_on"></b><?=$member[mb_id]?>님의 유튜브</a>
				<?php } else{ ?>
				<a href="member_form.php"><b class="youtube"></b>유튜브을 등록해주세요</a> 
				<?php } ?>
			</div>		
		</div>

		<div class="info_bottom">
			<ul>
				<li><a href="attendance.php"><span class="cal">출석</span> <span class="txt_num r_num"><?=number_format($member[mb_attendance])?></span></a></li>
				<li><a href="receive_message.php"><span class="mgs">메세지</span>  <span class="txt_num r_num"><?=number_format($receive_message[cnt])?></span></a></li>
				<li><a href="campaign_zzim_list.php"><span class="cam">관심</span>  <span class="txt_num r_num"><?=number_format($campaign_zzim_list[cnt])?></span></a></li>
				<li><a href="note_receive_list.php"><span class="note">쪽지</span>  <span class="txt_num r_num"><?=number_format($member[mb_note])?></span></a></li>
			</ul>
		</div>

	</div>
	
	<div class="group_my_campaign ">
		<div class="inner_my_campaign">
			<p>나의캠페인현황</p>
			<ul class="my_campaign">
				<li><a href="review_wait_list.php"><b class="txt_num cam_num"><?=number_format($review_count[review_count][1])?></b><span>신청</span></a></li>
				<li><a href="review_asign_list.php"><b class="txt_num cam_num"><?=number_format($review_count[review_count][2])?></b><span>선정</span></a></li>
				<li><a href="review_post_list.php"><b class="txt_num cam_num"><?=number_format($review_count[review_count][3])?></b><span>검수중</span></a></li>
				<li><a href="review_edit_list.php"><b class="txt_num cam_num"><?=number_format($review_count[review_count][7])?></b><span>수정요청</span></a></li>
			</ul>
		</div>
	</div>

	<div class="group_my_point">
		<ul class="my_point">
			<li><a href="point_list.php"><span class="pointit" style="color:#000;">사용가능포인트</span><b class="txt_num p_num point-color1"><?=number_format($member[mb_point])?>p</b></a></li>
			<li><a href="point_list.php"><span  class="pointit">누적포인트</span><b class="txt_num p_num"><?=number_format($pt_point_sum[pt_point_sum])?>p</b></a></li>
		</ul>
	</div>

	<div class="mypage_list">
		<ul>
			<li><a href="campaign_zzim_list.php">내스크랩</a> <b></b></li>
			<li><a href="point_list.php">포인트내역</a> <b></b></li>
			<li><a href="my_campaign_qna_list.php">캠페인문의/답변내역</a> <b></b></li>
			<li><a href="customer_form.php">1:1문의</a> <b></b></li>
			<li><a href="faq.php">자주묻는질문</a> <b></b></li>
			<li><a href="my_review_list.php">등록리뷰</a> <b></b></li>
			<?php if($member[mb_no]){ ?>
			<li><a href="member_confirm.php">정보수정</a> <b></b></li>
			<li><a href="logout.php">로그아웃</a> <b></b></li>
			<?php } else{ ?>
			<li><a href="member_join.php">회원가입</a> <b></b></li>
			<li><a href="login.php">로그인</a> <b></b></li>
			<?php } ?>
		</ul>
	</div>


</div>



<script type="text/javascript">
<!--
$(document).ready(function(){


	$("#mb_photo").on("change",function(){
		$("#multiform").submit();
	});

	function getDoc(frame) {
	 var doc = null;
	 
	 // IE8 cascading access check
	 try {
		 if (frame.contentWindow) {
			 doc = frame.contentWindow.document;
		 }
	 } catch(err) {
	 }

	 if (doc) { // successful getting content
		 return doc;
	 }

	 try { // simply checking may throw in ie8 under ssl or mismatched protocol
		 doc = frame.contentDocument ? frame.contentDocument : frame.document;
	 } catch(err) {
		 // last attempt
		 doc = frame.document;
	 }
	 return doc;
	}

	$("#multiform").submit(function(e){

		var formObj = $(this);
		var formURL = formObj.attr("action");

		if(window.FormData !== undefined){  // for HTML5 browsers if(false)
				var formData = new FormData(this);
				$.ajax({
					url: formURL,
					type: 'POST',
					data:  formData,
					mimeType:"multipart/form-data",
					contentType: false,
					cache: false,
					processData:false,
					success: function(data, textStatus, jqXHR)
					{
						var json = $.parseJSON(data); 
						if(json["result"]=="ok"){
							$("#profile_img").attr("src", json["mb_photo"]);
						} else{
							alert(json["msg"]);
						}		
					},
					error: function(jqXHR, textStatus, errorThrown) 
					{
						alert("업로드에 실패하였습니다");
					} 	        
			   });
				e.preventDefault();
				
		} else {  //for olden browsers

			//generate a random id
			var  iframeId = 'unique' + (new Date().getTime());

			//create an empty iframe
			var iframe = $('<iframe src="javascript:false;" name="'+iframeId+'" />');

			//hide it
			iframe.hide();

			//set form target to iframe
			formObj.attr('target',iframeId);

			//Add iframe to body
			iframe.appendTo('body');
			iframe.load(function(e)
			{
				var doc = getDoc(iframe[0]);
				var docRoot = doc.body ? doc.body : doc.documentElement;
				var data = docRoot.innerHTML;

				var json = $.parseJSON(data); 
				if(json["result"]=="ok"){				
					$("#profile_img").attr("src", json["mb_photo"]);
				} else{
					alert(json["msg"]);
				}	

			});

		}

	});


});
//-->
</script>

<?php
include_once $nfor[skin_path]."tail.php";
?>