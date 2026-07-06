<?php
function aligo_sms_send($sms_multi=array()){
	global $config;
	
	$sms = array();
	$sms = $sms_multi;

	$sms['user_id'] = $config['cf_kakao_userid']; // SMS 아이디
	$sms['key'] = $config['cf_kakao_apikey'];//인증키
	$sms['sender'] = $config['cf_tel']; // 발신번호
	$sms['msg_type'] = 'SMS'; // SMS(단문) , LMS(장문), MMS(그림문자)  = 필수항목

	$oCurl = curl_init();
	curl_setopt($oCurl, CURLOPT_PORT, 443);
	curl_setopt($oCurl, CURLOPT_URL, "https://apis.aligo.in/send_mass/");
	curl_setopt($oCurl, CURLOPT_POST, 1);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($oCurl, CURLOPT_POSTFIELDS, $sms);
	curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
	$ret = curl_exec($oCurl);
	curl_close($oCurl);

	$retArr = json_decode($ret,true); // 결과배열
	return $retArr[result_code];
}

function kakao_alarm_token(){
	global $config;
	/* 
	-----------------------------------------------------------------------------------
	알림톡 토큰 생성
	-----------------------------------------------------------------------------------
	API호출 URL의 유효시간을 결정하며 URL 의 구성중 "30"은 요청의 유효시간을 의미하며, "s"는 y(년), m(월), d(일), h(시), i(분), s(초) 중 하나이며 설정한 시간내에서만 토큰이 유효합니다.
	운영중이신 보안정책에 따라 토큰의 유효시간을 특정 기간만큼 지정할 경우 매번 호출할 필요없이 해당 유효시간내에 재사용 가능합니다.
	주의하실 점은 서버를 여러대 운영하실 경우 토큰은 서버정보를 포함하므로 각 서버에서 생성된 토큰 문자열을 사용하셔야 하며 토큰 문자열을 공유해서 사용하실 수 없습니다.
	*/

	$_apiURL	  =	'https://kakaoapi.aligo.in/akv10/token/create/30/s/';
	$_hostInfo	=	parse_url($_apiURL);
	$_port		  =	(strtolower($_hostInfo['scheme']) == 'https') ? 443 : 80;
	$_variables	=	array(
	'apikey' => $config['cf_kakao_apikey'],
	'userid' => $config['cf_kakao_userid']
	);

	$oCurl = curl_init();
	curl_setopt($oCurl, CURLOPT_PORT, $_port);
	curl_setopt($oCurl, CURLOPT_URL, $_apiURL);
	curl_setopt($oCurl, CURLOPT_POST, 1);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($oCurl, CURLOPT_POSTFIELDS, http_build_query($_variables));
	curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

	$ret = curl_exec($oCurl);
	$error_msg = curl_error($oCurl);
	curl_close($oCurl);

	// 리턴 JSON 문자열 확인
	//print_r($ret . PHP_EOL);
	// JSON 문자열 배열 변환
	$retArr = json_decode($ret,true);
	// 결과값 출력
	return $retArr;


	/*
	code : 0 성공, 나머지 숫자는 에러
	message : 결과 메시지
	*/

}


