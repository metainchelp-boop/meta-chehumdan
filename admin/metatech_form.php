<?php
include_once "path.php";
include_once "inc_metatech_head.php";


if($mode=="insert"){
	//demo_check();

	$msg = "정상적으로 등록 되었습니다";
	$move = "$list";

	$data['meta_op_a'] = $meta_op_a;
	$data['meta_op_b'] = $meta_op_b;
	$data['meta_op_c'] = $meta_op_c;
	$data['m_name'] = $m_name;
	$data['m_keyword'] = $m_keyword;
	$data['m_url'] = $m_url;
	$data['m_answer'] = $m_answer;
	$data['m_point'] = $m_point;

	$data['m_sdate'] = $m_sdate;
	$data['m_edate'] = $m_edate;
	$data['m_day_conut'] = $m_day_conut;

	// 노출 순위/위치 + 노출 사진 (2026-06-16)
	$data['m_rank_area'] = $m_rank_area;
	$data['m_rank']      = $m_rank;
	$data['m_rank_img']  = '';
	if(isset($_FILES['m_rank_img']) && $_FILES['m_rank_img']['name']){
		$__rf = file_upload($nfor[path]."/data/metatech/", $_FILES['m_rank_img']);   // 2026-06-17 fix2: admin_upload와 동일 $nfor[path]."/data/" 경로
		if($__rf) $data['m_rank_img'] = $__rf;
	}
	// 담당자 = 등록한 관리자 이름 자동 (2026-06-17)
	$data['m_md_name'] = $member[mb_name];

	$insert_result = insert_table_nfor_metatech($data);

	if($insert_result) {
		$msg = "등록이 성공 하였습니다.";
	}
	else{
		$msg = "등록이 실패 하였습니다.";
	}

	alert($msg,$move);
}

include_once "head.php";

?>
<style>
#map { height:350px; width:100%; margin-top:10px; }
</style>

<form name="fwrite" method="post" onsubmit="return fsubmit(this)" enctype="multipart/form-data" autocomplete="off">
<?=admin_hidden($write,"mode")?>

<?// text / pic 유형 두개?>
<input type="hidden" name="meta_op_c" id="meta_op_c"> 

<table class="table cols_tbl margin0" id="z_tb1">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>

<tr>
	<th>구분</th> 
	<td>
		<div class="form-inline">
		<?
		$meta_op_a_checker = "";
		$meta_op_a_arr = $admin["meta_op_a_arr"];
		for($i=0;$i<count($meta_op_a_arr);$i++){
			if($i==0){
				$meta_op_a_checked = " checked";
			}
			else{
				$meta_op_a_checked = "";			
			}
		?>
		<label class="radio-inline">
			<input type="radio" name="meta_op_a" value="<?=$meta_op_a_arr[$i]?>" <?=$meta_op_a_checked?> class="meta_op_a_radio"> 
			<em></em>
			<?=$meta_op_a_arr[$i]?></label>
		</div>
		<?
		}
		?>
	</td>
</tr>
<tr>
	<th>상호명</th> 
	<td><?=admin_text($write,"m_name")?></td>
</tr>
<tr>
	<th>진행기간</th> 
	<td>
		<div class="form-inline">
			<?=admin_text($_GET,"m_sdate","width-100p datepicker-here","data-language=\"ko\"")?> ~ <?=admin_text($_GET,"m_edate","width-100p datepicker-here","data-language=\"ko\"")?>
		</div>
	</td>
</tr>
<tr>
	<th>일 참여자 한도</th> 
	<td><?=admin_text($write,"m_day_conut")?></td>
