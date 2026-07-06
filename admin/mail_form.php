<?php
include_once "path.php";

$admin['ma_target'] = array(""=>"선택","전체회원" => "전체회원","입점회원" => "입점회원", "레벨회원" => "레벨회원", "성별회원"=>"성별회원", "연령별회원"=>"연령별회원", "지역별회원"=>"지역별회원", "휴면회원"=>"휴면회원", "캠페인참여회원"=>"캠페인참여회원");
$admin['ma_review'] = array(""=>"선택","1" => "신청목록", "2"=>"선정목록", "3"=>"검수요청목록", "7"=>"수정요청목록", "4"=>"등록완료목록", "6"=>"선정후 취소목록", "5"=>"미선정목록");
$admin['ma_sex'] = array(""=>"선택","M" => "남성", "F"=>"여성");
$admin['ma_age'] = array(""=>"선택","10대" => "10대", "20대"=>"20대", "30대"=>"30대", "40대"=>"40대", "50대"=>"50대", "60대"=>"60대", "70대"=>"70대", "80대"=>"80대");
$admin['ma_area'] = array(""=>"선택","서울" => "서울", "경기"=>"경기", "인천"=>"인천");
$admin['ma_agree'] = array("전체","수신동의","수신거부");
$admin['sm_target'] = $admin['ma_target'];
$admin['sm_sex'] = $admin['ma_sex'];
$admin['sm_age'] = $admin['ma_age'];
$admin['sm_area'] = $admin['ma_area'];
$admin['sm_agree'] = $admin['ma_agree'];

$qstr .= "&mail_type=$mail_type&ma_target=$ma_target&ma_target_detail=$ma_target_detail&ma_agree=$ma_agree";

$form = $_SERVER['PHP_SELF'];
$list = str_replace("form","list",$form);
$table = "nfor_mail";
$id = "ma_id";

$id_value = $$id;

if($id_value) $write = sql_fetch("select * from $table where $id='{$id_value}'");

if($mode=="target_change"){

	if(isset($_GET['ma_target'])){
		$ma_target = $_GET['ma_target'];

		if($ma_target=="성별회원"){
			foreach ($admin['ma_sex'] as $key => $value){
				$data[] = array(
					'code' => $key,
					'val' => $value
				);
			}
		} elseif($ma_target=="연령별회원"){
			foreach ($admin['ma_age'] as $key => $value){
				$data[] = array(
					'code' => $value,
					'val' => $value
				);
			}
		} elseif($ma_target=="지역별회원"){		
			foreach ($admin['ma_area'] as $key => $value){
				$data[] = array(
					'code' => $value,
					'val' => $value
				);
			}	
		} elseif($ma_target=="레벨회원"){
			$que = sql_query("select * from nfor_level where 1 order by lv_rank asc");
			while($lv = sql_fetch_array($que)){
				$data[] = array(
					'code' => $lv['lv_id'],
					'val' => $lv['lv_name']
				);
			}
		} elseif($ma_target=="캠페인참여회원"){
			foreach ($admin['ma_review'] as $key => $value){
				$data[] = array(
					'code' => $key,
					'val' => $value
				);
			}	
		} else{

		}


		$reply = array('data' => $data, 'error' => false);
	} else{
		$reply = array('error' => true);
	}

	$json = json_encode($reply);    
	echo $json;
	exit;
}

if($mode=="insert" or $mode=="update"){
	demo_check_json();

	if(!$ma_target) json_return("발송대상을 선택해주세요","ma_target");
	if(!$ma_subject) json_return("제목을 입력해주세요","ma_subject");
	if(!$ma_memo) json_return("내용을 입력해주세요","ma_memo");
	if(!$ma_email) json_return("보내는메일을 입력해주세요","ma_email");
	if($ma_target=="캠페인참여회원"){
		if(!$ma_target_detail) json_return("발송대상을 선택해주세요","ma_target_detail");
		if(!$ma_cp_id) json_return("캠페인코드를 입력해주세요","ma_cp_id");
	}

	$add_sql = "";
	$where_sql = "";

	if($mode=="insert"){
		$msg = "정상적으로 등록 되었습니다";
		$move = "$list";
		$add_sql .= ", ma_insert_id='{$member['mb_no']}', ma_insert_datetime=NOW()";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$move = "$form?{$qstr}&{$id}={$id_value}";
		$where_sql = "  where $id='{$id_value}'";
		$add_sql .= ", ma_update_id='{$member['mb_no']}', ma_update_datetime=NOW()";
	} else{

	}
	$common_sql = " $table set ma_cp_id='$ma_cp_id', ma_subject='$ma_subject', ma_memo='$ma_memo', ma_email='$ma_email', ma_target='$ma_target', ma_target_detail='$ma_target_detail', ma_agree='$ma_agree'";

	sql_query("$mode $common_sql $add_sql $where_sql");
	json_return($msg,"ok");
}

include_once "head.php";
?>

<?=admin_help("메일발송은 기본적으로 고객사 서버의 Sendmail 또는 Smtp 설정을 통해서 발송되며 수신을 보장하지 않습니다","line50 notice_gray")?>

