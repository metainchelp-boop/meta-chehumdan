<?php /* 관리자 플로팅 상담 콘솔 — admin 전 페이지 우하단(사용가이드 위). 2단(목록+대화)+회원정보+내부메모+알림(소리/브라우저/탭)+미응대경과. chat_list.php API 재활용. 2026-06-24 */ ?>
<style>
.mac-launch{position:fixed;right:24px;bottom:84px;z-index:99990;display:flex;align-items:center;gap:8px;background:#03c75a;color:#fff;border:0;cursor:pointer;font-family:'Pretendard','Apple SD Gothic Neo',sans-serif;font-weight:800;font-size:14px;padding:11px 18px;border-radius:30px;box-shadow:0 8px 22px rgba(3,199,90,.35)}
.mac-launch .b{background:#fff;color:#e0392f;font-size:11px;font-weight:900;border-radius:999px;padding:1px 7px;display:none}
.mac-launch .b.on{display:inline-block}
.mac-panel{position:fixed;right:24px;bottom:24px;width:820px;max-width:calc(100vw - 32px);height:608px;max-height:calc(100vh - 48px);background:#fff;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,.32);display:none;flex-direction:column;overflow:hidden;z-index:99991;font-family:'Pretendard','Apple SD Gothic Neo',sans-serif;color:#191f28}
.mac-panel.open{display:flex}
.mac-head{background:#03c75a;color:#fff;padding:13px 18px;display:flex;align-items:center;gap:10px;flex:0 0 auto}
.mac-head .t{font-weight:800;font-size:15px}
.mac-head .stat{margin-left:auto;display:flex;gap:8px;align-items:center}
.mac-head .stat a{color:#fff;font-size:12px;font-weight:700;text-decoration:none;opacity:.92}
.mac-head .x{background:transparent;border:0;color:#fff;font-size:22px;cursor:pointer;line-height:1}
.mac-main{flex:1;display:flex;min-height:0}
.mac-list{width:288px;flex:0 0 auto;border-right:1px solid #eaedf1;display:flex;flex-direction:column;min-height:0}
.mac-filt{padding:10px 12px;border-bottom:1px solid #eaedf1;display:flex;gap:6px;flex-wrap:wrap;align-items:center}
.mac-chip{border:1px solid #e3e8ee;background:#fff;color:#4e5968;font-family:inherit;font-weight:700;font-size:12px;padding:5px 10px;border-radius:999px;cursor:pointer;display:inline-flex;gap:4px;align-items:center}
.mac-chip.on{background:#03c75a;border-color:#03c75a;color:#fff}
.mac-chip .c{background:rgba(240,68,82,.9);color:#fff;border-radius:999px;padding:0 5px;font-size:10px}
.mac-chip.on .c{background:rgba(255,255,255,.3)}
.mac-filt select{border:1px solid #e3e8ee;border-radius:7px;padding:5px 7px;font-family:inherit;font-size:11.5px;color:#333}
.mac-convos{flex:1;overflow-y:auto;min-height:0}
.mac-cv{display:flex;gap:9px;padding:11px 12px;border-bottom:1px solid #f4f6f8;cursor:pointer}
.mac-cv:hover{background:#fafdff}.mac-cv.sel{background:#f1fcf5}.mac-cv.warn{background:#fff7f8}
.mac-cv .av{width:34px;height:34px;border-radius:50%;background:#eef1f4;flex:0 0 auto;display:flex;align-items:center;justify-content:center;font-size:15px}
.mac-cv .mn{flex:1;min-width:0}
.mac-cv .nm{font-weight:700;font-size:12.5px}
.mac-cv .cate{font-size:10px;font-weight:800;color:#7a4ddb;background:#f1ecfd;border-radius:5px;padding:1px 5px;margin-left:4px}
.mac-cv .cp{font-size:10.5px;color:#2b6fe0;background:#eef3ff;border-radius:5px;padding:1px 5px;display:inline-block;margin:2px 0}
.mac-cv .ls{font-size:11.5px;color:#8b95a1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.mac-cv .rt{display:flex;flex-direction:column;align-items:flex-end;gap:3px;flex:0 0 auto}
.mac-cv .ur{background:#f04452;color:#fff;font-size:10px;font-weight:800;border-radius:999px;min-width:16px;height:16px;display:flex;align-items:center;justify-content:center;padding:0 4px}
.mac-cv .wait{font-size:9.5px;font-weight:800;color:#e0392f}
.mac-detail{flex:1;display:flex;flex-direction:column;min-width:0}
.mac-dh{padding:11px 14px;border-bottom:1px solid #eaedf1;display:flex;align-items:center;gap:7px;flex-wrap:wrap}
.mac-dh .dn{font-weight:800;font-size:14px}
.mac-dh .dc{font-size:11px;color:#2b6fe0;background:#eef3ff;border-radius:6px;padding:2px 7px}
.mac-dh .dcat{font-size:11px;color:#7a4ddb;background:#f1ecfd;border-radius:6px;padding:2px 7px;font-weight:700}
.mac-dh .dm{font-size:11px;color:#8b95a1;margin-left:4px}.mac-dh .dm b{color:#191f28}
.mac-dh .sp{margin-left:auto;display:flex;gap:6px}
.mac-info{display:none;padding:12px 14px;background:#fbfcfe;border-bottom:1px solid #eaedf1;font-size:12px}
.mac-info.on{display:block}
.mac-info .grid{display:grid;grid-template-columns:1fr 1fr;gap:6px 14px;margin-bottom:10px}
.mac-info .grid .k{color:#8b95a1}.mac-info .grid .v{font-weight:700;color:#191f28}
.mac-info .recent{font-size:11px;color:#6b7684;line-height:1.7}
.mac-info textarea{width:100%;border:1px solid #dbe1e8;border-radius:8px;padding:8px;font-family:inherit;font-size:12px;min-height:48px;resize:vertical;margin-top:4px}
.mac-info .memorow{display:flex;gap:6px;align-items:flex-start;margin-top:8px}
.mac-info .memosave{background:#4e5968;border:0;color:#fff;font-family:inherit;font-weight:700;font-size:11px;padding:8px 11px;border-radius:8px;cursor:pointer;flex:0 0 auto}
.mac-ibtn{font-size:11px;font-weight:700;border:1px solid #dbe1e8;background:#fff;color:#4e5968;border-radius:8px;padding:6px 9px;cursor:pointer}
.mac-rsv{background:#eef1f4;border:0;color:#4e5968;font-family:inherit;font-weight:700;font-size:11.5px;padding:6px 10px;border-radius:8px;cursor:pointer}
.mac-rsv.done{background:#e7f7ee;color:#02b350}
.mac-body{flex:1;overflow-y:auto;padding:14px;background:#f7f9fb;min-height:0}
.mac-day{text-align:center;margin:10px 0 7px}.mac-day span{background:#e9edf1;color:#788293;font-size:10.5px;font-weight:700;padding:2px 10px;border-radius:999px}
.mac-foot{border-top:1px solid #eaedf1;padding:10px;display:flex;gap:7px;flex:0 0 auto}
.mac-foot textarea{flex:1;border:1px solid #dbe1e8;border-radius:9px;padding:10px 12px;font-family:inherit;font-size:13.5px;outline:none;resize:none;line-height:1.45;min-height:40px;max-height:120px;overflow-y:auto}
.mac-foot textarea:focus{border-color:#03c75a}
.mac-foot .sd{background:#03c75a;border:0;color:#fff;font-family:inherit;font-weight:800;padding:0 18px;border-radius:9px;cursor:pointer}
.mac-empty{flex:1;display:flex;align-items:center;justify-content:center;color:#b5bdc6;font-size:13px}
.mac-m{display:flex;margin-bottom:8px}.mac-m.me{justify-content:flex-end}
.mac-bub{max-width:76%;padding:8px 11px;border-radius:12px;font-size:13px;line-height:1.5;white-space:pre-wrap;word-break:break-word}
.mac-m.them .mac-bub{background:#fff;border:1px solid #eaedf1;color:#191f28;border-top-left-radius:3px}
.mac-m.me .mac-bub{background:#03c75a;color:#fff;border-top-right-radius:3px}
.mac-m .who{font-size:10.5px;color:#8b95a1;font-weight:700;margin:0 0 2px 2px}
.mac-tm{font-size:10px;color:#aab2bd;align-self:flex-end;white-space:nowrap;margin:0 5px}
.mac-cv .cvtm{font-size:10px;color:#9aa4af;white-space:nowrap;margin-bottom:3px}
.mac-cv .rn,.mac-dh .rn{color:#d6336c;font-weight:700;font-size:.92em}
@media (max-width:860px){ .mac-panel{width:calc(100vw - 24px);height:calc(100vh - 36px);bottom:12px;right:12px} .mac-list{width:200px} }
</style>
<button class="mac-launch" id="mac-launch" type="button">💬 상담 <span class="b" id="mac-badge">0</span></button>
<div class="mac-panel" id="mac-panel">
  <div class="mac-head"><span class="t">💬 고객센터 상담</span><span class="stat"><a href="chat_stats.php" target="_blank">📊 통계</a><button class="x" id="mac-x" type="button">×</button></span></div>
  <div class="mac-main">
    <div class="mac-list">
      <div class="mac-filt">
        <button class="mac-chip on" data-f="all" type="button">전체</button>
        <button class="mac-chip" data-f="unanswered" type="button">미답변 <span class="c" id="mac-un">0</span></button>
        <select id="mac-md"><option value="">담당자 전체</option></select>
      </div>
      <div class="mac-convos" id="mac-convos"></div>
    </div>
    <div class="mac-detail" id="mac-detail"><div class="mac-empty">왼쪽에서 상담을 선택하세요</div></div>
  </div>
</div>
<script>
(function(){
  if(window.macLoaded) return; window.macLoaded=true;
  var API='chat_list.php', fil='all', sel=null, last=0, lastDate='', timer=null, prevRooms=null, audioCtx=null, blinkTimer=null, origTitle=document.title;
  var STEP={'1':'신청','8':'1차후보','2':'선정','3':'검수요청','4':'등록완료','5':'미선정','6':'선정후취소','7':'수정요청'};
  function $(id){return document.getElementById(id);}
  function esc(s){return (s==null?'':String(s)).replace(/[&<>"]/g,function(c){return{'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c];});}
  function g(p){return fetch(API+'?'+p,{credentials:'same-origin'}).then(function(r){return r.json();});}
  function po(p){return fetch(API,{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/x-www-form-urlencoded;charset=UTF-8'},body:p}).then(function(r){return r.json();});}
  var panel=$('mac-panel'), launch=$('mac-launch'), badge=$('mac-badge');

  // 알림(소리/브라우저) 무장 — 최초 클릭 시
  document.addEventListener('click', function armOnce(){ try{ if(!audioCtx) audioCtx=new (window.AudioContext||window.webkitAudioContext)(); if(audioCtx&&audioCtx.state==='suspended')audioCtx.resume(); }catch(e){} if(window.Notification && Notification.permission==='default'){ try{Notification.requestPermission();}catch(e){} } document.removeEventListener('click',armOnce); }, true);
  function beep(){ if(!audioCtx)return; try{ [880,1245].forEach(function(f,i){ var o=audioCtx.createOscillator(),gn=audioCtx.createGain(); o.connect(gn);gn.connect(audioCtx.destination); o.type='sine';o.frequency.value=f; var t0=audioCtx.currentTime+i*0.18; gn.gain.setValueAtTime(.0001,t0); gn.gain.exponentialRampToValueAtTime(.18,t0+.02); gn.gain.exponentialRampToValueAtTime(.0001,t0+.16); o.start(t0);o.stop(t0+.17); }); }catch(e){} }
  function notify(n){ if(window.Notification && Notification.permission==='granted'){ try{ new Notification('🔔 새 고객 문의', {body:'미응대 '+n+'건이 있습니다. 상담함에서 확인하세요.'}); }catch(e){} } }
  function startBlink(n){ if(blinkTimer)return; var on=false; blinkTimer=setInterval(function(){ document.title=on?origTitle:('🔔 새 문의 '+n+'건'); on=!on; },900); }
  function stopBlink(){ if(blinkTimer){clearInterval(blinkTimer);blinkTimer=null;} document.title=origTitle; }

  launch.addEventListener('click',open); $('mac-x').addEventListener('click',close);
  [].forEach.call(document.querySelectorAll('.mac-chip'),function(b){ b.addEventListener('click',function(){ fil=b.getAttribute('data-f'); document.querySelectorAll('.mac-chip').forEach(function(x){x.classList.toggle('on',x.getAttribute('data-f')===fil);}); load(); }); });
  $('mac-md').addEventListener('change',load);
  function open(){ panel.classList.add('open'); stopBlink(); loadMds(); load(); startPoll(); }
  function close(){ panel.classList.remove('open'); stopPoll(); }
  function loadMds(){ g('mode=mds').then(function(d){ if(d.result!=='ok')return; var s=$('mac-md'); var cur=s.value; s.innerHTML='<option value="">담당자 전체</option>'+d.mds.map(function(m){return '<option value="'+esc(m)+'">'+esc(m)+'</option>';}).join(''); s.value=cur; }); }
  function fmtWait(m){ m=m||0; if(m<60)return m+'분'; if(m<720)return Math.floor(m/60)+'시간'; return Math.max(1,Math.round(m/1440))+'일'; }
  function load(){
    var md=$('mac-md').value;
    g('mode=threads&filter='+fil+'&md='+encodeURIComponent(md)).then(function(d){
      if(d.result!=='ok')return; var el=$('mac-convos');
      el.innerHTML = d.list.length ? d.list.map(function(c){
        var late=(c.unread>0 && c.wait>=10);
        return '<div class="mac-cv'+(c.ct_id===sel?' sel':'')+(late?' warn':'')+'" data-id="'+c.ct_id+'"><div class="av">🙂</div><div class="mn">'+
          '<div class="nm">'+esc(c.nick)+(c.name&&c.name!==c.nick?' <span class="rn">'+esc(c.name)+'</span>':'')+(c.cate?'<span class="cate">'+esc(c.cate)+'</span>':'')+'</div>'+
          '<span class="cp">'+esc((c.cp||'').length>15?c.cp.slice(0,15)+'…':c.cp)+'</span> <span style="font-size:10.5px;color:#8b95a1">· '+esc(c.md)+'</span>'+
          '<div class="ls">'+(c.status=='1'?'<span style="color:#02b350;font-weight:800">[해결] </span>':'')+esc(c.last||'(새 문의)')+'</div></div>'+
          '<div class="rt"><div class="cvtm">'+esc(c.tm||'')+'</div>'+(c.unread>0?'<div class="ur">'+c.unread+'</div>':'')+(late?'<div class="wait">⏱'+fmtWait(c.wait)+'</div>':'')+'</div></div>';
      }).join('') : '<div style="padding:24px;text-align:center;color:#b5bdc6;font-size:12.5px">해당 문의가 없습니다</div>';
      [].forEach.call(el.querySelectorAll('.mac-cv'),function(x){ x.addEventListener('click',function(){ openConvo(parseInt(x.getAttribute('data-id'),10)); }); });
    });
    badgePoll();
  }
  function openConvo(id){
    sel=id; last=0; lastDate='';
    g('mode=msgs&ct_id='+id+'&after=0').then(function(d){
      if(d.result!=='ok')return; var h=d.head;
      $('mac-detail').innerHTML=
        '<div class="mac-dh"><span class="dn">'+esc(h.nick)+'</span>'+(h.name&&h.name!==h.nick?'<span class="rn">'+esc(h.name)+'</span>':'')+(h.cate?'<span class="dcat">'+esc(h.cate)+'</span>':'')+'<span class="dc">'+esc(h.cp)+'</span><span class="dm">담당 <b>'+esc(h.md)+'</b></span>'+
        '<span class="sp"><button class="mac-ibtn" id="mac-itog" type="button">👤 회원정보</button><button class="mac-rsv'+(h.status=='1'?' done':'')+'" id="mac-rsv" type="button">'+(h.status=='1'?'✓ 해결됨':'해결완료')+'</button></span></div>'+
        '<div class="mac-info" id="mac-info"><div id="mac-mfill" class="recent">불러오는 중…</div>'+
        '<div class="memorow"><textarea id="mac-memo" placeholder="내부 메모(회원에게 안 보임)">'+esc(h.memo||'')+'</textarea><button class="memosave" id="mac-memosave" type="button">저장</button></div></div>'+
        '<div class="mac-body" id="mac-msgs"></div>'+
        '<div class="mac-foot"><textarea id="mac-input" rows="1" placeholder="답변 입력 (Enter 전송 · Shift+Enter 줄바꿈)"></textarea><button class="sd" id="mac-send" type="button">전송</button></div>';
      renderMsgs(d.msgs,true);
      $('mac-send').addEventListener('click',reply);
      $('mac-input').addEventListener('keydown',function(e){if(e.key==='Enter'&&!e.shiftKey){e.preventDefault();reply();}});
      $('mac-input').addEventListener('input',function(){this.style.height='auto';this.style.height=Math.min(this.scrollHeight,120)+'px';});
      $('mac-rsv').addEventListener('click',resolve);
      $('mac-itog').addEventListener('click',toggleInfo);
      $('mac-memosave').addEventListener('click',saveMemo);
      $('mac-input').focus(); load();
    });
  }
  function toggleInfo(){ var p=$('mac-info'); var on=p.classList.toggle('on'); if(on && !p.getAttribute('data-loaded')){ p.setAttribute('data-loaded','1'); g('mode=member&ct_id='+sel).then(function(d){ if(d.result!=='ok')return; var m=d.m;
    var rec=(m.recent||[]).map(function(r){return esc(r.cp)+' <span style="color:#aab">('+(STEP[r.step]||r.step)+')</span>';}).join('<br>')||'-';
    $('mac-mfill').innerHTML='<div class="grid">'+
      '<div><span class="k">닉네임</span> <span class="v">'+esc(m.nick||m.name||m.id)+'</span></div>'+
      '<div><span class="k">연락처</span> <span class="v">'+esc(m.hp||'-')+'</span></div>'+
      '<div><span class="k">이메일</span> <span class="v">'+esc(m.email||'-')+'</span></div>'+
      '<div><span class="k">포인트</span> <span class="v">'+Number(m.point||0).toLocaleString()+'P</span></div>'+
      '<div><span class="k">가입일</span> <span class="v">'+esc(m.join||'-')+'</span></div>'+
      '<div><span class="k">캠페인</span> <span class="v">진행 '+m.cp_ing+' · 완료 '+m.cp_done+' (총 '+m.cp_total+')</span></div>'+
      '</div><div style="color:#8b95a1;margin-bottom:3px">최근 참여 캠페인</div><div class="recent">'+rec+'</div>';
  }); } }
  function saveMemo(){ po('mode=memo&ct_id='+sel+'&memo='+encodeURIComponent($('mac-memo').value)).then(function(d){ var b=$('mac-memosave'); if(d.result==='ok'){ b.textContent='저장됨'; setTimeout(function(){b.textContent='저장';},1200);} else alert('저장 실패'); }); }
  function dayDiv(dd){ if(dd && dd!==lastDate){ lastDate=dd; var b=$('mac-msgs'); if(b) b.insertAdjacentHTML('beforeend','<div class="mac-day"><span>'+esc(dd)+'</span></div>'); } }
  function renderMsgs(msgs,reset){ var b=$('mac-msgs'); if(!b)return; if(reset){b.innerHTML='';lastDate='';}
    msgs.forEach(function(m){ last=Math.max(last,m.id); dayDiv((m.tm||'').slice(0,5));
      var mtm=(m.tm||'').slice(6);
      b.insertAdjacentHTML('beforeend', m.me ? '<div class="mac-m me"><span class="mac-tm">'+esc(mtm)+'</span><div class="mac-bub">'+esc(m.msg)+'</div></div>' : '<div class="mac-m them"><div><div class="who">'+esc(m.who)+'</div><div class="mac-bub">'+esc(m.msg)+'</div></div><span class="mac-tm">'+esc(mtm)+'</span></div>'); });
    if(msgs.length)b.scrollTop=b.scrollHeight; }
  function reply(){ var i=$('mac-input'); var t=i.value.replace(/^\s+|\s+$/g,''); if(!t||!sel)return; i.value=''; i.style.height='';
    po('mode=reply&ct_id='+sel+'&msg='+encodeURIComponent(t)).then(function(d){ if(d.result==='ok'){ g('mode=msgs&ct_id='+sel+'&after='+last).then(function(d2){ if(d2.result==='ok')renderMsgs(d2.msgs,false); }); load(); } else alert(d.message||'전송 실패'); }); }
  function resolve(){ if(!sel)return; var done=$('mac-rsv').classList.contains('done'); po('mode=resolve&ct_id='+sel+'&status='+(done?'0':'1')).then(function(d){ if(d.result==='ok')openConvo(sel); }); }
  function startPoll(){ stopPoll(); timer=setInterval(function(){ if(sel){ g('mode=msgs&ct_id='+sel+'&after='+last).then(function(d){ if(d.result==='ok'&&d.msgs.length)renderMsgs(d.msgs,false); }); } load(); },5000); }
  function stopPoll(){ if(timer){clearInterval(timer);timer=null;} }
  function badgePoll(){ g('mode=badge').then(function(d){ if(d.result!=='ok')return; var n=d.rooms;
    $('mac-un').textContent=n; if(n>0){badge.textContent=n;badge.classList.add('on');}else{badge.classList.remove('on');}
    if(prevRooms!==null && n>prevRooms && !panel.classList.contains('open')){ beep(); notify(n); startBlink(n); }
    if(n===0) stopBlink();
    prevRooms=n;
  }).catch(function(){}); }
  badgePoll(); setInterval(function(){ if(!panel.classList.contains('open')) badgePoll(); },15000);
})();
</script>
