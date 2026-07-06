<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.wrap_qna_view { margin:0px; padding:10px 10px; width:100%; box-sizing:border-box; -webkit-box-sizing:border-box;  }

.qna_view_title { background-color:#fff; border-top:solid 1px #ddd; border-left:solid 1px #ddd; border-right:solid 1px #ddd; }
.qna_view_title li{ position:relative; background-color:#fff; border-bottom:solid 1px #ddd; padding:10px;  }
.qna_view_title li .qa_insert_datetime { font-size:11px; color:#999; }
.qna_view_title li .qa_cp_subject { max-width:100%; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; letter-spacing:-1px; font-size:13px; color:#333; }

.qna_view_qna { background-color:#fff; border-top:solid 1px #ddd; border-left:solid 1px #ddd; border-right:solid 1px #ddd; margin:10px 0px; }
.qna_view_qna li{ position:relative; background-color:#fff; border-bottom:solid 1px #ddd; padding:10px; font-size:15px; }
.qna_view_qna li .qa_memo { padding-top:4px; padding-left:25px; font-size:13px; color:#333; min-height:45px;}
.qna_view_qna li.qna_a { background-color:#f7f7f7; font-size:15px; }
.qna_view_qna li .qa_memo_reply { margin-top:3px; margin-left:25px; font-size:13px; color:#333; }
.qna_view_qna li .qa_reply_datetime { font-size:11px; color:#888; }
.qna_view_qna li .ico_q  { width:22px; height:22px; line-height:22px; display:inline-block; position:absolute; left:10px; top:10px; background:#ec3940; border-radius:11px; text-align:center; color:#fff; font-family:Helvetica,'Apple SD Gothic Neo'; font-size:15px; letter-spacing:-1px; }
.qna_view_qna li .ico_a  { width:22px; height:22px; line-height:22px; display:inline-block; position:absolute; left:10px; top:10px; background:#bdc2c8; border-radius:11px; text-align:center; color:#fff; font-family:Helvetica,'Apple SD Gothic Neo'; font-size:15px; letter-spacing:-1px; }
</style>


<div class="wrap_qna_view">

	<ul class="qna_view_title">
		<li>
			<p class="qa_insert_datetime"><?=date("Y.m.d. H:i",strtotime($qna[qa_insert_datetime]))?></p>
			<p class="qa_cp_subject"><a href="campaign.php?cp_id=<?=$qna[qa_cp_id]?>"><?=$qna[qa_cp_subject]?></a></p>
		</li>
	</ul>

	<ul class="qna_view_qna">
		<li>
			<b class="ico_q">Q</b> 
			<span class="qa_memo"><?=nl2br($qna[qa_memo])?></span>
		</li>
		<?php
		$que2 = sql_query("select * from nfor_campaign_qna where qa_parent='$qna[qa_id]' order by qa_id desc");
		if(sql_num_rows($que2)){
			while($data2 = sql_fetch_array($que2)){
		?>
		<li class="qna_a">
			<b class="ico_a">A</b> 
			<span class="qa_memo_reply">
				<?=nl2br($data2[qa_memo])?>
				<span class="qa_reply_datetime"><?=date("Y.m.d. H:i",strtotime($data2[qa_insert_datetime]))?></span>
			</span>
		</li>
		<?php
			}
		} else{
		?>
		<li class="qna_a">
			<b class="ico_a">A</b> 
			<span class="qa_memo_reply">답변을 준비중입니다.</span>
		</li>
		<?php
		}
		?>
	</ul>

	<a href="my_campaign_qna_list.php" class="btn_list">목록보기</a>

</div>

<?php
include_once $nfor[skin_path]."tail.php";
?>