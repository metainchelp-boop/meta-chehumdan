<?php
include_once $nfor[skin_path]."mypage_head.php";
?>

<style>
.refound_list_wrap { padding:5px; }
.refound_list { background-color:#fff; }
.refound_list .list_item { overflow:hidden; border-bottom:solid 2px #fafafa; }
.refound_list .list_item a{ margin:0; border-top-style:dotted; }

.buy_item { padding-top:10px; padding-bottom:10px; padding-left:10px; }
.buy_item:after {display: block;clear: both;content: '';}
.buy_item .thumb { float:left; position:relative; padding-right:9px; }
.buy_item .thumb img { width:86px; height:86px; }
.buy_item .info { margin-left:95px; }
.buy_item .info .title{ display:block; margin-top:12px; margin-bottom:20px; font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal; font-size:.9em; line-height:1; }
.buy_item .sts { display: inline-block; margin-bottom: 10px; font-size: .75em;color: #f27935;}

</style>
<? if(count($return["list"]) > 0){ ?>
<div class="refound_list_wrap">

	<ul class="refound_list">
		<?php
		for($i=0; $i<count($return["list"]); $i++){
			$order = $return["list"][$i];
		?>
		<li class="list_item">
			<a href="order_view.php?od_id=<?=$order[ct_od_id]?>&it_id=<?=$order[ct_it_id]?>">
				<div class="buy_item">
					<div class="thumb">
						<img src="<?=$order[ct_it_img]?>">
					</div>
					<div class="info">
						<h3 class="title"><?=$order[ct_it_name]?></h3>
						<span class="sts"><?=$order[ct_order_state]?></span>
					</div>
				</div>	
			</a>
		</li>
		<?php
		}
		?>
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
<li class="list_item">
	<a href="order_view.php?od_id=<%=ct_od_id%>&it_id=<%=ct_it_id%>">
		<div class="buy_item">
				<div class="thumb">
					<img src="<%=ct_it_img%>">
				</div>
				<div class="info">
					<h3 class="title"><%=ct_it_name%></h3>
					<span class="sts"><%=ct_order_state%></span>
				</div>
		</div>	
	</a>
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
		$(".refound_list").html("");
	}
	$.ajax({
		type     : "get",
		url      : "refund_list.php",
		data     : "json=list&page="+page,
		dataType : "json",
		cache: false,
		success  : function(data) {
			if(data.count > 0){
				template = _.template($("#item_list_script").html());
				var template_html = "";
				for(var i=0; i<data.list.length; i++) {
					template_html +=  template({ct_it_name: data.list[i].ct_it_name
						, ct_od_id: data.list[i].ct_od_id
						, ct_it_id: data.list[i].ct_it_id
						, ct_it_img: data.list[i].ct_it_img
						, ct_order_state: data.list[i].ct_order_state});
				}
				$(".refound_list").append(template_html);
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
<? } else{ ?>
<div class="sch_no_data">
	<p>취소/교환/환불내역이 존재하지 않습니다.</p>
</div>
<? } ?>
<?php
include_once $nfor[skin_path]."mypage_tail.php";
?>