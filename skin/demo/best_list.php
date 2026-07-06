<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.best_wrap .best_bg{height:234px; background:url('<?=$nfor[skin_path]?>img/best_bg.png')center}
.best_wrap .best_bg div{text-align:center;}
.best_wrap .best_bg .txt1{font-size:35px; padding-top:70px;}
.best_wrap .best_bg .txt1 b{color:#e83862;}
.best_wrap .best_bg .txt2{padding-top:10px; color:#666;}
.best_wrap .tit{font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;padding-top: 61px;padding-bottom: 32px; border-bottom: 0; font-size: 34px; text-align:center;}
.best_wrap .sicon{display: block;position: relative; margin-bottom: 20px; }
.best_wrap .sicon img{height:35px; display:block; margin:5px auto;}
.best_wrap .sicon ul{overflow:hidden; margin: 0 0px; padding-top: 15px;}
.best_wrap .sicon ul li { float:left; position:relative; width:10%; margin-bottom:15px; text-align:center; margin-top:-1px; }
.best_wrap .sicon ul li:nth-child(1) { margin-left:0px; }
.best_wrap .sicon ul li a.on { border:solid 1px #e83862; color:#e83862; }
.best_wrap .sicon ul li a { cursor:pointer; display:block; font-size:12px; line-height:17px; color:#16181a; letter-spacing:-0px; padding:20px;  background-color:#fff;  border: 1px solid #f0f2f5; }
.best_wrap .sicon ul li a:hover { color:#e83862; }
</style>

<div class="best_wrap">

	<div class="best_bg">
		<div class="fotm txt1">가장 많이 팔린 <b>베스트상품</b></div>
		<div class="fotl txt2">가장 많은 판매상품 목록을 만나보세요</div>
	</div>

	<div class="layout_inner">
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