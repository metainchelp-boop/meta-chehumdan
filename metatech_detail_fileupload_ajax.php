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

$folder = "metatech";
if($filename_add = file_upload("./data/".$folder."/", $_FILES['file'])){
	$result = "./data/".$folder."/".$filename_add;
	$data['filename'] = $filename_add;

	// 2026-06-16: 사진형은 검수대기(status=0)로 저장하고 포인트는 관리자 승인 시 지급
	$data['status'] = 0;
	insert_table_nfor_metatech_user($data);
	// insert_point 제거 — 관리자 검수 승인(metatech_approve_user) 시점에 지급

	update_table_nfor_metatech_filed($data);
}


echo $result;
?>