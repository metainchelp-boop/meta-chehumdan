<style>
.budongsan_list{overflow:hidden; padding:10px 0px;  }
.budongsan_list ul{margin-left:-15px}
.budongsan_list li{float:left; position: relative; width:50%; padding-left:15px; padding-bottom:15px;}
.budongsan_list .box{border:solid 1px #e0e0e0;  padding:30px 20px}
.budongsan_list .thumb{position: absolute;  left:40px; top:50%; margin-top:-65px; width:120px; height:120px; overflow:hidden; border-radius:100px; } 
.budongsan_list .thumb img{height:100%;}
.budongsan_list .zzim_off{position: absolute; width:42px; height:37px; top:10px; left:20px; background:url(/skin/demo/img/zzim_off2.png);}
.budongsan_list .zzim_on{position: absolute; width:42px; height:37px; top:10px; left:20px; background:url(/skin/demo/img/zzim_on.png);}
.budongsan_list  .count_zone{position: absolute;  right:20px; top:25px; font-size:13px;;}

.budongsan_list .budongsan_info{padding-left:150px; letter-spacing:-1px;}
.budongsan_list .budongsan_info .budongsan_name{ display:block; font-size:25px; font-weight:300; margin-top:5px; letter-spacing:-1px;  font-family: 'montserrat', 'noto', 'Roboto', sans-serif;}
.budongsan_list .budongsan_info .budongsan_name:hover{color:#000;}
.budongsan_list .budongsan_info .budongsan_address{display:block; font-size:14px; color:#000; margin-top:5px;}
.budongsan_list .budongsan_info .ho_subject{display:block; max-width:300px; margin-top:10px;width: 100%; color: rgb(102, 102, 102);  font-size: 14px; font-weight: 300; line-height: 20px; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;}
.budongsan_list .budongsan_info .ceoname{display:block; max-width:300px; margin-top:5px;width: 100%; color: rgb(102, 102, 102);  font-size: 14px; font-weight: 300; line-height: 20px; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;}
.budongsan_list .budongsan_info .budongsan_tel{display:block; font-size:25px; margin-top:10px;}
</style>

<ul>
<?php
for($i=0; $i<count($return["list"]); $i++){
	$agent = $return["list"][$i];
?>
<li>
	<a class="agent_zzim_btn <?=$agent[mb_zzim_is]?>" data-mb_no="<?=$agent[mb_no]?>"></a>
	<a href="agent.php?mb_no=<?=$agent[mb_no]?>" target="_blank">
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
				<a class="budongsan_tel num_mon main_color"><?=$agent[mb_tel]?></a>
			</div>
		</div>
	</a>
</li>
<?php } ?>	
</ul>