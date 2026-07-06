<div style="margin-bottom:20px;">
주의사항 : 구글애널리틱스 GA4 의 경우 현재 베타 버전으로 UA와 달리 UTC 시간 차이만큼 지연된 데이터를 가져오고 있으며 구글쪽에서 이를 해결하었을때 개선이 가능하니 참고 바랍니다.
</div>

<form name="fsearch" id="fsearch" method="get">

<table class="table cols_tbl">
<tr>
	<th>기간검색</th>
	<td>
	<div class="form-inline">
		<?=admin_text($_GET,"period_sdate","width-100p datepicker-here","data-language=\"ko\"")?> ~ <?=admin_text($_GET,"period_edate","width-100p datepicker-here","data-language=\"ko\"")?>
		<?=period_day_echo($_GET['period_day'])?>
	</div>
	</td>
</tr>
</table>

<div class="table_btn"><div class="form-inline"><?=admin_submit("fsubmit", "검색하기", "btn-lg btn-black btn")?></div></div>

</form>
