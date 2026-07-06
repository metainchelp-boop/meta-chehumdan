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

.mail_wrap{width:100%; background-color:#f8f8f9; margin:0px; padding:0px;}
.mail_wrap .inner{width:800px; margin:0px auto; background-color:#FFF;}
.mail_wrap .inner .logo{padding:20px;}
.mail_wrap .inner .logo img{height:45px;}
.mail_wrap .inner .con{padding:20px; text-align:left; min-height:200px; display:block;font-size: 16px; font-family: '나눔고딕','NanumGothic','맑은고딕','Malgun Gothic','돋움','Dotum','Helvetica,Apple SD Gothic',' Neo,Sans-serif'; color: #484242;}

.mail_wrap .inner .con b{color:#ff3300}
.mail_wrap .inner .footer{background-color:#e5e5e5;  color: rgb(105, 105, 105); line-height:17px; font-family: 나눔고딕,NanumGothic,맑은고딕,Malgun Gothic,돋움,Dotum,Helvetica,Apple SD Gothic Neo,Sans-serif; font-size: 12px;}
.mail_wrap .inner .footer .customer{color:#696969; font-weight: bold; text-decoration: underline;}
.mail_wrap .inner .footer .txt{ padding: 26px 21px 13px;}
.mail_wrap .inner .footer .address{padding: 10px 21px 13px;}
.mail_wrap .inner .footer .link{padding: 10px 21px 13px;}
.mail_wrap .inner .footer .link a{padding: 0px 10px;  text-decoration: none;}
.mail_wrap .inner .footer .link a:nth-child(1){padding:0px;}
.mail_wrap .inner .footer .copy{line-height: 17px; padding-right: 21px; padding-bottom: 57px; padding-left: 21px; font-family: Helvetica; font-size: 12px}
</style>
<div class="mail_wrap">
	<div class="inner">
		<div class="logo"><a href=""><img src="/skin/demo/img/logo.png" /></a></div>
		<div class="con">
			<span style="display:block;color: rgb(68, 68, 68); letter-spacing: -3px; font-size: 32px;font-family: '맑은고딕','Malgun Gothic'; font-weight:bold; padding:10px;">주문하신 상품이 발송 되셨습니다.</span>
			<div style="padding:0px 10px; display:block; line-height: 20px; margin-top: 30px; font-size:12px; font-family: '나눔고딕','NanumGothic','맑은고딕','Malgun Gothic','돋움','Dotum','Helvetica,Apple SD Gothic',' Neo,Sans-serif';">
            <strong>{아이디}</strong>님, 저희 쇼핑몰을 이용하여 주셔서 감사합니다. <br> {아이디} 님께서 주문하신 상품이 아래와 같이 발송되었습니다. <br> 고객님께 빠르고 정확하게 제품이 전달될 수 있도록 최선을 다하겠습니다.
			</div>
			<div style="padding:10px;">
			<div style="margin: 40px 0px 10px; color: rgb(68, 68, 68); font-size: 15px; font-weight: bold;">상품배송정보</div>
			 <table style="width:100%; border-top-color: rgb(181, 181, 181); border-top-width: 1px; border-top-style: solid; border-collapse: collapse;">
				<colgroup>
					<col width="20%">
					<col width="">
					<col width="10%">
					<col width="">
				</colgroup>
				<thead>
				<tr>
					<th style="height: 43px; font-size: 13px; border-bottom-color: rgb(218, 218, 218); border-bottom-width: 1px; border-bottom-style: solid; background-color: rgb(247, 247, 247);">주문번호</th>
					<th style="height: 43px; font-size: 13px; border-bottom-color: rgb(218, 218, 218); border-bottom-width: 1px; border-bottom-style: solid; background-color: rgb(247, 247, 247);">상품명</th>
					<th style="height: 43px; font-size: 13px; border-bottom-color: rgb(218, 218, 218); border-bottom-width: 1px; border-bottom-style: solid; background-color: rgb(247, 247, 247);">수량</th>
					<th style="height: 43px; font-size: 13px; border-bottom-color: rgb(218, 218, 218); border-bottom-width: 1px; border-bottom-style: solid; background-color: rgb(247, 247, 247);">배송정보</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td style="height: 43px; text-align: center; padding-top: 15px; padding-bottom: 15px; padding-left: 20px; font-size: 13px; border-bottom-color: rgb(218, 218, 218); border-bottom-width: 1px; border-bottom-style: solid;" >주문번호</td>
					<td style="height: 43px; text-align: left; padding-top: 15px; padding-bottom: 15px; padding-left: 20px; font-size: 13px; border-bottom-color: rgb(218, 218, 218); border-bottom-width: 1px; border-bottom-style: solid;">
						대분류명<br>
						<span style="color: rgb(153, 153, 153); font-size: 12px;">상품설명</span>
						<br>
						<span style="color: rgb(153, 153, 153); font-size: 12px;">옵션명</span>
						</td>
						<td style="height: 43px; text-align: center; padding-top: 15px; padding-bottom: 15px; font-size: 13px; border-bottom-color: rgb(218, 218, 218); border-bottom-width: 1px; border-bottom-style: solid;">수량</td>
						<td style="height: 43px; text-align: center; padding-top: 15px; padding-bottom: 15px; font-size: 13px; border-bottom-color: rgb(218, 218, 218); border-bottom-width: 1px; border-bottom-style: solid;">한진택배<br><a href="#" target="_blank">123456789</a></td>
					</tr>
					<!--{/}-->
					</tbody>
				</table>

				<div style="margin: 40px 0px 10px; color: rgb(68, 68, 68); font-size: 15px; font-weight: bold;">배송지정보</div>
				<table style="width:100%; border-top-color: rgb(181, 181, 181); border-top-width: 1px; border-top-style: solid; border-collapse: collapse;">
					<tbody>
					<tr>
						<td style="height: 43px; color: rgb(136, 136, 136); padding-right: 20px; padding-left: 20px; font-size: 13px; border-bottom-color: rgb(229, 229, 229); border-bottom-width: 1px; border-bottom-style: solid; background-color: rgb(247, 247, 247);">받는사람</td>
						<td style="width: 80%; height: 43px; color: rgb(51, 51, 51); padding-left: 20px; font-size: 13px; border-bottom-color: rgb(229, 229, 229); border-bottom-width: 1px; border-bottom-style: solid;">임준아</td>
					</tr>
					<tr>
						<td style="height: 43px; color: rgb(136, 136, 136); padding-right: 20px; padding-left: 20px; font-size: 13px; border-bottom-color: rgb(229, 229, 229); border-bottom-width: 1px; border-bottom-style: solid; background-color: rgb(247, 247, 247);">주소</td>
						<td style="width: 80%; height: 43px; color: rgb(51, 51, 51); padding-left: 20px; font-size: 13px; border-bottom-color: rgb(229, 229, 229); border-bottom-width: 1px; border-bottom-style: solid;">인천시 서구경서동 청라더샵 레이크파크 451동 3104호 452-52</td>
					</tr>
					<tr>
						<td style="height: 43px; color: rgb(136, 136, 136); padding-right: 20px; padding-left: 20px; font-size: 13px; border-bottom-color: rgb(229, 229, 229); border-bottom-width: 1px; border-bottom-style: solid; background-color: rgb(247, 247, 247);">전화번호</td>
						<td style="width: 80%; height: 43px; color: rgb(51, 51, 51); padding-left: 20px; font-size: 13px; border-bottom-color: rgb(229, 229, 229); border-bottom-width: 1px; border-bottom-style: solid;">010-2774-4531</td>
					</tr>
					<tr>
						<td style="height: 43px; color: rgb(136, 136, 136); padding-right: 20px; padding-left: 20px; font-size: 13px; border-bottom-color: rgb(229, 229, 229); border-bottom-width: 1px; border-bottom-style: solid; background-color: rgb(247, 247, 247);">휴대폰번호</td>
						<td style="width: 80%; height: 43px; color: rgb(51, 51, 51); padding-left: 20px; font-size: 13px; border-bottom-color: rgb(229, 229, 229); border-bottom-width: 1px; border-bottom-style: solid;">010-2774-4531</td>
					</tr>
					<tr>
						<td style="height: 43px; color: rgb(136, 136, 136); padding-right: 20px; padding-left: 20px; font-size: 13px; border-bottom-color: rgb(229, 229, 229); border-bottom-width: 1px; border-bottom-style: solid; background-color: rgb(247, 247, 247);">배송메시지</td>
						<td style="width: 80%; height: 43px; color: rgb(51, 51, 51); padding-left: 20px; font-size: 13px; border-bottom-color: rgb(229, 229, 229); border-bottom-width: 1px; border-bottom-style: solid;">부재시 연락바람</td>
					</tr>
					</tbody>
				</table>
				<a href="#">사이트 바로가기</a>
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



