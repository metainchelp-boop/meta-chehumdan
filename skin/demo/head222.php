<?php
include_once $nfor[path]."/html_head.php";
?>
<?php // 레이어팝업 
include_once $nfor[skin_path]."layer_popup.php";
?>
<?php // 상단배너
//include_once "inc_banner_top.php";
?>
<style>
<?php if($is_index){ ?>

.intro_layout_inner{position: relative;  width:960px; margin:181px auto 50px; text-align:center; }
#header .logo{text-align:center}
#header .gnb{position: absolute; overflow:hidden;  bottom:10px; left:-8px; font-size:16px; }
#header .gnb li{float:left; }
#header .gnb .main_menu{display:block;padding:0px 8px; }

#header .top_menu{position: absolute; overflow:hidden;  bottom:10px; right:0px; font-size:13px; letter-spacing:-1px; }
#header .top_menu .no{position: relative;  padding:0px 8px; color:#757575; }
#header .top_menu .no:before{ position:absolute; top:3px; left:0px; width:0px; background-color:#a0a0a0; height:18px; display: block;clear: both; content: ''; }
#header .top_menu .no{position: relative;  padding:0px 8px; color:#757575; }
#header .top_menu .no + .no:before{ position:absolute; top:3px; left:0px; width:1px; background-color:#a0a0a0; height:18px; display: block;clear: both; content: ''; }


#header .search_wrap{background-color:#565eb6; height:106px; margin-bottom:20px;}
#header .search_wrap .search_inner{position: relative;  width:960px; margin:0px auto; text-align:center; padding:18px 0px; }
#header .search_wrap .search_select{float:left; width:364px; background-color:#ffffff;}
#header .search_wrap .search_select select { width:100%; height:65px;padding:0 45px 0 15px;border:1px solid #565eb6; font-size:20px; letter-spacing:-1px; font-weight:200; background-color:#ffffff; line-height:28px;  color:#757575; vertical-align:middle; background: url('/skin/demo/img/select_background.png') no-repeat 98% 50%; -webkit-appearance: none;  -moz-appearance: none; appearance: none; letter-spacing: -1px; /*outline:none;*/}
#header .search_wrap .search_select select { appearance: none; -webkit-appearance: none;}
#header .search_wrap .search_select select::-ms-expand { display:none; }
#header .search_wrap .search_box{background-color:#ffffff; width:474px;}
#header .search_wrap .search_box input[type="text"]{width:100%; font-size:20px; line-height:28px; height:65px; font-weight:200; margin:0;padding:0px 0px 0px 15px; border:1px solid #565eb6; color:#666; background:none;/*outline:none;*/box-shadow:none;-webkit-appearance:none;-webkit-appearance:none;-moz-appearance:none; letter-spacing: -1px;	vertical-align:middle;}
#header .search_wrap .search_box{float:left;}
#header .search_wrap  .btn_search_box{float:left;}
#header .search_wrap  .btn_search_box .btn_search_item{width:80px; height:63px; color:#fff; font-size:20px; border:1px solid #e0e0e0;margin-top:1px; margin-left:-1px; background-color:#565eb6; }
<?php } else{ ?>

#header .intro_layout_inner{position: relative;  width:100%; margin:0px auto 0px; text-align:right; border-bottom:solid 1px #efefef; height:73px;}
#header .logo{ position: absolute; display:inline-block; left:20px; top:20px; text-align:center; width:117px; ;}
#header .logo img{width:100%;}

#header .gnb{display:inline-block;font-size:16px; overflow:hidden; height:74px; letter-spacing:-1px;}
#header .gnb li{float:left;}
#header .gnb .main_menu{display:block; line-height:24px; padding:30px 15px 15px; }
#header .gnb .main_menu:hover{color:#565eb6;}
#header .gnb .on{ position: relative; color:#565eb6; font-weight:bold; }
#header .gnb .on:after{width:100%; height:69px; border-bottom:solid 2px #565eb6;position:absolute; top:0px; left:0px; display: block;clear: both; content: ''; }

#header .top_menu{display:inline-block; font-size:13px; margin-top:5px;  vertical-align:top; }
#header .top_menu .no{position: relative;  padding:10px 8px 10px;; color:#757575; line-height:80px; letter-spacing:-1px;}
#header .top_menu .no:before{ position:absolute; top:13px; left:0px; width:0px; background-color:#a0a0a0; height:12px; display: block;clear: both; content: ''; }
#header .top_menu .no + .no:before{ position:absolute; top:13px; left:0px; width:1px; background-color:#a0a0a0;  height:12px; display: block;clear: both; content: ''; }

#header .search_wrap{background-color:#565eb6; height:106px; margin-bottom:20px;display:none;}
#header .search_wrap .search_inner{position: relative;  width:960px; margin:0px auto; text-align:center; padding:18px 0px; }
#header .search_wrap .search_select{float:left; width:364px; background-color:#ffffff;}
#header .search_wrap .search_select select { width:100%; height:65px;padding:0 45px 0 15px;border:1px solid #565eb6; font-size:20px; letter-spacing:-1px; font-weight:200; background-color:#ffffff; line-height:28px;  color:#757575; vertical-align:middle; background: url('/skin/demo/img/select_background.png') no-repeat 98% 50%; -webkit-appearance: none;  -moz-appearance: none; appearance: none; letter-spacing: -1px; /*outline:none;*/}
#header .search_wrap .search_select select { appearance: none; -webkit-appearance: none;}
#header .search_wrap .search_select select::-ms-expand { display:none; }
#header .search_wrap .search_box{background-color:#ffffff; width:474px;}
#header .search_wrap .search_box input[type="text"]{width:100%; font-size:20px; line-height:28px; height:65px; font-weight:200; margin:0;padding:0px 0px 0px 15px; border:1px solid #565eb6; color:#666; background:none;/*outline:none;*/box-shadow:none;-webkit-appearance:none;-webkit-appearance:none;-moz-appearance:none; letter-spacing: -1px;	vertical-align:middle;}
#header .search_wrap .search_box{float:left; }
#header .search_wrap  .btn_search_box{float:left;}
#header .search_wrap  .btn_search_box .btn_search_item{width:80px; height:63px; color:#fff;font-size:20px; border:1px solid #e0e0e0;margin-top:1px; margin-left:-1px;}
<?}?>

