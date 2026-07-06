<?php
include_once "path.php";

$admin[excel_type] = array(""=>"전체", "ex_name"=>"엑셀 양식명" ,"ex_tab" => "구분", "ex_page"=>"파일명");
$admin[ex_tab] = $nfor[admin_tab];

$qstr .= "&excel_type=$excel_type";

$form = $_SERVER[PHP_SELF];
$list = str_replace("form","list",$form);
$table = "nfor_excel";
$id = "ex_id";


$id_value = $$id;

if($id_value){
	$write = sql_fetch("select * from $table where $id='{$id_value}'");
}

if($mode=="insert" or $mode=="update"){
	demo_check();

	$add_sql = "";
	$where_sql = "";

	$ex_field = "";
	for($i=0; $i<count($column_name); $i++){
		if($i) $ex_field .= "^^^^^^^^^";
		$ex_field .= $column_name[$i];
	}

	$ex_comment = "";
	for($i=0; $i<count($column_comment); $i++){
		if($i) $ex_comment .= "^^^^^^^^^";
		$ex_comment .= $column_comment[$i];
	}

	if($mode=="insert"){
		$msg = "정상적으로 등록 되었습니다";
		$move = "$list";
		$add_sql .= ", ex_insert_id='$member[mb_no]', ex_insert_datetime=NOW()";
	} elseif($mode=="update"){
		$msg = "정상적으로 수정 되었습니다";
		$move = "$form?{$qstr}&{$id}={$id_value}";
		$where_sql = "  where $id='{$id_value}'";
		$add_sql .= ", ex_update_id='$member[mb_no]', ex_update_datetime=NOW()";
	} else{

	}

	$common_sql = " $table set ex_tab='$ex_tab', ex_menu='$ex_menu', ex_page='$ex_page', ex_name='$ex_name', ex_field='$ex_field', ex_comment='$ex_comment', ex_table='$ex_table'";

	sql_query("$mode $common_sql $add_sql $where_sql");
	alert($msg,$move);
}

include_once "head.php";
$admin[ex_menu][""] = "선택";
$que = sql_query("select * from nfor_access_group where gp_group='$write[ex_tab]'");
while($data = sql_fetch_array($que)){
	$admin[ex_menu][$data[gp_id]] = $data[gp_text];
}
$admin[ex_page][""] = "선택";
$que = sql_query("select * from nfor_access where gp_id='$write[ex_menu]' and access_path='0' and access_table<>'' order by access_rank desc");
while($data = sql_fetch_array($que)){
	$admin[ex_page][$data[access_file]] = $data[access_text];
}
?>

<form name="fwrite" method="post" onsubmit="return fsubmit(this)">
<?=admin_hidden($write,"mode")?>
<?=admin_hidden($write,$id)?>
<?=admin_hidden($write,"ex_table")?>



