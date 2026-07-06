<?php
include_once "path.php";

$admin['sm_target'] = array(""=>"선택","전체회원" => "전체회원","입점회원" => "입점회원", "레벨회원" => "레벨회원", "성별회원"=>"성별회원", "연령별회원"=>"연령별회원", "지역별회원"=>"지역별회원", "휴면회원"=>"휴면회원", "캠페인참여회원"=>"캠페인참여회원");
$admin['sm_review'] = array(""=>"선택","1" => "신청목록", "2"=>"선정목록", "3"=>"검수요청목록", "7"=>"수정요청목록", "4"=>"등록완료목록", "6"=>"선정후 취소목록", "5"=>"미선정목록");
$admin['sm_sex'] = array(""=>"선택","M" => "남성", "F"=>"여성");
$admin['sm_age'] = array(""=>"선택","10대" => "10대", "20대"=>"20대", "30대"=>"30대", "40대"=>"40대", "50대"=>"50대", "60대"=>"60대", "70대"=>"70대", "80대"=>"80대");
$admin['sm_area'] = array(""=>"선택","서울" => "서울", "경기"=>"경기", "인천"=>"인천");
$admin['sm_agree'] = array("전체","수신동의","수신거부");
$admin['sm_target'] = $admin['sm_target'];
$admin['sm_sex'] = $admin['sm_sex'];
$admin['sm_age'] = $admin['sm_age'];
$admin['sm_area'] = $admin['sm_area'];

$qstr .= "&sms_type=$sms_type&sm_target=$sm_target&sm_target_detail=$sm_target_detail&sm_agree=$sm_agree";

$form = $_SERVER['PHP_SELF'];
$list = str_replace("form","list",$form);
$table = "nfor_sms";
$id = "sm_id";

$id_value = $$id;

if($id_value) $write = sql_fetch("select * from $table where $id='{$id_value}'");

