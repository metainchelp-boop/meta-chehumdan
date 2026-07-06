<?php
include_once "path.php";

$admin['status'] = array("S"=>"중단","A"=>"정상","R"=>"대기");
$admin['inspStatus'] = array("REG"=>"등록","REQ"=>"심사요청","APR"=>"승인","REJ"=>"반려");

if($mode=="delete"){
	demo_check_json();
	$return = kakao_alarm_template_delete($templtCode);
	json_return($return['message'],"ok");
}

if($mode=="request"){
	demo_check_json();
	$return = kakao_alarm_template_request($templtCode);
	json_return($return['message'],"ok");
}

$template_list = kakao_alarm_template_list();

include_once "head.php";
?>
<?=admin_help("카카오알림톡 템플릿 설정 서비스는 API 서버와 별도의 통신을 주고 받기 때문에 통신환경에 따라 느려질수 있습니다","line50 notice_gray")?>

<form name="flist" id="flist" method="post">
<table class="table row_tbl margin0">
<tr>
	<th>템플릿 명</th>
	<th>등록된 템플릿 콘텐츠</th>
	<!-- <th>발신프로필키	</th> 
	<th>템플릿에 사용된 버튼 정보</th>
	<th>템플릿 생성일</th>-->
	<th>템플릿 코드</th>
	<!-- <th>템플릿 코멘트</th> -->
	<th>검수상태</th>
	<th>승인상태</th>
	<th>수정</th>
	<th>삭제</th>
</tr>
<?php
for($i=0; $i<count($template_list['list']); $i++){
	$row = $template_list['list'][$i];
?>
<tr>
	<td><?=$row['templtName']?></td>
	<td><?=$row['templtContent']?></td>
	<!-- <td><?=$row['senderKey']?></td>
	<td><?=print_R($row['buttons'])?></td>
	<td><?=$row['cdate']?></td> -->
	<td><?=$row['templtCode']?></td>
	<!-- <td><?=print_r($row['comments'])?></td> -->
	<td><?=admin_echo($row,"status")?></td>
	<td>
		<?php if($row['inspStatus']=="REG" or $row['inspStatus']=="REJ"){ ?>
		<?=admin_a("request", "검수요청", "btn btn-white btn-sm nfor_button", "data-confirm=\"검수요청하시겠습니까?\" data-data=\"mode=request&templtCode={$row['templtCode']}\"")?>		
		<?php } else{ ?>		
		<?=admin_echo($row,"inspStatus")?>
		<?php } ?>
	</td>
	<td><?=admin_a("edit", "수정", "btn btn-white btn-sm", "", "kakao_template_form.php?templtCode={$row['templtCode']}")?></td>
	<td><?=admin_a("delete", "삭제", "btn btn-white btn-sm nfor_button", "data-confirm=\"삭제하시겠습니까?\" data-data=\"mode=delete&templtCode={$row['templtCode']}\"")?></td>
</tr>
<?php } ?>
</table>

<div class="bottom_btn">	
	<div class="form-inline"><?=admin_a("form", "등록하기", "btn-lg btn-black btn", "", "kakao_template_form.php")?></div>
</div>

</form>

<?php
include_once "tail.php";
?>