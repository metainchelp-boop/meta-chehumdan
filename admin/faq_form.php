<?php
include_once "path.php";

$admin[fa_use] = array("전체", "노출", "미노출");
$admin[fa_category][""] = "선택";
$que = sql_query("select * from nfor_value where val_code='faq' order by val_rank asc");
while($row = sql_fetch_array($que)){
	$admin[fa_category][$row[val_name]] = $row[val_name];
}

$qstr .= "&fa_category=$fa_category&fa_use=$fa_use";

$form = $_SERVER[PHP_SELF];
$list = str_replace("form","list",$form);
$table = "nfor_faq";
$id = "fa_id";

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
		$add_sql .= ", fa_insert_id='$member[mb_no]', fa_insert_datetime=NOW()";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$move = "$form?{$qstr}&{$id}={$id_value}";
		$where_sql = "  where $id='{$id_value}'";
		$add_sql .= ", fa_update_id='$member[mb_no]', fa_update_datetime=NOW()";
	} else{

	}
	$common_sql = " $table set fa_category='$fa_category', fa_subject='$fa_subject', fa_memo='$fa_memo', fa_rank='$fa_rank', fa_use='$fa_use'";

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
	<th>분류</th> 
	<td><div class="form-inline"><?=admin_select($write,"fa_category","width-150p","","0")?> <?=admin_a("find_cf_cp_zip","분류추가","btn-gray btn-sm"," target='_blank'","value_form.php")?> <?=admin_help("※ 분류 수정/추가는 '환경설정 > 사용자정의 데이터' 메뉴를 통해서 변경가능합니다")?></div></td>
</tr>
<tr>
	<th>제목</th> 
	<td><?=admin_text($write,"fa_subject")?></td>
</tr>
<tr>
	<th>내용</th> 
	<td><?=admin_textarea($write,"fa_memo","","rows=\"15\"")?></td>
</tr>
<tr>
	<th>노출순위</th> 
	<td><?=admin_text($write,"fa_rank","width-50p")?></td>
</tr>
<tr>
	<th>노출여부</th> 
	<td>
	<?php
	if(!$write[fa_use]) $write[fa_use] = "1";
	?>
	<?=admin_radio($write,"fa_use")?>
	</td>
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
	f.action = "<?=$form?>";
	return true;	    
}	
//-->
</SCRIPT>

<?php
include_once "tail.php";
?>