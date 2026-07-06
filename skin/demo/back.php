<!-- <colgroup>
	<col style="width:160px">
	<col>
	<col style="width:160px">
	<col>
</colgroup>
<tr>
	<th>매물종류</th>
	<td><?=admin_radio($write,"ho_type")?></td>
</tr>
<tr>
	<th>주소</th>
	<td>
		<div class="address">
		<div class="zip_wrap">
		<?=admin_text($write,"address_search_keyword","inppt"," placeholder=\"예) 가정1동 518-3,인천 서구 청라동\"")?>
		<?=admin_button("address_search_btn", "주소검색","btn_lg black")?>
		</div>
		<p>도로명 : <span id="ho_address1_span"><?=$write[ho_address1]?></span></p>
		<p>지번 : <span id="ho_address2_span"><?=$write[ho_address2]?></span></p>
		</div>
		<div id="map" style="<?=$write[ho_id]?"":"display:none;"?>"></div>
		


	
		<div class="ho_dong_wrap <?=$write[ho_no_dong]?"hide":""?>">동 <?=admin_text($write,"ho_dong")?></div>
		호 <?=admin_text($write,"ho_ho")?>

		<?=admin_checkbox($checkbox, "ho_no_dong", "", $write[ho_no_dong]?"checked":"", "등본에 동정보가 없는 경우 선택")?>

	</td>
</tr>
<tr>
	<th>거래종류</th>
	<td>

		<?=admin_checkbox($checkbox, "ho_sale", "", $write[ho_sale]?"checked":"", "매매")?>
		<?=admin_checkbox($checkbox, "ho_monthly_rent", "", $write[ho_monthly_rent]?"checked":"", "월세")?>
		<?=admin_checkbox($checkbox, "ho_yearly_rent", "", $write[ho_yearly_rent]?"checked":"", "전세")?>
		(<?=admin_checkbox($checkbox, "ho_short_rent", "", $write[ho_short_rent]?"checked":"", "단기 가능")?>)

	</td>
</tr>


<tr class="ho_sale_wrap <?=$write[ho_sale]?"":"hide"?>">
	<th>매매</th>
	<td>
		
		<?=admin_text($write,"ho_sale_price")?>만원

	</td>
</tr>



<tr class="ho_monthly_rent_wrap <?=$write[ho_monthly_rent]?"":"hide"?>">
	<th>월세</th>
	<td>

	<ul id="ho_monthly_rent_list_wrap">
		<?
		$exp1 = explode("||",$write[ho_monthly_rent_price1_array]);
		$exp2 = explode("||",$write[ho_monthly_rent_price2_array]);
		for($i=0; $i<count($exp1); $i++){
			$write["ho_monthly_rent_price1[]"] = $exp1[$i];
			$write["ho_monthly_rent_price2[]"] = $exp2[$i];
		?>
		<li>보증금 <?=admin_text($write,"ho_monthly_rent_price1[]")?>만원 / 월세 <?=admin_text($write,"ho_monthly_rent_price2[]")?> 만원 <span class="btn_pack"><?=admin_button("del_rent_price_btn", "- 삭제","del_rent_price_btn btn_sm white")?></span></li>
		<? } ?>
	</ul>

	<?=admin_button("ho_monthly_rent_add", "추가","btn_lg black")?>

	</td>
</tr>
<tr class="ho_yearly_rent_wrap <?=$write[ho_yearly_rent]?"":"hide"?>">
	<th>전세</th>
	<td>
		
		<?=admin_text($write,"ho_yearly_rent_price")?>만원

	</td>
</tr>
<tr>
	<th>건물크기</th>
	<td>
		공급면적
		<?=admin_text($write,"ho_pyung1")?>평(<?=admin_text($write,"ho_meter1")?>㎡)

		<br>

		전용면적
		<?=admin_text($write,"ho_pyung2")?>평(<?=admin_text($write,"ho_meter2")?>㎡)

	</td>
</tr>
<tr>
	<th>건물층수</th>
	<td>
		건물층수
		<?=admin_select($write,"ho_floor_top","","","0")?>

		해당층수
		<?=admin_select($write,"ho_floor_now","","","0")?>
	</td>
