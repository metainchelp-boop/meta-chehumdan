<?php
include_once "path.php";

$admin['sd_sms_use'] = array("전체","사용","미사용");
$admin['sd_sms_use[]'] = $admin['sd_sms_use'];

$form = $_SERVER['PHP_SELF'];
$list = str_replace("form","list",$form);
$table = "nfor_send";
$id = "sd_id";

$id_value = $$id;

if($id_value) $write = sql_fetch("select * from $table where $id='{$id_value}'");

if($mode=="insert" or $mode=="update"){
	demo_check_json();

	if(!$sd_msg) json_return("문자메시지를 입력해주세요","sd_msg");

	$add_sql = "";
	$where_sql = "";

	if($mode=="insert"){
		$msg = "정상적으로 등록 되었습니다";
		$add_sql .= ", sd_insert_id='{$member['mb_no']}', sd_insert_datetime=NOW()";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$where_sql .= " where $id='{$id_value}'";
		$add_sql .= ", sd_update_id='{$member['mb_no']}', sd_update_datetime=NOW()";
	} else{

	}
	$common_sql = " $table set sd_templt_code='$sd_templt_code', sd_name='$sd_name', sd_code='$sd_code', sd_msg='$sd_msg', sd_sms_use='$sd_sms_use'";

	sql_query("$mode $common_sql $add_sql $where_sql");
	
	$return['sd_templt_code'] = $sd_templt_code;
	json_return($msg,"ok");
}

include_once "head.php";
?>

<form name="fwrite" id="fwrite" method="post" autocomplete="off">
<?=admin_hidden($write,"mode")?>
<?=admin_hidden($write,$id)?>

<table class="table cols_tbl margin0">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>코드</th> 
	<td><?=admin_text($write,"sd_code","",$write['sd_id']?"readonly":"")?></td>
</tr>
<tr>
	<th>구분</th>
	<td><?=admin_text($write,"sd_name","",$write['sd_id']?"readonly":"")?></td>
</tr>
<tr>
	<th>문자메시지내용</th> 
	<td><?=admin_textarea($write,"sd_msg","",($write['sd_templt_code']?"readonly ":"")."rows=\"5\"")?></td>
</tr>
<tr>
	<th>사용여부</th> 
	<td>
	<?php
	if(!$write['sd_sms_use']) $write['sd_sms_use'] = "1";
	?>
	<?=admin_radio($write,"sd_sms_use")?>
	</td>
</tr>
<tr>
	<th>템플릿코드</th> 
	<td><div class="form-inline"><?=admin_text($write,"sd_templt_code","width-150p")?><?=admin_a("a", "알림톡 템플릿 검수/관리", "btn-gray btn-sm", "", "kakao_template_list.php")?> <?=admin_help("※ 템플릿코드가 지정되어 있을경우 알림톡 템플릿관리메뉴에서 문구수정이 가능합니다")?></div></td>
</tr>
</table>

<div class="bottom_btn">
	<div class="form-inline">
		<?=admin_submit("fsubmit_btn", $write[$id]?"수정하기":"등록하기", "btn btn-lg btn-red")?>
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
				if(json["sd_templt_code"]){
					$("#sd_msg").attr("readonly","readonly");
				} else{
					$("#sd_msg").removeAttr("readonly");
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