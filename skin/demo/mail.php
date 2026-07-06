<style>
@charset "utf-8";
html {overflow-y:scroll}
html, body { width:100%; height:100%; font-family:'돋움';}
body,p,h1,h2,h3,h4,h5,h6,ul,ol,li,dl,dt,dd,table,th,td,form,fieldset,legend,input,textarea a,button,select{margin:0;padding:0} 
ul,ol{list-style:none} em,address{font-style:normal} 
a:hover,a:active,a:focus{text-decoration:none; color:#333;} 
a { color:#333; text-decoration:none; }
a:hover { color:#333; text-decoration:none; }
.blind{visibility:hidden;overflow:hidden;position:absolute;top:0;left:0;width:1px;height: 1px;font-size:0;line-height:0} 
.layout_inner{position:relative;width:1000px; margin:0px auto; }
*:focus { outline:none;} 

.mail_wrap{width:100%; background-color:#f8f8f8; margin:0px; padding:0px;}
.mail_wrap .inner{width:800px; margin:0px auto; background-color:#FFF; padding-top:20px;}
.mail_wrap .inner .logo{padding:50px 20px 20px; }
.mail_wrap .inner .logo img{height:25px; border:0; }
.mail_wrap .inner .con{padding:20px; text-align:left; min-height:200px; display:block;font-size: 16px; font-family: '나눔고딕','NanumGothic','맑은고딕','Malgun Gothic','돋움','Dotum','Helvetica,Apple SD Gothic',' Neo,Sans-serif'; color: #484242;}

.mail_wrap .inner .b_title{display:block;color: rgb(68, 68, 68); letter-spacing: -3px; font-size: 32px;font-family: '맑은고딕','Malgun Gothic'; font-weight:bold; padding:10px;}
.mail_wrap .inner .s_title{padding:0px 10px; display:block; line-height: 20px; margin-top: 30px; font-size:12px; font-family: '나눔고딕','NanumGothic','맑은고딕','Malgun Gothic','돋움','Dotum','Helvetica,Apple SD Gothic',' Neo,Sans-serif';}


.mail_wrap .inner .tbl02 {width:100%; border-top-color:#b5b5b5; border-top-width: 1px; border-top-style: solid; border-collapse: collapse;}
.mail_wrap .inner .tbl02 th{height: 43px; font-size: 13px; border-bottom-color: #dadada; border-bottom-width: 1px; border-bottom-style: solid; background-color: #f7f7f7;}
.mail_wrap .inner .tbl02 td{height: 43px; text-align: center; padding-top: 15px; padding-bottom: 15px; padding-left: 20px; font-size: 13px; border-bottom-color: #dadada; border-bottom-width: 1px; border-bottom-style: solid;}
.mail_wrap .inner .tbl02 td span{color: rgb(153, 153, 153); font-size: 12px;}

.mail_wrap .inner .tbl03 {width:100%; border-top-color:#b5b5b5; border-top-width: 1px; border-top-style: solid; border-collapse: collapse;}
.mail_wrap .inner .tbl03 th{height: 43px; color: rgb(136, 136, 136); padding-right: 20px; padding-left: 20px; font-size: 13px; border-bottom-color: #e5e5e5; border-bottom-width: 1px; border-bottom-style: solid; background-color:#f7f7f7;}
.mail_wrap .inner .tbl03 td{width: 80%; height: 43px; color: rgb(51, 51, 51); padding-left: 20px; font-size: 13px; border-bottom-color: #e5e5e5; border-bottom-width: 1px; border-bottom-style: solid;}
.mail_wrap .inner .tbl03 td span{color: rgb(153, 153, 153); font-size: 12px;}


.mail_wrap .inner .con b{color:#ff3300}
.mail_wrap .inner .footer{background-color:#e5e5e5;  color: rgb(105, 105, 105); line-height:17px; font-family: "나눔고딕,NanumGothic,맑은고딕,Malgun Gothic,돋움,Dotum,Helvetica,Apple SD Gothic Neo,Sans-serif"; font-size: 12px;}
.mail_wrap .inner .footer .customer{color:#696969; font-weight: bold; text-decoration: underline;}
.mail_wrap .inner .footer .txt{ padding: 26px 21px 13px;}
.mail_wrap .inner .footer .address{padding: 10px 21px 13px;}
.mail_wrap .inner .footer .link{padding: 10px 21px 13px;}
.mail_wrap .inner .footer .link a{padding: 0px 10px;  text-decoration: none;}
.mail_wrap .inner .footer .link a:nth-child(1){padding:0px;}
.mail_wrap .inner .footer .copy{line-height: 17px; padding-right: 21px; padding-bottom: 57px; padding-left: 21px; font-family: Helvetica; font-size: 12px}


.mail_sub_title { margin: 40px 0px 10px; color: rgb(68, 68, 68); font-size: 15px; font-weight: bold; } 
.site_link_btn { display:block; width: 250px; text-align:center;height: 64px; font-size:18px; line-height: 64px; margin-top:20px; background-color:#e61b62;  border:solid 1px #e61b62; color:#FFF; font-family:'NanumGothicBold'; margin:0px auto; }
</style>


<!--  -->
<style>
.mail_menu{background-color:#666; height:50px; }
.mail_menu a{display:inline-block; padding:5px 20px; font-size:12px; color:#666;background-color:#FFF; margin-top:15px;}
</style>
<div class="mail_menu">
<a href="">광고메일</a>
<a href="">회원가입</a>
<a href="">비밀번호 /아이디</a>
<a href="">주문취소</a>
<a href="http://s009.nfor.net/mail.php">구매안내</a>
<a href="">기타안내</a>
</div>
<!--  -->


<div class="mail_wrap">
	<div class="inner">
		<div class="logo"><a href=""><img src="/skin/demo/img/logo.png" /></a></div>
		<div class="con">
			<span class="b_title">주문하신 상품이 발송 되셨습니다.</span>
			<div class="s_title">
            <strong>{아이디}</strong>님, 저희 쇼핑몰을 이용하여 주셔서 감사합니다. <br> {아이디} 님께서 주문하신 상품이 아래와 같이 발송되었습니다. <br> 고객님께 빠르고 정확하게 제품이 전달될 수 있도록 최선을 다하겠습니다.
			</div>
			<div style="padding:10px;">
			<div class="mail_sub_title">상품배송정보</div>
			 <table class="tbl02">
				<colgroup>
					<col width="20%">
					<col width="">
					<col width="10%">
					<col width="">
				</colgroup>
				<thead>
				<tr>
					<th>주문번호</th>
					<th>상품명</th>
					<th>수량</th>
					<th>배송정보</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td >주문번호</td>
					<td>
						대분류명<br>
						<span >상품설명</span>
						<br>
						<span>옵션명</span>
						</td>
						<td>수량</td>
						<td>한진택배<br><a href="#" target="_blank">123456789</a></td>
					</tr>
					<!--{/}-->
					</tbody>
				</table>

				<div class="mail_sub_title">배송지정보</div>
				<table class="tbl03">
					<tbody>
					<tr>
						<th>받는사람</th>
						<td style="">임준아</td>
					</tr>
					<tr>
						<th >주소</th>
						<td >인천시 서구경서동 청라더샵 레이크파크 451동 3104호 452-52</td>
					</tr>
					<tr>
						<th>전화번호</th>
						<td>010-2774-4531</td>
					</tr>
					<tr>
						<th>휴대폰번호</th>
						<td >010-2774-4531</td>
					</tr>
					<tr>
						<th >배송메시지</th>
						<td >부재시 연락바람</td>
					</tr>
					</tbody>
				</table>
				<a href="#" style="display:block; width: 250px; text-align:center;height: 64px; font-size:18px; line-height: 64px;; background-color:#e61b62;  border:solid 1px #e61b62; color:#FFF; font-family:'NanumGothicBold'; margin:20px auto;">사이트 바로가기</a>
				</div>

		</div>
			<div class="footer" >
				<div class="txt">
				본 메일은 발신전용입니다.<br>
				상품의 배송/반품/교환/취소/환불 문의는 판매자에게 문의하시면 더 빠르고 정확한 처리가 가능합니다.<br>
				메일 수신을 원하지 않으시면, 회원정보 수정에서 메일 수신거부를 선택해 주십시오<br>
				이용 관련 제안사항이나 불편 신고는 <a style=";" href="#" target="_blank">고객센터</a>를 이용해 주세요.
				</div>
				<div class="link" >
				<a href="#" target="_blank">이용약관</a>  l  <a href="#" target="_blank">청약철회 등에 관한 사항</a>  l  <a href="#" target="_blank">개인정보 취급방침</a>
				</div>
				<div class="address">
				상호 : {상호명} | 사업자등록번호 : {사업자등록번호} | 통신판매업신고 : {통신판매업신고}<br>
				주소 : {사업장소재지} | 대표자 : {대표자명} <br />개인정보보호책임자 : {개인정보보호책임자}({개인정보보호책임자이메일}) | 고객센터 : {대표전화}
				</div>

				<div class="copy"> Copyright ⓒ <strong>{상점명}.</strong> All Rights Reserved.</div>
		  </div>
	</div>
</div>

<!-- 광고메일 헤드 풋터 -->
<div class="mail_wrap">
	<div class="inner">
		<div class="logo"><a href=""><img src="/skin/demo/img/logo.png" /></a></div>
		

			<div class="footer" >
				<div class="txt">
				본 메일은 발신전용입니다.<br>
				상품의 배송/반품/교환/취소/환불 문의는 판매자에게 문의하시면 더 빠르고 정확한 처리가 가능합니다.<br>
				메일 수신을 원하지 않으시면, 회원정보 수정에서 메일 수신거부를 선택해 주십시오<br>
				이용 관련 제안사항이나 불편 신고는 <a style=";" href="#" target="_blank">고객센터</a>를 이용해 주세요.
				</div>
				<div class="link" >
				<a href="#" target="_blank">이용약관</a>  l  <a href="#" target="_blank">청약철회 등에 관한 사항</a>  l  <a href="#" target="_blank">개인정보 취급방침</a>
				</div>
				<div class="address">
				상호 : {상호명} | 사업자등록번호 : {사업자등록번호} | 통신판매업신고 : {통신판매업신고}<br>
				주소 : {사업장소재지} | 대표자 : {대표자명} <br />개인정보보호책임자 : {개인정보보호책임자}({개인정보보호책임자이메일}) | 고객센터 : {대표전화}
				</div>

				<div class="copy"> Copyright ⓒ <strong>{상점명}.</strong> All Rights Reserved.</div>
		  </div>
	</div>
</div>












<!-- 회원가입
<div class="mail_wrap">
	<div class="inner">
		<div class="logo"><a href=""><img src="{대표도메인}/skin/demo/img/logo.png" /></a></div>
		
			<div class="con">
			<span class="b_title">가입해 주셔서 감사합니다  .</span>
			<div class="s_title">
            <strong>{아이디}</strong>님, 앞으로 고객님께 알찬 정보, 다양한 이벤트와 혜택을 드리고자 최선을 다하겠습니다.
			</div>
			<div class="mail_sub_title">회원등록 정보</div>
				<table class="tbl03">
					<tbody>
					<tr>
						<th>아이디</th>
						<td>{아이디}</td>
					</tr>
					<tr>
						<th>이름</th>
						<td>{이름}</td>
					</tr>
					</tbody>
				</table>
				<div class="s_title">앞으로 더 좋은 서비스로 항상 고객님과 함께 하는 {상점명}이 될 것을 약속드립니다. 감사합니다</div><br><br>
				<a href="{대표도메인}" class="site_link_btn">사이트 바로가기</a>
			</div>
			<div class="footer" >
				<div class="txt">
				본 메일은 발신전용입니다.<br>
				메일 수신을 원하지 않으시면, 회원정보 수정에서 메일 수신거부를 선택해 주세요
				</div>
				<div class="address">
				상호 : {상호명} | 사업자등록번호 : {사업자등록번호} | 통신판매업신고 : {통신판매업신고}<br>
				주소 : {사업장소재지} | 대표자 : {대표자명} <br>개인정보보호책임자 : {개인정보보호책임자}({개인정보보호책임자이메일}) | 고객센터 : {대표전화}
				</div>

				<div class="copy"> Copyright ⓒ <strong>{상점명}.</strong> All Rights Reserved.</div>
		  </div>
	</div>
</div>
-->










<!-- 비번 찾기
<div class="mail_wrap">
	<div class="inner">
		<div class="logo"><a href="{대표도메인}"><img src="{대표도메인}/skin/demo/img/logo.png" /></a></div>
		
			<div class="con">
			<span class="b_title">비밀번호 찾기 다음과 같이 알려드립니다. </span>
			<div class="s_title">
            <strong>{아이디}</strong>님, 앞으로 고객님께 알찬 정보, 다양한 이벤트와 혜택을 드리고자 최선을 다하겠습니다.
			</div>
			<div class="mail_sub_title">회원등록 정보</div>
				<table class="tbl03">
					<tbody>		
					<tr>
						<th>아이디</th>
						<td>{아이디}</td>
					</tr>
					<tr>
						<th>비밀번호</th>
						<td>{비밀번호}</td>
					</tr>
					</tbody>
				</table>
				<div class="s_title">보다 상세한 가입정보는 홈페이지>마이페이지>나의 회원정보에서확인 및 변경하실 수 있습니다. 앞으로 더 좋은 서비스로 항상 고객님과 함께 하는 {상점명}이 될 것을 약속드립니다. 감사합니다</div><br><br>
				</div>
			<div class="footer">
				<div class="txt">
				본 메일은 발신전용입니다.<br>
				메일 수신을 원하지 않으시면, 회원정보 수정에서 메일 수신거부를 선택해 주세요
				</div>
				<div class="address">
				상호 : {상호명} | 사업자등록번호 : {사업자등록번호} | 통신판매업신고 : {통신판매업신고}<br>
				주소 : {사업장소재지} | 대표자 : {대표자명} <br>개인정보보호책임자 : {개인정보보호책임자}({개인정보보호책임자이메일}) | 고객센터 : {대표전화}
				</div>

				<div class="copy"> Copyright ⓒ <strong>{상점명}.</strong> All Rights Reserved.</div>
		  </div>
	</div>
</div>

-->








<!-- 구매취소 -->
<div class="mail_wrap">
	<div class="inner">
		<div class="logo"><a href=""><img src="/skin/demo/img/logo.png" /></a></div>
		<div class="con">
			<span class="b_title">주문하신 상품의구매취소가 완료되었습니다.</span>
			<div class="s_title">
            <strong>{아이디}</strong>님, 이용해 주셔서 진심으로 감사합니다.취소에 대한 상세한 내역을 보시려면 여기를 눌러주세요. 
			</div>
			<div style="padding:10px;">
			<div class="mail_sub_title">취소정보</div>
			 <table class="tbl02">
				<colgroup>
					<col width="20%">
					<col width="">
					<col width="10%">
					<col width="">
				</colgroup>
				<thead>
				<tr>
					<th>주문번호</th>
					<th>상품명</th>
					<th>수량</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td >주문번호</td>
					<td>
						대분류명<br>
						<span >상품설명</span>
						<br>
						<span>옵션명</span>
						</td>
						<td>수량</td>
					</tr>
					<!--{/}-->
					</tbody>
				</table>
				<div style="border:solid 1px #DCDCDC; padding:20px; font-size:12px;line-height:22px; margin:20px 0px;; ">
				구매 취소/환불안내 <br>
				취소/환불내역 확인 : 마이페이지  구매내역 또는 마이페이지  취소·환불·교환내역 메뉴에서 확인 부탁드립니다.<br>
				취소/환불방법 및 소요기간 : 결제수단에 따라 3~5일이내 결제수단 취소 또는 환불계좌 입금 처리됩니다.<br>
				단, 결제수단 별로 환불방법과 소요기간 상이하니 상세정보는 고객센터  자주 묻는 질문을 통해 확인 부탁드립니다.

				</div>
				
				<a href="#"  class="site_link_btn">사이트 바로가기</a>
				</div>

		</div>
			<div class="footer" >
				<div class="txt">
				본 메일은 발신전용입니다.<br>
				상품의 배송/반품/교환/취소/환불 문의는 판매자에게 문의하시면 더 빠르고 정확한 처리가 가능합니다.<br>
				메일 수신을 원하지 않으시면, 회원정보 수정에서 메일 수신거부를 선택해 주십시오<br>
				이용 관련 제안사항이나 불편 신고는 <a style=";" href="#" target="_blank">고객센터</a>를 이용해 주세요.
				</div>
				<div class="link" >
				<a href="#" target="_blank">이용약관</a>  l  <a href="#" target="_blank">청약철회 등에 관한 사항</a>  l  <a href="#" target="_blank">개인정보 취급방침</a>
				</div>
				<div class="address">
				상호 : {상호명} | 사업자등록번호 : {사업자등록번호} | 통신판매업신고 : {통신판매업신고}<br>
				주소 : {사업장소재지} | 대표자 : {대표자명} <br />개인정보보호책임자 : {개인정보보호책임자}({개인정보보호책임자이메일}) | 고객센터 : {대표전화}
				</div>

				<div class="copy"> Copyright ⓒ <strong>{상점명}.</strong> All Rights Reserved.</div>
		  </div>
	</div>
</div>
