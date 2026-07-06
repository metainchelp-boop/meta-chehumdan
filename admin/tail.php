			</td>
		</tr>
		</table>

		<address id="copyright">

			<br>

<span style="color:#ffff;">

			상호 :  <?=$config[cf_cp_name]?>  대표자 : <?=$config[cf_cp_ceo]?> <br>
			개인정보보호책임자 : <?=$config[cf_cp_privacy]?>(<?=$config[cf_cp_privacy_email]?>)<br>
			
			사업자등록번호 : <?=$config[cf_cp_number1]?><br>
			통신판매업신고 : <?=$config[cf_cp_number2]?><br>
			<!-- <?=$config[cf_cp_zip]?> <?=$config[cf_cp_addr1]?> <?=$config[cf_cp_addr2]?> -->
			주소 : 서울특별시 관악구 신림로 67번길 15, 프리미어 801호(신림동, 프리미어)<br>
			고객센터 : <?=$config[cf_tel]?> / <?=$config[cf_email]?><br>

			메타체험단은 체험단 중개 서비스를 제공합니다. <br><br>
			광고주 결제 서비스에 대한 환불, 민원 등의 처리는 메타체험단이 진행합니다.<br>
			민원 담당자 : 김우리 (직통 번호 : 010-4271-9811)<br>


<br>
        		<a href="/agreement.php?code=agreement" target="_blank" class="f_menu">이용약관</a> / 
				<a href="/agreement.php?code=privacy" target="_blank" class="f_menu">개인정보취급방침</a>

				<br>

</span>
			<br>



		<a>Copyright <em>&copy;</em>meta-chehumdan. All Rights Reserved.</a>	
		</address>

	</td>
</tr>
</table>













<style>
/* 기간설정 */

#period_sdate { border:solid 1px #D5D5D5; padding:4px 12px; font-size:12px; color:#555555; } 
#period_edate { border:solid 1px #D5D5D5; padding:4px 12px; font-size:12px; color:#555555; } 

.period_wrap { display:inline-block; vertical-align:middle;border-right:solid 1px #D5D5D5;}
.period_wrap label { float:left; border-left:solid 1px #D5D5D5; border-top:solid 1px #D5D5D5;  border-bottom:solid 1px #D5D5D5;color:#666; padding:3px 15px !important; font-size:12px; cursor:pointer; font-family:Malgun Gothic,"맑은 고딕",AppleGothic,Dotum,"돋움","Helvetica Neue", Helvetica, Arial, sans-serif; background-color:#FAFAFA; line-height:16px !important;}
.period_wrap label.checked { background-color:#444; border-color:#444;  color:#fff; }
.period_wrap input { display:none; }


</style>


<script type="text/javascript">
<!--
$(document).on("click","#search_tr_detail_btn",function(){ 

	if($("#search_tr_detail_btn").val()=="상세검색 펼침"){
		$("#search_tr_detail_btn").val("상세검색 닫힘");
		$("#search_tr_detail").val("1");
	} else{
		$("#search_tr_detail_btn").val("상세검색 펼침");
		$("#search_tr_detail").val("");
	}

	$(".search_tr_detail").toggle();
});

$(document).on("click",".period_wrap label",function(){ 
	$('.period_wrap label').removeClass("checked");
	$(this).addClass("checked");

	var prev_day = 0;
	var period_day_checked = $(':radio[name="period_day"]:checked').val();
	if(period_day_checked<0){
		prev_day = period_day_checked;
		$("#period_sdate").val("");
		$("#period_edate").val("");
	} else{
		prev_day = period_day_checked;
		var date = new Date(); 
		var period_edate = date.getFullYear() + "-" + date_add_zero(eval(date.getMonth()+1)) + "-" + date_add_zero(date.getDate());
		$(this).parent().parent().find("#period_edate").val(period_edate);

		var prev_date = new Date(period_edate);
		prev_date.setDate( prev_date.getDate() - prev_day );

		var period_sdate = prev_date.getFullYear() + "-" + date_add_zero(eval(prev_date.getMonth()+1)) + "-" + date_add_zero(prev_date.getDate());
		$(this).parent().parent().find("#period_sdate").val(period_sdate);
	}
});







// 엑셀다운
$(document).on("click",".exceldown",function(){ 
	nfor_layer("excel","ex_page=<?=basename($list)?>","엑셀다운");
});

$(document).on("click","#excel_download_btn",function(){ 
	var ex_id = $("#layer_excel #ex_id").val();
	if(!ex_id){
		alert("엑셀 양식을 선택해주세요");
		$("#layer_excel #ex_id").focus();
		return;
	}
	$("#flist #ex_id").val(ex_id);
	$("#mode").val("excel");
	document.flist.submit();
});











function nfor_list_reload(str,action,frm){
	if(frm){
		$('#mode'+frm).val(action);
	} else{
		$('#mode').val(action);
	}

	var chk = document.getElementsByName("chk[]");
	var bchk = false;

	for (i=0; i<chk.length; i++){
		if (chk[i].checked)
			bchk = true;
	}

	if (!bchk){
		alert(str+"할 자료를 하나 이상 선택하세요");
		return;
	}
	if(frm){

		$.ajax({
			type:"post",
			data :$('#flist'+frm).serialize(),
			url:"<?=$PHP_SELF?>",
			success:function(response){
				var json = $.parseJSON(response); 
				if(json["result"]=="ok"){
					if(json["msg"]){
						alert(json["msg"]);
					}
					location.reload();
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

	} else{

		$.ajax({
			type:"post",
			data :$("#flist").serialize(),
			url:"<?=$PHP_SELF?>",
			success:function(response){
				var json = $.parseJSON(response); 
				if(json["result"]=="ok"){
					if(json["msg"]){
						alert(json["msg"]);
					}
					location.reload();
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

	}
}




$(document).on("click", ".nfor_button", function(){


	var confirm_str = $(this).data("confirm");
	var data_str = $(this).data("data");

	if(confirm(confirm_str)){


		$.ajax({
			type:"post",
			data:data_str,
			url:"<?=$PHP_SELF?>",
			success:function(response){
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
				}
			}
		});


	}

});




// 컬러픽커
$(".colorpicker").minicolors({
	show: function() {
		console.log('Show event triggered!');
	}
});
//-->
</script>












<?php // 패스워드변경안내
if($nfor[password_change_popup]=="1" and $config[cf_password_admin_use]=="1"){
	include_once $nfor[skin_path]."password_change_popup.php";
}
?>



<?php @include_once dirname(__FILE__)."/mc_admin_chat.php"; /* 관리자 플로팅 상담 콘솔 2026-06-24 */ ?>
</body>
</html>