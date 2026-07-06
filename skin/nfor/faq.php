<?php
include_once $nfor[skin_path]."head.php";
?>

<div class="customer_main_wrap">

	<div class="faq_group_top">
		<div id="faq_search" class="serach_wrap">
			<form id="faq_form" method="get" autocomplete="off">
				<?=admin_text($write,"faq_keyword","search_input","placeholder=\"검색어를 입력하세요\"")?>
				<button type="submit" class="search_btn" style="border:none;">검색</button>
			</form>
		</div>
		<ul class="faq_list_wrap">
			<? foreach($admin[fa_category] as $key=>$value){ ?>	
				<li data-fa_category="<?=$key?>" <?=$key==$fa_category?"class='on'":""?>><a <? if(!$scroll_load){ ?>href="faq.php?fa_category=<?=$key?>"<? } ?>><?=$value?></a></li>
			<? } ?>
		</ul>
	</div>

	<ul class="qna_list faq_list">
		<?php
		for($i=0; $i<count($return["list"]); $i++){
			$faq = $return["list"][$i];
		?>
		<li class="faq_q">
			<b class="txt_num point-color1">Q</b>
			<a href="javascript:faq_show('.faq_a','<?=$faq[fa_id]?>');" class="arrow_faq"> <?=$faq[fa_subject]?></a>
		</li>
		<li class="faq_a faq_a_<?=$faq[fa_id]?>">
			<?=$faq[fa_memo]?>
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
$(document).on("click",".faq_list_wrap li",function(){
	$(".faq_list_wrap li").removeClass("on");
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
	<b class="txt_num point-color1">Q</b>
	<a href="javascript:faq_show('.faq_a','<%=fa_id%>');" class="arrow_faq"><%=fa_subject%></a>
</li>
<li class="faq_a faq_a_<%=fa_id%>">
	<%=fa_memo%>
</li>
</script>

<script>
var is_last = 0;
var page = 1;
var fa_category = "<?=$fa_category?>";
var faq_keyword = "<?=$faq_keyword?>";

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
include_once $nfor[skin_path]."tail.php";
?>