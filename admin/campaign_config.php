<?php
include_once "path.php";

$admin[cf_realtime_review] = array("전체","자동노출(리뷰등록 확인시)","수동노출(별도승인)");
$admin[cf_campaign_info] = array("전체","노출","미노출");

$admin[cf_contents_end_use] = array("전체","사용","미사용");
$admin[cf_naver_talk_use] = array("전체","노출","미노출");


$admin[cf_realtime_review_use] = array("전체","사용","미사용");
$admin[cf_map_campaign_use] = array("전체","사용","미사용");

$admin[cf_campaign_payment_use] = array("전체","사용","미사용");




$admin[cf_bank_file] = array("전체","사용","미사용");
$admin[cf_jumin_file] = array("전체","사용","미사용");
$admin[cf_jumin] = array("전체","사용","미사용");



$table = "nfor_campaign_config";
$form = $_SERVER[PHP_SELF];

if($mode=="update"){
	demo_check();

	$cf_campaign_recent_max = (int)$cf_campaign_recent_max;
	if($cf_campaign_recent_max<1){
		$cf_campaign_recent_max = "20";
	}

	sql_query("update $table set cf_bank_file='$cf_bank_file', cf_jumin_file='$cf_jumin_file', cf_jumin='$cf_jumin', cf_campaign_payment_use='$cf_campaign_payment_use', cf_campaign_edit='$cf_campaign_edit', cf_campaign_report='$cf_campaign_report', cf_campaign_delete='$cf_campaign_delete', cf_campaign_reject='$cf_campaign_reject', cf_campaign_asign='$cf_campaign_asign', cf_campaign_hold='$cf_campaign_hold', cf_review_form='$cf_review_form', cf_review_delete='$cf_review_delete', cf_review_admin_hidden='$cf_review_admin_hidden', cf_review_repair_asign='$cf_review_repair_asign', cf_review_repair_wait='$cf_review_repair_wait', cf_review_traffic='$cf_review_traffic', cf_review_edit='$cf_review_edit', cf_review_show='$cf_review_show', cf_review_hidden='$cf_review_hidden', cf_review_confirm='$cf_review_confirm', cf_review_wait_move='$cf_review_wait_move', cf_review_asign_cancel='$cf_review_asign_cancel', cf_review_asign='$cf_review_asign', cf_review_cancel='$cf_review_cancel', cf_link_blog='$cf_link_blog', cf_link_facebook='$cf_link_facebook', cf_link_instagram='$cf_link_instagram', cf_link_youtube='$cf_link_youtube', cf_realtime_review_use='$cf_realtime_review_use', cf_map_campaign_use='$cf_map_campaign_use', cf_naver_talk_use='$cf_naver_talk_use', cf_naver_talk='$cf_naver_talk', cf_campaign_price='$cf_campaign_price', cf_contents_end_use='$cf_contents_end_use', cf_contents_end_day='$cf_contents_end_day', cf_campaign_info='$cf_campaign_info', cf_realtime_review='$cf_realtime_review', cf_campaign_recent_max='$cf_campaign_recent_max', cf_get_money='$cf_get_money', cf_get_day='$cf_get_day', cf_at_point='$cf_at_point', cf_join_point='$cf_join_point'");

	sql_query("update nfor_config set cf_call_time='$cf_call_time'");


	if($cf_campaign_payment_use=="1"){		
		sql_query("update nfor_access set access_level='1' where gp_id='39'");
	} else{
		sql_query("update nfor_access set access_level='11' where gp_id='39'");
	}

	alert("정상적으로 수정되었습니다",$form);
}

include_once "head.php";


$admin[mb_admin] = array(""=>"전체", "1"=>"입점회원", "2"=>"캠페인관리자", "7"=>"부관리자", "10"=>"최고관리자");
$admin[cf_review_asign] = $admin[mb_admin];
$admin[cf_review_cancel] = $admin[mb_admin];
$admin[cf_review_asign_cancel] = $admin[mb_admin];
$admin[cf_review_wait_move] = $admin[mb_admin];
$admin[cf_review_confirm] = $admin[mb_admin];
$admin[cf_review_show] = $admin[mb_admin];
$admin[cf_review_hidden] = $admin[mb_admin];
$admin[cf_review_edit] = $admin[mb_admin];
$admin[cf_review_traffic] = $admin[mb_admin];
$admin[cf_review_repair_asign] = $admin[mb_admin];
$admin[cf_review_repair_wait] = $admin[mb_admin];


