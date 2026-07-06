<?php
include_once "path.php";
$imgsrc = $_REQUEST["imgsrc"];
?>
<style>
.metatech_pop_img{
    width:100%;
    height:100%;
    object-fit:cover;
}
</style>


	<div style="text-align:center">
		<img src="<?=$imgsrc?>" class="metatech_pop_img">
	</div>