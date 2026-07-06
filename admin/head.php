<?php	// 헤더
if(substr($nfor['root_path'],4,3) != "sub") die("");
preg_match("/오늘:(.*),어제:(.*),금주:(.*),전체:(.*)/", $config[cf_count], $count);
settype($count[0], "integer");
settype($count[1], "integer");
settype($count[2], "integer");
settype($count[3], "integer");
?>
<!DOCTYPE html>
<html lang="ko">
  <head>
    <meta charset="<?=$nfor[charset]?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$config[cf_title]?></title>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="<?=$nfor[path]?>/js/html5shiv.min.js"></script>
      <script src="<?=$nfor[path]?>/js/respond.min.js"></script>
    <![endif]-->
	<script src="<?=$nfor[path]?>/js/jquery-1.12.4.min.js"></script>
	<!-- 부트스트랩 -->
	<link rel="stylesheet" href="<?=$nfor[path]?>/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?=$nfor[path]?>/css/bootstrap-theme.min.css">
	<script src="<?=$nfor[path]?>/js/bootstrap.min.js"></script>
	<!-- //부트스트랩 -->
	<!-- 부트스트랩 플러그인 -->
	<link rel="stylesheet" href="<?=$nfor[path]?>/css/bootstrap-dialog.min.css" type="text/css" />
	<script src="<?=$nfor[path]?>/js/bootstrap-dialog.min.js"></script>
	<!-- //부트스트랩 플러그인 -->
	<link rel="stylesheet" href="/admin/nfor.css?time=<?=time()?>" type="text/css" />
	<link rel="stylesheet" href="/admin/mc_admin.css?v=<?=@filemtime($_SERVER['DOCUMENT_ROOT'].'/admin/mc_admin.css')?>" type="text/css" /><!-- 관리자 전역 현대화 레이어 2026-06-15 -->
	<!-- 달력 플러그인 -->
	<link href="<?=$nfor[path]?>/css/datepicker.css" rel="stylesheet" type="text/css">
	<script src="<?=$nfor[path]?>/js/datepicker.min.js"></script>
	<script src="<?=$nfor[path]?>/js/i18n/datepicker.ko.js?time=<?=time()?>"></script>
	<!-- //달력 플러그인 -->
	<!-- 차트 플러그인 -->
	<script src="<?=$nfor[path]?>/js/Chart.min.js"></script>
	<script src="<?=$nfor[path]?>/js/utils.js"></script>
	<!-- //차트 플러그인 -->
	<!-- 컬러픽커 플러그인 -->
	<script src="<?=$nfor[path]?>/js/jquery.minicolors.min.js"></script>
	<link rel="stylesheet" href="<?=$nfor[path]?>/css/jquery.minicolors.css">
	<!-- //컬러픽커 플러그인 -->
	<script type="text/javascript" src="<?=$nfor[path]?>/js/bootstrap-maxlength.js"></script>
	<script src="<?=$nfor[path]?>/js/jstree.min.js"></script>
	<script type="text/javascript" src="<?=$nfor[path]?>/js/underscore-min.js"></script>
	<script src="<?=$nfor[path]?>/js/jquery.plugin.min.js"></script>
	<script src="<?=$nfor[path]?>/js/jquery.countdown.min.js"></script>
	<script type="text/javascript" src="<?=$nfor[path]?>/editor/cheditor.js"></script>
	<script src="<?=$nfor[path]?>/admin/mc_guide.js?v=<?=@filemtime(dirname(__FILE__)."/mc_guide.js")?>"></script><!-- 페이지별 사용 가이드 2026-06-13 -->
	<!-- 다음/카카오 우편번호 API (구주소 ssl.daumcdn/dmaps.daum 폐기·503 → 현재 주소로 교체 2026-06-15) -->
	<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
	<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?=$api[api_kakao_javascript]?>&libraries=services"></script>
	<script type="text/javascript" src="<?=$nfor[path]?>/js/nfor.js?time=<?=time()?>"></script>
	<script type="text/javascript" src="<?=$nfor[path]?>/js/campaign.js?time=<?=time()?>"></script>
	<style type="text/css">
	/* 글자제한 */

@font-face {
    font-family: 'notokr-demilight';
    src: url('/skin/demo/fonts/notosanskr-demilight.woff2') format('woff2'),
         url('/skin/demo/fonts/notosanskr-demilight.woff') format('woff');
    font-weight: normal;
    font-style: normal;
}

