<?php
include_once "path.php";

if(!is_array($no_use)){
	$qstr .= "&no_use=$no_use";
}

$qstr .= "&notice_type=$notice_type&no_category=$no_category";

$form = $_SERVER[PHP_SELF];
$list = str_replace("view","list",$form);
$table = "nfor_supply_notice";
$id = "no_id";

$id_value = $$id;

if($id_value){
	$write = sql_fetch("select * from $table where $id='{$id_value}'");
}


if(!$_SESSION["no_hit_supply_notice_{$no_id}"]){
	sql_query(" update $table set no_hit = no_hit + 1 where no_id = '$no_id' ");
	$_SESSION["no_hit_supply_notice_{$no_id}"] = TRUE;
}



include_once "head.php";
?>



<table class="table cols_tbl margin0">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>분류</th> 
	<td><?=$write[no_category]?></td>
</tr>
<tr>
	<th>제목</th> 
	<td><?=$write[no_subject]?></td>
</tr>
<tr>
	<th>내용</th> 
	<td><?=$write[no_memo]?></td>
</tr>
<tr>
	<th>등록일시</th> 
	<td><?=$write[no_insert_datetime]?></td>
</tr>
<tr>
	<th>조회수</th> 
	<td><?=$write[no_hit]?></td>
</tr>
</table>

<div class="bottom_btn">
	<div class="form-inline">
		<?=admin_a("list", "목록보기", "btn btn-lg btn-black", "", $list."?".$qstr)?>
	</div>
</div>


<?php
include_once "tail.php";
?>