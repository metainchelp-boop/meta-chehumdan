<?php	// 정의함수

function encryptResidentNumber($residentNumber, $key) {
    // 암호화 알고리즘 및 옵션 설정
    $cipher = "aes-256-cbc";
    $ivLength = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivLength);

    // 주민등록번호를 암호화
    $encrypted = openssl_encrypt($residentNumber, $cipher, $key, 0, $iv);

    // 초기화 벡터(iv)와 함께 암호문을 반환
    return base64_encode($iv . $encrypted);
}

// 저장된 주민등록번호를 복호화하여 출력하는 함수
function decryptResidentNumber($encryptedData, $key) {
    // 암호화 알고리즘 및 옵션 설정
    $cipher = "aes-256-cbc";
    $ivLength = openssl_cipher_iv_length($cipher);

    // Base64 디코딩 및 초기화 벡터와 암호문 분리
    $data = base64_decode($encryptedData);
    $iv = substr($data, 0, $ivLength);
    $encrypted = substr($data, $ivLength);

    // 주민등록번호를 복호화
    $decrypted = openssl_decrypt($encrypted, $cipher, $key, 0, $iv);

    // 복호화된 주민등록번호 반환
    return $decrypted;
}

function nfor_tag_out($inputArray, $access=array()) {
    if (is_array($inputArray)) {
        foreach ($inputArray as $key => &$value) {

			if($access and in_array($key, $access)) {
				
			} else{
				$value = strip_tags($value);
				$value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
			}

        }
        return $inputArray;
    } else {
        return $inputArray;
    }
}

function make_timestamp(){
	global $member, $_SERVER;

	$timestamp = date("YmdHis").substr(microtime(),2,6);

	if($member['mb_no']){
		$timestamp .= "||".$member['mb_no'];
	} else{
		$timestamp .= "||".$_SERVER['REMOTE_ADDR'];
	}
	return $timestamp;
}

function nfor_get_browser($agent){
    $agent = strtolower($agent);

    if (preg_match("/msie ([1-9][0-9]\.[0-9]+)/", $agent, $m)) { $return = 'MSIE '.$m[1]; }
    else if(preg_match("/firefox/", $agent))            { $return = "FireFox"; }
    else if(preg_match("/chrome/", $agent))             { $return = "Chrome"; }
    else if(preg_match("/x11/", $agent))                { $return = "Netscape"; }
    else if(preg_match("/opera/", $agent))              { $return = "Opera"; }
    else if(preg_match("/gec/", $agent))                { $return = "Gecko"; }
    else if(preg_match("/bot|slurp/", $agent))          { $return = "Robot"; }
    else if(preg_match("/internet explorer/", $agent))  { $return = "IE"; }
    else if(preg_match("/mozilla/", $agent))            { $return = "Mozilla"; }
    else { $return = "기타"; }

    return $return;
}

function nfor_get_os($agent){
    $agent = strtolower($agent);

	if (preg_match("/windows 98/", $agent))                 { $return = "98"; }
    else if(preg_match("/windows 95/", $agent))             { $return = "95"; }
    else if(preg_match("/windows nt 4\.[0-9]*/", $agent))   { $return = "NT"; }
    else if(preg_match("/windows nt 5\.0/", $agent))        { $return = "2000"; }
    else if(preg_match("/windows nt 5\.1/", $agent))        { $return = "XP"; }
    else if(preg_match("/windows nt 5\.2/", $agent))        { $return = "2003"; }
    else if(preg_match("/windows nt 6\.0/", $agent))        { $return = "Vista"; }
    else if(preg_match("/windows nt 6\.1/", $agent))        { $return = "Windows7"; }
    else if(preg_match("/windows nt 6\.2/", $agent))        { $return = "Windows8"; }
    else if(preg_match("/windows 9x/", $agent))             { $return = "ME"; }
    else if(preg_match("/windows ce/", $agent))             { $return = "CE"; }
    else if(preg_match("/mac/", $agent))                    { $return = "MAC"; }
    else if(preg_match("/linux/", $agent))                  { $return = "Linux"; }
    else if(preg_match("/sunos/", $agent))                  { $return = "sunOS"; }
    else if(preg_match("/irix/", $agent))                   { $return = "IRIX"; }
    else if(preg_match("/phone/", $agent))                  { $return = "Phone"; }
    else if(preg_match("/bot|slurp/", $agent))              { $return = "Robot"; }
    else if(preg_match("/internet explorer/", $agent))      { $return = "IE"; }
    else if(preg_match("/mozilla/", $agent))                { $return = "Mozilla"; }
    else { $return = "기타"; }

    return $return;
}

function nfor_encrypt($str, $secret_key="nfor_key", $secret_key2="nfor_key"){
    $key = hash("sha256", $secret_key);
    $iv = substr(hash("sha256", $secret_key2), 0, 16)    ;

    return str_replace("=", "", base64_encode(
                 openssl_encrypt($str, "AES-256-CBC", $key, 0, $iv))
    );
}

function nfor_decrypt($str, $secret_key="nfor_key", $secret_key2="nfor_key"){
    $key = hash("sha256", $secret_key);
    $iv = substr(hash("sha256", $secret_key2), 0, 16);

    return openssl_decrypt(
            base64_decode($str), "AES-256-CBC", $key, 0, $iv
    );
}

function member_sns_type($data){
	if($data[mb_naver_id]){
		$return = "네이버";
	} elseif($data[mb_facebook_id]){
		$return = "페이스북";
	} elseif($data['mb_apple_id']){
		$return = "애플";
	} elseif($data[mb_google_id]){
		$return = "구글";
	} elseif($data[mb_kakao_id]){
		$return = "카카오";
	} else{
		$return = "";
	}
	return $return;
}	

function sns_id_check($member){
	if($member['mb_naver_id'] or $member['mb_facebook_id'] or $member['mb_kakao_id'] or $member['mb_google_id'] or $member['mb_apple_id']){
		return true;
	} else{
		return false;
	}
}

function logout(){
	session_unset();
	session_destroy();
	set_cookie("ck_mb_no", "", 0);
	set_cookie("ck_auto", "", 0);
}

function demo_check(){
	global $nfor;
	if($nfor[test]) alert($nfor[test_msg]);
}

function demo_check_json(){
	global $nfor;
	if($nfor[test]) json_return(stripcslashes($nfor[test_msg]),"test");
}

// 약관호출
function agreement($code){
	$agreement = sql_fetch("select * from nfor_agreement where ag_code='$code'");
	return $agreement[ag_memo];
}

// 최근검색어 저장여부
function recent_save(){
	global $member, $_SESSION;
	if($member[mb_no]){	
		if($member[mb_recent_save]){
			$recent_save = "0";
		} else{
			$recent_save = "1";
		}
	} else{
		if($_SESSION[guest_recent_save]){
			$recent_save = "0";
		} else{
			$recent_save = "1";
		}
	}
	return $recent_save;
}

