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
$list_v2 = true;
?>
<?php if (!empty($has_saved_search_condition) && !empty($initial_search_state)) { ?>
<script>
  // 앱을 완전히 종료했다 다시 켠 경우: sessionStorage는 앱 종료와 함께 비워지므로
  // 이 앱 실행 중 검색 상태(key)가 없으면 서버에 남은 저장 조건을 지우고 다시 불러온다
  window.__resumeSearchResetPending = false;
  (function() {
    var stateKey = <?php echo json_encode($initial_search_state['key']); ?>;
    var hasRunState = true;
    try {
      hasRunState = !!sessionStorage.getItem(stateKey);
    } catch (e) {}
    if (hasRunState) {
      return;
    }
    window.__resumeSearchResetPending = true;
    var url = new URL(location.href);
    url.searchParams.set('reset_search', '1');
    location.replace(url.toString());
  })();
</script>
<?php } ?>
<script>
  // 언어 전환 새로고침 직후: 전환 직전에 기억해 둔 자리(mlv2_lang_restore)가 있으면 그 자리에 맞춰질 때까지 화면을 가린다
  // 값은 읽는 즉시 지운다(1회용) → 다른 화면에 갔다 오면 항상 처음부터 보인다
  (function() {
    if (window.__resumeSearchResetPending) {
      return;
    }
    try {
      var raw = sessionStorage.getItem('mlv2_lang_restore');
      if (!raw) {
        return;
      }
      sessionStorage.removeItem('mlv2_lang_restore');
      var ticket = JSON.parse(raw);
      if (!ticket || ticket.screen !== 'resume' || (Date.now() - ticket.time) > 15000) {
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
<?= getCachCss(RES_PATH . "/css/hr_list.css") ?>
<!-- Google AdSense 인피드 광고 스크립트 (비활성화) -->
<!-- <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3622369584322145"
  crossorigin="anonymous"></script> -->
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
<?php include_once(get_base_url() . '/application/shop/views/mobile/_partial/app_intercept_layer.php'); ?>
<div class="page-recruit">

  <!-- <a href="#alarmPanel" aria-haspopup="dialog" aria-controls="alarmPanel" class="btn-alarm js-toggle-panel">
      <span class="sr-text">미확인 알림메세지</span> -->
  <!-- 미확인 알림개수 -->
  <!-- <span class="cnt"><?= $g_alarm_cnt ?></span>
    </a> -->


  <div id="hrPostPopup" class="hr-post-popup" data-state="closed"></div>

  <div class="page-content">
    <?php include(APPPATH . 'views/mobile/_partial/main_visual.php'); ?>

    <!-- 오늘 인재 현황 마퀴 배너 -->
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
        ['label' => t('인재이력서'), 'value' => number_format($all_resume_count) . t('개')],
        ['label' => t('신규이력서'), 'value' => number_format($today_resume_active_count) . t('건')],
      ];
      $status_items_html = '';
      foreach ($status_items as $item) {
        $status_items_html .= '<span class="marquee-item"><span class="label">' . $item['label'] . '</span><span class="value">' . $item['value'] . '</span></span>';
      }
    ?>
    <div class="section resume-status-banner" id="resumeStatusBanner">
      <div class="marquee-wrap">
        <span class="mq-tag"><?= $status_tag ?></span>
        <div class="mq-view">
          <div class="marquee-track"><?php for ($mq = 0; $mq < 4; $mq++) { ?><span class="mq-set"<?= $mq ? ' aria-hidden="true"' : '' ?>><?= $status_items_html ?></span><?php } ?></div>
        </div>
      </div>
    </div>

    <!-- 추천인재 -->
    <div class="section index_comp" id="recommSection">
      <div class="container">
        <h2 class="secondary-title index" style="margin: 0 0px 1.1rem;"><?= t('추천인재') ?></h2>
        <?php if ($has_initial_reco && is_array($initial_reco)) : ?>
          <?php $reco_chunks = array_chunk(array_slice($initial_reco, 0, 12), 3); ?>
          <div class="hot-list" style="display: flex; flex-direction: column;">
            <div class="slider-wrap keen-slider" id="recomm-list">
              <?php foreach ($reco_chunks as $chunk) : ?>
                <div class="keen-slider__slide">
                  <div class="hot-grid">
                    <?php foreach ($chunk as $row) : ?>
                      <div class="hot-item nvm-comp nvm-person person-grid-item" data-ru_idx="<?= htmlspecialchars($row['ru_idx'], ENT_QUOTES, 'UTF-8') ?>" data-mm_idx="<?= htmlspecialchars($row['mm_idx'], ENT_QUOTES, 'UTF-8') ?>">
                        <div class="nvm-logo nvm-avatar"><img src="<?= htmlspecialchars($row['ru_resume_img'], ENT_QUOTES, 'UTF-8') ?>" alt=""></div>
                        <div class="nvm-txt">
                          <div class="nvm-badges"><?= $row['ru_work_badge'] ?></div>
                          <strong class="nvm-name"><?= htmlspecialchars($row['ru_name'], ENT_QUOTES, 'UTF-8') ?></strong>
                          <p class="nvm-addr"><?= htmlspecialchars($row['ad_addr'], ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                        <span class="nvm-go"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></span>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
            <?php if (count($reco_chunks) > 1) : ?>
              <div class="slider-dots" id="recommSliderDots"><?php foreach ($reco_chunks as $dot_idx => $chunk) : ?><div class="dot<?= ($dot_idx === 0) ? ' active' : '' ?>"></div><?php endforeach; ?></div>
            <?php endif; ?>
          </div>
        <?php else : ?>
          <p style="text-align:center;padding:20px;color:#999;"><?= t('추천인재가 없습니다.') ?></p>
        <?php endif; ?>
      </div>
    </div>

    <div class="section is-last">
      <div class="container">
        <h2 class="secondary-title" style="margin: 0.3rem 0px 1.1rem;"><?= t('인재정보') ?></h2>
        <ol class="result-list result-list-card mlv2-ru" id="ajaxList">
          <?php
          // 첫 화면 카드도 AJAX 응답과 같은 파셜로 그린다 (_partial/resume_list_card.php)
          $card_list = ($use_server_render && isset($initial_resumes) && is_array($initial_resumes)) ? $initial_resumes : array();
          include(APPPATH . 'views/mobile/_partial/resume_list_card.php');
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

<!-- 목록 이어 불러오기 공통 스크립트 (아래 화면 스크립트가 바로 쓰므로 먼저 불러온다) -->
<?= getCashJs(RES_PATH . "/js/list-infinite-scroll.js") ?>

<script>

  var currentUserId = "<?= $this->user->isLogin() ? $this->user->getUserIdx() : '' ?>";
  var ad_addr = <?php echo json_encode(isset($addr_info["ad_addr"]) ? $addr_info["ad_addr"] : ""); ?>;
  <?php if (!empty($initial_search_state)) { ?>
  // 서버가 목록에 적용한 검색조건을 상세검색 저장값으로 넣음 (칩/체크박스 복원은 기존 흐름, 목록은 서버 결과 사용)
  (function() {
    // 앱 재실행으로 저장 조건을 지우는 중이면 이전 조건을 sessionStorage에 다시 쓰지 않는다
    if (window.__resumeSearchResetPending) {
      return;
    }
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
  var search_addr = []; // 선택된 지역 코드 배열 (예: ["11010", "11020"])
  var searchAreaStr = "";
  var addrName = ""; // 현재 선택된 지역 이름 (예: "서울 강남구 역삼동")
  var searchStateLoaded = false; // loadSearchStateFromStorage()에서 설정되는 플래그 (중복 호출 방지)
  var ITEMS_PER_PAGE = 20; // 한 번에 로드할 아이템 수
  var total_count = 0; // 전체 아이템 수
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

  // 다음 묶음 요청이 실패했을 때 나오는 "다시 시도" 버튼
  $(document).on("click", "#listMore .mlv2-more-retry", function(e) {
    e.preventDefault();
    list_more_failed = false;
    loadNextPage();
  });

  $(document).on("click", ".form-checkbox", function(e) {
    e.stopPropagation();
  });

  // 상세 팝업을 열 때 히스토리 항목을 하나 늘린다 (주소는 그대로)
  // 닫기 버튼·뒤로가기가 이 항목을 소비하면서 팝업을 닫는다 (closeHrPostPopup / popstate 참고)
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
    if (nos.length !== area.length) return;
    if (nos.length) qs.push("area=" + nos.join(","));
    $.each([["category", "category"], ["work", "work_type"], ["pay", "price_type"], ["week", "week"], ["sex", "sex"]], function(i, p) {
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
      var raw = sessionStorage.getItem("jobListAdvancedSearch_resume_" + (currentUserId || "guest"));
      var state = raw ? JSON.parse(raw) : null;
      if (state && state.userId === currentUserId) updateSearchUrlFromState(state);
    } catch (e) {}
  }

  // 목록 이어 불러오기 관리 객체 (끝 감지, 화면 근처 카드만 남기기, 중복 제거 — list-infinite-scroll.js)
  var listScroller = ListInfiniteScroll.create({
    listSelector: list_id,
    moreSelector: "#listMore",
    keyAttr: "data-ru_idx",
    onNeedMore: function() {
      loadNextPage();
    },
    onNeedChunk: function(page) {
      requestList("fill", "N", page);
    }
  });

  function initLoadData() {
    // 서버가 저장된 조건까지 적용해 처음 20건을 이미 그렸으므로 그 결과를 그대로 묶음 1로 등록한다
    // (진입 시 AJAX 재조회 없음, 결과가 0건이어도 동일)
    list_page = 1;
    list_loaded_page = 1;
    initial_item_count = $(list_id).find("li").not(".ad-item-adsense").length;
    // 서버가 계산한 개수(저장 조건이 있으면 필터된 개수)를 그대로 사용
    total_count = <?php echo json_encode((int) $initial_total_count); ?>;

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

  // 언어 스위치를 누른 순간(새로고침 직전) m_list_v2.js가 부른다: 화면 맨 위에 걸친 카드의 위치를 기억해 둔다
  // 저장하는 값은 카드 번호와 위치 숫자뿐이며, 새 화면에서 한 번 읽고 바로 지운다
  window.onBeforeLangSwitch = function() {
    try {
      var anchor = listScroller.getAnchor();
      anchor.screen = "resume";
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
      finishLangRestore(); // 첫 묶음 등록 없이 목록이 교체된 경우(저장 조건 재조회): 복원 없이 화면만 다시 보이게
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
          pushPopupHistory();
        }
        return;
      }

      // 브라우저 뒤로가기로 항목이 이미 소비된 상태이므로 히스토리는 건드리지 않고 닫기만 한다
      window.__closeHrPostPopupNow();
      return;
    }
    // 팝업이 없을 때는 할 일이 없다 (페이지 단위 히스토리를 만들지 않으므로 목록을 다시 조회하지 않는다)
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
  // save_yn === "Y"이면 같은 요청에서 검색 조건도 저장
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

    // 기다리는 동안의 표시: 교체는 기존 목록을 흐리게, 덧붙이기는 목록은 그대로 두고 표지판에 스피너
    if (mode === "replace") {
      setListLoading(true);
    } else if (mode === "append") {
      listScroller.setState("loading");
    }

    list_xhr = $.ajax({
      url: "/resume_proc/search_list2025", // 검색 조건에 맞는 글 목록을 가져오는 API 엔드포인트
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
        // 서버가 카드 파셜(resume_list_card.php)로 그린 HTML + 묶음 끝 광고 자리
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

  // 묶음(20건) 끝에 붙일 광고 자리 HTML — 광고를 쓰지 않으므로 항상 빈 문자열 (아래는 다시 쓸 때를 위한 보관용 주석)
  function getAdHtml() {
    // 도메인 환경 판별
    // var isDevEnv = window.location.hostname.includes('dev') || window.location.pathname.includes('/dev_nowapk');

    // if (isDevEnv) {
    //   // 개발 환경에서는 CSS로만 자리 표시
    //   return '<li class="list-item ad-item-adsense" style="position: relative; background: #f0f0f0; border: 2px dashed #ccc; padding: 20px; text-align: center; color: #999; font-size: 12px;">' +
    //     '광고 자리 (개발 환경)' +
    //     '</li>';
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

      // // 비동기로 광고 로드 (setTimeout 사용)
      // setTimeout(function() {
      //   try {
      //     (adsbygoogle = window.adsbygoogle || []).push({});
      //   } catch (e) {
      //   }
      // }, 0); // 비동기 로드 (즉시)
    // }
    return "";
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
    var ruIdx = $card.data('ru_idx') || $card.find('.person-card').data('ru_idx');
    if (!ruIdx) {
      return;
    }

    pushPopupHistory();
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
        commonAlert(t("로그인 후 이용해주세요."), function() {
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
            commonAlert(ret.msg || t("채용공고 개수를 확인할 수 없습니다."), function() {});
          }
        },
        error: function(xhr, status, error) {

          commonAlert(t("채용공고 개수를 확인할 수 없습니다."), function() {});
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
  // 목록을 교체 방식으로 갱신하므로 지역 적용 시 기존 목록을 비우지 않는다 (응답이 오면 교체)
  window.__addrPopupKeepListOnApply = true;

  // [중복 방지] resume 페이지에서만 한 번만 실행
  window.addEventListener('DOMContentLoaded', function() {
    if (window.__hrListDOMContentLoaded) {
      return;
    }
    window.__hrListDOMContentLoaded = true;

   

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
    setTimeout(syncSearchUrlFromStorage, 300);
    // 언어 전환 직후의 자리 복원은 아래 스크롤 설정 복원(0·50·200ms)이 모두 끝난 뒤에 한다 (그 전에 맞추면 위치가 다시 풀릴 수 있음)
    setTimeout(startLangRestore, 260);

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
            return s.src === script.src && !popup.contains(s);
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

<?= getCashJs(RES_PATH . "/js/m_list_v2.js") ?>
