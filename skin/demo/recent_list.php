<?php
include_once $nfor[skin_path]."mypage_head.php";
?>
<style>
.type_1 .border { height: 350px!important;}
.type_1 .border { position: relative; background-color: #FFF; margin: -1px -1px 0 0; padding: 20px!important; border: 1px solid #e3e5e8;}
.type_1 .border .thumb{height:180.75px;}
.type_1 .border .check { width:220px; background:url('<?=$nfor[skin_path]?>img/recent_bg.png');left:0px; top:0px;height:390px; position: absolute; z-index:20; display:none; }
.type_1 .border .check .on{position: absolute; top:45px; left:50px; width:140px; height:127px; background:url('<?=$nfor[skin_path]?>img/check_on2.png');}
.type_1 .border .check .off{position: absolute; top:45px; left:50px; width:140px; height:127px; background:url('<?=$nfor[skin_path]?>img/check_off2.png');}
.recent_btn_set{margin:10px 0px;position: absolute; top:45px; right:35px; }
.recent_btn_set a{font-size:12px; color:#666; cursor:pointer; }
.recent_allsel_btn{border:solid 1px #DCDCDC;display:inline; padding:5px 10px; }
.recent_allsel_btn.on{border:solid 1px #f27935; color:#f27935; }

.recent_del_btn{border:solid 1px #DCDCDC;display:inline; padding:5px 10px;}
.recent_del_btn span{color:#f27935; font-weight:normal}
.recent_edit, .recent_cencel{border:solid 1px #DCDCDC; display:inline; padding:5px 10px; cursor:pointer; }

.type_1 .border .check .chk { display:none; }
.recent_allbtn { display:none; }
</style>
<? if(count($return["list"]) > 0){ ?>
<form name="recent_list_form" id="recent_list_form" method="post">
<input type="hidden" name="mode" value="select_delete">

<div class="recent_btn_set">
	<a class="recent_edit">편집</a>
	<a class="recent_allsel_btn recent_allbtn">전체선택</a>
	<a class="recent_del_btn recent_allbtn"><span>0</span>개 삭제</a>
	<a class="recent_cencel recent_allbtn">취소</a>
</div>

<div class="item_list_wrap">
	<?php
	include_once $nfor[skin_path]."inc_item_list.php";
	?>
</div>

</form>

<script>

$(document).on("click",".recent_edit",function(){
	$(".recent_edit").hide();
	$(".recent_allbtn").show();
	$(".type_1 .border .check").show();
});

$(document).on("click",".recent_del_btn",function(){
	// 선택삭제
	if(!$(".chk:checked").length){
		alert("삭제할 상품을 선택해주세요");
	} else{
		if(confirm("선택하신 상품을 삭제하시겠습니까?")){			
			$.ajax({
				type: "post",
				data : $("#recent_list_form").serialize(),
				url: "recent_list.php",
				success: function(response){
					console.log(response);
					var json = $.parseJSON(response); 
					if(json["result"]=="ok"){
						document.location.reload();
					}
				}
			});
		}
	}
});

$(document).on("click",".recent_allsel_btn",function(){
	if($(this).hasClass("on")){
		$('input:checkbox[class="chk"]').prop("checked", false);
		$(".type_1 .border .check .on").addClass("off").removeClass("on");	
	} else{
		$('input:checkbox[class="chk"]').prop("checked", true);
		$(".type_1 .border .check .off").addClass("on").removeClass("off");
	}
	checkbox_state();
});

$(document).on("click",".recent_cencel",function(){
	$(".recent_edit").show();
	$(".recent_allbtn").hide();
	$(".type_1 .border .check").hide();
	$(".type_1 .border .check .on").addClass("off").removeClass("on");	
	$('input:checkbox[class="chk"]').prop("checked", false);
	checkbox_state();
});

$(document).on("click",".type_1 .border .check .off",function(){
	$(this).addClass("on").removeClass("off");
	$(this).next('input:checkbox[class="chk"]').prop("checked", true);
	checkbox_state();
});

$(document).on("click",".type_1 .border .check .on",function(){
	$(this).addClass("off").removeClass("on");
	$(this).next('input:checkbox[class="chk"]').prop("checked", false);
	checkbox_state();
});

function checkbox_state(){
	if($(".type_1 .border .check").length == $(".type_1 .border .check .on").length){
		$(".recent_allsel_btn").addClass("on").html("전체취소");
	} else{
		$(".recent_allsel_btn").removeClass("on").html("전체선택");
	}
	$(".recent_del_btn span").html($(".type_1 .border .check .on").length);
}
</script>
<? } else{ ?>
<div class="sch_no_data">
	<p>최근본상품이 존재하지 않습니다.</p>
</div>
<? } ?>
<?php
include_once $nfor[skin_path]."mypage_tail.php";
?>