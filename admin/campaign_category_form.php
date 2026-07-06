<?php
include_once "path.php";

$admin[cg_use] = array("전체","노출","미노출");
$admin[cg_level] = array("전체","전체(회원+비회원)","회원전용","특정회원");
$admin[cg_display] = array("전체","자동","수동");
$admin[cg_hit_use] = array("전체","노출","미노출");
$admin[cg_hit_rank] = array("전체","자동","수동");
$admin[cg_map] = array("전체","노출","미노출");



$table = "nfor_campaign_category";
$form = "campaign_category.php";

if($category_id or $cg_id){
	$write = sql_fetch("select * from $table where cg_id='$cg_id' or category_id='$category_id'");
	$category_link = "$nfor[url]/campaign_list.php?category_id=$write[category_id]";
}

if($mode=="insert" or $mode=="update"){
	demo_check();

	if(!$cg_category) alert("분류명을 입력하셔야 등록가능합니다");

	$add_sql = "";
	$where_sql = "";


	$cg_level_list = "";
	if($cg_level=="3"){
		for($i=0; $i<count($cg_level_list_val); $i++){
			if($i) $cg_level_list .= "||";
			$cg_level_list .= $cg_level_list_val[$i];
		}
	}

	if($mode=="insert"){
		$msg = "정상적으로 등록 되었습니다";
		$move = "$form?cg_id=$cg_id";
		$max = sql_fetch("select cg_rank from $table where cg_depth_id='0' order by cg_rank desc"); 
		$cg_rank = $max[cg_rank] + 1;
		$add_sql .= ", cg_insert_datetime=NOW(), cg_rank='$cg_rank'";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$move = "$form?cg_id=$cg_id";
		$where_sql = "  where cg_id='{$cg_id}'";
		$add_sql .= ", cg_update_datetime=NOW()";
	} else{

	}
	$cg_lv_id = json_encode($_REQUEST[lv_id],JSON_UNESCAPED_UNICODE); // php5.3 이상부터 적용

	$common_sql = " $table set cg_level_list='$cg_level_list', cg_level='$cg_level', cg_map='$cg_map', cg_category='$cg_category', cg_use='$cg_use', cg_display='$cg_display', cg_hit_use='$cg_hit_use', cg_category_head='$cg_category_head', cg_category_tail='$cg_category_tail', cg_child_same='$cg_child_same', cg_lv_id='$cg_lv_id', cg_display_1_sort='$cg_display_1_sort', cg_display_2_sort='$cg_display_2_sort', cg_hit_rank='$cg_hit_rank', cg_hit_rank_1_sort='$cg_hit_rank_1_sort', cg_hit_rank_2_sort='$cg_hit_rank_2_sort'";
	$common_sql .= admin_upload($campaign,"cg_img","category",$table,$where_sql);
	$common_sql .= admin_upload($campaign,"cg_img_ov","category",$table,$where_sql);
	$common_sql .= admin_upload($campaign,"cg_img_icon","category",$table,$where_sql);

	sql_query("$mode $common_sql $add_sql $where_sql");

	if($mode=="insert"){
		$cg_id = sql_insert_id();
		$category_id = substr("000".$cg_id,-3);

		// 분류수정
		$category_id .= "A"; 

		sql_query("update $table set category_id='$category_id' where cg_id='$cg_id'");
	}



	if($cg_child_same){
		sql_query("update $table set cg_level_list='$cg_level_list', cg_level='$cg_level', cg_lv_id='$cg_lv_id' where category_id like '$category_id%'");		
	}
	
	alert($msg,$move);
}

?>
<form name="fwrite" method="post" onsubmit="return fsubmit(this)" enctype="multipart/form-data">
<?=admin_hidden($write,"mode")?>
<?=admin_hidden($write,"cg_id")?>
<table class="table cols_tbl ">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>분류명</th> 
	<td colspan="3">

	<div class="form-inline">
		<?=admin_text($write,"cg_category","width-300p","maxlength=\"15\"")?>
		<? if($write[cg_id]){ ?>
		<a href="<?=$category_link?>" target="_blank" class="btn btn-sml btn-gray" role="button">사용자화면보기</a>	
		<? } ?>	
	</div>

	</td>
</tr>
<? if($write[cg_id]){ ?>
<tr>
	<th>분류 코드</th> 
	<td><?=$write[category_id]?></td>
	<th>등록 상품수	</th> 
	<td><?=number_format(category_campaign_count($write[category_id]))?>개 (하위분류포함)</td>
</tr>
<? } ?>
<tr>
	<th>카테고리 미선택 이미지</th> 
	<td><?=admin_file($write,"cg_img","category")?></td>
	<th>카테고리 선택 이미지</th> 
	<td><?=admin_file($write,"cg_img_ov","category")?></td>
</tr>
<tr>
	<th>아이콘이미지</th> 
	<td colspan="3"><?=admin_file($write,"cg_img_icon","category")?></td>
</tr>
<? 
if(!$write[cg_use]) $write[cg_use] = "1";
if(!$write[cg_level]) $write[cg_level] = "1";
if(!$write[cg_display]) $write[cg_display] = "1";
if(!$write[cg_hit_use]) $write[cg_hit_use] = "1";
if(!$write[cg_hit_rank]) $write[cg_hit_rank] = "1";
if(!$write[cg_map]) $write[cg_map] = "2";
?>
<tr>
	<th>노출여부</th> 
	<td colspan="3"><?=admin_radio($write,"cg_use")?></td>
