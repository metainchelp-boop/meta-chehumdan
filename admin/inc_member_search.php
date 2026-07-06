<form name="fsearch" id="fsearch" method="get">
<?=admin_hidden($_GET,"search_tr_detail")?>

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
<tr class="search_tr_detail">
	<th>승인여부</th>
	<td><?=admin_radio($_GET,"mb_asign","","","0")?></td>
	<th>레벨</th>
	<td>
	<div class="form-inline"><?=admin_select($_GET,"mb_level","width-80p","","0")?></div>
	</td>
</tr>
<tr class="search_tr_detail">
	<th>방문횟수</th>
	<td>
		<div class="form-inline"><?=admin_text($_GET,"mb_login_count_1","width-80p")?> 회 ~ <?=admin_text($_GET,"mb_login_count_2","width-80p")?> 회</div>
	</td>
	<th>장기 미로그인</th>
	<td>
		<div class="form-inline"><?=admin_text($_GET,"mb_last_login_day","width-80p")?> 일이상 로그인하지 않은 회원</div>
	</td>
</tr>

<tr class="search_tr_detail">
	<th>메일수신동의</th>
	<td>
	<?=admin_radio($_GET,"mb_mailling","","","0")?>
	</td>
	<th>SMS수신동의</th>
	<td>
	<?=admin_radio($_GET,"mb_sms","","","0")?>
	</td>
</tr>
<tr class="search_tr_detail">
	<th>가입경로</th>
	<td>
	<?=admin_radio($_GET,"mb_join_channel","","","0")?>
	</td>
	<th>성별</th>
	<td>
	<?=admin_radio($_GET,"mb_sex","","","0")?>
	</td>
</tr>
<tr class="search_tr_detail">
	<th>연결계정</th>
	<td>
	<?=admin_radio($_GET,"mb_sns_type","","","0")?>
	</td>
	<th>기타</th>
	<td colspan="3"><?=admin_checkbox($checkbox,"mb_access","",$_GET[mb_access]?"checked":"","접근차단")?> <?=admin_checkbox($checkbox,"mb_black","",$_GET[mb_black]?"checked":"","블랙컨슈머")?></td>
</tr>
</table>



<?=admin_button("search_tr_detail_btn", $search_tr_detail?"상세검색 닫힘":"상세검색 펼침", "btn btn-sm")?>

<div class="table_btn"><div class="form-inline"><?=admin_submit("fsubmit", "검색하기", "btn-lg btn-black btn")?> <?=admin_button("exceldown", "엑셀다운", "btn-lg btn exceldown")?></div></div>


<div class="ofw martop20">
	<div class="flol">
	전체 <span class="txt_red"><?=number_format($total_count)?></span>건 / 검색 <span class="txt_red"><?=number_format($search_count)?></span>건
	</div>
	<div class="flor">
	<?=admin_sort($admin[period_type],$sort)?>	
	<?=admin_page_row($page_row)?>
	</div>
</div>
</form>


<?php if(!$search_tr_detail){ ?>
<style>
.search_tr_detail { display:none; }
</style>
<?php } ?>