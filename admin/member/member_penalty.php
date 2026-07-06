<?php
include_once "path.php";

$admin[pe_type] = array("전체","1차경고","2차경고","이용정지","이용정지 해제");
$admin[keyword_type] = array(""=>"전체", "pe_mb_id"=>"아이디", "pe_mb_nick"=>"닉네임", "pe_mb_name"=>"이름",  "pe_mb_hp"=>"휴대폰", "pe_mb_email"=>"이메일", "pe_stop_memo"=>"사유");
$admin[period_type] = array("pe_datetime" => "처리일자", "pe_mb_stop_date" => "정지일자");

$qstr .= "&pe_type=$pe_type";


$list = $_SERVER[PHP_SELF];
$form = str_replace("list","form",$list);
$table = "nfor_penalty";
$id = "pe_id";


if($mode=="list_delete"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		sql_query("delete from $table where $id='{$_POST[$id][$k]}'");
	}
	json_return("정상적으로 삭제되었습니다","ok");
}


$sql_common = " from $table ";
$sql_search = " where pe_mb_no='$mb_no' ";

$all_count_sql = "select count(*) as cnt $sql_common $sql_search";

if($pe_type) $sql_search .= " and pe_type = '$pe_type'";

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

$sql_order = " order by $sort ";
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

$nfor[title] = "패널티 내역";

include_once "head.php";
?>

<style>
.member_penalty_form_btn_wrap { text-align:right; }
#member_penalty_form_btn { margin-bottom:10px; } 
</style>

<div class="member_penalty_form_btn_wrap">
<?=admin_a("member_penalty_form_btn", "패널티 등록", "btn btn-sm btn-red","","member_penalty_form.php?mb_no=$mb_no")?>
</div>

<table class="table row_tbl margin0">
<tr>
	<th>사유</th>
	<th>이용정지기간</th>
	<th>처리일시</th>
</tr>
<?php
for($i=0; $row=sql_fetch_array($result); $i++){	
?>
<tr>
	<td><?=$row[pe_stop_memo]?></td>
	<td>
		<?=$admin[pe_type][$row[pe_type]]?>
		<? if($row[pe_type]=="3" or $row[pe_type]=="4"){ ?>
		<?=$row[pe_stop_day]?>일 (<?=date("Y년 m월 d일",strtotime($row[pe_mb_stop_date]))?>까지)
		<? } ?>
	</td>
	<td><?=$row[pe_datetime]?></td>
</tr>
<?php
}
$pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");
?>
</table>

<div class="table_btn"><?=$pagelist?></div>

<?php
include_once "tail.php";
?>