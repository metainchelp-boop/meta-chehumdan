<?
include_once "path.php";
$target = $_REQUEST['target'];
$result = del_table_nfor_metatech_main($target);
$result = del_table_nfor_metatech_user_main($target);
echo $result;
?>