<?php
// pc에서는 사용 하지 않고 모바일에서만 사용하므로 디버그나 개발 할때만 사용 하면 됩니다..
//goto_url($nfor['path']);
if($is_member){
	$member_name_str = "안녕하세요, <strong>".$member[mb_name]."</strong>님";
	//$member_name_str = "안녕하세요, <strong>".$member[mb_nick]."</strong>님";
	//$member_name_str = "안녕하세요, <strong>".$member[mb_cp_name]."</strong>님";
}
else{
//	$member_name_str = "미가입자입니다.";
//	alert("로그인 페이지로 이동합니다..",$nfor['path']."/login.php");
	goto_url("./login.php");
}

include_once $nfor[skin_path]."metatech_head.php";
$meta_op_a_array = $nfor[meta_op_a];
$meta_op_b_array = array_merge(array("전체"),$nfor[meta_op_b]);

$canhavepoint = cal_metatech_canhavepoint($member[mb_id]);

//print_r2($member);

?>
        <!-- contents -->
        <div class="contents main">
            <div class="contents_inner">
                <div class="user_info">
                    <div class="user_name"><?=$member_name_str?></div>
                    <div class="user_point">
                        <span>
                            <div>나의 포인트</div>
                            <div class="num"><?=number_format($member[mb_point]);?>P</div>
                        </span>
                        <a href="javascript:void(0);" id="point_list">포인트 내역</a>
                    </div>
                    <div class="earnable_point">
                        <div>지금 획득할 수 있는 포인트</div>
                        <div class="num"><?=number_format($canhavepoint);?>P</div>
                    </div>
                </div>
                <div class="category">
                    <!-- 드롭다운시 ul에 class="down" 추가 -->
                    <ul class="">
<?
for($i=0;$i<count($meta_op_b_array);$i++){
	if($i == 0){
		$li_class_active = " class='active'";
	}
	else{
		$li_class_active = "";
	}
?>
						<li <?=$li_class_active?>><button type="button" class="meta_op_b"><?=$meta_op_b_array[$i]?></button></li-->
<?
}
?>
                    </ul>
                    <div class="btn_area">
                        <button type="button" class="btn_down"></button>
                    </div>
                    <div class="filter">
                        <div class="left">
                            <input type="checkbox" id="availability">
                            <label for="availability">참여 가능한 이벤트만 보기</label>
                        </div>
                        <button type="button" id="m_sort" class="btn_filter a active">신규이벤트 순</button>
                    </div>
                </div>
                <div class="event_list">
                    <ul id="event_li">
<?
$metatech_list_array = select_table_nfor_metatech_alllist($member[mb_id]);

for($i=0;$i<count($metatech_list_array);$i++){

	// 아이디로 찾기
	$do_have = find_table_nfor_metatech_user_twofiled($member[mb_id],$metatech_list_array[$i][idx]);

	$a_href = "./metatech_detail.php?target=".$metatech_list_array[$i][idx];
	if($is_member){
		$class_bottom_complete_html = "";
		if($do_have == 0){
			$a_href = "./metatech_detail.php?target=".$metatech_list_array[$i][idx];	
			$class_bottom_complete_html = "<div class='bottom'>".number_format($metatech_list_array[$i][m_point])."P</div>";
		}
		else{
			$a_href = "javascript:do_have_blocking();";			
			$class_bottom_complete_html = "<div class='bottom complete'>참여완료</div>";
		}
	}
	else{
		$a_href = "javascript:blocking();";
	}

	$keword_url_str = "";

	if($metatech_list_array[$i][m_keyword]){
		$keword_url_str = $metatech_list_array[$i][m_keyword];
	}
	else{
		$keword_url_str = $metatech_list_array[$i][m_url];	
	}


	$z = "";

	for($ii=0;$ii<count($nfor[meta_op_b]);$ii++){
		if($nfor[meta_op_b][$ii] == $metatech_list_array[$i][meta_op_b]){
			$z = $ii;
			break;
		}
	}

	$m_sub_str = "";

	switch ($z) {
		case 0:
			$m_sub_str = "정답을 맞추면";
			break;
		case 1:
			$m_sub_str = "정답을 맞추면";
			break;
		case 2:
			$m_sub_str = "공유하기 하면";
			break;
		case 3:
			$m_sub_str = "공유하기 하면";
			break;
		case 4:
			$m_sub_str = "저장하면";
			break;
		case 5:
			$m_sub_str = "알림받기 하면";
			break;
		case 6:
			$m_sub_str = "알림받기 하면";
			break;
		case 7:
			$m_sub_str = "상품찜 하면";
			break;
	}	

?>
                        <li data-point="<?=$metatech_list_array[$i][m_point]?>" data-date="<?=strtotime($metatech_list_array[$i][datetime])?>" data-metaop="<?=$metatech_list_array[$i][meta_op_b]?>" data-do_have="<?=$do_have?>">
                            <a href="<?=$a_href?>">
                                <div class="left_area">
                                    <div class="top"><?=$metatech_list_array[$i][m_name]?></div>
                                    <div class="center"><?=$m_sub_str?></div>
                                    <?=$class_bottom_complete_html?>
                                    <!-- 참여완료시 class="complete" -->
                                </div>
<?
	if($meta_op_a_array[0] == $metatech_list_array[$i][meta_op_a]){
		$p_s_class = "basic";
	}
	else{
		$p_s_class = "store";	
	}
?>
                                <!-- 핀 이미지일때 class="basic" -->
                                <!-- 스토어 이미지일떄 class="store" -->
                                <div class="<?=$p_s_class?>"></div>
                            </a>
                        </li>
<?
}
?>
                    </ul>
                </div>
            </div>
        </div>

