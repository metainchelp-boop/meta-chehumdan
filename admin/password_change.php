<?php
include_once "path.php";

$form = $_SERVER[PHP_SELF];
$table = "nfor_member";

if($mode=="update"){
	demo_check_json();

	if(!$password_1) json_return("현 비밀번호를 입력하셔야 수정이 가능합니다.","password_1");
	if(!$password_2) json_return("새 비밀번호를 입력하셔야 수정이 가능합니다.","password_2");

	if(sql_password($member[mb_id], $password_1) != $member[mb_password]) json_return("입력하신 현 비밀번호가 일치하지 않습니다.","password_2");

	if($password_2<>$password_3) json_return("입력하신 새 비밀번호가 서로 일치하지 않습니다.","password_3");
	
	password_change($member[mb_no], $password_3);

	json_return("패스워드가 정상적으로 변경되었습니다.", "ok");
}

include_once "head.php";

$write[mode] = "update";
?>


<form name="fwrite" id="fwrite" method="post" autocomplete="off">
<?=admin_hidden($write,"mode")?>

<table class="table cols_tbl margin0">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>현재 비밀번호</th>
	<td>
	<div class="form-inline">
	<?=admin_password($write,"password_1","width-200p")?> 
	<?=admin_help("※ 영문대문자/영문소문자/숫자/특수문자 중 2가지 이상 조합, 10~16자리 이하로 설정할 수 있습니다.")?>
	</div>
	</td>
</tr>
<tr>
	<th>새 비밀번호</th>
	<td><?=admin_password($write,"password_2","width-200p")?></td>
</tr>
<tr>
	<th>새 비밀번호 확인</th>
	<td><?=admin_password($write,"password_3","width-200p")?></td>
</tr>
</table>

<div class="bottom_btn"><div class="form-inline"><?=admin_submit("fsubmit_btn", "비밀번호 변경", "btn btn-lg btn-black")?></div></div>

</form>

<script>
$(document).on("click","#fsubmit_btn",function(){
	$.ajax({
		type:"post",
		data :$("#fwrite").serialize(),
		url:"<?=$form?>",
		success:function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				if(json["msg"]){
					alert(json["msg"]);
				}
				location.reload();
			} else{
				if(json["msg"]){
					alert(json["msg"]);
				}
				if(json["result"]){
					$("#"+json["result"]).focus();
				}
			}
		}
	});
	event.preventDefault();
});
</script>

<?php
include_once "tail.php";
?>