<?php
include_once $nfor[skin_path]."mypage_head.php";
?>

<style>
.my_campaign_qna_list_wrap { margin:0px; padding:0px; }
.my_campaign_qna_list { background-color:#fff; border-top:solid 1px #333;}
.my_campaign_qna_list .ttt {display:flex;}
.my_campaign_qna_list .ttt .title{flex:6}
.my_campaign_qna_list .ttt .title +.title{flex:1}
.my_campaign_qna_list li { border-bottom:solid 1px #f1f1f1; background-color:#FFF; box-sizing:border-box; -webkit-box-sizing:border-box; }
.my_campaign_qna_list .title{ color: #000; line-height: 45px;padding: 10px 30px; font-size:16px; display:inline-block; background-color:#f7f7f7;text-align:center;vertical-align:middle; }

.q_wrap { position:relative; min-height:150px; padding:20px 50px 5px 155px; border-bottom:solid 1px #cccdce; color:#363636; cursor:pointer; }
.q_wrap .it_img_wrap { position:absolute; top:10px; left:10px; padding:8px; }
.q_wrap .it_img_wrap img { width:100px; height:100px; border-radius:5px;}
.q_wrap .it_name { display:block;  font-size:18px; margin-bottom:10px; color:#000;  text-overflow:ellipsis; white-space:nowrap; overflow:hidden;}

.q_wrap .qa_memo { overflow:hidden; width:650px; font-size:15px; line-height:18px; overflow:hidden; text-overflow:ellipsis;-webkit-line-clamp:2; display:-webkit-box; -webkit-box-orient:vertical; color:#888; }

.q_wrap .qa_reply_state {position:absolute; top:50px; right:55px; ; border: solid 1px #ff0000; box-sizing: border-box; -webkit-box-sizing: border-box; display: inline-block; padding: 5px 10px;line-height: 16px; font-size: 13px;color: #ff0000;letter-spacing: -1px; margin-right: 10px;margin:5px 0px; }


.a_wrap { position:relative; min-height:60px; padding:20px; border-bottom:solid 1px #cccdce; color:#363636; }
.a_wrap .qa_insert_datetime { font-size:12px; color:#999; margin-bottom:10px;  display:block; }
.a_wrap .qa_mb_id { display:block; font-size:.8em; font-weight:bold; color:#e83862; }
.a_wrap .qa_memo { margin-top:3px; line-height:1.5; font-size:14px; color:#666; }


</style>


<div class="my_campaign_qna_list_wrap">

	<ul class="my_campaign_qna_list">
	<li class="ttt">
		<span class="title"style="width:*;">캠페인명/문의내용</span>
		<span class="title" style="width:155px;">답변여부</span>
	</li>
	<?php
	for($i=0; $i<count($return["list"]); $i++){
		$qna = $return["list"][$i];
	?>
	<li class="q_wrap" data-qa_id="<?=$qna[qa_id]?>">
		<a href="campaign.php?cp_id=<?=$qna[qa_cp_id]?>" class="it_img_wrap">
			<img src="<?=$qna[qa_cp_img]?>">
		</a>
		<p class="it_name"><?=$qna[qa_cp_subject]?></p>
		<span class="qa_reply_state"><?=$qna[qa_reply_state]?></span>
		<p class="qa_memo"><?=$qna[qa_memo]?></p>
		
	</li>
	<?php
		for($k=0; $k<count($qna["reply"]); $k++){
			$qna_reply = $qna["reply"][$k];
	?>
	<li class="a_wrap qa_parent_<?=$qna_reply[qa_parent]?>" style="display:none;">
		<span class="qa_insert_datetime txt_num"><?=$qna_reply[qa_insert_datetime]?></span>
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
	<p>캠페인문의 내역이 존재하지 않습니다.</p>
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

<script type="text/html" id="campaign_list_script">
<li class="q_wrap" data-qa_id="<%=qa_id%>">
	<a href="campaign.php?cp_id=<%=qa_cp_id%>" class="it_img_wrap">
		<img src="<%=qa_cp_img%>">
	</a>
	<p class="it_name"><%=qa_cp_subject%></p>
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
			campaign_list_load();
		}
	}
});

function campaign_list_load(){
	$(".loading_wait").show();
	if(page==1){
		$(".my_campaign_qna_list").html("");
	}
	$.ajax({
		type     : "get",
		url      : "my_campaign_qna_list.php",
		data     : "json=list&&page="+page,
		dataType : "json",
		cache: false,
		success  : function(data) {
			if(data.count > 0){
				template = _.template($("#campaign_list_script").html());
				var template_html = "";
				for(var i=0; i<data.list.length; i++) {
					template_html +=  template({qa_id: data.list[i].qa_id
						, qa_cp_id: data.list[i].qa_cp_id
						, qa_mb_id: data.list[i].qa_mb_id						
						, qa_memo: data.list[i].qa_memo
						, qa_reply_state: data.list[i].qa_reply_state
						, qa_cp_img: data.list[i].qa_cp_img
						, qa_cp_subject: data.list[i].qa_cp_subject
						, reply: data.list[i].reply});
				}
				$(".my_campaign_qna_list").append(template_html);
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