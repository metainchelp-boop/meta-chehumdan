<?php
include_once "path.php";

$admin[keyword_type] = array(""=>"전체","sd_name" => "구분", "sd_code"=>"코드", "sd_subject"=>"제목", "sd_memo"=>"내용");
$admin[sd_mail_use] = array("전체","사용","미사용");
$admin["sd_mail_use[]"] = $admin[sd_mail_use];
$admin[period_type] = array("sd_update_datetime" => "수정일자");	



$qstr .= "&sd_mail_use=$sd_mail_use";

$list = $_SERVER[PHP_SELF];
$form = str_replace("list","form",$list);
$table = "nfor_send";
$id = "sd_id";

if($mode=="list_update"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		sql_query("update $table set sd_subject='{$_POST['sd_subject'][$k]}', sd_mail_use='{$_POST['sd_mail_use'][$k]}' where $id='{$_POST[$id][$k]}'");
	}
	json_return("정상적으로 수정되었습니다","ok");
}

if($mode=="list_update"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		sql_query("update $table set sd_subject='{$sd_subject[$k]}', sd_mail_use='{$sd_mail_use[$k]}' where $id='{$_POST[$id][$k]}'");
	}
	json_return("정상적으로 삭제되었습니다","ok");
}

$sql_common = " from $table ";
$sql_search = " where sd_mail_show='1' ";

$all_count_sql = "select count(*) as cnt $sql_common $sql_search";

if($sd_mail_use) $sql_search .= " and sd_mail_use = '$sd_mail_use'";

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
<?=admin_help("자동으로 발송되는 메일의 내용을 설정합니다.(메일발송은 서버에서의 제한이 없으나 수신을 보장하지 않으므로 가급적 문자서비스를 이용해주세요)","line50 notice_gray")?>
<form name="fsearch" id="fsearch" method="get">
<table class="table cols_tbl">
<tr>
	<th>검색어</th>
	<td>
		<div class="form-inline">
		<?=admin_select($_GET,"keyword_type","","","0")?>
		<?=admin_text($_GET,"keyword","","maxlength=\"30\"")?>
		</div>
	</td>
	<th>사용여부</th>
	<td><?=admin_radio($_GET,"sd_mail_use","","","0")?></td>
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
</table>

<div class="table_btn"><div class="form-inline"><?=admin_submit("fsubmit", "검색하기", "btn-lg btn-black btn")?></div></div>

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

<form name="flist" id="flist" method="post">
<?=admin_hidden($hidden,"ex_id")?>
<?=admin_get()?>
<table class="table row_tbl margin0">
<colgroup>
	<col class="width-80p">
	<col class="width-100p">
	<col class="width-100p">
	<col >
	<col class="width-80p">
	<col class="width-100p">
	<col class="width-80p">
</colgroup>
<tr>
	<th><?=admin_checkbox($row,"chkall")?></th>
	<th>구분</th>
	<th>코드</th>
	<th>제목</th>
	<th>사용여부</th>
	<th>수정일</th>
	<th>수정</th>
</tr>
<?php
for($i=0; $row=sql_fetch_array($result); $i++){	
	$row["chk[]"] = $i;
	$row["{$id}[{$i}]"] = $row[$id];
	$row["sd_subject[]"] = $row[sd_subject];
	$row["sd_mail_use[]"] = $row[sd_mail_use];
?>
<tr>
	<td><?=admin_checkbox($row,"chk[]","chk")?><?=admin_hidden($row,"{$id}[{$i}]")?></td>
	<td><?=$row[sd_name]?></td>
	<td><?=$row[sd_code]?></td>
	<td><?=admin_text($row,"sd_subject[]")?></td>
	<td><?=admin_select($row,"sd_mail_use[]","width-80p")?></td>
	<td><?=substr($row[sd_update_datetime],0,10)?></td>
	<td><?=admin_a("edit", "수정", "btn btn-white btn-sm", "", "{$form}?{$qstr}&{$id}={$row[$id]}")?></td>
</tr>
<?php
}
$pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");
?>
</table>

<div class="bottom_btn">
	
	<div class="form-inline">
	<?=admin_button("list_update", "선택수정", "btn-lg btn-red btn")?>
	</div>

</div>

<div class="table_btn"><?=$pagelist?></div>

</form>

<script type="text/javascript">
<!--
$(document).on("click", "#chkall", function(){
	nfor_chk_all(this, 'chk');
});

$(document).on("click", "#list_update", function(){
	nfor_list_reload('수정','list_update');
});
//-->
</script>

<?
include_once "tail.php";
?>