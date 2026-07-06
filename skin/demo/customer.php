<?php
include_once $nfor[skin_path]."cus_head.php";
?>

<style>
.faq_best { border:solid 1px #efefef; background-color:#fff; min-height:45px; margin-top:15px; padding:25px 20px; box-sizing:border-box; -webkit-box-sizing:border-box; }
.faq_best .tit{ font-weight:normal; font-size:19px; margin-bottom:20px; }
.faq_best .teb_lst { overflow:hidden; }
.faq_best .teb_lst li { float:left; width:14%; border-left:solid 1px #efefef; }
.faq_best .teb_lst li:nth-child(1) { border-left:none; }
.faq_best .teb_lst li img { display:block; margin:0px auto; }
.faq_best .teb_lst li .mnu { font-size:12px; text-align:center; color:#555; display:block; }
.faq_best .teb_lst li .on { color:#ff0000; font-size:12px; text-align:center; display:block; }

.faq_top_q, .faq_q { text-align:left; padding:15px 10px;font-size:13px; color:#000; position:relative; }
.faq_top_q a, .faq_q a{ display:block; }
.faq_tr, .faq_top_tr { display:none; }
.faq_tr , .faq_top_tr { text-align:left; padding:20px; background-color:#fafafa; font-size:13px; color:#666; font-weight:normal; }

.faq_list{ width:100%; margin-top:20px; }
.faq_list li{ color:#666; border-bottom:dashed 1px #EFEFEF;}
.faq_list li .q{display:inline-block; padding: 2px 5px;font-family:"Rubik"; background-color:#3b3b3b; color:#FFF; font-size:11px; margin-right:10px;}
.faq_list li .a{display:inline-block; padding: 2px 5px;font-family:"Rubik"; background-color:#ff0000; color:#FFF; font-size:11px; margin-right:10px;}
.faq_list li .txt{font-size:12px; color:#555;}
.faq_list li .icon{display:inline-block; width:100px; height:21px; line-height:21px; font-size:12px; position: absolute; top:11px; text-align:center; right:5px; letter-spacing:-1px;}
.faq_list li .col_1{border:solid 1px #595a5b; color:#595a5b}
.faq_list li .col_2{border:solid 1px #099c24; color:#099c24}
.faq_list li .col_3{border:solid 1px #ff5a00; color:#ff5a00}
.faq_list li .col_4{border:solid 1px #fc01ff; color:#fc01ff}
.faq_list li .col_5{border:solid 1px #0030ff; color:#0030ff}
.faq_list li .col_6{border:solid 1px #4d6377; color:#4d6377}
.faq_list li .col_7{border:solid 1px #535252; color:#535252}
.faq_list li .col_8{border:solid 1px #595a5b; color:#595a5b}
.faq_list li .col_9{border:solid 1px #595a5b; color:#595a5b}
.faq_list li .col_10{border:solid 1px #595a5b; color:#595a5b}


.bottom_wrap { overflow:hidden; margin-top:15px; width:100%; }

.bottom_wrap .notice{float:left; width:49.5%; height: 213px; padding:20px 10px; box-sizing:border-box; -webkit-box-sizing:border-box; background-color:#FFF; border:solid 1px #EFEFEF;}
.bottom_wrap .notice .notice_list {margin-left:15px;height:200px;}
.bottom_wrap .notice .notice_list .title_box  {font-weight:normal; font-size:19px ;margin-bottom: 15px;}
.bottom_wrap .notice .list_box li a{ position:relative; width:100%; font-size:12px; line-height: 18px; color:#555; display:block; overflow:hidden; white-space: nowrap;  text-overflow: ellipsis;}
.bottom_wrap .notice .list_box li a .txt{display:inline-block; width:300px; overflow:hidden; white-space: nowrap;  text-overflow: ellipsis}
.bottom_wrap .notice .list_box li .date{position: absolute; top:0px; right:10px;}

.bottom_wrap .cm { float:right; width:49.5%; height: 213px; padding:20px 15px; box-sizing:border-box; -webkit-box-sizing:border-box; background-color:#fff; border:solid 1px #efefef; }
.bottom_wrap .cm .notice_list { margin-left:15px; height:200px; }
.bottom_wrap .cm .title_box  { font-weight:normal; font-size:19px; margin-bottom:15px; }
.bottom_wrap .cm .list_box { overflow:hidden; }
.bottom_wrap .cm .list_box li{ float:left; margin-left:10px; }
.bottom_wrap .cm .list_box li img { display:block; }
.bottom_wrap .cm .list_box li span { font-size:12px; display:block; text-align:center; color:#555; margin-top:10px; }


.qna_serch { overflow:hidden; }

.faq_zone { float:left; border:solid 1px #efefef; width:49.5%; height: 172px; padding:25px 20px; box-sizing:border-box; -webkit-box-sizing:border-box; background-color:#fff; }
.faq_zone .tit { font-weight:normal; font-size:19px; margin-bottom:20px; }
.faq_zone input[type=text] { float:left; width:350px; height:33px; padding-left:9px; border:1px solid #c0c0c0; border-right:0; line-height:33px; color:#828284; font-size:12px; outline:none; }
.faq_zone .btn_search { float: left; width: 62px; height: 35px; background-color:#ff0000; border:none;color:#FFFFFF;font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal; cursor:pointer; }

.faq_zone .keyword { overflow:hidden; clear:left; padding-top:15px; font-size:13px; letter-spacing:-1px; font-weight:normal; margin-top:10px; }
.faq_zone .keyword .top5 { float:left; color:#ff5000; font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal; }
.faq_zone .keyword .top5 span { display:inline-block; width:3px; height:5px; margin:-3px 12px 0 6px; background:#959595; vertical-align:middle; }
.faq_zone .keyword ul { float:left; }
.faq_zone .keyword li { float:left; }
.faq_zone .keyword li .bar { padding:0 7px; color:#e5e5e5; }
.faq_zone .keyword li:first-child .bar { display:none; }
.faq_zone .keyword li a { color: #9a9a9a; font-size:11px; }
.faq_zone .keyword li a:hover { text-decoration:underline; }

.tel_zone{ float:right; border:solid 1px #efefef; width:49.5%; height:172px; padding:25px 20px; box-sizing:border-box; -webkit-box-sizing:border-box; background-color:#fff; }
.tel_zone .inner { overflow:hidden; box-sizing:border-box; -webkit-box-sizing:border-box;}
.tel_zone .ct_tel { float:left; width:205px; overflow:hidden; box-sizing:border-box; -webkit-box-sizing:border-box;}
.tel_zone .ct_tel .tel{ font-size:35px; font-family:"Rubik"; color:#000; letter-spacing:-1px; }
.tel_zone .ct_tel .online_btn { display:block; background-color:#3d3d3d; color:#fff; font-size:11px; padding:10px; margin-top:10px; text-align:center; width:160px; }
.tel_zone .time { float:left; font-size:11px; line-height:18px; color:#666;  box-sizing:border-box; -webkit-box-sizing:border-box;}
</style>


<div class="qna_serch">
	<div class="faq_zone">
		<form id="faq_form" action="faq.php" method="get" autocomplete="off">
		<h3 class="tit fotr">궁금하신점을 검색해주세요</h3>
		<?=admin_text($write,"faq_keyword","faq_keyword","placeholder=\"검색어를 입력하세요\"")?>
		<button type="submit" class="btn_search">검색</button>
		<div class="keyword">
			<strong class="top5">추천검색어<span class="bu"></span></strong>
			<ul>
				<?php
				for($i=0; $i<count($return["faq_keyword_list"]); $i++){
					$faq_keyword = $return["faq_keyword_list"][$i];
				?>
				<li><span class="bar">|</span><a href="faq.php?faq_keyword=<?=$faq_keyword[val_name]?>"><?=$faq_keyword[val_name]?></a></li>
				<?php } ?>
			</ul>			
		</div>
		</form>
	</div>
	<div class="tel_zone">
		<h3 class="tit fotr">고객센터</h3>
		<div class="inner">
			<div class="ct_tel">
			<span class="tel"><?=$config[cf_tel]?></span>
			<a href="help_form.php" class="online_btn">1:1온라인문의</a>
			</div>
			<div class="time">
			<? if($config[cf_call_time]){ ?>· 상담시간 : <?=$config[cf_call_time]?><br><? } ?>
			<? if($config[cf_break_time]){ ?>· 점심시간 : <?=$config[cf_break_time]?><br><? } ?>
			<? if($config[cf_closed_time]){ ?><em style="color:#ff0000;letter-spacing:-1px;"><span style="font-size:12px;">※</span> <?=$config[cf_closed_time]?></em><br><? } ?>
			<? if($config[cf_email]){ ?>· 대표메일 : <a href="mailto:<?=$config[cf_email]?>"><?=$config[cf_email]?></a><br><? } ?>
			<? if($config[cf_fax]){ ?>· 팩스번호 : <?=$config[cf_fax]?><? } ?>
			</div>
		</div>
	</div>
</div>


<div class="faq_best">

	<h3 class="tit fotr">자주 묻는 질문 TOP 5</h3>	

	<!-- <ul class="teb_lst">
		<?
		$i = 1;
		foreach($admin[fa_category] as $key=>$value){
		?>
		<li class="<?=$key==$fa_category?"on":""?><?=count($admin[fa_category])==$i?" col_last":""?>">
		<img src="<?=$nfor[skin_path]?>img/faqico<?=$i?><?=$key==$fa_category?"_on":""?>.png">
		<a href="faq.php?fa_category=<?=$key?>" class="<?=$key==$fa_category?"on":"mnu"?>"><?=$value?></a>
		</li>
		<?
			$i++;
		}
		?>
	</ul> -->

	<ul class="faq_list">
	<?php
	for($i=0; $i<count($return["faq_list"]); $i++){
		$faq = $return["faq_list"][$i];
	?>
	<li class="faq_q">
		<a href="javascript:faq_show('.faq_tr','<?=$faq[fa_id]?>');"><span class="q">Q</span> <?=$faq[fa_subject]?> <span class="icon col_1"><?=$faq[fa_category]?> </span></a>
	</li>
	<li class="faq_tr faq_tr_<?=$faq[fa_id]?>">
		<span class="a">A</span> <?=$faq[fa_memo]?>
	</li>
	<?php } ?>
	</ul>

</div>


<div class="bottom_wrap">

	<div class="notice">
		<div class="notice_list">
			<div class="title_box">
				<a href="notice_list.php" class="link fotr">공지사항</a>
			</div>
			<div class="list_box">
				<ul>
					<?php
					for($i=0; $i<count($return["notice_list"]); $i++){
						$notice = $return["notice_list"][$i];
					?>
					<li><a href="notice_view.php?no_id=<?=$notice[no_id]?>" class="link"><span class="txt"><?=$notice[no_category]?"[".$notice[no_category]."] ":""?><?=$notice[no_subject]?></span><span class="date"><?=$notice[no_insert_datetime]?></span></a></li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>

	<div class="cm">
		<div class="title_box">
			<a class="link fotr">자주 찾는 서비스</a>
		</div>
		<div class="list_box">
			<ul>
				<li><a href="member_confirm.php"><img src="<?=$nfor[skin_path]?>img/ser_icon1.png"><span>정보수정</span></a></li>
				<li><a href="order_list.php"><img src="<?=$nfor[skin_path]?>img/ser_icon4.png"><span>주문목록</span></a></li>
				<li><a href="<?=$member[mb_no]?"customer_list.php":"help_form.php"?>"><img src="<?=$nfor[skin_path]?>img/ser_icon3.png"><span>1:1문의</span></a></li>
				<li><a href="cooperation_form.php"><img src="<?=$nfor[skin_path]?>img/ser_icon2.png"><span>제휴문의</span></a></li>
			</ul>
		</div>
	</div>

</div>


<script>
$(document).on("click",".btn_search",function(){
	if($("#faq_keyword").val()==""){
		alert("검색어를 입력해주세요");
		$("#faq_keyword").focus();
	} else{
		$("#faq_form").submit();
	}
	event.preventDefault();
});
</script>

<?php
include_once $nfor[skin_path]."cus_tail.php";
?>