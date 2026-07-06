<?php
include_once "path.php";
include_once "head.php";



$sql_search = "";
if($member[mb_admin]=="1"){
	$sql_search = " and cp_supply_no='$member[mb_no]'";	
} else if($member[mb_admin]=="2"){
	$sql_search = " and cp_md_no='$member[mb_no]'";	
} else{

}


if(!$year) $year = date("Y"); 
if(!$month) $month = date("n"); 
if(!$day) $day = date("j"); 
$lastday = date("t",strtotime($year."-".$month."-".$day));        // 이번달의 마지막날 
$startweek = date("w",strtotime($year."-".$month."-1"));        // 첫일의 요일 

$prev = strtotime("-1 month",strtotime($year."-".$month."-1"));
$next = strtotime("+1 month",strtotime($year."-".$month."-".$day));
?>

<style>
.cal_tbl { width:100%; border-top:solid 1px #dcdcdc; border-right:solid 1px #dcdcdc; margin-top:20px; }
.cal_tbl td { border-bottom:solid 1px #dcdcdc; border-left:solid 1px #dcdcdc; height:100px; vertical-align:top; padding:10px; width:14.28%; }
.tr_title td { height:30px; text-align:center; font-size:14px; vertical-align:middle; }

.open_a { color:red; }
.choice_a { color:blue; }
.close_a { color:green; }

.yoil_0 { color:red; }
.yoil_6 { color:blue; }

.cal_tbl .tr_title td { background-color:#f5f7f9 }
.tbl_typeB th { border-top:1px solid #dcdcdc; } 

.cal_nav { overflow:hidden; font-size:15px; }
.cal_nav div { float:left; width:33.33%; }
.now_yearmonth { text-align:center; font-size:20px; font-weight:bold; }
.now_left { text-align:left; }
.now_right { text-align:right; }
.campaign_text_wrap { font-size:11px; }
.now_today { background-color:#ececec; font-weight:bold; }

.now_select_day { color:red; font-weight:bold; }
</style>

<div class="cal_nav">
	<div class="now_left">
		<a href="<?=$PHP_SELF?>?year=<?=date("Y",$prev)?>&month=<?=date("n",$prev)?>">이전 달 - <?=date("Y년 n월",$prev)?></a>
	</div>
	<div class="now_yearmonth">
		<?=$year?>년 <?=$month?>월 
	</div>
	<div class="now_right">
		<a href="<?=$PHP_SELF?>?year=<?=date("Y",$next)?>&month=<?=date("n",$next)?>">다음 달 - <?=date("Y년 n월",$next)?></a>
	</div>
</div>

<table border="0" cellpadding="0" cellspacing="0" class="cal_tbl"> 
<tr class="tr_title"> 
	<td class="yoil_0">일</td><td>월</td><td>화</td><td>수</td><td>목</td><td>금</td><td class="yoil_6">토</td> 
</tr> 
<tr> 
<?php
$gong = 0;
for($i=0; $i<$startweek; $i++){ 
?> 
	<td></td> 
	<?php
		$gong++;
	} 
	for($i=1; $i<=$lastday; $i++){ 
		$week = date("w",strtotime($year."-".$month."-".$i)); 
		if($week == 0) echo "</tr><tr>";        // 첫 요일이 0이면 개행 
	?> 
	<td <?=date("Ymd")==date("Ymd",strtotime($year."-".$month."-".$i))?"class='now_today'":""?>>
	<a class="<?
	if($week==0){
		echo "yoil_0";
	} elseif($week==6){
		echo "yoil_6";
	} else{

	}
	?>"><?=$i?></a><br><br>

	<div class="campaign_text_wrap">
	<?php
	$ymd = $year."-".sprintf("%02d",$month)."-".sprintf("%02d",$i);
	$a = sql_fetch("select count(*) as cnt from nfor_campaign where date_format(cp_sdatetime,'%Y-%m-%d')='$ymd' $sql_search");
	$b = sql_fetch("select count(*) as cnt from nfor_campaign where date_format(cp_edatetime,'%Y-%m-%d')='$ymd' $sql_search");
	$c = sql_fetch("select count(*) as cnt from nfor_campaign where date_format(cp_pick_datetime,'%Y-%m-%d')='$ymd' $sql_search");
	$d = sql_fetch("select count(*) as cnt from nfor_campaign where date_format(cp_contents_sdatetime,'%Y-%m-%d')='$ymd' $sql_search");
	$e = sql_fetch("select count(*) as cnt from nfor_campaign where date_format(cp_contents_edatetime,'%Y-%m-%d')='$ymd' $sql_search");
	$f = sql_fetch("select count(*) as cnt from nfor_campaign where date_format(cp_result_datetime,'%Y-%m-%d')='$ymd' $sql_search");
	?>
	<? if($a[cnt]){ ?><a href="<?=$PHP_SELF?>?year=<?=$year?>&month=<?=$month?>&day=<?=$i?>&itype=a" <?=$day==$i && $itype=="a"?"class='now_select_day'":""?>>캠페인신청 시작(<?=$a[cnt]?>)</a><br><? } ?>
	<? if($b[cnt]){ ?><a href="<?=$PHP_SELF?>?year=<?=$year?>&month=<?=$month?>&day=<?=$i?>&itype=b" <?=$day==$i && $itype=="b"?"class='now_select_day'":""?>>캠페인신청 종료(<?=$b[cnt]?>)</a><br><? } ?>
	<? if($c[cnt]){ ?><a href="<?=$PHP_SELF?>?year=<?=$year?>&month=<?=$month?>&day=<?=$i?>&itype=c" <?=$day==$i && $itype=="c"?"class='now_select_day'":""?>>선정자 발표(<?=$c[cnt]?>)</a><br><? } ?>
	<? if($d[cnt]){ ?><a href="<?=$PHP_SELF?>?year=<?=$year?>&month=<?=$month?>&day=<?=$i?>&itype=d" <?=$day==$i && $itype=="d"?"class='now_select_day'":""?>>리뷰등록 시작(<?=$d[cnt]?>)</a><br><? } ?>
	<? if($e[cnt]){ ?><a href="<?=$PHP_SELF?>?year=<?=$year?>&month=<?=$month?>&day=<?=$i?>&itype=e" <?=$day==$i && $itype=="e"?"class='now_select_day'":""?>>리뷰등록 종료(<?=$e[cnt]?>)</a><br><? } ?>
	<? if($f[cnt]){ ?><a href="<?=$PHP_SELF?>?year=<?=$year?>&month=<?=$month?>&day=<?=$i?>&itype=f" <?=$day==$i && $itype=="f"?"class='now_select_day'":""?>>캠페인 결과발표(<?=$f[cnt]?>)</a><br><? } ?>
	</div>

	</td> 
	<?php
		$gong++;
	} 

	$gong = 7-($gong%7);

	if($gong==7){
		$gong = 0;
	}
	for($i=0; $i<$gong; $i++){
	?> 
	<td></td>
<? } ?>
</tr> 
</table>






<?php
if($itype){

	$ymd = $year."-".sprintf("%02d",$month)."-".sprintf("%02d",$day);
	if($itype=="a") $sql = "select * from nfor_campaign where date_format(cp_sdatetime,'%Y-%m-%d')='$ymd' $sql_search";
	if($itype=="b") $sql = "select * from nfor_campaign where date_format(cp_edatetime,'%Y-%m-%d')='$ymd' $sql_search";
	if($itype=="c") $sql = "select * from nfor_campaign where date_format(cp_pick_datetime,'%Y-%m-%d')='$ymd' $sql_search";
	if($itype=="d") $sql = "select * from nfor_campaign where date_format(cp_contents_sdatetime,'%Y-%m-%d')='$ymd' $sql_search";
	if($itype=="e") $sql = "select * from nfor_campaign where date_format(cp_contents_edatetime,'%Y-%m-%d')='$ymd' $sql_search";
	if($itype=="f") $sql = "select * from nfor_campaign where date_format(cp_result_datetime,'%Y-%m-%d')='$ymd' $sql_search";

	$result = sql_query($sql);
?>
<br>
<table class="table row_tbl margin0">
<tr>
	<th>대표이미지</th>
	<th>캠페인코드</th>
	<th>제목</th>
	<th>승인상태</th>
	<th>모집/신청</th>
	<th>신청기간/<br>신청인원</th>
	<th>선정자 발표/<br>선정인원</th>
	<th>리뷰 등록기간/<br>등록인원</th>
	<th>캠페인 결과발표/<br>등록확인인원</th>
	<th>등록일/<br>수정일</th>
	<th>조회수</th>
</tr>
<?php
for($i=0; $row=sql_fetch_array($result); $i++){	
	$row[cp_url] = "{$nfor[path]}/campaign.php?cp_id={$row[cp_id]}";
?>
<tr>
	<td><a href="<?=$row[cp_url]?>" target="_blank"><?=admin_img("campaign",$row[cp_img],"50","50")?></a></td>
	<td><a href="<?=$row[cp_url]?>" target="_blank"><?=$row[cp_id]?></a></td>
	<td class="textleft">
		<a href="<?=$row[cp_url]?>" target="_blank"><?=$row[cp_subject]?><br><?=$row[cp_description]?></a>
		<div class="sns_icon_wrap">
		<? if($row[cp_media_blog]){ ?><span class="blog_icon">블로그</span><? } ?>
		<? if($row[cp_media_instagram]){ ?><span class="instagram_icon">인스타그램</span><? } ?>
		<? if($row[cp_media_youtube]){ ?><span class="youtube_icon">유튜브</span><? } ?>
		<? if($row[cp_media_shop]){ ?><span class="shop_icon">쇼핑몰</span><? } ?>
		</div>
		<br><?=$row[cp_supply_no]?admin_echo($row,"cp_supply_no"):""?>
	</td>
	<td><?=admin_echo($row,"cp_asign")?></td>
	<td><?=number_format($row[cp_recruit])?>명/<?=number_format($row[cp_order])?>명</td>
	<td>
		<?=substr($row[cp_sdatetime],0,10)?><br><?=substr($row[cp_edatetime],0,10)?><br>
		<a href="review_wait_list.php?keyword_type=rv_cp_id&keyword=<?=$row[cp_id]?>"><?=number_format($row[cp_review_wait])?>명</a>
	</td>
	<td><?=substr($row[cp_pick_datetime],0,10)?><br><a href="review_asign_list.php?keyword_type=rv_cp_id&keyword=<?=$row[cp_id]?>"><?=number_format($row[cp_review_asign])?>명</a></td>
	<td><?=substr($row[cp_contents_sdatetime],0,10)?><br><?=substr($row[cp_contents_edatetime],0,10)?><br><a href="review_post_list.php?keyword_type=rv_cp_id&keyword=<?=$row[cp_id]?>"><?=number_format($row[cp_review_post])?>명</a></td>
	<td><?=substr($row[cp_result_datetime],0,10)?><br><a href="review_post_asign_list.php?keyword_type=rv_cp_id&keyword=<?=$row[cp_id]?>"><?=number_format($row[cp_review_post_asign])?>명</a></td>
	<td><?=substr($row[cp_insert_datetime],0,10)?><br><?=substr($row[cp_update_datetime],0,10)?></td>
	<td><?=number_format($row[cp_click])?></td>
</tr>
<?php
}
?>
</table>
<?php } ?>



<?php
include_once "tail.php";
?>