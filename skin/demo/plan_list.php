<?php
include_once $nfor[skin_path]."head.php";
?>

<style>
.plan_wrap .tit{font-family:'NanumGothicBold'; padding-top: 61px;padding-bottom: 40px; border-bottom: 0; font-size: 34px; text-align:center;}

/*박스형 4개*/
.plan_list_area .type_2{ width: 1220px; margin-left:-20px;; overflow:hidden; }
.plan_list_area .type_2 li{float:left; width: 385px;  margin-left: 20px; margin-bottom: 20px; -webkit-box-sizing: content-box; -moz-box-sizing: content-box; box-sizing: content-box; }
.plan_list_area .type_2 .border{padding:0px; background-color: white; line-height: 1.6;  overflow: hidden; width: 385px;  border: 1px solid rgb(208, 213, 217); }
.plan_list_area .type_2 .border:hover{border:solid 1px #e83862;}
.plan_list_area .type_2 .border .thumb{width:100%; height:195.6px; overflow:hidden;}
.plan_list_area .type_2 .border .thumb img{width:100%; float:left;}
.plan_list_area .type_2 .border .it_info{position: relative; -webkit-box-sizing: content-box; -moz-box-sizing: content-box; box-sizing: content-box; padding:0px 15px 10px;; }
.plan_list_area .type_2 .border .it_info .pl_period{overflow: hidden;width: 100%; height: 18px;  font-size: 12px;  color: #7d7e80; margin-top:10px;  white-space: nowrap;  text-overflow: ellipsis;}
.plan_list_area .type_2 .border .it_info .pl_subject{display: -webkit-box; overflow: hidden; height: 43px; margin-top: 1px; font-weight: 400; font-size: 15px;line-height: 22px;  text-overflow: ellipsis; -webkit-line-clamp: 2; -webkit-box-orient: vertical;}
.plan_list_area .type_2 .border .it_info .pl_subject:hover{text-decoration:underline;}
</style>

<div class="plan_wrap">
	<div class="tit">기획전<br>
	<span style="font-size:14px; font-family:'Nanum Gothic">프라이스 박스에서 야심차게 준비한 기획전</span>
	</div>
		
	<div class="plan_list_area">
		<div class="type_2">
			<ul class="plan_list">
				<?php
				for($i=0; $i<count($return["list"]); $i++){
					$plan = $return["list"][$i];
				?>
				<li>
					<div class="border">
						<a href="plan.php?pl_id=<?=$plan[pl_id]?>">
						<div class="thumb">
							<img src="<?=$plan[pl_img]?>">
						</div>
						<div class="it_info">
							<p class="pl_period"><?=$plan[pl_period]?></p>
							<p class="pl_subject"><?=$plan[pl_subject]?></p>
						</div>
						</a>			
					</div>				
				</li>
				<? } ?>
			</ul>
		</div>
	</div>

	<? if(!$scroll_load){ ?><div class="page_center"><?=$pagelist?></div><? } ?>
</div>


<? if($scroll_load){ ?>
<div class="loading_wait">
	<img src="<?=$nfor[skin_path]?>img/ajax-loader.gif">
	<span>목록을 불러오고 있습니다</span>
	<p>잠시만 기다려 주세요</p>
</div>

<script type="text/html" id="item_list_script">
<li>
	<div class="border">
		<a href="plan.php?pl_id=<%=pl_id%>">
		<div class="thumb">
			<img src="<%=pl_img%>">
		</div>
		<div class="it_info">
			<p class="pl_period"><%=pl_period%></p>
			<p class="pl_subject"><%=pl_subject%></p>
		</div>
		</a>			
	</div>				
</li>
</script>

<script>
var is_last = 0;
var page = 1;

$(window).scroll(function() {
	if ($(window).scrollTop() == $(document).height() - $(window).height()) {
		if(is_last==0){
			console.log(++page);
			item_list_load();
		}
	}
});

function item_list_load(){
	$(".loading_wait").show();
	if(page==1){
		$(".plan_list").html("");
	}
	$.ajax({
		type     : "get",
		url      : "plan_list.php",
		data     : "json=list&page="+page,
		dataType : "json",
		cache: false,
		success  : function(data) {
			if(data.count > 0){
				template = _.template($("#item_list_script").html());
				var template_html = "";
				for(var i=0; i<data.list.length; i++) {
					template_html +=  template({pl_id: data.list[i].pl_id
						, pl_period: data.list[i].pl_period
						, pl_subject: data.list[i].pl_subject
						, pl_img: data.list[i].pl_img
						, pl_bgcolor: data.list[i].pl_bgcolor
						, item: data.list[i].item});
				}
				$(".plan_list").append(template_html);
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