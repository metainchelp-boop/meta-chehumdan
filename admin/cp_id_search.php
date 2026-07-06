<?php
include_once "path.php";

$qstr .= "&return_id=$return_id";

include_once "inc_campaign_head.php";
include_once "inc_campaign_tail.php";
include_once "html_head.php";
?>

<style>
.sns_icon_wrap { margin-top:5px; }
.blog_icon { background-color:#fff; border:solid 1px #2ba406; color:#2ba406; margin:0px 1px; padding:1px 2px; font-size:9px; }
.instagram_icon { background-color:#fff; border:solid 1px #ff3478; color:#ff3478; margin:0px 1px; padding:1px 2px; font-size:9px; }
.youtube_icon { background-color:#fff; border:solid 1px #f41515; color:#f41515; margin:0px 1px; padding:1px 2px; font-size:9px; }
.shop_icon { background-color:#fff; border:solid 1px #3987ff; color:#3987ff; margin:0px 1px; padding:1px 2px; font-size:9px; }
</style>

<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name="return_id" value="<?=$return_id?>">
<table class="table cols_tbl">
<colgroup>
	<col class="width-100p">
	<col >
	<col class="width-100p">
	<col >
</colgroup>
<tr>
	<th>검색어</th>
	<td colspan="3">
		<div class="form-inline">
		<?=admin_select($_GET,"keyword_type","","","0")?>
		<?=admin_text($_GET,"keyword","","maxlength=\"30\"")?>
		</div>
	</td>
</tr>
<tr>
	<th>기간검색</th>
	<td colspan="3">
	<div class="form-inline">
		<?=admin_select($_GET,"period_type","","","0")?>
		<?=admin_text($_GET,"period_sdate","width-100p datepicker-here","data-language=\"ko\"")?> ~ <?=admin_text($_GET,"period_edate","width-100p datepicker-here","data-language=\"ko\"")?>
		<?=period_day_echo($_GET[period_day])?>
	</div>
	</td>
</tr>
</table>
<div class="table_btn"><div class="form-inline"><?=admin_submit("fsubmit", "검색하기", "btn-lg btn-black btn")?></div></div>
<div class="ofw martop20">
	<div class="flol">
	전체 <span class="txt_red"> <?=number_format($total_count)?></span>건 / 검색 <span class="txt_red"><?=number_format($search_count)?></span>건
	</div>
	<div class="flor">

	<? if(substr(basename($PHP_SELF),0,5) <> "layer"){ ?>
	
	<?=admin_sort($admin[sort_type],$sort)?>
	<?=admin_page_row($page_row)?>

	<? } ?>
	</div>
</div>
</form>


<form name="flist" id="flist" method="post">
<input type="hidden" name="return_id" value="<?=$return_id?>">
<table class="table row_tbl margin0">
<tr>
	<th>대표이미지</th>
	<th>캠페인코드</th>
	<th>제목</th>
	<th>모집/신청</th>
	<th>선택</th>
</tr>
<?php
for($i=0; $row=sql_fetch_array($result); $i++){	

	$row[cp_url] = "{$nfor[path]}/campaign.php?cp_id={$row[cp_id]}";
?>
<tr>
	<td><a href="<?=$row[cp_url]?>" target="_blank"><?=admin_img("campaign",$row[cp_img],"50","50")?></a></td>
	<td><a href="<?=$row[cp_url]?>" target="_blank"><?=$row[cp_id]?></a></td>
	<td class="textleft">
		<a href="<?=$row[cp_url]?>" target="_blank"><?=$row[cp_subject]?><br><?=$row[cp_description]?></a>
		<div class="sns_icon_wrap">
		<? if($row[cp_media_blog]){ ?><span class="blog_icon">블로그</span><? } ?>
		<? if($row[cp_media_instagram]){ ?><span class="instagram_icon">인스타그램</span><? } ?>
		<? if($row[cp_media_youtube]){ ?><span class="youtube_icon">유튜브</span><? } ?>
		<? if($row[cp_media_shop]){ ?><span class="shop_icon">쇼핑몰</span><? } ?>
		</div>
		<br><?=$row[cp_supply_no]?admin_echo($row,"cp_supply_no"):""?>
	</td>
	<td><?=number_format($row[cp_recruit])?>명/<?=number_format($row[cp_order])?>명</td>
	<td><?=admin_a("select_cp_id", "선택", "btn btn-white btn-sm nfor_button", "data-cp_id=\"{$row[$id]}\"")?></td>
</tr>
<?php
}
$pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");
?>
</table>

<div class="bottom_btn">

	<div class="form-inline">
	<?=admin_button("list_asign1", "선택하기", "btn btn-lg btn-red")?>
	</div>

</div>
<div class="table_btn"><?=$pagelist?></div>
</form>

<script>
$(document).on("change", "#sort, #page_row", function(){
	$('#fsearch').submit();
});
$(document).on("click", "#select_cp_id", function(){
	var cp_id = $(this).data("cp_id");
	opener.document.getElementById("<?=$return_id?>").value=cp_id;
	window.close();
});
</script>

<?php
include_once "html_tail.php";
?>