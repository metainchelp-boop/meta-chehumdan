<?php
include_once $nfor[path]."/html_head.php";
?>
<style>
@font-face {
	font-family: 'NSKL';
	src: /*url('/skin/demo/fonts/NotoSansKR-Light-Hestia.woff2') format('woff2'),*/
	   url('/skin/demo/fonts/NotoSansKR-Light-Hestia.woff') format('woff'),
	   url('/skin/demo/fonts/NotoSansKR-Light-Hestia.otf') format('opentype');
}

body{margin:0px; padding:0px;}
.main_loding{background-color:#000; opacity:0.6; width:100%; min-height:960px; text-align:center; padding:350px 0px;}
.main_loding .text1{color:#FFF; display:block; margin:30px; font-family:'NSKL';}
</style>
<div class="main_loding">
	<img src="/skin/demo/img/loding_page2.gif">
	<span class="text1 fotm">
		현재 결재중입니다. 창을 닫지말아주세요
	 </span>
</div>
<?php
include_once $nfor[path]."/html_tail.php";
?>