<?php
// pc에서는 사용 하지 않고 모바일에서만 사용하므로 디버그나 개발 할때만 사용 하면 됩니다..
goto_url($nfor['path']);

if(!$target or !$is_member){
	alert("올바른 방법으로 접속하여주세요.",$nfor['path']);
}

// 0 이면 안한것 1 이면 한것
$mission_did_check = find_table_nfor_metatech_user_twofiled($member[mb_id],$target);

if($mission_did_check > 0){
	alert("이미 수행한 미션입니다.");
}

// 리스트 가져오기
$metatech_list_array = select_table_nfor_metatech_list($target);

// 해당 미션의 오늘 참여자 인원 카운트 가져오기.
$to_day_cnt = select_table_nfor_metatech_user_list_match_idx_datetime($target);	

// 오늘의 카운트와 m_day_count 제한 카운트 를 비교..비노출..
if($to_day_cnt >= $metatech_list_array['m_day_count']){
	alert("현재 참여 제한 미션 입니다.");	
}

include_once $nfor[skin_path]."metatech_head.php";

if($metatech_list_array[meta_op_c] == "pic"){
	$meta_op_b_chk_type = true;
}
else{
	$meta_op_b_chk_type = false;
}
	
$z = "";
for($i=0;$i<count($nfor[meta_op_b]);$i++){
	if($nfor[meta_op_b][$i] == $metatech_list_array[meta_op_b]){
		$z = $i;
		break;
	}
}

$blue_b_str = "검색 키워드 복사하기";
$green_b_str = "";

switch ($z) {
	case 0:
		$green_b_str = "네이버앱 열기";
		$z_html .= '<button type="button" class="btn_copy">'.$blue_b_str.'</button>';
		$z_html .= '<a href="javascript:void(0);" class="link_naver" data-url="'.$metatech_list_array[m_url].'">'.$green_b_str.'</a>';

		$title_str_1 = "정답 맞추기";
		$title_str_2 = "대표 상품 가격을 입력하면";

		$tit_str = "정답";
		$placeholder_str = "정답을";
		$submit_str = " 정답";
		break;
	case 1:
		$green_b_str = "정답 맞추러 가기";
		$z_html .= '<a href="javascript:void(0);" class="link_naver" data-url="'.$metatech_list_array[m_url].'">'.$green_b_str.'</a>';

		$title_str_1 = "정답 맞추기";
		$title_str_2 = "대표 상품 가격을 입력하면";

		$tit_str = "정답";
		$placeholder_str = "정답을";
		$submit_str = " 정답";
		break;
	case 2:
		$green_b_str = "네이버앱 열기";
		$z_html .= '<button type="button" class="btn_copy">'.$blue_b_str.'</button>';
		$z_html .= '<a href="javascript:void(0);" class="link_naver" data-url="'.$metatech_list_array[m_url].'">'.$green_b_str.'</a>';

		$title_str_1 = "공유 미션";
		$title_str_2 = "공유하기 미션 완료하면";

		$tit_str = "링크";
		$placeholder_str = "링크를";	
		$submit_str = "";
		break;
	case 3:
		$green_b_str = "정답 맞추러 가기";
		$z_html .= '<a href="javascript:void(0);" class="link_naver" data-url="'.$metatech_list_array[m_url].'">'.$green_b_str.'</a>';

		$title_str_1 = "공유 미션";
		$title_str_2 = "공유하기 미션 완료하면";

		$tit_str = "링크";
		$placeholder_str = "링크를";	
		$submit_str = "";
		break;
	case 4:
		$green_b_str = "네이버앱 열기";
		$z_html .= '<button type="button" class="btn_copy">'.$blue_b_str.'</button>';
		$z_html .= '<a href="javascript:void(0);" class="link_naver" data-url="'.$metatech_list_array[m_url].'">'.$green_b_str.'</a>';

		$title_str_1 = "저장하기";
		$title_str_2 = "저장하기 미션 완료하면";
		break;
	case 5:
		$green_b_str = "네이버앱 열기";
		$z_html .= '<button type="button" class="btn_copy">'.$blue_b_str.'</button>';
		$z_html .= '<a href="javascript:void(0);" class="link_naver" data-url="'.$metatech_list_array[m_url].'">'.$green_b_str.'</a>';

		$title_str_1 = "알림받기";
		$title_str_2 = "알림받기 미션 완료하면";
		break;
	case 6:
		$green_b_str = "미션 수행하러 가기";
		$z_html .= '<a href="javascript:void(0);" class="link_naver" data-url="'.$metatech_list_array[m_url].'">'.$green_b_str.'</a>';

		$title_str_1 = "알림받기";
		$title_str_2 = "알림받기 미션 완료하면";
		break;
	case 7:
		$green_b_str = "미션 수행하러 가기";
		$z_html .= '<a href="javascript:void(0);" class="link_naver" data-url="'.$metatech_list_array[m_url].'">'.$green_b_str.'</a>';

		$title_str_1 = "상품찜";
		$title_str_2 = "상품찜하기 미션 완료하면";
		break;
}	
?>
		<!-- contents -->
        <div class="contents guess">
            <div class="contents_inner">
                <div class="top">
                    <div class="left">
                        <div class="tit"><?=$title_str_1?></div>
                        <div><?=$metatech_list_array[m_name]?></div>
                        <div><?=$title_str_2?></div>
                    </div>
                    <div class="right"><?=number_format($metatech_list_array[m_point])?>P</div>
                </div>
                <div class="cont">
                    <!-- 상세 이미지 -->
                    <img src="./img/img_cont_<?=$z+1?>.png">
                </div>
                <!--div class="notice">
                    <div class="tit">유의사항</div>
                    <ul>
                        <li>네이버 앱이 설치되어있지 않을 경우 참여에 제한이 있습니다.</li>
                        <li>미션을 참고하신 후 정확한 문구를 입력해주세요.</li>
                        <li>띄어쓰기에 유의해주세요.</li>
                        <li>미션 내용 및 이벤트는 당사 사정에 의해 사전 고지없이 변경되거나 종료될 수 있습니다.</li>
                        <li>사업자의 사정에 따라 동일한 사업자의 이벤트를 이미 참여했을 경우 해당 이벤트 참여가 불가하거나 지급된 포인트가 회수될 수 있습니다.</li>
                        <li>참여자가 몰릴 경우 이벤트가 빠르게 종료될 수 있습니다.</li>
                        <li>별도의 적립안내 알림은 발송되지 않으며, 포인트 적립내용을 통해서 확인이 가능합니다.</li>
                    </ul>
                </div-->
