<style>
#map { width:100%; height:250px; border:solid 1px #dcdcdc; margin:10px 0px; }

.map_info li { font-size:14px; border-bottom:dashed 1px #efefef; }
.map_info li span:nth-child(1) { width:20%; color:#989898; }
.map_info li span { width:50%; display:inline-block; padding:10px; letter-spacing:-.07em; }
.map_btns { overflow:hidden; text-align:center; border:1px solid #ececec; background-color:#f5f5f5; box-sizing:border-box; margin-top:15px; }
.map_btns a { position:relative; font-size:13px; display:inline-block; padding-top:10px; padding-bottom:10px; width:100%; color:#8c8c8c; float:left; width:33.3%; text-align:center; letter-spacing:-.07em; }
.map_btns a img { vertical-align:-4px; margin-right:5px; }
.map_btns a:nth-child(1):before { content: ''; width:0; height:0; position:static; left:0; top:0; margin-top:0; background:0 0; }
.map_btns a:before { position:absolute; top:10px; left:0; width:1px; height:14px; background-color:#dcdcdc; content: ''; }
</style>

<? if(count($admin[lo_id])){ ?>
<?=admin_select($admin,"lo_id","","","1")?>
<? } ?>

<div id="map"></div>

<ul class="map_info">
	<li><span>업체명</span><span id="lo_name"><?=$location[lo_name]?></span></li>
	<li><span>주소 </span><span id="lo_address"><?=$location[lo_address]?></span></li>
	<li><span>전화번호</span><span id="lo_tel"><?=$location[lo_tel]?></span></li>
</ul>

<div class="map_btns">
	<a href="tel:<?=$location[lo_tel]?>" id="lo_tel_href"><img src="<?=$nfor[skin_path]?>img/map_call.png">전화걸기</a>
	<a href="https://m.map.naver.com/map.nhn?lng=<?=$location[lo_lng]?>&lat=<?=$location[lo_lat]?>&dlevel=11&title=<?=$location[lo_name]?>&isShowPolygon=false&rcode=&isDetailAddress=true" id="lo_bigmap_href" target="_blank"><img src="<?=$nfor[skin_path]?>img/map_map.png">지도보기</a>
	<a href="https://m.map.naver.com/route.nhn?ename=<?=$location[lo_name]?>&ex=<?=$location[lo_lat]?>&ey=<?=$location[lo_lng]?>" id="lo_navi_href" target="_blank"><img src="<?=$nfor[skin_path]?>img/map_navi.png">길찾기</a>
</div>

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


/*
var mapTypeControl = new daum.maps.MapTypeControl();
map.addControl(mapTypeControl, daum.maps.ControlPosition.TOPRIGHT);

var zoomControl = new daum.maps.ZoomControl();
map.addControl(zoomControl, daum.maps.ControlPosition.RIGHT);
*/


marker.setMap(map);

// 드레그막기
//map.setDraggable(false); 

</script>