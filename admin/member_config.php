<?php
include_once "path.php";

$admin[cf_mb_id_type] = array(""=>"전체","mb_id"=>"아이디","mb_email"=>"이메일을 아이디로 사용");
$admin[cf_join_asign] = array("전체","승인 절차 없이 가입","승인 후 가입");
$admin[cf_join_age] = array("전체","제한없음","운영자 승인후 가입","가입불가");
$admin[cf_mb_again_asign] = array("전체","로그인 후 본인인증단계 없이 일반회원으로 전환","회원정보에 등록되어있는 정보 입력 후 일반회원으로 전환","본인인증 이후 일반회원으로 전환");
$admin[cf_mb_level][""] = "선택";
$que = sql_query("select * from nfor_level where 1 order by lv_rank asc");
while($row = sql_fetch_array($que)){
	$admin[cf_mb_level][$row[lv_id]] = $row[lv_name];
}
$admin[cf_mb_valid_date] = array(""=>"전체","1 year" => "1년", "3 year"=>"3년", "5 year"=>"5년", "leave"=>"탈퇴시");


$table = "nfor_member_config";
$form = $_SERVER[PHP_SELF];

$member_config = sql_fetch("select * from $table");

if($mode=="update"){
	demo_check();



	if($cf_mb_id_type=="mb_id"){
		$mb_id_use = "1";
		$mb_id_require = "1";
	} elseif($cf_mb_id_type=="mb_email"){
		$mb_email_use = "1";
		$mb_email_require = "1";
	} else{

	}


	$mb_zipcode_use = $mb_address_use;
	$mb_addr1_use = $mb_address_use;
	$mb_addr2_use = $mb_address_use;
	$mb_zipcode_require = $mb_address_require;
	$mb_addr1_require = $mb_address_require;
	$mb_addr2_require = $mb_address_require;

	$mb_cp_zipcode_use = $mb_cp_address_use;
	$mb_cp_addr1_use = $mb_cp_address_use;
	$mb_cp_addr2_use = $mb_cp_address_use;
	$mb_cp_zipcode_require = $mb_cp_address_require;
	$mb_cp_addr1_require = $mb_cp_address_require;
	$mb_cp_addr2_require = $mb_cp_address_require;


	sql_query("update $table set
	cf_join_asign='$cf_join_asign'
	, cf_join_age='$cf_join_age'
	, cf_join_teen='$cf_join_teen'
	, cf_join_adult='$cf_join_adult'
	, cf_join_notid='$cf_join_notid'
	, cf_mb_again_asign='$cf_mb_again_asign'
	, cf_asign_mb_hp='$cf_asign_mb_hp'
	, cf_asign_mb_email='$cf_asign_mb_email'
	, cf_asign_sms='$cf_asign_sms'
	, cf_asign_mailing='$cf_asign_mailing'
	, cf_asign_ipin='$cf_asign_ipin'
	, cf_asign_hp='$cf_asign_hp'
	, cf_join_notnick='$cf_join_notnick'	
	, mb_id_use='$mb_id_use'
	, mb_id_require='$mb_id_require'
	, mb_name_use='$mb_name_use'
	, mb_name_require='$mb_name_require'
	, mb_nick_use='$mb_nick_use'
	, mb_nick_require='$mb_nick_require'
	, mb_email_use='$mb_email_use'
	, mb_email_require='$mb_email_require'
	, mb_hp_use='$mb_hp_use'
	, mb_hp_require='$mb_hp_require'
	, mb_tel_use='$mb_tel_use'
	, mb_tel_require='$mb_tel_require'
	, mb_address_use='$mb_address_use'
	, mb_address_require='$mb_address_require'
	, mb_zipcode_use='$mb_zipcode_use'
	, mb_zipcode_require='$mb_zipcode_require'
	, mb_addr1_use='$mb_addr1_use'
	, mb_addr1_require='$mb_addr1_require'
	, mb_addr2_use='$mb_addr2_use'	
	, mb_addr2_require='$mb_addr2_require'
	, mb_friend_use='$mb_friend_use'
	, mb_friend_require='$mb_friend_require'
	, mb_birthday_use='$mb_birthday_use'
	, mb_birthday_require='$mb_birthday_require'
	, mb_birthday_type_use='$mb_birthday_type_use'
	, mb_birthday_type_require='$mb_birthday_type_require'
	, mb_sex_use='$mb_sex_use'
	, mb_sex_require='$mb_sex_require'	
	, mb_cp_name_use='$mb_cp_name_use'
	, mb_cp_name_require='$mb_cp_name_require'
	, mb_cp_ceo_use='$mb_cp_ceo_use'
	, mb_cp_ceo_require='$mb_cp_ceo_require'
	, mb_cp_number_use='$mb_cp_number_use'
	, mb_cp_number_require='$mb_cp_number_require'
	, mb_cp_address_use='$mb_cp_address_use'
	, mb_cp_address_require='$mb_cp_address_require'
	, mb_cp_zipcode_use='$mb_cp_zipcode_use'
	, mb_cp_zipcode_require='$mb_cp_zipcode_require'
	, mb_cp_addr1_use='$mb_cp_addr1_use'
	, mb_cp_addr1_require='$mb_cp_addr1_require'
	, mb_cp_addr2_use='$mb_cp_addr2_use'
	, mb_cp_addr2_require='$mb_cp_addr2_require'
	, mb_cp_type1_use='$mb_cp_type1_use'
	, mb_cp_type1_require='$mb_cp_type1_require'
	, mb_cp_type2_use='$mb_cp_type2_use'
	, mb_cp_type2_require='$mb_cp_type2_require' 
	, mb_mailling_use='$mb_mailling_use'
	, mb_mailling_require='$mb_mailling_require'
	, mb_blog_use='$mb_blog_use'
	, mb_blog_require='$mb_blog_require'
	, mb_instagram_use='$mb_instagram_use'
	, mb_instagram_require='$mb_instagram_require'
	, mb_youtube_use='$mb_youtube_use'
	, mb_youtube_require='$mb_youtube_require'
	, cf_mb_id_type='$cf_mb_id_type'
	, cf_mb_level='$cf_mb_level'
	, cf_mb_valid_date='$cf_mb_valid_date'
	, mb_sms_use='$mb_sms_use'
	, mb_sms_require='$mb_sms_require'");

	alert("정상적으로 수정되었습니다",$form);
}

include_once "head.php";
?>
 
<form name="fwrite" method="post" onsubmit="return fsubmit(this)">
<?=admin_hidden($member_config,"mode")?>

<?=admin_title("가입 설정","title_tbl")?>

   <?=admin_help(" 쇼핑몰 회원가입 시 회원에게 수집할 회원정보 항목을 설정합니다. <br>
   - 항목별 사용여부 및 필수여부 등을 선택할 수 있습니다.<br>
    - 회원가입 시 아이디/이름/비밀번호가 필수 입력사항입니다. 해당 항목에 대한 사용 및 필수 체크는 해제할 수 없습니다.","line50 notice_gray")?>
<table class="table cols_tbl " >
<colgroup>
   <col class="width-150p">
    <col >
 </colgroup>
<tr>
	<th>가입승인 사용설정</th>
	<td><?=admin_radio($member_config,"cf_join_asign")?></td>
</tr>
<tr>
	<th>가입연령제한 설정</th>
	<td><?=admin_radio($member_config,"cf_join_age")?></td>
</tr>
<tr id="cf_join_age_2">
	<th>승인대상 설정</th>
	<td><div class="form-inline"><?=admin_text($member_config,"cf_join_teen","width-50p")?>세 이하인 경우 운영자 승인후 가입</div></td>
</tr>
<tr id="cf_join_age_3">
	<th>제한대상 설정</th>
	<td><div class="form-inline"><?=admin_text($member_config,"cf_join_adult","width-50p")?>세 이하인 경우 가입제한</div></td>
</tr>
<tr>
	<th>회원가입시 기본레벨</th>
	<td><div class="form-inline"><?=admin_select($member_config,"cf_mb_level")?></div></td>
</tr>




<tr>
	<th>회원가입시 개인정보유효기간</th>
	<td><div class="form-inline"><?=admin_radio($member_config,"cf_mb_valid_date")?></div></td>
</tr>






</table>


<?=admin_title("가입불가 정보입력","title_tbl")?>
<table class="table cols_tbl " >
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>가입불가 아이디</th>
	<td>
	,(콤마)로 구분하여 입력합니다. 입력 예) master, user
	<?=admin_textarea($member_config,"cf_join_notid","","rows=\"10\"")?>
	</td>
</tr>
<tr>
	<th>가입불가 닉네임</th>
	<td>
	,(콤마)로 구분하여 입력합니다. 입력 예) 마스터, 판매자
	<?=admin_textarea($member_config,"cf_join_notnick","","rows=\"10\"")?>
	</td>
