<?php
include_once "path.php";

$qstr .= "&money_type=$money_type";

$form = $_SERVER[PHP_SELF];
$list = str_replace("form","list",$form);
$table = "nfor_money";
$id = "my_id";

$id_value = $$id;

if($id_value){
	$write = sql_fetch("select * from $table where $id='{$id_value}'");
}

if($mode=="insert"){
	demo_check();

	if(!$my_mb_id) alert("아이디가 입력되지 않았습니다.");
	$mb = sql_fetch("select * from nfor_member where mb_id='$my_mb_id' or mb_email='$my_mb_id'");
	if(!$mb[mb_no]) alert("존재하지 않는 아이디입니다.");

	if(!$my_money) alert("부여적립금이 입력되지 않았습니다.");
	if(!$my_memo) alert("적립내용이 입력되지 않았습니다.");

	if($my_money < 0 and mb_money($mb[mb_no]) < $my_money*-1){ 
		alert("보유하신 적립금보다 입력하신 차감 적립금이 더 큽니다");		
	}

	insert_money($mb[mb_no], $my_money, $my_memo,'0','','',$my_end_datetime);

	$msg = "정상적으로 등록 되었습니다";
	$move = "$list";

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
	<th>아이디</th> 
	<td><?=admin_text($write,"my_mb_id","width-200p")?></td>
</tr>
<tr>
	<th>부여적립금</th> 
	<td><?=admin_text($write,"my_money","width-100p")?></td>
</tr>
<tr>
	<th>적립내용</th> 
	<td><?=admin_text($write,"my_memo","width-150p")?></td>
</tr>
<tr>
	<th>만료일시</th> 
	<td><?=admin_text($write,"my_end_datetime","width-130p datepicker-here","data-timepicker=\"true\" data-language=\"ko\"")?></td>
</tr>
</table>

<div class="bottom_btn">
	<div class="form-inline">
		<?=admin_submit("fsubmit_btn", "등록하기", "btn btn-lg btn-red")?>
		<?=admin_a("list", "목록보기", "btn btn-lg btn-black", "", $list."?".$qstr)?>
	</div>
</div>

</form>

<SCRIPT LANGUAGE="JavaScript">
<!--
function fsubmit(f){
	f.action = "<?=$form?>";
	return true;	    
}	
//-->
</SCRIPT>

<?php
include_once "tail.php";
?>