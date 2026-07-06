<?php
include_once $nfor[skin_path]."head.php";
include_once $nfor[path]."/lib/div.lib.php";
?>
<style>
.cancel_list_top_wrap { padding:20px 10px; }
.cancel_list_tab  { border-top:solid 1px #999999; border-left:solid 1px #999999; overflow:hidden; }
.cancel_list_tab li { margin:0px 0px 0px -2px; background-color:#fff; float:left; width:50%; border-bottom:solid 1px #999999; border-right:solid 1px #999999; border-left:solid 1px #999999; }
.cancel_list_tab li a{ display:block; height:36px; line-height:36px; text-align:center; font-size:14px; letter-spacing:-1px; text-decoration:none; color:#999999; cursor:pointer; }
.cancel_list_tab li.on { background-color:#999999; }
.cancel_list_tab li.on a { color:#fff; font-weight:bold; }

.order_list_wrap { border-top:solid 1px #e1e1e1; padding:10px; background-color:#fff; }

.item_head { padding:3px 0px; color:#999; font-size:12px; }
.item_body { border:solid 1px #d8d5d5; margin-bottom:10px; background:#fff;  }
.item_title { position:relative; font-size:14px; color:#616161; padding:7px; border-bottom:1px solid #f1f1f1; background:#eee; }
.order_detail{ cursor:pointer; position:absolute; right:5px; top:6px; width:62px; border:solid 1px #ccc; background-color:#fff; font-size:12px; color:#3b3b3b; letter-spacing:-1px; text-align:center; display:block; padding:2px 0px; }
.item_content { display:block; overflow:hidden; border-top:solid 1px #eee;  }
.item_content li { position:relative; }
.item_img { position:absolute; left:10px; top:10px; width:75px; height:75px; }
.item_info_wrap { padding:10px 10px 10px 95px; }
.item_name1{ height:14px; font-size:14px; line-height:14px; overflow:hidden; color:#000; font-weight:bold; }
.item_name2{ height:36px; font-size:14px; line-height:18px; margin:3px 0px 5px 0px; color:#3a3a3a; overflow:hidden; }
.item_name3{ font-size:14px; line-height:18px; margin:3px 0px 0px 0px; color:#3a3a3a;}

.item_tail { border-top:solid 1px #e1e1e1; }
</style>

<div class="cancel_list_top_wrap">

	<ul class="cancel_list_tab">
		<li<?=basename($PHP_SELF)=="cancel_list.php"?" class='on'":""?>><a href="cancel_list.php">취소/반품 현황</a></li>
		<li<?=basename($PHP_SELF)=="bank_account.php"?" class='on'":""?>><a href="bank_account.php">무통장 환불계좌 설정</a></li>
	</ul>

</div>

<div class="order_list_wrap">
	<?
	for($i=0; $order=sql_fetch_array($result); $i++){
		echo item_div_print($order);
	}
	?>
</div>

<?
include_once $nfor[skin_path]."tail.php";
?>