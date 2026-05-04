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

  .main-visual-header .profile-icon img {
    width: 100%;
    height: 100%;
    object-fit: contain;
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

  /* 지역 선택 버튼: 초기(선택안함) 상태일 때 placeholder 스타일 */
  .search-option .current-area .btn.addrName[data-selected="false"] {
    color: #aaa;
    font-size: 1rem;
    font-weight: 400;
    background-image: none;
    /* 위치 아이콘, 캐럿 제거 */
    display: inline-block;
  }

  /* 지역 선택 버튼: 선택된 상태일 때 숨김 (태그로 표시됨) */
  .search-option .current-area .btn.addrName[data-selected="true"] {
    display: none;
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

<!-- 상세 검색 옵션 -->
<div class="search-option has-option">
  <style>
    .search-option.has-option .btn-option {
      top: 0.975rem;
    }
  </style>
  <!-- 지역검색 -->
  <div class="current-area">
    <div class="container">
      <a href="#changeArea" role="button" aria-haspopup="dialog" aria-controls="changeArea" class="btn js-toggle-panel addrName" data-selected="false">지역을 선택해 주세요</a>
    </div>
  </div>
  <!-- 선택된 지역 태그 -->
  <div class="selected-regions">
    <style>
      .search-option .selected-regions {
        padding: 0.5rem 0rem;
        background: #fff;
      }

      .search-option .selected-regions .container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
      }

      .search-option .selected-regions .region-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: #f5f5f5;
        border: 1px solid #ddd;
        border-radius: 20px;
        padding: 6px 12px;
        font-size: 13px;
        color: #333;
        white-space: nowrap;
      }

      .search-option .selected-regions .region-tag .close-btn {
        cursor: pointer;
        font-weight: bold;
        color: #aaa;
        margin-left: 4px;
      }

      .search-option .selected-regions .region-tag .close-btn:hover {
        color: #666;
      }
    </style>
    <div class="container" id="selected-regions-list"></div>
  </div>
  <!-- 선택된 카테고리 -->
  <div class="categories">
    <style>
      /* #tab_list 내 검색 조건 태그 스타일 */


      .search-option .categories .container {
        display: flex;
        flex-wrap: wrap;

        /* padding: 0 1.25rem; */
      }

      .search-option .categories {
        height: auto;
        margin-bottom: 10px;
        border-bottom: 1px solid #999;
        border-top: 1px solid #999;
        padding: 0.4rem 0rem;
      }

      /* 검색 조건 탭 기본 스타일 */
      .search-option .categories .filter-tag-item {
        display: none;
      }

      /* active 클래스가 있을 때만 표시 */
      .search-option .categories .filter-tag-item.on {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background-color: #fff;
        border-radius: 20px;
        padding: 5px 10px;
        font-size: 13px;
        color: #333;
        font-weight: 400;
        white-space: nowrap;
      }

      /* 탭 내부 체크박스 숨김 */
      .search-option .categories .filter-tag-item input[type="checkbox"] {
        display: none;
      }

      /* 탭 내부 텍스트 표시 */
      .search-option .categories .filter-tag-item .cate {
        display: inline;
        color: #333;
      }

      /* 탭 닫기 아이콘 */
      .search-option .categories .filter-tag-item.on::after {
        content: '×';
        font-size: 10px;
        color: #e8dfdf;
        margin-left: 2px;
        line-height: 1.3;
        border-radius: 50%;
        border: 1px solid #999;
        width: 15px;
        height: 15px;
        text-align: center;
        background: #999;
        position: relative;
        top: 1.5px;
        cursor: pointer;
      }

      /* #tab_list 내 요소들 클릭 이벤트 무시 */
      #tab_list {
        pointer-events: none;
      }

      #tab_list .form-toggle-btn2 {
        pointer-events: none;
        cursor: default;
      }
    </style>
    <div class="container" id="tab_list">

    </div>
  </div>
  <!-- 상세 검색 옵션 버튼 -->
  <a href="#advancedSearch" role="button" aria-haspopup="dialog" aria-controls="advancedSearch" class="btn-option js-toggle-panel"><span class="sr-text">상세검색 열기</span></a>
