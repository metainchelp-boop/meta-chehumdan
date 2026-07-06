<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.area_list_wrap{ background-color:#f6f6f6; padding:30px 30px; min-height:500px; }

.area_top{border:solid 1px  #f0f1f2; padding:10px; background-color:#ffffff; color:#888;}
.area_top .arrow{display:inline-block; width:15px; height:15px; background:url(/skin/nfor/img/arrow4.png)center no-repeat; background-size:auto 15px; vertical-align:-2px; }
.area_top .on{color:#000; font-weight:600;}

.area_szone{ margin-top:10px;}
.area_table{table-layout:fixed;width:100%;font-size:16px; overflow:hidden; border-left:1px solid #f0f1f2; border-top:1px solid #f0f1f2;} 
.area_table .area_cell{float:left; width:33.3%;height:46px; background-color:#ffffff; border-bottom:1px solid #f0f1f2;border-right:1px solid #f0f1f2;text-align:center;letter-spacing:-1px;box-sizing: border-box; -webkit-box-sizing: border-box;}
.area_table .area_cell:before{content:'';display:inline-block;height:100%;vertical-align:middle}
.area_table .area_inner{display:inline-block;vertical-align:middle;width:100%;}
.area_table .area_inner.is-selected{color:#26a93a}
</style>

<div class="area_list_wrap">

	<!-- <div class="area_top">
		<span class="on">서울시</span><b class="arrow"></b><span>시/군/구</span><b class="arrow"></b><span>읍/면/동</span>
	</div> -->

	<div class="area_szone">
		<ul class="area_table">
			<?php
			foreach ($admin[area_category] as $value) {
			?>
			<li class="area_cell"><a href="area_list.php" class="area_inner"><?=$value?></a></li>
			<?php } ?>
		</ul>
	</div>
</div>

<?php
include_once $nfor[skin_path]."tail.php";
?>