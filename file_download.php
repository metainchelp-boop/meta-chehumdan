<?php
include_once "path.php";

if(!$member['mb_no']) alert("비회원은 다운로드 권한이 없습니다");

if(!isset($file_tbl)) alert("테이블코드가 입력되지 않았습니다");
if(!isset($file_id)) alert("게시글 번호가 입력되지 않았습니다");
if(!isset($file_number)) alert("자료 번호가 입력되지 않았습니다");

if($file_tbl=="jumin" or $file_tbl=="bank" or $file_tbl=="cooperation"){
	if($member[mb_admin] < 7) alert("관리자만 접근가능합니다");
}

if($file_tbl=="jumin"){
	$data = sql_fetch("select * from nfor_point_bank where pb_id='$file_id'");
	if(!$data['pb_id']) alert("존재하지 않는 게시글입니다");
	$file_exp[0] = $data[pb_file1];
	$filename_exp[0] = $data[pb_filename1];
} elseif($file_tbl=="bank"){
	$data = sql_fetch("select * from nfor_point_bank where pb_id='$file_id'");
	if(!$data['pb_id']) alert("존재하지 않는 게시글입니다");
	$file_exp[0] = $data[pb_file2];
	$filename_exp[0] = $data[pb_filename2];
} elseif($file_tbl=="customer"){
	$data = sql_fetch("select * from nfor_customer where cs_id='$file_id'");
	if(!$data['cs_id']) alert("존재하지 않는 게시글입니다");
	if($data['cs_insert_id'] != $member['mb_no'] and $member['mb_admin'] < 7) alert("잘못된 접근입니다");

	$file_exp = explode("||",$data['cs_file_array']);
	$filename_exp = explode("||",$data['cs_filename_array']);
} elseif($file_tbl=="cooperation"){
	$data = sql_fetch("select * from nfor_cooperation where cp_id='$file_id'");
	if(!$data['cp_id']) alert("존재하지 않는 게시글입니다");
	if($data['cp_insert_id'] != $member['mb_no'] and $member['mb_admin'] < 7) alert("잘못된 접근입니다");

	$file_exp = explode("||",$data['cp_file_array']);
	$filename_exp = explode("||",$data['cp_filename_array']);
} else{
	alert("잘못된 접근입니다");
}

$folder = $file_tbl;
$file = $file_exp[$file_number];
$name = $file_exp[$file_number];

if(!$file) alert("파일을 찾을수 없습니다");

ob_end_clean();

$filepath = addslashes("{$nfor['path']}/data/{$folder}/{$file}");
if(!is_file($filepath) || !file_exists($filepath)) alert('파일이 존재하지 않습니다.'.$filepath);

if(!$name){
	$name = $file;
}

// 다운로드 파일명: 원본명(확장자 보존) 우선. 기존엔 저장명을 써서 확장자가 없어 알집으로 받아지던 문제 수정.
$dlname = (isset($filename_exp[$file_number]) && $filename_exp[$file_number]) ? $filename_exp[$file_number] : $name;
if(!$dlname) $dlname = $file;

$mimes = array(
	'jpg'=>'image/jpeg', 'jpeg'=>'image/jpeg', 'png'=>'image/png', 'gif'=>'image/gif',
	'webp'=>'image/webp', 'bmp'=>'image/bmp', 'pdf'=>'application/pdf'
);

// ★ 일부 첨부가 ZIP으로 저장됨(업로드 시 이미지를 zip으로 래핑). ZIP이면 내부 첫 이미지를 풀어서 그 내용을 직접 서빙
//    → 기존/신규 모두 JPG·PNG로 정상 받아지고, 페이지 <img>로도 표시됨. (2026-06-12)
$zipData = null;
$zfh = @fopen($filepath, 'rb'); $zhead = $zfh ? fread($zfh, 4) : ''; if($zfh) fclose($zfh);
if($zhead === "PK\x03\x04" && class_exists('ZipArchive')){
	$zip = new ZipArchive();
	if($zip->open($filepath) === true && $zip->numFiles > 0){
		$entry = $zip->getNameIndex(0);
		for($zi=0; $zi<$zip->numFiles; $zi++){
			$nm = $zip->getNameIndex($zi);
			if(preg_match('/\.(jpe?g|png|gif|webp|bmp|pdf)$/i', $nm)){ $entry = $nm; break; }
		}
		$content = $zip->getFromName($entry);
		$zip->close();
		if($content !== false && $content !== null && $content !== ''){
			$zipData = $content;
			$dlname = basename($entry); // 내부 실제 파일명(확장자 정확)
		}
	}
}

// 확장자 기반 실제 MIME. file/unknown 대신 정확한 타입 → JPG/PNG 등으로 정상 인식.
$ext = strtolower(pathinfo($dlname, PATHINFO_EXTENSION));
$ctype = isset($mimes[$ext]) ? $mimes[$ext] : 'application/octet-stream';

// 보기 모드: ?view=1 이고 이미지면 inline(페이지 <img> 표시용), 아니면 attachment(다운로드).
$is_image = (strpos($ctype, 'image/') === 0);
$disposition = (isset($view) && $view=='1' && $is_image) ? 'inline' : 'attachment';

header("content-type: ".$ctype);
header("content-length: ".($zipData !== null ? strlen($zipData) : filesize($filepath)));
header("content-disposition: ".$disposition."; filename=\"".$dlname."\"");
header("pragma: no-cache");
header("expires: 0");
flush();

if($zipData !== null){
	echo $zipData;
	flush();
} else {
	$fp = fopen($filepath, 'rb');
	$download_rate = 10;
	while(!feof($fp)) {
	    print fread($fp, round($download_rate * 1024));
	    flush();
	    usleep(1000);
	}
	fclose ($fp);
	flush();
}
?>