<style>
#map { width:100%; height:400px; border:solid 1px #dcdcdc; margin:20px 0px; }
.locat select {padding: 6px 5px 5px; height:30px; border: 1px solid #d0d0d0; color: #828284; vertical-align: middle;  border: 1px solid #d0d0d0;  color: #828284;  background: url(/skin/demo/img/select_background.png) no-repeat 100% 50%; font-size: 12px; -webkit-appearance: none; -moz-appearance: none;  appearance: none; width:100%;}
.locat select { appearance: none; -webkit-appearance: none;}
.locat select::-ms-expand { display:none; }
</style>


<? if(count($admin[lo_id])){ ?>
<div class="locat">
<?=admin_select($admin,"lo_id","","","1")?>
</div>
<? } ?>

<div id="map"></div>

<table cellpadding="0" cellspacing="0" border="0" class="tb_form">
<colgroup>
	<col width="200">
	<col width="*">
</colgroup>
<tbody>
<tr>
	<th>업체명</th>
	<td><span id="lo_name"><?=$location[lo_name]?></span></td>
</tr>
<tr>
	<th>주소</th>
	<td><span id="lo_address"><?=$location[lo_address]?></span></td>
</tr>
<tr>
	<th>전화번호</th>
	<td><span id="lo_tel"><?=$location[lo_tel]?></span></td>
</tr>
</tbody>
</table>

<script>
var lo_lat = "<?=$location[lo_lat]?>";
var lo_lng = "<?=$location[lo_lng]?>";

$(document).on("change", "#lo_id", function(){
	var lo_id = $(this).val();
	$.ajax({
		type: "post",
		data : "mode=location&lo_id="+lo_id,
		url: "item.php",
		success: function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				$("#lo_name").html(json["lo_name"]);
				$("#lo_address").html(json["lo_address"]);
				$("#lo_tel").html(json["lo_tel"]);

				$("#lo_tel_href").attr("href","tel:"+json["lo_tel"]);
				$("#lo_navi_href").attr("href","https://m.map.naver.com/route.nhn?ename="+json["lo_name"]+"&ex="+json["lo_lat"]+"&ey="+json["lo_lng"]);
				$("#lo_bigmap_href").attr("href","https://m.map.naver.com/map.nhn?lng="+json["lo_lng"]+"&lat="+json["lo_lat"]+"&dlevel=11&title="+json["lo_name"]+"&isShowPolygon=false&rcode=&isDetailAddress=true");

				var moveLatLon = new daum.maps.LatLng(json["lo_lat"], json["lo_lng"]);
				map.panTo(moveLatLon);        
				marker.setMap(null);				
				markerPosition  = new daum.maps.LatLng(json["lo_lat"], json["lo_lng"]);
				marker = new daum.maps.Marker({
					position: markerPosition
				});
				marker.setMap(map);
			} else{
				alert(json["msg"]);
			}
		}
	});

});

var mapContainer = document.getElementById('map'), 
    mapOption = { 
        center: new daum.maps.LatLng(lo_lat, lo_lng),
        level: 3
    };

var map = new daum.maps.Map(mapContainer, mapOption);

var markerPosition  = new daum.maps.LatLng(lo_lat, lo_lng); 

var marker = new daum.maps.Marker({
    position: markerPosition
});

var mapTypeControl = new daum.maps.MapTypeControl();
map.addControl(mapTypeControl, daum.maps.ControlPosition.TOPRIGHT);

var zoomControl = new daum.maps.ZoomControl();
map.addControl(zoomControl, daum.maps.ControlPosition.RIGHT);

marker.setMap(map);
</script>