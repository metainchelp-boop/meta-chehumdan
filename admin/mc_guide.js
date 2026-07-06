/* 관리자 페이지 공통 '사용 가이드' 시스템 (2026-06-13)
   head.php에서 전 admin 페이지에 로드. 현재 페이지(파일명)에 맞는 가이드가 있으면
   우측 하단에 "📖 사용 가이드" 버튼 표시 → 클릭 시 모달로 설명서 열람.
   ★새 페이지 추가 시: 아래 GUIDES 에 '파일명': {title, html} 한 줄만 추가하면 됨. */
(function(){
  var GUIDES = {
    "review_board.php": { title:"캠페인 진행 보드", html:
      "<p><b>한 캠페인의 전 과정</b>(신청→1차후보→선정→검수요청→등록완료)을 한 화면에서 보고 바로 처리하는 화면입니다.</p>"+
      "<ul>"+
      "<li><b>캠페인 선택</b> — 상단 칸에 캠페인명을 입력해 검색·선택하면 현황이 표시됩니다.</li>"+
      "<li><b>퍼널 숫자</b> — 각 단계에 <u>지금 머물러 있는 인원</u>입니다. (전 단계 합 = 총 신청) 숫자 박스를 누르면 그 단계 신청자가 아래에 보입니다.</li>"+
      "<li><b>신청자 카드의 버튼</b> — 단계별로 바로 처리합니다. 신청=[1차 선정]/[미선정], 1차후보=[2차 확정]/[제외], 검수요청=[검수 승인].</li>"+
      "<li><b>이 단계 관리하기</b> — 기존 목록 페이지(선정·검수 등 더 많은 기능)로 이동합니다.</li>"+
      "<li><b>🔗 광고주 진행현황 링크</b> — 상단 버튼을 누르면 <u>광고주에게 보낼 링크가 복사</u>됩니다. 카톡·문자·메일로 전달하세요.</li>"+
      "</ul>"+
      "<p><b>📤 광고주 진행현황 링크란?</b> 광고주가 로그인 없이 열어 <b>이번 회차 진행 상황을 실시간</b>으로 보는 페이지입니다.</p>"+
      "<ul>"+
      "<li>탭으로 <b>신청자 · 선정자 · 리뷰 등록현황 · 보고서</b>를 봅니다. 약 2분마다 자동 갱신돼요.</li>"+
      "<li>광고주가 신청자 줄의 <b>[선정 제외]</b>를 누르면 <u>즉시 미선정 처리</u>됩니다(되돌리려면 담당자가 처리). 그 외에는 모두 읽기 전용입니다.</li>"+
      "<li>개인정보 보호를 위해 신청자 <b>이름은 마스킹</b>(김○○)되고 <b>연락처·주소는 보이지 않습니다</b>. 닉네임·성별·연령·지역·채널만 노출돼요.</li>"+
      "<li>상세 결과 보고서는 <u>회차 마감 후 별도로 발송</u>되며, 이 링크는 <b>마감 전 진행 현황 확인용</b>입니다.</li>"+
      "</ul>"+
      "<p class='mcg-warn'>⚠ <b>[2차 확정]</b>은 회원에게 <b>선정 알림이 발송</b>되고, <b>[검수 승인]</b>은 등록완료 처리(포인트 등)됩니다. 실제로 확정할 때만 누르세요. ([1차 선정]은 회원 알림 없이 후보로만 이동.)<br>※ 광고주 링크는 <b>받은 사람 누구나</b> 열람·선정제외가 되니 광고주에게만 전달하세요.</p>" },

    "review_wait_list.php": { title:"신청목록", html:
      "<p>새로 신청한 리뷰어(아직 미선정) 목록입니다.</p>"+
      "<ul>"+
      "<li><b>입점업체·캠페인 검색</b> — 칸을 클릭해 이름을 입력하면 바로 찾습니다(긴 목록 스크롤 불필요).</li>"+
      "<li><b>회원정보</b> — 이름/전화/신고. <b>신청채널</b>의 [블로그 보러가기]로 블로그 확인, 아래 등급·방문수는 회원 블로그 기준 자동 표시.</li>"+
      "<li><b>신청 정보</b>의 [신청해요] 버튼 — 신청자가 쓴 한마디·필수정보 전체를 팝업으로 봅니다.</li>"+
      "<li><b>1차 선정</b> — 후보로 보냅니다(<u>회원 알림 없음</u>, 2차 확정 때 알림). <b>리뷰어 미선정</b> = 탈락 처리.</li>"+
      "</ul>" },

    "review_pre_list.php": { title:"1차 후보 목록", html:
      "<p>1차로 추려둔 후보입니다. 아직 <u>회원은 선정 사실을 모릅니다</u>. 데이터 비교·광고주 검토 후 확정합니다.</p>"+
      "<ul>"+
      "<li><b>2차 확정</b> — 최종 선정. 이때 <b>회원에게 선정 알림이 발송</b>되고 리뷰 권한이 열립니다.</li>"+
      "<li><b>제외</b> — 후보에서 빼서 신청목록으로 되돌립니다(회원은 모름).</li>"+
      "<li><b>광고주 공유 링크 / 명단 엑셀</b> — 후보 명단을 광고주에게 보내 제외할 사람을 받을 수 있습니다.</li>"+
      "</ul>" },

    "review_post_list.php": { title:"검수요청목록", html:
      "<p>리뷰어가 후기(리뷰 URL)를 제출해 <b>검수를 기다리는</b> 목록입니다.</p>"+
      "<ul>"+
      "<li><b>리뷰 보러가기</b> — 작성된 리뷰 글을 확인합니다. <b>구매평 캡쳐</b>는 썸네일/다운로드로 확인.</li>"+
      "<li>검수 후 <b>등록완료 처리</b>하면 완료 단계로 이동하고 리워드/포인트가 진행됩니다.</li>"+
      "</ul>" },

    "review_post_asign_list.php": { title:"등록완료목록", html:
      "<p>검수까지 끝나 <b>완료된</b> 리뷰 목록입니다.</p>"+
      "<ul>"+
      "<li><b>실시간리뷰 노출/미노출</b> — 사이트 후기 영역 노출 여부를 조절합니다.</li>"+
      "<li><b>유입분석</b> — 그 리뷰로 들어온 유입을 봅니다.</li>"+
      "<li><b>등록완료 취소(포인트 회수)</b> — 잘못 완료한 건을 되돌리고 지급 포인트를 회수합니다(신중히).</li>"+
      "</ul>" },

    "point_bank_list.php": { title:"출금신청목록", html:
      "<p>회원이 포인트를 <b>출금(환급) 신청</b>한 목록입니다.</p>"+
      "<ul>"+
      "<li><b>첨부파일</b> — 주민/신분증·통장사본이 <u>이미지 썸네일</u>로 보입니다(PDF는 문서 아이콘). 클릭하면 원본, [다운로드]는 JPG/PNG로 받아집니다.</li>"+
      "<li><b>입금예정</b> — 검토 후 입금 예정으로 상태 변경(회원에게 안내). <b>출금보류</b> = 보류 처리.</li>"+
      "<li>매주 모아서 처리하시면 됩니다. 계좌·예금주를 꼭 대조하세요.</li>"+
      "</ul>" },

    "member_list.php": { title:"일반회원목록", html:
      "<p>가입한 <b>일반회원(리뷰어)</b> 전체 목록입니다.</p>"+
      "<ul>"+
      "<li>아이디·이름·휴대폰·이메일로 <b>검색</b>해 회원을 찾습니다.</li>"+
      "<li><b>등급·포인트·방문횟수·네이버블로그·연결계정</b>으로 활동/신뢰도를 확인합니다.</li>"+
      "<li><b>정보수정</b>으로 회원 정보를 보고 수정 / 메일·SMS 동의, 승인여부도 확인됩니다.</li>"+
      "<li><b>탈퇴신청</b> 표시가 있으면 탈퇴 요청한 회원입니다.</li>"+
      "</ul>" },

    "supply_list.php": { title:"입점업체목록", html:
      "<p>캠페인을 의뢰하는 <b>입점업체(광고주)</b> 목록입니다.</p>"+
      "<ul><li>업체를 검색·조회하고 <b>등록하기</b>로 새 업체를 추가합니다.</li>"+
      "<li>업체별 담당자·연락처를 관리합니다. 캠페인 등록 시 이 업체를 연결합니다.</li></ul>" },

    "admin_list.php": { title:"부관리자목록", html:
      "<p><b>부관리자(직원) 계정</b> 목록입니다.</p>"+
      "<ul><li>관리자 계정을 추가/수정하고 <b>접근 권한</b>을 부여합니다.</li>"+
      "<li>직원마다 볼 수 있는 메뉴·기능을 제어할 수 있습니다.</li></ul>" },

    "leave_list.php": { title:"탈퇴신청회원목록", html:
      "<p><b>탈퇴를 신청한 회원</b> 목록입니다.</p>"+
      "<ul><li>탈퇴 사유를 확인하고 처리합니다.</li>"+
      "<li>★처리 전 <b>포인트 잔액·진행 중 캠페인</b>이 있는지 확인하세요.</li></ul>" },

    "md_list.php": { title:"캠페인관리자목록", html:
      "<p>캠페인을 담당하는 <b>캠페인 관리자(MD)</b> 목록입니다.</p>"+
      "<ul><li>MD를 등록/수정합니다. 캠페인 등록 시 담당 MD를 지정하면 그 MD 기준으로 분류·집계됩니다.</li></ul>" },

    "point_list.php": { title:"포인트관리", html:
      "<p>회원 <b>포인트 적립·차감 내역</b>을 관리합니다.</p>"+
      "<ul><li>회원별 포인트 지급/회수 기록을 조회합니다.</li>"+
      "<li>리뷰 완료 보상 적립, 출금(환급) 차감 등이 여기에 기록됩니다.</li></ul>" },

    "mail_list.php": { title:"메일발송", html:
      "<p>회원에게 <b>단체 이메일</b>을 보냅니다.</p>"+
      "<ul><li>대상(전체/조건)·제목·내용을 작성해 발송합니다.</li>"+
      "<li class=''>★<b>메일 수신 동의</b> 회원에게만 발송됩니다. 발송 전 대상·내용을 꼭 확인하세요.</li></ul>" },

    "sms_list.php": { title:"문자메시지발송", html:
      "<p>회원에게 <b>단체 문자(SMS/LMS)</b>를 보냅니다.</p>"+
      "<ul><li>대상·내용 작성 후 발송. SMS 수신 동의 회원 대상.</li></ul>"+
      "<p class='mcg-warn'>⚠ 문자는 <b>발송 건당 비용</b>이 발생합니다. 대상 인원수를 꼭 확인하고 보내세요.</p>" },

    "push_list.php": { title:"푸시발송", html:
      "<p>앱 <b>푸시 알림</b>을 보냅니다.</p>"+
      "<ul><li>제목·내용·대상을 정해 발송합니다.</li>"+
      "<li>새 캠페인·이벤트 안내 등에 활용합니다.</li></ul>" },

    "campaign_list.php": { title:"캠페인 목록", html:
      "<p>등록된 <b>캠페인 전체</b>를 관리하는 화면입니다.</p>"+
      "<ul>"+
      "<li><b>등록하기</b>로 새 캠페인 생성, <b>수정</b>으로 편집합니다.</li>"+
      "<li><b>다음회차</b> — 같은 캠페인을 <u>일정만 바꿔 복제</u> 등록(반복 캠페인에 편리).</li>"+
      "<li><b>담당자별 보기</b> — 상단 <b>담당자</b> 드롭다운에서 이름을 고르면 <u>그 즉시</u> 해당 담당자 캠페인만 모여서 보입니다(검색 버튼 불필요).</li>"+
      "<li>승인상태·노출상태·모집/신청·일정·등록인원을 한눈에 보고, <b>결과보고서</b>로 성과를 확인합니다.</li>"+
      "<li>진행 현황을 단계별로 보려면 신청서관리의 <b>📋 캠페인 진행 보드</b>를 쓰세요.</li>"+
      "</ul>" },

    "campaign_form.php": { title:"캠페인 등록 / 수정", html:
      "<p>새 캠페인을 만들거나 기존 캠페인을 수정하는 화면입니다.</p>"+
      "<ul>"+
      "<li><b>입점업체·캠페인 담당자</b> — 칸을 눌러 검색해서 선택합니다.</li>"+
      "<li><b>캠페인 담당자 이름은 필수</b>입니다. 비우면 등록·수정이 안 됩니다. <u>전산 직원 이름과 똑같이</u> 적어야 전산 캘린더에서 담당자별로 보입니다.</li>"+
      "<li><b>일정</b>(신청기간·선정발표·리뷰등록·결과발표)을 모두 채워야 등록됩니다.</li>"+
      "</ul>"+
      "<p><b>📱 메타테크 부업 미션 추가</b> (맨 아래 · 선택)</p>"+
      "<ul>"+
      "<li>체크를 켜면 <u>이 캠페인에 모바일 부업 미션이 함께 등록</u>됩니다(메타테크에 따로 또 등록할 필요 없음).</li>"+
      "<li><b>유형</b>: 정답맞추기·공유미션 = 이용자가 <u>정답을 입력</u>(서버가 자동 확인) / 저장하기·알림받기·상품찜 = 이용자가 <u>스크린샷 제출</u>(담당자 검수 후 지급).</li>"+
      "<li><b>검색키워드 / URL / 정답 / 포인트</b>만 입력하면 됩니다. 진행기간은 캠페인 일정을 자동으로 씁니다.</li>"+
      "<li><b>현재 노출 위치 · 노출 사진</b> — 검색영역(통합/쇼핑/플레이스)+순위(예: 7위)와 검색결과 캡처를 넣으면, 참여자가 <u>헤매지 않고 바로 찾습니다</u>(권장).</li>"+
      "<li>여기서 등록한 미션의 참여 현황은 <b>광고주 보고서</b>에 넣을 수 있습니다(아래 메타테크 안내 참고).</li>"+
      "</ul>" },

    "metatech_list.php": { title:"메타테크 목록", html:
      "<p><b>메타테크</b> = 이용자가 모바일에서 네이버앱으로 스토어/플레이스를 방문·찜·알림·정답확인 하는 <b>부업 미션</b>입니다.</p>"+
      "<ul>"+
      "<li><b>등록하기</b>로 미션을 만들거나, <u>캠페인 등록 화면의 '메타테크 미션 추가'</u>로 캠페인에 묶어서 만들 수 있습니다.</li>"+
      "<li><b>유형</b> — 정답맞추기/공유미션은 정답 입력형(자동 확인), 저장하기/알림받기/상품찜은 <b>스크린샷 검수형</b>입니다.</li>"+
      "<li><b>현재 노출 위치 · 노출 사진</b>을 넣으면 참여자가 검색결과에서 우리를 <u>빨리 찾습니다</u>(헤매는 시간 단축).</li>"+
      "<li>행을 누르면 상세에서 <b>참여자 명단과 검수</b>를 볼 수 있습니다.</li>"+
      "</ul>"+
      "<p class='mcg-warn'>📌 <b>스크린샷 검수형</b>은 이용자가 제출하면 바로 지급되지 않고 <b>검수대기</b> 상태가 됩니다. 상세에서 <b>승인</b>해야 포인트가 지급됩니다. (정답 입력형은 정답이 맞으면 자동 지급)</p>" },

    "metatech_detail.php": { title:"메타테크 상세 / 검수", html:
      "<p>해당 미션의 <b>참여자 명단</b>과 검수 화면입니다.</p>"+
      "<ul>"+
      "<li><b>상태</b> — 검수대기 / 지급완료 / 반려로 표시됩니다.</li>"+
      "<li><b>확인하기</b> — 이용자가 올린 스크린샷(인증샷)을 봅니다.</li>"+
      "<li><b>검수대기</b> 건: <b>[승인(지급)]</b>을 누르면 포인트가 지급되고, <b>[반려]</b>는 지급하지 않고 반려 처리합니다.</li>"+
      "<li><b>지급완료</b> 건: 잘못 지급했으면 <b>[회수하기]</b>로 포인트를 되돌립니다.</li>"+
      "</ul>" },

    "campaign_report_share.php": { title:"광고주 공유 보고서", html:
      "<p>캠페인 결과를 <b>로그인 없이 볼 수 있는 링크</b>로 만들어 광고주에게 보냅니다.</p>"+
      "<ul>"+
      "<li>리뷰어 <b>이름은 마스킹</b>, 연락처·회원정보는 빠집니다.</li>"+
      "<li>데이터가 갱신되면 다시 눌러 <b>같은 링크로 최신화</b>됩니다.</li>"+
      "<li><b>📱 메타테크 참여 현황 포함</b> — 이 캠페인에 연결된 메타테크가 있으면, 보고서에 <u>참여 현황을 포함/미포함</u> 선택할 수 있습니다. <b>참여가 적으면 미포함을 권장</b>합니다.</li>"+
      "</ul>" }
  };
  GUIDES["metatech_form.php"] = GUIDES["metatech_list.php"];
  // 같은 가이드를 쓰는 페이지 별칭
  GUIDES["point_bank_wait_list.php"] = GUIDES["point_bank_list.php"];
  GUIDES["point_bank_stop_list.php"] = GUIDES["point_bank_list.php"];
  GUIDES["point_bank_send_list.php"] = GUIDES["point_bank_list.php"];

  // 가이드 없는 페이지용 자동 요약(제목·표 항목·주요 버튼) — 모든 관리자 페이지에 버튼이 뜨게.
  function buildDefault(){
    function txt(el){ return el? el.textContent.replace(/\s+/g,' ').trim() : ''; }
    // 제목 = 현재 페이지 메뉴 링크 텍스트(가장 정확). 없으면 breadcrumb/heading 폴백.
    var fbase = (location.pathname.split('/').pop()||'').split('?')[0];
    var ml = Array.prototype.slice.call(document.querySelectorAll('a')).filter(function(a){ return ((a.getAttribute('href')||'').split('?')[0])===fbase; });
    // 같은 파일을 상단탭·사이드바가 함께 가리킬 수 있음 → 활성 서브메뉴(인라인 굵게) 우선
    var pick = ml.filter(function(a){ var fw=a.style.fontWeight; return fw==='bold'||(parseInt(fw,10)>=700); })[0] || ml[0];
    var title = pick ? pick.textContent.replace(/NEW/g,'').replace(/\[[^\]]*\]/g,'').replace(/\s+/g,' ').trim() : '';
    if(!title) title = (txt(document.querySelector('.crumbs')).split(/[›>〉]/).pop()||'').trim() || txt(document.querySelector('h1,h2,.ptitle')) || '이 페이지';
    if(!title || title.length>24) title = '이 페이지';
    var uniq = function(a){ return a.filter(function(v,i){ return v && a.indexOf(v)===i; }); };
    var cols = uniq(Array.prototype.map.call(document.querySelectorAll('table.row_tbl tr:first-child th'), txt))
                 .filter(function(x){ return x && x.length<=12 && !/^\s*$/.test(x); }).slice(0,14);
    var btns = uniq(Array.prototype.map.call(document.querySelectorAll('.bottom_btn .btn, .table_btn .btn, .btn-lg, .savebar .btn'), txt))
                 .filter(function(x){ return x && x.length<=22; }).slice(0,8);
    var html = "<p><b>"+title+"</b> 페이지입니다.</p>";
    if(cols.length) html += "<p><b>표 항목:</b> "+cols.join(' · ')+"</p>";
    if(btns.length) html += "<p><b>주요 버튼:</b> "+btns.join(' · ')+"</p>";
    html += "<p class='mcg-warn'>📝 이 페이지의 <b>상세 사용 가이드는 순차 작성 중</b>입니다. 위 내용은 화면 항목 자동 요약이에요.</p>";
    return { title:title, html:html };
  }
  function ready(fn){ if(document.readyState!='loading') fn(); else document.addEventListener('DOMContentLoaded',fn); }
  ready(function(){
    var file = (location.pathname.split('/').pop()||'').split('?')[0];
    var g = GUIDES[file] || buildDefault();

    var st = document.createElement('style');
    st.textContent =
      ".mcg-btn{position:fixed;right:22px;bottom:22px;z-index:9998;background:#0d9488;color:#fff;border:none;border-radius:999px;padding:12px 18px;font-size:14px;font-weight:800;cursor:pointer;box-shadow:0 6px 20px rgba(13,148,136,.35);font-family:'Pretendard',sans-serif}"+
      ".mcg-btn:hover{background:#0b7d72}"+
      ".mcg-ov{position:fixed;inset:0;background:rgba(15,23,42,.5);z-index:9999;display:none;align-items:center;justify-content:center;padding:20px}"+
      ".mcg-modal{background:#fff;border-radius:16px;max-width:560px;width:100%;max-height:84vh;overflow-y:auto;padding:24px 26px 22px;box-shadow:0 20px 60px rgba(0,0,0,.3);font-family:'Pretendard','Apple SD Gothic Neo',sans-serif}"+
      ".mcg-h{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px}"+
      ".mcg-h h3{font-size:18px;font-weight:800;color:#0d9488;margin:0}"+
      ".mcg-h .x{border:none;background:transparent;font-size:26px;color:#94a3b8;cursor:pointer;line-height:1}"+
      ".mcg-body{font-size:14px;line-height:1.7;color:#334155}"+
      ".mcg-body p{margin:0 0 10px}.mcg-body ul{margin:0 0 10px;padding-left:18px}.mcg-body li{margin-bottom:7px}"+
      ".mcg-body b{color:#111}.mcg-body u{text-underline-offset:2px}"+
      ".mcg-body .mcg-warn{background:#fff8ec;border:1px solid #ffe2ab;color:#b5740a;border-radius:10px;padding:11px 13px;font-size:13px;margin-top:6px}"+
      ".mcg-foot{margin-top:14px;font-size:11.5px;color:#94a3b8;text-align:right}";
    document.head.appendChild(st);

    var btn = document.createElement('button');
    btn.className='mcg-btn'; btn.type='button'; btn.textContent='📖 사용 가이드';
    document.body.appendChild(btn);

    var ov = document.createElement('div'); ov.className='mcg-ov';
    ov.innerHTML = "<div class='mcg-modal'><div class='mcg-h'><h3>📖 "+g.title+" 사용 가이드</h3>"+
      "<button class='x' type='button' aria-label='닫기'>&times;</button></div>"+
      "<div class='mcg-body'>"+g.html+"</div>"+
      "<div class='mcg-foot'>이 안내는 기능 이해용입니다 · 메타체험단 관리자</div></div>";
    document.body.appendChild(ov);

    function open(){ ov.style.display='flex'; } function close(){ ov.style.display='none'; }
    btn.addEventListener('click',open);
    ov.addEventListener('click',function(e){ if(e.target===ov) close(); });
    ov.querySelector('.x').addEventListener('click',close);
    document.addEventListener('keydown',function(e){ if(e.key==='Escape') close(); });
  });
})();
