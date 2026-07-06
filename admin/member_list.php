<?php
include_once "path.php";

include_once "inc_member_head.php";

if($mode=="list_asign"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		member_asign($_POST[$id][$k]);
	}
	json_return("선택한 인원에 대해서 가입승인 처리되었습니다","ok");
}

if($mode=="list_leave"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		member_leave($_POST[$id][$k]);
	}
	json_return("선택한 인원에 대해서 탈퇴신청 처리되었습니다","ok");
}

if($mode=="leave"){
	demo_check_json();
	member_leave($mb_no);
	json_return("선택한 인원에 대해서 탈퇴신청 처리되었습니다","ok");
}

if($mode=="list_delete"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		member_delete($_POST[$id][$k]);
	}
	json_return("선택한 인원에 대해서 탈퇴승인 처리되었습니다","ok");
}

if($mode=="list_leave_cancel"){
	demo_check_json();
	for($i=0; $i<count($chk); $i++){
		$k = $_POST['chk'][$i];
		member_leave_cancel($_POST[$id][$k]);
	}
	json_return("선택한 인원에 대해서 원복 처리되었습니다","ok");
}

if($mode=="delete"){
	demo_check_json();
	member_delete($mb_no);
	json_return("선택한 인원에 대해서 탈퇴승인 처리되었습니다","ok");
}

$sql_search = " where (1) ";

include_once "inc_member_tail.php";
include_once "head.php";
include_once "inc_member_search.php";
?>

<form name="flist" id="flist" method="post">
<?=admin_hidden($hidden,"ex_id")?>
<?=admin_get()?>
<table class="table row_tbl margin0">
<tr>
	<th><?=admin_checkbox($row,"chkall")?></th>
	<? if(basename($PHP_SELF)=="supply_list.php"){?>
	<th>상호</th>
	<th>전화번호</th>
	<? } ?>
	<th>아이디</th>
	<th>닉네임</th>
	<th>이름</th>
	<th>휴대폰</th>
	<th>이메일</th>
	<th>성별</th>
	<th>가입경로</th>
	<th>등급</th>
	<th>연결계정</th>
	<th>포인트</th>
	<th>방문횟수</th>
	<th>회원가입일자</th>
	<th>최근접속일자</th>
	<th>승인여부</th>
	<th>메일동의</th>
	<th>SMS동의</th>
	<th>네이버블로그</th>
	<? if(basename($PHP_SELF)=="leave_list.php"){ ?>
	<th>탈퇴신청일자</th>
	<th>탈퇴사유</th>
	<? } ?>
	<th>정보수정</th>
	<th>탈퇴신청</th>
</tr>
<?php
for($i=0; $row=sql_fetch_array($result); $i++){
	$row = nfor_tag_out($row);

	$row["chk[]"] = $i;
	$row["{$id}[{$i}]"] = $row[$id];

	//회원정보 추출
	$result_mem = sql_fetch("select * from nfor_member where mb_no='".$row[mb_no]."' limit 1");
?>
<tr>
	<td><?=admin_checkbox($row,"chk[]","chk")?><?=admin_hidden($row,"{$id}[{$i}]")?></td>
	<? if(basename($PHP_SELF)=="supply_list.php"){?>
	<td><?=$row[mb_cp_name]?></td>
	<td><?=$row[mb_tel]?></td>
	<? } ?>
	<td><?=$row[mb_id]?></td>
	<td><?=$row[mb_nick]?></td>
	<td><a href="javascript:member('<?=$row[mb_no]?>')"><?=$row[mb_name]?></a></td>
	<td><?=$row[mb_hp]?></td>
	<td><?=$row[mb_email]?></td>
	<td><?=admin_echo($row,"mb_sex")?></td>
	<td><?=admin_echo($row,"mb_join_channel")?></td>
	<td>
		<?=admin_echo($row,"mb_level")?><?php if($result_mem[mb_blog]) { ?><br>(<span id="blog_totalcount2_<?=$result_mem[mb_blog]?>" style="font-weight:bold; color:orange;">브론즈</span>)<?php } ?>
	</td>
	<td><?=member_sns_type($row)?></td>
	<td><a href="point_list.php?pt_mb_no=<?=$row[mb_no]?>"><?=number_format($row[mb_point])?> Point</a></td>
	<td><?=number_format($row[mb_login_count])?>회</td>
	<td><?=substr($row[mb_datetime],0,10)?></td>
	<td><?=substr($row[mb_login_datetime],0,10)?></td>
	<td><?=admin_echo($row,"mb_asign")?></td>
	<td><?=admin_echo($row,"mb_mailling")?></td>
	<td><?=admin_echo($row,"mb_sms")?></td>
	<td style="padding:3px;">
	<?php
	if($result_mem[mb_blog]) {
	?>
		<?=$result_mem[mb_blog]?><br>(<span id="blog_totalcount_<?=$result_mem[mb_blog]?>" data-ref="<?=$result_mem[mb_blog]?>" style="font-weight:bold; color:orange;"></span>)
	<?php
	}
	?>
	</td>
	<? if(basename($PHP_SELF)=="leave_list.php"){ ?>
	<td><?=substr($row[mb_leave_datetime],0,10)?></td>
	<td><?=$row[mb_secession]?></td>
	<? } ?>
	<td><?=admin_a("edit", "수정", "btn btn-white btn-sm", "", "{$form}?{$qstr}&{$id}={$row[$id]}")?></td>
	<td><?=admin_a("delete", basename($PHP_SELF)=="leave_list.php"?"탈퇴승인":"탈퇴신청", "btn btn-white btn-sm nfor_button", "data-confirm=\"".(basename($PHP_SELF)=="leave_list.php"?"탈퇴승인":"탈퇴신청")."하시겠습니까?\" data-data=\"mode=".(basename($PHP_SELF)=="leave_list.php"?"delete":"leave")."&{$id}={$row[$id]}\"")?></td>
</tr>
<?php
}
$pagelist = get_paging($config[cf_write_pages], $page, $total_page, "?$qstr&page=");
?>
</table>

