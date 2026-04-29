<?php
$banner_list = getBannerList("BN00302031");
$banner = null;
if (count($banner_list) > 0) {
  $banner = $banner_list[0];
}
?>
<style>
  /* 카드 스타일은 main.css의 .result-list-card 섹션에서 관리 */

  .page-content {
    background: #fff;
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

  /* 지금 뜨는 업체 슬라이더 양쪽 peek */
  .section:has(.hot-list) .container {
    overflow: visible;
  }

  .section:has(.hot-list) {
    overflow: hidden;
  }

  /* Google AdSense 인피드 광고 스타일 */
  .ad-item-adsense {
    list-style: none;
    margin: 15px 0;
    padding: 0;
    position: relative;
  }

  .ad-item-adsense::before {
    content: "AD";
    position: absolute;
    top: 8px;
    left: 8px;
    font-size: 10px;
    font-weight: 700;
    color: #999;
    z-index: 1;
  }

  .ad-item-adsense .adsense-wrapper {
    border-top: 1px solid #e0e0e0;
    border-bottom: 1px solid #e0e0e0;
    padding: 10px 0;
    background-color: #fafafa;
  }

  .ad-item-adsense ins.adsbygoogle {
    display: block;
    background-color: transparent;
  }
</style>
<!-- Google AdSense 인피드 광고 스크립트 -->
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-7573726661605583"
  crossorigin="anonymous"></script>
<div class="page-recruit">

  <!-- <a href="#alarmPanel" aria-haspopup="dialog" aria-controls="alarmPanel" class="btn-alarm js-toggle-panel">
      <span class="sr-text">미확인 알림메세지</span> -->
  <!-- 미확인 알림개수 -->
  <!-- <span class="cnt"><?= $g_alarm_cnt ?></span>
    </a> -->


  <div class="page-content">
    <?php include(APPPATH . 'views/mobile/_partial/main_visual.php'); ?>

    <!-- 지금 뜨는 업체 -->
    <div class="section">
      <div class="container">
        <h2 class="secondary-title index">지금 뜨는 업체</h2>
        <div class="hot-list">
          <div class="slider-wrap keen-slider">
            <?php
            $limited_up_list = isset($up_list) && is_array($up_list) ? array_slice($up_list, 0, 8) : array();
            $chunked_up_list = array_chunk($limited_up_list, 4);
            // 마지막 슬라이드가 4개 미만인 경우 빈 슬롯 추가
            if (!empty($chunked_up_list)) {
              $last_idx = count($chunked_up_list) - 1;
              while (count($chunked_up_list[$last_idx]) < 4) {
                $chunked_up_list[$last_idx][] = null;
              }
            }
            foreach ($chunked_up_list as $chunk) :
            ?>
              <div class="keen-slider__slide">
                <div class="hot-grid">
                  <?php foreach ($chunk as $row) : ?>
                    <?php if ($row !== null) : ?>
                      <a href="/recruit/company/<?= $row["mm_idx"] ?>" class="hot-item">
                        <div class="thumb">

                          <img data-src="<?= getCompImg($row) ?>" alt="" class="lozad">

                          <p class="name"><?= $row["mm_comp_name"] ?></p>
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
        </div>
      </div>
    </div>

    <div class="section is-last">
      <style>
        /* .section .container {
          padding-top: 0;
        } */

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
      </style>
      <div class="container">
        <h2 class="secondary-title">인재공고</h2>
        <ol class="result-list result-list-card" id="ajaxList" data-page="1">
        </ol>
        <div id="ajaxEmpty" class="is-empty_v2" style="display:none;background:#F4F4F4;">
          <p>등록된 정보가 없습니다.</p>
        </div>
        <!-- 무한 스크롤 로딩 표시 -->
        <div id="scrollLoader" style="display:none; text-align:center; padding:20px;">
          <span style="color:#999; font-size:14px;">불러오는 중...</span>
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

<?php $tpl_id = 'tpl_list';
$tpl_recomm_class = true;
$tpl_show_mm_idx = true; ?>
<?php include(APPPATH . 'views/mobile/_partial/resume_card_template.php'); ?>

<template id="tpl_category">
  <label class="form-toggle-btn2 sm {{active}} btn-category">
    <input type="checkbox" name="filter" value="{{category_name}}">
    <span class="cate">{{category_name}}</span>
  </label>
</template>


<input type="hidden" name="resume_yn" value="<?= $resume == null ? "N" : "Y" ?>" />
<input type="hidden" name="mm_type" value="<?= $this->user->getUserType() ?>" />

<script>
  // 현재 로그인한 사용자 정보
  var currentUserMmIdx = <?php
                          $mm_idx = $this->user->isLogin() ? $this->user->getUserIdx() : null;
                          echo json_encode($mm_idx ? (int)$mm_idx : null);
                          ?>;
  var currentUserType = <?php echo json_encode(isset($mm_type) ? $mm_type : ""); ?>;
</script>

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
  var ad_addr = <?php echo json_encode(isset($addr_info["ad_addr"]) ? $addr_info["ad_addr"] : ""); ?>;
  var list_id = "#ajaxList";
  var list_empty_id = "#ajaxEmpty";
  var list_page = 1;
  var list_loading = false;
  var scroll_loading = false; // 스크롤 로딩 중 여부
  var search_addr = [];
  var searchAreaStr = "";
  var addrName = "";
  var has_more = true; // 더 불러올 데이터가 있는지 여부
  var ITEMS_PER_PAGE = 20; // 한 번에 로드할 아이템 수
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
    df: "카테고리",
    text: "",
    active: ""
  });
  tab_list.push({
    df: "근무형태",
    text: "",
    active: ""
  });
  tab_list.push({
    df: "근무요일",
    text: "",
    active: ""
  });
  tab_list.push({
    df: "급여",
    text: "",
    active: ""
  });
  tab_list.push({
    df: "성별",
    text: "",
    active: ""
  });
  tab_list.push({
    df: "나이",
    text: "",
    active: ""
  });

  $(document).on("click", ".btn-category", function() {
    $("#advancedSearch").addClass("on");
  });

  $(document).on("click", ".form-checkbox", function(e) {
    e.stopPropagation();
  });

  function initCashingData() {
    showEmpty();
  }

  function initLoadData() {
    list_page = 1;
    has_more = true;
    getBoardList("");
  }

  function getBoardList(save_yn) {
    // 이미 로딩 중이거나 더 이상 데이터가 없으면 리턴
    if (scroll_loading || !has_more) return;

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
      sage: search_array.sage,
      eage: search_array.eage,
    };

    if (save_yn === "Y") {
      $.ajax({
        url: "/resume_proc/search_save_params2025",
        type: "post",
        data: params,
        traditional: false,
        dataType: "json",
        crossDomain: true,
        success: function(ret) {
          if (ret.code === "00") {
            // console.log('설정저장');
          }
        },
        error: function(xhr, status, error) {
          console.error("데이터 저장 오류:", error);
        }
      });
    }

    scroll_loading = true;
    $.ajax({
      url: "/resume_proc/search_list2025",
      type: "post",
      data: params,
      traditional: false,
      dataType: "json",
      crossDomain: true,
      success: function(ret) {
        scroll_loading = false;

        if (ret.code === "00" && ret.data && Array.isArray(ret.data.list)) {
          // 첫 로드 시에만 목록 초기화
          if (list_page === 1) {
            $(list_id).empty();
          }

          var list = ret.data.list;
          // 받은 데이터가 ITEMS_PER_PAGE보다 적으면 마지막 페이지
          if (list.length < ITEMS_PER_PAGE) {
            has_more = false;
          }

          // 목록에 새 데이터 append
          showBoardList(list);
          list_page++;
          showLoader(false);
        } else {
          console.error('API 응답 에러:', ret);
          showLoader(false);
        }
      },
      error: function(xhr, status, error) {
        scroll_loading = false;
        console.error("데이터 가져오기 오류:", error);
        showLoader(false);
      }
    });
  }

  //데이터 보여주기
  function showBoardList(list) {

    list.forEach(function(row, index) {
      var $clone = $("#tpl_list").clone();
      var $content = $clone.html();

      // $content가 null이거나 undefined인 경우 처리
      if (!$content || typeof $content !== 'string') {

        return;
      }

      if (row.pm_idx == null) {
        $content = $content.replaceAll("<div class=\"content-li recomm\"", "<div class=\"content-li\"");
        $content = $content.replaceAll("<p class=\"recomm-badge\"><span>추천</span></p>", "");
      }

      $content = $content.replaceAll("{{ru_idx}}", row.ru_idx || '');
      $content = $content.replaceAll("{{mm_idx}}", row.mm_idx || "");
      $content = $content.replaceAll("{{book_yn}}", row.book_yn == "Y" ? "on" : "");
      // 이름이 18자 이상이면 ...으로 자르기
      var mm_name = row.ru_name || '';
      if (mm_name && mm_name.length > 18) {
        mm_name = mm_name.substring(0, 18) + '...';
      }
      $content = $content.replaceAll("{{mm_name}}", mm_name);
      $content = $content.replaceAll("{{ru_week}}", row.ru_week || '');
      $content = $content.replaceAll("{{distance}}", (row.distance || 0) + "km");
      $content = $content.replaceAll("{{ru_work_img}}", row.ru_work_img || '');

      if (!row.mm_nation || (typeof row.mm_nation === 'string' && row.mm_nation.trim() == '')) {
        $content = $content.replaceAll("{{mm_nation}}", "");
        $content = $content.replaceAll("class=\"national\" style=\"display: inline-block;\"", "class=\"national\" style=\"display: none;\"");
      } else {
        $content = $content.replaceAll("{{mm_nation}}", row.mm_nation);
      }

      if (row.mm_age == '' || row.mm_age == '0') {
        $content = $content.replaceAll("{{ma_age}}", "");
        $content = $content.replaceAll("class=\"age\" style=\"display: inline-block;\"", "class=\"age\" style=\"display: none;\"");
      } else {
        $content = $content.replaceAll("{{ma_age}}", (row.mm_age || '') + "대");
      }

      $content = $content.replaceAll("{{ru_price_type}}", getPriceType(row.ru_price_type));
      $content = $content.replaceAll("{{ru_work_type}}", getWorkType(row.ru_work_type));
      $content = $content.replaceAll("{{ru_resume_img}}", row.ru_resume_img || '');
      $content = $content.replaceAll("{{ad_addr}}", row.ad_addr ? fRemoveBigAdrJS(row.ad_addr) : '');

      $clone.html($content);

      $(list_id).append($clone.html());

      // 3번째 아이템(index=2) 이후 광고 삽입
      if (index === 2) {
        insertAdsenseAd();
      }
    });

    showEmpty();
  }

  // Google AdSense 인피드 광고 삽입 함수
  function insertAdsenseAd() {
    var adHtml = '<li class="list-item ad-item-adsense" style="position: relative;">' +
      '<div class="adsense-wrapper" style="position: relative;">' +
      '<ins class="adsbygoogle" style="display:block"' +
      ' data-ad-format="fluid"' +
      ' data-ad-layout-key="-h2+u-2a-lm+1en"' +
      ' data-ad-client="ca-pub-7573726661605583"' +
      ' data-ad-slot="9872127954"></ins>' +
      '</div>' +
      '</li>';
    $(list_id).append(adHtml);

    // AdSense 광고 로드 (공식 코드)
    setTimeout(function() {
      try {
        (adsbygoogle = window.adsbygoogle || []).push({});
      } catch(e) {
        console.error('AdSense 광고 로드 실패:', e);
      }
    }, 100);
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
    const matches = work_type.match(/<span>(.*?)<\/span>/g);

    if (!matches) {
      return "";
    }
    return matches.map(item => {
      const text = item.replace(/<\/?span>/g, '').trim();
      let cls = '';
      if (text === '주간') {
        cls = 'wtype1';
      } else if (text === '야간') {
        cls = 'wtype2';
      } else if (text === '2교대') {
        cls = 'wtype3';
      } else if (text === '근무협의' || text === '시간협의') {
        // 근무협의는 근무형태가 3개 이상 선택된 경우 (시간협의도 호환성을 위해 처리)
        cls = 'wtype0';
        // 시간협의가 오면 근무협의로 변환
        if (text === '시간협의') {
          text = '근무협의';
        }
      }
      return `<em class="${cls}">${text}</em>`;
    }).join('');
  }

  $(document).on('click', '.content-li', function(e) {
    // 버튼이나 링크 클릭은 무시
    if ($(e.target).closest('button, a, input').length) {
      return;
    }
    // Google AdSense 광고 클릭이면 무시 (WebView의 shouldOverrideUrlLoading이 처리)
    if ($(e.target).closest('.ad-item-adsense').length) {
      return;
    }

    var $target = $(this);
    var ruIdx = $target.data('ru_idx');
    var resumeMmIdx = $target.data('mm_idx'); // 이력서 소유자 인덱스

    // 개인회원이고 자신의 이력서인 경우 클릭 가능
    if (currentUserType === "MEM" && currentUserMmIdx && resumeMmIdx && currentUserMmIdx == resumeMmIdx) {
      if (ruIdx) {
        var url = '/resume/hr_post/' + ruIdx;
        window.location.href = url;
      }
      return;
    }

    // 기업회원인 경우 클릭 가능
    <?php if ($mm_type === "COMP") { ?>
      if (ruIdx) {
        var url = '/resume/hr_post/' + ruIdx;
        window.location.href = url;
      }
    <?php } else { ?>
      // 개인회원이지만 자신의 이력서가 아닌 경우
      commonAlert("기업회원 전용 서비스입니다.", function() {});
    <?php } ?>
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

  // 스크롤 로더 표시/숨김
  function showLoader(show) {
    document.getElementById('scrollLoader').style.display = show ? 'block' : 'none';
  }

  // 무한 스크롤 이벤트
  window.addEventListener('scroll', function() {
    if (scroll_loading || !has_more) return;

    var scrollBottom = window.scrollY + window.innerHeight;
    var docHeight = document.documentElement.scrollHeight;

    // 하단 200px 이내에 도달하면 다음 배치 로드
    if (scrollBottom + 200 >= docHeight) {
      showLoader(true);
      getBoardList("");
    }
  }, {
    passive: true
  });

  // 채용공고 작성 버튼 클릭 이벤트 20250923
  // 이벤트 위임 사용 (main_menu.php include 이후에도 동작 보장)
  $(document).off('click', '#btnRecruitList').on('click', '#btnRecruitList', function(e) {
    e.preventDefault();

    // 공통 함수 사용 (main_menu.php에 정의된 전역 함수)
    if (typeof window.handleRecruitPageNavigation === 'function') {
      window.handleRecruitPageNavigation();
    } else {
      // 함수가 아직 로드되지 않은 경우 (안전장치)
      console.error('handleRecruitPageNavigation 함수를 찾을 수 없습니다.');
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
          console.error("채용공고 개수 확인 오류:", error);
          commonAlert("채용공고 개수를 확인할 수 없습니다.", function() {});
        }
      });
    }
  });
