<?php
include_once "path.php";

$admin[ex_id][""] = "선택";
$que = sql_query("select * from nfor_excel where ex_page='$ex_page'");
while($row = sql_fetch_array($que)){
	$admin[ex_id][$row[ex_id]] = $row[ex_name];
}
?>
<table class="table cols_tbl margin10">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>양식선택</th> 
	<td>
	<div class="form-inline">
		<?=admin_select($_GET,"ex_id","","0")?>
		<a href="excel_form.php" target="_blank" class="btn btn-sm btn-black" role="button">엑셀 다운로드양식 추가</a>
	</div>
	</td>
</tr>
</table>

<div class="text-center">
<?=admin_button("excel_download_btn","엑셀다운","btn btn-lg btn-black")?>
</div>