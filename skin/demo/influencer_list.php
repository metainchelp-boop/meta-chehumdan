<?php
include_once $nfor[skin_path]."head.php";
?>
<style>
.influencer_list_wrap{}
.influencer_list_wrap .inner_info{  border-radius:10px; padding:30px; margin-top:35px; background-color:#f6f6f6; background-image:url(/skin/demo/img/toptop.png)center; background-size:100%;}
.influencer_list_wrap h2{font-size:30px; color:#000; font-weight:normal; margin-bottom:20px; text-align:center; letter-spacing:-1px;}
.search_zone{text-align:center; padding:0px;}
.search_zone input[type=text]{height:38px; border:solid 1px #efefef; padding-left:10px; font-family: 'notokr-regular'; font-size:15px; width:350px; }
.search_zone .ser_btn{height:40px; width:80px; border:solid 1px #000; background-color:#000; color:#fff;}
.inf_list{overflow:hidden; margin-top:45px;}
.inf_list ul{margin-left:-0px;}
.inf_list li{float:left; width:25%; position:relative;   padding: 0px 20px 20px 0px;   -webkit-box-sizing: border-box;  -moz-box-sizing: border-box;  box-sizing: border-box;}
.inf_list .box{ position:relative;  border-radius:5px;}
.inf_list .inf_info{padding-left:120px; padding-top:20px; min-height:90px;}
.inf_list .box .thumb{position:absolute; top:20px;  left:15px;}
.inf_list .box .go_note{position: absolute; right:30px; top:30px;   display:inline-block; border:solid 1px #888; line-height:20px; border-radius:20px; width:20px; height:20px; background:url(/skin/demo/img/note_ico.png) center no-repeat ; background-position:transparent; ; background-size:12px;}
.inf_list .box .thumb img{border-radius:150px;}
.inf_list .box .name{font-size:18px; display:inline-block; color:#000; margin-top:15px;}
.inf_list .box .nick{font-size:14px; color:#888;display:inline-block;}

.inf_list .box .sns{margin-top:0px;}

.inf_list .box .sns a{text-indent:200px; overflow:hidden; display:inline-block;}

.inf_list .box .sns{margin-top:10px; margin-bottom:5px;}
.inf_list .box .sns a{text-indent:200px; overflow:hidden; display:inline-block; margin-right:0px;}
.inf_list .box .sns .blog{background: url('<?=$nfor[skin_path]?>img/blog.png') center no-repeat; width:30px; height:30px;   background-size:30px;}
.inf_list .box .sns .instagram{background: url('<?=$nfor[skin_path]?>img/instgram.png') center no-repeat; width:30px; height:30px;  background-size:30px}
.inf_list .box .sns .youtube{background: url('<?=$nfor[skin_path]?>img/youtube.png') center no-repeat; width:30px; height:30px;  background-size:30px}


.inf_list .box .opt_1{ margin:5px 5px; display:flex;  background-color:#f3f3f3; border-radius:5px;}
.inf_list .box .opt_1 span{ flex:1; display:inline-block;  position:relative; line-height:30px; font-size:14px; padding:8px 0px;  text-align:center;} 
.inf_list .box .opt_1 b{display:block; text-align:center;}
</style>
<div class="influencer_list_wrap">
	<div class="inner_info">
	<h2><span class="point_color4">인플루언서</span> 검색</h2>
	<div class="search_zone">
		<div class="inner">
			<input type="text" name="" placeholder="인플루언서 아이디를 입력하세요."><input type="submit" class="ser_btn">
		</div>
	</div>
	</div>
	<div class="inf_list">
		<ul>
	
		<li>
				<div class="box">
					<div class="inf_info">
						<a href="javascript:note()"  class="go_note "></a>
						<div class="thumb">
						 <img src="./data/member/mb/thumb/thumb-2009379591_Yv92pObI_20_80x80.png">
						</div>
				
						<span class="name">관리자</span>
						<span class="nick txt_num">admin</span>
						<div id="" class="sns">
							<a class="blog">블로그</a>
							<a class="instagram">인스타그램</a>
							<a class="youtube">유튜브</a>
						</div>
					</div>
					<div class="opt_1">
						<span class=""> <b class="txt_num point_color1">0</b>참여캠페인</span>
						<span class=""><b class="txt_num point_color2">0</b>리뷰어선정 </span>
						<span class=""><b class="txt_num point_color3">0</b>리뷰작성 </span>
					</div>
				</div>
			</li>
			<li>
				<div class="box">
					<div class="inf_info">
						<a href="javascript:note()"  class="go_note "></a>
						<div class="thumb">
						 <img src="./data/member/mb/thumb/thumb-2009379591_Yv92pObI_20_80x80.png">
						</div>
				
						<span class="name">관리자</span>
						<span class="nick txt_num">admin</span>
						<div id="" class="sns">
							<a class="blog">블로그</a>
							<a class="instagram">인스타그램</a>
							<a class="youtube">유튜브</a>
						</div>
					</div>
					<div class="opt_1">
						<span class=""> <b class="txt_num point_color1">0</b>참여캠페인</span>
						<span class=""><b class="txt_num point_color2">0</b>리뷰어선정 </span>
						<span class=""><b class="txt_num point_color3">0</b>리뷰작성 </span>
					</div>
				</div>
			</li>
		
		</ul>
	</div>
</div>
<?php
include_once $nfor[skin_path]."tail.php";
?>