</tr>
</table>


<?=admin_title("휴면회원 정책","title_tbl")?>
<table class="table cols_tbl " >
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>일반회원 전환방법</th>
	<td><?=admin_radio($member_config,"cf_mb_again_asign")?></td>
</tr>
<tr id="cf_mb_again_asign_2">
	<th>일반회원 전환수단</th>
	<td>
	<?=admin_checkbox($checkbox,"cf_asign_mb_hp","",$member_config[cf_asign_mb_hp]?"checked":"","휴대폰번호")?>
	<?=admin_checkbox($checkbox,"cf_asign_mb_email","",$member_config[cf_asign_mb_email]?"checked":"","이메일")?>
	</td>
</tr>
<tr id="cf_mb_again_asign_3">
	<th>일반회원 전환수단</th>
	<td>
	<?=admin_checkbox($checkbox,"cf_asign_sms","",$member_config[cf_asign_sms]?"checked":"","휴대폰번호로 인증번호 수신")?>
	<?=admin_checkbox($checkbox,"cf_asign_mailing","",$member_config[cf_asign_mailing]?"checked":"","이메일로 인증번호 수신")?>
	<?=admin_checkbox($checkbox,"cf_asign_ipin","",$member_config[cf_asign_ipin]?"checked":"","아이핀 본인인증")?>
	<?=admin_checkbox($checkbox,"cf_asign_hp","",$member_config[cf_asign_hp]?"checked":"","휴대폰 본인인증")?>
	</td>
