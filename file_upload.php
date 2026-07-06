<?php
include_once "path.php";
  
$arr = array("customer","cooperation","jumin","bank");
if(!in_array($data, $arr)){
	exit;
}

if(strpos($_FILES['uploadfile']['name'], ".php") !== false) {  
	exit;
}

if(strpos($_FILES['uploadfile']['name'], ".html") !== false) {  
	exit;
}

if(strpos($_FILES['uploadfile']['name'], ".htm") !== false) {  
	exit;
}





if($uploadfile = file_upload($nfor[path]."/data/$data/", $_FILES["uploadfile"],"zip")){
	$thumbnail = thumbnail("$nfor[path]/data/$data/".$uploadfile,80,80,0,1);
	$file = $_FILES['uploadfile']['name'];
	echo json_encode(array('success' => true, 'file'=>$file, 'filename'=>$uploadfile, 'thumbnail'=>$thumbnail));
} else{
	echo json_encode(array('success' => false, 'msg' => 'error msg'));  
}

?>