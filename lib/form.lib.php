<?php


function admin_radio_box($write,$name,$class="",$etc="",$all="1") {
	global $admin;
	$return = "";

	$k = 0;
	foreach($admin[$name] as $key=>$value){
		if($k >= $all){

			$return .= "<label class=\"radio-inline\"><input type=\"radio\" name=\"{$name}\" value=\"{$key}\"";	
			if($write[$name]==$key){
				$return .= " checked";
			}
			$return .= " class=\"{$class}\" {$etc}><em> ".$value."</em></label>";

		}
		$k++;
	}

	return $return;
	
}

function admin_checkbox_basic_chk($write, $name, $class="", $etc="", $label=""){
	
	$return = "";
	if($label){
		$return .= "<label>";
	}

	$return .= "<input type=\"checkbox\" name=\"$name\"";

	if(substr($name,-2) <> "[]"){
		$return .= " id=\"$name\"";
	}

	if(strlen($write[$name]) < 1 or !isset($write[$name])){
		$write[$name] = "1";	
	}

	$return .= " value=\"{$write[$name]}\" class=\"{$class}\" {$etc}>";
	
	if($label){
		$return .= " <em></em>$label</label>";
	}

	return $return;
}

function admin_checkbox_box($write, $name, $class="", $etc="", $label=""){
	
	$return = "";
	if($label){
		$return .= "<label>";
	}

	$return .= "<input type=\"checkbox\" name=\"$name\"";

	if(substr($name,-2) <> "[]"){
		$return .= " id=\"$name\"";
	}

	if(strlen($write[$name]) < 1 or !isset($write[$name])){
		$write[$name] = "1";	
	}

	$return .= " value=\"{$write[$name]}\" class=\"{$class}\" {$etc}>";
	
	if($label){
		$return .= " <em>$label</em></label>";
	}

	return $return;
}









function admin_radio($write,$name,$class="",$etc="",$all="1") {
	global $admin;
	$return = "";

	$k = 0;
	foreach($admin[$name] as $key=>$value){
		if($k >= $all){

			$return .= "<label class=\"radio-inline\"><input type=\"radio\" name=\"{$name}\" value=\"{$key}\"";	
			if($write[$name]==$key){
				$return .= " checked";
			}
			$return .= " class=\"{$class}\" {$etc}> <em></em>".$value."</label>";

		}
		$k++;
	}

	return $return;
	
}






















function admin_title($title,$class=""){
	$return = "<div class=\"$class\">$title</div>";
	return $return;
}




function admin_get(){
	global $_GET;
	$return = "<input type=\"hidden\" name=\"mode\" id=\"mode\">";
	foreach ($_GET as $key => $value){
		$return .= "<input type=\"hidden\" name=\"$key\" value=\"$value\">";
	}

	$ftimestamp = date("YmdHis").substr(microtime(),2,6);
	$return .= "<input type=\"hidden\" name=\"ftimestamp\" id=\"ftimestamp\" value=\"$ftimestamp\">";

	return $return;
}


function admin_sort($array,$sort,$id="sort") {

	$str = "<select name=\"$id\" id=\"$id\">";

	$key = array_keys($array);
	for($i=0; $i<count($key); $i++){
		$desc = $key[$i]." desc";
		$asc = $key[$i]." asc";
		$str .= "<option value=\"{$desc}\"";
		if($desc==$sort){
			$str .= " selected";
		}
		$str .= ">".$array[$key[$i]]." 올림차순정렬";

		$str .= "<option value=\"{$asc}\"";
		if($asc==$sort){
			$str .= " selected";
		}
		$str .= ">".$array[$key[$i]]." 내림차순정렬";

	}

	$str .= "</select>";

	return $str;
}

function admin_page_row($page_row) {

	$str = "<select name=\"page_row\" id=\"page_row\">";
	for($i=10; $i<=100; $i=$i+5){
		$str .= "<option value=\"$i\"";
		if($page_row==$i){
			$str .= " selected";
		}
		$str .= ">페이지당 {$i}개 표시";
	}

	for($i=150; $i<=500; $i=$i+50){
		$str .= "<option value=\"$i\"";
		if($page_row==$i){
			$str .= " selected";
		}
		$str .= ">페이지당 {$i}개 표시";
	}

	$str .= "</select>";

	return $str;
}

