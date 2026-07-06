<?
function create_table_nfor_metatech(){
	$t_sql = "
		CREATE TABLE nfor_metatech (
	  `idx` int(11) NOT NULL AUTO_INCREMENT,
	  `meta_op_a` varchar(20) NOT NULL,
	  `meta_op_b` varchar(20) NOT NULL,
	  `meta_op_c` varchar(20) NOT NULL,
	  `m_name` varchar(255) NOT NULL,
	  `m_keyword` varchar(1000) NOT NULL,
	  `m_url` varchar(1000) NOT NULL,
	  `m_answer` varchar(100) NOT NULL,
	  `m_point` int(10) NOT NULL,
	  `m_count` int(11) NOT NULL,
	  `m_sdate` varchar(10) NOT NULL,
	  `m_edate` varchar(10) NOT NULL,
	  `m_day_count` int(3) NOT NULL,
	  `m_cp_id` varchar(50) NOT NULL DEFAULT '',
	  `m_rank_area` varchar(30) NOT NULL DEFAULT '',
	  `m_rank` varchar(50) NOT NULL DEFAULT '',
	  `m_rank_img` varchar(255) NOT NULL DEFAULT '',
	  `m_md_name` varchar(60) NOT NULL DEFAULT '',
      `datetime` datetime NOT NULL,
	  PRIMARY KEY(`idx`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
//	echo $t_sql;
	$result_t = sql_query($t_sql);

	return $result_t;
}

// 기존 테이블에 신규 컬럼 보강(멱등) — 2026-06-16: 캠페인연동·검수후지급
function metatech_ensure_columns(){
	// nfor_metatech.m_cp_id (캠페인 연결)
	if(sql_num_rows(sql_query("SHOW TABLES LIKE 'nfor_metatech'"))){
		if(!sql_num_rows(sql_query("SHOW COLUMNS FROM nfor_metatech LIKE 'm_cp_id'"))){
			sql_query("ALTER TABLE nfor_metatech ADD COLUMN `m_cp_id` varchar(50) NOT NULL DEFAULT ''");
		}
		// 노출 순위/위치 + 노출 사진 (2026-06-16) — 참여자가 검색결과에서 빨리 찾게
		if(!sql_num_rows(sql_query("SHOW COLUMNS FROM nfor_metatech LIKE 'm_rank'"))){
			sql_query("ALTER TABLE nfor_metatech ADD COLUMN `m_rank_area` varchar(30) NOT NULL DEFAULT ''");
			sql_query("ALTER TABLE nfor_metatech ADD COLUMN `m_rank` varchar(50) NOT NULL DEFAULT ''");
			sql_query("ALTER TABLE nfor_metatech ADD COLUMN `m_rank_img` varchar(255) NOT NULL DEFAULT ''");
		}
		// 담당자(등록자) 이름 (2026-06-17)
		if(!sql_num_rows(sql_query("SHOW COLUMNS FROM nfor_metatech LIKE 'm_md_name'"))){
			sql_query("ALTER TABLE nfor_metatech ADD COLUMN `m_md_name` varchar(60) NOT NULL DEFAULT ''");
		}
	}
	// nfor_metatech_user.mb_no/pt_point/pt_memo/status (검수후지급)
	if(sql_num_rows(sql_query("SHOW TABLES LIKE 'nfor_metatech_user'"))){
		if(!sql_num_rows(sql_query("SHOW COLUMNS FROM nfor_metatech_user LIKE 'status'"))){
			sql_query("ALTER TABLE nfor_metatech_user ADD COLUMN `mb_no` varchar(20) NOT NULL DEFAULT ''");
			sql_query("ALTER TABLE nfor_metatech_user ADD COLUMN `pt_point` int(10) NOT NULL DEFAULT 0");
			sql_query("ALTER TABLE nfor_metatech_user ADD COLUMN `pt_memo` varchar(255) NOT NULL DEFAULT ''");
			sql_query("ALTER TABLE nfor_metatech_user ADD COLUMN `status` tinyint(1) NOT NULL DEFAULT 1"); // 1=지급완료/승인 0=검수대기 2=반려
		}
	}
}

// db 가 있는지 찾고 만들다
function find_create_table_nfor_metatech(){

	$sql = " SHOW TABLES LIKE 'nfor_metatech' ";

	$query = sql_query($sql);
	$total_count = sql_num_rows($query);

	if(!$total_count){
		create_table_nfor_metatech();
	}
	metatech_ensure_columns();
}

function insert_table_nfor_metatech($data){
//print_r2($data);
	metatech_ensure_columns();
	$nowdate = nowdate();
	// 명시 컬럼 방식(위치기반 금지 — 신규 m_cp_id 컬럼 안전)
	$sql = "insert into nfor_metatech "
		. "(meta_op_a, meta_op_b, meta_op_c, m_name, m_keyword, m_url, m_answer, m_point, m_count, m_sdate, m_edate, m_day_count, m_cp_id, m_rank_area, m_rank, m_rank_img, m_md_name, datetime) "
		. "values ("
		. "'".addslashes($data['meta_op_a'])."','"
		. addslashes($data['meta_op_b'])."','"
		. addslashes($data['meta_op_c'])."','"
		. addslashes($data['m_name'])."','"
		. addslashes($data['m_keyword'])."','"
		. addslashes($data['m_url'])."','"
		. addslashes($data['m_answer'])."','"
		. (int)$data['m_point']."','0','"
		. addslashes($data['m_sdate'])."','"
		. addslashes($data['m_edate'])."','"
		. (int)$data['m_day_conut']."','"
		. addslashes($data['m_cp_id'])."','"
		. addslashes($data['m_rank_area'])."','"
		. addslashes($data['m_rank'])."','"
		. addslashes($data['m_rank_img'])."','"
		. addslashes($data['m_md_name'])."','"
		. $nowdate."')";
	$query = sql_query($sql);

	if($query) $result = true;

	return $result;
}

// 캠페인(cp_id)에 연결된 메타테크가 있으면 update, 없으면 insert — 캠페인 등록폼 연동용 (2026-06-16)
function upsert_table_nfor_metatech_by_cp($data){
	metatech_ensure_columns();
	$cp = addslashes($data['m_cp_id']);
	if($cp === '') return insert_table_nfor_metatech($data);
	$exist = sql_fetch("select idx from nfor_metatech where m_cp_id='$cp' order by idx desc limit 1");
	if($exist['idx']){
		$sql = "update nfor_metatech set "
			. "meta_op_a='".addslashes($data['meta_op_a'])."', "
			. "meta_op_b='".addslashes($data['meta_op_b'])."', "
			. "meta_op_c='".addslashes($data['meta_op_c'])."', "
			. "m_name='".addslashes($data['m_name'])."', "
			. "m_keyword='".addslashes($data['m_keyword'])."', "
			. "m_url='".addslashes($data['m_url'])."', "
			. "m_answer='".addslashes($data['m_answer'])."', "
			. "m_point='".(int)$data['m_point']."', "
			. "m_sdate='".addslashes($data['m_sdate'])."', "
			. "m_edate='".addslashes($data['m_edate'])."', "
			. "m_day_count='".(int)$data['m_day_conut']."', "
			. "m_rank_area='".addslashes($data['m_rank_area'])."', "
			. "m_rank='".addslashes($data['m_rank'])."', "
			. "m_md_name='".addslashes($data['m_md_name'])."' "
			. ($data['m_rank_img']!=='' ? ", m_rank_img='".addslashes($data['m_rank_img'])."' " : "")
			. "where idx='".$exist['idx']."'";
		sql_query($sql);
		return $exist['idx'];
	}
	return insert_table_nfor_metatech($data);
}

function nowdate(){
	$result = date("Y-m-d H:i:s");
	return $result;
}

function select_table_nfor_metatech($idx){

	$sql = " select * from nfor_metatech where idx = '$idx'";

	$query = sql_fetch($sql);

	return $query;
}

function select_table_nfor_metatech_m_answer($idx){
	$sql = " select m_answer from nfor_metatech where idx = '$idx'";

	$query = sql_fetch($sql);
	$result = $query['m_answer'];

	return $result;
}

function select_table_nfor_metatech_m_day_count($idx){
	$sql = " select m_day_count from nfor_metatech where idx = '$idx'";

	$query = sql_fetch($sql);
	$result = $query['m_day_count'];

	return $result;
}

function find_table_nfor_metatech_idx($idx){
	$sql = " select count(*) as cnt from nfor_metatech where idx = '$idx'";

	$query = sql_fetch($sql);
	$result = $query['cnt'];

	return $result;
}

function find_table_nfor_metatech_user_twofiled($mb_id,$connect_idx){
	$sql = " select count(*) as cnt from nfor_metatech_user where mb_id = '$mb_id' and connect_idx = '$connect_idx'";
	$query = sql_fetch($sql);
	$result = $query['cnt'];

	return $result;	
}

function del_table_nfor_metatech_main($idx){
	$sql = " delete from nfor_metatech where idx = '$idx'";
//	echo $sql;
	$query = sql_query($sql);

	if($query) $result = true;

	return $result;
}

function del_table_nfor_metatech_user_main($idx){
	$sql = " delete from nfor_metatech_user where connect_idx = '$idx'";
//	echo $sql;
	$query = sql_query($sql);

	if($query) $result = true;

	return $result;
}

function select_table_nfor_metatech_alllist($mb_id){

	$sql = " select * from nfor_metatech where (DATE(m_sdate) <= DATE(NOW()) and DATE(m_edate) >= DATE(NOW())) order by idx desc";
	$query = sql_query($sql);
//echo $sql."<br>";
	$result = array();
	$z=0;
	while($row = sql_fetch_array($query)){
		$do_have = 0;
		$to_day_cnt = 0;

		// 참여 한적 있는지 찾기
		$do_have = find_table_nfor_metatech_user_twofiled($mb_id,$row[idx]);

		// 참여한 이력이 없다면..
		if(!$do_have){
			// 해당 미션의 오늘 참여자 인원 카운트 가져오기.
			$to_day_cnt = select_table_nfor_metatech_user_list_match_idx_datetime($row['idx']);	
		}

		// 오늘의 카운트와 m_day_count 제한 카운트 를 비교..비노출..
		if($to_day_cnt < $row['m_day_count']){
			$result[$z] = $row;
			$z++;		
		}
	}

	return $result;
}

function select_table_nfor_metatech_user_list_match_idx_datetime($idx){
	$sql = " select count(*) as cnt from nfor_metatech_user where connect_idx = '$idx' and DATE_FORMAT(datetime, '%Y-%m-%d') = CURDATE()";

	$query = sql_fetch($sql);
	$result = $query['cnt'];

	return $result;
}

function select_table_nfor_metatech_list($idx){
	$sql = " select * from nfor_metatech where idx = '$idx'";

	$query = sql_fetch($sql);
	$result = $query;

	return $result;
}

function create_table_nfor_metatech_user(){
	$t_sql = "
		CREATE TABLE nfor_metatech_user (
	  `idx` int(11) NOT NULL AUTO_INCREMENT,
	  `mb_id` varchar(1000) NOT NULL,
	  `connect_idx` varchar(20) NOT NULL,
	  `filename` varchar(1000) NOT NULL,
	  `mb_no` varchar(20) NOT NULL DEFAULT '',
	  `pt_point` int(10) NOT NULL DEFAULT 0,
	  `pt_memo` varchar(255) NOT NULL DEFAULT '',
	  `status` tinyint(1) NOT NULL DEFAULT 1,
      `datetime` datetime NOT NULL,
	  PRIMARY KEY(`idx`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
//	echo $t_sql;
	$result_t = sql_query($t_sql);

	return $result_t;
}
// db 가 있는지 찾고 만들다
function find_create_table_nfor_metatech_user(){

	$sql = " SHOW TABLES LIKE 'nfor_metatech_user' ";

	$query = sql_query($sql);
	$total_count = sql_num_rows($query);

	if(!$total_count){
		create_table_nfor_metatech_user();
	}
	metatech_ensure_columns();
}

function insert_table_nfor_metatech_user($data){
	metatech_ensure_columns();
	$nowdate = nowdate();
	$status = isset($data['status']) ? (int)$data['status'] : 1;   // 기본 1=지급완료(정답형). 사진형은 0=검수대기로 넘김
	$sql = "insert into nfor_metatech_user "
		. "(mb_id, connect_idx, filename, mb_no, pt_point, pt_memo, status, datetime) values ("
		. "'".addslashes($data['mb_id'])."','"
		. addslashes($data['connect_idx'])."','"
		. addslashes($data['filename'])."','"
		. addslashes($data['mb_no'])."','"
		. (int)$data['pt_point']."','"
		. addslashes($data['pt_memo'])."','"
		. $status."','"
		. $nowdate."')";
	$query = sql_query($sql);

	if($query) $result = true;

	return $sql;
//	return $result;
}

// 검수 승인 — 검수대기(status=0) 참여건에 포인트 지급 + 승인 처리 (2026-06-16)
function metatech_approve_user($user_idx){
	$user_idx = (int)$user_idx;
	$u = sql_fetch("select * from nfor_metatech_user where idx='$user_idx'");
	if(!$u['idx'] || $u['status'] != '0') return false;   // 대기건만 승인
	$ftimestamp = date("YmdHis").substr(microtime(),2,6);
	insert_point($u['mb_no'], $u['pt_point'], $u['pt_memo'], "4200", $u['mb_no']."_".$ftimestamp);
	sql_query("update nfor_metatech_user set status='1' where idx='$user_idx'");
	return true;
}

// 검수 반려 — 포인트 미지급, 반려 처리(기록 보존) (2026-06-16)
function metatech_reject_user($user_idx){
	$user_idx = (int)$user_idx;
	$u = sql_fetch("select * from nfor_metatech_user where idx='$user_idx'");
	if(!$u['idx'] || $u['status'] != '0') return false;
	sql_query("update nfor_metatech_user set status='2' where idx='$user_idx'");
	return true;
}

// 캠페인(cp_id)에 연결된 메타테크 미션 + 참여 집계 — 보고서용 (2026-06-16)
function metatech_report_by_cp($cp_id){
	metatech_ensure_columns();
	$cp = addslashes($cp_id);
	$rows = array();
	if($cp === '') return $rows;
	$q = sql_query("select * from nfor_metatech where m_cp_id='$cp' order by idx desc");
	while($m = sql_fetch_array($q)){
		$c = sql_fetch("select count(*) as c, "
			. "sum(case when status='1' then 1 else 0 end) as done, "
			. "sum(case when status='0' then 1 else 0 end) as wait "
			. "from nfor_metatech_user where connect_idx='".$m['idx']."'");
		$m['join_cnt'] = (int)$c['c'];
		$m['done_cnt'] = (int)$c['done'];
		$m['wait_cnt'] = (int)$c['wait'];
		$rows[] = $m;
	}
	return $rows;
}


function delete_table_nfor_metatech_user($data){

	$sql = " delete from nfor_metatech_user where idx = '".$data[idx]."'";

	$query = sql_query($sql);

	if($query) $result = true;	

	return $sql;
//	return $result;
}

function update_table_nfor_metatech_filed($data,$type=null){
	if(!$type){
		$type = "+";
	}
	
	$sql = "update nfor_metatech set m_count=m_count $type 1 where idx='".$data['connect_idx']."'";

	$query = sql_query($sql);

	if($query) $result = true;	

	return $sql;
}

function select_table_nfor_member_id_to_name($mb_id){
	$sql = " select mb_name from nfor_member where mb_id = '$mb_id'";

	$query = sql_fetch($sql);
	$result = $query['mb_name'];

	return $result;
}

function select_table_nfor_member_id_to_mb_no($mb_id){
	$sql = " select mb_no from nfor_member where mb_id = '$mb_id'";

	$query = sql_fetch($sql);
	$result = $query['mb_no'];

	return $result;
}

function select_table_nfor_member_id_to_mb_point($mb_id){
	$sql = " select mb_point from nfor_member where mb_id = '$mb_id'";

	$query = sql_fetch($sql);
	$result = $query['mb_point'];

	return $result;
}

function cal_metatech_canhavepoint($mb_id){
	// 미션 모든 포인트 가져오기 날짜로.
	$sql = " select sum(m_point) from nfor_metatech where (DATE(m_sdate) <= DATE(NOW()) and DATE(m_edate) >= DATE(NOW()))";

	$query = sql_fetch($sql);
	$total_p = $query['sum(m_point)'];

	// 참여한 내역 포인트 가져오기 날짜로.
	$sql2 = "select sum(b.m_point) from nfor_metatech_user a left join nfor_metatech b on a.connect_idx = b.idx where a.mb_id = '$mb_id' and DATE(b.m_edate) >= DATE(NOW())";
	$query = sql_fetch($sql2);
	$total_p2 = $query['sum(b.m_point)'];

	$sql = " select * from nfor_metatech where (DATE(m_sdate) <= DATE(NOW()) and DATE(m_edate) >= DATE(NOW())) order by idx desc";
	$query = sql_query($sql);
	
	$total_p3 = 0;

	while($row = sql_fetch_array($query)){
		// 미션에 참여한 모든 내역 개수를 가져온다.
		$r_sql2 = "select count(*) as cnt from nfor_metatech_user where connect_idx = '".$row[idx]."'";
		$r_query2 = sql_fetch($r_sql2);
		$r_all_cnt = $r_query2['cnt'];		

		// 미션에 참여한 유저의 개수를 가져온다. 참여 파악여부 
		$r_sql3 = "select count(*) as cnt from nfor_metatech_user where connect_idx = '".$row[idx]."' and mb_id = '$mb_id'";	
		$r_query3 = sql_fetch($r_sql3);
		$do_have = $r_query3['cnt'];	

		// 비노출 영역
		if($row[m_day_count] <= $r_all_cnt){
			// 가지고 있지 않다면..
			if($do_have == 0){
				$total_p3 = $total_p3 + $row[m_point];
			}
		}
	}

	$result = $total_p - $total_p2 - $total_p3;

	return $result;

}

function filename_dir_maker($filename,$dir){
	return $nfor['path']."/data/$dir/$filename";
}

function print_r2($var){
    ob_start();
    print_r($var);
    $str = ob_get_contents();
    ob_end_clean();
    $str = str_replace(" ", "&nbsp;", $str);
    echo nl2br("<span style='font-family:Tahoma, 굴림; font-size:9pt;'>$str</span>");
}

function get_os(){
	$userAgent = $_SERVER['HTTP_USER_AGENT'];

	// PC와 모바일 기기를 구분하기 위한 정규 표현식 패턴
	$patternMobile = '/(iPhone|iPod|iPad|Android|Windows Phone)/i';

	if (preg_match($patternMobile, $userAgent)) {
		// 운영체제 확인
		if (strpos($userAgent, 'Android') !== false) {
			$result = "android";
		} elseif (strpos($userAgent, 'iPhone') !== false || strpos($userAgent, 'iPad') !== false) {
			$result = "ios";
		}
	} else {
		$result = "pc";
	}
	
	return $result;
}
?>