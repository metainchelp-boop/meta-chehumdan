<?php
include_once "path.php";

$admin = array();
$admin['api_kakao_use'] = array(""=>"전체", "1" => "사용", "2"=>"미사용");
$admin['api_facebook_use'] = array(""=>"전체", "1" => "사용", "2"=>"미사용");
$admin['api_naver_use'] = array(""=>"전체", "1" => "사용", "2"=>"미사용");
$admin['api_google_use'] = array(""=>"전체", "1" => "사용", "2"=>"미사용");
$admin['api_google_recaptcha_use'] = array(""=>"전체", "1" => "사용", "2"=>"미사용");
$admin['api_apple_use'] = array(""=>"전체", "1" => "사용", "2"=>"미사용");

$form = $_SERVER['PHP_SELF'];
$list = str_replace("form","list",$form);
$table = "nfor_api";
$id = "api_id";

$id_value = $$id;

if($id_value){
	$write = sql_fetch("select * from $table where $id='{$id_value}'");
}

if($mode=="insert" or $mode=="update"){
	demo_check();

	$add_sql = "";
	$where_sql = "";

	$add_sql .= admin_upload($write,"api_google_analytics_json","google",$table);

	if($mode=="insert"){
		$msg = "정상적으로 등록 되었습니다";
		$move = "$list";
		$add_sql .= ", api_insert_id='{$member['mb_no']}', api_insert_datetime=NOW()";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$move = "$form?{$qstr}&{$id}={$id_value}";
		$where_sql = "  where $id='{$id_value}'";
		$add_sql .= ", api_update_id='{$member['mb_no']}', api_update_datetime=NOW()";
	} else{

	}
	$common_sql = " $table set api_google_analytics_propertyid='$api_google_analytics_propertyid', api_apple_use='$api_apple_use', api_apple_id='$api_apple_id', api_apple_client_id='$api_apple_client_id', api_apple_team_id='$api_apple_team_id', api_apple_key_id='$api_apple_key_id', api_google_recaptcha_use='$api_google_recaptcha_use', api_google_recaptcha_siteid='$api_google_recaptcha_siteid', api_google_recaptcha_secretkey='$api_google_recaptcha_secretkey', api_google_id='$api_google_id', api_kakao_id='$api_kakao_id', api_facebook_id='$api_facebook_id', api_naver_id='$api_naver_id', api_kakao_use='$api_kakao_use', api_facebook_use='$api_facebook_use', api_naver_use='$api_naver_use', api_google_use='$api_google_use', api_domain='$api_domain', api_naver_client_id='$api_naver_client_id', api_naver_client_secret='$api_naver_client_secret', api_facebook_appid='$api_facebook_appid', api_facebook_appsecret='$api_facebook_appsecret', api_kakao_rest='$api_kakao_rest', api_kakao_javascript='$api_kakao_javascript', api_google_client_id='$api_google_client_id', api_google_client_secret='$api_google_client_secret', api_google_analytics_viewid='$api_google_analytics_viewid', api_firebase_key_id='$api_firebase_key_id'";

	sql_query("$mode $common_sql $add_sql $where_sql");
	alert($msg,$move);
}

include_once "head.php";
?>

<form name="fwrite" method="post" onsubmit="return fsubmit(this)" enctype="multipart/form-data">
<?=admin_hidden($write,"mode")?>
<?=admin_hidden($write,$id)?>


<?=admin_title("도메인","title_tbl")?>
<table class="table cols_tbl">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>도메인</th> 
	<td>
	<div class="form-inline">
	<?=admin_text($write,"api_domain")?>
	http:// 과 www. 을 제외한 도메인 나머지를 입력해주세요 예) nfor.net
	</div>
	</td>
</tr>
</table>









<?=admin_title("애플","title_tbl")?>
<table class="table cols_tbl">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>애플 로그인 사용여부</th> 
	<td>
	<?php
	if(!$write['api_apple_use']) $write['api_apple_use'] = "1";
	?>
	<?=admin_radio($write,"api_apple_use")?>
	</td>
</tr>
<tr>
	<th>애플 개발자 아이디</th> 
	<td>
	<?=admin_text($write,"api_apple_id","width-150p")?>
	</td>
