<style>

#mainwidechg {width:1004px; padding:0 0 0px; margin:0 auto; }
#mainwidechg .main_visual {height:508px; margin:0px 0px 0px; position:relative; border-bottom:1px solid #dddddd; overflow:hidden;}
#mainwidechg .main_visual .bx-slider {}
#mainwidechg .main_visual .bx-slider li {}
#mainwidechg .main_visual .bx-slider li a {display:block; text-align:center;}
#mainwidechg .main_visual .bx-slider li a img {height:508px;}
#mainwidechg .main_visual .bx-custom-pager {display:table; table-layout:fixed; width:965px; position:absolute; left:50%; bottom:0; margin:0 0 0 -502px;}
#mainwidechg .main_visual .bx-custom-pager li {display:table-cell;}
#mainwidechg .main_visual .bx-custom-pager li a {display:block; height:40px; margin:0 0 0 1px; background:url(<?=$nfor[skin_path]?>/img/bn_fff.png) 0 0 repeat; font-size:14px; line-height:40px; color:#222222; text-align:center; position:relative; transition:all .5s;}
#mainwidechg .main_visual .bx-custom-pager li:first-child a {margin:0;}
#mainwidechg .main_visual .bx-custom-pager li a.active:before {display:block; content:''; width:100%; height:2px; position:absolute; top:0; left:0; transition:all .5s;}
#mainwidechg .main_visual .bx-custom-pager li a.active {background:#22b300; color:#ffffff;}
#mainwidechg .main_visual .bx-custom-pager li a.active:before {background:#383838;}

#mainwidechg .main_visual .bx-stop {display:block; width:39px; height:40px; position:absolute; left:50%; bottom:17px; margin:0 0 0 462px; background:url(<?=$nfor[skin_path]?>/img/bn_stop.png) 0 0 no-repeat; text-indent:-9999px;}
#mainwidechg .main_visual .bx-start {display:block; width:39px; height:40px; position:absolute; left:50%; bottom:17px; margin:0 0 0 462px; background:url(<?=$nfor[skin_path]?>/img/bn_play.png) 0 0 no-repeat; text-indent:-9999px;}

</style>

<script type="text/javascript">
<!--
$(function(){
	var amain_banner = $('#main_visual .bx-slider').bxSlider({
		auto : true,
		pause : 6000,
		controls : false,
		speed : 800,
		easing : 'easeInOutExpo',
		pagerCustom : '#main_visual .bx-custom-pager',
		autoControls : true,
		autoControlsCombine : true
	});
});
//-->
</script>


<div id="mainwidechg">



<div id="main_visual" class="main_visual">
<!-- 메인비주얼 이미지 -->
<ul class="bx-slider">
		<?php
		$bn_code = "pc_wide_banner";
		$sql = "select * from nfor_banner where bn_use='1' and bn_code='$bn_code' and ((bn_period_use='1') or (bn_period_use='2' and bn_sdatetime <='$nfor[ymdhis]' and bn_edatetime >='$nfor[ymdhis]')) order by bn_rank desc";
		$que = sql_query($sql);
		while($banner=sql_fetch_array($que)){	
		?>
		<li><a href="<?=$banner[bn_href]?>" target="<?=$banner[bn_target]?>"><img src="<?="$nfor[path]/data/banner/$banner[bn_img]"?>" alt="스마트용품" /></a></li>
		<? } ?>
		</ul>

	<!-- 메인비주얼 텍스트 -->
	<ul class="bx-custom-pager">
		<?php
		$i = 0;
		$bn_code = "pc_wide_banner";
		$sql = "select * from nfor_banner where bn_use='1' and bn_code='$bn_code' and ((bn_period_use='1') or (bn_period_use='2' and bn_sdatetime <='$nfor[ymdhis]' and bn_edatetime >='$nfor[ymdhis]')) order by bn_rank desc";
		$que = sql_query($sql);
		while($banner=sql_fetch_array($que)){	
		?>
		<li><a href="<?=$banner[bn_href]?>" data-slide-index="<?=$i?>"><?=$banner[bn_alt]?></a></li>
		<?
			$i++;
		}
		?>
	</ul>
</div>





</div>