<script>
var now_do_have = false;
var now_meta_op_b = "<?=$meta_op_b_array[0]?>";

$(document).on("click","#availability",function(){ 
	now_do_have = $(this).is(":checked");
	navi_control(now_do_have,now_meta_op_b);
});

$(document).on("click",".meta_op_b",function(){ 
	now_meta_op_b = $(this).text();
	navi_control(now_do_have,now_meta_op_b);
});

function navi_control(do_have,meta_op_b){
   $("#event_li >li").hide();

	if(meta_op_b == "전체"){
	   $("#event_li >li").each(function( index, element ) {
			if(do_have == true){
				if($(this).data('do_have') == 0){
					$(this).show();
				}
			}
			else{
				$(this).show();			
			}
	   });	
	}
	else{
	   $("#event_li >li").each(function( index, element ) {
		 //console.log($(this).data('metaop'));
		 if($(this).data('metaop') == meta_op_b){
			if(do_have == true){
				if($(this).data('do_have') == 0){
					$(this).show();
				}
			}
			else{
				$(this).show();			
			}
		 }
	   });		
	}	
}

$(document).on("click","#m_sort",function(){ 
	var target = $(this).text();
	var target_sort = "";
	//포인트 높은순
	if(target == "포인트 높은순"){
		target_sort = "point";
	}
	// 날짜 순
	else{
		target_sort = "date";		
	}
	listSort($(this), target_sort);
});

$(document).on("click","#point_list",function(){ 
	var link = "./point_list.php";
	location.href = link;
});

function listSort($targetObj, target){
	$("#event_li").html(
		$("#event_li li").sort(function(a, b){
			return $(b).data(target) - $(a).data(target);
		})
	);
}

function blocking(){
	alert("회원만 참여 할수 있습니다.");
}

function do_have_blocking(){
	alert("이미 참여 하였습니다.");
}

</script>
    <script>
        // category
        const categoryItems = document.querySelectorAll(".category ul li");
        categoryItems.forEach((item)=>{
            item.addEventListener('click',()=>{
                categoryItems.forEach((e)=>{
                //하나만 선택되도록 기존의 효과를 지워준다.
                    e.classList.remove('active');
                });
            // 선택한 그 아이만 효과를 추가해준다.
                item.classList.add('active');
            });
        });

        // accordion menu
        const btnDown = document.querySelector(".category .btn_down");
        const list = document.querySelector(".category ul");
        btnDown.addEventListener("click", () => {
            btnDown.classList.toggle("active");
            list.classList.toggle("down");
        });

        // btn_filter 
        const btnFilter = document.querySelector('.category .filter .btn_filter')
        btnFilter.addEventListener("click", () => {
            if(btnFilter.innerText == '신규이벤트 순' ){ //버튼의 텍스트값 확인
            btnFilter.innerText = '포인트 높은순'  // 텍스트를 unfollow로 변경
        } else{  // 반대일 경우 다시 변경
            btnFilter.innerText = '신규이벤트 순'
        }
        });
        
    </script>
 <?
include_once $nfor[skin_path]."metatech_tail.php";
?>