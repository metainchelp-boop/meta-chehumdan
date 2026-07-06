<?php
include_once "path.php";

$admin[cf_robot_use] = array("전체","검색로봇설정","검색로봇설정안함");

$table = "nfor_config";
$form = $_SERVER[PHP_SELF];

if($mode=="update"){

	demo_check();

	if($cf_robot_use=="1"){
		nfor_file_write("$nfor[path]/robots.txt",$cf_robot_txt);
	} else{
		nfor_file_write("$nfor[path]/robots.txt","");
	}

	$add_sql = "";
	$add_sql .= admin_upload($config,"cf_favicon","favicon","nfor_config");
	$add_sql .= admin_upload($config,"cf_sitemap_xml","sitemap","nfor_config");
	$add_sql .= admin_upload($config,"cf_image","og_image","nfor_config");
	$add_sql .= admin_upload($config,"cf_stamp","stamp","nfor_config");

	sql_query("update $table set
    cf_name='$cf_name'
    , cf_name_eng='$cf_name_eng'
    , cf_url='$cf_url'
    , cf_title='$cf_title'
    , cf_meta_description='$cf_meta_description'
    , cf_meta_keyword='$cf_meta_keyword'
    , cf_robot_use='$cf_robot_use'
    , cf_cp_name='$cf_cp_name'
    , cf_cp_ceo='$cf_cp_ceo'
    , cf_cp_number1='$cf_cp_number1'
    , cf_cp_number2='$cf_cp_number2'
    , cf_cp_type1='$cf_cp_type1'
    , cf_cp_type2='$cf_cp_type2'
    , cf_tel='$cf_tel'
    , cf_email='$cf_email'
    , cf_cp_zip='$cf_cp_zip'
    , cf_cp_addr1='$cf_cp_addr1'
    , cf_cp_addr2='$cf_cp_addr2'
    , cf_cp_privacy='$cf_cp_privacy'
    , cf_cp_privacy_email='$cf_cp_privacy_email'
	, cf_robot_txt='$cf_robot_txt'
	, cf_fax='$cf_fax'
	$add_sql");

	alert("정상적으로 수정되었습니다",$form);
}

include_once "head.php";
?>

<form name="fwrite" method="post" onsubmit="return fsubmit(this)" enctype="multipart/form-data">
<?=admin_hidden($config,"mode")?>
<?=admin_title("기본정보","title_tbl")?>
<table class="table cols_tbl" >
<colgroup>
	<col class="width-150p">
	<col >
	<col class="width-150p">
	<col>
</colgroup>
<tr>
	<th>사이트명</th>
	<td><?=admin_text($config,"cf_name","width-200p")?></td>
	<th>사이트명(영문)</th>
	<td><?=admin_text($config,"cf_name_eng","width-200p")?></td>
</tr>
<tr>
	<th>대표도메인</th>
	<td colspan="3"><div class="form-inline"><?=admin_text($config,"cf_url","width-200p")?></div></td>
</tr>
</table>


<?=admin_title("메타태그 및 검색엔진설정","title_tbl")?>
<table class="table cols_tbl">
<colgroup>
	<col class="width-150p">
	<col >
	<col class="width-150p">
	<col>
</colgroup>
<tr>
	<th>웹브라우져 타이틀</th>
	<td><?=admin_text($config,"cf_title","width-200p")?></td>
	<th>즐겨찾기 아이콘</th>
	<td><?=admin_file($config,"cf_favicon","favicon")?> <?=admin_help("※ 파비콘 사이즈는 가로 32px x 세로 32px / 파일 형식은 ico로 등록해주세요")?></td>
</tr>
<tr>
	<th>메타태그 Description</th>
	<td colspan="3"><?=admin_text($config,"cf_meta_description")?></td>
</tr>
<tr>
	<th>메타태그 Keyword</th>
	<td colspan="3"><?=admin_text($config,"cf_meta_keyword")?></td>
</tr>
<tr>
	<th>대표이미지</th>
	<td><?=admin_file($config,"cf_image","og_image")?>	 <?=admin_help("※ 사이즈 800px *400px 파일로 등록해주세요.")?></td>
	<th>사이트맵</th>
	<td><?=admin_file($config,"cf_sitemap_xml","sitemap")?></td>
</tr>
<tr>
	<th>검색로봇설정</th>
	<td colspan="3">
		<?=admin_radio($config,"cf_robot_use")?>
	</td>
</tr>
<tr id="cf_robot_use_1" <?=$config[cf_robot_use]<>"1"?"class=\"hide\"":""?>>
	<th>robots.txt</th>
	<td colspan="3">		
	<?=admin_textarea($config,"cf_robot_txt","","rows=\"10\"")?>

	 <?=admin_help("※ 초기셋팅시 robots.txt 파일의 권한은 707이상으로 설정해 주셔야 합니다")?>
	</td>
</tr>
</table>


<?=admin_title("사업자정보","title_tbl")?>
<table class="table cols_tbl">
<colgroup>
	<col class="width-150p">
	<col >
	<col class="width-150p">
	<col>
</colgroup>
<tr>
	<th>상호명(법인명)</th>
	<td><?=admin_text($config,"cf_cp_name","width-200p")?></td>
	<th>대표자명</th>
	<td><?=admin_text($config,"cf_cp_ceo","width-200p")?></td>
</tr>
<tr>
	<th>사업자등록번호</th>
	<td><div class="form-inline"><?=admin_text($config,"cf_cp_number1","width-200p")?> <?=admin_help("※ 입력하면 사이트 하단에 자동으로 사업자정보가 표시됩니다.")?></div></td>
	<th>통신판매신고번호</th>
	<td><?=admin_text($config,"cf_cp_number2","width-200p")?></td>
</tr>
<tr>
	<th>업태</th>
	<td><?=admin_text($config,"cf_cp_type1","width-200p")?></td>
	<th>종목</th>
	<td><?=admin_text($config,"cf_cp_type2","width-200p")?></td>
</tr>
<tr>
	<th>대표전화</th>
	<td><?=admin_text($config,"cf_tel","width-200p")?></td>
	<th>대표이메일</th>
	<td><div class="form-inline"><?=admin_text($config,"cf_email","width-200p")?> <?=admin_help("※ 대표 이메일은  메일 발송시 기본 발송자이메일 정보로 사용됩니다.")?></div></td>
</tr>
<tr>
	<th>팩스번호</th>
	<td><?=admin_text($config,"cf_fax","width-200p")?></td>
	<th>인감이미지</th>
	<td><?=admin_file($config,"cf_stamp","stamp")?><?=admin_help("※ 가로x세로 74픽셀, jpg/png/gif만 가능합니다.")?></td>
</tr>
<tr>
	<th>사업장 주소</th>
	<td colspan="3">
	<div class="form-inline marbottom5"><?=admin_text($config,"cf_cp_zip","width-80p")?> <?=admin_button("find_cf_cp_zip","우편번호찾기","btn-gray btn-sm")?></div>
	<div><div class="form-inline marbottom5"><?=admin_text($config,"cf_cp_addr1","width-380p")?> <?=admin_text($config,"cf_cp_addr2","width-200p")?></div></div>
	</td>
</tr>
<tr>
	<th>개인정보보호 책임자</th>
	<td><?=admin_text($config,"cf_cp_privacy","width-200p")?></td>
	<th>개인정보보호책임자 이메일</th>
	<td><?=admin_text($config,"cf_cp_privacy_email","width-200p")?></td>
</tr>
</table>

<div class="text-center"><div class="form-inline"><?=admin_submit("fsubmit_btn", "환경설정", "btn btn-lg btn-black")?></div></div>

</form>


<script>
function fsubmit(f){
	f.action = "<?=$form?>";
	return true;	    
}	

$(document).on("click","input[name=cf_robot_use]",function(){ 
	nfor_radio_click("cf_robot_use",this.value);
});

$(document).on("click","#find_cf_cp_zip",function(){
	zipcode('cf_cp_zip', 'cf_cp_addr1', 'cf_cp_addr2');
});
</script>

<?php
include_once "tail.php";
?>