<?php
include_once "path.php";
$admin[menu_tab] = array(""=>"선택","대메뉴" => "대메뉴", "고객센터"=>"고객센터", "마이페이지"=>"마이페이지");

$admin[menu_pc_scroll] = array(""=>"선택", "1"=>"스크롤", "0" => "페이징");
$admin[menu_mobile_scroll] = array(""=>"선택", "1"=>"스크롤", "0" => "페이징");

$qstr .= "&menu_tab=$menu_tab";

$form = $_SERVER[PHP_SELF];
$list = str_replace("form","list",$form);
$table = "nfor_menu";
$id = "menu_id";

$id_value = $$id;

if($id_value){
	$write = sql_fetch("select * from $table where $id='{$id_value}'");
}

if($mode=="insert" or $mode=="update"){
	demo_check();
	
	if($menu_pc_limit and $menu_pc_rows >= $menu_pc_limit){
		$menu_pc_rows = $menu_pc_limit-1;
	}

	if($menu_mobile_limit and $menu_mobile_rows >= $menu_mobile_limit){
		$menu_mobile_rows = $menu_mobile_limit-1;
	}

	$add_sql = "";
	$where_sql = "";

	if($mode=="insert"){
		$msg = "정상적으로 등록 되었습니다";
		$move = "$list";
		$add_sql .= ", menu_insert_datetime=NOW()";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$move = "$form?{$qstr}&{$id}={$id_value}";
		$where_sql = "  where $id='{$id_value}'";
		$add_sql .= ", menu_update_datetime=NOW()";
	} else{

	}
	$common_sql = " $table set menu_text='$menu_text', menu_file='$_POST[menu_file]', menu_rank='$menu_rank', menu_tab='$menu_tab', menu_pc_scroll='$menu_pc_scroll', menu_mobile_scroll='$menu_mobile_scroll', menu_pc_rows='$menu_pc_rows', menu_mobile_rows='$menu_mobile_rows', menu_pc_limit='$menu_pc_limit', menu_mobile_limit='$menu_mobile_limit'";

	sql_query("$mode $common_sql $add_sql $where_sql");

	if($mode=="insert"){
		$menu_id = sql_insert_id();
		sql_query("update nfor_menu set menu_path='0', menu_code='$menu_id' where menu_id='$menu_id'");		
	}

	alert($msg,$move);
}

include_once "head.php";
?>

<form name="fwrite" method="post" onsubmit="return fsubmit(this)">
<?=admin_hidden($write,"mode")?>
<?=admin_hidden($write,$id)?>



<table class="table cols_tbl margin0">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>메뉴구분</th>
	<td>
		<div class="form-inline">
		<?=admin_select($write,"menu_tab","","","0")?>
		</div>
	</td>
</tr>
<tr>
	<th>메뉴명</th> 
	<td><?=admin_text($write,"menu_text","width-200p")?></td>
</tr>
<tr>
	<th>파일명</th> 
	<td><?=admin_text($write,"menu_file","width-200p")?></td>
</tr>
<tr>
	<th>출력순위</th> 
	<td><?=admin_text($write,"menu_rank","width-50p")?></td>
</tr>
</table>

<br>

<table class="table cols_tbl margin0">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>스크롤/페이징 선택</th> 
	<td><div class="form-inline">PC <?=admin_select($write,"menu_pc_scroll","width-100p")?> 모바일 <?=admin_select($write,"menu_mobile_scroll","width-100p")?> <?=admin_help("※ 스크롤 모드의 경우, 뒤로가기 유지를 지원하지 않습니다")?></div></td>
</tr>
<tr>
	<th>한페이지당 표시수</th> 
	<td><div class="form-inline">PC<?=admin_text($write,"menu_pc_rows","width-50p")?> 모바일<?=admin_text($write,"menu_mobile_rows","width-50p")?> <?=admin_help("※ 스크롤 모드의 경우, 초기에 로드되는 표시수이며, 1회 호출시 가져오는 게시글수 설정으로 동작합니다")?></div></td>
</tr>
<tr>
	<th>상위 출력</th> 
	<td><div class="form-inline">PC<?=admin_text($write,"menu_pc_limit","width-50p")?> 모바일<?=admin_text($write,"menu_mobile_limit","width-50p")?> <?=admin_help("※ 상위 N개까지만 노출되도록 출력범위를 설정합니다 / 제한없을 경우 입력하지 않으며 게시글수보다 적어서는안됩니다.")?></div></td>
</tr>
</table>

<br>

<div class="bottom_btn">
	<div class="form-inline">
		<?=admin_submit("fsubmit_btn", $write[$id]?"수정하기":"등록하기", "btn btn-lg btn-red")?>
		<?=admin_a("list", "목록보기", "btn btn-lg btn-black", "", $list."?".$qstr)?>
	</div>
</div>

</form>

<script>
function fsubmit(f){
	f.action = "<?=$form?>";
	return true;	    
}
</script>

<?php
include_once "tail.php";
?>