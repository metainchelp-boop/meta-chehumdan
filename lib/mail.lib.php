<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// 메일 보내기 (파일 여러개 첨부 가능)
// type : text=0, html=1, text+html=2
function mailer($fname, $fmail, $to, $subject, $content, $type=0, $file="", $cc="", $bcc=""){
	global $config;

    if($type != 1){
        $content = nl2br($content);
	}

    $mail = new PHPMailer(); // defaults to using php "mail()"

    if ($config[cf_smtp_address]) {
        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->Host = $config[cf_smtp_address]; // SMTP server
		$mail->SMTPAuth = true; 
        if($config[cf_smtp_port]){
            $mail->Port = $config[cf_smtp_port];
		}
		$mail->SMTPSecure = "ssl";
		$mail->Username = $config[cf_smtp_user];
		$mail->Password = $config[cf_smtp_pass];
	    $mail->SetFrom($fmail, $fname);
		$mail->AddReplyTo($fmail, $fname);
    }

    $mail->CharSet = 'UTF-8';
    $mail->From = $fmail;
    $mail->FromName = $fname;
    $mail->Subject = $subject;
    $mail->AltBody = ""; // optional, comment out and test
    $mail->msgHTML($content);
    $mail->addAddress($to);
    if ($cc)
        $mail->addCC($cc);
    if ($bcc)
        $mail->addBCC($bcc);

    if ($file != "") {
        foreach ($file as $f) {
            $mail->addAttachment($f['path'], $f['name']);
        }
    }
	//$mail->SMTPDebug = 2;
    return $mail->send();
}

// 파일을 첨부함
function attach_file($filename, $tmp_name)
{
	global $nfor;
    // 서버에 업로드 되는 파일은 확장자를 주지 않는다. (보안 취약점)
    $dest_file = $nfor[path].'/tmp/'.str_replace('/', '_', $tmp_name);
    move_uploaded_file($tmp_name, $dest_file);
    $tmpfile = array("name" => $filename, "path" => $dest_file);
    return $tmpfile;
}

?>