$admin[cf_campaign_report] = $admin[mb_admin];
$admin[cf_campaign_edit] = $admin[mb_admin];



$admin[mb_master] = array(""=>"전체", "7"=>"부관리자", "10"=>"최고관리자");

$admin[cf_review_delete] = $admin[mb_master];
$admin[cf_review_admin_hidden] = $admin[mb_master];


$admin[cf_review_form] = $admin[mb_master];





$admin[cf_campaign_delete] = $admin[mb_master];




$admin[cf_campaign_reject] = $admin[mb_master];
$admin[cf_campaign_asign] = $admin[mb_master];
$admin[cf_campaign_hold] = $admin[mb_master];



?>
 
<form name="fwrite" method="post" onsubmit="return fsubmit(this)" enctype="multipart/form-data">
<?=admin_hidden($config,"mode")?>



<?=admin_title("캠페인관리 권한" ,"title_tbl")?>
<table class="table cols_tbl" >
<colgroup>
	<col class="width-150p ">
	<col >
	<col class="width-150p ">
	<col >
</colgroup>
<tr>
	<th>수정</th>
	<td><div class="form-inline"><?=admin_select($config,"cf_campaign_edit","width-150p")?></div></td>
	<th>삭제</th>
	<td><div class="form-inline"><?=admin_select($config,"cf_campaign_delete","width-150p")?></div></td>
</tr>




<tr>
	<th>미승인변경</th>
	<td><div class="form-inline"><?=admin_select($config,"cf_campaign_reject","width-150p")?></div></td>
	<th>승인변경</th>
	<td><div class="form-inline"><?=admin_select($config,"cf_campaign_asign","width-150p")?></div></td>
</tr>
<tr>
	<th>보류변경</th>
	<td><div class="form-inline"><?=admin_select($config,"cf_campaign_hold","width-150p")?></div></td>


	<th>결과보고서</th>
	<td><div class="form-inline"><?=admin_select($config,"cf_campaign_report","width-150p")?></div></td>
</tr>
</table>



<?=admin_title("신청서관리 권한" ,"title_tbl")?>
<table class="table cols_tbl" >
<colgroup>
	<col class="width-150p ">
	<col >
	<col class="width-150p ">
	<col >
</colgroup>
<tr>
	<th>리뷰어 선정</th>
	<td><div class="form-inline"><?=admin_select($config,"cf_review_asign","width-150p")?></div></td>
	<th>리뷰어 미선정</th>
	<td><div class="form-inline"><?=admin_select($config,"cf_review_cancel","width-150p")?></div></td>
</tr>
<tr>
	<th>리뷰 선정후 취소</th>
	<td><div class="form-inline"><?=admin_select($config,"cf_review_asign_cancel","width-150p")?></div></td>
	<th>신청목록이동</th>
	<td><div class="form-inline"><?=admin_select($config,"cf_review_wait_move","width-150p")?></div></td>
</tr>
<tr>
	<th>리뷰등록확인</th>
	<td><div class="form-inline"><?=admin_select($config,"cf_review_confirm","width-150p")?></div></td>
	<th>리뷰수정요청</th>
	<td><div class="form-inline"><?=admin_select($config,"cf_review_edit","width-150p")?></div></td>
</tr>
<tr>
	<th>실시간리뷰 노출</th>
	<td><div class="form-inline"><?=admin_select($config,"cf_review_show","width-150p")?></div></td>
	<th>실시간리뷰 미노출</th>
	<td><div class="form-inline"><?=admin_select($config,"cf_review_hidden","width-150p")?></div></td>
</tr>
<tr>
	<th>신청서복구(선정목록이동)</th>
	<td><div class="form-inline"><?=admin_select($config,"cf_review_repair_asign","width-150p")?></div></td>
	<th>신청서복구(신청목록이동)</th>
	<td><div class="form-inline"><?=admin_select($config,"cf_review_repair_wait","width-150p")?></div></td>
