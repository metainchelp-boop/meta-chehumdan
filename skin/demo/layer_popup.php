<style>
#layer_popup{ border:solid 4px #000;}
.layer_popup_bg { display:none; position:fixed; _position:absolute; top:0; left:0; width:100%; height:100%; z-index:99999; }
.layer_popup_bg .bg { position:absolute; top:0; left:0; width:100%; height:100%; background:#000; opacity:.5; filter:alpha(opacity=50); }
.layer_popup_bg .pop-layer { display:block; }

.pop-layer { display:none; position:absolute; top:50%; left:50%; background-color:#f7f7f7; z-index:10; }	
.pop-layer .pop-container { padding:20px 30px 30px; overflow:hidden; }

.pop-layer .btn-r { text-align:right; }
</style>

<!-- 레이어 팝업 -->
<div class="layer_popup_bg">
	<div class="bg"></div>
	<div id="layer_popup" class="pop-layer">
		<div class="pop-container">
			<div class="pop-conts">

				<div class="btn-r">
					<a href="#" class="cbtn"><img src="<?=$nfor[skin_path]?>/img/close_btn_sns.png"></a>
				</div>

				<div class="pop_wrap"></div>

			</div>
		</div>
	</div>
</div>
<!-- 레이어 팝업 end -->




<?php
include_once $nfor[skin_path]."appdown_layer.php";
?>



<script type="text/javascript">
function layer_popup_open(layer,width){

	$(".pop_wrap").html( $("#"+layer).html() );

	var el = "layer_popup";
	$(".pop-layer").css("width",width);

	var temp = $('#' + el);
	var bg = temp.prev().hasClass('bg');
	if(bg){
		$('.layer_popup_bg').fadeIn();
	}else{
		temp.fadeIn();
	}

	if (temp.outerHeight() < $(document).height() ) temp.css('margin-top', '-'+temp.outerHeight()/2+'px');
	else temp.css('top', '0px');
	if (temp.outerWidth() < $(document).width() ) temp.css('margin-left', '-'+temp.outerWidth()/2+'px');
	else temp.css('left', '0px');

	temp.find('a.cbtn').click(function(e){
		if(bg){
			$('.layer_popup_bg').fadeOut();
		}else{
			temp.fadeOut();
		}
		e.preventDefault();
	});

	$('.layer_popup_bg .bg').click(function(e){
		$('.layer_popup_bg').fadeOut();
		e.preventDefault();
	});

}			
</script>