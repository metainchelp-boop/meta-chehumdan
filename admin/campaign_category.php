<?php
include "path.php";

$table = "nfor_campaign_category";
$form = "campaign_category.php";
$form_load = "campaign_category_form.php";

if($mode=="category_move"){
	if($nfor[test]) json_return($nfor[test_msg],"test");
	if(!$category_id) json_return("카테고리가 지정되지 않았습니다","category_id");

	$parent = str_replace("#","",$parent);

	$chg_category_id = $parent.substr($category_id,-4); // 금번수정부분

	sql_query("update $table set category_id = REPLACE(category_id, '$category_id', '$chg_category_id') where category_id like '$category_id%'");

	sql_query("update $table set cg_depth = (LENGTH(category_id)/4)-1 where category_id like '$chg_category_id%'"); // 금번수정부분

	$cg_depth_id = (int) substr($parent,-4); // 금번수정부분

	sql_query("update $table set cg_depth_id='$cg_depth_id' where category_id = '$chg_category_id'");

	$data = sql_fetch("select * from $table where cg_depth_id='$cg_depth_id' and category_id <> '$chg_category_id' order by cg_rank asc limit $position,1");
	$cg_rank = $data[cg_rank];

	if($position>0 and !$cg_rank){
		$max = sql_fetch("select * from $table where cg_depth_id='$cg_depth_id' and category_id <> '$chg_category_id' order by cg_rank desc limit 1");
		$cg_rank = $max[cg_rank]+1;
	}


	sql_query("update $table set cg_rank='$cg_rank' where category_id = '$chg_category_id'");

	sql_query("update $table set cg_rank=cg_rank+1 where cg_depth_id='$cg_depth_id' and cg_rank >= '$cg_rank' and category_id <> '$chg_category_id'"); // 같거나 큰거업데이트

	for($i=1; $i<=10; $i++){
		sql_query("update nfor_campaign set cp_category = REPLACE(cp_category, '$category_id', '$chg_category_id'), cp_category_id{$i} = REPLACE(cp_category_id{$i}, '$category_id', '$chg_category_id') where cp_category_id{$i} like '$category_id%' ");
	}

	$return[category_id] = $chg_category_id;
	json_return("변경완료","ok");
}

if($mode=="category_insert"){
	if($nfor[test]) json_return($nfor[test_msg],"test");

	$cg_depth_id = (int) $cg_depth_id; // 금번수정부분

	$parent = sql_fetch("select * from $table where cg_id='$cg_depth_id'");
	if($parent[cg_id]){
		$cg_depth = $parent[cg_depth]+1;
	} else{
		$cg_depth = 0;
	}
	$max = sql_fetch("select * from $table where cg_depth_id='$cg_depth_id' order by cg_rank desc");
	$cg_rank = $max[cg_rank] + 1;
	$cg_use = "1";


	sql_query("insert $table set cg_category='$cg_category', cg_rank='$cg_rank', cg_use='$cg_use', cg_depth_id='$cg_depth_id', cg_depth='$cg_depth'");
    $cg_id = sql_insert_id();
	$cg_id = substr("000".$cg_id,-3);

	if($cg_depth_id){
		$data = sql_fetch("select * from $table where cg_id='$cg_depth_id'");
		$category_id = $data[category_id];
	} else{
		$category_id = "";
	}

	$category_id .= $cg_id;

	// 금번수정부분
	$category_id .= "A"; 

	sql_query("update $table set category_id='$category_id' where cg_id='$cg_id'");

	$return[category_id] = $category_id;

	json_return("변경완료","ok");
}

if($mode=="category_name_change"){
	if($nfor[test]) json_return($nfor[test_msg],"test");

	if(!$category_id) json_return("카테고리가 지정되지 않았습니다","category_id");
	if(!$cg_category) json_return("카테고리가 지정되지 않았습니다","cg_category");

	sql_query("update $table set cg_category='$cg_category' where category_id='$category_id'");	
	json_return("변경완료","ok");
}

if($mode=="category_name_delete"){
	if($nfor[test]) json_return($nfor[test_msg],"test");
	if(!$category_id) json_return("카테고리가 지정되지 않았습니다","category_id");

	sql_query("delete from $table where category_id like '$category_id%'");	

	for($i=1; $i<=10; $i++){
		sql_query("update nfor_campaign set cp_category_id{$i} = '' where cp_category_id{$i} like '$category_id%' ");
	}

	json_return("삭제완료","ok");
}

include "head.php";
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />


