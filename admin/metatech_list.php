<?php
include_once "path.php";
include_once "inc_metatech_head.php";
include_once "inc_metatech_tail.php";
include_once "head.php";

?>
<form name="fsearch" id="fsearch" method="get">

<table class="table cols_tbl">

<tr>
	<th>구분</th> 
	<td>
		<select name="meta_op_a" class="form-control width-150p">
		<?
		$meta_op_a_arr = $admin["meta_op_a_arr_list"];
		for($i=0;$i<count($meta_op_a_arr);$i++){
			if($meta_op_a == $meta_op_a_arr[$i]){
				$meta_op_a_checked = " selected";
			}
			else{
				$meta_op_a_checked = "";			
			}
		?>
			<option value="<?=$meta_op_a_arr[$i]?>" <?=$meta_op_a_checked?>  class="meta_op_a_option"><?=$meta_op_a_arr[$i]?></option>
		<?
		}
		?>
		</select>		

	</td>
	<th>유형</th> 
	<td>
		<select name="meta_op_b" class="form-control width-150p">
		<?
		$meta_op_b_arr = $admin["meta_op_b_arr_list"];
		for($i=0;$i<count($meta_op_b_arr);$i++){
			if($meta_op_b == $meta_op_b_arr[$i]){
				$meta_op_b_checked = " selected";
			}
			else{
				$meta_op_b_checked = "";			
			}
		?>
			<option value="<?=$meta_op_b_arr[$i]?>" <?=$meta_op_b_checked?>  class="meta_op_b_option"><?=$meta_op_b_arr[$i]?></option>
		<?
		}
		?>
		</select>		
	</td>
</tr>
<?
//include_once "../lib/campaign.lib.php";
?>

<tr>
	<th>검색어</th>
	<td>
		<div class="form-inline">
		<?=admin_text($_GET,"m_name","","maxlength=\"30\"")?>
		</div>
	</td>

	<th>등록일</th>
	<td>
	<div class="form-inline">
		<?=admin_text($_GET,"period_sdate","width-100p datepicker-here","data-language=\"ko\"")?> ~ <?=admin_text($_GET,"period_edate","width-100p datepicker-here","data-language=\"ko\"")?>
		<?=period_day_echo($_GET[period_day])?>
	</div>
	</td>
</tr>
</table>

<div class="table_btn"><div class="form-inline"><?=admin_submit("fsubmit", "검색하기", "btn-lg btn-black btn")?> <?=admin_button("exceldown", "엑셀다운", "btn-lg btn exceldown")?></div></div>
<div class="ofw martop20">
	<div class="flol">
	전체 <span class="txt_red"> <?=number_format($total_count)?></span>건 / 검색 <span class="txt_red"><?=number_format($search_count)?></span>건
	</div>
	<div class="flor">
	<?=admin_page_row($page_row)?>
	</div>
</div>
</form>

<div class="bottom_btn" style="background-color:white">
	<div class="form-inline">
	<?=admin_a("form", "등록하기", "btn-lg btn-black btn", "", $form)?>
	</div>
</div>

<form name="flist" id="flist" method="post">
<?=admin_hidden($hidden,"ex_id")?>
<?=admin_get()?>
<table class="table row_tbl margin0">
<tr>
	<th>번호</th>
	<th>구분</th>
	<th>상호명</th>
	<th>담당자</th>
	<!--th>텍스트/사진</th-->
	<th>유형</th>
	<th>진행기간</th>
	<th>포인트</th>
	<th>참여자수</th>
	<th>등록일</th>
</tr>
<?php

for($i=0; $row=sql_fetch_array($result); $i++){	
/*
	if($row[meta_op_c] == "pic"){
		$meta_op_c_row = "사진";
	}
	else{
		$meta_op_c_row = "텍스트";	
	}
*/
?>
<tr class="go_detail" data-target="<?=$row[idx]?>">
	<td><?=$cur_no?></td>
	<td><?=$row[meta_op_a]?></td>
	<td><?=$row[m_name]?></td>
	<td><?=$row[m_md_name]?htmlspecialchars($row[m_md_name]):'<span style="color:#c0c6cd">-</span>'?></td>
	<!--td><?=$meta_op_c_row?></td-->
	<td><?=$row[meta_op_b]?></td>
	<td><?=$row[m_sdate]."~".$row[m_edate]?></td>
	<td><?=$row[m_point]?></td>
	<td><?=$row[m_count]?></td>
	<td><?=$row[datetime]?></td>
</tr>
<?php
	$cur_no--;
}
$pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");
?>
</table>

<div class="table_btn"><?=$pagelist?></div>

</form>

<script type="text/javascript">
<!--
$(document).on("click", ".go_detail", function(){
	var data_str = $(this).data("target");
	var file_str = "./metatech_detail.php";
	location.href = file_str+"?target="+data_str;
});

//-->
</script>

<?php
include_once "tail.php";
?>