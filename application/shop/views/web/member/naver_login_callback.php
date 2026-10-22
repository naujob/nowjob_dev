 <div class="page page-join member-type1">
   <div class="top-bar">

     <h1 class="title">네이버 로그인 확인</h1>
   </div>
 </div>
<script>
  $(document).ready(function() {
    if ('<?= $code ?>' == '90') {
      //회원가입 확인 팝업 (카카오와 동일한 CSS 스타일)
      openPopup('confirm', {
        id: 'permission',
        content: '<strong>회원으로 가입된 정보가 없습니다.</strong><small>회원 가입을 진행하시겠습니까?</small>',
        firstButton: '취소',
        secondButton: '회원가입',
        firstCallback: function() {
          location.href = "/member/login";
        },
        secondCallback: function() {
          // PASS 인증 없이 바로 회원가입 페이지로 이동 (SNS 정보는 이미 세션에 저장됨)
          location.href = "/member/join_sns_start?join_key=<?= isset($res_id) ? $res_id : '' ?>&join_type=NAVER";
        },
      });
    } else {
      //오류메시지 (카카오와 동일한 CSS 스타일)
      openPopup('modal', {
        id: 'permission',
        title: '안내',
        content: '<div style="padding: 20px 70px;"><?= isset($msg) ? $msg : '' ?></div>',
        firstButton: '확인',
        firstCallback: function() {
          location.href = "/member/login";
        },
      });
    }

  });
</script>
<!-- PASS 인증 폼 제거됨 - 간편로그인 시 PASS 인증 단계 없이 바로 회원가입 진행 -->