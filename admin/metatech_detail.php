<?php
include_once "path.php";

$find_target = find_table_nfor_metatech_idx($target);

if(!$target or $find_target == 0){
	echo "<script>location.href='./metatech_list.php'</script>";		
}

include_once "inc_metatech_detail_head.php";
include_once "inc_metatech_detail_tail.php";
include_once "head.php";

//$hide_mode_arr = array("공유미션(온라인)","공유미션(오프라인)");
$hide_mode_arr = array("저장하기(오프라인)","알림받기(오프라인)","알림받기(온라인)","상품찜");
//저장하기(오프라인), 알림받기(오프라인), 알림받기(온라인), 상품찜
$open_td_checker = false;

for($i=0;$i<count($hide_mode_arr);$i++){
	if($hide_mode_arr[$i] == $main_info[meta_op_b]){
		$open_td_checker = true;	
	}
}
?>

<style>
.th_style_temp{
	width:100px;
}
</style>

<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name="target" value="<?=$target?>">
<table class="table cols_tbl">


<tr>
	<th class="th_style_temp">구분</th> 
	<td><?=$main_info[meta_op_a]?></td>
</tr>
<tr>
	<th class="th_style_temp">상호명</th>
	<td><?=$main_info[m_name]?></td>
</tr>
<?php if($main_info[m_md_name]){ ?>
<tr>
	<th class="th_style_temp">담당자</th>
	<td><?=htmlspecialchars($main_info[m_md_name])?></td>
</tr>
<?php } ?>
<tr>
	<th class="th_style_temp">진행기간</th> 
	<td><?=$main_info[m_sdate]?>~<?=$main_info[m_edate]?></td>
</tr>
<tr>
	<th class="th_style_temp">일 참여자 한도</th> 
	<td><?=$main_info[m_day_count]?></td>
</tr>
<tr>
	<th class="th_style_temp">유형</th> 
	<td><?=$main_info[meta_op_b]?></td>
</tr>
<?
if($main_info[m_keyword]){
	$main_th_str = "검색키워드";
	$main_td_str = $main_info[m_keyword];
}
else{
	$main_th_str = "URL";
	$main_td_str = $main_info[m_url];
}
?>
<tr>
	<th class="th_style_temp"><?=$main_th_str?></th> 
	<td><?=$main_td_str?></td>
</tr>
<?
$z = "";

for($i=0;$i<count($nfor[meta_op_b]);$i++){
	if($nfor[meta_op_b][$i] == $main_info[meta_op_b]){
		//echo $i . $main_info[meta_op_b];
		$z = $i;
		break;
	}
}

if($z < 4){
?>
<tr>
	<th class="th_style_temp">정답</th> 
	<td><?=$main_info[m_answer]?></td>
</tr>
<?
}
?>

<tr>
	<th class="th_style_temp">포인트</th>
	<td><?=$main_info[m_point]?></td>
</tr>
<?php if($main_info[m_rank] || $main_info[m_rank_area]){ ?>
<tr>
	<th class="th_style_temp">현재 노출 위치</th>
	<td><?=htmlspecialchars($main_info[m_rank_area])?> <?=htmlspecialchars($main_info[m_rank])?></td>
</tr>
<?php } ?>
<?php if($main_info[m_rank_img]){ ?>
<tr>
	<th class="th_style_temp">노출 사진</th>
	<td><a href="<?=$nfor[path]?>/data/metatech/<?=htmlspecialchars($main_info[m_rank_img])?>" target="_blank"><img src="<?=$nfor[path]?>/data/metatech/<?=htmlspecialchars($main_info[m_rank_img])?>" style="max-width:200px;border:1px solid #ddd;border-radius:6px"></a></td>
</tr>
<?php } ?>


</table>

<div class="table_btn">
	<div class="form-inline">
		<input type="button" name="metatech_del" id="metatech_del" value="삭제하기" class="btn-lg btn-black btn">
	</div>
</div>
<div class="ofw martop20">
	<div class="flol">
	전체 <span class="txt_red"> <?=number_format($total_count)?></span>건 / 검색 <span class="txt_red"><?=number_format($search_count)?></span>건
	</div>
	<div class="flor">
	<?=admin_page_row($page_row)?>
	</div>
</div>
</form>

<form name="flist" id="flist" method="post">
<?=admin_hidden($hidden,"ex_id")?>
<?=admin_get()?>
<table class="table row_tbl margin0">
<tr>
	<th>번호</th>
	<th>이름</th>
	<th>아이디</th>
	<th>수정날짜</th>
	<th>상태</th>
