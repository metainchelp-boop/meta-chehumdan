<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.money_list_wrap { padding:0px 10px; }
.money_top_wrap {  border-top:solid 2px #ddd; border-bottom:solid 1px #ddd;  border-left:solid 2px #ddd;  border-right:solid 2px #ddd; overflow:hidden; background-color:#fff; }
.money_top_wrap .expire_msg { color:#999; font-size:10px; line-height:15px; font-weight:normal; }
.money_top_wrap dt { font-weight:bold; font-size:15px; color:#666; text-align:left; width:50%; float:left; height:40px; line-height:40px; padding-left:10px; border-bottom:solid 1px #ddd; box-sizing:border-box; }
.money_top_wrap dd { font-size:15px; color:#ec3940; text-align:right; width:50%; float:left; height:40px; line-height:40px; padding-right:10px; border-bottom:solid 1px #ddd; box-sizing:border-box;  }
.money_list_wrap .msg_title_wrap { margin-top:10px; background-color:#fff; border:solid 1px #ddd; padding:10px 7px; background-color:#fefefe; position:relative; } 
.money_list_wrap .msg_title { font-size:12px; color:#666; letter-spacing:-1px; display:block; height:21px; line-height:21px; }
.money_list_wrap #money_msg_btn { cursor:pointer; border:solid 1px #ddd; font-size:11px; color:#555; letter-spacing:-1px; display:block; padding:2px 5px; position:absolute; right:10px; top:10px; }
.money_list_wrap .money_msg_btn2 { cursor:pointer; border:solid 1px #ddd; font-size:11px; color:#555; letter-spacing:-1px; display:block; padding:2px 5px; position:absolute; right:-100px; top:10px; }
.money_list_wrap #money_msg_wrap { padding:5px; background-color:#fff; border-bottom:solid 1px #ddd; border-left:solid 1px #ddd; border-right:solid 1px #ddd; }
.money_list_wrap #money_msg li { font-size:11px; color:#999; padding:2px 5px; }
.money_list { margin-top:10px; background-color:#fff; border-top:solid 1px #ddd; border-left:solid 1px #ddd; border-right:solid 1px #ddd; }
.money_list .money_li_title { position:relative; background-color:#eee; padding:5px 10px; } 
.money_list .money_li_memo { padding:15px 10px; } 
.money_list li{ position:relative; background-color:#fff; border-bottom:solid 1px #ddd;  }
.money_list .wr_date { font-size:11px; color:#888; line-height:15px; }
.money_list .subject { color:#333; font-size:14px; }
.money_list .memo { color:#999; max-width:90%; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; letter-spacing:-1px;  font-size:14px;line-height:18px; }
.money_list .money { font-size:14px; position:absolute; right:10px; bottom:10px; }
.money_list .money.plus { color:#333; }
.money_list .money.minus { color:#ec3940; }
.money_list .end_datetime {  position:absolute; right:10px; top:5px; font-size:11px; color:#888; }
.inp{margin:5px 0px;}
.displayin{display:inline-block; width:48%;}
.tit{margin:10px 0px; font-size:14px;}
#bank_upload_btn{border:solid 1px #efefef; background-color:#fff; margin-bottom:15px;}
#jumin_upload_btn{border:solid 1px #efefef; background-color:#fff; margin-bottom:15px;}
</style>

<div class="point">
	<ul>
		<li><span class="tit">보유포인트</span><b><span class="color1 "><?=number_format($member[mb_point])?>p</span></b></li>
		<li><span class="tit">누적포인트</span><b> <span class="color2 "><?=number_format($pt_point_sum[pt_point_sum])?>p</span></b></li>	
	</ul>
</div>

<div id="money_msg_wrap" class="msg_title_wrap listBlit_info">
	<span class="msg_title">출금 신청시 주의사항</span>
	<ul id="money_msg">
		<li>
		<!-- <li>포인트는 10원단위로 출금 가능하며, 최소 <span class="point-color1"> <?=number_format($config[cf_get_money])?>원 </span> 이상이 모여야 출금신청을 할 수 있습니다.</li>
		<li>출금요청시 포인트는 즉시 차감되며, 입금은 관리자 확인후 입급예정 절차를 거쳐 <span class="point-color1"> 다음 달 <?=$config[cf_get_day]?>일</span> 지급됩니다.</li>
		<li>입금은행 및 계좌번호 그리고 예금주는 반드시 회원정보의 실명과 일치하여야 지급됩니다.</li> -->


		<li>출금 요청 시 포인트는 즉시 차감됩니다.</li>
		<li>입금은 관리자 확인 후 입금 절차에 따라 최대 7일 소요될 수 있습니다.</li>
		<li>은행 및 계좌번호, 예금주는 정확한 정보를 기재해 주세요.</li>

	</ul>
</div>

<div class="get_point_wrap">
	<form name="review_form" id="review_form" method="post">
	<input type="hidden" name="mode" value="insert">
	<?php
	$write[ftimestamp] = date("YmdHis").substr(microtime(),2,6);
	?>
	<?=admin_hidden($write,"ftimestamp")?>
	<ul>
		<li>
			<div class="tit"></div>
			<div class="list"><?=admin_text($write,"pb_name","inp","placeholder='예금주'")?></div>
		</li>
		<li>
			<div class="tit"></div>
			<div class="list">	
				<select name="pb_bank" id="pb_bank" class="inp">
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
			</div>
		</li>

		<li>
			<div class="tit"></div>
			<div class="list"><?=admin_text($write,"pb_bank_number","inp","placeholder='입금계좌번호'")?></div>
		</li>

		<li>
			<div class="tit"></div>
			<div class="list"><?=admin_text($write,"pb_point","inp","placeholder='출금요청포인트(숫자만입력)'")?></div>
		</li>
		<? if($config[cf_jumin]=="1"){ ?>
		<li>
			<div class="tit">주민등록번호</div>
			<div class="list"><?=admin_text($write,"pb_jumin1","inp displayin","maxlength='6'")?> - <?=admin_password($write,"pb_jumin2","inp displayin","maxlength='7'")?></div>
		</li>
		<? } ?>
		<? if($config[cf_jumin_file]=="1"){ ?>
		<li>
			<div class="tit">주민등록증 사본</div>
			<div class="list">
			<?=admin_button("jumin_upload_btn", "찾아보기")?>
						
			<input type="hidden" name="pb_file1" id="pb_file1">
			<input type="hidden" name="pb_filename1" id="pb_filename1">
			<div id="pb_filename1_span"></div>
			
			</div>
		</li>
		<? } ?>
		<? if($config[cf_bank_file]=="1"){ ?>
		<li>
			<div class="tit">통장 사본</div>
			<div class="list">	
			<?=admin_button("bank_upload_btn", "찾아보기")?>			
			
			<input type="hidden" name="pb_file2" id="pb_file2">
			<input type="hidden" name="pb_filename2" id="pb_filename2">
			<div id="pb_filename2_span"></div>
			</div>
		</li>
		<? } ?>
	</ul>
	
	<span class="btn_pack mar_top20"><input type="button" value="출금신청" id="rv_submit_btn" class="btn_lg black"></span>

		




	
		





	</form>
</div>
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
include_once $nfor[skin_path]."tail.php";
?>