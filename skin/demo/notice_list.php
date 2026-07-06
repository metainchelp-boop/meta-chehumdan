<?php
include_once $nfor[skin_path]."cus_head.php";
?>
<div class="bstyle1_lst">
<table border="0" cellpadding="0" cellspacing="0" >
<colgroup>
	<col width="80">
	<col width="80">
	<col>
	<col width="121">
	<col width="80">
</colgroup>
<tr>
	<th>번호</th>
	<th>분류</th>
	<th>제목</th>
	<th>등록일</th>
	<th>조회</th>
</tr>
<tbody class="notice_list">
<?php
for($i=0; $i<count($return["list"]); $i++){
	$notice = $return["list"][$i];
?>
<tr>
	<td><span class="txt_num"><?=$notice[no_num]?></span></td>	
	<td><span class="sort">[<?=$notice[no_category]?>]</span></td>
	<td style="text-align:left"><a href="notice_view.php?no_id=<?=$notice[no_id]?>" ><span><?=$notice[no_subject]?></span></a></td>
	<td><span class="txt_num"><?=$notice[no_insert_datetime]?></span></td>
	<td><span class="txt_num"><?=$notice[no_hit]?></span></td>
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
	<td><span class="txt_num"><%=no_num%></span></td>	
	<td><span class="sort">[<%=no_category%>] </span></td>
	<td style="text-align:left"><a href="notice_view.php?no_id=<%=no_id%>" ><span><%=no_subject%></span></a></td>
	<td><span class="txt_num"><%=no_insert_datetime%></span></td>
	<td><span class="txt_num"><%=no_hit%></span></td>
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
		$(".notice_list").html("");
	}
	$.ajax({
		type     : "get",
		url      : "notice_list.php",
		data     : "json=list&page="+page,
		dataType : "json",
		cache: false,
		success  : function(data) {
			if(data.count > 0){
				template = _.template($("#item_list_script").html());
				var template_html = "";
				for(var i=0; i<data.list.length; i++) {
					template_html +=  template({no_id: data.list[i].no_id
						, no_num: data.list[i].no_num						 
						, no_category: data.list[i].no_category
						, no_subject: data.list[i].no_subject
						, no_hit: data.list[i].no_hit
						, no_insert_datetime: data.list[i].no_insert_datetime});
				}
				$(".notice_list").append(template_html);
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

<?php
include_once $nfor[skin_path]."cus_tail.php";
?>