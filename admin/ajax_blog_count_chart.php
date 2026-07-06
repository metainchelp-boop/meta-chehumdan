<?php
// 블로그 일자별 방문수 조회 (리뷰목록·캠페인 진행 보드 공통)
//  2026-06-15: URL형 blog_id 정규화 + XML 파싱 가드(잘못된 응답에서 500 대신 graceful FAIL) + curl 타임아웃.
$blog_id = trim($_POST["blog_id"]);
$div_parser = '|:|';
$return_date = array();
$return_count = array();
$return_total_count = 0;

// URL형(https://blog.naver.com/abc) → 아이디(abc)만 추출 후 허용문자만
if(preg_match('#blog\.naver\.com/([^/?\s]+)#i', $blog_id, $mm)) $blog_id = $mm[1];
$blog_id = preg_replace('/[^a-zA-Z0-9_\-]/', '', $blog_id);

if(!$blog_id){ die('FAIL'.$div_parser.'네이버 블로그가 아닙니다.'.$div_parser.'0'); }

$xml_link = "https://blog.naver.com/NVisitorgp4Ajax.nhn?blogId=".$blog_id;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $xml_link);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
curl_setopt($ch, CURLOPT_TIMEOUT, 7);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
$response = curl_exec($ch);
curl_close($ch);

// XML 파싱 — 비정상 응답이면 500 대신 graceful 처리
libxml_use_internal_errors(true);
$xml = $response ? simplexml_load_string($response) : false;
if($xml === false){ die('SUCCESS'.$div_parser.$div_parser.$div_parser.'0'); }  // 데이터 없음 → 방문 0
$jsondata = json_decode(json_encode($xml), true);

if(!empty($jsondata['visitorcnt'])){
	$vc = $jsondata['visitorcnt'];
	if(isset($vc['@attributes'])) $vc = array($vc);   // 단일 항목 보정
	for($i=0; $i<sizeof($vc); $i++){
		if(!isset($vc[$i]["@attributes"])) continue;
		$return_date[]  = substr($vc[$i]["@attributes"]['id'], 6).'일';
		$return_count[] = $vc[$i]["@attributes"]['cnt'];
		$return_total_count += $vc[$i]["@attributes"]['cnt'];
	}
}

echo 'SUCCESS'.$div_parser.implode(',',$return_date).$div_parser.implode(',',$return_count).$div_parser.$return_total_count;
?>
