<?php
include_once $nfor[skin_path]."head.php";
?>

<?php
include_once $nfor[skin_path]."inc_main_wide_banner.php";
?>

<style>
.page_center { display:none; }

.contant { width:100%; margin-top:0; margin-bottom:0; position:relative; }
.pointcolor { color:#e83862; font-weight:normal; }
.MDbanner { overflow:hidden; padding:60px 0px 60px; }
.MDbanner .title { display:inline-block; padding-bottom:20px; font-weight:bold; font-size: 34px; text-align: left; font-weight:normal; letter-spacing:-1.5px; }
.MDbanner .sub_tit { font-family: "나눔고딕",NanumGothic,"Nanum Gothic",돋움,dotum,Apple SD Gothic Neo,sans-serif; margin-left:10px; letter-spacing:-1px; font-size:16px; }
.MDbanner .title b{color:#e83862;}
.MDbanner li{float:left;}
.MDbanner ul:after { clear:both; display:block; content:''; }

.today_recom{background-color:#f8f8f8; padding-top:60px; padding-bottom:30px;}
.today_recom .title{ display:block; padding-bottom: 40px; font-weight:bold; font-size: 30px;text-align: left; font-weight:normal; letter-spacing:-1.5px;}
.ctrl {top: 0;right: 0; position: absolute; line-height: 30px;  height: 30px; color: #16181a; letter-spacing: -3px;}
.ctrl .cnt { display: inline-block; margin-right: 5px; font-size: 14px; font-family: Tahoma;  letter-spacing: 0; vertical-align: 10px;}
.product { background: #f8f8f8;}


.planconer { background-color:#fff; padding-top:40px; padding-bottom:50px; }
.planconer .title{ display:block; padding-bottom: 30px; font-weight: 600; font-size: 30px;text-align: left; font-weight:normal; letter-spacing:-1.5px;}
.planconer #plan { height:202px; overflow:hidden; }
.planconer .plan_slider { height:202px; }
.ctrl {top: 0;right: 0; position: absolute; line-height: 30px;  height: 30px; color: #16181a; letter-spacing: -3px;}
.ctrl .cnt { display: inline-block; margin-right: 5px; font-size: 14px; font-family: Tahoma;  letter-spacing: 0; vertical-align: 10px;}

.time_ls{background-color:#f8f8f8; padding-top:50px; padding-bottom:50px; }
.time_ls .title{ display:block; padding-bottom: 20px; font-weight: 600; font-size: 30px; text-align: left; font-weight:normal; letter-spacing:-1.5px;}
.time_ls .sicon{display: block;position: relative; margin-bottom: 20px; }
.time_ls .sicon img{height:45px; display:block; margin:5px auto;}
.time_ls .sicon ul{overflow:hidden; margin: 0 0px; padding-top: 15px;}
.time_ls .sicon ul li{float: left; ; position: relative; width: 10%; margin-bottom: 15px; text-align: center;}
.time_ls .sicon ul li a{display: block; font-size: 12px;line-height: 17px;color: #16181a; letter-spacing:-0px; cursor:pointer; }
.time_ls .sicon ul li a:hover{color:#e83862}
.time_ls .sicon ul li a.on{color:#e83862}
.product {overflow:hidden;}
.product .time_list{position: relative; margin: 1px 1px 0 0; }
.product .time_list:after{display: block;clear: both;  content: '';}
.product .time_list li{float:left; width:25%; }
.product .time_list .border{position: relative; background-color:#FFF; height: 433px;margin: -1px -1px 0 0;padding: 20px!important;border: 1px solid #e3e5e8;}
.product .time_list .border:hover{background-color:#f8f8f8}
.product .time_list .border .thumb{width:100%;}
.product .time_list .border .thumb img{width:100%;}
.product .time_list .border .it_info{position: relative; -webkit-box-sizing: content-box; -moz-box-sizing: content-box; box-sizing: content-box;  }
.product .time_list .border .it_info .it_description{overflow: hidden;width: 100%; height: 18px;  font-size: 12px;  color: #7d7e80; margin-top:10px;  white-space: nowrap;  text-overflow: ellipsis;}
.product .time_list .border .it_info .it_name{display: -webkit-box; overflow: hidden; height: 43px; margin-top: 1px; font-weight: 400; font-size: 15px;line-height: 22px;  text-overflow: ellipsis; -webkit-line-clamp: 2; -webkit-box-orient: vertical;}
.product .time_list .border .it_info .it_name:hover{text-decoration:underline;}
.product .time_list .border .it_info .it_price_info{width: 100%; height: 44px;font-size: 17px; line-height: 30px;vertical-align: bottom; margin-top:20px;}
.product .time_list .border .it_info .it_price_info .it_discount_rate{display: inline-block;margin-right: 4px;color: #e83862; font-family: Tahoma;}
.product .time_list .border .it_info .it_price_info .it_price{display: inline-block;height: auto;font-size: 16px;margin-top:-5px;}
.product .time_list .border .it_info .it_price_info .it_price .it_price1{display:block;color: #c2c7cc; font-size:12px; line-height:15px; text-decoration: line-through;}
.product .time_list .border .it_info .it_price_info .it_price .it_price2{display:block;font-weight:700; font-family: Tahoma; line-height:15px; }
.product .time_list .border .option{ position: relative;  width:100%;box-sizing: border-box; -webkit-box-sizing: border-box;  bottom: 0; min-height: 19px;padding: 12px 0; border-top: 1px solid #f0f2f5; font-size:12px;}
.product .time_list .border .option .coupon_price{display: inline-block;height: 16px;margin-right: 3px;padding: 0 3px 0;border: 1px solid #e83862; background-color:#fff;  font-size: 11px; font-family: "돋움",dotum,"Apple SD Gothic Neo",sans-serif;line-height: 18px;line-height: 19px \0/IE9;  color: #e83862; letter-spacing: -.9px;}
.product .time_list .border .option .besong{display: inline-block;height: 16px;margin-right: 3px;padding: 0 3px 0;border: 1px solid #36a77c; background-color:#fff; font-size: 11px; font-family: "돋움",dotum,"Apple SD Gothic Neo",sans-serif;line-height: 18px;line-height: 19px \0/IE9;  color: #36a77c; letter-spacing: -.9px;}
.product .time_list .border .option .it_sales_volume{position: absolute;  top:-30px; right:0px; font-size:11px; font-family: "돋움",dotum,"Apple SD Gothic Neo",sans-serif; color: #a6a9ad; letter-spacing: -.7px;}
/*찜*/
.product .time_list .border .option .zzim{position: absolute;  top:15px; right:0px; display:none; }
.product .time_list .border .option .zzim .btn_zzim{display:inline-block;position:relative;width:20px;height:17px;background:url('/skin/demo/img/zzim_ico_off.png') no-repeat;line-height:200px;vertical-align:middle;cursor:pointer}
.product .time_list .border .option .zzim .btn_zzim{display:inline-block;overflow:hidden;}
.product .time_list .border .option .zzim .zzim_success{position:absolute;top:82px;left:50%;z-index:30;width:161px;height:50px;margin-left:-80px;padding-top:111px;background:url('/skin/demo/img/zzim_ico_on.png') no-repeat;}
</style>

<div class="contant">

	<div class="MDbanner">
		<div class="layout_inner">
			<h2 class="title fotl"><b>MD</b> PICK's</h2><span class="sub_tit">프라이스박스 MD가 적극 추천하는 상품</span>
			<ul>
				<?php
				$bn_code = "md_pick";
				$sql = "select * from nfor_banner where bn_use='1' and bn_code='$bn_code' and ((bn_period_use='1') or (bn_period_use='2' and bn_sdatetime <='$nfor[ymdhis]' and bn_edatetime >='$nfor[ymdhis]')) order by bn_rank desc";
				$que = sql_query($sql);
				while($banner=sql_fetch_array($que)){	
				?>
				<li><a href="<?=$banner[bn_href]?>" target="<?=$banner[bn_target]?>"><img src="<?="$nfor[path]/data/banner/$banner[bn_img]"?>"></a></li>
				<? } ?>
			</ul>
		</div>
	</div>




<style>
.product .rc_list {overflow: hidden;position: relative; height: 852px; margin-left:-8px;}
.product .rc_list li{float:left; width:25%; }
.product .rc_list .border{margin: 0 8px 16px; padding: 0 0; border:solid 1px #DCDCDC;background: #fff; }
.product .rc_list .border:hover{border:solid 1px #ff0000;background: #fff;}
.product .rc_list .border .thumb{width:100%;}
.product .rc_list .border .thumb img{width:100%;}
.product .rc_list .border .it_info{position: relative; -webkit-box-sizing: content-box; -moz-box-sizing: content-box; box-sizing: content-box; height: 100px;  ;border-bottom: 1px solid #e3e5e8;}
.product .rc_list .border .it_info .it_description{display:none;}
.product .rc_list .border .it_info .it_name{display: block; overflow: hidden; height: auto; margin-top: 20px; margin-bottom: 0; padding: 0 15px; font-weight: 400; font-size: 15px; font-family: "나눔고딕",NanumGothic,"Nanum Gothic",돋움,dotum,Apple SD Gothic Neo,sans-serif; line-height: 23px; text-align: center; white-space: nowrap; text-overflow: ellipsis;}
.product .rc_list .border .it_info .it_price_info{position: absolute; bottom:25px; width: 100%; height: 44px;font-size: 17px; line-height: 30px;vertical-align: bottom;}
.product .rc_list .border .it_info .it_price_info .it_discount_rate{display:none;}
.product .rc_list .border .it_info .it_price_info .it_price{display:block; height: auto;font-size: 16px;text-align: center;}
.product .rc_list .border .it_info .it_price_info .it_price .it_price1{display:block;color: #c2c7cc; font-size:12px; line-height:15px; text-decoration: line-through;}
.product .rc_list .border .it_info .it_price_info .it_price .it_price2{display:block; font-weight:700; font-family: Tahoma;}
.product .rc_list .border .option{display:none}
</style>





	<div class="today_recom">
		<div class="layout_inner">
			<h2 class="title fotl">오늘의 <b class="pointcolor">추천 상품</b></h2>
			<div class="ctrl">
				<!-- <span class="cnt"><strong>1</strong>/<em>3</em></span>
				<a class="prev"><img src="skin/demo/img/pre.png"></a>
				<a class="next"><img src="skin/demo/img/next.png"></a> -->
			</div>
			<div class="product">
				<div class="rc_list">
					<?php
					$result = sql_query("select * from nfor_item limit 8");
					include $nfor[skin_path]."inc_sample_item_list.php";
					?>
				</div>
			</div>
		</div>
	</div>









	<div class="planconer">
		<div class="layout_inner">
			<h2 class="title fotl"><b class="pointcolor">기획전</b> 코너</h2>
			<div class="ctrl">
			<span class="cnt" id="plan_pager"></span>
			<a class="next" id="plan_next"></a><a class="prev" id="plan_prev"></a>
			</div>
			<div id="plan">
				<?php
				$sql = "select *  from nfor_plan where pl_display='1' or (pl_display='2' and pl_sdate <='$nfor[ymdhis]' and pl_edate >='$nfor[ymdhis]') and pl_view_mobile='1' order by pl_rank desc limit 10";
				$que = sql_query($sql);
				while($plan=sql_fetch_array($que)){	
				?>
				<div class="plan_slider"><a href="plan.php?pl_id=<?=$plan[pl_id]?>"><img src="<?=thumbnail("$nfor[path]/data/plan/$plan[pl_img]",600,"",0,1)?>"></div>
				<? } ?>
			</div>
		</div>
	</div>


	<script>
	$(document).ready(function(){

		$('#plan').bxSlider({
			nextSelector: '#plan_prev',
			prevSelector: '#plan_next',
			auto: true,
			pause:4000,
			minSlides:3,
			maxSlides:6,
			slideWidth:400,
			infiniteLoop:true,
			pager: true,
			pagerType: 'short',
			pagerSelector:'#plan_pager',
			nextText: "<img src='<?=$nfor[skin_path]?>img/next.png'>",
			prevText: "<img src='<?=$nfor[skin_path]?>img/pre.png'>"
		});

	});

	</script>



	<div class="time_ls">
		<div class="layout_inner">
			<h2 class="title fotl">베스트 <b class="pointcolor">상품</b></h2>
			<div class="sicon">
				<ul>
					<li><a data-category_id="" class="on"><img src="./skin/nfor/img/index_sicon10.png">전체보기</a></li>
					<?
					$que = sql_query("select * from nfor_item_category where cg_use='1' and cg_depth_id='0' and cg_depth='0' order by cg_rank asc");
					while($data = sql_fetch_array($que)){
					?>
					<li><a data-category_id="<?=$data[category_id]?>"><img src="<?="$nfor[path]/data/category/$data[cg_img_icon]"?>"><?=$data[cg_category]?></a></li>
					<? } ?>
				</ul>
			</div>
			<div class="product">
				<div class="time_list best_item_list">
					<?php
					$return = "";
					$result = sql_query("select * from nfor_item limit 12");
					for($i=0; $row=sql_fetch_array($result); $i++){
						$res[it_num] = ($page*$rows) - $rows + $i + 1; // 베스트넘버출력	
						$res[it_id] = $row[it_id];
						$res[it_img] = thumbnail("$nfor[path]/data/list/$row[it_img]",300,300,0,1);
						$res[it_name] = $row[it_name];
						$res[it_description] = $row[it_description];
						$res[it_discount_rate] = $row[it_discount_rate];
						$res[it_price1] = number_format($row[it_price1]);
						$res[it_price2] = number_format($row[it_price2]);
						$res[it_sales_volume] = $row[it_sales_volume];
						$return["list"][] = $res;
					}
					include $nfor[skin_path]."inc_item_list.php";
					?>
				</div>
			</div>
		</div>
	</div>




	<div class="time_ls" style="background-color:#FFF;">
		<div class="layout_inner">
			<h2 class="title fotl"><b class="pointcolor">한정</b>특가</h2>
			
			<div class="product">
				<div class="time_list">
					<?php
					$return = "";
					$result = sql_query("select * from nfor_item limit 12");
					for($i=0; $row=sql_fetch_array($result); $i++){
						$res[it_num] = ($page*$rows) - $rows + $i + 1; // 베스트넘버출력	
						$res[it_id] = $row[it_id];
						$res[it_img] = thumbnail("$nfor[path]/data/list/$row[it_img]",300,300,0,1);
						$res[it_name] = $row[it_name];
						$res[it_description] = $row[it_description];
						$res[it_discount_rate] = $row[it_discount_rate];
						$res[it_price1] = number_format($row[it_price1]);
						$res[it_price2] = number_format($row[it_price2]);
						$res[it_sales_volume] = $row[it_sales_volume];
						$return["list"][] = $res;
					}
					include $nfor[skin_path]."inc_item_list.php";
					?>
				</div>
			</div>
		</div>
	</div>



</div>










<script>
$(document).on("click", ".sicon li a", function(){
	var category_id = $(this).data("category_id");
	$(".best_item_list .deal_list_type ul").html("");

	$(".sicon li a").removeClass("on");
	$(this).addClass("on");

	$.ajax({
		type     : "get",
		url      : "best_list.php",
		data     : "json=list&category_id="+category_id,
		dataType : "json",
		cache: false,
		success  : function(data) {
			if(data.count > 0){
				template = _.template($("#item_list_script").html());
				var template_html = "";
				for(var i=0; i<data.list.length; i++) {
					template_html +=  template({it_id: data.list[i].it_id
						, it_img: data.list[i].it_img
						, it_description: data.list[i].it_description
						, it_name: data.list[i].it_name
						, it_discount_rate: data.list[i].it_discount_rate
						, it_price1: data.list[i].it_price1
						, it_price2: data.list[i].it_price2});
				}
				$(".best_item_list .deal_list_type ul").append(template_html);
			}		
		},
		error: function(e){
			console.log(e);
			console.log("Ajax failed");
		}
	});
});
</script>

<?php
include_once $nfor[skin_path]."tail.php";
?>