<style>
.delivery_chage_wrap { position:fixed; left:0; top:0; width:100%; height:100%; z-index:1000; overflow-y:auto; display:none;} 
.delivery_chage_wrap .title{background-color:#343b4a; height:48px; padding-left:20px; position:relative;}
.delivery_chage_wrap .title span{ font-size:18px;color:#ffffff;font-family:'nskl';  line-height:48px;}
.delivery_chage_wrap_close{position:absolute; right:20px; top:10px;}
.delivery_chage_wrap .inner{padding:20px;}
.dy_bg { position: absolute; margin:-350px 0 0 -250px; top:50%; left:50%; background-color:#fff;   border: 1px solid #c2c7cc; width:500px; min-height:500px; z-index:1001;  }
.tbl_typeE,.tbl_typeE th,.tbl_typeE td{border:0}
.tbl_typeE{border-bottom:1px solid #dddee2;border-top:2px solid #666666; font-family:'Verdana',dotum;font-size:12px; }
.tbl_typeE caption{display:none}
.tbl_typeE th{padding:8px 0 5px 20px;background:#f5f7f9;color:#666666;;font-weight:bold;text-align:left; border-bottom:solid 1px #eaeaea; }
.tbl_typeE td{padding:8px 5px 5px 12px;line-height:20px;vertical-align:middle;background-color:#ffffff; text-align:left; border-bottom:solid 1px #eaeaea; color:#666; }
.tbl_typeE input{ height:16px;margin-bottom:2px;padding:4px 6px 0; height:24px;border:1px solid;border-color:#bebec0 #d6d6d6 #eaeaea #d6d6d6;vertical-align:middle;color:#666;}
.tbl_typeE select{padding: 6px 5px 5px; height:30px; border: 1px solid #d0d0d0; color: #828284; vertical-align: middle;  border: 1px solid #d0d0d0;  color: #828284;  background: url(/skin/demo/img/select_background.png) no-repeat 100% 50%; font-size: 12px; -webkit-appearance: none; -moz-appearance: none;  appearance: none;}
#zipcode_btn {display: inline-block;width: 63px;height: 26px;line-height: 26px;border: 1px solid #d0d0d0; color: #4c4c4c; background-color: #fff; letter-spacing: -1px; font-size: 11px; padding: 3px 7px 0px 7px; vertical-align: middle; cursor: pointer;}
.width1{width:150px;}
.width2{width:100px;}
.width3{width:80px;}
.width4{width:95%;}

#od_dy_msg { width:90%; margin-top:5px; border:1px solid;border-color:#bebec0 #d6d6d6 #eaeaea #d6d6d6;vertical-align:middle;color:#666; } 
.btn_submit{display:inline-block; width:100px; border:none; height:45px; line-height:45px;border:1px solid ##8c98a5;color:#4c4c4c; background-color: #99a4b0; color:#fff;letter-spacing:-1px;font-size:15px; vertical-align:middle;cursor:pointer}
</style>

<div class="delivery_chage_wrap">


	<form id="fdelivery_chage" method="post">
	<input type="hidden" name="mode" value="delivery_chage">
	<input type="hidden" name="od_id" value="<?=$order[od_id]?>">
	<div class="dy_bg">
		<div class="title">
			<span>배송지변경</span>
			<input type="button" value="닫기" class="delivery_chage_wrap_close">
		</div>

		<div class="inner">
			<table  cellpadding="0" cellspacing="0" border="0" class="tbl_typeE" style="margin-top:20px;" width="100%">
			<colgroup>
				<col style="width:90px;">
			</colgroup>
			<tr>
				<th>이름</th>
				<td><?=admin_text($order,"od_dy_name","width1","placeholder=\"받으시는분 이름\"")?></td>
			</tr>
			<tr>
				<th>전화번호</th>
				<td><?=admin_text($order,"od_dy_hp","width2","pattern=\"[0-9]*\" placeholder=\"받으시는분 휴대전화번호\"")?></td>
			</tr>
			<tr>
				<th>주소</th>
				<td>
				<div class="od_dy_zip_wrap">
				<?=admin_text($order,"od_dy_zip","width3","readonly placeholder=\"우편번호\"")?>
				<a id="zipcode_btn">우편번호찾기</a>
				</div>
				<?=admin_text($order,"od_dy_addr1","width4","readonly placeholder=\"주소\"")?>
				<?=admin_text($order,"od_dy_addr2","width5","placeholder=\"상세주소\"")?>
				</td>
			</tr>
			<tr>
				<th>배송메세지</th>
				<td>
				<?=admin_select($order,"od_dy_msg_type","","","0")?>
				<?=admin_textarea($order, "od_dy_msg", in_array($order[od_dy_msg],$admin[od_dy_msg_type])?"hide":"")?>
				</td>
			</tr>
			</table>
			<div class="page_center"><?=admin_submit("delivery_chage_submit_btn", "배송지 변경", "btn_submit")?></div>
		</div>
	</div>
	</form>

</div>

<script>
$(document).on("click",".delivery_chage_wrap_close",function(){
	$(".delivery_chage_wrap").hide();
});

$(document).on("change", "#od_dy_msg_type", function(){
	var od_dy_msg_type = $(this).val();
	if(od_dy_msg_type=="직접 입력"){
		$("#od_dy_msg").removeClass("hide").val("").focus();
	} else{
		$("#od_dy_msg").addClass("hide").val(od_dy_msg_type);
	}
});

$(document).on("click","#zipcode_btn, #od_dy_zip, #od_dy_addr1",function(){
	zipcode("od_dy_zip","od_dy_addr1","od_dy_addr2");
});

$(document).on("click","#delivery_chage_submit_btn",function(){
	$.ajax({
		type:"post",
		cache : false,
		data : $("#fdelivery_chage").serialize(), 
		url:"order_view.php",
		success:function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				alert(json["msg"]);
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
	event.preventDefault();
});
</script>