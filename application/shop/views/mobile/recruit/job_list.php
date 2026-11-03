<?php
$banner_list = getBannerList("BN00302031");

// 사용자 주소 정보 로드
$addr_info = $this->user->getAddrInfo();
$mm_type = $this->user->getUserType();

// Controller에서 전달받은 포맷팅된 데이터 사용
// (이미 Controller의 job_list()에서 초기 데이터 로드 및 포맷팅됨)
$use_server_render = isset($use_server_render) ? $use_server_render : false;
$initial_recruits = isset($initial_recruits) ? $initial_recruits : ['list' => []];
$initial_total_count = isset($initial_total_count) ? $initial_total_count : 0;
$all_recruit_count = isset($all_recruit_count) ? $all_recruit_count : $initial_total_count;
$today_recruit_count = isset($today_recruit_count) ? $today_recruit_count : 0;
$hot_companies = isset($hot_companies) ? $hot_companies : ['chunks' => []];
$initial_page = isset($initial_page) ? $initial_page : 1;

?>
<?php if (!empty($initial_search_state)) { ?>
<script>
  // 서버가 목록에 적용한 검색조건(주소 검색조건 > 저장된 조건)을 상세검색 저장값으로 넣음
  // → 칩/체크박스 복원은 기존 흐름을 그대로 쓰고, 목록은 서버가 그린 것을 사용 (진입 시 재조회 없음)
  (function() {
    var initial = <?= json_encode($initial_search_state, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    window.SEARCH_STATE_APPLIED_BY_SERVER = true; // index_search.php의 상태 복원이 목록을 다시 조회하지 않게 함
    try {
      sessionStorage.setItem('selectedRegions_recruit', JSON.stringify(initial.state.selectedRegions));
      sessionStorage.setItem('searchAreaStr_recruit', initial.state.searchAreaStr);
      sessionStorage.setItem('addrName_recruit', initial.state.addrName);
      sessionStorage.setItem(initial.key, JSON.stringify(initial.state));
    } catch (e) {}
  })();
</script>
<?php } ?>
<?= getCachCss(RES_PATH . "/css/job_list.css") ?>
<style>
  /* 페이지 스타일은 job_list.css로 분리됨. 추가/오버라이드가 필요할 때만 여기에 작성 */

getCachCss</style>

<!-- Google AdSense 인피드 광고 스크립트 (비활성화) -->
<!-- <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3622369584322145"
  crossorigin="anonymous"></script> -->


<?php include_once(get_base_url() . '/application/shop/views/mobile/_partial/app_intercept_layer.php'); ?>
<div class="page-recruit">

  <!-- <a href="#alarmPanel" aria-haspopup="dialog" aria-controls="alarmPanel" class="btn-alarm js-toggle-panel">
      <span class="sr-text">미확인 알림메세지</span> -->
  <!-- 미확인 알림개수 -->
  <!-- <span class="cnt"><?= $g_alarm_cnt ?></span> -->

</div><!-- //header -->

<div id="jobPostPopup" class="job-post-popup" data-state="closed"></div>

<div class="page-content">
  <?php include(APPPATH . 'views/mobile/_partial/main_visual.php'); ?>

  <!-- 오늘 채용 현황 마퀴 배너 (1페이지에서만 노출) -->
  <?php
    $status_items = [
      ['label' => '', 'value' => date('Y-m-d')],
      ['label' => '전체채용공고', 'value' => number_format($all_recruit_count) . '개'],
      ['label' => '오늘등록공고', 'value' => number_format($today_recruit_count) . '건'],
    ];
    $status_items_html = '';
    foreach ($status_items as $item) {
      $status_items_html .= '<span class="marquee-item"><span class="label">' . $item['label'] . '</span>&nbsp;&nbsp;<span class="value">' . $item['value'] . '</span></span>';
    }
  ?>
  <div class="section recruit-status-banner" id="recruitStatusBanner"<?= ($initial_page > 1) ? ' style="display:none;"' : '' ?>>
    <div class="marquee-wrap">
      <div class="marquee-track">
        <?= $status_items_html ?>
        
      </div>
    </div>
  </div>

  <!-- 지금 뜨는 업체 -->
  <div class="section" id="hotListSection"<?= ($initial_page > 1) ? ' style="display:none;"' : '' ?>>
    <div class="container">
      <h2 class="secondary-title index" style="margin: 0 0px 1.1rem;">지금 뜨는 업체</h2>
      <div class="hot-list" style="display: flex; flex-direction: column;">
        <div class="slider-wrap keen-slider">
          <?php foreach ($hot_companies['chunks'] as $slideIndex => $chunk) : ?>
            <div class="keen-slider__slide">
              <div class="hot-grid">
                <?php foreach ($chunk as $row) : ?>
                  <?php if ($row !== null) : ?>
                    <?php $imgAttr = ($slideIndex === 0) ? 'src' : 'data-src'; ?>
                    <?php $lazyClass = ($slideIndex === 0) ? '' : 'lozad'; ?>
                    <a href="/recruit/company/<?= $row["mm_idx"] ?>" class="hot-item">
                      <div class="thumb">
                        <img <?= $imgAttr ?>="<?= $row['comp_img'] ?>" alt="" class="<?= $lazyClass ?>">
                        <p class="name" style="width: 104%;"><?= $row['comp_desc'] ?></p>
                      </div>
                    </a>
                  <?php else : ?>
                    <div class="hot-item hot-item--empty">
                      <div class="thumb"></div>
                    </div>
                  <?php endif; ?>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <!-- 슬라이더 dot 인디케이터 -->
        <div class="slider-dots" id="hotSliderDots"></div>
      </div>
    </div>
  </div>

  <div class="section is-last">
    <div class="container">
       <h2 class="secondary-title" style="margin: 0.3rem 0px 1.1rem;">채용공고</h2>
      <ol class="result-list result-list-card" id="ajaxList" data-page="<?= $initial_page ?>">
      <?php
        // 초기 공고 카드 (AJAX 페이지 이동/검색과 같은 파셜로 그림 — recruit_proc::getRecruitList(render=html))
        $card_list = ($use_server_render && isset($initial_recruits['list']) && is_array($initial_recruits['list'])) ? $initial_recruits['list'] : array();
        include(APPPATH . 'views/mobile/_partial/recruit_list_card.php');
      ?>
      </ol>
      <div id="ajaxEmpty" class="is-empty_v2" style="display:none;background:#F4F4F4;">
        <p>등록된 정보가 없습니다.</p>
      </div>
      <!-- 페이지네이션 버튼 -->
      <div id="paginationWrapper" class="pagination-wrapper" data-pagination="ready"></div>
    </div>
  </div>
  <!-- <div class="list-action">
    <a href="/recruit/job_map" class="btn-map"><span class="sr-text">지도보기</span></a>
  </div> -->

</div><!-- //page-content -->
<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/main_menu.php'); ?>
</div>

<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/popup_alarm.php'); ?>

<!-- 온보딩 튜토리얼 팝업 -->
<div id="tutorial-popup" style="display:none;">
  <span class="tutorial-skip-btn" onclick="window.closeTutorialPopup();">건너뛰기</span>

  <!-- 페이지 4 -->
  <div class="tutorial-page-4-wrapper" style="display:none;">
    <div class="tutorial-search" role="img" aria-label="검색"></div>
    <p class="tutorial-search-text">나에게 딱 맞는&nbsp;<span class="highlight-text-arrow">상세검색</span><br>지역 부터 업종까지 다양한 설정이 가능해요</p>
  </div>

  <div class="tutorial-container">
    <p class="tutorial-text"></p>
    <div class="tutorial-image" role="img" aria-label="터치 안내"></div>
    <div class="tutorial-indicators">
      <span class="indicator active" data-page="0"></span>
      <span class="indicator" data-page="1"></span>
      <span class="indicator" data-page="2"></span>
      <span class="indicator" data-page="3"></span>
    </div>
  </div>
  <!-- 페이지 1 -->
  <div class="tutorial-recruit page-1" role="img" aria-label="채용정보"></div>

  <p class="tutorial-arrow-text page-1">나에게 딱맞는!&nbsp;<span class="highlight-text-arrow">채용정보</span></p>

  <!-- 페이지 2 -->
  <div class="tutorial-resume page-2" role="img" aria-label="이력서" style="display:none;"></div>

  <p class="tutorial-arrow-text-page2 page-2" style="display:none;">우리 회사에 딱맞는!&nbsp;<span class="highlight-text-arrow">인재정보</span></p>

  <!-- 페이지 3 -->
  <div class="tutorial-map page-3" role="img" aria-label="지도" style="display:none;"></div>

  <p class="tutorial-map-text page-3" style="display:none;">우리 동네,&nbsp;<span class="highlight-text-arrow">채용지도</span></p>
</div>

<template id="tpl_category">
  <label class="filter-tag-item sm {{active}} btn-category" data-filter-key="{{filter_key}}">
    <input type="checkbox" name="filter" value="{{category_name}}">
    <span class="cate">{{category_name}}</span>
  </label>
</template>



<input type="hidden" name="mm_type" value="<?= $this->user->getUserType() ?>" />



<script>
  // ========================================
  // 1️⃣  전역 변수 선언
  // ========================================

  // PHP에서 미리 변환된 주소 데이터 (서버사이드 렌더링)
  var ad_addr = <?= json_encode(removeBigAddr(isset($addr_info["ad_addr"]) ? $addr_info["ad_addr"] : ""), JSON_UNESCAPED_UNICODE) ?>;
  var INITIAL_PAGE = <?= json_encode($initial_page) ?>;
  var list_id = "#ajaxList";
  var list_empty_id = "#ajaxEmpty";
  var list_page = parseInt(new URLSearchParams(window.location.search).get("page"), 10) || 1;
  var list_loading = false;
  var search_addr = [];
  var searchAreaStr = "";
  var addrName = "";
  var loadDataInitialized = false;// 데이터 로드 초기화 여부
  var ITEMS_PER_PAGE = 20;
  var total_count = 0;
  var total_pages = 1;
  var search_array = {
    category: "",
    work_type: "",
    week: "",
    price_type: "",
    sex: "",
    sage: "",
    eage: "",
  };

  var tab_list = [];
  tab_list.push({ key: "area", df: "근무지역", text: "", active: "" }); //0
  tab_list.push({ key: "category", df: "업직종", text: "", active: "" }); //1
  tab_list.push({ key: "work_type", df: "근무형태", text: "", active: "" }); //2
  tab_list.push({ key: "week", df: "근무요일", text: "", active: "" }); //3
  tab_list.push({ key: "price_type", df: "급여형태", text: "", active: "" }); //4
  tab_list.push({ key: "sex", df: "성별", text: "", active: "" }); //5
  tab_list.push({ key: "age", df: "나이", text: "", active: "" }); //6

  // ========================================
  // 2️⃣  URL/페이지 유틸리티 함수들
  // ========================================

  function getPageFromUrl() {
    var params = new URLSearchParams(window.location.search);
    return parseInt(params.get("page")) || 1;
  }

  // 현재 주소에서 page만 교체한 주소 문자열을 만든다
  function buildPageUrl(page) {
    var params = $.grep(window.location.search.replace(/^\?/, "").split("&"), function(p) {
      return p && p.indexOf("page=") !== 0;
    });
    params.push("page=" + page);
    return "?" + params.join("&");
  }

  // 페이지 이동/상세 진입처럼 "뒤로가기로 되돌아갈 지점"이 필요할 때만 히스토리를 늘린다
  function pushPageToUrl(page) {
    history.pushState({ page: page }, "", buildPageUrl(page));
  }

  // 화면 진입 시에는 히스토리를 늘리지 않고 현재 항목만 갱신한다.
  // 늘리면 handleAndroidBack()의 history.back()이 같은 화면으로 떨어져 뒤로가기를 두 번 눌러야 나가게 된다.
  function replacePageInUrl(page) {
    history.replaceState({ page: page }, "", buildPageUrl(page));
  }

  // 검색 조건 주소 연결 (PC와 같은 형식, 뒤로가기 기록은 남기지 않음)
  function updateSearchUrlFromState(state) {
    if (!state) return;
    var qs = [];
    var area = [];
    try {
      area = JSON.parse(state.searchAreaStr || "[]") || [];
    } catch (e) {}
    var nos = $.map(area, function(a) {
      return a && a.no ? a.no : null;
    });
    // 지역 번호(no)가 없는 예전 저장값은 주소로 옮기면 지역이 빠진 채 재진입하게 되므로 주소를 바꾸지 않음
    if (nos.length !== area.length) return;
    if (nos.length) qs.push("area=" + nos.join(","));
    $.each([["category", "category"], ["work", "work_type"], ["pay", "price_type"], ["week", "week"], ["sex", "sex"], ["nation", "nationality"]], function(i, p) {
      var vals = $.map(state[p[1]] || [], function(v) {
        return v ? encodeURIComponent(v) : null;
      });
      if (vals.length) qs.push(p[0] + "=" + vals.join(","));
    });
    var sage = parseInt(state.sage, 10);
    var eage = parseInt(state.eage, 10);
    if (sage && eage) {
      var ages = [];
      for (var age = sage; age <= eage; age += 10) ages.push(age);
      qs.push("age=" + ages.join(","));
    }
    if (list_page > 1) qs.push("page=" + list_page);

    var url = location.pathname + (qs.length ? "?" + qs.join("&") : "");
    if (url !== location.pathname + location.search) {
      history.replaceState(history.state, "", url);
    }
  }
  window.onSearchStateSaved = updateSearchUrlFromState;

  function syncSearchUrlFromStorage() {
    try {
      var raw = sessionStorage.getItem("jobListAdvancedSearch_recruit_" + (currentUserId || "guest"));
      var state = raw ? JSON.parse(raw) : null;
      if (state && state.userId === currentUserId) updateSearchUrlFromState(state);
    } catch (e) {}
  }

  // 마지막으로 본 페이지 저장 — 쿠키는 서버(recruit.php::job_list)가 재진입 시 그 페이지를 바로 렌더링하는 데 사용
  function rememberListPage(page) {
    sessionStorage.setItem('recruit_list_page', page);
    document.cookie = 'recruit_list_page=' + parseInt(page, 10) + '; path=/; SameSite=Lax';
  }

  function saveCurrentPage() {
    rememberListPage(list_page);
    pushPageToUrl(list_page);
  }

  function initCashingData() {
    list_page = getPageFromUrl();
    getBoardList("");
  }

  // ========================================
  // 3️⃣  데이터 조회 함수들 (AJAX)
  // ========================================
  
  // 목록 로딩 상태 표시 (data-loading="true"인 동안 기존 목록을 흐리게 유지, CSS에서 처리)
  function setListLoading(isLoading) {
    list_loading = isLoading;
    $(list_id).attr("data-loading", isLoading ? "true" : "false");
  }

  // 공고 리스트 조회 (save_yn === "Y"이면 같은 요청에서 검색조건도 저장 — 다음 진입 때 서버가 이 조건으로 목록을 그림)
  function getBoardList(save_yn) {
    if (list_loading) {
      return;
    }

    var params = {
      page: list_page,
      limit: ITEMS_PER_PAGE,
      "search_addr[]": search_addr,
      addrName: addrName,
      searchAreaStr: searchAreaStr,
      category: search_array.category,
      work_type: search_array.work_type,
      week: search_array.week,
      price_type: search_array.price_type,
      sex: search_array.sex,
      nationality: search_array.nationality,
      sage: search_array.sage,
      eage: search_array.eage,
      save_yn: save_yn === "Y" ? "Y" : "N",
      render: "html", // 첫 화면과 같은 카드 파셜로 서버가 그린 HTML을 받음
    };
    // 공고 리스트 데이터 조회 및 렌더링
    setListLoading(true);
    $.ajax({
      url: "/recruit_proc/getRecruitList",
      type: "post",
      data: params,
      traditional: false,
      dataType: "json",
      crossDomain: true,
      success: function(ret) {
        setListLoading(false);

        if (ret.code === "00" && ret.data && typeof ret.data.list_html === "string") {
          var listLen = parseInt(ret.data.list_count, 10) || 0;
          var serverTotal = parseInt(ret.data.total, 10);
          if (isNaN(serverTotal) || serverTotal < 0) {
            serverTotal = 0;
          }
          if (serverTotal > 0) {
            total_count = serverTotal;
          } else {
            total_count = (listLen === ITEMS_PER_PAGE) ? (list_page * ITEMS_PER_PAGE + 1) : ((list_page - 1) * ITEMS_PER_PAGE + listLen);
          }
          total_pages = Math.max(1, Math.ceil(total_count / ITEMS_PER_PAGE));

          showBoardList(ret.data.list_html, listLen);
          rememberListPage(list_page); // 검색조건 변경으로 1페이지가 된 경우도 재진입 페이지에 반영

          renderPagination("#paginationWrapper", list_page, total_pages, list_id);

          updateTopSectionsVisibility();
        } else {
          // 조회 실패: 기존 목록 유지 (목록이 비어 있으면 빈 상태 표시), 다시 시도할 수 있게 페이지네이션 복원
          showEmpty();
          renderPagination("#paginationWrapper", list_page, total_pages, list_id);
        }
      },
      error: function(xhr, status, error) {
        setListLoading(false);
        showEmpty();
        renderPagination("#paginationWrapper", list_page, total_pages, list_id);
      }
    });
  }

  // ========================================
  // 4️⃣  UI 렌더링 함수들
  // ========================================

  // 서버가 카드 파셜(recruit_list_card.php)로 그린 HTML을 한 번에 넣음 (예전: 행마다 템플릿 치환 + append 20회)
  function showBoardList(listHtml, listCount) {
    $(list_id).html(listHtml);

    if (listCount > 0) {
      insertAdsenseAd();
    }

    showEmpty();
    applyLastClickedHighlight();
  }

  function insertAdsenseAd() {
    var isDevEnv = window.location.hostname.includes('dev') || window.location.pathname.includes('/dev_nowapk');

    if (isDevEnv) {
      var adHtml = '<li class="list-item ad-item-adsense" style="position: relative; background: #f0f0f0; border: 2px dashed #ccc; padding: 20px; text-align: center; color: #999; font-size: 12px;">광고 자리 (개발 환경)</li>';
      $(list_id).append(adHtml);
    } else {
      // 운영 환경에서 실제 광고 로드 (비활성화됨)
      // var adHtml = '<li class="list-item ad-item-adsense" style="position: relative;">' +
      //   '<div class="adsense-wrapper" style="position: relative;">' +
      //   '<ins class="adsbygoogle" style="display:block"' +
      //   ' data-ad-format="fluid"' +
      //   ' data-ad-layout-key="-hd+j-2v-68+mb"' +
      //   ' data-ad-client="ca-pub-7573726661605583"' +
      //   ' data-ad-slot="9872127954"></ins>' +
      //   '</div>' +
      //   '</li>';
      // $(list_id).append(adHtml);

      // setTimeout(function() {
      //   var pushStartTime = performance.now();
      //   try {
      //     (adsbygoogle = window.adsbygoogle || []).push({});
      //     var pushTime = performance.now() - pushStartTime;
      //   } catch (e) {
      //   }
      // }, 0);
    }
  }

  // 빈 상태 표시 토글
  function showEmpty() {
    if ($(list_id).find("li").length == 0) {
      $(list_empty_id).show();
      $(list_id).hide();
    } else {
      $(list_id).show();
      $(list_empty_id).hide();
    }
  }

  // 상세페이지에서 뒤로가기로 돌아왔을 때 직전에 클릭한 카드에 하이라이트 적용
  function applyLastClickedHighlight() {
    var isFromJobPost = sessionStorage.getItem('recruit_came_from_job_post');
    if (isFromJobPost !== 'true') { // 직전에 채용공고 상세페이지에서 돌아온 경우가 아니라면 하이라이트 적용하지 않음
      return;
    }

    var highlightRcIdx = sessionStorage.getItem('recruit_highlight_rc_idx');
    if (!highlightRcIdx) { // highlightRcIdx가 없으면 하이라이트 적용하지 않음
      return;
    }

    // 기존 하이라이트 모두 제거
    $('.content-li.highlight-recent').removeClass('highlight-recent');

    // 새 하이라이트 적용
    $('.content-li[data-rc_idx="' + highlightRcIdx + '"]').addClass('highlight-recent');

    // 정리
    sessionStorage.removeItem('recruit_came_from_job_post');
    sessionStorage.removeItem('recruit_highlight_rc_idx');
  }


  // 페이지네이션 버튼 클릭 이벤트 (이벤트 위임)
  function goPage(page) {
    // 조회 중이면 무시 (getBoardList가 무시하는 동안 페이지 번호/URL만 바뀌는 어긋남 방지)
    if (list_loading || page < 1 || page > total_pages) {
      return;
    }

    list_page = page;// 페이지 번호 업데이트

    saveCurrentPage(); // 세션 스토리지 및 URL 업데이트
    // 기존 목록은 지우지 않고 로딩 표시만 함 → 응답이 오면 교체 (빈 화면 깜빡임 방지)

    $("html, body").animate({scrollTop: 0}, 200); // 스크롤 상단 이동

    // 페이지네이션 미리 숨김
    $("#paginationWrapper").removeClass('visible');

    getBoardList(""); // 데이터 조회
    updateTopSectionsVisibility(); // 상단 섹션 표시 업데이트
  }

  // 현재 페이지가 1페이지인지 여부에 따라 상단 영역들을 보이거나 숨기는 역할
  function updateTopSectionsVisibility() {
    var isFirstPage = (list_page <= 1); //true이면 1페이지, false이면 2페이지 이상
    $('.main-visual').toggle(isFirstPage);
    $('#hotListSection').toggle(isFirstPage);
    $('#recruitStatusBanner').toggle(isFirstPage);
  }

  // ========================================
  // 5️⃣  초기화 함수들
  // ========================================

  function initLoadData() {
    if (loadDataInitialized) {
      return;
    }
    loadDataInitialized = true;


    // 서버가 페이지(URL page → 없으면 쿠키의 마지막 페이지)와 검색조건(URL 조건 → 없으면 저장된 조건)을
    // 이미 적용해서 렌더링했으므로 그 결과를 그대로 사용 (진입 시 AJAX 재조회 없음, 결과가 0건이어도 동일)
    list_page = INITIAL_PAGE;

    var actualItemCount = $(list_id).find("li").not(".ad-item-adsense").length;
    var serverTotal = <?= json_encode((int) $initial_total_count) ?>;
    if (serverTotal > 0) {
      total_count = serverTotal;
    } else {
      total_count = (actualItemCount > 0) ? Math.max(actualItemCount, ITEMS_PER_PAGE) : 0;
    }
    total_pages = Math.ceil(total_count / ITEMS_PER_PAGE);

    if (actualItemCount > 0) {
      insertAdsenseAd();
    }

    renderPagination("#paginationWrapper", list_page, total_pages, list_id);
    showEmpty();
    updateTopSectionsVisibility();
    applyLastClickedHighlight();

    // 진입은 "되돌아갈 지점"이 아니므로 히스토리를 늘리지 않는다 (pushState 대신 replaceState)
    rememberListPage(list_page);
    replacePageInUrl(list_page);
  }

  function initHotSlider() {
    var hotSlider = document.querySelector('.hot-list .slider-wrap');
    if (hotSlider && typeof KeenSlider !== 'undefined') {
      if (!hotSlider.__keenslider__) {
        var slides = hotSlider.querySelectorAll('.keen-slider__slide');
        var actualSlideCount = slides.length;
        if (slides.length > 0 && slides.length < 4) {
          var cloneCount = Math.ceil(4 / slides.length) * slides.length;
          var added = slides.length;
          var imageSrcMap = {};
          slides.forEach(function(slide, idx) {
            slide.querySelectorAll('img').forEach(function(img, imgIdx) {
              imageSrcMap[idx + '-' + imgIdx] = img.src;
            });
          });
          while (added < cloneCount) {
            slides.forEach(function(slide, slideIdx) {
              if (added < cloneCount) {
                var cloned = slide.cloneNode(true);
                cloned.querySelectorAll('img').forEach(function(img, imgIdx) {
                  var srcKey = slideIdx + '-' + imgIdx;
                  if (imageSrcMap[srcKey]) img.src = imageSrcMap[srcKey];
                  if (img.classList.contains('lozad')) {
                    if (img.getAttribute('data-src') && !img.src) img.src = img.getAttribute('data-src');
                    img.setAttribute('data-loaded', 'true');
                  }
                });
                hotSlider.appendChild(cloned);
                added++;
              }
            });
          }
        }

        var keenSlider = new KeenSlider(hotSlider, {
          loop: true,
          slides: {
            perView: 1.1,
            spacing: 12
          },
          drag: true,
          dragMinDist: 0,
          rubberband: true,
          renderMode: 'precision'
        });

        var dotsContainer = document.getElementById('hotSliderDots');
        if (dotsContainer && actualSlideCount > 1) {
          for (var i = 0; i < actualSlideCount; i++) {
            var dot = document.createElement('div');
            dot.className = 'dot' + (i === 0 ? ' active' : '');
            dot.setAttribute('data-index', i);
            (function(index) {
              dot.addEventListener('click', function(e) {
                e.stopPropagation();
                keenSlider.moveToSlide(index);
              });
            })(i);
            dotsContainer.appendChild(dot);
          }

          keenSlider.on('slideChanged', function() {
            var currentIdx = ((keenSlider.track.details.abs % actualSlideCount) + actualSlideCount) % actualSlideCount;
            var dots = dotsContainer.querySelectorAll('.dot');
            dots.forEach(function(d) {
              d.classList.remove('active');
            });
            if (dots[currentIdx]) {
              dots[currentIdx].classList.add('active');
            }
          });
        }
      }
    }
  }
  // attemptRestoreScroll: 모바일 웹뷰에서 스크롤이 안되는 문제를 해결하기 위해 body와 html의 overflow 속성을 강제로 복원하는 함수
  function attemptRestoreScroll() {
    if (typeof forceRestoreBodyScroll === 'function') {
      forceRestoreBodyScroll();
    } else if (typeof restoreBodyScroll === 'function') {
      restoreBodyScroll();
    } else {
      const b = document.body;
      const h = document.documentElement;
      const propsToRemove = ['position', 'top', 'left', 'right', 'overflow', 'width', 'height'];

      if (b) {
        propsToRemove.forEach(p => {
          if (b.style.getPropertyValue(p)) {
            b.style.removeProperty(p);
          }
        });
        b.classList.remove('no-scroll');
      }

      if (h) {
        propsToRemove.forEach(p => {
          if (h.style.getPropertyValue(p)) {
            h.style.removeProperty(p);
          }
        });
      }

      const shouldForceOverflow = (b && (b.classList.contains('no-scroll') ||
          b.style.position === 'fixed' ||
          getComputedStyle(b).overflow === 'hidden')) ||
        (h && getComputedStyle(h).overflow === 'hidden');

      if (shouldForceOverflow) {
        $("body").css({
          "overflow": "auto",
          "overflow-y": "auto",
          "-webkit-overflow-scrolling": "touch"
        }).removeClass('no-scroll');
        $("html").css({
          "overflow": "auto",
          "overflow-y": "auto"
        });
      }

      ['.page-recruit', '.page-content', '#app'].forEach(selector => {
        const $container = $(selector);
        if ($container.length && $container.css('overflow') !== 'hidden') {
          $container.css('-webkit-overflow-scrolling', 'touch');
        }
      });
    }
  }

  // ========================================
  // 6️⃣  이벤트 리스너 등록
  // ========================================

  // 브라우저 뒤로가기/앞으로가기 감지
  window.addEventListener('popstate', function(e) {
    // 알림/확인 다이얼로그가 떠 있으면 가장 먼저 닫고, 목록은 다시 불러오지 않음
    if (typeof window.isLayerPopupOpen === 'function' && window.isLayerPopupOpen()) {
      window.closeTopLayerPopup();
      return;
    }

    // 온보딩 튜토리얼(상세 팝업 안의 tutorial-popup2 포함)이 열려 있으면 튜토리얼부터 닫기
    if (typeof window.isTutorialOpen === 'function' && window.isTutorialOpen()) {
      window.closeTutorialPopup();
      return;
    }

    // 팝업이 열려있는지 확인
    var popup = document.getElementById('jobPostPopup');
    if (popup && popup.getAttribute('data-state') === 'open') {
      // 팝업 내 열린 패널 확인 (길찾기 등)
      var openPanels = popup.querySelectorAll('.layer-panel.on');

      if (openPanels.length > 0) {
        // 패널이 있으면 패널만 닫기 (팝업은 유지)
        openPanels.forEach(function(panel) {
          if (typeof closePanel === 'function') {
            closePanel(panel);
          }
        });
        // 브라우저가 팝업용 히스토리 항목을 이미 소비했으므로, 팝업이 남아 있는 동안 다시 채워 둔다
        if (window.jobPostHistoryPushed) {
          pushPageToUrl(list_page);
        }
        // 뒤로가기는 여기서 처리하고 리스트 로드하지 않음
        return;
      }

      // 브라우저 뒤로가기로 이미 항목이 소비된 상태이므로 히스토리는 건드리지 않고 닫기만 한다
      window.__closeJobPostPopupNow();
      return;
    }

    list_page = (e.state && e.state.page) ? e.state.page : getPageFromUrl();
    getBoardList(""); // 기존 목록은 응답이 오면 교체 (로딩 표시만)
  });

  // 페이지네이션 버튼 클릭 (이벤트 위임)
  $(document).on("click", "#paginationWrapper button", function(e) {
    e.preventDefault();
    var page = $(this).data('page');
    if (page) {
      goPage(page);
    }
  });

  // 폼 체크박스 클릭 이벤트
  $(document).on("click", ".form-checkbox", function(e) {
    e.stopPropagation();
  });

  // 공고 카드 클릭 이벤트 (saveCurrentPage + 팝업 열기 통합)
  $(document).on('click', '.content-li', function(e) {
    if ($(e.target).closest('button, a, input').length) {
      return;
    }
    if ($(e.target).closest('.ad-item-adsense').length) {
      return;
    }
    // 채팅 마크 클릭은 무시 (별도 핸들러에서 처리)
    if ($(e.target).closest('.chat_mark').length) {
      return;
    }
    saveCurrentPage();
    window.jobPostHistoryPushed = true; // 이 시점에 히스토리 항목이 추가됨 (닫을 때 소비해야 함)
    var rcIdx = $(this).data('rc_idx');
    if (rcIdx) {
      window.openJobPostPopup(rcIdx);
    }
  });

  // 채팅 마크 클릭 시 채팅방으로 이동
  $(document).on('click', '.chat_mark', function(e) {
    e.stopPropagation();
    var $mark = $(this);
    var rmIdx = $mark.data('rm_idx');
    var chatType = $mark.data('chat_type');
    var visible = $mark.data('visible');

    if (rmIdx) {
      var targetUrl = '/chat/chat_view?rm_idx=' + rmIdx + '&from=job_list';
      location.href = targetUrl;
    }
  });

  // 이력서 작성 버튼 클릭 이벤트
  $("#btnResumeList").click(function(e) {
    e.preventDefault();
    if (typeof window.openResumeNavigationPopup === 'function') {
      openResumeNavigationPopup();
    } else {
      commonAlert("이력서 이동 팝업을 불러올 수 없습니다. 잠시 후 다시 시도해주세요.", function() {});
    }
  });

  // ========================================
  // 7️⃣  페이지 로드 시 초기화 (DOMContentLoaded)
  // ========================================

  window.addEventListener('DOMContentLoaded', function() {
      // 중복 방지: DOMContentLoaded 여러 번 발생 시 한 번만 실행
    if (window.__jobListDOMContentLoaded) {
      return;
    }
    window.__jobListDOMContentLoaded = true;

    $('html, body').css({ // 스크롤 강제 활성화 (모바일 웹뷰에서 overflow:hidden 잔존 대응)
      overflow: 'auto',
      'overflow-y': 'auto',
      height: 'auto'
    });

    var pageType = 'recruit';
    var storageKey_regions = 'selectedRegions_' + pageType;
    var storageKey_area = 'searchAreaStr_' + pageType;

    var addrNames = [];
    var searchArea = [];
    var savedSelectedRegions = sessionStorage.getItem(storageKey_regions); // 다른 페이지에서 선택한 지역 정보가 저장되어 있는지 확인
    var savedSearchAreaStr = sessionStorage.getItem(storageKey_area); // 다른 페이지에서 선택한 지역 이름 정보가 저장되어 있는지 확인

    // savedSelectedRegions:"[{\"addr\":\"인천광역시 강화군 강화읍\",\"step\":3},{\"addr\":\"인천광역시 동구 송림동\",\"step\":3}]"
   // savedSearchAreaStr: "[\"강화읍\",\"송림동\"]"
  

    if (savedSelectedRegions) {
      try {
        window.selectedRegions = JSON.parse(savedSelectedRegions);
        addrNames = window.selectedRegions.slice(0, 3);
        //addrNames:['강화읍', '송림동']
      } catch (e) {
 
        window.selectedRegions = [];
      }
    } else {
    
      window.selectedRegions = [];
    }

    if (savedSearchAreaStr) {
      try {
        searchArea = JSON.parse(savedSearchAreaStr);
      } catch (e) {
    
        searchArea = [];
      }
    }

    if (addrNames.length > 0) {
      addrName = addrNames.join(', ');
      $('.addrName').text(addrName).attr('data-selected', 'true');
    } else {
      addrName = '지역을 선택해 주세요';
      $('.addrName').text(addrName).attr('data-selected', 'false');
    }

    search_addr = [];
    searchArea.forEach(function(area) {
      if (area && area.addr) {
        search_addr.push(area.addr);
      }
    });
    searchAreaStr = JSON.stringify(searchArea);

    if (typeof selectedRegions === 'undefined') {
      window.selectedRegions = addrNames.slice(0, 3);
    }

  
    if (typeof window.updateSelectedRegionsTags === 'function') {
  
      window.updateSelectedRegionsTags();
    }

     // 네이티브 앱 식별자 확인
     function isNativeApp() {
      return !!(window.APP_NOWAPK || (window.webkit && window.webkit.messageHandlers));
    }

    // 온보딩 튜토리얼 최초 노출 체크 (Android/iOS 브릿지)
    window.onShowTutorialResult = function(isFirst) {
      if (isFirst === 'Y') openTutorialPopup();
    };

    // 처음 설치 시에만 팝업 표시 (개발/운영 환경 상관없이)
    if (isNativeApp()) {
      if (typeof APP_NOWAPK !== 'undefined' && APP_NOWAPK.showTutorial) {
        var result = APP_NOWAPK.showTutorial();
        if (result === 'Y') {
          openTutorialPopup();
        }
      } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.showTutorial) {
        window.webkit.messageHandlers.showTutorial.postMessage("");
      }
    }

    initCategory();
    initTabUi();
    // 추천공고(C001)는 getRecruitList가 목록 맨 앞에 "추천" 표시로 보여준다.
    // 별도 추천공고 조회(getRecruitRecoList)는 결과를 넣을 영역(.section.index_comp)이 없어 제거함.

    // 서버가 검색조건까지 적용해 그린 목록으로 초기화 (index_search의 상태 복원이 끝난 뒤 실행되도록 지연)
    setTimeout(initLoadData, 10);
    setTimeout(syncSearchUrlFromStorage, 300);

    attemptRestoreScroll();
    setTimeout(attemptRestoreScroll, 50);
    setTimeout(attemptRestoreScroll, 200);

    initHotSlider();
  });

  // ========================================
  // 8️⃣  기타 헬퍼 함수들
  // ========================================

  // 온보딩 튜토리얼 팝업 함수
  window.isTutorialOpen = function() {
    var popup = document.getElementById('tutorial-popup');
    return popup && popup.style.display !== 'none';
  };

  var TUTORIAL_TEXT = "나우잡에서<br><span class='highlight-text'>인재목록,인재지도,공고등록</span> 등 다양한 콘텐츠를<br>이용해보세요!";
  var CURRENT_TUTORIAL_PAGE = 0;

  function preventScroll(e) {
    e.preventDefault();
  }

  function openTutorialPopup() {
    var popup = document.getElementById('tutorial-popup');
    popup.style.display = 'flex';
    CURRENT_TUTORIAL_PAGE = 0;
    // 튜토리얼 이미지는 CSS background-image이므로, 이 시점(display:none 해제)에 브라우저가 알아서 받아온다
    document.body.style.overflow = 'hidden';
    document.documentElement.style.overflow = 'hidden';
    document.addEventListener('touchmove', preventScroll, { passive: false });
    document.addEventListener('wheel', preventScroll, { passive: false });

    var textEl = popup.querySelector('.tutorial-text');
    var containerEl = popup.querySelector('.tutorial-container');
    var skipBtn = popup.querySelector('.tutorial-skip-btn');
    var recruitEl = popup.querySelector('.tutorial-recruit');
    var arrowTextEl = popup.querySelector('.tutorial-arrow-text');

    textEl.innerHTML = TUTORIAL_TEXT;

    // 모든 page 요소들의 초기 opacity 설정
    gsap.set([
      popup.querySelectorAll('.page-1'),
      popup.querySelectorAll('.page-2'),
      popup.querySelectorAll('.page-3')
    ], { opacity: 0 });
    var page4Wrapper = popup.querySelector('.tutorial-page-4-wrapper');
    if (page4Wrapper) {
      page4Wrapper.style.display = 'none';
      gsap.set(page4Wrapper, { opacity: 0 });
    }

    // GSAP timeline으로 애니메이션 구성
    var tl = gsap.timeline();
    tl.fromTo(containerEl, { opacity: 0 }, { opacity: 1, duration: 0.6, display: 'flex' }, 0.5)
      .fromTo(skipBtn, { opacity: 0 }, { opacity: 1, duration: 0.3 }, 0.5)
      .fromTo(recruitEl, { opacity: 0 }, { opacity: 1, duration: 0.35 }, '+=0.35')
      .fromTo(arrowTextEl, { opacity: 0 }, { opacity: 1, duration: 0.3 }, '-=0.1');

    window.setTutorialPage(0);
  }

  window.closeTutorialPopup = function() {
    var popup = document.getElementById('tutorial-popup');
    popup.style.display = 'none';
    popup.querySelector('.tutorial-container').style.display = 'none';
    document.body.style.overflow = 'auto';
    document.documentElement.style.overflow = 'auto';
    document.removeEventListener('touchmove', preventScroll);
    document.removeEventListener('wheel', preventScroll);

    // GSAP timeline 초기화
    gsap.set([
      popup.querySelector('.tutorial-container'),
      popup.querySelector('.tutorial-skip-btn'),
      popup.querySelector('.tutorial-recruit'),
      popup.querySelector('.tutorial-arrow-text')
    ], { opacity: 0 });
    var page4Wrapper = popup.querySelector('.tutorial-page-4-wrapper');
    if (page4Wrapper) {
      page4Wrapper.style.display = 'none';
      gsap.set(page4Wrapper, { opacity: 0 });
    }

    if (typeof APP_NOWAPK !== 'undefined' && APP_NOWAPK.onCloseTutorial) {
      APP_NOWAPK.onCloseTutorial();
    } else if (window.webkit && window.webkit.messageHandlers && window.webkit.messageHandlers.onCloseTutorial) {
      window.webkit.messageHandlers.onCloseTutorial.postMessage("");
    }
  };

  var pageTransitionTimeline = null;

  window.setTutorialPage = function(pageIndex) {
    var popup = document.getElementById('tutorial-popup');
    CURRENT_TUTORIAL_PAGE = pageIndex;

    // 기존 애니메이션 중지
    if (pageTransitionTimeline) pageTransitionTimeline.kill();

    // 인디케이터 업데이트
    var indicators = popup.querySelectorAll('.indicator');
    indicators.forEach(function(ind, idx) {
      if (idx === pageIndex) {
        ind.classList.add('active');
      } else {
        ind.classList.remove('active');
      }
    });

    // 새 타임라인 생성
    pageTransitionTimeline = gsap.timeline({ defaults: { ease: 'power2.out' } });

    var page4Wrapper = popup.querySelector('.tutorial-page-4-wrapper');

    if (pageIndex === 0) {
      // 페이지 1: 페이지 1 요소들만 표시, 나머지 숨김
      var page2Elements = popup.querySelectorAll('.page-2');
      var page3Elements = popup.querySelectorAll('.page-3');
      page2Elements.forEach(function(el) { el.style.display = 'none'; gsap.set(el, { opacity: 0 }); });
      page3Elements.forEach(function(el) { el.style.display = 'none'; gsap.set(el, { opacity: 0 }); });
      if (page4Wrapper) page4Wrapper.style.display = 'none';

      var page1Elements = popup.querySelectorAll('.page-1');
      page1Elements.forEach(function(el) { el.style.display = ''; gsap.set(el, { opacity: 0 }); });
    }
    else if (pageIndex === 1) {
      // 페이지 2: 페이지 1, 3 요소들 숨김
      var page1Elements = popup.querySelectorAll('.page-1');
      var page3Elements = popup.querySelectorAll('.page-3');
      page1Elements.forEach(function(el) { el.style.display = 'none'; gsap.set(el, { opacity: 0 }); });
      page3Elements.forEach(function(el) { el.style.display = 'none'; gsap.set(el, { opacity: 0 }); });
      if (page4Wrapper) page4Wrapper.style.display = 'none';

      var page2Elements = popup.querySelectorAll('.page-2');
      page2Elements.forEach(function(el) { el.style.display = ''; gsap.set(el, { opacity: 0 }); });

      // 페이지 2 애니메이션 순서
      pageTransitionTimeline
        .fromTo('.tutorial-resume', { opacity: 0 }, { opacity: 1, duration: 0.35 }, 0)
        .fromTo('.tutorial-arrow-text-page2', { opacity: 0 }, { opacity: 1, duration: 0.3 }, '+=0.35');
    }
    else if (pageIndex === 2) {
      // 페이지 3: 페이지 1, 2 요소들 숨김
      var page1Elements = popup.querySelectorAll('.page-1');
      var page2Elements = popup.querySelectorAll('.page-2');
      page1Elements.forEach(function(el) { el.style.display = 'none'; gsap.set(el, { opacity: 0 }); });
      page2Elements.forEach(function(el) { el.style.display = 'none'; gsap.set(el, { opacity: 0 }); });
      if (page4Wrapper) page4Wrapper.style.display = 'none';

      var page3Elements = popup.querySelectorAll('.page-3');
      page3Elements.forEach(function(el) { el.style.display = ''; gsap.set(el, { opacity: 0 }); });

      // 페이지 3 애니메이션 순서
      pageTransitionTimeline
        .fromTo('.tutorial-map', { opacity: 0 }, { opacity: 1, duration: 0.35 }, 0)
        .fromTo('.tutorial-map-text', { opacity: 0 }, { opacity: 1, duration: 0.3 }, '+=0.35');
    }
    else if (pageIndex === 3) {
      // 페이지 4: 페이지 1, 2, 3 요소들 숨김
      var page1Elements = popup.querySelectorAll('.page-1');
      var page2Elements = popup.querySelectorAll('.page-2');
      var page3Elements = popup.querySelectorAll('.page-3');
      page1Elements.forEach(function(el) { el.style.display = 'none'; gsap.set(el, { opacity: 0 }); });
      page2Elements.forEach(function(el) { el.style.display = 'none'; gsap.set(el, { opacity: 0 }); });
      page3Elements.forEach(function(el) { el.style.display = 'none'; gsap.set(el, { opacity: 0 }); });

      var page4Wrapper = popup.querySelector('.tutorial-page-4-wrapper');
      if (page4Wrapper) {
        page4Wrapper.style.display = 'flex';
        gsap.set(page4Wrapper, { opacity: 1 });

        var searchEl = page4Wrapper.querySelector('.tutorial-search');
        var searchTextEl = page4Wrapper.querySelector('.tutorial-search-text');

        gsap.set([searchEl, searchTextEl], { opacity: 0 });

        // 페이지 4 애니메이션 순서: search 먼저, search-text 나중
        pageTransitionTimeline
          .fromTo(searchEl, { opacity: 0 }, { opacity: 1, duration: 0.35 }, 0)
          .fromTo(searchTextEl, { opacity: 0 }, { opacity: 1, duration: 0.3 }, '+=0.35');
      }
    }
  };

  // tutorial-popup 클릭 이벤트 (페이지 전환)
  document.addEventListener('click', function(e) {
    var popup = document.getElementById('tutorial-popup');
    if (!popup || popup.style.display === 'none') return;

    // indicator 클릭은 무시
    if (e.target.classList.contains('indicator')) {
      return;
    }

    // tutorial-popup 영역 클릭 감지 (배경, 이미지, 텍스트 등)
    if (e.target === popup || popup.contains(e.target)) {
      if (CURRENT_TUTORIAL_PAGE < 3) {
        window.setTutorialPage(CURRENT_TUTORIAL_PAGE + 1);
      } else {
        window.closeTutorialPopup();
      }
    }
  });

  // 지역 선택 팝업 적용 시 카테고리 텍스트 숨김
  window.onChangeAreaApply = function() {
    $(".search-option").addClass("hide-cate");
    if (typeof saveSearchStateToStorage === "function") {
      saveSearchStateToStorage();
    }
  };

  // Android 뒤로가기 패널 상태 관리
  window.isAdvancedSearchOpen = function() {
    return $('#advancedSearch').hasClass('on');
  };

  window.isChangeAreaOpen = function() {
    return $('#changeArea').hasClass('on');
  };

  // 패널 닫힐 때 공통 body 스타일 복원 함수
  window.restorePanelClosedState = function() {
    $('body, html').css({
      'overflow': 'auto',
      'overflow-y': 'auto',
      'position': 'relative',
      'top': 'auto',
      'left': 'auto',
      'right': 'auto',
      'width': 'auto',
      'height': 'auto'
    }).removeClass('no-scroll panel-open');

    var hasOtherOpenPanel = document.querySelector('.layer-panel.on') !== null;
    if (!hasOtherOpenPanel) {
      $('body').removeClass('panel-open');
    }

    if (typeof forceRestoreBodyScroll === 'function') {
      forceRestoreBodyScroll();
    } else if (typeof restoreBodyScroll === 'function') {
      restoreBodyScroll();
    }
  };

  // 채용공고 팝업 함수들
  window.openJobPostPopup = function(rcIdx) {
    var popup = document.getElementById('jobPostPopup');
    // 이미 로드 중이거나 열려 있으면 무시 (중복 클릭 방지)
    if (!popup || popup.getAttribute('data-loading') === 'true' || popup.getAttribute('data-state') === 'open') {
      return;
    }
    popup.setAttribute('data-loading', 'true');

    $.ajax({
      url: '/recruit/job_post/' + rcIdx,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).done(function(html) {
      popup.innerHTML = html;
      popup.setAttribute('data-loading', 'false');

      // 로드된 HTML 내 script 태그 실행
      var scripts = popup.querySelectorAll('script');
      var pendingScripts = 0;
      var sharePanelScriptLoaded = false;

      scripts.forEach(function(script) {
        if (script.src) {
          // 이미 같은 src 스크립트가 문서에 존재하면 재삽입하지 않음
          if (document.querySelector('script[src="' + script.src + '"]')) {
            if (script.src.indexOf('share-panel.js') !== -1 && typeof initializeSharePanel === 'function') {
              initializeSharePanel();
            }
            return;
          }
          pendingScripts++;
          var newScript = document.createElement('script');
          newScript.src = script.src;
          newScript.async = script.async;

          // share-panel.js 로드 완료 후 initializeSharePanel() 호출
          if (script.src.indexOf('share-panel.js') !== -1) {
            newScript.onload = function() {
              sharePanelScriptLoaded = true;
              if (typeof initializeSharePanel === 'function') {
                initializeSharePanel();
              }
            };
            newScript.onerror = function() {
              console.error('[openJobPostPopup] share-panel.js 로드 실패');
              sharePanelScriptLoaded = true;
            };
          }

          newScript.onloadend = function() {
            pendingScripts--;
          };

          popup.appendChild(newScript);
        } else {
          var newScript = document.createElement('script');
          newScript.textContent = script.textContent;
          popup.appendChild(newScript);
        }
      });

      document.body.style.overflow = 'hidden';
      sessionStorage.setItem('recruit_came_from_job_post', 'true');
      sessionStorage.setItem('recruit_highlight_rc_idx', rcIdx);

      // GSAP으로 팝업 열기 애니메이션
      gsap.fromTo('#jobPostPopup',
        {
          x: '100%'
        },
        {
          duration: 0.5,
          x: '0%',
          ease: "power1.out",
          clearProps: "transform",
          onComplete: function() {
            document.getElementById('jobPostPopup').setAttribute('data-state', 'open');
          }
        }
      );

      // 팝업 로드 후 이미지 lazy load 초기화
      if (typeof initLazyLoad === 'function') {
        initLazyLoad();
      }

      // 팝업 로드 후 keen-slider 초기화
      if (typeof KeenSlider !== 'undefined') {
        new KeenSlider(".view-visual-slider .slider-wrap", {
          loop: true,
        }, [
          sliderDots,
          addActiveClass
        ]);
      }

      // 팝업 로드 후 이미지 확대 기능 초기화 (magnificPopup)
      if (typeof jQuery !== 'undefined') {
        $('#jobPostPopup .view-visual-slider').magnificPopup({
          delegate: 'img',
          type: 'image',
          gallery: {
            enabled: true
          }
        });
      }

      // 팝업 로드 후 상세정보 확대/핀치줌 기능 초기화
      if (typeof window.initJobPostDescFeatures === 'function') {
        window.initJobPostDescFeatures();
      }
    }).fail(function() {
      popup.setAttribute('data-loading', 'false');
    });
  };

  // 닫기 요청 진입점 (닫기 버튼, 앱/iOS 뒤로가기 모두 여기로 온다)
  // 카드 클릭으로 히스토리 항목을 추가했다면 그 항목을 먼저 소비해야 하므로 history.back()으로 넘기고,
  // 실제 닫기는 popstate 핸들러가 __closeJobPostPopupNow()로 처리한다.
  window.closeJobPostPopup = function(e) {
    if (e) e.preventDefault();

    if (window.jobPostHistoryPushed) {
      history.back();
      return;
    }
    window.__closeJobPostPopupNow();
  };

  // 실제 닫기 (히스토리는 건드리지 않음)
  window.__closeJobPostPopupNow = function() {
    window.jobPostHistoryPushed = false;

    // 팝업 내 모든 열린 패널 닫기 (길찾기 등)
    var popup = document.getElementById('jobPostPopup');
    if (popup) {
      var openPanels = popup.querySelectorAll('.layer-panel.on');
      openPanels.forEach(function(panel) {
        if (typeof closePanel === 'function') {
          closePanel(panel);
        }
      });
    }

    // GSAP으로 팝업 닫기 애니메이션
    gsap.to('#jobPostPopup', {
      duration: 0.5,
      x: '100%',
      ease: "power1.out",
      onComplete: function() {
        document.getElementById('jobPostPopup').setAttribute('data-state', 'closed');
        $('#jobPostPopup').html('');
      }
    });

    // 스크롤 복원 (nested 팝업으로 인한 overflow 오버라이드 방지)
    document.body.style.overflow = 'auto';
    document.body.style.overflowY = 'auto';
    document.documentElement.style.overflow = 'auto';
    document.documentElement.style.overflowY = 'auto';

    // 모바일 웹뷰 강제 스크롤 복원
    if (typeof restoreBodyScroll === 'function') {
      restoreBodyScroll();
    }

    applyLastClickedHighlight();
  };

  // 채용공고 팝업 열림 여부: data-state="open"이면 열린 상태
  // (GSAP은 x 이동만 애니메이션하므로 opacity로는 판단할 수 없음)
  window.isJobPostPopupOpen = function() {
    var popup = document.getElementById('jobPostPopup');
    return !!popup && popup.getAttribute('data-state') === 'open';
  };

</script>
<?php include_once(get_base_url() . '/application/shop/views/mobile/recruit/index_search.php'); ?>
<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/addr_popup2025_new.php'); ?>
<script>
  // 주소 팝업이 독립적으로 작동하도록 페이지 타입 설정
  window.__addrPopupPageType = 'recruit';
</script>

<!-- 페이지네이션 헬퍼 스크립트 -->
<?= getCashJs(RES_PATH . "/js/pagination-helper.js") ?>

<!-- app 인터셉트 레이어 스크립트 -->
<?= getCashJs(RES_PATH . "/js/recruit/job_list.js") ?>