</tr>
<tr>
	<th>수정</th>
	<td><div class="form-inline"><?=admin_select($config,"cf_review_form","width-150p")?></div><?=admin_help("※ 신청서 상세 권한과 동일하게 셋팅해주세요")?></td>
	<th>컨텐츠 유입분석</th>
	<td><div class="form-inline"><?=admin_select($config,"cf_review_traffic","width-150p")?></div><?=admin_help("※ 통계/데이터 > 컨텐츠 유입분석에 대한 권한과 동일하게 셋팅해주세요")?></td>
</tr>
<tr>
	<th>신청서 삭제(DB삭제)</th>
	<td><div class="form-inline"><?=admin_select($config,"cf_review_delete","width-150p")?></div></td>
	<th>관리자모드에서 숨기기</th>
	<td><div class="form-inline"><?=admin_select($config,"cf_review_admin_hidden","width-150p")?></div></td>
</tr>
</table>



<?=admin_title("사이트 정보" ,"title_tbl")?>
<table class="table cols_tbl" >
<colgroup>
	<col class="width-150p ">
	<col >
</colgroup>
<tr>
	<th>상담시간</th>
	<td><?=admin_text($config,"cf_call_time")?></td>
</tr>
<tr>
	<th>캠페인 정보 노출여부<br>(사이트 하단)</th>
	<td><?=admin_radio($config,"cf_campaign_info")?></td>
</tr>
</table>



<?=admin_title("실시간리뷰 설정" ,"title_tbl")?>
<table class="table cols_tbl" >
<colgroup>
	<col class="width-150p ">
	<col >
</colgroup>
<tr>
	<th>실시간 리뷰</th>
	<td><?=admin_radio($config,"cf_realtime_review")?></td>
</tr>
</table>



<?=admin_title("포인트 설정" ,"title_tbl")?>
<table class="table cols_tbl" >
<colgroup>
	<col class="width-150p ">
	<col >
</colgroup>
<tr>
	<th>회원가입시 포인트</th>
	<td><div class="form-inline">회원가입시 <?=admin_text($config,"cf_join_point","width-100p")?>포인트 지급</div></td>
</tr>
<tr>
	<th>출석체크 포인트</th>
	<td><div class="form-inline">출석체크시 <?=admin_text($config,"cf_at_point","width-100p")?>포인트 지급</div></td>
</tr>
</table>




<?=admin_title("포인트 출금 관련 설정" ,"title_tbl")?>
<table class="table cols_tbl" >
<colgroup>
	<col class="width-150p ">
	<col >
</colgroup>
<tr>
	<th>최소 출금요청 포인트</th>
	<td>최소 <div class="form-inline"><?=admin_text($config,"cf_get_money","width-100p")?></div>포인트 이상이 모여야 출금신청을 할 수 있습니다</td>
</tr>
<tr>
	<th>출금포인트 지급일</th>
	<td>출금신청시, 매월 <div class="form-inline"><?=admin_text($config,"cf_get_day","width-50p")?></div>일에 입금처리됨을 표시합니다</td>
</tr>
<tr>
	<th>주민등록번호 수집</th>
	<td><div class="form-inline"><?=admin_radio($config,"cf_jumin")?></div><?=admin_help("※ 관련법에 따라 사용에 법률 문제가 발생할수 있으니 검토후 신중한 이용바랍니다")?></td>
</tr>
<tr>
	<th>신분증 사본 수집</th>
	<td><div class="form-inline"><?=admin_radio($config,"cf_jumin_file")?></div><?=admin_help("※ 관련법에 따라 사용에 법률 문제가 발생할수 있으니 검토후 신중한 이용바랍니다")?></td>
</tr>
<tr>
	<th>통장 사본 수집</th>
	<td><div class="form-inline"><?=admin_radio($config,"cf_bank_file")?></div><?=admin_help("※ 관련법에 따라 사용에 법률 문제가 발생할수 있으니 검토후 신중한 이용바랍니다")?></td>
</tr>
</table>



<div class="hide">

