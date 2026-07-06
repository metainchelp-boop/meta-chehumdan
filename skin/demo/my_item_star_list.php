<?php
include_once $nfor[skin_path]."mypage_head.php";
?>

<style>
.star_list_wrap { margin:0px; padding:0px; width:100%; box-sizing:border-box; -webkit-box-sizing:border-box; ;  border-top: 1px solid #4f525c; }
.star_list .list_item { margin-bottom:3px; background-color:#fff; border-bottom:solid 1px #e3e5e8;}
.buy_item { position: relative; padding-top:10px; padding-bottom:10px; padding-left:10px; position:relative; }

.buy_item:after { display:block; clear:both; content:''; }
.buy_item .thumb { position:relative; padding-right:9px; }
.buy_item .thumb img { width:60px; height:60px; }
.buy_item .info { display:block; }
.buy_item .info .title{position:absolute; margin-left:75px; top:35px;display: block; margin-top:5px;margin-right:45px; font-weight: 400;  font-size: .9em;  line-height: 1.2;  overflow:hidden; text-overflow:ellipsis; -webkit-line-clamp:1; display:-webkit-box; -webkit-box-orient:vertical;}
.buy_item .info .text{display: block; font-size: 12px; margin:20px;line-height: 18px; color: #6c7580; height:18px;  vertical-align: middle; overflow:hidden; text-overflow:ellipsis; -webkit-line-clamp:1; display:-webkit-box; -webkit-box-orient:vertical; box-sizing:border-box; -webkit-box-sizing:border-box;}
.buy_item b { position:absolute; top:50%; right:15px; width:7px; height:11px; margin-top:-6px; background:url( <?=$nfor[skin_path]?>img/layout.png ) no-repeat; background-position:-0px -400px;background-size: 320px auto; overflow:hidden; display:inline-block; text-indent:-999em; }
.st_insert_datetime{position:absolute; display: block; top:20px; margin-left:75px;font-size: 11px; color: #888; vertical-align: middle}

.buy_item .info .star_score {position:absolute; top:20px; right:25px;  height: 10px; cursor: pointer;}
.star_score .star_off{ display:inline-block; position:relative; width:87px; height:15px; background:url('<?=$nfor[skin_path]?>img/star.png'); background-repeat:no-repeat; background-position: 0px -0px;  vertical-align:middle; background-size:87px;}
.star_score .star_off .star_on {position:absolute; left:0px; top:0px; height:15px; background:url('<?=$nfor[skin_path]?>img/star.png'); background-repeat:no-repeat; background-position:0px -18.5px; background-size:87px; }
.star_score .sp_tcm {display:inline-block; position:relative; width:87px; height:15px; background:url('<?=$nfor[skin_path]?>img/star.png'); background-repeat:no-repeat; background-position: 0px -0px;  vertical-align:middle; background-size:87px;}

.sch_no_data{padding: 0 15px; text-align: center; background: #fff url(<?=$nfor[skin_path]?>img/bg_no_result03.png) no-repeat 50% 40%;  background-size: 64px auto; font-size: 15px; color: #999;}
.sch_no_data p{min-height: 240px; padding: 250px 0px 0px;}
</style>


<div class="star_list_wrap">

	<ul class="star_list">
		<?php
		for($i=0; $i<count($return["list"]); $i++){
			$star = $return["list"][$i];
		?>
		<li class="list_item">

			<div class="buy_item">
				<a href="item.php?it_id=<?=$star[st_it_id]?>" class="thumb">
					<img src="<?=$star[st_it_img]?>">
				</a>
				<a href="my_item_star_view.php?st_id=<?=$star[st_id]?>" class="info">
					<p class="st_insert_datetime"><?=$star[st_insert_datetime]?></p>
					<div class="star_score">
					<span class="star_off sp_tcm">
						<span class="star_on sp_tcm" style="width:<?=$star[st_star_per]?>%"></span>
					</span>
					</div>
					<h3 class="title"><?=$star[st_it_name]?></h3>
					<span class="text"><?=$star[st_memo]?></span>
				</a>
				<b></b>
			</div>	

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
<li class="list_item">

	<div class="buy_item">
		<a href="item.php?it_id=<%=st_it_id%>" class="thumb">
			<img src="<%=st_it_img%>">
		</a>
		<a href="my_item_star_view.php?st_id=<%=st_id%>" class="info">
			<p class="st_insert_datetime"><%=st_insert_datetime%></p>
			<div class="star_score">
			<span class="star_off sp_tcm">
				<span class="star_on sp_tcm" style="width:<%=st_star_per%>%"></span>
			</span>
			</div>
			<h3 class="title"><%=st_it_name%></h3>
			<span class="text"><%=st_memo%></span>
		</a>
		<b></b>
	</div>	

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
		$(".star_list").html("");
	}
	$.ajax({
		type     : "get",
		url      : "my_item_star_list.php",
		data     : "json=list&&page="+page,
		dataType : "json",
		cache: false,
		success  : function(data) {
			if(data.count > 0){
				template = _.template($("#item_list_script").html());
				var template_html = "";
				for(var i=0; i<data.list.length; i++) {
					template_html +=  template({st_id: data.list[i].st_id
						, st_insert_datetime: data.list[i].st_insert_datetime
						, st_star_per: data.list[i].st_star_per						
						, st_it_img: data.list[i].st_it_img						
						, st_it_name: data.list[i].st_it_name
						, st_it_id: data.list[i].st_it_id						
						, st_memo: data.list[i].st_memo});
				}
				$(".star_list").append(template_html);
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