<?php // 상품코드검색
include_once "path.php";
include_once "$nfor[path]/html_head.php";

$qstr .= "&key=$key";

$sql_common = " from nfor_member ";
$sql_search = " where mb_leave_datetime='' ";
if($stx) $sql_search .= " and $sfl like '%$stx%' ";
if(!$sst){
	$sst = "mb_no";
	$sod = "desc";
}
$sql_order = " order by $sst $sod ";
$sql = " select count(*) as cnt
						 $sql_common
						 $sql_search
						 $sql_order ";
$row = sql_fetch($sql);
$total_count = $row[cnt];
$rows = 10;
$total_page  = ceil($total_count / $rows);
if(!$page) $page = 1;
$from_record = ($page - 1) * $rows;
$sql = " select *
				  $sql_common
				  $sql_search
				  $sql_order
				  limit $from_record, $rows ";
$result = sql_query($sql);
?>
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="<?=$nfor[path]?>/js/html5shiv.min.js"></script>
  <script src="<?=$nfor[path]?>/js/respond.min.js"></script>
<![endif]-->
<!-- 부트스트랩 -->
<link rel="stylesheet" href="<?=$nfor[path]?>/css/bootstrap.min.css">
<link rel="stylesheet" href="<?=$nfor[path]?>/css/bootstrap-theme.min.css">
<script src="<?=$nfor[path]?>/js/bootstrap.min.js"></script>
<!-- //부트스트랩 -->
<!-- 부트스트랩 플러그인 -->
<link rel="stylesheet" href="<?=$nfor[path]?>/css/bootstrap-dialog.min.css" type="text/css" />
<script src="<?=$nfor[path]?>/js/bootstrap-dialog.min.js"></script>
<!-- //부트스트랩 플러그인 -->
<link rel="stylesheet" href="/admin/nfor.css?time=<?=time()?>" type="text/css" />

<style>
body { background-color:#fff; padding:10px; }
.page_list { text-align:center; }

form { margin-bottom:10px; }
</style>

<form method="get" action="<?=$PHP_SELF?>">
<input type="hidden" name="key" value="<?=$key?>">
<select name="sfl" style="border:solid 1px #DCDCDC; height:25px;">
	<option value="mb_name" <?=$sfl=="mb_name"?"selected":""?>>이름
	<option value="mb_id" <?=$sfl=="mb_id"?"selected":""?>>아이디
	<option value="mb_email" <?=$sfl=="mb_email"?"selected":""?>>이메일
</select>
<input type="text" name="stx" value="<?=$stx?>" style="border:solid 1px #DCDCDC; height:25px;">
<input type="submit" value="검색" class="btn btn-black btn-sm" style="vertical-align:2px;">
</form>

<table border="0" class="table row_tbl margin10">
<tr>
	<th>회원번호</th>
	<th>이름</th>
	<th>아이디</th>
	<th>이메일</th>
	<th>선택</th>
</tr>
<?	for($i=0; $row=sql_fetch_array($result); $i++){	?>
<tr>
	<td><?=$row[mb_no]?></td>
	<td><?=$row[mb_name]?></td>
	<td><?=$row[mb_id]?></td>
	<td><?=$row[mb_email]?></td>
	<td><input type="button" value="선택" onclick="insert_val_mb_id('<?=$key?>','<?=$row[mb_no]?>','cp_mb_id', '<?=$row[mb_id]?>')"></td>
</tr>
<?
}
$pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");
?>
</table>
<div class="page_list"><?=$pagelist?></div>


<script type="text/javascript">
<!--
function insert_val_mb_id(key,val,key2,val2){
	$("#"+key, opener.document).val(val);
	$("#"+key2, opener.document).val(val2);
    window.close();
}
//-->
</script>

<?
include_once "$nfor[path]/html_tail.php";
?>