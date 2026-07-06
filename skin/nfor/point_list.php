<?php
include_once $nfor[skin_path]."head.php";
?>

<?php 
include_once $nfor[skin_path]."inc_point.php";
?>
<?php if(count($return["list"]) > 0){ ?>
	<div class="point_list_wrap">
		<ul class="point_list">
		<?php
		for($i=0; $i<count($return["list"]); $i++){
			$point = $return["list"][$i];
		?>
		<li>	
			<span class="date"><?=$point[pt_datetime]?></span>
			<span class="memo"><?=$point[pt_memo]?></span>	
			<span class="grade <?=$point[pt_plus_minus]?>"><?=$point[pt_point]?></span>	
		</li>
		<?php } ?>
		</ul>
	</div>

	<?php if(!$scroll_load){ ?><div class="page_center"><?=$pagelist?></div><?php } ?>
<?php } else { ?>

	<div class="sch_no_data">
		<p>적립내역이 없습니다.</p>
	</div>

<?php } ?>

<? if($scroll_load){ ?>
<div class="loading_wait">
	<img src="<?=$nfor[skin_path]?>img/ajax-loader.gif">
	<span>목록을 불러오고 있습니다</span>
	<p>잠시만 기다려 주세요</p>
</div>

<script type="text/html" id="item_list_script">
<li>
	<span class="date"><%=pt_datetime%></span>
	<span class="memo"><%=pt_memo%></span>	
	<span class="grade <%=pt_plus_minus%>"><%=pt_point%></span>
</li>
</script>

<script>
var is_last = 0;
var page = 1;

$(window).scroll(function(){
    var scrolltop = parseInt ( $(window).scrollTop() );
    if( scrolltop >= $(document).height() - $(window).height() - 500 ){
        if(is_last==0){
			++page;
			item_list_load();
		}
    }
});

function item_list_load(){
	$(".loading_wait").show();
	if(page==1){
		$(".point_list").html("");
	}
	$.ajax({
		type     : "get",
		url      : "point_list.php",
		data     : "json=list&&page="+page,
		dataType : "json",
		cache: false,
		success  : function(data) {
			if(data.count > 0){
				template = _.template($("#item_list_script").html());
				var template_html = "";
				for(var i=0; i<data.list.length; i++) {
					template_html +=  template({pt_datetime: data.list[i].pt_datetime
						, pt_memo: data.list[i].pt_memo
						, pt_plus_minus: data.list[i].pt_plus_minus						
						, pt_point: data.list[i].pt_point});
				}
				$(".point_list").append(template_html);
			}
			$(".loading_wait").hide();			
			if(data.last_page == 1){
				is_last++;
			}			
		},
		error: function(e){
			console.log(e);
			console.log("Ajax failed");
		}
	});
}
</script>
<? } ?>

<div id="money_msg_wrap" class="msg_title_wrap listBlit_info">
	<span class="msg_title">출금 신청시 주의사항</span>
	<ul id="money_msg">
		<li>
		<li>포인트는 10원단위로 출금 가능하며, 최소 <span class="point-color1"> <?=number_format($config[cf_get_money])?>원 </span> 이상이 모여야 출금신청을 할 수 있습니다.</li>
		<li>출금요청시 포인트는 즉시 차감되며, 입금은 관리자 확인후 입급예정 절차를 거쳐 <span class="point-color1"> 다음 달 <?=$config[cf_get_day]?>일</span> 지급됩니다.</li>
		<li>입금은행 및 계좌번호 그리고 예금주는 반드시 회원정보의 실명과 일치하여야 지급됩니다.</li>
	</ul>
</div>

<?php
include_once $nfor[skin_path]."tail.php";
?>