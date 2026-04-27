<?php
$banner_list = getBannerList("BN00010001");
$banner = null;
if (count($banner_list) > 0) {
  $banner = $banner_list[0];
}
?>
<style>
  .page-content {
    background: #fff;
  }

  .current-area .btn {
    max-width: 90%;
  }

  .search-option .categories .cate {
    display: none;
  }





  .search-option .categories .form-toggle-btn2 {
    display: none;
  }

  .search-option .categories .form-toggle-btn2.on {
    display: inline-block;
  }

  .search-option .categories {
    height: auto;
    margin-bottom: 10px;
  }

  .current-area .btn {
    max-width: 100%;
    font-size: 1.8rem;
  }
</style>
<div class="page-recruit">

  <!-- <a href="#alarmPanel" aria-haspopup="dialog" aria-controls="alarmPanel" class="btn-alarm js-toggle-panel">
      <span class="sr-text">미확인 알림메세지</span> -->
  <!-- 미확인 알림개수 -->
  <!-- <span class="cnt"><?= $g_alarm_cnt ?></span> -->

</div><!-- //header -->

<div class="page-content">
  <?php include(APPPATH . 'views/mobile/_partial/top_visual.php'); ?>

  <!-- 상세 검색 옵션 선택시: has-option 클래스 추가 -->
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
        <!-- <?= $search_txt["searchAreaStr"]; ?> -->
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

  <div class="section is-last">
    <style>
      .item-info .thumb {
        width: 5rem;
      }

      .item-info {
        padding: 0.4rem 0;
      }

      .item-info .comp_name {
        margin-top: 2px;
      }

      .item-info .title {
        margin: 2px 0 2px;
      }

      .item-info .meta {
        margin: 2px -2px 0 0;
      }

      .item-info .period {
        /* font-size: 0.7rem; */
      }

      .item-info .working-time {
        /* font-size: 0.7rem; */
        line-height: 1rem;
      }

      .item-info .area {
        max-height: 1.3rem;
        margin-top: 4px;
      }

      .item-info {
        margin: 5px 0;
      }

      .item-info .amount {
        letter-spacing: -2px;
      }

      /* 페이지네이션 스타일 */
      .pagination-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 5px;
        margin: 20px 0;
        padding: 10px 0;
      }

      .pagination-wrapper button,
      .pagination-wrapper a {
        padding: 8px 12px;
        border: 1px solid #ddd;
        background: #fff;
        color: #333;
        cursor: pointer;
        border-radius: 4px;
        font-size: 14px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
      }

      .pagination-wrapper button:hover,
      .pagination-wrapper a:hover {
        background: #f5f5f5;
      }

      .pagination-wrapper button.active,
      .pagination-wrapper a.active {
        background: #1B9D7D;
        color: #fff;
        border-color: #1B9D7D;
      }

      .pagination-wrapper button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
      }

      .pagination-wrapper button:disabled:hover {
        background: #fff;
      }
    </style>
    <div class="container">
      <h2 class="sr-text">채용공고</h2>
      <ol class="result-list result-list-card" id="ajaxList" data-page="1">
      </ol>
      <div id="ajaxEmpty" class="is-empty_v2" style="display:none;background:#F4F4F4;">
        <p>등록된 정보가 없습니다.</p>
      </div>
      <!-- 페이지네이션 -->
      <div class="pagination-wrapper" id="paginationWrapper" style="display:none;">
        <button id="prevBtn" class="prev-btn">이전</button>
        <div id="pageNumbers"></div>
        <button id="nextBtn" class="next-btn">다음</button>
      </div>
    </div>
  </div>
  <div class="list-action">
    <a href="/recruit/job_map" class="btn-map"><span class="sr-text">지도보기</span></a>
  </div>

</div><!-- //page-content -->
<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/main_menu.php'); ?>
</div>

<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/popup_alarm.php'); ?>

<?php $tpl_id = 'tpl_list';
$tpl_recomm_class = true; ?>
<?php include(APPPATH . 'views/mobile/_partial/recruit_card_template.php'); ?>

<template id="tpl_category">
  <label class="form-toggle-btn2 sm {{active}} btn-category">
    <input type="checkbox" name="filter" value="{{category_name}}">
    <span class="cate">{{category_name}}</span>
  </label>
