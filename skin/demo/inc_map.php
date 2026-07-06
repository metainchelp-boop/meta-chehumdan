<!-- 상품 지도 -->
<?
$owner = sql_fetch("select * from nfor_member where mb_no='$item[supply_no]'");

if($item[it_map_use] and $item[it_x_point] and $item[it_y_point]){ 
	$navermap_key = sql_fetch("select * from nfor_naver_map where wr_domain='$nfor[host_domain]'");	
?>

<script type="text/javascript">
try {document.execCommand('BackgroundImageCache', false, true);} catch(e) {}
</script>
<script type="text/javascript" src="http://openapi.map.naver.com/openapi/naverMap.naver?ver=2.0&key=<?=$navermap_key[wr_key]?>"></script>
<script type="text/javascript">

var oSize = new nhn.api.map.Size(28, 37);
var oOffset = new nhn.api.map.Size(14, 37);
var oIcon = new nhn.api.map.Icon('img/pin_spot2.png', oSize, oOffset);

$(document).ready(function($){
	var oPoint = new nhn.api.map.LatLng(<?=$item[it_y_point]?>, <?=$item[it_x_point]?>);
	nhn.api.map.setDefaultPoint('LatLng');
	oMap = new nhn.api.map.Map('nfor_map', {
			point : oPoint,
			zoom : 11,
			enableWheelZoom : true,
			enableDragPan : true,
			enableDblClickZoom : false,
			mapMode : 0,
			activateTrafficMap : false,
			activateBicycleMap : false,
			minMaxLevel : [ 5, 12 ],
			size : new nhn.api.map.Size(450, 300)
	});

	var mapZoom = new nhn.api.map.ZoomControl();
	mapZoom.setPosition({left:10, top:10});
	oMap.addControl(mapZoom);

	mapTypeChangeButton = new nhn.api.map.MapTypeBtn();
	mapTypeChangeButton.setPosition({top:10, left:50});
	oMap.addControl(mapTypeChangeButton);

	var aPoint = new nhn.api.map.LatLng(<?=$item[it_y_point]?>,<?=$item[it_x_point]?>);
	oMap.setCenter(aPoint);
	oMap.setLevel(10);
	oMap.clearOverlay();

	var oMarker = new nhn.api.map.Marker(oIcon, { title : '마커' });
	oMarker.setPoint(aPoint);
	oMap.addOverlay(oMarker);
})
</script>




<div style="background-color:#fafafa; border:solid 1px #dcdcdc; padding:20px; margin:10px;">

	<div id="nfor_map" style="border:1px solid #dcdcdc; width:450px; height:300px;"></div>
	<div>
	업체명 : <?=$owner[supply_name]?>
	매장주소 : <?=$owner[mb_zip1]?>-<?=$owner[mb_zip2]?> <?=$owner[mb_addr1]?> <?=$owner[mb_addr2]?>
	전화번호 : <?=$owner[mb_tel]?>
	</div>

</div>




	






<? } ?>
<!-- // 상품지도 -->