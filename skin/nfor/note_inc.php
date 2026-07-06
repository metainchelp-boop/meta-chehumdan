<style>
.note_wrap{background:#fff; padding:15px 15px 0px 15px;}
.note_tab ul{overflow:hidden; display:flex; width:100%;}
.note_tab li{float:left; height:50px;  position:relative; flex:1}
.note_tab li a{display:block; position:relative; border: 1px solid #333; margin-left: -1px; height: 40px;  border: 1px solid #999;  line-height: 40px;text-align: center; background-color: #f2f2f2;}
.note_tab li .on{background: #fff; color: #000; border: 1px solid #333;  margin-left: -1px;}
.note_tab li:nth-child(1) a{margin-left:-0px;}
.note_receive_top{ padding:15px;background-color: #f3f3f3;  margin-top:0px;position:relative; margin-bottom:15px;}
.note_receive_top a{display:block; cursor:pointer;}
.note_receive_top b{font-weight:normal; padding-left:5px; display:inline-block}
.note_receive_top span{display:inline-block; margin-right:15px; color:#000; font-size:14px; }
.note_receive_top span + span{ margin-right:15px;}
.allchk{position:absolute; right:10px; top:15px; font-size:14px;}
.note_box{border:solid 1px #DCDCDC; padding: 10px 10px 45px 45px; position:relative; border-radius:5px; font-size:14px; margin-bottom:10px;}
.note_box .chck{ display:inline-block; position:absolute; left:10px; top:50%; margin-top:-11px;}
.note_box .bottom_info{position:absolute; bottom:10px; right:15px; font-size:12px; color:#888;  } 
.line{border-bottom:solid 1px #dcdcdc;}
.write_f li{padding:5px 0px; }
.view_memo{padding:10px; border:solid 1px #dcdcdc; font-size:14px; color:#666; min-height:250px;}
</style>
<div class="note_wrap">
	<div class="note_tab">
		<ul>
			<li><a href="note_receive_list.php" class="<?=basename($PHP_SELF)=="note_receive_list.php" || basename($PHP_SELF)=="note_receive_view.php"?"on":""?>">받은쪽지함 <?=$receive[cnt]?"[".number_format($receive[cnt])."]":""?></a></li>
			<li><a href="note_send_list.php" class="<?=basename($PHP_SELF)=="note_send_list.php" || basename($PHP_SELF)=="note_send_view.php"?"on":""?>">보낸쪽지함</a></li> 
			<li><a href="note_form.php" class="<?=basename($PHP_SELF)=="note_form.php"?"on":""?>">쪽지보내기</a></li> 
		</ul>
	</div>
</div>
<script type="text/javascript">
<!--
$(document).on("click", "#chkall", function(){
	nfor_chk_all(this, 'chk');
});

$(document).on("click", "#list_delete", function(){
	nfor_list_reload('삭제','list_delete');
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
//-->
</script>