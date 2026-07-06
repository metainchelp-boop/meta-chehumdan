<?php
include_once $nfor[skin_path]."mypage_head.php";
?>

<?php
include_once $nfor[skin_path]."inc_point.php";
?>

<div class="point_bank_list_wrap">
<table cellpadding="0" cellspacing="0" border="0" >
<tr>
	<th>신청일자</th>
	<th>예금주</th>
	<th>은행</th>
	<th>계좌번호</th>
	<th>출금요청금액</th>
	<th>상태</th>
	<th>상태변경일</th>
	<th>입금예정일</th>
</tr>
<tbody class="point_bank_list">
<?php
for($i=0; $i<count($return["list"]); $i++){
	$point_bank = $return["list"][$i];
?>
<tr>
	<td><span><?=$point_bank[pb_datetime]?></span></td>
	<td><?=$point_bank[pb_name]?></td>
	<td><?=$point_bank[pb_bank]?></td>
	<td><span><?=$point_bank[pb_bank_number]?></span></td>
	<td><span><?=$point_bank[pb_point]?></span>원</td>
	<td><?=$point_bank[pb_step]?></td>
	<td><span><?=$point_bank[pb_chage_datetime]?></span></td>
	<td><span><?=$point_bank[pb_send_date]?></span></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<? if(!$scroll_load){ ?><div class="page_center"><?=$pagelist?></div><? } ?>

<?php if($scroll_load){ ?>
<div class="loading_wait">
	<img src="<?=$nfor[skin_path]?>img/ajax-loader.gif">
	<span>목록을 불러오고 있습니다</span>
	<p>잠시만 기다려 주세요</p>
</div>

<script type="text/html" id="item_list_script">
<tr>
	<td><span><%=pb_datetime%></span></td>
	<td><%=pb_name%></td>
	<td><%=pb_bank%></td>
	<td><span><%=pb_bank_number%></span></td>
	<td><span><%=pb_point%></span>원</td>
	<td><%=pb_step%></td>
	<td><span><%=pb_chage_datetime%></span></td>
	<td><span><%=pb_send_date%></span></td>
</tr>
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

<div class="caution">
<!-- ◾ 포인트는 10원단위로 출금 가능하며, 최소 <span class="color1"> <?=number_format($config[cf_get_money])?>원 </span> 이상이 모여야 출금신청을 할 수 있습니다.<br>
◾ 출금요청시 포인트는 즉시 차감되며, 입금은 관리자 확인후 입급예정 절차를 거쳐 <span class="color1"> 다음 달 <?=$config[cf_get_day]?>일</span> 지급됩니다.<br>
◾ 입금은행 및 계좌번호 그리고 예금주는 반드시 회원정보의 실명과 일치하여야 지급됩니다.<br>

 -->
◾ 출금 요청 시 포인트는 즉시 차감됩니다.<br>

◾ 입금은 관리자 확인 후 입금 절차에 따라 최대 7일 소요될 수 있습니다.<br>

◾ 은행 및 계좌번호, 예금주는 정확한 정보를 기재해 주세요.<br>


</div>

<?php
include_once $nfor[skin_path]."mypage_tail.php";
?>