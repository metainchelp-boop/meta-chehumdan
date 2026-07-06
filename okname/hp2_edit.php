<?
include_once "path.php";

	header('Content-Type: text/html; charset=UTF-8');
	/**************************************************************************
		파일명 : hs_cnfrm_popup3.php
		
		본인확인서비스 결과 화면(return url)
	**************************************************************************/
	
	/* 공통 리턴 항목 */
	$rqstSiteNm				=	$_REQUEST["rqst_site_nm"];			// 접속도메인	
	$rqstCausCd		=	$_REQUEST["hs_cert_rqst_caus_cd"];	// 인증요청사유코드 2byte  (00:회원가입, 01:성인인증, 02:회원정보수정, 03:비밀번호찾기, 04:상품구매, 99:기타)

	/**************************************************************************
	 * 모듈 호출	; 본인확인서비스 결과 데이터를 복호화한다.
	 **************************************************************************/

	// 인증결과 암호화 데이터
	$encInfo = $_REQUEST["encInfo"];
	//KCB서버 공개키
	$WEBPUBKEY = trim($_REQUEST["WEBPUBKEY"]);
	//KCB서버 서명값
	$WEBSIGNATURE = trim($_REQUEST["WEBSIGNATURE"]);

	/**************************************************************************
	 * 파라미터에 대한 유효성여부를 검증한다.
	 **************************************************************************/
	if(preg_match('~[^0-9a-zA-Z+/=]~', $encInfo, $match)) {echo "입력 값 확인이 필요합니다"; exit;}
	if(preg_match('~[^0-9a-zA-Z+/=]~', $WEBPUBKEY, $match)) {echo "입력 값 확인이 필요합니다"; exit;}
	if(preg_match('~[^0-9a-zA-Z+/=]~', $WEBSIGNATURE, $match)) {echo "입력 값 확인이 필요합니다"; exit;}

	$memId = $config[cf_check_code];										// 회원사코드(아이디)

	$endPointUrl = "http://safe.ok-name.co.kr/KcbWebService/OkNameService"; // 운영 서버
		  
	// ########################################################################
	// # 암호화키 파일 설정 (절대경로) - 파일은 주어진 파일명으로 자동 생성되며 생성되지 않으면 S211오류가 발생됨
	// # 파일은 매월초에 갱신되며 만일 파일이 갱신되지 않으면 복화화데이터가 깨지는 현상이 발생됨.
	// ########################################################################
	$keyPath = "$nfor[root_path]/data/okname/safecert_".$memId.".key";	// 운영 키파일

	$logPath = "$nfor[root_path]/data/okname/log"; // 로그 경로 지정

	// ########################################################################
	// # 옵션값에 'L'을 추가하는 경우에만 로그(logPath변수에 설정된)가 생성됨.
	// # 시스템(환경변수 LANG설정)이 UTF-8인 경우 'U'옵션 추가 ex)$option='SLU'
	// ########################################################################
	$options = "SUL";	// S:인증결과복호화
		
	// 명령어
	$cmd = array($keyPath, $memId, $endPointUrl, $WEBPUBKEY, $WEBSIGNATURE, $encInfo, $logPath, $options);
//	echo "$cmd<br/>";
	
	/**************************************************************************
	okname 실행
	**************************************************************************/
	$output = NULL;
	// 실행
	$ret = okname($cmd, $output);
    //echo "ret=$ret<br/>";
    
	$retcode = "";

	if($ret == 0) {
		$result = explode("\n", $output);
		$retcode = $result[0];
	}
	else {
		if($ret <=200)
			$retcode=sprintf("B%03d", $ret);
		else
			$retcode=sprintf("S%03d", $ret);
	}

	if($ret == 0) { //인증결과 복호화 성공

		// 성명 $result[7]
		// 생년월일 $result[8]
		// 성별 $result[9]
		// 처리결과메시지 $result[1]
		// 거래일련번호 $result[2]
		// 인증일시 $result[3]
		// DI $result[4]
		// CI $result[5]
		// 내외국인구분 $result[10]
		// 통신사코드 $result[11]
		// 휴대폰번호 $result[12]
		// 리턴메시지 $result[16]

	 	if ($retcode == "B000") {  // 처리결과코드(성공이면)

			$mb_name = $result[7];
			$mb_birth = $result[8];
			$mb_sex = ($result[9] == 1 ? 'M' : 'F');
			$mb_ident = $result[4];
			$mb_hp = $result[12];

			// 성인인증결과
			$adult_day = date("Ymd", strtotime("-19 years"));
			$adult = ((int)$mb_birth <= (int)$adult_day) ? 1 : 0;

		/*
			$_SESSION[cert_adult] = $adult;
			$_SESSION[cert_ident] = $mb_ident;
		*/

sql_query("update nfor_member set mb_login_datetime=NOW() where mb_no='$mb_no'");

if($is_mobile){
	alert("인증이 확인되어 휴면회원이 해지되었습니다\n다시 로그인해주세요","$nfor[path]/login.php");
} else{

?>
<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script>
$(function() {
	opener.location.href = "/login.php";
    alert("인증이 확인되어 휴면회원이 해지되었습니다\n다시 로그인해주세요");
    window.close();
});
</script>
<?		

}





		} else{
			echo ("<script>alert('본인인증실패 : ".$retcode."'); self.close(); </script>");
		}

	} else {
		//인증결과 복호화 실패
		echo ("<script>alert('인증결과복호화 실패 : ".$ret."'); self.close(); </script>");
	}
?>