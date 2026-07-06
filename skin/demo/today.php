<?php
if($member[mb_no]){
	$zzim = sql_fetch("select count(*) as cnt from nfor_zzim where zz_mb_no='$member[mb_no]'");
}
?>
<style>
.today_view { margin-top: 0px; position: relative; z-index: 1000; border:solid 1px #e3e5e8; border-top:none; width:100px; font-size:11px; background-color:#FFF;}
.today_view .today_view_li { text-align:center; border-top:solid 1px #e3e5e8;  }
.today_view .today_item{padding:15px 0px; display:block; }
.today_view .today_ico{display:block;}
.today_view .today_tit b{color:#f27935; font-family:Verdana;  padding-left:0px; vertical-align:-1px;}
.today_tit{font-size:12px; color:#7d7e80;display:block; margin-top:5px;}
.flow.today_view { left:50%; top:100px; margin-left:635px; position:fixed; }
.today_view_img { width:80px; height:80px; z-index:7; }

.recent_view_tit { font-size:12px; display:block; padding:10px;  font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;}
.today_view .recent_view_tit b{ color:#f27935; font-family:Verdana;  padding-left:0px; vertical-align:-1px;}

.recent_list_ul{min-height:150px;}
.recent_list_ul li { position:relative; margin-bottom:10px;  }
.recent_list_ul span{display:block;padding-top:50px; color:#7d7e80;}
.recent_list_ul li a { display:block; }
.top_bottom {display:none;}
.flow.top_bottom { display:block;; left:50%; bottom:50px; margin-left:875px; position:fixed; width:41px; }

.infobox { display:none; position:absolute; top:-1px; right:9px; width:146px; height:50px; padding:15px 85px 15px 15px; border:1px solid #30343b; background-color:#fff; font-size:11px; text-align:left; z-index:-1; }
.infobox .it_name{padding:0px; font-size:12px; margin-bottom:10px; color:#666;text-overflow: ellipsis; -webkit-line-clamp:2; -webkit-box-orient: vertical; height:28px; overflow:hidden; width:148px;}
</style>

<div class="today_view">
	<ul>
		<li class="today_view_li">
			<a href="zzim_list.php" class="today_item">
				<span class="today_ico"><img src="/skin/demo/img/today_zzim2.png"></span>
				<span class="today_tit">찜한상품<? if(!$member[mb_no]){ ?><? } else{ ?><b><?=$zzim[cnt]?></b><? } ?></span> 		
			</a>
		</li>
		<li class="today_view_li">
			<a href="cart.php" class="today_item">
				<span class="today_ico"><img src="/skin/demo/img/today_zzim.png"></span>
				<span class="today_tit">장바구니 <b><?=cart_cnt($_SESSION[cart_id])?></b></span> 		
			</a>
		</li>
		<li class="today_view_li">
			<?php
			if(!$member[mb_no]) $iv_guest_no = $_SESSION[guest_no];
			$sql = "select count(*) as cnt from nfor_item_view where 1 ";
			if($member[mb_no]){
				$sql .= " and iv_mb_no = '$member[mb_no]' ";
			} else{
				$sql .= " and iv_guest_no = '$iv_guest_no' ";
			}
			$recent = sql_fetch($sql);
			?>
			<a href="recent_list.php" class="recent_view_tit">최근본상품<b><?=number_format($recent[cnt])?></b></a>
			<ul class="recent_list_ul">
			<?php			
			$sql = "select * from nfor_item_view a left join nfor_item b on ( a.iv_it_id = b.it_id ) where 1 ";
			if($member[mb_no]){
				$sql .= " and a.iv_mb_no = '$member[mb_no]' ";
			} else{
				$sql .= " and a.iv_guest_no = '$iv_guest_no' ";
			}
			$sql .= " order by iv_id desc limit 4";
			$que = sql_query($sql);
			while($row = sql_fetch_array($que)){
			?>
				<li>
					<a href="item.php?it_id=<?=$row[it_id]?>">
						<img src="<?=thumbnail("$nfor[path]/data/list/$row[it_img]",80,80,0,1)?>" class="today_view_img">						
						<div class="infobox">
							<span class="it_name"><?=$row[it_name]?></span>
							<p><b><?=number_format($row[it_price2])?></b>원</p>
						</div>
					</a>
				</li>
			<? } ?>
			</ul>
		</li>
	</ul>
</div>

<div class="top_bottom">
	<a href="javascript:scroll_move('top')" class="scrollbtn"> <img src="/skin/demo/img/top.png"></a>
	<a href="javascript:scroll_move('bottom')" class="scrollbtn"><img src="/skin/demo/img/bown.png"></a>
</div>	

<script>
$(function(){
  var hover1 = $('.today_view_li li');
  hover1.hover(function(){
    $(this).find(".infobox").show();
  },function(){
    $(this).find(".infobox").hide();
  });
});

$(window).scroll( function(){
	var scrollTop = $(document).scrollTop();
	var rightbannerBottom = $('#right_banner').position().top + $('#right_banner').height() + 200;
	if(scrollTop >= rightbannerBottom){
		$('.today_view').attr('class','today_view flow');
	} else if(scrollTop < rightbannerBottom){
		$('.today_view').attr('class','today_view');
	} else{

	}
});	
</script>