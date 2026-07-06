<?php
include "html_head.php";
?>
<link href="<?=$nfor[skin_path]?>css/style.css?time=<?=time()?>" rel="stylesheet" type="text/css" />

<style>

.bad_report_wrap{width:100%; font-family:"Nanum Gothic"; padding:10px; box-sizing:border-box; -webkit-box-sizing:border-box;}
.bad_report_title{border-bottom:1px solid #000; height:25px; line-height:25px; padding:10px; font-size:20px;font-weight:bold;}



.table1_list{border-top: 1px solid #717171; font-size:12px;font-family:"Nanum Gothic"; margin-bottom:10px; margin-top:20px;}
.table1_list th{padding: 10px 0;border-bottom: 1px solid #dbdbdb; background: #f7f7f7; color:#444; font-weight:normal;}
.table1_list td{border-bottom: 1px solid #dbdbdb;color:#444; padding:10px; }
.btngo3{display:inline-block;width:100px; font-weight:bold; padding:8px 20px; text-align:center; font-size:12px; background-image: none; background:#4f4e4d; border:1px solid #4f4e4d; color:#FFF; margin:20px auto;}
.btngo3:hover{color:#FFF}
.btn_del{display:inline-block; width:80px; font-weight:bold; padding:8px 20px;text-align:center;  font-size:12px; background-image: none; border:1px solid #4f4e4d; color:#4f4e4d; margin-right:5px; font-family:'Malgun Gothic';}
</style>
<div class="bad_report_wrap">
<div class="bad_report_title fotm">쪽지보내기</div>
<div class="bad_report_content">
<form method="post" action="note_form.php">
<input type="hidden" name="mode" value="insert">

<table cellpadding="0" cellspacing="0" border="0" class="table1_list" style="width:100%;" align="center">
<tr>
	<th><b>받는사람</b></th><td><input type="hidden" name="no_receive_id" value="<?=$no_receive_id?>" class="inp"> <?=mb_nick($no_receive_id)?>(<?=$no_receive_id?>)</td>
</tr>
<tr>
	<td colspan="2"><textarea name="no_memo" rows="17" style="width:96%; height:250px;"  class="inp"></textarea></td>
</tr>
</table>

<div  style="margin-top:10px; text-align:center;">
	<input type="submit" value="쪽지보내기" class="btngo3"> <input type="button" value="취소" class="btn_del" onclick="window.close()">
</div>

</div>
</form>
</div>

<?php
include "html_tail.php";
?>
