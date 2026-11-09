<?php
$banner_list = getBannerList("BN00010001");
$banner = null;
if (count($banner_list) > 0) {
  $banner = $banner_list[0];
}
?>
<?= getCachCss(RES_PATH . "/css/recruit_list.css") ?>
<?= getCachCss(RES_PATH . "/css/recruit_card.css") ?>
<?= getCachCss(RES_PATH . "/css/search_v2.css") ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<?= getCashJs(RES_PATH . "/js/ad-banner.js") ?>
<style>
  .search-result .content-area:first-child {
    margin-left: -65px;
}
  .search-result .content-area {
    -webkit-box-flex: 0;
    -ms-flex: 0 0 1530px;
    flex: 0 0 1530px;
  }
  .result-header .total.today-new {
    margin-left: 28px;
  }
  #page_cont {
    margin: 20px 0;
  }
  #page_cont .pagination {
    margin: 50px 0 0;
  }
.recomm-slider.recruit .container.swiperX {
  padding: 0 52px;
  max-width: 1634px;
}
.recomm-slider.recruit .recruit-card {
  width: calc(100% - 25px);
  margin: 0 25px 0 0;
  min-height: 470px;
}
.recomm-slider.recruit .recruit-card .thumb {
  padding: 100% 0 0;
}
.recomm-slider .recruit-card .tag {
  padding: 0;
}
.recomm-slider .recruit-card .tag:not(:last-child) {
  margin-right: 13px;
}

.page-recruit .result-list .recomm-badge {
  padding: 0 6px;
  font-size: 13px;
  font-weight: 500;
}
.page-recruit .result-list .content-info .content-body {
  gap: 20px;
}


.page-recruit .result-list .content-info .desc .desc1 {
  flex: 1 1 auto;
  width: auto;
  min-width: 0;
}
.page-recruit .result-list .content-info .desc .desc2 {
  flex: 0 0 240px;
  width: 240px;
}
.page-recruit .result-list .content-info .desc .desc3 {
  flex: 0 0 290px;
  width: 290px;
}
.arrow.arrow--left {
  left: -25px;
}
.recomm-slider .arrow.arrow--right {
    left: auto;
    right:  -17px;
}
  /* 첫 진입 시 AJAX 응답 전/결과 없음/실패 안내 문구 (빈 화면 대신 상태를 보여준다) */
  .page-recruit .result-list .list-state {
    list-style: none;
    padding: 80px 0;
    text-align: center;
    color: #999;
    font-size: 15px;
  }
  .recomm-slider.recruit .slider-state {
    min-height: 470px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
    font-size: 15px;
  }
