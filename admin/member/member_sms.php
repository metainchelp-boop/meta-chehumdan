<?php
include_once "path.php";

if($mode=="update"){
	demo_check_json();
	if(!$sm_hp) json_return("받는번호를 입력하세요","sm_hp");
	if(!$sm_memo) json_return("문자메시지 내용을 입력하세요","sm_memo");	
	sql_query("insert nfor_sms_log set sl_msg='$sm_memo', sl_hp='$sm_hp', sl_send_hp='{$config['cf_tel']}', sl_datetime=NOW(), sl_send='0', sl_templt_code='', sl_subject='개별발송'");
	json_return("발송하였습니다","ok");
}

$nfor['title'] = "문자발송";

include_once "head.php";

$write['mb_no'] = $mb['mb_no'];
$write['sm_hp'] = $mb['mb_hp'];
?>

<form name="fwrite" id="fwrite" method="post" autocomplete="off">
<?=admin_hidden($write,"mode")?>
<?=admin_hidden($write,"mb_no")?>

<table class="table cols_tbl">	
<colgroup>
	<col class="width-150p">
	<col>
	<col class="width-150p">
	<col>
</colgroup>

<tr>
	<th>받는번호</th> 
	<td><?=admin_text($write,"sm_hp","width-150p")?></td>
</tr>
<tr>
	<th>문자메시지 내용</th> 
	<td>
	<?=admin_textarea($write,"sm_memo","","rows=\"5\"")?>
	<span><span id='sm_memo_length'>0</span>/80</span>	
	</td>
</tr>
</table>

<div class="bottom_btn"><?=admin_submit("fsubmit_btn", "발송하기", "btn btn-lg btn-red")?></div>
</form>

<script>
$(document).on("click","#fsubmit_btn",function(){
	$.ajax({
		type:"post",
		data :$("#fwrite").serialize(),
		url:"<?=basename($_SERVER['PHP_SELF'])?>",
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
		}
	});
	event.preventDefault();
});

$('#sm_memo').bind({
  'keyup':function(){
    var dom=$(this);
    var str=dom.val();
    var _byte=0;
    var strlength=0;
    var charStr='';
    var maxlength=80;
    var cutstr='';
    if(str.length<=0){
      return;
    }
    for(var i=0;i<str.length;i++){
      charStr=str.charAt(i);
      if(escape(charStr).length>4){
        _byte+=2;
      }else{
        _byte++;
      }
      if(_byte<=maxlength){
        strlength=i+1;
      }
    }
    $('#sm_memo_length').text(_byte);
    if(_byte>maxlength){
      cutstr=dom.val().substr(str,strlength);
      dom.val(cutstr);
      $('#sm_memo_length').text(maxlength);
      return;
    }else{
      $('#sm_memo_length').text(_byte);
    }
  }
});
</script>

<?php
include_once "tail.php";
?>