<?php
/* 고객센터 상담함 (관리자) — 회원 채팅 응대. 담당자별 필터(공유계정 → 이름 선택)+전체. 2026-06-24 */
include_once "path.php";   // 관리자 인증($member,$config,sql_*,demo_check_json)

$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
function chx($a){ header('Content-Type: application/json; charset=utf-8'); echo json_encode($a); exit; }

/* 권한: 운영자 전원(관리자면 OK) */
$is_ajax = in_array($mode, array('threads','msgs','reply','resolve','mds','badge','member','memo'));
if($is_ajax && (empty($member[mb_no]) || (int)$member[mb_admin] < 1)) chx(array('result'=>'err','message'=>'권한 없음'));

if($mode=='badge'){
  // 미답변 = 마지막 메시지가 회원이고 미해결인 상담 (관리자가 읽어도 안 풀리고, 답변해야 풀림)
  $u=sql_fetch("select count(*) as c from nfor_chat where ct_last_sender='M' and ct_status<>'1'");
  chx(array('result'=>'ok','unread'=>(int)$u['c'],'rooms'=>(int)$u['c']));
}
if($mode=='mds'){
  $list=array(); $r=sql_query("select distinct ct_md_name from nfor_chat where ct_md_name<>'' and ct_md_name<>'고객센터' order by ct_md_name");
  while($x=sql_fetch_array($r)) $list[]=$x['ct_md_name'];
  chx(array('result'=>'ok','mds'=>$list));
}
if($mode=='threads'){
  $filter = isset($_GET['filter'])?$_GET['filter']:'all';
  $md = isset($_GET['md'])?trim($_GET['md']):'';
  $w = "1=1";
  if($filter=='unanswered') $w .= " and ct_last_sender='M' and ct_status<>'1'";
  if($md!=='') $w .= " and ct_md_name='".addslashes($md)."'";
  $list=array();
  $r=sql_query("select c.*, TIMESTAMPDIFF(MINUTE, c.ct_last_datetime, NOW()) as wait_min, m.mb_name as real_name from nfor_chat c left join nfor_member m on c.ct_mb_no=m.mb_no where $w order by (c.ct_ad_unread>0) desc, c.ct_last_datetime desc limit 200");
  while($x=sql_fetch_array($r)){
    $list[]=array('ct_id'=>(int)$x['ct_id'],'nick'=>$x['ct_mb_nick'],'name'=>$x['real_name'],'cp'=>($x['ct_cp_subject']?$x['ct_cp_subject']:'일반 문의'),'cp_id'=>$x['ct_cp_id'],
      'cate'=>$x['ct_cate'],'md'=>($x['ct_md_name']?$x['ct_md_name']:'고객센터'),'last'=>$x['ct_last_msg'],'tm'=>substr($x['ct_last_datetime'],5,11),
      'unread'=>(int)$x['ct_ad_unread'],'wait'=>(int)$x['wait_min'],'status'=>$x['ct_status']);
  }
  chx(array('result'=>'ok','list'=>$list));
}
if($mode=='msgs'){
  $ct=(int)$_GET['ct_id']; $after=(int)(isset($_GET['after'])?$_GET['after']:0);
  $t=sql_fetch("select * from nfor_chat where ct_id='$ct'"); if(empty($t['ct_id'])) chx(array('result'=>'err'));
  $msgs=array();
  $r=sql_query("select cm_id,cm_sender,cm_msg,cm_admin_name,cm_datetime from nfor_chat_msg where cm_ct_id='$ct' and cm_id>'$after' order by cm_id asc");
  while($x=sql_fetch_array($r)) $msgs[]=array('id'=>(int)$x['cm_id'],'me'=>($x['cm_sender']=='A'),'who'=>($x['cm_sender']=='A'?($x['cm_admin_name']?$x['cm_admin_name']:'고객센터'):$t['ct_mb_nick']),'msg'=>$x['cm_msg'],'tm'=>substr($x['cm_datetime'],5,11));
  sql_query("update nfor_chat set ct_ad_unread=0 where ct_id='$ct'");
  $tnm=sql_fetch("select mb_name from nfor_member where mb_no='".addslashes($t['ct_mb_no'])."'");
  chx(array('result'=>'ok','msgs'=>$msgs,'head'=>array('nick'=>$t['ct_mb_nick'],'name'=>$tnm['mb_name'],'cp'=>($t['ct_cp_subject']?$t['ct_cp_subject']:'일반 문의'),'cp_id'=>$t['ct_cp_id'],'cate'=>$t['ct_cate'],'memo'=>$t['ct_admin_memo'],'md'=>($t['ct_md_name']?$t['ct_md_name']:'고객센터'),'status'=>$t['ct_status'])));
}
if($mode=='reply'){
  demo_check_json();
  $ct=(int)$_POST['ct_id']; $msg=trim(isset($_POST['msg'])?$_POST['msg']:'');
  if($msg==='') chx(array('result'=>'err','message'=>'내용을 입력하세요.'));
  $t=sql_fetch("select ct_id from nfor_chat where ct_id='$ct'"); if(empty($t['ct_id'])) chx(array('result'=>'err'));
  $msg=mb_substr($msg,0,2000,'UTF-8');
  $who=addslashes($member[mb_name]?$member[mb_name].' (고객센터)':'고객센터');
  sql_query("insert into nfor_chat_msg set cm_ct_id='$ct', cm_sender='A', cm_msg='".addslashes($msg)."', cm_admin_name='".$who."', cm_datetime=NOW()");
  sql_query("update nfor_chat set ct_last_msg='".addslashes(mb_substr($msg,0,200,'UTF-8'))."', ct_last_datetime=NOW(), ct_last_sender='A', ct_mb_unread=ct_mb_unread+1, ct_ad_unread=0 where ct_id='$ct'");
  chx(array('result'=>'ok'));
}
if($mode=='resolve'){
  demo_check_json();
  $ct=(int)$_POST['ct_id']; $st=isset($_POST['status'])&&$_POST['status']=='0'?'0':'1';
  sql_query("update nfor_chat set ct_status='$st' where ct_id='".$ct."'");
  chx(array('result'=>'ok'));
}

