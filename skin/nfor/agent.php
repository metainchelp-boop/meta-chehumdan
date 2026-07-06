<?php
include_once $nfor[skin_path]."head.php";
?>


<style>
.hide{display:none!important;}
.paddi20{padding:20px;}
.bar{background-color:#f5f5f5; height:10px; margin-top:5px; border-bottom:solid 1px #efefef;}
.budongsan_info{ position: relative; padding:0px; background-color:#ffffff;}

.budongsan_info .budongsan_info_top{ position: relative; text-align:left;  padding:30px 15px 40px;}
.budongsan_info .budongsan_info_top .budongsan_logo{ position: absolute; top: 30px; right: 15px; border:solid 1px #e0e0e0; width:100px; height:100px; border-radius:60px; background-image:url(/skin/demo/img/no_perimg.png); background-position:center;}
.budongsan_info .budongsan_info_top .zzim_off{position: absolute; width:42px; height:37px; top:20px; right:15px; background:url(/skin/demo/img/zzim_off2.png); z-index:26;}
.budongsan_info .budongsan_info_top .zzim_on{position: absolute; width:42px; height:37px; top:20px; right:15px; background:url(/skin/demo/img/zzim_on.png); z-index:26;}

.budongsan_info .budongsan_info_top .info_wrap{ width: calc(100% - 131px);}
.budongsan_info .budongsan_info_top .info_wrap .view_gr2_tit{font-size:16px; padding:0px 0px 5px; font-weight:600;}
.budongsan_info .budongsan_info_top .info_wrap .bu_addr{font-size:12px; color:#666; padding-bottom:10px; height:42px;}
.budongsan_info .budongsan_info_top .info_wrap .gr_2{position: relative; font-size:12px; padding-bottom:15px;}
.budongsan_info .budongsan_info_top .info_wrap .gr_2 .right_info{position: absolute;  top:0px; right: 0px; color:#666;}

.budongsan_info .budongsan_info_top .map_ser{ position: relative; font-size:12px; border:solid 1px #565eb6; padding:5px 5px 5px 20px; border-radius:3px; margin-left:0px; color:#565eb6;}
.budongsan_info .budongsan_info_top .map_ser:after{ content: "";  display: block; width:15px; height:15px; position: absolute;  bottom:5px; left: 3px; background:url(/skin/nfor/img/map_ser_ico.png)center; background-size:15px;}
.budongsan_info .budongsan_info_top .budongsan_ser{ position: relative; font-size:12px; border:solid 1px #565eb6; padding:5px 5px 5px 20px; border-radius:3px; margin-left:5px; color:#565eb6;}
.budongsan_info .budongsan_info_top .budongsan_ser:after{ content: "";  display: block; width:15px; height:14px; position: absolute;  bottom:5px; left: 3px; background:url(/skin/nfor/img/sms_icon.png)center; background-size:15px;}

.budongsan_info .budongsan_info_top .call{ position: relative; font-size:12px; border:solid 1px #565eb6; padding:5px 5px 5px 20px; border-radius:3px; margin-left:5px; color:#565eb6;}
.budongsan_info .budongsan_info_top .call:after{ content: "";  display: block; width:15px; height:14px; position: absolute;  bottom:5px; left: 3px; background:url(/skin/nfor/img/call_icon.png)center; background-size:15px;}

.info_btn{position: absolute; bottom:20px; left:10px;}
.budongsan_info .detail_info1{padding:15px;}
.budongsan_info .detail_info1 h2{font-weight:normal; font-size:16px; margin-bottom:20px;}
.budongsan_info .detail_info1 li{line-height:24px; font-weight:normal; position:relative;}
.budongsan_info .detail_info1 .info_tit{width:120px; font-size:12px; color:#666; position: absolute; left:5px; top:0px; }
.budongsan_info .detail_info1 .info_txt{padding-left:120px; font-size:14px;display:block; letter-spacing:-1px;}
.budongsan_info .house_list_box .box .house_info .opt_top{display:none;}
</style>


<div class="budongsan_info">
	<div class="budongsan_info_top shadow">
		<div class="<?=$agent[mb_zzim_is]?>"></div>
		<div class="budongsan_logo">
			
		</div>
		<div class="info_wrap">
			<div class="view_gr2_tit"><?=$agent[mb_cp_name]?></div>
			<div class="bu_addr"><?=$agent[mb_cp_addr1]?> <?=$agent[mb_cp_addr2]?></div>
			<div class="gr_2">
			<span>대표자 : <?=$agent[mb_cp_name]?></span>
			<span class="right_info">찜 <b class="num_mon"><?=$agent[mb_zzim]?></b>   매물 <b class="num_mon"><?=$ho_total[cnt]?></b></span>
			</div>
			<div class="info_btn"><a href="<?=$agent[map_href]?>" target="_blank" class="map_ser">길찾기</a><a href="<?=$agent[sms_href]?>" class="budongsan_ser">문자보내기</a><a href="<?=$agent[tel_href]?>" class="call">전화걸기</a></div>
		</div>
	</div>
	<div class="bar"></div>
	<div class="detail_info1  shadow">
		<h2>기본정보</h2>
		<ul>
			<li><span class="info_tit">대표자명</span><span class="info_txt"><?=$agent[mb_cp_ceo]?></span></li>
			<li><span class="info_tit">전화번호</span><span class="info_txt"><b class="num_mon"><?=$agent[mb_tel]?>, <?=$agent[mb_hp]?></b></span></li>
			<li><span class="info_tit">최근 거래건</span><span class="info_txt"><b class="num_mon"><?=$ho_soldout[cnt]?></b>개</span></li>
			<li><span class="info_tit">매물수</span><span class="info_txt">매매 <?=$ho_sale[cnt]?> , 전세 <?=$ho_yearly_rent[cnt]?> , 월세 <?=$ho_monthly_rent[cnt]?> , 단기 <?=$ho_short_rent[cnt]?></span></li>
		</ul>
	</div>
	<div class="bar"></div>
	<div class="house_list_box paddi20">
		<?php
		include $nfor[skin_path]."inc_house_list.php";
		?>
	</div>

</div>



<?php
include_once $nfor[skin_path]."tail.php";
?>