</tr>
<tr>
	<th>유형</th>
	<td>
		<select name="meta_op_b" class="form-control width-300p">
		<?

		$meta_op_b_arr = $admin["meta_op_b_arr"];
		for($i=0;$i<count($meta_op_b_arr);$i++){
			if($i==0){
				$meta_op_b_checked = " selected";
			}
			else{
				$meta_op_b_checked = "";			
			}
		?>
			<option value="<?=$meta_op_b_arr[$i]?>" <?=$meta_op_b_checked?>  class="meta_op_b_option"><?=$meta_op_b_arr[$i]?></option>
		<?
		}
		?>
		</select>	
	</td>
</tr>
<tr>
	<th>현재 노출 위치</th>
	<td>
		<select name="m_rank_area" class="form-control" style="display:inline-block;width:auto">
			<option value="">검색영역</option>
			<option value="통합검색">통합검색</option>
			<option value="네이버쇼핑">네이버쇼핑</option>
			<option value="네이버플레이스">네이버플레이스</option>
			<option value="블로그탭">블로그탭</option>
		</select>
		<input type="text" name="m_rank" class="form-control" style="display:inline-block;width:220px" placeholder="순위/위치 (예: 7위, 2페이지 상단)">
		<div style="color:#64748b;font-size:12px;margin-top:4px">참여자가 검색결과에서 우리를 빨리 찾도록 현재 노출 위치를 알려줍니다.</div>
	</td>
</tr>
<tr>
	<th>노출 사진</th>
	<td>
		<input type="file" name="m_rank_img" accept="image/*">
		<div style="color:#64748b;font-size:12px;margin-top:4px">검색결과에서 우리 상품/플레이스가 어떻게 보이는지 캡처(선택). 참여자에게 보여집니다.</div>
	</td>
</tr>

</table>

<div class="bottom_btn">
	<div class="form-inline">
		<input type="button" name="fsubmit_chk" id="fsubmit_chk" value="등록하기" class="btn btn-lg btn-red">
		<?=admin_submit("fsubmit_btn", "등록하기", "btn btn-lg btn-red")?>
		<?=admin_a("list", "목록보기", "btn btn-lg btn-black", "", $form."?".$qstr)?>
	</div>
</div>

</form>

<script type="text/javascript">
var meta_op_a_list = <?php echo json_encode($meta_op_a_arr)?>; 
var meta_op_b_list = <?php echo json_encode($meta_op_b_arr)?>; 
var now_meta_op_a_radio = meta_op_a_list[0];
var now_meta_op_b_option = meta_op_b_list[0];

// 등록버튼 숨기기
$("#fsubmit_btn").hide();
// 유효성검사
$(document).on("click","#fsubmit_chk",function(){ 

	var m_name = $("#m_name").val();
	if(m_name == ""){
		alert("상호명을 입력해 주세요.");
		return false;
	}

	var m_name = $("#m_day_conut").val();
	if(m_name == ""){
		alert("일 참여자 한도를 입력해 주세요.");
		return false;
	}

	if(now_meta_op_b_option == meta_op_b_list[0]){
		alert("유형을 선택해주세요.");
		return false;
	}

	if(isIdExist("m_keyword")){
		var m_keyword = $("#m_keyword").val();
		if(m_keyword == ""){
			alert("검색키워드를 입력해 주세요.");
			return false;
		}
	}

	if(isIdExist("m_url")){
		var m_url = $("#m_url").val();
		if(m_url == ""){
			alert("URL 입력해 주세요.");
			return false;
		}
	}

	var m_answer = $("#m_answer").val();
	if(m_answer == ""){
		alert("정답을 입력해 주세요.");
		return false;
	}

	var m_point = $("#m_point").val();
	if(m_point == ""){
		alert("포인트를 입력해 주세요.");
		return false;
	}

	var today = new Date();
	var year = today.getFullYear();
	var month = ('0' + (today.getMonth() + 1)).slice(-2);
	var day = ('0' + today.getDate()).slice(-2);
	var dateString = year + '-' + month  + '-' + day;

	var m_sdate = $("#m_sdate").val();
	var m_edate = $("#m_edate").val();
	if(m_sdate == "" || m_edate == ""){
		alert("진행기간을 선택해 주세요.");
		return false;
	}

	if(m_sdate == m_edate){
		alert("똑같은 진행기간은 선택할수 없습니다.");
		return false;
	}

	var m_sdate_chk = new Date(m_sdate);
	var m_e_sdate_chk = new Date(m_edate);
	var today_chk = new Date(dateString);

	if(m_sdate_chk > m_e_sdate_chk){
		alert("진행 시작 날짜 보다 진행 끝 날짜가 더 작습니다.");
		return false;
	}

	if(today_chk > m_sdate_chk){
		alert("현재날짜 또는 이후 날짜를 선택하여 주세요.");
		return false;
	}

	$('#fsubmit_btn').trigger('click');
});

