<?php
include_once "path.php";

if(!$_GET[sort_type]){
	$_GET[sort_type] = "hour";
	$sort_type = "hour";
}

$admin[sort_type] = array(""=>"전체","hour" => "시간별", "day"=>"일별", "week"=>"요일별", "month"=>"월별");


if($rv_cp_id) $campaign = sql_fetch("select * from nfor_campaign where cp_id='$rv_cp_id'");


if(!$rv_supply_no){
	$rv_supply_no = $campaign[cp_supply_no];
}


$admin[rv_supply_no][""] = "전체";
$que = sql_query("select mb_no, mb_name, mb_cp_name, mb_tel from nfor_member where mb_leave_datetime='' and mb_cp_name<>'' and mb_admin='1' order by ( case when mb_cp_name between '가' and  '金' then 1 when mb_cp_name between 'a' and 'z'  THEN 3 when mb_cp_name between 'A' and 'Z' then 4  when mb_cp_name between '0' and '9' then 5  when mb_cp_name between '!' and '.' then 6 else 0  end ), mb_cp_name asc");
while($row = sql_fetch_array($que)){
	$admin[rv_supply_no][$row[mb_no]] = $row[mb_cp_name];
}

$admin[rv_cp_id][""] = "전체";
if($rv_supply_no or $member[mb_admin]=="1"){
	if(!$rv_supply_no) $rv_supply_no = $member[mb_no];
	$que = sql_query("select * from nfor_campaign where cp_supply_no='$rv_supply_no' order by ( case when cp_subject between '가' and  '金' then 1 when cp_subject between 'a' and 'z'  THEN 3 when cp_subject between 'A' and 'Z' then 4  when cp_subject between '0' and '9' then 5  when cp_subject between '!' and '.' then 6 else 0  end ), cp_subject asc");
} elseif($rv_md_no or $member[mb_admin]=="2"){
	if(!$rv_md_no) $rv_md_no = $member[mb_no];
	$que = sql_query("select * from nfor_campaign where cp_md_no='$rv_md_no' order by ( case when cp_subject between '가' and  '金' then 1 when cp_subject between 'a' and 'z'  THEN 3 when cp_subject between 'A' and 'Z' then 4  when cp_subject between '0' and '9' then 5  when cp_subject between '!' and '.' then 6 else 0  end ), cp_subject asc");
} else{
	$que = sql_query("select * from nfor_campaign where (1) order by ( case when cp_subject between '가' and  '金' then 1 when cp_subject between 'a' and 'z'  THEN 3 when cp_subject between 'A' and 'Z' then 4  when cp_subject between '0' and '9' then 5  when cp_subject between '!' and '.' then 6 else 0  end ), cp_subject asc");
}
while($row = sql_fetch_array($que)){
	$admin[rv_cp_id][$row[cp_id]] = $row[cp_subject];
}


$admin[rv_mb_no][""] = "전체";
if($rv_cp_id){
	$que = sql_query("select * from nfor_review where rv_cp_id='$rv_cp_id' order by rv_id desc");
	while($row = sql_fetch_array($que)){
		$admin[rv_mb_no][$row[rv_mb_no]] = addslashes($row['rv_mb_nick'])."(".$row['rv_mb_id'].")";
	}
}



if($window){
	include_once "html_head.php";
} else{
	include_once "head.php";
}

$y_w = date("Y_W",strtotime($campaign[cp_insert_datetime]));
$table = "nfor_traffic_".$y_w;
//echo "<!-- cp_insert_datetime : ". $campaign[cp_insert_datetime] ." -->";
//echo "<!-- strtotime : ". strtotime($campaign[cp_insert_datetime]) ." -->";
//echo "<!-- table : ". $table ." -->";
//
//
//include_once "$nfor[path]/lib/function.lib.php";
//
//$exp_test = explode("||",nfor_decrypt("YVczSzJtbEVtVFlwbHZzRWdMemZYQzh5WDhkRXVhV2pOcFcxTWFuODVERT0"));
//
//$table_test = "nfor_traffic_".$exp_test[2];
//
//echo "<!-- exp_test : ". print_r($exp_test) ." -->";
//echo "<!-- table_test : ". $table_test ." -->";
?>



