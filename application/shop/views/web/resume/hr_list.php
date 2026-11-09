<?php
$banner_list = getBannerList("BN00302041");
$banner = null;
if (count($banner_list) > 0) {
  $banner = $banner_list[0];
}
?>
<script src="<?= RES_PATH ?>/js/ad-banner.js"></script>
<?= getCachCss(RES_PATH . "/css/search_v2.css") ?>
<style>
.page-hr-list .item-info .recomm-badge {
  padding: 0 6px;
  font-size: 13px;
  font-weight: 500;
}

.search-result{
  width: auto;
}
  .search-result .content-area:first-child {
    margin-left: -65px;
  }
  .search-result .content-area {
    margin: 0 10px;
    -webkit-box-flex: 0;
    -ms-flex: 0 0 1530px;
    flex: 0 0 1530px;
  }

  .search-result .sidebar {
    width: 200px;
    height: 880px;
    -webkit-box-flex: 0;
    -ms-flex: 0 0 200px;
    flex: 0 0 200px;
    position: sticky;
    top: 90px;
    padding-left: 35px;

  }

  #page_cont {
    margin: 20px 0;
  }

  #page_cont .pagination {
    margin: 50px 0 0;
  }

  .result-header .total.today-new {
    margin-left: 28px;
  }

  .arrow--disabled {
    display: none;
  }
  .recomm-slider.person .container.swiperX {
  padding: 0 52px;
  max-width: 1634px;
}
.recomm-slider.person {
  padding-bottom: 90px;
}
.recomm-slider.person .person-card {
  padding: 0 25px 0 0;
  min-height: 470px;
}
.recomm-slider.person .person-card .img {
  width: 286px;
  height: 286px;
}
.recomm-slider .person-card .tag {
  padding: 0;
}
.recomm-slider .person-card .tag:not(:last-child) {
  margin-right: 13px;
}
  /* 첫 진입 시 AJAX 응답 전/결과 없음/실패 안내 문구 (빈 화면 대신 상태를 보여준다) */
  .page-hr-list .result-list .list-state {
    list-style: none;
    padding: 80px 0;
    text-align: center;
    color: #999;
    font-size: 15px;
  }
  .recomm-slider.person .slider-state {
    min-height: 470px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
    font-size: 15px;
  }
.page-hr-list .item-info .price_type_wrap {
  width: 240px !important;
  flex: 0 0 240px;
  margin-right: 9px;
}
.page-hr-list .item-info .working-day {
  width: 291px;
  flex: 0 0 291px;
  margin-right: 0;
  justify-content: flex-end;
}
.arrow.arrow--left {
  left: -25px;
}

.recomm-slider.person .arrow.arrow--right {
    left: auto;
    right:  -17px;
}
</style>
<div id="content" class="page page-person page-hr-list">
  <main class="page-content">

    <!-- <div class="visual-banner person">
      <div class="container">
        <div class="visual-content">
          <p>새로 등록된 추천 인재</p>
          <p class="headline">지금 꼭, 필요한 인재</p>
          <a href="javascript:void(0);" id="btnRegist" class="btn-round">채용공고 등록</a>
        </div>
      </div>
    </div> -->

    <div class="recomm-slider person">
      <div class="container swiperX">
        <h2 class="primary-title">추천인재</h2>
        <div class="slider-wrap keen-slider" id="my-keen-slider">
          <?php if (!empty($pc_ssr)) : ?>
            <?php $card_list = isset($initial_reco) ? $initial_reco : array(); include APPPATH . 'views/web/_partial/resume_reco_card.php'; ?>
          <?php else : ?>
            <div class="slider-state">추천 인재를 불러오는 중입니다.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="section">
      <div class="container">
        <div class="search-result">

          <div class="content-area">
           
            <?php
            $search_wrap_title = '인재정보';
            $search_wrap_class = 'search-v2';
            $search_addr_placeholder = '지역명 검색 예) 서울, 서초구';
            $search_hide_options = array('category:기타', 'price_type:시급');
            include APPPATH . 'views/web/_layout/search_recruit.php';
            ?>

            <div class="result-wrap">

              <div class="result-header">
                <p class="total">인재정보 <em id="total_count"><?= !empty($pc_ssr) ? (int) $initial_total_count : '…' ?></em>건</p>
                <p class="total today-new">신규 인재정보 <em><?= number_format(isset($today_resume_active_count) ? $today_resume_active_count : 0) ?></em>건</p>
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

              <!-- 목록 -->
              <ol class="result-list" id="ajaxList" data-page="1" style="min-height:400px;">
                <?php if (!empty($pc_ssr)) : ?>
                  <?php $card_list = isset($initial_resumes) ? $initial_resumes : array(); include APPPATH . 'views/web/_partial/resume_list_card.php'; ?>
                  <?php if (empty($card_list)) : ?><li class="list-state">검색 결과가 없습니다.</li><?php endif; ?>
                <?php else : ?>
                  <li class="list-state">인재정보를 불러오는 중입니다.</li>
                <?php endif; ?>
              </ol>
              <!-- //목록 -->

              <div id="page_cont">
                <?php if (!empty($pc_ssr) && !empty($initial_pagenation)) : ?>
                  <?= str_replace(array('<li>', 'page-next'), array('', 'page-next btn'), $initial_pagenation) ?>
                <?php endif; ?>
              </div>

              <div class="list-action v2">
                <a href="/resume/hr_map" title="지도에서 찾기" class="btn-map"><span class="sr-text">지도보기</span></a>
              </div>

              <script>
                /* 목록이 3개이하인경우 리스트액션 고정 */
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
              <a id="adBannerLink" href="" target="_blank">
                <img id="adBannerImg" src="" alt="" width="100%" height="100%" border="0" style="padding:0px; margin:0px;">
              </a>
            </div>
          </div>
        </div><!-- //search-result -->
      </div><!-- //container -->
    </div>
  </main><!-- //page-content -->
