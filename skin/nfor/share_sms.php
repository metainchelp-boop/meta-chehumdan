<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.share_sms_wrap { margin:0px; padding:10px 10px; width:100%; box-sizing:border-box; -webkit-box-sizing:border-box;  }
.row { margin-bottom:5px; }
.info_msg { color:#888; font-size:13px; letter-spacing:-1px; }
.copy_msg { width:100%; height:100px; padding:10px 10px; border:solid 1px #d4d4d4; background-color:#fff; color:#222; font-size:14px; letter-spacing:-1px; }


.copy_btn { display:block; height:40px; line-height:40px; margin:10px 0px; text-align:center; background-color:#e83862; border:solid 1px #e83862; color:#fff; font-size:16px; font-weight:bold; }

</style>

<div class="share_sms_wrap">

	<div class="row">
		<span class="info_msg">상품정보를 복사하셔서 문자메시지를 발송하세요.</span>
	</div>
	<div class="row">

<textarea id="copy_msg" class="copy_msg">[<?=$config[cf_name]?>] <?=$item[it_name]?> 
<?=$item[it_description]?> 
<?="$nfor[url]/item.php?it_id=$item[it_id]"?></textarea>

	</div>

	<a data-clipboard-target="#copy_msg" class="copy_btn">복사</a>

</div>



<script>
new ClipboardJS('.copy_btn');
</script>

<?php
include_once $nfor[skin_path]."tail.php";
?>