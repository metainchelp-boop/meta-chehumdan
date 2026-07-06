<style>
.opt_chg_bg { position:fixed; left:0; top:0; width:100%; height:100%; z-index:1000; overflow-y:auto; display:none;} 
.opt_chg_box { position: absolute; margin:-350px 0 0 -250px; top:50%; left:50%; background-color:#fff;   border: 1px solid #c2c7cc; width:500px; min-height:500px; z-index:1001;   padding: 39px 30px 40px;}
.pro_opt_pop{ position: relative;min-height: 80px;margin-top: 30px;}
.pro_opt_pop .prod_thumb {position: absolute; top: 0; left: 0; overflow: hidden; position: absolute; width: 120px; height: 80px;}
.pro_opt_pop .prod_thumb img{width:100%;}
.pro_opt_pop .it_name{display:block; margin-left: 149px;padding-top: 10px;  letter-spacing: -.08em;font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;}
.pro_opt_pop .it_description{margin-left: 149px; margin-top:10px; display:block; font-size: 11px; color: #959da6; font-family: "돋움",dotum,"Apple SD Gothic Neo",sans-serif;}
.opt_chg_close_wrap { text-align:right; }
.opt_chg_submit_wrap { text-align:center;  margin-top:30px;}
.opt_title1{margin-top:30px; font-size:16px; color:#000;padding-left:5px;}
.opt_chg_result{margin-top:10px;}
.opt_chg_submit_btn{display: inline-block;width: 136px;height: 42px; border: 1px solid #ff5000; font-weight: 700;font-size: 14px;  line-height: 42px;text-align: center; margin-left: 6px; background-color: #e61b62;;  color: #fff;}
.opt_chg_submit_btn:hover{color:#FFF}
.opt_chg_close_btn_bottom{display: inline-block;width: 136px;height: 42px; border: 1px solid #ff5000; font-weight: 700;font-size: 14px;  line-height: 42px;text-align: center;}
.opt_chg_close_btn{ position: absolute;right:20px; top:20px;   background: url(/admin/img/closeicon.gif) no-repeat 50% 50%; text-indent: -9000px;width: 22px; height: 22px; opacity: 1;}
</style>

<!-- 옵션변경레이어 -->
<div class="opt_chg_bg">

	<div class="opt_chg_box">
		
		<div class="opt_chg_close_wrap"><a class="opt_chg_close_btn"></a></div>
		<div class="pro_opt_pop">
			<a href="#self">
				<span class="it_name"></span>
				<span class="it_description"></span>
				<div class="prod_thumb"><img src="<?="$nfor[path]/img/noimg_s.png"?>" class="it_img"></div>
			</a>
		</div>
		<div class="opt_title1">옵션 변경 / 추가</div>
		<div class="opt_chg_result">결과값</div>		
		<div class="opt_chg_submit_wrap">
			<a class="opt_chg_submit_btn"><?=basename($PHP_SELF)=="zzim_list.php"?"장바구니 담기":"변경"?></a>
		</div>

	</div>
</div>
<!-- //옵션변경레이어 -->


<script>
$(document).on("click",".opt_chg_btn",function(){
	$(".pro_opt_pop a .it_name").html( $(this).data("it_name") );
	$(".pro_opt_pop a .it_description").html( $(this).data("it_description") );
	$(".pro_opt_pop .it_img").attr("src", $(this).data("it_img") );
	$.ajax({
		type: "post",
		url: "opt_popup.php",
		data: {
			"it_id": $(this).data("it_id")
		},
		cache: false,
		async: false,
		success: function(response){
			$(".opt_chg_bg").show();
			$(".opt_chg_result").html(response);
		}
	});
});

$(document).on("click",".opt_chg_submit_btn",function(){
	$('#move').val("opt_popup");	
	$.ajax({ 
		type : "post"
		, url : "cart.php"
		, cache : false  
		, data : $("#item_frm").serialize()
		, success : function(response){			
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){

				<? if(basename($PHP_SELF)=="zzim_list.php"){ ?>
				$(".opt_chg_bg").hide();
				$(".opt_chg_result").html("");

				$("#cart_cnt").html(json["cart_cnt"]);

				$("#cart_popup").animate({"top":"100px"}, 500 ).show();
				setTimeout(function(){
					$('#cart_popup').animate({"top":"-280px"}, 500 );
				},5000);
				<? } else{ ?>
					
				document.location.reload();

				<? } ?>

			} else{
				alert(json["msg"]);
			}	
		} 
	});
});

$(document).on("click",".opt_chg_close_btn",function(){
	$(".opt_chg_bg").hide();
	$(".opt_chg_result").html("");
});
</script>





<style>
/* 장바구니담기 팝업 */
#cart_popup { position:fixed; left:0px; top:-280px; width:100%; z-index:99999; display:none; } 
#cart_popup div { background-color:rgba(0,0,0,0.8); margin:0 auto; width:300px; text-align:center; padding:30px 0px; font-family:NSKL;}
#cart_popup p { margin-bottom:10px; display:block; font-size:16px; color:#fff; font-weight:bold; }
#cart_popup a { background-color:#e83862; height:30px; line-height:30px; display:block; width:150px; color:#fff; margin:0 auto; font-family:NSKL; }
</style>

<!-- 장바구니담기팝업 -->
<div id="cart_popup">
	<div>
		<p>장바구니에 상품이 담겼습니다.</p>
		<a href="cart.php">구매하러가기</a>
	</div>
</div>
<!-- 장바구니담기팝업 -->

<?php
include_once "inc_opt_popup_js.php"; // 옵션 자바스크립트
?>