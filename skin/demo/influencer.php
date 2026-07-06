<?php
include_once $nfor[skin_path]."head.php";
?>
<style>
.influencer_wrap{overflow:hidden; margin-top:30px;}
.top_influencer{ width:258px; position:relative;  overflow:hidden; float:left; }
.top_influencer .inner{overflow:hidden; display:block;  padding:50px 20px; border-radius:10px;  }
.top_influencer li{float:left; width:100px; }
.top_influencer li + li{ padding-top:0px;position:relative; }
.top_influencer .thumb{width:80px; margin:0px auto ;position:relative;}
.top_influencer .thumb img{width:80px; height:80px; border-radius:150px;}
.top_influencer .name{display:block; font-size:18px; color:#000; margin-bottom:5px;}
.top_influencer .name .nick{font-size:14px; color:#888; font-weight:normal;}
.top_influencer .go_note{position: absolute; right:5px; top:-20px;   display:inline-block; border:solid 1px #888; line-height:20px; border-radius:20px; width:20px; height:20px; background:url(/skin/demo/img/note_ico.png) center no-repeat ; background-position:transparent; ; background-size:12px;}

.top_influencer .sns{margin-top:15px; margin-bottom:5px;}
.top_influencer .sns a{text-indent:200px; overflow:hidden; display:inline-block; margin-right:0px;}
.top_influencer .sns .blog{background: url('<?=$nfor[skin_path]?>img/blog.png') center no-repeat; width:30px; height:30px;   background-size:30px;}
.top_influencer .sns .instagram{background: url('<?=$nfor[skin_path]?>img/instgram.png') center no-repeat; width:30px; height:30px;  background-size:30px}
.top_influencer .sns .youtube{background: url('<?=$nfor[skin_path]?>img/youtube.png') center no-repeat; width:30px; height:30px;  background-size:30px}



.tab{display:block; width:258px;margin-top:0px; overflow:hidden; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}
.tab a{float:left; display:block; width:100%;  padding-left:20px; position:relative; border:solid 1px #efefef; background-color:#fafafa; color:#666; border-left:none; border-right:none;  line-height:55px; margin-top:0px; font-size:16px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}
.tab a + a {margin-top:-1px; border:solid 1px #efefef;   border-left:none; border-right:none; }
.tab .on{color:#000; background-color:#fff;}
.tab .txt_num{ position:absolute; right:20px; top:0px; }
.content{min-height:800px; float:left; padding-left:30px; margin-top:50px;}
.content .title{font-size:24px; color:#000; }
</style>
<div class="influencer_wrap">
<div class="top_influencer">
	<div class="inner">
	<ul>
		<li>
		<div class="thumb">
			<a href="influencer.php"><img src="./data/member/mb/thumb/thumb-2009379591_Yv92pObI_20_80x80.png"></a>
		</div>

		</li>
		<li>
			<span class="name">뿌꾸엄마 </span>
			<span class="nick">widnzy90</span><a href="javascript:note()"  class="go_note "></a>
			<div id="" class="sns">
				<a class="blog">블로그</a>
				<a class="instagram">인스타그램</a>
				<a class="youtube">유튜브</a>
			</div>
			
		</li>
	</ul>
	</div>
	<div class="tab">
	<a href="" class="on">참여한캠페인<b class="txt_num">10</b></a>
	<a href="">리뷰어 선정<b class="txt_num">10</b></a>
	<a href="">작성 리뷰<b class="txt_num">10</b></a>
	<a href="">베스트 리뷰<b class="txt_num">10</b></a>
	<a href="">작성한 게시글<b class="txt_num">10</b></a>
</div>
</div>


<div class="content">
<div class="title">참여한 캠페인</div>
</div>
</div>
<?php
include_once $nfor[skin_path]."tail.php";
?>