</tr>
</table>




<?=admin_title("아이디 사용","title_tbl")?>
<table class="table cols_tbl " >
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>아이디 사용</th>
	<td>
		<?=admin_radio($member_config,"cf_mb_id_type")?>
		<span style="color:red;">※ 본 설정은 사이트 이용중 변경불가(사이트 오픈전에만 설정 가능)합니다.</span>

	</td>
</tr>
</table>





<?=admin_title("기본정보","title_tbl")?>
<table class="table cols_tbl">
<colgroup>
	<col class="width-150p">
	<col >
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>아이디</th>
	<td>
	<?=admin_checkbox($checkbox,"mb_id_use","",$member_config[mb_id_use]?"checked":"","사용")?>
	<?=admin_checkbox($checkbox,"mb_id_require","",$member_config[mb_id_require]?"checked":"","필수")?>
	</td>
	<th>이메일</th>
	<td>
	<?=admin_checkbox($checkbox,"mb_email_use","",$member_config[mb_email_use]?"checked":"","사용")?>
	<?=admin_checkbox($checkbox,"mb_email_require","",$member_config[mb_email_require]?"checked":"","필수")?>
	</td>
</tr>
<tr>
	<th>이름</th>
	<td>
	<?=admin_checkbox($checkbox,"mb_name_use","",$member_config[mb_name_use]?"checked":"","사용")?>
	<?=admin_checkbox($checkbox,"mb_name_require","",$member_config[mb_name_require]?"checked":"","필수")?>
	</td>
	<th>닉네임</th>
	<td>
	<?=admin_checkbox($checkbox,"mb_nick_use","",$member_config[mb_nick_use]?"checked":"","사용")?>
	<?=admin_checkbox($checkbox,"mb_nick_require","",$member_config[mb_nick_require]?"checked":"","필수")?>
	</td>