if($mode=='member'){
  $ct=(int)(isset($_GET['ct_id'])?$_GET['ct_id']:0);
  $tt=sql_fetch("select ct_mb_no from nfor_chat where ct_id='$ct'"); if(empty($tt['ct_mb_no'])) chx(array('result'=>'err'));
  $mbno=(int)$tt['ct_mb_no'];
  $m=sql_fetch("select mb_id,mb_name,mb_nick,mb_hp,mb_email,mb_blog,mb_point,mb_level,mb_datetime from nfor_member where mb_no='$mbno'");
  $cnt=sql_fetch("select sum(rv_step='4') as done, sum(rv_step in('2','3','7')) as ing, count(distinct rv_cp_id) as total from nfor_review where rv_mb_no='$mbno' and rv_delete='0'");
  $recent=array(); $rr=sql_query("select rv_cp_subject, rv_step, max(rv_id) as rid from nfor_review where rv_mb_no='$mbno' and rv_delete='0' group by rv_cp_id order by rid desc limit 5");
  while($x=sql_fetch_array($rr)) $recent[]=array('cp'=>$x['rv_cp_subject'],'step'=>$x['rv_step']);
  chx(array('result'=>'ok','m'=>array('id'=>$m['mb_id'],'name'=>$m['mb_name'],'nick'=>$m['mb_nick'],'hp'=>$m['mb_hp'],'email'=>$m['mb_email'],'blog'=>$m['mb_blog'],'point'=>(int)$m['mb_point'],'level'=>$m['mb_level'],'join'=>substr($m['mb_datetime'],0,10),'cp_done'=>(int)$cnt['done'],'cp_ing'=>(int)$cnt['ing'],'cp_total'=>(int)$cnt['total'],'recent'=>$recent)));
}
if($mode=='memo'){
  demo_check_json();
  $ct=(int)(isset($_POST['ct_id'])?$_POST['ct_id']:0);
  $memo=mb_substr(trim(isset($_POST['memo'])?$_POST['memo']:''),0,2000,'UTF-8');
  sql_query("update nfor_chat set ct_admin_memo='".addslashes($memo)."' where ct_id='$ct'");
  chx(array('result'=>'ok'));
}

