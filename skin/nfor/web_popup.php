<?
$banner = sql_fetch("select * from nfor_banner where wr_use='1' and wr_code='mobile_popup' and wr_sdate<='$nfor[ymdhis]' and wr_edate>='$nfor[ymdhis]' order by wr_rank limit 1");
if($banner[wr_banner_img]){
?><style>
.intro { top:0px; width:100%; height:100%; display:none; position:fixed; z-index:9999; background-color: rgba(0, 0, 0, 0.8); }
.int_chk { background: rgb(242, 242, 242); padding:0px; width:100%; bottom:0px; color:rgb(0, 0, 0); font-size:15px; position:absolute; }
.intro a.start { left:50%; top:25%; transform:translateX(-50%); width:250px; display:block; position:absolute; }
.intro a.start img { width:100%; }
</style>

<div class="intro" style="display:block" id="intro">
	<a href="javascript:app_link()" class="start"><img src="<?="$nfor[path]/data/banner/$banner[wr_banner_img]"?>" alt="어플다운로드" /></a>
	<div class="int_chk">


	<table cellpadding="0" cellspacing="0" border="0" width="100%" style=" border-top:solid 1px #e7e7e7;">
	<tr>
		<td onClick="today_popup_close()" style="text-align:center; vertical-align:middle; height:40px; line-height:40px; border-right:solid 1px #e7e7e7; width:49.9%;">오늘 하루 보지 않기</td>
		<td onclick="popup_close()" style="text-align:center; vertical-align:middle; height:40px; line-height:40px;">닫기</td>
	</tr>
	</table>
		



	</div>
</div>




<script type="text/javascript">
<!--

function app_link(){

	if(navigator.platform=="iPhone"){
		location.href="<?=$config[cf_ios_url]?>";
	} else{
		location.href="<?=$config[cf_android_url]?>";
	}


}

function popup_close(){
	$('#intro').hide();
}
function today_popup_close(){
	s_cookie("mobile_popup", "true", 1);
	$('#intro').hide();
}
function s_cookie(name, value, expiredays){
	var today = new Date();
	today.setDate( today.getDate() + expiredays );
	document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + today.toGMTString() + ";"
}


function r_cookie(cookiename){
	var is_cookie = false;
	cookiedata = document.cookie;
	if(cookiedata.indexOf(cookiename) >= 0){
		is_cookie = true;
	}
	return is_cookie;
}


var broswerInfo = navigator.userAgent;

if(broswerInfo.indexOf("GOLMARKET_ANDROID_APP")>-1){

	$('#intro').hide();

} else{

	if(r_cookie('mobile_popup')){
		$('#intro').hide();
	}

}
//-->
</script>



<? } ?>