<?php
include_once $nfor[skin_path]."mypage_head.php";
?>

<style>
.wrap_star_view { margin:0px; padding:0px; width:100%; box-sizing:border-box; -webkit-box-sizing:border-box;  }
.wrap_star_view .star_view .list_item{margin-bottom:3px;  border-top: 1px solid #4f525c;  border-bottom:solid 1px #e3e5e8;}
.wrap_star_view .star_view .list_item .view_box{position: relative; border-radius: 0px; background: #fff;}
.wrap_star_view .star_view .list_item .view_box .txt{padding:15px; color:#666; font-size: 12px; line-height: 18px; border-top:solid 1px #e3e5e8; min-height:150px;}
.wrap_star_view .star_view .list_item .view_box .opt{margin:15px 15px; padding-top:15px; border-top:solid 1px #e3e5e8;}
.wrap_star_view .star_view .list_item .view_box .opt p{margin-top:5px; font-size: .7em; line-height: 1.43em; letter-spacing: -.06em; color:#888;}
.wrap_star_view .star_view .list_item .view_box .opt p:last-child {margin-bottom:20px;}
.wrap_star_view .star_view .list_item .view_box .opt img{display: inline-block; ; margin-top:10px;}
.cmt_info { white-space: nowrap; position:absolute; top:12px; left:120px}



.cmt_info .date {font-size: 0.7em; line-height: 13px; color: #959da6;padding-right: 6px; margin-right: 2px;letter-spacing: -1px;}
.wrap_star_view .star_view .list_item .view_box .btn_delete { padding: 2px 10px; vertical-align: middle; font-size: 0.7em; line-height: 13px;  letter-spacing: normal; color: #959da6; border:solid 1px #DCDCDC; background-color:#FFF;}
.wrap_star_view .star_view .list_item .view_box .btn_modify { padding: 2px 10px; vertical-align: middle; font-size: 0.7em; line-height: 13px;  letter-spacing: normal; color: #959da6; border:solid 1px #DCDCDC; background-color:#FFF;}

.star_score {position:absolute;right:25px; top:10px; height: 10px; cursor: pointer;}
.star_score .star_off{ display:inline-block; position:relative; width:87px; height:15px; background:url('<?=$nfor[skin_path]?>img/star.png'); background-repeat:no-repeat; background-position: 0px -0px;  vertical-align:middle; background-size:87px;}
.star_score .star_off .star_on {position:absolute; left:0px; top:0px; height:15px; background:url('<?=$nfor[skin_path]?>img/star.png'); background-repeat:no-repeat; background-position:0px -18.5px; background-size:87px; }
.star_score .sp_tcm {display:inline-block; position:relative; width:87px; height:15px; background:url('<?=$nfor[skin_path]?>img/star.png'); background-repeat:no-repeat; background-position: 0px -0px;  vertical-align:middle; background-size:87px;}
.st_insert_datetime{display: block; padding: 15px 15px; font-size: 11px;color: #888;vertical-align: middle;background-color:#FaFaFa;}

.btns{box-sizing:border-box; -webkit-box-sizing:border-box;  padding:10px;}
.st_img{width:100px; height:100px;}

.bigimage_wrap { display:none; width:100%; height:100%; position:fixed; top:0; right:0;	z-index:99999; }
.bigimage_back { background-color:#000; height:100%; width:100%; opacity:1; }

.swiper-container {	width:100%;	height:100%; }
.swiper-slide { text-align:center;	font-size:18px;	background:#000; display:-webkit-box; display:-ms-flexbox; display:-webkit-flex; display:flex; -webkit-box-pack:center;	-ms-flex-pack:center; -webkit-justify-content:center;	justify-content:center;	-webkit-box-align:center; -ms-flex-align:center; -webkit-align-items:center; align-items:center; }
.swiper-slide img { width:86%; }
.swiper-pagination-bullet { background:#fff; }
.swiper-pagination-bullet-active { background:#e83862; }
.swiper-pagination-fraction, .swiper-pagination-custom, .swiper-container-horizontal > .swiper-pagination-bullets { bottom:15px; }
</style>

<div class="wrap_star_view">
	<ul class="star_view">
		<li class="list_item">
		<div class="view_box">
			<p class="st_insert_datetime"><?=$star[st_insert_datetime]?></p>
			<div class="star_score">
				<span class="star_off sp_tcm">
					<span class="star_on sp_tcm" style="width:<?=$star[st_star_per]?>%"></span>
				 </span>
			</div>
			<div class="txt"><?=$star[st_memo]?></div>
			<div class="opt">
				<?php
				for($i=0; $i<count($star[st_option]); $i++){
				?>
				<p><?=$star[st_option][$i]?></p>
				<? } ?>

				<?php
				for($i=0; $i<count($star[st_image]); $i++){
				?>
				<img src="<?=$star[st_image][$i]?>" data-number="<?=$i-1?>" class="st_img">
				<? } ?>
			</div>
			<div class="cmt_info">

			</div>
		</div>
		</li>
	</ul>
	<style>
	.rep{font-size:12px; color:#666; padding:20px;   position: relative; border-bottom:solid 1px #efefef;}
	.rep .btn_re{display:inline-block; padding:3px 5px; background-color:#ff284b; color:#FFF; border-radius:5px; font-size:11px;}
	.rep .id{ display:inline-block; height:35px; color:#000;}
	.rep .date{position: absolute;  top:25px;right:25px;color:#888}
	.rep .memo{line-height:18px;}
	</style>

	<?php
	for($i=0; $i<count($star[reply]); $i++){	
		$row = $star[reply][$i];
	?>
	<div class="rep">
	<span class="btn_re"> 답변</span>
	<span class="id"><?=$row[st_mb_id]?></span>
	<span class="date"><?=$row[st_insert_datetime]?></span>
	<p class="memo"><?=$row[st_memo]?></p>
	</div>
		<?php
		}
		?>
	<div class="bottom_btn">
	<span class="btn_pack"><a href="my_item_star_list.php" class="btn_lg black">목록으로</a></span>				
	<span class="btn_pack"><a class="btn_modify btn_lg white" href="item_star_form.php?st_id=<?=$star[st_id]?>">수정</a></span>
	<span class="btn_pack"><a class="btn_delete btn_lg white" data-st_id="<?=$star[st_id]?>">삭제</a></span></div>
</div>

<div class="bigimage_wrap">

	<div class="bigimage_back">

		<div class="swiper-container">
			<div class="swiper-wrapper">
				<?php
				for($i=0; $i<count($star[st_image]); $i++){
				?>
				<div class="swiper-slide"><img src="<?=$star[st_image][$i]?>"></div>
				<? } ?>
			</div>
			<div class="swiper-pagination"></div>
		</div>

	</div>

</div>

<script>
var swiper = null
$(document).on("click", ".swiper-slide img", function (){
	$(".bigimage_wrap").hide();
	swiper.destroy();
});

$(document).on("click", ".st_img", function (){
	var number = $(this).data("number");
	$(".bigimage_wrap").show();
	swiper = new Swiper('.swiper-container', {
	  initialSlide: number,
	  loop: true,
	  pagination: {
		el: '.swiper-pagination',
	  },
	});
});

$(document).on("click", ".btn_delete", function (){
	var st_id = $(this).data("st_id");
	if(confirm("상품후기 삭제시에는 복구 및 재등록은 불가능합니다.\n정말 삭제하시겠습니까?")){
		$.ajax({
			type: "post",
			data: {
				"mode":"delete",
				"st_id":st_id
			},
			url: "item_star_form.php",
			success: function(response){
				var json = $.parseJSON(response); 
				if(json["result"]=="ok"){
					location.href="my_item_star_list.php";
				} else{
					alert(json["msg"]);
				}
			}
		});
	}
});
</script>

<?php
include_once $nfor[skin_path]."mypage_tail.php";
?>