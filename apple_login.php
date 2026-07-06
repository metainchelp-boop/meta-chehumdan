<?php
include_once "path.php";

$_SESSION[state] = bin2hex(random_bytes(5));

goto_url(apple_login_btn());
?>