function campaign_qna_form(cp_id){
	window.open("campaign_qna_form.php?cp_id="+cp_id, "campaign_qna_form", "left=50,top=50,width=900,height=670,scrollbars=1");
}

function campaign_category_preview(){
	var campaign_select_preview = "";
	for(var k = 1; k <= 4; k++){
		if($("#campaign_category_"+k+" option:selected").val()){
			if(k>1){
				campaign_select_preview = campaign_select_preview + " > ";
			}
			campaign_select_preview = campaign_select_preview + $("#campaign_category_"+k+" option:selected").text();
		}
	}
	$("#campaign_select_preview").html(campaign_select_preview);
	if($("#campaign_select_preview").html()){
		$("#span_campaign_select_preview").removeClass("hide");
	} else{
		$("#span_campaign_select_preview").addClass("hide");
	}
}

function campaign_category_change(){

	var id = parseInt($(this).attr("id").substr(18,1));
	var prev_id = id - 1;
	var next_id = id + 1;
	var next_next_id = id + 2;
	var category_id = $(this).find(':selected').val();

	var prev_form = $(this).closest(".form-inline");

	if(category_id != ''){
		$(this).closest(".form-inline").find("#insert_cate_id").val(category_id);
		$.ajax({
			type     : "get",
			url      : nfor_path + "/json.php",
			data     : "mode=campaign_category&category_id="+category_id+"&depth="+id,
			dataType : 'json',
			cache: false,
			success  : function(data) {
				var output = '<option value="">==== '+next_id+'차 분류 ====</option>';
				for(var i=0; i<data.data.length; i++) {
					output += '<option value="' + data.data[i].category_id + '">' + data.data[i].cg_category + '</option>';
				}
				prev_form.find('#campaign_category_'+next_id).empty().append(output);
				for(var k = next_next_id; k <= 4; k++){
					prev_form.find('#campaign_category_'+k).empty().append('<option value="">==== '+k+'차 분류 ====</option>');
				}
			},
			error: function(e){
				console.log("Ajax failed");
				console.log(e);
			}
		});
	} else{
		var r = 0;
		for(var k = next_id; k <= 4; k++){
			$(this).closest(".form-inline").find('#campaign_category_'+k).empty().append('<option value="">==== '+k+'차 분류 ====</option>');
			r++;
		}

		if($('#campaign_category_'+prev_id).val()){
			$(this).closest(".form-inline").find("#insert_cate_id").val($('#campaign_category_'+prev_id).val());
		} else{
			$(this).closest(".form-inline").find("#insert_cate_id").val("");
		}
	}
}

