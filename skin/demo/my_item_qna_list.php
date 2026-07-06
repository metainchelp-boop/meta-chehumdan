<?php
include_once $nfor[skin_path]."mypage_head.php";
?>

<style>
.my_item_qna_list_wrap { margin:0px; padding:0px; }
.my_item_qna_list { background-color:#fff; }
.my_item_qna_list li { border-bottom:solid 1px #f1f1f1; background-color:#FFF; box-sizing:border-box; -webkit-box-sizing:border-box; }
.my_item_qna_list .title{ font-size:12px; display:inline-block; padding:15px 0 15px;border-top:1px solid #333;background-color:#f7f7f7;text-align:center;vertical-align:middle;color:#666; font-weight:normal; font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;;}

.q_wrap { position:relative; min-height:92px; padding:5px 50px 5px 105px; border-bottom:solid 1px #cccdce; color:#363636; }
.q_wrap .it_img_wrap { position:absolute; top:0; left:0; padding:8px; }
.q_wrap .it_img_wrap img { width:86px; height:86px; }
.q_wrap .it_name { font-size:13px; margin-top:0px; text-overflow:ellipsis; white-space:nowrap; overflow:hidden; font-family:NanumGothicBold;}

.q_wrap .qa_memo { overflow:hidden; width:650px; font-size:12px; line-height:18px;overflow:hidden; text-overflow:ellipsis;-webkit-line-clamp:2; display:-webkit-box; -webkit-box-orient:vertical; color:#666; }

.q_wrap .qa_reply_state {position:absolute; top:20px; right:55px; display:block; font-size:.75em; font-weight:bold; color:#e83862; margin:5px 0px;font-family:NanumGothicBold; }
.q_wrap .open_ico { display:block; position:absolute; top:50%; right:10px; width:20px; height:12px; margin-top:-6px; overflow:hidden; background:url(<?=$nfor[skin_path]?>img/layout_in.png) 0 0 no-repeat; background-position:-45px -71px; background-size:400px auto; }
.q_wrap .open_ico_off { display:block; position:absolute; top:50%; right:10px; width:20px; height:12px; margin-top:-6px; overflow:hidden; background:url(<?=$nfor[skin_path]?>img/layout_in.png) 0 0 no-repeat; background-position:-45px -71px; background-size:400px auto; webkit-transform:rotate(-180deg); }

.a_wrap { position:relative; min-height:60px; padding:15px; border-bottom:solid 1px #cccdce; color:#363636; }
.a_wrap .qa_insert_datetime { font-size:11px; color:#999; }
.a_wrap .qa_mb_id { display:block; font-size:.8em; font-weight:bold; color:#e83862; }
.a_wrap .qa_memo { margin-top:3px; line-height:1.6; font-size:12px; color:#6c7580; }


</style>


<div class="my_item_qna_list_wrap">

	<ul class="my_item_qna_list">
	<li>
	<span class="title" style="width:730px;">상품명/문의내용</span><span class="title" style="width:155px;">답변여부</span>
	</li>
	<?php
	for($i=0; $i<count($return["list"]); $i++){
		$qna = $return["list"][$i];
	?>
	<li class="q_wrap" data-qa_id="<?=$qna[qa_id]?>">
		<a href="item.php?it_id=<?=$qna[qa_it_id]?>" class="it_img_wrap">
			<img src="<?=$qna[qa_it_img]?>">
		</a>
		<p class="it_name"><?=$qna[qa_it_name]?></p>
		<span class="qa_reply_state"><?=$qna[qa_reply_state]?></span>
		<p class="qa_memo"><?=$qna[qa_memo]?></p>
		<i class="open_ico_off"></i>
	</li>
	<?php
		for($k=0; $k<count($qna["reply"]); $k++){
			$qna_reply = $qna["reply"][$k];
	?>
	<li class="a_wrap qa_parent_<?=$qna_reply[qa_parent]?>" style="display:none;">
		<span class="qa_insert_datetime"><?=$qna_reply[qa_insert_datetime]?></span>
		<span class="qa_mb_id"><?=$qna[qa_mb_id]?></span>
		<p class="qa_memo"><?=$qna_reply[qa_memo]?></p>
	</li>
	<? 
		}
	}
	?>
	</ul>
	<? if(!$scroll_load){ ?><div class="page_center"><?=$pagelist?></div><? } ?>


	
<? if(!count($return["list"])){ ?>
<div class="sch_no_data">
	<p>상품문의 내역이 존재하지 않습니다.</p>
</div>
<? } ?>

</div>


<script>
$(document).on("click", ".q_wrap", function(){
	var qa_id = $(this).data("qa_id");
	$(".qa_parent_"+qa_id).toggle();
});
</script>

<? if($scroll_load){ ?>
<div class="loading_wait">
	<img src="<?=$nfor[skin_path]?>img/ajax-loader.gif">
	<span>목록을 불러오고 있습니다</span>
	<p>잠시만 기다려 주세요</p>
</div>

<script type="text/html" id="item_list_script">
<li class="q_wrap" data-qa_id="<%=qa_id%>">
	<a href="item.php?it_id=<%=qa_it_id%>" class="it_img_wrap">
		<img src="<%=qa_it_img%>">
	</a>
	<p class="it_name"><%=qa_it_name%></p>
	<span class="qa_reply_state"><%=qa_reply_state%></span>
	<p class="qa_memo"><%=qa_memo%></p>
	<i class="open_ico_off"></i>
</li>
<%
_(reply).each(function(data){
%>
<li class="a_wrap qa_parent_<%=data.qa_parent%>" style="display:none;">
	<span class="qa_insert_datetime"><%=data.qa_insert_datetime%></span>
	<span class="qa_mb_id"><%=data.qa_mb_id%></span>
	<p class="qa_memo"><%=data.qa_memo%></p>
</li>
<%
});
%>
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
		$(".my_item_qna_list").html("");
	}
	$.ajax({
		type     : "get",
		url      : "my_item_qna_list.php",
		data     : "json=list&&page="+page,
		dataType : "json",
		cache: false,
		success  : function(data) {
			if(data.count > 0){
				template = _.template($("#item_list_script").html());
				var template_html = "";
				for(var i=0; i<data.list.length; i++) {
					template_html +=  template({qa_id: data.list[i].qa_id
						, qa_it_id: data.list[i].qa_it_id
						, qa_mb_id: data.list[i].qa_mb_id						
						, qa_memo: data.list[i].qa_memo
						, qa_reply_state: data.list[i].qa_reply_state
						, qa_it_img: data.list[i].qa_it_img
						, qa_it_name: data.list[i].qa_it_name
						, reply: data.list[i].reply});
				}
				$(".my_item_qna_list").append(template_html);
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

<?php
include_once $nfor[skin_path]."mypage_tail.php";
?>