</style>
<div id="content" class="page page-recruit">
  <main class="page-content">
    <!-- <div class="visual-banner recruit">
      <div class="container">
        <div class="visual-content">
          <p>새로 등록된 추천 공고</p>
          <p class="headline">지금 뜨는, 좋은 일자리</p>
          <?php if ($mm_type === "MEM") { ?> -->
    <!-- 일반회원만 노출: 기업회원은 이력서 등록 버튼 숨김 -->
    <!-- <a href="javascript:void(0);" id="btnRegist" class="btn-round">이력서 등록</a> -->
  <?php } ?>
  <!-- </div>
      </div>
    </div> -->
  <div class="recomm-slider recruit">
    <div class="container swiperX">
      <h2 class="primary-title">추천공고</h2>
      <div class="slider-wrap keen-slider swiper-wrapperX" id="my-keen-slider" style="width: calc(100% - 0px);">
        <?php if (!empty($pc_ssr)) : ?>
          <?php $card_list = isset($pc_reco_list) ? $pc_reco_list : array(); include APPPATH . 'views/web/_partial/recruit_reco_card.php'; ?>
        <?php else : ?>
          <div class="slider-state">추천 공고를 불러오는 중입니다.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="section">
    <div class="container">
      <div class="search-result">
        <div class="content-area">
          <!-- get_base_url(): /website/dev_nowapk-->
          <?php
          $search_wrap_class = 'search-v2';
          $search_addr_placeholder = '지역명 검색 예) 서울, 서초구';
          $search_show_nationality = true;
          include_once(get_base_url() . '/application/shop/views/web/_layout/search_recruit.php');
          ?>
          <div class="result-wrap">
            <div class="result-header">
              <p class="total">채용정보 <em id="total_count"><?= !empty($pc_ssr) ? (int) $initial_total_count : '…' ?></em>건</p>
              <p class="total today-new">신규 채용공고 <em><?= number_format(isset($today_recruit_count) ? $today_recruit_count : 0) ?></em>건</p>
              <div class="orderby">
                <label class="option">
                  <input type="radio" name="order_by" value="recent" checked>
                  <span class="label">최신순</span>
                </label>
                <label class="option">
                  <input type="radio" name="order_by" value="fav">
                  <span class="label">인기순</span>
                </label>
              </div>
            </div>
            <ol class="result-list" id="ajaxList" data-page="1" style="min-height:400px;">
              <?php if (!empty($pc_ssr)) : ?>
                <?php $card_list = isset($initial_recruits['list']) ? $initial_recruits['list'] : array(); include APPPATH . 'views/web/_partial/recruit_list_card.php'; ?>
                <?php if (empty($card_list)) : ?><li class="list-state">검색 결과가 없습니다.</li><?php endif; ?>
              <?php else : ?>
                <li class="list-state">채용공고를 불러오는 중입니다.</li>
              <?php endif; ?>
            </ol>
            <div id="page_cont">
              <?php if (!empty($pc_ssr) && !empty($initial_recruits['pagenation'])) : ?>
                <?= str_replace(array('<li>', 'page-next'), array('', 'page-next btn'), $initial_recruits['pagenation']) ?>
              <?php endif; ?>
            </div>
            <div class="list-action v2">
              <a href="/recruit/job_map" title="지도에서 찾기" class="btn-map"><span class="sr-text">지도보기</span></a>
            </div>
            <script>
              window.addEventListener('scroll', function() {
                var minY = document.querySelector('.result-wrap').getBoundingClientRect().top - (window.innerHeight / 2);
                var stickyElm = document.querySelector('.result-wrap .list-action');
                if (document.querySelector('.result-list').children.length < 3) return;
                if (minY <= document.body.scrollTop) {
                  stickyElm.style.position = 'sticky';
                } else {
                  stickyElm.style.position = '';
                }
              });
            </script>
          </div><!-- //result-wrap -->
        </div><!-- //content-area -->
        <div class="sidebar">
          <div class="adBanner adBanner2">
            <img src="/outer/ad/ad1.jpg" alt="" width="100%" height="100%" border="0" style="padding:0px; margin:0px;">
          </div>
          <div class="adBanner adBanner1">
            <a id="adBannerLink" href="https://naver.me/FDn3Ggxk" target="_blank">
              <img id="adBannerImg" src="/outer/ad/nowjob-banner-vertical_v5-2.png" alt="" width="100%" height="100%" border="0" style="padding:0px; margin:0px;">
            </a>
          </div>
        </div>
      </div><!-- //search-result -->
    </div><!-- //container -->
  </div>
  </main><!-- //page-content -->
</div><!-- //page -->

