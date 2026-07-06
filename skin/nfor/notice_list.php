<?php
include_once $nfor[skin_path]."head.php";
?>

<div class="notice_list_wrap">

	<ul class="bstyle1_lst">
	<?php
	for($i=0; $i<count($return["list"]); $i++){
		$notice = $return["list"][$i];
	?>
	<li>
	<a href="notice_view.php?no_id=<?=$notice[no_id]?>">
		<p class="wr_subject2"><span class="ca_name">[<?=$notice[no_category]?>]</span><?=$notice[no_subject]?></p>
		<span class="txt_num wr_datetime"><?=$notice[no_insert_datetime]?></span>
		<span class="txt_num wr_hit"><?=$notice[no_hit]?></span>
		<b class="arrow"></b>
	</a>
	</li>
	<?php } ?>
	</ul>
	<? if(!$scroll_load){ ?><div class="page_center"><?=$pagelist?></div><? } ?>

</div>

<?php if($scroll_load){ ?>
<div class="loading_wait">
	<img src="<?=$nfor[skin_path]?>img/ajax-loader.gif">
	<span>목록을 불러오고 있습니다</span>
	<p>잠시만 기다려 주세요</p>
</div>

<script type="text/html" id="item_list_script">
<li>
<a href="notice_view.php?no_id=<%=no_id%>">
	
	<p class="wr_subject2"><span class="ca_name">[<%=no_category%>]</span><%=no_subject%></p>
	<span class="txt_num wr_datetime"><%=no_insert_datetime%></span>
	<span class="txt_num wr_hit"><%=$row[no_hit]%></span>
	<b class="arrow"></b>
</a>
</li>
</script>

<script>
var is_last = 0;
var page = 1;

$(window).scroll(function(){
    var scrolltop = parseInt ( $(window).scrollTop() );
    if( scrolltop >= $(document).height() - $(window).height() - 500 ){
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
include_once $nfor[skin_path]."tail.php";
?>