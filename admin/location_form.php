<?php
include_once "path.php";

$admin[lo_use] = array("전체","사용","미사용");

if(!is_array($lo_use)){
	$qstr .= "&lo_use=$lo_use";
}

$qstr .= "&item_location_type=$item_location_type";

$form = $_SERVER[PHP_SELF];
$list = str_replace("form","list",$form);
$table = "nfor_location";
$id = "lo_id";

$id_value = $$id;

if($id_value){
	$write = sql_fetch("select * from $table where $id='{$id_value}'");
}

if($mode=="insert" or $mode=="update"){
	demo_check();

	$add_sql = "";
	$where_sql = "";

	if($mode=="insert"){
		$msg = "정상적으로 등록 되었습니다";
		$move = "$list?{$qstr}";
		$add_sql .= ", lo_insert_id='$member[mb_no]', lo_insert_datetime=NOW()";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$move = "$form?{$qstr}&{$id}={$id_value}";
		$where_sql = "  where $id='{$id_value}'";
		$add_sql .= ", lo_update_id='$member[mb_no]', lo_update_datetime=NOW()";
	} else{

	}
	$common_sql = " $table set lo_name='$lo_name', lo_use='$lo_use', lo_lat='$lo_lat', lo_lng='$lo_lng', lo_rank='$lo_rank'";

	sql_query("$mode $common_sql $add_sql $where_sql");
	alert($msg,$move);
}

include_once "head.php";
?>

<style>
#map { height:500px; width:100%; margin-top:10px; }
</style>

<form name="fwrite" method="post" onsubmit="return fsubmit(this)">
<?=admin_hidden($write,"mode")?>
<?=admin_hidden($write,$id)?>
<?=admin_hidden($write,"lo_lat")?>
<?=admin_hidden($write,"lo_lng")?>

<table class="table cols_tbl margin0">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>지역명</th> 
	<td><?=admin_text($write,"lo_name")?></td>
</tr>
<tr>
	<th>노출여부</th> 
	<td>
	<?php
	if(!$write[lo_use]) $write[lo_use] = "1";
	?>
	<?=admin_radio($write,"lo_use")?>
	</td>
</tr>
<tr>
	<th>지도</th>
	<td>
	
	<div class="form-inline">
		<?=admin_text($write,"xy_address","width-400p","placeholder=\"주소로 지도 검색 : 입력 예) 인천 서구 솔빛로 93\"")?>
		<?=admin_button("xy_search","검색","btn-gray btn-sm")?>
	</div>

	<div id="map"></div>

	</td>
</tr>

<tr>
	<th>정렬</th> 
	<td><?=admin_text($write,"lo_rank","width-50p")?></td>
</tr>


</table>

<div class="bottom_btn">
	<div class="form-inline">
		<?=admin_submit("fsubmit_btn", $write[$id]?"수정하기":"등록하기", "btn btn-lg btn-red")?>
		<?=admin_a("list", "목록보기", "btn btn-lg btn-black", "", $list."?".$qstr)?>
	</div>
</div>

</form>

<SCRIPT LANGUAGE="JavaScript">
<!--
function maxp_search(){

	// 주소-좌표 변환 객체를 생성합니다
	var geocoder = new daum.maps.services.Geocoder();

	// 주소로 좌표를 검색합니다
	geocoder.addressSearch($('#xy_address').val(), function(result, status) {

		// 정상적으로 검색이 완료됐으면 
		 if (status === daum.maps.services.Status.OK) {

			 $("#lo_lat").val(result[0].y);
			 $("#lo_lng").val(result[0].x);

			var coords = new daum.maps.LatLng(result[0].y, result[0].x);
			marker.setPosition(coords);  
			map.setCenter(coords);


		} else{
			alert("검색결과가 없습니다");
		}
	});    

}

$(document).on("keydown","#xy_address",function(e){ 
	if(e.keyCode == 13){
		maxp_search();
		return false;
	}
});


$(document).on("click", "#xy_search", function(){
	maxp_search();
});


var mapContainer = document.getElementById('map'),
	mapOption = { 
		center: new daum.maps.LatLng(<?=$write[lo_lat]?$write[lo_lat]:$nfor[default_lat]?>, <?=$write[lo_lng]?$write[lo_lng]:$nfor[default_lng]?>), // 지도의 중심좌표
		level: 6
	};

var map = new daum.maps.Map(mapContainer, mapOption);

var marker = new daum.maps.Marker({ 
	position: map.getCenter() 
}); 

marker.setMap(map);

daum.maps.event.addListener(map, 'click', function(mouseEvent) {        
	var latlng = mouseEvent.latLng; 
	marker.setPosition(latlng);    
	$("#lo_lat").val(latlng.getLat());
	$("#lo_lng").val(latlng.getLng());
});

function fsubmit(f){
	if(!$('#lo_name').val()){
		alert("사용처명을 입력해주세요");
		$('#lo_name').focus();
        return false;
	}
	f.action = "<?=$form?>";
	return true;	    
}	
//-->
</SCRIPT>

<?php
include_once "tail.php";
?>