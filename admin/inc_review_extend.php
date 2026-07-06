<?php /* 진행자별 마감 연장 — 모달 + JS. 목록 페이지 tail 직전에 1회 include. 2026-06-23 */ ?>
<style>
  .mc-ext-wrap{margin-top:8px;display:flex;flex-wrap:wrap;align-items:center;gap:6px}
  .mc-ext-btn{border:1px solid #03c75a !important;color:#02b350 !important;background:#fff !important;font-weight:700 !important}
  .mc-ext-btn:hover{background:#f1fcf5 !important}
  .mc-ext-chip{display:inline-block;background:#eef9f1;color:#02b350;border:1px solid #c7ecd4;font-size:11px;font-weight:800;padding:2px 8px;border-radius:999px;cursor:pointer}
  #mcExtOv{position:fixed;inset:0;background:rgba(15,20,30,.45);display:none;align-items:center;justify-content:center;padding:18px;z-index:9999}
  #mcExtOv.on{display:flex}
  #mcExtOv .box{background:#fff;border-radius:18px;width:100%;max-width:480px;max-height:92vh;overflow:auto;box-shadow:0 18px 50px rgba(0,0,0,.25);font-family:'Pretendard','Apple SD Gothic Neo',sans-serif;color:#191f28;text-align:left}
  #mcExtOv h3{margin:0;font-size:17px;font-weight:800;padding:20px 22px 4px}
  #mcExtOv .who{padding:0 22px 14px;color:#6b7684;font-size:13px;border-bottom:1px solid #eaedf1}
  #mcExtOv .who b{color:#191f28}
  #mcExtOv .mbody{padding:18px 22px}
  #mcExtOv .field{margin-bottom:16px}
  #mcExtOv .field label{display:block;font-size:13px;font-weight:700;margin-bottom:7px}
  #mcExtOv .req{color:#f04452;margin-left:3px}
  #mcExtOv .cur{background:#f7f8fa;border:1px solid #eaedf1;border-radius:10px;padding:10px 12px;font-weight:700;font-size:14px;color:#333}
  #mcExtOv input[type="datetime-local"],#mcExtOv textarea{width:100%;border:1px solid #d7dbe0;border-radius:10px;padding:11px 12px;font-family:inherit;font-size:14px;color:#191f28;box-sizing:border-box}
  #mcExtOv input[type="datetime-local"]:focus,#mcExtOv textarea:focus{outline:none;border-color:#03c75a;box-shadow:0 0 0 3px rgba(3,199,90,.12)}
  #mcExtOv textarea{min-height:78px;resize:vertical}
  #mcExtOv .hint{font-size:11px;color:#6b7684;margin-top:6px}
  #mcExtOv .err{color:#f04452;font-size:12px;margin-top:6px;display:none}
  #mcExtOv .hist{margin-top:8px;border-top:1px dashed #eaedf1;padding-top:14px}
  #mcExtOv .hist h4{font-size:12px;color:#6b7684;font-weight:800;margin:0 0 10px}
  #mcExtOv .hist .hr{display:flex;gap:10px;padding:9px 0;border-bottom:1px solid #f4f6f8;font-size:12px}
  #mcExtOv .hist .hr:last-child{border-bottom:none}
  #mcExtOv .hist .dot{flex:0 0 auto;width:8px;height:8px;border-radius:50%;background:#03c75a;margin-top:5px}
  #mcExtOv .hist .ln b{color:#191f28}
  #mcExtOv .hist .meta{color:#6b7684;margin-top:2px}
  #mcExtOv .hist .empty{color:#b0b8c1;font-size:12px;padding:4px 0}
  #mcExtOv .mfoot{display:flex;gap:10px;padding:14px 22px 22px}
  #mcExtOv .mfoot button{flex:1;border:0;cursor:pointer;font-family:inherit;font-weight:700;border-radius:9px;padding:12px 18px;font-size:14px}
  #mcExtOv .b-save{background:#03c75a;color:#fff}
  #mcExtOv .b-save:hover{background:#02b350}
  #mcExtOv .b-save:disabled{opacity:.5;cursor:default}
  #mcExtOv .b-cancel{background:#f2f4f6;color:#4e5968}
  #mcExtToast{position:fixed;left:50%;bottom:30px;transform:translateX(-50%) translateY(20px);background:#191f28;color:#fff;padding:13px 20px;border-radius:12px;font-size:13px;font-weight:600;opacity:0;pointer-events:none;transition:.25s;z-index:10000}
  #mcExtToast.on{opacity:1;transform:translateX(-50%) translateY(0)}
</style>

<div id="mcExtOv">
  <div class="box" role="dialog" aria-modal="true">
    <h3>리뷰 마감 연장</h3>
    <div class="who"><b id="mcExtNick"></b><br><span id="mcExtCamp"></span></div>
    <div class="mbody">
      <div class="field"><label>현재 마감일</label><div class="cur" id="mcExtCur">—</div></div>
      <div class="field">
        <label>새 마감일 <span class="req">*</span></label>
        <input type="datetime-local" id="mcExtNew">
        <div class="hint">날짜와 시각을 직접 선택하세요. (예: 2026-07-05 23:59)</div>
        <div class="err" id="mcExtErrNew">새 마감일을 선택해 주세요.</div>
      </div>
      <div class="field">
        <label>연장 사유 <span class="req">*</span></label>
        <textarea id="mcExtReason" maxlength="500" placeholder="예) 제품 배송이 3일 지연되어 작성 기간 부족 — 진행자 요청"></textarea>
        <div class="err" id="mcExtErrReason">연장 사유를 입력해 주세요.</div>
      </div>
      <div class="hist"><h4>📜 연장 이력</h4><div id="mcExtHist"><div class="empty">불러오는 중…</div></div></div>
    </div>
    <div class="mfoot">
      <button type="button" class="b-cancel" onclick="mcExtClose()">취소</button>
      <button type="button" class="b-save" id="mcExtSave" onclick="mcExtSave()">연장 저장</button>
    </div>
  </div>
</div>
<div id="mcExtToast"></div>

<script type="text/javascript">
var MC_EXT_RV = 0, MC_EXT_CUR = '';
function mcExtEsc(s){ return (s==null?'':String(s)).replace(/[&<>"]/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c];}); }
function mcExtToast(m){ var t=document.getElementById('mcExtToast'); t.textContent=m; t.classList.add('on'); setTimeout(function(){t.classList.remove('on');},2200); }
function mcExtOpen(rvId){
  var btn = document.querySelector('.mc-ext-btn[data-rv="'+rvId+'"]');
  if(!btn) return;
  MC_EXT_RV = rvId;
  var edate = btn.getAttribute('data-edate') || '';           // 'YYYY-MM-DD HH:MM'
  if(edate.indexOf('0000') === 0) edate = '';                 // MySQL 제로데이트 → 미설정 처리
  MC_EXT_CUR = edate;
  document.getElementById('mcExtNick').textContent = btn.getAttribute('data-nick') || '진행자';
  document.getElementById('mcExtCamp').textContent = btn.getAttribute('data-camp') || '';
  document.getElementById('mcExtCur').textContent  = edate ? edate.replace(/-/g,'.') : '미설정';
  document.getElementById('mcExtNew').value        = edate ? edate.replace(' ','T').substring(0,16) : '';
  document.getElementById('mcExtReason').value     = '';
  document.getElementById('mcExtErrNew').style.display='none';
  document.getElementById('mcExtErrReason').style.display='none';
  document.getElementById('mcExtHist').innerHTML   = '<div class="empty">불러오는 중…</div>';
  document.getElementById('mcExtOv').classList.add('on');
  // 이력 로드
  fetch('review_extend.php?mode=hist&rv_id='+rvId, {credentials:'same-origin'})
    .then(function(r){return r.json();})
    .then(function(d){
      var h=document.getElementById('mcExtHist');
      if(!d || d.result!=='ok' || !d.list || !d.list.length){ h.innerHTML='<div class="empty">아직 연장 이력이 없습니다.</div>'; return; }
      h.innerHTML = d.list.map(function(x){
        return '<div class="hr"><div class="dot"></div><div class="ln"><b>'+mcExtEsc(x.old)+' → '+mcExtEsc(x.neo)+'</b><div>'+mcExtEsc(x.reason)+'</div><div class="meta">'+mcExtEsc(x.admin)+' · '+mcExtEsc(x.at)+'</div></div></div>';
      }).join('');
    })
    .catch(function(){ document.getElementById('mcExtHist').innerHTML='<div class="empty">이력을 불러오지 못했습니다.</div>'; });
}
function mcExtClose(){ document.getElementById('mcExtOv').classList.remove('on'); }
function mcExtSave(){
  var nv = document.getElementById('mcExtNew').value;
  var rs = document.getElementById('mcExtReason').value.replace(/^\s+|\s+$/g,'');
  var bad=false;
  document.getElementById('mcExtErrNew').style.display    = nv ? 'none' : (bad=true,'block');
  document.getElementById('mcExtErrReason').style.display = rs ? 'none' : (bad=true,'block');
  if(bad) return;
  if(MC_EXT_CUR){                                             // '연장'인데 더 이른 날짜면 확인
    var curISO = MC_EXT_CUR.replace(' ','T').substring(0,16);
    if(nv < curISO && !confirm('현재 마감일('+MC_EXT_CUR+')보다 이른 날짜입니다.\n그래도 저장할까요?')) return;
  }
  var btn=document.getElementById('mcExtSave'); btn.disabled=true; btn.textContent='저장 중…';
  var body = 'mode=save&rv_id='+encodeURIComponent(MC_EXT_RV)+'&new_edate='+encodeURIComponent(nv)+'&reason='+encodeURIComponent(rs);
  fetch('review_extend.php', {method:'POST', credentials:'same-origin', headers:{'Content-Type':'application/x-www-form-urlencoded;charset=UTF-8'}, body:body})
    .then(function(r){return r.json();})
    .then(function(d){
      btn.disabled=false; btn.textContent='연장 저장';
      if(d && d.result==='ok'){
        mcExtClose(); mcExtToast('✅ 마감이 연장되고 이력이 기록되었습니다');
        setTimeout(function(){ location.reload(); }, 800);
      } else {
        alert((d && d.message) ? d.message : '저장에 실패했습니다.');
      }
    })
    .catch(function(){ btn.disabled=false; btn.textContent='연장 저장'; alert('저장 중 오류가 발생했습니다.'); });
}
document.getElementById('mcExtOv').addEventListener('click', function(e){ if(e.target.id==='mcExtOv') mcExtClose(); });
</script>
