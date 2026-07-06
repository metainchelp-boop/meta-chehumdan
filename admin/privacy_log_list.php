<?php
include_once "path.php";

$admin[privacy_log_type] = array(""=>"전체","log_mb_id" => "접속아이디", "log_ip"=>"접속아이피");

$qstr .= "&privacy_log_type=$privacy_log_type";

$list = $_SERVER[PHP_SELF];
$form = str_replace("list","form",$list);
$table = "nfor_privacy_log";
$id = "log_id";

if($mode=="list_delete"){
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		sql_query("delete from $table where $id='{$_POST[$id][$k]}'");
	}
	alert("정상적으로 삭제되었습니다","$list?$qstr");
	exit;
}

if($mode=="delete"){
	sql_query("delete from $table where $id='{$$id}'");
	alert("정상적으로 삭제되었습니다","$list?$qstr");
}

$sql_common = " from $table ";
$sql_search = " where (1) ";

$all_count_sql = "select count(*) as cnt $sql_common $sql_search";

if($keyword){
	if($privacy_log_type){
		$sql_search .= " and $privacy_log_type like '%$keyword%' ";
	} else{
		$sql_search .= " and (";
		$j = 0;
		foreach ($admin[privacy_log_type] as $key => $value){
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
<?=admin_help("개인정보의 안전성 확보에 필요한 조치에 관한 사항
개인정보의 기술적 관리적 보호조치 기준에 따라 정보통신서비스 제공자 등은 개인정보취급자가 개인정보처리시스템에 접속한 기록을
월 1회 이상 정기적으로 확인·감독하여야 하며,시스템 이상 유무의 확인 등을 위해 최소 6개월 이상 접속기록을 보존·관리하여야 합니다.
  ","line50 notice_gray")?>
<form name="fsearch" id="fsearch" method="get">
<table class="table cols_tbl">
<tr>
	<th>검색어</th>
	<td>
	<div class="form-inline">
	<?=admin_select($_GET,"privacy_log_type","","","0")?>
	<?=admin_text($_GET,"keyword","","maxlength=\"30\"")?>
	</div>
	</td>
</tr>
<tr>
	<th>기간검색</th>
	<td>
	<div class="form-inline">
		<?php
		$admin[period_type] = array("log_insert_datetime" => "접속일자");		
		?>
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
		전체 <span class="txt_red"><?=number_format($total_count)?></span>건 / 검색 <span class="txt_red"><?=number_format($search_count)?></span>건
		</div>
		<div class="flor">
		<?php
		$sort_array = array("log_insert_datetime" => "접속일자", "log_mb_id" => "접속아이디");		
		echo admin_sort($sort_array,$sort);
		?>
		<?=admin_page_row($page_row)?>
		</div>
</div>
</form>

<form name="flist" id="flist" method="post">
<?=admin_get()?>
<table  class="table row_tbl margin0">
<tr>
	<th><?=admin_checkbox($row,"chkall")?></th>
	<th>접속일시</th>
	<th>접속아이디</th>
	<th>접속아이피</th>
	<th>메뉴구분</th>
	<th>접속페이지(개인정보관련)</th>
	<th>수행업무</th>
</tr>
<?php
for($i=0; $row=sql_fetch_array($result); $i++){
	$row["chk[]"] = $i;
	$row["{$id}[{$i}]"] = $row[$id];

	$edit = "{$form}?{$qstr}&{$id}={$row[$id]}";
	$delete = "javascript:del('{$list}?{$qstr}&{$id}={$row[$id]}&mode=delete')";
?>
<tr>
	<td><?=admin_checkbox($row,"chk[]","chk")?><?=admin_hidden($row,"{$id}[{$i}]")?></td>
	<td><?=substr($row[log_insert_datetime],0,10)?></td>
	<td><?=$row[log_mb_id]?></td>
	<td><?=$row[log_ip]?></td>
	<td><?=$row[log_menu]?></td>
	<td><?=$row[log_url]?></td>
	<td><?=$row[log_type]?></td>
</tr>
<?php
}
$pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");
?>
</table>

<div class="table_btn"><?=$pagelist?></div>

</form>

<?php
include_once "tail.php";
?>