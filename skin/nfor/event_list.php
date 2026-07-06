<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.event_list_wrap { margin:0px; padding:0px; width:100%; box-sizing:border-box; -webkit-box-sizing:border-box; }
.event_list { width:100%; }
.event_list li { background-color:#fff; margin-bottom:10px; overflow:hidden; position:relative; }
.event_list li a { display:block; }
.event_list img { margin-right:10px; width:100%; }
</style>

<div class="event_list_wrap">
	<ul class="event_list">
	<?php
	for($i=0; $i<count($return["list"]); $i++){
		$event = $return["list"][$i];
	?>
	<li>
		<a href="event_view.php?ev_id=<?=$event[ev_id]?>"><img src="<?=$event[ev_img]?>"></a>
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
</li>
</script>

<script>
var is_last = 0;
var page = 1;

$(window).scroll(function() {
	if ($(window).scrollTop() + 220 >= $(document).height() - $(window).height()) {
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
						, ev_img: data.list[i].ev_img});
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