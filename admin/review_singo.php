<?php
include_once "path.php";

$rv_id = str_number($rv_id);

if(!$member['mb_no']) alert("잘못된 접근입니다");
if(!$member['mb_admin']) alert("잘못된 접근입니다");

$review = sql_fetch("select * from nfor_review where rv_id='$rv_id'");

if($member['mb_admin']=="1" and $review['rv_supply_no']<>$member['mb_no']) alert("본인의 리뷰만 신고 가능합니다");
if($member['mb_admin']=="2" and $review['rv_md_no']<>$member['mb_no']) alert("본인의 리뷰만 신고 가능합니다");


if($mode=="insert"){

	//$chk_singo = sql_fetch("select * from nfor_review_singo where sg_rv_id='$sg_rv_id'");
	//if($chk_singo[sg_id]) alert("이미 신고된 컨텐츠입니다");

	$review = sql_fetch("select * from nfor_review where rv_id='$sg_rv_id'");

	$review[rv_cp_subject] = addslashes($review[rv_cp_subject]);

	sql_query("insert nfor_review_singo set sg_memo='$sg_memo', sg_datetime=NOW(), sg_mb_hp='$member[mb_hp]', sg_mb_no='$member[mb_no]', sg_mb_id='$member[mb_id]', sg_mb_nick='$member[mb_nick]', sg_mb_name='$member[mb_name]', sg_rv_id='$sg_rv_id', sg_rv_cp_id='$review[rv_cp_id]', sg_rv_mb_id='$review[rv_mb_id]', sg_rv_mb_name='$review[rv_mb_name]', sg_rv_mb_hp='$review[rv_mb_hp]', sg_rv_mb_email='$review[rv_mb_email]', sg_rv_mb_nick='$review[rv_mb_nick]', sg_rv_mb_no='$review[rv_mb_no]', sg_rv_cp_subject='$review[rv_cp_subject]', sg_rv_url='$review[rv_url]', sg_type='1'");
	alert_close_refresh("신고가 완료되었습니다");
}

include_once "html_head.php";
?>

<style>
#sg_memo { width:98%; height:300px; border:1px solid #d5d5d5; margin:5px 0px; }
</style>

<div class="table_wrap">

<form name="fwrite" method="post" onsubmit="return fsubmit(this)" enctype="multipart/form-data">
<?=admin_hidden($singo,"mode")?>
<?=admin_hidden($singo,$id)?>
<?php
$singo[sg_rv_id] = $_GET[rv_id];
?>
<?=admin_hidden($singo,"sg_rv_id")?>



<table class="table cols_tbl margin0">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>캠페인명</th>
	<td><?=$review[rv_cp_subject]?></td>
</tr>
<tr>
	<th>신고대상</th>
	<td><?=$review[rv_mb_nick]?$review[rv_mb_nick]:$review[rv_mb_id]?></td>
</tr>
<tr>
	<th>리뷰URL</th>
	<td><a href="<?=$review[rv_url]?>" target="_blank"><?=$review[rv_url]?></a></td>
</tr>
<tr>
	<th>신고내용</th>
	<td>
		<?=admin_textarea($singo,"sg_memo")?>
	</td>
</tr>
</table>

<div class="bottom_btn"><?=admin_submit("fsubmit_btn", "신고하기", "btn btn-lg btn-red")?></div>

</div>

</form>

<SCRIPT LANGUAGE="JavaScript">
<!--
function fsubmit(f){

	if(!$("#sg_memo").val()){
		alert("신고내용을 입력해주세요");
		$("#sg_memo").focus();
		return false;
	}

	f.action = "<?=$PHP_SELF?>";
	return true;	    
}	
//-->
</SCRIPT>


<?php
include_once "html_tail.php";
?>