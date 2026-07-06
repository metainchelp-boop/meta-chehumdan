<?php // 설치팝업사용이면서 어플이아니면
if($config[cf_app_use]=="1" and !$nfor[is_app]){

	if(!$_COOKIE[app_install_popup] and $config[cf_app_img] and $nfor[app_download_url]){
?>

	<style>
	#popup { top:0px; width:100%; height:100%; position:fixed; z-index:99990; background-color: rgba(0, 0, 0, 0.8); }
	#popup_wrap { left:50%; top:30%; width:300px; margin-left:-150px; display:block; position:absolute; }
	#popup_wrap img { width:100%; cursor:pointer; }
	#popup_close { cursor:pointer; color:#fff; font-size:16px; font-weight:bold; text-align:center; text-decoration:underline; margin-top:15px; display:block; width:300px; }
	</style>

	<div id="popup">

		<div id="popup_wrap">
			<a href="<?=$nfor[app_download_url]?>"><img src="<?="$nfor[path]/data/app/$config[cf_app_img]"?>" alt="어플다운로드"/></a>
			<a id="popup_close">모바일웹으로 접속해서 바로보기</a>
		</div>

	</div>

	<script type="text/javascript">
	<!--
	$(document).on("click", "#popup_close", function(){
		set_cookie("app_install_popup","app_install", "24");
		$('#popup').hide();
	});
	//-->
	</script>

<? 
	}

} else{

	if(!$_COOKIE[mobile_popup]){

		$popup = sql_fetch("select * from nfor_popup where pop_device='2' and pop_use='1' and pop_sdatetime<='$nfor[ymdhis]' and pop_edatetime>='$nfor[ymdhis]' limit 1");
		if($popup[pop_id]){
			$popup[pop_memo] = str_replace("_blank","_self",$popup[pop_memo]);
?>

		<style>
		#popup { top:0px; width:100%; height:100%; display:block; position:fixed; z-index:19999; background-color: rgba(0, 0, 0, 0.8); }
		#popup #popup_memo { width:100%; height:100%; display:block; overflow-y:scroll; }
		#popup #popup_memo img { width:100%;  }
		#popup_button_wrap { overflow:hidden; position:fixed; bottom:0px; width:100%; height:40px; background: rgb(242, 242, 242); border-top:solid 1px #e7e7e7; } 
		#today_close { display:block; float:left; cursor:pointer; text-align:center; vertical-align:middle; height:40px; line-height:40px; border-right:solid 1px #e7e7e7; width:50%; margin-right:-1px; }
		#popup_close { display:block; float:left; cursor:pointer; text-align:center; vertical-align:middle; height:40px; line-height:40px; width:50%; }
		</style>

		<div id="popup">

			<div id="popup_memo"><?=$popup[pop_memo]?></div>

			<div id="popup_button_wrap">
				<a id="today_close">오늘 하루 보지 않기</a>
				<a id="popup_close">닫기</a>
			</div>

		</div>

		<script type="text/javascript">
		<!--
		$(document).on("click", "#today_close", function(){
			set_cookie("mobile_popup","appdown", "24");
			$('#popup').hide();
		});
		$(document).on("click", "#popup_close", function(){
			$('#popup').hide();
		});
		//-->
		</script>

<?php
		}
	} 
} 
?>