@font-face {font-family: "montserrat";
	font-style: normal;
	font-display: auto;
	font-weight: 700;
	src: local('montserrat'),	
	    url("/skin/demo/fonts/montserrat-regular-webfont.eot"),
	    url("/skin/demo/fonts/montserrat-regular-webfont.eot?#iefix") format("embedded-opentype"),
	    url("/skin/demo/fonts/montserrat-regular-webfont.woff2") format("woff2"),
	    url("/skin/demo/fonts/montserrat-regular-webfont.woff") format("woff"),
	    url("/skin/demo/fonts/montserrat-regular-webfont.ttf") format("truetype"),
	    url("/skin/demo/fonts/montserrat-regular-webfont.svg#Montserrat") format("svg");
}

@font-face {
	font-family: 'montserrat';
	font-style: normal;
	font-display: auto;
	font-weight: 400;
	src: local('montserrat'),
		url("/skin/demo/fonts/montserrat-light-webfont.eot"), /* IE9 Compat Modes */
		url("/skin/demo/fonts/montserrat-light-webfont.eot?#iefix") format('embedded-opentype'), /* IE6-IE8 */
		url("/skin/demo/fonts/montserrat-light-webfont.woff") format('woff'), /* Pretty Modern Browsers */
		url("/skin/demo/fonts/Montserrat-light-webfont.ttf")  format('truetype'), /* Safari, Android, iOS */
		url("/skin/demo/fonts/montserrat-light-webfont.svg#svgFontName") format('svg'); /* Legacy iOS */
}

@font-face {
    font-family: 'Spoqa Han Sans';
    font-weight: 700;
    src: local('Spoqa Han Sans Bold'),
    url('/skin/demo/fonts/SpoqaHanSansBold.woff2') format('woff2'),
    url('/skin/demo/fonts/SpoqaHanSansBold.woff') format('woff'),
    url('/skin/demo/fonts/SpoqaHanSansBold.ttf') format('truetype');
}

@font-face {
    font-family: 'Spoqa Han Sans';
    font-weight: 400;
    src: local('Spoqa Han Sans Regular'),
    url('/skin/demo/fonts/SpoqaHanSansRegular.woff2') format('woff2'),
    url('/skin/demo/fonts/SpoqaHanSansRegular.woff') format('woff'),
    url('/skin/demo/fonts/SpoqaHanSansRegular.ttf') format('truetype');
}

@font-face {
    font-family: 'Spoqa Han Sans';
    font-weight: 300;
    src: local('Spoqa Han Sans Light'),
    url('/skin/demo/fonts/SpoqaHanSansLight.woff2') format('woff2'),
    url('/skin/demo/fonts/SpoqaHanSansLight.woff') format('woff'),
    url('/skin/demo/fonts/SpoqaHanSansLight.ttf') format('truetype');
}

