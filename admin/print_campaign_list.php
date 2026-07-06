<?php
include_once "path.php";
include_once "inc_campaign_head.php";

if($mode=="list_update"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];

		sql_query("update $table set cp_use='{$_POST['cp_use'][$k]}',
										cp_rank1='{$_POST['cp_rank1'][$k]}',
										cp_rank2='{$_POST['cp_rank2'][$k]}',
										cp_rank3='{$_POST['cp_rank3'][$k]}',
										cp_rank4='{$_POST['cp_rank4'][$k]}',
										cp_rank5='{$_POST['cp_rank5'][$k]}'
										where cp_id='{$_POST['cp_id'][$k]}'");



	}

	json_return("정상적으로 수정되었습니다","ok");
}

include_once "inc_campaign_tail.php";
include_once "head.php";
include_once "inc_campaign_search.php";
?>

<form name="flist" id="flist" method="post">
<?=admin_hidden($hidden,"ex_id")?>
<?=admin_get()?>
<table class="table row_tbl margin0">
<tr>
	<th><?=admin_checkbox($row,"chkall")?></th>
	<th>대표이미지</th>
	<th>캠페인코드</th>
	<th>제목</th>
	<th>승인상태</th>
	<th>노출상태</th>
	<th>모집/신청</th>

	<th>추천캠페인</th>
	<th>여분필드</th>
	<th>여분필드</th>
	<th>여분필드</th>
	<th>여분필드</th>
</tr>
<?php
for($i=0; $row=sql_fetch_array($result); $i++){	
	$row["chk[]"] = $i;
	$row["{$id}[{$i}]"] = $row[$id];

	$admin["cp_use[$i]"] = $admin[cp_use];	
	$row["cp_use[$i]"] = $row[cp_use];	

	$row["cp_rank1[$i]"] = $row[cp_rank1];
	$row["cp_rank2[$i]"] = $row[cp_rank2];
	$row["cp_rank3[$i]"] = $row[cp_rank3];
	$row["cp_rank4[$i]"] = $row[cp_rank4];
	$row["cp_rank5[$i]"] = $row[cp_rank5];

	$row[cp_url] = "{$nfor[path]}/campaign.php?cp_id={$row[cp_id]}";
?>
<tr>
	<td><?=admin_checkbox($row,"chk[]","chk")?><?=admin_hidden($row,"{$id}[{$i}]")?></td>
	<td><a href="<?=$row[cp_url]?>" target="_blank"><?=admin_img("campaign",$row[cp_img],"50","50")?></a></td>
	<td><a href="<?=$row[cp_url]?>" target="_blank"><?=$row[cp_id]?></a></td>
	<td class="textleft">
		<a href="<?=$row[cp_url]?>" target="_blank"><?=$row[cp_subject]?><br><?=$row[cp_description]?></a>
		<div class="sns_icon_wrap">
		<? if($row[cp_media_blog]){ ?><span class="blog_icon">블로그</span><? } ?>
		<? if($row[cp_media_instagram]){ ?><span class="instagram_icon">인스타그램</span><? } ?>
		<? if($row[cp_media_youtube]){ ?><span class="youtube_icon">유튜브</span><? } ?>
		</div>
		<br><?=$row[cp_supply_no]?admin_echo($row,"cp_supply_no"):""?>
	</td>
	<td><?=admin_echo($row,"cp_asign")?></td>
	<td><?=admin_select($row,"cp_use[$i]","width-80p")?></td>
	<td><?=number_format($row[cp_recruit])?>명/<?=number_format($row[cp_order])?>명</td>
	<td><?=admin_text($row,"cp_rank1[$i]","width-50p")?></td>
	<td><?=admin_text($row,"cp_rank2[$i]","width-50p")?></td>
	<td><?=admin_text($row,"cp_rank3[$i]","width-50p")?></td>
	<td><?=admin_text($row,"cp_rank4[$i]","width-50p")?></td>
	<td><?=admin_text($row,"cp_rank5[$i]","width-50p")?></td>
</tr>
<?php
}
$pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");
?>
</table>

<div class="bottom_btn">

	<div class="form-inline">
	<?=admin_button("list_update", "선택수정", "btn btn-lg btn-red")?>
	</div>

</div>

<div class="table_btn"><?=$pagelist?></div>

</form>

<script type="text/javascript">
<!--
$(document).on("click", "#chkall", function(){
	nfor_chk_all(this, 'chk');
});
$(document).on("click", "#list_update", function(){
	nfor_list_reload('수정','list_update');
});
//-->
</script>

<?php
include_once "tail.php";
?>