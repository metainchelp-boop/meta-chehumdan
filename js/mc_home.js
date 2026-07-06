/* 메타체험단 홈 - 마우스 커서 따라다니는 효과 2026 */
(function(){
  try{
    if(window.matchMedia && window.matchMedia('(hover:none),(pointer:coarse)').matches) return;
    if(document.querySelector('.mc-cursor-ring')) return;
    var dot=document.createElement('div'); dot.className='mc-cursor-dot';
    var ring=document.createElement('div'); ring.className='mc-cursor-ring';
    document.body.appendChild(dot); document.body.appendChild(ring);
    var mx=window.innerWidth/2, my=window.innerHeight/2, rx=mx, ry=my;
    document.addEventListener('mousemove',function(e){ mx=e.clientX; my=e.clientY; dot.style.left=mx+'px'; dot.style.top=my+'px'; });
    (function loop(){ rx+=(mx-rx)*0.2; ry+=(my-ry)*0.2; ring.style.left=rx+'px'; ring.style.top=ry+'px'; requestAnimationFrame(loop); })();
    var SEL='a,button,.box,.icon,.zzim,input,.menu,.menu_sub';
    document.addEventListener('mouseover',function(e){ if(e.target.closest(SEL)) ring.classList.add('on'); });
    document.addEventListener('mouseout',function(e){ if(e.target.closest(SEL)) ring.classList.remove('on'); });
  }catch(err){}
})();
