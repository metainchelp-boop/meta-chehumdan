<?php
include_once "path.php";

$form = $_SERVER[PHP_SELF];
$list = str_replace("form","list",$form);
$table = "nfor_review";
$id = "rv_id";

$id_value = $$id;

if($id_value){
	$write = sql_fetch("select * from $table where $id='{$id_value}'");
}

if($mode=="update"){

	$add_sql = "";
	$add_sql .= admin_upload($write,"rv_img","review","$table"," where $id='{$id_value}'");

	if($write[rv_step]=="2" and $rv_url) $add_sql .= ", rv_step='3', rv_reg_datetime=NOW()";

	sql_query("update $table set rv_best='$rv_best', rv_review='$rv_review', rv_channel='$rv_channel', rv_msg='$rv_msg', rv_memo='$rv_memo', rv_dy_name='$rv_dy_name', rv_dy_hp='$rv_dy_hp', rv_dy_zip='$rv_dy_zip', rv_dy_addr1='$rv_dy_addr1', rv_dy_addr2='$rv_dy_addr2', rv_url='$rv_url', rv_buyer_name='$rv_buyer_name' $add_sql where $id='{$id_value}'");
	alert("수정되었습니다","$PHP_SELF?rv_id=$rv_id");
}

include_once "head.php";
?>

<form name="fwrite" method="post" onsubmit="return fsubmit(this)" enctype="multipart/form-data">
<?=admin_hidden($write,"mode")?>
<?=admin_hidden($write,$id)?>

<table class="table cols_tbl margin0">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>신청번호</th>
	<td><?=$write[rv_id]?></td>
</tr>
<tr>
	<th>회원정보</th>
	<td><?=campaign_member_info($write)?></td>
</tr>
<tr>
	<th>캠페인명</th>
	<td><?=$write[rv_cp_subject]?></td>
</tr>
<tr>
	<th>캠페인 코드</th>
	<td><?=$write[rv_cp_id]?></td>
</tr>
<tr>
	<th>채널</th>
	<td><?=admin_text($write,"rv_channel")?></td>
</tr>
<tr>
	<th>신청한마디</th>
	<td><?=admin_textarea($write,"rv_msg")?></td>
</tr>
<tr>
	<th>신청필수정보</th>
	<td><?=admin_textarea($write,"rv_memo")?></td>
</tr>
<tr>
	<th>배송지 받는분</th>
	<td><?=admin_text($write,"rv_dy_name","width-200p")?></td>
</tr>
<tr>
	<th>배송지 휴대전화</th>
	<td><?=admin_text($write,"rv_dy_hp","width-200p")?></td>
</tr>
<tr>
	<th>배송지 주소</th>
	<td>
	<div class="marbottom5"><div class="form-inline"><?=admin_text($write,"rv_dy_zip","width-80p")?> <?=admin_button("find_zipcode","우편번호찾기","btn-gray")?></div></div>
	<div class="form-inline"><?=admin_text($write,"rv_dy_addr1","width-380p")?> <?=admin_text($write,"rv_dy_addr2","width-200p")?></div>
	</td>
</tr>
<tr>
	<th>리뷰등록상태</th>
	<td><?=$write[rv_url]?"등록완료":"등록대기"?></td>
</tr>
<tr>
	<th>리뷰등록URL</th>
	<td><?=admin_text($write,"rv_url")?></td>
</tr>
<tr>
	<th>리뷰등록 한줄평</th>
	<td><?=admin_textarea($write,"rv_review")?></td>
</tr>

<tr>
	<th>리뷰등록 대표 이미지</th>
	<td colspan="3">
	<?=admin_file($write,"rv_img","review")?>
	</td>
</tr>
<?php if($write[rv_buyer_name] || $write[rv_buy_img]){ ?>
<tr>
	<th>구매자명 <span style="color:#888;font-weight:normal;">(페이백)</span></th>
	<td colspan="3">
		<?=admin_text($write,"rv_buyer_name")?>
		<?php if($write[rv_buyer_name] && trim($write[rv_buyer_name]) != trim($write[rv_mb_name])){ ?>
			<div style="margin-top:6px;color:#dc2626;font-weight:bold;">⚠️ 회원명(<?=$write[rv_mb_name]?>)과 구매자명이 다릅니다 — 본인 확인 필요</div>
		<?php } else if($write[rv_buyer_name]){ ?>
			<div style="margin-top:6px;color:#0d9488;">✓ 회원명(<?=$write[rv_mb_name]?>)과 일치</div>
		<?php } ?>
	</td>
</tr>
<tr>
	<th>구매평 캡쳐 <span style="color:#888;font-weight:normal;">(페이백)</span></th>
	<td colspan="3">
		<?php if($write[rv_buy_img]){ ?>
			<a href="<?=$nfor[path]?>/data/review/<?=$write[rv_buy_img]?>" target="_blank"><img src="<?=thumbnail($nfor[path]."/data/review/".$write[rv_buy_img],200,200,0,1)?>" style="border:1px solid #ddd;max-width:220px;"></a>
		<?php } else{ ?>미등록<?php } ?>
	</td>
</tr>
<?php } ?>
<tr>
	<th>신청일자</th>
	<td><?=substr($write[rv_datetime],0,10)?></td>
</tr>
<tr>
	<th>선정일자</th>
	<td><?=substr($write[rv_asign_datetime],0,10)?></td>
</tr>
<tr>
	<th>리뷰등록일자</th>
	<td><?=substr($write[rv_reg_datetime],0,10)?></td>
</tr>
<tr>
	<th>리뷰등록확인일자</th>
	<td><?=substr($write[rv_confirm_datetime],0,10)?></td>
</tr>

<tr>
	<th>베스트 선정리뷰</th>
	<td><?=admin_text($write,"rv_best","width-80p")?></td>
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
$(document).on("click", "#find_zipcode", function(){
	zipcode('rv_dy_zip', 'rv_dy_addr1', 'rv_dy_addr2');
});

function fsubmit(f){
	f.action = "<?=$PHP_SELF?>";
	return true;	    
}	
//-->
</SCRIPT>

<?php
include_once "tail.php";
?>