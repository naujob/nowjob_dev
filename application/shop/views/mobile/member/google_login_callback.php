<div class="page page-login">
  <div class="login-form-wrap">
    <h1 style="text-align: center; margin-bottom: 20px;">Google 로그인</h1>
  </div>
</div>

<script src="/resource/shop/mobile/js/logger.js"></script>
<script>
  $(document).ready(function() {
    var code = '<?= $code ?>';
    var msg = '<?= isset($msg) ? $msg : '' ?>';

 

    if (code == '90') {
     
      // 신규 회원 → 회원가입으로 이동
      setTimeout(function() {
        if (confirm('회원으로 가입된 정보가 없습니다. 회원 가입을 진행하시겠습니까?')) {
          location.href = "/member/join_sns_start?join_key=<?= isset($res_id) ? $res_id : '' ?>&join_type=GOOGLE";
        } else {
          location.href = "/member/login";
        }
      }, 500);
    } else if (code == '00') {
     
      // 로그인 성공 → 홈으로 이동
      setTimeout(function() {
        location.href = "/";
      }, 500);
    } else {
    
      // 오류
      alert(msg || 'Google 로그인에 실패했습니다.');
      location.href = "/member/login";
    }
  });
</script>
