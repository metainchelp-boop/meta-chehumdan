<?php
include_once "path.php";

$admin[cf_logout_admin_use] = array("전체","사용함","사용안함");
$admin[cf_logout_member_use] = array("전체","사용함","사용안함");

$admin[cf_admin_ip_use] = array("전체","사용함","사용안함");
$admin[cf_shop_ip_use] = array("전체","사용함","사용안함");

$admin[cf_mouse_drag_use] = array("전체","사용함","사용안함");
$admin[cf_mouse_right_use] = array("전체","사용함","사용안함");

$admin[cf_password_asign_email_use] = array("전체","사용함","사용안함");
$admin[cf_password_asign_hp_use] = array("전체","사용함","사용안함");

$admin[cf_password_admin_use] = array("전체","사용함","사용안함");
$admin[cf_password_member_use] = array("전체","사용함","사용안함");

$admin[cf_password_day_type] = array(""=>"전체","month" => "개월", "day"=>"일");
$admin[cf_password_day_again_type] = array(""=>"전체","month" => "개월", "day"=>"일");


$table = "nfor_config";
$form = $_SERVER[PHP_SELF];

if($mode=="update"){
	demo_check();


	if($_REQUEST[cf_shop_ip] and in_array($_SERVER[REMOTE_ADDR], $_REQUEST[cf_shop_ip])) alert("설정자 아이피는 지정할수 없습니다");

	if(!$_REQUEST[cf_admin_ip]) $_REQUEST[cf_admin_ip] = array();

	if(!in_array($_SERVER[REMOTE_ADDR], $_REQUEST[cf_admin_ip])){
		array_push($_REQUEST[cf_admin_ip], $_SERVER[REMOTE_ADDR]);
	}

	$cf_shop_ip = json_encode($_REQUEST[cf_shop_ip],JSON_UNESCAPED_UNICODE); // php5.3 이상부터 적용
	$cf_admin_ip = json_encode($_REQUEST[cf_admin_ip],JSON_UNESCAPED_UNICODE); // php5.3 이상부터 적용




	sql_query("update $table set
	cf_shop_ip='$cf_shop_ip'
	, cf_admin_ip='$cf_admin_ip'
	, cf_logout_admin_use='$cf_logout_admin_use'
	, cf_logout_admin_time='$cf_logout_admin_time'
	, cf_logout_member_use='$cf_logout_member_use'
	, cf_logout_member_time='$cf_logout_member_time'
	, cf_admin_ip_use='$cf_admin_ip_use'
	, cf_shop_ip_use='$cf_shop_ip_use'
	, cf_mouse_drag_use='$cf_mouse_drag_use'
	, cf_mouse_right_use='$cf_mouse_right_use'
	, cf_password_asign_email_use='$cf_password_asign_email_use'
	, cf_password_asign_hp_use='$cf_password_asign_hp_use'
	, cf_password_admin_use='$cf_password_admin_use'
	, cf_password_member_use='$cf_password_member_use'
	, cf_password_day='$cf_password_day'
	, cf_password_day_type='$cf_password_day_type'
	, cf_password_day_again='$cf_password_day_again'
	, cf_password_day_again_type='$cf_password_day_again_type'");

	
	alert("정상적으로 수정되었습니다",$form);
}

include_once "head.php";
?>
 
<form name="fwrite" method="post" onsubmit="return fsubmit(this)">
<?=admin_hidden($config,"mode")?>
<?=admin_title("관리자 자동 로그아웃 설정(PC/모바일웹)" ,"title_tbl")?>

<table class="table cols_tbl " >
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>관리자 자동 로그아웃</th>
	<td>
	<div class="marbottom5">
	<?=admin_radio($config,"cf_logout_admin_use")?>
	<?=admin_help("※ 입점회원/MD/부관리자/최고관리자의 자동 로그아웃을 설정합니다")?>
	</div>
	
	<div id="cf_logout_admin_use_1">
		<div class="padding5p line_gray">로그인 후 <div class="form-inline"><?=admin_text($config,"cf_logout_admin_time","width-50p")?></div> 분간 클릭 없을시 자동 로그아웃</div></div>
	</div>
	</td>
</tr>
<tr>
	<th>회원 자동 로그아웃</th>
	<td>
	<div class="marbottom5">
	<?=admin_radio($config,"cf_logout_member_use")?>
	<?=admin_help("※ 일반회원의 자동 로그아웃을 설정합니다")?>
	</div>
	
	<div id="cf_logout_member_use_1">
		<div class="padding5p line_gray">로그인 후 <div class="form-inline"><?=admin_text($config,"cf_logout_member_time","width-50p")?></div> 분간 클릭 없을시 자동 로그아웃</div></div>
	</div>
	
	</td>
</tr>
</table>

<?=admin_title("IP 접속제한 설정","title_tbl")?>
<table class="table cols_tbl " >
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>관리자 접속 허용 IP</th>
	<td>
	<div class="marbottom5">
	<?=admin_radio($config,"cf_admin_ip_use")?><?=admin_help("※ 설정 변경하는 PC의 아이피가 등록되지 않으면 권한없음으로 설정 변경이 불가능해 지기 때문에 현재 아이피($REMOTE_ADDR)는 자동으로 추가됩니다")?>
	</div>
	<div id="cf_admin_ip_use_1">

		<div class="form-inline marbottom5"><?=admin_button("add_admin_ip","추가","btn btn-white btn-sm")?></div>
		<ul id="admin_ip_list" >
		<?
		$cf_admin_ip = json_decode($config[cf_admin_ip]);
		for($i=0; $i<count($cf_admin_ip); $i++){
		?>
		<li id="admin_ip_<?=$i?>" data-li_id="<?=$i?>"  class="marbottom5 form-inline">
			<input type="text" name="cf_admin_ip[]" value="<?=$cf_admin_ip[$i]?>"  class="form-control form-inline width-150p"> <input type="button" class="delete_admin_ip  btn btn-sm btn-gray" value="삭제" data-admin_id="<?=$i?>">
		</li>
		<? } ?>
		</ul>
		
	</div>

	</td>
</tr>
<tr>
	<th>사이트 접속차단 IP</th>
	<td>
	<div class="marbottom5">
	<?=admin_radio($config,"cf_shop_ip_use")?><?=admin_help("※ 추가할 아이피가 많을 경우 환경설정 > 아이피차단관리 메뉴를 이용해주세요")?>
	</div>
	<div id="cf_shop_ip_use_1">

		<div class="form-inline marbottom5"><?=admin_button("add_shop_ip","추가","btn btn-white btn-sm")?></div>
		<ul id="shop_ip_list">
		<?
		$cf_shop_ip = json_decode($config[cf_shop_ip]);
		for($i=0; $i<count($cf_shop_ip); $i++){
		?>
		<li id="shop_ip_<?=$i?>" data-li_id="<?=$i?>"  class="marbottom5 form-inline">
			<input type="text" name="cf_shop_ip[]" value="<?=$cf_shop_ip[$i]?>" class="marbottom5 form-inline">
			<input type="button" class="delete_shop_ip" value="삭제" data-shop_id="<?=$i?>"  class="delete_admin_ip btn btn-sml btn btn-sm btn-gray">
		</li>
		<? } ?>
		</ul>

	</div>
	</td>
</tr>
</table>

<?=admin_title("사이트 화면 보안 설정","title_tbl")?>
<table class="table cols_tbl " >
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>마우스 드래그 차단</th>
	<td><?=admin_radio($config,"cf_mouse_drag_use")?><?=admin_help("※ '사용함' 선택 시 마우스로 드래그하여 텍스트 내용을 선택할 수 없습니다.")?></td>
</tr>
<tr>
	<th>오른쪽 마우스 차단</th>
	<td><?=admin_radio($config,"cf_mouse_right_use")?><?=admin_help("※ '사용함' 선택 시 마우스 오른쪽 버튼을 클릭할 수 없습니다.")?></td>
</tr>
</table>

<?=admin_title("비밀번호 찾기 설정" ,"title_tbl")?>
<table class="table cols_tbl " >
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>이메일로 찾기</th>
	<td><?=admin_radio($config,"cf_password_asign_email_use")?> <?=admin_help("※ 환경설정 > 회원설정관리 > 기본정보에 이메일이 사용/필수로 설정되어 있어야 정상동작합니다")?></td>
</tr>
<tr>
	<th>휴대폰으로 찾기</th>
	<td><?=admin_radio($config,"cf_password_asign_hp_use")?> <?=admin_help("※ 환경설정 > 회원설정관리 > 기본정보에 휴대폰이 사용/필수로 설정되어 있어야 정상동작합니다")?></td>
</tr>
</table>

<?=admin_title("비밀번호 변경안내 설정","title_tbl")?>
<table class="table cols_tbl margin0" >
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>관리자 사용설정</th>
	<td><?=admin_radio($config,"cf_password_admin_use")?></td>
</tr>
<tr>
	<th>사용자 사용설정</th>
	<td><?=admin_radio($config,"cf_password_member_use")?></td>
</tr>
<tr>
	<th>비밀번호 변경 안내 주기</th>
	<td>
		비밀번호 최종 변경일을 기준으로 
		<div class="form-inline">
			<?=admin_text($config,"cf_password_day","width-50p")?> 
			<?=admin_select($config,"cf_password_day_type")?>
		</div> 
		마다 로그인시 안내함
		</div>
	</td>
</tr>
<tr>
	<th>비밀번호 다음에 변경하기 재안내 주기</th>
	<td>
	다음에 변경하기 버튼 클릭일 기준으로
	<div class="form-inline">
		<?=admin_text($config,"cf_password_day_again","width-50p")?>
		<?=admin_select($config,"cf_password_day_again_type")?>
	</div> 
	마다 로그인시 안내함
	</td>
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

$(document).on("click","input[name=cf_logout_admin_use]",function(){  
	nfor_radio_click("cf_logout_admin_use",this.value);
});

$(document).ready(function (){
	nfor_radio_click("cf_logout_admin_use","<?=$config[cf_logout_admin_use]?>");
	nfor_radio_click("cf_logout_member_use","<?=$config[cf_logout_member_use]?>");
	nfor_radio_click("cf_admin_ip_use","<?=$config[cf_admin_ip_use]?>");
	nfor_radio_click("cf_shop_ip_use","<?=$config[cf_shop_ip_use]?>");
});

$(document).on("click","input[name=cf_logout_member_use]",function(){  
	nfor_radio_click("cf_logout_member_use",this.value);
});

$(document).on("click","input[name=cf_admin_ip_use]",function(){  
	nfor_radio_click("cf_admin_ip_use",this.value);
});

$(document).on("click","#add_admin_ip",function(){  
	admin_id = $("#admin_ip_list li").last().data("li_id");
	admin_id = admin_id + 1;

	template = _.template($('#append_admin_ip_li').html());
	template_html = template({
		admin_id: admin_id,
	});
	$("#admin_ip_list").append(template_html);
});

$(document).on("click",".delete_admin_ip",function(){  
	$("#admin_ip_"+$(this).data("admin_id")).remove();
});

$(document).on("click","input[name=cf_shop_ip_use]",function(){  
	nfor_radio_click("cf_shop_ip_use",this.value);
});

$(document).on("click","#add_shop_ip",function(){  
	shop_id = $("#shop_ip_list li").last().data("li_id");
	shop_id = shop_id + 1;

	template = _.template($('#append_shop_ip_li').html());
	template_html = template({
		shop_id: shop_id,
	});
	$("#shop_ip_list").append(template_html);
});

$(document).on("click",".delete_shop_ip",function(){  
	$("#shop_ip_"+$(this).data("shop_id")).remove();
});
//-->
</script>

<script type="text/html" id="append_admin_ip_li">
<li id="admin_ip_<%=admin_id%>" data-li_id="<%=admin_id%>"  class="marbottom5 form-inline">
	<input type="text" name="cf_admin_ip[]" class="form-control form-inline width-150p"> <input type="button" class="delete_admin_ip btn btn-sm btn-gray" value="삭제" data-admin_id="<%=admin_id%>">
</li>
</script>

<script type="text/html" id="append_shop_ip_li">
<li id="shop_ip_<%=shop_id%>" data-li_id="<%=shop_id%>"  class="marbottom5 form-inline">
	<input type="text" name="cf_shop_ip[]" class="form-control form-inline width-150p"> <input type="button" class="delete_shop_ip btn btn-sm btn-gray" value="삭제" data-shop_id="<%=shop_id%>">
</li>
</script>

<?php
include_once "tail.php";
?>