<div style="overflow:hidden;">
	<div style="float:left; width:20%; padding:0px 10px 0px 0px; min-height:1500px;">
		

			<div class="marbottom5">
			<button type="button" onclick="category_form_load();" class="btn btn-gray btn-sm marb5 width-103p">1차분류생성</button>
			<button type="button" onclick="nfor_jstree_open();" class="btn btn-gray btn-sm marb5 width-103p">전체열기</button>
			<button type="button" onclick="nfor_jstree_close();" class="btn btn-gray btn-sm marb5 width-103p">전체닫기</button>
			<button type="button" onclick="nfor_jstree_create();" class="btn btn-gray btn-sm marb5 width-103p">하위분류생성</button>
			<button type="button" onclick="nfor_jstree_rename();" class="btn btn-gray btn-sm marb5 width-103p" >분류명변경</button>
			<button type="button" onclick="nfor_jstree_delete();" class="btn btn-gray btn-sm marb5 width-103p">분류삭제</button>
			<input type="text" id="nfor_jstree_q" placeholder="Search" class="form-control" />
			</div>


			<div id="jstree">
			<ul>
				<?
				$que = sql_query("select * from $table where cg_depth='0' order by cg_rank asc");
				while($data = sql_fetch_array($que)){
				?>
				<li id="<?=$data[category_id]?>"><?=$data[cg_category]?><ul>
					<?
					$que2 = sql_query("select * from $table where cg_depth_id='$data[cg_id]' order by cg_rank asc");
					while($data2 = sql_fetch_array($que2)){
					?>
					<li id="<?=$data2[category_id]?>"><?=$data2[cg_category]?><ul>
						<?
						$que3 = sql_query("select * from $table where cg_depth_id='$data2[cg_id]' order by cg_rank asc");
						while($data3 = sql_fetch_array($que3)){
						?>
						<li id="<?=$data3[category_id]?>"><?=$data3[cg_category]?><ul>
							<?
							$que4 = sql_query("select * from $table where cg_depth_id='$data3[cg_id]' order by cg_rank asc");
							while($data4 = sql_fetch_array($que4)){
							?>
							<li id="<?=$data4[category_id]?>"><?=$data4[cg_category]?></li>
							<? } ?>
							</ul>
						</li>
						<? } ?>
						</ul>
					</li>
					<? } ?>
					</ul>
				</li>
				<? } ?>
			</ul>
			</div>


	</div>
	<div style="float:left; width:80%;">
		<div id="category_form_wrap"><? include_once "$form_load"; ?></div>
	</div>
</div>

<script>
function category_form_load(category_id){
	$('#category_form_wrap').load('<?=$form_load?>', { 'category_id':category_id });
}

$('#jstree').on("move_node.jstree", function (e, data) {

	$.ajax({
		type: "post",
		url: "<?=$form?>",
		data: {
			"mode":"category_move",
			"parent":data.parent,
			"position":data.position,
			"category_id":data.node.id
		},
		cache: false,
		async: false,
		success: function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){
				var ref = $('#jstree').jstree(true),
				sel = ref.get_selected();
				$('#jstree').jstree(true).set_id(sel,json["category_id"]);
				category_form_load(json["category_id"]);
				alert(json["msg"]);
				location.reload();
			} else{
				alert(json["msg"]);
			}
		}
	});
});

$("#jstree").on('changed.jstree', function (e, data) {
	var category_id = data.selected[0];
	console.log(category_id);
	if(category_id != null){
		category_form_load(category_id);
	}
});

function nfor_jstree_open() {
	var ref = $('#jstree').jstree(true);
	ref.open_all();
}

function nfor_jstree_close() {
	var ref = $('#jstree').jstree(true);
	ref.close_all();
}

function nfor_back(cg_depth_id){
	$('#category_form_wrap').load('<?=$form_load?>', { 'cg_depth_id':cg_depth_id });
}

function nfor_jstree_create() {
	var ref = $('#jstree').jstree(true),
	sel = ref.get_selected();
	if(!sel.length) { return false; }
	sel = sel[0];
	var cg_depth_id = sel.substring(sel.length-4, sel.length); // 금번수정부분

	sel = ref.create_node(sel,{"id":sel+'new'});
	if(sel) {
		$('#jstree').jstree("deselect_all"); // 선택해제 이벤트
		$('#jstree').jstree(true).select_node(sel); //분류선택

		ref.edit(sel,'새분류',function(node, success, cancelled) {
			$.ajax({
				type: "post",
				url: "<?=$form?>",
				data: {
					"mode":"category_insert",
					"cg_depth_id":cg_depth_id,
					"cg_category":node.text
				},
				cache: false,
				async: false,
				success: function(response){
					var json = $.parseJSON(response); 
					if(json["result"]=="ok"){
						$('#jstree').jstree(true).set_id(sel,json["category_id"]);
						category_form_load(json["category_id"]);
					} else{
						alert(json["msg"]);
					}
				}
			});

        });
	}

}

function nfor_jstree_rename() {
	var ref = $('#jstree').jstree(true),
		sel = ref.get_selected();
	if(!sel.length) { return false; }
	sel = sel[0];
	ref.edit(sel,null,rename_callback);
}

function nfor_jstree_delete() {
	var ref = $('#jstree').jstree(true),
	sel = ref.get_selected();
	category_id = sel[0];
	var cg_category = ref.get_text(ref.get_selected());
	var confirm_str = "'"+cg_category+"' 분류를 삭제하시겠습니까?\n해당 분류 삭제시 하위분류도 함께 삭제되며 복구가 불가능합니다.\n확인을 누르시면 삭제를 진행합니다";

	if(confirm(confirm_str)){

		$.ajax({
			type: "post",
			url: "<?=$form?>",
			data: {
				"mode":"category_name_delete",
				"category_id":category_id
			},
			cache: false,
			async: false,
			success: function(response){
				var json = $.parseJSON(response); 
				if(json["result"]=="ok"){			
					if(!sel.length) { return false; }
					ref.delete_node(sel);

					category_form_load();
					location.reload();

				} else{
					alert(json["msg"]);
				}
			}
		});

	}

}