function kakao_alarm_template_list($tpl_code=""){
	global $config;
	$token = kakao_alarm_token();

	/*
	-----------------------------------------------------------------------------------
	등록된 템플릿 리스트
	-----------------------------------------------------------------------------------
	등록된 템플릿 목록을 조회합니다. 템플릿 코드가 D 나 P 로 시작하는 경우 공유 템플릿이므로 삭제 불가능 합니다.
	*/

	$_apiURL		=	'https://kakaoapi.aligo.in/akv10/template/list/';
	$_hostInfo	=	parse_url($_apiURL);
	$_port			=	(strtolower($_hostInfo['scheme']) == 'https') ? 443 : 80;
	$_variables	=	array(
	'apikey'    => $config['cf_kakao_apikey'],
	'userid'    => $config['cf_kakao_userid'],
	'token'     => $token['token'],
	'senderkey' => $config['cf_kakao_senderkey'],
	'tpl_code' => $tpl_code
	);

	$oCurl = curl_init();
	curl_setopt($oCurl, CURLOPT_PORT, $_port);
	curl_setopt($oCurl, CURLOPT_URL, $_apiURL);
	curl_setopt($oCurl, CURLOPT_POST, 1);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($oCurl, CURLOPT_POSTFIELDS, http_build_query($_variables));
	curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

	$ret = curl_exec($oCurl);
	$error_msg = curl_error($oCurl);
	curl_close($oCurl);

	// 리턴 JSON 문자열 확인
	//print_r($ret . PHP_EOL);

	// JSON 문자열 배열 변환
	$retArr = json_decode($ret,true);

	// 결과값 출력
	return $retArr;

	/*
	code : 0 성공, 나머지 숫자는 에러
	message : 결과 메시지
	*/
}



function kakao_alarm_template_delete($tpl_code){
	global $config;
	$token = kakao_alarm_token();
	/*
	-----------------------------------------------------------------------------------
	템플릿 삭제 요청
	-----------------------------------------------------------------------------------
	승인이 이루어지지 않은 템플릿에 대하여 삭제요청 합니다. 
	삭제는 즉시 이루어 지나 이미 승인이 완료된 템플릿은 삭제불가 합니다.
	*/

	$_apiURL		=	'https://kakaoapi.aligo.in/akv10/template/del/';
	$_hostInfo	=	parse_url($_apiURL);
	$_port			=	(strtolower($_hostInfo['scheme']) == 'https') ? 443 : 80;
	$_variables	=	array(
	'apikey'        => $config['cf_kakao_apikey'],
	'userid'        => $config['cf_kakao_userid'],
	'token'         => $token['token'],
	'senderkey'     => $config['cf_kakao_senderkey'],
	'tpl_code'      => $tpl_code
	);

	$oCurl = curl_init();
	curl_setopt($oCurl, CURLOPT_PORT, $_port);
	curl_setopt($oCurl, CURLOPT_URL, $_apiURL);
	curl_setopt($oCurl, CURLOPT_POST, 1);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($oCurl, CURLOPT_POSTFIELDS, http_build_query($_variables));
	curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

	$ret = curl_exec($oCurl);
	$error_msg = curl_error($oCurl);
	curl_close($oCurl);

	// 리턴 JSON 문자열 확인
	//print_r($ret . PHP_EOL);

	// JSON 문자열 배열 변환
	$retArr = json_decode($ret,true);

	// 결과값 출력
	return $retArr;

	/*
	code : 0 성공, 나머지 숫자는 에러
	message : 결과 메시지
	*/
}




function kakao_alarm_template_request($tpl_code){
	global $config;
	$token = kakao_alarm_token();
	/*
	-----------------------------------------------------------------------------------
	템플릿 검수 요청
	-----------------------------------------------------------------------------------
	작성이 완료된 템플릿에 대하여 검수요청을 합니다. 검수 결과에 따라 재작성 요청이 발생 할 수 있습니다.
	*/

	$_apiURL		=	'https://kakaoapi.aligo.in/akv10/template/request/';
	$_hostInfo	=	parse_url($_apiURL);
	$_port			=	(strtolower($_hostInfo['scheme']) == 'https') ? 443 : 80;
	$_variables	=	array(
	'apikey'        => $config['cf_kakao_apikey'],
	'userid'        => $config['cf_kakao_userid'],
	'token'         => $token['token'],
	'senderkey'     => $config['cf_kakao_senderkey'],
	'tpl_code'      => $tpl_code
	);

	$oCurl = curl_init();
	curl_setopt($oCurl, CURLOPT_PORT, $_port);
	curl_setopt($oCurl, CURLOPT_URL, $_apiURL);
	curl_setopt($oCurl, CURLOPT_POST, 1);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($oCurl, CURLOPT_POSTFIELDS, http_build_query($_variables));
	curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

	$ret = curl_exec($oCurl);
	$error_msg = curl_error($oCurl);
	curl_close($oCurl);

	// 리턴 JSON 문자열 확인
	//print_r($ret . PHP_EOL);

	// JSON 문자열 배열 변환
	$retArr = json_decode($ret,true);

	// 결과값 출력
	return $retArr;

	/*
	code : 0 성공, 나머지 숫자는 에러
	message : 결과 메시지
	*/

}



