<?php
include_once $nfor[skin_path]."community_head.php";
?>

<div class="calender_wrap">
	<div class="calender_top">
			<div class="inner calender_month_wrap">
				<div class="cal_left"><a data-year="<?=$return[year_prev]?>" data-month="<?=$return[month_prev]?>" class="prev"><img src="<?=$nfor[skin_path]?>img/check_left.png" alt="이전"></a></div>
				<div class="cal_cen"><b class="year txt_num"><?=$return[year]?></b>년 <b class="txt_num month"><?=$return[month]?></b>월</div>
				<div class="cal_left"><a  data-year="<?=$return[year_next]?>" data-month="<?=$return[month_next]?>" class="next"><img src="<?=$nfor[skin_path]?>img/check_right.png" alt="다음"></a></div>
			</div>
			<span class="today_time">오늘날짜 : <b class="txt_num"><?=date("Y년 m월 d일")?></b></span>
			<a class="attendance_btn attendance_btn_check">출석체크</a>
			</div>	
	</div>
	<div class="calendar">
		<ul class="ul_calendar_head">
			<li><div>일</div></li>
			<li><div>월</div></li>
			<li><div>화</div></li>
			<li><div>수</div></li>
			<li><div>목</div></li>
			<li><div>금</div></li>
			<li><div>토</div></li>
		</ul>
	</div>
	<div class="calendar">
		<ul class="ul_calendar">
		<?
		for($i=0; $i<count($return["list"]); $i++){
		?>
		<li>
			<div>
				<p><?=$return["list"][$i][day]?></p> 
				<? if($return["list"][$i][check]){ ?>
				<img src="<?=$nfor[skin_path]?>img/check_on.png" class="check">
				<? } ?>
			</div>		
		</li>
		<? } ?>
		</ul>
	</div>
</div>

<script type="text/html" id="item_list_script">
<li>
	<div>
		<p><%=day%></p> 
		<% if(check){ %>
		<img src="<?=$nfor[skin_path]?>img/check_on.png" class="check">
		<% } %>
	</div>		
</li>
</script>

<script>
$(document).on("click", ".calender_month_wrap a", function(){
	$.ajax({
		type     : "get",
		url      : "attendance.php",
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
					, check: data.list[i].check});
			}
			$(".ul_calendar").html(template_html);			
		},
		error: function(e){
			console.log(e);
			console.log("Ajax failed");
		}
	});
});
$(document).on("click", ".attendance_btn", function(){
	$.ajax({
		type: "post",
		data : "mode=insert&ftimestamp=<?=date("YmdHis").substr(microtime(),2,6)?>",
		url: "attendance.php",
		success: function(response){
			var json = $.parseJSON(response);
			if(json["result"]=="ok"){
				alert(json["msg"]);
				location.reload();
			} else{
				alert(json["msg"]);
			}
		}
	});
});
</script>

<?php
include_once $nfor[skin_path]."community_tail.php";
?>