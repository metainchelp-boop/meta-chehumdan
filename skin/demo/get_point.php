<?php
include_once $nfor[skin_path]."mypage_head.php";
?>

<?php
include_once $nfor[skin_path]."inc_point.php";
?>

<form name="review_form" id="review_form" method="post">
<input type="hidden" name="mode" value="insert">
<?php
$write[ftimestamp] = date("YmdHis").substr(microtime(),2,6);
?>
<?=admin_hidden($write,"ftimestamp")?>

<div class="board_write" >

<table cellpadding="0" cellspacing="0" border="0" >
<colgroup>
	<col style="width:20%">
	<col style="width:70%">
</colgroup>
<tr>
	<th>예금주</th>
	<td><?=admin_text($write,"pb_name","input300")?></td>
</tr>
<tr>
	<th>입금은행</th>
	<td>
		<select name="pb_bank" id="pb_bank" class="input200">
			<option value="">입금은행
			<option value="국민">국민
			<option value="기업">기업
			<option value="농협">농협
			<option value="신한(구조흥포함)">신한(구조흥포함)
			<option value="우체국">우체국
			<option value="SC(스탠다드차타드)">SC(스탠다드차타드)
			<option value="하나(구외환포함)">하나(구외환포함)
			<option value="한국씨티(구한미)">한국씨티(구한미)
			<option value="우리">우리
			<option value="경남">경남
			<option value="광주">광주
			<option value="대구">대구
			<option value="도이치">도이치
			<option value="부산">부산
			<option value="산업">산업
			<option value="수협">수협
			<option value="전북">전북
			<option value="제주">제주
			<option value="새마을금고">새마을금고
			<option value="신용협동조합">신용협동조합
			<option value="홍콩상하이(HSBC)">홍콩상하이(HSBC)
			<option value="저축은행">저축은행
			<option value="뱅크오브아메리카">뱅크오브아메리카
			<option value="케이뱅크">케이뱅크
			<option value="카카오뱅크">카카오뱅크
			<option value="제이피모간체이스">제이피모간체이스
			<option value="비엔피파리바">비엔피파리바
			<option value="중국건설은행">중국건설은행
			<option value="산림조합">산림조합
			<option value="중국공상">중국공상	
		</select>
	</td>
</tr>
<tr>
	<th>입금계좌번호</th>
	<td><?=admin_text($write,"pb_bank_number","input300")?></td>
</tr>
<tr>
	<th>인출가능포인트</th>
	<td><span class="txt_num"><?=number_format($member[mb_point])?></span>포인트</td>
</tr>
<tr>
	<th>출금요청포인트</th>
	<td><?=admin_text($write,"pb_point","inp")?> 포인트</td>
</tr> 
<? if($config[cf_jumin]=="1"){ ?>
<tr>
	<th>주민등록번호</th>
	<td><?=admin_text($write,"pb_jumin1","inp","maxlength='6'")?> - <?=admin_password($write,"pb_jumin2","inp","maxlength='7'")?></td>
</tr>
<? } ?>
<? if($config[cf_jumin_file]=="1"){ ?>
<tr>
	<th>주민등록증 사본</th>
	<td>
	
			<?=admin_button("jumin_upload_btn", "찾아보기")?>			
			
			<input type="hidden" name="pb_file1" id="pb_file1">
			<input type="hidden" name="pb_filename1" id="pb_filename1">
			<span id="pb_filename1_span"></span>

	</td>
</tr>
<? } ?>
<? if($config[cf_bank_file]=="1"){ ?>
<tr>
	<th>통장 사본</th>
	<td>
	
			<?=admin_button("bank_upload_btn", "찾아보기")?>			
			
			<input type="hidden" name="pb_file2" id="pb_file2">
			<input type="hidden" name="pb_filename2" id="pb_filename2">
			<span id="pb_filename2_span"></span>

	</td>
</tr>
<? } ?>
</table>
</div>
<div class="board_btn_zone">
<span class="btn_pack"><input type="button" value="출금신청" class="btn_lg black" id="rv_submit_btn"></span>
</div>

</form>

<SCRIPT LANGUAGE="JavaScript">
<!--
window.onload = function() {

	var uploader1 = new ss.SimpleUpload({
		  button: 'jumin_upload_btn',
		  url: 'file_upload.php?data=jumin',
		  responseType: 'json',
		  name: 'uploadfile',
		  multiple: true,
		  onSubmit: function(filename, extension) {

		  },
		  onComplete:   function(filename, response) {
			$("#pb_file1").val(response.filename);
			$("#pb_filename1").val(response.file);
			$("#pb_filename1_span").html(response.file);
			if(!response){
				alert(filename + 'upload failed');
				return false;
			}

		  }
	});

	var uploader2 = new ss.SimpleUpload({
		  button: 'bank_upload_btn',
		  url: 'file_upload.php?data=bank',
		  responseType: 'json',
		  name: 'uploadfile',
		  multiple: true,
		  onSubmit: function(filename, extension) {

		  },
		  onComplete:   function(filename, response) {
			$("#pb_file2").val(response.filename);
			$("#pb_filename2").val(response.file);
			$("#pb_filename2_span").html(response.file);
			if(!response){
				alert(filename + 'upload failed');
				return false;
			}

		  }
	});
};
$(document).on("click","#rv_submit_btn",function(){

	$(this).hide();

	$.ajax({
		type:"post",
		data :$("#review_form").serialize(),
		url:"get_point.php",
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
<script>
$(function() {
  $('#pb_point').on('change', function() {
     var n = $(this).val(); 
     n = Math.floor(n/1000) * 1000; 
     //alert(n);  
     $(this).val(n);
     alert("포인트 출금 신청은 천원단위로 가능합니다.");
  });
});
</script>
<?php
include_once $nfor[skin_path]."mypage_tail.php";
?>