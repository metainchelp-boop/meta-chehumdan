<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.url_input_wrap{ padding:15px; background-color:#FFF;-webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; position:relative; font-size:13px; color:#333; }
.url_input_wrap .txt1{ display:block; font-size:18px; font-weight:300; color:#000; margin-top:15px;}
.url_input_wrap .txt2{ display:block;  font-size:13px; color:#999; margin-bottom:20px;}
.url_input_wrap .application_tbl{width:100%; border-top:solid 1px #333;}
.url_input_wrap .application_tbl li {padding:10px 0px 5px;; text-align:left; font-size:16px; font-weight:300; }
.url_input_wrap .url_input_tit{ line-height: 23px; font-size: 16px; color: #000;  position: relative;   padding: 10px 0px;} 
.url_input_wrap .blogtxt{background-color:#f4f4f4;color:#666; border:solid 1px #dcdcdc; width:100%;padding:10px;height:50px; border-radius:3px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}
.url_input_wrap .btn01{display:block; color:#FFFFFF; border: solid 1px #515b6b; background-color:#515b6b; line-height:25px;  font-size:18px;  -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}
.url_input_wrap .btn01:hover{color:#FFFFFF;}

.url_input_wrap .btn_del{position:absolute;top:0px;right:0px; display:inline-block; width:25px;height:25px;background:url("/skin/demo/img/ico_close30x30.png") no-repeat 50%;background-size:15px;background-color:rgba(0, 0, 0, 0.7);font-size:0; z-index:9999;}
.url_input_wrap .rv_img_thumb {position:relative; display:block;  width:80px; height:80px; text-indent: -999em; overflow:hidden; background-color:#ccc; z-index:3}
.btn_del.hide { display:none; }
.key_copy{display:inline-block; font-size:12px; font-weight:300;  background-color:#fff;  margin-left:15px; border:solid 1px #dcdcdc; width:60px; padding:3px 5px; text-align:center; margin-top:15px; cursor:pointer; }
.basic_form{}
.basic_form li{position:relative; padding:10px 15px 10px 15px; border-top:solid 0px #dcdcdc;}
.basic_form li + li{position:relative; padding: 10px 15px 10px 15px;; border-top:solid 1px #dcdcdc;}
.basic_form .tit_basic{ display:block;font-family: 'notokr-regular';   color:#000; letter-spacing:-1px;  font-size:14px; margin-bottom:10px;}
.basic_form .content{display:block;  line-height:24px; font-size:14px; color:#666; min-height:55px; }
#cp_keyword { border:0; width:1px; height:1px; }

#cp_hashtag { border:0; width:1px; height:1px; }


.marbottom10{margin-bottom:10px;}
</style>

<form name="review_form" id="review_form" method="post" enctype="multipart/form-data">
<input type="hidden" name="mode" value="update">
<?=admin_hidden($write,"rv_id")?>
<?=admin_hidden($write,"rv_img")?>


<div class="url_input_wrap">

	<span class="txt1"><?=$campaign[cp_subject]?></span>
	<span class="txt2"><?=$campaign[cp_description]?></span>

	<?php if($write[rv_step]=="7"){ ?>
	<ul class="application_tbl">
		<li>
			<span class="url_input_tit">수정요청사항</span>
		</li>
		<li>
			<?=nl2br($write[rv_edit])?>
		</li>
	</ul>
	<?php } ?>
	<?php if($write[rv_step]=="2"){ ?>
	<hr class="marbottom10"></hr>
	<span class="url_input_tit marbottom10" >리뷰작성가이드 </span>
	<div class="box">
		<div class="basic_form">
			<ul>
				<!-- <li id="nfor_page22">
					<span class="tit_basic">리뷰가이드</span>
					<span class="content"><?=($campaign[cp_guide_add])?></span>
				</li> -->
				<li id="nfor_page2">
					<span class="tit_basic">제공내역</span>
					<span class="content"><?=($campaign[cp_reward])?></span>
				</li>
				<li id="nfor_page3">
					<span class="tit_basic">키워드<b data-clipboard-target="#cp_keyword" class="key_copy">키워드복사</b></span>
					<span class="content"><?=nl2br($campaign[cp_keyword])?></span>
				</li>
				<li id="nfor_page33">
					<span class="tit_basic">해시태그<b data-clipboard-target="#cp_hashtag" class="key_copy">해시태그복사</b></span>
					<span class="content"><?=nl2br($campaign[cp_hashtag])?></span>
				</li>
				<li id="nfor_page4">
					<span class="tit_basic">포스팅 안내<!-- 리뷰작성시 안내사항 --></span>
					<span class="content"><?=($campaign[cp_guide])?></span>
				</li>
				<li id="nfor_page5">
					<span class="tit_basic">추가 안내</span>
					<span class="content"><?=($campaign[cp_notice])?></span>
				</li>
			</ul>
		<?=admin_textarea($campaign,"cp_keyword","","readonly")?>
		<?=admin_textarea($campaign,"cp_hashtag","","readonly")?>
		</div>
	</div>
	<?php } ?>





		<?php if($write[rv_media]=="blog"){ ?>


<style>

.tabmenu{overflow:hidden; margin-top:10px; margin-bottom:10px}
.tabmenu li{float:left;position:relative;}
.tabmenu li {display:block; position:relative; border: 1px solid #333; margin-left: -1px;   height: 40px; padding:0px 15px; border: 1px solid #999;  line-height: 40px;text-align: center; background-color: #f2f2f2; box-sizing: border-box;}
.tabmenu li a{color:#888;}
.tabmenu .active{background: #fff; color: #000; border: 1px solid #333;  margin-left: -1px;}
.tabmenu .active a{color:#000;}
.tabmenu li:nth-child(1) {margin-left: -0px;}

.tab-cont { display:none; border:solid 1px #dcdcdc;  padding:20px; min-height:140px; background-color:#fff; font-size:12px;}
#tab1 { display:block; }
#tab1 textarea{font-family: "montserrat"; letter-spacing:0px; line-height:24px; font-size:16px;width: 100%;height: 120px;padding: 7px 8px; border: 1px solid #d0d0d0;  line-height: 16px;color: #828284; -webkit-box-sizing: border-box;  -moz-box-sizing: border-box; box-sizing: border-box; }
#tab2 span{display:block; font-size:12px; line-height:18px; color:#666; padding-left:0px;}
#tab2 img{border:solid 1px #efefef; padding:10px; margin:10px 0px; width:100%; box-sizing: border-box;}
.coution{display:block; background-color:#fafafa; padding:20px; border:solid 1px #efefef;  margin:20px 0px; }
.coution span{position:relative; display:block; font-size:12px; line-height:18px; color:#666; padding-left:14px; }
.coution span b{font-weight:normal;}
.coution span:before{display: block; clear: both; content: ''; position: absolute;left: 0px;top: 13px;width: 2px; height: 2px;background-color: #666; }

.floating{position:absolute;; top: 0px; width: 300px; min-height:550px;  margin:0px auto; padding:10px 15px; z-index:999; }
</style>

		<ul>
			<li> <span style="color:#000; font-size:18px; margin-top:20px; display:block;">블로그 트레픽 확인코드</span ></li>
			<li>
			<ul class="tabmenu">
				<li class="active" id="tab1_li"><a data-tab="#tab1">HTML코드로 삽입</a></li>
				<li><a data-tab="#tab2">드레그하여 붙여넣기</a></li>
			</ul>
			
			<div id="tab1" class="tab-cont"> <?=admin_textarea($write,"rv_traffic_code","width_100")?></div>

			<div id="tab2" class="tab-cont"> 
				<a href="<?=$campaign_url?>" target="_blank"><img src="<?=$traffic_url?>" border="0"></a>			
				<span>HTML편집모드가 불가능한 블로그는 배너형을 이용을 권장하며 네이버 스마트 에디터 3.0 또는 소스편집이 불가능한 환경일 경우 아래의 이미지를 복사하여 넣어 주세요.</span>
			</div>	
			</li>
			<li>
				<div class="coution">
					<span><b class=" point_color4 ">캠페인 종료시까지 공개설정을 유지</b>해주셔야 선정확률이 높아집니다.</span>
					<span><b class=" point_color4 ">포스팅은 전체공개 스크랩 허용을 설정</b>한 후 아래의 포스팅 상단부분에 붙여주세요.</span>
					<span><b class=" point_color4 ">모바일은 열람및 카운팅 중복체크가 불가</b>하며,브라우저별 통계를 지원하지 않습니다.</span>
					<span>블로그내 이미지 로딩으로  기록되는 기능이므로 , <b class=" point_color4 ">블로그자체 썸네일 서버를 이용하여 이미지를 표시하는 경우 정상 작동 하지 않습니다</b>.</span>  
				</div>
			</li>
		</ul>

<script>
$(document).on("click", ".tabmenu li", function(){
	$(".tabmenu li").removeClass("active");
	$(this).addClass("active");
	$(".tab-cont").hide();
	$($(this).find("a").data("tab")).show();
});
</script>






		<?php } ?>








	<ul class="application_tbl">
		<?php if($campaign[cp_check2]){ ?>
		<li>
			<span class="url_input_tit">구매자명 <span style="color:#e74c3c">*</span></span>
		</li>
		<li>
			<input type="text" name="rv_buyer_name" id="rv_buyer_name" value="<?=$write[rv_buyer_name]?>" placeholder="실제 주문(구매)하신 분의 성함" maxlength="60">
			<div class="coution" style="margin-top:10px;">
				<span><b class="point_color4">⚠️ 실제 주문(구매)하신 분의 성함을 정확히 입력</b>해주세요.</span>
				<span>회원명과 다를 경우 <b class="point_color4">본인 확인 지연·페이백 보류 등 불이익</b>이 발생할 수 있습니다.</span>
			</div>
		</li>
		<li>
			<span class="url_input_tit">구매평 캡쳐 <span style="color:#e74c3c">*</span></span>
		</li>
		<li>
			<a class="rv_img_thumb">
				<img class="rv_img_thumb" id="rv_buy_img_thumb" src="<?=$write["rv_buy_img"]?thumbnail($nfor[path]."/data/review/".$write["rv_buy_img"],60,60,0,1):"/img/gong1.png"?>" alt="구매평 캡쳐" onclick="$('#rv_buy_img_file').click()">
				<b class="btn_del_buy <?=$write["rv_buy_img"]?"":"hide"?>"></b>
			</a>
			<input type="hidden" name="rv_buy_img" id="rv_buy_img" value="<?=$write["rv_buy_img"]?>">
		</li>
		<?php } ?>
		<li>
			<span class="url_input_tit">리뷰등록한 주소(URL)</span>
		</li>
		<li>
			<?=admin_text($write,"rv_url","","placeholder=''")?>
		</li>

		<li>
			<span class="url_input_tit">간단 리뷰설명</span>
		</li>
		<li>
			<?=admin_textarea($write,"rv_review","","placeholder=''")?>
		</li>
		<li>
			<span> 리뷰 대표이미지</span>
		</li>
		<li>
			<a class="rv_img_thumb" >
				<img class="rv_img_thumb" id="rv_img_thumb" src="<?=$write["rv_img"]?thumbnail($nfor[path]."/data/review/".$write["rv_img"],60,60,0,1):"/img/gong1.png"?>" alt="리뷰 대표이미지" onclick="$('#rv_img_file').click()">
				<b class="btn_del <?=$write["rv_img"]?"":"hide"?>"></b>
			</a>
		</li>
		<li>
			<div style="margin:0 auto; text-align:center;"><input type="button" class="btn01" id="rv_submit_btn" value="등록하기" style="margin:0 auto;"></div>
		</li>
	</ul>

</div>

</form>

<SCRIPT LANGUAGE="JavaScript">
<!--
$(document).on("click","#rv_submit_btn",function(){

	$(this).hide();

	$.ajax({
		type:"post",
		data :$("#review_form").serialize(),
		url:"url_input_win.php",
		success:function(response){
			console.log(response);
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				if(json["msg"]){
					alert(json["msg"]);
				}
				if(json["url"]){
					location.href = json["url"];
				} else{
					location.reload();
				}
			} else{
				if(json["msg"]){
					alert(json["msg"]);
				}
				if(json["result"]){
					$("#"+json["result"]).focus();
				}
				$("#rv_submit_btn").show();
			}
		}
	});
	event.preventDefault();
});
//-->
</SCRIPT>







<form name="rv_img_form" id="rv_img_form" action="json.php" method="post" enctype="multipart/form-data" style="display:none;">
	<input type="hidden" name="mode"  value="rv_img_upload"/>
	<input type="file" name="rv_img_file" id="rv_img_file"/>
</form>
<input type="file" name="rv_buy_img_file" id="rv_buy_img_file" accept="image/*" style="display:none;"/>	
<script type="text/javascript">
<!--
$(document).on("click",".btn_del",function(){
	if(confirm("삭제하시겠습니까?")){
		$("#rv_img_thumb").attr("src","/img/gong1.png");
		$("#rv_img").val("");
		$(this).addClass("hide");
	}
});

// 구매평 캡쳐 삭제/업로드 (페이백)
$(document).on("click",".btn_del_buy",function(){
	if(confirm("삭제하시겠습니까?")){
		$("#rv_buy_img_thumb").attr("src","/img/gong1.png");
		$("#rv_buy_img").val("");
		$(this).addClass("hide");
	}
});
$(document).on("change","#rv_buy_img_file",function(){
	var fd = new FormData();
	fd.append("mode","rv_buy_img_upload");
	fd.append("rv_buy_img_file", this.files[0]);
	$.ajax({
		url:"json.php", type:"POST", data:fd, contentType:false, cache:false, processData:false,
		success:function(data){
			var json = $.parseJSON(data);
			if(json["result"]=="ok"){
				$("#rv_buy_img_thumb").attr("src", json["rv_buy_img_thumb"]);
				$("#rv_buy_img").val(json["rv_buy_img"]);
				$(".btn_del_buy").removeClass("hide");
			} else{ alert(json["msg"]); }
		},
		error:function(){ alert("업로드에 실패하였습니다"); }
	});
});

$(document).ready(function(){


	$("#rv_img_file").on("change",function(){
		$("#rv_img_form").submit();
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

	$("#rv_img_form").submit(function(e){

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
							$("#rv_img_thumb").attr("src", json["rv_img_thumb"]);
							$("#rv_img").val(json["rv_img"]);
							$(".btn_del").removeClass("hide");
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
					$("#rv_img_thumb").attr("src", json["rv_img_thumb"]);
					$("#rv_img").val(json["rv_img"]);
					$(".btn_del").removeClass("hide");
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