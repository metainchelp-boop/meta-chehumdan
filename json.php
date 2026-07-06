<?php
include_once "path.php";

if($mode=="admin_menu_keyup"){

	$k = 0;
	$sql = "select * from nfor_access where access_path='0' and access_text like '%$keyword%' and access_level <= $member[mb_admin]";
	$que = sql_query($sql);
	while($data = sql_fetch_array($que)){
		$return[keyword][$data[access_file]] = $data[access_text];
		$k++;
	}
	
	if($k){
		$return[result] = "ok";
	} else{
		$return[result] = "no";
	}

	$json = json_encode($return);
	echo $json;
	exit;
}

if($mode=="bad_ip"){
	if($nfor[test]) json_return($nfor[test_msg],"test");

	if($REMOTE_ADDR==$bd_ip) json_return("본인의 아이피를 차단할수는 없습니다","ip");

	$chk_ip = sql_fetch("select * from nfor_bad_ip where bd_ip='$bd_ip'");
	if($chk_ip[bd_ip]){
		sql_query("delete from nfor_bad_ip where bd_ip='$bd_ip'");
		json_return("아이피 차단해제 되었습니다","delete");	
	} else{
		sql_query("insert nfor_bad_ip set bd_ip='$bd_ip', bd_memo='$bd_memo', bd_insert_id='$member[mb_no]', bd_insert_datetime=NOW()");
		json_return("아이피 차단등록 되었습니다","insert");
	}	
}

if($mode=="access_group"){

	if(isset($_GET['gp_group'])){
		$gp_group = $_GET['gp_group'];

		$que = sql_query("select * from nfor_access_group where gp_group='$gp_group' order by gp_rank desc");
		while($opt = sql_fetch_array($que)){
			$data[] = array(
			'gp_text' => $opt['gp_text'],
			'gp_id' => $opt['gp_id'],
			);
		}

		$reply = array('data' => $data, 'error' => false);
	} else{
		$reply = array('error' => true);
	}

	$json = json_encode($reply);    
	echo $json;
	exit;
}

if($mode=="access"){

	if(isset($_GET['gp_id'])){
		$gp_id = $_GET['gp_id'];

		$que = sql_query("select * from nfor_access where gp_id='$gp_id' and access_path='0' and access_table<>'' and access_level<=10 order by access_rank desc");
		while($opt = sql_fetch_array($que)){
			$data[] = array(
			'access_file' => $opt['access_file'],
			'access_text' => $opt['access_text'],
			'access_table' => $opt['access_table'],
			);
		}

		$reply = array('data' => $data, 'error' => false);
	} else{
		$reply = array('error' => true);
	}

	$json = json_encode($reply);    
	echo $json;
	exit;
}


if($mode=="tablelist"){

	if(isset($_GET['table'])){
		$table = $_GET['table'];

		$que = sql_query("SELECT COLUMN_NAME,COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='$nfor[sql_db]' and `TABLE_NAME` = '$table'");
		while($opt = sql_fetch_array($que)){
			$data[] = array(
			'COLUMN_NAME' => $opt['COLUMN_NAME'],
			'COLUMN_COMMENT' => $opt['COLUMN_COMMENT'],
			);
		}



		$que = sql_query("select * from nfor_field where fd_table='$table'");
		while($opt = sql_fetch_array($que)){
			$data[] = array(
			'COLUMN_NAME' => $opt['fd_name'],
			'COLUMN_COMMENT' => $opt['fd_text'],
			);
		}



		$reply = array('data' => $data, 'error' => false);
	} else{
		$reply = array('error' => true);
	}

	$json = json_encode($reply);    
	echo $json;
	exit;
}


if($mode=="tablelist_old"){

	if(isset($_GET['table'])){
		$table = $_GET['table'];
		$access_file = $_GET['access_file'];

		
		$write = sql_fetch("select * from nfor_access where access_file='$access_file' and access_table='$table'");
		$access_field_array = json_decode($write[access_field]);


		for($i=0; $i<count($access_field_array); $i++){
			$opt = sql_fetch("SELECT COLUMN_NAME,COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='$nfor[sql_db]' and `TABLE_NAME` = '$table' and COLUMN_NAME = '{$access_field_array[$i]}'");
			$data[] = array(
			'COLUMN_NAME' => $opt['COLUMN_NAME'],
			'COLUMN_COMMENT' => $opt['COLUMN_COMMENT'],
			);
		}

		$que = sql_query("select * from nfor_field where fd_table='$table'");
		while($opt = sql_fetch_array($que)){
			$data[] = array(
			'COLUMN_NAME' => $opt['fd_name'],
			'COLUMN_COMMENT' => $opt['fd_text'],
			);
		}



		$reply = array('data' => $data, 'error' => false);
	} else{
		$reply = array('error' => true);
	}

	$json = json_encode($reply);    
	echo $json;
	exit;
}