// 인기검색어 업데이트
function keyword_update(){ // 리눅스 크론을 통해 업데이트함

	global $config, $nfor;

	
	if($config[cf_keyword_type]=="0" or $config[cf_keyword_type]=="2"){ // 0 실제 검색데이터 기준으로 출력 / 2 실제 검색데이터 + 관리자입력 혼용출력

		$time1 = $config[cf_keyword_hour];
		$time2 = $config[cf_keyword_hour]*2;
		$se_datetime = date("Y-m-d H:i:s",strtotime("-$time1 hours")); // 전날
		$se_datetime_history = date("Y-m-d H:i:s",strtotime("-$time2 hours")); // 전전날 

		$i = 1;
		$que = sql_query("select se_keyword, count(*) as se_cnt from nfor_search where se_datetime < '$se_datetime' and se_datetime >= '$se_datetime_history' group by se_keyword order by se_cnt desc limit 100"); // 이전데이터를 가져옴
		while($data = sql_fetch_array($que)){
			$prev_rank[$data[se_keyword]] = $i;
			$i++;
		}

		$i = 1;
		$que = sql_query("select se_keyword, count(*) as se_cnt from nfor_search where se_datetime >= '$se_datetime' group by se_keyword order by se_cnt desc limit 20"); // 최근데이터를 가져옴
		while($data = sql_fetch_array($que)){
			if($i==$prev_rank[$data[se_keyword]]){ // 똑같음
				$print_rank = "middle";
				$chang_cnt = "";
			} elseif($i<$prev_rank[$data[se_keyword]]){ // 순위가올라갔다면
				$print_rank = "up";
				$chang_cnt = $prev_rank[$data[se_keyword]]-$i;
			} elseif($i>$prev_rank[$data[se_keyword]] and $prev_rank[$data[se_keyword]]){ // 순위가떨어졌다면						
				$print_rank = "down";
				$chang_cnt = $i-$prev_rank[$data[se_keyword]];
			} else{
				$print_rank = "new";
				$chang_cnt = "";
			}

			$echo_keyword[] = $data[se_keyword];
			$echo_rank[] = $print_rank;
			$echo_cnt[] = $chang_cnt;

			$i++;
		}

	}

	$limit = 21-$i;
	if(($config[cf_keyword_type]=="1" or $config[cf_keyword_type]=="2") and $limit){ // 1 관리자입력 기준으로 출력 / 2 실제 검색데이터 + 관리자입력 혼용출력
		$que = sql_query("select * from nfor_keyword where 1 order by ke_rank asc limit 20"); // 최근데이터를 가져옴
		while($data = sql_fetch_array($que)){

			if(!$data[ke_current]){ // 똑같음
				$print_rank = "middle";
				$chang_cnt = "";
			} elseif($data[ke_current]>0){ // 순위가올라갔다면
				$print_rank = "up";
				$chang_cnt = $data[ke_current];
			} elseif($data[ke_current]<0){ // 순위가떨어졌다면						
				$print_rank = "down";
				$chang_cnt = $data[ke_current];
			} else{
				$print_rank = "new";
				$chang_cnt = "";
			}

			$echo_keyword[] = $data[ke_keyword];
			$echo_rank[] = $print_rank;
			$echo_cnt[] = $chang_cnt;

			$i++;
		}
	}

	sql_query("truncate table `nfor_search_crontab`");

	for($i=0; $i<count($echo_keyword); $i++){

		$sc_keyword = addslashes($echo_keyword[$i]);
		$sc_rank = $echo_rank[$i];
		$sc_cnt = $echo_cnt[$i];
		sql_query("insert nfor_search_crontab set sc_keyword='$sc_keyword', sc_rank='$sc_rank', sc_cnt='$sc_cnt' ");

	}

}

function file_move($dir1,$dir2,$filename){
	global $nfor;
	@copy("$nfor[path]/data/$dir1/$filename", "$nfor[path]/data/$dir2/$filename");
	@unlink("$nfor[path]/data/$dir1/$filename");
}

function member_info($mb_no, $array){
	$mb = sql_fetch("select * from nfor_member where mb_no='$mb_no'");
	$str = "";
	for($i=0; $i<count($array); $i++){
		$str .= "<br>";
		$str .= $mb[$array[$i]];
	}
	return $str;
}





function send_notification($tokens, $data, $type){
	global $api;

	if(is_array($tokens)){
		$fields[registration_ids] = $tokens; // 1000까지
	}else{
		$fields[to] = $tokens;
	}

	if($type=="ios"){	// 아이폰일때만 해당 필드 필요
		$data[message] = $data[memo];
		$notification[body] = $data[memo];
		$notification[badge] = "1";
		$notification[sound] = "default";
		$fields[notification] = $notification;
	}

	$fields[priority] = 'high';
	$fields[content_available] = true; // 알림이나 메시지가 전송될 때 이 매개변수가 true로 설정되어 있으면 비활성 클라이언트 앱이 활성 상태로 전환됩니다

	$fields[data] = $data;		 

	$headers = array(
		'Authorization:key =' . $api[api_firebase_key_id],
		'Content-Type: application/json'
		);

   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
   curl_setopt($ch, CURLOPT_POST, true);
   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
   $result = curl_exec($ch);           
   if ($result === FALSE) {
	   die('Curl failed: ' . curl_error($ch));
   }
   curl_close($ch);
   return $result;
}

function admin_down($folder,$file,$name=""){
	global $nfor;
	$str = "<a href=\"$nfor[path]/down.php?folder=$folder&file=$file";
	if($name){
		$str .= "&name=$name";
	}
	$str .= "\">";
	$str .= "#첨부파일 다운로드";
	if($name){
		$str .= " - $name";
	}
	$str .= "</a>";
	return $str;
}

function mb_nick($mb_id){
	$mb = sql_fetch("select mb_nick from nfor_member where mb_id='$mb_id'");
	return $mb[mb_nick];
}

function password_change($mb_no, $password){
	global $member_config;
	$row = sql_fetch("select * from nfor_member where mb_no = '$mb_no'");
	$mb_password = sql_password($row[$member_config[cf_mb_id_type]], $password);

	sql_query("update nfor_member set mb_password='$mb_password' where mb_no='$mb_no'");
}

function member_asign($mb_no){
	sql_query("update nfor_member set mb_asign='1' where mb_no='$mb_no'");
}

function member_delete($mb_no){
	sql_query("update nfor_member set mb_out_datetime=NOW(), mb_password='', mb_name='', mb_email='', mb_level='', mb_birthday_type='', mb_birthday='', mb_sex='', mb_hp='', mb_tel='', mb_zipcode='', mb_addr1='', mb_addr2='', mb_mailling='', mb_sms='', mb_cp_name='', mb_cp_photo='', mb_photo='', mb_ident='', mb_bank_name='', mb_bank_account='', mb_bank_agree='', mb_cp_bank_name='', mb_cp_bank_account='', mb_cp_bank_account_holder='', mb_cp_ceo='', mb_cp_number='', mb_cp_zipcode='', mb_cp_addr1='', mb_cp_addr2='', mb_nick='', mb_facebook_id='', mb_kakao_id='', mb_naver_id='', mb_cp_type1='', mb_cp_type2='', mb_valid_date='', mb_join_channel='', mb_admin='', mb_asign='' where mb_no='$mb_no'");
}

function member_leave($mb_no,$mb_secession="관리자에 의한 삭제"){
	$mb = sql_fetch("select * from nfor_member where mb_no='$mb_no'");
	sql_query("update nfor_member set mb_leave_datetime=NOW(), mb_secession='$mb_secession' where mb_no='$mb_no'");
	nfor_send("member_leave",$mb[mb_email],$mb[mb_hp],$mb[mb_no]);
}

function member_leave_cancel($mb_no){
	$mb = sql_fetch("select * from nfor_member where mb_no='$mb_no'");
	sql_query("update nfor_member set mb_leave_datetime='', mb_secession='' where mb_no='$mb_no'");
}

function post_log($log_file){
	global $_REQUEST;

	foreach ($_REQUEST as $keyname => $value) {
		if($value) $log_text .= "$keyname=$value ";
	}
	sql_query("insert nfor_log set log_file='$log_file', log_text='$log_text', log_datetime=NOW()");

}

function sql_range($field, $start,$end){
	if($field){
		if($start or $end){
			if($start and $end){
				$sql_search .= " and $field >= '$start' and $field <= '$end'";
			} elseif($start){
				$sql_search .= " and $field >= '$start'";
			} elseif($end){
				$sql_search .= " and $field <= '$end'";
			} else{

			}
		}
	}
	return $sql_search;
}

function meta_tag(){
	global $config, $is_mobile, $campaign, $PHP_SELF, $nfor;
	$return = "<meta charset=\"utf-8\">\n";

	$return .= "<meta property=\"og:type\" content=\"website\">\n";


	if($campaign[cp_id] and basename($PHP_SELF)=="campaign.php"){
		$return .= "<meta property=\"og:url\" content=\"{$campaign[cp_url]}\" />\n";
		$return .= "<meta property=\"og:title\" content=\"{$campaign[cp_subject]}\" />\n";
		$return .= "<meta property=\"og:description\" content=\"{$campaign[cp_description]}\" />\n";
		$return .= "<meta property=\"og:image\" content=\"{$campaign[cp_img_url]}\" />\n";
		$return .= "<meta property=\"og:image:type\" content=\"image/jpeg\" />\n";
	} else{
		$return .= "<meta property=\"og:url\" content=\"{$config[cf_url]}\" />\n";
		$return .= "<meta property=\"og:title\" content=\"{$config[cf_title]}\" />\n";
		$return .= "<meta property=\"og:description\" content=\"{$config[cf_meta_description]}\" />\n";
		if($config[cf_image]){
			$image = "$nfor[url]/data/og_image/$config[cf_image]";
			$return .= "<meta property=\"og:image\" content=\"{$image}\" />\n";
			$return .= "<meta property=\"og:image:type\" content=\"image/jpeg\" />\n";
		}
	}
	$return .= "<meta name=\"description\" content=\"{$config[cf_meta_description]}\">\n";

	$return .= "<meta name=\"keywords\" content=\"{$config[cf_meta_keyword]}\" />\n";

	if($is_mobile){
		$return .= "<meta name=\"format-detection\" content=\"telephone=no\">\n";
		$return .= "<meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=no; target-densityDpi=device-dpi\" />\n";
	} else{
		$return .= "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0\" />\n"; // , user-scalable=0
	}
	return $return;
}

