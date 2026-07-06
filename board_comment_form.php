<?php
include_once "path.php";

if($ct_parent) $ct_parent = str_number($ct_parent);
if($ct_id) $ct_id = str_number($ct_id);
if($ct_nf_id) $ct_nf_id = str_number($ct_nf_id);

// 보안패치
$bo_tbl_array = array();
$que = sql_query("select * from nfor_board where 1");
while($row = sql_fetch_array($que)){
	$bo_tbl_array[$row['bo_tbl']] = $row['bo_tbl'];
}
if($tbl){
	if (!array_key_exists($tbl, $bo_tbl_array)) {
		$tbl = "";
	}
}
// 보안패치





$table = "nfor_comment_".$tbl;

if ($ct_id) $write = sql_fetch("select * from $table where ct_id='$ct_id'");

if ($mode == "insert") {

	foreach($_POST as $key => $value){
		if(!is_array($_POST[$key])) $$key = htmlspecialchars(strip_tags($value));
	}


    if (!$member[mb_no]) json_return("로그인하셔야 이용가능합니다", "login");
    $row = sql_fetch("select min(ct_rank) as min_ct_rank from $table");
    $ct_rank = $row[min_ct_rank] - 1;
    $ct_memo = strip_tags($ct_memo);
	$ct_mb_nick = $member[mb_nick]?$member[mb_nick]:$member[mb_id];
    $add_sql = ", ct_mb_id='{$member[$member_config[cf_mb_id_type]]}', ct_mb_nick='$ct_mb_nick'";
    sql_query("insert $table set ct_rank='$ct_rank', ct_nf_id='$ct_nf_id', ct_parent='$ct_parent', ct_memo='$ct_memo', ct_mb_no='$member[mb_no]', ct_insert_datetime='$nfor[ymdhis]' $add_sql");

    $nf_comment = sql_fetch("select count(*) as cnt from $table where ct_nf_id='$ct_nf_id' and ct_delete='0'");
    sql_query("update nfor_bbs_{$tbl} set nf_comment='$nf_comment[cnt]' where nf_id='$ct_nf_id'");

    json_return("글쓰기성공", "ok");
}

if ($mode == "delete") {
    if (!$member[mb_no]) json_return("로그인하셔야 이용가능합니다", "login");
    if (($write[ct_mb_no] and $write[ct_mb_no] == $member[mb_no]) or $is_admin) {

        if ($write[ct_reply]) {
            $return[ct_delete] = "1";
            sql_query("update $table set ct_memo='삭제된 댓글입니다', ct_delete='1' where ct_id='$ct_id'");
        } else {
            sql_query("delete from $table where ct_id='$ct_id'");
        }

        if ($write[ct_parent]) {
            $ct_reply = sql_fetch("select count(*) as cnt from $table where ct_parent='$write[ct_parent]'");

            if (!$ct_reply[cnt]) {
                $add_sql = ", ct_reply_mb_no='', ct_reply_datetime=''";
            }

            sql_query("update $table set ct_reply='$ct_reply[cnt]' $add_sql where ct_id='$write[ct_parent]'");


            $chk_parent = sql_fetch("select * from $table where ct_id='$write[ct_parent]' and ct_delete='1'");
            if ($chk_parent[ct_id] and !$ct_reply[cnt]) {
                sql_query("delete from $table where ct_id='$write[ct_parent]'");
            }

        }

        $nf_comment = sql_fetch("select count(*) as cnt from $table where ct_nf_id='$write[ct_nf_id]' and ct_delete='0'");
        sql_query("update nfor_bbs_{$tbl} set nf_comment='$nf_comment[cnt]' where nf_id='$write[ct_nf_id]'");
    }
    json_return("삭제되었습니다", "ok");
}

if ($mode == "reply") {

	foreach($_POST as $key => $value){
		if(!is_array($_POST[$key])) $$key = htmlspecialchars(strip_tags($value));
	}

    $write = sql_fetch("select * from $table where ct_id='$ct_parent'");

    if (!$member[mb_no]) json_return("로그인하셔야 이용가능합니다");


    $ct_rank = $write[ct_rank];


    $ct_memo = strip_tags($ct_memo);
	$ct_mb_nick = $member[mb_nick]?$member[mb_nick]:$member[mb_id];
    $add_sql = ", ct_mb_id='{$member[$member_config[cf_mb_id_type]]}', ct_mb_nick='$ct_mb_nick'";

    sql_query("insert $table set ct_rank='$ct_rank', ct_nf_id='$ct_nf_id', ct_parent='$ct_parent', ct_memo='$ct_memo', ct_mb_no='$member[mb_no]', ct_insert_datetime='$nfor[ymdhis]' $add_sql");

    $ct_reply = sql_fetch("select count(*) as cnt from $table where ct_parent='$ct_parent'");

    sql_query("update $table set ct_reply='$ct_reply[cnt]', ct_reply_mb_no='$member[mb_no]', ct_reply_datetime='$nfor[ymdhis]' where ct_id='$ct_parent'");


    $nf_comment = sql_fetch("select count(*) as cnt from $table where ct_nf_id='$ct_nf_id' and ct_delete='0'");
    sql_query("update nfor_bbs_{$tbl} set nf_comment='$nf_comment[cnt]' where nf_id='$ct_nf_id'");

    json_return("답변이등록되었습니다", "ok");
}

if ($mode == "update") {

	foreach($_POST as $key => $value){
		if(!is_array($_POST[$key])) $$key = htmlspecialchars(strip_tags($value));
	}

    if (!$member[mb_no]) json_return("로그인하셔야 이용가능합니다");
    $ct_memo = strip_tags($ct_memo);
    if (($write[ct_mb_no] and $write[ct_mb_no] == $member[mb_no]) or $is_admin) {
        sql_query("update $table set ct_memo='$ct_memo', ct_update_datetime='$nfor[ymdhis]' where ct_id='$ct_id'");
    }
    json_return("수정되었습니다", "ok");
}

if (!$member[mb_no]) alert("로그인하셔야 이용가능합니다", "login.php?url=$PHP_SELF?ct_nf_id=$ct_nf_id");

if ($write[ct_id] and !$write[ct_parent]) {
    $nfor[title] = "수정하기";
} elseif (!$write[ct_id] and $ct_parent) {
    $nfor[title] = "답변하기";
} elseif ($write[ct_id] and $write[ct_parent]) {
    $nfor[title] = "답변수정하기";
} else {
    $nfor[title] = "문의하기";
}

include_once $nfor[skin_path] . basename($_SERVER[PHP_SELF]);
?>