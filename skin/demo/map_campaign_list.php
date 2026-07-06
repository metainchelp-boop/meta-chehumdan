<?php
include_once $nfor[skin_path]."head.php";		
?>

<style>
.fon16{font-size:16px;}
.map_area{width:100%;min-height:700px;overflow:hidden;}
.map_area .map_zone{float:right; position:fixed;position:relative; top:auto; width:calc(100% - 510px); margin-top:0px; box-sizing:border-box; -webkit-box-sizing:border-box; }
.map_area .map_zone .map_ser{padding:20px 30px;; background-color:#FAFAFA; border-bottom:1px solid #d0d0d0;}
.map_area .map_zone .map_ser select {padding: 6px 5px 5px; height:35px; border: 1px solid #d0d0d0; color: #828284; vertical-align: middle;  border: 1px solid #d0d0d0;  color: #828284;  background: url(<?=$nfor[skin_path]?>img/select_background.png) no-repeat 100% 50%; font-size: 13px; -webkit-appearance: none; -moz-appearance: none;  appearance: none; background-color:#FFF;}
.map_area .map_zone .map_ser select { appearance: none; -webkit-appearance: none;}
.map_area .map_zone .map_ser select::-ms-expand { display:none; }
.map_area .map_zone .map_ser label{font-size:14px; color: #828284;  margin-left:10px;}
.map_area .map_zone .map_ser input[type='checkbox']{margin-right:5px;}
.map_area .map_zone .map{ height:650px;  clear:both; }

.map_area .map_list{float:left;width:510px; max-width:510px; background-color: #f6f6f6; position:relative;margin-left:-1px; border-right:1px solid #d0d0d0;box-sizing:border-box; -webkit-box-sizing:border-box; }
.map_area .map_list .cate .map_ser_de{border-bottom:1px solid #d0d0d0; padding:20px; font-size:12px; position:relative;}
.map_area .map_list .cate .map_ser_de .serch_zone{width:350px;;height:30px; position:relative;}
.map_area .map_list .cate .map_ser_de .serch_zone .btn{ cursor:pointer; display:block; position:absolute; width:50px;height:30px; line-height:30px; font-size:12px; color:#fff; text-align:center; right:0px; top:0px; background-color:#666; border:solid 1px #666;}
.map_area .map_list .cate .map_ser_de .serch_zone input{display:block;  width:350px; padding:0px 10px ; height:30px; line-height:30px; font-size:12px;  border: 1px solid #d0d0d0; background-color:#FAFAFA; color:#666;box-sizing: border-box;}
.map_area .map_list .cate .map_ser_de .spot{ cursor:pointer; position:absolute; right:20px; line-height:28px;color:#e75540; border:solid 1px #e75540; padding: 0px 10px 0px 5px;}
.map_area .map_list .cate .map_ser_de .spot img{vertical-align:-5px; margin-right:3px;}
.map_area .map_list .cate .map_ser_de span{font-size:16px; line-height:30px;}

.map_area .map_list .cate{width:100%; box-sizing: border-box; background-color:#FFF;}
.map_area .map_list .cate ul{overflow:hidden; border-left:1px solid #d0d0d0;  position:absolute; right:20px; top:20px;}
.map_area .map_list .cate ul li{float:left;color:#FFF; width:55px;text-align:center;margin-left:-1px; }
.map_area .map_list .cate ul li a{color:#666; width:100%; font-size:11px; display:inline-block; margin-left:-1px; line-height:30px; border-right:1px solid #d0d0d0;border-top:1px solid #d0d0d0;border-bottom:1px solid #d0d0d0;}
.map_area .map_list .cate ul li a:hover{background-color:#666; color:#fff}
.map_area .map_list .cate .on{background-color:#666; color:#fff}

.map_area .map_list .couponlist { width:100%; height:576px; box-sizing:border-box; overflow:hidden;overflow-y:auto; }
.map_area .map_list .couponlist ul { width:100%; }
.map_area .map_list .couponlist ul li { overflow:hidden;background-color:#FFF; border-bottom:solid 1px #DCDCDC; height:190px;}
.map_area .map_list .couponlist .pro_img { float:left; padding:20px; box-sizing:border-box; } 
.map_area .map_list .couponlist .pro_img img { width:150px; height:150px;border-radius:3px; }
.map_area .map_list .couponlist .subject { float:left; width:290px; margin-left:10px; padding:5px 0px; }

.map_area .map_list .couponlist .subject .cp_subject{display:block; line-height:25px; font-size:18px; margin:25px 0px 5px; color:#000;}
.map_area .map_list .couponlist .subject .cp_description{display:block; line-height:16px; font-size:13px;color:#333; height:16px; margin-bottom:5px;   text-overflow: ellipsis;overflow: hidden; white-space: nowrap;}
.map_area .map_list .couponlist .subject .cp_addr1{display:block; line-height:16px; font-size:13px;color:#888; margin-bottom:5px;}

.map_area .map_list .couponlist .subject .btn{width:260px; margin-top:15px; height:30px; line-height:30px; border:solid 1px #dc1636; background-color:#ec3c40; color:#FFFFFF; font-size:12px; display:inline-block; text-align:center; }
.map_area .map_list .couponlist .subject .btn:hover{color:#ec3c40;  border:solid 1px #dc1636;  background-color:#fff; }
.map_area .map_list .couponlist  .option{overflow:hidden;height:24px;margin:0 1px;padding:0px 9px 0 0px; text-align:left; font-size:12px;}
.map_area .map_list .couponlist  .option2 span{display:inline-block; border:solid 1px #ebebeb;margin-right:5px; padding:0px 5px;background-color:#ebebeb; letter-spacing:-1px; height:20px; line-height:20px;}
.map_area .map_list .couponlist  .option img{vertical-align:-3px; margin-right:5px;}
.map_area .map_list .couponlist  .option span{display:inline-block; margin-right:10px; font-family: 'Spoqa Han Sans'!important;}
.map_area .map_list .couponlist  .option2{overflow:hidden;height:34px;margin:0 1px;padding:5px 9px 0 0px; text-align:left; font-size:12px; }



.gps_sort a { cursor:pointer; }

.bubble_pop { padding:10px;font-size:12px;  width:200px;}
.bubble_pop .cp_img { width:200px; height:200px; }
.bubble_pop .cp_subject {font-size:16px; margin:5px 0px 5px; height:20px; color:#000; overflow:hidden; -webkit-line-clamp: 1; -webkit-box-orient: vertical;} 
.bubble_pop .cp_description {display:block; line-height:18px; font-size:14px;color:#888; margin-bottom:5px;  -webkit-line-clamp: 2;-webkit-box-orient: vertical;  letter-spacing: -0.0652em;}


#location_select { width:150px; }
#km { width:150px; }
</style>

<div class="map_area">

	<div class="map_zone">
		<div class="map_ser">
			<?=admin_select($write,"location_select","width-sm","","0")?>
			<?=admin_select($write,"km","width-sm","","0")?>

			<label><input type="checkbox" class="chk_category_all" checked> 전체</label>
			<?php
			foreach($admin[category_id] as $key => $value){
			?>
			<label><input type="checkbox" class="chk_category" value="<?=$key?>"> <?=$value?></label>	
			<? } ?>
		</div>
		<div class="map" id="map"></div>
	</div>

	<div class="map_list">
		
		<div class="cate">
			<form method="post" onsubmit="return map_address_submit()">
			<div class="map_ser_de">
				<div id="now_location" class="spot"><img src="<?=$nfor[skin_path]?>img/map_cio.png">현재위치로 설정</div>
				<div class="serch_zone"><input type="text" name="address" id="address" autocomplete="off" placeholder="지역명 또는 주소를 검색하세요."><input type="submit" value="검색" class="btn"></div>
			</div>
			</form>
			<div class="map_ser_de">
			<span class="fon16">현 지도 내의 <b id="result_cnt" class=" txt_num point_color4" ><?=number_format($total_count)?></b>건의 검색 결과입니다.</span>
			<ul class="gps_sort">
				<?php
				foreach($admin["sort"] as $key => $value){
				?>
				<li><a data-sort="<?=$key?>" <?=$key=="3"?"class=\"on\"":""?>><?=$value?></a></li>
				<? } ?>
			</ul>
			</div>
		</div>

		<div class="couponlist">
			<ul id="map_list_ul">
				<?php
				for($i=0; $i<count($return["list"]); $i++){
					$row = $return["list"][$i];
				?>
				<li>
					<a href="campaign.php?cp_id=<?=$row[cp_id]?>" class="pro_img"><img src="<?=$row[cp_img]?>"></a>
					<div class="subject">
						<a href="campaign.php?cp_id=<?=$row[cp_id]?>" class="cp_subject"><?=$row[cp_subject]?></a>
						<span class="cp_description"><?=$row[cp_description]?></span>
						<p class="cp_addr1"><?=$row[cp_addr1]?></p>
						<div class="option">
							<span><img src="<?=$nfor[skin_path]?>img/txt_ico.png"><b class="txt_num point_color4"><?=$row[cp_order]?></b>명 신청/<b class="txt_num point_color4"><?=$row[cp_recruit]?></b>명 모집중</span> 
							<span><img src="<?=$nfor[skin_path]?>img/pen_ico.png"><b class="txt_num point_color4"><?=$row[cp_review]?></b>개의 리뷰</span>		
						</div>
						<div class="option2">
							<?php if($row[cp_point]){ ?><span class="txt_num point_color4">+ <?=$row[cp_point]?>P</span><?php } ?>
							<span class="txt_num dday"><?=$row[cp_day]?></span>
						</div>
					</div>
				</li>
				<? } ?>				
			</ul>
			<div class="page_center"></div>
		</div>

	</div>

</div>


<script>
var category = "";
var sort = <?=json_encode((string)$sort, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT)?>;
var lat = "<?=$lat?>";
var lng = "<?=$lng?>";
var zoom_level = "8";
var km = "<?=$km?>";

var positions = [
	<?php
	for($i=0; $i<count($return["list"]); $i++){
		$row = $return["list"][$i];
		if($i) echo ",";
	?>
	{cp_order: '<?=$row[cp_order]?>', cp_recruit: '<?=$row[cp_recruit]?>', cp_review: '<?=$row[cp_review]?>', cp_point: '<?=$row[cp_point]?>', cp_day: '<?=$row[cp_day]?>', cp_description: '<?=$row[cp_description]?>', cp_addr1: '<?=$row[cp_addr1]?>', cp_img: '<?=$row[cp_img]?>', cp_id: '<?=$row[cp_id]?>', cp_subject: '<?=$row[cp_subject]?>', latlng: new daum.maps.LatLng(<?=$row[cp_lat]?>, <?=$row[cp_lng]?>) }
	<?php } ?>
];

$(document).on("click", ".chk_category", function(){
	category = "";
	$(".chk_category:checked").each(function() {
		if(category){
			category += ",";
		}
		category += $(this).val();
	});
	if(category){
		$(".chk_category_all").prop("checked",false);
	}
	map_campaign_list_load(lat,lng)
});

$(document).on("click", ".chk_category_all", function(){
	if($(".chk_category_all").is(":checked") == true){
		$(".chk_category").prop("checked",false);
		category = "";
	}
	map_campaign_list_load(lat,lng)
});

$(document).on("change", "#km", function(){
	km = $(this).val();
	map_campaign_list_load(lat,lng)
});

$(document).on("change", "#location_select", function(){
	var location_select = $(this).val();
	if(location_select){
		var location_select_exp = location_select.split(",");
		lat = location_select_exp[0];
		lng = location_select_exp[1];
		var moveLatLon = new daum.maps.LatLng(lat, lng);
		map.panTo(moveLatLon);
	}
});

$(document).on("click", ".gps_sort li a", function(){
	$(".gps_sort li a").removeClass("on");
	$(this).addClass("on");
	sort = $(this).data("sort");
	map_campaign_list_load(lat,lng);
});

$(document).on("click", "#now_location", function(){
	if(navigator.geolocation){
		navigator.geolocation.getCurrentPosition(function(position) {
			lat = position.coords.latitude;
			lng = position.coords.longitude;
			var moveLatLon = new daum.maps.LatLng(lat, lng);
			map.panTo(moveLatLon);
		}, function(error) {
			if(error.code=="1"){
				alert("위치허용 권한이 없습니다(사용자가 위치 정보 수집을 거부)");
			} else if(error.code=="2"){
				alert("위치 확인이 불가합니다(위치 정보 수집 불가 예: GPS 동작 불가 지역 등)");
			} else if(error.code=="3"){
				alert("위치 확인 시간이 초과하였습니다(위치 정보를 수집하기 전에 먼저 옵션의 timeout 값 시간이 소요)");
			} else{
				alert('Error occurred. Error code: ' + error.code);       
			}			
		},{timeout:4000});
	} else{
		alert('no geolocation support');
	}
});

var markers = [];

var mapContainer = document.getElementById('map'),
	mapOption = {
		center: new daum.maps.LatLng(lat, lng),
		level: zoom_level, 
		mapTypeId : daum.maps.MapTypeId.ROADMAP
	}; 

var map = new daum.maps.Map(mapContainer, mapOption); 

var mapTypeControl = new daum.maps.MapTypeControl();
map.addControl(mapTypeControl, daum.maps.ControlPosition.TOPRIGHT);	

var zoomControl = new daum.maps.ZoomControl();
map.addControl(zoomControl, daum.maps.ControlPosition.RIGHT);

daum.maps.event.addListener(map, 'idle', function () {
	lat = map.getCenter().getLat();
	lng = map.getCenter().getLng();		
	map_campaign_list_load(lat,lng);
});

var infowindow = new daum.maps.InfoWindow({zIndex:1});
for (var i = 0; i < positions.length; i ++) {
	displayMarker(positions[i]);  
}

function hideMarkers() {
    setMarkers(null);    
}

function setMarkers(map) {
	for (var i = 0; i < markers.length; i++) {
		markers[i].setMap(map);
	}  
}


function displayMarker(place) {
	var imageSrc = '<?=$nfor[skin_path]?>img/marker.png', // 마커이미지의 주소입니다    
		imageSize = new daum.maps.Size(40, 42), // 마커이미지의 크기입니다
		imageOption = {offset: new daum.maps.Point(18, 42)}; // 마커이미지의 옵션입니다. 마커의 좌표와 일치시킬 이미지 안에서의 좌표를 설정합니다.

	// 마커의 이미지정보를 가지고 있는 마커이미지를 생성합니다
	var markerImage = new daum.maps.MarkerImage(imageSrc, imageSize, imageOption);

    var marker = new daum.maps.Marker({
        map: map,
        position: place.latlng,
		image: markerImage
    });
    daum.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent('<div class="bubble_pop"><a href="campaign.php?cp_id=' + place.cp_id + '"><img src="' + place.cp_img + '" class="cp_img"><p class="cp_subject">' + place.cp_subject + '</p><p class="cp_description">' + place.cp_description + '</p></a></div>');
        infowindow.open(map, marker);
    });
    marker.setMap(map);
    markers.push(marker);
}

function map_address_submit(){
	var keyword = $("#address").val();
	if(keyword){
		var geocoder = new daum.maps.services.Geocoder();
		geocoder.addressSearch(keyword, function(result, status) {
			if(status === daum.maps.services.Status.OK) {
				var coords = new daum.maps.LatLng(result[0].y, result[0].x);
				map.setCenter(coords);
			} else{
				console.log("검색결과가 없습니다");
			}
		});
	}
	return false;
}

function map_campaign_list_load(lat,lng){
	$("#map_list_ul").html("");
	hideMarkers();
	$.ajax({
		type: "get",
		data : "json=list&lat="+lat+"&lng="+lng+"&sort="+sort+"&category="+category+"&km="+km,
		dataType : "json",
		url: "map_campaign_list.php",
		success: function(data){
			$("#result_cnt").html(data.count);
			if(data.count > 0){
				template = _.template($("#campaign_list_script").html());
				var template_html = "";
				for(var i=0; i<data.list.length; i++) {
					template_html +=  template({cp_id: data.list[i].cp_id
						, cp_img: data.list[i].cp_img
						, cp_order: data.list[i].cp_order
						, cp_recruit: data.list[i].cp_recruit
						, cp_review: data.list[i].cp_review
						, cp_point: data.list[i].cp_point
						, cp_day: data.list[i].cp_day
						, cp_addr1: data.list[i].cp_addr1
						, cp_subject: data.list[i].cp_subject
						, cp_description: data.list[i].cp_description});

						var positions = [ {cp_addr1: data.list[i].cp_addr1, cp_order: data.list[i].cp_order, cp_recruit: data.list[i].cp_recruit, cp_review: data.list[i].cp_review, cp_point: data.list[i].cp_point, cp_day: data.list[i].cp_day, cp_description: data.list[i].cp_description, cp_img: data.list[i].cp_img, cp_id: data.list[i].cp_id, cp_subject: data.list[i].cp_subject, latlng: new daum.maps.LatLng(data.list[i].cp_lat, data.list[i].cp_lng) } ];
						displayMarker(positions[0]);  
				}
				$("#map_list_ul").append(template_html);
			}
		}
	});
}
</script>
<script type="text/html" id="campaign_list_script">
<li>
	<a href="campaign.php?cp_id=<%=cp_id%>" class="pro_img"><img src="<%=cp_img%>"></a>
	<div class="subject">
		<a class="cp_subject" href="campaign.php?cp_id=<%=cp_id%>"><%=cp_subject%></a>
		<span class="cp_description"><%=cp_description%></span>
		<p class="cp_addr1"><%=cp_addr1%></p>
		<div class="option">
			<span><img src="<?=$nfor[skin_path]?>img/txt_ico.png"><b class="txt_num point_color4"><%=cp_order%></b>명 신청/<b class="txt_num point_color4"><%=cp_recruit%></b>명 모집중</span> 
			<span><img src="<?=$nfor[skin_path]?>img/pen_ico.png"><b class="txt_num point_color4"><%=cp_review%></b>개의 리뷰</span>		
		</div>
		<div class="option2">
			<% if(cp_point){ %><span class="txt_num point_color4">+ <%=cp_point%>P</span><% } %>
			<span class="txt_num dday"><%=cp_day%></span>
		</div>
	</div>
</li>
</script>

<?php
include_once $nfor[skin_path]."tail.php";
?>