


<form name="nf_form" id="nf_form" method="post" autocomplete="off">

<input type="hidden" name="tbl" value="<?=$board[bo_tbl]?>">
<input type="hidden" name="mode" value="<?=$write[nf_id]?"update":"insert"?>">
<input type="hidden" name="nf_id" value="<?=$write[nf_id]?>">
<input type="hidden" name="nf_category" id="nf_category" value="<?=$write[nf_category]?>">

<div class="board_write">
	<table>
	<colgroup>
				<col style="width:10%;">
				<col style="width:90%;">
	</colgroup>
	<tr>
		<th>제목</th>
		<td><?=admin_text($write,"nf_subject","inp wid100","placeholder='제목'")?></td>
	</tr>
	<tr>
		<th>내용</th>
		<td>	<?=admin_textarea($write,"nf_memo","","placeholder='내용'")?></td>
	</tr>
	<tr>
		<td colspan="2">
		<input type="button" value="사진파일 첨부하기  + " id="uploadBtn" class="img_upload_btn">
		<ul class="img_upload_preview">
		<?php
		if($write[nf_id]){
			for($i=0; $i<10; $i++){
				if($write["nf_img".$i]){
		?>
		<li>
		<img src='<?=thumbnail("$nfor[path]/data/board/$board[bo_tbl]/".$write["nf_img".$i],80,80,0,1)?>' class='preview_img'>
		<img src='<?=$nfor[skin_path]?>/img/x.png' class='preview_img_del'>
		<input type='hidden' name='nf_img[]' class='nf_img' value='<?=$write["nf_img".$i]?>'>			
		</li>
		<?php
				}
			}
		}
		?>
		</ul>
		<span class="coution">※ 사진은 <b class="txt_num">10MB</b>이하의 <b class="txt_num">PNG, GIF, JPG</b> 파일만 등록 가능합니다.</span>
		</td>

	</tr>
	</table>
</div>

<div class="board_btn_zone">
	<span class="btn_pack"><?=admin_submit("submit_btn", "글쓰기","btn_lg black")?></span>
	<span class="btn_pack"><a href="board_list.php?tbl=<?=$board[bo_tbl]?>" class="btn_lg white">목록으로</a></span>
</div>






</form>



<script>
var tbl = "<?=$board[bo_tbl]?>";

$(function(){
	$( ".img_upload_preview" ).sortable();
})

window.onload = function() {

	var uploader = new ss.SimpleUpload({
		  button: 'uploadBtn',
		  url: 'board_form.php?mode=upload&tbl='+tbl,
		  responseType: 'json',
		  name: 'uploadfile',
		  multiple: true,
		  allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
		  onSubmit: function(filename, extension) {

		  },
		  onComplete:   function(filename, response) {

			str = "<li>";
			str += "<img src='"+response.thumbnail+"' class='preview_img'>";
			str += "<img src='<?=$nfor[skin_path]?>img/x.png' class='preview_img_del'>";
			str += "<input type='hidden' name='nf_img[]' class='nf_img' value='"+response.filename+"'>";
			str += "</li>";
			$(".img_upload_preview").append(str);

			if(!response){
				alert(filename + 'upload failed');
				return false;
			}

		  }
	});
};

$(document).on("click", ".preview_img_del", function(){
	$(this).parent('li').remove();
});

$(document).on("click","#submit_btn",function(){
	$.ajax({
		type:"post",
		data :$("#nf_form").serialize(),
		url:"<?=$PHP_SELF?>",
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