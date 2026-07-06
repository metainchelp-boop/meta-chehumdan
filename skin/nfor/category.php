<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.category_top{background: #fff;     min-height: auto;}
.category_top li a img{position: absolute; top: 50%;left: 50%;width: 40px; height: auto;  -webkit-transform: translate(-50%,-92%); transform: translate(-50%,-92%);}/*left와 탑을 absolute에서 지정한만큼뺌*/
.category_top li a em{position: absolute;top: 50%; left: 50%; white-space: nowrap; -webkit-transform: translate(-50%,100%);  transform: translate(-50%,100%); font-style: normal}
.category_top ul{overflow:hidden; }
.category_top ul li{width: 25%; float: left; position: relative;}
.category_top ul li a{display: block; box-sizing: border-box;margin-left: -1px; padding-top:100%; border-bottom: 1px #ebedef solid;border-left: 1px #ebedef solid;font-size: 12px; line-height: 12px;  color: #16181a;  text-align: center;}

.category_bottom{margin:7px;}
.category_bottom ul{overflow:hidden; }
.category_bottom li{float: left;width: 50%;}
.category_bottom li a{display: block;position: relative; height: 57px; margin: 3px; padding-left: 15px; background: #fff; font-size: 13px; line-height: 57px;}
.category_bottom li a:after{display: block; position: absolute;top: 50%;right: 17px;width: 7px;height: 7px;margin-top: -3px;border-right: 1px solid;border-bottom: 1px solid;-webkit-transform: rotate(315deg);transform: rotate(315deg); content: ''; } /*화살표*/
</style>

<div class="category_top">
	<ul>
		<?php
		for($i=0; $i<count($return["category_top"]); $i++){
			$category = $return["category_top"][$i];
		?>
		<li>
			<a href="<?=$category[category_href]?>"> 
			<img src="<?=$category[category_icon]?>">
			<em><?=$category[category_name]?></em>
			</a>
		</li>
		<? } ?>
	</ul>
</div>

<div class="category_bottom">
	<ul>
		<?php
		for($i=0; $i<count($return["category_bottom"]); $i++){
			$category = $return["category_bottom"][$i];
		?>
		<li><a href="<?=$category[category_href]?>"><?=$category[category_name]?></a></li>
		<? } ?>
	</ul>
</div>

<?php
include_once $nfor[skin_path]."tail.php";
?>