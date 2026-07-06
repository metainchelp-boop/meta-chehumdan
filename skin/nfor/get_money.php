<?php
include_once $nfor['skin_path']."head.php";
?>

<style>
.disin{display:inline-block;width:45%;}
.disin2{display:inline-block;width:80%;}
.file_upload_preview1{margin-top:10px;}
.file_upload_preview2{margin-top:10px;}
.point-txt{display:inline-block; width:80px;margin-left:10px;}
</style>

<div class="my_db_list">

	<?php include_once $nfor['skin_path']."inc_ad2.php"; ?>


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
		<td><?=admin_text($write,"pb_name","input300","placeholder='예금주'")?></td>
	</tr>
	<tr>
		<th>입금은행</th>
		<td>
			<select name="pb_bank" id="pb_bank" class="input200">
				<option value="">입금은행 선택
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
		<td><?=admin_text($write,"pb_bank_number","input300","placeholder='입금계좌번호'")?></td>
	</tr>
	<tr>
		<th>인출가능포인트</th>
		<td>인출가능포인트 : <span class="txt_num"><?=number_format($member[mb_point])?></span>포인트</td>
	</tr>
	<tr>
		<th>출금요청포인트</th>
		<td>출금요청포인트 : <?=admin_text($write,"pb_point","inp disin2 ")?><span class="point_txt"> 포인트</span></td>
	</tr> 
	<tr>
		<th>주민등록번호</th>
		<td>주민등록번호<br><?=admin_text($write,"pb_jumin1","inp disin","maxlength='6'")?> - <?=admin_password($write,"pb_jumin2","inp disin","maxlength='7'")?></td>
	</tr>
	<tr>
		<th>신분증사본</th>
		<td colspan="3">

			<?=admin_button("file_upload_btn1", "신분증사본 첨부하기")?>

			<ul class="file_upload_preview1"></ul>

		</td>
	</tr>
	<tr>
		<th>통장사본</th>
		<td colspan="3">

			<?=admin_button("file_upload_btn2", "통장사본 첨부하기")?>

			<ul class="file_upload_preview2"></ul>

		</td>
	</tr>
	</table>
	</div>
	<div class="board_btn_zone">
	<span class="btn_pack"><input type="button" value="출금신청" class="btn_lg black" id="rv_submit_btn"></span>
	</div>

	</form>

</div>


<SCRIPT LANGUAGE="JavaScript">
<!--

window.onload = function() {

	var uploader1 = new ss.SimpleUpload({
		  button: 'file_upload_btn1',
		  url: 'file_upload.php?data=jumin',
		  responseType: 'json',
		  name: 'uploadfile',
		  multiple: true,
		  onSubmit: function(filename, extension) {

		  },
		  onComplete:   function(filename, response) {

			str = "<li>";
			str += response.file;
			str += "<img src='<?=$nfor['skin_path']?>/img/x.png' class='preview_file_del'>";
			str += "<input type='hidden' name='pb_filename1' value='"+response.filename+"'><input type='hidden' name='pb_file1' value='"+response.file+"'>";
			str += "</li>";
			$(".file_upload_preview1").html(str);

			if(!response){
				alert(filename + 'upload failed');
				return false;
			}

		  }
	});

	var uploader2 = new ss.SimpleUpload({
		  button: 'file_upload_btn2',
		  url: 'file_upload.php?data=bank',
		  responseType: 'json',
		  name: 'uploadfile',
		  multiple: true,
		  onSubmit: function(filename, extension) {

		  },
		  onComplete:   function(filename, response) {

			str = "<li>";
			str += response.file;
			str += "<img src='<?=$nfor['skin_path']?>/img/x.png' class='preview_file_del'>";
			str += "<input type='hidden' name='pb_filename2' value='"+response.filename+"'><input type='hidden' name='pb_file2' value='"+response.file+"'>";
			str += "</li>";
			$(".file_upload_preview2").html(str);

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








$(document).on("click","#rv_submit_btn",function(){

	$(this).hide();

	$.ajax({
		type:"post",
		data :$("#review_form").serialize(),
		url:"get_money.php",
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

<?php
include_once $nfor['skin_path']."tail.php";
?>