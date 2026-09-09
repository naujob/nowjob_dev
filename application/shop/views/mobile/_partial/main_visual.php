<?php
// 배너 목록 조회
if (!isset($banner_list)) {
  $banner_list = getBannerList("BN00010001");
}
$banner = null;
if (count($banner_list) > 0) {
  $banner = $banner_list[0];
}

// 현재 페이지 감지 (recruit vs resume)
$current_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
$is_recruit_page = (strpos($current_uri, '/recruit/') !== false);
$is_resume_page = (strpos($current_uri, '/resume/') !== false);

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
?>
<style>
  /* 메인 비주얼 헤더 스타일 */
  .main-visual {
    height: 180px;
    overflow: hidden;
        margin-top: 40px;
  }

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

  /* 지역 선택 버튼: 지역 태그가 없을 때만 표시 */
  .search-option .current-area .btn.addrName {
    color: #aaa;
    font-size: 1.2rem;
    font-weight: 400;
    background-image: none;
    display: inline-block;
    padding-left: 10px;
  }

  /* 지역 태그가 있으면 버튼과 리스트 컨테이너 전체 숨김 */
  .current-area:has(#selected-regions-list[data-has-regions="true"]) .btn.addrName {
    display: none;
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
  <div class="logo">
    <img src="/resource/shop/mobile/img/banner/banner_nowjob_logo.svg" alt="NOW JOB">
  </div>

  <?php
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

  <div class="profile-menu" data-badge="<?= $has_new_match ? 'true' : 'false' ?>">
    <a href="<?= $match_url ?>" id="aiMatchLink" class="ai-match-label" data-agree-ad="<?= $mm_agree_ad ?>" data-match-url="<?= $match_url ?>">A.I 매칭리스트</a>
    <a href="<?= $profile_url ?>" class="profile-icon" aria-label="프로필">
      <img src="/resource/shop/mobile/img/mypage_ic.png" alt="프로필">
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

<!-- 상세 검색 옵션 -->
<div class="search-option has-option">
  <style>
    .search-option {
      position: fixed;
      top: 3.5rem;
      left: 0;
      right: 0;
      width: 100%;
      z-index: 9;
      background-color: #fff;
    }

    .search-option.has-option .btn-option {
      top: 1.3rem;
    }
  </style>
  <!-- 지역검색 -->
  <div class="current-area">
    <div class="container">
      <a href="#changeArea" role="button" aria-haspopup="dialog" aria-controls="changeArea" class="btn js-toggle-panel addrName" data-selected="false">지역을 선택해 주세요</a>
      <div id="selected-regions-list" data-has-regions="false"></div>
      <a href="#advancedSearch" role="button" aria-haspopup="dialog" aria-controls="advancedSearch" class="btn-option js-toggle-panel"><span class="sr-text">상세검색 열기</span></a>
    </div>
  </div>
  <style>
    /* 지역 검색 영역 */
    .current-area {

      border-bottom: 1px solid #999;
      padding: 10px 0px;
      position: relative;
      z-index: -1;
    }

    .current-area .container {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      align-items: center;
      justify-content: space-between;
    }

    /* 지역 태그 스타일 */
    .current-area .region-tag {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      border: 1px solid #ddd;
      border-radius: 20px;
      padding: 6px 12px;
      font-size: 1.2rem;
      color: #333;
      white-space: nowrap;
      cursor: pointer;
    }

    .current-area .region-tag:hover {
      background-color: #f5f5f5;
      border-color: #999;
    }

    .current-area .close-btn {
      display: none;
    }

    .search-option.has-option .btn-option {
      top: auto;
      position: static;
      margin-right: 10px;
    }

    #selected-regions-list {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }
  </style>
  <!-- 선택된 카테고리 스타일 (공통) -->
  <style>
    /* #tab_list 내 검색 조건 태그 스타일 */


    .search-option .categories .container {
      display: flex;
      flex-wrap: wrap;

      /* padding: 0 1.25rem; */
    }

    .search-option .categories {
      height: auto;

    }

    /* #tab_list에 내용이 있을 때만 border 표시 */
    .search-option .categories.has-items {
      border-bottom: 1px solid #999;

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
      padding: 15px 10px;
      font-size: 1.2rem;
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

    /* has-items 클래스가 없을 때 .cate 텍스트 숨김 */
    .search-option .categories.hide-cate .filter-tag-item .cate {
      display: none;
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

    /* #tab_list 내 요소들 */
    #tab_list {
      pointer-events: auto;
    }

    #tab_list .filter-tag-item {
      pointer-events: auto;
    }

    /* × 버튼 클릭 활성화 */
    #tab_list .filter-tag-item::after {
      pointer-events: auto;
      cursor: pointer;
    }
  </style>

  <!-- 선택된 카테고리 (recruit 페이지) -->
  <?php if ($is_recruit_page) : ?>
    <div class="categories recruit-filter-tags">
      <div class="container" id="tab_list">

      </div>
    </div>
  <?php endif; ?>

  <!-- 선택된 카테고리 (resume 페이지) -->
  <?php if ($is_resume_page) : ?>
    <div class="categories resume-filter-tags">
      <div class="container" id="tab_list">

      </div>
    </div>
  <?php endif; ?>