function admin_textarea_span($write, $id, $class="", $etc=""){
	$memo = $write[$id];
	return "<textarea name=\"{$id}\" id=\"{$id}\"  class=\"form-control {$class}\" {$etc}>{$memo}</textarea> <span id=\"{$id}_msg\" class=\"p_msg\"></span>";
}

function admin_textarea($write, $id, $class="", $etc=""){
	$memo = $write[$id];
	return "<textarea name=\"{$id}\" id=\"{$id}\"  class=\"form-control {$class}\" {$etc}>{$memo}</textarea>";
}

function admin_editor_update($id){

    // myeditor라는 이름은 현재 데모에서 만들어진 에디터 개체 이름입니다.
    //
    // myeditor.outputBodyHTML() 메서드를 호출하면 에디터에서 작성한 글 내용이
    // myeditor.inputForm 설정 옵션에 지정한 'fm_post' 폼 값에 자동으로 입력됩니다.
    //
    // outputBodyHTML:  BODY 태그 안쪽 내용을 가져옵니다.
    // outputBodyText:  BODY 태그 안쪽의 HTML 태그를 제외한 텍스트만을 가져옵니다.
    // inputLength:     입력한 텍스트 문자 수를 리턴합니다.
    // contentsLength:  BODY 태그 안쪽의 HTML 태그를 포함한 모든 문자 수를 리턴합니다.
    // contentsLengthAll: HTML 문서의 모든 문자 수를 리턴합니다.

    $str = "{$id}.outputBodyHTML();\n";
	return $str;

}

function admin_editor($write, $id, $width="100%", $height="300px"){

	$memo = $write[$id];

    $str = "<textarea id=\"{$id}\" name=\"{$id}\">{$memo}</textarea>";

	$str .= "<script type=\"text/javascript\">
			var {$id} = new cheditor();
			{$id}.config.editorHeight = '{$height}';
			{$id}.config.editorWidth = '{$width}';
			{$id}.inputForm = '{$id}';
			{$id}.run();
			</script>";

	return $str;
}


function admin_button($id, $value, $class="", $etc="") {
	return "<input type=\"button\" name=\"$id\" id=\"$id\" value=\"$value\" class=\"{$class}\" {$etc}>";
}

function admin_submit($id, $value, $class="", $etc="") {
	return "<input type=\"submit\" name=\"$id\" id=\"$id\" value=\"$value\" class=\"{$class}\" {$etc}>";
}

function admin_a($id, $value, $class, $etc="", $href=""){
	$return = "<a";
	if($href){
		$return .= " href=\"$href\"";
	}
	$return .= " name=\"$id\" id=\"$id\" class=\"{$class}\" role=\"button\" {$etc}>$value</a>";
	return $return;
}

function admin_hidden($write, $name, $class=""){
	if($name=="mode"){

		if($write){
			$write[$name] = "update";
		} else{
			$write[$name] = "insert";
		}
	}

	$return = "<input type=\"hidden\" name=\"{$name}\" ";

	if(substr($name,-2) <> "[]"){
		$return .= " id=\"$name\"";
	}

	if($class) $return .= "class=\"{$class}\"";
	$return .= " value=\"{$write[$name]}\">";
	return $return;
}

function admin_select($write,$name,$class="",$etc="",$all="1") {
	global $admin;

	$return = "<select name=\"$name\"";

	if(substr($name,-2) <> "[]"){
		$return .= " id=\"$name\"";
	}

	$return .= " class=\"form-control {$class}\" {$etc}>";

	if($admin[$name]){
	$k = 0;
	foreach($admin[$name] as $key=>$value){
		if($k >= $all){

			$return .= "<option value=\"$key\"";
			if($write[$name]==$key and isset($write[$name])) $return .= " selected";
			$return .= ">".$value;

		}
		$k++;
	}
	}
	$return .= "</select>";

	return $return;


}


function admin_select_span($write,$name,$class="",$etc="",$all="1") {
	global $admin;

	$return = "<select name=\"$name\" id=\"$name\" class=\"form-control {$class}\" {$etc}>";

	$k = 0;
	foreach($admin[$name] as $key=>$value){
		if($k >= $all){

			$return .= "<option value=\"$key\"";
			if($write[$name]==$key and isset($write[$name])) $return .= " selected";
			$return .= ">".$value;

		}
		$k++;
	}
	$return .= "</select> <span id=\"{$name}_msg\" class=\"p_msg\"></span>";

	return $return;


}

