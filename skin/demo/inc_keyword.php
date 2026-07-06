<?
$sql = "select * from nfor_item where it_paydate <='$nfor[ymdhis]' and it_payenddate >='$nfor[ymdhis]' and it_view='0' order by rand() limit 10";
?>
<div class="keyword_wrap" onmouseover="$('.keyword_popup').show()" onmouseout="$('.keyword_popup').hide()">

	
	<div class="keyword_rollin_div">
	<ul class="keyword_rollin" id="keyword_rollin">
		<?		
		$i = 1;
		$que = sql_query($sql);
		while($data = sql_fetch_array($que)){
		?>
		<li><a><em class="ranking_ico ranking_ico0<?=$i?> "></em><?=$data[it_name]?></a></li>
		<?
			$i++;	
		}		
		?>
	</ul>
	</div>

    <div class="keyword_popup">
	<b style="color:#e45072;font-size:13px;">실시간인기순위 </b> 
		<?
		$i = 1;
		$que = sql_query($sql);
		while($data = sql_fetch_array($que)){
		?>
		<a href="item.php?it_id=<?=$data[it_id]?>"><em class="ranking_ico2 ranking_ico0<?=$i?> "></em> <?=$data[it_name]?> </a>
		<?
			$i++;	
		}		
		?>
	</div> 

</div>

<script type="text/javascript">
<!--
$('#keyword_rollin').bxSlider({
	mode: 'vertical',
	auto: true,
	autoHover: true,
	pause: 2000,
	controls: false,
	pager: false
});
//-->
</script>




<style>

.keyword_wrap { position:relative; width:170px; height:35px; text-align:left; float:left;  }
.keyword_popup { display:none; position:absolute; top:-1px; left:-100px; width:286px; height:auto; background-color:#fff;  z-index:99; padding:10px; border: 1px solid #ff8590; z-index:9999; }
.keyword_popup a { display:block; line-height:18px; padding:3px; overflow:hidden; }
.keyword_rollin_div::after{clear:both;}
.keyword_rollin_div { width:185px !important; height:35px; overflow:hidden; border-right:#fff;}
.keyword_rollin li { width:185px !important;  line-height:35px; }
.keyword_rollin a { overflow:hidden; } 

.ranking_ico{ display:block; width:12px; height:11px; margin:12px 5px;background: url("<?=$nfor[skin_path]?>ranking.png"); float:left; }
.ranking_ico2{ display:block; width:12px; height:11px; margin:3px 3px;background: url("<?=$nfor[skin_path]?>ranking.png"); float:left; }
.ranking_ico01{ background-position:-0px -0px;}
.ranking_ico02{ background-position:-0px -11px }
.ranking_ico03{ background-position:-0px -22px }
.ranking_ico04{ background-position:-0px -33px }
.ranking_ico05{ background-position:-0px -44px }
.ranking_ico06{ background-position:-0px -55px }
.ranking_ico07{ background-position:-0px -66px }
.ranking_ico08{ background-position:-0px -77px }
.ranking_ico09{ background-position:-0px -88px }
.ranking_ico010{ background-position:-0px -99px }

</style>