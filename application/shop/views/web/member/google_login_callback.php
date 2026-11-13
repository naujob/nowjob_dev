 <div class="page page-join member-type1">
   <div class="top-bar">

     <h1 class="title"><?= t('Google 로그인 확인') ?></h1>
   </div>
 </div>
<script>
  $(document).ready(function() {
    if ('<?= $code ?>' == '90') {
      // 회원가입 확인 팝업 (카카오와 동일한 CSS 스타일)
      openPopup('confirm', {
        id: 'permission',
        content: '<strong>' + t('회원으로 가입된 정보가 없습니다.') + '</strong><small>' + t('회원 가입을 진행하시겠습니까?') + '</small>',
        firstButton: t('취소'),
        secondButton: t('회원가입'),
        firstCallback: function() {
          location.href = "/member/login";
        },
        secondCallback: function() {
          // SNS 정보는 이미 세션에 저장됨
          location.href = "/member/join_sns_start?join_key=<?= isset($res_id) ? $res_id : '' ?>&join_type=GOOGLE";
        },
      });
    } else {
      // 오류메시지 (카카오와 동일한 CSS 스타일)
      openPopup('modal', {
        id: 'permission',
        title: t('안내'),
        content: '<div style="padding: 20px 70px;"><?= isset($msg) ? $msg : '' ?></div>',
        firstButton: t('확인'),
        firstCallback: function() {
          location.href = "/member/login";
        },
      });
    }

  });
</script>
