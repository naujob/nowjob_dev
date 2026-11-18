<?php
// 공통 상단 바 (로고 · A.I 매칭 · 언어 스위치 · 프로필)
// 쓰는 곳: _partial/main_visual.php (채용·인재 목록), recruit/company.php (기업 채용목록)
// 페이지별 설정 변수 (안 주면 기본값):
//   $top_bar_ai_match    : A.I 매칭 버튼 표시 여부 (기본 true)
//   $top_bar_lang_switch : 헤더 언어 스위치 표시 여부 (기본: $list_v2 와 같음)
$list_v2 = !empty($list_v2);
$top_bar_ai_match = isset($top_bar_ai_match) ? $top_bar_ai_match : true;
$top_bar_lang_switch = isset($top_bar_lang_switch) ? $top_bar_lang_switch : $list_v2;

// 현재 사용자 기준 값
$ai_user = in_array($this->user->getUserType(), array("MEM", "COMP")) ? $this->user->getUserType() : "GUEST";

// A.I 매칭 배지 표시 여부 확인
$has_new_match = false;
if ($this->user->getUserType() == "MEM") {
  $has_new_match = $this->member_model->hasUnreadMatchRecruit($this->user->getUserIdx());
} else if ($this->user->getUserType() == "COMP") {
  $has_new_match = $this->member_model->hasUnreadMatchResume($this->user->getUserIdx());
}

// A.I 매칭 광고성 정보 수신 동의 여부 확인
$mm_agree_ad = 'N';
if ($this->user->getUserType() == "MEM" || $this->user->getUserType() == "COMP") {
  $mem_info_for_ad = $this->member_model->getMember(["mm_idx" => $this->user->getUserIdx()]);
  $mm_agree_ad = (isset($mem_info_for_ad["mm_agree_ad"]) && $mem_info_for_ad["mm_agree_ad"] == "Y") ? 'Y' : 'N';
}

$profile_url = '/member/login';
$match_url = '/member/login';
if ($this->user->getUserType() == "COMP") {
  $profile_url = '/compage/index';
  $match_url = '/compage/match_list';
} else if ($this->user->getUserType() == "MEM") {
  $profile_url = '/mypage/index';
  $match_url = '/mypage/match_list';
}
?>
<style>
  /* 메인 비주얼 헤더 스타일 */
  .main-visual-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.25rem 1rem 1.25rem;
    background-color: #fff;
    position: sticky;
    top: 0;
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

  .main-visual-header .profile-icon img {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }

  /* A.I 매칭 배지 스타일 */
  .main-visual-header .profile-menu {
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .main-visual-header .ai-match-label {
    font-size: 1.1rem;
    color: #fff;
    background-color: #57CCAF;
    border-radius: 999px;
    white-space: nowrap;
    font-weight: 500;
    position: relative;
    left: 30px;
    padding-top: 4px;
    padding-bottom: 4px;
    padding-left: 14px;
    padding-right: 30px;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
  }

  .main-visual-header .ai-match-label:hover {
    opacity: 0.9;
  }

  .main-visual-header .profile-icon {
    position: relative;
  }

  .main-visual-header .badge-dot {
    display: none;
    position: absolute;
    top: -7px;
    right: -5px;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background-color: #f44336;
    border: 1.5px solid #fff;
  }

  .main-visual-header .profile-menu[data-badge="true"] .badge-dot {
    display: block;
  }
</style>

<!-- 메인 비주얼 헤더 -->
<div class="main-visual-header">
  <!-- 로고 클릭 시 현재 화면의 목록 1페이지로 이동 (page=1을 명시해 쿠키의 마지막 페이지 복원을 막음)
       인재목록(/resume/hr_list)에서는 인재목록 1페이지, 그 외에는 채용공고 목록 1페이지 -->
  <?php $logo_list_href = (strpos($_SERVER['REQUEST_URI'], '/resume/hr_list') !== false) ? '/resume/hr_list?page=1' : '/recruit/job_list?page=1'; ?>
  <a href="<?= $logo_list_href ?>" class="logo">
    <img src="/resource/shop/mobile/img/banner/banner_nowjob_logo.svg" alt="NOW JOB">
  </a>

  <div class="profile-menu" data-badge="<?= $has_new_match ? 'true' : 'false' ?>">
    <?php // 헤더 스위치는 $top_bar_lang_switch 일 때만 출력 (그 밖의 화면은 basic.php의 플로팅 스위치) ?>
    <?php if ($top_bar_lang_switch) : ?>
    <!-- 언어 전환 스위치: 누르면 쿠키 저장 후 목록 갱신 (마크업: _partial/lang_switch.php, 동작: js/m_lang_switch.js) -->
    <?php $lang_switch_variant = 'header'; include(APPPATH . 'views/mobile/_partial/lang_switch.php'); ?>
    <?php endif; ?>
    <?php if ($top_bar_ai_match) : ?>
    <a href="<?= $match_url ?>" id="aiMatchLink" class="ai-match-label<?= $list_v2 ? ' is-collapsed' : '' ?>" data-user="<?= $ai_user ?>" data-agree-ad="<?= $mm_agree_ad ?>" data-match-url="<?= $match_url ?>"><?php if ($list_v2) : ?><span class="ai-chip"><svg class="ai-mini" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 1.5c.6 5.6 4.9 9.9 10.5 10.5-5.6.6-9.9 4.9-10.5 10.5C11.4 16.9 7.1 12.6 1.5 12 7.1 11.4 11.4 7.1 12 1.5z" fill="#fff"/></svg>AI<i class="ai-star s1"></i><i class="ai-star s2"></i></span><span class="ai-txt"><?= ($ai_user == "COMP") ? t("매칭인재") : t("매칭공고") ?></span><?php else : ?><?= t('A.I 매칭리스트') ?><?php endif; ?></a>
    <?php endif; ?>
    <a href="<?= $profile_url ?>" class="profile-icon" aria-label="<?= t('프로필') ?>">
      <img src="/resource/shop/mobile/img/mypage_ic.png" alt="<?= t('프로필') ?>">
      <?php if ($has_new_match) : ?>
        <span class="badge-dot"></span>
      <?php endif; ?>
    </a>
  </div>

  <!-- 알림 패널 - 주석처리됨 -->
  <!-- <a href="#alarmPanel" aria-haspopup="dialog" aria-controls="alarmPanel" class="btn-alarm js-toggle-panel">
        <span class="sr-text">미확인 알림메세지</span>
        <span class="cnt"><?= $g_alarm_cnt ?></span>
      </a> -->
</div>

<?php
// A.I 매칭 버튼이 있으면 광고성 동의 바텀시트도 함께 (헤더 밖에 둔다: sticky 헤더의 쌓임 맥락 밖으로 빼기 위함)
if ($top_bar_ai_match) {
  include(APPPATH . 'views/mobile/_partial/main_visual_ai_consent.php');
}
?>
