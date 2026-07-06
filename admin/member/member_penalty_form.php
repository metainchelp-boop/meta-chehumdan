<?php
include_once "path.php";

$admin[pe_type] = array("전체","1차경고","2차경고","이용정지","이용정지 해제");

$nfor[title] = "패널티 등록";

$table = "nfor_penalty";

if($mode=="update"){
	
	$mb = sql_fetch("select * from nfor_member where mb_no='$mb_no'");
	if(($pe_type=="3" or $pe_type=="4") and !$pe_stop_day) alert("이용정지일을 입력해주세요");
	if(($pe_type=="3" or $pe_type=="4") and $pe_stop_day<1) alert("양수만 입력해주세요");

	if($pe_type=="3"){
		if($mb[mb_stop_date]){
			if(strtotime($mb[mb_stop_date]) < time()){
				$mb_stop_date = date("Y-m-d",strtotime("+$pe_stop_day day"));	
			} else{
				$mb_stop_date = date("Y-m-d",strtotime("$mb[mb_stop_date] +$pe_stop_day day"));	
			}
		} else{
			$mb_stop_date = date("Y-m-d",strtotime("+$pe_stop_day day"));	
		}
		sql_query("update nfor_member set mb_stop_date='$mb_stop_date' where mb_no='$mb_no'");
	}

	if($pe_type=="4"){
		if($mb[mb_stop_date]){
			if(strtotime($mb[mb_stop_date]) < time()){
				alert("해당회원은 이용정지 상태가 아닙니다");
			} else{
				$mb_stop_date = date("Y-m-d",strtotime("$mb[mb_stop_date] -$pe_stop_day day"));	
			}
		} else{
			alert("해당회원은 이용정지 상태가 아닙니다");
		}
		sql_query("update nfor_member set mb_stop_date='$mb_stop_date' where mb_no='$mb_no'");
	}


	sql_query("insert $table set pe_mb_stop_date='$mb_stop_date', pe_mb_id='$mb[mb_id]', pe_mb_email='$mb[mb_email]', pe_mb_name='$mb[mb_name]', pe_mb_nick='$mb[mb_nick]', pe_mb_hp='$mb[mb_hp]', pe_mb_no='$mb_no', pe_type='$pe_type', pe_datetime=NOW(), pe_stop_day='$pe_stop_day', pe_stop_memo='$pe_stop_memo'");	
	

	alert_close_refresh("패틸티를 적용하였습니다");
}


include_once "head.php";
?>


<form name="fwrite" method="post" onsubmit="return fsubmit(this)">
<?=admin_hidden($mb,"mode")?>
<?=admin_hidden($mb,"mb_no")?>

<table class="table cols_tbl">	
<colgroup>
	<col class="width-150p">
	<col>
</colgroup>
<tr>
	<th>구분</th>
	<td>
		<?php
		if(!$write[pe_type]) $write[pe_type] = "1";
		?>
		<?=admin_radio($write,"pe_type")?>
	</td>
</tr>
<tr class="map_tr <?=$write[pe_type]<>"3"?"hide":""?>">
	<th><span class="pe_stop_day_title">이용정지기간</span></th>
	<td><div class="form-inline"><?=admin_text($write,"pe_stop_day","width-50p")?>일</div></td>
</tr>

<tr>
	<th>사유</th>
	<td>
	<?=admin_text($write,"pe_stop_memo")?>
	</td>
</tr>
</table>

<div class="bottom_btn">
	<?=admin_submit("fsubmit_btn", "등록하기", "btn btn-lg btn-black")?>
</div>

</form>

<SCRIPT LANGUAGE="JavaScript">
<!--
$(document).on("click","input[name=pe_type]",function(){ 
	var type = this.value;
	if(type=="3"){
		$(".pe_stop_day_title").html("이용정지기간");
	}
	if(type=="4"){
		$(".pe_stop_day_title").html("이용해제기간");
	}
	if(type=="3" || type=="4"){
		$(".map_tr").removeClass("hide");
	} else{
		$(".map_tr").addClass("hide");
	}			
	map.relayout();
});

function fsubmit(f){
	if(!$("#pe_stop_memo").val()){
		alert("사유를 입력해주세요");
		$("#pe_stop_memo").focus();
		return false;
	}

	f.action = "<?=$PHP_SELF?>";
	return true;	    
}	
//-->
</SCRIPT>

<?php
include_once "tail.php";
?>