<?
if($open_td_checker == true){
?>
	<th>첨부파일</th>
	<th>검수 / 처리</th>
<?
}
?>
</tr>
<?php
for($i=0; $row=sql_fetch_array($result); $i++){
	$st = isset($row[status]) ? $row[status] : '1';   // 구 데이터(컬럼 없던 시절)는 지급완료로 간주
	if($st=='0')      $st_label = '<span style="color:#e8590c;font-weight:800">검수대기</span>';
	else if($st=='2') $st_label = '<span style="color:#868e96;font-weight:700">반려</span>';
	else              $st_label = '<span style="color:#0d9488;font-weight:800">지급완료</span>';
?>
<tr>
	<td><?=$cur_no?></td>
	<td><?=select_table_nfor_member_id_to_name($row[mb_id])?></td>
	<td><?=$row[mb_id]?></td>
	<td><?=$row[datetime]?></td>
	<td><?=$st_label?></td>
<?
	if($open_td_checker == true){
?>
	<td><input type="button" value="확인하기" class="btn-lg btn-black btn view_img" data-imgsrc="<?=filename_dir_maker($row[filename],"metatech")?>"></td>
	<td>
<?	if($st=='0'){ ?>
		<input type="button" value="승인(지급)" class="btn-lg btn approve_act" style="background:#0d9488;border-color:#0d9488;color:#fff" data-uidx="<?=$row[idx]?>" data-act="approve">
		<input type="button" value="반려" class="btn-lg btn approve_act" style="background:#868e96;border-color:#868e96;color:#fff" data-uidx="<?=$row[idx]?>" data-act="reject">
<?	} else if($st=='1'){ ?>
		<input type="button" value="회수하기" class="btn-lg btn-black btn take_point"
		data-idx="<?=$row[idx]?>"
		data-mb_id="<?=$row[mb_id]?>"
		data-mb_no="<?=select_table_nfor_member_id_to_mb_no($row[mb_id])?>"
		data-connect_idx="<?=$row[connect_idx]?>"
		data-pt_point="<?="-".$main_info[m_point]?>"
		data-pt_memo="<?=$main_info[meta_op_a]."|".$main_info[meta_op_b]."|회수"?>"
		>
<?	} else { ?>
		<span style="color:#adb5bd">반려됨</span>
<?	} ?>
	</td>
<?
}
?>
</tr>
<?php
	$cur_no--;
}
$pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");
?>
</table>

<div class="table_btn"><?=$pagelist?></div>

</form>

<script type="text/javascript">
const target = "<?=$target?>";
<!--
$(document).on("click", "#metatech_del", function(){
	if(confirm("정말로 삭제 하시겠습니까??")){
		metatech_del_ajax(target);
	}
});

$(document).on("click", ".take_point", function(){
	if(confirm("정말로 회수 하시겠습니까??")){
		var z_idx = $(this).data('idx');
		var z_mb_id = $(this).data('mb_id');
		var z_mb_no = $(this).data('mb_no');
		var z_connect_idx = $(this).data('connect_idx');
		var z_pt_point = $(this).data('pt_point');
		var z_pt_memo = $(this).data('pt_memo');

		take_point_ajax(z_idx,z_mb_id,z_mb_no,z_connect_idx,z_pt_point,z_pt_memo);
	}
});

// 검수 승인/반려 (사진형) 2026-06-16
$(document).on("click", ".approve_act", function(){
	var uidx = $(this).data('uidx');
	var act  = $(this).data('act');
	var msg  = (act=='approve') ? "이 참여를 승인하고 포인트를 지급할까요?" : "이 참여를 반려할까요?\n(포인트는 지급되지 않습니다)";
	if(!confirm(msg)) return false;
	$.ajax({
		dataType: "text",
		url : "metatech_detail_approve_ajax.php",
		data: { "uidx" : uidx, "act" : act },
		success : function(result){
			if(result == "1"){
				alert(act=='approve' ? "승인되어 포인트가 지급되었습니다." : "반려 처리되었습니다.");
				location.reload();
			} else {
				alert("처리에 실패했습니다. (이미 처리된 건일 수 있습니다)");
			}
		}
	});
});

// 첨부파일보기
$(document).on("click",".view_img",function(){ 
	var imgsrc = $(this).data('imgsrc');
	nfor_layer("metatech_detail_img","imgsrc="+imgsrc,"첨부파일");
});

function metatech_del_ajax(target){
	$.ajax({
		dataType: "text",
        url : "metatech_detail_maindel_ajax.php",
        data: {
			"target" : target
			},
			success : function(result){
			if(result > 0){
				alert("삭제가 성공 하였습니다.");
				location.href='./metatech_list.php';
			}
			else{
				alert("삭제가 실패 하였습니다.");				
			}
        }
    });
	return result;
}

function take_point_ajax(z_idx,z_mb_id,z_mb_no,z_connect_idx,z_pt_point,z_pt_memo){
	$.ajax({
		dataType: "text",
        url : "metatech_detail_takepoint_ajax.php",
        data: {
			"idx" : z_idx,
			"mb_id" : z_mb_id,
			"mb_no" : z_mb_no,
			"connect_idx" : z_connect_idx,
			"pt_point" : z_pt_point,
			"pt_memo" : z_pt_memo
			},
			success : function(result){
				if(result.length == 1){
					alert("회수가 성공 하였습니다.");
					location.href='./metatech_list.php';
				}
				else{
					alert(result);
				}
        }
    });
	return result;
}
//-->
</script>

<?php
include_once "tail.php";
?>