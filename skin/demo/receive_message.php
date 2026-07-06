<?php // 받은메시지
include_once $nfor[skin_path]."mypage_head.php";
?>

<style>
.receive_message{width:100%;}
.receive_message ul{overflow:hidden;}
.receive_message ul li{float:left;width:22%; min-height:159px; border:solid 1px #efefef; margin:10px; border-radius:10px;}
.receive_message ul li:hover{color:#000; border:solid 1px #ff0000;}
.receive_message .inner{position:relative; min-height:155px; margin:20px; padding-bottom:30px;  }
.receive_message .inner a{font-size:15px; color:#000; line-height:20px;}
.receive_message .inner .date{position:absolute; bottom:0px;right:0px; font-weight:normal; font-size:12px; color:#888; font-family: 'montserrat'; display:block;}
.receive_message .inner .mess_icon{width:24px; height:24px; position: absolute; left:0; bottom:0px; background: url('<?=$nfor[skin_path]?>img/mess_icon.png') no-repeat}
</style>


<?php if(count($return["list"]) > 0){ ?>

	<div class="receive_message nfor_message_list_wrap">
		<ul>
			<?php
			for($i=0; $i<count($return["list"]); $i++){
				$message = $return["list"][$i];
			?>
			<li>
				<div class="inner">
					<a href="<?=$PHP_SELF?>?mode=read&mg_id=<?=$message[mg_id]?>"><?=$message[mg_msg]?></a>
					<b class="mess_icon"></b>
					<b class="date"><?=$message[mg_datetime]?></b>
				</div>
			</li>
			<?php } ?>
		</ul>
	</div>

	<div class="page_center"><?=$pagelist?></div>

<?php } else { ?>

	<div class="sch_no_data">
		<p>받은 메시지가 없습니다.</p>
	</div>

<?php } ?>

<div class="loading_wait">
	<img src="<?=$nfor[skin_path]?>img/ajax-loader.gif">
	<span>상품목록을 불러오고 있습니다</span>
	<p>잠시만 기다려 주세요</p>
</div>

<script type="text/html" id="item_list_script">
<li>
	<div class="inner">
		<a href="<?=$PHP_SELF?>?mode=read&mg_id=<%=mg_id%>"><%=mg_msg%></a>
		<b class="mess_icon"></b>
		<b class="date"><%=mg_datetime%></b>
	</div>
</li>
</script>

<script>
var is_last = 0;
var page = 1;

<?php if($scroll_load){ ?>
$(window).scroll(function() {
	if ($(window).scrollTop() == $(document).height() - $(window).height()) {
		if(is_last==0){
			++page;
			item_list_load();
		}
	}
});
<?php } ?>

function item_list_load(){
	$(".loading_wait").show();
	if(page==1){
		$(".nfor_message_list_wrap ul").html("");
	}
	$.ajax({
		type     : "get",
		url      : "<?=basename($PHP_SELF)?>",
		data     : "json=list&page="+page,
		dataType : "json",
		cache: false,
		success  : function(data) {
			if(data.count > 0){
				template = _.template($("#item_list_script").html());
				var template_html = "";
				for(var i=0; i<data.list.length; i++) {
					template_html +=  template({mg_id: data.list[i].mg_id
						, mg_msg: data.list[i].mg_msg
						, mg_datetime: data.list[i].mg_datetime});
				}
				$(".nfor_message_list_wrap ul").append(template_html);
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

<?php
include_once $nfor[skin_path]."mypage_tail.php";
?>