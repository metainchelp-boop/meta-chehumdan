<?php
include_once $nfor[skin_path]."head.php";
?>


<style>
.hide{display:none!important;}


.budongsan_info{ position: relative; }
.budongsan_info .budongsan_info_top{ position: relative; text-align:center; margin-bottom:60px;}
.budongsan_info .budongsan_info_top .view_gr2_tit{font-size:24px; padding:20px 0px 30px; font-weight:200;}
.budongsan_info .budongsan_info_top .budongsan_logo{margin:0px auto; border:solid 1px #e0e0e0; width:100px; height:100px; border-radius:60px; background-image:url(/skin/demo/img/no_perimg.png); background-position:center;}
.budongsan_info .budongsan_info_top .map_ser{ position: relative; font-size:12px; border:solid 1px #565eb6; padding:5px 15px 5px 30px; border-radius:20px; margin-left:0px; color:#565eb6;}
.budongsan_info .budongsan_info_top .map_ser:after{ content: "";  display: block; width:15px; height:15px; position: absolute;  bottom:5px; left: 5px; background:url(/skin/demo/img/map_ser_ico.png)center; background-size:15px;}
.budongsan_info .budongsan_info_top .budongsan_ser{ position: relative; font-size:12px; border:solid 1px #565eb6; padding:5px 15px 5px 30px; border-radius:20px; margin-left:5px; color:#565eb6;}
.budongsan_info .budongsan_info_top .budongsan_ser:after{ content: "";  display: block; width:15px; height:14px; position: absolute;  bottom:5px; left: 8px; background:url(/skin/demo/img/bu_icon.png)center; background-size:15px;}
.budongsan_info .budongsan_info_top .zzim_on{ position: relative; font-size:12px; border:solid 1px #565eb6; padding:5px 15px 5px 30px; border-radius:20px; margin-left:5px; color:#565eb6;}
.budongsan_info .budongsan_info_top .zzim_on:after{ content: "";  display: block; width:15px; height:14px; position: absolute;  bottom:5px; left: 8px; background:url(/skin/demo/img/zzim_on.png)center; background-size:15px;}
.budongsan_info .budongsan_info_top .zzim_off{ position: relative; font-size:12px; border:solid 1px #888; padding:5px 15px 5px 30px; border-radius:20px; margin-left:5px; color:#888;}
.budongsan_info .budongsan_info_top .zzim_off:after{ content: "";  display: block; width:15px; height:14px; position: absolute;  bottom:5px; left: 8px; background:url(/skin/demo/img/zzim_off2.png)center; background-size:15px;}

.budongsan_info .house_info_tbl2{border-top:solid 2px #333;}
.budongsan_info .house_info_tbl2 table{letter-spacing:-1px; width:100%;}
.budongsan_info .house_info_tbl2 th {width:18%; background-color:#f4f4f4; padding:15px 10px; text-align:center; font-weight:normal; font-size:16px; border-bottom:solid 1px #e0e0e0;  border-left:solid 0px #e0e0e0;}
.budongsan_info .house_info_tbl2 th + th { position: relative;  border-left:solid 1px #e0e0e0;}
.budongsan_info .house_info_tbl2 td {text-align:center; font-size:18px; height:68px; font-weight:200; padding:25px 10px; border-bottom:solid 1px #e0e0e0; border-left:solid 0px #e0e0e0; font-family: 'montserrat', 'noto', 'Roboto', sans-serif}
.budongsan_info .house_info_tbl2 td + td{border-left:solid 1px #e0e0e0;}
</style>




<div class="budongsan_info">
	<div class="budongsan_info_top">
		<div class="budongsan_logo"></div>
		<div class="view_gr2_tit"><?=$agent[mb_cp_name]?></div>
		<div><a href="<?=$agent[map_href]?>" target="_blank" class="map_ser">길찾기</a><a href="<?=$agent[sms_href]?>" class="budongsan_ser">문자보내기 </a> <a data-mb_no="<?=$agent[mb_no]?>" class="agent_zzim_btn <?=$agent[mb_zzim_is]?>">찜하기</a></div>
	</div>
	<div class="house_info_tbl2">
		<table cellpadding="0" cellspacing="0">
		<tr>
			<th>대표자명</th>
			<th>전화번호</th>
			<th>최근 거래건</th>
			<th>매물수</th>
		</tr>
		<tr>
			<td><?=$agent[mb_cp_ceo]?></td>
			<td><span class="num_mon"><?=$agent[mb_tel]?>, <?=$agent[mb_hp]?></span></td>
			<td><span class="num_mon"><?=$ho_soldout[cnt]?></span>개</td>
			<td>매매 <?=$ho_sale[cnt]?> , 전세 <?=$ho_yearly_rent[cnt]?> , 월세 <?=$ho_monthly_rent[cnt]?> , 단기 <?=$ho_short_rent[cnt]?></td>
		</tr>
		</table>
	</div>

	<div class="house_list mar30">
	<?php
	include $nfor[skin_path]."inc_house_list.php";
	?>
	</div>

</div>



<?php
include_once $nfor[skin_path]."tail.php";
?>