</tr>
<tr>
	<th>Client ID</th> 
	<td><div class="form-inline"><?=admin_text($write,"api_apple_client_id")?><?=admin_a("api_apple_btn","애플API 발급받기","form-control btn-gray","target=\"_blank\"","https://developers.apple.com")?></div></td>
</tr>
<tr>
	<th>Client TeamID</th> 
	<td><?=admin_text($write,"api_apple_team_id")?></td>
</tr>
<tr>
	<th>Client KeyID</th> 
	<td><?=admin_text($write,"api_apple_key_id")?></td>
</tr>
<tr>
	<th>Callback Url</th>
	<td><?=$nfor['url']?>/apple_callback.php</td>
</tr>
</table>











<?=admin_title("네이버","title_tbl")?>
<table class="table cols_tbl">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>네이버 로그인 사용여부</th> 
	<td>
	<?php
	if(!$write['api_naver_use']) $write['api_naver_use'] = "1";
	?>
	<?=admin_radio($write,"api_naver_use")?>
	</td>
</tr>
<tr>
	<th>네이버 개발자 아이디</th> 
	<td>
	<?=admin_text($write,"api_naver_id","width-150p")?>
	</td>
</tr>
<tr>
	<th>Client ID</th> 
	<td><div class="form-inline"><?=admin_text($write,"api_naver_client_id")?><?=admin_a("api_naver_btn","네이버API 발급받기","form-control btn-gray","target=\"_blank\"","https://developers.naver.com")?></div></td>
</tr>
<tr>
	<th>Client Secret</th> 
	<td><?=admin_text($write,"api_naver_client_secret")?></td>
</tr>
<tr>
	<th>Callback Url</th>
	<td><?=$nfor['url']?>/naver_callback.php</td>
</tr>
</table>

<?=admin_title("페이스북","title_tbl")?>
<table class="table cols_tbl">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>페이스북 로그인 사용여부</th> 
	<td>
	<?php
	if(!$write['api_facebook_use']) $write['api_facebook_use'] = "1";
	?>
	<?=admin_radio($write,"api_facebook_use")?>
	</td>
</tr>
<tr>
	<th>페이스북 개발자 아이디</th> 
	<td>
	<?=admin_text($write,"api_facebook_id","width-150p")?>
	</td>
</tr>
<tr>
	<th>App ID</th> 
	<td><div class="form-inline"><?=admin_text($write,"api_facebook_appid")?><?=admin_a("api_facebook_btn","페이스북API 발급받기","form-control btn-gray","target=\"_blank\"","https://developers.facebook.com/apps")?></div></td>
</tr>
<tr>
	<th>App Secret</th> 
	<td><?=admin_text($write,"api_facebook_appsecret")?></td>
</tr>
<tr>
	<th>Callback Url</th>
	<td><?=$nfor['url']?>/facebook_callback.php</td>
</tr>
</table>

<?=admin_title("카카오톡","title_tbl")?>
<table class="table cols_tbl">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>카카오 로그인 사용여부</th> 
	<td>
	<?php
	if(!$write['api_kakao_use']) $write['api_kakao_use'] = "1";
	?>
	<?=admin_radio($write,"api_kakao_use")?>
	</td>
</tr>
<tr>
	<th>카카오 개발자 아이디</th> 
	<td>
	<?=admin_text($write,"api_kakao_id","width-150p")?>
	</td>
</tr>
<tr>
	<th>REST KEY</th> 
	<td><div class="form-inline"><?=admin_text($write,"api_kakao_rest")?><?=admin_a("api_kakao_btn","카카오API 발급받기","form-control btn-gray","target=\"_blank\"","https://developers.kakao.com/apps")?></div></td>
</tr>
<tr>
	<th>JAVASCRIPT KEY</th> 
	<td><?=admin_text($write,"api_kakao_javascript")?></td>
</tr>
<tr>
	<th>Callback Url</th>
	<td><?=$nfor['url']?>/kakao_callback.php</td>
</tr>
</table>

