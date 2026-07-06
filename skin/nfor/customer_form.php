<?php
include_once $nfor['skin_path']."head.php";
?>

<style>
.customer_form_wrap { padding:10px 15px; background-color:#fff; margin-top:10px; }
.customer_form_wrap .tbl { }
.customer_form_wrap .tbl th { text-align:left; font-size:.8em; font-weight:normal; letter-spacing:-.065em; border-bottom:dashed 1px #efefef; }
.customer_form_wrap .tbl td { padding:5px; border-bottom:dashed 1px #efefef;}
#cs_submit_btn { background-color:#ff3478; color:#fff; margin-top:10px; font-size:16px; height:45px;  line-height:45px; font-weight:bold; padding:0; }

.file_upload_preview { overflow:hidden; }
.file_upload_preview li { float:left; }
.preview_file_del { margin-left:5px; margin-right:10px; cursor:pointer; }
#file_upload_btn{border:solid 1px #dcdcdc; background-color:#fff; color:#666;} 
#file_upload_btn:hover{border:solid 1px #666; background-color:#fff; color:#666;} 
.file_upload_preview{font-size:12px ; color:#666; padding:10px;}
.file_upload_preview li{height:25px;}


.agreememt { border:solid 1px #dcdcdc; overflow-y:scroll; background-color:#fff; padding:10px; height:100px; font-size:12px; } 
.agree_view { background-color:#fafafa; border:solid 1px #dcdcdc; color:#333; font-size:13px; padding:5px; }
.agreememt_wrap { display:none; }
.agree_wrap { padding-top:10px; }
</style>



<?php
if(basename($PHP_SELF)=="customer_form.php"){
	include_once $nfor['skin_path']."inc_customer.php";
}
?>

<div class="customer_form_wrap">


	<form name="customer_form" id="customer_form" method="post" autocomplete="off">
	<input type="hidden" name="mode" value="insert">
	<?=admin_hidden($write,"cs_timestamp")?>

	<table width="100%" cellpadding="0" cellspacing="0" class="tbl">
	<colgroup>
		<col width="25%">
		<col>
	</colgroup>
	<tr>
		<th>문의유형</th>
		<td><?=admin_select($write,"cs_category","width-sm ","","0")?></td>
	</tr>
	<tr>
		<th>이름</th>
		<td><?=admin_text($write,"cs_name",""," placeholder=\"이름\"")?></td>
	</tr>
	<tr>
		<th>연락처</th>
		<td><?=admin_text($write,"cs_tel",""," placeholder=\"연락처\"")?></td>
	</tr>
	<tr>
		<th>이메일</th>
		<td><?=admin_text($write,"cs_email",""," placeholder=\"이메일\"")?></td>
	</tr>
	<tr>
		<td colspan="2"><?=admin_text($write,"cs_subject",""," placeholder=\"문의 제목을 입력해주세요\"")?></td>
	</tr>
	<tr>
		<td colspan="2"><?=admin_textarea($write,"cs_memo",""," placeholder=\"문의 내용을 입력해주세요\"")?></td>
	</tr>

	<tr>
		<td colspan="2">
		<input type="button" value="첨부파일" id="file_upload_btn">
		
		<ul class="file_upload_preview">
		<?php
		if(isset($write['cs_file'])){
			for($i=0; $i<count($write['cs_file']); $i++){
		?>
		<li>
			<?=$write['cs_filename'][$i]?>
			<img src='<?=$nfor['skin_path']?>/img/x.png' class='preview_file_del'>
			<input type='hidden' name='cs_file[]' value='<?=$write['cs_file'][$i]?>'><input type='hidden' name='cs_filename[]' value='<?=$write['cs_filename'][$i]?>'>	
		</li>
		<?php
			}
		}
		?>
		</ul>

		</td>
	</tr>
	<?php if($api['api_google_recaptcha_use']=="1" and !$nfor['is_app'] and $is_guest){ ?>
	<tr>
		<td colspan="2"><div class="g-recaptcha" data-sitekey="<?=$api['api_google_recaptcha_siteid']?>"></div></td>
	</tr>
	<?php } ?>
	<tr>
		<td colspan="2" class="agreememt_wrap">
		<div class="agreememt"><?=agreement("customer")?></div>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="agree_wrap">
		<?=admin_checkbox($write,"cs_agree","","","개인정보 수집, 이용에 동의합니다")?>
		<a class="agree_view">약관보기</a>
		</td>
	</tr>
	</table>

	<?=admin_submit("cs_submit_btn", "등록하기")?>

	</form>


</div>


<script>
window.onload = function() {

	var uploader = new ss.SimpleUpload({
		  button: 'file_upload_btn',
		  url: 'file_upload.php?data=customer',
		  responseType: 'json',
		  name: 'uploadfile',
		  multiple: true,
		  onSubmit: function(filename, extension) {

		  },
		  onComplete:   function(filename, response) {

			str = "<li>";
			str += response.file;
			str += "<img src='<?=$nfor['skin_path']?>img/x.png' class='preview_file_del'>";
			str += "<input type='hidden' name='cs_file[]' value='"+response.filename+"'><input type='hidden' name='cs_filename[]' value='"+response.file+"'>";
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

$(document).on("click","#cs_submit_btn",function(){
	$.ajax({
		type:"post",
		data :$("#customer_form").serialize(),
		url:"customer_form.php",
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