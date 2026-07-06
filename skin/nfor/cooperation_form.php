<?php
include_once $nfor['skin_path']."head.php";
?>

<style>
.customer_form_wrap { margin:0px; padding:10px 15px; width:100%; box-sizing:border-box; -webkit-box-sizing:border-box;  background-color:#FFF;}
.customer_form_wrap .box{ margin:20px  0px;}
.customer_form_wrap .box .title { display: inline-block; padding-bottom: 5px; font-weight: 600;  font-size: 17px; line-height: 20px; vertical-align: top; letter-spacing:-0.0625em;}
.customer_form_wrap .box .desc{font-size: 13px; line-height: 1.35em; color: #c2c7cc;  letter-spacing:-0.0625em;}
.wrap_customer_faq { display:none; }
.row_title {  margin-bottom:5px; margin-top:25px;font-size:15px;font-weight:bold;color:#555;}
.row { margin-bottom:5px; }
.col_1of3 { float:left; width:33.333%; }
.col_inner { margin-left:4px; }
.agreememt { border:solid 1px #dcdcdc; overflow-y:scroll; background-color:#fff; padding:10px; height:100px; font-size:12px; } 
.agree_view { background-color:#fafafa; border:solid 1px #dcdcdc; color:#333; font-size:13px; padding:5px; }
.agreememt_wrap { display:none; }

.file_upload_preview { overflow:hidden; }
.file_upload_preview li { float:left; }
.preview_file_del { margin-left:5px; margin-right:10px; cursor:pointer; }
#file_upload_btn{border:solid 1px #dcdcdc; background-color:#fff; color:#666;} 
#file_upload_btn:hover{border:solid 1px #ff284b; background-color:#fff; color:#ff284b;} 
.file_upload_preview{font-size:12px ; color:#666; padding:10px;}
.file_upload_preview li{height:25px;}

.btn_submit { margin-top:10px; }
</style>

<div class="customer_form_wrap">

	<div class="box">
		<h2 class="title">제휴 및 상담신청</h2>
		<p class="desc">전화연결이 어려우신 분들은 문의를 남겨 주시면 <strong>24시간이내</strong>로 담당자가 연락 드립니다. 주말 또는 휴무일 문의시에는 답변이 다소 지연될 수 있으니, 양해해 주시기 바랍니다.</p>
	</div>

	<form name="cooperation_form" id="cooperation_form" method="post" autocomplete="off">
	<input type="hidden" name="mode" value="insert">
	<?=admin_hidden($write,"cp_timestamp")?>

	<div class="row"><?=admin_select($write,"cp_category","","","0")?></div>
	<div class="row"><?=admin_text($write,"cp_company","width-sm","placeholder=\"회사명\"")?></div>
	<div class="row"><?=admin_text($write,"cp_homepage","width-sm","placeholder=\"홈페이지\"")?></div>

	<div class="row_title">답변받으실분</div>
	<div class="row"><?=admin_text($write,"cp_name","width-sm","placeholder=\"담당자명\"")?></div>
	<div class="row"><?=admin_text($write,"cp_tel","width-lg","placeholder=\"담당자 연락처\"")?></div>
	<div class="row"><?=admin_text($write,"cp_email","width-lg","placeholder=\"담당자 이메일\"")?></div>

	<div class="row_title">문의내용</div>
	<div class="row"><?=admin_text($write,"cp_subject","width100p","placeholder=\"제목을 입력해주세요\"")?></div>
	<div class="row"><?=admin_textarea($write,"cp_memo","","placeholder=\"내용을 입력해주세요\"")?></div>
	<div class="row">
		<?=admin_button("file_upload_btn", "첨부파일")?>		
		<ul class="file_upload_preview">
		<?php
		if(isset($write['cp_file'])){
			for($i=0; $i<count($write['cp_file']); $i++){
		?>
		<li>
			<?=$write['cp_filename'][$i]?>
			<img src='<?=$nfor['skin_path']?>/img/x.png' class='preview_file_del'>
			<input type='hidden' name='cp_file[]' value='<?=$write['cp_file'][$i]?>'><input type='hidden' name='cp_filename[]' value='<?=$write['cp_filename'][$i]?>'>
		</li>
		<?php
			}
		}
		?>
		</ul>
	</div>

	<?php if($api['api_google_recaptcha_use']=="1" and !$nfor['is_app'] and $is_guest){ ?>
	<div class="row">
		<div class="g-recaptcha" data-sitekey="<?=$api['api_google_recaptcha_siteid']?>"></div>
	</div>
	<?php } ?>

	<div class="row agreememt_wrap">
		<div class="agreememt"><?=agreement("cooperation")?></div>
	</div>

	<div class="row">
		<?=admin_checkbox($write,"cp_agree","",""," 개인정보 수집, 이용에 동의합니다")?>
		<a class="agree_view">약관보기</a>
	</div>

	<div class="row"><?=admin_submit("cp_submit_btn", "문의하기" ,"btn_submit")?></div>
	</form>

</div>

<script>
window.onload = function() {

	var uploader = new ss.SimpleUpload({
		  button: 'file_upload_btn',
		  url: 'file_upload.php?data=cooperation',
		  responseType: 'json',
		  name: 'uploadfile',
		  multiple: true,
		  onSubmit: function(filename, extension) {

		  },
		  onComplete:   function(filename, response) {

			str = "<li>";
			str += response.file;
			str += "<img src='<?=$nfor['skin_path']?>img/x.png' class='preview_file_del'>";
			str += "<input type='hidden' name='cp_file[]' value='"+response.filename+"'><input type='hidden' name='cp_filename[]' value='"+response.file+"'>";
			str += "</li>";
			$(".file_upload_preview").append(str);

			if(!response){
				alert(filename + 'upload failed');
				return false;
			}

		  }
	});

};

$(document).on("click", ".preview_file_del", function(){
	$(this).parent('li').remove();
});

$(document).on("click",".agree_view",function(){
	if($(this).text()=="약관보기"){
		$(this).text("약관닫기");
	} else{
		$(this).text("약관보기");
	}

	$(".agreememt_wrap").toggle();
});

$(document).on("click","#cp_submit_btn",function(){
	$.ajax({
		type:"post",
		data :$("#cooperation_form").serialize(),
		url:"cooperation_form.php",
		success:function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				if(json["msg"]){
					alert(json["msg"]);
				}
				if(json["url"]){
					location.href = json["url"];
				}
			} else{
				if(json["msg"]){
					alert(json["msg"]);
				}
				if(json["result"]){
					$("#"+json["result"]).focus();
				}
			}
		}
	});
	event.preventDefault();
});
</script>

<?php
include_once $nfor['skin_path']."tail.php";
?>