<?php
/* 출금신청(1)·입금예정(2) 관리자 지급정보 정정 공통 화면 */
if(basename($_SERVER['PHP_SELF']) === basename(__FILE__)) exit;
if(!isset($mc_pb_edit_step) || !in_array((int)$mc_pb_edit_step, array(1,2))) alert('잘못된 접근입니다.');

if($mode === 'account_update'){
    $result = mc_pb_admin_change($connect_db, array(
        'pbId'=>isset($_POST['pb_id']) ? $_POST['pb_id'] : 0,
        'expectedStep'=>$mc_pb_edit_step,
        'expectedRowRevision'=>isset($_POST['pb_row_revision']) ? $_POST['pb_row_revision'] : -1,
        'name'=>isset($_POST['pb_name']) ? $_POST['pb_name'] : '',
        'bank'=>isset($_POST['pb_bank']) ? $_POST['pb_bank'] : '',
        'account'=>isset($_POST['pb_bank_number']) ? $_POST['pb_bank_number'] : '',
        'actorName'=>isset($member['mb_id']) ? $member['mb_id'] : '관리자'
    ));
    if(!empty($result['ok'])) json_return('계좌정보가 수정되었습니다. 전산에는 다음 조회부터 수정값이 반영됩니다.', 'ok');
    $fieldMap = array('name'=>'pb_name', 'bank'=>'pb_bank', 'account'=>'pb_bank_number');
    $field = isset($result['field']) && isset($fieldMap[$result['field']]) ? $fieldMap[$result['field']] : 'fail';
    json_return(isset($result['message']) ? $result['message'] : '계좌정보 저장 중 오류가 발생했습니다.', $field);
}

function mc_pb_account_edit_button($row){
    $attrs = array(
        'data-id'=>(int)$row['pb_id'],
        'data-revision'=>(int)$row['pb_row_revision'],
        'data-name'=>$row['pb_name'],
        'data-bank'=>$row['pb_bank'],
        'data-account'=>$row['pb_bank_number']
    );
    $html = '';
    foreach($attrs as $key=>$value) $html .= ' '.$key.'="'.htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8').'"';
    return '<button type="button" class="btn btn-white btn-sm mc-pb-edit"'.$html.' style="margin-top:4px;white-space:nowrap;">계좌정보 수정</button>';
}

function mc_pb_account_edit_layer(){
?>
<style>
.mc-pb-modal{display:none;position:fixed;z-index:10020;inset:0;align-items:center;justify-content:center}
.mc-pb-modal__dim{position:absolute;inset:0;background:rgba(0,0,0,.45)}
.mc-pb-modal__box{position:relative;width:420px;max-width:calc(100% - 32px);background:#fff;border-radius:10px;padding:22px;box-sizing:border-box;box-shadow:0 12px 36px rgba(0,0,0,.22)}
.mc-pb-modal__head{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;font-size:18px;font-weight:700}
.mc-pb-modal__close{border:0;background:none;font-size:24px;cursor:pointer}
.mc-pb-field{margin-top:12px}.mc-pb-field label{display:block;margin-bottom:5px;font-weight:700}.mc-pb-field input{width:100%;height:38px;padding:0 10px;border:1px solid #ccd1d8;border-radius:5px;box-sizing:border-box}
.mc-pb-note{margin-top:12px;padding:10px;background:#f7f8fa;color:#555;line-height:1.5}
.mc-pb-actions{display:flex;gap:8px;justify-content:flex-end;margin-top:18px}
</style>
<div id="mc-pb-modal" class="mc-pb-modal" role="dialog" aria-modal="true" aria-labelledby="mc-pb-title">
  <div class="mc-pb-modal__dim"></div>
  <div class="mc-pb-modal__box">
    <div class="mc-pb-modal__head"><span id="mc-pb-title">계좌정보 수정</span><button type="button" class="mc-pb-modal__close" aria-label="닫기">&times;</button></div>
    <input type="hidden" id="mc-pb-id"><input type="hidden" id="mc-pb-revision">
    <div class="mc-pb-field"><label for="mc-pb-name">예금주</label><input type="text" id="mc-pb-name" maxlength="50" autocomplete="off"></div>
    <div class="mc-pb-field"><label for="mc-pb-bank">은행</label><input type="text" id="mc-pb-bank" maxlength="30" autocomplete="off"></div>
    <div class="mc-pb-field"><label for="mc-pb-account">계좌번호</label><input type="text" id="mc-pb-account" maxlength="40" inputmode="numeric" autocomplete="off" placeholder="숫자만 입력"></div>
    <div class="mc-pb-note">이 출금 건의 지급정보만 수정됩니다. 회원 기본정보와 다른 출금 신청 건은 변경되지 않습니다.</div>
    <div class="mc-pb-actions"><button type="button" class="btn btn-lg btn-white mc-pb-cancel">취소</button><button type="button" id="mc-pb-save" class="btn btn-lg btn-red">수정 저장</button></div>
  </div>
</div>
<script type="text/javascript">
(function($){
  var before={name:'',bank:'',account:''};
  function closeLayer(){ $('#mc-pb-modal').hide(); }
  $(document).on('click','.mc-pb-edit',function(){
    var $button=$(this);
    before={name:String($button.attr('data-name')||''),bank:String($button.attr('data-bank')||''),account:String($button.attr('data-account')||'')};
    $('#mc-pb-id').val($button.attr('data-id')); $('#mc-pb-revision').val($button.attr('data-revision'));
    $('#mc-pb-name').val(before.name); $('#mc-pb-bank').val(before.bank); $('#mc-pb-account').val(before.account);
    $('#mc-pb-modal').css('display','flex'); $('#mc-pb-name').focus();
  });
  $(document).on('click','.mc-pb-modal__dim,.mc-pb-modal__close,.mc-pb-cancel',closeLayer);
  $(document).on('keydown',function(event){ if(event.keyCode===27) closeLayer(); });
  $(document).on('click','#mc-pb-save',function(){
    var name=$.trim($('#mc-pb-name').val()), bank=$.trim($('#mc-pb-bank').val()), account=$.trim($('#mc-pb-account').val()).replace(/[\s-]+/g,'');
    if(!name){ alert('예금주를 입력해주세요.'); $('#mc-pb-name').focus(); return; }
    if(!bank){ alert('은행을 입력해주세요.'); $('#mc-pb-bank').focus(); return; }
    if(!/^[0-9]+$/.test(account)){ alert('계좌번호는 숫자만 입력해주세요.'); $('#mc-pb-account').focus(); return; }
    if(name===before.name && bank===before.bank && account===before.account.replace(/[\s-]+/g,'')){ alert('변경된 내용이 없습니다.'); return; }
    if(!confirm('이 출금 건의 계좌정보를 수정할까요?\n회원 기본정보는 변경되지 않습니다.')) return;
    var $save=$(this).prop('disabled',true).text('저장 중...');
    $.ajax({type:'post',url:location.pathname,dataType:'json',data:{mode:'account_update',pb_id:$('#mc-pb-id').val(),pb_row_revision:$('#mc-pb-revision').val(),pb_name:name,pb_bank:bank,pb_bank_number:account},
      success:function(response){ alert(response&&response.msg?response.msg:'처리가 완료되었습니다.'); if(response&&response.result==='ok'){ location.reload(); } else { $save.prop('disabled',false).text('수정 저장'); } },
      error:function(){ alert('저장 중 오류가 발생했습니다.'); $save.prop('disabled',false).text('수정 저장'); }
    });
  });
})(jQuery);
</script>
<?php
}
?>
