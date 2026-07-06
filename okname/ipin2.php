<?php
include_once "path.php";

	header('Content-Type: text/html; charset=UTF-8');

	//아이핀팝업에서 조회한 PERSONALINFO
	@$encPsnlInfo = $_REQUEST["encPsnlInfo"];
	//KCB서버 공개키
	@$WEBPUBKEY = trim($_REQUEST["WEBPUBKEY"]);
	//KCB서버 서명값
	@$WEBSIGNATURE = trim($_REQUEST["WEBSIGNATURE"]);

	//파라미터에 대한 유효성여부를 검증한다.
	if(preg_match('~[^0-9a-zA-Z+/=]~', $encPsnlInfo, $match)) {echo "입력 값 확인이 필요합니다"; exit;}
	if(preg_match('~[^0-9a-zA-Z+/=]~', $WEBPUBKEY, $match)) {echo "입력 값 확인이 필요합니다"; exit;}
	if(preg_match('~[^0-9a-zA-Z+/=]~', $WEBSIGNATURE, $match)) {echo "입력 값 확인이 필요합니다"; exit;}
  
	/*
	echo "$encPsnlInfo<br>";
	echo "$WEBPUBKEY<br>";
	echo "$WEBSIGNATURE<br>";
	*/
  
	//아이핀 서버와 통신을 위한 키파일 생성
	// 파라미터 정의
    // 암호화키 파일 설정 (ipin1.php에서 설정된 값과 동일)
	$keyPath = "$nfor[root_path]/okname/okname.key";	// 운영 키파일

	$cpCode    = $config[cf_check_code];			// 회원사코드 (테스트인 경우 'P00000000000'를 사용하며 운영시 발급받은 회원사코드를 설정)
	$endPointURL = "http://www.ok-name.co.kr/KcbWebService/OkNameService";// 운영 서버
    // 로그 경로 지정 및 권한 부여 (ipin1.php에서 설정된 값과 동일)
	$logPath = "$nfor[root_path]/data/okname/log";
	$options = "SUL";
		
	// 명령어
	$cmd = array($keyPath, $cpCode, $endPointUrl, $WEBPUBKEY, $WEBSIGNATURE, $encPsnlInfo, $logPath, $options);
	//echo "$cmd<br>";
	
	/**************************************************************************
	 * okname 실행
	 **************************************************************************/
	$output = NULL;
	// 실행
	$ret = okname($cmd, $output);
    //echo "ret=$ret<br/>";
    
	$retcode = "";

	if($ret == 0) {
		$result = explode("\n", $output);
		$retcode=sprintf("B%03d", $ret);
	}
	else {
		if($ret <=200)
			$retcode=sprintf("B%03d", $ret);
		else
			$retcode=sprintf("S%03d", $ret);
	}


	if($ret == 0) {
		/*
		encPsnlInfo   $encPsnlInfo
		dupinfo   $result[0]
		coinfo1   $result[1]
		coinfo2   $result[2]
		ciupdate  $result[3]
		virtualno  $result[4]
		cpcode  $result[5]
		realname   $result[6]
		cprequestnumber   $result[7]
		age   $result[8]
		sex   $result[9]
		nationalinfo   $result[10]
		birthdate   $result[11]
		authinfo   $result[12]
		*/

		$mb_name = $result[6];
		$mb_sex = ($result[9] == 1 ? 'M' : 'F');
		$mb_birth = $result[11];


		$_SESSION[okname_asign] = "ipin"; // 인증수단
		$_SESSION[okname_name] = $mb_name;
		$_SESSION[okname_sex] = $mb_sex;
		$_SESSION[okname_birth] = $mb_birth;
		$_SESSION[okname_hp] = "";



if($is_mobile){
	goto_url("$nfor[path]/member_join.php");
} else{

?>
<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script>
$(function() {
	opener.location.href = "/member_join.php";
    alert("실명인증이 완료되었습니다.");
    window.close();
});
</script>
<?

}





	} else {
		//인증결과 복호화 실패
		echo("<script>alert('인증결과복호화 실패 : ".$ret."'); self.close(); </script>");
	}
?>