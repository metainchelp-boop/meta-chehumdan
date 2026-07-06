<?php
include_once "path.php";


$result[user_id] = "17841432234265380";



$result[access_token] = "IGQVJXNVZABamZANajNfT3RQcmNaQXRiTlFqanJrOGc2alF2NENLSjhaYzh1cGNhbTFKTzloSFJUWkFGeXVPa0ZAXdFgwVHhKT1lyWXkzVHczeEJZAMS11QkJLSXRNY1NUaE1neUxLZAFpxeEppYWtpeTV2SgZDZD";






$call_url = "https://graph.facebook.com/$result[user_id]?fields=business_discovery.username(bbuk.u){followers_count,media_count,media}&access_token=$result[access_token]"; // 남의것 가져오기

echo $call_url;

$call_data = nfor_curl_request($call_url);

$json = json_decode($call_data,true);

print_r($json);

exit;


/*

exit;

echo "<br><Br>";



$call_url = "https://graph.facebook.com/v3.2/$result[user_id]?fields=business_discovery.username(bbuk.u){followers_count,media_count,media}&access_token=$result[access_token]"; // 남의것 가져오기

$call_data = nfor_curl_request($call_url);

$json = json_decode($call_data,true);

print_r($json);

exit;





echo "<br><Br>";

$call_url = "https://graph.facebook.com/v3.2/$result[user_id]?fields=business_discovery.username(bbuk.u){followers_count,media_count}&access_token=$result[access_token]"; // 남의것 가져오기

$call_data = nfor_curl_request($call_url);

$json = json_decode($call_data,true);

print_r($json);

exit;




echo "<br><Br>";

$call_url = "https://graph.instagram.com/$result[user_id]?fields=account_type,media_count,id,username&access_token=$result[access_token]"; // 내꺼 게시글수 가져오기

$call_data = nfor_curl_request($call_url);

$json = json_decode($call_data,true);

print_r($json);

exit;




echo "<br><Br>";

$call_url = "https://graph.instagram.com/me?fields=id,username&access_token=$result[access_token]"; // 이름가져오기

$call_data = nfor_curl_request($call_url);

$json = json_decode($call_data,true);

print_r($json);

exit;






*/







/*

$call_url = "https://graph.instagram.com/$result[user_id]/media?fields=id,media_type,media_url,permalink,thumbnail_url,username,caption&access_token=$result[access_token]";


$call_data = nfor_curl_request($call_url);

$json = json_decode($call_data,true);



for($i=0; $i<count($json[data]); $i++){
	$row = $json[data][$i];

	echo $row[id];

	echo "AAAAAAAA";
	echo $row[media_type];
	echo "AAAAAAAA";

	
	if($row[media_type]=="IMAGE" or $row[media_type]=="CAROUSEL_ALBUM"){
		echo "<img src='$row[media_url]' style='width:100px;'>";
	} elseif($row[media_type]=="VIDEO"){

		echo '<video preload="auto" autoplay="" playsinline="" webkit-playsinline="" x5-playsinline="" src="'.$row[media_url].'" style="width: 100px; height: 100px;"></video>';


	} else{
		echo "<br>";
		echo "<br>";
		echo $row[media_url];
		echo "<br>";
		echo "<br>";
	}

	echo $row[permalink];
	echo $row[id];
	echo $row[username];
	echo $row[caption];
	echo "<br>";
	echo "<br>";
	echo "<br>";
	echo "<br>";

}
*/
?>