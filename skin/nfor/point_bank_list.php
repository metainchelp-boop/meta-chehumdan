<?php
include_once $nfor[skin_path]."head.php";
?>

<?php
include $nfor[skin_path]."inc_point.php";
?>

<div class="point_list_wrap">
	
	<ul class="point_list point_bank_list">
		<?php
		for($i=0; $i<count($return["list"]); $i++){
			$point_bank = $return["list"][$i];
		?>
		<li>
			<div>
				<p class="date txt_num"><?=$point_bank[pb_datetime]?></p>
				<p class="memo"><?=$point_bank[pb_bank]?> <b class="txt_num"><?=$point_bank[pb_bank_number]?></b> <?=$point_bank[pb_name]?></p>
				<p class="chg  txt_num"><?=$point_bank[pb_step]?><?=$point_bank[pb_send_date]?></p>
				<p class="end_datetime">상태변경<?=$point_bank[pb_chage_datetime]?></p>
				<span class="money"><?=$point_bank[pb_point]?></span>
			</div>			
		</li>
		<?php
		}
		if(!$i){
		?>
		<li style="width:100%">
			<div class="sch_no_data">
				<p>출금내역이 없습니다.</p>
			</div>
		</li>
		<?php } ?>
	</ul>

</div>

<div class="page_center"><?=$pagelist?></div>


<?php if($scroll_load){ ?>
<div class="loading_wait">
	<img src="<?=$nfor[skin_path]?>img/ajax-loader.gif">
	<span>목록을 불러오고 있습니다</span>
	<p>잠시만 기다려 주세요</p>
</div>

<script type="text/html" id="item_list_script">
<li>
	<div>
		<p class="date txt_num"><%=pb_datetime%></p>
		<p class="memo"><%=pb_bank%> <b class="txt_num"><%=pb_bank_number%></b> <%=pb_name%></p>
		<p class="chg  txt_num"><%=pb_step%><%=pb_send_date%></p>
		<p class="end_datetime">상태변경<%=pb_chage_datetime%></p>
		<span class="money"><%=pb_point%></span>
	</div>			
</li>
</script>

<script>
var is_last = 0;
var page = 1;

$(window).scroll(function() {
	if ($(window).scrollTop() == $(document).height() - $(window).height()) {
		if(is_last==0){
			++page;
			item_list_load();
		}
	}
});

function item_list_load(){
	$(".loading_wait").show();
	if(page==1){
		$(".point_bank_list").html("");
	}
	$.ajax({
		type     : "get",
		url      : "point_bank_list.php",
		data     : "json=list&page="+page,
		dataType : "json",
		cache: false,
		success  : function(data) {
			if(data.count > 0){
				template = _.template($("#item_list_script").html());
				var template_html = "";
				for(var i=0; i<data.list.length; i++) {
					template_html +=  template({pb_datetime: data.list[i].pb_datetime
						, pb_name: data.list[i].pb_name						 
						, pb_bank: data.list[i].pb_bank
						, pb_bank_number: data.list[i].pb_bank_number
						, pb_point: data.list[i].pb_point
						, pb_step: data.list[i].pb_step
						, pb_chage_datetime: data.list[i].pb_chage_datetime
						, pb_send_date: data.list[i].pb_send_date});
				}
				$(".point_bank_list").append(template_html);
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
<?php } ?>


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