</tr>
<tr>
	<th>휴대폰번호</th>
	<td>
	<?=admin_checkbox($checkbox,"mb_hp_use","",$member_config[mb_hp_use]?"checked":"","사용")?>
	<?=admin_checkbox($checkbox,"mb_hp_require","",$member_config[mb_hp_require]?"checked":"","필수")?>
	</td>
	<th>전화번호</th>
	<td>
	<?=admin_checkbox($checkbox,"mb_tel_use","",$member_config[mb_tel_use]?"checked":"","사용")?>
	<?=admin_checkbox($checkbox,"mb_tel_require","",$member_config[mb_tel_require]?"checked":"","필수")?>
	</td>
</tr>
<tr>
	<th>주소</th>
	<td>
	<?=admin_checkbox($checkbox,"mb_address_use","",$member_config[mb_address_use]?"checked":"","사용")?>
	<?=admin_checkbox($checkbox,"mb_address_require","",$member_config[mb_address_require]?"checked":"","필수")?>
	</td>
	<th>성별</th>
	<td>
	<?=admin_checkbox($checkbox,"mb_sex_use","",$member_config[mb_sex_use]?"checked":"","사용")?>
	<?=admin_checkbox($checkbox,"mb_sex_require","",$member_config[mb_sex_require]?"checked":"","필수")?>
	</td>
</tr>
<tr>
	<th>생년월일(양력/음력)</th>
	<td>
	<?=admin_checkbox($checkbox,"mb_birthday_type_use","",$member_config[mb_birthday_type_use]?"checked":"","사용")?>
	<?=admin_checkbox($checkbox,"mb_birthday_type_require","",$member_config[mb_birthday_type_require]?"checked":"","필수")?>
	</td>
	<th>생년월일</th>
	<td>
	<?=admin_checkbox($checkbox,"mb_birthday_use","",$member_config[mb_birthday_use]?"checked":"","사용")?>
	<?=admin_checkbox($checkbox,"mb_birthday_require","",$member_config[mb_birthday_require]?"checked":"","필수")?>
	</td>
</tr>
<tr>
	<th>메일수신동의</th>
	<td>
	<?=admin_checkbox($checkbox,"mb_mailling_use","",$member_config[mb_mailling_use]?"checked":"","사용")?>
	<?=admin_checkbox($checkbox,"mb_mailling_require","",$member_config[mb_mailling_require]?"checked":"","필수")?>
	</td>
	<th>SMS수신동의</th>
	<td>
	<?=admin_checkbox($checkbox,"mb_sms_use","",$member_config[mb_sms_use]?"checked":"","사용")?>
	<?=admin_checkbox($checkbox,"mb_sms_require","",$member_config[mb_sms_require]?"checked":"","필수")?>
	</td>
</tr>
<tr>
	<th>추천인</th>
	<td colspan="3">
	<?=admin_checkbox($checkbox,"mb_friend_use","",$member_config[mb_friend_use]?"checked":"","사용")?>
	<?=admin_checkbox($checkbox,"mb_friend_require","",$member_config[mb_friend_require]?"checked":"","필수")?>
	</td>
</tr>
</table>



