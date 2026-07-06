<form name="fsearch" id="fsearch" method="get">
<?=admin_hidden($_GET,"search_tr_detail")?>

<table class="table cols_tbl">
<colgroup>
	<col class="width-100p">
	<col >
	<col class="width-100p">
	<col >
</colgroup>
<tr>
	<th>검색어</th>
	<td colspan="3">
		<div class="form-inline">
		<?=admin_select($_GET,"keyword_type","","","0")?>
		<?=admin_text($_GET,"keyword","","maxlength=\"30\"")?>
		</div>
	</td>
</tr>
<tr>
	<th>기간검색</th>
	<td colspan="3">
	<div class="form-inline">
		<?=admin_select($_GET,"period_type","","","0")?>
		<?=admin_text($_GET,"period_sdate","width-100p datepicker-here","data-language=\"ko\"")?> ~ <?=admin_text($_GET,"period_edate","width-100p datepicker-here","data-language=\"ko\"")?>
		<?=period_day_echo($_GET[period_day])?>
	</div>
	</td>
</tr>
<tr>
	<th>담당자</th>
	<td colspan="3">
	<div class="form-inline">
		<?=admin_select($_GET,"cp_md_name","width-200p","","0")?>
		<span style="color:#8b95a1;font-size:12px;margin-left:8px">담당자 이름으로 캠페인을 모아서 봅니다</span>
	</div>
	</td>
</tr>
<tr class="search_tr_detail">
	<th>캠페인분류</th>
	<td colspan="3">
	<div class="form-inline">
		<?=admin_hidden($_GET,"insert_cate_id")?>
		<div class="form-group"><?=admin_select($_GET,"campaign_category_1","campaign_multi_select","","0")?></div>
		<div class="form-group"><?=admin_select($_GET,"campaign_category_2","campaign_multi_select","","0")?></div>
		<div class="form-group"><?=admin_select($_GET,"campaign_category_3","campaign_multi_select","","0")?></div>
		<div class="form-group"><?=admin_select($_GET,"campaign_category_4","campaign_multi_select","","0")?></div>
	</div>
	</td>
</tr>
<?php
if($is_admin){
?>
<tr class="search_tr_detail">
	<th>입점업체</th>
	<td><?=admin_select($_GET,"cp_supply_no","width-150p","","0")?></td>
	<th>캠페인 관리자</th>
	<td><?=admin_select($_GET,"cp_md_no","width-150p","","0")?></td>
</tr>
<?php } ?>

<tr class="search_tr_detail">
	<th>노출상태</th>
	<td><?=admin_radio($_GET,"cp_use","","","0")?></td>
	<th>승인여부</th>
	<td><?=admin_radio($_GET,"cp_asign","","","0")?></td>
</tr>




<tr class="search_tr_detail">
	<th>리뷰 매체</th>
	<td>
		<div class="form-inline">
		<?=admin_checkbox($_GET,"cp_media_blog","",$_GET[cp_media_blog]?"checked=\"checked\"":"","블로그")?>
		<?=admin_checkbox($_GET,"cp_media_instagram","",$_GET[cp_media_instagram]?"checked=\"checked\"":"","인스타그램")?>
		<?=admin_checkbox($_GET,"cp_media_youtube","",$_GET[cp_media_youtube]?"checked=\"checked\"":"","유튜브")?>
		</div>
	</td>
	<th>캠페인 형태</th> 
	<td>
	<div class="form-inline"><?=admin_radio($_GET,"cp_type","","","0")?></div>
	</td>
</tr>



<tr class="search_tr_detail">
	<th>캠페인 결제상태(별도구매)</th> 
	<td colspan="3">
	<div class="form-inline"><?=admin_radio($_GET,"cp_pay_step","","","0")?></div>
	</td>
</tr>



</table>

<?=admin_button("search_tr_detail_btn", $search_tr_detail?"상세검색 닫힘":"상세검색 펼침", "btn btn-sm")?>


<? if(substr(basename($PHP_SELF),0,5)=="layer"){ ?>
<input type="hidden" name="page" id="layer_page" value="<?=$_GET[page]?>">
<div class="table_btn"><div class="form-inline"><?=admin_button("search_btn","검색","btn btn-lg btn-black")?></div></div>
<? } else{ ?>
<div class="table_btn"><div class="form-inline"><?=admin_submit("fsubmit", "검색하기", "btn-lg btn-black btn")?> <?=admin_button("exceldown", "엑셀다운", "btn btn-lg btn-black exceldown")?></div></div>
<? } ?>


<div class="ofw martop20">
	<div class="flol">
	전체 <span class="txt_red"> <?=number_format($total_count)?></span>건 / 검색 <span class="txt_red"><?=number_format($search_count)?></span>건
	</div>
	<div class="flor">

	<? if(substr(basename($PHP_SELF),0,5) <> "layer"){ ?>
	
	<?=admin_sort($admin[sort_type],$sort)?>
	<?=admin_page_row($page_row)?>

	<? } ?>
	</div>
</div>
</form>

<script>
$(document).on("change",".campaign_multi_select",campaign_category_change);
// 담당자 드롭다운 = 고르면 즉시 검색(검색 버튼 안 눌러도 됨) 2026-06-16
$(document).on("change","select[name=cp_md_name]",function(){
	if($('#layer_page').length){ $('#layer_page').val(''); }
	this.form.submit();
});
</script>

<?php if(!$search_tr_detail){ ?>
<style>
.search_tr_detail { display:none; }
</style>
<?php } ?>