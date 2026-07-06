<?php
// 카테고리 N+1 제거: depth 0+1 전체를 1쿼리로 로드 후 PHP에서 트리 구성 (LIKE prefix 동일, cg_rank 순서 보존)
$__cat_all = sql_query("select * from nfor_campaign_category where cg_use='1' and ((cg_depth='0' and cg_depth_id='0') or cg_depth='1') order by cg_rank asc");
$__cat_top = array(); $__cat_sub = array();
while($__c = sql_fetch_array($__cat_all)){
	if($__c[cg_depth]=='0') $__cat_top[] = $__c; else $__cat_sub[] = $__c;
}
for($i=0; $i<count($__cat_top); $i++){
	$row = $__cat_top[$i];
	$res = array();
	$res[category_id] = $row[category_id];
	$res[category_href] = "campaign_list.php?category_id=$row[category_id]";
	$res[category_name] = $row[cg_category];

	$reply_arr = array();
	for($j=0; $j<count($__cat_sub); $j++){
		$row_reply = $__cat_sub[$j];
		if(strpos($row_reply[category_id], $row[category_id]) === 0){
			$reply = array();
			$reply[category_id] = $row_reply[category_id];
			$reply[category_href] =  "campaign_list.php?category_id=$row_reply[category_id]";
			$reply[category_name] = $row_reply[cg_category];
			$reply_arr[] = $reply;
		}
	}
	$res[reply] = $reply_arr;


	$return["category_list"][] = $res;
}
include_once $nfor[path]."/html_head.php";
?>
<?include_once $nfor[skin_path]."popup.php";  // 검색?>
<script type="text/javascript">
<!--
var is_member = "<?=$is_member?>";
var nfor_path = "<?=$nfor[path]?>";
var nfor_url = "<?=$nfor[url]?>";
var nfor_name = "<?=$config[cf_name]?>";
$(document).ready(function(){
	$('.btn_top').click(function(){
		$('html,body').animate({scrollTop:0}, 1000);   
	});
});
$(window).scroll(function(){
	var scrollTop = $(document).scrollTop();
	if(scrollTop > 200){
		$('.btn_top').fadeIn();
	} else{
		$('.btn_top').fadeOut();
	}
});
//-->
</script>
<style>
<?php if($is_index){ ?>
#wrap { padding:56px 0px 57px 0px; }
<?php } elseif(basename($PHP_SELF)=="house_view.php"){ ?>
#wrap { padding:55px 0px 57px 0px; }
<?php } elseif(basename($PHP_SELF)=="house_map.php"){ ?>
#wrap { padding:55px 0px 57px 0px; }
<?php } elseif(basename($PHP_SELF)=="search.php"){ ?>
#wrap { padding:56px 0px 57px 0px; }
<?php } elseif(basename($PHP_SELF)=="mypage.php"){ ?>
#wrap { padding:0px 0px 57px 0px; }
<?php } else{ ?>
#wrap { padding:56px 0px 57px 0px; }
<?php } ?>

#container { min-height:300px; }
.top_move_btn { display:none; position:fixed; cursor:pointer; bottom:70px; right:15px; width:52px; height:52px; background:url(<?=$nfor[skin_path]?>img/layout.png) no-repeat -248px -50px; background-size:320px auto; z-index:999; } 
.sch_no_data{padding: 0 15px; text-align: center; background: #fff url(./skin/nfor/img/bg_no_result03.png) no-repeat 50% 40%;  background-size: 64px auto; font-size: 15px; color: #999;}
.sch_no_data p{min-height: 240px; padding: 250px 0px 0px;}
</style>

<?
if(basename($PHP_SELF)=="index.php" or basename($PHP_SELF)=="item_list_main.php"){
?>
<style>
#wrap { width:100%; padding:103px 0px 56px 0px; margin:0px; }
</style>
<?
} elseif(basename($PHP_SELF)=="item_list.php"){
?>
<style>
#wrap { width:100%; padding:56px 0px 56px 0px; margin:0px; }
</style>
<? } else{ ?>
<style>
#wrap { width:100%; padding:52px 0px 56px 0px; margin:0px; }
</style>
<? } ?>
<?php
include_once $nfor[skin_path]."inc_search.php";  // 검색
include_once $nfor[skin_path]."inc_side_left.php";  // 좌측사이드메뉴
?>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css"
  />
<div id="wrap">

	<header id="header">

		<? if($is_index){ ?>
		<nav id="gnb" class="line_none">
			<a class="btn_menu">사이드메뉴</a>			
			<a href="index.php" class="logo">홈으로</a>			
			<a class="btn_search">서치</a>
		</nav>		
		<? } else{ ?>
		<nav id="gnb">
			<a class="btn_back"></a>			
			<span class="txt_title"><?=$nfor[title]?></span>
			<a class="btn_search"></a>
		</nav>
		<? } ?>

	
		<?
		if(basename($PHP_SELF)=="index.php" or basename($PHP_SELF)=="item_list_main.php"){
		?>
		<style>
		.lnb{ white-space:nowrap; overflow-x:auto; -webkit-overflow-scrolling:touch; background-color:#fff; }
		.lnb ul{ display:table; width:100%; }
		.lnb li {  display:table-cell; border-bottom:solid 1px #ececec; width:20%; text-align:center; }
		.lnb li a{ color:#000; font-size:16px;  font-weight:bold; letter-spacing:-1px; display:block; padding:14px 10px 10px;  }
		.lnb li.active { border-bottom:solid 4px #ff3478; }
		.lnb li.active a{ color:#ff3478; font-weight:bold;  }
		.lnb::-webkit-scrollbar {display:none;}
		</style>
		<nav class="lnb">
		<ul>
			<?php
			for($i=0; $i<count($return["category_list"]); $i++){
				$category = $return["category_list"][$i];
			?>
			<li><a href="<?=$category[category_href]?>"><?=$category[category_name]?></a></li>
			<?php } ?>
<?
	$mem_chk_url =$nfor[https_url]."/metatech_list.php";
//	print_r2($config);
//$mem_chk_url= "#";
?>
				<?php if($config[cf_map_campaign_use]=="1"){ ?><li><a href="
<?=$mem_chk_url?>" class="menu" style="color:#3162C7;> <?=basename($PHP_SELF)==
$mem_chk_url?"on":""?>">메타테크</a></li><?php } ?>
			<?php if($config[cf_map_campaign_use]=="1"){ ?><li><a href="https://meta-chehumdan.com/banner_list.php" class="menu" style="color:#5D5D5D;> <?=basename($PHP_SELF)=="https://meta-chehumdan.com/banner_list.php"?"on":""?>">기획전</a></li><?php } ?>
				<?php if($config[cf_map_campaign_use]=="1"){ ?><li><a href="https://meta-chehumdan.com/notice_list.php" class="menu" target="_blank" style="color:#FF0000;> <?=basename($PHP_SELF)=="https://meta-chehumdan.com/notice_list.php"?"on":""?>">공지사항</a></li><?php } ?>
				
				

		</ul>
		</nav>
		<? } ?>
	</header>

	<style>
	#container { min-height:500px; }
	</style>

	<!-- container -->
	<div id="container">