</template>


<input type="hidden" name="resume_yn" value="<?= $resume == null ? "N" : "Y" ?>" />
<input type="hidden" name="mm_type" value="<?= $this->user->getUserType() ?>" />

<style>
  /* 팝업 스타일 */
  #applypop {
    position: fixed;
    top: 200px;
    /* 적절한 위치로 조정 */
    right: -5%;
    transform: translateX(-50%);
    background-color: #1B9D7D;
    color: white;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 100;
    white-space: nowrap;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    z-index: 10;
    display: none;
    /* 초기에는 숨김 */
  }

  /* 팝업의 화살표 (아래쪽 삼각형) */
  #applypop::after {
    content: "";
    position: absolute;
    bottom: 18px;
    right: -29px;
    transform: translateX(-26%);
    border-width: 12px;
    border-style: solid;
    border-color: transparent transparent transparent #1B9D7D;
  }
</style>


<script>
  var ad_addr = "<?= $addr_info["ad_addr"] ?>";
  var list_id = "#ajaxList";
  var list_empty_id = "#ajaxEmpty";
  var list_page = 1;
  var list_loading = false;
  var search_addr = [];
  var searchAreaStr = "";
  var addrName = "";
  var adShown = false;
  var adHidden = false;
  var menuOpen = false;
  var ITEMS_PER_PAGE = 20; // 한 페이지당 아이템 수
  var total_pages = 1; // 전체 페이지 수
  var current_page = 1; // 현재 페이지
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
  tab_list.push({
    df: "근무지역",
    text: "",
    active: ""
  }); //0 - 지역 정보
  tab_list.push({
    df: "카테고리",
    text: "",
    active: ""
  }); //1
  tab_list.push({
    df: "근무형태",
    text: "",
    active: ""
  }); //2
  tab_list.push({
    df: "근무요일",
    text: "",
    active: ""
  }); //3
  tab_list.push({
    df: "급여",
    text: "",
    active: ""
  }); //4
  tab_list.push({
    df: "성별",
    text: "",
    active: ""
  }); //5
  tab_list.push({
    df: "나이",
    text: "",
    active: ""
  }); //6

  $(document).on("click", ".btn-category", function() {
    $("#advancedSearch").addClass("on");
  });

  $(document).on("click", ".form-checkbox", function(e) {
    e.stopPropagation();
  });

  // 캐싱된 페이지 데이터 초기화
  function initCashingData() {
    current_page = 1;
    showEmpty();
  }

  // 초기 데이터 로드
  function initLoadData() {
    current_page = 1;
    list_page = 1;
    setTimeout(function() {
      getBoardList("");
    }, 100);
  }

  // 페이지네이션 렌더링 함수
  function renderPagination() {
    var pageNumbersDiv = document.getElementById('pageNumbers');
    pageNumbersDiv.innerHTML = '';

    // 페이지 번호 버튼 생성
    var maxVisiblePages = 5; // 한 번에 표시할 최대 페이지 수
    var startPage = Math.max(1, current_page - Math.floor(maxVisiblePages / 2));
    var endPage = Math.min(total_pages, startPage + maxVisiblePages - 1);

    // 시작 페이지 조정
    if (endPage - startPage + 1 < maxVisiblePages) {
      startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }

    for (var i = startPage; i <= endPage; i++) {
      var btn = document.createElement('button');
      btn.textContent = i;
      btn.className = i === current_page ? 'page-btn active' : 'page-btn';
      btn.setAttribute('data-page', i);
      btn.addEventListener('click', function() {
        var pageNum = parseInt(this.getAttribute('data-page'));
        goToPage(pageNum);
      });
      pageNumbersDiv.appendChild(btn);
    }

    // 페이지네이션 표시/숨김
    if (total_pages >= 1) {
      document.getElementById('paginationWrapper').style.display = 'flex';
    } else {
      document.getElementById('paginationWrapper').style.display = 'none';
    }

    // 이전/다음 버튼 활성화 제어
    document.getElementById('prevBtn').disabled = current_page === 1;
    document.getElementById('nextBtn').disabled = current_page === total_pages;
  }

  // 특정 페이지로 이동
  function goToPage(pageNum) {
    if (pageNum < 1 || pageNum > total_pages) return;
    current_page = pageNum;
    list_page = pageNum;
    getBoardList("");
    window.scrollTo(0, 0); // 페이지 상단으로 스크롤
  }
  // 공고 목록 가져오기
  function getBoardList(save_yn) {
    var params = {
      page: current_page,
      limit: ITEMS_PER_PAGE,
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
    };

    if (save_yn === "Y") {
      $.ajax({
        url: "/recruit_proc/search_save_params2025",
        type: "post",
        data: params,
        traditional: false,
        dataType: "json",
        crossDomain: true,
        success: function(ret) {
          if (ret.code === "00") {
            //console.log('설정저장');
          }
        },
        error: function(xhr, status, error) {
          console.error("데이터 저장 오류:", error);
        }
      });
    }

    list_loading = true;
    $.ajax({
      url: "/recruit_proc/getRecruitList",
      type: "post",
      data: params,
      traditional: false,
      dataType: "json",
      crossDomain: true,
      success: function(ret) {
        list_loading = false;
        console.log('API 응답:', ret);
        if (ret.code === "00" && ret.data && Array.isArray(ret.data.list)) {
          // 전체 페이지 수 계산
          var total_count = ret.data.total || ret.data.count || ret.data.list.length;
          total_pages = Math.ceil(total_count / ITEMS_PER_PAGE);

          console.log('전체 아이템 수:', total_count, '전체 페이지:', total_pages, 'ITEMS_PER_PAGE:', ITEMS_PER_PAGE);

          // 목록 초기화 및 새 데이터 표시
          $(list_id).empty();
          setTimeout(function() {
            showBoardList(ret.data.list);
            renderPagination();
          }, 100);
        } else {
          console.error('API 응답 에러:', ret);
        }
      },
      error: function(xhr, status, error) {
        list_loading = false;
        console.error("데이터 가져오기 오류:", error);
      }
    });
  }

  //데이터 보여주기
  function showBoardList(list) {

    list.forEach(function(row) {

      var $clone = $("#tpl_list").clone();
      var $content = $clone.html();

      if (row.pm_idx == null) {
        $content = $content.replaceAll("<div class=\"content-li recomm\"", "<div class=\"content-li\"");
        $content = $content.replaceAll("<p class=\"recomm-badge\"><span>추천</span></p>", "");
      }

      if (row.rc_nation_nm == "무관" || row.rc_nation_nm == "내국인,외국인") {
        $content = $content.replaceAll("{{rc_nation_nm}}", "국적무관");
      } else {
        $content = $content.replaceAll("{{rc_nation_nm}}", row.rc_nation_nm);
      }

      if (row.rc_age_nm == "무관") {
        $content = $content.replaceAll("{{rc_age_nm}}", "나이무관");
      } else {
        $content = $content.replaceAll("{{rc_age_nm}}", row.rc_age_nm);
      }

      $content = $content.replaceAll("{{rc_idx}}", row.rc_idx);
      $content = $content.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
      $content = $content.replaceAll("{{ad_addr}}", fRemoveBigAdrJS(row.ad_addr));
      // 제목이 18자 이상이면 ...으로 자르기
      var rc_name = row.rc_name;
      if (rc_name && rc_name.length > 18) {
        rc_name = rc_name.substring(0, 18) + '...';
      }
      $content = $content.replaceAll("{{rc_name}}", rc_name);


      $content = $content.replaceAll("{{rt_recruit_img}}", row.rt_recruit_img);
      $content = $content.replaceAll("{{rt_work_img}}", row.rt_work_img);
      $content = $content.replaceAll("{{rt_work_type}}", row.rt_work_type || "");
      $content = $content.replaceAll("{{rc_price_type}}", row.rt_price_type || '');
      $content = $content.replaceAll("{{rc_price_pay}}", row.rt_price_pay || '');

      $clone.html($content);
      $(list_id).append($clone.html());

    });

    showEmpty();

  }



  function showEmpty() {
    if ($(list_id).find("li").length == 0) {
      $(list_empty_id).show();
      $(list_id).hide();
    } else {
      $(list_id).show();
      $(list_empty_id).hide();
    }
  }

  // 페이지네이션 버튼 이벤트 바인딩
  $(document).on('click', '#prevBtn', function() {
    if (current_page > 1) {
      goToPage(current_page - 1);
    }
  });

  $(document).on('click', '#nextBtn', function() {
    if (current_page < total_pages) {
      goToPage(current_page + 1);
    }
  });

  // 이력서 작성 버튼 클릭 이벤트 (공통 팝업 호출)
  $("#btnResumeList").click(function(e) {
    e.preventDefault();
    if (typeof window.openResumeNavigationPopup === 'function') {
      openResumeNavigationPopup();
    } else {
      commonAlert("이력서 이동 팝업을 불러올 수 없습니다. 잠시 후 다시 시도해주세요.", function() {});
    }
  });
