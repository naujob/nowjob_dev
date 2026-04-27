<?php
// 배너 목록 조회
if (!isset($banner_list)) {
  $banner_list = getBannerList("BN00010001");
}
$banner = null;
if (count($banner_list) > 0) {
  $banner = $banner_list[0];
}
?>
<style>
  /* 메인 비주얼 헤더 스타일 */
  .main-visual {
    height: 180px;
    overflow: hidden;
  }

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

  /* 메인 비주얼 슬라이더: 활성 슬라이드만 클릭 가능하도록 처리 */
  /* 겹쳐진 슬라이드가 클릭을 가로채는 문제 예방 */
  .main-visual .visual-slider .slider-wrap .keen-slider__slide {
    pointer-events: none;
  }

  .main-visual .visual-slider .slider-wrap .keen-slider__slide.active {
    pointer-events: auto;
    z-index: 1;
  }

  /* 배너 이미지를 높이에 맞춤 */
  .main-visual .visual-slider .visual-img {
    height: 180px;
    overflow: hidden;
  }

  .main-visual .visual-slider .visual-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;

  }

  /* 배너 이미지 전체가 클릭 영역이 되도록 앵커를 블록화 */
  .main-visual .visual-slider .visual-img a {
    display: block;
    width: 100%;
    height: 100%;
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
      <i class="fa-solid fa-user"></i>
    </a>
  <?php } else if ($this->user->getUserType() == "MEM") { ?>
    <!-- 개인 로그인 상태 -->
    <a href="/mypage/index" class="profile-icon" aria-label="프로필">
      <i class="fa-solid fa-user"></i>
    </a>
  <?php } else { ?>
    <!-- 비로그인 상태 -->
    <a href="/member/login" class="profile-icon" aria-label="프로필">
      <i class="fa-solid fa-user"></i>
    </a>
  <?php } ?>

  <!-- 알림 패널 - 주석처리됨 -->
  <!-- <a href="#alarmPanel" aria-haspopup="dialog" aria-controls="alarmPanel" class="btn-alarm js-toggle-panel">
        <span class="sr-text">미확인 알림메세지</span>
        <span class="cnt"><?= $g_alarm_cnt ?></span>
      </a> -->
</div>

<!-- 상세 검색 옵션 - hr_list 페이지에서만 표시 -->
<?php
// 현재 페이지 경로 확인
$current_page = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
$is_hr_list = strpos($current_page, '/resume/hr_list') !== false;
if ($is_hr_list) {
?>
  <div class="search-option has-option">
    <style>
      .search-option.has-option .btn-option {
        top: 0.975rem;
      }
    </style>
    <!-- 지역검색 -->
    <div class="current-area">
      <div class="container">
        <a href="#changeArea" role="button" aria-haspopup="dialog" aria-controls="changeArea" class="btn js-toggle-panel addrName">전체</a>
      </div>
    </div>
    <!-- 선택된 카테고리 -->
    <div class="categories">
      <div class="container" id="tab_list">

      </div>
    </div>
    <!-- 상세 검색 옵션 버튼 -->
    <a href="#advancedSearch" role="button" aria-haspopup="dialog" aria-controls="advancedSearch" class="btn-option js-toggle-panel"><span class="sr-text">상세검색 열기</span></a>
  </div>
<?php } ?>

<div class="main-visual">
  <div class="visual-slider">
    <div class="slider-wrap keen-slider">
      <?php foreach ($banner_list as $banner) : ?>
        <div class="keen-slider__slide">
          <div class="visual-img">
            <?php if ($banner["bn_link_yn"] == "Y") : ?>
              <?php
              // community가 포함된 링크를 setting으로 변경
              $banner_link = str_replace("community", "setting", $banner["bn_link"]);
              // _blank를 _self로 변경
              $banner_target = ($banner["bn_link_type"] == "_blank") ? "_self" : $banner["bn_link_type"];
              ?>
              <a href="<?= $banner_link ?>" target="<?= $banner_target ?>">
                <img src="<?= $banner["bg_img"] ?>" alt="메인 배너 이미지">
              </a>
            <?php else : ?>
              <img src="<?= $banner["bg_img"] ?>" alt="메인 배너 이미지">
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <script>
    window.addEventListener('DOMContentLoaded', function() {
      new KeenSlider(".visual-slider .slider-wrap", {
        loop: true,
        // slides: {
        //   perView: 1,
        //   spacing: 12,
        // },
        defaultAnimation: {
          duration: 2000,
        },
        detailsChanged: function(s) {
          s.slides.forEach(function(element, idx) {
            element.style.opacity = s.track.details.slides[idx].portion;
          });
        },
        renderMode: 'custom',
      }, [
        //  sliderDots,
        addActiveClass,
        function autoplay(slider) {
          sliderAutoplay(slider, 4000, false);
        }
      ]);
    });
  </script>
</div>