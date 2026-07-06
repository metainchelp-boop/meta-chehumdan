<?php
$jwt_check = "1";

include_once "path.php";

$nfor['title'] = "1:1문의";

// 1:1문의 회원 전용: 비회원은 답변 확인이 불가하므로 로그인 유도
if(!$member['mb_no']){
	if($mode=="insert" or $json=="form"){
		json_return("1:1문의는 로그인 후 이용 가능합니다.\n로그인 후 마이페이지에서 답변을 확인하실 수 있습니다.","login");
	}
	alert("1:1문의는 로그인 후 이용 가능한 메뉴입니다","login.php");
	exit;
}

if($mode=="insert"){
	// 보안패치
	foreach($_POST as $key => $value){
		if(!is_array($_POST[$key])) $$key = htmlspecialchars(strip_tags($value));
	}


	if(!$cs_timestamp) json_return("중복체크값이 입력되지 않았습니다","cs_timestamp");
	if(!$cs_category) json_return("문의유형을 선택해주세요","cs_category");
	if(!$cs_name) json_return("이름을 입력해주세요","cs_name");
	if(!$cs_tel) json_return("연락처를 입력해주세요","cs_tel");
	if(!$cs_email) json_return("이메일을 입력해주세요","cs_email");
	if(!$cs_subject) json_return("제목을 입력해주세요","cs_subject");
	if(!$cs_memo) json_return("내용을 입력해주세요","cs_memo");
	if(!$cs_agree) json_return("개인정보 수집, 이용에 동의하셔야 진행가능합니다","cs_agree");

	recaptcha_check();

	$cs_file_array = "";
	$cs_filename_array = "";

	if(isset($_POST['cs_file'])){
		for($i=0; $i<count($_POST['cs_file']); $i++){
			if($i){
				$cs_file_array .= "||";
				$cs_filename_array .= "||"; 
			}
			$cs_file_array .= $_POST['cs_file'][$i];
			$cs_filename_array .= $_POST['cs_filename'][$i]; 
		}
	}

	sql_query("insert nfor_customer set cs_timestamp='$cs_timestamp', cs_mb_id='{$member['mb_id']}', cs_file_array='$cs_file_array', cs_filename_array='$cs_filename_array', cs_category='$cs_category', cs_name='$cs_name', cs_email='$cs_email', cs_tel='$cs_tel',  cs_subject='$cs_subject', cs_memo='$cs_memo', cs_insert_id='{$member['mb_no']}', cs_insert_datetime=NOW(), cs_ip='$REMOTE_ADDR', cs_mb_black='{$member['mb_black']}'", false);
	
	$return['url'] = $member['mb_no']?"customer_list.php":"index.php";
	json_return("문의가 접수되었습니다\n담당자 확인후 연락드리겠습니다", "ok");
}

$admin = array();
$admin['cs_category'][''] = "선택";
$que = sql_query("select * from nfor_value where val_code='customer' and val_use='1' order by val_rank asc");
while($row = sql_fetch_array($que)){
	$admin['cs_category'][$row['val_name']] = $row['val_name'];
}

$write = array();
$write['cs_timestamp'] = make_timestamp();

if($member['mb_no']){
	$write['cs_name'] = $member['mb_name'];
	$write['cs_tel'] = $member['mb_hp'];
	$write['cs_email'] = $member['mb_email'];
}

if($write['cs_file_array']){
	$cs_file_array = explode("||",$write['cs_file_array']);
	$cs_filename_array = explode("||",$write['cs_filename_array']);
	for($i=0; $i<count($cs_file_array); $i++){
		$write['cs_file'][$i] = $cs_file_array[$i];
		$write['cs_filename'][$i] = $cs_filename_array[$i];
	}
}

if($json=="form"){
	$return = array("form"=>$admin, "value"=> $write);
	json_return($nfor['title'], "ok");
}

include_once $nfor['skin_path']."customer_form.php";
?>