<form name="fsearch" id="fsearch" method="get" onsubmit="return fsubmit_search(this)" autocomplete="off">
<?=admin_hidden($_GET,"window")?>

<table class="table cols_tbl">
<?php if($is_admin){ ?>
<tr>
	<th>입점업체</th>
	<td><?=admin_select($_GET,"rv_supply_no","width-200p","","0")?></td>
</tr>
<?php } ?>
<tr>
	<th>캠페인</th>
	<td><?=admin_select($_GET,"rv_cp_id","","","0")?></td>
</tr>
<tr>
	<th>리뷰어</th>
	<td><?=admin_select($_GET,"rv_mb_no","","","0")?></td>
</tr>
<tr>
	<th>보기</th>
	<td>
		<?=admin_radio($_GET,"sort_type","","")?>
	</td>
</tr>
<tr>
	<th>기간검색</th>
	<td>
	<div class="form-inline">
		<?=admin_text($_GET,"period_sdate","width-100p datepicker-here","data-language=\"ko\"")?> ~ <?=admin_text($_GET,"period_edate","width-100p datepicker-here","data-language=\"ko\"")?>
		<?=period_day_echo($_GET[period_day])?>
	</div>
	</td>
</tr>
</table>

<div class="table_btn"><div class="form-inline"><?=admin_submit("fsubmit", "검색하기", "btn-lg btn-black btn")?></div></div>

</form>


<script>

function fsubmit_search(f){
	if(!$('#rv_cp_id').val()){
		alert("캠페인을 선택해주세요");
		$('#rv_cp_id').focus();
        return false;
	}
	return true;
}


$(document).on("change", "#rv_supply_no", function(){
	var cp_supply_no = $(this).val();

	$.ajax({
		type     : "get",
		url      : nfor_path + "/json.php",
		data     : "mode=campaign_list&cp_supply_no="+cp_supply_no,
		dataType : 'json',
		cache: false,
		success  : function(data) {
			var output = '<option value="">선택</option>';
			if(data.cnt>0){
				for(var i=0; i<data.data.length; i++) {
					output += '<option value="' + data.data[i].cp_id + '">' + data.data[i].cp_subject + '</option>';
				}
			}
			$('#rv_cp_id').empty().append(output);

			var output = '<option value="">선택</option>';
			$('#rv_mb_no').empty().append(output);
		},
		error: function(){
			console.log("Ajax failed");
		}
	});

});

$(document).on("change", "#rv_cp_id", function(){
	var rv_cp_id = $(this).val();

	$.ajax({
		type     : "get",
		url      : nfor_path + "/json.php",
		data     : "mode=review_list&rv_cp_id="+rv_cp_id,
		dataType : 'json',
		cache: false,
		success  : function(data) {
			var output = '<option value="">선택</option>';
			if(data.cnt>0){
				for(var i=0; i<data.data.length; i++) {
					output += '<option value="' + data.data[i].rv_mb_no + '">' + data.data[i].rv_info + '</option>';
				}
			}
			$('#rv_mb_no').empty().append(output);
		},
		error: function(){
			console.log("Ajax failed");
		}
	});

});
</script>