</div>

<div class="main-visual"<?= (isset($initial_page) && $initial_page > 1) ? ' style="display:none;"' : '' ?>>
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
    // ========================================
    // 1️⃣  유틸리티/헬퍼 함수들
    // ========================================

    // 고급 검색 상태 저장을 위한 고유 키 생성 함수
    function getAdvancedSearchStorageKey() {
      var userId = window.currentUserId || "guest";
      var scope = window.location.pathname.indexOf('/resume/') !== -1 ? 'resume' : 'recruit';
      return "jobListAdvancedSearch_" + scope + "_" + userId;
    }
    window.getAdvancedSearchStorageKey = getAdvancedSearchStorageKey;

    // 지역 필터 상태 초기화 함수: 지역 관련 전역 상태와 UI를 모두 초기화
    function clearAreaFilterState() {
      // 전역 상태 초기화
      window.selectedRegions = [];
      if (typeof window.addrName !== 'undefined') window.addrName = '';
      if (typeof window.search_addr !== 'undefined') window.search_addr = [];
      if (typeof window.searchAreaStr !== 'undefined') window.searchAreaStr = JSON.stringify([]);

      // 팝업 UI 정리
      Array.prototype.forEach.call(document.querySelectorAll('.selbtn'), function(el) {
        el.remove();
      });
      Array.prototype.forEach.call(document.querySelectorAll('li.addr_on'), function(el) {
        el.classList.remove('addr_on');
      });

      // 버튼 상태 초기화 (initAddrNameButton 함수와 동일한 로직)
      var addrBtn = document.querySelector('.addrName');
      if (addrBtn) {
        addrBtn.textContent = '지역을 선택해 주세요';
        initAddrNameButton(); // data-selected 속성 설정
      }

      // selected-regions-list 초기화
      updateSelectedRegionsTags();
    }

    // 검색 조건 초기화 함수: filterKey에 따라 관련된 폼 컨트롤과 전역 상태를 초기화
    function clearFormControlsByFilterKey(filterKey) {
      var inputNameMap = {
        category: 'category[]',
        work_type: 'work_type[]',
        week: 'week[]',
        price_type: 'price_type[]',
        sex: 'sex[]'
      };

      if (inputNameMap[filterKey]) {
        Array.prototype.forEach.call(document.querySelectorAll('input[name="' + inputNameMap[filterKey] + '"]'), function(input) {
          input.checked = false;
          input.dispatchEvent(new Event('change', {
            bubbles: true
          }));
        });
      }

      if (filterKey === 'week') {
        var workingDay = document.querySelector('input[name="workingDay"]');
        if (workingDay) workingDay.checked = false;
      }

      if (filterKey === 'price_type') {
        var priceNegotiable = document.querySelector('input[name="priceNegotiable"]');
        if (priceNegotiable) priceNegotiable.checked = false;
      }

      if (filterKey === 'age') {
        var ageRangeSelect = document.getElementById('ageRangeSelect');
        var sageInput = document.getElementById('sageInput') || document.querySelector('input[name="sage"]');
        var eageInput = document.getElementById('eageInput') || document.querySelector('input[name="eage"]');
        var ageAnyCheckbox = document.querySelector('input[name="age_any"]');

        if (ageRangeSelect) ageRangeSelect.value = '';
        if (sageInput) sageInput.value = '';
        if (eageInput) eageInput.value = '';
        if (ageAnyCheckbox) ageAnyCheckbox.checked = true;
      }

      if (filterKey === 'area') {
        clearAreaFilterState();
      }
    }

    // 검색 조건 초기화 함수: filterKey에 따라 관련된 폼 컨트롤과 전역 상태를 초기화
    function clearSearchArrayByFilterKey(filterKey) {
      if (typeof window.search_array === 'undefined') return;

      if (filterKey === 'age') {
        window.search_array.sage = '';
        window.search_array.eage = '';
      } else if (filterKey === 'area') {
        clearAreaFilterState();
      } else if (typeof window.search_array[filterKey] !== 'undefined') {
        window.search_array[filterKey] = '';
      }
    }

    // 지금 클릭한 태그가 어떤 필터인지 알아내는 key 값을 리턴
    function inferFilterKeyFromTag(filterTag) {
      var filterKey = filterTag.getAttribute('data-filter-key');
      if (filterKey) return filterKey;

      var tagItems = filterTag.parentNode ? filterTag.parentNode.querySelectorAll('label.btn-category') : [];
      var tagIndex = Array.prototype.indexOf.call(tagItems, filterTag);
      var tabInfo = (typeof window.tab_list !== 'undefined' && window.tab_list[tagIndex]) ? window.tab_list[tagIndex] : null;
      var tabLabel = tabInfo && tabInfo.df ? tabInfo.df : '';

      if (tabInfo && tabInfo.key) return tabInfo.key;
      if (tabLabel.indexOf('지역') !== -1) return 'area';
      if (tabLabel.indexOf('업직종') !== -1 || tabLabel.indexOf('카테고리') !== -1) return 'category';
      if (tabLabel.indexOf('근무형태') !== -1) return 'work_type';
      if (tabLabel.indexOf('근무요일') !== -1) return 'week';
      if (tabLabel.indexOf('급여') !== -1) return 'price_type';
      if (tabLabel.indexOf('성별') !== -1) return 'sex';
      if (tabLabel.indexOf('나이') !== -1) return 'age';

      return '';
    }

    // 현재 검색 조건을 세션 스토리지에 저장하는 함수
    function persistFilterState() {
      if (typeof window.saveSearchStateToStorage === 'function') {
        window.saveSearchStateToStorage();
      } else if (typeof saveSearchStateToStorage === 'function') {
        saveSearchStateToStorage();
      }
    }

    // ========================================
    // 2️⃣  UI 초기화 함수들
    // ========================================

    // "지역을 선택해주세요" 버튼 초기화
    function initAddrNameButton() {
      var addrBtn = document.querySelector('.addrName');
      if (addrBtn) {
        var text = addrBtn.textContent.trim();
        // "전체" 또는 빈 값이면 미선택 상태
        if (text === '전체' || text === '' || text === '지역을 선택해 주세요') {
          addrBtn.setAttribute('data-selected', 'false');
        } else {
          // 지역이 선택되어 있으면 선택 상태
          addrBtn.setAttribute('data-selected', 'true');
        }
      }
    }

    // 선택된 지역 태그 표시 함수
    function updateSelectedRegionsTags() {
      var regionsList = document.getElementById('selected-regions-list');
      var addrBtn = document.querySelector('.addrName');

      if (!regionsList) return;

      // MutationObserver 일시 중지 (무한 루프 방지)
      window.skipAddrNameUpdate = true;

      // 먼저 기존 태그 모두 제거
      regionsList.innerHTML = '';

      // window.selectedRegions 배열을 기반으로 태그 생성 (단일 소스)
      var regions = window.selectedRegions || [];

      if (Array.isArray(regions) && regions.length > 0) {
        regions.forEach(function(region, index) {
          if (region && region !== '지역을 선택해 주세요') {
            var tag = document.createElement('div');
            tag.className = 'region-tag';
            tag.innerHTML = region;

            // 지역 태그 클릭 시 팝업 열기
            tag.addEventListener('click', function(e) {
              e.stopPropagation();
              var changeAreaBtn = document.querySelector('a.js-toggle-panel[aria-controls="changeArea"]');
              if (changeAreaBtn) {
                changeAreaBtn.click();
              }
            });
            regionsList.appendChild(tag);
          }
        });
        // 지역 태그가 있으면 data-has-regions을 true로 설정
        regionsList.setAttribute('data-has-regions', 'true');
        // 버튼 숨김
        if (addrBtn) {
          addrBtn.style.display = 'none';
        }
      } else {
        // 지역 태그가 없으면 data-has-regions을 false로 설정
        regionsList.setAttribute('data-has-regions', 'false');
        // 버튼 표시
        if (addrBtn) {
          addrBtn.textContent = '지역을 선택해 주세요';
          addrBtn.style.display = 'inline-block';
        }
      }

      // MutationObserver 재개
      setTimeout(function() {
        window.skipAddrNameUpdate = false;
      }, 100);
    }

    // #tab_list 내용 변경 감지하여 border 표시/숨김
    function updateCategoriesBorder() {
      var tabList = document.getElementById('tab_list');
      var categoriesSection = document.querySelector('.search-option .categories');
      if (!tabList || !categoriesSection) return;

      // tab_list에 filter-tag-item.on이 하나라도 있으면 has-items 클래스 추가, hide-cate 제거
      if (tabList.querySelector('.filter-tag-item.on')) {
        categoriesSection.classList.add('has-items');
        categoriesSection.classList.remove('hide-cate');
      } else {
        categoriesSection.classList.remove('has-items');
        categoriesSection.classList.add('hide-cate');
      }
    }

    // ========================================
    // 3️⃣  필터 제거 함수 (초기화 함수 사용)
    // ========================================

    // 필터 태그 제거 함수
    function removeFilterTag(filterKey) {
      if (!filterKey) return;

      clearFormControlsByFilterKey(filterKey);
      clearSearchArrayByFilterKey(filterKey);

      if (typeof window.initCategory === 'function') {
        window.initCategory();
      } else if (typeof initCategory === 'function') {
        initCategory();
      }

      persistFilterState();

      if (typeof window.initTabUi === 'function') {
        window.initTabUi();
      } else if (typeof initTabUi === 'function') {
        initTabUi();
      }

      if (typeof window.updateSelectedRegionsTags === 'function') {
        window.updateSelectedRegionsTags();
      }
      if (typeof window.updateSelectedRegionsDisplay === 'function') {
        window.updateSelectedRegionsDisplay();
      }

      if (typeof window.list_page !== 'undefined') {
        window.list_page = 1;
      }
      if (typeof window.list_id !== 'undefined' && window.jQuery) {
        window.jQuery(window.list_id).html('');
      }
      if (typeof window.getBoardList === 'function') {
        window.getBoardList('Y');
      } else if (typeof getBoardList === 'function') {
        getBoardList('Y');
      }

      updateCategoriesBorder();
    }
    window.removeFilterTag = removeFilterTag;

    // ========================================
    // 4️⃣  초기 상태 설정 (DOMContentLoaded 전)
     //  페이지가 열리면 현재 데이터에 맞게 화면 설정
    // ========================================

    // 페이지 로드 시 초기화
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', function() {
        initAddrNameButton();
        updateSelectedRegionsTags(); // 선택된 지역 태그 초기 표시
        updateCategoriesBorder();
      }, { once: true });
    } else {
      initAddrNameButton();
      updateSelectedRegionsTags();
      updateCategoriesBorder();
    }

    // ========================================
    // 5️⃣  이벤트 리스너 등록
    // ========================================

    // 팝업 닫힐 때 상태 확인
    document.addEventListener('click', function(e) {
      if (e.target.classList && e.target.classList.contains('js-toggle-panel') &&
        e.target.getAttribute('aria-controls') === 'changeArea') {
        setTimeout(function() {
          initAddrNameButton();
          if (!window.skipAddrNameUpdate) {
            updateSelectedRegionsTags();
          }
        }, 400);
      }
    });

    // MutationObserver: .addrName 텍스트 변경 감지
    var addrBtn = document.querySelector('.addrName');
    if (addrBtn) {
      var observer = new MutationObserver(function(mutations) {
        if (!window.skipAddrNameUpdate) {
          updateSelectedRegionsTags();
        }
      });
      observer.observe(addrBtn, {
        childList: true,
        characterData: true,
        subtree: true
      });
    }

    // MutationObserver: #tab_list 변경 감지
    var tabList = document.getElementById('tab_list');
    if (tabList) {
      var tabObserver = new MutationObserver(function(mutations) {
        updateCategoriesBorder();
      });
      tabObserver.observe(tabList, {
        childList: true,
        subtree: true
      });
    }

    // 카테고리 필터 태그 클릭 핸들러
    document.addEventListener('click', function(e) {
      var filterTag = e.target.closest ? e.target.closest('label.filter-tag-item.on.btn-category') : null;
      if (!filterTag) return;

      e.preventDefault();
      e.stopPropagation();

      removeFilterTag(inferFilterKeyFromTag(filterTag));
    }, true);

    // KeenSlider 초기화
    window.addEventListener('DOMContentLoaded', function() {
      new KeenSlider(".visual-slider .slider-wrap", {
        loop: true,
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
        addActiveClass,
        function autoplay(slider) {
          sliderAutoplay(slider, 4000, false);
        }
      ]);
    });
  </script>
