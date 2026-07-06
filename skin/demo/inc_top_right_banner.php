<div class="top_right_banner_wrap">
	<ul id="top_right_banner">
	<?
	$que = sql_query("select * from nfor_banner where wr_use='1' and wr_code='pc_top_right' and wr_sdate<='$nfor[ymdhis]' and wr_edate>='$nfor[ymdhis]' order by wr_rank asc");
	while($banner=sql_fetch_array($que)){	
	?>
	<li><a href="<?=$banner[wr_banner_link]?>" target="<?=$banner[wr_target]?>"><img src="<?="$nfor[path]/data/banner/$banner[wr_banner_img]"?>" alt=""></a></li>
	<? } ?>
	</ul>
	<span id="slider-prevt"></span>
	<span id="slider-nextt"></span>
</div>

<style>
.top_right_banner_wrap { position:relative; width:190px; height:79px; overflow:hidden; }
.top_right_banner_wrap #slider-prevt { position:absolute; top:5px; right:19px;   }
.top_right_banner_wrap #slider-nextt { position:absolute; top:5px; right:5px;  }
</style>

<script type="text/javascript">
<!--
(function($){
	$(function(){
		var top_right_banner = $('#top_right_banner').bxSlider({
		auto: true,
		autoHover: true,
		autoControls: false,
		pause: 3000,
		controls: true,
		pager: false,
		nextSelector: '#slider-nextt',
		  prevSelector: '#slider-prevt',
		  nextText: "<img src='<?=$nfor[skin_path]?>img/slider_next_btn.png'>",
		  prevText: "<img src='<?=$nfor[skin_path]?>img/slider_prev_btn.png'>"
		});
	});
}(jQuery))
//-->
</script>