<?php
include_once $nfor[skin_path]."cus_head.php";
?>


<div class="faq_zone">
	<div class="inner">
		<form id="faq_form" method="get" autocomplete="off">
		<?=admin_text($write,"faq_keyword","faq_keyword","placeholder=\"검색어를 입력하세요\"")?>
		<button type="submit" class="btn_search">검색</button>
		<div class="faq_keyword_wrap">
			<strong class="top5">인기검색어<span class="bu"></span></strong>
			<ul class="fa_keyword">
				<? foreach($admin[fa_keyword] as $key=>$value){ ?>	
				<li><span class="bar">|</span><a data-fa_keyword="<?=$key?>" <? if(!$scroll_load){ ?>href="faq.php?faq_keyword=<?=urlencode($key)?>"<? } ?>><?=$value?></a></li>
				<? } ?>
			</ul>
		</div>
		</form>

	</div>
</div>
<div class="faq">
	<?
	$i = 1;
	foreach($admin[fa_category] as $key=>$value){
	?>
		<a data-fa_category="<?=$key?>" <? if(!$scroll_load){ ?>href="faq.php?fa_category=<?=$key?>"<? } ?> class="faq_cate <?=$key==$fa_category?"on":""?>"><?=$value?></a>
	<? 
		$i++;
	} 
	?>
</div>

	<ul class="faq_list">
	<?php
	for($i=0; $i<count($return["list"]); $i++){
		$faq = $return["list"][$i];
	?>
	<li class="faq_q">
		<a href="javascript:faq_show('.faq_tr','<?=$faq[fa_id]?>');"><span class="txt_num point_color1">Q.</span> &nbsp;&nbsp;<?=$faq[fa_subject]?> </span></a>
	</li>
	<li class="faq_tr faq_tr_<?=$faq[fa_id]?>">
		<span class="txt_num point_color3">A.</span>&nbsp;&nbsp; <?=$faq[fa_memo]?>
	</li>
	<?php } ?>
	</ul>

	<? if(!$scroll_load){ ?><div class="page_center"><?=$pagelist?></div><? } ?>
	
</div>

<script>
$(document).on("keyup", "#faq_keyword", function(){
	faq_keyword = $("#faq_keyword").val();
});
<? if($scroll_load){ ?>
$(document).on("click",".faq_menu li a",function(){
	$(".faq_menu li a").removeClass("on");
	$(this).addClass("on");
	
	fa_category = $(this).data("fa_category");
	faq_keyword = "";
	$("#faq_keyword").val(faq_keyword);
	
	page = 1;
	is_last = 0;

	item_list_load();
});
<? } ?>
$(document).on("click",".btn_search",function(){
	if($("#faq_keyword").val()==""){
		alert("검색어를 입력해주세요");
		$("#faq_keyword").focus();
	} else{
		<?php if($scroll_load){ ?>
		faq_keyword = $("#faq_keyword").val();
		page = 1;
		is_last = 0;

		item_list_load();
		<?php } else{ ?>
		$("#faq_form").submit();
		<?php } ?>
	}
	event.preventDefault();
});
</script>

<? if($scroll_load){ ?>
<div class="loading_wait">
	<img src="<?=$nfor[skin_path]?>img/ajax-loader.gif">
	<span>목록을 불러오고 있습니다</span>
	<p>잠시만 기다려 주세요</p>
</div>

<script type="text/html" id="item_list_script">
<li class="faq_q">
	<a href="javascript:faq_show('.faq_tr','<%=fa_id%>');"><span class="txt_num point_color1">Q.</span> &nbsp;&nbsp;<%=fa_subject%> </span></a>
</li>
<li class="faq_tr faq_tr_<%=fa_id%>">
	<span class="txt_num point_color3">A.</span> &nbsp;&nbsp;<%=fa_memo%>
</li>
</script>

<script>
var is_last = 0;
var page = 1;
var fa_category = "<?=$fa_category?>";
var faq_keyword = "<?=$faq_keyword?>";

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
		$(".faq_list").html("");
	}
	$.ajax({
		type     : "get",
		url      : "faq.php",
		data     : "json=list&page="+page+"&faq_keyword="+faq_keyword+"&fa_category="+fa_category,
		dataType : "json",
		cache: false,
		success  : function(data) {
			if(data.count > 0){
				template = _.template($("#item_list_script").html());
				var template_html = "";
				for(var i=0; i<data.list.length; i++) {
					template_html +=  template({fa_id: data.list[i].fa_id
						, fa_subject: data.list[i].fa_subject
						, fa_category: data.list[i].fa_category	
						, fa_memo: data.list[i].fa_memo});
				}
				$(".faq_list").append(template_html);
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
include_once $nfor[skin_path]."cus_tail.php";
?>