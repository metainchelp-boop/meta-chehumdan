<?php
include_once $nfor['skin_path']."cus_head.php";
?>

<style>
.cooper_title { font-size:24px;  font-weight:200; margin-bottom:20px; display:block; }
.cooper_sub_tit{ font-size:16px; display:block; margin-bottom:60px; line-height:18px; color:#7d7e80; }
#cp_subject{width:100%;}

.file_upload_preview { overflow:hidden; }
.file_upload_preview li { float:left; }
.preview_file_del { margin-left:5px; margin-right:10px; cursor:pointer; }
</style>

<div class="cooper_title_wrap">
	<span class="cooper_title">입점상담 절차안내 및 상담신청</span>
	<span class="cooper_sub_tit">- 심사 결과는 이메일로 발송됩니다.<br>- 주말 또는 휴무일 문의 시에는 답변이 다소 지연될 수 있으니, 양해해 주시기 바랍니다.</span>
	<div class="board_write">


		<form name="cooperation_form" id="cooperation_form" method="post" autocomplete="off">
		<input type="hidden" name="mode" value="insert">
		<?=admin_hidden($write,"cp_timestamp")?>

		<table cellpadding="0" cellspacing="0" border="0" class="tb_form">
		<colgroup>
			<col style="width:160px">
			<col>
			<col style="width:160px">
			<col>
		</colgroup>
		<tr>
			<th>분류</th>
			<td><?=admin_select($write,"cp_category","width-sm","","0")?></td>
			<th>회사명</th>
			<td><?=admin_text($write,"cp_company","width-sm")?></td>
		</tr>
		<tr>
			<th>담당자명</th>
			<td><?=admin_text($write,"cp_name","width-sm")?></td>
			<th>담당자 연락처</th>
			<td><?=admin_text($write,"cp_tel")?></td>
		</tr>
		<tr>
			<th>담당자 이메일</th>
			<td><?=admin_text($write,"cp_email","width-lg")?></td>
			<th>홈페이지</th>
			<td><?=admin_text($write,"cp_homepage","width-lg")?></td>
		</tr>
		<tr>
			<th>제목</th>
			<td colspan="3"><?=admin_text($write,"cp_subject","width100p")?></td>
		</tr>
		<tr>
			<th>내용</th>
			<td colspan="3"><?=admin_textarea($write,"cp_memo")?></td>
		</tr>
		<tr>
			<th>첨부파일</th>
			<td colspan="3">

				<?=admin_button("file_upload_btn", "찾아보기")?>

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

			</td>
		</tr>
		<?php if($api['api_google_recaptcha_use']=="1" and !$nfor['is_app'] and $is_guest){ ?>
		<tr>
			<th>보안확인</th>
			<td colspan="3"><div class="g-recaptcha" data-sitekey="<?=$api['api_google_recaptcha_siteid']?>"></div></td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="4" class="agreememt_wrap">
			<div class="agreememt"><?=agreement("cooperation")?></div>
			</td>
		</tr>
		<tr>
			<td colspan="4" class="cp_agree">
			<div class="basic_chk"><?=admin_checkbox($write,"cp_agree","",""," 개인정보 수집, 이용에 동의합니다")?></div>
			<a class="agree_view">수집동의 약관보기</a>
			</td>
		</tr>
		</table>
	</div>
	<div class="board_btn_zone">
		<span class="btn_pack"><?=admin_submit("cp_submit_btn", "문의하기","btn_lg black")?></span>
	</div>
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
			str += "<img src='<?=$nfor['skin_path']?>/img/x.png' class='preview_file_del'>";
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
	if($(this).text()=="수집동의 약관보기"){
		$(this).text("수집동의 약관닫기");
	} else{
		$(this).text("수집동의 약관보기");
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
		},
		error : function(request, status, error ) {   // 오류가 발생했을 때 호출된다. 
		console.log(request);
		console.log(status);
		console.log(error);

			console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
		}
	});
	event.preventDefault();
});
</script>

<?php
include_once $nfor['skin_path']."cus_tail.php";
?>