<?php
include_once $nfor[skin_path]."head.php";
?>

<div class="customer_main_wrap">
	<div class="faq_group_top">
		<h2>자주묻는 질문</h2>
		<div class="serach_wrap">
		<form name="faq.php">
			<input type="search" name="keyword" placeholder="검색어를 입력하세요" value="<?=htmlspecialchars($keyword)?>" class="search_input">
			<a  class="search_btn">검색</a>
			</form>
		</div>
		<div class="faq_list_wrap">
			<ul >
			<? foreach($admin[fa_category] as $key=>$value){ ?>	
			<li data-fa_category="<?=$key?>" <?=$key==$fa_category?"class='on'":""?>><a <? if(!$scroll_load){ ?>href="faq.php?fa_category=<?=$key?>"<? } ?>><?=$value?></a></li>
			<? } ?>
			</ul>
		</div>
	</div>
	<div class="center_group">
	<a class="customer_cc" href="customer_form.php">1:1 문의하러가기</a>
	<div class="customer_tel">
		<span class="left">
			고객센터 
			<b class="txt_num c_num"><?=$config[cf_tel]?> </b>
		</span>
		<span class="left">
			<?=$config[cf_call_time]?>
			<a href="cooperation_form.php">광고및 제휴문의 하기</a>
		</span>
		<b class="icon_customer"></b>
	</div>
	</div>
	<div class="notice_group">
		<h2>공지사항<a class="more" href="notice_list.php">더보기 +</a></h2>
		<ul class="notice_list">
			<?
				$que = sql_query("select * from nfor_value where val_code='notice' order by val_rank asc limit 5");
				for ($i=0; $row=sql_fetch_array($result); $i++) {
			?>
			<li>
				<a href="notice_view.php?no_id=<?=$notice[no_id]?>"">
				<span class="cate">[<?=$notice[no_category]?>]</span>
				<span class="date">조회 : 24   등록일시 : <?=date("Y.m.d",strtotime($row[no_insert_datetime]))?></span>	
				<span class="subject"><?=$notice[no_subject]?></span>
				</a>
			</li>
			<? } ?>
		</ul>
	</div>
</div>
<?php
include_once $nfor[skin_path]."tail.php";
?>