<?php if($metatech_list_array[m_rank] || $metatech_list_array[m_rank_img]){ ?>
                <!-- 현재 노출 위치 안내 (2026-06-16) -->
                <div class="mt-rank" style="background:#f1faf4;border:1px solid #c3eed5;border-radius:12px;padding:14px;margin:14px 0;">
                    <div style="font-weight:800;color:#0d9488;font-size:14px;margin-bottom:6px;">📍 현재 노출 위치</div>
<?php if($metatech_list_array[m_rank]){ ?>
                    <div style="font-size:16px;font-weight:700;color:#0f172a;"><?=$metatech_list_array[m_rank_area]?htmlspecialchars($metatech_list_array[m_rank_area]).' ':''?><?=htmlspecialchars($metatech_list_array[m_rank])?></div>
<?php } ?>
<?php if($metatech_list_array[m_rank_img]){ ?>
                    <div style="margin-top:8px;"><img src="<?=$nfor[path]?>/data/metatech/<?=htmlspecialchars($metatech_list_array[m_rank_img])?>" style="max-width:100%;border-radius:8px;border:1px solid #e2e8f0;"></div>
                    <div style="font-size:12px;color:#64748b;margin-top:4px;">↑ 이렇게 보이는 걸 찾아주세요!</div>
<?php } ?>
                </div>
<?php } ?>
                <div class="btn_area">
<?
echo $z_html;
?>
                </div>

<?
if($meta_op_b_chk_type == false){
?>
                <!-- 정답제출 경우01 : 텍스트 입력 -->
                <div class="answer">
                    <div class="tit"><?=$tit_str?></div>
					<form id="answer_frm">
						<input type="text" name="answer_user" id="answer_user" placeholder="<?=$placeholder_str?> 입력해 주세요">
						<input type="hidden" name="mb_id"  value="<?=$member[mb_id]?>">
						<input type="hidden" name="mb_no"  value="<?=$member[mb_no]?>">
						<input type="hidden" name="connect_idx" value="<?=$target?>">
						<input type="hidden" name="pt_point" value="<?=$metatech_list_array[m_point]?>">
						<input type="hidden" name="pt_memo" value="<?=$metatech_list_array[meta_op_a]."|".$metatech_list_array[meta_op_b]."|적립"?>">
						<button type="button" class="btn_submit" data-submit="text_submit"><?=$submit_str?> 제출하기</button>
					</form>
                </div>

<?
}
else{
?>
                <!-- 정답제출 경우02 : 파일 업로드 -->
                <div class="answer">
                    <!-- 파일 업로드 전 -->
                    <div class="filebox">
                        <span>
                            저장하기 상태의<br>
                            스크린샷을 업로드해 주세요.
                        </span>
                    </div>

                    <label for="file">
                        <div class="btn_upload">스크린샷 업로드하기</div>
                    </label>
					<form id="file_frm">
						<input type="file" name="file" id="file">
						<input type="hidden" name="mb_id"  value="<?=$member[mb_id]?>">
						<input type="hidden" name="mb_no"  value="<?=$member[mb_no]?>">
						<input type="hidden" name="connect_idx" value="<?=$target?>">
						<input type="hidden" name="pt_point" value="<?=$metatech_list_array[m_point]?>">
						<input type="hidden" name="pt_memo" value="<?=$metatech_list_array[meta_op_a]."|".$metatech_list_array[meta_op_b]."|적립"?>">

						<button type="button" class="btn_submit" data-submit="file_submit">제출하기</button>
					</form>
                </div>
<?

}
?>


            </div>
        </div>

