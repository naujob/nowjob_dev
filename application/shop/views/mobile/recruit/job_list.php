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
$list_v2 = true;

?>
<?php if (!empty($has_saved_search_condition) && !empty($initial_search_state)) { ?>
<script>
  // 앱을 완전히 종료했다 다시 켠 경우: sessionStorage는 앱 종료와 함께 비워지므로
  // 이 앱 실행 중 검색 상태(key)가 없으면 서버에 남은 저장 조건을 지우고 다시 불러온다
  window.__searchResetPending = false;
  (function() {
    var stateKey = <?php echo json_encode($initial_search_state['key']); ?>;
    var hasRunState = true;
    try {
      hasRunState = !!sessionStorage.getItem(stateKey);
    } catch (e) {}
    if (hasRunState) {
      return;
    }
    window.__searchResetPending = true;
    var url = new URL(location.href);
    url.searchParams.set('reset_search', '1');
    location.replace(url.toString());
  })();
</script>
<?php } ?>
<?php if (!empty($initial_search_state)) { ?>
<script>
  // 서버가 목록에 적용한 검색조건(주소 검색조건 > 저장된 조건)을 상세검색 저장값으로 넣음
  // → 칩/체크박스 복원은 기존 흐름을 그대로 쓰고, 목록은 서버가 그린 것을 사용 (진입 시 재조회 없음)
  (function() {
    // 앱 재실행으로 저장 조건을 지우는 중이면 이전 조건을 sessionStorage에 다시 쓰지 않는다
    if (window.__searchResetPending) {
      return;
    }
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
<script>
  // 언어 전환 새로고침 직후: 전환 직전에 기억해 둔 자리(mlv2_lang_restore)가 있으면 그 자리에 맞춰질 때까지 화면을 가린다
  // 값은 읽는 즉시 지운다(1회용) → 다른 화면에 갔다 오면 항상 처음부터 보인다
  (function() {
    if (window.__searchResetPending) {
      return;
    }
    try {
      var raw = sessionStorage.getItem('mlv2_lang_restore');
      if (!raw) {
        return;
      }
      sessionStorage.removeItem('mlv2_lang_restore');
      var ticket = JSON.parse(raw);
      if (!ticket || ticket.screen !== 'recruit' || (Date.now() - ticket.time) > 15000) {
        return;
      }
      window.__mlv2LangRestore = ticket;
      document.documentElement.classList.add('mlv2-restoring');
      // 복원이 끝나지 않아도 화면이 가려진 채 남지 않게 하는 안전장치
      setTimeout(function() {
        if (typeof window.__mlv2FinishLangRestore === 'function') {
          window.__mlv2FinishLangRestore();
        } else {
          document.documentElement.classList.remove('mlv2-restoring');
        }
      }, 4000);
    } catch (e) {}
  })();
</script>
<?= getCachCss(RES_PATH . "/css/m_list_v2.css") ?>
<?= getCachCss(RES_PATH . "/css/m_card_v2.css") ?>
<?= getCachCss(RES_PATH . "/css/job_list.css") ?>
<?= getCachCss(RES_PATH . "/css/m_detail_v2.css") ?>
<?= getCachCss(RES_PATH . "/css/m_job_post_v2.css") ?>
<style>
  /* 페이지 스타일은 job_list.css로 분리됨. 추가/오버라이드가 필요할 때만 여기에 작성 */

  /* 검색지역 설정 패널 닫기 버튼 위치 (job_list 전용)
     addr_popup2025_new.php의 같은 규칙은 다른 페이지가 쓰므로 그대로 둔다.
     #changeArea를 붙여 특이도를 높여서 뒤에 include되는 addr_popup 규칙보다 우선하게 함 */
  #changeArea .panel-header .btn-close-panel.join_mem_finish {
    position: absolute;
    top: -53px;
    right: -7px;
  }

</style>

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

  <!-- 오늘 채용 현황 마퀴 배너 -->
  <?php
    $status_week = array('일', '월', '화', '수', '목', '금', '토');
    // 날짜 표시: 사전의 '월'·'일'은 요일 뜻(월요일·일요일)으로 등록된 글자라, 날짜의 月·日은 사전에 기대지 않고 여기서 직접 적는다
    // 요일은 사전의 한자식 표기(月·火·水·木·金·土·日)를 그대로 쓴다
    // 한국어: 11월 17일(월) / 중국어: 11月17日(月)
    if (site_lang() === 'zh') {
      $status_tag = date('n') . '月' . date('j') . '日(' . t($status_week[date('w')]) . ')';
    } else {
      $status_tag = date('n') . '월 ' . date('j') . '일(' . $status_week[date('w')] . ')';
    }
    // 단위 글자는 t()로 번역 (중국어: 个 / 条)
    $status_items = [
      ['label' => t('채용공고'), 'value' => number_format($all_recruit_count) . t('개')],
      ['label' => t('신규공고'), 'value' => number_format($today_recruit_count) . t('건')],
    ];
    $status_items_html = '';
    foreach ($status_items as $item) {
      $status_items_html .= '<span class="marquee-item"><span class="label">' . $item['label'] . '</span><span class="value">' . $item['value'] . '</span></span>';
    }
  ?>
  <div class="section recruit-status-banner" id="recruitStatusBanner">
    <div class="marquee-wrap">
      <span class="mq-tag"><?= $status_tag ?></span>
      <div class="mq-view">
        <div class="marquee-track"><?php for ($mq = 0; $mq < 4; $mq++) { ?><span class="mq-set"<?= $mq ? ' aria-hidden="true"' : '' ?>><?= $status_items_html ?></span><?php } ?></div>
      </div>
    </div>
  </div>

  <!-- 추천기업 -->
  <?php if (!empty($hot_companies['chunks'])) : ?>
  <div class="section" id="hotListSection">
    <div class="container">
      <h2 class="secondary-title index" style="margin: 0 0px 1.1rem;"><?= t('추천기업') ?></h2>
      <div class="hot-list" style="display: flex; flex-direction: column;">
        <div class="slider-wrap keen-slider">
          <?php foreach ($hot_companies['chunks'] as $chunk) : ?>
            <div class="keen-slider__slide">
              <div class="hot-grid">
                <?php foreach ($chunk as $row) : ?>
                  <a href="/recruit/company/<?= (int) $row["mm_idx"] ?>" class="hot-item nvm-comp">
                    <div class="nvm-logo"><img src="<?= htmlspecialchars($row['comp_img'], ENT_QUOTES, 'UTF-8') ?>" alt=""></div>
                    <div class="nvm-txt">
                      <strong class="nvm-name"><?= htmlspecialchars($row['mm_comp_name'], ENT_QUOTES, 'UTF-8') ?></strong>
                      <p class="nvm-desc"><?= htmlspecialchars($row['comp_desc'], ENT_QUOTES, 'UTF-8') ?></p>
                      <span class="nvm-live"><?= t('공고') ?> <?= number_format((int) $row['cnt']) ?><?= t('건') ?></span>
                    </div>
                    <span class="nvm-go"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></span>
                  </a>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <?php if (count($hot_companies['chunks']) > 1) : ?>
          <div class="slider-dots" id="hotSliderDots"><?php foreach ($hot_companies['chunks'] as $dot_idx => $chunk) : ?><div class="dot<?= ($dot_idx === 0) ? ' active' : '' ?>"></div><?php endforeach; ?></div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <div class="section is-last">
    <div class="container">
       <h2 class="secondary-title" style="margin: 0.3rem 0px 1.1rem;"><?= t('채용공고') ?></h2>
      <ol class="result-list result-list-card mlv2-rc" id="ajaxList">
      <?php
        // 초기 공고 카드 (AJAX 페이지 이동/검색과 같은 파셜로 그림 — recruit_proc::getRecruitList(render=html))
        $card_list = ($use_server_render && isset($initial_recruits['list']) && is_array($initial_recruits['list'])) ? $initial_recruits['list'] : array();
        include(APPPATH . 'views/mobile/_partial/recruit_list_card.php');
      ?>
      </ol>
      <div id="ajaxEmpty" class="is-empty_v2" style="display:none;background:#F4F4F4;">
        <p><?= t('등록된 정보가 없습니다.') ?></p>
      </div>
      <!-- 목록 끝 표지판: 화면에 들어오면 다음 20건을 이어 불러온다 (동작: list-infinite-scroll.js, 모양: m_list_v2.css)
           data-state = idle(평소) | loading(받는 중) | error(실패) | end(마지막까지 봄) -->
      <div id="listMore" class="mlv2-more" data-state="idle">
        <span class="mlv2-more-spinner" aria-hidden="true"></span>
        <button type="button" class="mlv2-more-retry"><?= t('다시 시도') ?></button>
      </div>
    </div>
  </div>
  <!-- <div class="list-action">
    <a href="/recruit/job_map" class="btn-map"><span class="sr-text">지도보기</span></a>
  </div> -->

</div><!-- //page-content -->
<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/main_menu.php'); ?>
<?php include_once(get_base_url() . '/application/shop/views/mobile/_partial/scroll_top_button.php'); ?>
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

<!-- 목록 이어 불러오기 공통 스크립트 (아래 화면 스크립트가 바로 쓰므로 먼저 불러온다) -->
<?= getCashJs(RES_PATH . "/js/list-infinite-scroll.js") ?>

<script>
  // ========================================
  // 1️⃣  전역 변수 선언
  // ========================================

  // PHP에서 미리 변환된 주소 데이터 (서버사이드 렌더링)
  var ad_addr = <?= json_encode(removeBigAddr(isset($addr_info["ad_addr"]) ? $addr_info["ad_addr"] : ""), JSON_UNESCAPED_UNICODE) ?>;
  var list_id = "#ajaxList";
  var list_empty_id = "#ajaxEmpty";
  var list_page = 1; // 화면 밖 코드(지역 팝업·상세검색·조건 칩)가 1로 되돌리는 값. 실제 판단은 list_loaded_page로 한다
  var list_loaded_page = 1; // 마지막으로 받은 묶음(서버 페이지) 번호
  var list_loading = false; // 목록 교체 조회 중 여부 (조회 동안 목록을 흐리게)
  var list_has_more = false; // 다음 묶음이 남아 있는지
  var list_more_failed = false; // 다음 묶음 요청이 실패한 상태인지 ("다시 시도" 버튼으로만 재요청)
  var list_xhr = null; // 진행 중인 목록 요청 (한 번에 하나만)
  var list_request_seq = 0; // 요청 번호 (늦게 도착한 이전 응답을 버리는 데 사용)
  var list_restoring = false; // 언어 전환 후 자리 복원 중 여부
  var initial_item_count = 0; // 서버가 첫 화면에 그린 카드 수
  var search_addr = [];
  var searchAreaStr = "";
  var addrName = "";
  var loadDataInitialized = false;// 데이터 로드 초기화 여부
  var ITEMS_PER_PAGE = 20;
  var total_count = 0;
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

  // 상세 팝업을 열 때 히스토리 항목을 하나 늘린다 (주소는 그대로)
  // 닫기 버튼·뒤로가기가 이 항목을 소비하면서 팝업을 닫는다 (closeJobPostPopup / popstate 참고)
  function pushPopupHistory() {
    history.pushState({ popup: true }, "", location.href);
  }

  // 예전 주소에 남아 있는 page 값을 떼어 낸다 (이어 불러오기 방식에서는 쓰지 않음, 히스토리는 늘리지 않음)
  function removePageFromUrl() {
    var params = $.grep(window.location.search.replace(/^\?/, "").split("&"), function(p) {
      return p && p.indexOf("page=") !== 0;
    });
    var url = location.pathname + (params.length ? "?" + params.join("&") : "");
    if (url !== location.pathname + location.search) {
      history.replaceState(history.state, "", url);
    }
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

  // ========================================
  // 3️⃣  데이터 조회 함수들 (AJAX)
  // ========================================

  // 목록 이어 불러오기 관리 객체 (끝 감지, 화면 근처 카드만 남기기, 중복 제거 — list-infinite-scroll.js)
  var listScroller = ListInfiniteScroll.create({
    listSelector: list_id,
    moreSelector: "#listMore",
    keyAttr: "data-rc_idx",
    onNeedMore: function() {
      loadNextPage();
    },
    onNeedChunk: function(page) {
      requestList("fill", "N", page);
    }
  });

  // 목록 로딩 상태 표시 (data-loading="true"인 동안 기존 목록을 흐리게 유지, CSS에서 처리)
  function setListLoading(isLoading) {
    list_loading = isLoading;
    $(list_id).attr("data-loading", isLoading ? "true" : "false");
  }

  // 다음 묶음이 남았는지 정하고 목록 끝 표지판 상태를 맞춘다
  // listLen: 방금 받은(또는 서버가 그린) 묶음의 카드 수
  function updateHasMore(listLen) {
    var isLast = (listLen < ITEMS_PER_PAGE) || (total_count > 0 && list_loaded_page * ITEMS_PER_PAGE >= total_count);
    list_has_more = !isLast;
    list_more_failed = false;
    listScroller.setState(list_has_more ? "idle" : "end");
    if (list_has_more) {
      listScroller.check(); // 표지판이 아직 화면 안이면(카드가 적거나 전부 중복) 바로 다음 묶음 요청
    }
  }

  // 조건이 바뀌었을 때: 처음 20건을 새로 받아 목록을 교체한다
  // 화면 밖 코드(지역 팝업·상세검색·조건 칩)가 이 이름으로 부른다. list_page를 1로 돌려놓지 않고 불러도 항상 처음부터 받는다.
  // save_yn === "Y"이면 같은 요청에서 검색조건도 저장 — 다음 진입 때 서버가 이 조건으로 목록을 그림
  function getBoardList(save_yn) {
    requestList("replace", save_yn);
  }

  // 스크롤이 목록 끝에 닿았을 때: 다음 20건을 받아 뒤에 덧붙인다
  function loadNextPage() {
    if (list_xhr || !list_has_more || list_more_failed || list_restoring) {
      return;
    }
    requestList("append", "N");
  }

  // 서버에 목록을 요청하고 응답을 처리한다
  // mode: "replace"(처음 20건으로 교체) | "append"(다음 20건을 뒤에 덧붙임) | "fill"(언어 전환 직후 아직 받지 않은 묶음 채우기)
  // fill_page: mode가 "fill"일 때 받을 묶음(페이지) 번호
  function requestList(mode, save_yn, fill_page) {
    var page = (mode === "replace") ? 1 : ((mode === "fill") ? fill_page : list_loaded_page + 1);

    if (list_xhr) {
      if (mode !== "replace") {
        // 받는 중에는 새 요청을 내지 않는다 (채우기는 다음에 다시 시도되도록 표시만 푼다)
        if (mode === "fill") {
          listScroller.failChunk(page);
        }
        return;
      }
      // 조건이 바뀐 요청이 우선: 진행 중이던 요청을 끊는다
      list_xhr.abort();
      list_xhr = null;
    }

    var seq = ++list_request_seq;
    var params = {
      page: page,
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

    // 기다리는 동안의 표시: 교체는 기존 목록을 흐리게, 덧붙이기는 목록은 그대로 두고 표지판에 스피너
    if (mode === "replace") {
      setListLoading(true);
    } else if (mode === "append") {
      listScroller.setState("loading");
    }

    list_xhr = $.ajax({
      url: "/recruit_proc/getRecruitList",
      type: "post",
      data: params,
      traditional: false,
      dataType: "json",
      crossDomain: true,
      success: function(ret) {
        if (seq !== list_request_seq) {
          return; // 더 새로운 요청이 나간 뒤 도착한 이전 응답은 버린다
        }
        list_xhr = null;

        if (!(ret.code === "00" && ret.data && typeof ret.data.list_html === "string")) {
          handleListFail(mode, page);
          return;
        }

        var listLen = parseInt(ret.data.list_count, 10) || 0;
        var serverTotal = parseInt(ret.data.total, 10);
        if (isNaN(serverTotal) || serverTotal < 0) {
          serverTotal = 0;
        }
        // 서버가 카드 파셜(recruit_list_card.php)로 그린 HTML + 묶음 끝 광고 자리
        var listHtml = ret.data.list_html + (listLen > 0 ? getAdHtml() : "");

        if (mode === "replace") {
          setListLoading(false);
          total_count = serverTotal;
          list_loaded_page = 1;
          list_page = 1;
          listScroller.replaceAll(listHtml);
          listScroller.scrollToTop(); // 목록이 20건으로 줄었으므로 맨 위에서 다시 시작
          showEmpty();
          applyLastClickedHighlight();
          updateHasMore(listLen);
        } else if (mode === "append") {
          if (serverTotal > 0) {
            total_count = serverTotal;
          }
          list_loaded_page = page;
          list_page = page;
          listScroller.append(listHtml);
          updateHasMore(listLen);
        } else {
          // 채우기: 가장 뒤 묶음을 채운 경우(언어 전환 후 보던 묶음)에만 "다음 묶음 위치"를 옮긴다
          var isNewLast = (page > list_loaded_page);
          if (isNewLast) {
            if (serverTotal > 0) {
              total_count = serverTotal;
            }
            list_loaded_page = page;
            list_page = page;
          }
          listScroller.fillChunk(page, listHtml);
          if (isNewLast) {
            updateHasMore(listLen);
          }
        }
      },
      error: function(xhr, status, error) {
        if (seq !== list_request_seq) {
          return;
        }
        list_xhr = null;
        if (status === "abort") {
          return; // 조건 변경으로 일부러 끊은 요청은 실패로 치지 않는다
        }
        handleListFail(mode, page);
      }
    });
  }

  // 목록 요청 실패 처리
  function handleListFail(mode, page) {
    if (mode === "replace") {
      // 기존 목록 유지 (목록이 비어 있으면 빈 상태 표시)
      setListLoading(false);
      showEmpty();
      listScroller.setState(list_has_more ? "idle" : "end");
    } else if (mode === "append") {
      // "다시 시도" 버튼을 보여 주고, 누를 때까지 자동으로 다시 요청하지 않는다
      list_more_failed = true;
      listScroller.setState("error");
    } else {
      listScroller.failChunk(page);
    }
  }

  // ========================================
  // 4️⃣  UI 렌더링 함수들
  // ========================================

  // 묶음(20건) 끝에 붙일 광고 자리 HTML — 광고를 쓰지 않으므로 항상 빈 문자열 (아래는 다시 쓸 때를 위한 보관용 주석)
  function getAdHtml() {
    // var isDevEnv = window.location.hostname.includes('dev') || window.location.pathname.includes('/dev_nowapk');

    // if (isDevEnv) {
    //   return '<li class="list-item ad-item-adsense" style="position: relative; background: #f0f0f0; border: 2px dashed #ccc; padding: 20px; text-align: center; color: #999; font-size: 12px;">광고 자리 (개발 환경)</li>';
    // } else {
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
    // }
    return "";
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


  // ========================================
  // 5️⃣  초기화 함수들
  // ========================================

  function initLoadData() {
    if (loadDataInitialized) {
      return;
    }
    loadDataInitialized = true;

    // 서버가 검색조건(URL 조건 → 없으면 저장된 조건)을 적용해 처음 20건을 이미 그렸으므로
    // 그 결과를 그대로 묶음 1로 등록한다 (진입 시 AJAX 재조회 없음, 결과가 0건이어도 동일)
    list_page = 1;
    list_loaded_page = 1;
    initial_item_count = $(list_id).find("li").not(".ad-item-adsense").length;
    total_count = <?= json_encode((int) $initial_total_count) ?>;

    if (initial_item_count > 0) {
      $(list_id).append(getAdHtml());
    }
    listScroller.startFromPage();

    showEmpty();
    applyLastClickedHighlight();
    removePageFromUrl();

    // 언어 전환 직후라면 자리 복원이 끝난 뒤에 다음 묶음 여부를 정한다 (복원 도중 다음 묶음이 끼어들지 않게)
    if (window.__mlv2LangRestore) {
      list_restoring = true;
      return;
    }
    updateHasMore(initial_item_count);
  }

  // ---------- 언어 전환 시 보던 자리 유지 ----------

  // 언어 스위치: 새로고침 없이 카드 목록만 새 언어로 교체한다 (js/m_lang_switch.js 가 쿠키를 바꾼 뒤 호출)
  window.onLangSwitch = function(newLang, done) {
    var anchor = listScroller.getAnchor();
    list_restoring = true;
    setListLoading(true); // 목록만 흐리게 (전체 화면 가리기 없음)

    if (list_xhr) { list_xhr.abort(); list_xhr = null; }
    var seq = ++list_request_seq;

    list_xhr = $.ajax({
      url: "/recruit_proc/getRecruitList",
      type: "post",
      dataType: "json",
      traditional: false,
      crossDomain: true,
      data: {
        page: 1,
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
        save_yn: "N",
        keep_seed: "Y", // 언어만 바뀌므로 목록 순서(seed)를 유지해 같은 공고를 번역해서 받는다
        render: "html"
      },
      success: function(ret) {
        if (seq !== list_request_seq) { finishLangSwitch(); return; }
        list_xhr = null;
        if (ret.code === "00" && ret.data && typeof ret.data.list_html === "string") {
          var listLen = parseInt(ret.data.list_count, 10) || 0;
          var serverTotal = parseInt(ret.data.total, 10);
          if (isNaN(serverTotal) || serverTotal < 0) serverTotal = 0;
          var listHtml = ret.data.list_html + (listLen > 0 ? getAdHtml() : "");
          total_count = serverTotal;
          list_loaded_page = 1;
          list_page = 1;
          listScroller.setState("idle");
          listScroller.replaceAll(listHtml);
          $("#total_count").html(serverTotal);
          showEmpty();
          applyLastClickedHighlight();
          updateHasMore(listLen);
          listScroller.restoreAt(anchor, finishLangSwitch);
        } else {
          finishLangSwitch();
        }
      },
      error: function(xhr) {
        if (xhr.statusText === "abort") return;
        list_xhr = null;
        finishLangSwitch();
      }
    });

    // 안전장치: 5초 후 강제 해제 (restoreAt이 무한 대기하는 경우 대비)
    setTimeout(function() { if (list_restoring) { finishLangSwitch(); } }, 5000);

    function finishLangSwitch() {
      list_restoring = false;
      setListLoading(false);
      if (typeof done === 'function') done(); // 버튼 moving 플래그 해제
    }
  };

  // 언어 스위치를 누른 순간(새로고침 직전) m_list_v2.js가 부른다: 화면 맨 위에 걸친 카드의 위치를 기억해 둔다
  // 저장하는 값은 카드 번호와 위치 숫자뿐이며, 새 화면에서 한 번 읽고 바로 지운다
  window.onBeforeLangSwitch = function() {
    try {
      var anchor = listScroller.getAnchor();
      anchor.screen = "recruit";
      anchor.time = Date.now();
      sessionStorage.setItem("mlv2_lang_restore", JSON.stringify(anchor));
    } catch (e) {}
  };

  // 새 화면에서 기억해 둔 자리로 맞춘다 (보던 묶음 하나만 새 언어로 받아 그 카드를 같은 높이에 놓는다)
  function startLangRestore() {
    var ticket = window.__mlv2LangRestore;
    if (!ticket) {
      return;
    }
    window.__mlv2LangRestore = null;
    if (!list_restoring) {
      finishLangRestore(); // 첫 묶음 등록 전에 목록이 교체된 경우: 복원 없이 화면만 다시 보이게
      return;
    }
    listScroller.restoreAt(ticket, finishLangRestore);
  }

  // 복원 종료(성공·실패·시간 초과 공통): 가려 둔 화면을 다시 보이게 한다
  function finishLangRestore() {
    var wasRestoring = list_restoring;
    list_restoring = false;
    window.__mlv2LangRestore = null;
    document.documentElement.classList.remove("mlv2-restoring");
    // 묶음 1에서 끝난 경우(1번째 묶음을 보던 중이었거나 복원 실패)에는 여기서 다음 묶음 여부를 정한다
    if (wasRestoring && list_loaded_page === 1) {
      updateHasMore(initial_item_count);
    }
  }
  window.__mlv2FinishLangRestore = finishLangRestore;

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
          pushPopupHistory();
        }
        // 뒤로가기는 여기서 처리하고 리스트 로드하지 않음
        return;
      }

      // 브라우저 뒤로가기로 이미 항목이 소비된 상태이므로 히스토리는 건드리지 않고 닫기만 한다
      window.__closeJobPostPopupNow();
      return;
    }
    // 팝업이 없을 때는 할 일이 없다 (페이지 단위 히스토리를 만들지 않으므로 목록을 다시 조회하지 않는다)
  });

  // 다음 묶음 요청이 실패했을 때 나오는 "다시 시도" 버튼
  $(document).on("click", "#listMore .mlv2-more-retry", function(e) {
    e.preventDefault();
    list_more_failed = false;
    loadNextPage();
  });

  // 폼 체크박스 클릭 이벤트
  $(document).on("click", ".form-checkbox", function(e) {
    e.stopPropagation();
  });

  // 공고 카드 클릭 이벤트 (팝업용 히스토리 항목 추가 + 팝업 열기)
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
    pushPopupHistory();
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
      commonAlert(t("이력서 이동 팝업을 불러올 수 없습니다. 잠시 후 다시 시도해주세요."), function() {});
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
      if (!TUTORIAL_POPUP_ENABLED) return;
      if (isFirst === 'Y') openTutorialPopup();
    };

    // 처음 설치 시에만 팝업 표시 (개발/운영 환경 상관없이)
    if (TUTORIAL_POPUP_ENABLED && isNativeApp()) {
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
    // 언어 전환 직후의 자리 복원은 위 스크롤 설정 복원(0·50·200ms)이 모두 끝난 뒤에 한다 (그 전에 맞추면 위치가 다시 풀릴 수 있음)
    setTimeout(startLangRestore, 260);
  });

  // ========================================
  // 8️⃣  기타 헬퍼 함수들
  // ========================================

  // 온보딩 튜토리얼 팝업 함수
  window.isTutorialOpen = function() {
    var popup = document.getElementById('tutorial-popup');
    return popup && popup.style.display !== 'none';
  };

  // 온보딩 튜토리얼 노출 임시 중단 스위치 (false면 어떤 경로로도 팝업을 띄우지 않음, 다시 켜려면 true)
  var TUTORIAL_POPUP_ENABLED = false;

  var TUTORIAL_TEXT ="나우잡에서<br><span class='highlight-text'>인재목록,인재지도,공고등록</span> 등 다양한 콘텐츠를<br>이용해보세요!";
  var CURRENT_TUTORIAL_PAGE = 0;

  function preventScroll(e) {
    e.preventDefault();
  }

  function openTutorialPopup() {
    // 스위치가 꺼져 있으면 호출 경로와 무관하게 팝업을 띄우지 않는다
    if (!TUTORIAL_POPUP_ENABLED) return;
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

  // 상세 본문(.jd-body)의 스크롤 위치를 y에 맞춘다 (언어 전환 직후 레이아웃이 늦게 잡히는 동안 위치 유지용)
  // 사용자가 손으로 스크롤을 시작하면 즉시 멈추고, 최대 1.5초 동안만 다시 맞춘다. 이미지 로드 시점에도 한 번 더 맞춘다
  function keepBodyScrollAt(body, y) {
    var userTouched = false;
    var stopAt = Date.now() + 1500;
    var stop = function() { userTouched = true; };
    body.addEventListener('touchstart', stop, { passive: true, once: true });
    body.addEventListener('wheel', stop, { passive: true, once: true });

    function tick() {
      if (userTouched || Date.now() > stopAt) return;
      if (Math.abs(body.scrollTop - y) > 1) body.scrollTop = y;
      window.requestAnimationFrame(tick);
    }
    tick();

    // 아직 안 받은 이미지가 로드되면 그때 다시 맞춘다
    var imgs = body.querySelectorAll('img');
    for (var i = 0; i < imgs.length; i++) {
      if (!imgs[i].complete) {
        imgs[i].addEventListener('load', function() {
          if (!userTouched) body.scrollTop = y;
        }, { once: true });
      }
    }
  }

  // 채용공고 팝업 함수들
  // inPlace=true: 이미 열린 팝업의 내용만 새 언어로 다시 받아 바꾼다 (언어 전환용: 닫기·열기 애니메이션 없이, 스크롤 위치 유지)
  window.openJobPostPopup = function(rcIdx, inPlace) {
    var popup = document.getElementById('jobPostPopup');
    if (!popup || popup.getAttribute('data-loading') === 'true') {
      return; // 로드 중이면 무시 (중복 클릭 방지)
    }
    // 일반 열기는 이미 열려 있으면 무시. 제자리 갱신은 열린 상태에서 들어오므로 예외
    if (!inPlace && popup.getAttribute('data-state') === 'open') {
      return;
    }
    popup.setAttribute('data-loading', 'true');

    $.ajax({
      // 언어 전환 재요청(inPlace)은 조회수를 올리지 않도록 lang_refresh=1을 붙인다
      url: '/recruit/job_post/' + rcIdx + (inPlace ? '?lang_refresh=1' : ''),
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).done(function(rawHtml) {
      // 스타일시트가 준비된 뒤에 HTML을 넣는다 (CSS 없이 보이는 깜빡임 방지, m_lang_switch.js)
      window.hoistPopupStylesheets(rawHtml, function(html) {
      // 바꾸기 전에 상세 본문의 스크롤 위치를 기억해 둔다 (제자리 갱신 후 복원)
      var prevBody = popup.querySelector('.jd-body');
      var prevScrollY = prevBody ? prevBody.scrollTop : 0;
      // 제자리 갱신이면 슬라이드 영역(이미 받은 이미지·초기화된 슬라이더)을 따로 떼어 둔다
      // 슬라이드는 언어와 무관하므로 새 HTML로 다시 그리면 이미지를 처음부터 다시 받게 된다
      var prevPhoto = inPlace ? popup.querySelector('.jd-photo') : null;
      // 지도도 언어와 무관하므로 같은 방식으로 떼어 둔다 (다시 그리면 지도 타일을 새로 받음)
      var prevMap = inPlace ? popup.querySelector('.jd-map') : null;
      popup.innerHTML = html;
      popup.setAttribute('data-loading', 'false');

      // 떼어 둔 슬라이드·지도 영역을 새 HTML의 같은 자리에 되돌려 끼운다 (노드를 그대로 옮기므로 슬라이더·확대·지도 기능도 유지됨)
      var sliderKept = false;
      if (prevPhoto) {
        var nextPhoto = popup.querySelector('.jd-photo');
        if (nextPhoto && nextPhoto.parentNode) {
          nextPhoto.parentNode.replaceChild(prevPhoto, nextPhoto);
          sliderKept = true;
        }
      }
      if (prevMap) {
        var nextMap = popup.querySelector('.jd-map');
        if (nextMap && nextMap.parentNode) {
          nextMap.parentNode.replaceChild(prevMap, nextMap);
        }
      }

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

      // 일반 열기일 때만: body 잠금, 열림 기록, 슬라이드 열기 애니메이션 (제자리 갱신은 건너뜀)
      if (!inPlace) {
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
      }

      // 팝업 로드 후 이미지 lazy load 초기화
      if (typeof initLazyLoad === 'function') {
        initLazyLoad();
      }

      // 팝업 로드 후 keen-slider 초기화
      // 슬라이드 영역을 그대로 남겼다면 기존 슬라이더를 쓰므로 다시 만들지 않는다 (중복 초기화 방지)
      if (!sliderKept && typeof KeenSlider !== 'undefined') {
        new KeenSlider(".view-visual-slider .slider-wrap", {
          loop: true,
        }, [
          sliderDots,
          addActiveClass
        ]);
      }

      // 팝업 로드 후 이미지 확대 기능 초기화 (magnificPopup)
      if (!sliderKept && typeof jQuery !== 'undefined') {
        $('#jobPostPopup .view-visual-slider').magnificPopup({
          delegate: 'img',
          type: 'image',
          showCloseBtn: false,
          closeOnContentClick: true,
          gallery: {
            enabled: true,
            navigateByImgClick: false
          }
        });
      }

      // 팝업 로드 후 상세정보 확대/핀치줌 기능 초기화
      if (typeof window.initJobPostDescFeatures === 'function') {
        window.initJobPostDescFeatures();
      }

      // 제자리 갱신이면 보던 스크롤 위치를 유지한다
      // 이미지·지연 로딩으로 위쪽 높이가 나중에 바뀌어도 위치가 밀리지 않도록, 사용자가 직접 스크롤하기 전까지 잠시 다시 맞춘다
      if (inPlace) {
        var nextBody = popup.querySelector('.jd-body');
        if (nextBody) keepBodyScrollAt(nextBody, prevScrollY);
      }
      });
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
  // 목록을 교체 방식으로 갱신하므로 지역 적용 시 기존 목록을 비우지 않는다 (응답이 오면 교체)
  window.__addrPopupKeepListOnApply = true;
</script>

<!-- app 인터셉트 레이어 스크립트 -->
<?= getCashJs(RES_PATH . "/js/recruit/job_list.js") ?>
<?= getCashJs(RES_PATH . "/js/m_list_v2.js") ?>
