
<style>
.customer_tab_wrap { padding:15px 15px 0px; }
.customer_tab {  }
.customer_tab li{float:left; width:50%; text-align:center; background-color:#FFF; font-size:.8em; box-sizing: border-box; -webkit-box-sizing: border-box; letter-spacing:-.0625em;border-bottom: 3px solid #DCDCDC;}
.customer_tab li:nth-child(2){margin-left:-1px;}
.customer_tab li a{display:block;padding:10px;}
.customer_tab li.on{background-color:#FFF; color:#ff3478;  border-bottom: 3px solid #ff3478;}
.customer_tab li.on a{color:#ff3478; }
.customer_tab:after{display: block;clear: both;height: 0; line-height: 0;content: '';}
</style>

<div class="customer_tab_wrap">
	<ul class="customer_tab">
		<li <?=basename($PHP_SELF)=="customer_form.php"?"class='on'":""?>><a href="customer_form.php">1:1문의하기</a></li>
		<li <?=basename($PHP_SELF)=="customer_list.php" || basename($PHP_SELF)=="customer_view.php"?"class='on'":""?>><a href="customer_list.php">1:1문의답변확인</a></li>
	</ul>
</div>