<style>
  /* 메인 비주얼 헤더 스타일 */
  .main-visual-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.25rem;
    background-color: #fff;
    position: relative;
    z-index: 10;
  }

  .main-visual-header .logo {
    width: auto;
    height: 2rem;
  }

  .main-visual-header .logo img {
    height: 100%;
    width: auto;
  }

  .main-visual-header .profile-icon {
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    background-color: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
  }

  .main-visual-header .profile-icon i {
    font-size: 1.125rem;
    color: #666;
  }
</style>

<!-- 메인 비주얼 헤더 -->
<div class="main-visual-header">
  <div class="logo">
    <img src="/resource/shop/mobile/img/banner/banner_nowjob_logo.svg" alt="NOW JOB">
  </div>

  <?php if ($this->user->getUserType() == "COMP") { ?>
    <!-- 기업 로그인 상태 -->
    <a href="/compage/index" class="profile-icon" aria-label="프로필">
      <img src="/resource/shop/mobile/img/mypage_ic.png" alt="프로필">
    </a>
  <?php } else if ($this->user->getUserType() == "MEM") { ?>
    <!-- 개인 로그인 상태 -->
    <a href="/mypage/index" class="profile-icon" aria-label="프로필">
      <img src="/resource/shop/mobile/img/mypage_ic.png" alt="프로필">
    </a>
  <?php } else { ?>
    <!-- 비로그인 상태 -->
    <a href="/member/login" class="profile-icon" aria-label="프로필">
      <img src="/resource/shop/mobile/img/mypage_ic.png" alt="프로필">
    </a>
  <?php } ?>

  <!-- 알림 패널 - 주석처리됨 -->
  <!-- <a href="#alarmPanel" aria-haspopup="dialog" aria-controls="alarmPanel" class="btn-alarm js-toggle-panel">
        <span class="sr-text">미확인 알림메세지</span>
        <span class="cnt"><?= $g_alarm_cnt ?></span>
      </a> -->
</div>