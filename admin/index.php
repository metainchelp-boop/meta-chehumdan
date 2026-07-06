<?php
include_once "path.php";

if($member[mb_admin]=="1" or $member[mb_admin]=="2") goto_url("review_wait_list.php");

include_once "head.php";
?>

<style>
.index_layout h4{font-size:15px;font-weight:bold;}
.top_title{ width: auto; padding-top:5px; margin-top:-25px; margin-bottom:30px;  border-bottom: 1px solid #333; height:50px;}
.martop8{margin-top:8px;}

#nfor_graph {position:relative; height:350px;}
#nfor_graph_price {position:absolute;top:10px;left:20px;margin:0;padding:0;width:12%;list-style:none}
#nfor_graph_price li {position:relative;padding:0 10% 0 0;height:48px;font-family:tahoma;text-align:right}
#nfor_graph_price li span {position:absolute;top:7px;right:-10%;width:10px;height:1px;background:#e9e9e9}
#nfor_graph_area {position:absolute;top:0;left:15%;margin:0;padding:0;width:85%;height:260px;border:1px solid #e9e9e9;list-style:none}
#nfor_graph_area li {position:relative;float:left;padding:0 1% 0 0;width:14%;height:100%}
#nfor_graph_area .graph {position:absolute;bottom:0;width:40%;height:0}
#nfor_graph_area .order {background:#7baade;left:7%}
#nfor_graph_area .cancel {background:#99a4b2;right:7%}
#nfor_graph_area #price_tooltip {display:none;position:absolute;top:-18px;left:0;background-color:#fff;border:1px solid gray}
#nfor_graph_area #price_tooltip div {white-space:nowrap}
#nfor_graph_date {position:absolute;top:275px;left:15%;margin:0;padding:0;width:85%;border:1px solid #fff;list-style:none}
#nfor_graph_date li {position:relative;float:left;width:14%;font-family:tahoma;text-align:center}
#nfor_graph_date li span {position:absolute;top:-16px;right:0;width:1px;height:10px;background:#e9e9e9}
#nfor_graph_legend {position:absolute;top:-25px;left:15%}
#nfor_graph_legend span {display:inline-block;width:13px;height:13px;vertical-align:middle}
#nfor_graph_legend #legend_order {background:#7baade}
#nfor_graph_legend #legend_cancel {margin:0 0 0 10px;background:#99a4b2}

#nfor_graph_area .bg0{background:#fff }
#nfor_graph_area .bg1{background:#eff3f9 }
.ttbl{ width:100%; border-right: 1px solid #E6E6E6;}
.ttbl th{ background-color: #BCBCBC !important; color: #FFFFFF; border-top: 1px solid #AEAEAE !important; border-bottom: 1px solid #AEAEAE !important;    border-right: 1px solid #aeaeae; padding:10px; text-align:center;}
.ttbl td{border-left: 1px solid #E6E6E6; line-height: 1.42857143; color: #444444; vertical-align: middle; border-top: 1px solid #E6E6E6; border-bottom: 1px solid #E6E6E6;height: 31px; text-align:center; padding:10px; }
.padding0{padding:0px;}
.mar2-20{margin:20px 0px;}


.board-list-newest li{display: block;overflow: hidden;position: relative;margin-top: 10px;padding-left:5px;}
.board-list-newest li a{color: #333;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;display: block;padding-right: 100px; letter-spacing:-1px;}
.board-list-newest li a+span{overflow: hidden;display: block;white-space: nowrap;position: absolute;top: 0;right: 0;color: #999;font-size: 11px;}
.table-title {border-bottom: 1px solid #333;padding-bottom:11px;margin-bottom: 15px;}


.btn_gray{padding:3px 5px; width: 100px;font-size:10px; border:solid 1px #dedede; display:inline-block;  text-align:center; color:#666; margin-top:5px; text-decoration:none;}
.btn_gray:hover{padding:3px 5px; width: 100px;font-size:10px; border:solid 1px #666; display:inline-block;  text-align:center; color:#000; margin-top:5px; text-decoration:none;}

.btn_green{padding:3px 5px; width: 100px;font-size:10px; border:solid 1px #7baade; background-color:#7baade; display:inline-block;  text-align:center; color:#FFF; margin-top:5px;}

.no_ttbl{border-top:solid 1px #333; background-color:#FFF;border-right: 1px solid #E6E6E6; width:100%}
.no_ttbl td{border-left: 1px solid #E6E6E6; line-height: 1.42857143; color: #444444; vertical-align: middle;  border-bottom: 1px solid #E6E6E6;height: 31px; text-align:right; padding:15px; }
.no_ttbl td:hover{background-color:#f7f7f7;}
.no_ttbl .tt_title{text-align:left; padding:5px;font-weight:bold;}
.no_ttbl .tt_num{text-align:right; padding:5px;}
.no_ttbl .tt_num .num{color:#3389de; font-size:17px; font-family:'roboto'; font-weight:600; line-height:20px;}
</style>
<div class="index_layout">


	<div class="top_title">
		<div class="col-md-8 padding0">
			<h4><i class="glyphicon glyphicon-home"></i> 사이트명 : <?=$config[cf_name]?> <small><?=$config[cf_url]?></small></h4>
		</div>
		<div class="col-md-4 martop8">
			<!-- <span style="font-size:13px;"><i class="glyphicon glyphicon-envelope"></i> SMS 잔여건수 100/20건 남음 </span><a class="btn btn-gray btn-sm" style="vertical-align:2px;">충전</a> -->
		</div>
	</div>

	<?php
	// 캠페인 진행 KPI (2026-06-15)
	$kr1=sql_fetch("select count(*) as c from nfor_review where rv_step='1' and DATE_FORMAT(rv_datetime,'%Y-%m-%d')=CURDATE() and rv_delete='0'"); $k_today=(int)$kr1['c'];
	$kr3=sql_fetch("select count(*) as c from nfor_review where rv_step='3' and rv_delete='0'"); $k_inspect=(int)$kr3['c'];
	$kr8=sql_fetch("select count(*) as c from nfor_review where rv_step='8' and rv_delete='0'"); $k_cand=(int)$kr8['c'];
	$kra=sql_fetch("select count(*) as c from nfor_campaign where cp_use='1' and cp_edatetime>=NOW()"); $k_active=(int)$kra['c'];
	$k_prop=0; if(sql_fetch("show tables like 'nfor_creator_proposal'")){ $krp=sql_fetch("select count(*) as c from nfor_creator_proposal"); $k_prop=(int)$krp['c']; }
	?>
	<div style="clear:both"></div>
	<div class="mc-kpi" style="display:flex;gap:12px;flex-wrap:wrap;margin:4px 0 6px;font-family:'Pretendard','Apple SD Gothic Neo',sans-serif">
		<a href="review_wait_list.php" style="flex:1;min-width:140px;background:#fff;border:1px solid #eaedf1;border-radius:14px;padding:15px 17px;box-shadow:0 1px 2px rgba(16,24,40,.04);text-decoration:none;display:block">
			<div style="font-size:12.5px;color:#6b7684;font-weight:700">오늘 신청</div>
			<div style="font-size:24px;font-weight:800;margin-top:6px;color:#3182f6"><?=number_format($k_today)?><span style="font-size:13px;color:#8b95a1;font-weight:700">건</span></div>
		</a>
		<a href="review_post_list.php" style="flex:1;min-width:140px;background:#fff8ec;border:1px solid #ffe2a8;border-radius:14px;padding:15px 17px;text-decoration:none;display:block">
			<div style="font-size:12.5px;color:#8a6d3b;font-weight:700">검수 대기</div>
			<div style="font-size:24px;font-weight:800;margin-top:6px;color:#b5740a"><?=number_format($k_inspect)?><span style="font-size:13px;color:#bfa06a;font-weight:700">명</span></div>
		</a>
		<a href="review_pre_list.php" style="flex:1;min-width:140px;background:#fff;border:1px solid #eaedf1;border-radius:14px;padding:15px 17px;box-shadow:0 1px 2px rgba(16,24,40,.04);text-decoration:none;display:block">
			<div style="font-size:12.5px;color:#6b7684;font-weight:700">1차후보 대기</div>
			<div style="font-size:24px;font-weight:800;margin-top:6px;color:#191f28"><?=number_format($k_cand)?><span style="font-size:13px;color:#8b95a1;font-weight:700">명</span></div>
		</a>
		<a href="campaign_list.php" style="flex:1;min-width:140px;background:#eafaf0;border:1px solid #c3eed5;border-radius:14px;padding:15px 17px;text-decoration:none;display:block">
			<div style="font-size:12.5px;color:#0d8a5f;font-weight:700">모집중 캠페인</div>
			<div style="font-size:24px;font-weight:800;margin-top:6px;color:#02b150"><?=number_format($k_active)?><span style="font-size:13px;color:#7bc7a0;font-weight:700">개</span></div>
		</a>
		<a href="creator_list.php?view=proposals" style="flex:1;min-width:140px;background:#f4ecff;border:1px solid #e4d4fb;border-radius:14px;padding:15px 17px;text-decoration:none;display:block">
			<div style="font-size:12.5px;color:#7c3aed;font-weight:700">역제안 발송</div>
			<div style="font-size:24px;font-weight:800;margin-top:6px;color:#7c3aed"><?=number_format($k_prop)?><span style="font-size:13px;color:#b794e6;font-weight:700">건</span></div>
		</a>
	</div>
	<div style="font-size:12.5px;color:#8b95a1;margin:0 2px 16px;font-family:'Pretendard','Apple SD Gothic Neo',sans-serif">📋 <a href="review_board.php" style="color:#02b150;font-weight:700;text-decoration:none">캠페인 진행 보드</a> 에서 신청→선정→검수→완료를 한 화면에서 처리하세요</div>

	<div class="container-fluid padding0">
		<!-- 회원현황 / 방문현황 — 현대 카드 (신청현황 그래프는 상단 KPI와 중복이라 제거 2026-06-16) -->
		<?php
			$m_ymd_t = date("Y-m-d");
			$m_ymd_y = date("Y-m-d", strtotime("-1 day"));
			$m_today = sql_fetch("select count(*) as cnt from nfor_member where mb_admin='0' and mb_leave_datetime='' and DATE_FORMAT(mb_datetime,'%Y-%m-%d')='$m_ymd_t'");
			$m_yest  = sql_fetch("select count(*) as cnt from nfor_member where mb_admin='0' and mb_leave_datetime='' and DATE_FORMAT(mb_datetime,'%Y-%m-%d')='$m_ymd_y'");
			$m_total = sql_fetch("select count(*) as cnt from nfor_member where mb_admin='0' and mb_leave_datetime=''");
			$m_leave = sql_fetch("select count(*) as cnt from nfor_member where mb_level > 1 and mb_leave_datetime <> ''");
		?>
		<style>
		.mc-dash{display:flex;gap:14px;flex-wrap:wrap;margin-bottom:4px;font-family:'Pretendard','Apple SD Gothic Neo',sans-serif}
		.mc-dash .card{flex:1 1 340px;background:#fff;border:1px solid #eaedf1;border-radius:16px;padding:18px 20px;box-shadow:0 1px 2px rgba(16,24,40,.04),0 6px 18px rgba(16,24,40,.05)}
		.mc-dash .ch{display:flex;align-items:center;justify-content:space-between;margin-bottom:15px}
		.mc-dash .ch b{font-size:15.5px;font-weight:800;color:#191f28;letter-spacing:-.01em}
		.mc-dash .ch a{font-size:12px;font-weight:700;color:#6b7684;text-decoration:none;border:1px solid #e2e8f0;border-radius:8px;padding:5px 11px;background:#fafbfc}
		.mc-dash .ch a:hover{border-color:#03c75a;color:#02b150}
		.mc-dash .grid{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
		.mc-dash .st{text-align:center;background:#f8fafb;border:1px solid #f0f3f5;border-radius:12px;padding:14px 6px;text-decoration:none;display:block}
		.mc-dash a.st:hover{background:#f1faf4;border-color:#c3eed5}
		.mc-dash .st .v{font-size:23px;font-weight:800;color:#191f28;line-height:1.1}
		.mc-dash .st .v small{font-size:12px;color:#b0b8c1;font-weight:700}
		.mc-dash .st .k{font-size:11.5px;color:#8b95a1;margin-top:5px;font-weight:600}
		.mc-dash .st.accent .v{color:#02b150}
		.mc-dash .st.warn .v{color:#e8590c}
		</style>
		<div class="mc-dash">
			<div class="card">
				<div class="ch"><b>👥 회원현황</b><a href="member_list.php">전체보기</a></div>
				<div class="grid">
					<div class="st"><div class="v"><?=number_format($m_today[cnt])?><small>명</small></div><div class="k">오늘가입</div></div>
					<div class="st"><div class="v"><?=number_format($m_yest[cnt])?><small>명</small></div><div class="k">어제가입</div></div>
					<div class="st accent"><div class="v"><?=number_format($m_total[cnt])?><small>명</small></div><div class="k">전체가입</div></div>
					<a class="st warn" href="leave_list.php"><div class="v"><?=number_format($m_leave[cnt])?><small>명</small></div><div class="k">탈퇴신청</div></a>
				</div>
			</div>
			<div class="card">
				<div class="ch"><b>📈 방문현황</b><a href="analytics_day.php">전체보기</a></div>
				<div class="grid">
					<div class="st"><div class="v"><?=number_format($count[1])?><small>명</small></div><div class="k">오늘방문</div></div>
					<div class="st"><div class="v"><?=number_format($count[2])?><small>명</small></div><div class="k">어제방문</div></div>
					<div class="st"><div class="v"><?=number_format($count[3])?><small>명</small></div><div class="k">최대방문</div></div>
					<div class="st accent"><div class="v"><?=number_format($count[4])?><small>명</small></div><div class="k">전체방문</div></div>
				</div>
			</div>
		</div>

	</div>.

	<!-- 담당자별 진행 중 캠페인 (2026-06-16) -->
	<div class="container-fluid padding0">
		<?php
		// 담당자별 진행 중을 두 단계로 분리(2026-06-18): 모집중(신청기간 내) / 모집마감 후 진행중(선정·리뷰)
		$_notfin = "(cp_result_datetime is null or cp_result_datetime='' or cp_result_datetime='0000-00-00 00:00:00' or cp_result_datetime >= NOW())";
		$md_group = function($where){
			$rows=array(); $tot=0;
			$q=sql_query("select cp_md_name, count(*) as cnt from nfor_campaign where $where group by cp_md_name order by cnt desc, cp_md_name asc");
			while($r=sql_fetch_array($q)){ $rows[]=$r; $tot+=(int)$r['cnt']; }
			return array($rows,$tot);
		};
		list($rec_rows,$rec_tot) = $md_group("cp_use='1' and cp_sdatetime<=NOW() and cp_edatetime>=NOW()");
		list($on_rows,$on_tot)   = $md_group("cp_use='1' and cp_edatetime<NOW() and $_notfin");

		// 한 그룹(칩 묶음) 렌더
		$md_chips = function($rows, $phase, $accent, $bg, $line){
			if(!$rows){ echo '<span style="color:#94a3b8;font-size:13px;padding:6px 2px">해당 캠페인이 없습니다.</span>'; return; }
			foreach($rows as $mr){
				$nm=trim($mr['cp_md_name']); $un=($nm===''); $param=$un?'__none__':urlencode($nm);
				$cbg = $un?'#fff4e6':$bg; $cline = $un?'#ffd8a8':$line; $cink = $un?'#e8590c':'#0f172a'; $badge = $un?'#e8590c':$accent;
				echo '<a href="campaign_list.php?cp_md_name='.$param.'&mc_phase='.$phase.'" title="'.($un?'담당자 미지정':htmlspecialchars($nm)).' 보기" style="display:inline-flex;align-items:center;gap:7px;background:'.$cbg.';border:1px solid '.$cline.';border-radius:999px;padding:8px 14px;font-size:13.5px;font-weight:700;color:'.$cink.';text-decoration:none;transition:.1s" onmouseover="this.style.filter=\'brightness(.96)\'" onmouseout="this.style.filter=\'\'">';
				echo ($un?'⚠ 미지정':htmlspecialchars($nm));
				echo '<b style="background:'.$badge.';color:#fff;border-radius:999px;padding:1px 9px;font-size:12.5px;line-height:1.5">'.number_format($mr['cnt']).'건</b></a>';
			}
		};
		?>
		<div class="mc-dash">
			<div class="card" style="flex:1 1 100%">
				<div class="ch"><b>🧑‍💼 담당자별 진행 중 캠페인 <span style="color:#8b95a1;font-weight:700;font-size:13px">· 총 <?=number_format($rec_tot+$on_tot)?>건</span></b><a href="campaign_list.php">캠페인 목록</a></div>

				<div style="display:flex;align-items:center;gap:8px;margin:4px 0 9px 2px"><span style="background:#02b150;color:#fff;border-radius:6px;padding:2px 9px;font-size:12.5px;font-weight:800">🟢 모집 중</span><span style="color:#8b95a1;font-size:12.5px;font-weight:700"><?=number_format($rec_tot)?>건 · 지금 신청 가능(공개 사이트 노출)</span></div>
				<div style="display:flex;flex-wrap:wrap;gap:9px;margin-bottom:16px">
				<?php $md_chips($rec_rows, 'recruit', '#02b150', '#f1faf4', '#c3eed5'); ?>
				</div>

				<div style="display:flex;align-items:center;gap:8px;margin:4px 0 9px 2px"><span style="background:#f08c00;color:#fff;border-radius:6px;padding:2px 9px;font-size:12.5px;font-weight:800">🟠 모집 마감 후 진행 중</span><span style="color:#8b95a1;font-size:12.5px;font-weight:700"><?=number_format($on_tot)?>건 · 선정·리뷰 진행 중(공개 사이트 미노출)</span></div>
				<div style="display:flex;flex-wrap:wrap;gap:9px">
				<?php $md_chips($on_rows, 'ongoing', '#f08c00', '#fff7ed', '#ffd8a8'); ?>
				</div>
			</div>
		</div>
	</div>




















<div class="container-fluid padding0">

	<div class="col-md-4 mar2-20 padding0" >
		<h4 class="table-title">
			<span>제휴문의관리</span>
			<div class="pull-right more-view">
				<a href="cooperation_list.php" class="btn btn-sm  btn-white">+ 더보기</a>
			</div>
		</h4>
		<ul class="board-list-newest">
			<?php
			$que = sql_query("select * from nfor_cooperation where (1) order by cp_id desc limit 6");
			while($data = sql_fetch_array($que)){
				$data = nfor_tag_out($data);
			?>
			<li><a href="cooperation_form.php?cp_id=<?=$data[cp_id]?>"><?=$data[cp_category]?"[$data[cp_category]] ":""?><?=$data[cp_subject]?> / <?=$data[cp_name]?></a><span><?=date("Y.m.d.",strtotime($data[cp_insert_datetime]))?></span></li>
			<?php } ?>
		</ul>
	</div>

	<div class="col-md-4 mar2-20">
		<h4 class="table-title">
			<span>고객문의관리</span>
			<div class="pull-right more-view">
				<a href="customer_list.php" class="btn btn-sm  btn-white">+ 더보기</a>
			</div>
		</h4>
		<ul class="board-list-newest">
			<?php
			$que = sql_query("select * from nfor_customer where (1) order by cs_id desc limit 6");
			while($data = sql_fetch_array($que)){
				$data = nfor_tag_out($data);
			?>
			<li><a href="customer_form.php?cs_id=<?=$data[cs_id]?>"><?=$data[cs_category]?"[$data[cs_category]] ":""?><?=$data[cs_subject]?></a><span><?=date("Y.m.d.",strtotime($data[cs_insert_datetime]))?></span></li>
			<?php } ?>
		</ul>
	</div>

	<div class="col-md-4 mar2-20">
		<h4 class="table-title">
			<span>입점업체 공지사항</span>
			<div class="pull-right more-view">
				<a href="supply_notice_list.php" class="btn btn-sm  btn-white">+ 더보기</a>
			</div>
		</h4>
		<ul class="board-list-newest">
			<?php
			$que = sql_query("select * from nfor_supply_notice where (1) order by no_id desc limit 6");
			while($data = sql_fetch_array($que)){
				$data = nfor_tag_out($data);
			?>
			<li><a href="supply_notice_view.php?no_id=<?=$data[no_id]?>"><?=$data[no_category]?"[$data[no_category]] ":""?><?=$data[no_subject]?></a><span><?=date("Y.m.d.",strtotime($data[no_insert_datetime]))?></span></li>
			<?php } ?>
		</ul>
	</div>

</div>




<div class="martop20">

	<h4 class="">
		<span>최근 신청현황</span>
		<div class="pull-right more-view">
			<a href="review_wait_list.php" class="btn btn-sm  btn-white">+ 더보기</a>
		</div>
	</h4>


	<?php
	$admin[rv_media] = $nfor[cp_media];

	$ymd = date("Y-m-d");
	$result = sql_query("select * from nfor_review where rv_step='1' order by rv_id desc limit 20"); //  and DATE_FORMAT(rv_datetime,'%Y-%m-%d')='$ymd'
	?>
	<table class="table row_tbl margin0">
	<tr>
		<th>신청번호</th>
		<th>캠페인명/캠페인코드</th>
		<th>회원정보</th>
		<th>신청채널</th>
		<th>배송지정보</th>
		<th>신청일</th>
	</tr>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++){
		$row = nfor_tag_out($row);
	?>
	<tr>
		<td><?=$row[rv_id]?></td>
		<td><a href="<?=$nfor[path]?>/campaign.php?cp_id=<?=$row[rv_cp_id]?>" target="_blank"><?=$row[rv_cp_subject]?><br><?=$row[rv_cp_id]?></a></td>
		<td><a href="javascript:member('<?=$row[rv_mb_no]?>')"><?=campaign_member_info($row)?></a></td>
		<td class="textleft"><?=channel_url($row[rv_channel])?><?php if($row[rv_media]){ ?><div class="sns_icon_wrap"><span class="<?=$row[rv_media]?>_icon"><?=admin_echo($row,"rv_media")?></span></div><?php } ?></td>
		<td class="textleft">
		<?=$row[rv_dy_name]?> <?=$row[rv_dy_hp]?><br>
		<?=$row[rv_dy_zip]?> <?=$row[rv_dy_addr1]?> <?=$row[rv_dy_addr2]?>
		</td>
		<td><?=substr($row[rv_datetime],0,10)?></td>
	</tr>
	<?php
	}
	if(!$i){
	?>
	<tr>
		<td colspan="6" style="height:150px; text-align:center; line-height:45px;">최근 신청내역이 없습니다</td>
	</tr>
	<?php } ?>
	</table>


</div>


<?php
include_once "tail.php";
?>
