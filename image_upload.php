<?php
include_once "path.php";



$data = it_img_upload2("preview","image");


$return[data] = $data;
json_return("데이터를 로드하였습니다","ok");


function it_img_upload2($folder,$fieldname){
	global $nfor, $_FILES;
	if($filename = file_upload($nfor[path]."/data/".$folder."/", $_FILES[$fieldname])){
		$ret[upload_name] = $filename;
		$ret[upload_img] = thumbnail($nfor[path]."/data/".$folder."/".$filename,140,140,0,1);
		return $ret;
	}
}


/*
if(!file_exists("upload")) {
	mkdir("upload");
	chmod("upload", 0777);
}

print_r($_FILES);

if($_FILES['formfile']['name'] != "") {
	$file_name = $_FILES['formfile']['name'];
	$target = "./upload/".$file_name;
	if (move_uploaded_file($_FILES['formfile']['tmp_name'], $target)) {
		chmod("$target", 0666);
		sleep(1);
		echo $file_name;
	}

}
*/

?>