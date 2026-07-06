<?php
include_once "path.php";

$admin[cf_item_weight] = array(""=>"전체","kg" => "킬로그램(kg)", "g"=>"그램(g)");

$admin[cf_guest] = array("전체","회원 및 비회원","회원만");

$admin[cf_intro_use] = array("전체","사용","미사용");
$admin[cf_intro_type] = array("전체","제한없음","접속불가","회원만가능","성인만가능");

$admin[cf_delivery_auto_use] = array("전체","사용","미사용");

$admin[cf_cart_save_type] = array("전체","제한없음","기간설정");
$admin[cf_cart_in_same] = array("전체","기존수량에 더함","수량을 변경함");
$admin[cf_cart_in_show] = array("전체","이동선택 팝업노출","장바구니 이동");
$admin[cf_buy_with_cart] = array("전체","장바구니 상품과 함께구매","해당상품만구매");
$admin[cf_cart_in_noprice] = array("전체","장바구니에 담음","장바구니에 담지 않음");

$admin[cf_keyword_type] = array("실제 검색데이터 기준으로 출력", "관리자입력 기준으로 출력", "실제 검색데이터 + 관리자입력 혼용출력");





$table = "nfor_config";
$form = $_SERVER[PHP_SELF];

if($mode=="update"){
	demo_check();



	
	$cf_item_recent_hour = (int)$cf_item_recent_hour;
	if($cf_item_recent_hour<1){
		$cf_item_recent_hour = "24";
	}


	$cf_item_recent_max = (int)$cf_item_recent_max;
	if($cf_item_recent_max<1){
		$cf_item_recent_max = "20";
	}

	$cf_recent_max = (int)$cf_recent_max;
	if($cf_recent_max<1){
		$cf_recent_max = "10";
	}


	$cf_recent_day = (int)$cf_recent_day;
	if($cf_recent_day<1){
		$cf_recent_day = "1";
	}





	

	sql_query("update $table set
	cf_cart_save_type='$cf_cart_save_type'
	, cf_cart_save_day='$cf_cart_save_day'
	, cf_cart_in_same='$cf_cart_in_same'
	, cf_cart_in_show='$cf_cart_in_show'
	, cf_cart_in_noprice='$cf_cart_in_noprice'
	, cf_buy_with_cart='$cf_buy_with_cart'
	, cf_call_time='$cf_call_time'
	, cf_break_time='$cf_break_time'
	, cf_closed_time='$cf_closed_time'
	, cf_item_tax='$cf_item_tax'
	, cf_delivery_tax='$cf_delivery_tax'
	, cf_item_recent_hour='$cf_item_recent_hour'
	, cf_item_recent_max='$cf_item_recent_max'
	, cf_delivery_auto_use='$cf_delivery_auto_use'
	, cf_delivery_auto_day='$cf_delivery_auto_day'
	, cf_intro_use='$cf_intro_use'
	, cf_intro_type='$cf_intro_type'
	, cf_item_weight='$cf_item_weight'
	, cf_banking_limit='$cf_banking_limit'
	, cf_vbanking_limit='$cf_vbanking_limit'
	, cf_guest='$cf_guest'	
	, cf_keyword_type='$cf_keyword_type'
	, cf_keyword_hour='$cf_keyword_hour'
	, cf_kd_keyword='$cf_kd_keyword'
	, cf_kd_it_name='$cf_kd_it_name'
	, cf_kd_it_description='$cf_kd_it_description'
	, cf_page_rows='$cf_page_rows'
	, cf_ticket_send_cnt='$cf_ticket_send_cnt'
	, cf_ticket_exp_day='$cf_ticket_exp_day'
	, cf_cancle_day='$cf_cancle_day'	
	, cf_recent_max='$cf_recent_max'
	, cf_recent_day='$cf_recent_day'
	, cf_order_hide_day='$cf_order_hide_day'
	");

	$config = sql_fetch("select * from nfor_config");
	keyword_update();

	alert("정상적으로 수정되었습니다",$form);
}

include_once "head.php";
?>
 
<form name="fwrite" method="post" onsubmit="return fsubmit(this)" enctype="multipart/form-data">
<?=admin_hidden($config,"mode")?>

<?=admin_title("상담시간 설정" ,"title_tbl")?>
<table class="table cols_tbl " >
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>상담시간</th>
	<td><?=admin_text($config,"cf_call_time","width-300p")?></td>
</tr>
<tr>
	<th>점심시간</th>
	<td><?=admin_text($config,"cf_break_time","width-300p")?></td>
</tr>
<tr>
	<th>휴무안내</th>
	<td><?=admin_text($config,"cf_closed_time","width-400p")?></td>
</tr>
</table>



<div style="display:none">

<?=admin_title("부가세 설정" ,"title_tbl")?>
<table class="table cols_tbl " >
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>상품 부가세 세율</th>
	<td><div class="form-inline"><?=admin_text($config,"cf_item_tax","width-50p")?>%</div><?=admin_help("※ 선택된 세율은 상품>상품관리>상품등록 에서 상품 등록 시 기본 세율이 됩니다.")?></td>
</tr>
<tr>
	<th>배송비 부가세 세율</th>
	<td><div class="form-inline"><?=admin_text($config,"cf_delivery_tax","width-50p")?>%</div><?=admin_help("※ 선택된 세율은 기본설정>배송정책>배송비조건 등록 에서 배송비조건 등록 시 기본 세율이 됩니다.")?></td>
</tr>
</table>

<?=admin_title("단위 설정" ,"title_tbl")?>
<table class="table cols_tbl " >
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>무게 단위</th>
	<td>	
	<?=admin_radio($config,"cf_item_weight")?>
	</td>
</tr>
</table>


</div>


<?=admin_title("최근 검색어 설정" ,"title_tbl")?>
<table class="table cols_tbl" >
<colgroup>
	<col class="width-150p ">
	<col >
</colgroup>
<tr>
	<th>저장 키워드수</th>
	<td>
		<div class="form-inline">최근 <?=admin_text($config,"cf_recent_max","width-50p")?>개 키워드까지 저장하도록 허용</div>
	</td>
</tr>
<tr>
	<th>저장 기간</th>
	<td>검색일로부터 <div class="form-inline"><?=admin_text($config,"cf_recent_day","width-50p")?>일 까지 최근검색어를 저장함</div><?=admin_help("※ 리눅스 크론(작업스케쥴러)에 의해 관리됩니다")?></td>
</tr>
<tr>
	<th>검색시 자동완성DB</th>
	<td>
	<?=admin_checkbox($checkbox,"cf_kd_keyword","",$config[cf_kd_keyword]?"checked=\"checked\"":"","키워드")?>
	<?=admin_checkbox($checkbox,"cf_kd_it_name","",$config[cf_kd_it_name]?"checked=\"checked\"":"","상품명")?>
	<?=admin_checkbox($checkbox,"cf_kd_it_description","",$config[cf_kd_it_description]?"checked=\"checked\"":"","상품설명")?>
	</td>
</tr>
</table>






<?=admin_title("인기검색어 설정" ,"title_tbl")?>
<table class="table cols_tbl" >
<colgroup>
	<col class="width-150p ">
	<col >
</colgroup>
<tr>
	<th>운영형태</th>
	<td><?=admin_radio($config,"cf_keyword_type","","","0")?> <?=admin_help("※ 리눅스 크론(작업스케쥴러)에 의해 관리됩니다")?></td>
</tr>
<tr>
	<th>이전순위 기준시간</th>
	<td><div class="form-inline"><?=admin_text($config,"cf_keyword_hour","width-50p")?>시간</div></td>
</tr>
</table>





<?=admin_title("최근 본 상품 설정" ,"title_tbl")?>
<table class="table cols_tbl " >
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>저장 시간</th>
	<td><div class="form-inline">최근 <?=admin_text($config,"cf_item_recent_hour","width-50p")?></div>시간까지 최근 본 상품을 저장함 <?=admin_help("※ 리눅스 크론(작업스케쥴러)에 의해 관리됩니다")?></td>
</tr>
<tr>
	<th>저장 상품수</th>
	<td><div class="form-inline">최근 본 상품 <?=admin_text($config,"cf_item_recent_max","width-50p")?></div>개 상품까지 저장하도록 허용</td>
</tr>
</table>

<?=admin_title("상품 구매 권한" ,"title_tbl")?>
<table class="table cols_tbl " >
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>상품 구매 권한</th>
	<td><?=admin_radio($config,"cf_guest")?></td>
</tr>
</table>





<?=admin_title("티켓 설정" ,"title_tbl")?>
<table class="table cols_tbl " >
<colgroup>
	<col class="width-150p ">
	<col >
</colgroup>
<tr>
	<th>티켓 발송 횟수 제한</th>
	<td><div class="form-inline"><?=admin_text($config,"cf_ticket_send_cnt","width-50p")?>회까지 문자를 발송하도록 설정</div></td>
</tr>
<tr>
	<th>티켓만료 문자사전발송</th>
	<td>티켓만료 <div class="form-inline"><?=admin_text($config,"cf_ticket_exp_day","width-50p")?>일전 티켓만료 문자를 사전에 발송하도록 설정</div></td>
</tr>
</table>





<?=admin_title("인트로페이지 사용 설정" ,"title_tbl")?>
<table class="table cols_tbl " >
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>사용여부</th>
	<td><?=admin_radio($config,"cf_intro_use")?></td>
</tr>
<tr id="cf_intro_use_1">
	<th>서브페이지 접근권한</th>
	<td><?=admin_radio($config,"cf_intro_type")?></td>
</tr>
</table>

<?=admin_title("주문 설정" ,"title_tbl")?>
<table class="table cols_tbl " >
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>무통장입금 입금기한</th>
	<td>
		 주문일로부터 <div class="form-inline"><?=admin_text($config,"cf_banking_limit","width-50p")?></div>일이 지나면 주문서 자동취소 <?=admin_help("※ 리눅스 크론(작업스케쥴러)에 의해 관리됩니다")?>
	</td>
</tr>
<tr>
	<th>가상계좌 입금기한</th>
	<td>
		 주문일로부터 <div class="form-inline"><?=admin_text($config,"cf_vbanking_limit","width-50p")?></div>일이 지나면 주문서 자동취소 <?=admin_help("※ 리눅스 크론(작업스케쥴러)에 의해 관리됩니다")?>
	</td>
</tr>

<tr>
	<th>주문숨김 버튼</th>
	<td>주문일로부터 <div class="form-inline"><?=admin_text($config,"cf_order_hide_day","width-50p")?>일 동안 주문 숨김버튼 활성화</div> <?=admin_help("※ 주문상세 페이지에 주문숨김 버튼이 노출됩니다")?></td>
</tr>

<tr>
	<th>주문취소 가능기간</th>
	<td><div class="form-inline">결제일로부터 <?=admin_text($config,"cf_cancle_day","width-50p")?>일 까지 주문취소가 가능하도록 설정합니다</div> <?=admin_help("※ 주문목록/주문상세 페이지에서 설정기간이 지나면 주문취소버튼이 비활성화됩니다")?></td>
</tr>




<tr>
	<th>배송중 기간설정</th>
	<td>
	<?=admin_radio($config,"cf_delivery_auto_use")?> <?=admin_help("※ 별도의 택배사 API를 사용하지 않기 때문에 송장번호 입력일을 기준으로 설정일동안 배송상태를 배송중으로 임의 표시합니다")?>
	<div id="cf_delivery_auto_use_1"  class="martop5">
		 <div class="form-inline">송장번호 입력일로부터 <?=admin_text($config,"cf_delivery_auto_day","width-50p")?>일 까지 배송중인 상태로 표시되며 이후에는 발송완료로 표시됩니다</div> 
	</div>	
	<div id="cf_delivery_auto_use_2"  class="martop5">
	배송중 기간설정 미사용시 배송중 표기 없이 바로 발송완료로 표시됩니다
	</div>
	</td>
</tr>
</table>

<?=admin_title("장바구니 설정" ,"title_tbl")?>
<table class="table cols_tbl" >
<colgroup>
	<col class="width-150p ">
	<col >
</colgroup>
<tr>
	<th>상품보관기간</th>
	<td>	
	<div class="form-inline"><?=admin_radio($config,"cf_cart_save_type")?></div>
	<div id="cf_cart_save_type_2" class="martop5">
		<div class="form-inline">장바구니에 담긴 시점부터 <?=admin_text($config,"cf_cart_save_day","width-50p")?> 일 까지 보관 <?=admin_help("※ 리눅스 크론(작업스케쥴러)에 의해 관리됩니다")?></div>
	</div>	
	</td>
</tr>
<tr>
	<th>장바구니에 담은후 노출</th>
	<td><?=admin_radio($config,"cf_cart_in_show")?></td>
</tr>
<tr>
	<th>구매하기 클릭 설정</th>
	<td><?=admin_radio($config,"cf_buy_with_cart")?></td>
</tr>
<tr>
	<th>가격이 0원인 상품을 담을 경우</th>
	<td><?=admin_radio($config,"cf_cart_in_noprice")?></td>
</tr>








<tr>
	<th>장바구니에 같은 상품 담을 경우</th>
	<td><?=admin_radio($config,"cf_cart_in_same")?></td>
</tr>
</table>










<?=admin_title("기타 설정" ,"title_tbl")?>
<table class="table cols_tbl " >
<colgroup>
	<col class="width-150p ">
	<col >
</colgroup>
<tr>
	<th>한줄당 표시할 게시글수</th>
	<td><div class="form-inline"><?=admin_text($config,"cf_page_rows","width-50p")?>개</div> <?=admin_help("※ 사이트 전반의 목록에 출력되는 페이지당 기본 게시글수 지정을 의미합니다")?></td>
</tr>
</table>






<div class="bottom_btn">
	<div class="form-inline">
		<?=admin_submit("fsubmit_btn", "설정하기", "btn btn-lg btn-red")?>
	</div>
</div>

</form>

<script type="text/javascript">
<!--
function fsubmit(f){

	f.action = "<?=$form?>";
	return true;	    
}	

$(document).ready(function (){
	nfor_radio_click("cf_intro_use","<?=$config[cf_intro_use]?>");
	nfor_radio_click("cf_delivery_auto_use","<?=$config[cf_delivery_auto_use]?>");
	nfor_radio_click("cf_cart_save_type","<?=$config[cf_cart_save_type]?>");
});

$(document).on("click", "input[name=cf_intro_use]", function(){
	nfor_radio_click("cf_intro_use",this.value);
});

$(document).on("click", "input[name=cf_delivery_auto_use]", function(){
	nfor_radio_click("cf_delivery_auto_use",this.value);
});

$(document).on("click", "input[name=cf_cart_save_type]", function(){
	nfor_radio_click("cf_cart_save_type",this.value);
});
//-->
</script>

<?php
include_once "tail.php";
?>