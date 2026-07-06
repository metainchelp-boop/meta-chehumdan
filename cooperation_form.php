<?php
include_once "path.php";

$nfor['title'] = "제휴문의";

if($mode=="insert"){
	// 보안패치
	foreach($_POST as $key => $value){
		if(!is_array($_POST[$key])) $$key = htmlspecialchars(strip_tags($value));
	}

	if(!$cp_timestamp) json_return("중복체크값이 입력되지 않았습니다","cp_timestamp");
	if(!$cp_category) json_return("분류를 선택해주세요","cp_category");
	if(!$cp_company) json_return("회사명을 입력해주세요","cp_company");
	if(!$cp_name) json_return("담당자명을 입력해주세요","cp_name");
	if(!$cp_tel) json_return("연락처를 입력해주세요","cp_tel");
	if(!$cp_email) json_return("이메일을 입력해주세요","cp_email");
	if(!$cp_subject) json_return("제목을 입력해주세요","cp_subject");
	if(!$cp_memo) json_return("내용을 입력해주세요","cp_memo");
	if(!$cp_agree) json_return("개인정보 수집, 이용에 동의하셔야 진행가능합니다","cp_agree");

	recaptcha_check();

	$cp_file_array = "";
	$cp_filename_array = "";

	if(isset($_POST['cp_file'])){
		for($i=0; $i<count($_POST['cp_file']); $i++){
			if($i){
				$cp_file_array .= "||";
				$cp_filename_array .= "||"; 
			}
			$cp_file_array .= $_POST['cp_file'][$i];
			$cp_filename_array .= $_POST['cp_filename'][$i]; 
		}
	}

	sql_query("insert nfor_cooperation set cp_timestamp='$cp_timestamp', cp_mb_id='{$member['mb_id']}',cp_file_array='$cp_file_array', cp_filename_array='$cp_filename_array', cp_pass='$cp_pass', cp_category='$cp_category', cp_name='$cp_name', cp_email='$cp_email', cp_tel='$cp_tel', cp_subject='$cp_subject', cp_memo='$cp_memo', cp_company='$cp_company', cp_homepage='$cp_homepage', cp_insert_id='{$member['mb_no']}', cp_insert_datetime=NOW(), cp_ip='$REMOTE_ADDR', cp_mb_black='{$member['mb_black']}'", false);

	$return = array("url" => "index.php");
	json_return("제휴문의 및 상담신청이 완료되었습니다\n담당자 확인후 연락드리겠습니다","ok");
}

$admin = array();
$admin['cp_category'][''] = "선택";
$que = sql_query("select * from nfor_value where val_code='cooperation' and val_use='1' order by val_rank asc");
while($row = sql_fetch_array($que)){
	$admin['cp_category'][$row['val_name']] = $row['val_name'];
}

$write = array();
$write['cp_timestamp'] = make_timestamp();

if($member['mb_no']){
	$write['cp_name'] = $member['mb_name'];
	$write['cp_tel'] = $member['mb_hp'];
	$write['cp_email'] = $member['mb_email'];
}

if($write['cp_file_array']){
	$cp_file_array = explode("||",$write['cp_file_array']);
	$cp_filename_array = explode("||",$write['cp_filename_array']);
	for($i=0; $i<count($cp_file_array); $i++){
		$write['cp_file'][$i] = $cp_file_array[$i];
		$write['cp_filename'][$i] = $cp_filename_array[$i];
	}
}

if($json=="form"){
	$return = array("form"=>$admin, "value"=> $write);
	json_return($nfor['title'], "ok");
}

include_once $nfor['skin_path'].basename($_SERVER['PHP_SELF']);
?>