function external_script($position){ // 외부스크립트
	global $script;
	for($i=0; $i<count($script[$position]); $i++){
		$return .= $script[$position][$i]."\n";
	}
	return $return;
}

function fa_group_fnc($fa_group){
	$data = sql_fetch("select gp_name from nfor_faq_group where gp_id='$fa_group'");
	return $data[gp_name];
}



// 한페이지에 보여줄 행, 현재페이지, 총페이지수, URL
function layer_paging($write_pages, $cur_page, $total_page)
{

    $str = '';
    if ($cur_page > 1) {
        $str .= '<li><a data-page="1">처음</a></li>'.PHP_EOL;
    }

    $start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
    $end_page = $start_page + $write_pages - 1;

    if ($end_page >= $total_page) $end_page = $total_page;


    if ($start_page > 1) $str .= '<li><a data-page="'.($start_page-1).'">이전</a></li>'.PHP_EOL;

    if ($total_page > 1) {
        for ($k=$start_page;$k<=$end_page;$k++) {
            if ($cur_page != $k)
                $str .= '<li><a data-page="'.$k.'">'.$k.'</a></li>'.PHP_EOL;
            else
                $str .= '<li class="active"><a data-page="'.$k.'">'.$k.'</a></li>'.PHP_EOL;
        }
    }

    if ($total_page > $end_page) $str .= '<li><a data-page="'.($end_page+1).'">다음</a></li>'.PHP_EOL;



    if ($cur_page < $total_page) {
        $str .= '<li><a data-page="'.$total_page.'">맨끝</a></li>'.PHP_EOL;
    }

    if ($str)
        return "<nav aria-label=\"Page navigation\"><ul class=\"nfor_pagination pagination pagination-sm\">{$str}</ul></nav>";
    else
        return "";
}


// 한페이지에 보여줄 행, 현재페이지, 총페이지수, URL
function layer_get_paging($write_pages, $cur_page, $total_page)
{

    $str = '';
    if ($cur_page > 1) {
        $str .= '<li><a href="javascript:layer_page_fnc(1)">처음</a></li>'.PHP_EOL;
    }

    $start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
    $end_page = $start_page + $write_pages - 1;

    if ($end_page >= $total_page) $end_page = $total_page;


    if ($start_page > 1) $str .= '<li><a href="javascript:layer_page_fnc('.($start_page-1).')">이전</a></li>'.PHP_EOL;

    if ($total_page > 1) {
        for ($k=$start_page;$k<=$end_page;$k++) {
            if ($cur_page != $k)
                $str .= '<li><a href="javascript:layer_page_fnc('.$k.')">'.$k.'</a></li>'.PHP_EOL;
            else
                $str .= '<li class="active"><a>'.$k.'</a></li>'.PHP_EOL;
        }
    }

    if ($total_page > $end_page) $str .= '<li><a href="javascript:layer_page_fnc('.($end_page+1).')">다음</a></li>'.PHP_EOL;



    if ($cur_page < $total_page) {
        $str .= '<li><a href="javascript:layer_page_fnc('.$total_page.')">맨끝</a></li>'.PHP_EOL;
    }

    if ($str)
        return "<nav aria-label=\"Page navigation\"><ul class=\"pagination pagination-sm\">{$str}</ul></nav>";
    else
        return "";
}


// 한페이지에 보여줄 행, 현재페이지, 총페이지수, URL
function get_paging($write_pages, $cur_page, $total_page, $url, $add="")
{
    //$url = preg_replace('#&amp;page=[0-9]*(&amp;page=)$#', '$1', $url);
    $url = preg_replace('#&page=[0-9]*#', '', $url) . '&amp;page=';

    $str = '';
    if ($cur_page > 1) {
        $str .= '<li><a href="'.$url.'1'.$add.'">처음</a></li>'.PHP_EOL;
    }

    $start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
    $end_page = $start_page + $write_pages - 1;

    if ($end_page >= $total_page) $end_page = $total_page;

    if ($start_page > 1) $str .= '<li><a href="'.$url.($start_page-1).$add.'">이전</a></li>'.PHP_EOL;

    if ($total_page > 1) {
        for ($k=$start_page;$k<=$end_page;$k++) {
            if ($cur_page != $k)
                $str .= '<li><a href="'.$url.$k.$add.'">'.$k.'</a></li>'.PHP_EOL;
            else
                $str .= '<li class="active"><a>'.$k.'</a></li>'.PHP_EOL;
        }
    }

    if ($total_page > $end_page) $str .= '<li><a href="'.$url.($end_page+1).$add.'">다음</a></li>'.PHP_EOL;

    if ($cur_page < $total_page) {
        $str .= '<li><a href="'.$url.$total_page.$add.'">맨끝</a></li>'.PHP_EOL;
    }

    if ($str)
        return "<nav aria-label=\"Page navigation\"><ul class=\"pagination pagination-sm\">{$str}</ul></nav>";
    else
        return "";
}

function sql_page_row($page_row){
	global $config;
	$rows = $page_row?$page_row:$config[cf_page_rows];
	return $rows;	
}


function admin_echo($row,$key,$chk="") {
	global $admin;

	if($chk){
		if($row[$key]){
			$return = $admin[$key][$row[$key]];
		} else{
			$return = "";
		}
	} else{
		$return = $admin[$key][$row[$key]];
	}
	return $return;
}

function nfor_file_write($filename,$string){
	$handle = @fopen($filename, 'w');
   @fwrite($handle, $string);
   @fclose($handle);
}

function nfor_file_read($filename){
	$handle = @fopen($filename, "r");
	$contents = @fread($handle, filesize($filename));
	@fclose($handle);
	return $contents;
}

function admin_file($write, $filename, $dir){
	global $nfor;

	$return = "<div class=\"form-inline\"><input type=\"file\" name=\"{$filename}\" id=\"{$filename}\" class=\"form-control\">";

	if($write[$filename]){
		$filepath = "$nfor[path]/data/{$dir}/$write[$filename]";
		$file = thumbnail($filepath,23,23,0,1);
		$return .= " <input type=\"checkbox\" name=\"{$filename}_del\" id=\"{$filename}_del\" value=\"1\"> ";
		$return .= " <a href=\"$filepath\" target=\"_blank\"><img src=\"{$file}\" class=\"admin_file_img\"></a> ";
	}
	$return .= "</div>";
	return $return;
}

function admin_upload($write, $filename,$folder,$tbl="",$where=""){
	global $nfor, $_FILES, $_POST;

	if($_POST[$filename."_del"]){
		@unlink("{$nfor[path]}/data/{$folder}/".$write[$filename]);
		if($tbl){
			sql_query("update $tbl set $filename='' $where");
		}
	}

	if($filename_add = file_upload($nfor[path]."/data/".$folder."/", $_FILES[$filename])){
		return " , $filename='$filename_add' ";
	}
}

function nfor_curl_request($url, $method="get", $post_param="", $header="", $show_header=""){

	// $method 값은 get 또는 post
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt($ch, CURLOPT_SSLVERSION, 1 );

	if($show_header){
		curl_setopt($ch, CURLOPT_HEADER, true );
	}

	if($header){
		/* 헤더를 출력하고 싶다면 사용
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		*/
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header); // $header 는 배열로 처리  array('Authorization: Bearer','nfor: son');
	}


	if($method=="post" and $post_param){   // $post_param 값은 다음과 같이 셋팅 a=1&b=2
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_param);
	}

	$response = curl_exec($ch);
	curl_close($ch);
	return $response;
}

function download_url($dirname,$filename,$orgname=""){
	$str = "download.php?dirname=$dirname&filename=".urlencode($filename);
	if($orgname){
		$str .= "&orgname=".urlencode($orgname);
	}
	return $str;
}

