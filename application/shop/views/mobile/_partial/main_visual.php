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

// 새 목록 디자인(채용목록·인재목록에서 $list_v2 = true로 켬)
// 상단 바(로고·A.I 매칭·언어 스위치·프로필)는 _partial/main_visual_top_bar.php 에서 그린다
$list_v2 = !empty($list_v2);
?>
<style>
  /* 메인 비주얼 헤더 스타일 */
  .main-visual {
    height: 180px;
    overflow: hidden;
        margin-top: 40px;
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

  /* main.css 기본값 보정 (초기화 전 깜빡임/검은 배경 방지)
     - main.css: .slider-wrap 배경 #000, 슬라이드 opacity 0 (KeenSlider 초기화 후에야 보임)
     - 첫 슬라이드는 처음부터 보이게 하고, 배경은 흰색으로 덮어씀 */
  .main-visual .visual-slider .slider-wrap {
    background: #fff;
  }

  .main-visual .visual-slider .slider-wrap .keen-slider__slide:first-child {
    opacity: 1;
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

  /* 상단 바 스타일은 _partial/main_visual_top_bar.php 에 있다 */
</style>

<!-- 공통 상단 바 (로고 · A.I 매칭 · 언어 스위치 · 프로필) -->
<?php include(APPPATH . 'views/mobile/_partial/main_visual_top_bar.php'); ?>

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
      <?php if ($list_v2) : ?>
        <a href="#advancedSearch" role="button" aria-haspopup="dialog" aria-controls="advancedSearch" class="btn js-toggle-panel sch-open"><?= t('상세검색') ?></a>
      <?php endif; ?>
      <?php // 주의: 아래 "지역을 선택해 주세요"는 JS가 '지역 미선택' 판단에 쓰는 값이라 t()로 감싸지 않는다 (새 목록 디자인에서는 화면에 보이지 않음) ?>
      <a href="#changeArea" role="button" aria-haspopup="dialog" aria-controls="changeArea" class="btn js-toggle-panel addrName" data-selected="false">지역을 선택해 주세요</a>
      <div id="selected-regions-list" data-has-regions="false"></div>
      <?php if ($list_v2) : ?>
        <div class="sel-row" id="selRow"></div>
      <?php endif; ?>
      <a href="#advancedSearch" role="button" aria-haspopup="dialog" aria-controls="advancedSearch" class="btn-option js-toggle-panel"><span class="sr-text"><?= t('상세검색 열기') ?></span></a>
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

    /* 지역 태그 개별 삭제(X) 버튼 */
    .current-area .region-tag-remove {
      background: none;
      border: none;
      padding: 0 0 0 2px;
      font-size: 1.4rem;
      line-height: 1;
      color: #999;
      cursor: pointer;
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

<!-- 고정 검색 바 아래 빈 칸: 배너가 숨겨진 페이지(2페이지 이상)에서만 바 높이만큼 확보 -->
<div id="search-bar-spacer" style="height:0;"></div>

<div class="main-visual"<?= (!$list_v2 && isset($initial_page) && $initial_page > 1) ? ' style="display:none;"' : '' ?>>
  <div class="visual-slider">
    <?php if ($list_v2) : ?>
      <div class="mvb-track">
        <?php foreach ($banner_list as $banner) : ?>
          <?php if ($banner["bn_link_yn"] == "Y") : ?>
            <a class="mvb-slide" href="<?= str_replace("community", "setting", $banner["bn_link"]) ?>" target="<?= ($banner["bn_link_type"] == "_blank") ? "_self" : $banner["bn_link_type"] ?>"><img src="<?= $banner["bg_img"] ?>" alt="<?= t('메인 배너 이미지') ?>"></a>
          <?php else : ?>
            <div class="mvb-slide"><img src="<?= $banner["bg_img"] ?>" alt="<?= t('메인 배너 이미지') ?>"></div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
      <?php if (count($banner_list) > 1) : ?>
        <span class="mvb-dots" aria-hidden="true"><?php for ($dot_idx = 0; $dot_idx < count($banner_list); $dot_idx++) : ?><i<?= ($dot_idx === 0) ? ' class="on"' : '' ?>></i><?php endfor; ?></span>
      <?php endif; ?>
    <?php else : ?>
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
                <img src="<?= $banner["bg_img"] ?>" alt="<?= t('메인 배너 이미지') ?>">
              </a>
            <?php else : ?>
              <img src="<?= $banner["bg_img"] ?>" alt="<?= t('메인 배너 이미지') ?>">
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
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

            // 지역명은 textContent로 넣어 저장값의 HTML 주입을 막음
            var tagText = document.createElement('span');
            tagText.textContent = region;
            tag.appendChild(tagText);

            // 개별 삭제(X): 같은 index의 지역을 모든 데이터 소스에서 함께 제거
            var removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'region-tag-remove';
            removeBtn.setAttribute('aria-label', (typeof window.t === 'function') ? window.t('지역 삭제') : '지역 삭제');
            removeBtn.setAttribute('data-index', index);
            removeBtn.textContent = '×';
            removeBtn.addEventListener('click', function(e) {
              e.stopPropagation(); // 태그 클릭(팝업 열기)으로 번지지 않게 차단
              window.removeSelectedRegion(index);
            });
            tag.appendChild(removeBtn);

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

    // 지역 태그 X 버튼 삭제: selectedRegions / search_addr / searchAreaStr를 같은 index로 함께 제거하고
    // 팝업 상태, 상세검색 상태, sessionStorage, 목록을 모두 맞춘다 (데이터 정합성)
    window.removeSelectedRegion = function(index) {
      var regions = (window.selectedRegions || []).slice();
      if (index < 0 || index >= regions.length) return;

      // searchAreaStr([{addr, step}]) 파싱, 실패 시 빈 배열
      var searchArea = [];
      try {
        searchArea = JSON.parse(window.searchAreaStr || '[]') || [];
      } catch (e) {
        searchArea = [];
      }

      // 지역명 배열과 주소 배열을 같은 index에서 제거
      regions.splice(index, 1);
      if (index < searchArea.length) {
        searchArea.splice(index, 1);
      }
      var addrs = searchArea.map(function(area) {
        return area.addr;
      });

      // 전역 상태 반영
      window.selectedRegions = regions;
      if (typeof selectedRegions !== 'undefined') selectedRegions = regions;
      search_addr = addrs;
      searchAreaStr = JSON.stringify(searchArea);
      addrName = regions.length > 0 ? regions.join(', ') : '지역을 선택해 주세요';
      $('.addrName').text(addrName).attr('data-selected', regions.length > 0 ? 'true' : 'false');

      // 지역 팝업(.div2 .selbtn, li.addr_on, sessionStorage, 백업) 상태 동기화
      if (typeof window.setAddrPopupSelection === 'function') {
        window.setAddrPopupSelection(regions, searchArea);
      }

      // 상세검색 칩/카운터, 저장된 검색 조건 동기화 (이전 상태로 되살아나지 않도록 백업 폐기)
      window.backupSearchState = null;
      if (typeof window.updateSelectedRegionsDisplay === 'function') {
        window.updateSelectedRegionsDisplay();
      }
      if (typeof window.initCategory === 'function') {
        window.initCategory();
      }
      if (typeof window.initTabUi === 'function') {
        window.initTabUi();
      }
      if (typeof window.saveSearchStateToStorage === 'function') {
        window.saveSearchStateToStorage();
      }

      // 태그 UI 재렌더 (0개면 "지역을 선택해 주세요" 버튼 복귀)
      updateSelectedRegionsTags();

      // 남은 조건으로 목록 재조회
      if (typeof window.list_page !== 'undefined') window.list_page = 1;
      // 목록을 응답으로 통째로 교체하는 화면(채용목록·인재목록, __addrPopupKeepListOnApply=true)은 미리 비우지 않는다
      // (비우면 응답이 올 때까지 페이지 번호만 보이고 목록이 빈 화면이 된다)
      if (!window.__addrPopupKeepListOnApply && typeof window.list_id !== 'undefined' && window.jQuery) window.jQuery(window.list_id).html('');
      if (typeof window.getBoardList === 'function') window.getBoardList('Y');
    };

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
      // 목록을 응답으로 통째로 교체하는 화면(__addrPopupKeepListOnApply=true)은 미리 비우지 않는다 (빈 화면 깜빡임 방지)
      if (!window.__addrPopupKeepListOnApply && typeof window.list_id !== 'undefined' && window.jQuery) {
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

    // 고정 검색 바 아래 빈 칸(spacer) 높이 동기화
    // 배너(.main-visual)가 숨겨진 페이지(2페이지 이상)에서만 바 높이만큼 spacer를 열어 목록이 가려지지 않게 함
    (function() {
      var bar = document.querySelector('.search-option');
      var spacer = document.getElementById('search-bar-spacer');
      var banner = document.querySelector('.main-visual');
      var categoriesEl = bar ? bar.querySelector('.categories') : null;
      if (!bar || !spacer || !banner) return;

      function syncSearchBarSpacer() {
        var bannerHidden = banner.style.display === 'none';
        // 배너가 보일 때: 기존 설계상 .current-area 높이만큼은 배너가 가려주므로,
        // 필터 태그(.categories)가 늘어난 높이만큼만 추가로 밀어내서 배너가 덜 가려지게 보정
        // 배너가 숨겨진 페이지(목록 2페이지 이상): 바 전체 높이만큼 밀어내야 목록이 안 가려짐
        var categoriesHeight = categoriesEl ? categoriesEl.offsetHeight : 0;
        spacer.style.height = (bannerHidden ? bar.offsetHeight : categoriesHeight) + 'px';
      }

      // 태그가 추가/삭제되어 바 높이가 바뀌면 자동 반영
      if (window.ResizeObserver) {
        new ResizeObserver(syncSearchBarSpacer).observe(bar);
      }
      // 페이지 전환으로 배너가 show/hide 되면 반영 (jQuery toggle은 inline style을 바꿈)
      new MutationObserver(syncSearchBarSpacer).observe(banner, { attributes: true, attributeFilter: ['style'] });
      syncSearchBarSpacer();
    })();

    // KeenSlider 초기화
    <?php if (!$list_v2) : ?>
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
    <?php endif; ?>
  </script>
</div>

<!-- A.I 매칭 광고성 동의 바텀시트·약관 패널: 상단 바 partial 에서 함께 include (_partial/main_visual_ai_consent.php) -->