function kakao_alarm_template_insert($tpl_name, $tpl_content, $tpl_button=""){
	global $config;
	$token = kakao_alarm_token();
	/* 
	-----------------------------------------------------------------------------------
	템플릿 등록 요청
	-----------------------------------------------------------------------------------
	알림톡을 전송하기 위해서는 템플릿을 작성해야 하며, 작성된 템플릿은 다음-카카오측의 4-5일간의 검수후
	결과에 따라 거부되어 재작성 요청이 발생할 수 있습니다.
	*/

	$_apiURL	  =	'https://kakaoapi.aligo.in/akv10/template/add/';
	$_hostInfo	=	parse_url($_apiURL);
	$_port		  =	(strtolower($_hostInfo['scheme']) == 'https') ? 443 : 80;
	$_variables	=	array(
	'apikey'	    => $config['cf_kakao_apikey'],
	'userid'	    => $config['cf_kakao_userid'],
	'token'		    => $token['token'],
	'senderkey'	  => $config['cf_kakao_senderkey'],
	'tpl_name'	  => $tpl_name,
	'tpl_content' => $tpl_content,
	'tpl_button'  => $tpl_button // '{"button":[{"name":"테스트 버튼","linkType":"DS"}]}'
	);

	$oCurl = curl_init();
	curl_setopt($oCurl, CURLOPT_PORT, $_port);
	curl_setopt($oCurl, CURLOPT_URL, $_apiURL);
	curl_setopt($oCurl, CURLOPT_POST, 1);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($oCurl, CURLOPT_POSTFIELDS, http_build_query($_variables));
	curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

	$ret = curl_exec($oCurl);
	$error_msg = curl_error($oCurl);
	curl_close($oCurl);

	// 리턴 JSON 문자열 확인
	//print_r($ret . PHP_EOL);

	// JSON 문자열 배열 변환
	$retArr = json_decode($ret,true);

	// 결과값 출력
	return $retArr;

	/*
	code : 0 성공, 나머지 숫자는 에러
	message : 결과 메시지
	*/

}


function kakao_alarm_template_modify($tpl_code, $tpl_name, $tpl_content, $tpl_button=""){
	global $config;
	$token = kakao_alarm_token();
	/*
	-----------------------------------------------------------------------------------
	템플릿 수정
	-----------------------------------------------------------------------------------
	작성 또는 반려된 템플릿을 수정하는 기능이며, 템플릿상태가 대기(R)이고 템플릿 검수상태가 등록(REG) 또는 반려(REJ)인 경우에만 수정 가능합니다.
	*/

	$_apiURL		=	'https://kakaoapi.aligo.in/akv10/template/modify/';
	$_hostInfo	=	parse_url($_apiURL);
	$_port			=	(strtolower($_hostInfo['scheme']) == 'https') ? 443 : 80;
	$_variables	=	array(
	'apikey'        => $config['cf_kakao_apikey'],
	'userid'        => $config['cf_kakao_userid'],
	'token'         => $token['token'],
	'senderkey'     => $config['cf_kakao_senderkey'],
	'tpl_code'      => $tpl_code,
	'tpl_name'      => $tpl_name,
	'tpl_content'   => $tpl_content,
	'tpl_button'    => $tpl_button,
	);

	$oCurl = curl_init();
	curl_setopt($oCurl, CURLOPT_PORT, $_port);
	curl_setopt($oCurl, CURLOPT_URL, $_apiURL);
	curl_setopt($oCurl, CURLOPT_POST, 1);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($oCurl, CURLOPT_POSTFIELDS, http_build_query($_variables));
	curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

	$ret = curl_exec($oCurl);
	$error_msg = curl_error($oCurl);
	curl_close($oCurl);

	// 리턴 JSON 문자열 확인
	//print_r($ret . PHP_EOL);

	// JSON 문자열 배열 변환
	$retArr = json_decode($ret,true);

	// 결과값 출력
	return $retArr;

	/*
	code : 0 성공, 나머지 숫자는 에러
	message : 결과 메시지
	*/
}




