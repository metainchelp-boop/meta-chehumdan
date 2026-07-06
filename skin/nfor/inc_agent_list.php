<style>
.budongsan_list{overflow:hidden; padding:0px 0px; background-color:#ffffff; }
.budongsan_list ul{}
.budongsan_list li{ position: relative; width:100%;}
.budongsan_list .box{border-bottom:solid 1px #e0e0e0;  padding:40px 20px 20px;}
.budongsan_list .thumb{position: absolute;  right:15px; top:50%; margin-top:-65px; width:120px; height:120px; overflow:hidden; border-radius:100px;z-index:25 } 
.budongsan_list .thumb img{height:100%;}
.budongsan_list .zzim_off{position: absolute; width:42px; height:37px; top:15px; right:5px; background:url(/skin/demo/img/zzim_off2.png); z-index:26;}
.budongsan_list .zzim_on{position: absolute; width:42px; height:37px; top:15px; right:5px; background:url(/skin/demo/img/zzim_on.png); z-index:26;}
.budongsan_list  .count_zone{position: absolute;  left:20px; top:20px; font-size:11px; color:#666;}

.budongsan_list .budongsan_info{padding-right:120px; letter-spacing:-1px;}
.budongsan_list .budongsan_info .budongsan_name{ display:block; font-size:18px; font-weight:600; margin-top:5px; letter-spacing:-1px;  font-family: 'montserrat', 'noto', 'Roboto', sans-serif;}
.budongsan_list .budongsan_info .budongsan_name:hover{color:#000;}
.budongsan_list .budongsan_info .budongsan_address{display:block; font-size:12px; color:#000; margin-top:5px;}
.budongsan_list .budongsan_info .ho_subject{display:block; max-width:300px; margin-top:10px;width: 100%; color: rgb(102, 102, 102);  font-size: 14px; font-weight: 300; line-height: 20px; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;}
.budongsan_list .budongsan_info .ceoname{display:block; max-width:300px; margin-top:5px;width: 100%; color: rgb(102, 102, 102);  font-size: 12px; font-weight: 300; line-height: 20px; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;}
.budongsan_list .budongsan_info .budongsan_tel{display:block; font-size:25px; margin-top:10px;}
.budongsan_list .budongsan_info .budongsan_tel span{display:inline-block;background-color:#ccc; color:#fff; border-radius:5px; padding:3px 5px; font-size:11px; vertical-align:3px; margin-right:5px; }
</style>
<ul>
<?php
for($i=0; $i<count($return["list"]); $i++){
	$agent = $return["list"][$i];
?>
	<li>
	<a class="agent_zzim_btn <?=$agent[mb_zzim_is]?>" data-mb_no="<?=$agent[mb_no]?>"></a>
	<a href="agent.php?mb_no=<?=$agent[mb_no]?>">
	<div class="box">
		<div class="thumb">	
			<img src="./data/house/thumb/thumb-2009376294_v5nYCcgx_09_718x457.png">
		</div>
		<div class="count_zone">
			<span class="ho_hit">조회 <b class="num_mon"><?=$agent[mb_hit]?></b></span>
			<span class="ho_zzim">찜 <b class="num_mon"><?=$agent[mb_zzim]?></b></span>
			<span class="ho_zzim">매물 <b class="num_mon"><?=$agent[mb_house]?></b></span>
		</div>
		<div class="budongsan_info">
			<span class="budongsan_name"><?=$agent[mb_cp_name]?></span>
			<span class="budongsan_address"><?=$agent[mb_cp_addr1]?> <?=$agent[mb_cp_addr2]?></span>
			<span class="ceoname">대표자: <?=$agent[mb_cp_ceo]?></span>
			<a class="budongsan_tel num_mon main_color"><span>전화번호</span><?=$agent[mb_tel]?></a>
		</div>
	</div>
	</a>
	</li>
<?php } ?>	
</ul>