function curl_request($url, $params, $isPostMethod=false, $headers=""){
	$opts = array(
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HEADER => false,
		CURLOPT_HTTPHEADER => $headers,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSLVERSION => 1 // TLS
	);
	if($isPostMethod){
		$opts[CURLOPT_URL] = $url;
		$opts[CURLOPT_POST] = true;
		if(!empty($params)){
			$opts[CURLOPT_POSTFIELDS] = http_build_query($params);
		}
	} else{
		$url .= '?';
		if(!empty($params)) {
			$url .= http_build_query($params);
		}
		$opts[CURLOPT_URL] = $url;
	}
	$curlSession = curl_init();
	curl_setopt_array($curlSession, $opts);

	$result = curl_exec($curlSession);

	if($result === false){
		$error = curl_error($curlSession);
		curl_close($curlSession);
		return $error;
	}
	curl_close($curlSession);
	return $result;
}

function str_eng_under_number2($str){
	$str = preg_replace("/([^a-zA-Z0-9_])/", "", $str);
	return $str;
}


function str_eng_number($str){
	$str = preg_replace("/([^a-zA-Z0-9])/", "", $str);
	return $str;
}

function str_number($str){
	$str = preg_replace("/[^0-9]*/s", "", $str);
	return $str;
}


function json_return($msg="",$result=""){
	global $return;
	$return[msg] = $msg;
	$return[result] = $result;

	//print_r($return);
	//exit;

	$json = json_encode($return);
	$json = urldecode($json);
	echo $json;
	exit;
}

function yoil($date){
	$daily = array("일","월","화","수","목","금","토");
	$yoil = date("w",strtotime($date));
	return $daily[$yoil];
}

/* member_form.php */
function check_alert($field, $value, $mb_no){
	$check = json_decode(nfor_check($field,$value, $mb_no),true);
	if($check[result] != "ok") alert($check[msg]);
}


function nfor_check_return($field,$value, $mb_no=""){
	$check = json_decode(nfor_check($field,$value, $mb_no),true);
	if($check[result] != "ok"){
		$return[msg] = $check[msg];
		$return[result] = $check[result];
		$json = json_encode($return);
		echo $json;
		exit;
	}
}

function nfor_check($field,$value, $mb_no=""){
	global $member_config, $config, $nfor, $is_admin, $_POST;

	$mb_array[mb_memo] = "회원메모";
	$mb_array[mb_cp_bank_account_holder] = "입점업체 정산계좌정보(예금주)";
	$mb_array[mb_cp_bank_account] = "입점업체 정산계좌정보(계좌번호)";
	$mb_array[mb_cp_bank_name] = "입점업체 정산계좌정보(은행명)";
	$mb_array[mb_cp_addr2] = "사업장 주소";
	$mb_array[mb_cp_addr1] = "사업장 주소";
	$mb_array[mb_cp_zipcode] = "사업장 우편번호";
	$mb_array[mb_cp_type2] = "업종";
	$mb_array[mb_cp_type1] = "업태";
	$mb_array[mb_cp_number] = "사업자번호";
	$mb_array[mb_cp_ceo] = "대표자명";
	$mb_array[mb_cp_name] = "상호";
	$mb_array[mb_bank_account] = "환불계좌번호";
	$mb_array[mb_bank_name] = "환불은행";
	$mb_array[mb_valid_date] = "개인정보유효기간";
	$mb_array[mb_birthday] = "생년월일";
	$mb_array[mb_birthday_1] = "생년월일(년)";
	$mb_array[mb_birthday_2] = "생년월일(월)";
	$mb_array[mb_birthday_3] = "생년월일(일)";
	$mb_array[mb_password] = "비밀번호";
	$mb_array[mb_name] = "이름";
	$mb_array[mb_tel] = "전화번호";
	$mb_array[mb_zipcode] = "우편번호";
	$mb_array[mb_addr1] = "주소";
	$mb_array[mb_addr2] = "상세주소";
	$mb_array[mb_sex] = "성별";
	$mb_array[mb_birthday_type] = "생년월일(양력/음력)";
	$mb_array[mb_blog] = "블로그";
	$mb_array[mb_instagram] = "인스타그램";
	$mb_array[mb_youtube] = "유튜브";

	if($field=="mb_birthday_1" or $field=="mb_birthday_2" or $field=="mb_birthday_3"){
		$member_config[$field."_require"] = $member_config["mb_birthday_require"];
	} 

	if(substr($nfor['root_path'],4,3) != "sub") die("");

	if(!$member_config[$field."_require"]){ // 필수가 아니면 체크 안함
		$return[msg] = "";
		$return[result] = "ok";
	} else{
		$value = trim($value);
		if($field=="mb_id"){
			if($value==""){
				$return[msg] = "아이디를 입력하십시오.";
			} else if(preg_match("/[\,]?{$value}/i", $member_config[cf_join_notid])){
				$return[msg] = "예약된 아이디는 사용할 수 없습니다.";
			} else if (preg_match("/[^0-9a-z_-]+/i", $value)){
				$return[msg] = "아이디는 영문자,숫자,_만 허용됩니다.";
			} else if (strlen($value) < 3){
				$return[msg] = "아이디는 최소 3자리 이상 입력하십시오.";
			} else{
				$row = sql_fetch("select mb_id, mb_leave_datetime from nfor_member where mb_no<>'$mb_no' and mb_id = '$value'");
				if($row[mb_id]){

					if($row[mb_leave_datetime]){
						$return[msg] = "탈퇴한 처리된 아이디는 사용할수 없습니다.";
					} else{
						$return[msg] = "이미 존재하는 아이디입니다.";
					}

				} else {
					$return[msg] = "사용하셔도 좋은 아이디입니다.";
					$return[result] = "ok";
				}
			}
		} elseif($field=="mb_nick"){
			if($value==""){
				$return[msg] = "닉네임을 입력하십시오.";
			} else if(preg_match("/[\,]?{$value}/i", $member_config[cf_join_notnick])){
				$return[msg] = "예약된 닉네임은 사용할 수 없습니다.";
			} else if (strlen($value) < 2){
				$return[msg] = "닉네임은 한글 1자, 영문 2자 이상 입력하세요.";
			} else{
				$row = sql_fetch("select count(*) as cnt from nfor_member where mb_no<>'$mb_no' and mb_nick = '$value'");
				if($row[cnt]){
					$return[msg] = "이미 존재하는 닉네임입니다.";
				} else {
					$return[msg] = "사용하셔도 좋은 닉네임입니다.";
					$return[result] = "ok";
				}
			}
		} elseif($field=="mb_email"){
			if($value==''){
				$return[msg] = "E-mail 주소를 입력하십시오.";
			} elseif(!preg_match("/([0-9a-zA-Z_-]+)@([0-9a-zA-Z_-]+)\.([0-9a-zA-Z_-]+)/", $value)) {
				$return[msg] = "E-mail 주소가 형식에 맞지 않습니다.";
			} else{
				$row = sql_fetch("select count(*) as cnt from nfor_member where mb_no <> '$mb_no' and mb_email = '$value'");
				if($row[cnt]){
					$return[msg] = "이미 존재하는 E-mail 주소입니다.";
				} else {
					$return[msg] = "사용하셔도 좋은 E-mail 주소입니다.";
					$return[result] = "ok";
				}
			}
		} elseif($field=="mb_blog"){
			if($value==''){
				$return[msg] = "블로그 주소를 입력하십시오.";
			} else{
				$row = sql_fetch("select count(*) as cnt from nfor_member where mb_no <> '$mb_no' and mb_blog = '$value'");
				if($row[cnt]){
					$return[msg] = "이미 존재하는 블로그 주소입니다.";
				} else {
					$return[msg] = "사용하셔도 좋은 블로그 주소입니다.";
					$return[result] = "ok";
				}
			}
		} elseif($field=="mb_instagram"){
			if($value==''){
				$return[msg] = "인스타그램 주소를 입력하십시오.";
			} else{
				$row = sql_fetch("select count(*) as cnt from nfor_member where mb_no <> '$mb_no' and mb_instagram = '$value'");
				if($row[cnt]){
					$return[msg] = "이미 존재하는 인스타그램 주소입니다.";
				} else {
					$return[msg] = "사용하셔도 좋은 인스타그램 주소입니다.";
					$return[result] = "ok";
				}
			}
		} elseif($field=="mb_youtube"){
			if($value==''){
				$return[msg] = "유뷰트 주소를 입력하십시오.";
			} else{
				$row = sql_fetch("select count(*) as cnt from nfor_member where mb_no <> '$mb_no' and mb_youtube = '$value'");
				if($row[cnt]){
					$return[msg] = "이미 존재하는 유뷰트 주소입니다.";
				} else {
					$return[msg] = "사용하셔도 좋은 유뷰트 주소입니다.";
					$return[result] = "ok";
				}
			}
		} elseif($field=="mb_password_now"){
			$row = sql_fetch("select * from nfor_member where mb_no = '$mb_no'");
			if($row[mb_password]==sql_password($row[$member_config[cf_mb_id_type]], $value)){
				$return[msg] = "기존 패스워드와 일치합니다.";
				$return[result] = "ok";
			} else{
				$return[msg] = "기존 패스워드와 일치하지 않습니다.";
			}
		} elseif($field=="mb_password_confirm"){
			if($value==""){
				$return[msg] = "비밀번호를 입력해주세요";
			} elseif($_POST[mb_password] != $value){
				$return[msg] = "입력하신 패스워드가 일치하지 않습니다";
			} else{
				$return[msg] = "";
				$return[result] = "ok";
			}
		} elseif($field=="mb_hp"){
			if($value==""){
				$return[msg] = "휴대폰번호를 입력해주세요";
			//} elseif(preg_match("/[^0-9]+/i", $value)){
			//	$return[msg] = "숫자만입력해주세요";
			} elseif(strlen($value) < 10){
				$return[msg] = "휴대폰번호를 올바르게 입력해주세요";
			} else{
				$value = add_hyphen($value);
				$data = sql_fetch("select mb_id, mb_email, mb_hp from nfor_member where mb_no <> '$mb_no' and mb_hp = '$value'");
				if($data[mb_hp]){
					$return[msg] = $data[$member_config[cf_mb_id_type]]." 으로 가입된 휴대폰 번호입니다.";
					$return[result_detail] = "is_number";
				} else{
					$return[msg] = "사용가능한 휴대폰번호입니다"; // 사용가능한 휴대폰번호입니다
					$return[result] = "ok";
				}
			}
		} elseif($field=="mb_friend"){
			if($value==""){
				$return[msg] = "추천인 아이디를 입력하세요";
			} else{
				$row = sql_fetch("select count(*) as cnt from nfor_member where mb_no<>'$mb_no' and mb_id = '$value'");
				if($row[cnt]){
					$return[msg] = "";
					$return[result] = "ok";
				} else {
					$return[msg] = "존재하지 않는 추천인 아이디입니다";
				}
			}
		} elseif($field=="mb_mailling"){
			if($value==""){
				$return[msg] = "메일 수신에 동의해 주세요";
			} else{
				$return[msg] = "";
				$return[result] = "ok";
			}
		} elseif($field=="mb_sms"){
			if($value==""){
				$return[msg] = "SMS 수신에 동의해 주세요";
			} else{
				$return[msg] = "";
				$return[result] = "ok";
			}
		} else{
			if($mb_array[$field]){
				if($value==""){
					$return[msg] = $mb_array[$field]."를 입력하세요";
				} else{
					$return[msg] = "";
					$return[result] = "ok";
				}
			}
		}
		if($return[result]<>"ok") $return[result] = $field;
	}
	$json = json_encode($return);
	return $json;
}