function kakao_alarm_send($tpl_code,$sender, $receiver_1, $subject_1, $message_1, $button_1=""){
	global $config;
	$token = kakao_alarm_token();
	/* 
	-----------------------------------------------------------------------------------
	알림톡 전송
	-----------------------------------------------------------------------------------
	버튼의 경우 템플릿에 버튼이 있을때만 버튼 파라메더를 입력하셔야 합니다.
	버튼이 없는 템플릿인 경우 버튼 파라메더를 제외하시기 바랍니다.
	*/
	if($button_1){
		$button = array();
		$button[button] = array($button_1);
		$button_1 = json_encode($button,JSON_UNESCAPED_UNICODE);
	}

	$_apiURL    =	'https://kakaoapi.aligo.in/akv10/alimtalk/send/';
	$_hostInfo  =	parse_url($_apiURL);
	$_port      =	(strtolower($_hostInfo['scheme']) == 'https') ? 443 : 80;
	$_variables =	array(
	'apikey'        => $config['cf_kakao_apikey'],
	'userid'        => $config['cf_kakao_userid'],
	'token'         => $token['token'],
	'senderkey'     => $config['cf_kakao_senderkey'],
	'tpl_code'      => $tpl_code,
	'sender'      => $sender, // 발신자번호
	// 'senddate'    => date("YmdHis", strtotime("+10 minutes")), 예약일
	'receiver_1'  => $receiver_1, // 첫번째 알림톡을 전송받을 휴대폰 번호
	//'recvname_1'  => $recvname_1, // 첫번째 알림톡을 전송받을 사용자 명
	'subject_1'   => $subject_1, // 첫번째 알림톡을 제목
	'message_1'   => $message_1, // 첫번째 템플릿내용을 기초로 작성된 전송할 메세지 내용
	'button_1'    => $button_1, // {"button":[{"name":"테스트 버튼","linkType":"DS"}]} 템플릿에 버튼이 없는경우 제거하시기 바랍니다.
	'failover' => "Y",
	'fsubject_1' => $subject_1,
	'fmessage_1' => $message_1
	);

	/*

	-----------------------------------------------------------------
	치환자 변수에 대한 처리
	-----------------------------------------------------------------

	등록된 템플릿이 "#{이름}님 안녕하세요?" 일경우
	실제 전송할 메세지 (message_x) 에 들어갈 메세지는
	"홍길동님 안녕하세요?" 입니다.

	카카오톡에서는 전문과 템플릿을 비교하여 치환자이외의 부분이 일치할 경우
	정상적인 메세지로 판단하여 발송처리 하는 관계로
	반드시 개행문자도 템플릿과 동일하게 작성하셔야 합니다.

	예제 : message_1 = "홍길동님 안녕하세요?"

	-----------------------------------------------------------------
	버튼타입이 WL일 경우 (웹링크)
	-----------------------------------------------------------------
	링크정보는 다음과 같으며 버튼도 치환변수를 사용할 수 있습니다.
	{"button":[{"name":"버튼명","linkType":"WL","linkP":"https://www.링크주소.com/?example=12345", "linkM": "https://www.링크주소.com/?example=12345"}]}

	-----------------------------------------------------------------
	버튼타입이 AL 일 경우 (앱링크)
	-----------------------------------------------------------------
	{"button":[{"name":"버튼명","linkType":"AL","linkI":"https://www.링크주소.com/?example=12345", "linkA": "https://www.링크주소.com/?example=12345"}]}

	-----------------------------------------------------------------
	버튼타입이 DS 일 경우 (배송조회)
	-----------------------------------------------------------------
	{"button":[{"name":"버튼명","linkType":"DS"}]}

	-----------------------------------------------------------------
	버튼타입이 BK 일 경우 (봇키워드)
	-----------------------------------------------------------------
	{"button":[{"name":"버튼명","linkType":"BK"}]}

	-----------------------------------------------------------------
	버튼타입이 MD 일 경우 (메세지 전달)
	-----------------------------------------------------------------
	{"button":[{"name":"버튼명","linkType":"MD"}]}

	-----------------------------------------------------------------
	버튼이 여러개 인경우 (WL + DS)
	-----------------------------------------------------------------
	{"button":[{"name":"버튼명","linkType":"WL","linkP":"https://www.링크주소.com/?example=12345", "linkM": "https://www.링크주소.com/?example=12345"}, {"name":"버튼명","linkType":"DS"}]}

	*/

	$oCurl = curl_init();
	curl_setopt($oCurl, CURLOPT_PORT, $_port);
	curl_setopt($oCurl, CURLOPT_URL, $_apiURL);
	curl_setopt($oCurl, CURLOPT_POST, 1);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($oCurl, CURLOPT_POSTFIELDS, http_build_query($_variables));
	curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

	$ret = curl_exec($oCurl);
	$error_msg = curl_error($oCurl);
	curl_close($oCurl);

	// 리턴 JSON 문자열 확인
	//print_r($ret . PHP_EOL);

	// JSON 문자열 배열 변환
	$retArr = json_decode($ret,true);

	// 결과값 출력
	return $retArr[code];

	/*
	code : 0 성공, 나머지 숫자는 에러
	message : 결과 메시지
	*/

}




