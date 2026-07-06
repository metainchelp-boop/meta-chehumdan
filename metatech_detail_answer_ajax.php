<?php
include_once "./nfor_ajax.php";
//echo $_REQUEST['mb_id'];
//echo $_REQUEST['connect_idx'];
//print_r2($_FILES['file']);

$data['mb_id'] = $_REQUEST['mb_id'];
$data['mb_no'] = $_REQUEST['mb_no'];
$data['connect_idx'] = $_REQUEST['connect_idx'];
$data['pt_point'] = $_REQUEST['pt_point'];
$data['pt_memo'] = $_REQUEST['pt_memo'];
$data['answer_user'] = $_REQUEST['answer_user'];

$data['m_answer'] = select_table_nfor_metatech_m_answer($data['connect_idx']);

// 정답 비교 정규화 — 공백 제거 + 소문자 통일(띄어쓰기/대소문자 차이로 오답처리 방지) 2026-06-16
function mt_norm_answer($s){ return strtolower(preg_replace('/\s+/u','', trim((string)$s))); }

if(mt_norm_answer($data['m_answer']) === mt_norm_answer($data['answer_user']) && mt_norm_answer($data['answer_user']) !== ''){

	$data['filename'] = '';
	$data['status']   = 1;   // 정답형은 서버 검증 통과 = 즉시 지급
	$ftimestamp = date("YmdHis").substr(microtime(),2,6);
	insert_table_nfor_metatech_user($data);
	insert_point($data['mb_no'], $data['pt_point'], $data['pt_memo'],"4200",$data['mb_no']."_".$ftimestamp);

	update_table_nfor_metatech_filed($data);

	$result = 1;
}

echo $result;
?>