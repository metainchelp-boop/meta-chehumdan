<?php
include_once "path.php";

$form = $_SERVER['PHP_SELF'];
$list = str_replace("form","list",$form);
$table = "nfor_member";
$id = "mb_no";

$id_value = $$id;

if($id_value){
	$write = sql_fetch("select * from $table where $id='{$id_value}'");
	$write = nfor_tag_out($write);
}

if($mode=="change"){
	$_SESSION['ss_mb_no'] = $write['mb_no'];
	if($write['mb_admin']){
		$return['url'] = "index.php";
	} else{
		$return['url'] = $nfor['path']."/index.php";
	}
	json_return("관리자 모드를 종료하고 사용자 모드로 접속합니다","ok");
}

include_once $nfor['path']."/inc_member_check.php";

include_once "head.php";
?>

<form name="fmember" id="fmember" method="post" onsubmit="return fsubmit();" autocomplete="off" enctype="multipart/form-data">
<?=admin_hidden($write,"mode")?>
<?php
$write['mb_timestamp'] = make_timestamp();
?>
<?=admin_hidden($write,"mb_timestamp")?>
<?=admin_hidden($write,$id)?>

<?=admin_title("기본정보","title_tbl")?>
<table class="table cols_tbl">	
<colgroup>
	<col class="width-150p">
	<col style="width:35%">
	<col class="width-150p">
	<col>
</colgroup>
<tr>
	<th>회원형태</th>
	<td colspan="3"><?=admin_select($write,"mb_admin","width-150p")?></td>
</tr>
<tr>
	<th>레벨</th>
	<td><?=admin_select($write,"mb_level","width-100p")?></td>
	<th>승인여부</th>
	<td>
	<?php
	if(!$write[mb_asign]) $write[mb_asign] = "1";
	?>
	<?=admin_radio_span($write,"mb_asign")?>
	</td>
</tr>
<tr>
	<th>아이디</th>
	<td><?=admin_text_span($write,"mb_id"," width-150p")?></td>
	<th>닉네임</th>
	<td><?=admin_text_span($write,"mb_nick","width-150p")?></td>
</tr>
<tr>
	<th>이메일</th>
	<td><?=admin_text_span($write,"mb_email","width-200p")?></td>
	<th>비밀번호</th>
	<td>
	<?php
	$write[mb_password] = "";
	?>
	<?=admin_password_span($write,"mb_password","width-200p")?>
	</td>
</tr>
<tr>
	<th>이름</th>
	<td colspan="3"><?=admin_text_span($write,"mb_name","width-150p")?></td>
</tr>
<tr>
	<th>휴대폰</th>
	<td><?=admin_text_span($write,"mb_hp","width-200p")?></td>
	<th>전화번호</th>
	<td><?=admin_text_span($write,"mb_tel","width-200p")?></td>
</tr>
<tr>
	<th>주소</th>
	<td colspan="3">
	<div class="marbottom5"><div class="form-inline"><?=admin_text($write,"mb_zipcode","width-80p")?> <?=admin_button("find_mb_zipcode","우편번호찾기","btn-gray")?></div></div>
	<div class="form-inline"><?=admin_text($write,"mb_addr1","width-380p")?> <?=admin_text_span($write,"mb_addr2","width-200p")?></div>
	</td>
</tr>
<tr>
	<th>성별</th>
	<td><?=admin_radio_span($write,"mb_sex")?></td>
	<th>생년월일</th>
	<td><div class="form-inline"><?=admin_select_span($write,"mb_birthday_type","width-80p")?><?=admin_text_span($write,"mb_birthday","datepicker-here","data-language=\"ko\"")?></div></td>
</tr>
<tr>
	<th>이메일 수신동의</th>
	<td><?=admin_radio_span($write,"mb_mailling")?></td>
	<th>SMS 수신동의</th>
	<td><?=admin_radio_span($write,"mb_sms")?></td>
</tr>


<tr>
	<th>추천인아이디</th>
	<td <?=!$write[mb_no]?"colspan='3'":""?>>
	<div class="form-inline"><?=admin_text_span($write,"mb_friend")?></div>
	</td>
	<?php
	if($write[mb_no]){
	?>
	<th>추천받은횟수</th>
	<td><?=number_format($write[mb_friend_count])?>회</td>
	<? } ?>
</tr>

