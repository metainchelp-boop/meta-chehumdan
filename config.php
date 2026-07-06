<?php	// 환경설정

if(function_exists("date_default_timezone_set")){
	date_default_timezone_set("Asia/Seoul");
}

$nfor[version] = "7.0.7.160701";
$nfor[charset] = "utf-8";
$nfor[ymd] = date("Y-m-d");
$nfor[his] = date("H:i:s");
$nfor[ymdhis] = date("Y-m-d H:i:s");

$nfor[money_ymdhis] = date("Y-m-d",strtotime("+12 month"));

$nfor[cookie_domain] = "";

/*
본솔루션은 엔포(NFOR)에서 제작된 솔루션으로
저작권 및 상표권 등록을 모두 완료하여 공식웹사이트를 통해서 유료 판매되고 있습니다.

1도메인 1라이센스 정책으로 구매하신 분에 한해 1개의 사이트에 한해서 라이센스 등록을 별도로 해드리고 있습니다.
솔루션의 재판매 및 소스 무단복사를 금지하고 있으며
라이센스 관리를 위해 엔포(NFOR)에서 지정한 법무법인을 통해서 솔루션의 저작권관리를 진행하고 있습니다.

라이센스 위반의 경우 대행사를 통해서 매우 엄격하게 관리되고 있기 때문에
절대 협의되지 않으며 반드시 1도메인1라이센스 정책을 준수하여 이용해 주셔야 합니다.

귀하는 config.php(환경설정 파일)수정을 통해 사전에 라이센스/저작권 위반에 대한 고지를 사전에 인지하였음을 확인합니다.

위 사항은 대행사를 통한 소송등 진행시 모든 법적 책임과 손해배상을 의미합니다.

홈페이지 : http://nfor.net
전화 : 1899-0320
*/


$nfor[sql_host] = "localhost";
$nfor[sql_user] = "yoosub92";
$nfor[sql_password] = "dbslzhs12@@";
$nfor[sql_db] = "yoosub92";


$nfor[editor_path] = "$nfor[path]/editor";
$nfor[item_load] = "item.php";

$nfor[https] = "0";


$nfor[test] = "0";	// 테스트모드




$nfor[test_password] = "1q2w3e4r"; // 테스트 계정 비밀번호(admin)
$nfor[test_msg] = "데모모드(테스트모드)에서는 관리자페이지의\\n장난 및 악의적 변경등의 방지를 위해 관리자모드 이용이 제한됩니다.\\n수일내로 별도의 테스트 환경이 제공될 예정이니 양해부탁드립니다\\n\\n솔루션 구매 및 문의 전화 : 1899-0320";

$nfor[pg_type] = "inipay";
$nfor[pg_id] = "INIpayTest";	// 변경 INIpayTest
$nfor[pg_id_m] = "INIpayTest";

$nfor[pg_id_ies] = "INIpayTest";
$nfor[pg_id_m_ies] = "INIpayTest";

$nfor[pg_signkey] = "SU5JTElURV9UUklQTEVERVNfS0VZU1RS";
$nfor[pg_signkey_ies] = "SU5JTElURV9UUklQTEVERVNfS0VZU1RS"; 

$nfor[root_path] = "/yoosub92/www";
$nfor[pg_path] = $nfor[root_path]."/pg/".$nfor[pg_type];

/*
nfor_order
pay_step
0 - 결제대기
1 - 결제완료
2 - 취소신청
3 - 취소완료
4 - 입금대기
5 - 입금대기취소

delivery_step
0 - 배송대기 1
1 - 배송준비 
2 - 배송완료 1
3 - 반품신청
4 - 반품완료
5 - 주문취소
*/

$nfor[id_secret] = "1"; // 아이디비공개(상품문의/상품평)

if(substr($HTTP_HOST,0,4)=="www."){
	$nfor[host_domain] = substr($HTTP_HOST,4);
} else{
	$nfor[host_domain] = $HTTP_HOST;
}

$nfor[http_url] = "http://".$HTTP_HOST;
$nfor[https_url] = "https://".$HTTP_HOST;

/*
적립금
money_type
0 - 임의입력
1 - 회원가입
2 - 상품구매
4 - 베스트 구매후기작성적립
5 - 구매후기작성적립
6 - 포토 구매후기작성적립
7 - 적립금 상품구매 취소
8 - 적립금 상품구매 부분취소
9 - 추천인입력
10 - 추천받음 
11 - 회원탈퇴 
88 - 이전적립
66 - 커뮤니티
90 - 출석체크
*/

$nfor[dy_group] = "1"; // 묶음배송이용하면 1 개별배송은 0


$nfor[skin_path] = $nfor[path]."/skin/demo/";



$nfor[m_skin_path] = $nfor[path]."/skin/nfor/";




$nfor[admin_path] = $nfor[path]."/admin/";



$nfor[default_lat] = "37.5325989619262";
$nfor[default_lng] = "126.63399696350098";

$nfor[delivery_step] = "0";

$nfor[script_name] = array("메인","상품리스트","상품상세","장바구니","주문","주문완료","로그인","회원가입");
$nfor[script_file] = array("index.php","item_list.php","item.php","cart.php","cart_order.php","cart_order_result.php","login.php","member_join.php");

$nfor[admin_tab] = array(""=>"선택","환경설정" => "환경설정", "캠페인관리"=>"캠페인관리", "신청서관리"=>"신청서관리", "회원/발송관리"=>"회원/발송관리", "사이트관리"=>"사이트관리", "통계/데이터"=>"통계/데이터", "메타테크관리"=>"메타테크관리");
$nfor[admin_tab_ico] = array(""=>"선택","환경설정" => "glyphicon-cog", "캠페인관리"=>"glyphicon-inbox", "신청서관리"=>"glyphicon-inbox", "회원/발송관리"=>"glyphicon-user", "사이트관리"=>"glyphicon-home", "통계/데이터"=>"glyphicon-signal");


$nfor[jwt_path] = "$nfor[path]/google/vendor/firebase/php-jwt/src/";
$nfor[jwt_key] = "nfor_key";

$nfor[jwt_token_time] = "600";
$nfor[jwt_refresh_token_time] = "1200";








$nfor[cp_media] = array(""=>"전체", "blog"=>"블로그", "instagram"=>"인스타그램", "youtube" => "유튜브", "shop" => "쇼핑몰");
$nfor[cp_type] = array("전체","배송형","방문형","배송형+리워드","방문형+리워드");


//메타테크 옵션 1 옵션 2 
$nfor[meta_op_a] = array("플레이스","스토어");
$nfor[meta_op_b] = array("정답맞추기(오프라인)","정답맞추기(온라인)","공유미션(오프라인)","공유미션(온라인)","저장하기(오프라인)","알림받기(오프라인)","알림받기(온라인)","상품찜");

?>