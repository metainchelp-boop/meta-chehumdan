/* 리뷰목록 공통: 긴 텍스트(신청자 한마디/필수정보/리뷰내용) 모달 팝업 (2026-06-12, 범용화)
   inc_review_search.php에서 로드. 좁은 textarea 대신 버튼 → 큰 창에서 전체 확인.
   대상: <td class="mc-msg-cell"> 안의 textarea 전부를 읽어 모달 섹션으로 표시(name→제목 매핑).
   원리: 원본 textarea는 폼 전송 위해 DOM 유지·CSS로 숨김, 값은 JS가 textContent로 출력(이스케이프 안전). */
$(function(){

	var LABELS = {
		"rv_msg"   : "📝 신청자 한마디",
		"rv_memo"  : "📋 신청필수 정보",
		"rv_review": "✍️ 리뷰 내용"
	};

	// 모달 1개를 body에 생성(내용은 클릭 시 동적 구성)
	var ov = document.createElement('div');
	ov.className = 'mc-modal-overlay';
	ov.style.display = 'none';
	ov.innerHTML = '<div class="mc-modal"><button type="button" class="mc-modal-x" aria-label="닫기">&times;</button><div class="mc-modal-content"></div></div>';
	document.body.appendChild(ov);

	function closeModal(){ ov.style.display = 'none'; }
	ov.addEventListener('click', function(e){ if(e.target === ov) closeModal(); });
	ov.querySelector('.mc-modal-x').addEventListener('click', closeModal);
	document.addEventListener('keydown', function(e){ if(e.key === 'Escape') closeModal(); });

	function openCell(cell){
		var box = ov.querySelector('.mc-modal-content');
		box.innerHTML = '';
		var tas = cell.querySelectorAll('textarea');
		for(var i=0; i<tas.length; i++){
			var ta = tas[i];
			var sec  = document.createElement('div'); sec.className  = 'mc-modal-sec';
			var head = document.createElement('div'); head.className = 'mc-modal-h';    head.textContent = LABELS[ta.name] || ta.name;
			var body = document.createElement('div'); body.className = 'mc-modal-body'; body.textContent = (ta.value || '').trim() || '(작성 내용 없음)';
			sec.appendChild(head); sec.appendChild(body); box.appendChild(sec);
		}
		ov.style.display = 'flex';
	}

	var cells = document.querySelectorAll('.mc-msg-cell');
	for(var i=0; i<cells.length; i++){
		(function(cell){
			var btn = cell.querySelector('.mc-msg-btn');
			if(btn) btn.addEventListener('click', function(){ openCell(cell); });
		})(cells[i]);
	}
});
