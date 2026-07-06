<?php
include_once "path.php";

$admin['period_type'] = array("api_insert_datetime" => "등록일자", "api_update_datetime" => "수정일자");	

$list = $_SERVER['PHP_SELF'];
$form = str_replace("list","form",$list);
$table = "nfor_api";
$id = "api_id";

if($mode=="list_delete"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		sql_query("delete from $table where $id='{$_POST[$id][$k]}'");
	}
	json_return("정상적으로 삭제되었습니다","ok");
}

if($mode=="delete"){
	demo_check_json();
	sql_query("delete from $table where $id='{$$id}'");
	json_return("정상적으로 삭제되었습니다","ok");
}

$sql_common = " from $table ";
$sql_search = " where (1) ";

$all_count_sql = "select count(*) as cnt $sql_common $sql_search";

if($keyword) $sql_search .= " and api_domain like '%$keyword%'";

if($period_sdate and $period_edate and $period_type){
	$sql_search .= " and date_format($period_type,'%Y-%m-%d')>='$period_sdate' and date_format($period_type,'%Y-%m-%d')<='$period_edate' ";
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

$page_row = sql_page_row($page_row);

$total_page  = ceil($search_count / $page_row);
if(!$page) $page = 1;
$from_record = ($page - 1) * $page_row;
$sql = " select *
				  $sql_common
				  $sql_search
				  $sql_order
				  limit $from_record, $page_row ";
$result = sql_query($sql);

include_once "head.php";
?>
<?=admin_help("※ SNS 로그인 및 구글 애널리틱스 카운터, 지도 등 API 관련 설정을 할수 있습니다","line50 notice_gray")?>
<form name="fsearch" id="fsearch" method="get">
<table class="table cols_tbl">
<tr>
	<th>도메인</th>
	<td><?=admin_text($_GET,"keyword","","maxlength=\"30\"")?></td>
</tr>
<tr>
	<th>기간검색</th>
	<td>
	<div class="form-inline">
		<?=admin_select($_GET,"period_type","","","0")?>
		<?=admin_text($_GET,"period_sdate","width-100p datepicker-here","data-language=\"ko\"")?> ~ <?=admin_text($_GET,"period_edate","width-100p datepicker-here","data-language=\"ko\"")?>
		<?=period_day_echo($_GET[period_day])?>
	</div>
	</td>
</tr>
</table>

<div class="table_btn"><div class="form-inline"><?=admin_submit("fsubmit", "검색하기", "btn-lg btn-black btn")?></div></div>

<div class="ofw martop20">
	<div class="flol">
	전체 <span class="txt_red"> <?=number_format($total_count)?></span>건 / 검색 <span class="txt_red"><?=number_format($search_count)?></span>건
	</div>
	<div class="flor">
	<?=admin_sort($admin['period_type'],$sort)?>
	<?=admin_page_row($page_row)?>
	</div>
</div>
</form>

<form name="flist" id="flist" method="post">
<?=admin_hidden($hidden,"ex_id")?>
<?=admin_get()?>
<table class="table row_tbl margin0">
<colgroup>
	<col class="width-80p">
	<col >
	<col >
	<col >
	<col >
	<col >
	<col class="width-100p">
	<col class="width-80p">
	<col class="width-80p">
</colgroup>
<tr>
	<th><?=admin_checkbox($row,"chkall")?></th>
	<th>도메인</th>
	<th>네이버</th>	
	<th>카카오톡</th>
	<th>페이스북</th>
	<th>구글</th>
	<th>등록일자</th>
	<th>수정</th>
	<th>삭제</th>
</tr>
<?php
for($i=0; $row=sql_fetch_array($result); $i++){
	$row["chk[]"] = $i;
	$row["{$id}[{$i}]"] = $row[$id];
?>
<tr>
	<td><input type="checkbox" name="chk[]" class="chk_ea" value="<?=$i?>"><input type="hidden" name="<?=$id?>[<?=$i?>]" value="<?=$row[$id]?>"></td>
	<td><?=$row[api_domain]?></td>
	<td>
		<b>Client ID</b><br><?=$row['api_naver_client_id']?><br><br>
		<b>Client Secret</b><br><?=$row['api_naver_client_secret']?>
	</td>
	<td>
		<b>REST KEY</b><br><?=$row['api_kakao_rest']?><br><br>
		<b>JAVASCRIPT KEY</b><br><?=$row['api_kakao_javascript']?>
	</td>
	<td>
		<b>App ID</b><br><?=$row['api_facebook_appid']?><br><br>
		<b>App Secret</b><br><?=$row['api_facebook_appsecret']?>
	</td>
	<td>
		<b>애널리틱스 ViewId</b><br><?=$row['api_google_analytics_viewid']?><br><br>
		<b>애널리틱스 속성Id</b><br><?=$row['api_google_analytics_propertyid']?>
	</td>
	<td><?=substr($row['api_insert_datetime'],0,10)?></td>
	<td><?=admin_a("edit", "수정", "btn btn-white btn-sm", "", "{$form}?{$qstr}&{$id}={$row[$id]}")?></td>
	<td><?=admin_a("delete", "삭제", "btn btn-white btn-sm nfor_button", "data-confirm=\"삭제하시겠습니까?\" data-data=\"mode=delete&{$id}={$row[$id]}\"")?></td>
</tr>
<?php
}
$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "?$qstr&page=");
?>
</table>

<div class="bottom_btn">
	
	<div class="form-inline">
	<?=admin_button("list_delete", "선택삭제", "btn-lg btn-red btn")?>
	<?=admin_a("form", "등록하기", "btn-lg btn-black btn", "", $form)?>
	</div>

</div>

<div class="table_btn"><?=$pagelist?></div>
</form>

<script type="text/javascript">
<!--
$(document).on("click", "#chkall", function(){
	nfor_chk_all(this, 'chk')
});

$(document).on("click", "#list_delete", function(){
	nfor_list_reload('삭제','list_delete');
});
//-->
</script>

<?php
include_once "tail.php";
?>