<input type="hidden" name="mm_type" value="<?= $this->user->getUserType() ?>" />
<script>
  var list_id = "#ajaxList";
  var list_empty_id = "#ajaxEmpty";
  var list_page = 1;
  var list_loading = false;
  // 첫 화면을 서버가 그렸는지 (true면 첫 목록·추천 AJAX를 건너뜀)
  var INITIAL_SSR = <?= !empty($pc_ssr) ? 'true' : 'false' ?>;
  var search_addr = [];
  var searchAreaStr = "";
  var addrName = "";
  var search_array = {
    category: "",
    work_type: "",
    week: "",
    price_type: "",
    sex: "",
    nationality: "",
    sage: "",
    eage: "",
    order_by: "",
  };

  $("[name='order_by']").click(function() {
    initPage(true);
  });

  function initPage(is_top) {
    list_page = 1;
    getBoardList(is_top);
  }

  function goPage(page) {
    list_page = page;
    getBoardList(true);
  }

  function getCheckedValues(name) {
    var result = "";
    $("[name='" + name + "']:checked").each(function() {
      var val = $(this).val();
      if (val !== "") {
        result += (result === "" ? "" : "^") + val;
      }
    });
    return result;
  }

  function setBoardParams() {

    var arr_category = getCheckedValues("category");
    var arr_work_type = getCheckedValues("work_type");
    var arr_price_type = getCheckedValues("price_type");
    var arr_week = getCheckedValues("week");
    var arr_sex = getCheckedValues("sex");
    var arr_nationality = getCheckedValues("nationality");

    search_array.sage = "";
    search_array.eage = "";

    var checkedAges = $("[name='eage']:checked").map(function() {
      return parseInt($(this).val().replace("대", ""), 10);
    }).get();

    if (checkedAges.length > 0) {
      var minAge = Math.min(...checkedAges);
      var maxAge = Math.max(...checkedAges);
      search_array.sage = minAge;
      search_array.eage = maxAge + 9;
    }

    search_array.category = arr_category;
    search_array.work_type = arr_work_type;
    search_array.week = arr_week;
    search_array.price_type = arr_price_type;
    search_array.sex = arr_sex;
    search_array.nationality = arr_nationality;
    search_array.order_by = $("[name='order_by']:checked").val();
  }

  function getBoardList(is_top) {
    if (search_restoring) return;
    getBoardListDelay(is_top);
  }

  function getBoardListDelay(is_top) {
    setBoardParams();
    updateSearchUrl();

    console.log('🔎 [PC job_list] getBoardList 호출:', {
      search_addr: search_addr,
      addrName: addrName,
      searchAreaStr: searchAreaStr
    });

    var params = {
      page: list_page,
      pagesize: 30,
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
      order_by: search_array.order_by,
      render: "html", // 카드 HTML을 서버 파셜로 받는다 (첫 화면과 같은 마크업)
    };
    $.ajax({
      url: "/recruit_proc/getRecruitList",
      type: "post",
      data: params,
      traditional: false,
      dataType: "json",
      crossDomain: true,
      success: function(ret) {
        if (ret.code == "00") {
          console.log('✅ [PC job_list] API 응답 성공:', {
            total: ret.data.count,
            listCount: ret.data.list ? ret.data.list.length : 0,
            returnedData: ret.data
          });

          $(list_id).attr("data-page", list_page);
          $("#total_count").html(ret.data.count);
          if (ret.data.pagenation && ret.data.pagenation.trim() !== '') {
            $("#page_cont").html(ret.data.pagenation.replace(/<li>/g, '').replace(/page-next/g, 'page-next btn'));
          } else {
            $("#page_cont").html('');
          }
          showBoardList(ret.data.list_html, is_top);
        } else {
          console.error('❌ [PC job_list] API 응답 실패:', ret);
          $("#total_count").text("-");
          $(list_id).html('<li class="list-state">목록을 불러오지 못했습니다. 잠시 후 다시 시도해 주세요.</li>');
        }
      },
      error: function(xhr, status, error) {
        console.error('❌ [PC job_list] API 요청 오류:', {
          status: status,
          error: error,
          xhr: xhr
        });
        // 실패 시 로딩 문구가 계속 남지 않도록 안내 문구로 바꾼다
        $("#total_count").text("-");
        $(list_id).html('<li class="list-state">목록을 불러오지 못했습니다. 잠시 후 다시 시도해 주세요.</li>');
      }
    });
  }

  // 서버가 카드 파셜(web/_partial/recruit_list_card.php)로 그린 HTML을 그대로 넣는다 (JS 템플릿 치환 없음)
  function showBoardList(html, is_top) {
    $(list_id).html($.trim(html) ? html : '<li class="list-state">검색 결과가 없습니다.</li>');

    if (is_top == true) {
      var ajax_top = $(".search-result").position().top - 50;
      $('html, body').animate({
        scrollTop: ajax_top
      }, 400);
    }
  }

  function getRecoList() {
    var params = {};
    params["addr1"] = $("[name='searchArea']:checked").val() == "전국" ? "" : $("[name='searchArea']:checked").val();
    params["addr2"] = $("[name='searchArea2']:checked").val();
    params["addr3"] = $("[name='searchArea3']:checked").val();
    params["limit"] = "24";
    params["render"] = "html"; // 추천 카드를 서버 파셜 HTML로 받는다

    comAjax("/recruit_proc/getRecruitRecoList", params, function(ret) {
      if (ret.code == "00") {
        // 서버가 그린 카드 HTML을 그대로 넣는다 (JS 템플릿 치환 없음)
        $("#my-keen-slider").html(ret.data.list_html);
        if ($("#my-keen-slider .keen-slider__slide").length > 0 && typeof initRecommendationSlider === 'function') {
          initRecommendationSlider('.recomm-slider.recruit', 5, 311);
        }
      } else {
        // 응답 코드가 실패면 추천공고 로딩 문구를 지운다
        $("#my-keen-slider").html("");
      }
    });
  }