</div><!-- //page -->

<!-- 인재 목록 템플릿 (공유) -->
<?php
$tpl_id               = 'tpl_resume_card';
$tpl_show_timestamp   = true;
$tpl_show_mm_idx      = true;
$tpl_price_wrap_width = '290px';
$tpl_show_recomm_badge = true;
include APPPATH . 'views/web/_layout/resume_list_template.php';
?>

<!-- 추천 리스트 템플릿 -->
<?php
$reco_show_bookmark = true;
$reco_show_mm_idx   = true;
include APPPATH . 'views/web/_layout/reco_list_template.php';
?>

<input type="hidden" name="mm_type" value="<?= $this->user->getUserType() ?>" />

<script>
  var appBaseUrl = "<?php echo rtrim($this->config->item('app_base_url') ? $this->config->item('app_base_url') : base_url(), '/'); ?>";

  // 현재 로그인한 사용자 정보
  var currentUserMmIdx = <?php echo $this->user->isLogin() ? $this->user->getUserIdx() : 'null'; ?>;
  var currentUserType = '<?php echo isset($mm_type) ? $mm_type : ""; ?>';

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
    sage: "",
    eage: "",
    order_by: "",
  };

  $(document).ready(function() {
    $('.btn-map').insertBefore('#bottomchatbtn');
    loadRandomAdBanner('hr');
    if (INITIAL_SSR) {
      // 서버가 그린 추천인재 슬라이더 초기화 (추천이 없으면 건너뜀)
      if ($('#my-keen-slider .keen-slider__slide').length > 0) {
        initRecommendationSlider('.recomm-slider.person', 5, 311);
      }
    } else {
      getRecoList();
    }
    restoreSearchFromUrl();
  });

  // 값이 없을 때 trim에서 에러가 나지 않도록 안전하게 처리하는 헬퍼
  function safeTrim(value) {
    if (value === null || value === undefined) {
      return "";
    }
    return String(value).trim();
  }

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
    search_array.order_by = $("[name='order_by']:checked").val();
  }

  function getBoardList(is_top) {
    if (search_restoring) return;
    setTimeout(function() {
      getBoardListDelay(is_top);
    }, 500);
  }

  function getBoardListDelay(is_top) {
    setBoardParams();
    updateSearchUrl();
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
      sage: search_array.sage,
      eage: search_array.eage,
      order_by: search_array.order_by,
      render: "html", // 카드 HTML을 서버 파셜로 받는다 (첫 화면과 같은 마크업)
    };
    $.ajax({
      url: "/resume_proc/search_list2025",
      type: "post",
      data: params,
      traditional: false,
      dataType: "json",
      crossDomain: true,
      success: function(ret) {
        if (ret.code == "00") {
          $(list_id).attr("data-page", list_page);
          $("#total_count").html(ret.data.count);
          if (ret.data.pagenation && ret.data.pagenation.trim() !== '') {
            $("#page_cont").html(ret.data.pagenation.replace(/<li>/g, '').replace(/page-next/g, 'page-next btn'));
          } else {
            $("#page_cont").html('');
          }
          showBoardList(ret.data.list_html, is_top);
        }
      },
      error: function(xhr, status, error) {
        console.error("데이터 가져오기 오류:", error);
        // 실패 시 로딩 문구가 계속 남지 않도록 안내 문구로 바꾼다
        $("#total_count").text("-");
        $(list_id).html('<li class="list-state">목록을 불러오지 못했습니다. 잠시 후 다시 시도해 주세요.</li>');
      }
    });
  }

  // 서버가 카드 파셜(web/_partial/resume_list_card.php)로 그린 HTML을 그대로 넣는다 (JS 템플릿 치환 없음)
  function showBoardList(html, is_top) {
    $(list_id).html($.trim(html) ? html : '<li class="list-state">검색 결과가 없습니다.</li>');

    if (is_top == true) {
      var ajax_top = $(".search-result").position().top - 30;
      $('html, body').animate({
        scrollTop: ajax_top
      }, 400);
    }
  }

  function getRecoList() {
    getRecoListCommon({
      containerId: 'my-keen-slider',
      limit: 10,
      showMmIdx: true,
      perView: 5,
      slideWidth: 311
    });
  }


  // 나이 계산: mm_birth는 출생연도 4자리(YYYY)로 저장된다. 과거 YYYYMMDD 값도 앞 4자리로 계산한다
  function getKoreanAge(birth) {
    if (!birth) {
      return 0;
    }

    let year = parseInt(String(birth).slice(0, 4), 10);
    if (isNaN(year)) {
      return 0;
    }

    return new Date().getFullYear() - year;
  }
</script>

<script>
  $("#btnRegist").click(function() {
    if (typeof requireCompCertWithGuide === 'function' && !requireCompCertWithGuide()) {
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
            location.href = appBaseUrl + "/compage/recruit_list";
          } else {
            location.href = appBaseUrl + "/compage/recruit_write";
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
  });

  $(document).on('click', '.desc', function() {
    var $target = $(this).closest('.list-item,.person-card');
    var ruIdx = $target.data('ru_idx');

    if (ruIdx) {
      var url = '/resume/hr_post/' + ruIdx;
      window.open(url, '_blank');
    }
  });
</script>