/* //member_form.php */

function mobile_check(){
    global $HTTP_USER_AGENT;
    $MobileArray  = array("iphone","lgtelecom","skt","mobile","samsung","nokia","blackberry","android","android","sony","phone","okhttp");

    $checkCount = 0;
        for($i=0; $i<sizeof($MobileArray); $i++){
            if(preg_match("/$MobileArray[$i]/", strtolower($HTTP_USER_AGENT))){ $checkCount++; break; }
        }
   return ($checkCount >= 1) ? "mobile" : "pc";
}

// 휴대폰 번호 사이에 '-' 하이픈 넣기
function add_hyphen($hp){
	$hp = preg_replace("/[^0-9]/", "", $hp);
	return preg_replace("/([0-9]{3})([0-9]{3,4})([0-9]{4})$/", "\\1-\\2-\\3", $hp);
}

function member($type, $value){
	$mb = sql_fetch("select * from nfor_member where $type='$value'");
	$mb_birthday = explode("-", $mb[mb_birthday]);
	$mb[mb_birthday_1] = $mb_birthday[0];
	$mb[mb_birthday_2] = $mb_birthday[1];
	$mb[mb_birthday_3] = $mb_birthday[2];

	return $mb;
}

// url에 http:// 를 붙인다
function set_http($url){
    if (!trim($url)) return;

    if (!preg_match("/^(http|https|ftp|telnet|news|mms)\:\/\//i", $url))
        $url = "http://" . $url;

    return $url;
}

function set_https($url){
    if (!trim($url)) return;

    if (!preg_match("/^(http|https|ftp|telnet|news|mms)\:\/\//i", $url))
        $url = "https://" . $url;

    return $url;
}

function xml2array($contents, $get_attributes=1, $priority = 'tag') {
    if(!$contents) return array();

    if(!function_exists('xml_parser_create')) {
        //print "'xml_parser_create()' function not found!";
        return array();
    }

    //Get the XML parser of PHP - PHP must have this module for the parser to work
     $parser = xml_parser_create('');
    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "utf-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
     xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, trim($contents), $xml_values);
    xml_parser_free($parser);

    if(!$xml_values) return;//Hmm...

    //Initializations
    $xml_array = array();
    $parents = array();
    $opened_tags = array();
    $arr = array();

    $current = &$xml_array; //Refference

    //Go through the tags.
    $repeated_tag_index = array();//Multiple tags with same name will be turned into an array
     foreach($xml_values as $data) {
        unset($attributes,$value);//Remove existing values, or there will be trouble

        //This command will extract these variables into the foreach scope
        // tag(string), type(string), level(int), attributes(array).
        extract($data);//We could use the array by itself, but this cooler.

        $result = array();
        $attributes_data = array();

        if(isset($value)) {
            if($priority == 'tag') $result = $value;
            else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
         }

        //Set the attributes too.
        if(isset($attributes) and $get_attributes) {
            foreach($attributes as $attr => $val) {
                if($priority == 'tag') $attributes_data[$attr] = $val;
                else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
             }
        }

        //See tag status and do the needed.
        if($type == "open") {//The starting of the tag '<tag>'
            $parent[$level-1] = &$current;
            if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                 $current[$tag] = $result;
                if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
                 $repeated_tag_index[$tag.'_'.$level] = 1;

                $current = &$current[$tag];

            } else { //There was another element with the same tag name

                if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
                     $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                     $repeated_tag_index[$tag.'_'.$level]++;
                } else {//This section will make the value an array if multiple tags with the same name appear together
                     $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
                     $repeated_tag_index[$tag.'_'.$level] = 2;

                    if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                         $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                        unset($current[$tag.'_attr']);
                    }

                }
                $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
                $current = &$current[$tag][$last_item_index];
            }

        } elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
            //See if the key is already taken.
            if(!isset($current[$tag])) { //New Key
                $current[$tag] = $result;
                $repeated_tag_index[$tag.'_'.$level] = 1;
                if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;

            } else { //If taken, put all things inside a list(array)
                if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...

                    // ...push the new element into that array.
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;

                    if($priority == 'tag' and $get_attributes and $attributes_data) {
                         $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                     }
                    $repeated_tag_index[$tag.'_'.$level]++;

                } else { //If it is not an array...
                    $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
                     $repeated_tag_index[$tag.'_'.$level] = 1;
                    if($priority == 'tag' and $get_attributes) {
                        if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well

                            $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                            unset($current[$tag.'_attr']);
                        }

                        if($attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                         }
                    }
                    $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
                 }
            }

        } elseif($type == 'close') { //End of tag '</tag>'
            $current = &$parent[$level-1];
        }
    }

    return($xml_array);
}



