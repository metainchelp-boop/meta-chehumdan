<?php
include_once "path.php";

if($mode=="update"){
	sql_query("update nfor_config set cf_email='$cf_email', cf_smtp_address='$cf_smtp_address', cf_smtp_port='$cf_smtp_port', cf_smtp_user='$cf_smtp_user', cf_smtp_pass='$cf_smtp_pass', cf_mail_head='$cf_mail_head', cf_mail_tail='$cf_mail_tail'");
	alert("정상적으로 수정되었습니다","mail_config.php");
	exit;
}

include_once "head.php";
?>
<form method="post" action="mail_config.php">
<input type="hidden" name="mode" value="update">





<?=admin_title("SMPT 계정 설정" ,"title_tbl")?>
<table class="table cols_tbl">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>대표이메일</th>
	<td>
		<div class="form-inline"><?=admin_text($config,"cf_email","width-100p")?> <?=admin_help("대표로 발송되는 이메일주소입니다")?></div>
	</td>
</tr>
<tr>
	<th>서비스신청</th>
	<td>
	<?=admin_a("a", "서비스신청", "btn-gray btn-sm", " target=\"_blank\"", "http://gmail.com")?>
	<?=admin_help("일반적으로 GMAIL 서버의 SMTP 서비스를 이용하며 타사 SMTP 서버를 이용하셔도 무방합니다")?>
	</td>
</tr>
<tr>
	<th>호스트주소</th>
	<td>
	<div class="form-inline"><?=admin_text($config,"cf_smtp_address")?></div>
	</td>
</tr>
<tr>
	<th>포트</th>
	<td>
	<div class="form-inline"><?=admin_text($config,"cf_smtp_port")?></div>
	</td>
</tr>
<tr>
	<th>아이디</th>
	<td>
	<div class="form-inline"><?=admin_text($config,"cf_smtp_user")?></div>
	</td>
</tr>
<tr>
	<th>패스워드</th>
	<td>
	<div class="form-inline"><?=admin_password($config,"cf_smtp_pass")?></div>
	</td>
</tr>
<tr>
	<th>구글SMTP사용시 유의사항</th>
	<td>
	<div class="form-inline"><?=admin_a("a", "설정변경", "btn-gray btn-sm", " target=\"_blank\"", "https://www.google.com/settings/security/lesssecureapps")?><?=admin_help("Gmail smtp 사용할 경우 보안 수준이 낮은 앱 설정을 사용해야 서버에서 발송이 가능합니다")?></div>
	</td>
</tr>
</table>






<?=admin_title("메일디자인 설정" ,"title_tbl")?>
<table class="table cols_tbl " >
<colgroup>
	<col class="width-150p ">
	<col >
</colgroup>
<tr>
	<th>메일 상단 디자인</th>
	<td><?=admin_textarea($config,"cf_mail_head","","rows='7'")?></td>
</tr>
<tr>
	<th>메일 하단 디자인</th>
	<td><?=admin_textarea($config,"cf_mail_tail","","rows='7'")?></td>
</tr>
</table>




<div class="text-center"><div class="form-inline"><?=admin_submit("fsubmit", "설정하기", "btn btn-lg btn-black")?></div></div>

</form>

<?php
include_once "tail.php";
?>