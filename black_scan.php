<?php
include_once "path.php";

/*
검증되지 않음 테스트중
*/

define('KH_SCAN_FILE', $nfor[path].'/data/black_scan.dat');

class KH_Scanner
{
	private function SeekFile($dir, &$files)
	{
		$hnd = opendir($dir);
		while (false !== ($entry = readdir($hnd))) 
		{
			if(in_array($entry, array('.', '..'))) continue;

			if(is_dir($dir.$entry) && !in_array($entry, array('.', '..')))
			{
				$this->SeekFile($dir.$entry.'/', $files);
			}else{
				if(!in_array(substr($entry, strrpos($entry, '.')+1), array('php', 'inc', 'html', 'htm', 'php3'))) continue;

				$file = $dir.$entry;
				if(!in_array($file, $files)) $files[] = $file;
			}
		}
		closedir($hnd);
	}

	private function Diagnosis($files)
	{
		$suspicion = array();
		$loop = 0;
		do {
			$file = $files[$loop];
			$loop++;
			
			if($file == $_SERVER['SCRIPT_FILENAME']) continue;

			$data = file_get_contents($file);
			$data = preg_replace('/\/\*(.*?)\*\//is', '', $data);
			$data = preg_replace('/\/\/*(.*)\n/', '', $data);

			if(preg_match('/(^|\s|\?php3|[?])(eval|system|exec)\s*\(/i', $data))
				$suspicion[] = $file; 
		} while ($loop < count($files));

		return $suspicion;
	}

	public function Run()
	{
		$files = array();
		$this->SeekFile($_SERVER['DOCUMENT_ROOT'].'/', $files);
		return $this->Diagnosis($files);
	}
}

function kh_scan()
{
	set_time_limit(0);

	global $config;

	$scanner = new KH_Scanner;
	$suspicion = $scanner->Run();
	$suspicion_new = array();
	$suspicion_old = unserialize(@file_get_contents(KH_SCAN_FILE));
	file_put_contents(KH_SCAN_FILE, serialize($suspicion));

	if(!empty($suspicion))
		foreach($suspicion as $val)
			if(empty($suspicion_old)||!in_array($val, $suspicion_old))
				$suspicion_new[] = $val;

	if(!empty($suspicion_new))
	{
		$content = "위험성이 있는 코드가 사용된 파일이 발견되었습니다.\r\n\r\n%s";

		$bs_memo = sprintf($content, implode("\r\n", $suspicion_new));

		sql_query("insert nfor_black_sacn set bs_memo='$bs_memo', bs_datetime=NOW()");
	}
}

if(!is_file(KH_SCAN_FILE)||(time()-filemtime(KH_SCAN_FILE)>3600)) // 1h
{
	if(is_file(KH_SCAN_FILE))
		touch(KH_SCAN_FILE, time());
	else
		file_put_contents(KH_SCAN_FILE, 'N;');

	kh_scan();
}
?>