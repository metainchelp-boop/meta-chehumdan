<?php
include_once "path.php";

header('Content-Type: text/html; charset=UTF-8');

$SITE_NAME	=	$config["cf_cp_name"];
$SITE_URL 	=   $_SERVER['HTTP_HOST'];

$CP_CD = $config["cf_check_code"]; // 회원사코드

$RETURN_URL = "https://".$_SERVER['HTTP_HOST']."/okcert3/hp2.php";// 인증 완료 후 리턴될 URL (도메인 포함 full path)

$RQST_CAUS_CD ="00";
	
$target = "PROD";
$popupUrl = "https://safe.ok-name.co.kr/CommonSvl";	// 운영 URL
	
$license = $nfor['root_path']."/okcert3/".$CP_CD."_IDS_01_".$target."_AES_license.dat";


	
	/**************************************************************************
	okcert3 request param JSON String
	**************************************************************************/
	$params  = '{ "CP_CD":"'.$CP_CD.'",';
	$params .= '"RETURN_URL":"'.$RETURN_URL.'",';
	$params .= '"SITE_NAME":"'.$SITE_NAME.'",';
	$params .= '"SITE_URL":"'.$SITE_URL.'",';
	
	//$params .= '"CHNL_CD":"'.$CHNL_CD.'",';
	//$params .= '"RETURN_MSG":"'.$RETURN_MSG.'",';

	//' 거래일련번호는 기본적으로 모듈 내에서 자동 채번되고 채번된 값을 리턴해줌.
	//'	회원사가 직접 채번하길 원하는 경우에만 아래 코드를 주석 해제 후 사용.
	//' 각 거래마다 중복 없는 $을 생성하여 입력. 최대길이:20바이트
	//$params .= '"TX_SEQ_NO":"'."123456789012345".'",'; 
	
	$params .= '"RQST_CAUS_CD":"'.$RQST_CAUS_CD.'" }';
	
	
	$svcName = "IDS_HS_POPUP_START";
	$out = NULL;
	
	// okcert3 실행
	$ret = okcert3_u($target, $CP_CD, $svcName, $params, $license, $out);	// UTF-8
	
	/**************************************************************************
	okcert3 응답 정보
	**************************************************************************/
	$RSLT_CD = "";						// 결과코드
	$RSLT_MSG = "";						// 결과메시지
	$MDL_TKN = "";						// 모듈토큰
	$TX_SEQ_NO = "";					// 거래일련번호
	
	if ($ret == 0) {// 함수 실행 성공일 경우 변수를 결과에서 얻음
		//$out = iconv("euckr","utf-8",$out);		// 인코딩 icnov 처리. okcert3 호출(EUC-KR)일 경우에만 사용 (json_decode가 UTF-8만 가능)
		$output = json_decode($out,true);		// $output = UTF-8
		
		$RSLT_CD = $output['RSLT_CD'];
		$RSLT_MSG  = iconv("utf-8","euckr", $output["RSLT_MSG"]);	// 다시 EUC-KR 로 변환
		
		if(isset($output["TX_SEQ_NO"])) $TX_SEQ_NO = $output["TX_SEQ_NO"]; // 필요 시 거래 일련 번호 에 대하여 DB저장 등의 처리
		
		if( $RSLT_CD == "B000" ) { // B000 : 정상건
			$MDL_TKN = $output['MDL_TKN']; 
		}
	}
	else {
		echo ("<script>alert('Fuction Fail / ret: ".$ret."'); self.close();</script>");
	}
?>
<title>KCB 휴대폰 본인확인 서비스 샘플 2</title>
<script>
	function request(){
		document.form1.action = "<?=$popupUrl?>";
		document.form1.method = "post";

		document.form1.submit();
	}
</script>
</head>

<body>
	<form name="form1">
	<!-- 인증 요청 정보 -->
	<!--// 필수 항목 -->
	<input type="hidden" name="tc" value="kcb.oknm.online.safehscert.popup.cmd.P931_CertChoiceCmd"/>		<!-- 변경불가-->
	<input type="hidden" name="cp_cd" value="<?=$CP_CD?>">	<!-- 회원사코드 -->
	<input type="hidden" name="mdl_tkn" value="<?=$MDL_TKN?>">	<!-- 모듈토큰 --> 
	<input type="hidden" name="target_id" value="">	
	<!-- 필수 항목 //-->	
	</form>
<?php
 	if ($RSLT_CD == "B000") {
		//인증요청
		echo ("<script>request();</script>");
	} else {
		//요청 실패 페이지로 리턴
		echo ("<script>alert('".$RSLT_CD." : ".$RSLT_MSG."'); self.close();</script>");
	}
?>
</body>
</html>