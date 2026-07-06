<?php
include_once "path.php";

$sql_common = " from nfor_level ";
$sql_search = " where (1) ";
if($keyword) $sql_search .= " and lv_name like '%$keyword%' ";
if(!$sst){
	$sst = "lv_rank";
	$sod = "asc";
}
$sql_order = " order by $sst $sod ";
$sql = " select count(*) as cnt
						 $sql_common
						 $sql_search
						 $sql_order ";
$row = sql_fetch($sql);
$total_count = $row[cnt];
$rows = 10;
$total_page  = ceil($total_count / $rows);
if(!$page) $page = 1;
$from_record = ($page - 1) * $rows;
$sql = " select *
				  $sql_common
				  $sql_search
				  $sql_order
				  limit $from_record, $rows ";
$result = sql_query($sql);
?>
<form id="fsearch">
<input type="hidden" name="page" id="layer_page" value="<?=$_GET[page]?>">
<table class="table cols_tbl margin0">
<tr>
	<th>회원등급명</th>
	<td>
		<div class="form-inline">
		<?=admin_text($_GET,"keyword","width-150p")?>
		<?=admin_button("search_btn","검색","btn btn-sm btn-black")?>
		</div>
	</td>
</tr>
</table>
</form>
<br>
<table class="table row_tbl margin0">
<thead>
<tr>
	<th><?=admin_checkbox($write,"allchk","")?></th>
	<th>회원등급명</th>
	<th>등록일자</th>
</tr>
</thead>
<tbody>
<?php
for($i=0; $row=sql_fetch_array($result); $i++){	
?>
<tr>
	<td><input type="checkbox" class="chk" value="<?=$row[lv_id]?>" data-lv_name="<?=$row[lv_name]?>"></td>
	<td><?=$row[lv_name]?></td>
	<td><?=substr($row[lv_insert_datetime],0,10)?></td>
</tr>
<?php
}
$pagelist = layer_paging(5, $page, $total_page);
?>
</tbody>
</table>
<div class="table_btn martop5"><?=$pagelist?></div>
<div class="text-center"><?=admin_button("insert_btn", "레벨선택","btn btn-lg btn-black")?></div>