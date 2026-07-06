<?php
include_once "path.php";


$admin[cf_android_use] = array("전체","사용","미사용");
$admin[cf_ios_use] = array("전체","사용","미사용");


$table = "nfor_config";
$form = $_SERVER[PHP_SELF];

if($mode=="update"){
	demo_check();

	$add_sql = "";
	$add_sql .= admin_upload($config,"cf_app_img","app","nfor_config");

	sql_query("update $table set
	cf_ios_schema='$cf_ios_schema'
	, cf_android_schema='$cf_android_schema'
	, cf_ios_url='$cf_ios_url'
	, cf_android_url='$cf_android_url'
	, cf_ios_package='$cf_ios_package'
	, cf_android_package='$cf_android_package'
	, cf_android_use='$cf_android_use'
	, cf_ios_use='$cf_ios_use'$add_sql
	");


	alert("정상적으로 수정되었습니다",$form);
}

include_once "head.php";
?>
 
<?=admin_help("※ 어플설정은 별도로 엔포 솔루션을 통해 어플을 제작/구매하셔야 연동 이용이 가능한 부가서비스입니다","line50 notice_gray")?>


<form name="fwrite" method="post" onsubmit="return fsubmit(this)" enctype="multipart/form-data">
<?=admin_hidden($config,"mode")?>

<?=admin_title("안드로이드 설정" ,"title_tbl")?>
<table class="table cols_tbl " >
<colgroup>
	<col class="width-150p ">
	<col >
</colgroup>
<tr>
	<th>안드로이드 어플 사용여부</th>
	<td><?=admin_radio($config,"cf_android_use")?></td>
</tr>
<tr>
	<th>플레이스토어 주소</th>
	<td><?=admin_text($config,"cf_android_url")?></td>
</tr>
<tr>
	<th>안드로이드 스키마</th>
	<td><?=admin_text($config,"cf_android_schema")?></td>
</tr>
<tr>
	<th>안드로이드 패키지명</th>
	<td><?=admin_text($config,"cf_android_package")?></td>
</tr>
</table>

<?=admin_title("아이폰(IOS) 설정" ,"title_tbl")?>
<table class="table cols_tbl " >
<colgroup>
	<col class="width-150p ">
	<col >
</colgroup>
<tr>
	<th>아이폰 어플 사용여부</th>
	<td><?=admin_radio($config,"cf_ios_use")?></td>
</tr>
<tr>
	<th>앱스토어 주소</th>
	<td><?=admin_text($config,"cf_ios_url")?></td>
</tr>
<tr>
	<th>아이폰 스키마</th>
	<td><?=admin_text($config,"cf_ios_schema")?></td>
</tr>
<tr>
	<th>아이폰 패키지명</th>
	<td><?=admin_text($config,"cf_ios_package")?></td>
</tr>
</table>



<?=admin_title("팝업 설정" ,"title_tbl")?>
<table class="table cols_tbl " >
<colgroup>
	<col class="width-150p ">
	<col >
</colgroup>
<tr>
	<th>어플 설치 팝업 이미지</th>
	<td><?=admin_file($config,"cf_app_img","app")?> <div class="form-inline">※ 어플 사용여부가 사용으로 되어있는 경우, 모바일웹 접속시 설치 팝업이 노출됩니다</div></td>
</tr>
</table>




<div class="bottom_btn">
	<div class="form-inline">
		<?=admin_submit("fsubmit_btn", "설정하기", "btn btn-lg btn-red")?>
	</div>
</div>

</form>

<script type="text/javascript">
<!--
function fsubmit(f){

	f.action = "<?=$form?>";
	return true;	    
}	
//-->
</script>

<?php
include_once "tail.php";
?>