<tr>
	<th>개인정보유효기간</th>
	<td colspan="3">
	<?php
	if(!$write[mb_valid_date]) $write[mb_valid_date] = $member_config[cf_mb_valid_date];
	?>
	<?=admin_radio_span($write,"mb_valid_date")?>
	</td>
</tr>

<? if($write[mb_no]){ ?>
	<tr>
		<th>접근차단</th>
		<td><?=admin_checkbox($checkbox,"mb_access","",$write[mb_access]?"checked":"","체크시 접근차단")?></td>
		<th>블랙컨슈머</th>
		<td><?=admin_checkbox($checkbox,"mb_black","",$write[mb_black]?"checked":"","체크시 경고출력")?></td>
	</tr>
	<tr>
		<th>포인트</th>
		<td><a href="point_list.php?pt_mb_no=<?=$write[mb_no]?>"><?=number_format($write[mb_point])?> Point</a></td>
		<th>가입경로</th>
		<td><?=admin_echo($write,"mb_join_channel")?></td>
	</tr>
	<tr>
		<th>회원가입일시</th>
		<td><?=$write[mb_datetime]?> - <?=$write[mb_ip]?></td>
		<th>최근접속일시</th>
		<td><?=$write[mb_login_datetime]?> - <?=$write[mb_login_ip]?></td>
	</tr>
	<? if($write[mb_leave_date]){ ?>
	<tr>
		<th>탈퇴신청일시</th>
		<td><?=$write[mb_leave_datetime]?></td>
		<th>탈퇴사유</th>
		<td><?=nl2br($write[mb_secession])?></td>
	</tr>
	<? } ?>

<? } ?>
</table>





<?=admin_title("채널정보","title_tbl")?>


<table class="table cols_tbl">	
<colgroup>
	<col class="width-150p">
	<col style="width:35%">
	<col class="width-150p">
	<col>
</colgroup>
<tr>
	<th>블로그</th>
	<td><?=admin_text_span($write,"mb_blog")?></td>
	<th>인스타그램</th>
	<td><?=admin_text_span($write,"mb_instagram")?></td>
</tr>
<tr>
	<th>유튜브</th>
	<td colspan="3"><?=admin_text_span($write,"mb_youtube")?></td>
</tr>
</table>




<?=admin_title("사업자정보","title_tbl")?>
<table class="table cols_tbl">	
<colgroup>
	<col class="width-150p">
	<col style="width:35%">
	<col class="width-150p">
	<col>
</colgroup>
<tr>
	<th>상호</th>
	<td><?=admin_text_span($write,"mb_cp_name")?></td>
	<th>대표자명</th>
	<td><?=admin_text_span($write,"mb_cp_ceo")?></td>
</tr>
<tr>
	<th>사업자번호</th>
	<td colspan="3"><?=admin_text_span($write,"mb_cp_number")?></td>
</tr>
<tr>
	<th>업태</th>
	<td><?=admin_text_span($write,"mb_cp_type1")?></td>
	<th>업종</th>
	<td><?=admin_text_span($write,"mb_cp_type2")?></td>
</tr>
<tr>
	<th>사업장 주소</th>
	<td colspan="3">
	<div class="marbottom5"><div class="form-inline"><?=admin_text($write,"mb_cp_zipcode","width-80p")?> <?=admin_button("find_mb_cp_zipcode","우편번호찾기","btn-gray")?></div></div>
	<div class="form-inline"><?=admin_text($write,"mb_cp_addr1","width-380p")?> <?=admin_text_span($write,"mb_cp_addr2","width-200p")?></div>
	</td>
</tr>
</table>



<? if(basename($_SERVER[PHP_SELF])=="supply_form.php"){ ?>
<?=admin_title("정산계좌정보","title_tbl")?>
<table class="table cols_tbl">	
<colgroup>
	<col class="width-150p">
	<col>
</colgroup>
<tr>
	<th>은행명</th>
	<td><?=admin_text_span($write,"mb_cp_bank_name")?></td>
</tr>
<tr>
	<th>계좌번호</th>
	<td><?=admin_text_span($write,"mb_cp_bank_account")?></td>
</tr>
<tr>
	<th>예금주</th>
	<td><?=admin_text_span($write,"mb_cp_bank_account_holder")?></td>
</tr>
</table>
<? } ?>

<?=admin_title("회원메모","title_tbl")?>
<table class="table cols_tbl">	
<colgroup>
	<col class="width-150p">
	<col>
</colgroup>
<tr>
	<th>회원메모</th>
	<td><?=admin_textarea_span($write,"mb_memo","","rows=\"10\"")?></td>
