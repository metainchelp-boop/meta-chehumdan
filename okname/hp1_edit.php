<?php
include_once "path.php";

	header('Content-Type: text/html; charset=UTF-8');

	$rqst_caus_cd = "00";
	$in_tp_bit = "0";

	// 서비스거래번호를 생성한다.
	function generateSvcTxSeqno() {   
		$numbers  = "0123456789";   
		$svcTxSeqno = date("YmdHis");   
		$nmr_loops = 6;   
		while ($nmr_loops--) {   
			$svcTxSeqno .= $numbers[mt_rand(0, strlen($numbers)-1)];   
		}   
		return $svcTxSeqno;   
	}   

	/**************************************************************************
	 * okname 본인확인서비스 파라미터
	 **************************************************************************/
	$name = "x";							// 성명
	$birthday = "x";						// 생년월일 
	$sex = "x";								// 성별
	$nation="x";							// 내외국인구분 
	$telComCd="x";							// 이동통신사코드 
	$telNo="x";								// 휴대폰번호 

	/**************************************************************************
	 * 파라미터에 대한 유효성여부를 검증한다.
	 **************************************************************************/
	$inTpBit = $in_tp_bit;	// 입력구분코드(0:없음, 1:기본정보, 2:내외국인, 4:휴대폰정보)
	if (preg_match('~[^0-9]~', $inTpBit, $match)) {
		echo ("<script>alert('입력구분코드에 유효하지 않은 문자열이 있습니다.'); self.close();</script>");
		exit;
	}
	$inTpBitVal = intval($inTpBit, 0);

	if (($inTpBitVal & 1) == 1) {
		$name = $_REQUEST["name"];				// 성명
		if (preg_match('~[^\x{ac00}-\x{d7af}a-zA-Z ]~u', $name, $match)) {	// UTF-8인 경우
			echo ("<script>alert('성명에 유효하지 않은 문자열이 있습니다.'); self.close();</script>");
			exit;
		}
	}
	
	if (($inTpBitVal & 2) == 2) {
		$birthday = $_REQUEST["birthday"];		// 생년월일
		if (preg_match('~[^0-9]~', $birthday, $match)) {
			echo ("<script>alert('생년월일에 유효하지 않은 문자열이 있습니다.'); self.close();</script>");
			exit;
		}
	}
	
	if (($inTpBitVal & 4) == 4) {
		$sex = $_REQUEST["sex"];				// 성별
		$nation = $_REQUEST["nation"];			// 내외국인구분
		if (preg_match('~[^01]~', $sex, $match)) {
			echo ("<script>alert('성별에 유효하지 않은 문자열이 있습니다.'); self.close();</script>");
			exit;
		}
		if (preg_match('~[^12]~', $nation, $match)) {
			echo ("<script>alert('내외국인 구분에 유효하지 않은 문자열이 있습니다.'); self.close();</script>");
			exit;
		}
	}
	
	if (($inTpBitVal & 8) == 8) {
		$telComCd = $_REQUEST["tel_com_cd"];	// 통신사코드
		$telNo = $_REQUEST["tel_no"];			// 휴대폰번호
		if (preg_match('~[^0-9]~', $telComCd, $match)) {
			echo ("<script>alert('통신사코드에 유효하지 않은 문자열이 있습니다.'); self.close();</script>");
			exit;
		}
		if (preg_match('~[^0-9]~', $telNo, $match)) {
			echo ("<script>alert('휴대폰번호에 유효하지 않은 문자열이 있습니다.'); self.close();</script>");
			exit;
		}
	}

	$rqstCausCd = $rqst_caus_cd;			// 인증요청사유코드 2byte  (00:회원가입, 01:성인인증, 02:회원정보수정, 03:비밀번호찾기, 04:상품구매, 99:기타)
	if (preg_match('~[^0-9]~', $rqstCausCd, $match)) {
		echo ("<script>alert('인증요청사유코드에 유효하지 않은 문자열이 있습니다.'); self.close();</script>");
		exit;
	}

	$svcTxSeqno = generateSvcTxSeqno();	// 거래번호. 동일문자열을 두번 사용할 수 없음. (최대 30자리의 문자열. 0-9,A-Z,a-z 사용)
	
	$memId = $config[cf_check_code];										// 회원사코드(아이디)

	$serverIp = "x";					// 모듈이 설치된 서버IP (서버IP검증을 무시하려면 'x'로 설정)
	$siteDomain = $_SERVER['HTTP_HOST'];		// 회원사 도메인. (휴대폰인증번호 발송시 제휴사명에 노출)
	
	$rsv1 = "0";						// 예약 항목
	$rsv2 = "0";						// 예약 항목
	$rsv3 = "0";						// 예약 항목
	
	$hsCertMsrCd = "10";				// 인증수단코드 2byte  (10:핸드폰)
	
	$returnMsg = "x";					// 리턴메시지 (고정값 'x') 
	
	// ########################################################################
	// # 리턴 URL 설정
	// ########################################################################
	// opener(hs_cnfrm_popup1.php)의 도메일과 일치하도록 설정해야 함. 
	// (http://www.test.co.kr과 http://test.co.kr는 다른 도메인으로 인식하며, http 및 https도 일치해야 함)
	$returnUrl = $_SERVER[REQUEST_SCHEME]."://".$_SERVER[HTTP_HOST]."/okname/hp2_edit.php?mb_no=$mb_no";// 본인인증 완료후 리턴될 URL (도메인 포함 full path)
	
	$endPointURL = "http://safe.ok-name.co.kr/KcbWebService/OkNameService"; // 운영 서버 

	$logPath = "$nfor[root_path]/data/okname/log"; // 로그 경로 지정

	// ########################################################################
	// # 옵션값에 'L'을 추가하는 경우에만 로그(logPath변수에 설정된)가 생성됨.
	// # 시스템(환경변수 LANG설정)이 UTF-8인 경우 'U'옵션 추가 ex)$option='QLU'
	// ########################################################################
	$options = "QUL";		// Q:인증요청데이터 암호화

	$cmd = array($svcTxSeqno, $name, $birthday, $sex, $nation, $telComCd,
				$telNo, $rsv1, $rsv2, $rsv3, $returnMsg, $returnUrl, $inTpBit,
				$hsCertMsrCd, $rqstCausCd, $memId, $serverIp, $siteDomain,
				$endPointURL, $logPath, $options);
	