function url_fsockopen($url){
	$url_parsed = parse_url($url);

	$host = $url_parsed["host"];
	$port = $url_parsed["port"];
	if ($port==0)
		$port = 80;

	$path = $url_parsed["path"];
	if ($url_parsed["query"] != "")
		$path .= "?".$url_parsed["query"];

	$out = "GET $path HTTP/1.0\r\nHost: $host\r\n\r\n";

	$fp = fsockopen($host, $port, $errno, $errstr, 30);
	if (!$fp) {
		echo "$errstr ($errno)<br>\n";
	} else {
		fputs($fp, $out);
		$body = false;
		while (!feof($fp)) {
			$contents = fgets($fp, 128);
			if ($body)
				$social .= $contents;
			if ($contents == "\r\n")
				$body = true;
		}
		fclose($fp);
		return $social;
	}
}


function get_paging_it_select($write_pages, $cur_page, $total_page, $url, $add=""){
	$str = "<div class='paging'>";
	if ($cur_page > 1) {
		$str .= "<a href='javascript:it_select_page(1)' class='num_box pre'>&lt;&lt;</a>";
	}

	$start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
	$end_page = $start_page + $write_pages - 1;

	if ($end_page >= $total_page) $end_page = $total_page;

	if ($start_page > 1) $str .= " &nbsp;<a href='javascript:it_select_page(".($start_page-1).")' class='num_box pre'>&lt;</a>";

	if ($total_page > 1) {
		for ($k=$start_page;$k<=$end_page;$k++) {
			if ($cur_page != $k)
				$str .= " &nbsp;<a href='javascript:it_select_page($k)' class='num_box'>$k</a>";
			else
				$str .= " &nbsp;<strong>$k</strong> ";
		}
	}

	if ($total_page > $end_page) $str .= " &nbsp;<a href='javascript:it_select_page(".($end_page+1).")' class='num_box next'>&gt;</a>";

	if ($cur_page < $total_page) {
		$str .= " &nbsp;<a href='javascript:it_select_page($total_page)' class='num_box next'>&gt;&gt;</a>";
	}
	$str .= "</div>";

	return $str;
}



function reply_name($mb_no){
	$data = sql_fetch("select mb_name from nfor_member where mb_no='$mb_no'");
	echo $data[mb_name];
}













