<?php
$banner_list = getBannerList("BN00302031");
$banner = null;
if (count($banner_list) > 0) {
  $banner = $banner_list[0];
}

// Controller에서 전달받은 포맷팅된 데이터 사용
// (이미 resume.php의 hr_list()에서 초기 데이터 로드 및 포맷팅됨)
$use_server_render = isset($use_server_render) ? $use_server_render : false;
$initial_resumes = isset($initial_resumes) ? $initial_resumes : [];
$initial_total_count = isset($initial_total_count) ? $initial_total_count : 0;
$all_resume_count = isset($all_resume_count) ? $all_resume_count : $initial_total_count;
$today_recruit_count = isset($today_recruit_count) ? $today_recruit_count : 0;
$today_resume_active_count = isset($today_resume_active_count) ? $today_resume_active_count : 0;
$has_initial_reco = isset($has_initial_reco) ? $has_initial_reco : false;
$initial_reco = isset($initial_reco) ? $initial_reco : [];
$initial_page = isset($initial_page) ? $initial_page : 1;
?>
<?= getCachCss(RES_PATH . "/css/hr_list.css") ?>
<!-- Google AdSense 인피드 광고 스크립트 (비활성화) -->
<!-- <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3622369584322145"
  crossorigin="anonymous"></script> -->
