<?php
if($apply_good){

	$banner = sql_fetch("select * from nfor_banner where bn_use='1' and bn_code='index_mini' and ((bn_period_use='1') or (bn_period_use='2' and bn_sdatetime <='$nfor[ymdhis]' and bn_edatetime >='$nfor[ymdhis]')) order by bn_rank desc");	

	if($banner[bn_img]){

		if(!$_COOKIE[top_banner]){
?>
<style>
.top_banner{background:url('<?="$nfor[path]/data/banner/$banner[bn_img_over]"?>')center; text-align:center;}
</style>
<!-- top_banner -->
<div class="top_banner">
	<div style="margin:0px auto; width:1443px;">
		

		<a href="<?=$banner[bn_href]?>" target="<?=$banner[bn_target]?>" class="banner"><img src="<?="$nfor[path]/data/banner/$banner[bn_img]"?>" alt=""></a>


		<img  src="<?="$nfor[path]/data/banner/$banner[bn_img_hover]"?>" alt="닫기" class="btn_close top_banner_close" style="margin-top:10px; margin-right:20px;"> 
	</div>
</div>
<!-- //topbanner -->

<script type="text/javascript">
<!--
$(document).on("click", ".top_banner_close", function(){
	nfor_set_cookie('top_banner','1');
	$(".top_banner").slideUp("normal");
});
//-->
</script>
<? 
		} 
	}

}
?>