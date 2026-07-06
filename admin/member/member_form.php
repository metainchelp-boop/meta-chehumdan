<?php
include_once "path.php";

$nfor[title] = "회원정보";

include_once "head.php";
if($mb['mb_admin']=="10") alert("최고관리자의 정보수정은 마이페이지>정보수정을 이용해주세요");
?>


<?php
include_once "../member_form.php";
?>



<?php
include_once "tail.php";
?>