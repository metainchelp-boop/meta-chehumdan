<?php
include_once $nfor[skin_path]."head.php";		
?>

<style>
#map { width:100%; height:100vh; } 
#container { min-height:100vh; }


.map_area .map_zone { width:100%; height:100vh; z-index:9; }
.map { width:100vh; height:100vh; }


.map_ser { display:none; }

.map_area { position:relative; width:100%; height:100vh; }
.map_list { position:absolute; width:50%; max-width:360px; left:50%; top:0px; z-index:9; height:100vh; background-color:#fff; }

.map_area .map_list .couponlist { width:100%; height:100%; box-sizing:border-box; overflow:hidden; overflow-y:auto; }
.map_area .map_list .couponlist ul { width:100%; }
.map_area .map_list .couponlist ul li { overflow:hidden; background-color:#fff; border-bottom:solid 1px #dcdcdc; padding:10px; }
.map_area .map_list .couponlist .pro_img { padding:0px; box-sizing:border-box; } 
.map_area .map_list .couponlist .pro_img img { width:100%; }
.map_area .map_list .couponlist .subject { padding:10px 10px 0px; }
.map_area .map_list .couponlist .subject .it_name { display:block; line-height:18px; font-size:14px; margin-bottom:3px; }
.map_area .map_list .couponlist .subject .it_description { display:block; line-height:14px; font-size:12px; color:#666; margin-bottom:5px; }
.map_area .map_list .couponlist .subject .it_discount_rate { display:inline-block; margin-right:4px; color:#e83862; font-family:Tahoma; }
.map_area .map_list .couponlist .subject .it_discount_rate strong { font-size:30px; }
.map_area .map_list .couponlist .subject .it_price s { color:#aaa; font-size:12px; }
.map_area .map_list .couponlist .subject .it_price {  display:inline-block; font-weight:bold; font-family:Tahoma; line-height:15px; }

.map_popup_btn { position:absolute; left:-40px; top:75%; background-color:#ff3478; color:#fff; padding:5px; font-size:15px;  } 

#header { display:none; } 
.footer_wrap { display:none; }  
#wrap { padding:0px 0px 57px 0px }


#now_location { position:fixed; left:10px; top:10px; height:20px; line-height:20px; font-size:15px; z-index:999; padding:10px; background-color:#ff3478; color:#fff; border-radius:10px; }


.bubble_pop { width:150px;  }
.bubble_pop img { width:100%; }
.bubble_pop .cp_subject { display:block; padding:5px 5px 0px; font-size:12px; font-weight:bold;  }
.bubble_pop .cp_description { display:block; padding:5px; font-size:12px; }

.footer { display:none; }
</style>

<div class="map_area">

	<div id="now_location" class="spot">현재위치로 설정</div>

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
		
		<div class="cate" style="display:none;">
			<form method="post" onsubmit="return map_address_submit()">
			<div class="map_ser_de">
				<div class="serch_zone"><input type="text" name="address" id="address" autocomplete="off" placeholder="지역명 또는 주소를 검색하세요."><input type="submit" value="검색" class="btn"></div>
			</div>
			</form>
			<div class="map_ser_de">
			<span >현 지도 내의 <b id="result_cnt"><?=number_format($total_count)?></b>건의 검색 결과입니다</span>
			<ul class="gps_sort">			
				<?php
				foreach($admin["sort"] as $key => $value){
				?>
				<li><a data-sort="<?=$key?>" <?=$key=="3"?"class=\"on\"":""?>><?=$value?></a></li>
				<? } ?>
			</ul>
			</div>
		</div>

		<a class="map_popup_btn">닫기</a>

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
$(document).on("click", ".map_popup_btn", function(){
	if($(".map_popup_btn").hasClass("open")){
		$(this).removeClass("open").html("닫기");
		$(".map_list").animate({"left":"50%"}, 500 );
	} else{
		$(this).addClass("open").html("열기");
		$(".map_list").animate({"left":"100%"}, 500 );
	}
});

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

<style>
#map_list_ul li .pro_img img {  }
#map_list_ul li .cp_subject { font-size:13px; font-weight:bold; display:block; }
#map_list_ul li .cp_description { font-size:12px; display:block; line-height:14px; height:28px; overflow:hidden; margin:3px 0px; }
#map_list_ul li .cp_addr1 { font-size:12px; margin-bottom:3px; color:#aaaaaa; }
#map_list_ul li .option { font-size:12px; }
#map_list_ul li .option img { margin-right:3px; }
#map_list_ul li .option2 { font-size:12px; }
</style>

<?php
include_once $nfor[skin_path]."tail.php";
?>