</script>
<?php include_once(get_base_url() . '/application/shop/views/mobile/_layout/addr_popup2025_new.php'); ?>
<?php include_once(get_base_url() . '/application/shop/views/mobile/resume/index_search2025_new.php'); ?>
<script>
  // DOMContentLoaded는 한 번만 실행되도록 플래그 설정
  var domContentLoadedExecuted = false;
  window.addEventListener('DOMContentLoaded', function() {
    // 이미 실행되었다면 중복 실행 방지
    if (domContentLoadedExecuted) {
      return;
    }
    domContentLoadedExecuted = true;

    // 스크롤 차단 방지: 초기 진입 시 html/body overflow 해제
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

    initCategory();
    initTabUi();

    var params = {
      cashing_id: "resume_cashing",
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

    // 지금 뜨는 업체 슬라이더 초기화
    var hotSlider = document.querySelector('.hot-list .slider-wrap');
    if (hotSlider && typeof KeenSlider !== 'undefined') {
      if (!hotSlider.__keenslider__) {
        var slides = hotSlider.querySelectorAll('.keen-slider__slide');
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
        new KeenSlider(hotSlider, {
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
      }
    }

    // 네이티브 광고 제어는 main.js에서 자동으로 초기화되므로 중복 호출 제거
  });

  // 스와이프 뒤로가기 비활성화 (이 페이지에서는 스와이프로 뒤로가기 불가)
  window.DISABLE_SWIPE_BACK = true;
</script>