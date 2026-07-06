<!-- main_container -->
<div class="main_container">

	<!-- item_wrap -->
	<div class="item_wrap">

		<!-- 전체상품노출-->
		<div class="item_list_wrap">
			<p class="item_recommend_title">베스트 <b style="color:#d32f2f">상품보기</b></p>
			<?
			$result = sql_query(" select * from nfor_item where it_paydate <='$nfor[ymdhis]' and it_payenddate >='$nfor[ymdhis]' and it_view='0' and it_hit>0 order by it_hit desc");
			include $nfor[skin_path]."inc_index_list_item.php";
			?>

		</div>
		<!-- 전체상품노출-- -->
	</div>
	<!-- //item_wrap -->



</div>
<!-- //main_container -->