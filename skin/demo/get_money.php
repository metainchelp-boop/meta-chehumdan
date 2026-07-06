<?php
include_once $nfor['skin_path']."mypage_head.php";
?>


<style>
.my_db_list{}

.my_db_list .t_info_tab{display:flex; margin-bottom:30px;}
.my_db_list .t_info_tab .menu{flex:1; text-align:center; font-size:18px; height:56px; line-height:56px; letter-spacing:-1px; border: solid 1px #dcdcdc; background-color: #fafafa; margin-left:-1px; color:#888;    font-family: 'notokr-medium';}
.my_db_list .t_info_tab .menu:nth-child(1){margin-left:0px;}
.my_db_list .t_info_tab .on{background-color:#ffffff; border-bottom:solid 1px #fff; color:#000;}


.my_db_list .my_db_top{border:solid 1px #dcdcdc;  padding:20px; position:relative; background-color:#fafafa}
.my_db_list .my_db_top input[type=text] {width:20%;  height: 35px;   font-family: "montserrat"; padding-left: 9px; border: 1px solid #dcdcdc;  line-height: 35px;  color: #828284; font-size: 15px;  outline: none; box-sizing: border-box; -webkit-box-sizing: border-box;}
.my_db_list .my_db_top .cal_ico{display:inline-block; width: 35px; height:35px; background:url(/skin/demo/img/cal_ico.png)center no-repeat; background-size:30px; vertical-align:-10px;}
.my_db_list .my_db_top .period_wrap{display:inline-block; margin-left:40px;}
.my_db_list .my_db_top .period_wrap label{margin-right:10px; font-size:15px; color:#000; font-family: "montserrat";}
.my_db_list .my_db_top .btn_pack{position:absolute; top:20px; right:20px;}
.my_db_list .my_db_top .period_wrap input[type="radio" i] {margin: 0px 0px 0px 5px; vertical-align:-1px; }


.my_db_list  .mydb_top_zone{overflow:hidden; margin:0px auto; border-bottom:solid 1px #cfd6dc; border-top:solid 1px #333; margin-bottom:20px;}
.my_db_list  .mydb_top_zone li{float:left; width:25%; text-align:center; border-right:solid 1px #cfd6dc; box-sizing:border-box; -webkit-box-sizing:border-box;}
.my_db_list  .mydb_top_zone img{width:20px; display:inline-block; vertical-align:-5px; margin-right:10px;}
.my_db_list  .mydb_top_zone li span{display:block; padding:15px 0px 10px; color:#000; background-color:#fafafa; font-size:16px;  }
.my_db_list  .mydb_top_zone li span:last-child{border-top:solid 1px #cfd6dc; font-weight:600; font-size:24px;letter-spacing:-1px; background-color:#fff;  color:#267bf8;}
.my_db_list  .mydb_top_zone li span b{font-size:15px; font-weight:normal; display:inline-block; margin-left:5px; color:#666;}
.my_db_list  .mydb_top_zone li:last-child{border-right:none; }
.my_db_list  .mydb_title{ position:relative;  font-size:24px; font-family: 'notokr-medium'; letter-spacing:-1px; margin-top:20px;}
.my_db_list  .mydb_title .more{position:absolute; top:15px;right:15px; font-size:15px;  font-family: 'Spoqa Han Sans';}
#file_upload_btn2{border:solid 1px #dcdcdc; background-color:#fff; padding:10px 20px; margin-bottom:10px; }
#file_upload_btn1{border:solid 1px #dcdcdc; background-color:#fff; padding:10px 20px; margin-bottom:10px; }
.disin{width:100px;}
.point_txt{display:inline-block; vertical-align:-8px;}
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
		<td><span class="txt_num"><?=number_format($member[mb_point])?></span> 포인트</td>
	</tr>
	<tr>
		<th>출금요청포인트</th>
		<td><?=admin_text($write,"pb_point","inp disin")?> <span class="point_txt">  포인트</span></td>
	</tr> 

	<tr>
		<th>주민등록번호</th>
		<td><?=admin_text($write,"pb_jumin1","inp disin","maxlength='6'")?> - <?=admin_password($write,"pb_jumin2","inp disin","maxlength='7'")?></td>
	</tr>
	<tr>
		<th>신분증사본</th>
		<td colspan="3">

			<?=admin_button("file_upload_btn1", "찾아보기")?>

			<ul class="file_upload_preview1"></ul>

		</td>
	</tr>
	<tr>
		<th>통장사본</th>
		<td colspan="3">

			<?=admin_button("file_upload_btn2", "찾아보기")?>

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
			str += "<img src='<?=$nfor['skin_path']?>img/x.png' class='preview_file_del'>";
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
			str += "<img src='<?=$nfor['skin_path']?>img/x.png' class='preview_file_del'>";
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
include_once $nfor['skin_path']."mypage_tail.php";
?>