<?php
include_once $nfor[skin_path]."mypage_head.php";
?>

<style>
.item_star_form_wrap { background-color:#fff; padding:10px; position:relative; padding-bottom:10px; border-top:1px solid #333; color:#70737b; }

.order_item_wrap { padding:13px 0 13px; border-bottom:1px solid #eeeef0;  }
.order_item_wrap .item_wrap { display:block; }
.order_item_wrap .item_wrap .thumb{ float:left; position:relative; padding-right:10px; }
.order_item_wrap .item_wrap .it_name {display: block;  font-weight: normal;  font-size: 13px;  line-height:18px; color:#000;}
.order_item_wrap .item_wrap:after { display: block; clear:both; content:''; }


.order_item_wrap .item_option_list { padding: 10px; }
.order_item_wrap .item_option_list li { padding:5px 0px;position:relative; }
.order_item_wrap .item_option_list li:last-child { border:none; }
.order_item_wrap .item_option_list { font-size:12px; color:#5f5f5f; margin-top:5px;}
.order_item_wrap .item_option_list span { color:#f27935; }
.order_item_wrap .item_option_list .count { display:inline-block; color:#5f5f5f; }
.order_item_wrap .item_option_list .count:before{content: "|"; display:inline-block; padding: 0px 5px; color:#a5a5a5 ;}

.item_star_form_wrap .star_score { overflow:hidden; padding:15px 0px 0px;; }
.item_star_form_wrap .star_score .star_title {float:left; width:104px;height:80px;border:1px solid #8a8b98;background:#9798a5;line-height:80px;color:#fff;text-align:center;}
.item_star_form_wrap .star_zone{vertical-align: middle;height:50px; padding:15px 5px;}


.item_star_form_wrap .star_score .star_ava {height: 26px;margin-bottom: 15px;}
.item_star_form_wrap .star_score .sub_tit {margin-right: 5px; font-size: 0.928em; font-weight: bold; color: #6c7580;line-height: 1.642em;}

#st_memo{border:1px solid #d5d9e0; width:100%;  height:250px; padding:10px; font-size:12px; box-sizing: border-box; -webkit-box-sizing: border-box; line-height: 16px;  color: #828284; font-family: '맑은고딕'; margin-bottom:5px;}

.review_zone span{font-size:12px;}

#star_ratio .img_star { background:url('<?=$nfor[skin_path]?>img/star_big.png') no-repeat 0 -49px; background-size:243px auto; }
#star_ratio input { width:44px; height:44px; background-position:0 0; border:0px; padding:0px; margin:0px; }
#star_ratio .star_ov { background-position:0 0px; }


.item_star_form_wrap .img_zone .img_title{height:25px; line-height:25px; color:#000; font-size:12px; color:#70737b;}

.item_star_form_wrap .review_zone { border-bottom:solid 1px #efefef; padding:15px 0px; }

.item_star_form_wrap .img_zone { padding:15px 0px; }




#upload_preview { width:100%; overflow:hidden; }
#upload_preview li { float:left; width:20%; padding:5px; -webkit-box-sizing:content-box; -moz-box-sizing:content-box; box-sizing:content-box; }
#upload_preview li .preview_img { width:100%; -webkit-box-sizing:content-box; -moz-box-sizing:content-box; box-sizing:content-box; } 
</style>



<div class="item_star_form_wrap">

	<div class="order_item_wrap">
		<a href="item.php?it_id=<?=$it[it_id]?>" class="item_wrap">
			<div class="thumb">
				<img src="<?=$it[it_img]?>" width="100" height="100" >
			</div>
			<div class="it_name"><?=$it[it_name]?></div>
			<ul class="item_option_list">
			<?
			for($i=0; $i<count($it[it_option]); $i++){
			?>
			<li><?=$it[it_option][$i][ct_value]?> <div class="count">구매 <span><?=$it[it_option][$i][ct_opt_cnt]?></span>개</div></li>
			<? } ?>
		</ul>
		</a>
		
	</div>	
	

	<form name="item_star_form" id="item_star_form" method="post">
	<input type="hidden" name="mode" value="<?=$write[st_id]?"update":"insert"?>">
	<?=admin_hidden($write,"st_id")?>
	<?=admin_hidden($write,"st_it_id")?>
	<?=admin_hidden($write,"st_od_id")?>
	<?=admin_hidden($write,"st_star")?>

	<br><br>
	<table cellpadding="0" cellspacing="0" class="tb_form">
	<colgroup>
		<col width="150">
	</colgroup>
	<tr>
		<th>별점</th>
		<td>	
			<div class="star_zone">
				<div id="star_ratio">
					<? for($i=1; $i<=5; $i++){ ?><input type="button" id="score_0<?=$i?>" class="img_star<? if($i<=$write[st_star]){ ?> star_ov<? } ?>"/><? } ?>
				</div>
			</div>
		</td>
	</tr>
	<tr>
		<th>구매후기</th>
		<td>
			<textarea name="st_memo" id="st_memo" class="st_memo" cols="30" rows="5" placeholder="구매후기를 작성해주세요" maxlength="600"><?=$write[st_memo]?></textarea>
			<span>구매후기는 한글 기준 5자 이상 600자 이하로 가능합니다. (0/600)</span>
			
		
		</td>
	</tr>
	<tr>
		<th>이미지등록</th>
		<td>
		<div class="img_zone">


			<img src="<?=$nfor[skin_path]?>/img/imgplus.png" id="uploadBtn" style="vertical-align:-8px;">&nbsp;&nbsp;
			<span class="img_title">이미지 최대 10장 등록가능</span>

			<ul id="upload_preview">
			<?
			for($i=0; $i<count($write[st_img_thumb]); $i++){
			?>
			<li>
				<img src='<?=$write[st_img_thumb][$i]?>' class='preview_img'>
				<input type='hidden' name='st_img[]' class='st_img' value='<?=$write[st_img][$i]?>'>
			</li>
			<?
			}
			?>
			</ul>

		</div>
		</td>
	</tr>
	</table>

	<div class="bottom_btn"><span class="btn_pack"><?=admin_button("is_submit_btn", $write[st_id]?"수정하기":"등록하기","btn_lg black")?></span></div>
	</form>
</div>


<script>

$(function(){
	$( "#upload_preview" ).sortable();
})

$(document).on("click", ".preview_img", function(){
	if(confirm("등록된 이미지를 삭제하시겠습니까?")){
		$(this).parent('li').remove();
	}	
});

window.onload = function() {

	var uploader = new ss.SimpleUpload({
		  button: 'uploadBtn',
		  url: 'item_star_form.php?mode=upload',
		  responseType: 'json',
		  name: 'uploadfile',
		  multiple: true,
		  allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
		  onSubmit: function(filename, extension) {

		  },
		  onComplete:   function(filename, response) {

			str = "<li>";
			str += "<img src='"+response.thumbnail+"' class='preview_img'>";
			str += "<input type='hidden' name='st_img[]' class='st_img' value='"+response.filename+"'>";
			str += "</li>";
			$("#upload_preview").append(str);

			if(!response){
				alert(filename + 'upload failed');
				return false;
			}

		  }
	});
};

$(document).on("click", "#star_ratio input", function(){
	var eqindex = $("#star_ratio input").index(this);
	for(i=0; i<$("#star_ratio input").length; i++){
		if(i <= eqindex){
			$("#star_ratio").children().eq(i).addClass("star_ov");
		} else{
			$("#star_ratio").children().eq(i).removeClass("star_ov");
		}
	}
	$("#st_star").val(eqindex+1);
});

$(document).on("click","#is_submit_btn",function(){
	$.ajax({
		type:"post",
		data :$("#item_star_form").serialize(),
		url:"item_star_form.php",
		success:function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				if(json["msg"]){
					alert(json["msg"]);
				}
				if(json["url"]){
					location.href = json["url"];
				}
			} else{
				if(json["msg"]){
					alert(json["msg"]);
				}
				if(json["result"]){
					$("#"+json["result"]).focus();
				}
			}
		}
	});
	event.preventDefault();
});
</script>

<?php
include_once $nfor[skin_path]."mypage_tail.php";
?>