</div>

<!-- A.I 매칭 광고성 정보 수신 동의 바텀시트 -->
<?php if ($this->user->getUserType() == "MEM" || $this->user->getUserType() == "COMP") : ?>
<?php
require_once(get_base_url() . '/application/shop/views/mobile/_partial/bottom_sheet_template.php');
$this->load->model('settingmodel', 'setting_model');
$policy5 = $this->setting_model->getSettingInfo(["st_group" => "이용약관", "st_key" => "adv_m"]);
$aiMatchConsentContent = '
  <p class="ai-match-desc">서비스 이용을 위해 동의해 주세요.<br>A.I매칭 서비스를 위해 고객 동의가 필요합니다.</p>
  <label class="ai-match-agree-check" style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
    <input type="checkbox" id="rd_agree_ad" name="rd_agree_ad" value="1" style="width: 18px; height: 18px; cursor: pointer; accent-color: #17B890; margin: 0;">
    <div class="ai-match-terms-link" style="display: flex; align-items: center; justify-content: space-between; gap: 12px; flex: 1;">
      <a href="javascript:void(0);" role="button" onclick="openAdAgreeTermsPanel();" style="color: #333; text-decoration: none; flex: 1; font-size: 13px;">광고성 정보 알림 수신 동의</a>
      <i class="fa fa-angle-right" style="color: #999; flex-shrink: 0;"></i>
    </div>
  </label>';
