<?php
include_once "path.php";

$admin[qa_reply_state] = array("전체", "답변대기", "답변완료");

$qstr .= "&campaign_qna_type=$campaign_qna_type&qa_reply_state=$qa_reply_state&qa_supply_no=$qa_supply_no";

$form = $_SERVER[PHP_SELF];
$list = str_replace("form","list",$form);
$table = "nfor_campaign_qna";
$id = "qa_id";

$id_value = $$id;

if($id_value){
	$write = sql_fetch("select * from $table where $id='{$id_value}'");
	$write = nfor_tag_out($write);
}

if($mode=="update"){
	demo_check();

	$msg = "답변이 등록 되었습니다";
	$move = "$form?{$qstr}&{$id}={$id_value}";

	$qa_parent = $qa_id;
	$qa_memo = strip_tags($qa_memo);

	$add_sql = ", qa_mb_id = '{$member[$member_config[cf_mb_id_type]]}', qa_mb_nick='$member[mb_nick]', qa_mb_name = '$member[mb_name]', qa_mb_hp = '$member[mb_hp]', qa_mb_email = '$member[mb_email]', qa_cp_subject = '$write[qa_cp_subject]'";

	sql_query("insert nfor_campaign_qna set qa_view='1', qa_cp_id_gp='$write[qa_cp_id_gp]', qa_cp_id='$write[qa_cp_id]', qa_supply_no='$write[qa_supply_no]', qa_parent='$qa_parent', qa_memo='$qa_memo', qa_insert_id='$member[mb_no]', qa_insert_datetime=NOW() $add_sql");

	$qa_reply = sql_fetch("select count(*) as cnt from nfor_campaign_qna where qa_parent='$qa_parent'");

	sql_query("update nfor_campaign_qna set qa_reply='$qa_reply[cnt]', qa_reply_id='$member[mb_no]', qa_reply_datetime=NOW() where qa_id='$qa_parent'");

	if($member[mb_admin]){
		$mb = sql_fetch("select mb_no, mb_email, mb_hp from nfor_member where mb_no='$write[qa_insert_id]'");
		nfor_send("campaign_qna", $mb[mb_email], $mb[mb_hp], $mb[mb_no]);	
	}

	alert($msg,$move);
}


if($write[qa_parent]) alert("잘못된 접근입니다");

$write[qa_it_url] = "{$nfor[path]}/campaign.php?cp_id={$write[qa_cp_id]}";


include_once "head.php";
?>

<form name="fwrite" method="post" onsubmit="return fsubmit(this)">
<?=admin_hidden($write,"mode")?>
<?=admin_hidden($write,$id)?>



	
<table class="table cols_tbl margin0">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>회원정보</th> 
	<td><?=qna_mb_echo($write)?></td>
</tr>
<tr>
	<th>캠페인명</th>
	<td><a href="<?=$write[qa_it_url]?>" target="_blank"><?=$write[qa_cp_subject]?></a></td>
</tr>
<tr>
	<th>캠페인코드</th>
	<td><?=$write[qa_cp_id]?></td>
</tr>
<tr>
	<th>문의내용</th> 
	<td><?=nl2br($write[qa_memo])?></td>
</tr>
<tr>
	<th>답변내용</th> 
	<td><?=admin_textarea($textarea,"qa_memo","","rows=\"10\"")?></td>
</tr>
</table>

<div class="bottom_btn">
	<div class="form-inline">
		<?=admin_submit("fsubmit_btn", "답변하기", "btn btn-lg btn-red")?>
		<?=admin_a("list", "목록보기", "btn btn-lg btn-black", "", $list."?".$qstr)?>
	</div>
</div>

</form>

<table class="table row_tbl margin0">
<colgroup>
	<col >
	<col class="width-150p">
	<col class="width-100p">
	<col class="width-80p">
	<col class="width-80p">
</colgroup>
<tr>
	<th>답변내용</th>
	<th>회원정보</th> 
	<th>답변일자</th>
	<th>수정</th>
	<th>삭제</th>
</tr>
<?php
$result = sql_query("select * from nfor_campaign_qna where qa_parent='$write[qa_id]'");
for($i=0; $row=sql_fetch_array($result); $i++){	
	$row = nfor_tag_out($row);
?>
<tr>
	<td class="textleft"><?=nl2br($row[qa_memo])?></td>
	<td><?=qna_mb_echo($row)?></td>
	<td><?=substr($row[qa_insert_datetime],0,10)?></td>
	<td><?=admin_a("update", "수정", "btn btn-white btn-sm update", "data-qa_id={$row[qa_id]}")?></td>
	<td><?=admin_a("delete", "삭제", "btn btn-white btn-sm delete", "data-qa_id={$row[qa_id]}")?></td>
</tr>
<?php
}
?>
</table>

<SCRIPT LANGUAGE="JavaScript">
<!--
$(document).on("click", ".update", function (){
	var qa_id = $(this).data("qa_id");
	nfor_layer("campaign_qna_form","qa_id="+qa_id,"답변내용 수정");	
});

$(document).on("click","#layer_campaign_qna_form #submit_btn",function(){ 
	layer_campaign_qna_form_insert();
});

function layer_campaign_qna_form_insert(){

	$.ajax({
		type: "post",
		url: "layer_campaign_qna_form.php",
		data: $("#layer_campaign_qna_form #layer_insert_form").serialize(),
		cache: false,
		async: false,
		success: function(response){
			var json = $.parseJSON(response);
			if(json["result"]=="ok"){
				window.location.reload();
			} else{
				alert(json["msg"]);
			}
		}
	});
}

$(document).on("click", ".delete", function (){
	var qa_id = $(this).data("qa_id");
	if(confirm("삭제하시겠습니까?")){
		$.ajax({
			type: "post",
			data: {
				"mode":"delete",
				"qa_id":qa_id
			},
			url: nfor_path + "/campaign_qna_form.php",
			success: function(response){
				var json = $.parseJSON(response); 
				if(json["result"]=="ok"){
					document.location.reload();
				} else{
					alert(json["msg"]);
				}
			}
		});
	}
});

function fsubmit(f){
	f.action = "<?=$form?>";
	return true;	    
}	
//-->
</SCRIPT>

<?php
include_once "tail.php";
?>