</tr>

<tr>
	<th>지도보기 노출여부</th> 
	<td colspan="3"><?=admin_radio($write,"cg_map")?></td>
</tr>

<tr>
	<th>접근권한</th> 
	<td colspan="3">
	<?=admin_radio($write,"cg_level")?>
	<?=admin_button("cg_level_btn", "레벨선택")?>
	<ul id="layer_level_append">
		<?
		if($write[cg_level_list]){
			$cg_level_list = explode("||",trim($write[cg_level_list]));
			for($i=0; $i<count($cg_level_list); $i++){
				$data = sql_fetch("select lv_name from nfor_level where lv_id='{$cg_level_list[$i]}'");
		?>
		<li id="level_<?=$cg_level_list[$i]?>">
			<span><?=$data[lv_name]?></span>
			<input type="hidden" name="cg_level_list_val[]" value="<?=$cg_level_list[$i]?>">
			<img src="img/color_del.png" alt="삭제" class="li_remove">
		</li>
		<?
			}
		}
		?>
	</ul>
		
	<?php
	$checkbox[cg_child_same] = "1";
	?>
	<?=admin_checkbox($checkbox,"cg_child_same","",$write[cg_child_same]?"checked":"","하위 카테고리 동일 적용")?>

	</td>
</tr>
<tr style="display:none;">
	<th>정렬형태</th> 
	<td colspan="3"><?=admin_radio($write,"cg_display")?>
	
	<div id="cg_display_1" class="form-inline">
	<?
	$sort_array1[it_name] = "상품명";
	$sort_array1[it_price2] = "판매가";
	$sort_array1[it_insert_datetime] = "등록일자";
	$sort_array1[it_update_datetime] = "수정일자";
	echo admin_sort($sort_array1,$write[cg_display_1_sort],"cg_display_1_sort");
	?>
	</div>
	<div id="cg_display_2" class="form-inline">
	<?
	$sort_array2[it_insert_datetime] = "등록일자";
	echo admin_sort($sort_array2,$write[cg_display_2_sort],"cg_display_2_sort");
	?>
	</div>

	<script type="text/javascript">
	$("input[name=cg_display]").click(function () {	
		nfor_radio_click("cg_display",this.value);
	});
	$(document).ready(function (){
		nfor_radio_click("cg_display","<?=$write[cg_display]?>");
	});
	</script>

	
	</td>
</tr>
</table>

<?=admin_title("태그설정","title_tbl")?>

<table class="table cols_tbl margin0">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr style="display:none;">
	<th>추천상품</th> 
	<td colspan="3"><?=admin_radio($write,"cg_hit_use")?></td>
</tr>
<tr style="display:none;">
	<th>추천상품 정렬형태</th> 
	<td colspan="3"><?=admin_radio($write,"cg_hit_rank")?>
	
	
	<div id="cg_hit_rank_1" class="form-inline">
	<?
	$sort_array1[it_name] = "상품명";
	$sort_array1[it_price2] = "판매가";
	$sort_array1[it_insert_datetime] = "등록일자";
	$sort_array1[it_update_datetime] = "수정일자";
	echo admin_sort($sort_array1,$write[cg_hit_rank_1_sort],"cg_hit_rank_1_sort");
	?>
	</div>
	<div id="cg_hit_rank_2" class="form-inline">
	<?
	$sort_array2[it_insert_datetime] = "등록일자";
	echo admin_sort($sort_array2,$write[cg_hit_rank_2_sort],"cg_hit_rank_2_sort");
	?>
	</div>

	<script type="text/javascript">
	$("input[name=cg_hit_rank]").click(function () {	
		nfor_radio_click("cg_hit_rank",this.value);
	});
	$(document).ready(function (){
		nfor_radio_click("cg_hit_rank","<?=$write[cg_hit_rank]?>");
	});
	</script>


	
	</td>
</tr>
<tr style="display:none;">
	<th>추천상품</th> 
	<td colspan="3">
	<?=admin_button("cg_hit_btn", "추천상품")?>
	<table class="table">
	<thead>
	<tr>
		<th><input type="checkbox" id="allCheck" value="y" onclick="nfor_chk_all(this, 'it_hit_chk')"></th>
		<th>이미지</th>
		<th>상품명</th>
		<th>판매가</th>
		<th>공급업체</th>
		<th>재고</th>
		<th>품절상태</th>
		<th>노출상태</th>
		<th>삭제</th>
	</tr>
	</thead>
	<tbody id="layer_hit_append"></tbody>
	</table>
	<?=admin_button("cg_hit_delete_btn", "선택삭제")?>
	</td>
</tr>
<tr>
	<th>상단영역</th> 
	<td colspan="3"><?=admin_editor($write,"cg_category_head")?></td>
</tr>
<tr>
	<th>하단영역</th> 
	<td colspan="3"><?=admin_editor($write,"cg_category_tail")?></td>
</tr>
</table>

<div class="bottom_btn">
	<div class="form-inline">
		<?=admin_submit("fsubmit_btn", $write[cg_id]?"수정하기":"등록하기", "btn btn-lg btn-red")?>
	</div>
</div>

</form>