function nfor_send($sd_code,$email="",$hp="",$mb_no="",$rv_id="",$url=""){
	global $config, $nfor, $member, $member_config;
	$send = sql_fetch("select * from nfor_send where sd_code='$sd_code'");

	$sd_subject = $send[sd_subject];
	if($send[sd_mail_design_use]=="1"){
		$sd_memo = $config[cf_mail_head].$send[sd_memo].$config[cf_mail_tail];
	} else{
		$sd_memo = $send[sd_memo];
	}
	$sd_msg = $send[sd_msg];
	$sd_alarm_msg = $send[sd_alarm_msg];

	if(substr($nfor['root_path'],4,3) != "sub") die("");

	if($nfor[cf_email]) $config[cf_email] = $nfor[cf_email];
	$sd_subject=str_replace("#{메일제목}", $nfor[sd_subject], $sd_subject);
	$sd_memo=str_replace("#{메일내용}", $nfor[sd_memo], $sd_memo);
	$sd_msg=str_replace("#{메일내용}", $nfor[sd_memo], $sd_msg);
	$sd_alarm_msg=str_replace("#{메일내용}", $nfor[sd_memo], $sd_alarm_msg);


	// 캠페인번호가 넘어오면
	if($rv_id){
		$review = sql_fetch("select * from nfor_review where rv_id='$rv_id'");
		$nfor_campaign = sql_fetch("select * from nfor_campaign where cp_id='$review[rv_cp_id]'");

		$sd_subject=str_replace("#{캠페인명}", $review[rv_cp_subject], $sd_subject);
		$sd_memo=str_replace("#{캠페인명}", $review[rv_cp_subject], $sd_memo);
		$sd_msg=str_replace("#{캠페인명}", $review[rv_cp_subject], $sd_msg);
		$sd_alarm_msg=str_replace("#{캠페인명}", $review[rv_cp_subject], $sd_alarm_msg); 



		$sd_subject=str_replace("#{마감일}", $config[cf_contents_end_day], $sd_subject);
		$sd_memo=str_replace("#{마감일}", $config[cf_contents_end_day], $sd_memo);
		$sd_msg=str_replace("#{마감일}", $config[cf_contents_end_day], $sd_msg);
		$sd_alarm_msg=str_replace("#{마감일}", $config[cf_contents_end_day], $sd_alarm_msg); 


	}



	// 회원정보가 인자로 넘어왔을때
	if($mb_no){
		$mb = sql_fetch("select * from nfor_member where mb_no='$mb_no'");
		$sd_subject=str_replace("#{아이디}", $mb[$member_config[cf_mb_id_type]], $sd_subject);
		$sd_memo=str_replace("#{아이디}", $mb[$member_config[cf_mb_id_type]], $sd_memo);
		$sd_msg=str_replace("#{아이디}", $mb[$member_config[cf_mb_id_type]], $sd_msg);

		$sd_subject=str_replace("#{이름}", $mb[mb_name], $sd_subject);
		$sd_memo=str_replace("#{이름}", $mb[mb_name], $sd_memo);
		$sd_msg=str_replace("#{이름}", $mb[mb_name], $sd_msg);
		$sd_alarm_msg=str_replace("#{이름}", $mb[mb_name], $sd_alarm_msg);
	}



	// 수정요청사항
	$sd_subject = str_replace("#{수정요청사항}", $nfor[tmp_edit], $sd_subject);
	$sd_memo = str_replace("#{수정요청사항}", $nfor[tmp_edit], $sd_memo);
	$sd_msg = str_replace("#{수정요청사항}", $nfor[tmp_edit], $sd_msg);
	$sd_alarm_msg = str_replace("#{수정요청사항}", $nfor[tmp_edit], $sd_alarm_msg);



	// 임시비밀번호
	$sd_subject = str_replace("#{비밀번호}", $nfor[tmp_password], $sd_subject);
	$sd_memo = str_replace("#{비밀번호}", $nfor[tmp_password], $sd_memo);
	$sd_msg = str_replace("#{비밀번호}", $nfor[tmp_password], $sd_msg);
	$sd_alarm_msg = str_replace("#{비밀번호}", $nfor[tmp_password], $sd_alarm_msg);


	// 회원전번
	$sd_subject=str_replace("#{회원전번}", $order[od_mb_hp], $sd_subject);
	$sd_memo=str_replace("#{회원전번}", $order[od_mb_hp], $sd_memo);
	$sd_msg=str_replace("#{회원전번}", $order[od_mb_hp], $sd_msg);
	$sd_alarm_msg=str_replace("#{회원전번}", $order[od_mb_hp], $sd_alarm_msg);


	// 회원가입 인증번호
	$sd_subject=str_replace("#{인증번호}", $nfor[asign_number], $sd_subject);
	$sd_memo=str_replace("#{인증번호}", $nfor[asign_number], $sd_memo);
	$sd_msg=str_replace("#{인증번호}", $nfor[asign_number], $sd_msg);
	$sd_alarm_msg=str_replace("#{인증번호}", $nfor[asign_number], $sd_alarm_msg);


	$sd_subject=str_replace("#{대표도메인}", $config[cf_url], $sd_subject);
	$sd_memo=str_replace("#{대표도메인}", $config[cf_url], $sd_memo);
	$sd_msg=str_replace("#{대표도메인}", $config[cf_url], $sd_msg);
	$sd_alarm_msg=str_replace("#{대표도메인}", $config[cf_url], $sd_alarm_msg);


	$sd_subject=str_replace("#{상점명}", $config[cf_name], $sd_subject);
	$sd_memo=str_replace("#{상점명}", $config[cf_name], $sd_memo);
	$sd_msg=str_replace("#{상점명}", $config[cf_name], $sd_msg);
	$sd_alarm_msg=str_replace("#{상점명}", $config[cf_name], $sd_alarm_msg);

	$sd_subject=str_replace("#{상점명영문}", $config[cf_name_eng], $sd_subject);
	$sd_memo=str_replace("#{상점명영문}", $config[cf_name_eng], $sd_memo);
	$sd_msg=str_replace("#{상점명영문}", $config[cf_name_eng], $sd_msg);
	$sd_alarm_msg=str_replace("#{상점명영문}", $config[cf_name_eng], $sd_alarm_msg);
	


	$sd_subject=str_replace("#{상호명}", $config[cf_cp_name], $sd_subject);
	$sd_memo=str_replace("#{상호명}", $config[cf_cp_name], $sd_memo);
	$sd_msg=str_replace("#{상호명}", $config[cf_cp_name], $sd_msg);
	$sd_alarm_msg=str_replace("#{상호명}", $config[cf_cp_name], $sd_alarm_msg);


	$sd_subject=str_replace("#{사업자등록번호}", $config[cf_cp_number1], $sd_subject);
	$sd_memo=str_replace("#{사업자등록번호}", $config[cf_cp_number1], $sd_memo);
	$sd_msg=str_replace("#{사업자등록번호}", $config[cf_cp_number1], $sd_msg);
	$sd_alarm_msg=str_replace("#{사업자등록번호}", $config[cf_cp_number1], $sd_alarm_msg);


	$sd_subject=str_replace("#{통신판매업신고}", $config[cf_cp_number2], $sd_subject);
	$sd_memo=str_replace("#{통신판매업신고}", $config[cf_cp_number2], $sd_memo);
	$sd_msg=str_replace("#{통신판매업신고}", $config[cf_cp_number2], $sd_msg);
	$sd_alarm_msg=str_replace("#{통신판매업신고}", $config[cf_cp_number2], $sd_alarm_msg);


	$sd_subject=str_replace("#{사업장소재지}", "$config[cf_cp_zip] $config[cf_cp_addr1] $config[cf_cp_addr2]", $sd_subject);
	$sd_memo=str_replace("#{사업장소재지}", "$config[cf_cp_zip] $config[cf_cp_addr1] $config[cf_cp_addr2]", $sd_memo);
	$sd_msg=str_replace("#{사업장소재지}", "$config[cf_cp_zip] $config[cf_cp_addr1] $config[cf_cp_addr2]", $sd_msg);
	$sd_alarm_msg=str_replace("#{사업장소재지}", "$config[cf_cp_zip] $config[cf_cp_addr1] $config[cf_cp_addr2]", $sd_alarm_msg);


	$sd_subject=str_replace("#{대표자명}", $config[cf_cp_ceo], $sd_subject);
	$sd_memo=str_replace("#{대표자명}", $config[cf_cp_ceo], $sd_memo);
	$sd_msg=str_replace("#{대표자명}", $config[cf_cp_ceo], $sd_msg);
	$sd_alarm_msg=str_replace("#{대표자명}", $config[cf_cp_ceo], $sd_alarm_msg);


	$sd_subject=str_replace("#{대표전화}", $config[cf_tel], $sd_subject);
	$sd_memo=str_replace("#{대표전화}", $config[cf_tel], $sd_memo);
	$sd_msg=str_replace("#{대표전화}", $config[cf_tel], $sd_msg);
	$sd_alarm_msg=str_replace("#{대표전화}", $config[cf_tel], $sd_alarm_msg);


	$sd_subject=str_replace("#{개인정보보호책임자}", $config[cf_cp_privacy], $sd_subject);
	$sd_memo=str_replace("#{개인정보보호책임자}", $config[cf_cp_privacy], $sd_memo);
	$sd_msg=str_replace("#{개인정보보호책임자}", $config[cf_cp_privacy], $sd_msg);
	$sd_alarm_msg=str_replace("#{개인정보보호책임자}", $config[cf_cp_privacy], $sd_alarm_msg);


	$sd_subject=str_replace("#{개인정보보호책임자이메일}", $config[cf_cp_privacy_email], $sd_subject);
	$sd_memo=str_replace("#{개인정보보호책임자이메일}", $config[cf_cp_privacy_email], $sd_memo);
	$sd_msg=str_replace("#{개인정보보호책임자이메일}", $config[cf_cp_privacy_email], $sd_msg);
	$sd_alarm_msg=str_replace("#{개인정보보호책임자이메일}", $config[cf_cp_privacy_email], $sd_alarm_msg);

	$sd_subject=str_replace("#{상점URL}", $nfor[url], $sd_subject);
	$sd_memo=str_replace("#{상점URL}", $nfor[url], $sd_memo);
	$sd_msg=str_replace("#{상점URL}", $nfor[url], $sd_msg);
	$sd_alarm_msg=str_replace("#{상점URL}", $nfor[url], $sd_alarm_msg);




	$sd_subject=str_replace("#{이용약관URL}", $nfor[url]."/agreement.php?code=agreement", $sd_subject);
	$sd_memo=str_replace("#{이용약관URL}", $nfor[url]."/agreement.php?code=agreement", $sd_memo);
	$sd_msg=str_replace("#{이용약관URL}", $nfor[url]."/agreement.php?code=agreement", $sd_msg);
	$sd_alarm_msg=str_replace("#{이용약관URL}", $nfor[url]."/agreement.php?code=agreement", $sd_alarm_msg);


	$sd_subject=str_replace("#{개인정보취급방침URL}", $nfor[url]."/agreement.php?code=privacy", $sd_subject);
	$sd_memo=str_replace("#{개인정보취급방침URL}", $nfor[url]."/agreement.php?code=privacy", $sd_memo);
	$sd_msg=str_replace("#{개인정보취급방침URL}", $nfor[url]."/agreement.php?code=privacy", $sd_msg);
	$sd_alarm_msg=str_replace("#{개인정보취급방침URL}", $nfor[url]."/agreement.php?code=privacy", $sd_alarm_msg);


	$sd_subject=str_replace("#{고객센터URL}", $nfor[url]."/help_form.php", $sd_subject);
	$sd_memo=str_replace("#{고객센터URL}", $nfor[url]."/help_form.php", $sd_memo);
	$sd_msg=str_replace("#{고객센터URL}", $nfor[url]."/help_form.php", $sd_msg);
	$sd_alarm_msg=str_replace("#{고객센터URL}", $nfor[url]."/help_form.php", $sd_alarm_msg);



	$sd_subject=str_replace("#{탈퇴일자}", date("Y년 m월 d일"), $sd_subject);
	$sd_memo=str_replace("#{탈퇴일자}", date("Y년 m월 d일"), $sd_memo);
	$sd_msg=str_replace("#{탈퇴일자}", date("Y년 m월 d일"), $sd_msg);
	$sd_alarm_msg=str_replace("#{탈퇴일자}", date("Y년 m월 d일"), $sd_alarm_msg);





	$sd_subject=str_replace("#{어플다운로드링크}", cut_url($nfor['url']."/appdown.php"), $sd_subject);
	$sd_memo=str_replace("#{어플다운로드링크}", cut_url($nfor['url']."/appdown.php"), $sd_memo);
	$sd_msg=str_replace("#{어플다운로드링크}", cut_url($nfor['url']."/appdown.php"), $sd_msg);
	$sd_alarm_msg=str_replace("#{어플다운로드링크}", cut_url($nfor['url']."/appdown.php"), $sd_alarm_msg);




	if($send[sd_mail_use]=="1"){
		$sd_subject = addslashes($sd_subject);
		$sd_memo = addslashes($sd_memo);
		sql_query("insert nfor_mail_log set ml_subject='$sd_subject', ml_memo='$sd_memo', ml_email='$email', ml_send_email='$config[cf_email]', ml_send_name='$config[cf_name]', ml_datetime=NOW(), ml_send='0'");
	}

	if($send[sd_sms_use]=="1"){
		$sd_msg = addslashes($sd_msg);
		sql_query("insert nfor_sms_log set sl_msg='$sd_msg', sl_hp='$hp', sl_send_hp='$config[cf_tel]', sl_datetime=NOW(), sl_send='0', sl_templt_code='$send[sd_templt_code]', sl_subject='$send[sd_name]'");
	}

	if($send[sd_alarm_use]=="1"){
		$sd_alarm_msg = addslashes($sd_alarm_msg);
		sql_query("insert nfor_message set mg_mb_no='$mb_no', mg_datetime=NOW(), mg_msg='$sd_alarm_msg', mg_url='$url'");
	}

}

function mb_id($mb_no){
	global $nfor, $member_config;
	$data = sql_fetch("select mb_email,mb_id from nfor_member where mb_no='$mb_no'");
	if($member_config[cf_mb_id_type]=="mb_email"){
		$str = $data[mb_email];
	} else{
		$str = $data[mb_id];
	}
	return $str;
}


