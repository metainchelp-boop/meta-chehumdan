<?php
include_once "path.php";

$admin[pu_target] = array(""=>"선택","Google" => "구글","Apple" => "애플");

$qstr .= "&pu_target=$pu_target";

$form = $_SERVER[PHP_SELF];
$list = str_replace("form","list",$form);
$table = "nfor_push";
$id = "pu_id";

$id_value = $$id;

if($id_value){
	$write = sql_fetch("select * from $table where $id='{$id_value}'");
}

if($mode=="insert" or $mode=="update"){
	demo_check();

	$add_sql = "";
	$where_sql = "";

	$add_sql .= admin_upload($write,"pu_img","push","$table"," where $id='{$id_value}'");

	if($mode=="insert"){
		$msg = "정상적으로 등록 되었습니다";
		$move = "$list";
		$add_sql .= ", pu_insert_id='$member[mb_no]', pu_insert_datetime=NOW()";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$move = "$form?{$qstr}&{$id}={$id_value}";
		$where_sql = "  where $id='{$id_value}'";
		$add_sql .= ", pu_update_id='$member[mb_no]', pu_update_datetime=NOW()";
	} else{

	}
	$common_sql = " $table set pu_subject='$pu_subject', pu_memo='$pu_memo', pu_url='$pu_url', pu_target='$pu_target'";

	sql_query("$mode $common_sql $add_sql $where_sql");

	alert($msg,$move);
}

include_once "head.php";
?>

<form name="fwrite" method="post" onsubmit="return fsubmit(this)" enctype="multipart/form-data">
<?=admin_hidden($write,"mode")?>
<?=admin_hidden($write,$id)?>


<table class="table cols_tbl margin0">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>발송대상</th> 
	<td>
	<?=admin_select($write,"pu_target","width-150p","","0")?>
	</td>
</tr>
<tr>
	<th>제목</th> 
	<td><?=admin_text($write,"pu_subject")?></td>
</tr>
<tr>
	<th>내용</th> 
	<td><?=admin_textarea($write,"pu_memo","","rows=\"4\"")?></td>
</tr>
<tr>
	<th>이동주소</th> 
	<td><?=admin_text($write,"pu_url")?></td>
</tr>
<tr>
	<th>이미지</th>
	<td><?=admin_file($write,"pu_img","push")?></td>
</tr>
</table>

<div class="bottom_btn">
	<div class="form-inline">
		<?=admin_submit("fsubmit_btn", $write[$id]?"수정하기":"등록하기", "btn btn-lg btn-red")?>
		<?=admin_a("list", "목록보기", "btn btn-lg btn-black", "", $list."?".$qstr)?>
	</div>
</div>

</form>

<script type="text/javascript">
<!--
function fsubmit(f){

	f.action = "<?=$form?>";
	return true;	    
}	
//-->
</script>

<?php
include_once "tail.php";
?>