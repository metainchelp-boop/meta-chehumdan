<style>

#side_left_close { cursor:pointer; display:block; border-left: 1px solid #393939; background:url("<?=$nfor[skin_path]?>img/close_ico_btn.png")center no-repeat ; background-size: 15px auto; height:50px; width:50px; position:absolute; top:15px; right:10px; z-index:99999; }



#side_left_wrap { display:none; left:-0px;  width:100%; position:fixed; z-index:99999; background-color: 1px solid #eee; }
#side_left { background-color:#fff; height:100%; width:100%; box-shadow:2px 0px 3px rgba(0,0,0,.7); overflow-y:scroll; -webkit-overflow-scrolling:touch;  }

#side_left_bg { display:none;  cursor:pointer; width:100%; background-color:rgba(0,0,0,0.5); height:100%; position:fixed; top:0; z-index:99990; }
#side_left::-webkit-scrollbar {display:none;}


.side_left_top { background:#222;  position:relative;  line-height:80px;  }
.side_left_top .login_msg { color:#fff; margin-left:10px; }
.side_left_top .login_btn { background-color:#ff3478; color:#fff; font-size:14px; line-height:13px; display:inline-block; vertical-align:0px;margin-left:5px;  padding:10px 20px;  -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}
.side_left_top .join_btn { background-color:#ff3478; color:#fff; font-size:14px; line-height:13px; display:inline-block; vertical-align:0px;margin-left:5px;  padding:10px 20px;  -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}

.side_left_top .m_name{margin-left:10px;  display:inline-block;font-size:16px;line-height:24px; margin-top:10px; color:#FFF;}
.side_left_top .m_id{display:inline-block;font-size:13px; line-height:18px; margin-bottom:10px; color:#efefef;}

.link_group{overflow:hidden; border-top: 1px solid #000;background-color: #222; white-space: nowrap;}
.link_group li{float:left; width:25%; text-align:center; line-height:50px;}
.link_group li a{display:block; font-size:13px; background-color:#222; letter-spacing:-1px; color:#fff; line-height:24px;}
.link_group li a img{display:block; width:30px; margin:10px auto 0px;}
.link_group li a span{line-height:12px; display:block; margin-bottom:10px; margin-top:5px;}
.link_group li + li{border-left:solid 1px #000; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}


.cooperation{ position:relative; } 
.customer{ position:relative; } 
.list_customer{display:block; border-bottom: 1px solid #ccc; color:#000; font-size:16px; font-weight:bold; padding:15px 20px;}

.cate_list_top{display:block; border-bottom: 2px solid #000; color:#000; font-size:16px; font-weight:bold; padding:15px 20px;}

.side_left_category{border-top:solid 1px #eee;  border-bottom: 1px solid #ccc;  -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}
.sub_category { display:none;  overflow:hidden;}
.sub_category li { float:left; width:50%; background-color:#f9f9f9; border-bottom:solid 1px #f1f1f1; font-size:14px; color:#999; height:45px; line-height:45px; padding-left:20px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}
.sub_category li:nth-child(even){border-left:solid 1px #f1f1f1}


.category_mnu a { display:block; cursor:pointer; border-bottom:solid 1px #eee;  height:45px; line-height:45px; padding-left:20px; position:relative; font-size:14px; color:#666;   -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}
.category_mnu a .arrow_off { position: absolute;  right: 10px; top: 19px;  display: inline-block;width: 16px; height: 9px; background-position: -200px -200px;  background-image: url('<?=$nfor[skin_path]?>img/layout.png');  background-repeat: no-repeat; background-size: 320px auto; overflow: hidden;text-indent: -1000px;}
.category_mnu a .arrow_on {  position: absolute;   right: 10px;   top: 19px;  display: inline-block; width: 16px; height: 9px; background-position: -248px -200px; background-image: url('<?=$nfor[skin_path]?>img/layout.png');background-repeat: no-repeat;  background-size: 320px auto; overflow: hidden;  text-indent: -1000px;}

.gong{height:10px; background-color:#eee;}

/*하단 배너*
.m_banner{width:100%;}
.m_banner ul{overflow:hidden; border-top:solid 1px #eee; }
.m_banner ul li{float:left; width:50%; border-right:solid 1px #eee; border-bottom:solid 1px #eee;text-align:center}
.m_banner ul li:nth-child(even){border-right:none; margin-left:-1px;}
.m_banner ul li a{display:inline-block;width:100%; font-size:13px; line-height:35px;}
/**/
.snb_cs {padding: 16px 0 19px; color: #898989; background: #fff;text-align:center; border-bottom: 1px solid #ccc; border-top: 1px solid #eee;}
.snb_cs h2 {  font-size: 13px;  line-height: 1;}
.snb_cs a { font-size: 21px; font-weight: 700;}
.snb_cs p {  margin-top: 2px;  font-size: 12px;}

</style>

<div id="side_left_bg"></div>
<div id="side_left_wrap">
	
	<div id="side_left">

		<div class="side_left_top">
		<a id="side_left_close"></a>
			<?php if($member[mb_no]){ ?>
			<span class="m_name"><?=$member[mb_nick]?$member[mb_nick]:$member[$member_config[cf_mb_id_type]]?>님</span><span class="m_id txt_num">(<?=$member[mb_id]?>) </span><a href="logout.php" class="login_btn">로그아웃</a>
			<?php } else{ ?>
			<span class="login_msg"><a href="login.php" class="login_btn">로그인</a> </span>
			<?php } ?>
		</div>

		<div class="link_group">
			<ul>
				<li><a href="cooperation_form.php" class="cooperation"><img src="/skin/nfor/img/side_top1.png"><span>제휴문의</span></a></li>
				<li><a href="customer_form.php" class="customer"><img src="/skin/nfor/img/side_top4.png"><span>1:1문의</span></a></li>
				<li><a href="notice_list.php" class="cooperation"><img src="/skin/nfor/img/side_top2.png"><span>공지사항</span></a></li>
				<li><a href="faq.php" class="customer"><img src="/skin/nfor/img/side_top3.png"><span>FAQ</span></a></li>
			</ul>
		</div>
	
		<a href="customer_main.php" class="list_customer">고객센터</a>

		<div class="gong"></div>
		<div class="side_left_category">
			<div class="category_mnu">
				<span class="cate_list_top">카테고리</span>
				
				<?php
				for($i=0; $i<count($return["category_list"]); $i++){
					$category = $return["category_list"][$i];
				?>	
				<a <?php if(!count($category["reply"])){ ?>href="<?=$category[category_href]?>"<?php } else{ ?>class="depth_1"<?php } ?> data-category_id="<?=$category[category_id]?>"><?=$category[category_name]?><?php if(count($category["reply"])){ ?><b class="arrow arrow_off"></b><?php } ?></a>
				
				<ul class="sub_category" id="mnu_<?=$category[category_id]?>">	
					<?php
					for($k=0; $k<count($category["reply"]); $k++){
						$sub_category = $category["reply"][$k];
					?>
					<li><a href="<?=$sub_category[category_href]?>"><?=$sub_category[category_name]?></a></li>
					<?php } ?>
				</ul>
				<?php } ?>
					
		
			</div>		

		</div>
		<div class="gong"></div>
		<div class="snb_cs">
              <h2>전화 문의</h2>
              <a href="tel:<?=$config[cf_tel]?>"><?=$config[cf_tel]?></a>
              <p class="cs_time"><?=$config[cf_time]?></p>
         </div>
		<div style="height:300px;background-color:#eee;"></div>


	</div>

</div>


<SCRIPT LANGUAGE="JavaScript">
<!--
$(document).ready(function(){
	resize_slide();
});

$(window).on('resize',function(){ setTimeout(resize_slide(),0); });
$(window).bind('orientationchange', function(e){ setTimeout(resize_slide(),0); });


function resize_slide(){
	$('#side_left, #side_left_bg').height( window.innerHeight );
	if( $('#side_left').is(':visible') ) { $('body, html').css({'height':window.innerHeight,'width':window.innerWidth}); }
}


$(document).on("click", ".depth_1", function(){
	var category_id = $(this).data("category_id");

	if($("#mnu_"+category_id).css("display")=="none"){
		$(".sub_category").hide();
		$("#mnu_"+category_id).show();
		$("#side_left").scrollTo($(this),300);
		$(".arrow").removeClass('arrow_on').addClass('arrow_off');
		$(this).find('.arrow_off').removeClass('arrow_off').addClass('arrow_on');
	} else{
		$("#mnu_"+category_id).hide();	
		$("#side_left").scrollTo("body",300);
		$(this).find('.arrow_on').removeClass('arrow_on').addClass('arrow_off');
	}
});

$(document).on("click", ".btn_menu", function(){
	$("#side_left_bg").show();
	$("#side_left_wrap").animate({"left":"0px"}, 500 ).show();
	$("html").css("overflow-y","hidden").css("position","fixed");
	$("body").css("overflow-y","hidden").css("position","fixed");

});
$(document).on("click", "#side_left_bg, #side_left_close", function(){
	$("#side_left_bg").hide();
	$("#side_left_wrap").animate({"left":"-1000px"}, 500 ).show();
	$("html").css("overflow-y","").css("position","");
	$("body").css("overflow-y","").css("position","");
});
//-->
</SCRIPT>