<?php
include_once "path.php";

$admin[cf_ipin_use] = array("전체","사용","미사용");
$admin[cf_hp_use] = array("전체","사용","미사용");

$table = "nfor_config";
$form = $_SERVER[PHP_SELF];

if($mode=="update"){
	demo_check();
	sql_query("update $table set 
	cf_ipin_use='$cf_ipin_use'
	, cf_hp_use='$cf_hp_use'
	, cf_check_code='$cf_check_code'");	
	alert("정상적으로 수정되었습니다",$form);
}

include_once "head.php";
?>

<form name="fconfig" method="post" action="<?=$form?>">
<?=admin_hidden($config,"mode")?>
<?=admin_help("- 쇼핑몰에서 회원가입/성인인증 등 본인확인이 필요한 메뉴에서 본인확인을 위해 사용하는 서비스입니다.<br>
       - 서비스 설정전에 반드시 서버에 KCB 서버모듈이 설치되어 있어야 정상동작합니다","line50 notice_gray")?>

<table class="table cols_tbl" >
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>서비스신청</th>
	<td>
	<?=admin_a("a", "서비스신청", "btn-gray btn-sm", " target=\"_blank\"", "http://ok-name.co.kr")?>
	</td>
</tr>
<tr>
	<th>아이핀 사용여부</th>
	<td><?=admin_radio($config,"cf_ipin_use")?></td>
</tr>
<tr>
	<th>휴대폰 본인확인 사용여부</th>
	<td><?=admin_radio($config,"cf_hp_use")?></td>
</tr>
<tr>
	<th>KCB 회원사코드</th>
	<td><?=admin_text($config,"cf_check_code","width-200p")?></td>
</tr>
</table>

<div class="text-center"><div class="form-inline"><?=admin_submit("fsubmit", "환경설정", "btn btn-lg btn-black")?></div></div>

</form>

<?php
include_once "tail.php";
?>