function file_upload($targetdir, $filearray, $file_type="image"){	
	global $_SERVER;

	$tmp_file = $filearray['tmp_name'];

    if(is_uploaded_file($tmp_file)){

		@mkdir($targetdir, 0707);
		@chmod($targetdir, 0707);

		$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));
		$filename = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filearray['name']);

		shuffle($chars_array);
		$shuffle = implode("", $chars_array);

		$change_filename = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.str_replace('%', '', urlencode(str_replace(' ', '_', $filename)));
		$change_filename_zip = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.time().".zip";

		$dest_file = $targetdir.$change_filename;
		$dest_file_zip = $targetdir.$change_filename_zip;

		if($file_type=="image"){

			$quality = "90";

			$image_info = @getimagesize($filearray['tmp_name']);
			if($image_info['mime']=="image/gif"){
				$isimage = @imagecreatefromgif($filearray['tmp_name']);
			} elseif($image_info['mime']=="image/jpeg"){
				$isimage = @imagecreatefromjpeg($filearray['tmp_name']);

				$exif = exif_read_data($filearray['tmp_name']);				
				if(!empty($exif['Orientation'])){

					switch($exif['Orientation']){
						case 8:
							$isimage = imagerotate($isimage,90,0);
							break;
						case 3:
							$isimage = imagerotate($isimage,180,0);
							break;
						case 6:
							$isimage = imagerotate($isimage,-90,0);
							break;
					}
				}
			
			} elseif($image_info['mime']=="image/png"){
				$isimage = @imagecreatefrompng($filearray['tmp_name']);
			} else{
				return false;
			}

			if($isimage){
				imagejpeg($isimage, $dest_file, $quality);
				@chmod($dest_file, 0604);
				return $change_filename;
			} else{
				return false;
			}

		} else if($file_type=="original"){
		
			if(move_uploaded_file($filearray['tmp_name'], $dest_file)){
				@chmod($dest_file, 0604);
				return $change_filename;
			}
			
		} else{

			$zip = new ZipArchive;
			$res = $zip->open($dest_file_zip, ZipArchive::CREATE);
			if ($res === TRUE) { 
				$zip->addFile($filearray['tmp_name'], $filearray['name']); // $change_filename
				$zip->close();
				@chmod($dest_file_zip, 0604);
				return $change_filename_zip;
			} else {
				return false;
			}

		}

	}

}

/*
function file_upload($targetdir, $filearray){

	$tmp_file = $filearray['tmp_name'];

    if(is_uploaded_file($tmp_file)){

		@mkdir($targetdir, 0707);
		@chmod($targetdir, 0707);

		$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));
		$filename = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filearray['name']);

		shuffle($chars_array);
		$shuffle = implode("", $chars_array);

		$change_filename = abs(ip2long($_SERVER[REMOTE_ADDR])).'_'.substr($shuffle,0,8).'_'.str_replace('%', '', urlencode(str_replace(' ', '_', $filename)));

		$dest_file = $targetdir.$change_filename;

		if(move_uploaded_file($filearray['tmp_name'], $dest_file)){
			@chmod($dest_file, 0606);
			return $change_filename;
		} else{
			return false;
		}

	}

}
*/

function multi_upload($targetdir, $filename, $k){
	global $_FILES;
	$dest_file = time().$_FILES[$filename]['name'][$k];
	if(move_uploaded_file($_FILES[$filename]['tmp_name'][$k], $targetdir.$dest_file)){
		@chmod($dest_file, 0606);
		$add_sql = " , $filename='$dest_file'";
	} else{
		$add_sql = "";
	}
	return $add_sql;

}

// 메타태그를 이용한 URL 이동
function goto_url($url){
    echo "<script type='text/javascript'> location.replace('$url'); </script>";
    exit;
}

// 쿠키변수 생성
function set_cookie($cookie_name, $value, $expire){
    global $nfor;
    setcookie(md5($cookie_name), base64_encode($value), time() + $expire, '/', $nfor[cookie_domain]);
}

// 쿠키변수값 얻음
function get_cookie($cookie_name){
    $cookie = md5($cookie_name);
    if (array_key_exists($cookie, $_COOKIE))
        return base64_decode($_COOKIE[$cookie]);
    else
        return "";
}

// 경고메세지를 경고창으로
function alert($msg='', $url=''){
	global $nfor;

    if (!$msg) $msg = '올바른 방법으로 이용해 주십시오.';

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=$nfor[charset]\">";
	echo "<script type='text/javascript'>alert('$msg');";
    if (!$url)
        echo "history.go(-1);";
    echo "</script>";
    if ($url)
        goto_url($url);
    exit;
}


// 경고메세지 출력후 창을 닫음
function alert_close($msg){
	global $nfor;

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=$nfor[charset]\">";
    echo "<script type='text/javascript'> alert('$msg'); window.close(); </script>";
    exit;
}

// 경고메세지 출력후 창을 닫음
function alert_close_refresh($msg){
	global $nfor;

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=$nfor[charset]\">";
    echo "<script type='text/javascript'> alert('$msg'); window.opener.document.location.href = window.opener.document.URL; window.close(); </script>";
    exit;
}

function cut_str($str, $len, $suffix="…"){
    global $nfor;

    $s = substr($str, 0, $len);
    $cnt = 0;
    for ($i=0; $i<strlen($s); $i++)
        if (ord($s[$i]) > 127)
            $cnt++;
    if ($nfor[charset] == 'utf-8')
        $s = substr($s, 0, $len - ($cnt % 3));
    else
        $s = substr($s, 0, $len - ($cnt % 2));
    if (strlen($s) >= strlen($str))
        $suffix = "";


    return $s . $suffix;
}



function sql_num_rows($result){
	return mysqli_num_rows($result);
}

function sql_close(){
    global $connect_db;
    return mysqli_close($connect_db);
}

function sql_insert_id(){
	global $connect_db;
	return mysqli_insert_id($connect_db);
}

function sql_query($sql, $error=TRUE){
	global $connect_db;

    if ($error)
        $result = @mysqli_query($connect_db, $sql) or die("데이터 처리 중 오류가 발생했습니다."); // 보안(2026-06-11): SQL/DB에러/경로 노출 제거(halt 유지)
    else
        $result = @mysqli_query($connect_db, $sql);

    return $result;
}

function sql_fetch($sql, $error=TRUE){
    $result = sql_query($sql, $error);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    return $row;
}

// 보안(2026-06-11): ORDER BY 화이트리스트 — 정상 정렬값(컬럼[.컬럼][ asc|desc], 콤마다중, RAND())은 그대로,
// 괄호/서브쿼리/세미콜론 등 인젝션 시도는 '1'(첫 컬럼)로 폴백. 정렬 파라미터 SQL injection 차단.
function safe_orderby($s){
    $s = trim($s);
    if($s === '') return "1";
    if(strcasecmp($s, "RAND()") === 0) return "RAND()";
    if(preg_match('/^[A-Za-z_][A-Za-z0-9_]*(\.[A-Za-z_][A-Za-z0-9_]*)?( +(asc|desc))?( *, *[A-Za-z_][A-Za-z0-9_]*(\.[A-Za-z_][A-Za-z0-9_]*)?( +(asc|desc))?)*$/i', $s)) return $s;
    return "1";
}


function sql_object($sql, $error=TRUE){
    $result = sql_query($sql, $error);
    $row = mysqli_fetch_object($result);
    return $row;
}


function sql_fetch_array($result){
    $row = @mysqli_fetch_assoc($result);
    return $row;
}

function sql_password($id, $pass){
	$password = sha1(crypt(md5($pass), sha1(crypt(md5($pass),$id))));
    return $password;
}

function sql_old_password($value){
    $row = sql_fetch(" select old_password('$value') as pass ");
    return $row[pass];
}


/** 
* Get hearder Authorization 
* */ 
function getAuthorizationHeader(){ 
	$headers = null; 
	if (isset($_SERVER['Authorization'])) { 
		$headers = trim($_SERVER["Authorization"]); 
	} else if(isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI 
		$headers = trim($_SERVER["HTTP_AUTHORIZATION"]); 
	} elseif (function_exists('apache_request_headers')) { 
		$requestHeaders = apache_request_headers(); 
		// Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization) 
		$requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders)); 
		//print_r($requestHeaders); 
		if (isset($requestHeaders['Authorization'])) { 
			$headers = trim($requestHeaders['Authorization']); 
		} 
	} 
	return $headers; 
} 

function getBearerToken() { 
	$headers = getAuthorizationHeader(); 
	// HEADER: Get the access token from the header 
	if (!empty($headers)) { 
		if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) { 
			return $matches[1]; 
		} 
	} 
	return null; 
} 
?>