</tr>
</table>


<div class="bottom_btn">
	<div class="form-inline">
		<?=admin_submit("fsubmit_btn", $write[$id]?"수정하기":"등록하기", "btn btn-lg btn-red")?>

		<? if(!$is_pop){ ?>
		<?=admin_a("list", "목록보기", "btn btn-lg btn-black history_back")?>
		<?=admin_button("mb_change","사용자모드로접속하기","btn btn-lg btn-black nfor_button"," data-confirm=\"관리자모드를 종료하고 사용자로 로그인하시겠습니까?\" data-data=\"mode=change&mb_no=$write[mb_no]\"")?>
		<? } ?>

	</div>
</div>

</form>

<script type="text/javascript">
<!--
$(document).on("click", ".history_back", function(){
	history.back();
});
$(document).on("click", "#find_mb_cp_zipcode", function(){
	zipcode('mb_cp_zipcode', 'mb_cp_addr1', 'mb_cp_addr2');
});
$(document).on("click", "#find_mb_zipcode", function(){
	zipcode('mb_zipcode', 'mb_addr1', 'mb_addr2');
});

$(document).on("blur","#mb_blog, #mb_instagram, #mb_youtube, #mb_id, #mb_nick, #mb_email, #mb_password, #mb_name, #mb_hp, #mb_tel, #mb_zipcode, #mb_addr1, #mb_addr2, #mb_mailling, #mb_sms, #mb_sex, #mb_birthday_type, #mb_birthday, #mb_friend, #mb_valid_date, #mb_cp_name, #mb_cp_ceo, #mb_cp_number, #mb_cp_type1, #mb_cp_type2, #mb_cp_zipcode, #mb_cp_addr1, #mb_cp_addr2, #mb_cp_bank_name, #mb_cp_bank_account, #mb_cp_bank_account_holder, #mb_birthday_1, #mb_birthday_2, #mb_birthday_3, #mb_password_confirm",function(){
	json_check(this.id);
});

function fsubmit(){

	<? if($member_config[cf_mb_id_type]=="mb_id"){ ?>
	if(!nfor_check("mb_id")) return false;
	<? } ?>

	if(!nfor_check("mb_nick")) return false;

	if(!nfor_check("mb_email")) return false;

	if(!nfor_check("mb_name")) return false;

	if(!nfor_check("mb_sex")) return false;

	if(!nfor_check("mb_zipcode")) return false;

	if(!nfor_check("mb_addr1")) return false;

	if(!nfor_check("mb_addr2")) return false;

	<? if(!$write[mb_no]){ ?>
	if(!nfor_check("mb_password")) return false;
	<? } ?>

	if(!nfor_check("mb_birthday_type")) return false;

	if(!nfor_check("mb_birthday")) return false;

	if(!nfor_check("mb_mailling")) return false;

	if(!nfor_check("mb_sms")) return false;

	/* 기타정보 */
	if(!nfor_check("mb_hp")) return false;
	if(!nfor_check("mb_tel")) return false;

	if(!nfor_check("mb_friend")) return false;
	if(!nfor_check("mb_valid_date")) return false;

	if($("#mb_admin").val()=="1"){
		if(!nfor_check("mb_cp_name")) return false;
		if(!nfor_check("mb_cp_ceo")) return false;
		if(!nfor_check("mb_cp_number")) return false;
		if(!nfor_check("mb_cp_type1")) return false;
		if(!nfor_check("mb_cp_type2")) return false;
		if(!nfor_check("mb_cp_zipcode")) return false;
		if(!nfor_check("mb_cp_addr1")) return false;
		if(!nfor_check("mb_cp_addr2")) return false;
	}
	if($("#mb_admin").val()=="1"){
		if(!nfor_check("mb_cp_bank_name")) return false;
		if(!nfor_check("mb_cp_bank_account")) return false;
		if(!nfor_check("mb_cp_bank_account_holder")) return false;
	}
	/* 기타정보 */


	$.ajax({ 
		type : "post"
		, url : "<?=$nfor[path]?>/admin/member_form.php"
		, cache : false  
		, data : $("#fmember").serialize() 
		, success : function(response){ 
			console.log(response);
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				alert(json["msg"]);
				document.location.reload();
			} else{
				alert(json["msg"]);
			}
		}
	});

	return false;
}
//-->
</script>

<?php
include_once "tail.php";
?>