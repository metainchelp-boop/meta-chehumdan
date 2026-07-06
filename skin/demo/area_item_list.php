<?php
include_once $nfor[skin_path]."head.php";
?>

<style> 
.line{border-top:solid 1px #DCDCDC; height:0px; }
.top_cate_menu2{overflow:hidden; position:relative;  padding-top:10px; margin-bottom:50px;}

.top_cate_menu2 .navi{ overflow:hidden; margin-left:0px;}
.top_cate_menu2 .navi li{float:left; padding: 0px 0px;}
.top_cate_menu2 .navi li{ float: left; position: relative; width: 20%; height: 28px; padding: 1px 0 2px;}
.top_cate_menu2 .navi li a {display: block;  overflow: hidden;  height: 28px;  padding: 0 9px 0 19px; font-size: 14px;  font-family: "나눔고딕",NanumGothic,"Nanum Gothic",돋움,dotum,Apple SD Gothic Neo,sans-serif; line-height: 28px; color: #16181a; white-space: nowrap;}
.top_cate_menu2 .navi li span { display: inline-block; height: 28px;}
.top_cate_menu2 .navi li.on span {padding: 0 9px;background: #e83862; color: #fff;}
.top_cate_menu2 .navi li .on{background-color:#000;}
.top_cate_menu2 [class^=bg].bg1 {left: 20%;}
.top_cate_menu2 [class^=bg].bg2 {right: 20%;}
.top_cate_menu2 [class^=bg].bg3 { display:none;}
.top_cate_menu2 [class^=bg] { position: absolute;  top: 1px; bottom: 2px; width: 20%; border-right: 1px solid #e3e5e8; border-left: 1px solid #e3e5e8;}
</style>


<div class="item_list">


	<div class="subpath">	
		<a href="index.php"><img src="<?=$nfor[skin_path]?>img/homenavbtn.png" alt="홈">&nbsp;&nbsp; 홈</a>
		<?php
		echo "<img src=\"".$nfor[skin_path]."img/arrow.png\">"; 
		echo "<a href=\"$PHP_SELF?area_id=".$navi[category_id][0]."\">".$navi[cg_category][0]."</a>";

		for($i=1; $i < count($navi[category_id]); $i++){
			echo "<img src=\"".$nfor[skin_path]."img/arrow.png\">"; 
			echo "<a href=\"$PHP_SELF?area_id=".$navi[category_id][$i]."\">".$navi[cg_category][$i]."</a>";
		}
		?>
	</div>

	

	<h3 style="text-align:center;margin: 30px; font-size:30px; font-family:'NanumGothicBold';"><?=$navi[category]?></h3>
	
	<hr class="line"></hr>

	<div class="top_cate_menu2">

		<i class="bg1"></i>
		<i class="bg2"></i>
		<i class="bg3"></i>

		<?
		$cg_depth = strlen($area_id)/3;
		$sql = "select * from nfor_area_category where cg_use='1' and cg_depth='$cg_depth'";
		if($area_id) $sql .= " and category_id like '$area_id%'";
		$sql .= " order by cg_rank asc";
		$que = sql_query($sql);
		if(sql_num_rows($que)){
		?>
		<ul class="navi">
			<? if(substr($area_id,0,-3)){ ?><li class="<?=$area_id?"on":""?>"><a href="<?=$PHP_SELF?>?area_id=<?=$area_id?>"><span class="txt">전체보기</span></a></li><? } ?>
			<?
			while($data = sql_fetch_array($que)){
			?>
			<li class="<?=$data[category_id]==$area_id?"on":""?>"><a href="<?=$PHP_SELF?>?area_id=<?=$data[category_id]?>"><span class="txt"><?=$data[cg_category]?></span></a></li>
			<? } ?>
		</ul>
		<? } ?>

	</div>
	<!-- //서브분류 -->


	<?=$area[cg_category_head]?>

	<!-- 정렬 -->
	<div class="title_zone">

		<div class="besttit fotr"><?=$navi[category]?> 전체상품</div>
		<ul class="select_orderby subcete">
			<? foreach($admin[orderby_text] as $key => $value){ ?>	
			<li><a data-orderby="<?=$key?>" data-orderby_text="<?=$value?>" <? if($orderby==$key){ ?>class="on"<? } ?>><?=$value?></a></li>
			<? } ?>
		</ul>

	</div>
	<!-- //정렬 -->

	<!-- 상품리스트 -->
	<div class="item_list_wrap"><?php include_once $nfor[skin_path]."inc_item_list.php"; ?></div>
	<!-- //상품리스트 -->

</div>

<script>
$(document).on("click", ".select_orderby a", function(){
	location.href="<?="$PHP_SELF?area_id=$area_id&orderby="?>" + $(this).data("orderby");	
});
</script>


<?=$area[cg_category_tail]?>

<?php
include_once $nfor[skin_path]."tail.php";
?>