function rename_callback(){
	var ref = $('#jstree').jstree(true),
	category_id = ref.get_selected();
	var cg_category = ref.get_text(ref.get_selected());

	category_id = category_id[0];

	$.ajax({
		type: "post",
		url: "<?=$form?>",
		data: {
			"mode":"category_name_change",
			"category_id":category_id,
			"cg_category":cg_category
		},
		cache: false,
		async: false,
		success: function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){				
				category_form_load(category_id);
			} else{
				alert(json["msg"]);
			}
		}
	});
}

$(function () {
	var to = false;
	$('#nfor_jstree_q').keyup(function () {
		if(to) { clearTimeout(to); }
		to = setTimeout(function () {
			var v = $('#nfor_jstree_q').val();
			$('#jstree').jstree(true).search(v);
		}, 250);
	});

	$('#jstree')
		.jstree({
			"core" : {
				"animation" : 0,
				"check_callback" : true,
				"multiple": false,
				'force_text' : true,
				"themes" : { "stripes" : true }
			},
			"types" : {
				"#" : { "max_children" : 1, "max_depth" : 4, "valid_children" : ["root"] },
				"root" : { "icon" : "/static/3.3.2/assets/images/tree_icon.png", "valid_children" : ["default"] },
				"default" : { "valid_children" : ["default","file"] },
				"file" : { "icon" : "glyphicon glyphicon-file", "valid_children" : [] }
			},
			"plugins" : [ "contextmenu", "dnd", "search", "state", "types", "wholerow" ]
		});
});

/* 추천상품 */
$(document).on("click","#cg_hit_btn",function(){ 
	nfor_layer('hit');
});

function cg_hit_del(it_id){
	$("#it_id_"+it_id).remove();
}

$(document).on("click","#cg_hit_delete_btn",function(){ 
	$(".it_hit_chk:checked").each(function(index, campaign){
		$("#it_id_"+this.value).remove();
	});
});

/* 레벨선택 */
$(document).on("click","#cg_level_btn",function(){ 
	cg_level_check(3);
});

$(document).on("click","input[name=cg_level]",function(){ 
	cg_level_check(this.value);
});

function cg_level_check(val){
	if(val == "3"){
		$('input:radio[name=cg_level]:input[value='+val+']').attr("checked", true);
		$("#layer_level_append").show();
		$("#cg_level_btn").attr("disabled",false);
		nfor_layer('level');
	} else{
		$("#layer_level_append").hide();
		$("#cg_level_btn").attr("disabled",true);
		$("#cg_level_div").html("");
	}
}

/* 분류설정 */
function fsubmit(f){
    <?=admin_editor_update('cg_category_head')?>
    <?=admin_editor_update('cg_category_tail')?>

	f.action = "<?=$form_load?>";
	return true;	    
}
</script>



<!-- 레벨 -->
<script type="text/javascript">
<!--
$(document).on("click","#layer_level #insert_btn",function(){ 

	var str_lv_name = "";
	var lv_id = "";
	var lv_name = "";

	if($("#layer_level .chk:checked").length<1){
		alert("등급을 선택해주세요");
		return false;
	}

	$("#layer_level .chk:checked").each(function(index, campaign){
		lv_id = campaign.value;
		lv_name = $(this).data("lv_name");
		if(!$("#layer_level_append #level_"+lv_id).length){
			template = _.template($('#layer_level_script').html());
			template_html =  template({lv_name: lv_name, lv_id: lv_id});
			$("#layer_level_append").append(template_html);
		} else{
			if(str_lv_name) str_lv_name += ", ";
			str_lv_name += lv_name;
		}
	});

	if(str_lv_name){
		BootstrapDialog.alert("선택하신 "+str_lv_name+"은 이미 추가된 레벨입니다");
	}

	$('div.bootstrap-dialog-close-button').click();

});

$(document).on("click","#layer_level #allchk",function(){ 
	nfor_chk_all(this, 'chk');
});

$(document).on("click","#layer_level #search_btn",function(){ 
	layer_level_search();
});

$(document).on("keydown","#layer_level #keyword",function(e){ 
	if(e.keyCode == 13){
		layer_level_search();
		return false;
	}
});

function layer_level_search(){
	$.get("layer_level.php", $("#layer_level #form").serialize(), function (data) {
		$("#layer_level").html(data);
	});
}
//-->
</script>
<script type="text/html" id="layer_level_script">
    <li id="level_<%=lv_id%>">
        <span><%=lv_name%></span>
        <input type="hidden" name="cg_level_list_val[]" value="<%=lv_id%>">
		<img src="img/color_del.png" alt="삭제" class="li_remove">
    </li>
</script>
<!-- //레벨 -->

<?
include "tail.php";
?>