<?=admin_help(" 관리자 내 정보를 엑셀파일로 다운로드 받을 경우 다운로드 받을 항목을 미리 선택하여 양식으로 저장해놓은 다운로드 양식을 관리합니다. <br>
       - 신규 다운로드 양식을 등록하거나 기존 양식을 수정/삭제할 수 있습니다. <br>
       - 상품, 주문, 회원에서 정보를 다운로드 받을 때 다운로드 양식을 선택하여 엑셀 다운로드를 진행합니다. <br>
       - 다운로드 양식은 상세항목별로 하나씩 기본 양식을 제공합니다. 기본 양식에는 해당 상세항목에서 다운로드 받을 수 있는 모든 항목이 선택되어 있습니다.<br>
	   - 엑셀필드는 최대 256개까지 추가 가능합니다","line50 notice_gray")?>
<table class="table cols_tbl margin0">
<colgroup>
	<col class="width-150p">
	<col >
</colgroup>
<tr>
	<th>메뉴선택</th> 
	<td>
	<div class="form-inline">
	<?=admin_select($write,"ex_tab","","","0")?>
	<?=admin_select($write,"ex_menu","","","0")?>
	<?=admin_select($write,"ex_page","","","0")?>
	</div>
	</td>
</tr>
<tr>
	<th>엑셀 양식명</th> 
	<td><?=admin_text($write,"ex_name")?></td>
</tr>
<tr>
	<th>필드목록</th> 
	<td>
	
<?=admin_help("※ Shift 버튼을 누른 상태에서 선택하면 여러 항목을 동시에 선택할 수 있습니다.","notice_gray")?>

	<table>
	<tr>
		<td>

			<div class="excel_option_wrap">

				<table class="tbl1">
				<tbody id="layer_table_append">
				<?
				if($write[ex_table]){
				
								
				$que = sql_query("SELECT COLUMN_NAME,COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='$nfor[sql_db]' and `TABLE_NAME` = '$write[ex_table]'");
				while($data = sql_fetch_array($que)){
				?>
				<tr>
					<td data-column_name="<?=$data[COLUMN_NAME]?>" data-column_comment="<?=$data[COLUMN_COMMENT]?>"><?=$data[COLUMN_NAME]?> <?=$data[COLUMN_COMMENT]?></td>
				</tr>
				<? 
				}


				$que = sql_query("select * from nfor_field where fd_table = '$write[ex_table]'");
				while($data = sql_fetch_array($que)){
				?>
				<tr>
					<td data-column_name="<?=$data[fd_name]?>" data-column_comment="<?=$data[fd_text]?>"><?=$data[fd_name]?> <?=$data[fd_text]?></td>
				</tr>
				<? 
				}


				}
				?>
				</tbody>
				</table>

			</div>

		</td>
		<td>


			<div class="form-inline mar10">
				<div class="mar10"">
				<?=admin_button("tbl_add", "추가  +  ", "marbottom5 btn btn-sm btn-white width-60p")?><br>
				<?=admin_button("tbl_alladd", "전체추가", "marbottom5 btn btn-sm btn-white width-60p")?><br>
				<?=admin_button("tbl_alldel", "전체삭제", "marbottom5 btn btn-sm btn-white width-60p")?>
				</div>
			</div>


		</td>
		<td>


			<div class="excel_option_wrap">

				<table class="tbl2">
				<tbody id="layer_excel_append">			
				<?
				if($write[ex_field]){
					$ex_field = explode("^^^^^^^^^",$write[ex_field]);
					$ex_comment = explode("^^^^^^^^^",$write[ex_comment]);
					for($i=0; $i<count($ex_field); $i++){
				?>
				<tr id="excel_<?=$ex_field[$i]?>">
					<td>
					<?
					$excel["column_comment[]"] = $ex_comment[$i];
					$excel["column_name[]"] = $ex_field[$i];
					?>
					<?=admin_text($excel, "column_comment[]")?>
					<?=admin_hidden($excel, "column_name[]")?>
					</td>
					<td>
					<div class="form-inline">
					<?=admin_button("tr_up", "위로", "btn btn-sm btn-white  btn tr_up")?>
					<?=admin_button("tr_down", "아래로", "btn btn-sm btn-white btn tr_down")?>
					<?=admin_button("tr_top", "맨위로", "btn btn-sm btn-white tr_top")?>
					<?=admin_button("tr_bottom", "맨아래", "btn btn-sm btn-white btn tr_bottom")?>
					<?=admin_button("tr_remove", " - 삭제", "btn btn-sm btn-white tr_remove")?>
					</div>
					</td>
				</tr>
				<? 
					}
				}
				?>
				</tbody>
				</table>

			</div>

		
		</td>
	</tr>
	</table>


	</td>
</tr>
</table>

<div class="bottom_btn">
	<div class="form-inline">
		<?=admin_submit("fsubmit_btn", $write[$id]?"수정하기":"등록하기", "btn btn-lg btn-red")?>
		<?=admin_a("list", "목록보기", "btn btn-lg btn-black", "", $list."?".$qstr)?>
	</div>
</div>

</form>

<SCRIPT LANGUAGE="JavaScript">
<!--
$(document).on("click", ".tbl1 td", function(){
	
	if($(this).hasClass("istd")){
		alert("이미 추가된 항목입니다");
	} else if($(this).hasClass("addtd")){
		$(this).removeClass("addtd");
	} else{
		$(this).addClass("addtd");
	}

});

$(document).on("click", "#tbl_add", function(){

	if($(".addtd").length<1){
		alert("추가할 항목을 선택해주세요");
	} else{
		var str = "";
		$(".addtd").each(function(){
			var column_name = $(this).data("column_name");
			var column_comment = $(this).data("column_comment");
			
			if(!$("#excel_"+column_name).length){
				template = _.template($('#layer_excel_script').html());
				template_html =  template({column_name: column_name, column_comment: column_comment});
				$("#layer_excel_append").append(template_html);
			} else{
				if(str)	str += ", ";
				str += column_comment;
			}
		});	
		if(str){
			str += " 항목은 이미 추가된 항목입니다\n해당항목을 제외한 나머지 항목은 정상적으로 추가되었습니다.";
			alert(str);
		}
		$(".addtd").addClass("istd").removeClass("addtd");
	}

});

function fsubmit(f){
	
	if(!$("#ex_name").val()){
		alert("엑셀 양식명");
		$("#ex_name").focus();
		return false;
	}

	f.action = "<?=$form?>";
	return true;	    
}	

$(document).on("click", "#tbl_alladd", function(){

	var str = "";

	$(".tbl1 td").each(function(){
		var column_name = $(this).data("column_name");
		var column_comment = $(this).data("column_comment");
		
		if(!$("#excel_"+column_name).length){
			template = _.template($('#layer_excel_script').html());
			template_html =  template({column_name: column_name, column_comment: column_comment});
			$("#layer_excel_append").append(template_html);
		} else{
			if(str)	str += ", ";
			str += column_comment;
		}
	});	

	if(str){
		str += " 항목은 이미 추가된 항목입니다\n해당항목을 제외한 나머지 항목은 정상적으로 추가되었습니다.";
		alert(str);
	}

	$(".tbl1 td").addClass("istd").removeClass("addtd");


});

$(document).on("click", "#tbl_alldel", function(){

	$(".tbl1 td").each(function(){
		$(this).removeClass("istd").removeClass("addtd");

	});	

	$(".tbl2 tbody").html("");

});

$(document).on("change", "#ex_tab", function(){

	var gp_group = $(this).val();

	$.ajax({
		type     : "get",
		url      : nfor_path + "/json.php",
		data     : encodeURI("mode=access_group&gp_group="+gp_group),
		dataType : 'json',
		cache: false,
		success  : function(data) {
			var output = '<option value="">선택</option>';
			for(var i=0; i<data.data.length; i++) {
				output += '<option value="' + data.data[i].gp_id + '">' + data.data[i].gp_text + '</option>';
			}
			$('#ex_menu').empty().append(output);
			$("#ex_page").empty().append('<option value="">선택</option>');
		},
		error: function(){
			console.log("Ajax failed");
		}
	});
});

$(document).on("change", "#ex_menu", function(){

	var gp_id = $(this).val();

	$.ajax({
		type     : "get",
		url      : nfor_path + "/json.php",
		data     : "mode=access&gp_id="+gp_id,
		dataType : 'json',
		cache: false,
		success  : function(data) {
			var output = '<option value="">선택</option>';
			for(var i=0; i<data.data.length; i++) {
				output += '<option value="' + data.data[i].access_file + '" data-access_table="' + data.data[i].access_table + '">' + data.data[i].access_text + '</option>';
			}
			$('#ex_page').empty().append(output);
		},
		error: function(){
			console.log("Ajax failed");
		}
	});
});

/*
$(document).on("change", "#ex_page", function(){

	var table = $("#ex_page option:selected").data("access_table");
	var access_file = $(this).val();

	$("#ex_table").val(table);

	$.ajax({
		type     : "get",
		url      : nfor_path + "/json.php",
		data     : "mode=tablelist_old&table="+table+"&access_file="+access_file,
		dataType : 'json',
		cache: false,
		success  : function(data) {

			$('#layer_table_append').html("");
			for(var i=0; i<data.data.length; i++) {
				
				var COLUMN_NAME = data.data[i].COLUMN_NAME;
				var COLUMN_COMMENT = data.data[i].COLUMN_COMMENT;

				template = _.template($('#layer_table_script').html());
				template_html =  template({COLUMN_NAME: COLUMN_NAME, COLUMN_COMMENT: COLUMN_COMMENT});
				$("#layer_table_append").append(template_html);

			}

		},
		error: function(){
			console.log("Ajax failed");
		}
	});

});
*/

$(document).on("change", "#ex_page", function(){

	var table = $("#ex_page option:selected").data("access_table");

	$("#ex_table").val(table);

	$.ajax({
		type     : "get",
		url      : nfor_path + "/json.php",
		data     : "mode=tablelist&table="+table,
		dataType : 'json',
		cache: false,
		success  : function(data) {

			$('#layer_table_append').html("");
			for(var i=0; i<data.data.length; i++) {
				
				var COLUMN_NAME = data.data[i].COLUMN_NAME;
				var COLUMN_COMMENT = data.data[i].COLUMN_COMMENT;

				template = _.template($('#layer_table_script').html());
				template_html =  template({COLUMN_NAME: COLUMN_NAME, COLUMN_COMMENT: COLUMN_COMMENT});
				$("#layer_table_append").append(template_html);

			}

		},
		error: function(){
			console.log("Ajax failed");
		}
	});

});

$(document).on("click", ".tr_up", function(){
	var $tr = $(this).closest('tr');
	$tr.prev().before($tr);
});

$(document).on("click", ".tr_down", function(){
	var $tr = $(this).closest('tr');
	$tr.next().after($tr);
});

$(document).on("click", ".tr_top", function(){
	var $tr = $(this).closest('tr');
	$tr.closest('table').find('tr:first').before($tr);
});

$(document).on("click", ".tr_bottom", function(){
	var $tr = $(this).closest('tr');
	$tr.closest('table').find('tr:last').after($tr);
});
//-->
</script>

<script type="text/html" id="layer_table_script">
	<tr>
		<td data-column_name="<%=COLUMN_NAME%>" data-column_comment="<%=COLUMN_COMMENT%>"><%=COLUMN_COMMENT%></td>
	</tr>
</script>

<script type="text/html" id="layer_excel_script">
	<tr id="excel_<%=column_name%>">
		<td>
		<input type="text" name="column_comment[]" class="form-control" value="<%=column_comment%>">
		<input type="hidden" name="column_name[]" class="form-control" value="<%=column_name%>">
		</td>
		<td>
			<div class="form-inline">
			<?=admin_button("tr_up", " 위로", "btn btn-sm btn-white tr_up")?>
			<?=admin_button("tr_down", " 아래로", "btn btn-sm btn-white tr_down")?>
			<?=admin_button("tr_top", " 맨위로", "btn btn-sm btn-white tr_top")?>
			<?=admin_button("tr_bottom", " 맨아래", "btn btn-sm btn-white tr_bottom")?>
			<?=admin_button("tr_remove", " - 삭제", "btn btn-sm btn-white tr_remove")?>
			</div>
		</td>
	</tr>
</script>

<style>
.tbl1 td { cursor:pointer; height:30px; padding-left:10px; font-size:11px; border-bottom:solid 1px #ccc; }
.tbl2 td { height:30px; padding-left:10px; font-size:11px; border-bottom:solid 1px #ccc; }


.addtd { background-color:yellow; } 
.istd { background-color:#eee; }

.excel_option_wrap { width:450px; height:450px; overflow:scrool; overflow-x:hidden; border:solid 1px #ccc; }
.excel_option_wrap table { width:100%; }
</style>

<?php
include_once "tail.php";
?>