$(document).on("click",".meta_op_a_radio",function(){ 
	now_meta_op_a_radio = $("input[type=radio]:checked").val();
});

$("select[name=meta_op_b]").change(function(){
	var html_maker = "";
	now_meta_op_b_option = $(this).val();
	html_maker = html_maker_f(now_meta_op_b_option);
	$('#z_tb1').append(html_maker);

});

$(document).on("keyup","#m_point",function(){ 
	$("#m_point").val($("#m_point").val().replace(/[^0-9]/g,""));
});

function html_maker_f(target){
	$(".meta_op_add").remove();
	var html="";

	if(target == meta_op_b_list[1] || target == meta_op_b_list[3]){
		html += '<tr class="meta_op_add">';
		html += '<th>검색키워드</th>';
		html += '<td><?=admin_text($write,"m_keyword")?></td>';
		html += '</tr>';
		html += '<tr class="meta_op_add">';
		html += '<th>정답</th>';
		html += '<td><?=admin_text($write,"m_answer")?></td>';
		html += '</tr>';
		html += '<tr class="meta_op_add">';
		html += '<th>포인트</th>';
		html += '<td><?=admin_text($write,"m_point")?></td>';
		html += '</tr>';

		$("#meta_op_c").val("text");
	
	}
	else if(target == meta_op_b_list[2] || target == meta_op_b_list[4]){
		html += '<tr class="meta_op_add">';
		html += '<th>URL</th>';
		html += '<td><?=admin_text($write,"m_url")?></td>';
		html += '</tr>';
		html += '<tr class="meta_op_add">';
		html += '<th>정답</th>';
		html += '<td><?=admin_text($write,"m_answer")?></td>';
		html += '</tr>';
		html += '<tr class="meta_op_add">';
		html += '<th>포인트</th>';
		html += '<td><?=admin_text($write,"m_point")?></td>';
		html += '</tr>';	

		$("#meta_op_c").val("text");
	}
	else if(target == meta_op_b_list[5] || target == meta_op_b_list[6]){
		html += '<tr class="meta_op_add">';
		html += '<th>검색키워드</th>';
		html += '<td><?=admin_text($write,"m_keyword")?></td>';
		html += '</tr>';
		html += '<tr class="meta_op_add">';
		html += '<th>포인트</th>';
		html += '<td><?=admin_text($write,"m_point")?></td>';
		html += '</tr>';

		$("#meta_op_c").val("pic");
	}
	else if(target == meta_op_b_list[7] || target == meta_op_b_list[8]){
		html += '<tr class="meta_op_add">';
		html += '<th>URL</th>';
		html += '<td><?=admin_text($write,"m_url")?></td>';
		html += '</tr>';
		html += '<tr class="meta_op_add">';
		html += '<th>포인트</th>';
		html += '<td><?=admin_text($write,"m_point")?></td>';
		html += '</tr>';

		$("#meta_op_c").val("pic");
	}
	return html;
}

function isIdExist(id) {
	var result;
	if($('#' + id).length > 0) {
		result = true;
	}
	else{
		result = false;
	}

	return result;
}
</script>

<?php
include_once "tail.php";
?>