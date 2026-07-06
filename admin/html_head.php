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
	<script src="<?=$nfor[path]?>/js/jquery-1.9.1.min.js"></script>




	<!-- 부트스트랩 -->
	<link rel="stylesheet" href="<?=$nfor[path]?>/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?=$nfor[path]?>/css/bootstrap-theme.min.css">
	<script src="<?=$nfor[path]?>/js/bootstrap.min.js"></script>
	<!-- //부트스트랩 -->
	<!-- 부트스트랩 플러그인 -->
	<link rel="stylesheet" href="<?=$nfor[path]?>/css/bootstrap-dialog.min.css" type="text/css" />
	<script src="<?=$nfor[path]?>/js/bootstrap-dialog.min.js"></script>
	<!-- //부트스트랩 플러그인 -->


	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<script src="http://code.jquery.com/jquery-latest.min.js"></script>

	<link rel="stylesheet" href="/admin/nfor.css" type="text/css" />






	<!-- 달력 플러그인 -->
	<link href="<?=$nfor[path]?>/css/datepicker.css" rel="stylesheet" type="text/css">
	<script src="<?=$nfor[path]?>/js/datepicker.min.js"></script>
	<script src="<?=$nfor[path]?>/js/i18n/datepicker.ko.js"></script>
	<!-- //달력 플러그인 -->






	<script type="text/javascript" src="<?=$nfor[path]?>/js/bootstrap-maxlength.js"></script>

	<script src="<?=$nfor[path]?>/js/jstree.min.js"></script>


	<script type="text/javascript" src="<?=$nfor[path]?>/js/underscore-min.js"></script>


	<script type="text/javascript" src="<?=$nfor[path]?>/js/jquery.countdown.min.js"></script>


	<script type="text/javascript" src="<?=$nfor[path]?>/editor/cheditor.js"></script>


<? if($_SERVER['REQUEST_SCHEME']=="https"){ ?>
<script src="https://ssl.daumcdn.net/dmaps/map_js_init/postcode.v2.js"></script>
<? } else{ ?>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<? } ?>

<script type="text/javascript" src="<?=$nfor[path]?>/js/nfor.js"></script>


<script type="text/javascript">
<!--
var nfor_path      = "<?=$nfor[path]?>";
//-->
</script>


<style>
#content2{width:1000px; top:-34px;padding-left:30px; padding-right:30px;}
#content2 .crm{margin-top:24px; margin-bottom:25px; border-bottom: 2px solid #888888; position:relative;}
#content2 .crm h3{font-size:22px; color:#222222; font-weight:bold; margin-top:17px; margin-bottom:17px;}
#content2 .crm .close{position:absolute; right:0px; top:0px; background: url(/admin/img/closeicon.gif) no-repeat 50% 50%;width:22px; height:22px; opacity: 1; border:none;}

#content2 .member_zone{overflow:hidden;}
#content2 .member_zone .left{float:left; overflow:hidden; height:45px;}
#content2 .member_zone .left h3{ float:left; display:inline-block;color: #222222;  text-decoration: none;  margin:0px; font-size: 24px; line-height:45px; font-weight: bold;}
#content2 .member_zone .left .btn_zone{float:right; margin-top:10px; margin-left:10px;}
#content2 .member_zone .right{float:right; padding-top:20px;}
#content2 .member_info{border:solid 1px #ccc; padding:10px; margin-top:10px; overflow:hidden; position:relative; background-color:#FAFAFA;}
#content2 .member_info li{ float:left; padding:0px 10px; border-left:solid 1px #DCDCDC;}
#content2 .member_info li:nth-child(1){ border-left:none; padding-left:0px;}
#content2 .member_info li b{color:#ff0000}
#content2 .member_info button{position:absolute; right:10px; top:6px;}


#content2 .member_con .navi{margin:20px 0 20px; overflow:hidden; border-bottom: 1px solid #888888;}
#content2 .member_con .navi li{ float:left; background-color: #F6F6F6; margin-right: -1px; border: 1px solid #E4E4E4; border-radius:0;  }
#content2 .member_con .navi li:hover{ background-color: #FFF; }
#content2 .member_con .navi li a{display:block; width:100%; padding: 10px 15px; text-decoration:none;}
#content2 .member_con .navi li a:hover{color:#666;}
#content2 .member_con .navi .on {color:#222222;font-size:12px;  margin-right: -1px;background-color: #FFFFFF; border: 1px solid #888888; border-bottom-color: transparent;font-weight: bold;  z-index: 2;}
#content2 .member_con .navi .on a{border-right:1px solid #888; display:block; width:100%; padding: 10px 15px; }


#content2 .member_con .con1 h3{font-size:16px; font-weight:bold; color:#222; border-bottom:2px solid #888888; padding:10px 0px;}
#content2 .member_con .con1 .title{padding:10px 0px; font-size:14px; }

/* 기간설정 */

#period_sdate { border:solid 1px #D5D5D5; padding:4px 12px; font-size:12px; color:#555555; } 
#period_edate { border:solid 1px #D5D5D5; padding:4px 12px; font-size:12px; color:#555555; } 

.period_wrap { display:inline-block; vertical-align:middle;border-right:solid 1px #D5D5D5;}
.period_wrap label { float:left; border-left:solid 1px #D5D5D5; border-top:solid 1px #D5D5D5;  border-bottom:solid 1px #D5D5D5;color:#666; padding:3px 15px !important; font-size:12px; cursor:pointer; font-family:Malgun Gothic,"맑은 고딕",AppleGothic,Dotum,"돋움","Helvetica Neue", Helvetica, Arial, sans-serif; background-color:#FAFAFA; line-height:16px !important;}
.period_wrap label.checked { background-color:#444; border-color:#444;  color:#fff; }
.period_wrap input { display:none; }
</style>
<script>

$(document).on("click",".period_wrap label",function(){ 
	$('.period_wrap label').removeClass("checked");
	$(this).addClass("checked");

	var prev_day = 0;
	var period_day_checked = $(':radio[name="period_day"]:checked').val();
	if(period_day_checked<0){
		prev_day = period_day_checked;
		$("#period_sdate").val("");
		$("#period_edate").val("");
	} else{
		prev_day = period_day_checked;
		var date = new Date(); 
		var period_edate = date.getFullYear() + "-" + date_add_zero(eval(date.getMonth()+1)) + "-" + date_add_zero(date.getDate());
		$(this).parent().parent().find("#period_edate").val(period_edate);

		var prev_date = new Date(period_edate);
		prev_date.setDate( prev_date.getDate() - prev_day );

		var period_sdate = prev_date.getFullYear() + "-" + date_add_zero(eval(prev_date.getMonth()+1)) + "-" + date_add_zero(prev_date.getDate());
		$(this).parent().parent().find("#period_sdate").val(period_sdate);
	}
});

</script>
</head>
<body style="background-color:#FFF; padding:20px;"> 
