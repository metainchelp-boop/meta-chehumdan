<?php
include_once $nfor['skin_path']."head.php";
?>

<style>
.customer_list_wrap { padding:10px 15px; }
.customer_list { }
.customer_list li{ position:relative; background-color:#fff;  padding:15px 10px 10px 15px;  margin-bottom:5px;}
.customer_list li .cs_insert_datetime { font-size:11px; color:#888; line-height:18px; }
.customer_list li .cs_subject { max-width:90%; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;  font-size:12px; color:#555; margin:0px 0px 5px 0px; }
.cs_reply_state { font-size:11px; color:#888; line-height:18px;  color:#ff9900;}
.customer_list li b {  position:absolute; top:50%; right:15px; width:7px; height:11px; margin-top:-6px; background:url( <?=$nfor['skin_path']?>img/layout.png ) no-repeat; background-position:-0px -400px;background-size: 320px auto; overflow:hidden; display:inline-block; text-indent:-999em; }
.sch_no_data{padding: 0 15px; text-align: center; background:url(./skin/nfor/img/bg_no_result03.png) no-repeat 50% 40%;  background-size: 64px auto; font-size: 15px; color: #999;}
.sch_no_data p{min-height: 240px; padding: 250px 0px 0px;}
</style>

<?php include_once $nfor['skin_path']."inc_customer.php"; ?>

<?php if(count($return["list"]) > 0){ ?>
<div class="customer_list_wrap">

	<ul class="customer_list">
		<?php
		for($i=0; $i<count($return["list"]); $i++){
			$customer = $return["list"][$i];
		?>
		<li>
			<a href="customer_view.php?cs_id=<?=$customer['cs_id']?>">
				<p class="cs_insert_datetime"><?=$customer['cs_insert_datetime']?></p>
				<p class="cs_subject"><?=$customer['cs_subject']?></p>
				<p class="cs_reply_state"><?=$customer['cs_reply_state_text']?></p>				
				<b></b>
			</a>
		</li>
		<?php }	?>
	</ul>
	<?php if(!$scroll_load){ ?><div class="page_center"><?=$pagelist?></div><?php } ?>

</div>
<?php } else { ?>
<div class="sch_no_data">
	<p>문의내역이 존재하지 않습니다.</p>
</div>
<?php } ?>


<?php if($scroll_load){ ?>
<div class="loading_wait">
	<img src="<?=$nfor['skin_path']?>img/ajax-loader.gif">
	<span>목록을 불러오고 있습니다</span>
	<p>잠시만 기다려 주세요</p>
</div>

<script type="text/html" id="item_list_script">
<li>
	<a href="customer_view.php?cs_id=<%=cs_id%>">
		<p class="cs_insert_datetime"><%=cs_insert_datetime%></p>
		<p class="cs_subject"><%=cs_subject%></p>
		<p class="cs_reply_state"><%=cs_reply_state_text%></p>				
		<b></b>
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
		$(".customer_list").html("");
	}
	$.ajax({
		type     : "get",
		url      : "customer_list.php",
		data     : "json=list&&page="+page,
		dataType : "json",
		cache: false,
		success  : function(data) {
			if(data.count > 0){
				template = _.template($("#item_list_script").html());
				var template_html = "";
				for(var i=0; i<data.list.length; i++) {
					template_html +=  template({cs_id: data.list[i].cs_id
						, cs_insert_datetime: data.list[i].cs_insert_datetime
						, cs_subject: data.list[i].cs_subject						
						, cs_reply_state_text: data.list[i].cs_reply_state_text});
				}
				$(".customer_list").append(template_html);
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
include_once $nfor['skin_path']."tail.php";
?>