<?=admin_title("구글","title_tbl")?>
<table class="table cols_tbl">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>구글 로그인 사용여부</th> 
	<td>
	<?php
	if(!$write['api_google_use']) $write['api_google_use'] = "1";
	?>
	<?=admin_radio($write,"api_google_use")?>
	</td>
</tr>
<tr>
	<th>구글 개발자 아이디</th> 
	<td>
	<?=admin_text($write,"api_google_id","width-150p")?>
	</td>
</tr>
<tr>
	<th>Client ID</th> 
	<td><div class="form-inline"><?=admin_text($write,"api_google_client_id")?><?=admin_a("api_console_btn","구글콘솔API 발급받기","form-control btn-gray","target=\"_blank\"","http://code.google.com/apis/console/")?></div></td>
</tr>
<tr>
	<th>Client Secret</th>
	<td><?=admin_text($write,"api_google_client_secret")?></td>
</tr>
<tr>
	<th>Callback Url</th>
	<td><?=$nfor['url']?>/google_callback.php</td>
</tr>
</table>

<?=admin_title("구글 reCAPTCHA v2","title_tbl")?>
<table class="table cols_tbl">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>구글 reCAPTCHA v2 사용여부</th> 
	<td>
	<?php
	if(!$write['api_google_recaptcha_use']) $write['api_google_recaptcha_use'] = "1";
	?>
	<?=admin_radio($write,"api_google_recaptcha_use")?>
	</td>
</tr>
<tr>
	<th>사이트키</th> 
	<td><div class="form-inline"><?=admin_text($write,"api_google_recaptcha_siteid")?><?=admin_a("api_recaptcha_btn","reCAPTCHA v2 설정","form-control btn-gray","target=\"_blank\"","https://www.google.com/recaptcha/admin")?></div></td>
</tr>
<tr>
	<th>비밀키</th> 
	<td><div class="form-inline"><?=admin_text($write,"api_google_recaptcha_secretkey")?></div></td>
</tr>
</table>

<?=admin_title("구글 애널리틱스","title_tbl")?>
<table class="table cols_tbl">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>View ID</th> 
	<td><div class="form-inline"><?=admin_text($write,"api_google_analytics_viewid")?><?=admin_a("api_analytics_btn","구글애널리틱스 설정","form-control btn-gray","target=\"_blank\"","https://analytics.google.com")?></div></td>
</tr>

<tr>
	<th>속성 ID</th> 
	<td><div class="form-inline"><?=admin_text($write,"api_google_analytics_propertyid")?><?=admin_a("api_analytics_btn","구글애널리틱스 설정","form-control btn-gray","target=\"_blank\"","https://analytics.google.com")?></div></td>
</tr>



<tr>
	<th>JSON</th> 
	<td><div class="form-inline"><?=admin_file($write,"api_google_analytics_json","google")?><?=admin_a("api_console_btn","구글콘솔API 발급받기","form-control btn-gray","target=\"_blank\"","http://code.google.com/apis/console/")?></div></td>
</tr>
</table>

<?=admin_title("구글 파이어베이스","title_tbl")?>
<table class="table cols_tbl">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>Server Key</th> 
	<td>
	<div class="form-inline">
	<?=admin_text($write,"api_firebase_key_id")?>
	<?=admin_a("api_firebase_btn","파이어베이스 발급받기","form-control btn-gray","target=\"_blank\"","https://console.firebase.google.com")?>
	<?=admin_help("＊엔포 솔루션을 통한 어플 제작시에만 이용가능한 API입니다","notice_gray")?>
	</div>
	</td>
</tr>
</table>

<div class="bottom_btn">
	<div class="form-inline">
		<?=admin_submit("fsubmit_btn", $write[$id]?"수정하기":"등록하기", "btn btn-lg btn-red")?>
		<?=admin_a("list", "목록보기", "btn btn-lg btn-black", "", $list."?".$qstr)?>
	</div>
</div>

</form>

<SCRIPT LANGUAGE="JavaScript">
<!--
function fsubmit(f){
	if(!$('#api_domain').val()){
		alert("도메인을 입력해주세요");
		$('#api_domain').focus();
        return false;
	}
	f.action = "<?=$form?>";
	return true;	    
}	
//-->
</SCRIPT>

<?php
include_once "tail.php";
?>