<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.event_list_top_wrap { background-color:#efefef; height:200px; text-align:center; }
.event_list_wrap { margin:0px auto; background-color:#fff; padding:25px 20px; box-sizing:border-box; -webkit-box-sizing:border-box; min-height:600px; width:1220px; }
.tabb { font-size:22px; font-family:"NSKM"; display:inline-block; height:55px; }
.tabb span{ font-size:12px; color:#ff0000; font-family:verdana; font-weight:bold; line-height:55px; }

.event_list { overflow:hidden; width:100%; font-size:16px; }
.event_list a { color:#666; font-size:14px;}
.event_list a:hover { color:black; text-decoration:underline; }
.event_list li { float:left; overflow:hidden; position:relative; width:380px; padding:0px; margin-left:10px; margin-bottom:20px; border-bottom:solid 1px #efefef; } 
.event_list li:nth-child(3n+1) { margin-left:0px; } 
.event_list img { margin-right:30px; width:100%; }
.event_list div { padding:0px 5px 15px; } 
.event_list h2 { margin:5px 0 4px; font-size:16px; line-height:28px; color:#666;  font-weight:normal;}
.event_list h2 span { display:inline-block; vertical-align:3px; color:#fff; padding:5px 5px 3px 5px; height:12px; line-height:12px; font-size:11px; text-align:center; }
.event_list h2 span.ing { background-color:#ff0000; }
.event_list h2 span.end { background-color:#666; }

.event_list .ev_description { margin:9px 0 11px; color:#8a8a8a; line-height:16px; font-size:12px; }
.event_list .ev_date { display:block; padding:0px 0px 0px; color:#888; font-size:11px; }
.event_list .ev_hit { position:absolute; bottom:15px; right:15px; color:#a2a2a2; font-size:11px; padding-right:10px; }

.event_list li.nodata { width:100%; height:350px; text-align:center; font-size:12px; line-height:350px;color:#999; border:none; }
</style>


<div class="event_list_wrap"> 

	<a href="event_list.php?period=ing" class="tabb">이벤트 </a>
	
	<ul class="event_list">
	<?php
	for($i=0; $i<count($return["list"]); $i++){
		$event = $return["list"][$i];
	?>
    <li>
        <a href="event_view.php?ev_id=<?=$event[ev_id]?>"><img src="<?=$event[ev_img]?>"></a>
		<div>
			<h2>
			  <a href="event_view.php?ev_id=<?=$event[ev_id]?>"><?=$event[ev_subject]?></a> <span class="<?=$event[ev_state]?>"><?=$event[ev_state_text]?></span>
			</h2>
			<p class="ev_description"><?=$event[ev_description]?></p>
			<span class="ev_date">참여기간 : <?=$event[ev_sdatetime]?> - <?=$event[ev_edatetime]?></span>
			<em class="ev_hit">조회 <?=$event[ev_hit]?></em>
		</div>
    </li>
	<? 
	} 
	if(!$i){
	?>
	<li class="nodata">
		등록된 이벤트가 없습니다.
	</li>
	<? } ?>
	
	</ul>

	<? if(!$scroll_load){ ?><div class="page_center"><?=$pagelist?></div><? } ?>

</div>


<? if($scroll_load){ ?>
<div class="loading_wait">
	<img src="<?=$nfor[skin_path]?>img/ajax-loader.gif">
	<span>목록을 불러오고 있습니다</span>
	<p>잠시만 기다려 주세요</p>
</div>

<script type="text/html" id="item_list_script">
<li>
	<a href="event_view.php?ev_id=<%=ev_id%>"><img src="<%=ev_img%>"></a>
	<div>
		<h2>
		  <a href="event_view.php?ev_id=<%=ev_id%>"><%=ev_subject%></a> <span class="<%=ev_state%>"><%=ev_state_text%></span>
		</h2>
		<p class="ev_description"><%=ev_description%></p>
		<span class="ev_date">참여기간 : <%=ev_sdatetime%> - <%=ev_edatetime%></span>
		<em class="ev_hit">조회 <%=ev_hit%></em>
	</div>
</li>
</script>

<script>
var is_last = 0;
var page = 1;

$(window).scroll(function() {
	if ($(window).scrollTop() == $(document).height() - $(window).height()) {
		if(is_last==0){
			console.log(++page);
			item_list_load();
		}
	}
});

function item_list_load(){
	$(".loading_wait").show();
	if(page==1){
		$(".event_list").html("");
	}
	$.ajax({
		type     : "get",
		url      : "event_list.php",
		data     : "json=list&&page="+page,
		dataType : "json",
		cache: false,
		success  : function(data) {
			if(data.count > 0){
				template = _.template($("#item_list_script").html());
				var template_html = "";
				for(var i=0; i<data.list.length; i++) {
					template_html +=  template({ev_id: data.list[i].ev_id
						, ev_img: data.list[i].ev_img
						, ev_subject: data.list[i].ev_subject
						, ev_state: data.list[i].ev_state
						, ev_state_text: data.list[i].ev_state_text
						, ev_description: data.list[i].ev_description
						, ev_sdatetime: data.list[i].ev_sdatetime
						, ev_edatetime: data.list[i].ev_edatetime
						, ev_hit: data.list[i].ev_hit});
				}
				$(".event_list").append(template_html);
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
include_once $nfor[skin_path]."tail.php";
?>