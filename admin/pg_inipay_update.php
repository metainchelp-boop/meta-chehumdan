<?php
include_once "path.php";

post_log(basename($PHP_SELF));

require_once($nfor[pg_path].'/libs/INIStdPayUtil.php');
require_once($nfor[pg_path].'/libs/HttpClient.php');

$util = new INIStdPayUtil();

try {

	//#############################
	// 인증결과 파라미터 일괄 수신
	//#############################
	//		$var = $_REQUEST["data"];

	//#####################
	// 인증이 성공일 경우만
	//#####################
	if (strcmp("0000", $_REQUEST["resultCode"]) == 0) {

		//echo "####인증성공/승인요청####";
		//echo "<br/>";

		//############################################
		// 1.전문 필드 값 설정(***가맹점 개발수정***)
		//############################################;

		$mid 			= $_REQUEST["mid"];     					// 가맹점 ID 수신 받은 데이터로 설정
		$signKey 		= $nfor[pg_signkey]; 		// 가맹점에 제공된 키(이니라이트키) (가맹점 수정후 고정) !!!절대!! 전문 데이터로 설정금지
		$timestamp 		= $util->getTimestamp();   					// util에 의해서 자동생성
		$charset 		= "UTF-8";        							// 리턴형식[UTF-8,EUC-KR](가맹점 수정후 고정)
		$format 		= "JSON";        							// 리턴형식[XML,JSON,NVP](가맹점 수정후 고정)

		$authToken 		= $_REQUEST["authToken"];   				// 취소 요청 tid에 따라서 유동적(가맹점 수정후 고정)
		$authUrl 		= $_REQUEST["authUrl"];    					// 승인요청 API url(수신 받은 값으로 설정, 임의 세팅 금지)
		$netCancel 		= $_REQUEST["netCancelUrl"];   				// 망취소 API url(수신 받은f값으로 설정, 임의 세팅 금지)

		$mKey 			= hash("sha256", $signKey);					// 가맹점 확인을 위한 signKey를 해시값으로 변경 (SHA-256방식 사용)

		//#####################
		// 2.signature 생성
		//#####################
		$signParam["authToken"] 	= $authToken;  	// 필수
		$signParam["timestamp"] 	= $timestamp;  	// 필수
		// signature 데이터 생성 (모듈에서 자동으로 signParam을 알파벳 순으로 정렬후 NVP 방식으로 나열해 hash)
		$signature = $util->makeSignature($signParam);


		//#####################
		// 3.API 요청 전문 생성
		//#####################
		$authMap["mid"] 			= $mid;   		// 필수
		$authMap["authToken"] 		= $authToken; 	// 필수
		$authMap["signature"] 		= $signature; 	// 필수
		$authMap["timestamp"] 		= $timestamp; 	// 필수
		$authMap["charset"] 		= $charset;  	// default=UTF-8
		$authMap["format"] 			= $format;  	// default=XML


		try {

			$httpUtil = new HttpClient();

			//#####################
			// 4.API 통신 시작
			//#####################

			$authResultString = "";
			
			if ($httpUtil->processHTTP($authUrl, $authMap)) {
				$authResultString = $httpUtil->body;
				//echo "<p><b>RESULT DATA :</b> $authResultString</p>";			//PRINT DATA
			} else {
				//echo "Http Connect Error\n";
				//echo $httpUtil->errormsg;

				throw new Exception("Http Connect Error");
			}

			//############################################################
			//5.API 통신결과 처리(***가맹점 개발수정***)
			//############################################################
			//echo "## 승인 API 결과 ##";

			$resultMap = json_decode($authResultString, true);
			

			$korea = $mid." / ".$timestamp." / ".$resultMap["MOID"]." / ".$resultMap["TotPrice"]." / ".$_REQUEST["mid"]." / ".$resultMap["tid"];

			/*************************  결제보안 추가 2016-05-18 START ****************************/ 
			$secureMap["mid"]		= $mid;							//mid
			$secureMap["tstamp"]	= $timestamp;					//timestemp
			$secureMap["MOID"]		= $resultMap["MOID"];			//MOID
			$secureMap["TotPrice"]	= $resultMap["TotPrice"];		//TotPrice
			
			// signature 데이터 생성 
			$secureSignature = $util->makeSignatureAuth($secureMap);
			/*************************  결제보안 추가 2016-05-18 END ****************************/


			if (strcmp("0000", $resultMap["resultCode"]) == 0){	//  && (strcmp($secureSignature, $resultMap["authSignature"]) == 0) 이니시스쪽에서 제거요청 18-01-23 결제보안 추가 2016-05-18

			   /*****************************************************************************
			   * 여기에 가맹점 내부 DB에 결제 결과를 반영하는 관련 프로그램 코드를 구현한다.  
			   
				 [중요!] 승인내용에 이상이 없음을 확인한 뒤 가맹점 DB에 해당건이 정상처리 되었음을 반영함
						처리중 에러 발생시 망취소를 한다.
			   ******************************************************************************/

				if(isset($resultMap["point"]) && strcmp("1", $resultMap["point"]) == 0) {
					$co_card_point = $resultMap["point"];
				}

				if(isset($resultMap["OCB_Num"]) && !is_null($resultMap["OCB_Num"]) && !empty($resultMap["OCB_Num"])){
					if(in_array($resultMap["OCB_PayPrice"] , $resultMap)){
						$od_card_ocb = $resultMap["OCB_PayPrice"]; // OK CashBag 지불 금액
					} 
				}

				if(isset($resultMap["GSPT_Num"]) && !is_null($resultMap["GSPT_Num"]) && !empty($resultMap["GSPT_Num"])){
					if(in_array($resultMap["GSPT_ApplPrice"] , $resultMap)){
						$od_card_gspt = $resultMap["GSPT_ApplPrice"]; // GS포인트 승인금액
					}
				}

				if(isset($resultMap["UNPT_CardNum"]) && !is_null($resultMap["UNPT_CardNum"]) && !empty($resultMap["UNPT_CardNum"])){
					
					if(in_array($resultMap["UPNT_PayPrice"] , $resultMap)){
						$od_card_unpt = $resultMap["UPNT_PayPrice"]; // U포인트 지불 금액
					}

				}
			
				$co_card_use_point = $resultMap["CARD_UsePoint"];

				$pg_tid = $resultMap["tid"];
				$pg_price = $resultMap["TotPrice"];
				$cp_id = $resultMap["MOID"];
				$pg_method = $resultMap["payMethod"];
				$pg_cash_yn = $resultMap["CSHRResultCode"];
				$pg_cash_authno = "";
				$pg_id = $nfor[pg_id];

				$bank_code = $resultMap["VACT_BankCode"]; // 입금은행코드
				$bank_number = $resultMap["vactBankName"]." ".$resultMap["VACT_Num"]; // 입금은행 계좌번호

				$bank_user = $resultMap["VACT_Name"]; // 예금주명
				$bank_name = $resultMap["VACT_InputName"]; //송금자명
				$bank_date = $resultMap["VACT_Date"];
				$bank_date = date("Y-m-d",strtotime($bank_date));

				if($pg_method=="VBank"){
					$add_sql = ", co_pay_step='4', co_pg_id='$pg_id', co_bank_number='$bank_number', co_bank_code='$bank_code', co_bank_user='$bank_user', co_bank_name='$bank_name', co_bank_expire='$bank_date', co_pg_type='$nfor[pg_type]'";
				} else{
					$add_sql = ", co_pay_step='1', co_card_use_point='$co_card_use_point', co_card_point='$co_card_point', od_card_ocb='$od_card_ocb', od_card_gspt='$od_card_gspt', od_card_unpt='$od_card_unpt'";
					$add_sql .= ", co_pg_id='$pg_id', co_pg_type='$nfor[pg_type]', co_pg_tid='$pg_tid', co_pg_price='$pg_price', co_pay_datetime=NOW()";					
				}


				$campaign = sql_fetch("select * from nfor_campaign where cp_id='$cp_id'");
				$co_cp_subject = addslashes($campaign[cp_subject]);

				$co_campaign_price = $config[cf_campaign_price]*$campaign[cp_recruit];
				$co_point_price = $campaign[cp_point]*$campaign[cp_recruit];
				$co_total_price = $co_campaign_price+$co_point_price;

				
				if($pg_method=="VBank"){
					$co_payment_type = "vbanking";
				} elseif($pg_method=="Card"){
					$co_payment_type = "card";
				} else{
					$co_payment_type = "";
				}

				sql_query("insert nfor_campaign_order set co_payment_type='$co_payment_type', co_supply_no='$campaign[cp_supply_no]', co_md_no='$campaign[cp_md_no]', co_mb_no='$member[mb_no]', co_pg_method='$pg_method', co_total_price='$co_total_price', co_point_price='$co_point_price', co_campaign_price='$co_campaign_price', co_cp_type='$campaign[cp_type]', co_cp_reward_type='$campaign[cp_reward_type]', co_cp_recruit='$campaign[cp_recruit]', co_cf_campaign_price='$config[cf_campaign_price]', co_cp_point='$campaign[cp_point]', co_mb_black='$member[mb_black]', co_mb_id='$member[mb_id]', co_mb_name='$member[mb_name]', co_mb_nick='$member[mb_nick]', co_mb_hp='$member[mb_hp]', co_mb_email='$member[mb_email]', co_cp_subject='$co_cp_subject', co_cp_id='$cp_id', co_datetime=NOW() $add_sql");

				if($pg_method=="VBank"){
					sql_query("update nfor_campaign set cp_pay_step='4' where cp_id='$cp_id'");
				} else{
					sql_query("update nfor_campaign set cp_pay_step='1' where cp_id='$cp_id'");
				}


				$result_url = "campaign_order_list.php";

			} else{
				alert( $resultMap["resultCode"]." ".$resultMap["resultMsg"] );
				//결제보안키가 다른 경우.
				/* 이니시스쪽에서 제거 요청 18-01-23
				if (strcmp($secureSignature, $resultMap["authSignature"]) != 0) {
					alert( "데이터 위변조 체크 실패".$korea );

					//망취소
					if(strcmp("0000", $resultMap["resultCode"]) == 0) {
						throw new Exception("데이터 위변조 체크 실패");
					}
				} else {
					alert( $resultMap["resultCode"]." ".$resultMap["resultMsg"] );
				}
				*/				
			}

			goto_url($result_url);

			// 수신결과를 파싱후 resultCode가 "0000"이면 승인성공 이외 실패
			// 가맹점에서 스스로 파싱후 내부 DB 처리 후 화면에 결과 표시
			// payViewType을 popup으로 해서 결제를 하셨을 경우
			// 내부처리후 스크립트를 이용해 opener의 화면 전환처리를 하세요
			//throw new Exception("강제 Exception");
		} catch (Exception $e) {
			// $s = $e->getMessage() . ' (오류코드:' . $e->getCode() . ')';
			//####################################
			// 실패시 처리(***가맹점 개발수정***)
			//####################################
			//---- db 저장 실패시 등 예외처리----//
			$s = $e->getMessage() . ' (오류코드:' . $e->getCode() . ')';
			//echo $s;

			//#####################
			// 망취소 API
			//#####################

			$netcancelResultString = ""; // 망취소 요청 API url(고정, 임의 세팅 금지)
			
			if ($httpUtil->processHTTP($netCancel, $authMap)) {
				$netcancelResultString = $httpUtil->body;
			} else {
				echo "Http Connect Error\n";
				echo $httpUtil->errormsg;

				throw new Exception("Http Connect Error");
			}

			echo "<br/>## 망취소 API 결과 ##<br/>";
			
			/*##XML output##*/
			//$netcancelResultString = str_replace("<", "&lt;", $$netcancelResultString);
			//$netcancelResultString = str_replace(">", "&gt;", $$netcancelResultString);
			
			// 취소 결과 확인
			echo "<p>". $netcancelResultString . "</p>";
		}

	} else {

		//#############
		// 인증 실패시
		//#############
		alert("인증실패");
		//echo "<pre>" . var_dump($_REQUEST) . "</pre>";
	}

} catch (Exception $e) {
	$s = $e->getMessage() . ' (오류코드:' . $e->getCode() . ')';
	alert($s);
}
?>