<?php
include_once "path.php";

$form = $_SERVER[PHP_SELF];
$list = str_replace("form","list",$form);
$table = "nfor_level";
$id = "lv_id";

$id_value = $$id;

if($id_value){
	$write = sql_fetch("select * from $table where $id='{$id_value}'");
}

if($mode=="insert" or $mode=="update"){
	demo_check();

	$add_sql = "";
	$where_sql = "";

	$add_sql .= admin_upload($write,"lv_icon_img","level/icon","$table"," where $id='{$id_value}'");
	$add_sql .= admin_upload($write,"lv_img","level/img","$table"," where $id='{$id_value}'");

	if($mode=="insert"){
		$msg = "정상적으로 등록 되었습니다";
		$move = "$list";
		$add_sql .= ", lv_insert_id='$member[mb_no]', lv_insert_datetime=NOW()";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$move = "$form?{$qstr}&{$id}={$id_value}";
		$where_sql = "  where $id='{$id_value}'";
		$add_sql .= ", lv_update_id='$member[mb_no]', lv_update_datetime=NOW()";
	} else{

	}
	$common_sql = " $table set lv_rank='$lv_rank', lv_name='$lv_name'";

	sql_query("$mode $common_sql $add_sql $where_sql");


	/* DB부하를 줄이고자 네이밍 적용 */
	if($mode=="insert"){
		$lv_id = sql_insert_id();
	}
	$data = sql_fetch("select * from $table where lv_id='$lv_id'");
    if($_FILES[lv_icon_img]['name']){
		rename("$nfor[path]/data/level/icon/$data[lv_icon_img]","$nfor[path]/data/level/icon/$lv_id");
		sql_query("update $table set lv_icon_img='$lv_id' where lv_id='$lv_id'");
	}
    if($_FILES[lv_img]['name']){
		rename("$nfor[path]/data/level/img/$data[lv_img]","$nfor[path]/data/level/img/$lv_id");
		sql_query("update $table set lv_img='$lv_id' where lv_id='$lv_id'");
	}
	/* DB부하를 줄이고자 네이밍 적용 */

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
	<th>레벨명</th> 
	<td><?=admin_text($write,"lv_name")?></td>
</tr>
<tr>
	<th>레벨아이콘</th> 
	<td>

	<div id="lv_icon_type_3">
	<?=admin_file($write,"lv_icon_img","level/icon")?>
	<?=admin_help(" ※ jpg, jpeg, png, gif만 등록 가능하며, 기본 등급표시 아이콘은 16x16 pixel 입니다. ")?>
	</div>

	</td>
</tr>
<tr>
	<th>레벨이미지</th> 
	<td>
	
	<div id="lv_img_type_3">
	<?=admin_file($write,"lv_img","level/img")?>
	<?=admin_help(" ※ jpg, jpeg, png, gif만 등록 가능하며, 기본 등급표시 아이콘은 70x70 pixel 입니다. ")?>
	</div>

	</td>
</tr>
<tr>
	<th>레벨우선순위</th> 
	<td><?=admin_text($write,"lv_rank","width-50p")?></td>
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