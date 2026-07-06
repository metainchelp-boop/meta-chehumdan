<style>
.notice2_wrap{float:left; width:630px;  overflow:hidden; position:relative; padding-top:30px; padding-bottom:20px; }
.notice2_wrap .subject{ display:block;width:630px; text-overflow:ellipsis; overflow:hidden; white-space:nowrap; padding-bottom:10px; }
.notice2_wrap li{position: relative}
.notice2_wrap .text{font-size:16px;color:#fff; font-family: 'notokr-demilight';}
.notice2_wrap .memo{display: -webkit-box; overflow: hidden; height: 43px; margin-top: 1px; font-weight: 400; font-size: 13px;l  text-overflow: ellipsis; -webkit-line-clamp: 2; -webkit-box-orient: vertical; color:#7d7db9; line-height:20px; margin-bottom:10px;}
.notice2_wrap .date{ display:block;width:630px; 	font-family: 'montserrat'; font-size:13px; color:#ffffff;}
.bx-wrapper .bx-pager.bx-default-pager a:hover, .bx-wrapper .bx-pager.bx-default-pager a.active { background: #7d7db9!important;}
.bx-controls{position: relative; width:450px; height:15px;}
.bx-wrapper .bx-pager { text-align: center; font-size: .85em; font-family: Arial;  font-weight: bold; color: #666;  margin-left: -0px;   position: absolute;  right: 0px;; bottom: 5px;}

</style>
			<div class="notice2_wrap">
		
				<ul class="bxslider2">
				<?php
					for($i=0; $i<count($return["notice_list"]); $i++){
						$notice = $return["notice_list"][$i];
					?>
					<li>
					<span class="subject"><a href="notice_view.php?no_id=<?=$notice[no_id]?>" class="text">[<?=$notice[no_category]?>] <?=$notice[no_subject]?></a></span>
					<span class="memo"><?=$notice[no_memo]?></span>
					<span class="date"><?=$notice[no_insert_datetime]?></span></li>
					<? } ?>
				</ul>
			</div>



<script type="text/javascript">
<!--
$(document).ready(function(){

	$('.bxslider2').bxSlider({
		auto: true,
		autoControls: false,
		mode: 'horizontal',
		infiniteLoop: true,
		pager: true,
		controls: false,
	});

});
//-->
</script>