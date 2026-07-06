<?php
include_once "path.php";

post_log(basename($PHP_SELF));

//**********************************************************************************
//이니시스가 전달하는 가상계좌이체의 결과를 수신하여 DB 처리 하는 부분 입니다.
//**********************************************************************************

$INIpayHome = $nfor[pg_path];      // 이니페이 홈디렉터리

$TEMP_IP = getenv("REMOTE_ADDR");
$PG_IP  = substr($TEMP_IP,0, 10);

if( $PG_IP == "203.238.37" || $PG_IP == "210.98.138" || $TEMP_IP=="183.109.71.153" || $TEMP_IP=="39.115.212.9"){  //PG에서 보냈는지 IP로 체크

	$msg_id = $msg_id;             //메세지 타입
	$no_tid = $no_tid;             //거래번호
	$no_oid = $no_oid;             //상점 주문번호
	$id_merchant = $id_merchant;   //상점 아이디
	$cd_bank = $cd_bank;           //거래 발생 기관 코드
	$cd_deal = $cd_deal;           //취급 기관 코드
	$dt_trans = $dt_trans;         //거래 일자
	$tm_trans = $tm_trans;         //거래 시간
	$no_msgseq = $no_msgseq;       //전문 일련 번호
	$cd_joinorg = $cd_joinorg;     //제휴 기관 코드

	$dt_transbase = $dt_transbase; //거래 기준 일자
	$no_transeq = $no_transeq;     //거래 일련 번호
	$type_msg = $type_msg;         //거래 구분 코드
	$cl_close = $cl_close;         //마감 구분코드
	$cl_kor = $cl_kor;             //한글 구분 코드
	$no_msgmanage = $no_msgmanage; //전문 관리 번호
	$no_vacct = $no_vacct;         //가상계좌번호
	$amt_input = $amt_input;       //입금금액
	$amt_check = $amt_check;       //미결제 타점권 금액
	$nm_inputbank = $nm_inputbank; //입금 금융기관명
	$nm_input = $nm_input;         //입금 의뢰인
	$dt_inputstd = $dt_inputstd;   //입금 기준 일자
	$dt_calculstd = $dt_calculstd; //정산 기준 일자
	$flg_close = $flg_close;       //마감 전화

	//가상계좌채번시 현금영수증 자동발급신청시에만 전달
	$dt_cshr      = $dt_cshr;       //현금영수증 발급일자
	$tm_cshr      = $tm_cshr;       //현금영수증 발급시간
	$no_cshr_appl = $no_cshr_appl;  //현금영수증 발급번호
	$no_cshr_tid  = $no_cshr_tid;   //현금영수증 발급TID

	$logfile = fopen( $INIpayHome . "/log/result.log", "a+" );
	fwrite( $logfile,"************************************************");
	fwrite( $logfile,"ID_MERCHANT : ".$id_merchant."\r\n");
	fwrite( $logfile,"NO_TID : ".$no_tid."\r\n");
	fwrite( $logfile,"NO_OID : ".$no_oid."\r\n");
	fwrite( $logfile,"NO_VACCT : ".$no_vacct."\r\n");
	fwrite( $logfile,"AMT_INPUT : ".$amt_input."\r\n");
	fwrite( $logfile,"NM_INPUTBANK : ".$nm_inputbank."\r\n");
	fwrite( $logfile,"NM_INPUT : ".$nm_input."\r\n");
	fwrite( $logfile,"************************************************");
	fclose( $logfile );

	$co_cp_id = $no_oid;
	$co_pg_tid = $no_tid;
	$co_pg_price = $amt_input;

	$order = sql_fetch("select * from nfor_campaign_order where co_cp_id='$co_cp_id'");
	if($order[co_pay_step]<>"1"){
		sql_query("update nfor_campaign_order set co_pg_tid='$co_pg_tid', co_pg_price='$co_pg_price', co_pay_step='1', co_pay_datetime=NOW() where co_cp_id='$co_cp_id'");
		sql_query("update nfor_campaign set cp_pay_step='1' where cp_id='$co_cp_id'");
	}

	$order = sql_fetch("select * from nfor_campaign_order where co_cp_id='$co_cp_id'");
	if($order[co_pay_step]=="1"){
		echo "OK";
	}

}
?>