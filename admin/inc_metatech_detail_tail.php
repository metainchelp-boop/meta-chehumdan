<?php

$sql_common = " from $table ";
$sql_search = " where connect_idx = '$target'";

$all_count_sql = "select count(*) as cnt $sql_common $sql_search";

$sql_order = " order by datetime desc";
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

$cur_no = $search_count - $page_row*($page-1); 

$sql = " select *
				  $sql_common
				  $sql_search
				  $sql_order";

$sql .= " limit $from_record, $page_row ";
$result = sql_query($sql);

if($target){
	$main_info = select_table_nfor_metatech($target);
}
?>