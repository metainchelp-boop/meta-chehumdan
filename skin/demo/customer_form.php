<?php
if(basename($PHP_SELF)=="customer_form.php"){
	include_once $nfor['skin_path']."mypage_head.php";
} else{
	include_once $nfor['skin_path']."cus_head.php";
}
?>

<style>
#ca_name{width:150px;}
#wr_name{width:20%;}
#wr_email{width:30%}
#wr_hp2{width:80px;}
#wr_hp3{width:80px;}
#cs_subject{width:100%;}
#cs_category{width:20%;}
.file_upload_preview { overflow:hidden; }
.file_upload_preview li { float:left; }
.preview_file_del { margin-left:5px; margin-right:10px; cursor:pointer; }
</style>

<div class="board_write">

	<form name="customer_form" id="customer_form" method="post" autocomplete="off">
	<input type="hidden" name="mode" value="insert">
	<?=admin_hidden($write,"cs_timestamp")?>
	<table border="0" cellpadding="0" cellspacing="0" class="tb_form">
	<colgroup>
		<col style="width:160px">
		<col>
	</colgroup>
	<tr>
		<th>문의유형</th>
		<td><?=admin_select($write,"cs_category","width-sm width-lg","","0")?></td>
	</tr>
	<tr>
		<th>이름</th>
		<td><?=admin_text($write,"cs_name","width-sm")?></td>
	</tr>
	<tr>
		<th>연락처</th>
		<td><?=admin_text($write,"cs_tel")?></td>
	</tr>
	<tr>
		<th>이메일</th>
		<td><?=admin_text($write,"cs_email","width-lg")?></td>
	</tr>	
	<tr>
		<th>제목</th>
		<td><?=admin_text($write,"cs_subject","width100p")?></td>
	</tr>
	<tr>
		<th>내용</th>
		<td><?=admin_textarea($write,"cs_memo")?></td>
	</tr>
	<tr>
		<th>첨부파일</th>
		<td>

			<?=admin_button("file_upload_btn", "찾아보기")?>			
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
		<th>보안확인</th>
		<td><div class="g-recaptcha" data-sitekey="<?=$api['api_google_recaptcha_siteid']?>"></div></td>
	</tr>
	<?php } ?>
	<tr>
		<td colspan="2" class="agreememt_wrap">
		<div class="agreememt"><?=agreement("customer")?></div>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="cs_agree">
		<?=admin_checkbox($write,"cs_agree","","","개인정보 수집, 이용에 동의합니다")?>
		<a class="agree_view">수집동의 약관보기</a>
		</td>
	</tr>
	</table>

	<div class="board_btn_zone">
		<span class="btn_pack"><?=admin_submit("cs_submit_btn", "문의하기","btn_lg black")?></span>
		<?php if(basename($PHP_SELF)=="customer_form.php"){ ?><span class="btn_pack"><a href="customer_list.php" class="btn_lg white">목록으로</a></span><?php } ?>
	</div>
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
	if($(this).text()=="수집동의 약관보기"){
		$(this).text("수집동의 약관닫기");
	} else{
		$(this).text("수집동의 약관보기");
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
if(basename($PHP_SELF)=="customer_form.php"){
	include_once $nfor['skin_path']."mypage_tail.php";
} else{
	include_once $nfor['skin_path']."cus_tail.php";
}
?>