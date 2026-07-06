<?php
include_once $nfor[skin_path]."head.php";
?>



<style>
.hide { display: none!important; border:none;}
/*레이아웃*/
.sub_item_wrap{ margin-top:30px; overflow:hidden;}
.sub_item_wrap .item_thumb_wrap{ float:left; width:40%; padding: 0px 30px; }
.sub_item_wrap .item_thumb_wrap .it_img_m{width:100%; border:solid 1px #efefef;}
.sub_item_wrap .iteminfo_list_wrap{float:left; width:50%;}



/*상단 표시 버튼들 */
.sub_item_wrap .iteminfo_list_wrap .iteminfo_top{margin:20px 0px 10px;  position: relative;}
.sub_item_wrap .iteminfo_list_wrap .iteminfo_top span{letter-spacing:-1px; margin-right:0px; font-size:14px; display:inline-block; padding:4px 8px; margin-right:10px;}
.sub_item_wrap .iteminfo_list_wrap .iteminfo_top span + span{letter-spacing:-1px; margin-right:5px;}
.sub_item_wrap .iteminfo_list_wrap .iteminfo_top .nor_box{background-color:#efefef; font-size:14px; border:solid 1px #dcdcdc;}
.sub_item_wrap .iteminfo_list_wrap .iteminfo_top .instagram{color:#ff3478;  border:solid 1px #ff0055;}
.sub_item_wrap .iteminfo_list_wrap .iteminfo_top .blog{color:#2ba406; border:solid 1px #2ba406; ;}
.sub_item_wrap .iteminfo_list_wrap .iteminfo_top .facebook{color:#415fc1; border:solid 1px #415fc1; }
.sub_item_wrap .iteminfo_list_wrap .iteminfo_top .youtube{color:#f41515;  border:solid 1px #f41515;}
.sub_item_wrap .iteminfo_list_wrap .iteminfo_top .kakaostory{color:#f3d710; border:solid 1px #f3d710; }

.sub_item_wrap .iteminfo_list_wrap .iteminfo_top .sns_gong{ display:inline-block; font-size:13px;}
.sub_item_wrap .iteminfo_list_wrap .iteminfo_top .sns_gong i{display: inline-block; width: 15px; height: 18px; margin: 0px 4px 0 0; background: url(skin/demo//img/sns_gong.png) no-repeat ; vertical-align: top;}
/*상품명 /부가설명*/
.sub_item_wrap .iteminfo_list_wrap .itname{width:100%; font-size:24px; color:#000; margin-top:20px;}
.sub_item_wrap .iteminfo_list_wrap .it_description{width:100%;  font-size:16px; margin-top:10px;}
.sub_item_wrap .iteminfo_list_wrap .iteminfor_list{overflow:hidden; font-size:16px; margin-top:20px}
.sub_item_wrap .iteminfo_list_wrap .iteminfor_list + .iteminfor_list{margin-top:0px}
.sub_item_wrap .iteminfo_list_wrap .iteminfor_list li{float:left; line-height:35px;}
.sub_item_wrap .iteminfo_list_wrap .iteminfor_list li:nth-child(even){width:70%;}
.sub_item_wrap .iteminfo_list_wrap .iteminfor_list li:nth-child(odd){width:20%;}

#countdown b{font-size:20px;}
.sub_item_wrap .iteminfo_list_wrap .iteminfor_list .title{width:100px; color:#000; display:inline-block;}
.sub_item_wrap .iteminfo_list_wrap .coution{position:relative; font-size:12px; padding-left:35px; margin-top:10px; line-height:14px;}
.sub_item_wrap .iteminfo_list_wrap .coution:after {position: absolute;width: 30px;height: 30px;  top: 0px;  left: 0px; display: block;  clear: both;  content: '';  background: url(/skin/demo/img/coution_mark.png);}


/**/
.sub_item_wrap .iteminfo_list_wrap .review_step_wrap{margin-top:20px; max-width:550px; font-size:14px; letter-spacing:-1px; padding:15px 15px; border:solid 1px #efefef; background-color:#fafafa;}
.sub_item_wrap .iteminfo_list_wrap .review_step_wrap .step{display:block; height:25px; line-height:25px;}
.sub_item_wrap .iteminfo_list_wrap .review_step_wrap .step em{display:inline-block; width:100px; position:relative; padding-left:15px;}
.sub_item_wrap .iteminfo_list_wrap .review_step_wrap .step em:before{display: block;clear: both; content: ''; position:absolute; left:0px; top:13px; width:3px; height:3px;  background-color:#666; }

.txt_sizeup{font-size:20px;}






/*탭메뉴*/
@media (max-width:1444px){
.item_detail{margin-top:30px;}
.item_detail .tab-wrap {position: relative;z-index: 200; width: 100%;height: 58px;}
.item_detail .tab-wrap .tab-inner { position: absolute; top: 0; left: 0;  width: 100%;border-bottom: 2px solid #30343b; background-color: #fff;}
.item_detail .tab-wrap .tab-inner .tabmenu { width: 1100px;margin: -1px auto 0;}
.deals_container { position: relative;width: 1100px; margin: -58px auto 0; padding-top: 58px; }
.deals_floating_opt{display:none;}
.tab-cont { width: 1100px; min-height:800px;padding:0px 0px; box-sizing:border-box; -webkit-box-sizing:border-box; -moz-box-sizing:border-box; border-right: 0px solid #e3e5e8;min-height:1000px;}

}

@media (min-width:1444px){
.item_detail{margin-top:30px;}
.item_detail .tab-wrap {position: relative;z-index: 200; width: 100%;height: 58px;}
.item_detail .tab-wrap .tab-inner { position: absolute; top: 0; left: 0;  width: 100%;border-bottom: 2px solid #30343b; background-color: #fff;}
.item_detail .tab-wrap .tab-inner .tabmenu { width: 1444px;margin: -1px auto 0;}
.deals_container { position: relative;width: 1444px; margin: -58px auto 0; padding-top: 58px; }
.deals_floating_opt { position: absolute; top: 58px; right: 0;width: 344px;background-color: #fff;}
.tab-cont {width: 1100px; padding:0px 0px; box-sizing:border-box; -webkit-box-sizing:border-box; -moz-box-sizing:border-box; border-right: 1px solid #e3e5e8;min-height:1200px;}

}

.item_detail{margin-top:30px;}
.item_detail .tab-wrap {position: relative;z-index: 200; width: 100%;height: 58px;}
.item_detail .tab-wrap .tab-inner .tabmenu li { float: left; position: relative; width: 196px; text-align: center;}
.item_detail .tab-wrap .tab-inner .tabmenu li + li  a{ margin-left:-1px; border-left:solid 0px #e3e5e8;}
.item_detail .tab-wrap .tab-inner .tabmenu li a { cursor:pointer; display: block; width: 100%; font-size: 16px;  border-top: 1px solid #e3e5e8; border-left:solid 1px #e3e5e8; border-right:solid 1px #e3e5e8; line-height: 58px; color: #666; background-color:#FFF;-webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}
.fixed {top: 0px; position: fixed!important; z-index: 300!important }
.tab-wrap .tab-inner .tabmenu li.active a { background-color: #30343b; border:solid 1px #30343b;  color: #fff; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}
/**/
.nfor_line { height:1px; }
.tab-cont { display:none;  }
#tab1 { display:block; }
.item_detail .deals_floating_opt .right_order{ padding:0px 20px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;}
.order_top { overflow:hidden; min-height:600px; height:auto; margin:20px 0px; box-sizing:border-box; -webkit-box-sizing:border-box; -moz-box-sizing:border-box; }
.order_bottom { overflow:visible; position:absolute; bottom:0; left:0; width:100%; height:112px;  background-color:#fff; padding: 0px 20px; box-sizing:border-box; -webkit-box-sizing:border-box; -moz-box-sizing:border-box; }
.right_order { position:relative; top:auto; bottom:auto; height:0px; min-height:520px; width:344px; max-height:750px!important;  }
.right_order.junadown { position:fixed; top:58px; bottom:0px; height:auto; min-height:540px!important; max-height:774px!important; }
.plus_txt{font-size:14px; margin-top:10px; display:block; padding-left:10px;}


/*리뷰버튼*/
.btn_zone{ max-width: 550px; margin-top:30px;}
.btn_zone li:nth-child(1){width:60%; float:left;}
.btn_zone li:nth-child(2){width:40%; float:left; padding-left:5px;  box-sizing:border-box; -webkit-box-sizing:border-box; -moz-box-sizing:border-box; }
.btn_zone li:nth-child(3){width:20%; float:left;   position:relative;  padding-left:5px;  box-sizing:border-box; -webkit-box-sizing:border-box; -moz-box-sizing:border-box; }

.b_btn{width:100%;font-size:18px; display:inline-block; padding:15px; box-sizing:border-box; -webkit-box-sizing:border-box; -moz-box-sizing:border-box; }

.review_req{background-color:#ff0055; border:solid 1px #ff0055; color:#fff; position:relative; }
.review_req:hover{ color:#fff;}
.review_req:after{display: block;clear: both; content: '';  position:absolute; top:15px; right:15px; width:28px;height:28px;background-image:url('/skin/demo/img/review_icon.png'); }
.scrap_btn{position:relative; color:#ccc;  border:solid 1px #ccc; }
.scrap_btn:hover{ color:#ccc; }
.scrap_btn:after{display: block;clear: both; content: '';  position:absolute; top:15px; right:15px; width:28px;height:28px; background-size:28px;  background-image:url('/skin/demo/img/zzim.png');}
.scrap_btn_on{position:relative; color:#ff0055;  background-color:#fff; border:solid 1px #ff0055;}
.scrap_btn_on:hover{color:#ff0055;}
.scrap_btn_on:after{display: block;clear: both; content: '';  position:absolute; top:15px; right:15px; width:28px;height:28px;background-size:28px; background-image:url('/skin/demo/img/zzim_on.png');}


/*따라다니는 리뷰버튼*/
.deals_floating_opt .btn_zone_floating{ }
.deals_floating_opt .btn_zone_floating li:nth-child(1){}
.deals_floating_opt .btn_zone_floating li:nth-child(2){ }

.b_btn{width:100%;font-size:18px; display:inline-block; padding:15px; box-sizing:border-box; -webkit-box-sizing:border-box; -moz-box-sizing:border-box; }

.review_req{background-color:#ff0055; border:solid 1px #ff0055; color:#fff; position:relative; }
.review_req:hover{ color:#fff;}
.review_req:after{display: block;clear: both; content: '';  position:absolute; top:15px; right:15px; width:28px;height:28px;background-image:url('/skin/demo/img/review_icon.png'); }
.scrap_btn{position:relative; color:#ccc;  border:solid 1px #ccc; }
.scrap_btn:hover{ color:#ccc; }
.scrap_btn:after{display: block;clear: both; content: '';  position:absolute; top:15px; right:15px; width:28px;height:28px; background-size:28px;  background-image:url('/skin/demo/img/zzim.png');}
.scrap_btn_on{position:relative; color:#ff0055;  background-color:#fff; border:solid 1px #ff0055;}
.scrap_btn_on:hover{color:#ff0055;}
.scrap_btn_on:after{display: block;clear: both; content: '';  position:absolute; top:15px; right:15px; width:28px;height:28px;background-size:28px; background-image:url('/skin/demo/img/zzim_on.png');}



.nfor_layer_popup_wrap2 { position:absolute; top:35px; left:200px; width:215px; height:150px; z-index:1000; border:solid 1px #ccc; background-color:#fff; z-index:99999; }
.nfor_layer_popup_wrap2 .lay_tit { height: 38px; padding: 0 15px; border-bottom: 1px solid #dadada; font-size: 14px; font-weight:normal;  color: #333;  line-height: 34px;}
.nfor_layer_popup_wrap2 .lay_conts{padding: 15px 0 10px; font-size: 14px; line-height: 1.6; color: #666;}
.nfor_layer_popup_wrap2 .lay_conts .sns_type{overflow:hidden;padding: 0 0 15px 0px; }
.nfor_layer_popup_wrap2 .lay_conts .sns_type li { float:left; margin-left:15px; }
.nfor_layer_popup_wrap2 .lay_conts .sns_type .facebook{ display: block;width: 33px; height: 32px; background: url('skin/demo/img/sns_face.png') no-repeat; border:none;}
.nfor_layer_popup_wrap2 .lay_conts .sns_type .twitter{ display: block;width: 33px; height: 32px; background: url('skin/demo/img/sns_twee.png') no-repeat; border:none;}
.nfor_layer_popup_wrap2 .lay_conts .sns_type .kakao{ display: block;width: 33px; height: 32px; background: url('skin/demo/img/sns_kaka.png') no-repeat; border:none;}
.nfor_layer_popup_wrap2 .lay_conts .sns_type .blog{ display: block;width: 33px; height: 32px; background: url('skin/demo/img/sns_naver.png') no-repeat; border:none;}

.nfor_layer_popup_wrap2 .sns_url { padding: 10px 15px 0;border-top: 1px solid #dadada;}
.nfor_layer_popup_wrap2 .sns_url input[type='text'] {  width:124px; padding: 3px 4px 0;  padding-top: 5px \0/IE8;  height:25px; padding-bottom: 2px \0/IE8; color: #666;  font-size: 12px;  border: 1px solid #dadada;  border-right: 0; line-height: 25px;   vertical-align: middle;}
.nfor_layer_popup_wrap2 .sns_url  a.copy_btn {display: inline-block; width: 41px; height: 23px; border: 1px solid #555;  background-color: #555;  font-size: 12px; line-height: 20px; color: #fff;  text-align: center; vertical-align: middle;}
#popup_close_btn2 { position:absolute;right: 4px; top: 5px;  width: 24px;  height: 24px; border:none; cursor:pointer;  background: url('skin/demo/img/close_btn_sns.png') no-repeat;} 

.tab-wrap.top { position:fixed; top:0px; left:0px; height:58px; width:100%; }
</style>



<div class="sub_item_wrap">

</div>



<div class="sub_item_wrap">
	<div class="item_thumb_wrap">
		<img id="it_img_m" src="<?=$campaign[cp_img]?>" class="it_img_m">
	</div>
	<div class="iteminfo_list_wrap">
		<div class="iteminfo_top">
			<span class="<?=$campaign[cp_media]?>"><?=$campaign[cp_media_text]?></span>
			<span class="nor_box"><?=$campaign[cp_type]?></span>
			<?php if($campaign[cp_point]){ ?><span class="nor_box point_color4 txt_num">+<?=$campaign[cp_point]?>P</span><?php } ?>
			<div class="sns_gong"><i></i> 공유하기</div>
				<!-- 공유하기 팝업 -->
				<script type="text/javascript">
				<!--
				$(document).on("click", ".sns_gong", function(){
					$('#nfor_layer_popup_wrap2').show();
					$('#nfor_popup_content2').animate( { scrollTop : 0 }, 0 );
				});
				//-->
				</script>
				<div id="nfor_layer_popup_wrap2" class="nfor_layer_popup_wrap2" style="display:none;">
					<h3 class="lay_tit">공유하기</h3>
					<div class="lay_conts">
						<ul class="sns_type">
							<li><a href="javascript:sns_link('naver')" class="naver" title="네이버 공유" ><span class="hide">naver</span></a></li>
							<li><a href="javascript:sns_link('facebook')" class="facebook" title="페이스북 공유" ><span class="hide">facebook</span></a></li>
							<li><a href="javascript:sns_link('twitter')" class="twitter" title="트위터에 보내기"><span class="hide">twitter</span></a></li>
							<li><a href="javascript:sns_link('kakaostory')" class="kakao" title="카카오스토리 공유"><span class="hide">카카오 스토리</span></a></li>
						</ul>
						<div class="sns_url">
							<input type="text" id="copy_msg" value="<?=$nfor[url]."/campaign.php?cp_id=".$campaign[cp_id]?>" class="text" title="URL 복사" readonly="" style="box-sizing:border-box; -webkit-box-sizing:border-box;"><a data-clipboard-target="#copy_msg" class="copy_btn">복사</a>
						</div>
						<div onclick="$('#nfor_layer_popup_wrap2').hide()" id="popup_close_btn2"><span class="hide">레이어 닫기</span></div>
					</div>					
				</div>
				<script>
				new ClipboardJS('.copy_btn');
				</script>
				<!-- //공유하기 팝업 -->
		</div>

		<div class="itname"><?=$campaign[cp_subject]?></div>

		<div class="it_description"><?=$campaign[cp_description]?></div>

		<div class="review_step_wrap">
			<span class="step">
				<em class="point_color4">리뷰어 신청기간</em> 
				<b class="txt_num point_color4"><?=date("m.d",strtotime($campaign[cp_sdatetime]))?> ~ <?=date("m.d",strtotime($campaign[cp_edatetime]))?></b>
			</span>
			<span class="step">
				<em>선정자 발표</em> 
				<b class="txt_num"><?=date("m.d",strtotime($campaign[cp_pick_datetime]))?></b>
			</span>
			<span class="step">
				<em >리뷰 등록기간</em> 
				<b class="txt_num"><?=date("m.d",strtotime($campaign[cp_contents_sdatetime]))?> ~ <?=date("m.d",strtotime($campaign[cp_contents_edatetime]))?></b>
			</span>
			<span class="step">
				<em>캠페인 결과발표</em> 
				<b class="txt_num"><?=date("m.d",strtotime($campaign[cp_result_datetime]))?></b>
			</span>
		</div>
		<div class="iteminfor_list">
			<ul>
				<li><span class="title">남은시간 </span></li>
				<li>
					<span class="item_info">
						<?
						$gap = strtotime($campaign[cp_edatetime])-time();
						if($gap>0){
							sscanf(date('His',$gap-32400),'%2d%2d%2d',$h,$i,$s);
							$d = floor($gap/86400);
						?>

						<script type="text/javascript">
						$(function (){
							var austDay = new Date(<?=strtotime($campaign[cp_edatetime])*1000?>);
							$('#countdown').countdown({until: austDay,  layout: "<? if($d){ ?><b class='txt_num'><?=$d?></b>일<? } ?> <b class='txt_num'>{hnn}</b>시간 <b class='txt_num'>{mnn}</b>분 <b class='txt_num'>{snn}</b>초" });
						});
						</script>

							<div id='countdown'>
							<? if($d){ ?>
							<b class='txt_num'><?=$d?></b>일
							<? } ?> 
							<b class='txt_num '><?=sprintf("%02d",$h)?></b>시간 
							<b class='txt_num'><?=sprintf("%02d",$i)?></b>분 
							<b class='txt_num'><?=sprintf("%02d",$s)?></b>초
							</div>


						<? } else{ ?>
						<div >접수마감</div>
						<? } ?>
					</span>
				</li>
				<li><span class="title">신청내역</span></li>
				<li><span class="item_info"> <b class="txt_num point_color4 txt_sizeup"><?=$campaign[cp_order]?></b>명 신청  / 모집 <b class="txt_num"><?=$campaign[cp_recruit]?></b> 명</span></li>
			</ul>
		</div>


		<div class="btn_zone">
			<ul>
				<li><a class="review_req b_btn" id="btn_order">리뷰신청하기<strong  id="review_btn_cnt" class="txt_num"><?=$campaign[cp_order]?></strong></a></li>
				<li><a class="b_btn scrap_btn<?=$campaign[cp_zzim_is]?"_on":""?>" id="btn_zzim"> 찜하기<?=$campaign[cp_zzim]?></a></li>
			</ul>
		</div>
	</div>

</div> 





<div class="nfor_line"></div>
<div class="item_detail">
	<div class="tab-wrap">
		<div class="tab-inner" > <!-- fixed 상단으로 올라갔을때 클래스명 -->
			<ul class="tabmenu">
				<li class="active"><a data-tab="#tab1">캠페인정보</a></li>
				<li><a data-tab="#tab2">신청자 한마디</a></li>
			</ul>
		</div>
	</div>
	<div class="deals_container">
		<div id="tab1" class="tab-cont">
			<?=$campaign[cp_memo]?>
		</div>
		<div id="tab2" class="tab-cont">
			aaa
		</div>
		<div class="deals_floating_opt">
			<div class="inner right_order">
				<div class="order_top">
				<?
				include $nfor[skin_path]."inc_right_floating.php";
				?>
				</div>
				<div class="order_bottom ">
					<div class="btn_zone_floating">
					<ul>
						<li><a class="review_req b_btn" id="btn_order">리뷰신청하기<strong  id="review_btn_cnt" class="txt_num"><?=$campaign[cp_id]?></strong></a></li>
						<li><a class="b_btn scrap_btn<?=$campaign[zzim_is]?"_on":""?>" id="btn_zzim"> 찜하기<?=$campaign[it_zzim]?></a></li>
					</ul>
				</div>
				</div>
				
			</div>
		</div>
	</div>
</div>


<script>


$(window).scroll( function(){

	var scrollTop = $(document).scrollTop();

	// 스크롤 상단위치가 탭위치로 오면
	if(scrollTop >= $('.nfor_line').offset().top + 1){
		$(".right_order").addClass("junadown").css("height","");

		$(".tab-wrap").addClass("top");

	} else{

		var scrollBottom = $(window).scrollTop() + $(window).height();

		bottomtop = scrollBottom-$('.nfor_line').offset().top;
		bottomtop = bottomtop - 90;

		$(".right_order").removeClass("junadown").css("height",bottomtop+"px");

		$(".right_order .opt_cal_list").css("height",(bottomtop/2) + "px");


		$(".tab-wrap").removeClass("top");
	}

	var scrollHeight = $(document).height();
	var scrollPosition = $(window).height() + $(window).scrollTop()

	bottom_height = scrollHeight - scrollPosition;
	
	$(".junadown").css("height",bottom_height+"px");

});

</script>

<span id="bottom"></span>

<?php
include_once $nfor[skin_path]."tail.php";
?>