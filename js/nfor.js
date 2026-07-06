
function addslashes(string) {
    return string.replace(/\\/g, '\\\\').
        replace(/\u0008/g, '\\b').
        replace(/\t/g, '\\t').
        replace(/\n/g, '\\n').
        replace(/\f/g, '\\f').
        replace(/\r/g, '\\r').
        replace(/'/g, '\\\'').
        replace(/"/g, '\\"');
}
function htmlspecialchars(str) {
	var map = {
		'&': '&amp;',
		'<': '&lt;',
		'>': '&gt;',
		'"': '&quot;',
		"'": '&#039;'
	};
	return str.replace(/[&<>"']/g, function(m) { return map[m]; });
}

function logout(url){

	$.ajax({
		type: "post",
		url: nfor_path + "/login.php",
		data: {
			"mode":"logout",
			"url":url
		},
		cache: false,
		async: false,
		success: function(response){
			var json = $.parseJSON(response); 
			if(json["result"]=="ok"){	
				location.href = json["url"];
			} else{
				alert(json["msg"]);					
			}
		}
	});
}

function tbl_tr_add(tbl, td_cnt){
	var colspan = "";
	var append_html = "";

	var tr_last_id = $("."+tbl+"_tbl").find('tr:last').get(0).id;

	if (!tr_last_id) {
		tr_last_id = 0;
	}

	var tr_set_id = parseInt(tr_last_id) + 1;

	if (td_cnt == 2) {
		colspan = ' colspan="3"';
	}

	append_html += '<tr id="'+tr_set_id+'">';

	append_html += '<td><input type="checkbox" class="'+tbl+'_chk"></td>';

	append_html += '<th><input type="text" name="'+tbl+'[title]['+tr_set_id+'][0]" class="form-control"></th>';
	append_html += '<td' + colspan + '><input type="text" name="'+tbl+'[text]['+tr_set_id+'][0]" class="form-control"></td>';
	if (td_cnt == 4) {
		append_html += '<th><input type="text" name="'+tbl+'[title]['+tr_set_id+'][1]" class="form-control"></th>';
		append_html += '<td><input type="text" name="'+tbl+'[text]['+tr_set_id+'][1]" class="form-control"></td>';
	}
	append_html += '<td><button type="button" class="btn btn-default btn-sm tr_remove">삭제</button></td>';
	append_html += '</tr>\n';
	$("."+tbl+"_tbl tbody").append(append_html);
}



function tk_chg_win(ct_id){
    window.open("ticket_change.php?ct_id="+ct_id, "ticket_chage", "left=50,top=50,width=540,height=600,scrollbars=1");
}





function note(mb_id){
	if(mb_id){
		window.open("note_form.php?no_receive_id="+mb_id, "note", "left=50,top=50,width=900,height=800,scrollbars=1");
	} else{
		window.open("note_receive_list.php", "note", "left=50,top=50,width=900,height=800,scrollbars=1");
	}
}














function opt_cnt_total(layer, it_id){
	var opt_cnt_total = 0;
	$('.' + layer + ':first #it_id_'+it_id+' .opt_cnt').each(function(){
		opt_cnt_total = opt_cnt_total + parseInt($(this).val());
	});
	return opt_cnt_total;
}

function nfor_set_cookie( name, value, expiredays ){
	var todayDate = new Date();
	todayDate.setDate( todayDate.getDate() + expiredays );
	document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
}

function nfor_favorite(){

	if(window.chrome){
		alert("키보드의 Ctrl+D 키를 함께 누르시면 즐겨찾기에 추가됩니다.");
	} else if(window.sidebar){
		alert("키보드의 Ctrl+B 키를 함께 누르시면 즐겨찾기에 추가됩니다.");
		window.sidebar.addPanel(nfor_name, nfor_url, "");
	} else{
		window.external.AddFavorite(nfor_url, nfor_name);
	}

}

function value_change(){

	$.ajax({
		type     : "get",
		url      : nfor_path + "/json.php",
		data     : "mode=value&gp_type="+$(this).val(),
		dataType : 'json',
		cache: false,
		success  : function(data) {
			var output = '';

			for(var i=0; i<data.data.length; i++) {
				output += '<option value="' + data.data[i].gp_id + '">' + data.data[i].gp_name + '</option>';
			}

			$('#val_group').empty().append(output);


		},
		error: function(){
			console.log("Ajax failed");
		}
	});

}

function remove_id(remove_id){
	$("#"+remove_id).remove();
}

function category_row_delete(obj){
	$(obj).parent().parent().remove();
} 

function date_add_zero(i){
	var rtn = i + 100;
	return rtn.toString().substring(1,3);
}

function nfor_chk_all(this_id, this_class){
	if($(this_id).is(":checked")){
		$("."+this_class).prop("checked",true);
	} else{
		$("."+this_class).prop("checked",false);
	}
}

function nfor_radio_click(click_id, val) {
	$("[id*=\'"+click_id+"_\']").addClass("hide");
	$("#"+click_id+"_"+val).removeClass("hide");
}

function kcb_hp_win(){
	window.open(nfor_path + "/okname/hp1.php", "auth_popup", "width=430,height=590,scrollbar=yes");
}

function kcb_ipin_win(){
	window.open(nfor_path + "/okname/ipin1.php", "kcbPop", "left=200, top=100, status=0, width=450, height=550");
}

function ticket_send(od_id, it_id, ct_id){

	$.ajax({
		type: "post",
		url: nfor_path + "/order_list.php",
		data: {
			"mode":"ticket_send",
			"od_id":od_id,
			"it_id":it_id,
			"ct_id":ct_id
		},
		cache: false,
		async: false,
		success: function(response){
			var json = $.parseJSON(response);
			if(json["result"]=="ok"){
				alert(json["msg"]);
			} else{
				alert(json["msg"]);
			}
		}
	});

}

function order_cancel_btn(url,msg,ty){
	var confirm_msg = "";
	if(ty=="pg"){
		confirm_msg = "주문취소처리대상\n"+msg+"\n\n확인버튼을 누르실경우 전자결제(PG)와 주문서 상태가 함께 취소됩니다 그래도 진행하시겠습니까?\n\nPG 취소의 경우 전자결제(PG)업체와 통신을 진행하기 때문에 반드시 처리 완료 메시지가 나올때까지 기다려주세요";
	} else{
		confirm_msg = "주문취소처리대상\n"+msg+"\n\n확인버튼을 누르실경우 전자결제(PG)는 취소되지 않으며 주문서 상태만 취소됩니다 그래도 진행하시겠습니까?";
	}
	if(confirm(confirm_msg)){
		location.href = url;
	}
}

function order_asign_btn(url,msg){
	var confirm_msg = "주문완료처리대상\n"+msg+"\n\n확인버튼을 누르실경우 주문서 상태가 주문완료로 변경됩니다 그래도 진행하시겠습니까?";
	if(confirm(confirm_msg)){
		location.href = url;
	}
}

function order_cancelrequest_btn(url,msg){
	var confirm_msg = "주문취소신청처리대상\n"+msg+"\n\n확인버튼을 누르실경우 주문서 상태가 주문취소신청으로 변경됩니다 그래도 진행하시겠습니까?";
	if(confirm(confirm_msg)){
		location.href = url;
	}
}

function it_discount_rate_fnc(){
	if(parseInt($('#it_price1').val()) && parseInt($('#it_price2').val())){
		$('#it_discount_rate').val(100-Math.round((parseInt($('#it_price2').val())/parseInt($('#it_price1').val()))*100));
	} else{
		$('#it_discount_rate').val('');
	}
}

function password_confirm_check(ty){
	if(!$("#"+ty).val()){
		result = "비밀번호확인을 입력해주세요";
	} else if($("#mb_password").val() != $("#"+ty).val()){
		result = "먼저 입력하신 패스워드와 일치하지 않습니다";
	} else{
		result = "";
	}
	$("#"+ty+"_msg").html(result).css("color", "red");
	return result;
}

function nfor_check(field){
	var str = json_check(field);		
	if(str){
		alert(str);
		$("#"+field).focus();
		return false;
	} else{
		return true;
	}
}

function json_check(ty){
	var result = "";
	if(ty=="mb_password_confirm"){
		result = password_confirm_check("mb_password_confirm");
	} else {
		var value = "";
		if($(":radio[name="+ty+"]").length){
			value = $(":input:radio[name="+ty+"]:checked").val();
		} else{
			value = $("#"+ty).val();
		}
		$.ajax({
			type: "POST",
			url: nfor_path+"/json.php",
			data: {
				"mode":"nfor_check",
				"field" : ty,
				"value" : value,
				"mb_no": $("#mb_no").val()
			},
			cache: false,
			async: false,
			success: function(response){
				console.log(response);
				var json = $.parseJSON(response);

				if(json["result"]=="ok"){

					if(ty=="mb_hp"){
						$("#mb_hp_asign").hide();
						$("#asign_number").val("");
					}

					$("#"+ty+"_msg").html(json["msg"]).css("color", "blue");
					result = "";
				} else{
					
					if(json["result_detail"]=="is_number"){
						$("#mb_hp_asign").show();
						$("#asign_number").val("");
					}

					$("#"+ty+"_msg").html(json["msg"]).css("color", "red");
					result = json["msg"];
				}
			}
		});
	}
	return result;
}

function area_preview(){
	var select_preview = "";
	for(var k = 1; k <= 4; k++){
		if($("#area_"+k+" option:selected").val()){
			if(k>1){
				select_preview = select_preview + " > ";
			}
			select_preview = select_preview + $("#area_"+k+" option:selected").text();
		}
	}
	$("#select_area_preview").html(select_preview);
	if($("#select_area_preview").html()){
		$("#span_select_area_preview").removeClass("hide");
	} else{
		$("#span_select_area_preview").addClass("hide");
	}
}

function area_change(){

	var id = parseInt($(this).attr("id").substr(5,1));
	var prev_id = id - 1;
	var next_id = id + 1;
	var next_next_id = id + 2;
	var category_id = $(this).find(':selected').val();

	var prev_form = $(this).closest(".form-inline");

	if(category_id != ''){
		$(this).closest(".form-inline").find("#insert_area_id").val(category_id);
		$.ajax({
			type     : "get",
			url      : nfor_path + "/json.php",
			data     : "mode=area&category_id="+category_id+"&depth="+id,
			dataType : 'json',
			cache: false,
			success  : function(data) {
				var output = '<option value="">==== '+next_id+'차 분류 ====</option>';
				for(var i=0; i<data.data.length; i++) {
					output += '<option value="' + data.data[i].category_id + '">' + data.data[i].cg_category + '</option>';
				}
				prev_form.find('#area_'+next_id).empty().append(output);
				for(var k = next_next_id; k <= 4; k++){
					prev_form.find('#area_'+k).empty().append('<option value="">==== '+k+'차 분류 ====</option>');
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
			$(this).closest(".form-inline").find('#area_'+k).empty().append('<option value="">==== '+k+'차 분류 ====</option>');
			r++;
		}

		if($('#area_'+prev_id).val()){
			$(this).closest(".form-inline").find("#insert_area_id").val($('#area_'+prev_id).val());
		} else{
			$(this).closest(".form-inline").find("#insert_area_id").val("");
		}
	}
}

function brand_change(){

	var id = parseInt($(this).attr("id").substr(9,1));
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
			data     : "mode=brand&category_id="+category_id+"&depth="+id,
			dataType : 'json',
			cache: false,
			success  : function(data) {

				var output = '<option value="">==== '+next_id+'차 분류 ====</option>';
				
				if(data.cnt > 0){
					for(var i=0; i<data.data.length; i++) {
						output += '<option value="' + data.data[i].category_id + '">' + data.data[i].cg_category + '</option>';
					}
				}

				prev_form.find('#category_'+next_id).empty().append(output);


				for(var k = next_next_id; k <= 4; k++){
					prev_form.find('#category_'+k).empty().append('<option value="">==== '+k+'차 분류 ====</option>');
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
			$(this).closest(".form-inline").find('#category_'+k).empty().append('<option value="">==== '+k+'차 분류 ====</option>');
			r++;
		}

		if($(this).closest(".form-inline").find('#category_'+prev_id).val()){
			$(this).closest(".form-inline").find("#insert_cate_id").val($(this).closest(".form-inline").find('#category_'+prev_id).val());
		} else{
			$(this).closest(".form-inline").find("#insert_cate_id").val("");
		}
	}
}

function set_cookie(name, value, expirehours, domain){
	var today = new Date();
	today.setTime(today.getTime() + (60*60*1000*expirehours));
	document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + today.toGMTString() + ";";
	if (domain) {
		document.cookie += "domain=" + domain + ";";
	}
}

function get_cookie(name){
	var find_sw = false;
	var start, end;
	var i = 0;

	for (i=0; i<= document.cookie.length; i++)
	{
		start = i;
		end = start + name.length;

		if(document.cookie.substring(start, end) == name)
		{
			find_sw = true
			break
		}
	}

	if (find_sw == true)
	{
		start = end + 1;
		end = document.cookie.indexOf(";", start);

		if(end < start)
			end = document.cookie.length;

		return document.cookie.substring(start, end);
	}
	return "";
}

function del_cookie(name){
	var today = new Date();

	today.setTime(today.getTime() - 1);
	var value = get_cookie(name);
	if(value != "")
		document.cookie = name + "=" + value + "; path=/; expires=" + today.toGMTString();
}

function sns_link(cp){
	
	var kakaostory_text = '[' + sns_title + '] \n' + sns_description;
	var kakaotalk_text = '[' + sns_title + '] \n' + sns_description;
	var twitter_text = '[' + sns_title + '] \n' + sns_description;
	var naverline_text = '[' + sns_title + '] ' + sns_description + ' ' + sns_url;
	var naverblog_text = '[' + sns_title + '] \n' + sns_description;
	naverblog_text = encodeURI(naverblog_text);

	var sms_text = sns_url;
	var broswerInfo = navigator.userAgent;
	if(broswerInfo.indexOf("NFOR_APP")>-1){
		if(cp=="naverline"){
			location.href="line://msg/text/" + naverline_text;
		} else if(cp=="kakaostory"){
			location.href="storylink://posting?post=" + kakaostory_text + "&appid="+app_package+"&appver=14.0&apiver=1.0&appname="+nfor_name;
		} else{

		}
	} else{
		if(cp=="naverline"){
			sns_url = encodeURI(sns_url);
			location.href="https://social-plugins.line.me/lineit/share?url=" + sns_url;
		} else if(cp=="kakaostory"){
			Kakao.Story.share({
				url: sns_url,
				text: kakaostory_text
			});
		} else{

		}
	}

	if(cp=="naver"){
		sns_url = encodeURI(encodeURIComponent(sns_url));
		var url = "http://share.naver.com/web/shareView.nhn?url="+sns_url+"&title="+naverblog_text;
		if(is_mobile){
			location.href = url;
		} else{
			newwindow = window.open(url,'naverpopup', 'toolbar=0, status=0, width=626, height=626');
		}
	} else if(cp=="naverblog"){
		sns_url = encodeURI(sns_url);
		location.href="http://blog.naver.com/openapi/share?url="+sns_url+"&title="+naverblog_text;
	} else if(cp=="kakaotalk"){

		Kakao.Link.sendDefault({
			objectType: 'feed',
			content: {
			  title: kakaotalk_text,
			  //description: kakaotalk_text,
			  imageUrl: sns_img,
			  link: {
				mobileWebUrl: sns_url,
				webUrl: sns_url
			  }
			}
		});

	} else if(cp=="copy"){
		prompt("길게 누르셔서 전체를 선택후 복사하세요",sns_url);
	} else if(cp=="twitter"){
		window.open('http://twitter.com/share?text=' + encodeURIComponent(twitter_text) + '&url=' + sns_url,'twitterpopup', 'toolbar=0, status=0, width=626, height=436');
	} else if(cp=="facebook"){
		newwindow = window.open('http://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(sns_url),'facebookpopup', 'toolbar=0, status=0, width=626, height=436');
		if(window.focus){ newwindow.focus(); }
	} else{

	}
}

function show_hide(div){
	if($(div).css('display')=="none"){
		$(div).show();
	} else{
		$(div).hide();
	}
}

function nfor_list_up(str,action,frm,hide){
	if(frm){
		$('#mode'+frm).val(action);
	} else{
		$('#mode').val(action);
	}

	var chk = document.getElementsByName("chk[]");
	var bchk = false;

	for (i=0; i<chk.length; i++){
		if (chk[i].checked)
			bchk = true;
	}

	if (!bchk){
		alert(str+"할 자료를 하나 이상 선택하세요");
		return;
	}

	$("."+hide).hide();
	if(frm){
		$('#flist'+frm).submit();
	} else{
		$('#flist').submit();
	}
}

function nfor_list(str,action,frm){
	if(frm){
		$('#mode'+frm).val(action);
	} else{
		$('#mode').val(action);
	}

	var chk = document.getElementsByName("chk[]");
	var bchk = false;

	for (i=0; i<chk.length; i++){
		if (chk[i].checked)
			bchk = true;
	}

	if (!bchk){
		alert(str+"할 자료를 하나 이상 선택하세요");
		return;
	}
	if(frm){
		$('#flist'+frm).submit();
	} else{
		$('#flist').submit();
	}
}

function faq_show(ty,wr_id){
	if($(ty+'_'+wr_id).css('display')=="none"){
		$(ty).hide();
		$(ty+'_'+wr_id).show();
	} else{
		$(ty+'_'+wr_id).hide();
	}
}

function search_it_id(key){
    window.open("search_it_id.php?key="+key, "search_it_id", "left=50,top=50,width=600,height=600,scrollbars=1");
}

function search_category_id(key){
    window.open("search_category_id.php?key="+key, "search_category_id", "left=50,top=50,width=600,height=600,scrollbars=1");
}

function search_mb_id(key){
    window.open("search_mb_id.php?key="+key, "search_mb_id", "left=50,top=50,width=600,height=600,scrollbars=1");
}

function insert_val(key,val){
	$("#"+key, opener.document).val(val);
    window.close();
}

function ticket_print(od_id, it_id){
	var url = nfor_path+"/ticket_print.php?od_id="+od_id+"&it_id="+it_id;
    window.open(url, "ticket_print", "left=50,top=50,width=770,height=600,scrollbars=1");
}


function order_cancel_confirm(ct_id){
	var url = nfor_path+"/order_cancel_confirm.php?ct_id="+ct_id;
    window.open(url, "order_cancle_confirm", "left=50,top=50,width=770,height=600,scrollbars=1");
}

function delivery_change(od_id){
	var url = nfor_path+"/delivery_change.php?od_id="+od_id;
    window.open(url, "delivery_change", "left=50,top=50,width=770,height=400,scrollbars=1");
}

function my_address(){
	var url = "myaddress.php";
    window.open(url, "my_address", "left=50,top=50,width=770,height=600,scrollbars=1");
}

function it_select(frm,fid){
	window.open('it_select.php?frm='+frm+'&fid='+fid+'&it_value='+$('#'+fid).val(),'it_select', 'width=1000, height=900, scrollbars=yes');
}

function company_info(wrkr_no){
	var url = "http://www.ftc.go.kr/info/bizinfo/communicationViewPopup.jsp?wrkr_no="+wrkr_no;
	window.open(url, "company_info", "width=750, height=700;");
}

function zipcode(mb_zipcode,mb_addr1,mb_addr2){
	// 2026-06-15: .open() 팝업 → 자체 오버레이 .embed() 방식으로 변경.
	//  카카오/네이버 인앱 브라우저는 팝업창(.open)을 막아 우편번호찾기가 먹통이 되므로,
	//  페이지 내부에 iframe(.embed)으로 주소검색을 띄운다.
	if(typeof daum === 'undefined' || !daum.Postcode){
		alert('주소 검색을 불러오지 못했습니다. 새로고침 후 다시 시도해 주세요.');
		return;
	}
	var ov = document.getElementById('mc-zip-ov');
	if(!ov){
		ov = document.createElement('div'); ov.id = 'mc-zip-ov';
		ov.style.cssText = 'position:fixed;left:0;top:0;right:0;bottom:0;z-index:99999;background:rgba(0,0,0,.5);display:none;align-items:center;justify-content:center;';
		var box = document.createElement('div');
		box.style.cssText = 'position:relative;width:96%;max-width:480px;height:80%;max-height:560px;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 10px 40px rgba(0,0,0,.3);display:flex;flex-direction:column;';
		var bar = document.createElement('div');
		bar.style.cssText = 'height:46px;flex:0 0 46px;display:flex;align-items:center;justify-content:space-between;padding:0 14px;border-bottom:1px solid #eee;font-weight:700;font-size:15px;';
		bar.innerHTML = '<span>주소 검색</span>';
		var x = document.createElement('button'); x.type='button'; x.textContent='닫기 ✕';
		x.style.cssText = 'border:none;background:#f1f3f5;border-radius:8px;padding:8px 13px;cursor:pointer;font-size:13px;font-weight:700;';
		x.onclick = function(){ ov.style.display='none'; document.getElementById('mc-zip-embed').innerHTML=''; };
		bar.appendChild(x);
		var host = document.createElement('div'); host.id='mc-zip-embed'; host.style.cssText='flex:1;width:100%;';
		box.appendChild(bar); box.appendChild(host); ov.appendChild(box); document.body.appendChild(ov);
		ov.addEventListener('click', function(e){ if(e.target===ov){ ov.style.display='none'; host.innerHTML=''; } });
	}
	var embedHost = document.getElementById('mc-zip-embed');
	embedHost.innerHTML = '';
	ov.style.display = 'flex';
	new daum.Postcode({
		oncomplete: function(data) {
			var fullRoadAddr = data.roadAddress;
			var extraRoadAddr = '';
			if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){ extraRoadAddr += data.bname; }
			if(data.buildingName !== '' && data.apartment === 'Y'){ extraRoadAddr += (extraRoadAddr !== '' ? ', ' + data.buildingName : data.buildingName); }
			if(extraRoadAddr !== ''){ extraRoadAddr = ' (' + extraRoadAddr + ')'; }
			if(fullRoadAddr !== ''){ fullRoadAddr += extraRoadAddr; }
			document.getElementById(mb_zipcode).value = data.zonecode;
			document.getElementById(mb_addr1).value = fullRoadAddr;
			ov.style.display = 'none'; embedHost.innerHTML = '';
			var a2 = document.getElementById(mb_addr2); if(a2) a2.focus();
		},
		width: '100%', height: '100%'
	}).embed(embedHost);
}

function number_format(data){

	data = data+'';

	var tmp = '';
	var number = '';
	var cutlen = 3;
	var comma = ',';
	var i;

	len = data.length;
	mod = (len % cutlen);
	k = cutlen - mod;
	for (i=0; i<data.length; i++)
	{
		number = number + data.charAt(i);

		if (i < data.length - 1)
		{
			k++;
			if ((k % cutlen) == 0)
			{
				number = number + comma;
				k = 0;
			}
		}
	}
	return number;
}

function sms_count(obj,name) {
	var bytesLength = 0;
	var validMsgLength = 0;
	var validBytesLength = 0;
	for ( i = 0; i < obj.value.length; i++ ) {
		var oneChar = obj.value.charAt(i);
		if (escape(oneChar).length > 4) {
			bytesLength += 2;
		} else if (oneChar != '\r') {
			bytesLength++;
		}
		if ( bytesLength <= 80 )	{
			validMsgLength = i + 1;
			validBytesLength = bytesLength;
		}
	}
	if (bytesLength > 80) {
		alert("80바이트 이상의 메세지는 전송하실 수 없습니다");
		realValue = obj.value.substr(0, validMsgLength);
		obj.value = realValue;
		bytesVal = validBytesLength;
	} else {
		bytesVal = bytesLength;
	}
	$('#'+name).html(bytesVal);
	obj.focus();
}

function nfor_copy_url(type){
	var doc = $('#'+type).get(0).createTextRange();
	$('#'+type).get(0).select();
    doc.execCommand('copy');
    alert('주소가 복사 되었습니다');
	return;
}

function del(href){
	if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")){
		document.location.href = href;
    }
}

function check_all(f){
	var chk = document.getElementsByName("chk[]");
	for (i=0; i<chk.length; i++)
		chk[i].checked = f.chkall.checked;
}

function nfor_load(tbl_id, load_page, page){

	$.ajax({
	   type: "POST",
	   url: nfor_path+"/"+load_page,
	   data: "page=" + page,
	   success: function(response){
			$('#'+tbl_id).html(response);
	   }
	});

}