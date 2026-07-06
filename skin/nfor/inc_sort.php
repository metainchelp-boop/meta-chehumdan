

<script>

var sort_str = "";

var area_array = [];
var area_id_array = [];
var type_array = [];

$(document).on("click", ".order_option_wrap li a", function(){

	var order_text = $(this).data("order_text");
	var order_sort = $(this).data("order_sort");


	$(".order_option_wrap li a").removeClass("on");


	sort_str = order_sort;


	if($(this).hasClass("on")){
		$(this).removeClass("on");
	} else{
		$(this).addClass("on");
	}

	//item_list_load();

});

$(document).on("click", ".type_option_wrap li a", function(){

	var type_text = $(this).data("type_text");
	if(type_text){

		if(type_text=="부위전체"){
			$(".type_option_wrap li a").removeClass("on");
			type_array = [];
			all_type = "type_all";
		} else{
			type_array = $.grep(type_array, function(value) { 
				return value != "부위전체"; 
			});
			all_type = "";
			$("#type_all").removeClass("on");
		}

		if($.inArray(type_text, type_array) != -1){
			type_array.splice($.inArray(type_text, type_array),1);
			console.log(type_array);
		} else{
			type_array.push(type_text);
			console.log(type_array);
		}

	}

	if($(this).hasClass("on")){
		$(this).removeClass("on");
	} else{
		$(this).addClass("on");
	}

	//item_list_load();

});


$(document).on("click", ".area_option_wrap li a", function(){

	var area_id = $(this).data("area_id");
	var area_text = $(this).data("area_text");
	if(area_text){

		if(area_text=="지역전체"){
			$(".area_option_wrap li a").removeClass("on");
			area_array = [];
			area_id_array = [];
			all_type = "area_all";
		} else{
			area_array = $.grep(area_array, function(value) { 
				return value != "지역전체"; 
			});
			area_id_array = $.grep(area_id_array, function(value) { 
				return value != ""; 
			});
			all_type = "";
			$("#area_all").removeClass("on");
		}

		if($.inArray(area_text, area_array) != -1){

			area_array.splice($.inArray(area_text, area_array),1);
			area_id_array.splice($.inArray(area_id, area_id_array),1);
			console.log(area_array);
			console.log(area_id_array);

		} else{
			area_array.push(area_text);
			area_id_array.push(area_id);
			console.log(area_array);
			console.log(area_id_array);
		}

	}

	if($(this).hasClass("on")){
		$(this).removeClass("on");
	} else{
		$(this).addClass("on");
	}

	//item_list_load();

});


$(document).on("click", ".nfor_cancel", function(){
	$(".nfor_bottom_layer").hide();
});


$(document).on("click", ".nfor_bg", function(){
	$(".nfor_bottom_layer").hide();
});

$(document).on("click", ".sorting span", function(){
	var type = $(this).data("type");
	$(".nfor_bottom_layer").show();
	$(".common_option_wrap").hide();
	$("."+type+"_option_wrap").show();	
});





function area_str_func(){
	var area_str = "";
	var type_str = "";
	for(var i=0; i<area_id_array.length; i++){
		if(i>0) area_str += "||";
		area_str += area_id_array[i];
	}
	for(var i=0; i<type_array.length; i++){
		if(i>0) type_str += "||";
		type_str += type_array[i];
	}
	if(type_str=="부위전체"){
		type_str = "";
	}




	var string_str = "&area_str="+area_str+"&type_str="+type_str+"&sort="+sort_str;

	console.log(string_str);
	return string_str;
}
</script>