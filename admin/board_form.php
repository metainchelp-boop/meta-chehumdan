<?php
include_once "path.php";
$admin[bo_pc_scroll] = array(""=>"선택", "1"=>"스크롤", "0" => "페이징");
$admin[bo_mobile_scroll] = array(""=>"선택", "1"=>"스크롤", "0" => "페이징");
$admin[bo_use] = array("전체","노출","미노출");

$form = $_SERVER[PHP_SELF];
$list = str_replace("form","list",$form);
$table = "nfor_board";
$id = "bo_id";

$id_value = $$id;

if($id_value){
	$write = sql_fetch("select * from $table where $id='{$id_value}'");
}

if($mode=="insert" or $mode=="update"){
	demo_check();

	if($bo_pc_rows<1) $bo_pc_rows = "20";
	if($bo_mobile_rows<1) $bo_mobile_rows = "20";

	$add_sql = "";
	$where_sql = "";

	if($mode=="insert"){
		$msg = "정상적으로 등록 되었습니다";
		$move = "$list";
		$add_sql .= ", bo_insert_id='$member[mb_no]', bo_insert_datetime=NOW()";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$move = "$form?{$qstr}&{$id}={$id_value}";
		$where_sql = "  where $id='{$id_value}'";
		$add_sql .= ", bo_update_id='$member[mb_no]', bo_update_datetime=NOW()";
	} else{

	}
	$common_sql = " $table set bo_rank='$bo_rank', bo_pc_scroll='$bo_pc_scroll', bo_mobile_scroll='$bo_mobile_scroll', bo_pc_rows='$bo_pc_rows', bo_mobile_rows='$bo_mobile_rows', bo_tbl='$bo_tbl', bo_name='$bo_name', bo_skin='$bo_skin', bo_html_head='$bo_html_head', bo_html_tail='$bo_html_tail', bo_include_head='$bo_include_head', bo_include_tail='$bo_include_tail', bo_include_mobile_head='$bo_include_mobile_head', bo_include_mobile_tail='$bo_include_mobile_tail', bo_use='$bo_use'";

	sql_query("$mode $common_sql $add_sql $where_sql");

	if($mode=="insert"){
		$sql = "CREATE TABLE `nfor_bbs_$bo_tbl` (
		  `nf_id` int(11) NOT NULL AUTO_INCREMENT,
		  `nf_category` varchar(255) NOT NULL,
		  `nf_subject` varchar(255) NOT NULL,
		  `nf_memo` text NOT NULL,
		  `nf_mb_id` varchar(255) NOT NULL,
		  `nf_datetime` datetime NOT NULL,
		  `nf_hit` int(11) NOT NULL,
		  `nf_like` int(11) NOT NULL,
		  `nf_mb_no` varchar(255) NOT NULL,
		  `nf_mb_nick` varchar(255) NOT NULL,
		  `nf_ip` varchar(255) NOT NULL,
		  `nf_link1` varchar(255) NOT NULL,
		  `nf_link2` varchar(255) NOT NULL,
		  `nf_img0` varchar(255) NOT NULL,
		  `nf_img1` varchar(255) NOT NULL,
		  `nf_img2` varchar(255) NOT NULL,
		  `nf_img3` varchar(255) NOT NULL,
		  `nf_img4` varchar(255) NOT NULL,
		  `nf_img5` varchar(255) NOT NULL,
		  `nf_img6` varchar(255) NOT NULL,
		  `nf_img7` varchar(255) NOT NULL,
		  `nf_img8` varchar(255) NOT NULL,
		  `nf_img9` varchar(255) NOT NULL,
		  `nf_img10` varchar(255) NOT NULL,
		  `nf_comment` int(11) NOT NULL,
		  `nf_view` int(1) NOT NULL DEFAULT '1',
		  PRIMARY KEY (`nf_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		sql_query($sql);


		$sql = "CREATE TABLE `nfor_comment_$bo_tbl` (
		  `ct_id` int(11) NOT NULL AUTO_INCREMENT,
		  `ct_parent` int(11) NOT NULL,
		  `ct_insert_datetime` datetime NOT NULL,
		  `ct_nf_id` int(11) NOT NULL,
		  `ct_memo` text NOT NULL,
		  `ct_mb_id` varchar(255) NOT NULL,
		  `ct_mb_no` varchar(255) NOT NULL,
		  `ct_rank` int(11) NOT NULL,
		  `ct_mb_nick` varchar(255) NOT NULL,
		  `ct_reply_datetime` datetime NOT NULL,
		  `ct_reply_mb_no` varchar(255) NOT NULL,
		  `ct_reply` int(11) NOT NULL,
		  `ct_update_datetime` datetime NOT NULL,
		  `ct_delete` int(1) NOT NULL,
		  `ct_secret` int(1) NOT NULL,
		   PRIMARY KEY (`ct_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		sql_query($sql);

	}

	alert($msg,$move);
}

include_once "head.php";



 
$handle  = opendir($nfor[skin_path]);
$files = array();
while (false !== ($filename = readdir($handle))) {
    if($filename == "." || $filename == ".." || substr($filename,0,11)<>"board_list."){
        continue;
    }
    if(is_file($nfor[skin_path] . "/" . $filename)){
        $files[] = substr($filename,11,-4);
    }
}
closedir($handle);
sort($files);
$admin[bo_skin][""] = "선택";
foreach ($files as $f) {
	$admin[bo_skin][$f] = $f;
}




?>

<form name="fwrite" method="post" onsubmit="return fsubmit(this)">
<?=admin_hidden($write,"mode")?>
<?=admin_hidden($write,$id)?>



<table class="table cols_tbl margin0">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>게시판코드</th> 
	<td><?=admin_text($write,"bo_tbl","width-150p",$write[bo_id]?"readonly":"")?></td>
</tr>
<tr>
	<th>게시판명</th> 
	<td><?=admin_text($write,"bo_name","width-200p")?></td>
</tr>
<tr>
	<th>노출여부</th> 
	<td>
	<?php
	if(!$write[bo_use]) $write[bo_use] = "1";
	?>
	<?=admin_radio($write,"bo_use")?>
	</td>
</tr>
<tr>
	<th>스킨</th> 
	<td><?=admin_select($write,"bo_skin","width-150p","","0")?></td>
</tr>


	

<tr>
	<th>스크롤/페이징 선택</th> 
	<td>
	<div class="form-inline">
	PC <?=admin_select($write,"bo_pc_scroll","width-100p")?>
	모바일 <?=admin_select($write,"bo_mobile_scroll","width-100p")?>
	</div>
	</td>
</tr>
<tr>
	<th>한페이지당 표시수</th> 
	<td>
	<div class="form-inline">
	PC <?=admin_text($write,"bo_pc_rows","width-50p")?>
	모바일 <?=admin_text($write,"bo_mobile_rows","width-50p")?>
	</div>
	</td>
</tr>

<tr>
	<th>정렬순위</th> 
	<td><?=admin_text($write,"bo_rank","width-50p")?></td>
</tr>
<tr>
	<th>상단 공통적용 파일(PC)</th> 
	<td><?=admin_text($write,"bo_include_head")?></td>
</tr>
<tr>
	<th>하단 공통적용 파일(PC)</th> 
	<td><?=admin_text($write,"bo_include_tail")?></td>
</tr>

<tr>
	<th>상단 공통적용 파일(모바일)</th> 
	<td><?=admin_text($write,"bo_include_mobile_head")?></td>
</tr>
<tr>
	<th>하단 공통적용 파일(모바일)</th> 
	<td><?=admin_text($write,"bo_include_mobile_tail")?></td>
</tr>
<tr>
	<th>상단HTML</th> 
	<td><?=admin_editor($write,"bo_html_head")?></td>
</tr>
<tr>
	<th>하단HTML</th> 
	<td><?=admin_editor($write,"bo_html_tail")?></td>
</tr>
</table>

<div class="bottom_btn">
	<div class="form-inline">
		<?=admin_submit("fsubmit_btn", $write[$id]?"수정하기":"등록하기", "btn btn-lg btn-red")?>
		<?=admin_a("list", "목록보기", "btn btn-lg btn-black", "", $list."?".$qstr)?>
	</div>
</div>

</form>

<SCRIPT LANGUAGE="JavaScript">
<!--
function fsubmit(f){
	if(!$('#bo_tbl').val()){
		alert("게시판코드를 입력해주세요");
		$('#bo_tbl').focus();
        return false;
	}
	if(!$('#bo_name').val()){
		alert("게시판명을 입력해주세요");
		$('#bo_name').focus();
        return false;
	}
	<?=admin_editor_update("bo_html_head")?>
	<?=admin_editor_update("bo_html_tail")?>
	f.action = "<?=$form?>";
	return true;	    
}	
//-->
</SCRIPT>

<?php
include_once "tail.php";
?>