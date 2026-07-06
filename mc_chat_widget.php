<?php /* 고객센터 채팅 위젯 — 프론트 전 페이지 공통(데모/모바일 양 스킨 tail.php include). 카카오 대체. 유형선택·자동응답·디자인 고도화 2026-06-24 */ ?>
<style>
#mcw,#mcw *{box-sizing:border-box;font-family:'Pretendard','Apple SD Gothic Neo',sans-serif}
#mcw-launch{position:fixed;right:24px;bottom:24px;z-index:99998;display:flex;align-items:center;gap:8px;background:#03c75a;color:#fff;border:0;cursor:pointer;font-weight:800;font-size:15px;padding:13px 19px;border-radius:30px;box-shadow:0 8px 24px rgba(3,199,90,.35);transition:transform .12s}
#mcw-launch:hover{transform:translateY(-2px)}
#mcw-launch .b{background:#fff;color:#02b350;font-size:12px;font-weight:900;border-radius:999px;padding:1px 7px;display:none}
#mcw-launch .b.on{display:inline-block}
#mcw-panel{position:fixed;right:24px;bottom:24px;width:372px;max-width:calc(100vw - 32px);height:564px;max-height:calc(100vh - 48px);background:#fff;border-radius:18px;box-shadow:0 18px 50px rgba(0,0,0,.28);display:flex;flex-direction:column;overflow:hidden;z-index:99999;opacity:0;transform:translateY(18px) scale(.98);pointer-events:none;transition:opacity .2s ease,transform .2s ease}
#mcw-panel.open{opacity:1;transform:none;pointer-events:auto}
#mcw-head{background:linear-gradient(135deg,#03c75a,#02b350);color:#fff;padding:15px 18px;position:relative;flex:0 0 auto;display:flex;align-items:center;gap:11px}
#mcw-head .ava{width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,.22);display:flex;align-items:center;justify-content:center;font-size:19px;flex:0 0 auto}
#mcw-head .ti .t{font-weight:800;font-size:15.5px}
#mcw-head .ti .s{font-size:11.5px;opacity:.92;margin-top:2px}
#mcw-head .x{position:absolute;right:13px;top:13px;background:transparent;border:0;color:#fff;font-size:22px;cursor:pointer;line-height:1}
#mcw-head .bk{background:transparent;border:0;color:#fff;font-size:13px;cursor:pointer;opacity:.92;padding:0;display:none;align-items:center;gap:3px}
#mcw-body{flex:1;overflow-y:auto;padding:15px;background:#f7f9fb}
#mcw-foot{flex:0 0 auto;border-top:1px solid #eaedf1;padding:9px;display:flex;gap:7px;align-items:center}
#mcw-foot textarea{flex:1;border:1px solid #dbe1e8;border-radius:18px;padding:10px 15px;font-size:14px;font-family:inherit;outline:none;resize:none;line-height:1.45;min-height:40px;max-height:100px;overflow-y:auto}
#mcw-foot textarea:focus{border-color:#03c75a}
#mcw-foot .snd{background:#03c75a;border:0;color:#fff;width:40px;height:40px;border-radius:50%;cursor:pointer;font-size:16px;flex:0 0 auto}
#mcw-foot.off{display:none}
#mcw .greet{background:#fff;border:1px solid #eaedf1;border-radius:14px;padding:15px;margin-bottom:12px}
#mcw .greet h3{margin:0 0 5px;font-size:15px;font-weight:800;color:#191f28}
#mcw .greet p{margin:0;font-size:13px;color:#6b7684;line-height:1.6}
#mcw .notice{background:#fff7e6;border:1px solid #ffe0a6;color:#8a6d00;border-radius:12px;padding:11px 13px;font-size:12.5px;line-height:1.6;margin-bottom:12px;display:flex;gap:8px}
#mcw .notice .ic{flex:0 0 auto}
#mcw .lab{font-size:12px;font-weight:800;color:#6b7684;margin:12px 4px 7px}
#mcw .cpc{display:flex;align-items:center;gap:10px;background:#fff;border:1px solid #eaedf1;border-radius:12px;padding:10px 12px;margin-bottom:7px;cursor:pointer;transition:.12s}
#mcw .cpc:hover{border-color:#03c75a;background:#f1fcf5}
#mcw .cpc .ic{width:38px;height:38px;border-radius:9px;background:#eef1f4;flex:0 0 auto;display:flex;align-items:center;justify-content:center;font-size:18px}
#mcw .cpc .nm{font-size:13px;font-weight:700;color:#191f28;line-height:1.3}
#mcw .cpc .mt{font-size:11px;color:#8b95a1;margin-top:2px}
#mcw .cpc .st{margin-left:auto;font-size:10.5px;font-weight:800;padding:2px 8px;border-radius:999px;flex:0 0 auto}
#mcw .st.ing{background:#e7f1ff;color:#2b6fe0}#mcw .st.done{background:#eef1f4;color:#8b95a1}
#mcw .gen{width:100%;border:1px dashed #c9d2db;background:#fff;color:#4e5968;font-weight:700;font-size:13px;padding:11px;border-radius:12px;cursor:pointer;margin-top:4px}
#mcw .gen:hover{border-color:#03c75a;color:#02b350}
#mcw .tchip{display:inline-block;background:#fff;border:1px solid #dbe1e8;color:#333;font-weight:700;font-size:13px;padding:10px 14px;border-radius:11px;cursor:pointer;margin:0 7px 7px 0;transition:.12s}
#mcw .tchip:hover{border-color:#03c75a;background:#f1fcf5;color:#02b350}
#mcw .sys{text-align:center;margin:9px 0;font-size:11.5px;color:#9aa4af}#mcw .sys b{color:#02b350}
#mcw .day{text-align:center;margin:12px 0 8px}#mcw .day span{background:#e9edf1;color:#788293;font-size:11px;font-weight:700;padding:3px 11px;border-radius:999px}
#mcw .newdiv{text-align:center;margin:10px 0}#mcw .newdiv span{background:#fdecef;color:#e0392f;font-size:11px;font-weight:800;padding:2px 10px;border-radius:999px}
#mcw .m{display:flex;margin-bottom:9px;align-items:flex-end;gap:7px}#mcw .m.me{justify-content:flex-end}
#mcw .m .av{width:26px;height:26px;border-radius:50%;background:#03c75a;color:#fff;display:flex;align-items:center;justify-content:center;font-size:13px;flex:0 0 auto}
#mcw .bub{max-width:74%;padding:9px 12px;border-radius:14px;font-size:13.5px;line-height:1.5;white-space:pre-wrap;word-break:break-word}
#mcw .m.them .bub{background:#fff;border:1px solid #eaedf1;border-top-left-radius:4px;color:#191f28}
#mcw .m.me .bub{background:#03c75a;color:#fff;border-top-right-radius:4px}
#mcw .m .who{font-size:11px;color:#8b95a1;font-weight:700;margin:0 0 3px 2px}
#mcw .m .tm{font-size:10px;color:#b0b8c1;align-self:flex-end;margin:0 2px 2px}
#mcw .typing{display:flex;align-items:center;gap:7px;margin-bottom:9px}
#mcw .typing .dots{background:#fff;border:1px solid #eaedf1;border-radius:14px;border-top-left-radius:4px;padding:11px 13px;display:flex;gap:4px}
#mcw .typing .dots i{width:6px;height:6px;border-radius:50%;background:#c0c8d0;animation:mcwb 1s infinite}
#mcw .typing .dots i:nth-child(2){animation-delay:.15s}#mcw .typing .dots i:nth-child(3){animation-delay:.3s}
@keyframes mcwb{0%,60%,100%{opacity:.3;transform:translateY(0)}30%{opacity:1;transform:translateY(-3px)}}
#mcw .note{text-align:center;color:#8b95a1;font-size:13px;padding:30px 16px;line-height:1.7}
#mcw .note .em{font-size:30px;display:block;margin-bottom:8px}
#mcw .note a{color:#02b350;font-weight:700;text-decoration:none}
#mcw .resume{background:#eafaf0;border:1px solid #c3eed5;border-radius:12px;padding:12px 14px;margin-bottom:12px;cursor:pointer;color:#02b350;font-weight:800;font-size:13.5px;display:flex;align-items:center;gap:8px}
#mcw .resume:hover{background:#dff5e8}
#mcw .resume .rs{color:#6b7684;font-weight:600;font-size:12px;margin-top:2px}
#mcw .resume .go{margin-left:auto;color:#02b350;font-size:16px}
@media (max-width:768px){
  #mcw-launch{bottom:78px;right:14px;padding:11px 16px;font-size:14px}
  #mcw-panel{bottom:0;right:0;left:0;width:100vw;max-width:100vw;height:90vh;max-height:90vh;border-radius:18px 18px 0 0}
}
</style>

