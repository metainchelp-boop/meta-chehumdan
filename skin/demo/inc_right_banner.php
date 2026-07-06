<style>
.rightbanner { float:left; padding:<?=basename($PHP_SELF)=="index.php"?"213":"130"?>px 10px 10px 0px; }
.rightbanner ul li img { width:147px; margin-bottom:23px; }
</style>
<div class="rightbanner">

	<ul>
	<?
	$que = sql_query("select * from nfor_banner where wr_use='1' and wr_code='pc_right' and wr_sdate<='$nfor[ymdhis]' and wr_edate>='$nfor[ymdhis]' order by wr_rank asc");
	while($banner=sql_fetch_array($que)){							
	?>
	<li><a href="<?=$banner[wr_banner_link]?>" target="<?=$banner[wr_target]?>"><img src="<?="$nfor[path]/data/banner/$banner[wr_banner_img]"?>"></a></li>
	<? } ?>
	</ul>
</div>