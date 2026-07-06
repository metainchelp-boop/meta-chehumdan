<?php
$admin[keyword_type] = array(""=>"전체","cp_subject" => "캠페인명", "cp_id"=>"캠페인코드", "cp_description"=>"캠페인간단설명");
$admin[cp_asign] = array("전체","미승인","승인","보류");
$admin[cp_use] = array("전체","노출","미노출");

$admin[period_type] = array("cp_insert_datetime" => "등록일자", "cp_update_datetime" => "수정일자", "cp_sdatetime" => "리뷰신청시작일", "cp_edatetime" => "리뷰신청종료일", "cp_pick_datetime" => "선정자발표일", "cp_contents_sdatetime" => "리뷰등록시작일", "cp_contents_edatetime" => "리뷰등록종료일", "cp_result_datetime" => "캠페인 결과발표일");		
$admin[sort_type] = array("cp_insert_datetime" => "등록일자", "cp_update_datetime" => "수정일자", "cp_click" => "조회수", "cp_sdatetime" => "리뷰신청시작일", "cp_edatetime" => "리뷰신청종료일", "cp_pick_datetime" => "선정자발표일", "cp_contents_sdatetime" => "리뷰등록시작일", "cp_contents_edatetime" => "리뷰등록종료일", "cp_result_datetime" => "캠페인 결과발표일");	

$admin[cp_type] = $nfor[cp_type];

$admin[cp_pay_step] = array(""=>"전체", "1"=>"결제완료", "3" => "결제취소", "4" => "입금대기", "5" => "입금전취소", "7" => "부분취소");  


$qstr .= "&cp_supply_no=$cp_supply_no&cp_md_no=$cp_md_no&cp_use=$cp_use&cp_asign=$cp_asign&cp_media_blog=$cp_media_blog&cp_media_instagram=$cp_media_instagram&cp_media_youtube=$cp_media_youtube&cp_type=$cp_type&cp_pay_step=$cp_pay_step";

$list = $_SERVER[PHP_SELF];
$form = str_replace("list","form",$list);
$table = "nfor_campaign";
$id = "cp_id";

?>