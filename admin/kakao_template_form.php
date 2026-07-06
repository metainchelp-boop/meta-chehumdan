<?php
include_once "path.php";

if($mode=="insert" or $mode=="update"){
	demo_check_json();
	if(!$tpl_name) json_return("템플릿 이름을 입력해주세요","tpl_name");
	if(!$tpl_content) json_return("템플릿 내용을 입력해주세요","tpl_content");
	if($mode=="insert") $return = kakao_alarm_template_insert($tpl_name, $tpl_content);
	if($mode=="update"){
		$return = kakao_alarm_template_modify($tpl_code, $tpl_name, $tpl_content);
		if($return['code'] == "0") sql_query("update nfor_send set sd_msg='$tpl_content' where sd_templt_code='$tpl_code'");
	}
	$return['url'] = "kakao_template_list.php";
	json_return($return['message'],"ok");
}

if($templtCode){
	$data = kakao_alarm_template_list($templtCode);
	$write['tpl_name'] = $data['list']['0']['templtName'];
	$write['tpl_content'] = $data['list']['0']['templtContent'];
	$write['tpl_button'] = $data['list']['0']['buttons'];
	$write['tpl_code'] = $data['list']['0']['templtCode'];
}

include_once "head.php";
?>

<form name="fwrite" id="fwrite" method="post" autocomplete="off">
<?=admin_hidden($write,"mode")?>
<?=admin_hidden($write,"tpl_code")?>
<table class="table cols_tbl margin0">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>템플릿 이름</th> 
	<td><?=admin_text($write,"tpl_name")?></td>
</tr>
<tr>
	<th>템플릿 내용</th> 
	<td><?=admin_textarea($write,"tpl_content")?></td>
</tr>
</table>

<div class="bottom_btn">
	<div class="form-inline">
		<?=admin_submit("fsubmit_btn", $templtCode?"수정하기":"등록하기", "btn btn-lg btn-red")?>
		<?=admin_a("list", "목록보기", "btn_back btn btn-lg btn-black")?>
	</div>
</div>

</form>

<SCRIPT LANGUAGE="JavaScript">
<!--
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

$(document).on("click", ".btn_back", function(){
	location.href = document.referrer;
});
//-->
</SCRIPT>

<?php 
include_once "tail.php";
?>