function admin_password_span($write,$name,$class="",$etc=""){

	$return = "<input type=\"password\" name=\"{$name}\" id=\"{$name}\" value=\"{$write[$name]}\" autocomplete=\"off\" class=\"form-control {$class}\" {$etc}> <span id=\"{$name}_msg\" class=\"p_msg\"></span>";
	return $return;
}

function admin_password($write,$name,$class="",$etc=""){

	$return = "<input type=\"password\" name=\"{$name}\" id=\"{$name}\" value=\"{$write[$name]}\" autocomplete=\"off\" class=\"form-control {$class}\" {$etc}>";
	return $return;
}


function admin_text_span($write,$name,$class="",$etc=""){

	$return = "<input type=\"text\" name=\"{$name}\" id=\"{$name}\" value=\"{$write[$name]}\" class=\"form-control {$class}\" {$etc}> <span id=\"{$name}_msg\" class=\"p_msg\"></span>";
	return $return;
}

function admin_help($msg,$class=""){
	$return = "<span class=\"notice_red $class\">$msg</span>";
	return $return;
}


function admin_text($write,$name,$class="",$etc=""){

	$return = "<input type=\"text\" name=\"{$name}\"";

	if(substr($name,-2) <> "[]"){
		$return .= " id=\"$name\"";
	}

	$return .= " value=\"{$write[$name]}\" class=\"form-control {$class}\" {$etc}>";

	return $return;
}


function admin_number($write,$name,$class="",$etc=""){
	$return = "<input type=\"number\" pattern=\"[0-9]*\" name=\"{$name}\"";
	if(substr($name,-2) <> "[]"){
		$return .= " id=\"$name\"";
	}
	$return .= " value=\"{$write[$name]}\" class=\"form-control {$class}\" {$etc}>";
	return $return;
}


function admin_checkbox($write, $name, $class="", $etc="", $label=""){
	
	$return = "";
	if($label){
		$return .= "<label>";
	}

	$return .= "<input type=\"checkbox\" name=\"$name\"";

	if(substr($name,-2) <> "[]"){
		$return .= " id=\"$name\"";
	}

	if(strlen($write[$name]) < 1 or !isset($write[$name])){
		$write[$name] = "1";	
	}

	$return .= " value=\"{$write[$name]}\" class=\"{$class}\" {$etc}>";
	
	if($label){
		$return .= " $label</label>";
	}

	return $return;
}




function admin_img($dir,$img,$w="",$h="",$class="",$etc=""){
	global $nfor;
	$return = "";
	$src = $nfor[path]."/data/".$dir."/".$img;

	if(file_exists($src) and $img){
		$src = thumbnail($src, $w, $h, 0, 1);
		$return = "<img src=\"{$src}\" class=\"{$class}\" {$etc}>";
	}

	return $return;
}

function admin_radio_span($write,$name,$class="",$etc="",$all="1") {
	global $admin;
	$return = "";

	$k = 0;
	foreach($admin[$name] as $key=>$value){
		if($k >= $all){

			$return .= "<label class=\"radio-inline\"><input type=\"radio\" name=\"{$name}\" value=\"{$key}\"";	
			if($write[$name]==$key){
				$return .= " checked";
			}
			$return .= " class=\" {$class}\" {$etc}> ".$value."</label>";

		}
		$k++;
	}

	$return .= " <span id=\"{$name}_msg\" class=\"p_msg\"></span>";

	return $return;
	
}


function period_day_echo($period_day){

	if(!strlen($period_day)){
		$period_day = "-1";
	}

	$array1 = array("전체","오늘","7일","15일","1개월","3개월");
	$array2 = array("-1","0","7","15","30","90");

	$return = "<div class=\"period_wrap\">";

	
	for($i=0; $i<count($array1); $i++){
		$return .= "<label";
		
		if($array2[$i]==$period_day){
			$return .= " class=\"checked\"";
			$checked = " checked=\"checked\"";
		} else{
			$checked = "";
		}

		$return .= ">     <input type=\"radio\" name=\"period_day\" value=\"{$array2[$i]}\" {$checked}>      {$array1[$i]}</label>";
	}

	$return .= "</div>";
	return $return;

}

?>