renderSelectBottomSheet(
  'adConsentSheet', 'A.I 매칭', $aiMatchConsentContent,
  '닫기', 'btnAdConsentClose', "closePanel(document.getElementById('adConsentSheet'));",
  '동의하기', 'btnAdConsentAgree', 'submitAdAgree();'
);
?>

<!-- 광고성 정보 알림 수신 동의 약관 상세 -->
<div id="adConsentTermsPanel" class="layer-panel card-type" tabindex="-1">
  <div class="panel-container">
    <div class="panel-header">
      <h2 class="panel-title">광고성 정보 알림 수신 동의</h2>
    </div>
    <div class="panel-body">
      <div class="panel-content" style="padding: 20px;">
        <div class="terms-wrap"><?= isset($policy5["adv_m"]) ? $policy5["adv_m"] : '' ?></div>
      </div>
    </div>
    <div class="panel-footer">
      <div class="sticky-bottom view-footer">
        <button type="button" class="btn btn-primary" onclick="checkAdAgreeTerms();">확인</button>
      </div>
    </div>
  </div>
</div>

<style>
  /* 동의 바텀시트 전용 스타일 (신규, 다른 영역에 영향 없음) */
  .ai-match-desc { margin-bottom: 20px; color: #666; line-height: 1.5; }
  .ai-match-agree-check { display: flex; align-items: center; gap: 8px; }
  .ai-match-terms-link { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex: 1; }
  .ai-match-terms-link a { color: #333; text-decoration: none; flex: 1; }
  .ai-match-terms-link i { color: #999; flex-shrink: 0; }
  .bottom-sheet-content .bottom-sheet-content-body {
    padding: 0px 20px 10px 0px;
        display: flex;
    flex-direction: column;
    gap: 11px;
  }
</style>

<script>
  // A.I 매칭 클릭 시 광고성 정보 동의 여부 확인 후 가로채기
  (function() {
    var aiMatchLink = document.getElementById('aiMatchLink');
    if (!aiMatchLink) return;
    aiMatchLink.addEventListener('click', function(e) {
      var agreeAd = aiMatchLink.getAttribute('data-agree-ad');
      if (typeof BrowserLogger !== 'undefined') {
        BrowserLogger.info('A.I 매칭 링크 클릭 | 동의 여부: ' + agreeAd);
      } else {
        console.log('A.I 매칭 링크 클릭 | 동의 여부:', agreeAd);
      }

      if (agreeAd === 'Y') {
        if (typeof BrowserLogger !== 'undefined') {
          BrowserLogger.info('이미 동의함 | 링크 이동');
        } else {
          console.log('이미 동의함 | 링크 이동');
        }
        return; // 이미 동의: 정상 이동
      }

      e.preventDefault();
      if (typeof BrowserLogger !== 'undefined') {
        BrowserLogger.info('동의 여부가 N | 팝업 오픈');
      } else {
        console.log('동의 여부가 N | 팝업 오픈');
      }

      var chk = document.getElementById('rd_agree_ad');
      if (chk) chk.checked = true; // 체크박스 기본 체크

      var sheet = document.getElementById('adConsentSheet');
      if (sheet && typeof openPanel === 'function') {
        openPanel(sheet);
        if (typeof BrowserLogger !== 'undefined') {
          BrowserLogger.info('동의 팝업 오픈 완료');
        } else {
          console.log('동의 팝업 오픈 완료');
        }
      }
    });
  })();

  // 약관 상세 패널 열기 (바텀시트는 열려있고 패널만 오픈)
  function openAdAgreeTermsPanel() {
    var panel = document.getElementById('adConsentTermsPanel');
    if (panel && typeof window.openPanel === 'function') {
      window.openPanel(panel);
    }
  }

  // 약관 상세 패널의 "확인" 클릭 시 체크박스 동의 처리 및 패널 닫기
  function checkAdAgreeTerms() {
    document.getElementById('rd_agree_ad').checked = true;
    var panel = document.getElementById('adConsentTermsPanel');
    if (panel) {
      if (typeof window.closePanel === 'function') {
        window.closePanel(panel);
      } else {
        panel.classList.remove('on');
      }
    }
  }

  // 동의하기 클릭 시 서버에 mm_agree_ad=Y 저장 후 매칭 페이지 이동
  function submitAdAgree() {
    if (typeof BrowserLogger !== 'undefined') {
      BrowserLogger.info('동의하기 버튼 클릭');
    } else {
      console.log('동의하기 버튼 클릭');
    }

    var chk = document.getElementById('rd_agree_ad');
    if (!chk || !chk.checked) {
      if (typeof BrowserLogger !== 'undefined') {
        BrowserLogger.info('체크박스 미체크 상태 | 알림 표시');
      } else {
        console.log('체크박스 미체크 상태 | 알림 표시');
      }
      commonAlert('광고성 정보 알림 수신에 동의해주세요.', function() {});
      return;
    }

    if (typeof BrowserLogger !== 'undefined') {
      BrowserLogger.info('체크박스 체크됨 | 서버 요청 시작');
    } else {
      console.log('체크박스 체크됨 | 서버 요청 시작');
    }

    var matchUrl = document.getElementById('aiMatchLink').getAttribute('data-match-url');
    if (typeof BrowserLogger !== 'undefined') {
      BrowserLogger.info('매칭 URL: ' + matchUrl);
    } else {
      console.log('매칭 URL:', matchUrl);
    }

    comAjax('/setting_proc/agree_ad', {}, function(ret) {
      if (typeof BrowserLogger !== 'undefined') {
        BrowserLogger.info('서버 응답 | 코드: ' + ret.code);
      } else {
        console.log('서버 응답 | 코드:', ret.code);
      }

      if (ret.code == '00') {
        if (typeof BrowserLogger !== 'undefined') {
          BrowserLogger.info('동의 저장 성공 | 매칭 페이지로 이동');
        } else {
          console.log('동의 저장 성공 | 매칭 페이지로 이동');
        }
        location.href = matchUrl;
      } else {
        if (typeof BrowserLogger !== 'undefined') {
          BrowserLogger.info('동의 저장 실패 | 메시지: ' + ret.msg);
        } else {
          console.error('동의 저장 실패 | 메시지:', ret.msg);
        }
        commonAlert(ret.msg, function() {});
      }
    });
  }
</script>
<?php endif; ?>