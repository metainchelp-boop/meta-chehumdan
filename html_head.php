<?php
// 외부스크립트
$script['top'] = array();
$script['bottom'] = array();
$script['page'] = array();
if($is_admin){
	$que = sql_query("select * from nfor_script where st_use='1' or st_use='3'");
} else{
	$que = sql_query("select * from nfor_script where st_use='1'");
}
while($data = sql_fetch_array($que)){
	if($data['st_top_use']){
		$script['top'][] = $data['st_top'];
	}
	if($data['st_bottom_use']){
		$script['bottom'][] = $data['st_bottom'];
	}
	if($data['st_page_use']){
		$sp_file = basename($PHP_SELF);
		$que2 = sql_query("select * from nfor_script_page where sp_parents='$data[st_id]' and sp_file='$sp_file'");
		while($row = sql_fetch_array($que2)){
			$script['page'][] = $row['sp_script'];
		}
	}
}
?><!doctype html>
<html lang="ko">
<head>
<?=meta_tag()?>
<title><?=$config['cf_title']?></title>
<script src="<?=$nfor['path']?>/js/jquery-1.12.4.min.js"></script>
<script src="<?=$nfor['path']?>/js/jquery.plugin.min.js"></script>
<script src="<?=$nfor['path']?>/js/jquery.countdown.min.js"></script>
<script src="<?=$nfor['path']?>/js/underscore-min.js"></script>
<script src="<?=$nfor['path']?>/js/nfor.js?v=<?=@filemtime($_SERVER['DOCUMENT_ROOT'].'/js/nfor.js')?>"></script>
<script src="<?=$nfor['path']?>/js/campaign.js"></script>
<?php if($api['api_google_recaptcha_use']=="1" and !$nfor['is_app'] and $is_guest){ ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php } ?>
<!-- 다음/카카오 우편번호 API (구주소 ssl.daumcdn/dmaps.daum 폐기·404 → 현재 주소로 교체 2026-06-07) -->
<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script src="<?=$nfor['path']?>/js/placeholders.jquery.min.js"></script>
<script src="<?=$nfor['path']?>/js/jquery.form.min.js"></script>
<link href="<?=$nfor['skin_path']?>css/style.css" rel="stylesheet" type="text/css" />
<link href="<?=$nfor['skin_path']?>css/mc_front.css?v=<?=@filemtime($_SERVER['DOCUMENT_ROOT'].'/skin/demo/css/mc_front.css')?>" rel="stylesheet" type="text/css" /><!-- 고객 프론트 전역 현대화 레이어 -->

<link href="<?=$nfor['path']?>/css/swiper.min.css" rel="stylesheet" />
<script src="<?=$nfor['path']?>/js/swiper.min.js"></script>
<script src="<?=$nfor['path']?>/js/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?=$nfor['path']?>/js/jquery.bxslider.min.js"></script>
<link href="<?=$nfor['path']?>/css/jquery.bxslider.css" rel="stylesheet" />
<script src="<?=$nfor['path']?>/editor/cheditor.js"></script>
<script src="<?=$nfor['path']?>/js/SimpleAjaxUploader.min.js"></script>
<script src="<?=$nfor['path']?>/js/clipboard.min.js"></script>
<script src="<?=$nfor['path']?>/js/sticky-kit.min.js"></script>
<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?=$api['api_kakao_javascript']?>&libraries=services"></script>
<?php if($config['cf_favicon']){ ?>
<link rel="icon" href="<?=$nfor['path']."/data/favicon/".$config['cf_favicon']?>" type="image/x-icon">
<link rel="shortcut icon" href="<?=$nfor['path']."/data/favicon/".$config['cf_favicon']?>">
<?php } ?>
<script>
var is_member = "<?=$is_member?>";
var nfor_path = "<?=$nfor['path']?>";
var nfor_url = "<?=$nfor['url']?>";
var nfor_name = "<?=$config['cf_name']?>";
var is_mobile = "<?=$is_mobile?>";
var cf_name = "<?=$config['cf_name']?>";
var app_package = "<?=$nfor['app_package']?>";
var kakao_key = "<?=$api['api_kakao_javascript']?>";
Kakao.init(kakao_key);
</script>
<?=external_script("top")?>
</head>
<body<?=$config['cf_mouse_drag_use']=="1"?" ondragstart=\"return false\" onselectstart=\"return false\"":""?><?=$config['cf_mouse_right_use']=="1"?" oncontextmenu=\"return false\"":""?>>