if($mode=="value"){

	if(isset($_GET['gp_type'])){
		$gp_type = $_GET['gp_type'];

		$que = sql_query("select * from nfor_value_group where gp_type='$gp_type' order by gp_id desc");
		while($row = sql_fetch_array($que)){
			$data[] = array(
			'gp_id' => $row['gp_id'],
			'gp_name' => $row['gp_name']
			);
		}

		$reply = array('data' => $data, 'error' => false);
	} else{
		$reply = array('error' => true);
	}

	$json = json_encode($reply);    
	echo $json;
	exit;
}



if($mode=="area"){

	if(isset($_GET['category_id'])){
		$category_id = $_GET['category_id'];
		$cg_depth = $_GET['depth'];
		
		$cnt = 0;
		$que = sql_query("select * from nfor_area_category where cg_depth='$cg_depth' and category_id like '$category_id%' order by cg_rank asc");
		while($opt = sql_fetch_array($que)){
			$data[] = array(
			'category_id' => $opt['category_id'],
			'cg_category' => $opt['cg_category'],
			);
			$cnt++;
		}

		$reply = array('data' => $data, 'error' => false, 'cnt' => $cnt);
	} else{
		$reply = array('error' => true);
	}

	$json = json_encode($reply);    
	echo $json;
	exit;
}


if($mode=="campaign_category"){

	if(isset($_GET['category_id'])){
		$category_id = $_GET['category_id'];
		$cg_depth = $_GET['depth'];
		
		$cnt = 0;
		$que = sql_query("select * from nfor_campaign_category where cg_depth='$cg_depth' and category_id like '$category_id%' order by cg_rank asc");
		while($opt = sql_fetch_array($que)){
			$data[] = array(
			'category_id' => $opt['category_id'],
			'cg_category' => $opt['cg_category'],
			);
			$cnt++;
		}

		$reply = array('data' => $data, 'error' => false, 'cnt' => $cnt);
	} else{
		$reply = array('error' => true);
	}

	$json = json_encode($reply);    
	echo $json;
	exit;
}


if($mode=="brand"){

	if(isset($_GET['category_id'])){
		$category_id = $_GET['category_id'];
		$cg_depth = $_GET['depth'];
		
		$cnt = 0;
		$que = sql_query("select * from nfor_item_brand where cg_depth='$cg_depth' and category_id like '$category_id%' order by cg_rank asc");
		while($opt = sql_fetch_array($que)){
			$data[] = array(
			'category_id' => $opt['category_id'],
			'cg_category' => $opt['cg_category'],
			);
			$cnt++;
		}

		$reply = array('data' => $data, 'error' => false, 'cnt' => $cnt);
	} else{
		$reply = array('error' => true);
	}

	$json = json_encode($reply);    
	echo $json;
	exit;
}


if($mode=="nfor_check"){
	//$$field = $value;
	echo nfor_check($field,$value,$mb_no);
	exit;
}

if($mode=="hp_asign"){
	if(!$mb_hp) json_return("휴대폰 번호를 입력해주세요","mb_hp");
	$mb_hp = trim($mb_hp);
	$mb_hp = add_hyphen($mb_hp);
	$nfor[asign_number] = rand("11111","99999");
	sql_query("insert nfor_hp_asign set wr_hp='$mb_hp', wr_asign='$nfor[asign_number]', wr_datetime=NOW()");
	nfor_send("hp_asign", $mb_email, $mb_hp);
	json_return("인증번호를 전송하였습니다","ok");	
}

if($mode=="hp_asign_confirm"){
	if(!$mb_hp) json_return("휴대폰 번호를 입력해주세요","mb_hp");
	if(!$asign_number) json_return("인증 번호를 입력해주세요","asign_number");

	$mb_hp = add_hyphen($mb_hp);

	$wr_asign = $asign_number;
	$chk_hp = sql_fetch("select wr_asign from nfor_hp_asign where wr_hp='$mb_hp' and wr_asign='$wr_asign' order by wr_id desc limit 1");

	if($chk_hp[wr_asign]){
		json_return("인증되었습니다","ok");	
	} else{
		json_return("인증번호가 일치하지 않습니다","different");
	}
}


