<?php
include_once "path.php";

$page_row = 10;

$admin[keyword_type] = array(""=>"전체","gp_code" => "그룹코드", "gp_name"=>"그룹이름");

$file = "layer_banner.php";
$table = "nfor_banner_group";
$id = "gp_id";

if($mode=="list_update"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		sql_query("update $table set gp_code='{$_POST[gp_code][$k]}', gp_name='{$_POST[gp_name][$k]}' where $id='{$_POST[$id][$k]}'");
	}
	json_return("수정되었습니다","ok");
}

if($mode=="list_delete"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		sql_query("delete from $table where $id='{$_POST[$id][$k]}'");
	}
	json_return("삭제되었습니다","ok");
}

if($mode=="delete"){
	demo_check_json();
	sql_query("delete from $table where $id='{$$id}'");
	json_return("삭제되었습니다","ok");
}

if($mode=="insert"){
	demo_check_json();
	$chk_code = sql_fetch("select * from $table where gp_code='$gp_code'");
	if($chk_code[gp_code]){
		json_return("이미 존재하는 그룹코드입니다","is");
	}
	sql_query("insert $table set gp_name='$gp_name', gp_code='$gp_code'");
	json_return("등록되었습니다","ok");
}

$sql_common = " from $table ";
$sql_search = " where (1) ";

$all_count_sql = "select count(*) as cnt $sql_common $sql_search";

if($keyword){
	if($keyword_type){
		$sql_search .= " and $keyword_type like '%$keyword%' ";
	} else{
		$sql_search .= " and (";
		$j = 0;
		foreach ($admin[keyword_type] as $key => $value){
			if($j>1) $sql_search .= " or ";
			if($key){
				$sql_search .= " ($key like '%$keyword%') ";
			}
			$j++;
		}
		$sql_search .= ") ";
	}
}

if(!$sort){
	$sort = "$id desc";
}

$sql_order = " order by ".safe_orderby($sort)." ";
$search_count_sql = "select count(*) as cnt $sql_common $sql_search";

$row = sql_fetch($all_count_sql);
$total_count = $row[cnt];

if($all_count_sql==$search_count_sql){
	$search_count = $total_count;
} else{
	$row = sql_fetch($search_count_sql);
	$search_count = $row[cnt];
}


$total_page  = ceil($search_count / $page_row);
if(!$page) $page = 1;
$from_record = ($page - 1) * $page_row;
$sql = " select *
				  $sql_common
				  $sql_search
				  $sql_order
				  limit $from_record, $page_row ";
$result = sql_query($sql);
?>

<form id="layer_insert_form">
<input type="hidden" name="mode" value="insert">
<table  class="table cols_tbl margin0">
<tr>
	<th>그룹코드</th>
	<td><?=admin_text($write,"gp_code")?></td>
	<th>그룹이름</th>
	<td><?=admin_text($write,"gp_name")?></td>
</tr>
</table>
<div  class="bottom_btn">
<input type="button" value="등록하기" class="btn btn-red" id="submit_btn">
</div>
</form>

<form id="fsearch">
<input type="hidden" name="mode" id="layer_mode">
<input type="hidden" name="page" id="layer_page" value="<?=$_GET[page]?>">

<table class="table cols_tbl margin5">
<tr>
	<th>검색</th>
	<td><div class="form-inline"><?=admin_select($_GET,"keyword_type")?><?=admin_text($_GET,"keyword")?><?=admin_button("search_btn","조회하기","btn-gray btn-sm")?></div></td>
</tr>
</table>

<table class="table row_tbl margin0">
<thead>
<tr>
	<th><?=admin_checkbox($write,"allchk","")?></th>
	<th>그룹코드</th>
	<th>그룹이름</th>
	<th>삭제</th>
</tr>
</thead>
<tbody>
<?
for($i=0; $row=sql_fetch_array($result); $i++){	
	$row["gp_code[$i]"] = $row[gp_code];
	$row["gp_name[$i]"] = $row[gp_name];
?>
<tr>
	<td><input type="checkbox" name="chk[]" class="gp_chk" value="<?=$i?>"><input type="hidden" name="<?=$id?>[<?=$i?>]" value="<?=$row[$id]?>"></td>
	<td><?=admin_text($row,"gp_code[$i]")?></td>
	<td><?=admin_text($row,"gp_name[$i]")?></td>
	<td><a class="btn btn-white btn-sm delete" data-gp_name="<?=$row[gp_name]?>" data-gp_id="<?=$row[$id]?>">삭제</a></td>
 </tr>
<?
}
$pagelist = layer_paging(5, $page, $total_page);
?>
</tbody>
</table>
<div class="table_btn martop5"><?=$pagelist?></div>
<div class="table_btn martop5"><?=admin_button("select_delete_btn","선택 삭제","btn btn-lg btn-red")?> <?=admin_button("select_update_btn","선택 수정","btn btn-lg btn-black")?></div>
</form>