</script>

<script>
  var appBaseUrl = "<?php echo rtrim($this->config->item('app_base_url') ? $this->config->item('app_base_url') : base_url(), '/'); ?>";

  $(document).ready(function() {
    $('.btn-map').insertBefore('#bottomchatbtn');
    loadRandomAdBanner('recruit');
    if (INITIAL_SSR) {
      // 서버가 그린 추천공고 슬라이더 초기화 (추천이 없으면 건너뜀)
      if ($('#my-keen-slider .keen-slider__slide').length > 0) {
        initRecommendationSlider('.recomm-slider.recruit', 5, 311);
      }
    } else {
      getRecoList();
    }
    restoreSearchFromUrl();

  });

  $("#btnRegist").click(function() {
    if ($("#is_login").val() != "Y") {
      commonAlert("로그인후 이용가능한 기능입니다.", function() {
        // 확인 버튼 클릭 시 팝업만 닫히도록 처리
      });
      return;
    }

    if ($("[name='mm_type']").val() != "MEM") {
      commonAlert("일반회원만 이력서 작성이 가능합니다.", function() {});
      return;
    }

    // 이력서 개수 확인 후 페이지 이동
    $.ajax({
      url: "/mypage_proc/getResumeCount",
      type: "post",
      dataType: "json",
      success: function(ret) {
        if (ret.code == "00") {
          var resumeCount = ret.data.count || 0;

          // 이력서가 1개 이상이면 관리 페이지로, 0개이면 등록 페이지로 이동
          if (resumeCount >= 1) {
            location.href = appBaseUrl + "/mypage/resume_list";
          } else {
            location.href = appBaseUrl + "/mypage/resume_write";
          }
        } else {
          commonAlert(ret.msg || "이력서 개수를 확인할 수 없습니다.", function() {});
        }
      },
      error: function(xhr, status, error) {
        console.error("이력서 개수 확인 오류:", error);
        commonAlert("이력서 개수를 확인할 수 없습니다.", function() {});
      }
    });
  });
  $(document).on('click', '.desc', function() {
    var rcIdx = $(this).closest('.list-item').data('rc_idx');
    if (rcIdx) {
      var url = '/recruit/job_post/' + rcIdx;
      window.open(url, '_blank');
    }
  });
</script>