</script>
<?php include_once(get_base_url() . '/application/shop/views/mobile/recruit/index_search.php'); ?>
<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/addr_popup2025_new.php'); ?>
<script>
  window.addEventListener('DOMContentLoaded', function() {
    // 스크롤 강제 활성화 (모바일 웹뷰에서 overflow:hidden 잔존 대응)
    $('html, body').css({
      overflow: 'auto',
      'overflow-y': 'auto',
      height: 'auto'
    });

    const count = $('.selbtn').length;
    var addrNames = [];

    if ($('.selbtn').length > 0) {
      $('.selbtn').each(function() {
        const text = $(this).clone().children().remove().end().text().trim();
        addrNames.push(text);
      });
      addrName = addrNames.join(', ');
      $('.addrName').text(addrName);
    } else {
      addrName = '전체';
      $('.addrName').text(addrName);
    }

    const searchArea = [];
    search_addr = [];

    $('.selbtn').each(function() {
      const addr = $(this).data('addr');
      const step = $(this).data('step');
      if (addr) {
        searchArea.push({
          addr: addr,
          step: step
        });
        search_addr.push(addr);
      }
    });
    searchAreaStr = JSON.stringify(searchArea);

    // 페이지 로드 시 선택된 지역 정보를 selectedRegions에 반영
    if (typeof selectedRegions === 'undefined') {
      window.selectedRegions = [];
    }
    if (addrNames.length > 0) {
      window.selectedRegions = addrNames.slice(0, 3); // 최대 3개까지만
    } else {
      window.selectedRegions = [];
    }

    initCategory();
    initTabUi();

    var params = {
      cashing_id: "recruit_cashing",
      list_id: list_id,
      category_list_id: "#tab_list",
      scroll_obj: $(".page-recruit"),
      fn_cashing: initCashingData,
      fn_load: initLoadData,
    };
    $.CashingAjax(params);

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

    // 네이티브 광고 제어 (스크롤 감지 + 메뉴 opacity 감지)
    if (typeof initNativeAdControl === 'function') {
      initNativeAdControl();
    }
  });
  // 지역 선택 팝업 적용 시 카테고리 텍스트 숨김
  window.onChangeAreaApply = function() {
    $(".search-option").addClass("hide-cate");
    if (typeof saveSearchStateToStorage === "function") {
      saveSearchStateToStorage();
    }
  };

  // 헬퍼: 광고 제어
  var hideAd = function() {
    if (window.nowjob && typeof window.nowjob.hideNativeAd === 'function') {
      window.nowjob.hideNativeAd();
    }
  };

  // 공고 제목 클릭 시 광고 숨기기
  $(document).on('click', '.desc', function() {
    var rcIdx = $(this).closest('.content-li').data('rc_idx');
    if (rcIdx) {
      hideAd();
      window.location.href = '/recruit/job_post/' + rcIdx;
    }
  });

  // 내부 링크 클릭 시 광고 숨기기 (job_list 제외)
  $(document).on('click', 'a[href^="/"]', function() {
    var href = $(this).attr('href');
    if (href && !href.includes('job_list')) {
      hideAd();
    }
  });

  // 페이지 이동/이탈 시 광고 숨기기
  window.addEventListener('beforeunload', hideAd);

  // 스와이프 뒤로가기 비활성화
  window.DISABLE_SWIPE_BACK = true;
</script>