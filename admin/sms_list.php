<?php
include_once "path.php";

$admin['keyword_type'] = array(""=>"전체","sm_subject" => "제목", "sm_memo"=>"내용", "sm_hp"=>"발송번호");
$admin['sm_target'] = array(""=>"선택","전체회원" => "전체회원","입점회원" => "입점회원", "레벨회원" => "레벨회원", "성별회원"=>"성별회원", "연령별회원"=>"연령별회원", "지역별회원"=>"지역별회원",  "휴면회원"=>"휴면회원", "캠페인참여회원"=>"캠페인참여회원");
$admin['sm_sex'] = array(""=>"선택","M" => "남성", "F"=>"여성");
$admin['sm_age'] = array(""=>"선택","10대" => "10대", "20대"=>"20대", "30대"=>"30대", "40대"=>"40대", "50대"=>"50대", "60대"=>"60대", "70대"=>"70대", "80대"=>"80대");
$admin['sm_area'] = array(""=>"선택","서울" => "서울", "경기"=>"경기", "인천"=>"인천");
$admin['sm_agree'] = array("전체","수신동의","수신거부");
$admin['sm_review'] = array(""=>"선택","1" => "신청목록", "2"=>"선정목록", "3"=>"검수요청목록", "7"=>"수정요청목록", "4"=>"등록완료목록", "6"=>"선정후 취소목록", "5"=>"미선정목록");
$admin['period_type'] = array("sm_insert_datetime" => "등록일자", "sm_update_datetime" => "수정일자", "sm_send_datetime" => "발송일자");		
if($_GET['sm_target']=="성별회원"){
	$admin['sm_target_detail'] = $admin['sm_sex'];
} elseif($_GET['sm_target']=="연령별회원"){
	$admin['sm_target_detail'] = $admin['sm_age'];
} elseif($_GET['sm_target']=="지역별회원"){
	$admin['sm_target_detail'] = $admin['sm_area'];
} elseif($_GET['sm_target']=="레벨회원"){
	$que = sql_query("select * from nfor_level where 1 order by lv_rank asc");
	while($row = sql_fetch_array($que)){
		$admin['sm_level'][$row['lv_id']] = $row['lv_name'];
	}	
	$admin['sm_target_detail'] = $admin['sm_level'];
} else{

}

$qstr .= "&sm_target=$sm_target&sm_target_detail=$sm_target_detail&sm_agree=$sm_agree";

$list = $_SERVER['PHP_SELF'];
$form = str_replace("list","form",$list);
$table = "nfor_sms";
$id = "sm_id";

if($mode=="send"){
	demo_check_json();

	$write = sql_fetch("select * from $table where $id='{$$id}'");

	sql_query("update $table set sm_send_datetime=NOW() where $id='{$$id}'");

	if($write['sm_target']=="전체회원"){
		$send_sql = "select mb_hp from nfor_member where mb_leave_datetime='' and mb_sms='{$write['sm_agree']}' ";
	} elseif($write['sm_target']=="휴면회원"){
		$ymd = date("Y-m-d",strtotime("-1 year"));
		$send_sql = "select mb_hp from nfor_member where DATE_FORMAT(mb_login_datetime,'%Y-%m-%d') < '$ymd' ";
	} elseif($write['sm_target']=="입점회원"){
		$send_sql = "select mb_hp from nfor_member where mb_leave_datetime='' and mb_admin='1' and mb_sms='{$write['sm_agree']}' ";
	} elseif($write['sm_target']=="레벨회원"){
		$send_sql = "select mb_hp from nfor_member where mb_leave_datetime='' and mb_level='{$write['sm_target_detail']}' and mb_sms='{$write['sm_agree']}' ";
	} elseif($write['sm_target']=="성별회원"){
		$send_sql = "select mb_hp from nfor_member where mb_leave_datetime='' and mb_sex='{$write['sm_target_detail']}' and mb_sms='{$write['sm_agree']}' ";
	} elseif($write['sm_target']=="연령별회원"){
		$write['sm_target_detail'] = str_number($write['sm_target_detail']);
		$sdate = (date("Y")-($write['sm_target']+9))."-12-31";
		$edate = (date("Y")-$write['sm_target'])."-01-01";
		$sql = "select mb_hp from nfor_member where mb_leave_datetime='' and date_format(mb_birthday,'%Y-%m-%d') > '$sdate' and date_format(mb_birthday,'%Y-%m-%d') < '$edate' and mb_sms='{$write['sm_agree']}' ";
	} elseif($write['sm_target']=="지역별회원"){
		$send_sql = "select mb_hp from nfor_member where mb_leave_datetime='' and mb_addr1 like '{$write['sm_target_detail']}%' and mb_sms='{$write['sm_agree']}' ";
	} elseif($write['sm_target']=="구독자"){
		$send_sql = "select ss_hp as mb_hp from nfor_subscribe";
	} elseif($write['sm_target']=="캠페인참여회원"){
		$send_sql = "select rv_mb_hp as mb_hp from nfor_review where rv_step='{$write['sm_target_detail']}' and rv_cp_id='{$write['sm_cp_id']}' and rv_delete='0'";
	} else{

	}

	$sl_subject = addslashes($write['sm_subject']);
	$sd_msg = addslashes($write['sm_memo']);

	if(!$write['sm_hp']) $write['sm_hp'] = $config['cf_tel'];
	$que = sql_query($send_sql);
	while($sms = sql_fetch_array($que)){
		sql_query("insert nfor_sms_log set sl_msg='$sd_msg', sl_hp='{$sms['mb_hp']}', sl_send_hp='{$write['sm_hp']}', sl_datetime=NOW(), sl_send='0', sl_templt_code='{$write['sm_templt_code']}', sl_subject='$sl_subject'");
	}

	json_return("발송 요청하였습니다","ok");
}

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

