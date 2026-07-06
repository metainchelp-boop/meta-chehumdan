<?
include_once "path.php";
include_once $nfor[path]."/lib/campaign.lib.php";

$data['idx'] = $_REQUEST['idx'];
$data['mb_id'] = $_REQUEST['mb_id'];
$data['mb_no'] = $_REQUEST['mb_no'];
$data['connect_idx'] = $_REQUEST['connect_idx'];
$data['pt_point'] = $_REQUEST['pt_point'];
$data['pt_memo'] = $_REQUEST['pt_memo'];


// "아이디가 입력되지 않았습니다."
if(!$data[mb_id]) $result = "아이디가 입력되지 않았습니다.\n회수 실패하였습니다.";

$mb = sql_fetch("select * from nfor_member where $member_config[cf_mb_id_type]='".$data[mb_id]."'");
// "존재하지 않는 아이디입니다."
if(!$mb[mb_no]) $result = "존재하지 않는 아이디입니다.\n회수 실패하였습니다.";

// "적립/차감 포인트가 입력되지 않았습니다."
if(!$data[pt_point]) $result = "적립/차감 포인트가 입력되지 않았습니다.\n회수 실패하였습니다.";
// "적립내용이 입력되지 않았습니다."
if(!$data[pt_memo]) $result = "적립내용이 입력되지 않았습니다.\n회수 실패하였습니다.";

$mb_point = select_table_nfor_member_id_to_mb_point($data[mb_id]);

//  "보유하신 포인트보다 입력하신 차감 포인트이 더 큽니다"
if($data[pt_point] < 0 and $mb_point < $data[pt_point]*-1) $result = "보유하신 포인트보다 입력하신 차감 포인트이 더 큽니다\n회수 실패하였습니다.";

if($result == ""){

	$ftimestamp = date("YmdHis").substr(microtime(),2,6);
	delete_table_nfor_metatech_user($data);
	insert_point($data['mb_no'], $data['pt_point'], $data['pt_memo'],"4200",$data['mb_no']."_".$ftimestamp);
	update_table_nfor_metatech_filed($data,"-");

	$result = 1;

}

echo $result;

?>