<?php
include_once "path.php";

$admin = array();
$admin['cs_reply_state'] = array("전체","답변대기","답변완료");
$admin['cs_category'][""] = "전체";
$que = sql_query("select * from nfor_value where val_code='customer' order by val_rank asc");
while($row = sql_fetch_array($que)){
	$admin['cs_category'][$row['val_name']] = $row['val_name'];
}

$form = $_SERVER['PHP_SELF'];
$list = str_replace("form","list",$form);
$table = "nfor_customer";
$id = "cs_id";

$id_value = $$id;

if($id_value){
	$write = sql_fetch("select * from $table where $id='{$id_value}'");
	$write = nfor_tag_out($write);
}

if($mode=="update"){
	demo_check_json();

	$where_sql = "  where $id='{$id_value}'";
	$add_sql = ", cs_reply_id='{$member['mb_no']}', cs_reply_datetime=NOW()";

	$common_sql = " $table set cs_reply_memo='$cs_reply_memo', cs_reply_state='$cs_reply_state'";

	sql_query("$mode $common_sql $add_sql $where_sql");

	if($write['cs_reply_state']=="1" and $cs_reply_state=="2"){
		$nfor['sd_memo'] = nl2br($cs_reply_memo);
		nfor_send("customer",$write['cs_email'],$write['cs_tel'],$write['cs_insert_id']);
	}

	json_return("답변사항을 수정 하였습니다","ok");
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
	<th>문의유형</th> 
	<td><?=$write['cs_category']?></td>
	<th>아이디</th>
	<td><?=$write['cs_mb_id']?></td>
</tr>
<tr>
	<th>이름</th>
	<td><?=$write['cs_name']?></td>
	<th>이메일</th>
	<td><?=$write['cs_email']?></td>
</tr>
<tr>
	<th>연락처</th>
	<td><?=$write['cs_tel']?></td>
	<th>아이피</th>
	<td>
	<?=$write['cs_ip']?>	
	<a class="btn_bad_ip btn-gray btn-sm" data-bd_ip="<?=$write['cs_ip']?>" data-bd_memo="관리자 임의차단(고객문의)">아이피차단</a>	
	</td>
</tr>
<tr>
	<th>제목</th> 
	<td colspan="3"><?=$write['cs_subject']?></td>
</tr>
<tr>
	<th>내용</th> 
	<td colspan="3"><?=nl2br($write['cs_memo'])?></td>
</tr>
<tr>
	<th>등록일시</th> 
	<td colspan="3"><?=$write['cs_insert_datetime']?></td>
</tr>
<?php if($write['cs_file_array']){ ?>
<tr>
	<th>첨부파일</th>
	<td colspan="3">
		<ul class="file_upload_preview">
		<?php		
		$cs_file_array = explode("||",$write['cs_file_array']);
		$cs_filename_array = explode("||",$write['cs_filename_array']);
		for($i=0; $i<count($cs_file_array); $i++){
		?>
		<li>
			<a href="<?=$nfor['path']?>/file_download.php?file_tbl=customer&file_id=<?=$write['cs_id']?>&file_number=<?=$i?>"><?=$cs_filename_array[$i]?></a>
		</li>
		<?php }	?>
		</ul>
	</td>
</tr>
<?php } ?>
<tr>
	<th>답변내용</th> 
	<td colspan="3"><?=admin_textarea($write,"cs_reply_memo",""," rows=\"15\"")?></td>
</tr>
<tr>
	<th>답변상태</th> 
	<td colspan="3"><?=admin_radio($write,"cs_reply_state")?></td>
</tr>
<?php if($write['cs_reply_state']=="2"){ ?>
<tr>
	<th>답변일시</th> 
	<td colspan="3"><?=$write['cs_reply_datetime']?></td>
</tr>
<?php } ?>
</table>

<div class="bottom_btn">
	<div class="form-inline">
		<?=admin_submit("fsubmit_btn", "답변하기", "btn btn-lg btn-red")?>
		<?=admin_a("list", "목록보기", "btn_back btn btn-lg btn-black")?>
	</div>
</div>

</form>

<SCRIPT LANGUAGE="JavaScript">
<!--
$(document).on("click", ".btn_bad_ip", function(){
	var bd_ip = $(this).data("bd_ip");
	var bd_memo = $(this).data("bd_memo");
	$.ajax({
		type: "post",
		data : "mode=bad_ip&bd_ip="+bd_ip+"&bd_memo="+bd_memo,
		url: nfor_path + "/json.php",
		success: function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="insert"){
				alert(json["msg"]);		
			} else{
				alert(json["msg"]);
			}
		}
	});
});

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