<?php if($rv_cp_id){ ?>

<?
$where_sql = " where tr_cp_id='$rv_cp_id'";
if($rv_mb_no) $where_sql .= " and tr_mb_no='$rv_mb_no'";
if($period_sdate and $period_edate) $where_sql .= " and tr_datetime between '$sdate' and '$edate'";


$weekday = array ('월', '화', '수', '목', '금', '토', '일');
$max = 0;
$sum_count = 0;
?>


<table class="table row_tbl">
<colgroup>
	<col class="width-150p">
	<col class="width-150p">
	<col class="width-150p">
	<col style="width:850px; ">
</colgroup>
<tr>
    <th><?=$admin[sort_type][$_GET[sort_type]]?></td>
    <th>방문자수</th>
    <th>비율(%)</th>
    <th>그래프</th>
</tr>



<?php
if($sort_type=="hour"){
	$sql = " select SUBSTRING(tr_datetime,12,2) as print_title, count(tr_ip) as cnt from $table $where_sql group by print_title order by print_title ";
	$result = sql_query($sql);
	for ($i=0; $row=sql_fetch_array($result); $i++) {
		$arr[$row[print_title]] = $row[cnt];
		if ($row[cnt] > $max) $max = $row[cnt];
		$sum_count += $row[cnt];
	}

	for($i=0; $i<24; $i++){
		$hour = sprintf("%02d", $i);
		$count = (int)$arr[$hour];
		$bar_width = $count>0?($count / $max * 100):"0";
?>
<tr>
	<td><?=$hour?>시</td>
	<td><?=number_format($count)?>명</td>
	<td><?=$count==0?"0":number_format(@($count / $sum_count * 100), 1)?>%</td>
	<td align="left" style="padding-right:5px; text-align:left;"><img src='img/graph.gif' width='<?=$bar_width?>%' height='18'></td>
</tr>
<?php
	}
}
?>



<?php
if($sort_type=="week"){
	$sql = " select WEEKDAY(tr_date) as print_title, count(tr_ip) as cnt from $table $where_sql group by print_title order by print_title ";
	$result = sql_query($sql);
	for ($i=0; $row=sql_fetch_array($result); $i++) {
		$arr[$row[print_title]] = $row[cnt];
		if ($row[cnt] > $max) $max = $row[cnt];
		$sum_count += $row[cnt];
	}

	for($i=0; $i<7; $i++){
		$count = (int) $arr[$i];
		$bar_width = $count>0?($count / $max * 100):"0";
?>
<tr>
	<td><?=$weekday[$i]?></td>
	<td><?=number_format($count)?>명</td>
	<td><?=$count==0?"0":number_format(@($count / $sum_count * 100), 1)?>%</td>
	<td align="left" style="padding-right:5px; text-align:left;"><img src='img/graph.gif' width='<?=$bar_width?>%' height='18'></td>
</tr>
<?php
	}
}
?>




<?php
if($sort_type=="day"){
	$sql = " select tr_date as print_title, count(tr_ip) as cnt from $table $where_sql group by print_title order by print_title ";
	$result = sql_query($sql);
	for ($i=0; $row=sql_fetch_array($result); $i++) {
		$arr[$row[print_title]] = $row[cnt];
		if ($row[cnt] > $max) $max = $row[cnt];
		$sum_count += $row[cnt];
	}

	if(count($arr)){
		foreach ($arr as $key=>$value) {
			$count = $value;
			$bar_width = $count>0?($count / $max * 100):"0";
?>
<tr>
	<td><?=$key?></td>
	<td><?=number_format($count)?>명</td>
	<td><?=$count==0?"0":number_format(@($count / $sum_count * 100), 1)?>%</td>
	<td align="left" style="padding-right:5px; text-align:left;"><img src='img/graph.gif' width='<?=$bar_width?>%' height='18'></td>
</tr>
<?php
		}
	}
}
?>




<?php
if($sort_type=="month"){
	$sql = " select SUBSTRING(tr_date,1,7) as print_title, count(tr_ip) as cnt from $table $where_sql group by print_title order by print_title ";
	$result = sql_query($sql);
	for ($i=0; $row=sql_fetch_array($result); $i++) {
		$arr[$row[print_title]] = $row[cnt];
		if ($row[cnt] > $max) $max = $row[cnt];
		$sum_count += $row[cnt];
	}

	if(count($arr)){
		foreach ($arr as $key=>$value) {
			$count = $value;
			$bar_width = $count>0?($count / $max * 100):"0";
?>
<tr>
	<td><?=$key?></td>
	<td><?=number_format($count)?>명</td>
	<td><?=$count==0?"0":number_format(@($count / $sum_count * 100), 1)?>%</td>
	<td align="left" style="padding-right:5px; text-align:left;"><img src='img/graph.gif' width='<?=$bar_width?>%' height='18'></td>
</tr>
<?php
		}
	}
}
?>


</table>



<?php } ?>





<?php
if($window){
	include_once "html_tail.php";
} else{
	include_once "tail.php";
}
?>