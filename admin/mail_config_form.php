<?php
include_once "path.php";





if($mode=="test"){
	demo_check_json();

	// 답변내용
	$nfor[sd_memo] = "안녕하세요 지금 보시는 화면은<br>엔포 솔루션 데모 페이지의 샘플 답변멘트입니다";

	// 임시비밀번호
	$nfor[tmp_password] = rand("1111","9999");

	// 인증번호
	$nfor[asign_number] = rand("1111","9999");

/*
	if($sd_code=="order" or $sd_code=="order_cancelrequest" or $sd_code=="order_cancel" or $sd_code=="banking_request" or $sd_code=="order_wait_asign" or $sd_code=="delivery" or $sd_code=="ticket" or $sd_code=="delivery_ready" or $sd_code=="delivery_return_wait" or $sd_code=="delivery_return" or $sd_code=="ticket_exp"){
			
		
		if($sd_code=="delivery"){
			$cart = sql_fetch("select * from nfor_cart where ct_pay_step='1' and ct_it_type='1' and ct_dy_num<>''");
			$order = sql_fetch("select * from nfor_order where od_id='$cart[ct_od_id]'");
		} elseif($sd_code=="ticket" or $sd_code=="ticket_exp"){
			$cart = sql_fetch("select * from nfor_cart where ct_pay_step='1' and ct_it_type='2'");
			$order = sql_fetch("select * from nfor_order where od_id='$cart[ct_od_id]'");
		} else{
			$order = sql_fetch("select * from nfor_order where od_pay_step='1' and od_payment_type='banking'");
		}

		
		$od_id = $order[od_id];
		$ct_id = $cart[ct_id];
	} else{
		$od_id = "";
		$ct_id = "";
	}
*/

	
	$review = sql_fetch("select * from nfor_review where rv_mb_no='$member[mb_no]'");
	$rv_id = $review[rv_id];


	nfor_send($sd_code, $member[mb_email], $member[mb_hp], $member[mb_no], $rv_id, $url);

	json_return("정상적으로 발송되었습니다","ok");
}



$admin[sd_mail_use] = array("전체","사용","미사용");
$admin[sd_mail_design_use] = array("전체","사용","미사용");

if(!is_array($sd_mail_use)){
	$qstr .= "&sd_mail_use=$sd_mail_use";
}

$qstr .= "&mail_config_type=$mail_config_type";

$form = $_SERVER[PHP_SELF];
$list = str_replace("form","list",$form);
$table = "nfor_send";
$id = "sd_id";

$id_value = $$id;

if($id_value){
	$write = sql_fetch("select * from $table where $id='{$id_value}'");
}

if($mode=="insert" or $mode=="update"){
	demo_check();

	$add_sql = "";
	$where_sql = "";

	if($mode=="insert"){
		$msg = "정상적으로 등록 되었습니다";
		$move = "$list";
		$add_sql .= ", sd_insert_id='$member[mb_no]', sd_insert_datetime=NOW()";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$move = "$form?{$qstr}&{$id}={$id_value}";
		$where_sql = "  where $id='{$id_value}'";
		$add_sql .= ", sd_update_id='$member[mb_no]', sd_update_datetime=NOW()";
	} else{

	}
	$common_sql = " $table set sd_name='$sd_name', sd_code='$sd_code', sd_subject='$sd_subject', sd_memo='$sd_memo', sd_mail_use='$sd_mail_use', sd_mail_design_use='$sd_mail_design_use'";

	sql_query("$mode $common_sql $add_sql $where_sql");
	alert($msg,$move);
}

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
	<th>코드</th> 
	<td><?=admin_text($write,"sd_code","",$write[sd_id]?"readonly":"")?></td>
</tr>
<tr>
	<th>구분</th>
	<td><?=admin_text($write,"sd_name","",$write[sd_id]?"readonly":"")?></td>
</tr>


<tr>
	<th>제목</th> 
	<td><?=admin_text($write,"sd_subject")?></td>
</tr>
<tr>
	<th>메일 상하단 디자인 사용</th> 
	<td>
	<?
	if(!$write[sd_mail_design_use]) $write[sd_mail_design_use] = "1";
	?>
	<?=admin_radio($write,"sd_mail_design_use")?>

	<?=admin_help("※ 환경설정 > 사이트 운영설정의 메일디자인 설정을 통해서 디자인을 공통 반영합니다")?>
	</td>
</tr>
<tr>
	<th>내용</th> 
	<td><?=admin_textarea($write,"sd_memo","","rows=\"20\"")?></td>
</tr>
<tr>
	<th>사용여부</th> 
	<td>
	<?
	if(!$write[sd_mail_use]) $write[sd_mail_use] = "1";
	?>
	<?=admin_radio($write,"sd_mail_use")?>
	</td>
</tr>

<? if($write[sd_id]){ ?>
<tr>
	<th>발송테스트</th>
	<td>
		<?=admin_a("test", "발송테스트", "btn btn-white btn-sm nfor_button", "data-confirm=\"테스트 발송하시겠습니까?\" data-data=\"mode=test&sd_code={$write[sd_code]}\"")?>
	</td>
</tr>
<? } ?>



</table>

<div class="bottom_btn">
	<div class="form-inline">
		<?=admin_submit("fsubmit", $write[$id]?"수정하기":"등록하기", "btn btn-lg btn-red")?>
		<?=admin_a("list", "목록보기", "btn btn-lg btn-black", "", $list."?".$qstr)?>
	</div>
</div>

</form>

<SCRIPT LANGUAGE="JavaScript">
<!--
function fsubmit(f){
	if(!$('#sd_subject').val()){
		alert("제목을 입력해주세요");
		$('#sd_subject').focus();
        return false;
	}
	if(!$('#sd_memo').val()){
		alert("내용을 입력해주세요");
		$('#sd_memo').focus();
        return false;
	}
	f.action = "<?=$form?>";
	return true;	    
}	
//-->
</SCRIPT>

<?php
include_once "tail.php";
?>