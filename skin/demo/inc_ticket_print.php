<?php
include_once "path.php";

if($lo_id){
	$location = sql_fetch("select * from nfor_item_location where lo_id='$lo_id'");
} else{
	$location = sql_fetch("select * from nfor_item_location where lo_it_id='$it_id' and lo_use='1' order by lo_id asc");
}
?>
<table border="1">
<tr>
	<td>
		
		<table>
		<tr>
			<th>업체명</th>
			<td><?=$location[lo_name]?></td>
		</tr>
		<tr>
			<th>주소</th>
			<td><?=$location[lo_address]?></td>
		</tr>
		<tr>
			<th>전화번호</th>
			<td><?=$location[lo_tel]?></td>
		</tr>
		</table>


	</td>
	<td>
	
		<img src="http://maps.google.com/maps/api/staticmap?size=300x200&zoom=15&sensor=false&center=<?=$location[lo_lat]?>,<?=$location[lo_lng]?>&markers=color:red|label:A|<?=$location[lo_lat]?>,<?=$location[lo_lng]?>">

	</td>
</tr>
</table>




