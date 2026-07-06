
<div class="note_tab">
<ul>
	<li><a href="note_receive_list.php" class="<?=basename($PHP_SELF)=="note_receive_list.php" || basename($PHP_SELF)=="note_receive_view.php"?"on":""?>">받은쪽지함 <?=$receive[cnt]?"[".number_format($receive[cnt])."]":""?></a></li>
	<li><a href="note_send_list.php" class="<?=basename($PHP_SELF)=="note_send_list.php" || basename($PHP_SELF)=="note_send_view.php"?"on":""?>">보낸쪽지함</a></li> 
	<li><a href="note_form.php" class="<?=basename($PHP_SELF)=="note_form.php"?"on":""?>">쪽지보내기</a></li> 
<ul>
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