<form name="fwrite" id="fwrite" method="post" autocomplete="off">
<?=admin_hidden($write,"mode")?>
<?=admin_hidden($write,$id)?>
<table class="table cols_tbl margin0">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>발송대상</th> 
	<td>

		<div class="form-inline">
		<?=admin_select($write,"ma_target","width-150p","","0")?>

		<?php
		if($write['ma_target']=="성별회원"){
			$admin['ma_target_detail'] = $admin['ma_sex'];
		} elseif($write['ma_target']=="연령별회원"){
			$admin['ma_target_detail'] = $admin['ma_age'];
		} elseif($write['ma_target']=="지역별회원"){
			$admin['ma_target_detail'] = $admin['ma_area'];
		} elseif($write['ma_target']=="레벨회원"){
			$que = sql_query("select * from nfor_level where 1 order by lv_rank asc");
			while($row = sql_fetch_array($que)){
				$admin['ma_level'][$row['lv_id']] = $row['lv_name'];
			}	
			$admin['ma_target_detail'] = $admin['ma_level'];
		} elseif($write['ma_target']=="캠페인참여회원"){
			$admin['ma_target_detail'] = $admin['ma_review'];
		} else{

		}
		?>
		<?=admin_select($write,"ma_target_detail","width-150p","","0")?>	
		<div class="ma_cp_id_wrap form-inline <?=$write['ma_target']=="캠페인참여회원"?"":"hide"?>"><?=admin_text($write,"ma_cp_id","width-150p","placeholder='캠페인코드'")?><?=admin_button("ma_cp_id_search","캠페인코드 검색","btn-gray btn-sm","data-return_id='ma_cp_id'")?></div>
		</div>

	</td>
</tr>
<tr>
	<th>수신동의</th> 
	<td>
	<?php
	if(!$write['ma_agree']) $write['ma_agree'] = "1";
	?>
	<?=admin_radio($write,"ma_agree")?>
	</td>
</tr>
<tr>
	<th>제목</th> 
	<td><?=admin_text($write,"ma_subject")?></td>
</tr>
<tr>
	<th>내용</th> 
	<td><?=admin_textarea($write,"ma_memo","","rows=\"15\"")?></td>
</tr>
<tr>
	<th>보내는메일</th> 
	<td>
	<?php
	if(!$write['ma_email']) $write['ma_email'] = $config['cf_email'];
	?>
	<?=admin_text($write,"ma_email","width-150p")?>
	</td>
</tr>
</table>

<div class="bottom_btn">
	<div class="form-inline">
		<?=admin_submit("fsubmit_btn", $write[$id]?"수정하기":"등록하기", "btn btn-lg btn-red")?>
		<?=admin_a("list", "목록보기", "btn_back btn btn-lg btn-black")?>
	</div>
</div>

</form>

<script type="text/javascript">
<!--
$(document).on("click","#fsubmit_btn",function(){
	$.ajax({
		type:"post",
		data :$("#fwrite").serialize(),
		url:"<?=basename($_SERVER['PHP_SELF'])?>",
		success:function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				if(json["msg"]){
					alert(json["msg"]);
				}
				if(json["url"]){
					location.href = json["url"];
				}
			} else{
				if(json["msg"]){
					alert(json["msg"]);
				}
				if(json["result"]){
					$("#"+json["result"]).focus();
				}
			}
		}
	});
	event.preventDefault();
});

$(document).on("click", ".btn_back", function(){
	location.href = document.referrer;
});	

$(document).on("change","#ma_target",function(){

	$("#ma_cp_id").val("");

	if($(this).val()=="성별회원" || $(this).val()=="연령별회원" || $(this).val()=="지역별회원" || $(this).val()=="레벨회원" || $(this).val()=="캠페인참여회원"){

		if($(this).val()=="캠페인참여회원"){
			$(".ma_cp_id_wrap").removeClass("hide");
		} else{
			$(".ma_cp_id_wrap").addClass("hide");
		}

		$.ajax({
			type     : "get",
			url      : "mail_form.php",
			data     : "mode=target_change&ma_target="+$(this).val(),
			dataType : 'json',
			cache: false,
			success  : function(data) {
				var output = '';
				for(var i=0; i<data.data.length; i++) {
					output += '<option value="' + data.data[i].code + '">' + data.data[i].val + '</option>';
				}
				$('#ma_target_detail').empty().append(output).show();
			},
			error: function(){
				console.log("Ajax failed");
			}
		});

	} else{
		$(".ma_cp_id_wrap").addClass("hide");
		$('#ma_target_detail').empty().hide();
	}

});

$(document).on("click","#ma_cp_id_search",function(){
	var return_id = $(this).data("return_id");
    window.open("cp_id_search.php?return_id="+return_id, "cp_id_search", "left=50,top=50,width=900,height=900,scrollbars=1");
});
//-->
</script>

<?php
if(!$write['ma_target'] or $write['ma_target']=="전체회원" or $write['ma_target']=="구독자" or $write['ma_target']=="휴면회원" or $write['ma_target']=="입점회원"){
?>
<style>
#ma_target_detail { display:none; }
</style>
<?php } ?>

<?php
include_once "tail.php";
?>