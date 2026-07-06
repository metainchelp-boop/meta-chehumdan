<?
$cookie_keyword = get_cookie("search_keyword");
$cookie_keyword_date = get_cookie("search_keyword_date");

$exp = explode("|",$cookie_keyword);
$exp_date = explode("|",$cookie_keyword_date);

$recent_cnt = count($exp)-1;


if($config[cf_keyword_type]=="0" or $config[cf_keyword_type]=="2"){

	$time1 = $config[cf_keyword_hour];
	$time2 = $config[cf_keyword_hour]*2;
	$se_datetime = date("Y-m-d H:i:s",strtotime("-$time1 hours")); // 전날
	$se_datetime_history = date("Y-m-d H:i:s",strtotime("-$time2 hours")); // 전전날 

	$i = 1;
	$que = sql_query("select se_keyword, count(*) as se_cnt from nfor_search where se_datetime < '$se_datetime' and se_datetime >= '$se_datetime_history' group by se_keyword order by se_cnt desc limit 100"); // 이전데이터를 가져옴
	while($data = sql_fetch_array($que)){
		$prev_rank[$data[se_keyword]] = $i;
		$i++;
	}

	$i = 1;
	$que = sql_query("select se_keyword, count(*) as se_cnt from nfor_search where se_datetime >= '$se_datetime' group by se_keyword order by se_cnt desc limit 20"); // 최근데이터를 가져옴
	while($data = sql_fetch_array($que)){
		if($i==$prev_rank[$data[se_keyword]]){ // 똑같음
			$print_rank = "middle";
			$chang_cnt = "";
		} elseif($i<$prev_rank[$data[se_keyword]]){ // 순위가올라갔다면
			$print_rank = "up";
			$chang_cnt = $prev_rank[$data[se_keyword]]-$i;
		} elseif($i>$prev_rank[$data[se_keyword]] and $prev_rank[$data[se_keyword]]){ // 순위가떨어졌다면						
			$print_rank = "down";
			$chang_cnt = $i-$prev_rank[$data[se_keyword]];
		} else{
			$print_rank = "new";
			$chang_cnt = "";
		}

		$echo_keyword[] = $data[se_keyword];
		$echo_rank[] = $print_rank;
		$echo_cnt[] = $chang_cnt;

		$i++;
	}

}

$limit = 21-$i;
if(($config[cf_keyword_type]=="1" or $config[cf_keyword_type]=="2") and $limit){
	$que = sql_query("select * from nfor_keyword where 1 order by ke_rank asc limit 20"); // 최근데이터를 가져옴
	while($data = sql_fetch_array($que)){

		if(!$data[ke_current]){ // 똑같음
			$print_rank = "middle";
			$chang_cnt = "";
		} elseif($data[ke_current]>0){ // 순위가올라갔다면
			$print_rank = "up";
			$chang_cnt = $data[ke_current];
		} elseif($data[ke_current]<0){ // 순위가떨어졌다면						
			$print_rank = "down";
			$chang_cnt = $data[ke_current];
		} else{
			$print_rank = "new";
			$chang_cnt = "";
		}

		$echo_keyword[] = $data[ke_keyword];
		$echo_rank[] = $print_rank;
		$echo_cnt[] = $chang_cnt;

		$i++;
	}
}
?>
<style>
#search_bg { width: 100%; background-color:rgba(0,0,0,0.5); height:100%; position:fixed; top:0; display:none; z-index:99990; }


#header_search { position:fixed; left:0px; top:-600px; width:100%; z-index:99999; height:56px; display:none; }
#gnb_search { width:100%; height:56px; box-sizing:border-box;  background-color:#fff; border-bottom:solid 1px #ccc; position:relative; padding:8px 60px 0px 10px; }


#gnb_search .search_input { width:100%; height:40px; border:solid 1px #ea2323; padding:0px 35px; box-sizing:border-box; -webkit-box-sizing:border-box; font-size:16px; color:#999999; -webkit-appearance:none;  border-radius:0px; }


#gnb_search .search_btn { cursor:pointer; display:block; position:absolute; left:20px; top:18px; background: url("<?=$nfor[skin_path]?>img/layout.png") no-repeat;background-size: 320px auto;background-position: -50px -400px;width:18px; height:18px; }

#gnb_search .search_close { cursor:pointer; display:block; position:absolute; right:5px; top:8px; padding:10px; font-size:16px; }

