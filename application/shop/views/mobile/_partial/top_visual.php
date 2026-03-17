<style>
  /* 상단 비주얼 배너 높이 설정 */
  .top-visual {
    height: 180px;
    overflow: hidden;
  }

  /* 상단 비주얼 헤더 스타일 */
  .top-visual-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.25rem;
    background-color: #fff;
    position: relative;
    z-index: 10;
  }

  .top-visual-header .logo {
    width: auto;
    height: 2rem;
  }

  .top-visual-header .logo img {
    height: 100%;
    width: auto;
  }

  .top-visual-header .profile-icon {
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    background-color: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
  }

  .top-visual-header .profile-icon i {
    font-size: 1.125rem;
    color: #666;
  }
</style>

<!-- 상단 비주얼 헤더 -->
<div class="top-visual-header">
  <div class="logo">
    <img src="/resource/shop/mobile/img/banner/banner_nowjob_logo.svg" alt="NOW JOB">
  </div>

  <!-- 알림 패널 -->
  <a href="#alarmPanel" aria-haspopup="dialog" aria-controls="alarmPanel" class="btn-alarm js-toggle-panel">
    <span class="sr-text">미확인 알림메세지</span>
    <span class="cnt"><?= $g_alarm_cnt ?></span>
  </a>
</div>

<?php if ($banner != null) : ?>
  <div class="top-visual">
   
      <!-- 채용공고 작성 버튼: 기업회원 + resume/hr_list에서만 보이게 -->
      <?php if ($mm_type === "COMP" && strpos(current_url(), "/resume/hr_list") !== false) : ?>
            <a href="#" class="btn top-visual-action-btn" id="btnRecruitList">채용공고 등록</a>
      <?php endif; ?>
      <!-- 이력서 등록 버튼: 일반회원 + recruit/job_list에서만 보이게 -->
      <?php if ($mm_type === "MEM" && strpos(current_url(), "/recruit/job_list") !== false) : ?>
            <a href="#" class="btn top-visual-action-btn" id="btnResumeList">이력서 등록</a>
      <?php endif; ?>
      <div class="visual-img">
        <img src="<?= $banner["bg_img"] ?>" alt="">
      </div>

    
  </div>
<?php endif; ?>