<div class="page-recruit">

  <!-- <a href="#alarmPanel" aria-haspopup="dialog" aria-controls="alarmPanel" class="btn-alarm js-toggle-panel">
      <span class="sr-text">미확인 알림메세지</span> -->
  <!-- 미확인 알림개수 -->
  <!-- <span class="cnt"><?= $g_alarm_cnt ?></span>
    </a> -->


  <div id="hrPostPopup" class="hr-post-popup" data-state="closed"></div>

  <div class="page-content">
    <?php include(APPPATH . 'views/mobile/_partial/main_visual.php'); ?>

    <!-- 오늘 인재 현황 마퀴 배너 (1페이지에서만 노출) -->
    <?php
      $status_items = [
        ['label' => '', 'value' => date('Y-m-d')],
        ['label' => '전체이력서', 'value' => number_format($all_resume_count) . '개'],
        ['label' => '오늘등록이력서', 'value' => number_format($today_resume_active_count) . '건'],
      ];
      $status_items_html = '';
      foreach ($status_items as $item) {
        $status_items_html .= '<span class="marquee-item"><span class="label">' . $item['label'] . '</span>&nbsp;&nbsp;<span class="value">' . $item['value'] . '</span></span>';
      }
    ?>
    <div class="section resume-status-banner" id="resumeStatusBanner"<?= ($initial_page > 1) ? ' style="display:none;"' : '' ?>>
      <div class="marquee-wrap">
        <div class="marquee-track">
          <?= $status_items_html ?>
       
        </div>
      </div>
    </div>

    <!-- 추천인재 -->
    <div class="section index_comp" id="recommSection"<?= ($initial_page > 1) ? ' style="display:none;"' : '' ?>>
      <div class="container">
        <h2 class="secondary-title index" style="margin: 0 0px 1.1rem;">추천인재</h2>
        <div class="recomm-slider person" style="display: flex; flex-direction: column;">
          <div class="slider-wrap keen-slider" id="recomm-list">
            <?php
            if ($has_initial_reco && isset($initial_reco) && is_array($initial_reco)) {
              $chunked = array_chunk($initial_reco, 4);
              foreach ($chunked as $slideIndex => $chunk) {
                ?>
                <div class="keen-slider__slide">
                  <div class="person-grid">
                    <?php foreach ($chunk as $row) { ?>
                      <div class="person-grid-item">
                        <div class="person-card" data-ru_idx="<?= htmlspecialchars($row['ru_idx'], ENT_QUOTES, 'UTF-8') ?>" data-mm_idx="<?= htmlspecialchars($row['mm_idx'], ENT_QUOTES, 'UTF-8') ?>">
                          <div class="thumb-wrap">
                            <div class="thumb">
                              <?php $imgAttr = ($slideIndex === 0) ? 'src' : 'data-src'; ?>
                              <?php $lazyClass = ($slideIndex === 0) ? '' : 'lozad'; ?>
                              <img <?= $imgAttr ?>="<?= htmlspecialchars($row['ru_resume_img'], ENT_QUOTES, 'UTF-8') ?>" alt="프로필" class="<?= $lazyClass ?>">
                            </div>
                            <div class="person-badge-wrap">
                              <div class="person-badge-icon"><?= $row['ru_work_img'] ?></div>
                              <p class="person-badge"><?= htmlspecialchars($row['ru_name'], ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                          </div>
                          <div class="desc">
                            <div class="content-wrap" style="margin: 0;">
                              <div class="meta">
                                <p class="work_type"><?= $row['ru_work_type'] ?></p>
                                <span class="meta-divider">|</span>
                                <p class="national"><?= htmlspecialchars($row['mm_nation'], ENT_QUOTES, 'UTF-8') ?></p>
                                <span class="meta-divider">|</span>
                                <p class="age"><?= ($row['mm_age'] > 0) ? $row['mm_age'].'대' : '' ?></p>
                                <span class="meta-divider">|</span>
                                <p class="price_type"><?= $row['ru_price_type'] ?></p>
                              </div>
                              <p class="addr"><?= htmlspecialchars($row['ad_addr'], ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php } ?>
                  </div>
                </div>
                <?php
              }
            } else {
              echo '<p style="text-align:center;padding:20px;color:#999;">추천인재가 없습니다.</p>';
            }
            ?>
          </div>
          <!-- 슬라이더 dot 인디케이터 -->
          <div class="slider-dots" id="recommSliderDots"></div>
        </div>
      </div>
    </div>

    <div class="section is-last">
      <div class="container">
        <h2 class="secondary-title" style="margin: 0.3rem 0px 1.1rem;">인재정보</h2>
        <ol class="result-list result-list-card" id="ajaxList" data-page="<?= $initial_page ?>">
          <?php
          // 첫 화면 카드도 AJAX 응답과 같은 파셜로 그린다 (_partial/resume_list_card.php)
          $card_list = ($use_server_render && isset($initial_resumes) && is_array($initial_resumes)) ? $initial_resumes : array();
          include(APPPATH . 'views/mobile/_partial/resume_list_card.php');
          ?>
        </ol>
        <div id="ajaxEmpty" class="is-empty_v2" style="display:none;background:#F4F4F4;">
          <p>등록된 정보가 없습니다.</p>
        </div>
        <!-- 페이지네이션 버튼 -->
        <div id="paginationWrapper" class="pagination-wrapper" style="margin: 0px 0 48px 0;">
        </div>
      </div>
    </div>
    <!-- <div class="list-action">
      <a href="/resume/hr_map" class="btn-map"><span class="sr-text">지도보기</span></a>
    </div> -->

  </div><!-- //page-content -->
  <?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/main_menu.php'); ?>
</div>

<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/popup_alarm.php'); ?>

<template id="tpl_category">
  <label class="filter-tag-item sm {{active}} btn-category" data-filter-key="{{filter_key}}">
    <input type="checkbox" name="filter" value="{{category_name}}">
    <span class="cate">{{category_name}}</span>
  </label>
</template>

<input type="hidden" name="mm_type" value="<?= $this->user->getUserType() ?>" />

<script>
  // 현재 로그인한 사용자 정보
  var currentUserMmIdx = <?php
                          $mm_idx = $this->user->isLogin() ? $this->user->getUserIdx() : null;
                          echo json_encode($mm_idx ? (int)$mm_idx : null);
                          ?>;
  var currentUserType = <?php echo json_encode(isset($mm_type) ? $mm_type : ""); ?>;
</script>



<script>
  // mm_birth 디버깅 - 초기 렌더링 데이터 확인
  var debugInitialResumes = <?php echo json_encode(isset($initial_resumes) && is_array($initial_resumes) ? $initial_resumes : array()); ?>;
  if (debugInitialResumes.length > 0) {
    debugInitialResumes.forEach(function(row, idx) {
    });
  }

  var currentUserId = "<?= $this->user->isLogin() ? $this->user->getUserIdx() : '' ?>";
  var ad_addr = <?php echo json_encode(isset($addr_info["ad_addr"]) ? $addr_info["ad_addr"] : ""); ?>;
  <?php if (!empty($initial_search_state)) { ?>
  // 서버가 목록에 적용한 검색조건을 상세검색 저장값으로 넣음 (칩/체크박스 복원은 기존 흐름, 목록은 서버 결과 사용)
  (function() {
    var initial = <?= json_encode($initial_search_state, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    window.SEARCH_STATE_APPLIED_BY_SERVER = true; // index_search2025_new.php의 상태 복원이 목록을 다시 조회하지 않게 함
    try {
      sessionStorage.setItem('selectedRegions_resume', JSON.stringify(initial.state.selectedRegions));
      sessionStorage.setItem('searchAreaStr_resume', initial.state.searchAreaStr);
      sessionStorage.setItem('addrName_resume', initial.state.addrName);
      sessionStorage.setItem(initial.key, JSON.stringify(initial.state));
    } catch (e) {}
  })();
  <?php } ?>
  var INITIAL_PAGE = <?= json_encode($initial_page) ?>;
  var list_id = "#ajaxList";
  var list_empty_id = "#ajaxEmpty";
  var list_page = 1; // 현재 페이지 번호
  var list_loading = false; // 데이터 로딩 중 여부 (중복 요청 방지)
  var search_addr = []; // 선택된 지역 코드 배열 (예: ["11010", "11020"])
  var searchAreaStr = "";
  var addrName = ""; // 현재 선택된 지역 이름 (예: "서울 강남구 역삼동")
  var searchStateLoaded = false; // loadSearchStateFromStorage()에서 설정되는 플래그 (중복 호출 방지)
  var ITEMS_PER_PAGE = 20; // 한 번에 로드할 아이템 수
  var total_count = 0; // 전체 아이템 수
  var total_pages = 1; // 전체 페이지 수
  var search_array = { // 검색 조건 객체
    category: "",
    work_type: "",
    week: "",
    price_type: "",
    sex: "",
    sage: "",
    eage: "",
  };

  var tab_list = [];
  // 근무지역(지역 선택) 탭 항목 — 항상 tab_list[0]으로 유지 (recruit/job_list.php 패턴과 동일)
  tab_list.push({
    key: "area",
    df: "근무지역",
    text: "",
    active: ""
  });
  tab_list.push({
    key: "category",
    df: "업직종",
    text: "",
    active: ""
  });
  tab_list.push({
    key: "work_type",
    df: "근무형태",
    text: "",
    active: ""
  });
  tab_list.push({
    key: "week",
    df: "근무요일",
    text: "",
    active: ""
  });
  tab_list.push({
    key: "price_type",
    df: "급여형태",
    text: "",
    active: ""
  });
  tab_list.push({
    key: "sex",
    df: "성별",
    text: "",
    active: ""
  });
  tab_list.push({
    key: "age",
    df: "나이",
    text: "",
    active: ""
  });

  // 페이지네이션 버튼 클릭 (이벤트 위임)
  $(document).on("click", "#paginationWrapper button", function(e) {
    e.preventDefault();
    var page = $(this).data('page');
    if (page) {
      goPage(page);
    }
  });

  $(document).on("click", ".form-checkbox", function(e) {
    e.stopPropagation();
  });

  // URL에서 page 파라미터 읽기
  function getPageFromUrl() {
    var params = new URLSearchParams(window.location.search);
    return parseInt(params.get("page")) || 1;
  }

  // URL에 page 파라미터 반영 (히스토리 push)
  function pushPageToUrl(page) {
    var params = new URLSearchParams(window.location.search);
    params.set("page", page);
    history.pushState({ //브라우저를 새로고침하지 않고 URL 변경  example.com?page=3
      page: page // 첫 번째 인자는 상태 객체(브라우저 히스토리에 저장하여 나중에 뒤로가기 할 때 이 값을 꺼낼 수 있음), 두 번째 인자는 제목(대부분의 브라우저에서 무시), 세 번째 인자는 변경할 URL
    }, "", "?" + params.toString());
  }

  // 현재 항목의 page만 바꾼다 (히스토리 항목을 늘리지 않음) — 진입 시 사용: 되돌아갈 지점이 아니므로 추가하지 않는다
  function replacePageInUrl(page) {
    var params = new URLSearchParams(window.location.search);
    params.set("page", page);
    history.replaceState({ page: page }, "", "?" + params.toString());
  }

  // 캐싱된 페이지 데이터 초기화 (뒤로가기 복원 시)
  function initCashingData() {
    list_page = getPageFromUrl(); // URL에서 현재 페이지 번호 읽어서 list_page에 저장 example.com?page=3 -> list_page = 3
    getBoardList(""); // 현재 페이지 번호에 해당하는 글 목록 불러오기
  }

  function initLoadData() {
    // URL 파라미터 > sessionStorage > 기본값(1)
    // 서버가 URL page → 없으면 쿠키(마지막 페이지) 순으로 정해 이미 그 페이지를 렌더링했으므로 그대로 사용
    // (예전에는 sessionStorage 페이지로 다시 조회해서 서버가 그린 1페이지를 버리는 이중 조회가 있었음)
    list_page = INITIAL_PAGE;

    total_count = 0;
    total_pages = 1;

    // 첫 페이지는 서버가 저장된 조건까지 적용해 그렸으므로, 결과가 0건이어도 AJAX로 다시 조회하지 않는다
    var initialListCount = $(list_id).find("li").length;

    if (list_page === INITIAL_PAGE) {
      // 광고 제외하고 실제 아이템 개수 계산
      var adCount = $(list_id).find(".ad-item-adsense").length;
      var actualItemCount = initialListCount - adCount;

      // 서버가 계산한 개수(저장 조건이 있으면 필터된 개수)를 그대로 사용
      total_count = <?php echo json_encode((int) $initial_total_count); ?>;
      total_pages = Math.max(1, Math.ceil(total_count / ITEMS_PER_PAGE));
      if (total_count > 0) {
        renderPagination("#paginationWrapper", list_page, total_pages, list_id);
      } else {
        $("#paginationWrapper").empty();
      }

      // 광고 삽입
      if (actualItemCount > 0) {
        insertAdsenseAd();
      }

      showEmpty();
      updateTopSectionsVisibility();
      applyLastClickedHighlight();
    } else {
      // 초기 데이터 없음 또는 페이지 2+: AJAX로 로드
      // searchStateLoaded가 true면 이미 loadSearchStateFromStorage()에서 호출했으므로 스킵
      if (!searchStateLoaded) {
        getBoardList("");
      }
    }

    // 진입은 되돌아갈 지점이 아니므로 히스토리를 늘리지 않고 현재 항목만 갱신한다
    rememberListPage(list_page);
    replacePageInUrl(list_page);
  }

  // 마지막으로 본 페이지 저장 — 쿠키는 서버(resume.php::hr_list)가 재진입 시 그 페이지를 바로 렌더링하는 데 사용
  function rememberListPage(page) {
    sessionStorage.setItem('resume_list_page', page);
    document.cookie = 'resume_list_page=' + parseInt(page, 10) + '; path=/; SameSite=Lax';
  }

  // 현재 페이지를 저장 (상세페이지 돌아올 때 복원용)
  function saveCurrentPage() {
    rememberListPage(list_page);
    pushPageToUrl(list_page);
  }

  // 브라우저 뒤로가기/앞으로가기 감지
  window.addEventListener('popstate', function(e) {
    // 인재 상세 팝업이 열려 있으면 팝업부터 닫고, 목록은 다시 불러오지 않음
    var popup = document.getElementById('hrPostPopup');
    if (popup && popup.getAttribute('data-state') === 'open') {
      // 팝업 내 열린 패널(공유/선택 등)이 있으면 패널만 닫기
      var openPanels = popup.querySelectorAll('.layer-panel.on');
      if (openPanels.length > 0) {
        openPanels.forEach(function(panel) {
          if (typeof closePanel === 'function') {
            closePanel(panel);
          }
        });
        // 브라우저가 팝업용 히스토리 항목을 이미 소비했으므로, 팝업이 남아 있는 동안 다시 채워 둔다
        if (window.hrPostHistoryPushed) {
          pushPageToUrl(list_page);
        }
        return;
      }

      // 브라우저 뒤로가기로 항목이 이미 소비된 상태이므로 히스토리는 건드리지 않고 닫기만 한다
      window.__closeHrPostPopupNow();
      return;
    }

    list_page = (e.state && e.state.page) ? e.state.page : getPageFromUrl();
    getBoardList(""); // 기존 목록은 응답이 오면 교체 (로딩 표시만)
  });

  // 목록 로딩 상태 표시 (data-loading="true"인 동안 기존 목록을 흐리게 유지, CSS에서 처리)
  function setListLoading(isLoading) {
    list_loading = isLoading;
    $(list_id).attr("data-loading", isLoading ? "true" : "false");
  }

  function getBoardList(save_yn) { // 글 목록을 가져오는 함수 (save_yn === "Y"이면 같은 요청에서 검색 조건도 저장)
    if (list_loading) return;

    var params = {
      page: list_page,
      pagesize: ITEMS_PER_PAGE,
      "search_addr[]": search_addr,
      addrName: addrName,
      searchAreaStr: searchAreaStr,
      category: search_array.category,
      work_type: search_array.work_type,
      week: search_array.week,
      price_type: search_array.price_type,
      sex: search_array.sex,
      sage: search_array.sage,
      eage: search_array.eage,
      save_yn: save_yn === "Y" ? "Y" : "N",
      render: "html", // 첫 화면과 같은 카드 파셜로 서버가 그린 HTML을 받음
    };

    setListLoading(true);
    $.ajax({
      url: "/resume_proc/search_list2025", // 검색 조건에 맞는 글 목록을 가져오는 API 엔드포인트
      type: "post",
      data: params,
      traditional: false,
      dataType: "json",
      crossDomain: true,
      success: function(ret) {
        setListLoading(false);

        if (ret.code === "00" && ret.data && typeof ret.data.list_html === "string") {
          // 로그: AJAX 응답 데이터 확인
      

          $(list_id).empty(); // 매번 초기화
          total_count = ret.data.total || 0;
          total_pages = Math.ceil(total_count / ITEMS_PER_PAGE) || 1;

          showBoardList(ret.data.list_html, parseInt(ret.data.list_count, 10) || 0);
          rememberListPage(list_page); // 검색조건 변경으로 1페이지가 된 경우도 재진입 페이지에 반영
          renderPagination("#paginationWrapper", list_page, total_pages, list_id);
          updateTopSectionsVisibility();
        } else {
         
        }
      },
      error: function(xhr, status, error) {
        setListLoading(false); // 실패하면 기존 목록을 그대로 둔다
      }
    });
  }

  //데이터 보여주기
  // 서버가 카드 파셜(resume_list_card.php)로 그린 HTML을 한 번에 넣는다 (예전: 행마다 템플릿 치환 + append)
  function showBoardList(listHtml, listCount) {
    $(list_id).html(listHtml);

    if (listCount > 0) {
      insertAdsenseAd();
    }

    showEmpty();
    applyLastClickedHighlight();
  }

  // Google AdSense 인피드 광고 삽입 함수 (비동기 로드)
  function insertAdsenseAd() {
    // 도메인 환경 판별
    var isDevEnv = window.location.hostname.includes('dev') || window.location.pathname.includes('/dev_nowapk');

    if (isDevEnv) {
      // 개발 환경에서는 CSS로만 자리 표시
      var adHtml = '<li class="list-item ad-item-adsense" style="position: relative; background: #f0f0f0; border: 2px dashed #ccc; padding: 20px; text-align: center; color: #999; font-size: 12px;">' +
        '광고 자리 (개발 환경)' +
        '</li>';
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

      // // 비동기로 광고 로드 (setTimeout 사용)
      // setTimeout(function() {
      //   try {
      //     (adsbygoogle = window.adsbygoogle || []).push({});
      //   } catch (e) {
      //   }
      // }, 0); // 비동기 로드 (즉시)
    }
  }



  function getPriceType(price_type) {
    if (!price_type) {
      return "";
    }
    if (price_type === "일급,주급,월급") {
      return "급여협의";
    }
    return price_type
      .split(',')
      .map(type => `${type}`)
      .join('');
  }

  function getWorkType(work_type) {
    if (!work_type) {
      return "";
    }

    let items = [];

    // HTML span 형식 처리
    if (work_type.includes('<span>')) {
      const matches = work_type.match(/<span>(.*?)<\/span>/g);
      if (matches && matches.length > 0) {
        items = matches.map(m => m.replace(/<\/?span>/g, '').trim());
      }
    } else {
      // 쉼표로 구분된 일반 문자열 처리
      items = work_type.split(',').map(item => item.trim()).filter(item => item);
    }

    if (items.length === 0) {
      return "";
    }

    // 모든 값을 처리하여 HTML 생성
    return items.map((text, index) => {
      let cls = '';
      if (text === '주간') {
        cls = 'wtype1';
      } else if (text === '야간') {
        cls = 'wtype2';
      } else if (text === '2교대') {
        cls = 'wtype3';
      }
      let result = `<em class="${cls}">${text}</em>`;
      // 마지막 항목이 아니면 쉼표 추가
      if (index < items.length - 1) {
        result += ',';
      }
      return result;
    }).join('');
  }

  // 이력서 상세 페이지 접근 — 모든 사용자 클릭 가능 (hr_post에서 블러 처리)
  function handleResumeCardClick(ruIdx, resumeMmIdx) {
    if (ruIdx) {
      window.openHrPostPopup(ruIdx);
    }
  }

  // 인재 카드 클릭 (목록 카드 + 추천 슬라이더 카드 공통)
  // 상세 팝업을 여는 만큼 히스토리 항목을 하나 추가하고, 닫을 때 그 항목을 소비한다 (job_list와 같은 방식)
  $(document).on('click', '.content-li, .person-grid-item', function(e) {
    // 버튼·링크·입력 클릭은 무시
    if ($(e.target).closest('button, a, input').length) {
      return;
    }
    // Google AdSense 광고 클릭이면 무시 (WebView의 shouldOverrideUrlLoading이 처리)
    if ($(e.target).closest('.ad-item-adsense').length) {
      return;
    }
    // 채팅 마크 클릭은 무시 (별도 핸들러에서 처리)
    if ($(e.target).closest('.chat_mark').length) {
      return;
    }
    // 팝업이 이미 열리는 중이거나 열려 있으면 무시 (중복 클릭으로 히스토리 항목이 여러 개 쌓이는 것 방지)
    var popup = document.getElementById('hrPostPopup');
    if (popup && (popup.getAttribute('data-loading') === 'true' || popup.getAttribute('data-state') === 'open')) {
      return;
    }

    var $card = $(this);
    var ruIdx = $card.hasClass('person-grid-item') ? $card.find('.person-card').data('ru_idx') : $card.data('ru_idx');
    if (!ruIdx) {
      return;
    }

    saveCurrentPage();
    window.hrPostHistoryPushed = true;
    handleResumeCardClick(ruIdx);
  });

  // 채팅 마크 클릭 시 채팅방으로 이동
  $(document).on('click', '.chat_mark', function(e) {
    e.stopPropagation();
    var $mark = $(this);
    var rmIdx = $mark.data('rm_idx');
    var chatType = $mark.data('chat_type');
    var visible = $mark.data('visible');

    // 로그: 클릭 이벤트 확인
 

    if (rmIdx) {
      var targetUrl = '/chat/chat_view?rm_idx=' + rmIdx + '&from=hr_list';
   
      location.href = targetUrl;
    } else {
      
    }
  });


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
    var isFromHrPost = sessionStorage.getItem('resume_came_from_hr_post');
    if (isFromHrPost !== 'true') {
      return;
    }

    var highlightRuIdx = sessionStorage.getItem('resume_highlight_ru_idx');
    if (!highlightRuIdx) {
      return;
    }

    // 기존 하이라이트 모두 제거
    $('.content-li.highlight-recent').removeClass('highlight-recent');

    // 새 하이라이트 적용
    $('.content-li[data-ru_idx="' + highlightRuIdx + '"]').addClass('highlight-recent');

    // 정리
    sessionStorage.removeItem('resume_came_from_hr_post');
    sessionStorage.removeItem('resume_highlight_ru_idx');
  }


  // 페이지 이동 함수
  // page: 이동할 페이지 번호
  function goPage(page) {
    if (list_loading || page < 1 || page > total_pages) return; // 조회 중이거나 이동할 수 없는 페이지면 무시
    list_page = page; // 전역 변수 업데이트 현재 페이지를 3으로 바꿈
    saveCurrentPage(); // 현재 페이지를 저장 (pushPageToUrl 포함)
    // 기존 목록은 지우지 않고 응답이 오면 교체 (빈 화면 깜빡임 방지)
    $("html, body").animate({
      scrollTop: 0 //scrollTop: 0으로 하면 화면 맨 위로 이동
    }, 200); //화면 맨 위로 올림
    getBoardList(""); // 이동할 페이지 글 목록 다시 불러옴
    updateTopSectionsVisibility();
  }

  // 채용공고 작성 버튼 클릭 이벤트 20250923
  // 이벤트 위임 사용 (main_menu.php include 이후에도 동작 보장)
  $(document).off('click', '#btnRecruitList').on('click', '#btnRecruitList', function(e) {
    e.preventDefault();

    // 공통 함수 사용 (main_menu.php에 정의된 전역 함수)
    if (typeof window.handleRecruitPageNavigation === 'function') {
      window.handleRecruitPageNavigation();
    } else {
      // 함수가 아직 로드되지 않은 경우 (안전장치)

      // 로그인 여부 확인
      var user_type = $("[name='mm_type']").val();
      if (user_type != "COMP") {
        commonAlert("로그인 후 이용해주세요.", function() {
          location.href = '/member/login';
        });
        return;
      }

      // 채용공고 개수 확인 후 페이지 이동
      $.ajax({
        url: "/compage_proc/getRecruitCount",
        type: "post",
        dataType: "json",
        success: function(ret) {
          if (ret.code == "00") {
            var recruitCount = ret.data.count || 0;

            // 채용공고가 1개 이상이면 관리 페이지로, 0개이면 등록 페이지로 이동
            if (recruitCount >= 1) {
              location.href = "https://nowjob.co.kr/compage/recruit_list";
            } else {
              location.href = "https://nowjob.co.kr/compage/recruit_write1_1";
            }
          } else {
            commonAlert(ret.msg || "채용공고 개수를 확인할 수 없습니다.", function() {});
          }
        },
        error: function(xhr, status, error) {

          commonAlert("채용공고 개수를 확인할 수 없습니다.", function() {});
        }
      });
    }
  });
</script>
<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/addr_popup2025_new.php'); ?>
<?php include_once(get_base_url() . '/application/shop/views/mobile/resume/index_search2025_new.php'); ?>
<script>
  // 주소 팝업이 독립적으로 작동하도록 페이지 타입 설정
  window.__addrPopupPageType = 'resume';

  // [중복 방지] resume 페이지에서만 한 번만 실행
  window.addEventListener('DOMContentLoaded', function() {
    if (window.__hrListDOMContentLoaded) {
      return;
    }
    window.__hrListDOMContentLoaded = true;

    // 로그: 서버사이드 렌더링된 1페이지 카드들의 chat_type 확인
    var chatMarks = document.querySelectorAll('.chat_mark');
   

    // 스크롤 차단 방지: 초기 진입 시 html/body overflow 해제
    $('html, body').css({
      overflow: 'auto',
      'overflow-y': 'auto',
      height: 'auto'
    });

    // sessionStorage에서 선택된 지역 복원 (resume 페이지별)
    var pageType = 'resume';
    var storageKey_regions = 'selectedRegions_' + pageType;
    var storageKey_area = 'searchAreaStr_' + pageType;

    var addrNames = [];
    var searchArea = [];
    var savedSelectedRegions = sessionStorage.getItem(storageKey_regions);
    var savedSearchAreaStr = sessionStorage.getItem(storageKey_area);

    if (savedSelectedRegions) {
      try {
        window.selectedRegions = JSON.parse(savedSelectedRegions);
        addrNames = window.selectedRegions.slice(0, 3);
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

    // addrName 설정
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

    // window.selectedRegions 동기화
    if (typeof selectedRegions === 'undefined') {
      window.selectedRegions = addrNames.slice(0, 3);
    }

    // UI 동기화
    if (typeof window.updateSelectedRegionsTags === 'function') {
      window.updateSelectedRegionsTags();
    }

    // 검색 조건 초기화 (index_search2025_new.php의 $(document).ready()에서 이미 복원됨)
    if (typeof window.initCategory === 'function' && typeof window.initTabUi === 'function') {
      window.initCategory();
      window.initTabUi();
    }

    // initLoadData 호출: searchStateLoaded가 true이면 index_search에서 이미 getBoardList를 호출했으므로 건너뜀
    setTimeout(function() {
      if (!searchStateLoaded) {
        initLoadData();
      }
    }, 10);


    // 추천인재 슬라이더 초기화 (서버사이드 렌더링된 데이터 기반)
    // 서버가 항상 데이터를 내려주므로 데이터가 있을 때만 슬라이더 초기화
    var hasInitialReco = <?php echo json_encode($has_initial_reco); ?>;
    if (hasInitialReco) {
      setTimeout(function() {
        initRecommSlider();
      }, 100);
    }

    // iOS 웹뷰 대응: 초기 진입 시 스크롤 복원 (개선 버전)
    // 로드 순서 대비 재시도 로직 포함
    function attemptRestoreScroll() {
      if (typeof forceRestoreBodyScroll === 'function') {
        forceRestoreBodyScroll();
      } else if (typeof restoreBodyScroll === 'function') {
        restoreBodyScroll();
      } else {
        // fallback: 직접 복원
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

        // 스크롤 컨테이너에도 적용
        ['.page-recruit', '.page-content', '#app'].forEach(selector => {
          const $container = $(selector);
          if ($container.length && $container.css('overflow') !== 'hidden') {
            $container.css('-webkit-overflow-scrolling', 'touch');
          }
        });
      }
    }

    // 즉시 시도
    attemptRestoreScroll();

    // iOS 타이밍 이슈 대비 재시도
    setTimeout(attemptRestoreScroll, 50);
    setTimeout(attemptRestoreScroll, 200);

    // 네이티브 광고 제어는 main.js에서 자동으로 초기화되므로 중복 호출 제거
  });

  // 추천인재 슬라이더 초기화
  function initRecommSlider() {
    var recommSlider = document.querySelector('.recomm-slider.person .slider-wrap');
    if (recommSlider && typeof KeenSlider !== 'undefined' && !recommSlider.__keenslider__) {
      var slides = recommSlider.querySelectorAll('.keen-slider__slide');
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
                if (imageSrcMap[srcKey]) {
                  img.src = imageSrcMap[srcKey];
                }
                if (img.classList.contains('lozad')) {
                  if (img.getAttribute('data-src') && !img.src) {
                    img.src = img.getAttribute('data-src');
                  }
                  img.setAttribute('data-loaded', 'true');
                }
              });
              recommSlider.appendChild(cloned);
              added++;
            }
          });
        }
      }

      var slider = new KeenSlider(recommSlider, {
        loop: true,
        initial: 0,
        slides: {
          perView: 1.1,
          spacing: 12
        },
        drag: true,
        dragMinDist: 0,
        rubberband: true,
        renderMode: 'precision'
      });

      // dot 인디케이터 생성 및 업데이트
      var dotsContainer = document.getElementById('recommSliderDots');
      if (dotsContainer && actualSlideCount > 1) {
        // 원본 슬라이드 개수만큼 dot 생성
        for (var i = 0; i < actualSlideCount; i++) {
          var dot = document.createElement('div');
          dot.className = 'dot' + (i === 0 ? ' active' : '');
          dot.setAttribute('data-index', i);
          (function(index) {
            dot.addEventListener('click', function(e) {
              e.stopPropagation();
              slider.moveToSlide(index);
            });
          })(i);
          dotsContainer.appendChild(dot);
        }

        // 슬라이더 이동 시 dot 업데이트
        slider.on('slideChanged', function() {
          var currentIdx = ((slider.track.details.abs % actualSlideCount) + actualSlideCount) % actualSlideCount;
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

  // 지역 선택 팝업 적용 시 카테고리 텍스트 숨김
  window.onChangeAreaApply = function() {
    $(".search-option").addClass("hide-cate");
    if (typeof saveSearchStateToStorage === "function") {
      saveSearchStateToStorage();
    }

    // updateSelectedRegionsTags 함수 호출
    if (typeof window.updateSelectedRegionsTags === "function") {
      window.updateSelectedRegionsTags();
    }
  };

  // 1페이지 여부에 따라 배너/슬라이더 섹션 표시/숨김
  function updateTopSectionsVisibility() {
    var isFirstPage = (list_page <= 1);
    $('.main-visual').toggle(isFirstPage);
    $('#recommSection').toggle(isFirstPage);
  }

  // Android 뒤로가기 패널 상태 관리
  window.isAdvancedSearchOpen = function() {
    return $('#advancedSearch').hasClass('on');
  };

  window.isChangeAreaOpen = function() {
    return $('#changeArea').hasClass('on');
  };

  // 패널 닫힐 때 공통 body 스타일 복원 함수 (main.js의 closePanel 호출을 대신함)
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

    // 다른 열린 패널 확인
    var hasOtherOpenPanel = document.querySelector('.layer-panel.on') !== null;
    if (!hasOtherOpenPanel) {
      $('body').removeClass('panel-open');
    }

    // iOS 웹뷰 대응: body 스타일 완전 복원
    if (typeof forceRestoreBodyScroll === 'function') {
      forceRestoreBodyScroll();
    } else if (typeof restoreBodyScroll === 'function') {
      restoreBodyScroll();
    }
  };

  // 인재 상세 팝업 함수들
  window.openHrPostPopup = function(ruIdx) {
    var popup = document.getElementById('hrPostPopup');
    // 이미 로드 중이거나 열려 있으면 무시 (중복 클릭으로 요청·스크립트가 중복 실행되는 것 방지)
    if (!popup || popup.getAttribute('data-loading') === 'true' || popup.getAttribute('data-state') === 'open') {
      return;
    }
    popup.setAttribute('data-loading', 'true');

    $.ajax({
      url: '/resume/hr_post/' + ruIdx,
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
          // 이미 같은 src 스크립트가 문서에 있으면 다시 넣지 않음 (share-panel은 DOM이 새로 그려졌으니 초기화만 다시 호출)
          var alreadyLoaded = Array.prototype.some.call(document.scripts, function(s) {
            return s.src === script.src;
          });
          if (alreadyLoaded) {
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
              console.error('[openHrPostPopup] share-panel.js 로드 실패');
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
      localStorage.setItem('resume_came_from_hr_post', 'true');
      localStorage.setItem('came_from_hr_list', 'true');
      sessionStorage.setItem('resume_highlight_ru_idx', ruIdx);

      // GSAP으로 팝업 열기 애니메이션
      gsap.fromTo('#hrPostPopup',
        {
          x: '100%'
        },
        {
          duration: 0.5,
          x: '0%',
          ease: "power1.out",
          clearProps: "transform",
          onComplete: function() {
            document.getElementById('hrPostPopup').setAttribute('data-state', 'open');
          }
        }
      );

      // 팝업 로드 후 이미지 lazy load 초기화
      if (typeof initLazyLoad === 'function') {
        initLazyLoad();
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
  // 카드 클릭으로 히스토리 항목을 추가했다면 history.back()으로 그 항목을 소비하고,
  // 실제 닫기는 popstate 핸들러가 __closeHrPostPopupNow()로 처리한다
  window.closeHrPostPopup = function(e) {
    if (e) e.preventDefault();

    if (window.hrPostHistoryPushed) {
      history.back();
      return;
    }
    window.__closeHrPostPopupNow();
  };

  // 실제 닫기 (히스토리는 건드리지 않음)
  window.__closeHrPostPopupNow = function() {
    window.hrPostHistoryPushed = false;

    // 진행 중인 선택 패널(공고 선택)이 열려 있으면 닫기 (상태 잔존 방지)
    var $selectPanel = $("#selectPanel");
    if ($selectPanel.length > 0) {
      $selectPanel.removeClass("on");
    }

    // GSAP으로 팝업 닫기 애니메이션
    gsap.to('#hrPostPopup', {
      duration: 0.5,
      x: '100%',
      ease: "power1.out",
      onComplete: function() {
        document.getElementById('hrPostPopup').setAttribute('data-state', 'closed');
        $('#hrPostPopup').html('');
      }
    });

    document.body.style.overflow = 'auto';
    document.documentElement.style.overflow = 'auto';
    applyLastClickedHighlight();
  };

  // 인재 상세 팝업 열림 여부: data-state="open"이면 열린 상태
  // (GSAP은 x 이동만 애니메이션하므로 opacity로는 판단할 수 없음)
  window.isHrPostPopupOpen = function() {
    var popup = document.getElementById('hrPostPopup');
    return !!popup && popup.getAttribute('data-state') === 'open';
  };
</script>

<!-- 페이지네이션 헬퍼 스크립트 -->
<?= getCashJs(RES_PATH . "/js/pagination-helper.js") ?>
