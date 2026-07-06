<?php
include_once "path.php";

	$MDL_TKN	=	$_REQUEST["mdl_tkn"];			// 모듈토큰

	$CP_CD = $config[cf_check_code];				// 회원사코드(아이디)
	
	$target = "PROD"; // 테스트="TEST", 운영="PROD"


	$license = $nfor['root_path']."/okcert3/".$CP_CD."_IDS_01_".$target."_AES_license.dat";
	
	$params = '{ "MDL_TKN":"'.$MDL_TKN.'" }';
    
	
	$svcName = "IDS_HS_POPUP_RESULT";
	$out = NULL;
	
	// okcert3 실행
	$ret = okcert3_u($target, $CP_CD, $svcName, $params, $license, $out);  // UTF-8
	
	/**************************************************************************
	okcert3 응답 정보
	**************************************************************************/
	$RSLT_CD = "";						// 결과코드
	$RSLT_MSG = "";						// 결과메시지
	$TX_SEQ_NO = "";					// 거래일련번호
	
	$RSLT_NAME		= "";
	$RSLT_BIRTHDAY 	= "";
	$RSLT_SEX_CD	= "";
	$RSLT_NTV_FRNR_CD="";
	
	$DI				= "";
	$CI				= "";
	$CI_UPDATE		= "";
	$TEL_COM_CD		= "";
	$TEL_NO			= "";
	
	$RETURN_MSG 	= "";				// 리턴메시지

	if($ret == 0) {		// 함수 실행 성공일 경우 변수를 결과에서 얻음
		//$out = iconv("euckr","utf-8",$out);		// 인코딩 icnov 처리. okcert3 호출(EUC-KR)일 경우에만 사용 (json_decode가 UTF-8만 가능)
		$output = json_decode($out,true);		// $output = UTF-8
		
		$RSLT_CD	= $output['RSLT_CD'];
		$RSLT_MSG  = $output["RSLT_MSG"];	// 다시 EUC-KR 로 변환
		
		if(isset($output["TX_SEQ_NO"])) $TX_SEQ_NO = $output["TX_SEQ_NO"]; // 필요 시 거래 일련 번호 에 대하여 DB저장 등의 처리
		if(isset($output["RETURN_MSG"]))  $RETURN_MSG  = $output['RETURN_MSG'];
		
		if( $RSLT_CD == "B000" ) { // B000 : 정상건
			$RSLT_NAME  = $output['RSLT_NAME']; // 다시 EUC-KR 로 변환
			$RSLT_BIRTHDAY	= $output['RSLT_BIRTHDAY'];
			$RSLT_SEX_CD	= $output['RSLT_SEX_CD'];
			$RSLT_NTV_FRNR_CD=$output['RSLT_NTV_FRNR_CD'];
			
			$DI				= $output['DI'];
			$CI 			= $output['CI'];
			$CI_UPDATE		= $output['CI_UPDATE'];
			$TEL_COM_CD		= $output['TEL_COM_CD'];
			$TEL_NO			= $output['TEL_NO'];
		}
	}

	if($ret == 0) {
		//인증결과 복호화 성공
		// 인증결과를 확인하여 페이지분기등의 처리를 수행해야한다.
	 	if ($RSLT_CD == "B000") {




			$mb_name = $RSLT_NAME;
			$mb_birth = substr($RSLT_BIRTHDAY,0,4);
			$mb_sex = $RSLT_SEX_CD;
			$mb_ident = $DI;
			$mb_hp = $TEL_NO;

			// 성인인증결과
			$adult_day = date("Ymd", strtotime("-19 years"));
			$adult = ((int)$mb_birth <= (int)$adult_day) ? 1 : 0;

		/*
			$_SESSION[cert_adult] = $adult;
			$_SESSION[cert_ident] = $mb_ident;
		*/

$_SESSION[okname_asign] = "hp"; // 인증수단
$_SESSION[okname_name] = $mb_name;
$_SESSION[okname_sex] = $mb_sex;
$_SESSION[okname_birth] = $mb_birth;
$_SESSION[okname_hp] = $mb_hp;





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
<?php
}




		}
		else {
			echo ("<script>alert('본인인증실패 : ".$RSLT_CD." : ".$RSLT_MSG."'); fncOpenerSubmit();</script>");
		}
	} else {
		//인증결과 복호화 실패
		echo ("<script>alert('인증결과복호화 실패 : ".$ret."'); self.close(); </script>");
	}
?>