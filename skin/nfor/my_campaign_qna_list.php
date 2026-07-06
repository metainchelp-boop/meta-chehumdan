<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.my_campaign_qna_list_wrap { margin:0px; padding:0px; }
.my_campaign_qna_list { background-color:#fff; }
.my_campaign_qna_list li { border-bottom:solid 1px #f1f1f1;cursor:pointer;}

.q_wrap { position:relative; min-height:92px; padding:5px 50px 5px 105px; border-bottom:solid 1px #cccdce; color:#363636; }
.q_wrap .it_img_wrap { position:absolute; top:0; left:0; padding:8px; }
.q_wrap .it_img_wrap img { width:86px; height:86px; }
.q_wrap .it_name { font-size:.85em; margin-top:8px; letter-spacing:-.03em; text-overflow:ellipsis; white-space:nowrap; overflow:hidden; }
.q_wrap .qa_memo { overflow:hidden; font-size:.7em; overflow:hidden; text-overflow:ellipsis;-webkit-line-clamp:2; display:-webkit-box; -webkit-box-orient:vertical; letter-spacing:-.03em; }
.q_wrap .qa_reply_state { display:block; font-size:.75em; font-weight:bold; color:#e83862; margin:5px 0px; }
.q_wrap .open_ico { display:block; position:absolute; top:50%; right:10px; width:20px; height:12px; margin-top:-6px; overflow:hidden; background:url(<?=$nfor[skin_path]?>img/layout_in.png) 0 0 no-repeat; background-position:-45px -71px; background-size:400px auto; }
.q_wrap .open_ico_off { display:block; position:absolute; top:50%; right:10px; width:20px; height:12px; margin-top:-6px; overflow:hidden; background:url(<?=$nfor[skin_path]?>img/layout_in.png) 0 0 no-repeat; background-position:-45px -71px; background-size:400px auto; webkit-transform:rotate(-180deg); }

.a_wrap { position:relative; min-height:60px; padding:15px; border-bottom:solid 1px #cccdce; color:#363636; }
.a_wrap .qa_insert_datetime { position:absolute; right:15px; top:15px; font-size:11px; color:#999; }
.a_wrap .qa_mb_id { display:block; font-size:.8em; font-weight:bold; color:#e83862; }
.a_wrap .qa_memo { margin-top:20px; line-height:1.6; font-size:.8em; letter-spacing:-.03em; color:#6c7580; }

.sch_no_data{padding: 0 15px; text-align: center; background: #fff url(<?=$nfor[skin_path]?>img/bg_no_result03.png) no-repeat 50% 40%;  background-size: 64px auto; font-size: 15px; color: #999;}
.sch_no_data p{min-height: 240px; padding: 250px 0px 0px;}
</style>

<? if(count($return["list"]) > 0){ ?>
<div class="my_campaign_qna_list_wrap">

	<ul class="my_campaign_qna_list">
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

$(window).scroll(function(){
    var scrolltop = parseInt ( $(window).scrollTop() );
    if( scrolltop >= $(document).height() - $(window).height() - 500 ){
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
<? } else { ?>
<div class="sch_no_data">
	<p>캠페인문의 내역이 존재하지 않습니다.</p>
</div>
<? } ?>
<?php
include_once $nfor[skin_path]."tail.php";
?>