include_once "head.php";
?>
<style>
.mcc-wrap{display:grid;grid-template-columns:340px 1fr;gap:0;background:#fff;border:1px solid #eaedf1;border-radius:14px;overflow:hidden;height:calc(100vh - 220px);min-height:520px;margin-top:10px}
.mcc-list{border-right:1px solid #eaedf1;display:flex;flex-direction:column;min-width:0}
.mcc-lhead{padding:13px 15px 10px;border-bottom:1px solid #eaedf1}
.mcc-lhead h2{margin:0 0 9px;font-size:16px;font-weight:800}
.mcc-filt{display:flex;gap:6px;flex-wrap:wrap;align-items:center}
.mcc-chip{border:1px solid #e3e8ee;background:#fff;color:#4e5968;font-weight:700;font-size:12px;padding:6px 11px;border-radius:999px;cursor:pointer;display:inline-flex;gap:5px;align-items:center}
.mcc-chip.on{background:#03c75a;border-color:#03c75a;color:#fff}
.mcc-chip .c{background:rgba(240,68,82,.9);color:#fff;border-radius:999px;padding:0 6px;font-size:11px}
.mcc-chip.on .c{background:rgba(255,255,255,.3)}
.mcc-filt select{border:1px solid #e3e8ee;border-radius:8px;padding:6px 9px;font-family:inherit;font-size:12px;color:#333}
.mcc-convos{flex:1;overflow-y:auto}
.mcc-cv{display:flex;gap:10px;padding:12px 15px;border-bottom:1px solid #f4f6f8;cursor:pointer}
.mcc-cv:hover{background:#fafdff}.mcc-cv.sel{background:#f1fcf5}
.mcc-cv .av{width:38px;height:38px;border-radius:50%;background:#eef1f4;flex:0 0 auto;display:flex;align-items:center;justify-content:center;font-size:16px}
.mcc-cv .mn{flex:1;min-width:0}
.mcc-cv .r1{display:flex;justify-content:space-between;gap:6px}
.mcc-cv .nm{font-weight:700;font-size:13px}.mcc-cv .tm{font-size:11px;color:#9aa4af;flex:0 0 auto}
.mcc-cv .cp{font-size:11px;color:#2b6fe0;background:#eef3ff;border-radius:6px;padding:1px 6px;display:inline-block;margin:3px 0}
.mcc-cv .md{font-size:11px;color:#8b95a1}
.mcc-cv .ls{font-size:12px;color:#8b95a1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-top:2px}
.mcc-cv .ur{background:#f04452;color:#fff;font-size:11px;font-weight:800;border-radius:999px;min-width:18px;height:18px;display:flex;align-items:center;justify-content:center;padding:0 5px;flex:0 0 auto}
.mcc-cv .rs{font-size:10px;color:#02b350;font-weight:800}
.mcc-detail{display:flex;flex-direction:column;min-width:0}
.mcc-dh{padding:13px 16px;border-bottom:1px solid #eaedf1;display:flex;align-items:center;gap:10px}
.mcc-dh .dn{font-weight:800;font-size:15px}.mcc-dh .dc{font-size:12px;color:#2b6fe0;background:#eef3ff;border-radius:7px;padding:2px 8px;margin-top:3px;display:inline-block}
.mcc-dh .dm{margin-left:auto;text-align:right;font-size:12px;color:#8b95a1}.mcc-dh .dm b{color:#191f28}
.mcc-rsv{background:#eef1f4;border:0;color:#4e5968;font-weight:700;font-size:12px;padding:8px 12px;border-radius:9px;cursor:pointer;margin-left:8px}
.mcc-rsv.done{background:#e7f7ee;color:#02b350}
.mcc-body{flex:1;overflow-y:auto;padding:16px;background:#f7f9fb}
.mcc-foot{border-top:1px solid #eaedf1;padding:11px;display:flex;gap:8px}
.mcc-foot textarea{flex:1;border:1px solid #dbe1e8;border-radius:10px;padding:11px 13px;font-family:inherit;font-size:14px;outline:none;resize:none;line-height:1.45;min-height:44px;max-height:130px;overflow-y:auto}
.mcc-foot textarea:focus{border-color:#03c75a}
.mcc-foot .sd{background:#03c75a;border:0;color:#fff;font-weight:800;padding:0 20px;border-radius:10px;cursor:pointer;font-family:inherit}
.mcc-empty{flex:1;display:flex;align-items:center;justify-content:center;color:#b5bdc6;font-size:14px}
.mcc-m{display:flex;margin-bottom:9px}.mcc-m.me{justify-content:flex-end}
.mcc-bub{max-width:74%;padding:9px 12px;border-radius:13px;font-size:13.5px;line-height:1.5;white-space:pre-wrap;word-break:break-word}
.mcc-m.them .mcc-bub{background:#fff;border:1px solid #eaedf1;color:#191f28;border-top-left-radius:4px}
.mcc-m.me .mcc-bub{background:#03c75a;color:#fff;border-top-right-radius:4px}
.mcc-m .who{font-size:11px;color:#8b95a1;font-weight:700;margin:0 0 3px 2px}
.mcc-tm{font-size:10px;color:#aab2bd;align-self:flex-end;white-space:nowrap;margin:0 6px}
.mcc-cv .rn,.mcc-dh .rn{color:#d6336c;font-weight:700;font-size:.9em}
</style>

<div class="mcc-wrap">
  <div class="mcc-list">
    <div class="mcc-lhead">
      <h2>💬 고객센터 상담함</h2>
      <div class="mcc-filt">
        <button class="mcc-chip on" data-f="all" onclick="mccFilter('all')">전체</button>
        <button class="mcc-chip" data-f="unanswered" onclick="mccFilter('unanswered')">미답변 <span class="c" id="mccUn">0</span></button>
        <select id="mccMd" onchange="mccLoad()"><option value="">담당자 전체</option></select>
      </div>
    </div>
    <div class="mcc-convos" id="mccConvos"></div>
  </div>
  <div class="mcc-detail" id="mccDetail"><div class="mcc-empty">왼쪽에서 상담을 선택하세요</div></div>
</div>

<script>
var mccFil='all', mccSel=null, mccLast=0, mccTimer=null;
function mccEsc(s){return (s==null?'':String(s)).replace(/[&<>"]/g,function(c){return{'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c];});}
function mccGet(p){return fetch('chat_list.php?'+p,{credentials:'same-origin'}).then(r=>r.json());}
function mccPost(p){return fetch('chat_list.php',{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/x-www-form-urlencoded;charset=UTF-8'},body:p}).then(r=>r.json());}
function mccFilter(f){mccFil=f;document.querySelectorAll('.mcc-chip').forEach(b=>b.classList.toggle('on',b.dataset.f===f));mccLoad();}
function mccLoadMds(){ mccGet('mode=mds').then(function(d){ if(d.result!=='ok')return; var s=document.getElementById('mccMd'); var cur=s.value; s.innerHTML='<option value="">담당자 전체</option>'+d.mds.map(m=>'<option value="'+mccEsc(m)+'">'+mccEsc(m)+'</option>').join(''); s.value=cur; }); }
function mccLoad(){
  var md=document.getElementById('mccMd').value;
  mccGet('mode=threads&filter='+mccFil+'&md='+encodeURIComponent(md)).then(function(d){
    if(d.result!=='ok')return; var el=document.getElementById('mccConvos');
    el.innerHTML = d.list.length ? d.list.map(function(c){
      return '<div class="mcc-cv'+(c.ct_id===mccSel?' sel':'')+'" onclick="mccOpen('+c.ct_id+')"><div class="av">🙂</div><div class="mn">'+
        '<div class="r1"><span class="nm">'+mccEsc(c.nick)+(c.name&&c.name!==c.nick?' <span class="rn">'+mccEsc(c.name)+'</span>':'')+'</span><span class="tm">'+mccEsc(c.tm)+'</span></div>'+
        '<span class="cp">'+mccEsc(c.cp.length>20?c.cp.slice(0,20)+'…':c.cp)+'</span> <span class="md">· '+mccEsc(c.md)+'</span>'+
        '<div class="ls">'+(c.status=='1'?'<span class="rs">[해결] </span>':'')+mccEsc(c.last||'(새 문의)')+'</div></div>'+
        (c.unread>0?'<div class="ur">'+c.unread+'</div>':'')+'</div>';
    }).join('') : '<div style="padding:30px;text-align:center;color:#b5bdc6;font-size:13px">해당 문의가 없습니다</div>';
  });
  mccGet('mode=badge').then(function(d){ if(d.result==='ok'){ document.getElementById('mccUn').textContent=d.rooms; } });
}
function mccOpen(id){
  mccSel=id; mccLast=0;
  mccGet('mode=msgs&ct_id='+id+'&after=0').then(function(d){
    if(d.result!=='ok')return; var h=d.head;
    var det=document.getElementById('mccDetail');
    det.innerHTML='<div class="mcc-dh"><div><div class="dn">'+mccEsc(h.nick)+(h.name&&h.name!==h.nick?' <span class="rn">'+mccEsc(h.name)+'</span>':'')+'</div><span class="dc">'+mccEsc(h.cp)+(h.cp_id?(' · '+mccEsc(h.cp_id)):'')+'</span></div>'+
      '<div class="dm">담당자<br><b>'+mccEsc(h.md)+'</b></div>'+
      '<button class="mcc-rsv'+(h.status=='1'?' done':'')+'" id="mccRsv" onclick="mccResolve()">'+(h.status=='1'?'✓ 해결됨':'해결완료')+'</button></div>'+
      '<div class="mcc-body" id="mccMsgs"></div>'+
      '<div class="mcc-foot"><textarea id="mccInput" rows="1" placeholder="답변 입력 (Enter 전송 · Shift+Enter 줄바꿈)" onkeydown="if(event.key===\'Enter\'&&!event.shiftKey){event.preventDefault();mccReply();}" oninput="this.style.height=\'auto\';this.style.height=Math.min(this.scrollHeight,130)+\'px\'"></textarea><button class="sd" onclick="mccReply()">전송</button></div>';
    mccRenderMsgs(d.msgs,true); document.getElementById('mccInput').focus();
    mccLoad();
  });
  mccStartPoll();
}
function mccRenderMsgs(msgs,reset){ var b=document.getElementById('mccMsgs'); if(!b)return; if(reset)b.innerHTML='';
  msgs.forEach(function(m){ mccLast=Math.max(mccLast,m.id); var mtm=(m.tm||'').slice(6);
    b.insertAdjacentHTML('beforeend', m.me
      ? '<div class="mcc-m me"><span class="mcc-tm">'+mccEsc(mtm)+'</span><div class="mcc-bub">'+mccEsc(m.msg)+'</div></div>'
      : '<div class="mcc-m them"><div><div class="who">'+mccEsc(m.who)+'</div><div class="mcc-bub">'+mccEsc(m.msg)+'</div></div><span class="mcc-tm">'+mccEsc(mtm)+'</span></div>'); });
  if(msgs.length)b.scrollTop=b.scrollHeight;
}
function mccReply(){ var i=document.getElementById('mccInput'); var t=i.value.trim(); if(!t||!mccSel)return; i.value=''; i.style.height='';
  mccPost('mode=reply&ct_id='+mccSel+'&msg='+encodeURIComponent(t)).then(function(d){ if(d.result==='ok'){ mccGet('mode=msgs&ct_id='+mccSel+'&after='+mccLast).then(function(d2){ if(d2.result==='ok')mccRenderMsgs(d2.msgs,false); }); mccLoad(); } else alert(d.message||'전송 실패'); }); }
function mccResolve(){ if(!mccSel)return; var done=document.getElementById('mccRsv').classList.contains('done');
  mccPost('mode=resolve&ct_id='+mccSel+'&status='+(done?'0':'1')).then(function(d){ if(d.result==='ok'){ mccOpen(mccSel); } }); }
function mccStartPoll(){ if(mccTimer)clearInterval(mccTimer); mccTimer=setInterval(function(){
  if(mccSel){ mccGet('mode=msgs&ct_id='+mccSel+'&after='+mccLast).then(function(d){ if(d.result==='ok'&&d.msgs.length)mccRenderMsgs(d.msgs,false); }); }
  mccLoad();
},5000); }
mccLoadMds(); mccLoad(); mccStartPoll();
</script>
<?php include_once "tail.php"; ?>
