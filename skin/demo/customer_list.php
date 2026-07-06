<?php
include_once $nfor['skin_path']."mypage_head.php";
?>

<?php if(count($return["list"]) > 0){ ?>
<div class="bstyle1_lst">
	
	<table cellpadding="0" cellspacing="0" border="0" >
	<colgroup>
		<col width="200">
		<col >
		<col width="86">
		<col width="120">
		<col width="120">
	</colgroup>
	<tr>
		<th>문의분류</th>
		<th>문의제목</th>
		<th>등록일</th>
		<th>답변여부</th>
		<th>삭제</th>
	</tr>
	<tbody class="customer_list">
	<?php
	for($i=0; $i<count($return["list"]); $i++){
		$customer = $return["list"][$i];
	?>
	<tr>
		<td><?=$customer['cs_category']?></td>
		<td><a href="customer_view.php?cs_id=<?=$customer['cs_id']?>" class="text_align_left"><?=$customer['cs_subject']?></a></td>
		<td><span class="date"><?=$customer['cs_insert_datetime']?></span></td>
		<td><span data-cs_id="<?=$customer['cs_id']?>" class="color<?=$customer['cs_reply_state']?>"><?=$customer['cs_reply_state_text']?></span></td>
		<td><span data-cs_id="<?=$customer['cs_id']?>" class="del_btn statbtn">삭제하기</span></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
	
	<?php if(!$scroll_load){ ?><div class="page_center"><?=$pagelist?></div><?php } ?>

</div>
<?php } else{ ?>
<div class="sch_no_data">
	<p>문의내역이 존재하지 않습니다.</p>
</div>
<?php } ?>

<div class="board_btn_zone"><span class="btn_pack"><a href="customer_form.php" class="btn_lg black">문의하기</a></span></div>

<?php if($scroll_load){ ?>
<div class="loading_wait">
	<img src="<?=$nfor['skin_path']?>img/ajax-loader.gif">
	<span>목록을 불러오고 있습니다</span>
	<p>잠시만 기다려 주세요</p>
</div>

<script type="text/html" id="item_list_script">
<tr>
	<td><%=cs_category%></td>
	<td><a href="customer_view.php?cs_id=<%=cs_id%>" class="text_align_left"><%=cs_subject%></a></td>
	<td><span class="date"><%=cs_insert_datetime%></span></td>
	<td><span data-cs_id="<%=cs_id%>" class="color<%=cs_reply_state%>"><%=cs_reply_state_text%></span></td>
	<td><span data-cs_id="<%=cs_id%>" class="del_btn statbtn">삭제하기</span></td>
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
		$(".customer_list").html("");
	}
	$.ajax({
		type     : "get",
		url      : "customer_list.php",
		data     : "json=list&&page="+page,
		dataType : "json",
		cache: false,
		success  : function(data) {
			if(data.count > 0){
				template = _.template($("#item_list_script").html());
				var template_html = "";
				for(var i=0; i<data.list.length; i++) {
					template_html +=  template({cs_id: data.list[i].cs_id
						, cs_insert_datetime: data.list[i].cs_insert_datetime
						, cs_subject: data.list[i].cs_subject		
						, cs_reply_state_text: data.list[i].cs_reply_state_text	
						, cs_category: data.list[i].cs_category						
						, cs_reply_state: data.list[i].cs_reply_state});
				}
				$(".customer_list").append(template_html);
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

<script>
$(document).on("click", ".del_btn", function(){
	var cs_id = $(this).data("cs_id");
	var obj = $(this).closest("tr");
	
	if(confirm("삭제하신 데이터는 복구할수 없습니다\n정말 삭제하시겠습니까?")){
		$.ajax({
			type: "post",
			data : "mode=delete&cs_id="+cs_id,
			url: "customer_list.php",
			success: function(response){
				var json = $.parseJSON(response);
				if(json["result"]=="ok"){
					obj.remove();
					<?php if(!$scroll_load){ ?>
					if($(".customer_list tr").length<1){
						location.href="customer_list.php";
					}
					<?php } ?>
				} else{
					alert(json["msg"]);
				}
			}
		});
	}
});
</script>

<?php
include_once $nfor['skin_path']."mypage_tail.php";
?>