</div>

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
    // 지역 선택 버튼 초기화: 초기값이 "전체"이면 placeholder 스타일로 변경
    function initAddrNameButton() {
      var addrBtn = document.querySelector('.addrName');
      if (addrBtn) {
        var text = addrBtn.textContent.trim();
        // "전체" 또는 빈 값이면 placeholder로 변경
        if (text === '전체' || text === '') {
          addrBtn.textContent = '지역을 선택해 주세요';
          addrBtn.setAttribute('data-selected', 'false');
          addrBtn.removeAttribute('data-selected');
          addrBtn.setAttribute('data-selected', 'false');
        } else {
          // 지역이 선택되어 있으면 기본 스타일 적용
          addrBtn.setAttribute('data-selected', 'true');
        }
      }
    }

    // 선택된 지역 태그 표시 함수
    function updateSelectedRegionsTags() {
      var addrBtn = document.querySelector('.addrName');
      var regionsList = document.getElementById('selected-regions-list');

      if (!regionsList) return;

      regionsList.innerHTML = '';

      if (addrBtn && addrBtn.getAttribute('data-selected') === 'true') {
        var regions = addrBtn.textContent.trim().split(',').map(function(r) {
          return r.trim();
        });

        regions.forEach(function(region, index) {
          if (region) {
            var tag = document.createElement('div');
            tag.className = 'region-tag';
            tag.innerHTML = region + ' <span class="close-btn">×</span>';

            // 닫기 버튼 클릭 핸들러
            var closeBtn = tag.querySelector('.close-btn');
            closeBtn.addEventListener('click', function(e) {
              e.stopPropagation();

              // 지역 선택 해제 (전체로 리셋)
              addrBtn.textContent = '지역을 선택해 주세요';
              addrBtn.setAttribute('data-selected', 'false');

              // 팝업 내의 선택된 지역 초기화
              var selbtnElements = document.querySelectorAll('.selbtn');
              selbtnElements.forEach(function(el) {
                el.remove();
              });

              // 전역 변수 초기화 (다른 스크립트와 동기화)
              if (typeof window.addrName !== 'undefined') {
                window.addrName = '';
              }
              if (typeof window.search_addr !== 'undefined') {
                window.search_addr = [];
              }
              if (typeof window.searchAreaStr !== 'undefined') {
                window.searchAreaStr = JSON.stringify([]);
              }

              // 팝업 내 addr_on 클래스 제거
              var addrOnElements = document.querySelectorAll('li.addr_on');
              addrOnElements.forEach(function(el) {
                el.classList.remove('addr_on');
              });

              // 태그 업데이트
              updateSelectedRegionsTags();

              // 페이지 검색 재실행 (필요시)
              if (typeof window.getBoardList === 'function') {
                window.getBoardList('Y');
              }
            });

            regionsList.appendChild(tag);
          }
        });
      }
    }

    // 페이지 로드 시 및 팝업 닫힐 때 초기화
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', function() {
        initAddrNameButton();
        updateSelectedRegionsTags();
      });
    } else {
      initAddrNameButton();
      updateSelectedRegionsTags();
    }

    // 팝업 닫힐 때도 상태 확인 (다른 스크립트가 text를 변경했을 수 있음)
    document.addEventListener('click', function(e) {
      if (e.target.classList && e.target.classList.contains('js-toggle-panel') &&
        e.target.getAttribute('aria-controls') === 'changeArea') {
        // 팝업 닫힐 때 (약 400ms 후) 상태 업데이트
        setTimeout(function() {
          initAddrNameButton();
          updateSelectedRegionsTags();
        }, 400);
      }
    });

    // MutationObserver로 .addrName 텍스트 변경 감지
    var addrBtn = document.querySelector('.addrName');
    if (addrBtn) {
      var observer = new MutationObserver(function(mutations) {
        updateSelectedRegionsTags();
      });
      observer.observe(addrBtn, {
        childList: true,
        characterData: true,
        subtree: true
      });
    }

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