<div class="bottom_btn">

	<div class="form-inline">


	<? if(basename($PHP_SELF) == "member_list.php"){ ?>
		<?=admin_button("list_asign", "가입승인", "btn btn-lg btn-red")?>
	<? } ?>

	<? if(basename($PHP_SELF) == "leave_list.php"){ ?>
		<?=admin_button("list_delete", "탈퇴승인", "btn btn-lg btn-red")?>

		<?=admin_button("list_leave_cancel", "원복하기", "btn btn-lg btn-black")?>
	<? } else{ ?>
		<?=admin_button("list_leave", "탈퇴신청", "btn btn-lg btn-red")?>
	<? } ?>

	<? if(basename($PHP_SELF) <> "leave_list.php"){ ?>
		<?=admin_a("form", "등록하기", "btn-lg btn-black btn", "", $form)?>
	<? } ?>

	</div>

</div>

<div class="table_btn"><?=$pagelist?></div>
</form>

<script>
$(document).on("click", "#chkall", function(){
	nfor_chk_all(this, 'chk');
});

$(document).on("click", "#list_delete", function(){
	nfor_list_reload('탈퇴승인','list_delete');
});

$(document).on("click", "#list_leave", function(){
	nfor_list_reload('탈퇴신청','list_leave');
});

$(document).on("click", "#list_asign", function(){
	nfor_list_reload('가입승인','list_asign');
});

$(document).on("click", "#list_leave_cancel", function(){
	nfor_list_reload('원복하기','list_leave_cancel');
});


</script>

<script>

	//블로그 최근 5일 방문자
	$("[id*='blog_totalcount_']").each(function(){
		var blog_id = $(this).data('ref');

		$.ajax({
			type     : "post",
			url      : nfor_path + "/admin/ajax_blog_count_chart.php",
			data     : "blog_id="+blog_id,
			success  : function(data) {
				var arr = data.split("|:|");
				//console.log('arr', arr[0], arr[1], arr[2], arr[3]);

				labels = arr[1].split(',');
				datas = arr[2].split(',');
				total_count = arr[3];

				if(arr[0] == 'SUCCESS') {
					$('#blog_totalcount_'+blog_id).html(total_count);
					if(total_count < 3000) {
						$('#blog_totalcount2_'+blog_id).html("브론즈");
					} else if(total_count < 10000) {
						$('#blog_totalcount2_'+blog_id).html("실버");
					} else if(total_count < 40000) {
						$('#blog_totalcount2_'+blog_id).html("골드");
					} else if(total_count < 70000) {
						$('#blog_totalcount2_'+blog_id).html("플레티넘");
					} else {
						$('#blog_totalcount2_'+blog_id).html("다이아몬드");
					}
				} else {

					$('#blog_totalcount_'+blog_id).text('0');
				}

			},
			error: function(){
				console.log("Ajax failed");
			}
		});

	});
</script>

<?php
include_once "tail.php";
?>