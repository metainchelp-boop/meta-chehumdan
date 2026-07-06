<style>
.member_sc { position: relative; margin: -1px 0px 0px -1px; font-size:14px; border-bottom:solid 0px #efefef; padding: 27px 15px 0px 15px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;background-color:#FFFFFF;}
.member_sc .member_inner{overflow:hidden; padding:20px 0px 30px;}
.member_sc .member_inner .left{float:left; width:50%; text-align:left;}
.member_sc .profile_img{background:url('/skin/demo/img/pro.png') no-repeat;background-position:0 -0px; position:relative; display:block; width:80px; height:80px; margin:0px auto; border-radius:80px; background-size:80px;}
.member_sc .profile_img .set { position: absolute; right: 0px; bottom: 0px;display: inline-block; width: 24px; height: 24px; background: url('<?=$nfor[skin_path]?>img/set.png') no-repeat; background-position: -0px -0px;  vertical-align: top; overflow: hidden;  line-height: 9999px; z-index: 10;}
.member_sc .profile_img .mask { position: absolute; left:0;  top:0; background: url('/skin/demo/img/mask.png') no-repeat; background-size:80px; display: block; width: 80px; height: 80px;}

.member_sc .m_name{display:block;font-size:18px; width:100%;text-align:left; line-height:24px; margin-top:0px; color:#000;}
.member_sc .m_id{display:block;font-size:14px; width:100%;text-align:left; line-height:18px; margin-bottom:0px}
.member_sc .note{ position:relative; display:inline-block; font-size:12px; padding:3px 10px 3px 25px; background-color:#fff; border:solid 1px #dcdcdc; margin-top:13px; border-radius:3px;}
.member_sc .note:before{display: inline-block; position: absolute; left:5px;  top:3px;clear: both; content: ''; width:15px; height:15px; background:url(/skin/demo/img/note_ico.png)center no-repeat; background-size:15px;}


.member_line .my_list{width:100%; overflow:hidden; border-bottom:solid 1px #efefef; border-top:solid 1px #efefef;  height:55px;}
.member_line .my_list .left{float:left; line-height:55px;}
.member_line .my_list .left a {font-size:14px; color:#666;}
.member_line .my_list .left a img{vertical-align:-5px; margin-right:10px;}
.member_line .my_list .right{float:right; line-height:55px;font-family:montserrat; font-weight: 400;}
.member_line .my_list .right .txt-color{color:#000; font-size:15px; }

.member_line .add_blog{font-size:14px; border-bottom:solid 1px #efefef; height:55px; line-height:55px; position:relative; padding-left:40px;}
.member_line .add_inst{font-size:14px; border-bottom:solid 1px #efefef; height:55px; line-height:55px; position:relative; padding-left:40px;}
.member_line .add_youtube{font-size:14px; border-bottom:solid 1px #efefef; height:55px; line-height:55px; position:relative; padding-left:40px;}

.member_line .blog{background: url('<?=$nfor[skin_path]?>img/blog_off.png') center no-repeat; width:30px; height:30px;  position:absolute; left:0px; top:13px;background-size:30px;}
.member_line .blog_on{background: url('<?=$nfor[skin_path]?>img/blog.png') center no-repeat; width:30px; height:30px;  position:absolute; left:0px; top:13px; background-size:30px;}

.member_line .inst{background: url('<?=$nfor[skin_path]?>img/instgram.png') center no-repeat; width:30px; height:30px;  position:absolute; left:0px; top:13px; background-size:30px;}
.member_line .inst_on{background: url('<?=$nfor[skin_path]?>img/instgram.png') center no-repeat; width:30px; height:30px;  position:absolute; left:0px; top:13px; background-size:30px}
.member_line .youtube{background: url('<?=$nfor[skin_path]?>img/youtube_off.png') center no-repeat; width:30px; height:30px;  position:absolute; left:0px; top:13px; background-size:30px}
.member_line .youtube_on{background: url('<?=$nfor[skin_path]?>img/youtube.png') center no-repeat; width:30px; height:30px;  position:absolute; left:0px; top:13px; background-size:30px}

.member_line .attendance{padding:10px 0px;}
.member_line .attendance .attendance_btn{background-color:#18a8f1; display:block; color:#FFF; height:48px; font-size:17px; text-align:center; line-height:48px; position:relative; padding-left:32px; }
.member_line .attendance .attendance_btn:before{content:""; display:block; clear:both; position: absolute; left:20px; bottom:15px; width:20px; height:22px; background: url('<?=$nfor[skin_path]?>img/att_ico.png') center no-repeat;  }
.member_line .attendance .attendance_btn_ok{background-color:#747c8a;line-height:48px; color:#FFF; overflow:hidden; font-size:15px;}
.member_line .attendance .attendance_btn_ok .mon_att{display:block;float:left;width:50%; border-right:1px solid #b5bcc7; text-align:center}
.member_line .attendance .attendance_btn_ok .all_att{display:block;float:left;width:50%; margin-left:-1px; text-align:center}

.member_line .attendance .attendance_btn_ok .num_att{font-family:Montserrat;letter-spacing: -0.02em !important; color:#ffff00;}
.marb13{margin-bottom:13px;}
.member_sc .main_txt{font-size:23px; display:block; }
.member_sc .sub_txt{display:block; font-size:14px; color:#000; margin-bottom:20px;} 
</style>

<?php
if($member[mb_no]){
?>

<div class="member_sc">
	<div class="member_inner">
		<div class="left">
			<a onclick="$('#mb_photo').click()" class="profile_img">
				<img class="profile_img" id="profile_img" src="<?=$member["mb_photo"]?thumbnail($nfor[path]."/data/member/mb/".$member["mb_photo"],80,80,0,1):$nfor[skin_path]."img/pro.png"?>" alt="" >
				<span class="set">설정변경</span>
				<span class="mask"></span>
			</a>
			<form name="multiform" id="multiform" action="json.php" method="post" enctype="multipart/form-data" style="display:none;">
				<input type="hidden" name="mode"  value="mb_photo_upload"/>
				<input type="file" name="mb_photo" id="mb_photo"/>
			</form>	
		</div>
		<div class="left">
			
			<?php if($member[mb_nick]){ ?><span class="m_name"><?=$member[mb_nick]?>님</span><?php } ?>
			<span class="m_id"><?=$member[$member_config[cf_mb_id_type]]?></span>
			<a href="javascript:note()" class="note">쪽지 <b class="txt_num point_color3"><?=number_format($member[mb_note])?> </b>통</a>  
		
		</div>
	</div>
</div>

<div class="member_line">

	<div class="my_list">
		<div class="left"><a href="point_list.php"><img src="<?=$nfor[skin_path]?>img/pico.png">나의 포인트</a></div>
		<div class="right"><a href="point_list.php"><em class="txt-color" ><?=number_format($member[mb_point])?></em> <b>P</b></a></div>
	</div>
	<div class="add_blog"> 
		<?php if($member[mb_blog]){ ?>
		<a href="<?=blog_make_url($member[mb_blog])?>" target="_blank"><b class="blog_on"></b><?=$member[mb_nick]?$member[mb_nick]:$member[$member_config[cf_mb_id_type]]?>님의 블로그</a>
		<?php } else{ ?>
		<a href="member_form.php"><b class="blog"></b>블로그를 등록해주세요</a>		
		<?php } ?>
	</div>
	<div class="add_inst">
		<?php if($member[mb_instagram]){ ?>
		<a href="<?=instagram_make_url($member[mb_instagram])?>" target="_blank"><b class="inst_on"></b><?=$member[mb_nick]?$member[mb_nick]:$member[$member_config[cf_mb_id_type]]?>님의 인스타그램</a>
		<?php } else{ ?>
		<a href="member_form.php"><b class="inst"></b>인스타그램을 등록해주세요</a> 
		<?php } ?>
	</div>
	<div class="add_youtube">		
		<?php if($member[mb_youtube]){ ?>
		<a href="<?=youtube_make_url($member[mb_youtube])?>" target="_blank"><b class="youtube_on"></b><?=$member[mb_nick]?$member[mb_nick]:$member[$member_config[cf_mb_id_type]]?>님의 유튜브</a>
		<?php } else{ ?>
		<a href="member_form.php"><b class="youtube"></b>유튜브을 등록해주세요</a> 
		<?php } ?>
	</div>
	<div class="attendance">
		<a href="attendance.php" class="attendance_btn">출석체크하기</a>
		<?php
		if($member[mb_no]){
			$this_month = sql_fetch("select count(*) as cnt from nfor_attendance where at_mb_no='$member[mb_no]' and date_format(at_datetime,'%Y-%m') ='".date("Y-m")."'");
		?>
		<div class="attendance_btn_ok">
			<span class="mon_att">이번달출석 <b class="num_att"><?=number_format($this_month[cnt])?></b></span>
			<span class="all_att">총누적 출석 <b class="num_att"><?=number_format($member[mb_attendance])?></b></span>
		</div>
		<?php } ?>
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
<? } else{ ?>
<div class="member_sc">
	
	<span class="main_txt point_color4">커뮤니티</span>
	<span class="sub_txt">로그인 해주세요.</span>
</div>
<? } ?>