if($mode=="target_change"){

	if(isset($_GET['sm_target'])){
		$sm_target = $_GET['sm_target'];

		if($sm_target=="성별회원"){
			foreach ($admin['sm_sex'] as $key => $value){
				$data[] = array(
					'code' => $key,
					'val' => $value
				);
			}
		} elseif($sm_target=="연령별회원"){
			foreach ($admin['sm_age'] as $key => $value){
				$data[] = array(
					'code' => $value,
					'val' => $value
				);
			}
		} elseif($sm_target=="지역별회원"){
			foreach ($admin['sm_area'] as $key => $value){
				$data[] = array(
					'code' => $value,
					'val' => $value
				);
			}	
		} elseif($sm_target=="레벨회원"){
			$que = sql_query("select * from nfor_level where 1 order by lv_rank asc");
			while($lv = sql_fetch_array($que)){
				$data[] = array(
					'code' => $lv['lv_id'],
					'val' => $lv['lv_name']
				);
			}
		} elseif($sm_target=="캠페인참여회원"){
			foreach ($admin['sm_review'] as $key => $value){
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

	if(!$sm_target) json_return("발송대상을 선택해주세요","sm_target");
	if(!$sm_subject) json_return("제목을 입력해주세요","sm_subject");
	if(!$sm_memo) json_return("내용을 입력해주세요","sm_memo");
	if(!$sm_hp) json_return("발송번호를 입력해주세요","sm_hp");
	if($sm_target=="캠페인참여회원"){
		if(!$sm_target_detail) json_return("발송대상을 선택해주세요","sm_target_detail");
		if(!$sm_cp_id) json_return("캠페인코드를 입력해주세요","sm_cp_id");
	}

	$add_sql = "";
	$where_sql = "";

	if($mode=="insert"){
		$msg = "정상적으로 등록 되었습니다";
		$move = "$list";
		$add_sql .= ", sm_insert_id='{$member['mb_no']}', sm_insert_datetime=NOW()";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$move = "$form?{$qstr}&{$id}={$id_value}";
		$where_sql .= "  where $id='{$id_value}'";
		$add_sql .= ", sm_update_id='{$member['mb_no']}', sm_update_datetime=NOW()";
	} else{

	}
	$common_sql = " $table set sm_templt_code='$sm_templt_code', sm_cp_id='$sm_cp_id', sm_subject='$sm_subject', sm_memo='$sm_memo', sm_hp='$sm_hp', sm_target='$sm_target', sm_target_detail='$sm_target_detail', sm_agree='$sm_agree'";

	sql_query("$mode $common_sql $add_sql $where_sql");
	$return[url] = "sms_list.php";
	json_return($msg,"ok");
}

include_once "head.php";
?>

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
		<?=admin_select($write,"sm_target","width-150p","","0")?>
		<?php
		if($write['sm_target']=="성별회원"){
			$admin['sm_target_detail'] = $admin['sm_sex'];
		} elseif($write['sm_target']=="연령별회원"){
			$admin['sm_target_detail'] = $admin['sm_age'];
		} elseif($write['sm_target']=="지역별회원"){
			$admin['sm_target_detail'] = $admin['sm_area'];
		} elseif($write['sm_target']=="레벨회원"){
			$que = sql_query("select * from nfor_level where 1 order by lv_rank asc");
			while($row = sql_fetch_array($que)){
				$admin['sm_level'][$row['lv_id']] = $row['lv_name'];
			}	
			$admin['sm_target_detail'] = $admin['sm_level'];
		} elseif($write['sm_target']=="캠페인참여회원"){
			$admin['sm_target_detail'] = $admin['sm_review'];
		} else{

		}
		?>
		<?=admin_select($write,"sm_target_detail","width-150p","","0")?>	
		<div class="sm_cp_id_wrap form-inline <?=$write['sm_target']=="캠페인참여회원"?"":"hide"?>"><?=admin_text($write,"sm_cp_id","width-150p","placeholder='캠페인코드'")?><?=admin_button("sm_cp_id_search","캠페인코드 검색","btn-gray btn-sm","data-return_id='sm_cp_id'")?></div>
		</div>

	</td>
</tr>
<tr>
	<th>수신동의</th> 
	<td>
	<?php
	if(!$write['sm_agree']) $write['sm_agree'] = "1";
	?>
	<?=admin_radio($write,"sm_agree")?>
	</td>
</tr>
<tr>
	<th>제목</th> 
	<td><?=admin_text($write,"sm_subject")?></td>
</tr>
<tr>
	<th>내용</th> 
	<td><?=admin_textarea($write,"sm_memo","","rows=\"15\"")?></td>
</tr>
<tr>
	<th>발송번호</th> 
	<td>
	<?php
	if(!$write['sm_hp']) $write['sm_hp'] = $config['cf_tel'];
	?>
	<?=admin_text($write,"sm_hp","width-150p")?>
	</td>
</tr>
<tr>
	<th>템플릿코드(알림톡)</th> 
	<td><div class="form-inline"><?=admin_text($write,"sm_templt_code","width-150p")?><?=admin_a("a", "알림톡 템플릿 검수/관리", "btn-gray btn-sm", "", "kakao_template_list.php")?> <?=admin_help("※ 카카오 알림톡의 경우 정해진 템플릿 형태로만 전송이 가능합니다. 알림톡으로 전송을 원하시면 사전에 템플릿을 등록하신후 코드를 지정해주세요(미입력시 SMS로 발송됩니다)")?></div></td>
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

$(document).on("change","#sm_target",function(){

	$("#sm_cp_id").val("");

	if($(this).val()=="성별회원" || $(this).val()=="연령별회원" || $(this).val()=="지역별회원" || $(this).val()=="레벨회원" || $(this).val()=="캠페인참여회원"){

		if($(this).val()=="캠페인참여회원"){
			$(".sm_cp_id_wrap").removeClass("hide");
		} else{
			$(".sm_cp_id_wrap").addClass("hide");
		}

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
		$(".sm_cp_id_wrap").addClass("hide");
		$('#sm_target_detail').empty().hide();
	}

});

$(document).on("click","#sm_cp_id_search",function(){
	var return_id = $(this).data("return_id");
    window.open("cp_id_search.php?return_id="+return_id, "cp_id_search", "left=50,top=50,width=900,height=900,scrollbars=1");
});
//-->
</script>

<?php
if(!$write['sm_target'] or $write['sm_target']=="전체회원" or $write['sm_target']=="구독자" or $write['sm_target']=="휴면회원" or $write['sm_target']=="입점회원"){
?>
<style>
#sm_target_detail { display:none; }
</style>
<?php } ?>


<?php
include_once "tail.php";
?>