/* 리뷰목록 공통: 입점업체/캠페인 드롭다운을 '타이핑 검색형'으로 개선 (2026-06-12)
   inc_review_search.php에서 로드. 외부 라이브러리 없음(순수 JS).
   원리: 기존 <select>는 숨기고(폼 전송값 유지), 그 자리에 검색 입력칸+필터 목록을 띄움.
   업체 선택 시 select에 change 이벤트를 발생시켜 기존 '캠페인 자동필터' AJAX 연동 보존. */
$(function(){

	function enhance(sel, ph){
		if(!sel || sel._mcEnhanced) return;
		sel._mcEnhanced = true;
		sel.style.display = 'none';

		var wrap  = document.createElement('div');  wrap.className  = 'mc-ss-wrap';
		var input = document.createElement('input'); input.type = 'text'; input.className = 'mc-ss-input'; input.setAttribute('autocomplete','off');
		if(ph) input.placeholder = ph;
		var list  = document.createElement('div');   list.className = 'mc-ss-list'; list.style.display = 'none';
		wrap.appendChild(input); wrap.appendChild(list);
		sel.parentNode.insertBefore(wrap, sel.nextSibling);

		function curText(){ var o = sel.options[sel.selectedIndex]; return o ? o.text : ''; }
		function syncInput(){ input.value = curText(); }
		syncInput();

		function buildList(filter){
			list.innerHTML = '';
			filter = (filter || '').toLowerCase().trim();
			var shown = 0;
			for(var i=0; i<sel.options.length; i++){
				var t = sel.options[i].text;
				if(filter && t.toLowerCase().indexOf(filter) === -1) continue;
				var item = document.createElement('div');
				item.className = 'mc-ss-item';
				item.textContent = t;
				item.title = t;
				(function(idx){
					item.addEventListener('mousedown', function(e){
						e.preventDefault();
						sel.selectedIndex = idx;
						syncInput();
						list.style.display = 'none';
						sel.dispatchEvent(new Event('change', {bubbles:true}));
					});
				})(i);
				list.appendChild(item);
				if(++shown >= 200){
					var more = document.createElement('div'); more.className = 'mc-ss-more';
					more.textContent = '… 결과가 많습니다. 더 입력해 좁혀주세요';
					list.appendChild(more);
					break;
				}
			}
			if(shown === 0){
				var none = document.createElement('div'); none.className = 'mc-ss-none';
				none.textContent = '검색 결과 없음';
				list.appendChild(none);
			}
		}

		input.addEventListener('focus', function(){ input.value = ''; buildList(''); list.style.display = 'block'; });
		input.addEventListener('input', function(){ buildList(input.value); list.style.display = 'block'; });
		input.addEventListener('blur',  function(){ setTimeout(function(){ list.style.display = 'none'; syncInput(); }, 150); });

		// 캠페인 목록이 AJAX로 갈아끼워질 때(업체 변경 시) 입력칸 동기화
		try { new MutationObserver(function(){ syncInput(); }).observe(sel, {childList:true}); } catch(e){}
	}

	enhance(document.getElementById('rv_supply_no'), '업체명 검색'); // 입점업체(리뷰목록·보드)
	enhance(document.getElementById('rv_cp_id'),     '캠페인명 검색'); // 캠페인(리뷰목록·보드)
	enhance(document.getElementById('sel_supply'),   '입점업체명 검색'); // 캠페인 등록 입점업체(campaign_form, 2026-06-17 분리)
	enhance(document.getElementById('sel_adv'),      '광고주명 검색');   // 캠페인 등록 광고주(campaign_form, 2026-06-17 신설)
	enhance(document.getElementById('cp_md_no'),     '관리자명 검색');   // 캠페인 등록 관리자(campaign_form)
});