</style>
<meta name="viewport" content="width=1300">
<div id="wrap">
	<div id="header">
		<div class="intro_layout_inner">
			<h2 class="logo"><a href="index.php"><img src="<?=$nfor[skin_path]?>img/logo.png" alt="로고"/></a></h2>
			<div class="gnb">
				<ul>
					<li><a href="house_form.php" <?=basename($PHP_SELF)=="house_form.php"?"class='main_menu on'":"class='main_menu'"?>>방내놓기</a></li>
					<li><a href="house_list.php"  <?=basename($PHP_SELF)=="house_list.php"?"class='main_menu on'":"class='main_menu'"?>>내방관리</a></li>
					<li><a href="house_map.php" <?=basename($PHP_SELF)=="house_map.php"?"class='main_menu on'":"class='main_menu'"?>>지도보기</a> </li>
					<li><a href="zzim_list.php" class="main_menu">관심매물</a> </li>
				</ul>
			</div>
			<div class="top_menu">
					<? if(!$member[mb_no]){ ?>
					<a href="login.php" class="no">로그인</a>
					<a href="member_main.php" class="no">회원가입</a>
					<? } else{ ?>                                                                                                                                     
					<a href="javascript:logout()" class="no">로그아웃</a>	
					<a href="zzim_list.php" class="no">마이페이지</a>
					<? } ?>
					<a href="faq.php" class="no">고객센터</a>
			</div>
		</div>
		<div class="search_wrap">
				<div class="search_inner">
					<form id="item_form" action="search.php" method="get" autocomplete="off">
						<div class="search_select"> 
							<select name="" class="">
								<option value="" selected>어떤 방을 구하세요
								<option value="">아파트                                                                    
									<option value="">오피스텔
									<option value="">도시형생활주택
									<option value=""> 단독주택 
									<option value="">다가구주택
									<option value="">빌라/연립/다세대
									<option value=""> 상가주택
							</select>
						</div>
						<div class="search_box">
							<input type="text" name="keyword" id="keyword" value="<?=$_GET[keyword]?>" placeholder="지역을 입력해주세요. ex)청라2동" class="search_in">
						</div>
						<div class="btn_search_box">
							<input type="submit" value="검색" class="btn_search_item">
						</div>
					</form>
				</div>
				
				<script>
				$(document).on("click",".btn_search_item",function(){
					if($("#keyword").val()==""){
						alert("검색어를 입력해주세요");
						$("#keyword").focus();
					} else{
						$("#item_form").submit();
					}
					event.preventDefault();
				});
				</script>
		</div>
	</div>
	<style>
	.layout_frame {padding: 30px 0px;}
	.layout_frame .layout_frame_inner{ position:relative;width:1200px; margin:0px auto; padding-bottom:0px; }
	.layout_noframe .layout_frame_inner{padding-bottom:60px;  }
	</style>

	<?
	if(basename($PHP_SELF)=="map_item_list.php" or basename($PHP_SELF)=="house_map.php"){
		$class = "layout_noframe";
	} else{
		$class = "layout_frame";
	}


	?>

	<div class="<?=$class?>" >
	<div class="layout_frame_inner">
