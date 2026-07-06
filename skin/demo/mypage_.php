<?php
include_once $nfor[skin_path]."mypage_head.php";
?>
<style>
.my_page_set1 { border:solid 1px #efefef; padding:25px 20px; box-sizing:border-box; -webkit-box-sizing:border-box; overflow:hidden; background-color:#fff; }
.my_page_set1 .my_zone1{ overflow:hidden; border-right:solid 1px #efefef; width:49.5%; float:left; }
.my_page_set1 .my_zone1 li { float:left; }
.my_page_set1 .my_zone1 li:nth-child(2) { float:left; margin-left:30px; }
.my_page_set1 .my_zone1 li span { display:block; }
.my_page_set1 .my_zone1 li .title { font-size:21px; height:35px; line-height:35px; margin-top:5px; }
.my_page_set1 .my_zone1 li .date { font-size:12px; color:#666;  height:25px; line-height:25px; }
.my_page_set1 .my_zone1 li .btn { padding:5px 10px; background-color:#ff0000; color: #FFF; font-size:12px; margin-top:5px; display:inline-block; }
.my_page_set1 .my_zone2 { overflow:hidden; float:left; padding-left:20px; margin-top:5px; } 
.my_page_set1 .my_zone2 li { float:left; width:135px; text-align:center; padding:0px 0px; }
.my_page_set1 .my_zone2 li span { display:block; font-size:12px; color:#555; }
.my_page_set1 .my_zone2 li img { margin: 5px 0px; }
.my_page_set1 .my_zone2 li strong { color:#ff0000; }

.my_page_set2{border:solid 1px #efefef; background-color:#FFF; min-height: 45px; margin-top:15px;  padding:25px 20px; box-sizing:border-box; -webkit-box-sizing:border-box;overflow:hidden}
.my_page_set2{font-size:13px;}
.my_page_set2 .none{height:150px;color:#666; font-size:11px;letter-spacing:-1px; width:100%;text-align:center;}
.my_page_set2 .title{overflow:hidden; margin-top:0px; margin-bottom:0px;height:24px;}
.my_page_set2 .title .h_txt{float:left;display:inline-block;margin-left:-1px; margin-right:15px;font-size:19px;color:#16181a;}
.my_page_set2 .title .h_txt_sub{float:left;display:inline-block; margin-top:5px;color:#666;line-height:24px;}
.my_page_set2 .title .h_more{float:right;display:inline-block; margin-top:13px;margin-right:5px;font-size:11px;color:#666; letter-spacing:-1px;}
.my_page_set2 .tbl td .price{font-size:15px;font-family:tahoma;font-weight:bold;vertical-align:middle;color:#f05a23;}
.my_page_set2 .tbl{width:100%;margin-top:10px;border-top:2px solid #4f525c;border-bottom:1px solid #dbdee6; margin-bottom:20px;}
.my_page_set2 .tbl th{padding:11px 0 11px;;background-color:#f5f5f5;text-align:center;vertical-align:middle;color:#666; font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal; border-bottom: 1px solid #dfdfe2; ;}
.my_page_set2 .tbl td{padding:11px 11px 11px 0px;border-bottom:1px solid #edeff4;color:#333;background-color:#fff; text-align:center;  border-bottom: 1px solid #dfdfe2;}
.my_page_set2 .tbl td em{display:block;font-size:12px;font-family:verdana;color:#8c8f9a;}
.my_page_set2 .tbl td .bank_date{display:block;font-size:11px;font-family:verdana;color:#8c8f9a;letter-spacing:-1px;}
.my_page_set2 .tbl td .bank_info{display:block;font-size:11px;font-family:verdana;color:#8c8f9a;letter-spacing:-1px;}
.my_page_set2 .tbl td strong{display:block; line-height:35px;font-size:12px;font-family:verdana;color:#545456;}

.my_page_set2 .product_info{overflow:hidden;}
.my_page_set2 .product_info .thumb{float:left;overflow:hidden;width:106px;height:83px;}
.my_page_set2 .product_info .thumb img{float:left;width:100%;;}
.my_page_set2 .product_info .info{float:left;text-align:left;padding:15px 20px;}
.my_page_set2 .product_info .info .pro_name{display:block;color:#3d4058; font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;height:30px;}
.my_page_set2 .addr{display:block;font-size:11px; background-color:#fafafa; width:100%; padding: 10px; text-align:left; margin-top:10px; border:1px solid #e7e7e9; box-sizing:border-box; -webkit-box-sizing:border-box}
.my_page_set2 .opt_info{margin-top:5px;margin-bottom:1px;padding:8px 19px 5px 19px;background:#eaebf0;letter-spacing:-1px;line-height:17px;text-align:left;}
.my_page_set2 .opt_info .opt_name{ display:block;color:#3d4058;font-weight:bold;}
.my_page_set2 .opt_info .opt_num{ display:block;font-size:11px;color:#616372; margin-top:5px;}
.my_page_set2 .opt_info .opt_ticket{display:block;font-size:11px;color:#616372;}


.my_page_set2 .tbl td .btn01{display:block; padding:5px; margin:5px auto;border:solid 1px #dcdcdc;font-size:11px;letter-spacing:-1px;color:#666;}
.my_page_set2 .tbl td .btn02{display:block; padding:5px; margin:0px auto 5px;border:solid 1px #9297a8; background-color:#9297a8;font-size:11px;letter-spacing:-1px;color:#FFF;}
.my_page_set2 .tbl td .btn03{display:block; padding:5px; margin:0px auto 5px;border:solid 1px #ff6600; background-color:#ff6600;font-size:11px;letter-spacing:-1px;color:#FFF;}
.my_page_set2 .tbl td .btn04{display:block; padding:5px; margin:0px auto 5px;border:solid 1px #3399ff; background-color:#3399ff;;font-size:11px;letter-spacing:-1px;color:#FFF;}

.my_page_set3 .board { width:49.5%;border:solid 1px #efefef; background-color:#FFF; min-height: 45px; margin-top:15px; height:215px; padding:25px 20px; box-sizing:border-box; -webkit-box-sizing:border-box} 
.my_page_set3 .fl{float:left;}
.my_page_set3 .fr{float:right;}
.my_page_set3 .board .title_box  {font-weight:normal; font-size:19px ;margin-bottom: 15px;}
.my_page_set3 .board .list_box li a{ position:relative; font-size:12px; line-height: 20px; color:#555; display:block;}
.my_page_set3 .board .list_box li .btn{position: absolute; top:0px; right:10px; display:inline-block; text-align:center; border:solid 1px #888; color:#888; letter-spacing:-1px; padding:0px 10px; line-height:16px; font-size:11px; width:50px;}
.my_page_set3 .board .list_box li .date{position: absolute; top:0px; right:10px;}
.my_page_set3 .board .list_box li .txt{display:inline-block; width:300px; overflow:hidden; white-space: nowrap;  text-overflow: ellipsis}

.event_zone{border:solid 1px #efefef; background-color:#FFF; min-height: 45px; margin-top:15px;  padding:25px 20px; box-sizing:border-box; -webkit-box-sizing:border-box;overflow:hidden}
.event_zone .title{font-weight:normal; font-size:19px;margin-bottom: 20px; display:block; position:relative;}
.event_zone .title img{position: absolute; right:10px; top:10px;}
.event_zone .event_list{overflow:hidden;width:100%; font-size:16px;}
.event_zone .event_list a{color:#666;}
.event_zone .event_list a:hover{color:black;text-decoration:underline}
.event_zone .event_list li{float:left; overflow:hidden;position:relative;width:250px;padding:20px; border:solid 1px #efefef;  margin-left:10px; margin-bottom:10px;} 
.event_zone .event_list li:nth-child(3n+1){margin-left:0px;} 
.event_zone .event_list .thumb{margin-right:30px;border:1px solid #e3e3e3}
.event_zone .event_list h2{margin:5px 0 4px;font-size:14px; line-height:20px; color:#666;}
.event_zone .event_list h2 span{display:inline-block;color:#FFF; padding:5px 5px 3px 5px;height:12px; line-height:12px;text-align:center;}
.event_zone .event_list h2 span.ico_ing{background-color:#ff0000; }
.event_zone .event_list h2 span.ico_end{background-color:#666; }
.event_zone .event_list span{color:#ff7713;font-weight:bold;font-size:11px; letter-spacing:-1px;}
.event_zone .event_list p{margin:9px 0 11px;color:#8a8a8a;line-height:16px;font-size:12px;}
</style>

<div class="my_page_set1">
	<div class="my_zone1">
		<ul>
			
			<li>
			<span class="title fotr"><?=$return[mb_id]?>님 안녕하세요</span>
			<span class="date">마지막 접속일 <?=$return[mb_login_datetime]?></span>
			<a class="btn" href="member_confirm.php">회원정보수정</a>
			</li>
		</ul>
	</div>
	<div class="my_zone2">
		<ul>
			<li>
			<a href="order_list.php">
				<span>최근 3개월내 주문</span>
				<img src="<?=$nfor[skin_path]?>img/my_ico1.png">
				<span>구매<strong><?=$return[order_count]?></strong>/ 배송완료 <strong><?=$return[delivery_count]?></strong></span>
			</a>
			</li>
			<li>
			<a href="zzim_list.php">
				<span>찜한내역</span>
				<img src="<?=$nfor[skin_path]?>img/my_ico3.png">
				<span><strong><?=$return[zzim_count]?></strong>건</span>
			</a>
			</li>
			<li>
			<a href="member_confirm.php">
				<span>이메일</span>
				<img src="<?=$nfor[skin_path]?>img/my_ico4.png">
				<span><?=$return[mb_email]?></span>
			</a>
			</li>
		</ul>
	</div>
</div>





<div class="my_page_set3">
	<div class="board fl">

		<div class="title_box">
			<a href="notice_list.php" class="link fotr">공지사항</a>
		</div>
		<div class="list_box">
			<ul>
				<?php
				for($i=0; $i<count($return["notice_list"]); $i++){
					$notice = $return["notice_list"][$i];
				?>
				<li>
					<a href="notice_view.php?no_id=<?=$notice[no_id]?>" class="link">
						<span class="txt">[<?=$notice[no_category]?>] <?=$notice[no_subject]?></span>
						<span class="date"><?=$notice[no_insert_datetime]?></span>
					</a>
				</li>
				<?php } ?>
			</ul>
		</div>

	</div> 
	<div class="board fr">

		<div class="title_box">
			<a href="customer_list.php" class="link fotr">문의내역</a>
		</div>
		<div class="list_box">
			<ul>
				<?php
				for($i=0; $i<count($return["customer_list"]); $i++){
					$customer = $return["customer_list"][$i];
				?>
				<li><a href="customer_view.php?cs_id=<?=$customer[cs_id]?>" class="link"><?=$customer[cs_subject]?><span class="btn"><?=$customer[cs_reply_state]?></span></a></li>
				<?php } ?>
			</ul>
		</div>
		
	</div>
</div>
<?
include_once $nfor[skin_path]."mypage_tail.php";
?>