if($sm_agree) $sql_search .= " and sm_agree='$sm_agree' ";

if($sm_target) $sql_search .= " and sm_target='$sm_target' ";

if($sm_target_detail) $sql_search .= " and sm_target_detail='$sm_target_detail' ";

if($keyword){
	if($keyword_type){
		$sql_search .= " and $keyword_type like '%$keyword%' ";
	} else{

		$sql_search .= " and (";
		$j = 0;
		foreach ($admin['keyword_type'] as $key => $value){
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
$total_count = $row['cnt'];

if($all_count_sql==$search_count_sql){
	$search_count = $total_count;
} else{
	$row = sql_fetch($search_count_sql);
	$search_count = $row['cnt'];
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
</colgroup>
<tr>
	<th>발송대상</th>
	<td>
		<div class="form-inline">
		<?=admin_select($_GET,"sm_target","width-150p","","0")?>
		<?=admin_select($_GET,"sm_target_detail","width-150p","","0")?>	
		</div>	
	</td>
	<th>수신동의</th>
	<td><?=admin_radio($_GET,"sm_agree","","","0")?></td>
</tr>

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
</table>

<div class="table_btn"><div class="form-inline"><?=admin_submit("fsubmit", "검색하기", "btn-lg btn-black btn")?></div></div>

<div class="ofw martop20">
	<div class="flol">
	전체 <span class="txt_red"> <?=number_format($total_count)?></span>건 / 검색 <span class="txt_red"> <?=number_format($search_count)?></span>건
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
	<col class="width-50p">
	<col class="width-150p">
	<col>
	<col class="width-100p">
	<col class="width-100p">	
	<col class="width-100p">
	<col class="width-100p">
	<col class="width-80p">
	<col class="width-80p">
	<col class="width-80p">
</colgroup>
<tr>
	<th><?=admin_checkbox($row,"chkall")?></th>
	<th>발송대상</th>
	<th>제목</th>
	<th>수신동의</th>
	<th>발송번호</th>
	<th>등록일자</th>
	<th>발송일자</th>
	<th>발송</th>
	<th>수정</th>
	<th>삭제</th>
</tr>
<?php
for($i=0; $row=sql_fetch_array($result); $i++){	
	$row["chk[]"] = $i;
	$row["{$id}[{$i}]"] = $row[$id];

	$sm_target_detail = "";
	if($row['sm_target_detail']){
		if($row['sm_target']=="캠페인참여회원"){
			$sm_target_detail .= $admin['sm_review'][$row['sm_target_detail']];
			$sm_target_detail .= "<br>";
			$sm_target_detail .= $row['sm_cp_id'];
		} else{
			$sm_target_detail .= $row['sm_target_detail'];
		}		
	}
?>
<tr>
	<td><?=admin_checkbox($row,"chk[]","chk")?><?=admin_hidden($row,"{$id}[{$i}]")?></td>
	<td><?=$row['sm_target']?><br><?=$sm_target_detail?></td>
	<td><?=$row['sm_subject']?></td>
	<td><?=admin_echo($row,"sm_agree")?></td>
	<td><?=$row['sm_hp']?></td>
	<td><?=substr($row['sm_insert_datetime'],0,10)?></td>
	<td><?=$row['sm_send_datetime']?substr($row['sm_send_datetime'],0,10):"발송대기"?></td>
	<td><?=admin_a("send", "발송", "btn btn-white btn-sm nfor_button", "data-confirm=\"발송하시겠습니까?\" data-data=\"{$id}={$row[$id]}&mode=send\"")?></td>
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
	<?=admin_button("list_delete", "선택삭제", "btn btn-lg btn-red")?>
	<?=admin_a("form", "등록하기", "btn-lg btn-black btn", "", $form)?>
	</div>

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

$(document).on("change","#sm_target",function(){

	if($(this).val()=="성별회원" || $(this).val()=="연령별회원" || $(this).val()=="지역별회원" || $(this).val()=="레벨회원"){

		$.ajax({
			type     : "get",
			url      : "sms_form.php",
			data     : "mode=target_change&sm_target="+$(this).val(),
			dataType : 'json',
			cache: false,
			success  : function(data) {
				var output = '';
				for(var i=0; i<data.data.length; i++) {
					output += '<option value="' + data.data[i].code + '">' + data.data[i].val + '</option>';
				}
				$('#sm_target_detail').empty().append(output).show();
			},
			error: function(){
				console.log("Ajax failed");
			}
		});

	} else{
		$('#sm_target_detail').empty().hide();
	}

});
//-->
</script>

<?php
if(!$sm_target or $sm_target=="전체회원" or $sm_target=="구독자" or $sm_target=="휴면회원" or $sm_target=="입점회원"){
?>
<style>
#sm_target_detail { display:none; }
</style>
<?php } ?>

<?php
include_once "tail.php";
?>