function kakao_alarm_cash(){
	global $config;
	$token = kakao_alarm_token();
	/* 
	-----------------------------------------------------------------------------------
	발송가능건수
	-----------------------------------------------------------------------------------
	보유한 충전금 잔액으로 발송가능한 잔여건수를 문자구분(유형)별로 조회하실 수 있습니다.
	SMS, LMS, MMS, ALT 로 발송시 가능한 잔여건수이며 남은 충전금을 문자유형별로 보냈을 경우 가능한 잔여건입니다.
	예를들어 SMS_CNT : 11 , ALT_CNT : 15 인 경우 단문전송시 11건이 가능하고, 알림톡으로 전송시 15건이 가능합니다.
	*/

	$_apiURL	  =	'https://kakaoapi.aligo.in/akv10/heartinfo/';
	$_hostInfo	=	parse_url($_apiURL);
	$_port		  =	(strtolower($_hostInfo['scheme']) == 'https') ? 443 : 80;
	$_variables	=	array(
	'apikey'        => $config['cf_kakao_apikey'],
	'userid'        => $config['cf_kakao_userid'],
	'token'         => $token['token']
	);

	$oCurl = curl_init();
	curl_setopt($oCurl, CURLOPT_PORT, $_port);
	curl_setopt($oCurl, CURLOPT_URL, $_apiURL);
	curl_setopt($oCurl, CURLOPT_POST, 1);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($oCurl, CURLOPT_POSTFIELDS, http_build_query($_variables));
	curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

	$ret = curl_exec($oCurl);
	$error_msg = curl_error($oCurl);
	curl_close($oCurl);

	// 리턴 JSON 문자열 확인
	//print_r($ret . PHP_EOL);

	// JSON 문자열 배열 변환
	$retArr = json_decode($ret,true);

	// 결과값 출력
	return $retArr;

	/*
	code : 0 성공, 나머지 숫자는 에러
	message : 결과 메시지
	*/

}