<div id="mcw">
<button id="mcw-launch" type="button">💬 상담하기 <span class="b" id="mcw-badge">0</span></button>
<div id="mcw-panel">
  <div id="mcw-head">
    <div class="ava" id="mcw-hava">🎧</div>
    <div class="ti">
      <div class="t" id="mcw-title">메타체험단 고객센터</div>
      <div class="s" id="mcw-sub">보통 몇 분 내 답변드려요 · 평일 10:00~18:00</div>
    </div>
    <button class="bk" type="button" id="mcw-bk">‹ 뒤로</button>
    <button class="x" type="button" id="mcw-x">×</button>
  </div>
  <div id="mcw-body"></div>
  <div id="mcw-foot" class="off"><textarea id="mcw-input" rows="1" placeholder="메시지를 입력하세요 (Enter 전송·Shift+Enter 줄바꿈)" maxlength="1000"></textarea><button class="snd" id="mcw-snd" type="button">➤</button></div>
</div>
</div><!-- /#mcw -->

<script>
(function(){
  if(window.mcwLoaded) return; window.mcwLoaded=true;
  var API='/mc_chat.php', LOGIN='/login.php';
  var $=function(id){return document.getElementById(id);};
  var panel=$('mcw-panel'), launch=$('mcw-launch'), body=$('mcw-body'), foot=$('mcw-foot'),
      input=$('mcw-input'), badge=$('mcw-badge'), bk=$('mcw-bk'), titleEl=$('mcw-title'), subEl=$('mcw-sub'), hava=$('mcw-hava');
  var ctId=null, lastId=0, lastDate='', pollTimer=null, curMd='고객센터', pick=null, typingOn=false;
  function esc(s){return (s==null?'':String(s)).replace(/[&<>"]/g,function(c){return{'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c];});}
  function api(p,opt){opt=opt||{}; if(opt.post){return fetch(API,{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/x-www-form-urlencoded;charset=UTF-8'},body:opt.post}).then(function(r){return r.json();});} return fetch(API+'?'+p,{credentials:'same-origin'}).then(function(r){return r.json();});}
  function fmtDay(d){ if(!d)return''; var p=d.split('-'); return p[0]+'.'+p[1]+'.'+p[2]; }

  launch.addEventListener('click',openPanel);
  $('mcw-x').addEventListener('click',closePanel);
  bk.addEventListener('click',goHome);
  $('mcw-snd').addEventListener('click',send);
  input.addEventListener('keydown',function(e){if(e.key==='Enter'&&!e.shiftKey){e.preventDefault();send();}});
  input.addEventListener('input',function(){input.style.height='auto';input.style.height=Math.min(input.scrollHeight,100)+'px';});

  function openPanel(){ panel.classList.add('open'); launch.style.display='none'; goHome(); }
  function closePanel(){ panel.classList.remove('open'); launch.style.display='flex'; stopPoll(); ctId=null; checkBadge(); }
  function setHome(home){ bk.style.display=home?'none':'flex'; foot.classList.toggle('off',home); if(home){titleEl.textContent='메타체험단 고객센터';subEl.textContent='보통 몇 분 내 답변드려요 · 평일 10:00~18:00';} }

  function goHome(){
    stopPoll(); ctId=null; pick=null; setHome(true);
    body.innerHTML='<div class="note"><span class="em">⏳</span>불러오는 중…</div>';
    api('mode=campaigns').then(function(d){
      if(d.result==='login'){ body.innerHTML='<div class="note"><span class="em">🔒</span>상담은 로그인 후 이용하실 수 있어요.<br><br><a href="'+LOGIN+'">로그인 하러 가기 ›</a></div>'; return; }
      var h='';
      if(d.last){ h+='<div class="resume" data-rid="'+d.last.ct_id+'"><div><div>💬 진행 중인 상담 이어가기</div><div class="rs">'+esc(d.last.subject)+(d.last.cate?' · '+esc(d.last.cate):'')+'</div></div><span class="go">›</span></div>'; }
      if(d.status && d.status!=='open'){ h+='<div class="notice"><span class="ic">🕒</span><span>'+esc(d.notice)+'</span></div>'; }
      h+='<div class="greet"><h3>무엇을 도와드릴까요? 👋</h3><p>문의하실 캠페인을 선택하시면 담당자가 도와드려요.</p></div>';
      if(d.ing && d.ing.length){ h+='<div class="lab">📍 참여중인 캠페인</div>'; d.ing.forEach(function(c){h+=card(c,'ing');}); }
      if(d.done && d.done.length){ h+='<div class="lab">✅ 참여 완료한 캠페인</div>'; d.done.forEach(function(c){h+=card(c,'done');}); }
      h+='<div class="lab">그 외</div><button class="gen" data-cp="" data-md="고객센터" data-sj="일반 문의">＋ 캠페인과 무관한 일반 문의</button>';
      body.innerHTML=h; body.scrollTop=0;
      [].forEach.call(body.querySelectorAll('[data-cp]'),function(el){ el.addEventListener('click',function(){ chooseType(el.getAttribute('data-cp'), el.getAttribute('data-sj'), el.getAttribute('data-md')); }); });
      var rc=body.querySelector('.resume'); if(rc && d.last) rc.addEventListener('click',function(){ resumeThread(d.last); });
    }).catch(function(){ body.innerHTML='<div class="note"><span class="em">⚠️</span>불러오지 못했습니다. 잠시 후 다시 시도해 주세요.</div>'; });
  }
  function card(c,k){ var ic=k==='ing'?'🟢':'✅';
    return '<div class="cpc" data-cp="'+esc(c.cp_id)+'" data-sj="'+esc(c.subject)+'" data-md="'+esc(c.md)+'"><div class="ic">'+ic+'</div>'+
      '<div><div class="nm">'+esc(c.subject)+'</div><div class="mt">담당자 '+esc(c.md)+'</div></div>'+
      '<span class="st '+k+'">'+(k==='ing'?'참여중':'완료')+'</span></div>'; }

  // 문의 유형 선택 단계
  function chooseType(cp,sj,md){
    pick={cp:cp,sj:sj,md:md}; setHome(false);
    titleEl.textContent=(sj&&sj.length>20)?sj.slice(0,20)+'…':(sj||'문의'); subEl.textContent='문의 유형을 선택하세요';
    var types=['배송 문의','포인트','리뷰 등록','캠페인 문의','기타'];
    var h='<div class="greet"><h3>어떤 내용인가요?</h3><p>유형을 선택하시면 더 빠르게 도와드릴 수 있어요.</p></div><div style="padding:0 2px">';
    types.forEach(function(t){ h+='<span class="tchip" data-t="'+esc(t)+'">'+esc(t)+'</span>'; });
    h+='</div>';
    body.innerHTML=h; body.scrollTop=0;
    [].forEach.call(body.querySelectorAll('.tchip'),function(el){ el.addEventListener('click',function(){ start(el.getAttribute('data-t')); }); });
  }

  function start(cate){
    curMd=pick.md||'고객센터'; setHome(false);
    titleEl.textContent=(pick.sj&&pick.sj.length>20)?pick.sj.slice(0,20)+'…':(pick.sj||'문의');
    subEl.textContent='메타체험단 고객센터 · '+cate;
    body.innerHTML='<div class="note"><span class="em">⏳</span>연결 중…</div>';
    api('mode=start',{post:'mode=start&cp_id='+encodeURIComponent(pick.cp)+'&cate='+encodeURIComponent(cate)}).then(function(d){
      if(d.result!=='ok'){ body.innerHTML='<div class="note">'+esc(d.message||'오류가 발생했습니다.')+'</div>'; return; }
      ctId=d.ct_id; lastId=0; lastDate='';
      body.innerHTML='<div class="sys">'+(pick.cp?('「'+esc(pick.sj).slice(0,16)+'…」'):'일반 문의')+' · '+esc(cate)+' 상담 시작 · <b>메타체험단 고객센터</b></div>';
      them(d.notice || '안녕하세요! 무엇을 도와드릴까요?');   // 시간대별 자동응답/인사
      poll(false); startPoll(); input.focus();
    });
  }

  // 진행 중인 상담 이어가기(재진입 시 유형선택 없이 바로 이전 대화)
  function resumeThread(L){ stopPoll(); curMd=L.md||'고객센터'; setHome(false);
    titleEl.textContent=(L.subject&&L.subject.length>20)?L.subject.slice(0,20)+'…':(L.subject||'문의');
    subEl.textContent='메타체험단 고객센터'+(L.cate?' · '+L.cate:'');
    ctId=L.ct_id; lastId=0; lastDate='';
    body.innerHTML='<div class="sys">이전 상담을 이어서 표시합니다</div>';
    poll(false); startPoll(); input.focus();
  }
  function dayDivider(d){ if(d && d!==lastDate){ lastDate=d; body.insertAdjacentHTML('beforeend','<div class="day"><span>'+fmtDay(d)+'</span></div>'); } }
  function them(txt,d){ dayDivider(d); body.insertAdjacentHTML('beforeend','<div class="m them"><div class="av">🎧</div><div><div class="who">메타체험단 고객센터</div><div class="bub">'+esc(txt)+'</div></div></div>'); body.scrollTop=body.scrollHeight; }
  function meMsg(txt,d){ dayDivider(d); body.insertAdjacentHTML('beforeend','<div class="m me"><div class="bub">'+esc(txt)+'</div></div>'); body.scrollTop=body.scrollHeight; }
  function showTyping(){ if(typingOn)return; typingOn=true; body.insertAdjacentHTML('beforeend','<div class="typing" id="mcw-typing"><div class="av" style="width:26px;height:26px;border-radius:50%;background:#03c75a;color:#fff;display:flex;align-items:center;justify-content:center;font-size:13px">🎧</div><div class="dots"><i></i><i></i><i></i></div></div>'); body.scrollTop=body.scrollHeight; }
  function hideTyping(){ typingOn=false; var t=$('mcw-typing'); if(t)t.remove(); }

  function poll(viaUser){ if(!ctId)return; api('mode=poll&ct_id='+ctId+'&after='+lastId).then(function(d){
      if(d.result!=='ok'||!d.msgs)return;
      if(d.msgs.length){ var hadThem=false;
        d.msgs.forEach(function(m){ lastId=Math.max(lastId,m.id); if(m.me){meMsg(m.msg,m.d);} else {hideTyping(); them(m.msg,m.d); hadThem=true;} });
      }
    }); }
  function startPoll(){ stopPoll(); pollTimer=setInterval(function(){poll(false);},4000); }
  function stopPoll(){ if(pollTimer){clearInterval(pollTimer);pollTimer=null;} }
  function send(){ var t=input.value.replace(/^\s+|\s+$/g,''); if(!t||!ctId)return; input.value=''; input.style.height='';
    meMsg(t); showTyping();
    api('mode=send',{post:'mode=send&ct_id='+ctId+'&msg='+encodeURIComponent(t)}).then(function(){ setTimeout(function(){poll(false);},600); });
    setTimeout(hideTyping,6000);
  }

  function checkBadge(){ api('mode=unread').then(function(d){ if(d.result==='ok'&&d.unread>0){ badge.textContent=d.unread; badge.classList.add('on'); } else { badge.classList.remove('on'); } }).catch(function(){}); }
  checkBadge(); setInterval(function(){ if(!panel.classList.contains('open')) checkBadge(); },25000);
})();
</script>