@font-face {
    font-family: 'Spoqa Han Sans';
    font-weight: 100;
    src: local('Spoqa Han Sans Thin'),
    url('/skin/demo/fonts/SpoqaHanSansThin.woff2') format('woff2'),
    url('/skin/demo/fonts/SpoqaHanSansThin.woff') format('woff'),
    url('/skin/demo/fonts/SpoqaHanSansThin.ttf') format('truetype');
}


	.nfor_maxlength_wrap { position:relative; margin-right:55px; }
	.nfor_maxlength_addon { position:absolute; top:3px; right:-50px; background-color:#fff; border:solid 1px #ccc; border-radius:2px; padding:2px 6px; font-size:10px;line-height:16px; }
	.width_ss { width:110px !important; }
	.width_s { width:150px !important; }
	.width_m { width:200px !important; }
	.width_l { width:250px !important; }
	.width_xl { width:350px !important; }
	.golmarket { display:none; }
	.od_id_bold { color:red; font-weight:bold; }
	.od_memo_bold { color:red; font-weight:bold; }  
	/* 레이어팝업 페이지 */
	.nfor_pagination a { cursor:pointer; }
	/* 레이어팝업시 레이어가림 보완 */
	.datepicker-here_wrap { z-index:999999; }

	.lnb_zone{ min-width:1500px; }
	.lnb_zone:after { clear:both; }

	.lnb_zone .menu{overflow:hidden;float:left;  margin-top:35px; }
	.lnb_zone .menu li{float:left; margin-right:3px}
	.lnb_zone .menu li a{display:block;padding:10px 30px;background-color:#494b50;color:#FFF;text-decoration:none; border-top-left-radius:3px; border-top-right-radius:3px; border-top:solid 1px #525459;border-left:solid 1px #242424;border-right:solid 1px #242424;
	background: #45484d; /* Old browsers */
	background: -moz-linear-gradient(top,  #45484d 0%, #3f3f3f 100%); /* FF3.6-15 */
	background: -webkit-linear-gradient(top,  #45484d 0%,#3f3f3f 100%); /* Chrome10-25,Safari5.1-6 */
	background: linear-gradient(to bottom,  #45484d 0%,#3f3f3f 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#45484d', endColorstr='#3f3f3f',GradientType=0 ); /* IE6-9 */
	}
	.lnb_zone .menu .on{display:block;padding:10px 30px;background-color:#0081E6;color:#FFF;text-decoration:none; border-top-left-radius:3px; border-top-right-radius:3px; border-top:solid 1px #0081E6;border-left:solid 1px #0081E6;border-right:solid 1px #0081E6;
	background: #499bea; /* Old browsers */
	background: -moz-linear-gradient(top,  #499bea 0%, #207ce5 100%); /* FF3.6-15 */
	background: -webkit-linear-gradient(top,  #499bea 0%,#207ce5 100%); /* Chrome10-25,Safari5.1-6 */
	background: linear-gradient(to bottom,  #499bea 0%,#207ce5 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#499bea', endColorstr='#207ce5',GradientType=0 ); /* IE6-9 */
	}
	.lnb_zone .menu li a i{font-size:12px;}
	.lnb_zone .side_menu{float:left;margin-top:35px; height:25px;}

	.lnb_zone .side_menu .txt{color:#FFF; display:inline-block; margin-left:35px; line-height:25px; }
	.lnb_zone .side_menu .btn01{display:inline-block; padding:3px 10px; border:solid 1px #0081E6;; background-color:#0081E6;; font-size:11px; color:#FFF; margin-left:20px; text-decoration:none;}
	.lnb_zone .side_menu .btn01:hover{color:#FFF; text-decoration:none;}

	.lnb_zone .side_menu .btn02{display:inline-block; padding:3px 10px; border:solid 1px #dcdcdc;  font-size:11px; color:#FFF; text-decoration:none;}
	.lnb_zone .side_menu .btn02:hover{color:#FFF; text-decoration:none;}
	.lnb_zone .side_menu .ser{display:inline-block; position: relative; height:25px!important;}
	.lnb_zone .side_menu select{background: #f0f0f0; border: 1px solid #303134; height:25px; font-size: 11px; color: #484848;}
	.lnb_zone .side_menu .ser input[type=text]{ background: #f0f0f0; width: 120px;  height:25px; border: 1px solid #303134; font-size: 11px; color: #484848; font-weight: normal; padding: 0 0 0 3px;}
	.lnb_zone .side_menu .ser .icon-magni{position: absolute; top: 3px; right: 6px;}
	.ser_result_wrap { display:none; position:absolute; top:27px; left:1px; background-color:#fff; width:200px; height:100px; z-index:9999; border:solid 1px #ccc; overflow-y:scroll; font-size:11px; padding:10px 5px; }

	.sns_icon_wrap { margin-top:5px; }
	.blog_icon { background-color:#fff; border:solid 1px #2ba406; color:#2ba406; margin:0px 1px; padding:1px 2px; font-size:9px; }
	.instagram_icon { background-color:#fff; border:solid 1px #ff3478; color:#ff3478; margin:0px 1px; padding:1px 2px; font-size:9px; }
	.youtube_icon { background-color:#fff; border:solid 1px #f41515; color:#f41515; margin:0px 1px; padding:1px 2px; font-size:9px; }
	.shop_icon { background-color:#fff; border:solid 1px #3987ff; color:#3987ff; margin:0px 1px; padding:1px 2px; font-size:9px; }
	.ohouse_icon { background-color:#fff; border:solid 1px #2ebfff; color:#2ebfff; margin:0px 1px; padding:1px 2px; font-size:9px; }
	.coupang_icon { background-color:#fff; border:solid 1px #d84520; color:#d84520; margin:0px 1px; padding:1px 2px; font-size:9px; }
	</style>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	var nfor_path = "<?=$nfor[path]?>";

	$(document).ready(function($) {
		$("input[maxlength]").maxlength();
	});

	$(document).on("click",".li_remove",function(){ 
		$(this).closest("li").remove();
	});

	$(document).on("click",".tr_remove",function(){ 
		$(this).closest("tr").remove();
	});

	function member(mb_no,file) {

		if(!file){
			file = "index.php";
		}
		var url = nfor_path+"/admin/member/"+file+"?mb_no="+mb_no;
		window.open(url, "member", "left=50,top=50,width=1020,height=800,scrollbars=1");
	}
	//-->
	</script>
</head>
<body>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td width="207" height="69"><a href="index.php"><img src="img/main_logo.jpg" alt="처음으로"></a></td>
	<td>
		<div class="lnb_zone">

			<ul class="menu">
			<?php
			$i = 0;
			foreach($nfor[admin_tab] as $value){
				$tab_link = sql_fetch("select * from nfor_access a, nfor_access_group b where a.gp_id=b.gp_id and b.gp_group='{$value}' and a.access_level<='$member[mb_admin]' and a.access_path='0' order by b.gp_rank desc, access_rank desc");
				if($tab_link[access_file]){
			?>
			<li><a href="<?=$tab_link[access_file]?>" class=<?=$menu_code[gp_group]==$value?"on":""?>><i class="glyphicon <?=$nfor[admin_tab_ico][$value]?>"></i> <?=$value?></a></li>
			<?	
				}
				$i++;
			}
			?>
			</ul>

			<form name="fwrite" method="post" onsubmit="return fadmin_menu_fnc(this)">
			<div class="side_menu">
				<span class="txt"><?=$member[mb_name]?>(<?=$member[$member_config[cf_mb_id_type]]?>)님</span> <a href="javascript:logout()" class="btn01">로그아웃</a><a href="<?=$nfor[path]?>/index.php" target="_blank" class="btn02">내사이트바로가기</a>
				 <!-- <select name="">
					<option value="" selected>메뉴선택
					<option value="">
				</select> 
				-->
				 <div class="ser">
					<span class="icon-magni"><img src="/img/ic_search_btn.png" alt=""></span>
					<input type="text" name="admin_menu_keyword" id="admin_menu_keyword" placeholder="메뉴검색" autocomplete="off">
					<div class="ser_result_wrap">		
						<ul id="keyword_keyup">
							<li></li>
						</ul>					
					</div>
				 </div>

			</div>
			</form>

		</div>
	</td>
</tr>
</table>

<script type="text/javascript">
<!--
function fadmin_menu_fnc(){
	return false;
}






$(document).on("keyup keydown", "#admin_menu_keyword", function(){

	var keyword = $(this).val();
	if(keyword){
		$.ajax({
			type: "post",
			data : "mode=admin_menu_keyup&keyword="+keyword,
			url: nfor_path + "/json.php",
			success: function(response){
				var json = $.parseJSON(response);
				if(typeof json["keyword"] == "object"){					
					if(json["result"]=="ok"){						
						$(".ser_result_wrap").show();
						$("#keyword_keyup").html("");
						var keyword_array = [];
						keyword_array = json["keyword"];
						$.each(keyword_array, function(index, value) {
							$("#keyword_keyup").append("<li><a href=\""+index+"\">"+value+"</a></li>");
						});
					} else{						
						$(".ser_result_wrap").show();
						$(".ser_result_wrap").html("검색된 메뉴가 없습니다");
					}
				}
			}
		});		
	} else{
		$(".ser_result_wrap").hide();
	}
});
//-->
</script>



<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td align="center" width="207" valign="top">






	<table background="img/bottom.jpg" border="0" cellpadding="0" cellspacing="0" width="182" style="margin-top:7px;">
	<tr>
		<td><a href="<?=$nfor[url]?>" target="_blank"><img src="img/shopgo_btn.jpg" alt="내사이트 바로가기"></a></td>
	</tr>
	</table>




	<?
	$sql = "select * from nfor_access a, nfor_access_group b where a.gp_id=b.gp_id and b.gp_group='$menu_code[gp_group]' and a.access_level<='$member[mb_admin]' group by b.gp_id order by b.gp_rank desc";
	$que = sql_query($sql);
	while($data = sql_fetch_array($que)){
	?>	
	<table border="0" cellpadding="0" cellspacing="0" width="182" style="margin-top:7px;" background="img/leftmenu_bg_middle.jpg">
	<tr>
		<td background="img/leftmenu_bg_top.jpg" height="46" style="text-align:left; font-family:'굴림'; font-size:12px; color:#FFFFFF; padding-left:15px;">
		<?=$data[gp_text]?>
		</td>
	</tr>
	<tr>
		<td background="img/leftmenu_bg_middle.jpg" style="text-align:left; padding-left:15px;">

		<table border="0" cellpadding="0" cellspacing="0">
		<?
		$que2 = sql_query("select * from nfor_access where gp_id='$data[gp_id]' and access_path='0' and access_level<='$member[mb_admin]' order by access_rank desc");
		while($data2 = sql_fetch_array($que2)){
		?>	
		<tr>
			<td height="18"><a href="<?=$data2[access_file]?>" style="<? if($access_code==$data2[access_code]) echo "font-weight:bold;"; if($data2[access_file]=='review_board.php') echo "color:#f03e3e;font-weight:800;"; ?>"><?=$data2[access_text]?><? if($data2[access_file]=='review_board.php') echo " <span style=\"display:inline-block;font-size:9px;font-weight:800;color:#fff;background:#f03e3e;border-radius:8px;padding:0 5px;vertical-align:middle;\">NEW</span>"; ?></a> <?=$menu_cnt[$data2[access_code]]?"[".$menu_cnt[$data2[access_code]]."]":""?>
			</td>
		</tr>
		<? } ?>
		</table>

		</td>
	</tr>
	<tr>
		<td background="img/leftmenu_bg_bottom.jpg" height="7"></td>
	</tr>
	</table>
	<? } ?>


	<div style="background-color:#fff; width:182px; margin:10px; 0px; border-radius:2px; padding:5px 10px 10px;">
		<?php include_once "calendar.php"; ?>
	</div>



	<table border="0" cellpadding="0" cellspacing="0" width="182" style="margin-top:7px;" background="img/leftmenu_bg_middle.jpg">
	<tr>
		<td background="img/leftmenu_bg_top.jpg" height="46" style="text-align:left; font-family:'굴림'; font-size:12px; color:#FFFFFF; padding-left:15px;">
		관리메모
		</td>
	</tr>
	<tr>
		<td background="img/leftmenu_bg_middle.jpg" align="center" valign="top" style="padding:0px 10px;">
		
		<?php include_once "memo.php"; ?>

		</td>
	</tr>
	<tr>
		<td background="img/leftmenu_bg_bottom.jpg" height="7"></td>
	</tr>
	</table>
	<? if($nfor[test]){ ?>
	<a href="http://nfor.net/#page8" target="_blank"><img src="/admin/img/banner25.png" style="margin-top:10px;"></a>
	<? }  ?>
	</td>
	<td  valign="top">

		<table border="0" cellpadding="0" cellspacing="0" width="100%"  style="border:7px solid #0081E6; background-color:#fff;">
		<tr>
			<td style="padding:20px 20px 10px 20px; " valign="top">&nbsp;
			<?
			if($menu_code){
			?>	
					<table border="0" cellpadding="0" cellspacing="0" width="100%" >
					<tr>
						<td><span  style="font-size: 22px; color: #222222; "><?=$menu_code[access_text]?></span></td>
						<td align="right"><span class="loc01"><?=$menu_code[gp_group]?> > <?=$menu_code[gp_text]?> > </span> <span class="loc02"><?=$menu_code[access_text]?></span></td>
					</tr>
					</table>
					<p style="border-bottom:1px solid #DCDCDC;margin-top:10px;"></p>
			<? } ?>
			</td>
		</tr>
		<tr>
			<td style="padding:0px 20px 20px 20px; background-color:#fff; min-height:750px;" valign="top">












<div class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Modal title</h4>
      </div>
      <div class="modal-body">
        <p>One fine body…</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
    

<script type="text/javascript">
<!--
function nfor_layer(filename, param, title) {

    $.ajax({
        url: "layer_" + filename + ".php",
        type: "get",
        data: param,
        async: false,
		cache: false,
        success: function (response){
            var layerForm = "<div id='layer_"+ filename +"'>" + response + "</div>";
            BootstrapDialog.show({
                title: title,
                message: $(layerForm),
				cssClass: "layer_"+filename + "_wrap",
                closable: true,
            });
        }
    });
}



$(document).on("change", "#sort, #page_row", function(){
	$('#fsearch').submit();
});

//-->
</script>

<style>
.admin_file_img { border:solid 1px #ccc; }



/* 레이어팝업 사이즈 임의 조정 */
.layer_relation_wrap .modal-dialog { 
	width:900px;
}
</style>

