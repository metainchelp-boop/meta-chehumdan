<?php

$qstr .= "&target=$target";

$admin["meta_op_a_arr"] = $nfor[meta_op_a];
$admin["meta_op_b_arr"] = array_merge(array("선택"),$nfor[meta_op_b]);

$admin["meta_op_a_arr_list"] = array_merge(array("전체"),$nfor[meta_op_a]);
$admin["meta_op_b_arr_list"] = array_merge(array("전체"),$nfor[meta_op_b]);

$form = $_SERVER[PHP_SELF];

$list = $form;

if($form == "/admin/metatech_list.php"){
	//$ex_page = 
	$form = str_replace("list","form",$form);
}
else if($form == "/admin/metatech_form.php"){
	$form = str_replace("form","list",$form);
}

$table = "nfor_metatech_user";

// 테이블이 있는지 확인하고 테이블 생성
find_create_table_nfor_metatech();
// 테이블이 있는지 확인하고 유저 테이블 생성
find_create_table_nfor_metatech_user();

?>