.hit_recent_search_wrap { background-color:#fff; width:100%; }


/* 검색어선택 */
.search_select { margin:0px 0px 0px -1px; padding:0px; width:100%; overflow:hidden;   }



.search_select div {  width:50%; padding:0px; float:left; background-color:#f7f7f7; border-bottom:solid 1px #ddd; border-top:none; text-align:center; margin:0px 0px 0px -1px; }
.search_select div a { cursor:pointer; display:block; overflow:hidden; padding:9px 0px; font-size:14px; }
.search_select div.on { position:relative; background-color:#ffffff; border:solid 1px #cccccc; border-top:none; border-bottom:none; padding-top:1px; }
.search_select div.on a { cursor:pointer; }


.search_select div.on:last-child { margin:0px; border-right:none;  }

/* 검색어선택 */


/* 인기검색어 */

.hit_keyword_wrap{ display:none;  }
.hit_keyword_wrap.on{ display:block;  }

.hit_keyword { padding:0px; margin:0px; list-style:none; overflow:hidden; background-color:#fff; width:100%; text-overflow:ellipsis; -webkit-text-overflow:ellipsis; white-space:nowrap; overflow:hidden; }
.hit_keyword li { width:50%; padding:0px; float:left; background-color:#fff; margin:0px 0px 0px -1px;  border-bottom:solid 1px #eee; border-left:solid 1px #eee; position:relative;  }
.hit_keyword li a { display:block; padding:8px 15px; font-size:13px; text-decoration:none; color:#000000; }  
/* 인기검색어 */

/* 최근검색어 */
.recent_keyword_wrap { margin:0px; padding:0px; width:100%; display:none; }
.recent_keyword_wrap.on { display:block; }
.recent_keyword_wrap .recent_keyword { padding:0px; margin:0px; list-style:none; overflow:hidden; background-color:#fff; width:100%; max-height:170px; overflow-x:hidden; overflow-y:scroll; -webkit-overflow-scrolling:touch; }
.recent_keyword_wrap .recent_keyword li { padding:0px 90px 0px 0px; background-color:#fff; border-bottom:solid 1px #eee; position:relative; }
.recent_keyword_wrap .recent_keyword li a { display:block; padding:9px 0px 9px 15px; font-size:14px; text-decoration:none; color:#000000; }  


.recent_keyword_wrap .recent_keyword li span { position:absolute; top:10px; right:35px; color:#ccc; font-size:13px; }
.recent_keyword_wrap .recent_keyword li .recent_delete_ea_btn { display:block; cursor:pointer; padding:10px 10px; position:absolute; top:0px; right:0px; color:#ccc; font-size:13px; }


.recent_keyword_wrap .recent_keyword li:last-child { border-bottom:solid 1px #fff; } 

.recent_delete_all_btn { cursor:pointer; background-color:#f7f7f7; border-top:solid 1px #eee; height:34px; line-height:34px; font-size:14px; text-align:right; padding-right:10px; }



#keyword_keyup li{ padding:12px 0px; border-top:solid 1px #f4f4f4; background-color:#fff; }
#keyword_keyup a{ display:block; font-size:15px; background-color:#fff; margin:0px 20px; text-overflow:ellipsis; -webkit-text-overflow:ellipsis; white-space:nowrap; overflow:hidden; }

/* 최근검색어 */




.recent_history_no { text-align:center; padding:60px 0px; font-size:15px; color:#666; }
.recent_history_no.off { display:none; }





.hit_keyword { position:relative; }

.hit_keyword i { font-style:normal; color:#a8adb9; }
.hit_keyword span { color:#666; }
.hit_keyword em { font-style:normal; position:absolute; top:10px; right:23px; display:block; width:11px; height:11px;; }


.hit_keyword em.up { background: url("<?=$nfor[skin_path]?>img/rank2.png") no-repeat 0 -0px; background-size:11px 44px; }
.hit_keyword em.down { background: url("<?=$nfor[skin_path]?>img/rank2.png") no-repeat 0 -11px; background-size:11px 44px; }

.hit_keyword em.middle { background: url("<?=$nfor[skin_path]?>img/rank2.png") no-repeat 0px -22px; background-size:auto 44px; }
.hit_keyword em.new { background: url("<?=$nfor[skin_path]?>img/rank2.png") no-repeat -0px -33px; background-size:auto 44px; }


.hit_keyword b { font-weight:normal; position:absolute; top:0px; right:3px; display:block; width:15px; height:33px; line-height:33px; }



.wrap_search { margin:12px 0px 0px 0px; padding:10px 10px; width:100%; background-color:#eee; box-sizing:border-box; -webkit-box-sizing:border-box;  }
</style>
<SCRIPT LANGUAGE="JavaScript">
<!--

$(document).on("click", "#search_bg", function(){
	$(".search_input").val("");
	$("#search_bg").hide();
	$("#header_search").animate({"top":"-600px"}, 500 );
});



$(document).on("click", ".search_close", function(){
	$(".search_input").val("");
	$("#search_bg").hide();
	$("#header_search").animate({"top":"-600px"}, 500 );
});

$(document).on("click", ".btn_search", function(){
	$("#search_bg").show();
	$("#header_search").animate({"top":"0px"}, 500 ).show();
	$(".search_input").val("").focus();
});

$(document).on("click", ".search_btn", function(){
	document.item_search.submit();
});

$(document).on("click", ".recent_delete_all_btn", function(){

	$.ajax({
		type: "post",
		data : "mode=delete_all",
		url: "search.php",
		success: function(response){
			var json = $.parseJSON(response); 
			
			if(json["result"]=="ok"){
				$(".recent_keyword").html("");
				$(".recent_history_no").show();
			} else{
				alert(json["msg"]);
			}
		}
	});

});


$(document).on("click", ".recent_delete_ea_btn", function(){

	if(!$(".recent_keyword li").length){
		$(".recent_history_no").show();
	}

	
	var keyword = $(this).data("keyword");
	var keyword_id = $(this).data("keyword_id");

	$.ajax({
		type: "post",
		data : "mode=delete&keyword="+keyword,
		url: "search.php",
		success: function(response){
			var json = $.parseJSON(response); 
			
			if(json["result"]=="ok"){
				$("#keyword_"+keyword_id).remove();
			} else{
				alert(json["msg"]);
			}
		}
	});

});

$(document).on("click", ".search_select div", function(){
	$(".search_select div").removeClass("on");
	$(this).addClass("on");

	$(".keyword_wrap").hide();
	$("."+$(this).attr("id")+"_keyword_wrap").show();

});

$(document).on("keyup", ".search_input", function(){
	var keyword = $(this).val();
	if(keyword){
		$.ajax({
			type: "post",
			data : "mode=keyup&keyword="+keyword,
			url: "search.php",
			success: function(response){
				var json = $.parseJSON(response); 
				if(typeof json["keyword"] == "object" && json["keyword"]){
					if(json["result"]=="ok"){
						$("#keyword_keyup").html("");
						var keyword_array = [];
						keyword_array = json["keyword"];											
						$.each(keyword_array, function(index, value) {
							$("#keyword_keyup").append("<li><a href=\"search.php?keyword="+value+"\">"+value+"</a></li>");
						});
						$(".keyword_keyup_wrap").show();
						$(".hit_recent_search_wrap").hide()
					} else{
						alert(json["result"]);
						alert(json["msg"]);
					}
				} else{
					//$("#keyword_keyup").html("");
				}
			}
		});
	} else{
		$(".keyword_keyup_wrap").hide();
		$(".hit_recent_search_wrap").show();
	}

});

//-->
</SCRIPT>



<div id="search_bg"></div>

<div id="header_search">

	<div id="gnb_search">
		

		<form name="item_search" action="search.php" autocomplete="off">
			<a class="search_btn"></a>
			<input type="search" name="keyword" placeholder="검색어를 입력하세요" value="<?=htmlspecialchars($keyword)?>" class="search_input">
			<a class="search_close">닫기</a>
		</form>

		
	</div>


	<div class="keyword_keyup_wrap">
	
		<ul id="keyword_keyup"></ul>

	</div>


	<div class="hit_recent_search_wrap">

		<div class="search_select">
			<div id="hit"<?=!$recent_cnt?" class=\"on\"":""?>><a>인기검색어</a></div>
			<div id="recent"<?=$recent_cnt?" class=\"on\"":""?>><a>최근검색어</a></div>
		</div>

		<div class="hit_keyword_wrap keyword_wrap<?=!$recent_cnt?" on":""?>">
			<ul class="hit_keyword">
				<?
				for($i=0; $i<count($echo_keyword); $i++){
				?>
				<li><a href="search.php?keyword=<?=urlencode($echo_keyword[$i])?>"><i><?=$i+1?></i> <span><?=$echo_keyword[$i]?></span> <em class="<?=$echo_rank[$i]?>"></em> <b><?=$echo_cnt[$i]?></b></a></li>
				<? } ?>
			</ul>
			<div style="clear:both;"></div>
		</div>

		<div class="recent_keyword_wrap keyword_wrap<?=$recent_cnt?" on":""?>">
			<ul class="recent_keyword">
				<?
				for($i=$recent_cnt-1; $i>=0; $i--){
					$regdate = date("y.m.d",strtotime($exp_date[$i]));
				?>
				<li id="keyword_<?=$i?>"><a href="search.php?keyword=<?=urlencode($exp[$i])?>"><?=$exp[$i]?></a> <span><?=$regdate?></span> <a class="recent_delete_ea_btn" data-keyword_id="<?=$i?>" data-keyword="<?=$exp[$i]?>">X</a></li>
				<? } ?>
			</ul>

			<div class="recent_history_no<?=$recent_cnt?" off":""?>">최근검색어가 없습니다.</div>

			<div class="recent_delete_all_btn">전체삭제</div>
		</div>


	</div>
	
	
</div>