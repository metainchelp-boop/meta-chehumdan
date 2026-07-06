<?php
include_once "path.php";

$form = $_SERVER[PHP_SELF];
$list = str_replace("form","list",$form);
$table = "nfor_point";
$id = "pt_id";

$id_value = $$id;

if($id_value){
	$write = sql_fetch("select * from $table where $id='{$id_value}'");
}

if($mode=="insert" or $mode=="update"){
	demo_check();

	if(!$pt_mb_id) alert("아이디가 입력되지 않았습니다.");
	$mb = sql_fetch("select * from nfor_member where $member_config[cf_mb_id_type]='$pt_mb_id'");
	if(!$mb[mb_no]) alert("존재하지 않는 아이디입니다.");

	if(!$pt_point) alert("적립/차감 포인트가 입력되지 않았습니다.");
	if(!$pt_memo) alert("적립내용이 입력되지 않았습니다.");

	if($pt_point < 0 and $mb[mb_point] < $pt_point*-1) alert("보유하신 포인트보다 입력하신 차감 포인트이 더 큽니다");		
		
	insert_point($mb[mb_no], $pt_point, $pt_memo,"8282",$mb[mb_no]."_".$ftimestamp);

	alert("정상적으로 등록 되었습니다",$list);
}

include_once "head.php";
?>

<form name="fwrite" method="post" onsubmit="return fsubmit(this)">
<?=admin_hidden($write,"mode")?>
<?=admin_hidden($write,$id)?>

<?php
$write[ftimestamp] = date("YmdHis").substr(microtime(),2,6);
?>
<?=admin_hidden($write,"ftimestamp")?>

<table class="table cols_tbl margin0">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>아이디</th> 
	<td><?=admin_text($write,"pt_mb_id","width-150p")?></td>
</tr>
<tr>
	<th>적립/차감 포인트</th> 
	<td><div class="form-inline"><?=admin_text($write,"pt_point","width-100p")?>Point</div></td>
</tr>
<tr>
	<th>적립내용</th> 
	<td><?=admin_text($write,"pt_memo")?></td>
</tr>
</table>

<div class="bottom_btn">
	<div class="form-inline">
		<?=admin_submit("fsubmit_btn", $write[$id]?"수정하기":"등록하기", "btn btn-lg btn-red")?>
		<?=admin_a("list", "목록보기", "btn btn-lg btn-black", "", $list."?".$qstr)?>
	</div>
</div>

</form>

<SCRIPT LANGUAGE="JavaScript">
<!--
function fsubmit(f){
	if(!$('#pt_mb_id').val()){
		alert("아이디를 입력해주세요");
		$('#pt_mb_id').focus();
        return false;
	}
	if(!$('#pt_point').val()){
		alert("적립/차감 포인트를 입력해주세요");
		$('#pt_point').focus();
        return false;
	}
	if(!$('#pt_memo').val()){
		alert("적립내용을 입력해주세요");
		$('#pt_memo').focus();
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