function kakao_alarm_history_list($page, $limit, $startdate, $enddate){
	global $config;
	$token = kakao_alarm_token();
	/*
	-----------------------------------------------------------------------------------
	전송내역조회
	-----------------------------------------------------------------------------------
	최근 요청및 처리된 전송내역을 조회하실 수 있습니다.
	사이트내 전송결과조회 페이지와 동일한 내역이 조회되며, 날짜기준으로 조회가 가능합니다.
	발신번호별 조회기능은 제공이 되지 않습니다.
	조회시작일을 지정하실 수 있으며, 시작일 이전 몇일까지 조회할지 설정이 가능합니다.
	조회시 최근발송내역 순서로 소팅됩니다.
	*/

	$_apiURL		=	'https://kakaoapi.aligo.in/akv10/history/list/';
	$_hostInfo	=	parse_url($_apiURL);
	$_port			=	(strtolower($_hostInfo['scheme']) == 'https') ? 443 : 80;
	$_variables	=	array(
	'apikey'        => $config['cf_kakao_apikey'],
	'userid'        => $config['cf_kakao_userid'],
	'token'         => $token['token'],
	'page'          => $page,
	'limit'         => $limit,
	'startdate'     => $startdate,
	'enddate'       => $enddate
	);

	$oCurl = curl_init();
	curl_setopt($oCurl, CURLOPT_PORT, $_port);
	curl_setopt($oCurl, CURLOPT_URL, $_apiURL);
	curl_setopt($oCurl, CURLOPT_POST, 1);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($oCurl, CURLOPT_POSTFIELDS, http_build_query($_variables));
	curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

	$ret = curl_exec($oCurl);
	$error_msg = curl_error($oCurl);
	curl_close($oCurl);

	// 리턴 JSON 문자열 확인
	//print_r($ret . PHP_EOL);

	// JSON 문자열 배열 변환
	$retArr = json_decode($ret,true);

	// 결과값 출력
	return $retArr;

	/*
	code : 0 성공, 나머지 숫자는 에러
	message : 결과 메시지
	*/
}



function kakao_alarm_history_detail($mid){
	global $config;
	$token = kakao_alarm_token();
	/*
	-----------------------------------------------------------------------------------
	전송결과조회(상세)
	-----------------------------------------------------------------------------------
	최근 요청및 처리된 전송내역을 조회하실 수 있습니다.
	사이트내 전송결과조회 페이지와 동일한 내역이 조회되며, 날짜기준으로 조회가 가능합니다.
	발신번호별 조회기능은 제공이 되지 않습니다.
	조회시작일을 지정하실 수 있으며, 시작일 이전 몇일까지 조회할지 설정이 가능합니다.
	조회시 최근발송내역 순서로 소팅됩니다.
	*/

	$_apiURL		=	'https://kakaoapi.aligo.in/akv10/history/detail/';
	$_hostInfo	=	parse_url($_apiURL);
	$_port			=	(strtolower($_hostInfo['scheme']) == 'https') ? 443 : 80;
	$_variables	=	array(
	'apikey'    => $config['cf_kakao_apikey'],
	'userid'    => $config['cf_kakao_userid'],
	'token'     => $token['token'],
	'mid'           => $mid // 메시지 고유ID
	);

	$oCurl = curl_init();
	curl_setopt($oCurl, CURLOPT_PORT, $_port);
	curl_setopt($oCurl, CURLOPT_URL, $_apiURL);
	curl_setopt($oCurl, CURLOPT_POST, 1);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($oCurl, CURLOPT_POSTFIELDS, http_build_query($_variables));
	curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

	$ret = curl_exec($oCurl);
	$error_msg = curl_error($oCurl);
	curl_close($oCurl);

	// 리턴 JSON 문자열 확인
	//print_r($ret . PHP_EOL);

	// JSON 문자열 배열 변환
	$retArr = json_decode($ret,true);

	// 결과값 출력
	return $retArr;

	/*
	code : 0 성공, 나머지 숫자는 에러
	message : 결과 메시지
	*/

}
?>