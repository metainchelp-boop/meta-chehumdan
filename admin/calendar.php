<?php
include_once "path.php";

if(!$year) $year = date('Y'); 
if(!$month) $month = date('m');

$time = strtotime($year.'-'.$month.'-01');
list($tday, $sweek) = explode('-', date('t-w', $time));  // 총 일수, 시작요일 
$tweek = ceil(($tday + $sweek) / 7);  // 총 주차 
$lweek = date('w', strtotime($year.'-'.$month.'-'.$tday));  // 마지막요일 


$ymd_prev = strtotime("-1 month",$time);
$ymd_next = strtotime("+1 month",$time);

$calendar[year_prev] = date("Y",$ymd_prev);
$calendar[month_prev] = date("m",$ymd_prev);

$calendar[year_next] = date("Y",$ymd_next);
$calendar[month_next] = date("m",$ymd_next);

$calendar[year] = $year;
$calendar[month] = $month;

for($n=1,$i=0; $i<$tweek; $i++){
	for($k=0; $k<7; $k++){
		$res = array();
		if(($i == 0 && $k < $sweek) || ($i == $tweek-1 && $k > $lweek)){
			$res[day] = "";
			$res[check] = "off";
			$calendar["list"][] = $res;
			continue;
		}

		$res[day] = $n;
		$me_ymd = $year."-".sprintf("%02d",$month)."-".sprintf("%02d",$n);
		$res[me_ymd] = $me_ymd;
		$check = sql_fetch("select * from nfor_memo where me_mb_no='$member[mb_no]' and date_format(me_ymd,'%Y-%m-%d') ='$me_ymd'");
		if($check[me_id]){
			$res[check] = "on";
		}
		$n++;
		$calendar["list"][] = $res;
	} 
}

if(basename($PHP_SELF)=="calendar.php"){

	if($json=="list"){
		$return = $calendar;
		json_return($nfor[title],"ok");
	}

}
?>
<style>
.month_wrap { overflow:hidden; margin:5px 0px; }
.month_wrap .prev { float:left; width:25%; display:block; text-align:left; cursor:pointer; }
.month_wrap .year_month { float:left; width:50%;  text-align:center; font-weight:bold; }
.month_wrap .next { float:left; width:25%; display:block; text-align:right; cursor:pointer; }

.ul_calendar_head { width:100%; overflow:hidden; }
.ul_calendar_head li { width:14.28%; height:24px; line-height:24px; float:left; font-size:13px; box-sizing:border-box; -webkit-box-sizing:border-box; text-align:center; }
.ul_calendar_head li div { background-color:#eff3f9; height:100%; width:100%; border:solid 1px #fff; } 


.ul_calendar { width:100%; overflow:hidden; }
.ul_calendar li { width:14.28%; height:24px; line-height:24px; float:left; font-size:13px; box-sizing:border-box; -webkit-box-sizing:border-box; text-align:center; }
.ul_calendar li div { cursor:pointer; background-color:#eff3f9; padding:0px; height:100%; width:100%; border:solid 1px #fff; position:relative; text-align:center; } 
.ul_calendar li div.on { background-color:#7baade; color:#fff; }
.ul_calendar li div.off { background-color:#fff; color:#fff; }

#layer_calendar #me_memo { height:300px; }
</style>


<div class="month_wrap">
	<a data-year="<?=$calendar[year_prev]?>" data-month="<?=$calendar[month_prev]?>" class="prev">이전</a>
	<div class="year_month"><span class="year"><?=$calendar[year]?></span>년 <span class="month"><?=$calendar[month]?></span>월</div>
	<a data-year="<?=$calendar[year_next]?>" data-month="<?=$calendar[month_next]?>" class="next">다음</a>
</div>

<ul class="ul_calendar_head">
	<li style="color:#cc3300"><div>일</div></li>
	<li><div>월</div></li>
	<li><div>화</div></li>
	<li><div>수</div></li>
	<li><div>목</div></li>
	<li><div>금</div></li>
	<li style="color:#0099ff"><div>토</div></li>
</ul>

<ul class="ul_calendar">
<?
for($i=0; $i<count($calendar["list"]); $i++){
?>
<li>
	<div data-me_ymd="<?=$calendar["list"][$i][me_ymd]?>" class="<?=$calendar["list"][$i][check]?>">
		<?=$calendar["list"][$i][day]?>
	</div>		
</li>
<? } ?>
</ul>


<script type="text/html" id="item_list_script">
<li>
	<div data-me_ymd="<%=me_ymd%>" class="<%=check%>">
		<%=day%>
	</div>		
</li>
</script>

<script>
$(document).on("click", ".ul_calendar li div", function(){
	var me_ymd = $(this).data("me_ymd");
	if(me_ymd){
		nfor_layer("calendar", "me_ymd="+me_ymd, "달력메모 " + me_ymd);
	}
});

$(document).on("click", ".month_wrap a", function(){
	$.ajax({
		type     : "get",
		url      : "calendar.php",
		data     : "json=list&year="+$(this).data("year")+"&month="+$(this).data("month"),
		dataType : "json",
		cache: false,
		success  : function(data) {
			console.log(data);
			$(".year").html(data.year);
			$(".month").html(data.month);
			$(".prev").data("year",data.year_prev).data("month",data.month_prev);
			$(".next").data("year",data.year_next).data("month",data.month_next);	
			template = _.template($("#item_list_script").html());
			var template_html = "";
			for(var i=0; i<data.list.length; i++) {
				template_html +=  template({day: data.list[i].day
					, check: data.list[i].check, me_ymd: data.list[i].me_ymd});
			}
			$(".ul_calendar").html(template_html);			
		},
		error: function(e){
			console.log(e);
			console.log("Ajax failed");
		}
	});
});

$(document).on("click","#layer_calendar #memo_submit_btn",function(){
	$.ajax({
		type:"post",
		data :$("#layer_calendar #layer_insert_form").serialize(),
		url:"layer_calendar.php",
		success:function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				document.location.reload();
			} else{
				if(json["msg"]){
					alert(json["msg"]);
				}
			}
		}
	});
	event.preventDefault();
});

$(document).on("click","#layer_calendar #memo_delete_btn",function(){
	$.ajax({
		type     : "get",
		url      : "layer_calendar.php",
		data     : "mode=delete&me_ymd="+$("#me_ymd").val(),
		dataType : "json",
		cache: false,
		success  : function(data) {
			document.location.reload();
		},
		error: function(e){
			console.log(e);
			console.log("Ajax failed");
		}
	});
});
</script>