//	echo $cmd."<br/>";
	
	/**************************************************************************
	okname 실행
	**************************************************************************/
	$output = NULL;
	//cmd 실행
	$ret = okname($cmd, $output);
//	echo "ret=".$ret."<br/>";
	
	/**************************************************************************
	okname 응답 정보
	**************************************************************************/
	$retcode = "";						// 결과코드
	$retmsg = "";						// 결과메시지
	$e_rqstData = "";					// 암호화된요청데이터
	
	if ($ret == 0) {//성공일 경우 변수를 결과에서 얻음
		$result = explode("\n", $output);
		$retcode = $result[0];
		$retmsg  = $result[1];
		$e_rqstData = $result[2];
	}
	else {
		if($ret <=200)
			$retcode=sprintf("B%03d", $ret);
		else
			$retcode=sprintf("S%03d", $ret);
	}
	
	$targetId = "";		// 타겟ID (결과를 전달할 팝업이 따로 있을 경우 해당 팝업명(window.name 설정값)을 설정. 일반적으로 ""으로 설정)

    $commonSvlUrl = "https://safe.ok-name.co.kr/CommonSvl";	// 운영 URL
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>KCB 본인확인서비스 샘플</title>
<script>
	function request(){
		window.name = "<?=$targetId?>";
		document.form1.action = "<?=$commonSvlUrl?>";
		document.form1.method = "post";
		document.form1.submit();
	}
</script>
</head>
<body>
	<form name="form1">
	<!-- 인증 요청 정보 -->
	<!--// 필수 항목 -->
	<input type="hidden" name="tc" value="kcb.oknm.online.safehscert.popup.cmd.P901_CertChoiceCmd">				<!-- 변경불가-->
	<input type="hidden" name="rqst_data"				value="<?=$e_rqstData?>">		<!-- 요청데이터 -->
	<input type="hidden" name="target_id"				value="<?=$targetId?>">				<!-- 타겟ID --> 
	<!-- 필수 항목 //-->	
	</form>
<?php
 	if ($retcode == "B000") {
		//인증요청
		echo ("<script>request();</script>");
	} else {
		//요청 실패 페이지로 리턴
		echo ("<script>alert(\"$retcode\"); self.close();</script>");
	}
?>
</body>
</html>