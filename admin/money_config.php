<?php
include_once "path.php";


$admin[cf_star_type] = array("전체", "주문합산 퍼센트 %", "임의지정금액");
$admin[cf_pstar_type] = array("전체", "주문합산 퍼센트 %", "임의지정금액");
$admin[cf_bstar_type] = array("전체", "주문합산 퍼센트 %", "임의지정금액");



$table = "nfor_config";
$form = $_SERVER[PHP_SELF];

if($mode=="update"){
	demo_check();

	sql_query("update $table set	
	cf_money='$cf_money'
	, cf_money_min='$cf_money_min'
	, cf_join_money='$cf_join_money'
	, cf_friend_money1='$cf_friend_money1'
	, cf_friend_money2='$cf_friend_money2'
	, cf_at_money='$cf_at_money'
	, cf_star_type='$cf_star_type'
	, cf_star_per='$cf_star_per'
	, cf_star_won='$cf_star_won'
	, cf_pstar_type='$cf_pstar_type'
	, cf_pstar_per='$cf_pstar_per'
	, cf_pstar_won='$cf_pstar_won'	
	, cf_bstar_type='$cf_bstar_type'
	, cf_bstar_per='$cf_bstar_per'
	, cf_bstar_won='$cf_bstar_won'
	");

	alert("정상적으로 수정되었습니다",$form);
}

include_once "head.php";
?>
 


<form name="fwrite" method="post" onsubmit="return fsubmit(this)" enctype="multipart/form-data">
<?=admin_hidden($config,"mode")?>







<?=admin_title("회원가입시 적립금" ,"title_tbl")?>
<table class="table cols_tbl" >
<colgroup>
	<col class="width-150p ">
	<col >
</colgroup>
<tr>
	<th>회원가입시 적립금</th>
	<td><div class="form-inline"><?=admin_text($config,"cf_join_money","width-100p")?>원</div></td>
</tr>
<tr>
	<th>추천인입력시 적립금</th>
	<td><div class="form-inline"><?=admin_text($config,"cf_friend_money1","width-100p")?>원</div></td>
</tr>
<tr>
	<th>추천받는분의 적립금</th>
	<td><div class="form-inline"><?=admin_text($config,"cf_friend_money2","width-100p")?>원</div></td>
</tr>
</table>





<?=admin_title("주문시 적립금" ,"title_tbl")?>
<table class="table cols_tbl" >
<colgroup>
	<col class="width-150p ">
	<col >
</colgroup>
<tr>
	<th>적립금 사용제한</th>
	<td><div class="form-inline">적립금이 <?=admin_text($config,"cf_money","width-100p")?>원 이상 모였을 경우에만 사용가능 하도록 설정</div></td>
</tr>
<tr>
	<th>적립금 주문제한</th>
	<td><div class="form-inline">주문금액이 <?=admin_text($config,"cf_money_min","width-100p")?>원 이상인 경우에만 사용가능 하도록 설정</div></td>
</tr>
</table>



<?=admin_title("후기작성시 적립금" ,"title_tbl")?>
<table class="table cols_tbl" >
<colgroup>
	<col class="width-150p ">
	<col >
</colgroup>
<tr>
	<th>구매후기 적립금</th>
	<td>

		<?=admin_radio($config,"cf_star_type")?>

		<div id="cf_star_type_1" <? if($config[cf_star_type]=="2") echo " class='hide'"; ?>>
			<div class="form-inline"><?=admin_text($config,"cf_star_per","width-50p")?>%</div>
		</div>

		<div id="cf_star_type_2" <? if($config[cf_star_type]=="1" or !$config[cf_star_type]) echo " class='hide'"; ?>>
			<div class="form-inline"><?=admin_text($config,"cf_star_won","width-100p")?>원</div>
		</div>

	</td>
</tr>
<tr>
	<th>포토 구매후기 적립금</th>
	<td>

		<?=admin_radio($config,"cf_pstar_type")?>

		<div id="cf_pstar_type_1" <? if($config[cf_pstar_type]=="2") echo " class='hide'"; ?>>
			<div class="form-inline"><?=admin_text($config,"cf_pstar_per","width-50p")?>%</div>
		</div>

		<div id="cf_pstar_type_2" <? if($config[cf_pstar_type]=="1" or !$config[cf_pstar_type]) echo " class='hide'"; ?>>
			<div class="form-inline"><?=admin_text($config,"cf_pstar_won","width-100p")?>원</div>
		</div>

	</td>
</tr>
<tr>
	<th>베스트후기 적립금</th>
	<td>

		
		<?=admin_radio($config,"cf_bstar_type")?>

		<div id="cf_bstar_type_1" <? if($config[cf_bstar_type]=="2") echo " class='hide'"; ?>>
			<div class="form-inline"><?=admin_text($config,"cf_bstar_per","width-50p")?>%</div>
		</div>

		<div id="cf_bstar_type_2" <? if($config[cf_bstar_type]=="1" or !$config[cf_bstar_type]) echo " class='hide'"; ?>>
			<div class="form-inline"><?=admin_text($config,"cf_bstar_won","width-100p")?>원</div>
		</div>

	</td>
</tr>
</table>



<?=admin_title("기타 적립금" ,"title_tbl")?>
<table class="table cols_tbl" >
<colgroup>
	<col class="width-150p ">
	<col >
</colgroup>
<tr>
	<th>출석체크 적립금</th>
	<td><div class="form-inline"><?=admin_text($config,"cf_at_money","width-100p")?>원</div></td>
</tr>
</table>


<div class="bottom_btn">
	<div class="form-inline">
		<?=admin_submit("fsubmit_btn", "설정하기", "btn btn-lg btn-red")?>
	</div>
</div>

</form>

<script type="text/javascript">
<!--
function fsubmit(f){

	f.action = "<?=$form?>";
	return true;	    
}	

$(document).ready(function (){
	nfor_radio_click("cf_star_type","<?=$config[cf_star_type]?>");
	nfor_radio_click("cf_pstar_type","<?=$config[cf_pstar_type]?>");
	nfor_radio_click("cf_bstar_type","<?=$config[cf_bstar_type]?>");
});

$(document).on("click", "input[name=cf_star_type]", function(){
	nfor_radio_click("cf_star_type",this.value);
});

$(document).on("click", "input[name=cf_pstar_type]", function(){
	nfor_radio_click("cf_pstar_type",this.value);
});

$(document).on("click", "input[name=cf_bstar_type]", function(){
	nfor_radio_click("cf_bstar_type",this.value);
});
//-->
</script>

<?php
include_once "tail.php";
?>