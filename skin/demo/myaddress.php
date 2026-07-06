<?php
include_once "html_head.php";
?>


<style>
.popupadd{}
.popupadd .addtit{background-color:#343b4a; height:48px; padding-left:20px;}
.popupadd .addtit span{font-weight:bold; font-size:18px;color:#ffffff;font-family:'nskr';  line-height:48px;}
.popupadd .inner{padding:20px;}
.big_tit{font-size:16px; color:#333; }
.popupadd input[type=text]{ height:16px;margin-bottom:2px;padding:4px 6px 0; height:24px;border:1px solid;border-color:#bebec0 #d6d6d6 #eaeaea #d6d6d6;vertical-align:middle;color:#666;}
.popupadd .txt{font-size:11px; color:#666;letter-spacing:-1px;}

.popupadd .tbl_typeE,.tbl_typeE th,.tbl_typeE td{border:0}
.popupadd .tbl_typeE{border-bottom:1px solid #dddee2;border-top:2px solid #666666; font-family:'Verdana',dotum;font-size:12px; }
.popupadd .tbl_typeE caption{display:none}
.popupadd .tbl_typeE th{padding:8px 0 5px 20px;background:#f5f7f9;color:#666666;;font-weight:bold;text-align:left; border-bottom:solid 1px #eaeaea; }
.popupadd .tbl_typeE td{padding:8px 5px 5px 12px;line-height:20px;vertical-align:middle;background-color:#ffffff; text-align:left; border-bottom:solid 1px #eaeaea; color:#666; }

.popupadd .tbl_typeE td .zipcode_btn{display:inline-block; width:63px; height:26px; line-height:26px;border:1px solid #d0d0d0;color:#4c4c4c;background-color:#fff; letter-spacing:-1px;font-size:11px;padding:3px 7px 0px 7px;vertical-align:middle;cursor:pointer;}

.popupadd .btn_cen{text-align:center; padding:20px;}



.popupadd .save_btn{display:inline-block; width:85px; height:37px; line-height:37px;border:1px solid #99a4b0;color:#ffffff; background-color:#99a4b0; letter-spacing:-1px; padding:0px; margin-bottom:0px;  font-size:15px;vertical-align:middle;cursor:pointer; }
.popupadd .cen_btn{display:inline-block; width:85px; height:35px; line-height:35px;border:1px solid #d0d0d0;color:#4c4c4c; background-color:#ffffff; letter-spacing:-1px;font-size:15px; padding:0px;vertical-align:middle;cursor:pointer; }

.popupadd .tbl_cart{width:98%;margin-top:10px;border-top:2px solid #4f525c;border-bottom:1px solid #dbdee6; margin-bottom:20px; font-size:12px;}
.popupadd .tbl_cart th{padding:11px 0 11px;color:#666; border-bottom:1px solid #e7e7e9; border-left:1px solid #edeff4;background-color:#f5f5f5;text-align:center;vertical-align:middle}
.popupadd .tbl_cart td{padding:11px 0px 11px 5px;border-bottom:1px solid #edeff4;border-left:1px solid #edeff4;background-color:#fff;text-align:center; color:#666;}
.popupadd .tbl_cart th:first-child{border-left:none;}
.popupadd .tbl_cart td:first-child{border-left:none;}

.phone{ height:16px;margin-bottom:2px;padding:4px 6px 0; height:24px;border:1px solid;border-color:#bebec0 #d6d6d6 #eaeaea #d6d6d6;vertical-align:middle;color:#666;}
.zipcode{ height:16px;margin-bottom:2px;padding:4px 6px 0; height:24px;border:1px solid;border-color:#bebec0 #d6d6d6 #eaeaea #d6d6d6;vertical-align:middle;color:#666;}
.zipcode{ height:16px;margin-bottom:2px;padding:4px 6px 0; height:24px;border:1px solid;border-color:#bebec0 #d6d6d6 #eaeaea #d6d6d6;vertical-align:middle;color:#666;}

.address { width:350px; }
</style>

<form id="myaddress" method="post" action="myaddress.php">
<input type="hidden" name="mode" value="<?=$write[my_id]?"update":"insert"?>">
<input type="hidden" name="my_id" value="<?=$write[my_id]?>">

<div class="popupadd">

	<div class="addtit"><span>내주소관리</span></div>

	<div class="inner">	
		<b class="big_tit fotr">자주쓰는 배송주소를 등록 관리합니다.</b><br>
		<span class="txt">※ 회원정보와 별도로 배송주소를 등록하여 자유롭게 설정할수 있습니다.</span>
		<table cellpadding="0" cellspacing="0" border="0" class="tbl_typeE" style="margin-top:20px;" width="95%">
		<tr>
			<th>배송지명</th>
			<td><input type="text" name="my_nick" id="my_nick" value="<?=$write[my_nick]?>" class="popup_input"> <label><input type="checkbox" name="my_basic" value="1"> 기본배송지로 설정</label></td>
		</tr>
		<tr>
			<th>이름</th>
			<td><input type="text" name="my_name" id="my_name" class="popup_input" value="<?=$write[my_name]?>"></td>
		</tr>
		<tr>
			<th>휴대폰</th>
			<td><input type="text" name="my_hp" id="my_hp" class="phone" value="<?=$write[my_hp]?>"></td>
		</tr>
		<!-- <tr>
			<th>전화번호</th>
			<td><input type="text" name="my_tel" id="my_tel" class="phone" value="<?=$write[my_tel]?>"></td>
		</tr> -->
		<tr>
			<th>배송지</th>
			<td>
			<input type="text" name="my_zip" id="my_zip" maxlength="6" size="6" class="zipcode" value="<?=$write[my_zip]?>" >
			<a href="javascript:zipcode('my_zip','my_addr1','my_addr2')" class="zipcode_btn">우편번호검색</a><br>
			<input type="text" name="my_addr1" id="my_addr1" class="address" style="margin:3px 0px;width:400px;" value="<?=$write[my_addr1]?>" class="popup_input"><br>
			<input type="text" name="my_addr2" id="my_addr2" class="address" value="<?=$write[my_addr2]?>" class="popup_input"></td>
		</tr>
		</table>
		
		<div class="btn_cen"><?=admin_submit("my_submit_btn", "등록하기","save_btn fotr")?>&nbsp;<a href="<?=$write[my_id]?"myaddress.php":"javascript:window.close();"?>"  class="cen_btn fotr">취소</a></div>

		<table cellpadding="0" cellspacing="0" border="0" class="tbl_cart" >
		<tr>
			<th style="width:150px;">배송지명</th>
			<th style="width:80px;">이름</th>
			<th style="width:80px;">휴대폰</th>
			<!-- <th style="width:80px;">전화번호</th> -->
			<th>배송지</th>
			<th style="width:60px;">수정</th>
			<th style="width:60px;">삭제</th>
		</tr>
		<?php
		$result = sql_query("select * from nfor_myaddress where my_mb_no='$member[mb_no]' order by my_basic desc, my_id desc");
		for($i=0; $data=sql_fetch_array($result); $i++){
		?>
		<tr data-my_id="<?=$data[my_id]?>" data-my_nick="<?=$data[my_nick]?>" data-my_name="<?=$data[my_name]?>" data-my_hp="<?=$data[my_hp]?>" data-my_tel="<?=$data[my_tel]?>" data-my_zip="<?=$data[my_zip]?>" data-my_addr1="<?=$data[my_addr1]?>" data-my_addr2="<?=$data[my_addr2]?>">
			<td><?=$data[my_nick]?><? if($data[my_basic]){ ?><br><b style="font-family:돋움; font-size:11px;">[기본배송지]</b><? } ?></td>
			<td><a class="myaddress"><?=$data[my_name]?></a></td>
			<td><a class="myaddress"><?=$data[my_hp]?></a></td>
			<!-- <td><a class="myaddress"><?=$data[my_tel]?></a></td> -->
			<td><a class="myaddress"><?=$data[my_zip]?> <?=$data[my_addr1]?> <?=$data[my_addr2]?></a></td>
			<td><a href="myaddress.php?my_id=<?=$data[my_id]?>">[수정]</a></td>
			<td><a class="my_delete_btn" data-my_id="<?=$data[my_id]?>">[삭제]</a></td>
		</tr>
		<?
		} 
		if(!$i){
		?>
		<tr>
			<td colspan="8" style="height:150px; text-align:center; line-height:45px;"> 등록된데이타가 없습니다.</td>
		</tr>
		<? } ?>
		</table>

	</div>
</div>

</form>



<script>
$(document).on("click", ".my_delete_btn", function(){

	if(confirm("삭제하시겠습니까?")){

		var my_id = $(this).data("my_id");
		$.ajax({
			type: "post",
			url: "myaddress.php",
			data: {
				"mode":"delete",
				"my_id":my_id
			},
			cache: false,
			async: false,
			success: function(response){
				var json = $.parseJSON(response);
				if(json["result"]=="ok"){
					alert(json["msg"]);
					window.location.reload();
				} else{
					alert(json["msg"]);
				}
			}
		});

	}

});


$(document).on("click", ".myaddress", function(){

	var my_id = $(this).closest("tr").data("my_id");
	var my_nick = $(this).closest("tr").data("my_nick");
	var my_name = $(this).closest("tr").data("my_name");
	var my_hp = $(this).closest("tr").data("my_hp");
	var my_tel = $(this).closest("tr").data("my_tel");
	var my_zip = $(this).closest("tr").data("my_zip");
	var my_addr1 = $(this).closest("tr").data("my_addr1");
	var my_addr2 = $(this).closest("tr").data("my_addr2");

	$(".my_id",window.opener.document).val(my_id);
	$(".dy_nick_span",window.opener.document).html(my_nick);
	$(".dy_name_span",window.opener.document).html(my_name);
	$(".dy_hp_span",window.opener.document).html(my_hp);
	$(".dy_tel_span",window.opener.document).html(my_tel);
	$(".dy_zip_span",window.opener.document).html(my_zip);
	$(".dy_addr1_span",window.opener.document).html(my_addr1);
	$(".dy_addr2_span",window.opener.document).html(my_addr2);


	$(".od_delivery_info span",window.opener.document).removeClass("hide");
	$(".od_delivery_info input",window.opener.document).addClass("hide");
	$("#zipcode_btn",window.opener.document).addClass("hide");

	$("#dy_info",window.opener.document).val(1);

    window.close();
});

$(document).on("click","#my_submit_btn",function(){
	$.ajax({
		type:"post",
		data :$("#myaddress").serialize(),
		url:"myaddress.php",
		success:function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="insert" || json["result"]=="update"){
				if(json["msg"]){
					alert(json["msg"]);		
					
					if(json["result"]=="insert"){
						window.location.reload();
					} else{
						location.href="myaddress.php";

					}

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
include_once "html_tail.php";
?>