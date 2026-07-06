<?php
include_once "path.php";

	header('Content-Type: text/html; charset=UTF-8');

	// KCB 운영서버를 호출할 경우
	$idpUrl    = "https://ipin.ok-name.co.kr/tis/ti/POTI90B_SendCertInfo.jsp";

	// 아이핀 인증을 마치고 돌아올 페이지 주소. opener(ipin1.php)의 도메일과 일치하도록 설정해야 함.
	// (http://www.test.co.kr과 http://test.co.kr는 다른 도메인으로 인식하며, http 및 https도 일치해야 함)
	$returnUrl = $_SERVER[REQUEST_SCHEME]."://".$_SERVER[HTTP_HOST]."/okname/ipin2.php";		// 아이핀 인증을 마치고 돌아올 페이지 주소

	$idpCode   = "V";					// 고정값. KCB기관코드
	$moduleType= "3";					// 서버에 설치된 okname모듈의 유형 (1:exe, 2:com+, 3:php_ext, 4:jni)
	$cpCode    = $config[cf_check_code];		// 회원사코드 (테스트인 경우 'P00000000000'를 사용하며 운영시 발급받은 회원사코드를 설정)
	
	// 암호화키 파일 설정 (절대경로) - 파일은 주어진 파일명으로 자동 생성되며 매월마다 갱신됨. 웹서버에 해당파일을 생성할 권한 필요.
	$keyPath = "$nfor[root_path]/okname/okname.key";		// 운영 키파일

	$memId = $cpCode;			// 회원사코드
	$reserved1 = "0";			//reserved1
	$reserved2 = "0";			//reserved2
	$endPointUrl = "http://www.ok-name.co.kr/KcbWebService/OkNameService";// 운영 서버
	// 로그 경로 지정 및 권한 부여 (절대경로)
	// options값에 'L'을 추가하는 경우에만 로그가 생성됨.
	$logPath = "$nfor[root_path]/data/okname/log";					// 로그파일을 남기는 경우 로그파일이 생성될 경로
	$options = "CUL";// Option

	// 명령어
	$cmd = array($keyPath, $memId, $reserved1, $reserved2, $endPointUrl, $logPath, $options);

	//echo "$cmd<br/>";
	/**************************************************************************
	 * okname 실행
	 **************************************************************************/
	$output = NULL;
	// 실행
	$ret = okname($cmd, $output);
    //echo "ret=$ret<br/>";
    
	$retcode = "";										// 결과코드
	$pubkey = "";
	$sig = "";
	$curtime = "";

	if ($ret == 0) {//성공일 경우 변수를 결과에서 얻음
		$result = explode("\n", $output);
		$retcode=sprintf("B%03d", $ret);
		$pubkey=$result[0];
		$sig=$result[1];
		$curtime=$result[2];
	}
	else {
		if($ret <=200)
			$retcode=sprintf("B%03d", $ret);
		else
			$retcode=sprintf("S%03d", $ret);
	}

/*
echo "$pubkey<br/>";
echo "$sig<br/>";
echo "$curtime<br/>";
*/

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">


<script type="text/javascript">
<!--
	function certKCBIpin(){
		document.kcbInForm.target = "_self";

		//KCB 운영서버를 호출할 경우
		document.kcbInForm.action = "https://ipin.ok-name.co.kr/tis/ti/POTI01A_LoginRP.jsp";

		//인증요청
		document.kcbInForm.submit();
		return	;
	}
//-->
</script>

</head>
<body>
	<form name="kcbInForm" method="post" >
		<input type="hidden" name="IDPCODE" value="<?=$idpCode?>"/>
		<input type="hidden" name="IDPURL" value="<?=$idpUrl?>"/>
		<input type="hidden" name="CPCODE" value="<?=$cpCode?>"/>
		<input type="hidden" name="CPREQUESTNUM" value="<?=$curtime?>"/>
		<input type="hidden" name="RETURNURL" value="<?=$returnUrl?>"/>
		<input type="hidden" name="WEBPUBKEY" value="<?=$pubkey?>"/>
		<input type="hidden" name="WEBSIGNATURE" value="<?=$sig?>"/>
		<input type="hidden" name="MODULETYPE" value="<?=$moduleType?>"/>
	</form>





<?php
	if($retcode == "B000") {
?>
<script type="text/javascript">
<!--
certKCBIpin();
//-->
</script>
<?
} else {
?>
<script type="text/javascript">
<!--
	alert("<?=$retcode?>");
	self.close();
//-->
</script>
<?
	}
?>
</body>
</html>