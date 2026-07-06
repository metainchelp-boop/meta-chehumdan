<?php
include_once "path.php";


/*
https://developers.facebook.com/docs/instagram-basic-display-api/reference
*/

$code = $_GET[code];

//액세스 토큰 발급
$url = "https://api.instagram.com/oauth/access_token";
$post_array = array(
    'client_id'=>'176084334160115',
    'client_secret'=>'eb26cecd0681b6880b80e5a96835b792',
    'grant_type'=>'authorization_code',
    'redirect_uri'=>'https://influencer.nfor.net/instagram_callback.php',
    'code'=>$code
);
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_POST,true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $post_array);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($curl);
curl_close($curl);
$result = json_decode($result,true);
print_r($result);

/*
여기서 발급된 액세스 토큰 또한 인증 코드와 마찬가지로 유효시간이 1시간입니다.
이것을 아래 주소로 요청하여 장기 (60일) 토큰으로 바꿀 수 있습니다.
https://graph.instagram.com/access_token?grant_type=ig_exchange_token&client_secret={instagram-app-secret}&access_token={access_token}

정상적으로 요청이 되었다면
{ "access_token": "IGQVJWY1dYNW......", "token_type": "bearer", "expires_in": 5184000 }
이렇게 장기 액세스 토큰이 발급됩니다.
장기 엑세스 토큰은 아래와 같이 새로 고침을 하여 기간을 갱신할 수 있습니다.
http://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token={long-lived-access-token}
*/




echo "<br><Br>";



$call_url = "https://graph.facebook.com/$result[user_id]?fields=business_discovery.username(bbuk.u){followers_count,media_count}&access_token=$result[access_token]"; // 남의것 가져오기

$call_data = nfor_curl_request($call_url);

$json = json_decode($call_data,true);

print_r($json);




$result[access_token] = "IGQVJXWmlKcW5lZA3ZAVOHQ3amQyN0JUbklnRm9xejJfSGFwd3VNM3NGdDlhVGdBLUFLc05OZA1ZABYVF4ckZAWdzJQM1p2U2tuRmFKS2dkM0JTamtyeFAweWlCenpPT3VkRWhqOG43dEVnajNHTVhGaG9YQgZDZD";

$call_url = "https://graph.facebook.com/$result[user_id]?fields=business_discovery.username(bbuk.u){followers_count,media_count}&access_token=$result[access_token]"; // 남의것 가져오기

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