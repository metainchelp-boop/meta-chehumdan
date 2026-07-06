<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.event_view_wrap{ box-sizing:border-box; -webkit-box-sizing:border-box; min-height:1000px; width:1100px; margin:0px auto;}
.event_view { padding:0; font-size:12px; min-height:450px; border-top:1px solid #4f525c; }
.event_view .ev_memo { min-height:450px; border-bottom:solid 1px #DCDCDC;  }
.event_view .ev_memo img{width:100%;}
.event_view .ev_subject { background:#f7f7f7; padding:20px; border-bottom:1px solid #dbdbdb; text-align:center; position:relative; color:#000; font-weight:600; font-size:16px; font-family:'NanumGothicBold' ,Sans-serif; -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; font-smoothing: antialiased; font-weight:normal;}
.event_view .event_info { overflow:hidden; padding:8px 0 7px 0; border-bottom:1px solid #dbdbdb; position:relative; padding-left:10px; }
.event_view .event_info .txt { float:left; padding-left:20px; margin-right:25px; overflow:hidden; color:#333; line-height:1.6; letter-spacing:0px; position:relative; }
.event_view .event_info .txt .opt { color:#999; float:left; line-height:1.6; margin-right:8px; }
.event_view .event_info .txt:before { content:''; width:2px; height:10px; background:#ddd; position:absolute; left:0; top:3px; }
.event_view .event_info .txt:first-of-type { padding-left:10px; }
.event_view .event_info .txt:first-of-type:before { display:none; }
.event_view .num { font-size:11px; font-family:verdana; color:#8c8f9a; letter-spacing:-1px; padding:0px 5px; }
</style>

<div class="event_view_wrap">

	<div class="event_view">

		<div class="ev_subject"><?=$event[ev_subject]?></div>
		<div class="ev_memo">
			<?=$event[ev_memo]?>
		</div>

		<div class="event_info">
			<span class="txt">
				<span class="opt">참여기간 :</span>
				<span class="num"><?=$event[ev_sdatetime]?> - <?=$event[ev_edatetime]?></span>
			</span>
				<span class="txt">
				<span class="opt">조회수:</span>
				<span class="num"><?=$event[ev_hit]?></span>
			</span>
		</div>


	</div>
</div>


<div class="bottom_btn">
	<span class="btn_pack"><a class="btn_lg black" href="javascript:history.back()">목록으로</a></span>
</div>

<?php
include_once $nfor[skin_path]."tail.php";
?>