</tr>
<tr>
	<th>난방방식</th>
	<td>
		
		<?=admin_select($write,"ho_heating","","","0")?>

	</td>
</tr>
<tr>
	<th>입주 가능일</th>
	<td>
		
		<?=admin_radio($write,"ho_move_in_date_type")?>

		<?=admin_text($write,"ho_move_in_date")?>

	</td>
</tr>
<tr>
	<th>관리비</th>
	<td>

		<?=admin_text($write,"ho_management_price")?>만원

		<?=admin_checkbox($checkbox, "ho_no_management_price", "", $write[ho_no_management_price]?"checked":"", "관리비 없음")?>

	</td>
</tr>
<tr>
	<th>관리비항목</th>
	<td>

		<?=admin_checkbox($checkbox, "ho_management_1", "", $write[ho_management_1]?"checked":"", "전기요금")?>
		<?=admin_checkbox($checkbox, "ho_management_2", "", $write[ho_management_2]?"checked":"", "도시가스")?>
		<?=admin_checkbox($checkbox, "ho_management_3", "", $write[ho_management_3]?"checked":"", "수도세")?>
		<?=admin_checkbox($checkbox, "ho_management_4", "", $write[ho_management_4]?"checked":"", "청소비")?>
		<?=admin_checkbox($checkbox, "ho_management_5", "", $write[ho_management_5]?"checked":"", "유선방송")?>
		<?=admin_checkbox($checkbox, "ho_management_6", "", $write[ho_management_6]?"checked":"", "인터넷")?>
		<?=admin_checkbox($checkbox, "ho_management_7", "", $write[ho_management_7]?"checked":"", "기타")?>

	</td>
</tr>
<tr>
	<th>주차</th>
	<td>
		
		<?=admin_radio($write,"ho_parking")?>

	</td>
</tr>
<tr>
	<th>반려동물</th>
	<td>
		
		<?=admin_radio($write,"ho_pet")?>

	</td>
</tr>
<tr>
	<th>옵션</th>
	<td>

		<?=admin_checkbox($checkbox, "ho_option_1", "", $write[ho_option_1]?"checked":"", "에어컨")?>
		<?=admin_checkbox($checkbox, "ho_option_2", "", $write[ho_option_2]?"checked":"", "세탁기")?>
		<?=admin_checkbox($checkbox, "ho_option_3", "", $write[ho_option_3]?"checked":"", "침대")?>
		<?=admin_checkbox($checkbox, "ho_option_4", "", $write[ho_option_4]?"checked":"", "책상")?>
		<?=admin_checkbox($checkbox, "ho_option_5", "", $write[ho_option_5]?"checked":"", "옷장")?>
		<?=admin_checkbox($checkbox, "ho_option_6", "", $write[ho_option_6]?"checked":"", "TV")?>
		<?=admin_checkbox($checkbox, "ho_option_7", "", $write[ho_option_7]?"checked":"", "신발장")?>
		<?=admin_checkbox($checkbox, "ho_option_8", "", $write[ho_option_8]?"checked":"", "냉장고")?>
		<?=admin_checkbox($checkbox, "ho_option_9", "", $write[ho_option_9]?"checked":"", "가스레인지")?>
		<?=admin_checkbox($checkbox, "ho_option_10", "", $write[ho_option_10]?"checked":"", "인덕션")?>
		<?=admin_checkbox($checkbox, "ho_option_11", "", $write[ho_option_11]?"checked":"", "전자레인지")?>
		<?=admin_checkbox($checkbox, "ho_option_12", "", $write[ho_option_12]?"checked":"", "도어락")?>
		<?=admin_checkbox($checkbox, "ho_option_13", "", $write[ho_option_13]?"checked":"", "비데")?>

	</td>
</tr>
<tr>
	<th>전세자금대출</th>
	<td>
		
		<?=admin_radio($write,"ho_loan")?>

	</td>
</tr>



<tr>
	<th>준공년도</th>
	<td>
		
		<?=admin_text($write,"ho_completion_year")?>

	</td>
</tr>


 -->
