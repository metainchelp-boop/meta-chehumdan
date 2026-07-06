<form name="fsearch" id="fsearch" method="get">
<table class="table cols_tbl">
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
	<th>결제수단</th> 
	<td><?=admin_radio($_GET,"co_payment_type","payment_type","","0")?></td>
	<th>캠페인 형태</th> 
	<td><?=admin_radio($_GET,"co_cp_type","co_cp_type","","0")?></td>
</tr>
<tr>
	<th>제공내역 형태</th> 
	<td colspan="3"><?=admin_radio($_GET,"co_cp_reward_type","co_cp_reward_type","","0")?></td>
</tr>
</table>

<div class="table_btn"><div class="form-inline"><?=admin_submit("fsubmit", "검색하기", "btn-lg btn-black btn")?> <?=admin_button("exceldown", "엑셀다운", "btn-lg btn exceldown")?></div></div>

<div class="ofw martop20">
	<div class="flol">
	전체 <span class="txt_red"> <?=number_format($total_count)?></span>건 / 검색 <span class="txt_red"><?=number_format($search_count)?></span>건
	</div>
	<div class="flor">
	<?=admin_sort($admin[period_type],$sort)?>
	<?=admin_page_row($page_row)?>
	</div>
</div>
</form>