<?=admin_title("최근 본 캠페인 설정" ,"title_tbl")?>
<table class="table cols_tbl" >
<colgroup>
	<col class="width-150p ">
	<col >
</colgroup>
<tr>
	<th>저장 캠페인수</th>
	<td><div class="form-inline">최근 본 캠페인 <?=admin_text($config,"cf_campaign_recent_max","width-50p")?></div>개 까지 저장하도록 허용</td>
</tr>
</table>

</div>



<?=admin_title("공식 운영채널 설정" ,"title_tbl")?>
<table class="table cols_tbl" >
<colgroup>
	<col class="width-150p ">
	<col >
</colgroup>
<tr>
	<th>블로그 주소</th>
	<td><?=admin_text($config,"cf_link_blog","width-350p")?></td>
</tr>
<tr>
	<th>페이스북 주소</th>
	<td><?=admin_text($config,"cf_link_facebook","width-350p")?></td>
</tr>
<tr>
	<th>인스타그램 주소</th>
	<td><?=admin_text($config,"cf_link_instagram","width-350p")?></td>
</tr>
<tr>
	<th>유뷰브 주소</th>
	<td><?=admin_text($config,"cf_link_youtube","width-350p")?></td>
</tr>
</table>


<?=admin_title("컨텐츠 등록 마감임박 발송설정" ,"title_tbl")?>
<table class="table cols_tbl" >
<colgroup>
	<col class="width-150p ">
	<col >
</colgroup>
<tr>
	<th>마감임박 발송설정</th>
	<td><?=admin_radio($config,"cf_contents_end_use")?></td>
</tr>
<tr>
	<th>발송시점</th>
	<td><div class="form-inline">리뷰어 선정후, 컨텐츠 등록 마감 <?=admin_text($config,"cf_contents_end_day","width-50p")?></div>일 전까지 컨텐츠를 등록하지 않은 상태일 경우 자동 안내 발송(발송 템플릿 설정에 따름)</td>
</tr>
</table>


<?=admin_title("네이버톡톡 사용설정" ,"title_tbl")?>
<table class="table cols_tbl" >
<colgroup>
	<col class="width-150p ">
	<col >
</colgroup>
<tr>
	<th>버튼 노출여부</th>
	<td><?=admin_radio($config,"cf_naver_talk_use")?></td>
</tr>
<tr>
	<th>네이버톡톡 링크주소</th>
	<td><div class="form-inline"><?=admin_text($config,"cf_naver_talk","width-200p")?><?=admin_a("a", "서비스신청", "btn-gray btn-sm", " target=\"_blank\"", "https://partner.talk.naver.com/accounts")?></div></td>
</tr>
</table>



<?=admin_title("추가기능 사용여부" ,"title_tbl")?>
<table class="table cols_tbl" >
<colgroup>
	<col class="width-150p ">
	<col >
</colgroup>
<tr>
	<th>실시간리뷰 사용여부</th>
	<td><?=admin_radio($config,"cf_realtime_review_use")?></td>
</tr>
<tr>
	<th>지도보기 사용여부</th>
	<td><?=admin_radio($config,"cf_map_campaign_use")?></td>
</tr>
</table>



<?=admin_title("캠페인등록 결제모듈(별도구매)" ,"title_tbl")?>
<table class="table cols_tbl" >
<colgroup>
	<col class="width-150p ">
	<col >
</colgroup>
<tr>
	<th>사용여부</th>
	<td><?=admin_radio($config,"cf_campaign_payment_use")?></td>
</tr>
<tr>
	<th>캠페인등록 비용</th>
	<td><div class="form-inline">캠페인 등록시 모집인원 1명당 <?=admin_text($config,"cf_campaign_price","width-100p")?></div>원을 결제합니다</td>
</tr>
</table>











<div class="bottom_btn">
	<div class="form-inline">
		<?=admin_submit("fsubmit_btn", "설정하기", "btn btn-lg btn-red")?>
	</div>
</div>

</form>

<script type="text/javascript">
<!--
function fsubmit(f){
	f.action = "<?=$form?>";
	return true;	    
}
//-->
</script>

<?php
include_once "tail.php";
?>