if($mode=="mb_photo_upload"){



	if (exif_imagetype($_FILES["mb_photo"]["tmp_name"]) != IMAGETYPE_JPEG and exif_imagetype($_FILES["mb_photo"]["tmp_name"]) != IMAGETYPE_PNG) { // exif_imagetype($_FILES["mb_photo"]["tmp_name"]) != IMAGETYPE_GIF and 
		json_return("정상적인 이미지가 아닙니다(jpg,png만 선택해주세요)","fail");
	}



	if($mb_photo = file_upload($nfor[path]."/data/member/mb/", $_FILES["mb_photo"])){
		sql_query("update nfor_member set mb_photo='$mb_photo' where mb_no='$member[mb_no]'");
		$return[mb_photo] = thumbnail($nfor[path]."/data/member/mb/".$mb_photo,60,60,0,1);
		json_return("업로드에 성공하였습니다","ok");
	} else{
		json_return("업로드에 실패하였습니다 잠시후 다시 시도해주세요","fail");
	}
}





if($mode=="rv_img_upload"){

	if (exif_imagetype($_FILES["rv_img_file"]["tmp_name"]) != IMAGETYPE_JPEG and exif_imagetype($_FILES["rv_img_file"]["tmp_name"]) != IMAGETYPE_PNG) { // exif_imagetype($_FILES["rv_img_file"]["tmp_name"]) != IMAGETYPE_GIF and 
		json_return("정상적인 이미지가 아닙니다(jpg,png만 선택해주세요)","fail");
	}

	if($rv_img = file_upload($nfor[path]."/data/review/", $_FILES["rv_img_file"])){
		$return[rv_img] = $rv_img;
		$return[rv_img_thumb] = thumbnail($nfor[path]."/data/review/".$rv_img,80,80,0,1);
		json_return("업로드에 성공하였습니다","ok");
	} else{
		json_return("업로드에 실패하였습니다 잠시후 다시 시도해주세요","fail");
	}
}

// 페이백 캠페인: 구매평 캡쳐 업로드 (리뷰 대표이미지 rv_img_upload 와 동일 방식)
if($mode=="rv_buy_img_upload"){

	if (exif_imagetype($_FILES["rv_buy_img_file"]["tmp_name"]) != IMAGETYPE_JPEG and exif_imagetype($_FILES["rv_buy_img_file"]["tmp_name"]) != IMAGETYPE_PNG) {
		json_return("정상적인 이미지가 아닙니다(jpg,png만 선택해주세요)","fail");
	}

	if($rv_buy_img = file_upload($nfor[path]."/data/review/", $_FILES["rv_buy_img_file"])){
		$return[rv_buy_img] = $rv_buy_img;
		$return[rv_buy_img_thumb] = thumbnail($nfor[path]."/data/review/".$rv_buy_img,80,80,0,1);
		json_return("업로드에 성공하였습니다","ok");
	} else{
		json_return("업로드에 실패하였습니다 잠시후 다시 시도해주세요","fail");
	}
}

if($mode=="campaign_list"){

	if(isset($_GET['cp_supply_no'])){
		$cp_supply_no = $_GET['cp_supply_no'];
		
		$i = 0;
		$que = sql_query("select * from nfor_campaign where cp_supply_no='$cp_supply_no' order by cp_id desc");
		while($campaign = sql_fetch_array($que)){
			$data[] = array(
			'cp_subject' => $campaign['cp_subject'],
			'cp_id' => $campaign['cp_id'],
			);
			$i++;
		}

		$reply = array('data' => $data, 'error' => false, 'cnt' => $i);
	} else{
		$reply = array('error' => true);
	}

	$json = json_encode($reply);    
	echo $json;
	exit;
}


if($mode=="review_list"){

	if(isset($_GET['rv_cp_id'])){
		$rv_cp_id = $_GET['rv_cp_id'];
		
		$i = 0;
		$que = sql_query("select * from nfor_review where rv_cp_id='$rv_cp_id' and rv_review<>'' order by rv_id desc");
		while($review = sql_fetch_array($que)){
			$data[] = array(
			'rv_info' => addslashes($review['rv_mb_nick'])."(".$review['rv_mb_id'].")"." - ".addslashes($review['rv_review']),
			'rv_mb_no' => $review['rv_mb_no'],
			);
			$i++;
		}

		$reply = array('data' => $data, 'error' => false, 'cnt' => $i);
	} else{
		$reply = array('error' => true);
	}

	$json = json_encode($reply);    
	echo $json;
	exit;
}

?>