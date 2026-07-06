#!/usr/local/php/bin/php -q
<?php

/*
CURL은 750
일반파일은 권한은 777

파일의 퍼미션을 실행권한이 부여된 750으로 변경하십시오.
ex) chmod 750 파일명
dos2unix 파일명을 이용하여 dos에서 unix 타입으로 변경하십시오.
ex) dos2unix 파일명
     dos2unix: converting file 파일명 to UNIX format ...
php 파일의 경우 맨 위에 "#!/usr/local/bin/php"를 추가하십시오.
ex) #!/usr/local/php/bin/php (PHP7.0)
ex) #!/usr/local/php73/bin/php (PHP7.3)
ex) #!/usr/local/php/bin/php (PHP5.2)
ex) #!/usr/local/php53/bin/php (PHP5.3)
ex) #!/usr/local/php55/bin/php (PHP5.5)
ex) #!/usr/local/php/bin/php (PHP4)

쉘에서 wheris php 했을때 나오는 경로로 지정하시면됩니다

mysql 함수 사용 시 "홈디렉토리/.my.cnf" 빈 파일 생성하십시오.
ex) touch ~아이디/.my.cnf
스크립트내 파일참조,실행,생성등에 필요한 경로지정은 반드시 절대경로로 지정하십시오.
ex) include('/home/hosting_users/아이디/www/lib/function.php');
*/


/*
크론 호출 예시
repair.php 을 직접적으로 호출할수 없는경우 다음함수를 이용
*/

function nfor_curl_request($url, $method="get", $post_param="", $header="", $show_header=""){

	// $method 값은 get 또는 post
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt($ch, CURLOPT_SSLVERSION, 1 );

	if($show_header){
		curl_setopt($ch, CURLOPT_HEADER, true );
	}

	if($header){
		/* 헤더를 출력하고 싶다면 사용
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		*/
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header); // $header 는 배열로 처리  array('Authorization: Bearer','nfor: son');
	}


	if($method=="post" and $post_param){   // $post_param 값은 다음과 같이 셋팅 a=1&b=2
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_param);
	}

	$response = curl_exec($ch);
	curl_close($ch);
	return $response;
}

nfor_curl_request("https://meta-chehumdan.com/crontab/counter.php");


?>ok