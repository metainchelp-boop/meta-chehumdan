<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.today_bg{background:url(/skin/demo/img/new_bg.png); height:234px; margin-bottom:20px;}
.today_bg .tit{ font-size: 16px;  padding-left:20px;  letter-spacing:-1px; text-align:center; margin-top:10px;}
.today_bg .sub_tit{font-size:30px; text-align:center; padding-top:70px;} 
.today_bg .sub_tit b{color:#e61b62;}
.today_bg .sub_tit span{color:#2f6291;}
.today_list .sicon{display: block;position: relative; margin-bottom: 20px; }
.today_list .sicon img{height:35px; display:block; margin:5px auto;}
.today_list .sicon ul{overflow:hidden; margin: 0 0px; padding-top: 15px;}
.today_list .sicon ul li{float: left;  position: relative; width: 10%; margin-bottom: 15px; text-align: center;margin-top: -1px; }
.today_list .sicon ul li:nth-child(1){margin-left:0px;}
.today_list .sicon ul li a.on{border:solid 1px #e83862; color:#e83862;}
.today_list .sicon ul li a{display: block; font-size: 12px;line-height: 17px;color: #16181a; letter-spacing:-0px; padding: 20px;  background-color:#FFF;  border: 1px solid #f0f2f5; }
.today_list .sicon ul li a:hover{color:#e83862; }
</style>
<div class="today_bg">
	
	<div class="sub_tit fotm"><b><?=date("m")?></b>월 <b><?=date("d")?></b>일 <span>오픈</span>하는 <b>신규상품</b>을 만나보세요</div>
	<div class="tit fotl">매일 매일 기대되는 프라이스박스 신규상품</div>
</div>
<div class="today_list layout_inner">

	<div class="sicon">
		<ul>
			<?php
			foreach($admin[category_id] as $key => $value){
			?>
			<li><a <?php if(!$scroll_load){ ?>href="<?=$PHP_SELF?>?category_id=<?=$key?>"<?php } ?> data-category_id="<?=$key?>" <?=$key==$category_id?"class=\"on\"":""?>><img src="<?=$admin[category_img][$key]?>"><?=$value?></a></li>
			<? } ?>
		</ul>
	</div>

	<div class="item_list_wrap"><?php include_once $nfor[skin_path]."inc_item_list.php"; ?></div>

</div>

<?php if($scroll_load){ ?>
<script>
$(document).on("click",".sicon li a",function(){
	$(".sicon li a").removeClass("on");
	$(this).addClass("on");
	category_id = $(this).data("category_id");
	page = 1;
	is_last = 0;

	item_list_load();
});
</script>
<?php } ?>

<?php
include_once $nfor[skin_path]."tail.php";
?>