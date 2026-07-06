<?php
include_once "path.php";

$admin[keyword_type] = array(""=>"전체", "qa_cp_subject" => "캠페인명", "qa_cp_id" => "캠페인코드", "qa_memo"=>"문의내용", "qa_mb_id"=>"아이디", "qa_mb_nick"=>"닉네임", "qa_mb_name"=>"이름", "qa_mb_hp"=>"휴대폰", "qa_mb_email"=>"이메일");
$admin[qa_reply_state] = array("전체", "답변대기", "답변완료");
$admin[qa_cp_id][""] = "전체";
$que = sql_query("select * from nfor_campaign where 1 order by cp_id desc");
while($row = sql_fetch_array($que)){
	$admin[qa_cp_id][$row[cp_id]] = $row[cp_subject];
}
$admin[qa_supply_no][""] = "전체";
$que = sql_query("select mb_no, mb_name, mb_cp_name, mb_tel from nfor_member where mb_leave_datetime='' and mb_cp_name <> '' and mb_admin='1' order by ( case when mb_cp_name between '가' and  '金' then 1 when mb_cp_name between 'a' and 'z'  THEN 3 when mb_cp_name between 'A' and 'Z' then 4  when mb_cp_name between '0' and '9' then 5  when mb_cp_name between '!' and '.' then 6 else 0  end ), mb_cp_name asc");
while($row = sql_fetch_array($que)){
	$admin[qa_supply_no][$row[mb_no]] = $row[mb_cp_name];
}
$admin[period_type] = array("qa_insert_datetime" => "등록일자", "qa_update_datetime" => "수정일자", "qa_reply_datetime" => "답변일자");		

$qstr .= "&qa_reply_state=$qa_reply_state&qa_supply_no=$qa_supply_no";

$list = $_SERVER[PHP_SELF];
$form = str_replace("list","form",$list);
$table = "nfor_campaign_qna";
$id = "qa_id";

if($mode=="list_delete"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		$write = sql_fetch("select * from $table where $id='{$_POST[$id][$k]}'");
		sql_query("delete from $table where $id='{$_POST[$id][$k]}'");
		sql_query("delete from $table where qa_parent='{$_POST[$id][$k]}'");
		cp_qna_cnt_update($write[qa_cp_id_gp]);
	}
	json_return("정상적으로 삭제되었습니다","ok");
}

if($mode=="delete"){
	demo_check_json();
	$write = sql_fetch("select * from $table where $id='{$$id}'");
	sql_query("delete from $table where $id='{$$id}'");
	sql_query("delete from $table where qa_parent='{$$id}'");
	cp_qna_cnt_update($write[qa_cp_id_gp]);

	json_return("정상적으로 삭제되었습니다","ok");
}






$sql_common = " from $table ";
$sql_search = " where qa_parent='0' ";


if($member[mb_admin]=="1"){
	$sql_search .= " and qa_supply_no='$member[mb_no]'";	
} else if($member[mb_admin]=="2"){
	$sql_search .= " and qa_md_no='$member[mb_no]'";	
} else{

}

$all_count_sql = "select count(*) as cnt $sql_common $sql_search";

if($qa_supply_no) $sql_search .= " and qa_supply_no = '$qa_supply_no' ";

if($qa_cp_id) $sql_search .= " and qa_cp_id = '$qa_cp_id' ";

if($qa_reply_state=="1"){
	$sql_search .= " and qa_reply = '0' ";
} elseif($qa_reply_state=="2"){
	$sql_search .= " and qa_reply > '0' ";
} else{

}

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
<form name="fsearch" id="fsearch" method="get">
<table class="table cols_tbl">
<colgroup>
	<col class="width-150p">
	<col >
	<col class="width-150p">
	<col >
</colgroup>
<? if($is_admin){ ?>
<tr>
	<th>입점업체</th> 
	<td><?=admin_select($_GET,"qa_supply_no","width-200p","","0")?></td>
	<th>캠페인선택</th> 
	<td><?=admin_select($_GET,"qa_cp_id","width-150p","","0")?></td>
</tr>
<? } ?>
<tr>
	<th>검색어</th>
	<td>
		<div class="form-inline">
		<?=admin_select($_GET,"keyword_type","","","0")?>
		<?=admin_text($_GET,"keyword","","maxlength=\"30\"")?>
		</div>	
	</td>
	<th>답변상태</th> 
  	<td><?=admin_radio($_GET,"qa_reply_state","","","0")?></td>
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
	전체 <span class="txt_red"> <?=number_format($total_count)?></span>건 / 검색  <span class="txt_red"><?=number_format($search_count)?></span>건
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
	<col class="width-50p">
	<col class="width-200p">
	<col class="width-200p">
	<col >
	<col class="width-150p">
	<col class="width-100p">
	<col class="width-100p">
	<col class="width-80p">
	<col class="width-80p">
</colgroup>
<tr>
	<th><?=admin_checkbox($row,"chkall")?></th>
	<th>캠페인명</th>
	<th>입점업체</th>
	<th>문의내용</th>
	<th>회원정보</th>
	<th>등록일자</th>
	<th>답변상태/일자</th>
	<th>답변</th>
	<? if($is_admin){ ?><th>삭제</th><? } ?>
</tr>
<?php

for($i=0; $row=sql_fetch_array($result); $i++){	
	$row = nfor_tag_out($row);


	$row["chk[]"] = $i;
	$row["{$id}[{$i}]"] = $row[$id];
?>
<tr>
	<td><?=admin_checkbox($row,"chk[]","chk")?><?=admin_hidden($row,"{$id}[{$i}]")?></td>
	<td><a href="<?="{$nfor[path]}/campaign.php?cp_id={$row[qa_cp_id]}"?>" target="_blank"><?=$row[qa_cp_subject]?></a></td>
	<td>
	<?=$row[qa_supply_no]?admin_echo($row,"qa_supply_no"):""?>
	<?=member_info($row[qa_supply_no], array("mb_tel"))?>
	</td>
	<td class="textleft"><?=nl2br($row[qa_memo])?></td>
	<td><?=qna_mb_echo($row)?></td>
	<td><?=substr($row[qa_insert_datetime],0,10)?></td>
	<td><?
	if($row[qa_reply]){
		echo "답변완료";
		echo "<br>".substr($row[qa_reply_datetime],0,10);
	} else{
		echo "답변대기";		
	}
	?></td>
	<td><?=admin_a("edit", "답변", "btn btn-white btn-sm", "", "{$form}?{$qstr}&{$id}={$row[$id]}")?></td>
	<? if($is_admin){ ?><td><?=admin_a("delete", "삭제", "btn btn-white btn-sm nfor_button", "data-confirm=\"삭제하시겠습니까?\" data-data=\"mode=delete&{$id}={$row[$id]}\"")?></td><? } ?>
</tr>
<?php
}
$pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");
?>
</table>

<div class="bottom_btn">
	<? if($is_admin){ ?>
	<div class="form-inline">
	<?=admin_button("list_delete", "선택삭제", "btn btn-lg btn-red")?>
	</div>
	<? } ?>
</div>

<div class="table_btn"><?=$pagelist?></div>

</form>

<script type="text/javascript">
<!--
$(document).on("click", "#chkall", function(){
	nfor_chk_all(this, 'chk');
});

$(document).on("click", "#list_delete", function(){
	nfor_list_reload('삭제','list_delete');
});
//-->
</script>

<?php
include_once "tail.php";
?>