<?=admin_title("운영채널정보","title_tbl")?>
<table class="table cols_tbl margin10" >
<colgroup>
	<col class="width-150p">
	<col >
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>블로그</th>
	<td>
	<?=admin_checkbox($checkbox,"mb_blog_use","",$member_config[mb_blog_use]?"checked":"","사용")?>
	<?=admin_checkbox($checkbox,"mb_blog_require","",$member_config[mb_blog_require]?"checked":"","필수")?>
	</td>
	<th>인스타그램</th>
	<td>
	<?=admin_checkbox($checkbox,"mb_instagram_use","",$member_config[mb_instagram_use]?"checked":"","사용")?>
	<?=admin_checkbox($checkbox,"mb_instagram_require","",$member_config[mb_instagram_require]?"checked":"","필수")?>
	</td>
</tr>
<tr>
	<th>유튜브</th>
	<td colspan="3">
	<?=admin_checkbox($checkbox,"mb_youtube_use","",$member_config[mb_youtube_use]?"checked":"","사용")?>
	<?=admin_checkbox($checkbox,"mb_youtube_require","",$member_config[mb_youtube_require]?"checked":"","필수")?>
	</td>
</tr>
</table>


<?=admin_title("사업자정보","title_tbl")?>
<table class="table cols_tbl margin0" >
<colgroup>
	<col class="width-150p">
	<col >
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>상호</th>
	<td>
	<?=admin_checkbox($checkbox,"mb_cp_name_use","",$member_config[mb_cp_name_use]?"checked":"","사용")?>
	<?=admin_checkbox($checkbox,"mb_cp_name_require","",$member_config[mb_cp_name_require]?"checked":"","필수")?>
	</td>
	<th>대표자명</th>
	<td>
	<?=admin_checkbox($checkbox,"mb_cp_ceo_use","",$member_config[mb_cp_ceo_use]?"checked":"","사용")?>
	<?=admin_checkbox($checkbox,"mb_cp_ceo_require","",$member_config[mb_cp_ceo_require]?"checked":"","필수")?>
	</td>
</tr>
<tr>
	<th>사업자번호</th>
	<td>
	<?=admin_checkbox($checkbox,"mb_cp_number_use","",$member_config[mb_cp_number_use]?"checked":"","사용")?>
	<?=admin_checkbox($checkbox,"mb_cp_number_require","",$member_config[mb_cp_number_require]?"checked":"","필수")?>
	</td>
	<th>사업장주소</th>
	<td>
	<?=admin_checkbox($checkbox,"mb_cp_address_use","",$member_config[mb_cp_address_use]?"checked":"","사용")?>
	<?=admin_checkbox($checkbox,"mb_cp_address_require","",$member_config[mb_cp_address_require]?"checked":"","필수")?>
	</td>
</tr>
<tr>
	<th>업태</th>
	<td>
	<?=admin_checkbox($checkbox,"mb_cp_type1_use","",$member_config[mb_cp_type1_use]?"checked":"","사용")?>
	<?=admin_checkbox($checkbox,"mb_cp_type1_require","",$member_config[mb_cp_type1_require]?"checked":"","필수")?>
	</td>
	<th>업종</th>
	<td>
	<?=admin_checkbox($checkbox,"mb_cp_type2_use","",$member_config[mb_cp_type2_use]?"checked":"","사용")?>
	<?=admin_checkbox($checkbox,"mb_cp_type2_require","",$member_config[mb_cp_type2_require]?"checked":"","필수")?>
	</td>
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

$(document).ready(function (){
	nfor_radio_click("cf_join_age","<?=$member_config[cf_join_age]?>");
	nfor_radio_click("cf_mb_again_asign","<?=$member_config[cf_mb_again_asign]?>");
});
$(document).on("click", "input[name=cf_join_age]", function(){
	nfor_radio_click("cf_join_age",this.value);
});
$(document).on("click", "input[name=cf_mb_again_asign]", function(){
	nfor_radio_click("cf_mb_again_asign",this.value);
});
//-->
</script>

<?php
include_once "tail.php";
?>