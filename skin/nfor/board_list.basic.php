<div class="board_list">
	<a href="board_form.php?tbl=<?=$board[bo_tbl]?>"  class="write_btn"><img src="<?=$nfor[skin_path]?>img/write_btn_ic.png">글쓰기</a>
	<ul >
		<?php
		for($i=0; $i<count($return["list"]); $i++){
			$row = $return["list"][$i];
		?>
		<li>
		<a href="board_view.php?tbl=<?=$board[bo_tbl]?>&nf_id=<?=$row[nf_id]?>">
			<div class="avatar" <?php if($row[nf_mb_photo]){ ?>style="background:url(<?=$row[nf_mb_photo]?>)"<?php } ?>></div>
		    <strong class="nick"><?=$row[nf_mb_nick]?></strong>
            <p class="subject"><?=$row[nf_subject]?></p>
			<div class="right_mem_info">
				<span class="hit">조회 : <b class="txt_num"><?=$row[nf_hit]?></b></span>
				<span class="date">등록일시 : <b class="txt_num"><?=$row[nf_datetime]?></b></span>
				<span class="reple txt_num"> <?=$row[nf_comment]?></span>
			</div>
			<? if($row[nf_img]){ ?><div class="img_list"><img src="<?=$row[nf_img]?>"></div><? } ?>
		</a>
		</li>
		<?php } ?>
	</ul>
</div>

<? if(!$scroll_load){ ?><div class="page_center"><?=$pagelist?></div><? } ?>



<?php if($scroll_load){ ?>
<div class="loading_wait">
	<img src="<?=$nfor[skin_path]?>img/ajax-loader.gif">
	<span>목록을 불러오고 있습니다</span>
	<p>잠시만 기다려 주세요</p>
</div>

<script type="text/html" id="item_list_script">
<li>
<a href="board_view.php?tbl=<%=tbl%>&nf_id=<%=nf_id%>">
	<div class="avatar" <% if(nf_mb_photo){ %>style="background:url(<%=nf_mb_photo%>)"<% } %>></div>
    <strong class="nick"><%=nf_mb_nick%></strong>
	<p class="subject"><%=nf_subject%></p>
	<div class="right_mem_info">
		<span class="hit">조회 : <b class="txt_num"><%=nf_hit%></b></span>
		<span class="date">등록일시 : <b class="txt_num"><%=nf_datetime%></b></span>
		<span class="reple txt_num"> <%=nf_comment%></span>
	</div>
	<% if(nf_img){ %><div class="img_list"><img src="<%=nf_img%>"></div><% } %>
</a>
</li>
</script>

<script>
var tbl = "<?=$board[bo_tbl]?>";
var is_last = 0;
var page = 1;

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
		$(".board_list ul").html("");
	}
	$.ajax({
		type     : "get",
		url      : "board_list.php",
		data     : "tbl="+tbl+"&json=list&page="+page,
		dataType : "json",
		cache: false,
		success  : function(data) {
			if(data.count > 0){
				template = _.template($("#item_list_script").html());
				var template_html = "";
				for(var i=0; i<data.list.length; i++) {
					template_html +=  template({nf_id: data.list[i].nf_id
						, nf_img: data.list[i].nf_img
						, nf_subject: data.list[i].nf_subject
						, nf_mb_nick: data.list[i].nf_mb_nick
						, nf_comment: data.list[i].nf_comment
						, nf_mb_photo: data.list[i].nf_mb_photo
						, nf_datetime: data.list[i].nf_datetime
						, nf_hit: data.list[i].nf_hit});
				}
				$(".board_list ul").append(template_html);
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
<?php } ?>