<script>
var get_os = "<?=get_os()?>";
var m_keyword = "<?=$metatech_list_array['m_keyword']?>";
var file_upload_flag = false;

$(document).on("click",".link_naver",function(){ 

	var naver_link = "";
	var target_link = $(this).data('url');

	if(get_os == "android"){
		// ANDROID 네이버 앱 URL 스킴
		if(target_link){
		    naver_link = "naversearchapp://inappbrowser?url="+target_link+"&target=new&version=6";	
		}
		else{
		    naver_link = "naversearchapp://inappbrowser?url=http://m.naver.com&target=new&version=6";
		}
	}
	else{
		// 아이폰 도 안드로이드 적용 해봄.. 테스트 현재 못함.
		if(target_link){
		    naver_link = "naversearchapp://inappbrowser?url="+target_link+"&target=new&version=6";	
		}
		else{
		    naver_link = "naversearchapp://inappbrowser?url=http://m.naver.com&target=new&version=6";
		}
	}

    location.href = naver_link;

});


$(document).on("click",".btn_copy",function(){ 
	window.navigator.clipboard.writeText(m_keyword).then(() => {
		// 복사가 완료되면 호출된다.
		alert("키워드가 복사 되었습니다.");
		//alert(m_keyword + "\n키워드가 복사 되었습니다.");
	});
});

$(document).on("click",".btn_submit",function(){ 
	var action_flag = $(this).data("submit");
	if(action_flag == "file_submit"){
		file_frm_submit(this.form);
	}
	else{
		var answer_user = $("#answer_user").val();
		if(answer_user){
			answer_submit();
		}
		else{
			alert("정답을 입력하여 주세요");
			return false;
		}
	}
});

// 파일 미리보기
$("input:file").change(function (e){

    var file = this.files[0];

    var file = e.target.files[0];
    if(isImageFile(file)) {
      var reader = new FileReader(); 
      reader.onload = function(e) {

		$('.filebox').empty();
		$('.filebox').addClass("after");
		$('.filebox').append("<span>제출할 이미지를 확인해 주세요!</span><img src='"+e.target.result+"'>");
      }
      reader.readAsDataURL(file);
    } else {
      alert("이미지 파일만 첨부 가능합니다.");
      $("#form_file").val("");
      $("#preview").attr("src", "");
    }
});

// 업로드 파일 이미지 파일인지 확인
function isImageFile(file) {
  // 파일명에서 확장자를 가져옴
  var ext = file.name.split(".").pop().toLowerCase(); 
  return ($.inArray(ext, ["jpg", "jpeg", "gif", "png"]) === -1) ? false : true;
}


// 아작스 파일 제출
function file_frm_submit(frm) {
	if (file_upload_flag){
		alert("이미 정답 제출이 완료 되었습니다.");
		return false;
	}
	
	var fileCheck = frm.file.value;
	
	if(!fileCheck) {
		alert("업로드할 파일을 선택하세요.");
		return false;
	}
	
	var formData = new FormData(frm);
	
	$.ajax({
		url		: 'metatech_detail_fileupload_ajax.php',
		type		: 'POST',
		dataType	: 'html',
		enctype		: 'multipart/form-data',
		processData	: false,
		contentType	: false,
		data		: formData,
		async		: false,
		success		: function(response) {
			if(response){
				alert("제출이 완료되었습니다.\n담당자 검수 후 포인트가 지급됩니다.");
				var link = "./metatech_list.php";
				location.href=link;
				
				file_upload_flag = true;
			}
		}
	});
}

function answer_submit(){

	var queryString = $("#answer_frm").serialize();

	$.ajax({
		type : "post",
		url : "metatech_detail_answer_ajax.php",
		data: queryString,
		success : function(result){
			if(result.length == 1){
				alert("정답입니다.\n포인트 지급이 완료되었습니다.");
				location.href='./metatech_list.php';
			}
			else{
				alert("정답을 다시 입력해주세요.");
			}
		}
	});

}
</script>
<?
include_once $nfor[skin_path]."metatech_tail.php";
?>
