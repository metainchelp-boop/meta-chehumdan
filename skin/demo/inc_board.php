<style>
.cus_leftmenu{ float:left; width:248px; }
.cus_leftmenu ul{margin-top:10px;}
.cus_leftmenu li {height:53px; line-height:53px;background-color:#FFFFFF; border-bottom:solid 1px #efefef; border-top:solid 1px #efefef; position:relative; }
.cus_leftmenu li + li {height:53px; line-height:53px;background-color:#FFFFFF; border-bottom:solid 1px #efefef; border-top:solid 0px #efefef;}

.cus_leftmenu li:hover { color:#FFFFFF;}
.cus_leftmenu li a{ display:block; width:100%; padding-left:10px; color:#000; font-size:15px; letter-spacing:-1px;}
.cus_leftmenu li a:hover{ color:#ff3478; background-color:#fafafa;}
.cus_leftmenu li.on{background-color:#efefef; color:#ff3478; }
.cus_leftmenu .on a{color:#ff3478;}
.cus_num{position: absolute; right: 20px; bottom: 0px; font-family:Montserrat;letter-spacing: -0.02em !important; color:#000;}
.cus_leftmenu li.on .cus_num{position: absolute; right: 20px; bottom: 0px; font-family:Montserrat;letter-spacing: -0.02em !important; color:#ff3478;}
.cus_leftmenu li a:hover .cus_num{position: absolute; right: 20px; bottom: 0px; font-family:Montserrat;letter-spacing: -0.02em !important; color:#ff3478;}
</style>
<div class="cus_leftmenu">

	<?php
	include_once $nfor[skin_path]."inc_photo.php";
	?>

	<ul>
		<?php
		for($i=0; $i<count($tbl_list); $i++){
		?>
		<li <?=$tbl==$tbl_list[$i][bo_tbl]?"class='on'":""?>><a href="board_list.php?tbl=<?=$tbl_list[$i][bo_tbl]?>"><?=$tbl_list[$i][bo_name]?></a></li>
		<?php } ?>
	</ul>

</div>