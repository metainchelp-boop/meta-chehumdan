<?php
include_once $nfor[skin_path]."mypage_head.php";
?>

<style>
/*주문order_list*/
.order_list_top_wrap { }
.order_list_tab  {overflow:hidden;background-color:#fafafa;  border-left:1px solid #dadddf; }
.order_list_tab li {margin:0px 0px 0px -2px; background-color:#fafafa; float:left; width:33.3333%;  border-right:solid 1px #dadddf; border-left:solid 1px #dadddf; border-bottom:1px solid #dadddf; border-top:solid 1px #dadddf;  box-sizing:border-box; -webkit-box-sizing:border-box;}
.order_list_tab li a{display:block; height:45px; line-height:45px;  text-align:center; font-size:14px; letter-spacing:-1px; text-decoration:none; color:#999999; }
.order_list_tab li.on {background-color:#FFFFFf; border-bottom:1px solid #fff;}
.order_list_tab li.on a {color:#333; font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;}
</style>

<div class="order_list_top_wrap">
	<ul class="order_list_tab">
		<li<?=!$it_type?" class='on'":""?>><a href="order_list.php">전체</a></li>
		<li<?=$it_type=="1"?" class='on'":""?>><a href="order_list.php?it_type=1">배송상품</a></li>
		<li<?=$it_type=="2"?" class='on'":""?>><a href="order_list.php?it_type=2">티켓상품</a></li>
	</ul>
</div>
<? if(count($return["list"]) > 0){ ?>
<div class="order_list_wrap">

	<div class="order_list">
	<?php
	for($y=0; $y<count($return["list"]); $y++){
		$order = $return["list"][$y];
	?>
		<div class="order_info_top_wrap">
			<span class="od_datetime_wrap">
			주문일 : <b class="od_datetime"><?=$order[ct_datetime]?></b>
			</span>
			<a href="order_view.php?od_id=<?=$order[ct_od_id]?>" class="order_detail">주문상세보기</a>
		</div>
		<?php
		$print = $order["list"];
		include $nfor[skin_path]."inc_order_list.php";
		?>
	<? } ?>
	</div>
	<? if(!$scroll_load){ ?><div class="page_center"><?=$pagelist?></div><? } ?>

</div>

<script>
$(document).on("click", ".ticket_send_btn", function(){
	var od_id = $(this).data("od_id");
	var it_id = $(this).data("it_id");
	$.ajax({
		type: "post",
		url: "order_list.php",
		data: {
			"mode":"ticket_send",
			"od_id":od_id,
			"it_id":it_id
		},
		cache: false,
		async: false,
		success: function(response){
			var json = $.parseJSON(response);
			if(json["result"]=="ok"){
				alert(json["msg"]);
			} else{
				alert(json["msg"]);
			}
		}
	});
});
</script>

<? if($scroll_load){ ?>
<div class="loading_wait">
	<img src="<?=$nfor[skin_path]?>img/ajax-loader.gif">
	<span>목록을 불러오고 있습니다</span>
	<p>잠시만 기다려 주세요</p>
</div>

<script type="text/html" id="item_list_script">
<div class="order_info_top_wrap">
	<span class="od_datetime_wrap">
	주문일 : <b class="od_datetime"><%=ct_datetime%></b>
	</span>
	<a href="order_view.php?od_id=<%=ct_od_id%>" class="order_detail">주문상세보기</a>
</div>
<%
_(list).each(function(data){
%>
<div class="order_info">

	<a href="item.php?it_id=<%=data.ct_it_id%>" class="go_to_item">
		<div class="thumb">
			<img src="<%=data.ct_it_img%>" width="100" height="100" >
		</div>
		<div class="info">
			<h3 class="title"><%=data.ct_it_name%></h3>
							
			<div class="del_num_area">
				<%
				if(data.ct_dy_num){
				%>
				<span>송장번호 | </span>
				<b><%=data.ct_dy_num%></b>
				<% } %>
				
				<%
				if(data.ct_expiry_date){
				%>
				<span>유효기간 | </span>
				<b><%=data.ct_expiry_date%></b>
				<% } %>

				<p><%=data.ct_order_state%></p>
			</div>
		</div>
	</a>

	<ul class="buy_list">
	<%
	_(data.option).each(function(opt){
	%>
		<li class="<%=opt.ct_cancel%>">
			<%=opt.ct_value%>
			<div class="count">구매 <span><%=opt.ct_opt_cnt%></span>개<% if(opt.ct_tk_used>0){ %> / 사용 <span><%=opt.ct_tk_used%></span>개<% } %></div>
			<% if(opt.ct_ticket_number){ %><span class="tiketnum"><%=opt.ct_ticket_number%></span><% } %>
		</li>
	<%
	});
	%>
	</ul>
	
	<div class="order_btn_wrap">
	<%
	_(data.button).each(function(btn){
	%>
		<div><a href="<%=btn.href%>" <%=btn.etc%>><%=btn.text%></a></div>
	<%
	});
	%>
	</div>
	
</div>
<%
});
%>
</script>

<script>
var it_type = "<?=$it_type?>";
var is_last = 0;
var page = 1;

$(window).scroll(function() {
	if($(window).scrollTop() == $(document).height() - $(window).height()){
		if(is_last==0){
			++page;
			item_list_load();
		}
	}
});

function item_list_load(){
	$(".loading_wait").show();
	if(page==1){
		$(".order_list").html("");
	}
	$.ajax({
		type     : "get",
		url      : "order_list.php",
		data     : "json=list&&page="+page+"&it_type="+it_type,
		dataType : "json",
		cache: false,
		success  : function(data) {
			console.log(data);
			if(data.count > 0){
				template = _.template($("#item_list_script").html());
				var template_html = "";
				for(var i=0; i<data.list.length; i++) {
					template_html +=  template({ct_od_id: data.list[i].ct_od_id
						, ct_datetime: data.list[i].ct_datetime
						, list: data.list[i].list});
				}
				$(".order_list").append(template_html);
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
	<p>구매내역이 존재하지 않습니다.</p>
</div>
<? } ?>
<?php
include_once $nfor[skin_path]."mypage_tail.php";
?>